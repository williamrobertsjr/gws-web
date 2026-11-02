<?php
/**
 * Custom Quote (/custom-quote, Gravity Form GWS_CQ_FORM_ID).
 *
 * Single source of truth for the quote form's option lists and field IDs.
 * Tool types, series and coatings come from master_product_data /
 * master_series_data so the form only offers what we actually sell. The
 * one-off form builder and the "Modify a standard tool" lookup both use
 * these helpers, so their strings can't drift apart.
 */

require_once __DIR__ . '/part-number.php';

define('GWS_CQ_FORM_ID', 55);
define('GWS_CQ_SEP', ' — ');

// Category value => [label, tool_type, sub_types allowed (null = all), sub_sub_types omitted]
function gws_cq_categories() {
    return [
        'milling'    => ['Milling', 'MILLING', null, []],
        'holemaking' => ['Holemaking', 'HOLEMAKING', null, []],
        'threading'  => ['Threading', 'THREADING', ['Taps', 'Thread Mills', 'Drill/Taps', 'Spiral Flute'], ['Thread Mill']],
        'inserts'    => ['Inserts (Advanced Materials)', 'INSERTS', null, []],
        'specialty'  => ['Specialty', 'SPECIALTY', null, []],
    ];
}

// Gravity Form field IDs, per category where a field only applies to one.
function gws_cq_field_ids() {
    return [
        'category'   => 20,
        'mode'       => 30,
        'edp'        => 31,
        'units'      => 32,
        'changes'    => 33,
        'material'   => 12,
        'type'       => ['milling' => 21, 'holemaking' => 22, 'threading' => 23, 'inserts' => 24, 'specialty' => 25],
        'series'     => ['milling' => 41, 'holemaking' => 42, 'threading' => 43, 'inserts' => 44, 'specialty' => 45],
        'coating'    => ['milling' => 119, 'holemaking' => 140, 'threading' => 158, 'specialty' => 184],
        'operation'  => ['milling' => 122],
        // Prefill: field ID => master_product_data column. Dimension columns are
        // read as *_in_display or *_m_display depending on the part's units.
        'prefill'    => [
            'milling'    => [112 => 'cut_dia', 113 => 'radius', 114 => 'loc', 115 => 'reach', 116 => 'oal', 117 => 'shank_dia', 118 => '=flutes', 124 => '=helix'],
            'holemaking' => [131 => 'cut_dia', 136 => 'loc', 135 => 'oal', 137 => 'shank_dia', 138 => '=angle_display'],
            'threading'  => [151 => '=taps_thread_size', 152 => '=taps_class_of_fit'],
            'inserts'    => [171 => '=designation'],
            'specialty'  => [181 => 'cut_dia', 182 => 'oal', 183 => 'shank_dia'],
        ],
    ];
}

// Catalog page 12 ISO material groups, plus O (non-metals). Value = ISO code.
function gws_cq_materials() {
    return [
        'P1'    => ['Non-alloyed steel, free cutting steel, cast steel', '#084f9c'],
        'P2'    => ['Low and medium carbon steel, cast steel', '#084f9c'],
        'P3'    => ['High carbon steel, alloy steel, tool steel', '#084f9c'],
        'M1'    => ['Stainless steel', '#f0de0a'],
        'M2'    => ['PH stainless steel', '#f0de0a'],
        'K1'    => ['Grey cast iron', '#d30d2b'],
        'K2'    => ['Nodular cast iron', '#d30d2b'],
        'N1'    => ['Aluminum wrought alloy', '#2b9452'],
        'N2'    => ['Cast / high-silicon aluminum', '#2b9452'],
        'S1'    => ['High-temp alloys (Fe, Ni or Co based)', '#c79c6e'],
        'S2'    => ['Titanium and titanium alloys', '#c79c6e'],
        'H1'    => ['Hardened steel 45-55 HRC (H13, S7, A2)', '#9c9c9c'],
        'H2'    => ['Hardened steel 55-60 HRC (D2, CPM, M2)', '#9c9c9c'],
        'O'     => ['Non-metals: plastics, composites, graphite', '#000000'],
        'Other' => ['Other (describe in grade / condition)', ''],
    ];
}

// Operation tokens match master_series_data.app_icons so the series guide can filter on them.
function gws_cq_operations() {
    return [
        'milling'    => ['slotting' => 'Slotting', 'profile' => 'Profiling', 'pocketing' => 'Pocketing', 'ramp' => 'Ramping', 'helical' => 'Helical interpolation', 'contouring' => 'Contouring', 'highspeedmilling' => 'High-speed milling'],
        'holemaking' => ['drilling' => 'Drilling', 'through' => 'Through hole', 'blind' => 'Blind hole', 'countersinking' => 'Countersinking'],
        'threading'  => ['through' => 'Through hole', 'blind' => 'Blind hole', 'externalthread' => 'External thread'],
    ];
}

function gws_cq_category_for_tool_type($tool_type) {
    foreach (gws_cq_categories() as $value => $c) {
        if (strtoupper((string) $tool_type) === $c[1]) {
            return $value;
        }
    }
    return null;
}

