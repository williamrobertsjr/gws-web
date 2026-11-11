<?php
/**
 * SEO titles, meta descriptions, canonical and schema for part and series pages.
 *
 * Yoast SEO still prints every tag. This file only supplies the values through
 * Yoast's filters, computed from master_product_data / master_series_data at
 * render time (per URL, so Breeze page caching stays safe).
 *
 * Series pages: template-series.php calls gws_seo_register_series($series_data).
 * Part pages:   hooks below register themselves on is_singular('product').
 */

const GWS_SEO_TITLE_MAX = 60;
const GWS_SEO_DESC_MAX  = 155;

// ─────────────────────────────────────────────────────────────
// Helpers
// ─────────────────────────────────────────────────────────────

/** Title-case a DB label like "MILLING" or "Square and Radius". */
function gws_seo_label($s) {
    $s = trim((string) $s);
    if ($s === '' || strtoupper($s) === 'NULL') return '';
    return $s === strtoupper($s) ? ucwords(strtolower($s)) : $s;
}

/** Fix double-encoded UTF-8 ("Ã\u0098" -> "Ø") and flatten pipe-delimited labels. */
function gws_seo_clean_text($s) {
    $s = html_entity_decode(wp_strip_all_tags((string) $s), ENT_QUOTES, 'UTF-8');
    if (strpos($s, "\xC3\x83") !== false) {
        $fixed = @mb_convert_encoding($s, 'ISO-8859-1', 'UTF-8');
        if ($fixed !== false && mb_check_encoding($fixed, 'UTF-8')) $s = $fixed;
    }
    $s = preg_replace('/\s*\|\s*/', ', ', $s);
    $s = preg_replace('/\s+/', ' ', $s);
    return trim($s, " ,");
}

/** Cut to $max chars on a word boundary, adding an ellipsis only if cut. */
function gws_seo_truncate($s, $max) {
    if (mb_strlen($s) <= $max) return $s;
    $cut = mb_substr($s, 0, $max - 1);
    $cut = preg_replace('/\s+\S*$/u', '', $cut);
    return rtrim($cut, " ,.;:-") . '…';
}

/** Join title segments with " | ", dropping optional segments until it fits. */
function gws_seo_fit_title(array $required, array $optional_drop_first) {
    $site = get_bloginfo('name');
    $opt  = array_values(array_filter($optional_drop_first));
    while (true) {
        $title = implode(' | ', array_merge($required, $opt, [$site]));
        if (mb_strlen($title) <= GWS_SEO_TITLE_MAX || !$opt) return $title;
        array_pop($opt);
    }
}

function gws_seo_series_url($series) {
    return home_url('/series-' . rawurlencode(str_replace(' ', '-', $series)) . '/');
}

// ─────────────────────────────────────────────────────────────
// Series pages
// ─────────────────────────────────────────────────────────────

/** Called from template-series.php once the series row is loaded. */
function gws_seo_register_series(array $s) {
    $series  = (string) $s['series'];
    $prefix  = ($s['family'] ?? '') === 'Armory' ? '' : 'Series ';
    $name    = $prefix . $series;
    $subtitle = gws_seo_clean_text($s['subtitle'] ?? '');
    $brand   = gws_seo_label($s['brand'] ?? '');
    $type    = gws_seo_label($s['tool_type'] ?? '');
    $sub     = gws_seo_label($s['tool_sub_type'] ?? '');

    // "Series 1000 Carbide Unground Square Blanks | Round Tools | GWS Tool Group"
    $head  = $subtitle !== '' && mb_strlen($subtitle) <= 40 ? "$name $subtitle" : $name;
    $title = gws_seo_fit_title([$head], [$brand]);

    $kind = trim($sub . (strcasecmp($sub, $type) === 0 ? '' : ' ' . $type));
    $base = gws_seo_clean_text($subtitle !== '' ? $subtitle : ($s['series_description'] ?? ''));
    if ($base === '') $base = $kind !== '' ? ($brand !== '' && strcasecmp($brand, $type) !== 0 ? "$brand " : '') . $kind : '';
    $desc = trim("$name: " . ($base !== '' ? rtrim($base, '.') . '.' : ''));
    $desc .= ' Browse sizes, specs and pricing at GWS Tool Group.';
    $desc = gws_seo_truncate($desc, GWS_SEO_DESC_MAX);

    $url = gws_seo_series_url($series);

    add_filter('pre_get_document_title', fn() => $title, 99);
    add_filter('wpseo_title', fn() => $title, 99);
    add_filter('wpseo_opengraph_title', fn() => $title, 99);
    add_filter('wpseo_metadesc', fn() => $desc, 99);
    add_filter('wpseo_opengraph_desc', fn() => $desc, 99);
    add_filter('wpseo_twitter_description', fn() => $desc, 99);
    add_filter('wpseo_canonical', fn() => $url, 99);
    add_filter('wpseo_opengraph_url', fn() => $url, 99);
}

// ─────────────────────────────────────────────────────────────
// Part (single product) pages
// ─────────────────────────────────────────────────────────────

