<?php
/**
 * Seed attribute_dictionary - the legend, made queryable.
 *
 * Display labels are taken from inc/column-mapping.php and WooCommerce attribute
 * slugs from inc/attribute-mapping.php, so the labels the site already uses are
 * carried forward rather than reinvented. Per-tool-type and per-series label
 * overrides that currently live in two duplicated ~250-line switch statements in
 * assets/js/tool-filter.js become rows here.
 *
 * ISO codes marked CODE_VERIFIED='Y' were confirmed against the published Dormer
 * Pramet / Sandvik Coromant ISO 13399 parameter dictionaries. 'N' means no ISO
 * 13399 property exists for that attribute and the name is descriptive.
 *
 * Usage: php scripts/seed_attribute_dictionary.php
 */

require __DIR__ . '/lib/dimension-parser.php';
require __DIR__ . '/lib/migrate-config.php';

$themeDir = dirname(__DIR__);
$labels   = include $themeDir . '/inc/column-mapping.php';
$wooAttrs = include $themeDir . '/inc/attribute-mapping.php';

/**
 * [COLUMN_NAME, HOST_TABLE, LABEL, ISO_CODE, ISO_STANDARD, VERIFIED, UNIT,
 *  UNIT_CODE, DECIMALS, IS_DIMENSION, LEGACY_COLUMNS, NOTES]
 *
 * UNIT_CODE values are UN/CEFACT Recommendation 20: MMT millimetre, DD degree,
 * C62 one (dimensionless).
 */