/**
 * "Sub Type — Sub Sub Type" label as shown in the Tool Type dropdown, or null
 * when the category doesn't offer that sub_type.
 */
function gws_cq_tool_type_label($category, $sub_type, $sub_sub_type) {
    $cats = gws_cq_categories();
    if (!isset($cats[$category])) {
        return null;
    }
    [, , $allowed, $omit] = $cats[$category];
    $fold   = ['Spiral Flute' => 'Taps'];
    $labels = ['Boring bar' => 'Boring Bars'];

    $st = trim((string) $sub_type);
    if ($st === '' || ($allowed !== null && !in_array($st, $allowed, true))) {
        return null;
    }
    $st  = $fold[$st] ?? $st;
    $st  = $labels[$st] ?? $st;
    $sst = trim((string) $sub_sub_type);
    if ($sst === '' || in_array($sst, $omit, true) || stripos($st, $sst) !== false) {
        return $st;
    }
    return $st . GWS_CQ_SEP . $sst;
}

function gws_cq_tool_type_choices($category) {
    global $wpdb;
    $tool_type = gws_cq_categories()[$category][1];
    $rows = $wpdb->get_results($wpdb->prepare(
        "SELECT DISTINCT sub_type, sub_sub_type FROM master_product_data WHERE UPPER(tool_type) = %s AND sub_type IS NOT NULL AND sub_type <> ''",
        $tool_type
    ));
    $opts = [];
    foreach ($rows as $r) {
        $label = gws_cq_tool_type_label($category, $r->sub_type, $r->sub_sub_type);
        if ($label !== null) {
            $opts[$label] = $label;
        }
    }
    ksort($opts, SORT_NATURAL | SORT_FLAG_CASE);
    return array_values($opts);
}

// Coatings/finishes we sell for this category, most common first. Bright = uncoated.
function gws_cq_coating_label($coating) {
    $coating = trim((string) $coating);
    return $coating === 'Bright' ? 'Bright (Uncoated)' : $coating;
}

function gws_cq_coating_choices($category) {
    global $wpdb;
    $tool_type = gws_cq_categories()[$category][1];
    $rows = $wpdb->get_col($wpdb->prepare(
        "SELECT coating FROM master_product_data
         WHERE UPPER(tool_type) = %s AND web = 'Y' AND coating IS NOT NULL AND TRIM(coating) <> ''
         GROUP BY coating ORDER BY COUNT(*) DESC",
        $tool_type
    ));
    return array_merge(array_map('gws_cq_coating_label', $rows), ['Other', 'Not sure']);
}

function gws_cq_series_image($series) {
    $dir = get_template_directory() . '/images/catalog_images/';
    foreach ([strtolower(str_replace(' ', '_', $series)), $series] as $name) {
        if (file_exists($dir . $name . '_catalog.png')) {
            return get_template_directory_uri() . '/images/catalog_images/' . rawurlencode($name) . '_catalog.png';
        }
    }
    return '';
}

/**
 * Series offered per category (series with at least one web product), with
 * their ISO material ratings and operations for the series guide.
 */
function gws_cq_series($category) {
    global $wpdb;
    $tool_type = gws_cq_categories()[$category][1];
    $rows = $wpdb->get_results($wpdb->prepare(
        "SELECT s.series, s.tool_sub_type, s.subtitle, s.app_icons,
                s.p1, s.p2, s.p3, s.m1, s.m2, s.k1, s.k2, s.n1, s.n2, s.s1, s.s2, s.h1, s.h2, s.o
         FROM master_series_data s
         WHERE UPPER(s.tool_type) = %s
           AND EXISTS (SELECT 1 FROM master_product_data p WHERE p.series = s.series AND p.web = 'Y')
         ORDER BY s.series",
        $tool_type
    ));
    $out = [];
    foreach ($rows as $r) {
        $ratings = [];
        foreach (['p1', 'p2', 'p3', 'm1', 'm2', 'k1', 'k2', 'n1', 'n2', 's1', 's2', 'h1', 'h2', 'o'] as $col) {
            if (in_array($r->$col, ['best', 'good'], true)) {
                $ratings[strtoupper($col)] = $r->$col;
            }
        }
        $out[] = [
            'series'   => $r->series,
            'subType'  => $r->tool_sub_type,
            'subtitle' => trim((string) $r->subtitle),
            'ops'      => array_values(array_filter(array_map('trim', explode(',', (string) $r->app_icons)))),
            'ratings'  => $ratings,
            'image'    => gws_cq_series_image($r->series),
        ];
    }
    return $out;
}

function gws_cq_series_label(array $s) {
    return $s['subtitle'] !== '' ? $s['series'] . GWS_CQ_SEP . $s['subtitle'] : $s['series'];
}

/**
 * Display values look like "(.1250) 1/8" (inch) or "(.1575) 4.0mm" (metric).
 * Inch parts take the decimal in parentheses; metric parts take the mm value.
 * Anything we can't read confidently comes back blank rather than wrong.
 */
