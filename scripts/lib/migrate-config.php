<?php
/**
 * Mapping tables and helpers for migrate_product_data.php.
 *
 * The legacy->new column mapping lives here as data rather than inline logic so
 * it can be diffed against the legend in the plan, and so attribute_dictionary
 * can be seeded from the same source of truth (see seed_attribute_dictionary.php).
 */

// ---------------------------------------------------------------------------
// Universal dimensions.
//
// `sources` is ordered by precedence. parse_dimension() already prefers an exact
// fraction inside a string over its parenthetical decimal, so precedence here
// only decides which *column* wins when more than one is populated.
//
// DC deliberately excludes `outer_dimension`: it is a 3dp rounding that also
// mixes millimetres with inches in one column, so it carries nothing the other
// three sources lack.
// ---------------------------------------------------------------------------
const MIG_CORE_DIMENSIONS = [
    'DC'  => ['unit' => DIM_UNIT_MM,    'display' => true,
              'sources' => ['cut_dia_in_display', 'cut_dia_m_display', 'diasize', 'diadec']],
    'DMM' => ['unit' => DIM_UNIT_MM,    'display' => true,
              'sources' => ['shank_dia_in_display', 'shank_dia_m_display', 'taps_shank']],
    'OAL' => ['unit' => DIM_UNIT_MM,    'display' => true,
              'sources' => ['oal_in_display', 'oal_m_display']],
    'LCF' => ['unit' => DIM_UNIT_MM,    'display' => true,
              'sources' => ['loc_in_display', 'loc_m_display', 'flutelength']],
    'RE'  => ['unit' => DIM_UNIT_MM,    'display' => true,
              'sources' => ['radius_in_display', 'radius_m_display']],
    'LPR' => ['unit' => DIM_UNIT_MM,    'display' => true,
              'sources' => ['reach_in_display', 'reach_m_display']],
    'SIG' => ['unit' => DIM_UNIT_DEG,   'display' => false, 'sources' => ['angle_display']],
    'NOF' => ['unit' => DIM_UNIT_COUNT, 'display' => false, 'sources' => ['flutes']],
    // text_fallback: `helix` carries genuine non-numeric specs - "Variable" (855
    // rows) and "N" (81) - that have no numeric equivalent. Without a fallback
    // those 936 rows would silently lose the spec, so the text is kept in
    // FHA_DISPLAY when there is no angle to store.
    'FHA' => ['unit' => DIM_UNIT_DEG,   'display' => true, 'text_fallback' => true,
              'sources' => ['helix']],
];

const MIG_TYPE_TABLE = [
    'MILLING'    => 'milling_attributes',
    'HOLEMAKING' => 'holemaking_attributes',
    'THREADING'  => 'threading_attributes',
    'INSERTS'    => 'inserts_attributes',
    'SPECIALTY'  => 'specialty_attributes',
    'ARMORY'     => 'armory_attributes',
];

/**
 * Per-type attribute mapping.
 *   'dim'  => [target, legacy_column, unit, has_display]
 *   'text' => [target, legacy_column]
 *   'bool' => [target, legacy_column]
 */
