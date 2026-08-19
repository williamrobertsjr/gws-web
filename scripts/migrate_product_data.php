<?php
/**
 * Migrate master_product_data -> product_core + per-type attribute tables.
 *
 * Reads master_product_data STRICTLY READ-ONLY and rebuilds the new tables from
 * scratch on every run, so it is safe to re-run as review feedback lands.
 *
 * Usage:  php scripts/migrate_product_data.php [--dry-run]
 *
 * Emits CSV reports to scripts/audit/ alongside the migration, because the
 * reports and the migration need identical parsing - deriving them in a separate
 * pass would let the two drift.
 */

require __DIR__ . '/lib/dimension-parser.php';
require __DIR__ . '/lib/migrate-config.php';

const BATCH_SIZE = 400;

$dryRun  = in_array('--dry-run', $argv, true);
$auditDir = __DIR__ . '/audit';
if (!is_dir($auditDir)) {
    mkdir($auditDir, 0775, true);
}

$conn = mig_connect();
$conn->query("SET SESSION sql_mode = 'STRICT_ALL_TABLES'");

// ---------------------------------------------------------------------------
// Baseline: prove the source table is untouched by comparing this to the value
// recorded after the run.
// ---------------------------------------------------------------------------
$checksumBefore = mig_checksum($conn, 'master_product_data');
fwrite(STDERR, "source checksum before: $checksumBefore\n");

// ---------------------------------------------------------------------------
// Load the set of valid part numbers once. Used to decide whether a value in a
// variant column is a real sibling part or contaminated text.
// ---------------------------------------------------------------------------
$validParts = [];
$res = $conn->query("SELECT part FROM master_product_data");
while ($r = $res->fetch_row()) {
    $validParts[$r[0]] = true;
}
$res->free();
fwrite(STDERR, 'valid parts: ' . count($validParts) . "\n");

// ---------------------------------------------------------------------------
// Rebuild: clear targets in FK-safe order (children before parent).
// ---------------------------------------------------------------------------
if (!$dryRun) {
    foreach (mig_target_tables() as $t) {
        $conn->query("DELETE FROM `$t`");
    }
}

// ---------------------------------------------------------------------------
// Reports
// ---------------------------------------------------------------------------
// Not all of these are failures: RECOVERED rows are text that was successfully
// rerouted to the column that actually means it. Only CONFLICT and UNROUTED need a
// human, and only those two are counted in the `unparseable` statistic.
$rpUnparseable = mig_csv($auditDir . '/unparseable_values.csv',
    ['part', 'tool_type', 'legacy_column', 'raw_value', 'reason', 'disposition', 'routed_to']);
$rpCorruption = mig_csv($auditDir . '/corruption_report.csv',
    ['part', 'tool_type', 'legacy_column', 'raw_value', 'defect', 'action']);
$rpMerge = mig_csv($auditDir . '/dimension_merge_report.csv',
    ['part', 'attribute', 'chosen_source', 'chosen_value_mm', 'chosen_display', 'exact',
     'candidates', 'severity', 'detail']);

$stats = [
    'rows' => 0, 'core' => 0, 'variants' => 0, 'precision' => 0,
    'unparseable' => 0, 'corruption' => 0, 'conflicts' => 0, 'rounding' => 0,
];
$byType = [];

$batches = [];

