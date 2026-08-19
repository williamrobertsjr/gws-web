<?php
/**
 * Verify the migrated tables against the untouched source.
 *
 * Every check reads master_product_data read-only and compares it to the new
 * tables, so this is safe to re-run after each migration pass. Exits non-zero if
 * any check fails, so it can gate the hand-off.
 *
 * Usage: php scripts/verify_product_data.php
 */

require __DIR__ . '/lib/dimension-parser.php';
require __DIR__ . '/lib/migrate-config.php';

$conn = mig_connect();
$auditDir = __DIR__ . '/audit';

$failures = 0;
$checks   = 0;

/** Record a pass/fail line. Detail is printed only when the check fails. */
function check($label, $ok, $detail = '')
{
    global $failures, $checks;
    $checks++;
    if (!$ok) {
        $failures++;
    }
    printf("[%s] %s\n", $ok ? ' ok ' : 'FAIL', $label);
    if (!$ok && $detail !== '') {
        foreach (explode("\n", rtrim($detail)) as $line) {
            echo "         $line\n";
        }
    }
}

function scalar(mysqli $conn, $sql)
{
    $r = $conn->query($sql);
    if (!$r) {
        throw new RuntimeException($conn->error . " :: $sql");
    }
    return $r->fetch_row()[0];
}

function rows(mysqli $conn, $sql, $limit = 10)
{
    $r = $conn->query($sql);
    if (!$r) {
        throw new RuntimeException($conn->error . " :: $sql");
    }
    $out = [];
    while (($x = $r->fetch_assoc()) && count($out) < $limit) {
        $out[] = implode(' | ', array_map(fn($v) => $v === null ? 'NULL' : $v, $x));
    }
    return $out;
}

echo "=== 1. Row counts ===\n";

$srcRows = (int) scalar($conn, "SELECT COUNT(*) FROM master_product_data");
$core    = (int) scalar($conn, "SELECT COUNT(*) FROM product_core");
check("product_core row count matches source ($core = $srcRows)", $core === $srcRows);

$distinctParts = (int) scalar($conn, "SELECT COUNT(DISTINCT part) FROM master_product_data");
check("source part numbers are unique ($distinctParts distinct of $srcRows)",
      $distinctParts === $srcRows);