const MIG_TYPE_ATTRS = [
    'milling_attributes' => [
        ['dim',  'STEP_DC',       'step_dia',            DIM_UNIT_MM,    true],
        ['dim',  'SDL_1',         'l1',                  DIM_UNIT_MM,    true],
        ['dim',  'SDL_2',         'l2',                  DIM_UNIT_MM,    true],
        ['dim',  'CHW',           'chamfer',             DIM_UNIT_MM,    false],
        ['dim',  'ULDR',          'ldr',                 DIM_UNIT_RATIO, false],
        ['bool', 'HAS_WELDON_FLAT', 'weldon'],
        ['text', 'DRIVE_TYPE',    'torx_type'],
    ],
    'holemaking_attributes' => [
        ['dim',  'PILOT_DC', 'pilot_dia',   DIM_UNIT_MM, true],
        ['dim',  'DMIN',     'min_bore',    DIM_UNIT_MM, true],
        ['dim',  'BORE_MAX', 'max_bore',    DIM_UNIT_MM, true],
        ['dim',  'LPR',      'projection',  DIM_UNIT_MM, false],
        ['bool', 'HAS_DRILL_POINT', 'drillpoint'],
        ['text', 'PAC_DRILL_SIZE', 'pac_drill_size'],
        ['text', 'SHANK_THREAD_TDZ', 'thread'],
    ],
    'threading_attributes' => [
        ['text', 'TAP_CATEGORY',  'taps_sub_cat'],
        ['text', 'TDZ',           'taps_thread_size'],
        ['text', 'PIPE_TDZ',      'taps_pipe_size'],
        ['dim',  'TPI',           'tpi',                     DIM_UNIT_COUNT, false],
        ['text', 'THFT',          'pitch_classification'],
        ['text', 'THREAD_DIRECTION', 'thread_direction'],
        // Neck diameter is a THREADING attribute in practice: all 52 populated
        // neck_dia_in_display rows are taps, none are milling. Flagged for review.
        ['dim',  'DN',            'neck_dia_in_display',     DIM_UNIT_MM,    true],
        ['text', 'TCTR',          'taps_thread_limit'],
        ['text', 'CLASS_OF_FIT',  'taps_class_of_fit'],
        ['text', 'THCHT',         'taps_chamfer_type'],
        ['dim',  'CHAMFER_LEAD_THREADS', 'chamfer',          DIM_UNIT_COUNT, false],
        // taps_standard is NOT a standards reference: it holds the thread form
        // (NPT, NPTF, BSPP, BSW...) and is byte-identical to pitch_classification
        // in all 349 populated rows, so THFT already carries it. STANDARD_REF is
        // therefore sourced only from the ANSI / STD / DIN 40430 values recovered
        // out of oal_in_display and shank_dia_in_display below.
        ['dim',  'TAP_DRILL_MIN', 'taps_min_tap_drill_size', DIM_UNIT_MM, true],
        ['dim',  'TAP_DRILL_MAX', 'taps_max_tap_drill_size', DIM_UNIT_MM, true],
        ['dim',  'THLGTH',        'taps_thread_length',      DIM_UNIT_MM, true],
        ['dim',  'DRILL_LENGTH',  'taps_drill_length',       DIM_UNIT_MM, true],
        ['dim',  'SQUARE_SIZE',   'taps_square',             DIM_UNIT_MM, true],
        ['dim',  'DCX',           'taps_od1',                DIM_UNIT_MM, false],
        ['text', 'CNSC',          'taps_coolant_duct_type'],
        ['text', 'GAGE_MEMBER',   'taps_go_nogo'],
        ['bool', 'HAS_HANDLE',    'taps_taps_handle'],
        ['text', 'GAGE_BODY',     'taps_body'],
        ['dim',  'GAGE_DEPTH',    'taps_depth',              DIM_UNIT_MM, false],
        ['dim',  'GAGE_DIA',      'taps_dia',                DIM_UNIT_MM, false],
        // These three are referenced by live table configs, so they are carried
        // rather than dropped. None is a dimension: taps_amount is a fluids pack
        // size ("1 GAL"), taps_size a metric range a handle accepts ("M8/M11"),
        // taps_tap_size the inch equivalent ("5/16", "#12").
        ['text', 'PACKAGE_SIZE',     'taps_amount'],
        ['text', 'TAP_SIZE_RANGE',   'taps_size'],
        ['text', 'ACCEPTS_TAP_SIZE', 'taps_tap_size'],
    ],
    'inserts_attributes' => [
        ['text', 'ISO_DESIGNATION', 'iso_code'],
        ['text', 'SHAPE_CODE',      'shape'],
        ['dim',  'IC',              'ic',        DIM_UNIT_MM, true],
        ['dim',  'S',               'thickness', DIM_UNIT_MM, true],
        ['dim',  'W1',              'width',     DIM_UNIT_MM, true],
        ['text', 'MOUNTING_STYLE',  'mounting'],
    ],
    'specialty_attributes' => [
        ['text', 'BURR_DESIGNATION', 'tool'],
        ['dim',  'SPLIT_LENGTH',     'split',     DIM_UNIT_MM, true],
        ['dim',  'HTH',              'height',    DIM_UNIT_MM, true],
        ['dim',  'S',                'thickness', DIM_UNIT_MM, true],
        ['dim',  'W1',               'width',     DIM_UNIT_MM, true],
        // CUT_STYLE is handled specially: legacy c2 holds cut-style text for Burrs
        // but grade-sibling part numbers for Blanks.
    ],
    'armory_attributes' => [],
];

