<?php
/**
 * Dimension parser for the master_product_data → product_core migration.
 *
 * The legacy table packs a decimal and a human-readable form into one varchar,
 * and reuses the same column for unrelated data depending on tool_type. Examples
 * of every format found in the 31,275-row source:
 *
 *   0.2202            bare decimal, inches
 *   (.1885) 0.1885    parenthetical decimal + decimal text
 *   (3.500) 3-1/2     parenthetical decimal + mixed fraction
 *   (.1575) 4.0mm     parenthetical decimal (inches) + metric text
 *   (.0635) #52       parenthetical decimal + drill gauge letter/number
 *   1-5/8             bare mixed fraction
 *   3/32              bare simple fraction
 *   4.0mm             bare metric
 *   140               degrees (angle columns)
 *   M35 / ANSI        not a dimension at all -> residual_text
 *
 * Canonical storage unit is the ISO 13399 base unit: millimetres for lengths,
 * degrees for angles, dimensionless for counts.
 *
 * Exactness is decided with integer arithmetic on a rational (num/den), NOT with
 * floats. A value is exact when it survives 4-decimal storage losslessly, i.e.
 * when num * 10000 is divisible by den. This is what makes the `exact` flag
 * trustworthy enough to drive dimension_precision_exceptions: 1/64" = 0.396875 mm
 * is correctly reported inexact, while 3/16" = 4.7625 mm is reported exact.
 */

const DIM_UNIT_MM    = 'MM';
const DIM_UNIT_DEG   = 'DEG';
const DIM_UNIT_COUNT = 'COUNT';
const DIM_UNIT_RATIO = 'RATIO';

/** Inches -> millimetres as an exact rational multiplier: 254/10. */
const DIM_IN_TO_MM_NUM = 254;
const DIM_IN_TO_MM_DEN = 10;

/**
 * Float-comparison slack when testing whether a parenthetical is a valid rounding
 * of the value beside it. See dim_rounding_tolerance().
 */
const DIM_EPSILON = 0.0000001;

/**
 * Parse one raw legacy value.
 *
 * @param string|null $raw  Value straight from master_product_data.
 * @param string      $unit One of DIM_UNIT_*. Determines whether inch input is
 *                          converted (MM) or taken as-is (DEG, COUNT).
 * @return array{
 *   value:?float,          // magnitude in $unit, rounded to 4dp
 *   display:?string,       // human form only, decimal stripped
 *   exact:bool,            // false when 4dp storage rounded the true value
 *   exact_decimal:?string, // full-precision decimal string when inexact
 *   residual_text:?string, // text that is not a dimension (M35, ANSI, #52)
 *   corrupt_text:?string,  // text rejected as implausible (Excel serial dates)
 *   range_high:?float,     // upper bound when the value was written as "lo - hi"
 *   paren_delta_in:?float, // |parenthetical - resolved| in inches, when both present
 *   paren_mismatch:?array  // [paren_in, resolved_in] only when delta exceeds tolerance
 * }
 */