$missing = rows($conn, "SELECT s.part FROM master_product_data s
                        LEFT JOIN product_core c ON c.PART = s.part
                        WHERE c.PART IS NULL");
check('every source part exists in product_core', !$missing, implode("\n", $missing));

$extra = rows($conn, "SELECT c.PART FROM product_core c
                      LEFT JOIN master_product_data s ON s.part = c.PART
                      WHERE s.part IS NULL");
check('product_core invents no parts', !$extra, implode("\n", $extra));

echo "\n=== 2. Per-type attribute table counts ===\n";

// The attribute tables are deliberately sparse: a part whose type-specific columns
// are all empty gets no row, rather than a row of NULLs. So the invariant is not
// "count matches the type count" but "a row exists exactly when there is data".
foreach (MIG_TYPE_TABLE as $type => $table) {
    $typeCount = (int) scalar($conn, "SELECT COUNT(*) FROM product_core WHERE TOOL_TYPE = '$type'");
    $actual    = (int) scalar($conn, "SELECT COUNT(*) FROM `$table`");
    printf("         %-24s %6d rows of %d %s parts\n", $table, $actual, $typeCount, $type);
    check("$table never exceeds its tool type's part count ($actual <= $typeCount)",
          $actual <= $typeCount);

    $mismatch = rows($conn, "SELECT a.PART, c.TOOL_TYPE FROM `$table` a
                             JOIN product_core c ON c.PART = a.PART
                             WHERE c.TOOL_TYPE <> '$type'");
    check("$table holds only $type parts", !$mismatch, implode("\n", $mismatch));

    // No row may be entirely empty - that would mean the sparsity rule leaked.
    $cols = [];
    $r = $conn->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS
                       WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table'
                         AND COLUMN_NAME <> 'PART'");
    while ($x = $r->fetch_row()) {
        $cols[] = "`{$x[0]}` IS NULL";
    }
    if ($cols) {
        $empty = rows($conn, "SELECT PART FROM `$table` WHERE " . implode(' AND ', $cols));
        check("$table has no all-NULL rows", !$empty, implode("\n", $empty));
    }
}

echo "\n=== 3. Variants ===\n";

$vTotal = (int) scalar($conn, "SELECT COUNT(*) FROM product_variants");
$vAxes  = (int) scalar($conn, "SELECT COUNT(DISTINCT VARIANT_AXIS) FROM product_variants");
printf("         %d variant rows across %d axes\n", $vTotal, $vAxes);

// VARIANT_PART is deliberately not FK-constrained: a handful of legacy references
// point at parts that no longer exist. Those are kept rather than dropped, so the
// check is not "all resolve" but "every one that does not is in the corruption
// report" - which is what stops a silent regression from hiding among them.
$dangling = [];
$r = $conn->query("SELECT v.PART, v.LEGACY_COLUMN, v.VARIANT_PART FROM product_variants v
                   LEFT JOIN product_core c ON c.PART = v.VARIANT_PART
                   WHERE c.PART IS NULL");
while ($x = $r->fetch_assoc()) {
    $dangling[$x['PART'] . '|' . $x['LEGACY_COLUMN'] . '|' . $x['VARIANT_PART']] = true;
}
printf("         %d dangling VARIANT_PART references (kept, not FK-constrained)\n",
       count($dangling));

$reported = [];
$csv = $auditDir . '/corruption_report.csv';
if (is_readable($csv)) {
    $fh = fopen($csv, 'r');
    $hdr = fgetcsv($fh);
    while (($line = fgetcsv($fh)) !== false) {
        $rowc = array_combine($hdr, $line);
        if (strpos($rowc['defect'], 'does not resolve to a known part') !== false) {
            $reported[$rowc['part'] . '|' . $rowc['legacy_column'] . '|' . $rowc['raw_value']] = true;
        }
    }
    fclose($fh);
}
$unreported = array_keys(array_diff_key($dangling, $reported));
check('every dangling VARIANT_PART is recorded in corruption_report.csv ('
      . count($reported) . ' reported)',
      !$unreported, implode("\n", array_slice($unreported, 0, 20)));

$badSelf = rows($conn, "SELECT PART, VARIANT_PART, IS_SELF FROM product_variants
                        WHERE IS_SELF <> (PART = VARIANT_PART)");
check('IS_SELF agrees with PART = VARIANT_PART', !$badSelf, implode("\n", $badSelf));

$unknownCol = rows($conn, "SELECT DISTINCT LEGACY_COLUMN FROM product_variants
                           WHERE LEGACY_COLUMN NOT IN ('"
                           . implode("','", array_keys(MIG_VARIANT_COLUMNS)) . "')");
check('every LEGACY_COLUMN is a declared variant column', !$unknownCol, implode("\n", $unknownCol));

echo "\n=== 4. Dimension precision ===\n";

$dimAttrs = [];
foreach (MIG_CORE_DIMENSIONS as $attr => $spec) {
    if ($spec['unit'] === DIM_UNIT_MM) {
        $dimAttrs[] = $attr;
    }
}

// The stored value must be the 4dp rounding of the exact value, not merely close
// to it: an off-by-one-ulp result would mean the parser and the column disagree.
$badRound = rows($conn, "SELECT PART, ATTRIBUTE, STORED_VALUE, EXACT_DECIMAL
                         FROM dimension_precision_exceptions
                         WHERE EXACT_DECIMAL IS NOT NULL
                           AND STORED_VALUE <> ROUND(EXACT_DECIMAL, 4)");
check('STORED_VALUE = ROUND(EXACT_DECIMAL, 4) for every exception',
      !$badRound, implode("\n", $badRound));

$nExc = (int) scalar($conn, "SELECT COUNT(*) FROM dimension_precision_exceptions");
$maxErr = scalar($conn, "SELECT COALESCE(MAX(ABS(STORED_VALUE - EXACT_DECIMAL)), 0)
                         FROM dimension_precision_exceptions WHERE EXACT_DECIMAL IS NOT NULL");
printf("         %d exceptions, worst absolute error %s mm\n", $nExc, $maxErr);
check("rounding error stays within half a unit in the last place (<= 0.00005 mm)",
      (float) $maxErr <= 0.00005 + DIM_EPSILON, "max error $maxErr mm");

// Exception rows are for rounded values only; an exactly representable value must
// not be listed.
$exactListed = rows($conn, "SELECT PART, ATTRIBUTE, STORED_VALUE, EXACT_DECIMAL
                            FROM dimension_precision_exceptions
                            WHERE EXACT_DECIMAL IS NOT NULL
                              AND EXACT_DECIMAL = ROUND(EXACT_DECIMAL, 4)");
check('no exactly-representable value is flagged as an exception',
      !$exactListed, implode("\n", $exactListed));

$orphanExc = rows($conn, "SELECT e.PART, e.ATTRIBUTE FROM dimension_precision_exceptions e
                          LEFT JOIN product_core c ON c.PART = e.PART
                          WHERE c.PART IS NULL");
check('every exception row points at a real part', !$orphanExc, implode("\n", $orphanExc));

// Generated inch columns must reproduce the mm value exactly on divide-back.
foreach ($dimAttrs as $attr) {
    $bad = rows($conn, "SELECT PART, `$attr`, `{$attr}_IN` FROM product_core
                        WHERE `$attr` IS NOT NULL
                          AND ABS(`{$attr}_IN` * 25.4 - `$attr`) > 0.0001");
    check("{$attr}_IN round-trips to $attr within 0.0001 mm", !$bad, implode("\n", $bad));
}

echo "\n=== 5. Parser parity (all rows re-parsed) ===\n";

// Re-run the parser over the source and diff against what is stored. This is the
// check that would catch a parser change silently altering already-loaded values.
$stored = [];
$sel = implode(', ', array_map(fn($a) => "`$a`, `{$a}_DISPLAY`",
       array_keys(array_filter(MIG_CORE_DIMENSIONS, fn($s) => !empty($s['display'])))));
$res = $conn->query("SELECT PART, NOF, SIG, $sel FROM product_core");
while ($r = $res->fetch_assoc()) {
    $stored[$r['PART']] = $r;
}
$res->free();

$noop = function () {};
$discard = ['rows' => 0, 'core' => 0, 'variants' => 0, 'precision' => 0,
            'unparseable' => 0, 'corruption' => 0, 'conflicts' => 0, 'rounding' => 0];

// Stored type-attribute rows, so the same pass can check them without a second
// read of the source.
$storedAttrs = [];
foreach (array_unique(array_values(MIG_TYPE_TABLE)) as $t) {
    $res = $conn->query("SELECT * FROM `$t`");
    while ($r = $res->fetch_assoc()) {
        $storedAttrs[$t][$r['PART']] = $r;
    }
    $res->free();
}

$parityFails = [];
$attrFails   = [];
$reparsed = 0;
$res = $conn->query("SELECT * FROM master_product_data");
while ($row = $res->fetch_assoc()) {
    $part = trim((string) $row['part']);
    $toolType = mig_normalise_tool_type($row['tool_type']);
    $have = $stored[$part] ?? null;
    if ($have === null) {
        continue;
    }
    $reparsed++;
    $dims      = [];
    $residuals = [];
    foreach (MIG_CORE_DIMENSIONS as $attr => $spec) {
        $r = mig_resolve_dimension($row, $attr, $spec, $part, $toolType,
                                   $noop, $noop, $discard);
        $dims[$attr] = $r;
        foreach ($r['residuals'] as $leg => $txt) {
            $residuals[$leg] = $txt;
        }
        $expect = $r['value'];
        $actual = $have[$attr];

        if ($attr === 'NOF') {
            $expect = $expect === null ? null : (string) (int) round($expect);
            if ((string) $actual !== (string) $expect) {
                $parityFails[] = "$part $attr stored=" . var_export($actual, true)
                               . ' reparsed=' . var_export($expect, true);
            }
            continue;
        }
        $same = ($expect === null && $actual === null)
             || ($expect !== null && $actual !== null
                 && abs((float) $actual - (float) $expect) < 0.00005);
        if (!$same) {
            $parityFails[] = "$part $attr stored=" . var_export($actual, true)
                           . ' reparsed=' . var_export($expect, true);
        }
        if (!empty($spec['display'])) {
            $dCol = $attr . '_DISPLAY';
            if ((string) ($have[$dCol] ?? '') !== (string) ($r['display'] ?? '')) {
                $parityFails[] = "$part $dCol stored=" . var_export($have[$dCol], true)
                               . ' reparsed=' . var_export($r['display'], true);
            }
        }
    }

    // Type-specific attributes, rebuilt the same way the migration builds them.
    $table = MIG_TYPE_TABLE[$toolType] ?? null;
    if ($table !== null) {
        $rebuilt = mig_build_type_attributes($table, $row, $part, $toolType,
                                             $residuals, $dims, $noop, $noop, $discard);
        $haveAttrs = $storedAttrs[$table][$part] ?? null;

        if (($rebuilt === null) !== ($haveAttrs === null)) {
            $attrFails[] = "$part $table row " . ($haveAttrs === null ? 'missing' : 'unexpected')
                         . ' (rebuild ' . ($rebuilt === null ? 'empty' : 'non-empty') . ')';
        } elseif ($rebuilt !== null) {
            foreach ($rebuilt as $col => $want) {
                $got = $haveAttrs[$col] ?? null;
                $same = ($want === null && $got === null)
                     || ($want !== null && $got !== null
                         && (is_numeric($want) && is_numeric($got)
                             ? abs((float) $got - (float) $want) < 0.00005
                             : (string) $got === (string) $want));
                if (!$same) {
                    $attrFails[] = "$part $table.$col stored=" . var_export($got, true)
                                 . ' rebuilt=' . var_export($want, true);
                }
            }
        }
    }
}
$res->free();

check("all $reparsed rows re-parse to the stored core values ("
      . count($parityFails) . ' mismatches)',
      !$parityFails, implode("\n", array_slice($parityFails, 0, 20))
        . (count($parityFails) > 20 ? "\n... and " . (count($parityFails) - 20) . ' more' : ''));

check('type-attribute rows rebuild identically, and exist exactly where there is data ('
      . count($attrFails) . ' mismatches)',
      !$attrFails, implode("\n", array_slice($attrFails, 0, 20))
        . (count($attrFails) > 20 ? "\n... and " . (count($attrFails) - 20) . ' more' : ''));

echo "\n=== 6. Dictionary completeness ===\n";

$dict = [];
$res = $conn->query("SELECT HOST_TABLE, COLUMN_NAME FROM attribute_dictionary");
while ($r = $res->fetch_row()) {
    $dict[$r[0] . '.' . $r[1]] = true;
}
$res->free();

$realCols = [];
$tables = array_values(MIG_TYPE_TABLE);
$tables[] = 'product_core';
$tables = array_unique($tables);
foreach ($tables as $t) {
    $res = $conn->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS
                         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$t'");
    while ($r = $res->fetch_row()) {
        $realCols[$t . '.' . $r[0]] = true;
    }
    $res->free();
}

$undocumented = array_values(array_diff(array_keys($realCols), array_keys($dict)));
check('every column in the new tables has a dictionary row ('
      . count($undocumented) . ' undocumented)',
      !$undocumented, implode("\n", $undocumented));

$phantom = array_values(array_diff(array_keys($dict), array_keys($realCols)));
check('every dictionary row names a real column (' . count($phantom) . ' phantom)',
      !$phantom, implode("\n", $phantom));

// IS_DIMENSION is used in the ISO 80000 sense: 1 for a quantity that carries a
// physical dimension (length or angle), 0 for a dimensionless one (count, ratio)
// and for text. So MM/INCH/DEG must be flagged and COUNT/RATIO must not, and
// DECIMALS, CANONICAL_UNIT and UNIT_CODE are declared as a set or not at all.
$badUnit = rows($conn, "SELECT HOST_TABLE, COLUMN_NAME, IS_DIMENSION, CANONICAL_UNIT, DECIMALS
                        FROM attribute_dictionary
                        WHERE (DECIMALS IS NULL) <> (CANONICAL_UNIT IS NULL)
                           OR (CANONICAL_UNIT IS NOT NULL AND UNIT_CODE IS NULL)
                           OR (CANONICAL_UNIT IN ('MM','INCH','DEG') AND IS_DIMENSION <> 1)
                           OR (IS_DIMENSION = 1 AND CANONICAL_UNIT NOT IN ('MM','INCH','DEG'))", 20);
check('unit metadata is declared as a set, and IS_DIMENSION matches the unit',
      !$badUnit, implode("\n", $badUnit));

// A generated inch twin must exist for exactly the millimetre attributes on
// product_core - that is the pair the eventual consumer transition relies on.
$dictMm = array_map(fn($s) => explode(' | ', $s)[0],
          rows($conn, "SELECT COLUMN_NAME FROM attribute_dictionary
                       WHERE HOST_TABLE = 'product_core' AND CANONICAL_UNIT = 'MM'", 100));
$missingIn = [];
foreach ($dictMm as $col) {
    if (!isset($realCols["product_core.{$col}_IN"])) {
        $missingIn[] = "product_core.{$col}_IN";
    }
}
check('every millimetre attribute on product_core has a generated _IN twin',
      !$missingIn, implode("\n", $missingIn));

$badVerified = rows($conn, "SELECT HOST_TABLE, COLUMN_NAME, ISO_CODE, CODE_VERIFIED
                            FROM attribute_dictionary
                            WHERE (ISO_CODE IS NOT NULL AND CODE_VERIFIED <> 'Y')
                               OR (CODE_VERIFIED = 'Y' AND ISO_CODE IS NULL AND ISO_STANDARD IS NULL)");
check("CODE_VERIFIED='Y' exactly where a standard is cited", !$badVerified, implode("\n", $badVerified));

echo "\n=== 7. No silently unpopulated column ===\n";

// A mapped column that ends up entirely NULL means the routing is wrong, not that
// the data is absent - this is what caught THREAD_DIRECTION (10,596 source rows
// never written), DN (routed to the wrong tool type) and ULDR ("3X" unparsed).
// armory_attributes is exempt: it is empty by design, documented in the DDL.
$emptyCols = [];
foreach ($tables as $t) {
    if ($t === 'armory_attributes') {
        continue;
    }
    $r = $conn->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS
                       WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$t'
                         AND EXTRA NOT LIKE '%GENERATED%'");
    $cols = [];
    while ($x = $r->fetch_row()) {
        $cols[] = $x[0];
    }
    foreach ($cols as $col) {
        $filled = (int) scalar($conn, "SELECT COUNT(*) FROM `$t`
                                       WHERE `$col` IS NOT NULL AND TRIM(`$col`) <> ''");
        if ($filled === 0) {
            $legacy = scalar($conn, "SELECT COALESCE(LEGACY_COLUMN_NAME, '')
                                     FROM attribute_dictionary
                                     WHERE HOST_TABLE = '$t' AND COLUMN_NAME = '$col'
                                       AND TOOL_TYPE = '' AND SERIES = '' LIMIT 1");
            $emptyCols[] = "$t.$col (legacy: " . ($legacy ?: 'none') . ')';
        }
    }
}
check('no mapped column is entirely empty (' . count($emptyCols) . ' empty)',
      !$emptyCols, implode("\n", $emptyCols));

echo "\n=== 8. Source table untouched ===\n";

$sum = mig_checksum($conn, 'master_product_data');
$expected = '3199538653';
check("master_product_data checksum is $expected (got $sum)", (string) $sum === $expected,
      'A different checksum means either the source was written to, or it was legitimately '
      . "edited upstream. Confirm which before trusting the migration's no-op claim.");

echo "\n=== Audit report sizes ===\n";
foreach (glob($auditDir . '/*.csv') as $f) {
    printf("         %-34s %6d rows\n", basename($f),
           max(0, count(file($f, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)) - 1));
}

printf("\n%d checks, %d failures\n", $checks, $failures);
exit($failures === 0 ? 0 : 1);
