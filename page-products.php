<?php
// Products Page Template (the catalog overview at /products)
// Description: Renders the catalog's Product Index (Category > Material > Shape > Line Item) as a
// single filterable page per category (progressive drill-down menu + tile grid), matching the
// pattern used on /all-series/. A line item can belong to more than one material (e.g. a tool
// rated for both Steel and Cast Iron), so each tile is tagged with ALL of its material slugs
// (pi_materials) plus its shape (pi_shape, from master_series_data.tool_sub_type) and its single
// line-item slug (pi_item). The drill-down filters the grid client-side via Isotope's compound
// class selectors. Categories are added here as their views/woo/product-index-{category}.php data
// files are built.
//
// This intentionally does not touch the WooCommerce/FacetWP category archives at
// /products/{category}/ (e.g. /products/milling/) -- those "Tool Filter" pages are a separate,
// standalone browsing path and are unaffected by this overview page.

include 'db_connection.php';

function gws_pi_slugify($value) {
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    return trim($value, '-');
}

function gws_product_index_category($conn, $data_file) {
    $data = include $data_file;

    // series -> ['materials' => [material_slug, ...], 'item' => item_slug], built from the compiled mapping
    $lookup = [];
    foreach ($data['materials'] as $material) {
        foreach ($material['items'] as $item) {
            foreach ($item['series'] as $series_code) {
                if (!isset($lookup[$series_code])) {
                    $lookup[$series_code] = ['materials' => [], 'item' => $item['slug']];
                }
                if (!in_array($material['material_slug'], $lookup[$series_code]['materials'], true)) {
                    $lookup[$series_code]['materials'][] = $material['material_slug'];
                }
            }
        }
    }

    $series_codes = array_keys($lookup);
    $series_list = [];
    $shape_by_series = [];
    if ($series_codes) {
        $placeholders = implode(',', array_fill(0, count($series_codes), '?'));
        $types = str_repeat('s', count($series_codes));
        $stmt = $conn->prepare("SELECT * FROM `master_series_data` WHERE series IN ($placeholders) AND `catalog` = 'Y' ORDER BY `order`");
        $stmt->bind_param($types, ...$series_codes);
        $stmt->execute();
        $result = $stmt->get_result();
        $series_list = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($series_list as $series) {
            $shape_by_series[$series['series']] = $series['tool_sub_type'];
        }

        foreach ($series_list as &$series) {
            $series['pi_materials'] = $lookup[$series['series']]['materials'];
            $series['pi_item'] = $lookup[$series['series']]['item'];
            $series['pi_shape'] = gws_pi_slugify($shape_by_series[$series['series']]);
        }
        unset($series);
    }

    // Group each material's items by shape (master_series_data.tool_sub_type), so the menu can
    // drill down Material > Shape > Line Item instead of dumping every item under a material at
    // once. Categories marked 'flat' (e.g. Miscellaneous, where "material" is really just the
    // catalog's sub-category) skip this -- their menu is one level deep: material > item.
    if (empty($data['flat'])) {
        foreach ($data['materials'] as &$material) {
            $shapes = [];
            foreach ($material['items'] as $item) {
                $first_series = $item['series'][0] ?? null;
                $shape_label = $first_series ? ($shape_by_series[$first_series] ?? 'Other') : 'Other';
                $shape_slug = gws_pi_slugify($shape_label);
                if (!isset($shapes[$shape_slug])) {
                    $shapes[$shape_slug] = ['shape' => $shape_label, 'shape_slug' => $shape_slug, 'items' => []];
                }
                $shapes[$shape_slug]['items'][] = $item;
            }
            $material['shapes'] = array_values($shapes);
        }
        unset($material);
    }

    $data['series_list'] = $series_list;
    return $data;
}

// Inserts, Burrs, and Armory Tools have no material/shape breakdown of their own -- each is just
// every catalog='Y' series for a tool_type (optionally narrowed to one tool_sub_type), no drill-down.
function gws_product_index_flat_category($conn, $tool_type, $category, $category_slug, $description, $tool_sub_type = null) {
    if ($tool_sub_type) {
        $stmt = $conn->prepare("SELECT * FROM `master_series_data` WHERE tool_type = ? AND tool_sub_type = ? AND `catalog` = 'Y' ORDER BY `order`");
        $stmt->bind_param('ss', $tool_type, $tool_sub_type);
    } else {
        $stmt = $conn->prepare("SELECT * FROM `master_series_data` WHERE tool_type = ? AND `catalog` = 'Y' ORDER BY `order`");
        $stmt->bind_param('s', $tool_type);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    return [
        'category' => $category,
        'category_slug' => $category_slug,
        'description' => $description,
        'materials' => [],
        'series_list' => $result->fetch_all(MYSQLI_ASSOC),
    ];
}

$context = Timber::context();

$context['product_index_categories'] = [
    gws_product_index_category($conn, get_template_directory() . '/views/woo/product-index-milling.php'),
    gws_product_index_category($conn, get_template_directory() . '/views/woo/product-index-holemaking.php'),
    gws_product_index_category($conn, get_template_directory() . '/views/woo/product-index-threading.php'),
    gws_product_index_flat_category(
        $conn,
        'INSERTS',
        'Inserts',
        'inserts',
        'GWS Tool Group inserts cover PCD, PCBN, and ceramic grades engineered for high-performance turning and milling applications.'
    ),
    gws_product_index_flat_category(
        $conn,
        'SPECIALTY',
        'Burrs',
        'burrs',
        'GWS Tool Group burrs cover a full range of shapes and cuts for deburring, deflashing, and weld prep across ferrous and non-ferrous materials.',
        'Burrs'
    ),
    gws_product_index_flat_category(
        $conn,
        'SPECIALTY',
        'Armory',
        'armory',
        'Custom solid carbide tooling engineered for defense manufacturing -- charging handles, receivers, barrel nuts, and other defense-system-specific applications.',
        'Armory'
    ),
    gws_product_index_category($conn, get_template_directory() . '/views/woo/product-index-miscellaneous.php'),
];

Timber::render('page-products.twig', $context);