/**
 * The 30 legacy columns that held sibling part numbers rather than attributes.
 * legacy column => [axis, code].
 *
 * c2/c5 are carbide grades C2/C5 (verified: part 700-007001 has grade=C2 and
 * c5=700-007001A, its C5-grade sibling). c2 is dual-purpose - for Burrs it holds
 * cut-style text instead - so its rows are split on whether the value resolves to
 * a real part number.
 */
const MIG_VARIANT_COLUMNS = [
    'none'          => ['COATING', 'None'],
    'altin'         => ['COATING', 'AlTiN'],
    'nf1'           => ['COATING', 'NF1'],
    'fx1'           => ['COATING', 'FX1'],
    'fx2'           => ['COATING', 'FX2'],
    'fx3'           => ['COATING', 'FX3'],
    'fx5'           => ['COATING', 'FX5'],
    'fx7'           => ['COATING', 'FX7'],
    'ticn'          => ['COATING', 'TiCN'],
    'tin'           => ['COATING', 'TiN'],
    'tialn'         => ['COATING', 'TiAlN'],
    'taps_none'     => ['COATING', 'None'],
    'taps_tialn'    => ['COATING', 'TiAlN'],
    'taps_tin'      => ['COATING', 'TiN'],
    'nitride_steam' => ['COATING', 'Nitride/Steam'],
    'super_tin'     => ['COATING', 'Super TiN'],
    'balplus'       => ['COATING', 'Balplus'],
    'steam_oxide'   => ['COATING', 'Steam Oxide'],
    'ground'        => ['SURFACE_CONDITION', 'Ground'],
    'unground'      => ['SURFACE_CONDITION', 'Unground'],
    'single'        => ['CUT_STYLE', 'Single Cut'],
    'double'        => ['CUT_STYLE', 'Double Cut'],
    'aluma'         => ['CUT_STYLE', 'Aluma Cut'],
    'c2'            => ['GRADE', 'C2'],
    'c5'            => ['GRADE', 'C5'],
    'a30'           => ['ENGRAVING_ANGLE', '30'],
    'a60'           => ['ENGRAVING_ANGLE', '60'],
    'a90'           => ['ENGRAVING_ANGLE', '90'],
    'taps_plug'     => ['CHAMFER_FORM', 'Plug'],
    'taps_bottom'   => ['CHAMFER_FORM', 'Bottom'],
];

/** Delete order: children before product_core. */
function mig_target_tables()
{
    return [
        'dimension_precision_exceptions',
        'product_variants',
        'milling_attributes',
        'holemaking_attributes',
        'threading_attributes',
        'inserts_attributes',
        'specialty_attributes',
        'armory_attributes',
        'product_core',
    ];
}

// ---------------------------------------------------------------------------
// Connection / small helpers
// ---------------------------------------------------------------------------