$D = [
    // ---- product_core: identity and classification ----
    ['PART', 'product_core', 'Part', null, null, 'N', null, null, null, 0, 'part', 'Primary key'],
    ['SERIES', 'product_core', 'Series', null, null, 'N', null, null, null, 0, 'series', null],
    ['FAMILY', 'product_core', 'Family', null, null, 'N', null, null, null, 0, 'family', null],
    ['BRAND', 'product_core', 'Brand', null, null, 'N', null, null, null, 0, 'brand', null],
    ['TOOL_TYPE', 'product_core', 'Tool Type', null, null, 'N', null, null, null, 0, 'tool_type', 'Case normalised; legacy had both INSERTS and Inserts'],
    ['SUB_TYPE', 'product_core', 'Sub Type', null, null, 'N', null, null, null, 0, 'sub_type', null],
    ['SUB_SUB_TYPE', 'product_core', 'Product Type', null, null, 'N', null, null, null, 0, 'sub_sub_type', null],
    ['PART_DESCRIPTION', 'product_core', 'Description', null, null, 'N', null, null, null, 0, 'part_description', 'inserts_descriptions holds a fuller value for 91 parts - see review'],
    ['COUNTRY_OF_ORIGIN', 'product_core', 'Country of Origin', null, 'ISO 3166-1', 'Y', null, null, null, 0, 'coo', 'alpha-3'],
    ['NATIVE_UNIT_SYSTEM', 'product_core', 'Measurement', null, 'ISO 80000-1', 'N', null, null, null, 0, 'measurement', 'Display preference only; storage unit is always the ISO base unit'],
    ['COATING', 'product_core', 'Coating', 'COATING', 'ISO 13399', 'Y', null, null, null, 0, 'coating', 'Ground/Unground moved to the SURFACE_CONDITION variant axis'],
    ['GRADE', 'product_core', 'Grade', null, null, 'N', null, null, null, 0, 'grade', 'Grade designation such as CG88'],
    ['SUBSTRATE', 'product_core', 'Material', 'SUBSTRATE', 'ISO 13399', 'Y', null, null, null, 0, 'taps_material', 'HSS / Carbide / PM'],
    ['TSYC', 'product_core', 'Style', 'TSYC', 'ISO 13399', 'Y', null, null, null, 0, 'style', 'Spans 4 tool types, so held in core'],
    ['EDGE_PREPARATION', 'product_core', 'Edge Prep', null, null, 'N', null, null, null, 0, 'edge_prep', 'No ISO 13399 code'],
    ['IS_WEB_VISIBLE', 'product_core', 'Web Visible', null, null, 'N', null, null, null, 0, 'web', null],
    ['IS_IN_CATALOG', 'product_core', 'In Catalog', null, null, 'N', null, null, null, 0, 'catalog', null],
    ['SORT_ORDER', 'product_core', 'Sort Order', null, null, 'N', null, null, null, 0, 'row_order', null],
    ['CUSTOM_IMAGE_SLUG', 'product_core', 'Custom Image', null, null, 'N', null, null, null, 0, 'customimage', null],

    // ---- product_core: universal dimensions ----
    ['DC', 'product_core', 'Diameter (D₁)', 'DC', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1,
     'cut_dia_in_display,cut_dia_m_display,diasize,diadec', 'outer_dimension discarded: 3dp rounding that also mixed mm with inches'],
    ['DC_DISPLAY', 'product_core', 'Diameter (D₁)', null, null, 'N', null, null, null, 0, 'diasize,cut_dia_in_display', 'Human form; decimal split out into DC'],
    ['DC_IN', 'product_core', 'Diameter (D₁) in', 'DC', 'ISO 13399', 'Y', 'INCH', 'INH', 6, 1, null, 'Generated: DC / 25.4'],
    ['DMM', 'product_core', 'Shank (D₂)', 'DMM', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1,
     'shank_dia_in_display,shank_dia_m_display,taps_shank', 'DMM is Shank diameter; DCON is Connection diameter, a different property'],
    ['DMM_DISPLAY', 'product_core', 'Shank (D₂)', null, null, 'N', null, null, null, 0, 'shank_dia_in_display', null],
    ['DMM_IN', 'product_core', 'Shank (D₂) in', 'DMM', 'ISO 13399', 'Y', 'INCH', 'INH', 6, 1, null, 'Generated: DMM / 25.4'],
    ['OAL', 'product_core', 'OAL (L)', 'OAL', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1,
     'oal_in_display,oal_m_display', 'OAL and LF (Functional length) are distinct ISO properties'],
    ['OAL_DISPLAY', 'product_core', 'OAL (L)', null, null, 'N', null, null, null, 0, 'oal_in_display', null],
    ['OAL_IN', 'product_core', 'OAL (L) in', 'OAL', 'ISO 13399', 'Y', 'INCH', 'INH', 6, 1, null, 'Generated: OAL / 25.4'],
    ['LCF', 'product_core', 'LOC (L₁)', 'LCF', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1,
     'loc_in_display,loc_m_display,flutelength', 'LCF is Length chip flute'],
    ['LCF_DISPLAY', 'product_core', 'LOC (L₁)', null, null, 'N', null, null, null, 0, 'loc_in_display', null],
    ['LCF_IN', 'product_core', 'LOC (L₁) in', 'LCF', 'ISO 13399', 'Y', 'INCH', 'INH', 6, 1, null, 'Generated: LCF / 25.4'],
    ['RE', 'product_core', 'Radius (R)', 'RE', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'radius_in_display,radius_m_display', null],
    ['RE_DISPLAY', 'product_core', 'Radius (R)', null, null, 'N', null, null, null, 0, 'radius_in_display', null],
    ['RE_IN', 'product_core', 'Radius (R) in', 'RE', 'ISO 13399', 'Y', 'INCH', 'INH', 6, 1, null, 'Generated: RE / 25.4'],
    ['LPR', 'product_core', 'Reach (L₂)', 'LPR', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'reach_in_display,reach_m_display', 'Protruding length'],
    ['LPR_DISPLAY', 'product_core', 'Reach (L₂)', null, null, 'N', null, null, null, 0, 'reach_in_display', null],
    ['LPR_IN', 'product_core', 'Reach (L₂) in', 'LPR', 'ISO 13399', 'Y', 'INCH', 'INH', 6, 1, null, 'Generated: LPR / 25.4'],
    ['SIG', 'product_core', 'Incl. Angle', 'SIG', 'ISO 13399', 'Y', 'DEG', 'DD', 4, 1, 'angle_display', 'Point angle'],
    ['NOF', 'product_core', 'Flutes', 'NOF', 'ISO 13399', 'Y', 'COUNT', 'C62', 0, 0, 'flutes', 'NOF is Flute count; ZEFP is the effective cutting-edge count'],
    ['FHA', 'product_core', 'Helix', 'FHA', 'ISO 13399', 'Y', 'DEG', 'DD', 4, 1, 'helix', 'Flute helix angle'],
    ['FHA_DISPLAY', 'product_core', 'Helix', null, null, 'N', null, null, null, 0, 'helix', 'Non-numeric specs with no angle equivalent: Variable (855 rows), N (81)'],

    // ---- milling_attributes ----
    ['STEP_DC', 'milling_attributes', 'D₂', null, null, 'N', 'MM', 'MMT', 4, 1, 'step_dia', null],
    ['SDL_1', 'milling_attributes', 'L₁', 'SDL', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'l1', 'Step diameter length'],
    ['SDL_2', 'milling_attributes', 'L₂', 'SDL', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'l2', 'Step diameter length'],
    ['CHW', 'milling_attributes', 'Chamfer Width', 'CHW', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'chamfer', 'Corner chamfer width; the milling sense of legacy `chamfer`'],
    ['ULDR', 'milling_attributes', 'L/D Ratio', 'ULDR', 'ISO 13399', 'Y', 'RATIO', 'C62', 4, 0, 'ldr', 'Usable length diameter ratio - dimensionless, so not a length dimension'],
    ['HAS_WELDON_FLAT', 'milling_attributes', 'Weldon Flat', null, null, 'N', null, null, null, 0, 'weldon', 'No ISO 13399 code; WT is Weight of item'],
    ['DRIVE_TYPE', 'milling_attributes', 'Torx Type', null, null, 'N', null, null, null, 0, 'torx_type', null],

    // ---- holemaking_attributes ----
    ['PILOT_DC', 'holemaking_attributes', 'Pilot (D)', null, null, 'N', 'MM', 'MMT', 4, 1, 'pilot_dia', null],
    ['DMIN', 'holemaking_attributes', 'Min Bore', 'DMIN', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'min_bore', 'Minimum bore diameter'],
    ['BORE_MAX', 'holemaking_attributes', 'Max Bore', null, null, 'N', 'MM', 'MMT', 4, 1, 'max_bore', 'No verified ISO code for the maximum'],
    ['LPR', 'holemaking_attributes', 'Projection', 'LPR', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'projection', 'Protruding length'],
    ['HAS_DRILL_POINT', 'holemaking_attributes', 'Drill Point', null, null, 'N', null, null, null, 0, 'drillpoint', 'Legacy value is Yes/No, not a point type'],
    ['PAC_DRILL_SIZE', 'holemaking_attributes', 'Pac Drill Size', null, null, 'N', null, null, null, 0, 'pac_drill_size', null],
    ['DRILL_GAUGE', 'holemaking_attributes', 'Drill Gauge', null, null, 'N', null, null, null, 0, 'cut_dia_in_display,diasize', 'Letter/number sizes such as #52, recovered from the diameter columns'],
    ['SHANK_THREAD_TDZ', 'holemaking_attributes', 'Shank Thread', 'TDZ', 'ISO 13399', 'Y', null, null, null, 0, 'thread', 'Legacy `thread` holds a size (1/4-28), not a flag'],

    // ---- threading_attributes ----
    ['TAP_CATEGORY', 'threading_attributes', 'Tap Category', null, null, 'N', null, null, null, 0, 'taps_sub_cat', 'Differs from SUB_SUB_TYPE in all 5,055 rows, so preserved separately'],
    ['TDZ', 'threading_attributes', 'Thread Size', 'TDZ', 'ISO 13399', 'Y', null, null, null, 0, 'taps_thread_size,cut_dia_in_display', 'Thread diameter size'],
    ['NOMINAL_SIZE', 'threading_attributes', 'Nominal Size', null, null, 'N', null, null, null, 0, 'cut_dia_in_display', 'Nominal portion of the designation (#8 where TDZ is 8-32); complementary to TDZ, not a duplicate'],
    ['PIPE_TDZ', 'threading_attributes', 'Pipe Size', 'TDZ', 'ISO 13399', 'Y', null, null, null, 0, 'taps_pipe_size,taps_pipe_tap_size', null],
    ['TPI', 'threading_attributes', 'TPI', 'TPI', 'ISO 13399', 'Y', 'COUNT', 'C62', 4, 0, 'tpi', 'Threads per inch'],
    ['THFT', 'threading_attributes', 'Thread Classification', 'THFT', 'ISO 13399', 'Y', null, null, null, 0, 'pitch_classification', 'Form type: UNC/UNF/UNS/M (ISO 68-1)'],
    ['DN', 'threading_attributes', 'Neck Dia.', 'DN', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'neck_dia_in_display',
     'All 52 populated legacy rows are taps, none milling, so it lives here rather than in milling_attributes'],
    ['THREAD_DIRECTION', 'threading_attributes', 'Thread Direction', null, null, 'N', null, null, null, 0, 'thread_direction', 'No hand-of-cut code exists in the ISO 13399 dictionary'],
    ['TCTR', 'threading_attributes', 'Thread Limit', 'TCTR', 'ISO 13399', 'Y', null, null, null, 0, 'taps_thread_limit', 'Thread tolerance class'],
    ['CLASS_OF_FIT', 'threading_attributes', 'Class of Fit', null, null, 'N', null, null, null, 0, 'taps_class_of_fit', null],
    ['THCHT', 'threading_attributes', 'Chamfer', 'THCHT', 'ISO 13399', 'Y', null, null, null, 0, 'taps_chamfer_type', 'Threading chamfer type; ISO 529 form'],
    ['CHAMFER_LEAD_THREADS', 'threading_attributes', 'Chamfer Lead', null, null, 'N', 'COUNT', 'C62', 4, 0, 'chamfer', 'Threads of lead; the threading sense of legacy `chamfer`'],
    ['STANDARD_REF', 'threading_attributes', 'Standard', null, null, 'N', null, null, null, 0, 'taps_standard,oal_in_display,shank_dia_in_display', 'ANSI / DIN 40430 values recovered from misfiled dimension columns'],
    ['TAP_DRILL_MIN', 'threading_attributes', 'Min Tap/Drill Size', null, null, 'N', 'MM', 'MMT', 4, 1, 'taps_min_tap_drill_size', null],
    ['TAP_DRILL_MAX', 'threading_attributes', 'Max Tap/Drill Size', null, null, 'N', 'MM', 'MMT', 4, 1, 'taps_max_tap_drill_size', 'Takes the upper bound where the legacy value was a range'],
    ['THLGTH', 'threading_attributes', 'Thread Len. (L₂)', 'THLGTH', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'taps_thread_length', null],
    ['DRILL_LENGTH', 'threading_attributes', 'Drill Len. (L₁)', null, null, 'N', 'MM', 'MMT', 4, 1, 'taps_drill_length', null],
    ['SQUARE_SIZE', 'threading_attributes', 'Square Size (D)', null, null, 'N', 'MM', 'MMT', 4, 1, 'taps_square', 'No ISO code'],
    ['DCX', 'threading_attributes', 'OD', 'DCX', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'taps_od1', 'Cutting diameter maximum'],
    ['CNSC', 'threading_attributes', 'Coolant Duct', 'CNSC', 'ISO 13399', 'Y', null, null, null, 0, 'taps_coolant_duct_type', 'Coolant entry style code'],
    ['GAGE_MEMBER', 'threading_attributes', 'Go / NoGo', null, null, 'N', null, null, null, 0, 'taps_go_nogo', null],
    ['HAS_HANDLE', 'threading_attributes', 'Handle', null, null, 'N', null, null, null, 0, 'taps_taps_handle', null],
    ['GAGE_BODY', 'threading_attributes', 'Gage Body', null, null, 'N', null, null, null, 0, 'taps_body', null],
    ['GAGE_DEPTH', 'threading_attributes', 'Gage Depth', null, null, 'N', 'MM', 'MMT', 4, 1, 'taps_depth', null],
    ['GAGE_DIA', 'threading_attributes', 'Gage Diameter', null, null, 'N', 'MM', 'MMT', 4, 1, 'taps_dia', null],
    ['PACKAGE_SIZE', 'threading_attributes', 'Package Size', null, null, 'N', null, null, null, 0, 'taps_amount', 'Container size for tapping fluids (1 OZ .. 55 GAL); not a tool dimension'],
    ['TAP_SIZE_RANGE', 'threading_attributes', 'Tap Size Range', null, null, 'N', null, null, null, 0, 'taps_size', 'Range a wrench or holder covers, e.g. M4/M6'],
    ['ACCEPTS_TAP_SIZE', 'threading_attributes', 'Accepts Tap Size', null, null, 'N', null, null, null, 0, 'taps_tap_size', 'Single tap size a holder accepts; kept because filters_config still references it'],

    // ---- inserts_attributes ----
    ['ISO_DESIGNATION', 'inserts_attributes', 'ISO Code', null, 'ISO 1832', 'Y', null, null, null, 0, 'iso_code', 'e.g. RNGN-090300'],
    ['SHAPE_CODE', 'inserts_attributes', 'Shape', null, 'ISO 1832', 'Y', null, null, null, 0, 'shape', 'Legacy `designation` is unusable: it holds the series number on 17 rows'],
    ['IC', 'inserts_attributes', 'I.C.', 'IC', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'ic', 'Inscribed circle diameter'],
    ['S', 'inserts_attributes', 'Thickness', 'S', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'thickness', 'Insert thickness'],
    ['W1', 'inserts_attributes', 'Width', 'W1', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'width', 'Insert width'],
    ['MOUNTING_STYLE', 'inserts_attributes', 'Mounting', null, null, 'N', null, null, null, 0, 'mounting', 'Flat Top / V Bottom / With Hole / Dimple'],

    // ---- specialty_attributes ----
    ['BURR_DESIGNATION', 'specialty_attributes', 'Tool', null, 'ANSI B94.53', 'Y', null, null, null, 0, 'tool', 'Burr designation such as SA-61; ANSI, not ISO'],
    ['CUT_STYLE', 'specialty_attributes', 'Cut Type', null, null, 'N', null, null, null, 0, 'c2', 'Text rows of legacy c2; its part-number rows are C2 grade variants'],
    ['SPLIT_LENGTH', 'specialty_attributes', 'Split Length (L₁)', null, null, 'N', 'MM', 'MMT', 4, 1, 'split', 'Legacy `split` is a packed length, not a category'],
    ['HTH', 'specialty_attributes', 'Height', 'HTH', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'height', null],
    ['S', 'specialty_attributes', 'Thickness', 'S', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'thickness', null],
    ['W1', 'specialty_attributes', 'Width', 'W1', 'ISO 13399', 'Y', 'MM', 'MMT', 4, 1, 'width', null],
];