/** Master row + series row for a SKU, cached for the request. */
function gws_seo_part_data($sku) {
    static $cache = [];
    if (isset($cache[$sku])) return $cache[$sku];
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare(
        "SELECT p.part, p.series, p.tool_type, p.sub_type, p.part_description, s.brand, s.family
         FROM master_product_data p
         LEFT JOIN master_series_data s ON s.series = p.series
         WHERE p.part = %s LIMIT 1",
        $sku
    ), ARRAY_A);
    return $cache[$sku] = $row ?: [];
}

function gws_seo_part_context() {
    if (!function_exists('is_singular') || !is_singular('product')) return null;
    $product = wc_get_product(get_queried_object_id());
    if (!$product) return null;
    $sku  = $product->get_sku() ?: $product->get_name();
    $d    = gws_seo_part_data($sku);
    $type = gws_seo_label($d['tool_type'] ?? $product->get_attribute('pa_tool-type'));
    $sub  = gws_seo_label($d['sub_type'] ?? $product->get_attribute('pa_sub-type'));
    return [
        'product' => $product,
        'sku'     => $sku,
        'series'  => trim((string) ($d['series'] ?? $product->get_attribute('pa_series'))),
        'brand'   => gws_seo_label($d['brand'] ?? $product->get_attribute('pa_brand')),
        'type'    => $type,
        'sub'     => $sub,
        'desc'    => gws_seo_clean_text($d['part_description'] ?? $product->get_short_description()),
    ];
}

add_filter('wpseo_title', function ($title) {
    $c = gws_seo_part_context();
    if (!$c) return $title;
    $kind = $c['sub'];
    if ($c['type'] !== '' && strcasecmp($c['type'], $c['sub']) !== 0) $kind = trim($c['sub'] . ' ' . $c['type']);
    $head = trim($c['sku'] . ' ' . $kind);
    return gws_seo_fit_title([$head], [$c['series'] !== '' ? 'Series ' . $c['series'] : '']);
}, 99);

$gws_seo_part_desc = function ($current) {
    $c = gws_seo_part_context();
    if (!$c) return $current;
    // Keep hand-written Yoast descriptions.
    $manual = get_post_meta($c['product']->get_id(), '_yoast_wpseo_metadesc', true);
    if (current_filter() === 'wpseo_metadesc' && $manual) return $current;

    $parts = [$c['sku'] . ($c['desc'] !== '' ? ': ' . $c['desc'] : '')];
    $line2 = trim(($c['brand'] !== '' ? $c['brand'] : 'GWS Tool Group') . ($c['series'] !== '' ? ', Series ' . $c['series'] : ''));
    $suffix = ' ' . $line2 . '. Request a quote.';
    $lead = rtrim($parts[0], '.');
    $room = GWS_SEO_DESC_MAX - mb_strlen($suffix) - 1;
    return gws_seo_truncate($lead, $room) . '.' . $suffix;
};
add_filter('wpseo_metadesc', $gws_seo_part_desc, 99);
add_filter('wpseo_opengraph_desc', $gws_seo_part_desc, 99);
add_filter('wpseo_twitter_description', $gws_seo_part_desc, 99);

add_filter('wpseo_opengraph_type', function ($type) {
    return is_singular('product') ? 'product' : $type;
});

// og:image from the catalog image the template already resolves.
add_filter('wpseo_opengraph_image', function ($img) {
    $c = gws_seo_part_context();
    if (!$c) return $img;
    $url = gws_seo_part_image_url($c['product'], $c['series']);
    return $url ?: $img;
});

function gws_seo_part_image_url($product, $series) {
    $base = get_template_directory_uri() . '/images/catalog_images/';
    $dir  = get_template_directory() . '/images/catalog_images/';
    $map  = function_exists('gws_get_sku_image_map') ? gws_get_sku_image_map() : [];
    $name = $map[$product->get_sku()] ?? null;
    if ($name && file_exists($dir . $name . '_catalog.png')) return $base . $name . '_catalog.png';
    if ($series !== '' && file_exists($dir . $series . '_catalog.png')) return $base . $series . '_catalog.png';
    return '';
}

// Product schema (no Offer/price: pricing is tiered and gated).
add_filter('wpseo_schema_graph', function ($graph) {
    $c = gws_seo_part_context();
    if (!$c || !is_array($graph)) return $graph;
    $url   = get_permalink($c['product']->get_id());
    $piece = [
        '@type' => 'Product',
        '@id'   => $url . '#product',
        'url'   => $url,
        'name'  => trim($c['sku'] . ' ' . $c['desc']),
        'sku'   => $c['sku'],
        'mpn'   => $c['sku'],
    ];
    if ($c['desc'] !== '')  $piece['description'] = $c['desc'];
    if ($c['brand'] !== '') $piece['brand'] = ['@type' => 'Brand', 'name' => $c['brand']];
    if ($c['type'] !== '')  $piece['category'] = trim($c['type'] . ($c['sub'] !== '' ? ' > ' . $c['sub'] : ''));
    $img = gws_seo_part_image_url($c['product'], $c['series']);
    if ($img) $piece['image'] = $img;
    $graph[] = $piece;
    return $graph;
}, 99);