function parse_dimension($raw, $unit = DIM_UNIT_MM)
{
    $blank = [
        'value'          => null,
        'display'        => null,
        'exact'          => true,
        'exact_decimal'  => null,
        'residual_text'  => null,
        'corrupt_text'   => null,
        'range_high'     => null,
        'paren_delta_in' => null,
        'paren_mismatch' => null,
        'suspect_fraction' => false,
    ];

    if ($raw === null) {
        return $blank;
    }
    $s = trim((string) $raw);
    if ($s === '' || $s === '-') {
        return $blank;
    }

    // Split an optional leading "(decimal)" from the human-readable remainder.
    $paren     = null;
    $paren_dp  = 0;
    $text      = $s;
    if (preg_match('/^\(\s*(?P<dec>[0-9]*\.?[0-9]+)\s*\)\s*(?P<rest>.*)$/', $s, $m)) {
        $paren    = dim_rational_from_decimal($m['dec']);
        $paren_dp = dim_decimal_places($m['dec']);
        $text     = trim($m['rest']);
    }

    // The display form is the human part only; the decimal now lives in its own
    // column. With no parenthetical there is nothing to strip.
    $display = $text !== '' ? $text : null;

    // Ranges: "0.283 - 0.285" in the tap-drill columns (102 rows). The spaces
    // around the dash are what distinguish a range from a mixed fraction such as
    // "3-1/2", which never has them.
    $range_high = null;
    $resolved   = null;
    if ($text !== '' && preg_match('/^(?P<lo>[^-]+?)\s+-\s+(?P<hi>.+)$/', $text, $rm)) {
        $lo = dim_parse_magnitude(trim($rm['lo']), $unit);
        $hi = dim_parse_magnitude(trim($rm['hi']), $unit);
        if ($lo !== null && $hi !== null) {
            $resolved   = $lo;
            $range_high = round(dim_to_float($hi), 4);
        }
    }
    if ($resolved === null && $text !== '') {
        $resolved = dim_parse_magnitude($text, $unit);
    }

    // Reject magnitudes that cannot be real for this unit. This is what catches
    // Excel serial-date corruption: "(.125) 44569" is the fraction 1/8 that a
    // spreadsheet autoconverted to a date, and 44569" is ~1.1 km. Where a
    // parenthetical survives it still holds the true value, so the corrupt text is
    // discarded and the parenthetical is used instead.
    $corrupt = null;
    if ($resolved !== null && !dim_is_plausible($resolved, $unit)) {
        $corrupt  = $text;
        $resolved = null;
    }

    // Inch fractions are conventionally halves down to 64ths, so a denominator that
    // is not a power of two is not a real size: "1-3/10", "1-5/19", "1 1/78" and
    // their sequential neighbours look like a spreadsheet fill-down artifact. The
    // value still parses, so it would otherwise be stored silently wrong - flag it
    // instead and let the review decide.
    $suspect = false;
    if ($unit === DIM_UNIT_MM && $resolved !== null
        && preg_match('#/\s*(\d+)\s*$#', $text, $fm)) {
        $den = (int) $fm[1];
        $suspect = $den > 0 && ($den & ($den - 1)) !== 0;
    }

    $residual = null;
    if ($resolved === null && $text !== '') {
        // Text is not a dimension (M35, ANSI, DIN 40430, #52, "1/16 & 1/8") or was
        // rejected as implausible just above.
        $residual = $text;
    }

    // Prefer the human form: a fraction is an exact value, whereas the
    // parenthetical is a rounded rendering of it. Fall back to the parenthetical
    // when the text carries no parseable magnitude (e.g. "(.0635) #52").
    $rat = $resolved;
    if ($rat === null && $paren !== null) {
        $rat = dim_convert($paren, $unit);
    }

    if ($rat === null) {
        $blank['display']       = $display;
        $blank['residual_text'] = $residual;
        $blank['corrupt_text']  = $corrupt;
        return $blank;
    }

    // Compare the parenthetical against the resolved value, in inches.
    //
    // Most disagreements are simply the parenthetical being a rounded rendering of
    // the exact value beside it, at whatever precision the parenthetical was
    // written to: "(1.06) 1-1/16" is 1.0625 shown to 2dp, "(.1563) 5/32" is
    // .15625 shown to 4dp. Both are fine. A fixed absolute tolerance cannot tell
    // those apart from real errors, because a 2dp rendering is legitimately off by
    // up to 0.005" while a 4dp one should be within 0.00005".
    //
    // So the tolerance is derived from the parenthetical's own stated precision. A
    // value outside it is a genuine defect, e.g. "(.0404) 3/64" where 3/64 =
    // .046875 - the parenthetical is simply wrong.
    $delta_in = null;
    $mismatch = null;
    if ($paren !== null && $resolved !== null) {
        $resolved_in = $unit === DIM_UNIT_MM
            ? dim_to_float($rat) * DIM_IN_TO_MM_DEN / DIM_IN_TO_MM_NUM
            : dim_to_float($rat);
        $paren_in = dim_to_float($paren);
        $delta_in = round(abs($paren_in - $resolved_in), 8);
        if ($delta_in > dim_rounding_tolerance($paren_dp) + DIM_EPSILON) {
            $mismatch = [round($paren_in, 6), round($resolved_in, 6)];
        }
    }

    $exact = dim_is_exact_at_4dp($rat);

    return [
        'value'          => round(dim_to_float($rat), 4),
        'display'        => $corrupt !== null ? null : $display,
        'exact'          => $exact,
        'exact_decimal'  => $exact ? null : dim_to_decimal_string($rat, 10),
        'residual_text'  => $residual,
        'corrupt_text'   => $corrupt,
        'range_high'     => $range_high,
        'paren_delta_in' => $delta_in,
        'paren_mismatch' => $mismatch,
        'suspect_fraction' => $suspect,
    ];
}

/**
 * Sanity bounds per unit. Nothing in this catalogue is a metre long, turns more
 * than a full circle, or has a hundred flutes; a value beyond these is corruption
 * rather than data.
 */
function dim_is_plausible(array $rat, $unit)
{
    $v = abs(dim_to_float($rat));
    switch ($unit) {
        case DIM_UNIT_MM:
            return $v <= 2540.0;  // 100 inches
        case DIM_UNIT_DEG:
            return $v <= 360.0;
        case DIM_UNIT_COUNT:
        case DIM_UNIT_RATIO:
            return $v <= 100.0;
    }
    return true;
}