/** Read DB credentials from wp-config.php without booting WordPress. */
function mig_connect()
{
    $dir = __DIR__;
    $cfg = null;
    for ($i = 0; $i < 8; $i++) {
        $dir = dirname($dir);
        if (is_file($dir . '/wp-config.php')) {
            $cfg = $dir . '/wp-config.php';
            break;
        }
    }
    if ($cfg === null) {
        fwrite(STDERR, "FATAL: could not locate wp-config.php\n");
        exit(1);
    }
    $src = file_get_contents($cfg);
    $get = function ($key) use ($src) {
        if (preg_match("/define\(\s*'" . $key . "'\s*,\s*'([^']*)'\s*\)/", $src, $m)) {
            return $m[1];
        }
        fwrite(STDERR, "FATAL: $key not found in wp-config.php\n");
        exit(1);
    };
    $conn = new mysqli($get('DB_HOST'), $get('DB_USER'), $get('DB_PASSWORD'), $get('DB_NAME'));
    if ($conn->connect_error) {
        fwrite(STDERR, 'FATAL: ' . $conn->connect_error . "\n");
        exit(1);
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

function mig_checksum(mysqli $conn, $table)
{
    $r = $conn->query("CHECKSUM TABLE `$table`")->fetch_assoc();
    return (string) $r['Checksum'];
}

function mig_normalise_tool_type($v)
{
    $v = strtoupper(trim((string) $v));
    return $v === '' ? 'UNKNOWN' : $v;
}

/** Trim to null. Legacy uses both '' and '-' as absent. */
function mig_str($v)
{
    if ($v === null) {
        return null;
    }
    $v = trim((string) $v);
    return ($v === '' || $v === '-') ? null : $v;
}

function mig_bool($v)
{
    $v = strtoupper(trim((string) $v));
    if ($v === 'Y' || $v === 'YES' || $v === '1') {
        return 1;
    }
    if ($v === 'N' || $v === 'NO' || $v === '0') {
        return 0;
    }
    return null;
}

function mig_unit_system($v)
{
    $v = strtoupper(trim((string) $v));
    if ($v === 'INCH') {
        return 'INCH';
    }
    if ($v === 'METRIC') {
        return 'METRIC';
    }
    return null;
}

/**
 * `coating` mixes real coatings with surface conditions (Ground/Unground), which
 * are not coatings. Those two move to the SURFACE_CONDITION variant axis, so they
 * are not carried here as a coating value.
 */
function mig_coating($v)
{
    $v = mig_str($v);
    if ($v === null) {
        return null;
    }
    return in_array(strtolower($v), ['ground', 'unground'], true) ? null : $v;
}

// ---------------------------------------------------------------------------
// Dimension resolution
// ---------------------------------------------------------------------------

/**
 * Resolve one universal dimension from its ordered candidate columns, recording
 * how the choice was made and flagging disagreements.
 */
function mig_resolve_dimension(array $row, $attr, array $spec, $part, $toolType,
                               callable $rpMerge, callable $rpCorruption, array &$stats)
{
    $chosen     = null;
    $chosenFrom = null;
    $residuals  = [];
    $candidates = [];
    $worst      = null; // most severe paren mismatch seen

    foreach ($spec['sources'] as $col) {
        if (!array_key_exists($col, $row)) {
            continue;
        }
        $raw = $row[$col];
        if (mig_str($raw) === null) {
            continue;
        }

        // Legacy taps_plug/taps_bottom style CONCATENATE garbage never reaches
        // here, but guard generally against absurd lengths in dimension columns.
        if (strlen((string) $raw) > 40) {
            $stats['corruption']++;
            $rpCorruption(['part' => $part, 'tool_type' => $toolType, 'legacy_column' => $col,
                           'raw_value' => substr((string) $raw, 0, 120),
                           'defect' => 'implausibly long value in a dimension column',
                           'action' => 'excluded']);
            continue;
        }

        $p = parse_dimension($raw, $spec['unit']);
        $candidates[] = $col . '=' . trim((string) $raw)
            . ($p['value'] === null ? ' (no value)' : ' -> ' . $p['value']);

        if ($p['corrupt_text'] !== null) {
            // Implausible magnitude - in this data set always Excel serial-date
            // corruption of a fraction, e.g. "1/8" saved as 44569.
            $stats['corruption']++;
            $rpCorruption([
                'part' => $part, 'tool_type' => $toolType, 'legacy_column' => $col,
                'raw_value' => trim((string) $raw),
                'defect' => 'Excel serial-date corruption of a dimension',
                'action' => $p['value'] !== null
                    ? 'value recovered from the parenthetical; corrupt text dropped'
                    : 'UNRECOVERABLE - needs re-sourcing from the catalogue',
            ]);
        } elseif ($p['residual_text'] !== null) {
            $residuals[$col] = $p['residual_text'];
        }
        if ($p['paren_mismatch'] !== null) {
            $worst = [$col, $raw, $p['paren_mismatch']];
        }
        if (!empty($p['suspect_fraction'])) {
            $stats['corruption']++;
            $rpCorruption([
                'part' => $part, 'tool_type' => $toolType, 'legacy_column' => $col,
                'raw_value' => trim((string) $raw),
                'defect' => 'inch fraction with a denominator that is not a power of two',
                'action' => 'value stored as written - unverifiable, needs a catalogue check',
            ]);
        }
        if ($p['value'] !== null && $chosen === null) {
            $chosen     = $p;
            $chosenFrom = $col;
        }
    }

    // Report only where there is something worth a human's attention: a genuine
    // internal contradiction, or more than one candidate disagreeing.
    if ($worst !== null) {
        $stats['conflicts']++;
        $rpMerge([
            'part' => $part, 'attribute' => $attr, 'chosen_source' => (string) $chosenFrom,
            'chosen_value_mm' => $chosen['value'] ?? '', 'chosen_display' => $chosen['display'] ?? '',
            'exact' => isset($chosen) && $chosen['exact'] ? 'Y' : 'N',
            'candidates' => implode(' | ', $candidates),
            'severity' => 'CONFLICT',
            'detail' => sprintf('%s: parenthetical %.6f" vs value %.6f"',
                                $worst[0], $worst[2][0], $worst[2][1]),
        ]);
    } elseif ($chosen !== null && count($candidates) > 1) {
        $delta = mig_candidate_spread($row, $spec);
        if ($delta !== null && $delta > 0.00005) {
            $stats['rounding']++;
            $rpMerge([
                'part' => $part, 'attribute' => $attr, 'chosen_source' => $chosenFrom,
                'chosen_value_mm' => $chosen['value'], 'chosen_display' => (string) $chosen['display'],
                'exact' => $chosen['exact'] ? 'Y' : 'N',
                'candidates' => implode(' | ', $candidates),
                'severity' => $delta > 0.002 ? 'CROSS_COLUMN_CONFLICT' : 'ROUNDING',
                'detail' => sprintf('candidate spread %.6f"', $delta),
            ]);
        }
    }

    // Where the attribute has no numeric home for its text (currently only FHA:
    // "Variable" / "N"), keep the text in _DISPLAY so the spec is not lost.
    $display = $chosen['display'] ?? null;
    if ($chosen === null && !empty($spec['text_fallback'])) {
        foreach ($spec['sources'] as $col) {
            if (isset($residuals[$col])) {
                $display = $residuals[$col];
                unset($residuals[$col]);
                break;
            }
        }
    }

    return [
        'value'         => $chosen['value'] ?? null,
        'display'       => $display,
        'exact'         => $chosen['exact'] ?? true,
        'exact_decimal' => $chosen['exact_decimal'] ?? null,
        'source'        => $chosenFrom,
        'residuals'     => $residuals,
    ];
}

/** Largest disagreement (inches) between populated candidate columns. */
function mig_candidate_spread(array $row, array $spec)
{
    $vals = [];
    foreach ($spec['sources'] as $col) {
        if (!array_key_exists($col, $row) || mig_str($row[$col]) === null) {
            continue;
        }
        $p = parse_dimension($row[$col], $spec['unit']);
        if ($p['value'] !== null) {
            $vals[] = $p['value'];
        }
    }
    if (count($vals) < 2) {
        return null;
    }
    $spreadMm = max($vals) - min($vals);
    return $spec['unit'] === DIM_UNIT_MM ? $spreadMm / 25.4 : $spreadMm;
}

// ---------------------------------------------------------------------------
// Type-specific attributes
// ---------------------------------------------------------------------------

function mig_build_type_attributes($table, array $row, $part, $toolType,
                                   array &$residuals, array $dims,
                                   callable $rpUnparseable, callable $rpCorruption,
                                   array &$stats)
{
    $out  = [];
    $any  = false;

    foreach (MIG_TYPE_ATTRS[$table] ?? [] as $m) {
        $kind = $m[0];
        $tgt  = $m[1];
        $col  = $m[2];
        if (!array_key_exists($col, $row)) {
            continue;
        }
        $raw = $row[$col];

        // `chamfer` holds two different attributes: chamfer lead in threads for
        // THREADING, chamfer width in inches for MILLING. The table it lands in
        // already disambiguates, so no value-sniffing is needed here.
        if ($kind === 'text') {
            $v = mig_str($raw);
            if ($v !== null) {
                $out[$tgt] = $v;
                $any = true;
            }
            continue;
        }
        if ($kind === 'bool') {
            $v = mig_bool($raw);
            if ($v !== null) {
                $out[$tgt] = $v;
                $any = true;
            }
            continue;
        }
        // dim
        $unit    = $m[3];
        $hasDisp = $m[4];
        if (mig_str($raw) === null) {
            continue;
        }
        if (mig_is_concat_garbage($raw)) {
            $stats['corruption']++;
            $rpCorruption(['part' => $part, 'tool_type' => $toolType, 'legacy_column' => $col,
                           'raw_value' => substr((string) $raw, 0, 120),
                           'defect' => 'spreadsheet CONCATENATE artifact, truncated',
                           'action' => 'excluded']);
            continue;
        }
        $p = parse_dimension($raw, $unit);
        if ($p['value'] !== null) {
            // Ranges ("0.274 - 0.276", 102 rows in the tap-drill columns): a _MAX
            // target takes the upper bound, everything else the lower.
            $isMaxTarget = substr($tgt, -4) === '_MAX';
            $out[$tgt] = ($isMaxTarget && $p['range_high'] !== null)
                ? $p['range_high']
                : $p['value'];
            $any = true;
            if ($hasDisp) {
                $out[$tgt . '_DISPLAY'] = $p['display'];
            }
        } elseif ($p['residual_text'] !== null) {
            if (mig_is_unit_label($p['residual_text'])) {
                $stats['corruption']++;
                $rpCorruption(['part' => $part, 'tool_type' => $toolType, 'legacy_column' => $col,
                               'raw_value' => $p['residual_text'],
                               'defect' => 'unit label in a value column, no number present',
                               'action' => 'excluded']);
            } else {
                $stats['unparseable']++;
                $rpUnparseable(['part' => $part, 'tool_type' => $toolType, 'legacy_column' => $col,
                                'raw_value' => $p['residual_text'], 'reason' => 'not numeric',
                                'disposition' => 'UNROUTED',
                                'routed_to' => $tgt . ' skipped']);
            }
        }
    }

    // -- Recover data misfiled in the universal dimension columns ------------
    // For THREADING the legacy cut_dia/oal/shank columns hold thread
    // designations and standard names rather than dimensions.
    if ($table === 'threading_attributes') {
        // cut_dia_in_display carries the *nominal* size ("#8", "M6 X 1") while
        // taps_thread_size carries the full designation ("8-32"). They are
        // complementary, so the nominal size gets its own column instead of being
        // discarded as a conflict; it only fills TDZ when TDZ has no source.
        foreach (['cut_dia_in_display' => isset($out['TDZ']) ? 'NOMINAL_SIZE' : 'TDZ',
                  'oal_in_display' => 'STANDARD_REF',
                  'shank_dia_in_display' => 'STANDARD_REF'] as $col => $tgt) {
            if (!isset($residuals[$col])) {
                continue;
            }
            $txt = $residuals[$col];
            if (!isset($out[$tgt])) {
                $out[$tgt] = $txt;
                $any = true;
                $rpUnparseable(['part' => $part, 'tool_type' => $toolType, 'legacy_column' => $col,
                                'raw_value' => $txt, 'reason' => 'non-dimensional text in a dimension column',
                                'disposition' => 'RECOVERED',
                                'routed_to' => $tgt]);
            } elseif ($out[$tgt] !== $txt) {
                // Two sources disagree - worth a human look.
                $stats['unparseable']++;
                $rpUnparseable(['part' => $part, 'tool_type' => $toolType, 'legacy_column' => $col,
                                'raw_value' => $txt,
                                'reason' => 'conflicts with the value already captured (' . $out[$tgt] . ')',
                                'disposition' => 'CONFLICT',
                                'routed_to' => 'DROPPED - review']);
            }
            // Identical duplicate: the legacy table stores the same thread size or
            // standard in two places. Nothing to review, so not counted.
            unset($residuals[$col]);
        }
    }

    // Letter/number drill gauges arrive as residual text on the diameter columns:
    // "#52", "F", "No. 8". Both cut_dia_in_display and diasize commonly carry the
    // same gauge, so the second one is a duplicate rather than something unrouted.
    if ($table === 'holemaking_attributes') {
        foreach (['cut_dia_in_display', 'diasize'] as $col) {
            if (!isset($residuals[$col]) || !mig_is_drill_gauge($residuals[$col])) {
                continue;
            }
            if (!isset($out['DRILL_GAUGE'])) {
                $out['DRILL_GAUGE'] = $residuals[$col];
                $any = true;
            }
            unset($residuals[$col]);
        }
    }

    // `c2` for SPECIALTY: text values are the cut style; part numbers are grade
    // siblings and are handled by the variant builder instead.
    if ($table === 'specialty_attributes') {
        $c2 = mig_str($row['c2'] ?? null);
        if ($c2 !== null && !preg_match('/^\d{3}-\d{6}/', $c2)) {
            $out['CUT_STYLE'] = $c2;
            $any = true;
        }
    }

    // Verified: across all 18 ARMORY rows, every populated legacy column belongs to
    // product_core or the variant matrix - there is nothing type-specific to store.
    // The table is kept as the declared extension point for the type, but writing a
    // row of NULLs per part would only assert data that does not exist.
    if ($table === 'armory_attributes') {
        return null;
    }
    return $any ? $out : null;
}

/**
 * Letter and number drill gauge designations: "#52", "F", "No. 8".
 * These are real sizes, just not expressible as a decimal in the source.
 */
function mig_is_drill_gauge($txt)
{
    return (bool) preg_match('/^(#\s*\d+|No\.?\s*\d+|[A-Z])$/i', trim((string) $txt));
}

/**
 * Unit labels that leaked into value columns, e.g. step_dia = "IN." on 88 rows.
 * Not a value and not recoverable - there is no number to keep.
 */
function mig_is_unit_label($txt)
{
    return (bool) preg_match('/^(IN\.?|MM\.?|DEG\.?)$/i', trim((string) $txt));
}

/**
 * Detect the broken spreadsheet CONCATENATE output found in taps_plug /
 * taps_bottom on HOLEMAKING rows, e.g.
 *   NNONE0.125NONE0.8751.875DRILL POINTSHARPNONE
 * Recognised by run-together words with no separators plus embedded decimals.
 */
function mig_is_concat_garbage($raw)
{
    $v = trim((string) $raw);
    if (strlen($v) < 20) {
        return false;
    }
    return (bool) preg_match('/NONE|DRILL POINT|SHARP/i', $v)
        && (bool) preg_match('/\d/', $v);
}

// ---------------------------------------------------------------------------
// Variants
// ---------------------------------------------------------------------------

function mig_build_variants(array &$batches, array $row, $part, $toolType,
                            array $validParts, callable $rpCorruption, array &$stats)
{
    $n    = 0;
    $seen = [];
    foreach (MIG_VARIANT_COLUMNS as $col => [$axis, $code]) {
        if (!array_key_exists($col, $row)) {
            continue;
        }
        $v = mig_str($row[$col]);
        if ($v === null) {
            continue;
        }

        if (mig_is_concat_garbage($v)) {
            $stats['corruption']++;
            $rpCorruption(['part' => $part, 'tool_type' => $toolType, 'legacy_column' => $col,
                           'raw_value' => substr($v, 0, 120),
                           'defect' => 'spreadsheet CONCATENATE artifact, truncated',
                           'action' => 'excluded from product_variants']);
            continue;
        }

        // A few legacy cells hold a comma-separated pair of siblings, e.g.
        // `none` = "12004843, 12004844" on 2 rows.
        foreach (preg_split('/\s*,\s*/', $v) as $sibling) {
            $sibling = trim($sibling);
            if ($sibling === '') {
                continue;
            }

            // c2 carries cut-style text for Burrs; only real part numbers are variants.
            if (!isset($validParts[$sibling])) {
                if ($col === 'c2') {
                    continue; // routed to specialty_attributes.CUT_STYLE instead
                }
                if (strlen($sibling) > 13) {
                    // Cannot be a part number at all - the column is 13 chars.
                    $stats['corruption']++;
                    $rpCorruption(['part' => $part, 'tool_type' => $toolType,
                                   'legacy_column' => $col,
                                   'raw_value' => substr($sibling, 0, 120),
                                   'defect' => 'value exceeds the 13-char part number width',
                                   'action' => 'excluded from product_variants']);
                    continue;
                }
                $stats['corruption']++;
                $rpCorruption(['part' => $part, 'tool_type' => $toolType, 'legacy_column' => $col,
                               'raw_value' => $sibling,
                               'defect' => 'variant reference does not resolve to a known part',
                               'action' => 'kept - VARIANT_PART is intentionally not FK-constrained']);
            }

            // Legacy has duplicate coverage of the same axis+code, e.g. `none` and
            // `taps_none` both meaning uncoated. First populated column wins.
            $key = $axis . '|' . $code . '|' . $sibling;
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;

            mig_queue($batches, 'product_variants', [
                'PART'          => $part,
                'VARIANT_AXIS'  => $axis,
                'VARIANT_CODE'  => $code,
                'VARIANT_PART'  => $sibling,
                'IS_SELF'       => $sibling === $part ? 1 : 0,
                'LEGACY_COLUMN' => $col,
            ]);
            $n++;
        }
    }
    return $n;
}

// ---------------------------------------------------------------------------
// Batched writes
// ---------------------------------------------------------------------------

function mig_queue(array &$batches, $table, array $rowData)
{
    $batches[$table][] = $rowData;
}

/**
 * Flush pending rows, parent before children.
 *
 * Batches fill at different rates - there are ~1.3 variant rows per part, so
 * product_variants hits BATCH_SIZE well before product_core does. Flushing in
 * arbitrary order would insert a child before its parent row exists and trip the
 * foreign key. So once anything is ready, everything is flushed in dependency
 * order (product_core first) rather than table by table.
 */
function mig_flush(mysqli $conn, array &$batches, $force)
{
    if (!$force) {
        $ready = false;
        foreach ($batches as $rows) {
            if (count($rows) >= BATCH_SIZE) {
                $ready = true;
                break;
            }
        }
        if (!$ready) {
            return;
        }
    }

    foreach (array_reverse(mig_target_tables()) as $table) {
        if (empty($batches[$table])) {
            continue;
        }
        mig_insert_rows($conn, $table, $batches[$table]);
        $batches[$table] = [];
    }
}

function mig_insert_rows(mysqli $conn, $table, array $rows)
{
    // Rows can have differing key sets, so group by column signature.
    $groups = [];
    foreach ($rows as $r) {
        $keys = array_keys($r);
        sort($keys);
        $groups[implode(',', $keys)][] = $r;
    }
    foreach ($groups as $sig => $group) {
        $cols  = explode(',', $sig);
        $ph    = '(' . implode(',', array_fill(0, count($cols), '?')) . ')';
        $sql   = "INSERT INTO `$table` (`" . implode('`,`', $cols) . '`) VALUES '
               . implode(',', array_fill(0, count($group), $ph));
        $stmt  = $conn->prepare($sql);
        if (!$stmt) {
            fwrite(STDERR, "FATAL: prepare failed on $table: " . $conn->error . "\n$sql\n");
            exit(1);
        }
        $types = '';
        $vals  = [];
        foreach ($group as $r) {
            foreach ($cols as $c) {
                $v = $r[$c];
                if ($v === null) {
                    $types .= 's';
                    $vals[] = null;
                } elseif (is_int($v)) {
                    $types .= 'i';
                    $vals[] = $v;
                } elseif (is_float($v)) {
                    $types .= 'd';
                    $vals[] = $v;
                } else {
                    $types .= 's';
                    $vals[] = (string) $v;
                }
            }
        }
        $stmt->bind_param($types, ...$vals);
        if (!$stmt->execute()) {
            fwrite(STDERR, "FATAL: insert failed on $table: " . $stmt->error . "\n");
            exit(1);
        }
        $stmt->close();
    }
}

// ---------------------------------------------------------------------------
// CSV reporting
// ---------------------------------------------------------------------------

function mig_csv($path, array $header)
{
    $fh = fopen($path, 'w');
    fputcsv($fh, $header);
    return function (array $rowData) use ($fh, $header) {
        $line = [];
        foreach ($header as $h) {
            $line[] = $rowData[$h] ?? '';
        }
        fputcsv($fh, $line);
    };
}
