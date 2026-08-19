<?php
/**
 * Audit master_product_data for the engineering review package.
 *
 * Strictly read-only. Produces two CSVs that the migration cannot derive, because
 * they are about how the legacy columns are *referenced* rather than what they
 * contain:
 *
 *   column_usage_matrix.csv  every legacy column: fill count, which config
 *                            surfaces reference it, and its disposition
 *   broken_config_refs.csv   config rows naming columns that do not exist
 *
 * The parsing-dependent reports (unparseable_values, corruption_report,
 * dimension_merge_report) are emitted by migrate_product_data.php instead, so the
 * two never drift.
 *
 * Usage: php scripts/audit_master_product_data.php
 */

require __DIR__ . '/lib/dimension-parser.php';
require __DIR__ . '/lib/migrate-config.php';

$auditDir = __DIR__ . '/audit';
if (!is_dir($auditDir)) {
    mkdir($auditDir, 0775, true);
}

$conn = mig_connect();

// ---------------------------------------------------------------------------
// Legacy columns and their fill counts
// ---------------------------------------------------------------------------
$columns = [];
$r = $conn->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS
                   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'master_product_data'
                   ORDER BY ORDINAL_POSITION");
while ($x = $r->fetch_row()) {
    $columns[] = $x[0];
}

$fill = [];
foreach ($columns as $c) {
    $q = $conn->query("SELECT COUNT(*) FROM `master_product_data`
                       WHERE `$c` IS NOT NULL AND TRIM(`$c`) <> ''");
    $fill[$c] = (int) $q->fetch_row()[0];
}

// ---------------------------------------------------------------------------
// Which config surfaces reference each column
// ---------------------------------------------------------------------------
$refSeries  = audit_referenced($conn, "SELECT data_fields FROM master_series_data
                                        WHERE data_fields IS NOT NULL AND data_fields <> ''");
$refFilters = audit_referenced($conn, "SELECT data_filters FROM filters_config
                                        WHERE data_filters IS NOT NULL AND data_filters <> ''");
$refWoo     = audit_referenced($conn, "SELECT t.name FROM wp_terms t
                                        JOIN wp_term_taxonomy tt ON tt.term_id = t.term_id
                                        WHERE tt.taxonomy = 'pa_data-fields'");

// ---------------------------------------------------------------------------
// Disposition, derived from the actual migration mapping rather than restated
// ---------------------------------------------------------------------------
$mapped = [];
$r = $conn->query("SELECT COLUMN_NAME, HOST_TABLE, LEGACY_COLUMN_NAME
                   FROM attribute_dictionary WHERE LEGACY_COLUMN_NAME IS NOT NULL");
while ($x = $r->fetch_assoc()) {
    foreach (explode(',', $x['LEGACY_COLUMN_NAME']) as $leg) {
        $leg = trim($leg);
        if ($leg !== '') {
            $mapped[$leg][] = $x['HOST_TABLE'] . '.' . $x['COLUMN_NAME'];
        }
    }
}

$out = mig_csv($auditDir . '/column_usage_matrix.csv', [
    'legacy_column', 'filled_rows', 'in_data_fields', 'in_data_filters',
    'in_pa_data_fields', 'referenced_anywhere', 'disposition', 'maps_to',
]);

$tally = ['MAPPED' => 0, 'VARIANT' => 0, 'DROPPED' => 0];
foreach ($columns as $c) {
    $inS = isset($refSeries[$c]);
    $inF = isset($refFilters[$c]);
    $inW = isset($refWoo[$c]);
    $any = $inS || $inF || $inW;

    if (isset(MIG_VARIANT_COLUMNS[$c])) {
        $disp   = 'VARIANT';
        $mapsTo = 'product_variants (' . MIG_VARIANT_COLUMNS[$c][0] . '='
                . MIG_VARIANT_COLUMNS[$c][1] . ')';
    } elseif (isset($mapped[$c])) {
        $disp   = 'MAPPED';
        $mapsTo = implode(' + ', array_unique($mapped[$c]));
    } else {
        $disp   = 'DROPPED';
        $mapsTo = '';
    }
    $tally[$disp]++;

    $out([
        'legacy_column'       => $c,
        'filled_rows'         => $fill[$c],
        'in_data_fields'      => $inS ? 'Y' : '',
        'in_data_filters'     => $inF ? 'Y' : '',
        'in_pa_data_fields'   => $inW ? 'Y' : '',
        'referenced_anywhere' => $any ? 'Y' : 'N',
        'disposition'         => $disp,
        'maps_to'             => $mapsTo,
    ]);
}

// ---------------------------------------------------------------------------
// Config rows naming columns that do not exist in master_product_data
// ---------------------------------------------------------------------------
$broken = mig_csv($auditDir . '/broken_config_refs.csv',
    ['source', 'identifier', 'broken_reference', 'full_value', 'note']);

$known    = array_flip($columns);
$nBroken  = 0;

$r = $conn->query("SELECT series, data_fields FROM master_series_data
                   WHERE data_fields IS NOT NULL AND data_fields <> ''");
while ($x = $r->fetch_assoc()) {
    foreach (explode(',', $x['data_fields']) as $f) {
        $f = trim($f);
        if ($f === '' || isset($known[$f])) {
            continue;
        }
        $partsInMaster = audit_series_part_count($conn, $x['series']);
        $broken([
            'source' => 'master_series_data.data_fields',
            'identifier' => 'series ' . $x['series'],
            'broken_reference' => $f,
            'full_value' => $x['data_fields'],
            'note' => $partsInMaster === 0
                ? 'series has 0 parts in master_product_data - orphaned definition'
                : audit_other_table_note($conn, $f),
        ]);
        $nBroken++;
    }
}

$r = $conn->query("SELECT id, tool_type, sub_type, sub_sub_type, data_filters FROM filters_config
                   WHERE data_filters IS NOT NULL AND data_filters <> ''");
while ($x = $r->fetch_assoc()) {
    foreach (explode(',', $x['data_filters']) as $f) {
        $f = trim($f);
        if ($f === '' || isset($known[$f])) {
            continue;
        }
        $broken([
            'source' => 'filters_config.data_filters',
            'identifier' => sprintf('#%d %s/%s/%s', $x['id'], $x['tool_type'],
                                    $x['sub_type'], $x['sub_sub_type'] ?? ''),
            'broken_reference' => $f,
            'full_value' => $x['data_filters'],
            'note' => audit_other_table_note($conn, $f),
        ]);
        $nBroken++;
    }
}

printf("column_usage_matrix.csv : %d columns (mapped %d, variant %d, dropped %d)\n",
    count($columns), $tally['MAPPED'], $tally['VARIANT'], $tally['DROPPED']);
printf("broken_config_refs.csv  : %d broken references\n", $nBroken);

// ---------------------------------------------------------------------------

/** Collect the set of column names named across a comma-separated config column. */
function audit_referenced(mysqli $conn, $sql)
{
    $set = [];
    $r = $conn->query($sql);
    if (!$r) {
        return $set;
    }
    while ($x = $r->fetch_row()) {
        foreach (explode(',', (string) $x[0]) as $f) {
            $f = trim($f);
            if ($f !== '') {
                $set[$f] = true;
            }
        }
    }
    return $set;
}

function audit_series_part_count(mysqli $conn, $series)
{
    $stmt = $conn->prepare("SELECT COUNT(*) FROM master_product_data WHERE series = ?");
    $stmt->bind_param('s', $series);
    $stmt->execute();
    $n = (int) $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    return $n;
}

/** Say where a broken reference does resolve, if anywhere. */
function audit_other_table_note(mysqli $conn, $column)
{
    $stmt = $conn->prepare("SELECT TABLE_NAME FROM information_schema.COLUMNS
                            WHERE TABLE_SCHEMA = DATABASE() AND COLUMN_NAME = ?
                              AND TABLE_NAME NOT LIKE 'wp_%' LIMIT 3");
    $stmt->bind_param('s', $column);
    $stmt->execute();
    $res = $stmt->get_result();
    $hits = [];
    while ($x = $res->fetch_row()) {
        $hits[] = $x[0];
    }
    $stmt->close();
    return $hits
        ? 'exists on: ' . implode(', ', $hits)
        : 'does not exist on any non-wp table';
}