/**
 * Per-tool-type and per-series label overrides, lifted from the duplicated switch
 * statements in assets/js/tool-filter.js. TOOL_TYPE/SERIES '' means "applies to
 * all"; lookup precedence is exact SERIES, then exact TOOL_TYPE, then ''.
 */
$OVERRIDES = [
    // [COLUMN_NAME, TOOL_TYPE, SERIES, LABEL]
    ['DC',          'THREADING',  '',     'Cutter Dia.'],
    ['DC_DISPLAY',  'THREADING',  '',     'Cutter Dia.'],
    ['LCF',         'HOLEMAKING', '',     'Flute Length'],
    ['LCF_DISPLAY', 'HOLEMAKING', '',     'Flute Length'],
    ['LCF',         '',           '187',  'Neck Len.'],
    ['LCF',         '',           '189',  'Neck Len.'],
    ['LCF',         '',           '189M', 'Neck Len.'],
    ['TDZ',         '',           '187',  'TPI Range'],
];

$conn = mig_connect();

/**
 * Rows that follow mechanically from the DDL are derived from it rather than
 * hand-listed, so the dictionary cannot fall out of step with the tables: the
 * per-table PART key, and a _DISPLAY companion for every dimension that has one.
 */
$ddlColumns = [];
$r = $conn->query("SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.COLUMNS
                   WHERE TABLE_SCHEMA = DATABASE()
                     AND TABLE_NAME IN ('product_core','"
                     . implode("','", array_values(MIG_TYPE_TABLE)) . "')");
while ($x = $r->fetch_row()) {
    $ddlColumns[$x[0] . '.' . $x[1]] = true;
}

$declared = [];
foreach ($D as $d) {
    $declared[$d[1] . '.' . $d[0]] = true;
}

$derived = [];
foreach (array_unique(array_values(MIG_TYPE_TABLE)) as $t) {
    if (!isset($declared["$t.PART"])) {
        $derived[] = ['PART', $t, 'Part', null, null, 'N', null, null, null, 0, 'part',
                      'Primary key and foreign key to product_core.PART'];
    }
}
foreach ($D as $d) {
    $companion = $d[0] . '_DISPLAY';
    $key = $d[1] . '.' . $companion;
    if ($d[9] === 1 && isset($ddlColumns[$key]) && !isset($declared[$key])) {
        $derived[] = [$companion, $d[1], $d[2], null, null, 'N', null, null, null, 0,
                      $d[10], 'Human form of ' . $d[0] . '; the decimal is split out into ' . $d[0]];
        $declared[$key] = true;
    }
}
$derived[] = ['NOTES', 'armory_attributes', 'Notes', null, null, 'N', null, null, null, 0,
              null, 'Free text; Armory has no type-specific attributes beyond product_core'];

$D = array_merge($D, $derived);
fwrite(STDERR, 'derived ' . count($derived) . " rows from the DDL\n");

$conn->query('DELETE FROM attribute_dictionary');

$sql = 'INSERT INTO attribute_dictionary
        (COLUMN_NAME, HOST_TABLE, TOOL_TYPE, SERIES, DISPLAY_LABEL, ISO_CODE, ISO_STANDARD,
         CODE_VERIFIED, CANONICAL_UNIT, UNIT_CODE, DECIMALS, IS_DIMENSION, SORT_ORDER,
         LEGACY_COLUMN_NAME, WOO_ATTRIBUTE, NOTES)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
$stmt = $conn->prepare($sql);
if (!$stmt) {
    fwrite(STDERR, 'FATAL: ' . $conn->error . "\n");
    exit(1);
}

$order = 0;
$count = 0;
foreach ($D as $d) {
    [$col, $host, $label, $iso, $std, $verified, $unit, $unitCode, $dec, $isDim, $legacy, $notes] = $d;

    // Prefer the label the site already uses, keyed by the first legacy column.
    $firstLegacy = $legacy === null ? null : explode(',', $legacy)[0];
    if ($firstLegacy !== null && isset($labels[$firstLegacy]['label'])) {
        $label = $labels[$firstLegacy]['label'];
    }
    $woo = ($firstLegacy !== null && !empty($wooAttrs[$firstLegacy])) ? $wooAttrs[$firstLegacy] : null;

    $order += 10;
    $tt = '';
    $se = '';
    $stmt->bind_param('ssssssssssiiisss',
        $col, $host, $tt, $se, $label, $iso, $std, $verified, $unit, $unitCode,
        $dec, $isDim, $order, $legacy, $woo, $notes);
    if (!$stmt->execute()) {
        fwrite(STDERR, "FATAL: $col: " . $stmt->error . "\n");
        exit(1);
    }
    $count++;
}

// Overrides inherit everything but the label from their base row.
foreach ($OVERRIDES as [$col, $tt, $se, $label]) {
    $base = null;
    foreach ($D as $d) {
        if ($d[0] === $col) {
            $base = $d;
            break;
        }
    }
    if ($base === null) {
        fwrite(STDERR, "WARN: override for unknown column $col - skipped\n");
        continue;
    }
    [$c, $host, , $iso, $std, $verified, $unit, $unitCode, $dec, $isDim, $legacy, $notes] = $base;
    $order += 10;
    $note = 'Label override lifted from assets/js/tool-filter.js';
    $woo  = null;
    $stmt->bind_param('ssssssssssiiisss',
        $c, $host, $tt, $se, $label, $iso, $std, $verified, $unit, $unitCode,
        $dec, $isDim, $order, $legacy, $woo, $note);
    if (!$stmt->execute()) {
        fwrite(STDERR, "FATAL: override $col: " . $stmt->error . "\n");
        exit(1);
    }
    $count++;
}

$stmt->close();
printf("attribute_dictionary rows: %d\n", $count);

$r = $conn->query("SELECT CODE_VERIFIED, COUNT(*) c FROM attribute_dictionary GROUP BY CODE_VERIFIED");
while ($x = $r->fetch_assoc()) {
    printf("  CODE_VERIFIED=%s : %d\n", $x['CODE_VERIFIED'], $x['c']);
}