function gws_cq_parse_dimension($value, $metric) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }
    if (preg_match('/^\(\s*([\d.]+)\s*\)\s*(.*)$/', $value, $m)) {
        if ($metric) {
            return preg_match('/^([\d.]+)\s*mm$/i', trim($m[2]), $mm) ? $mm[1] : '';
        }
        return $m[1][0] === '.' ? '0' . $m[1] : $m[1];
    }
    if ($metric) {
        return preg_match('/^([\d.]+)\s*mm$/i', $value, $mm) ? $mm[1] : '';
    }
    return preg_match('/^[\d.]+$/', $value) ? $value : '';
}

/**
 * Look up a standard part for "Modify a standard tool". Returns the category,
 * tool type, series, units and a field ID => value prefill map.
 */
function gws_cq_lookup_tool($edp) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM master_product_data WHERE REPLACE(REPLACE(UPPER(part), '-', ''), ' ', '') = %s LIMIT 1",
        gws_normalize_part_number($edp)
    ), ARRAY_A);
    if (!$row) {
        return ['found' => false, 'message' => "We couldn't find that EDP. Choose Design a special instead, or upload a print."];
    }

    $category = gws_cq_category_for_tool_type($row['tool_type']);
    $type     = $category ? gws_cq_tool_type_label($category, $row['sub_type'], $row['sub_sub_type']) : null;
    if (!$category || $type === null) {
        return [
            'found'   => false,
            'part'    => $row['part'],
            'message' => 'We found ' . $row['part'] . ", but it isn't a tool type we quote as a modification. Describe what you need in Notes or upload a print.",
        ];
    }

    $ids    = gws_cq_field_ids();
    $metric = strcasecmp((string) $row['measurement'], 'Metric') === 0;
    $suffix = $metric ? '_m_display' : '_in_display';

    $prefill = [];
    foreach ($ids['prefill'][$category] as $field_id => $col) {
        if ($col[0] === '=') {
            $val = trim((string) ($row[substr($col, 1)] ?? ''));
            $val = ($val === '0') ? '' : $val;
        } else {
            $val = gws_cq_parse_dimension($row[$col . $suffix] ?? '', $metric);
        }
        if ($val !== '') {
            $prefill[$field_id] = $val;
        }
    }

    // The designation column holds only the shape (RNGN); the full ISO code
    // (RNGN-45-T2A) leads the part description.
    $designation_field = array_search('=designation', $ids['prefill'][$category], true);
    if ($designation_field && isset($prefill[$designation_field])) {
        $first = strtok(trim((string) $row['part_description']), ' ');
        if ($first && stripos($first, $prefill[$designation_field]) === 0) {
            $prefill[$designation_field] = $first;
        }
    }

    if (isset($ids['coating'][$category]) && trim((string) $row['coating']) !== '') {
        $coating = gws_cq_coating_label($row['coating']);
        $prefill[$ids['coating'][$category]] = in_array($coating, gws_cq_coating_choices($category), true) ? $coating : 'Other';
    }

    $series = '';
    foreach (gws_cq_series($category) as $s) {
        if ($s['series'] === $row['series']) {
            $series = $s['series'];
            break;
        }
    }

    return [
        'found'       => true,
        'part'        => $row['part'],
        'description' => trim((string) $row['part_description']),
        'category'    => $category,
        'typeField'   => $ids['type'][$category],
        'type'        => $type,
        'seriesField' => $ids['series'][$category],
        'series'      => $series,
        'image'       => gws_cq_series_image((string) $row['series']),
        'units'       => $metric ? 'Metric' : 'Imperial',
        'prefill'     => $prefill,
    ];
}

function gws_cq_register_routes() {
    register_rest_route('gws/v1', '/custom-quote/tool', [
        'methods'             => 'GET',
        'callback'            => function (WP_REST_Request $request) {
            return new WP_REST_Response(gws_cq_lookup_tool($request->get_param('edp')), 200);
        },
        'permission_callback' => '__return_true',
        'args'                => [
            'edp' => [
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => function ($value) {
                    return strlen(trim($value)) >= 3;
                },
            ],
        ],
    ]);
}
add_action('rest_api_init', 'gws_cq_register_routes');

/**
 * Static option data for assets/js/custom-quote.js (series guide, diagrams,
 * material swatches). Same for every visitor, so it's safe under page caching.
 */
function gws_cq_client_data() {
    $cached = get_transient('gws_cq_client_data');
    if ($cached !== false) {
        return $cached;
    }
    $series = [];
    foreach (array_keys(gws_cq_categories()) as $category) {
        $series[$category] = gws_cq_series($category);
    }
    $materials = [];
    foreach (gws_cq_materials() as $code => [$label, $color]) {
        $materials[] = ['code' => $code, 'label' => $label, 'color' => $color];
    }
    $data = [
        'restUrl'    => esc_url_raw(rest_url('gws/v1/custom-quote/tool')),
        'formId'     => GWS_CQ_FORM_ID,
        'fields'     => gws_cq_field_ids(),
        'materials'  => $materials,
        'operations' => gws_cq_operations(),
        'series'     => $series,
        'iconBase'   => get_template_directory_uri() . '/images/icons/',
    ];
    set_transient('gws_cq_client_data', $data, 6 * HOUR_IN_SECONDS);
    return $data;
}