// ---------------------------------------------------------------------------
// Main pass
// ---------------------------------------------------------------------------
// Buffered deliberately: an unbuffered (MYSQLI_USE_RESULT) read would block the
// INSERTs that run inside this loop on the same connection.
$res = $conn->query("SELECT * FROM master_product_data");
while ($row = $res->fetch_assoc()) {
    $stats['rows']++;
    $part     = trim((string) $row['part']);
    $toolType = mig_normalise_tool_type($row['tool_type']);
    $byType[$toolType] = ($byType[$toolType] ?? 0) + 1;

    // -- Universal dimensions ------------------------------------------------
    $dims      = [];
    $residuals = [];
    foreach (MIG_CORE_DIMENSIONS as $attr => $spec) {
        $r = mig_resolve_dimension($row, $attr, $spec, $part, $toolType,
                                   $rpMerge, $rpCorruption, $stats);
        $dims[$attr] = $r;
        foreach ($r['residuals'] as $leg => $txt) {
            $residuals[$leg] = $txt;
        }
    }

    // -- product_core -------------------------------------------------------
    $core = [
        'PART'               => $part,
        'SERIES'             => mig_str($row['series']),
        'FAMILY'             => mig_str($row['family']),
        'BRAND'              => mig_str($row['brand']),
        'TOOL_TYPE'          => $toolType,
        'SUB_TYPE'           => mig_str($row['sub_type']),
        'SUB_SUB_TYPE'       => mig_str($row['sub_sub_type']),
        'PART_DESCRIPTION'   => mig_str($row['part_description']),
        'COUNTRY_OF_ORIGIN'  => mig_str($row['coo']),
        'NATIVE_UNIT_SYSTEM' => mig_unit_system($row['measurement']),
        'COATING'            => mig_coating($row['coating']),
        'GRADE'              => mig_str($row['grade']),
        'SUBSTRATE'          => mig_str($row['taps_material']),
        'TSYC'               => mig_str($row['style']),
        'EDGE_PREPARATION'   => mig_str($row['edge_prep']),
        'IS_WEB_VISIBLE'     => mig_bool($row['web']) ?? 0,
        'IS_IN_CATALOG'      => mig_bool($row['catalog']) ?? 0,
        'SORT_ORDER'         => is_numeric($row['row_order']) ? (int) $row['row_order'] : 0,
        'CUSTOM_IMAGE_SLUG'  => mig_str($row['customimage']),
    ];
    foreach (MIG_CORE_DIMENSIONS as $attr => $spec) {
        $core[$attr] = $dims[$attr]['value'];
        if (!empty($spec['display'])) {
            $core[$attr . '_DISPLAY'] = $dims[$attr]['display'];
        }
    }
    // NOF is a count, not a measurement.
    $core['NOF'] = $dims['NOF']['value'] === null ? null : (int) round($dims['NOF']['value']);
    mig_queue($batches, 'product_core', $core);
    $stats['core']++;

    // -- Precision exceptions ----------------------------------------------
    foreach ($dims as $attr => $r) {
        if ($r['value'] !== null && !$r['exact'] && $r['display'] !== null) {
            mig_queue($batches, 'dimension_precision_exceptions', [
                'PART'          => $part,
                'ATTRIBUTE'     => $attr,
                'STORED_VALUE'  => $r['value'],
                'EXACT_TEXT'    => $r['display'],
                'EXACT_DECIMAL' => $r['exact_decimal'],
            ]);
            $stats['precision']++;
        }
    }

    // -- Type-specific attributes ------------------------------------------
    $table = MIG_TYPE_TABLE[$toolType] ?? null;
    if ($table !== null) {
        $attrs = mig_build_type_attributes(
            $table, $row, $part, $toolType, $residuals, $dims,
            $rpUnparseable, $rpCorruption, $stats
        );
        if ($attrs !== null) {
            $attrs['PART'] = $part;
            mig_queue($batches, $table, $attrs);
        }
    }

    // -- Unrouted residual text --------------------------------------------
    foreach ($residuals as $leg => $txt) {
        $stats['unparseable']++;
        $rpUnparseable(['part' => $part, 'tool_type' => $toolType, 'legacy_column' => $leg,
                        'raw_value' => $txt, 'reason' => 'not a dimension',
                        'disposition' => 'UNROUTED',
                        'routed_to' => 'UNROUTED - review']);
    }

    // -- Variants -----------------------------------------------------------
    $stats['variants'] += mig_build_variants(
        $batches, $row, $part, $toolType, $validParts, $rpCorruption, $stats
    );

    if (!$dryRun) {
        mig_flush($conn, $batches, false);
    }
}
$res->free();

if (!$dryRun) {
    mig_flush($conn, $batches, true);

    // Record the rebuild in the same registry that tracks master_price_data_*, so
    // downstream consumers have one place to ask how fresh a table is.
    $reg = $conn->prepare('INSERT INTO gws_data_table_updates (table_name, updated_at)
                           VALUES (?, NOW()) ON DUPLICATE KEY UPDATE updated_at = NOW()');
    foreach (array_merge(mig_target_tables(), ['attribute_dictionary']) as $t) {
        $reg->bind_param('s', $t);
        $reg->execute();
    }
    $reg->close();
}

// ---------------------------------------------------------------------------
// No-op proof
// ---------------------------------------------------------------------------
$checksumAfter = mig_checksum($conn, 'master_product_data');

fwrite(STDERR, "\n=== migration summary ===\n");
foreach ($stats as $k => $v) {
    fwrite(STDERR, sprintf("  %-14s %d\n", $k, $v));
}
fwrite(STDERR, "  by tool_type: " . json_encode($byType) . "\n");
fwrite(STDERR, sprintf("  source checksum: before=%s after=%s -> %s\n",
    $checksumBefore, $checksumAfter,
    $checksumBefore === $checksumAfter ? 'UNCHANGED' : '*** MODIFIED ***'));

if ($checksumBefore !== $checksumAfter) {
    fwrite(STDERR, "FATAL: master_product_data was modified. Investigate before proceeding.\n");
    exit(1);
}
fwrite(STDERR, $dryRun ? "\ndry run - nothing written\n" : "\ndone\n");