/**
 * Parse a human-readable magnitude into a rational in the canonical unit.
 * Returns null when the text is not a dimension.
 */
function dim_parse_magnitude($text, $unit)
{
    // Strip a trailing inch mark (1/8" -> 1/8) and a degree symbol (5° -> 5).
    // The degree sign appears in 12 `helix` rows; the value is otherwise fine.
    $t = trim(preg_replace(['/"\s*$/u', '/\s*\x{00B0}\s*$/u'], '', $text));
    if ($t === '') {
        return null;
    }

    // A ratio is written with a multiplier suffix: ldr = "3X" means 3x diameter.
    if ($unit === DIM_UNIT_RATIO) {
        $t = preg_replace('/\s*[xX]\s*$/', '', $t);
        if ($t === '') {
            return null;
        }
    }

    // Metric: "4.0mm", "52 mm"
    if (preg_match('/^([0-9]*\.?[0-9]+)\s*mm$/i', $t, $m)) {
        // Already millimetres - no conversion regardless of $unit.
        return dim_rational_from_decimal($m[1]);
    }

    // Mixed fraction: "3-1/2", "1 5/8"
    if (preg_match('/^(\d+)\s*[-\s]\s*(\d+)\s*\/\s*(\d+)$/', $t, $m)) {
        $den = (int) $m[3];
        if ($den === 0) {
            return null;
        }
        $num = (int) $m[1] * $den + (int) $m[2];
        return dim_convert([$num, $den], $unit);
    }

    // Simple fraction: "3/32"
    if (preg_match('/^(\d+)\s*\/\s*(\d+)$/', $t, $m)) {
        $den = (int) $m[2];
        if ($den === 0) {
            return null;
        }
        return dim_convert([(int) $m[1], $den], $unit);
    }

    // Plain decimal or integer: "0.2202", "140"
    if (preg_match('/^[0-9]*\.?[0-9]+$/', $t)) {
        return dim_convert(dim_rational_from_decimal($t), $unit);
    }

    return null;
}

/** Convert an inch-denominated rational to the canonical unit. */
function dim_convert(array $rat, $unit)
{
    if ($unit === DIM_UNIT_MM) {
        return dim_reduce([$rat[0] * DIM_IN_TO_MM_NUM, $rat[1] * DIM_IN_TO_MM_DEN]);
    }
    // Degrees and counts are stored as given.
    return $rat;
}

/** "0.1250" | ".1885" | "140" -> [numerator, denominator]. */
function dim_rational_from_decimal($s)
{
    $s = ltrim(trim($s), '+');
    if (strpos($s, '.') === false) {
        return [(int) $s, 1];
    }
    [$whole, $frac] = explode('.', $s, 2);
    $frac  = rtrim($frac, '0');
    $scale = strlen($frac);
    if ($scale === 0) {
        return [(int) $whole, 1];
    }
    $num = (int) (($whole === '' ? '0' : $whole) . $frac);
    return dim_reduce([$num, (int) pow(10, $scale)]);
}

/** Number of significant decimal places in a decimal string ("1.06" -> 2). */
function dim_decimal_places($s)
{
    $s = trim($s);
    $dot = strpos($s, '.');
    if ($dot === false) {
        return 0;
    }
    return strlen(rtrim(substr($s, $dot + 1), '0'));
}

/**
 * Largest legitimate error for a value rendered to $dp decimal places: half of
 * the last place. A parenthetical written to 0dp is uninformative, so treat it
 * generously rather than flagging every whole number as a conflict.
 */
function dim_rounding_tolerance($dp)
{
    if ($dp <= 0) {
        return 0.5;
    }
    return 0.5 * pow(10, -$dp);
}

function dim_reduce(array $rat)
{
    $g = dim_gcd(abs($rat[0]), abs($rat[1]));
    if ($g > 1) {
        return [intdiv($rat[0], $g), intdiv($rat[1], $g)];
    }
    return $rat;
}

function dim_gcd($a, $b)
{
    while ($b !== 0) {
        [$a, $b] = [$b, $a % $b];
    }
    return $a === 0 ? 1 : $a;
}

/**
 * True when the rational is exactly representable with 4 decimal places, i.e.
 * num * 10000 is divisible by den. Integer-only, so no float error.
 */
function dim_is_exact_at_4dp(array $rat)
{
    return ($rat[0] * 10000) % $rat[1] === 0;
}

function dim_to_float(array $rat)
{
    return $rat[1] == 0 ? 0.0 : $rat[0] / $rat[1];
}

/** Full-precision decimal string, for recording the exact value we rounded away. */
function dim_to_decimal_string(array $rat, $places)
{
    if (function_exists('bcdiv')) {
        return bcdiv((string) $rat[0], (string) $rat[1], $places);
    }
    return number_format($rat[0] / $rat[1], $places, '.', '');
}
