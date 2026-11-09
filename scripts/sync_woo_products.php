<?php
/**
 * Create/update WooCommerce products for master_product_data parts.
 *
 * Source of truth is the database (master_product_data, master_series_data and
 * master_price_data_jun_2026); WooCommerce is written as a one-way projection.
 * Re-runnable: products are matched on SKU and updated in place.
 *
 * Usage (from the WordPress root, so wp-cli boots the right site):
 *   wp eval-file wp-content/themes/gws-web/scripts/sync_woo_products.php CD CX DN          # dry run
 *   wp eval-file wp-content/themes/gws-web/scripts/sync_woo_products.php CD CX DN apply    # write
 *
 * What a product gets (mirrors the existing imported products):
 *   - simple product, title = part, SKU = part, short description = part_description
 *   - _regular_price / _price from the list price table
 *   - category chain  TOOL_TYPE > family > tool_sub_type > series
 *   - taxonomy attributes: pa_series, pa_tool-type, pa_sub-type, pa_family, pa_brand,
 *     pa_columns, pa_data-fields
 *   - one attribute per series data_fields column. Columns mapped to a pa_* taxonomy in
 *     inc/attribute-mapping.php become term attributes; columns mapped to a plain name
 *     become LOCAL attributes (no taxonomy, no terms) - used for continuous dimensions.
 *   - series meta copied onto the product (series_bullets, series_description, ...)
 *
 * Images are not touched: add the featured image in WooCommerce afterwards.
 */

if (!defined('ABSPATH') || !class_exists('WooCommerce')) {
    fwrite(STDERR, "Run through wp-cli: wp eval-file <this file> SERIES... [apply]\n");
    exit(1);
}

global $wpdb;
// wp eval-file hands the positional arguments to the file as a local $args.
$args   = isset($args) ? $args : [];
$apply  = in_array('apply', $args, true);
$series = array_values(array_filter($args, fn($a) => $a !== 'apply'));
if (!$series) {
    WP_CLI::error('Give at least one series code, e.g. CD CX DN');
}

$mapping = include get_template_directory() . '/inc/attribute-mapping.php';

/** Term id for $name in $taxonomy, creating it (under $parent) when missing. */
function gws_sync_term($name, $taxonomy, $parent = 0, $slugHint = null)
{
    $existing = get_terms(['taxonomy' => $taxonomy, 'name' => $name, 'parent' => $parent,
                           'hide_empty' => false, 'fields' => 'ids']);
    if (!is_wp_error($existing) && $existing) {
        return (int) $existing[0];
    }
    $opts = ['parent' => $parent];
    if ($slugHint) {
        $opts['slug'] = $slugHint;
    }
    $r = wp_insert_term($name, $taxonomy, $opts);
    if (is_wp_error($r)) {
        WP_CLI::error("Could not create $taxonomy term '$name': " . $r->get_error_message());
    }
    return (int) $r['term_id'];
}

function gws_sync_tax_attribute($taxonomy, $value, $position)
{
    $a = new WC_Product_Attribute();
    $a->set_id(wc_attribute_taxonomy_id_by_name($taxonomy));
    $a->set_name($taxonomy);
    $a->set_options([gws_sync_term($value, $taxonomy)]);
    $a->set_position($position);
    $a->set_visible(true);
    $a->set_variation(false);
    return $a;
}

$created = $updated = 0;

foreach ($series as $code) {
    $s = $wpdb->get_row($wpdb->prepare('SELECT * FROM master_series_data WHERE series = %s', $code), ARRAY_A);
    if (!$s) {
        WP_CLI::warning("Series $code not in master_series_data - skipped");
        continue;
    }
    $parts = $wpdb->get_results($wpdb->prepare(
        "SELECT p.*, pr.LIST_PRICE FROM master_product_data p
         LEFT JOIN master_price_data_jun_2026 pr
                ON pr.part COLLATE utf8mb4_unicode_ci = p.part COLLATE utf8mb4_unicode_ci
         WHERE p.series = %s AND p.web = 'Y' ORDER BY p.part", $code), ARRAY_A);
    WP_CLI::log(sprintf('Series %s: %d parts (%s)', $code, count($parts), $apply ? 'APPLY' : 'dry run'));

    $dataFields = array_filter(array_map('trim', explode(',', (string) $s['data_fields'])));
    $toolType   = strtoupper(trim($s['tool_type']));

    // Category chain: TOOL_TYPE > family > tool_sub_type > series.
    $catIds = [];
    if ($apply) {
        $c1 = gws_sync_term($toolType, 'product_cat', 0);
        $c2 = gws_sync_term($s['family'], 'product_cat', $c1);
        $c3 = gws_sync_term($s['tool_sub_type'], 'product_cat', $c2, sanitize_title($s['tool_sub_type'] . '-' . $s['family']));
        $c4 = gws_sync_term($code, 'product_cat', $c3);
        $catIds = [$c1, $c2, $c3, $c4];
    }

    foreach ($parts as $p) {
        $sku = $p['part'];
        $id  = wc_get_product_id_by_sku($sku);

        if (!$apply) {
            WP_CLI::log(sprintf('  %-14s %s  $%s  [%s]', $sku, $id ? 'update' : 'create', $p['LIST_PRICE'] ?? 'NO PRICE',
                implode(', ', array_map(fn($f) => $f . '=' . ($p[$f] ?? ''), $dataFields))));
            $id ? $updated++ : $created++;
            continue;
        }

        $product = $id ? wc_get_product($id) : new WC_Product_Simple();
        $product->set_name($sku);
        $product->set_slug(sanitize_title($sku));
        $product->set_sku($sku);
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
        $product->set_short_description((string) $p['part_description']);
        if ($p['LIST_PRICE'] !== null) {
            $product->set_regular_price((string) $p['LIST_PRICE']);
            $product->set_price((string) $p['LIST_PRICE']);
        }
        $product->set_tax_status('none');
        $product->set_manage_stock(false);
        $product->set_stock_status('instock');
        $product->set_category_ids($catIds);

        $attrs = [];
        $pos = 0;
        foreach ([
            'pa_series'    => $code,
            'pa_tool-type' => $toolType,
            'pa_sub-type'  => $p['sub_type'],
            'pa_family'    => $s['family'],
            'pa_brand'     => $p['brand'],
            'pa_columns'   => $s['cols'],
            'pa_data-fields' => $s['data_fields'],
        ] as $tax => $val) {
            if ($val === null || $val === '') {
                continue;
            }
            $attrs[$tax] = gws_sync_tax_attribute($tax, $val, $pos++);
        }
        foreach ($dataFields as $field) {
            $val = trim((string) ($p[$field] ?? ''));
            // KAPR is NULL in the data for round-insert holders (the shape lives in insert_shape);
            // the catalog shows it as 'Round', so display it that way. Display only - data unchanged.
            if ($field === 'kapr' && $val === '' && strtoupper(substr(trim((string) ($p['holder_insert'] ?? '')), 0, 1)) === 'R') {
                $val = 'Round';
            }
            $map = $mapping[$field] ?? '';
            if ($val === '' || $map === '') {
                continue;
            }
            if (strpos($map, 'pa_') === 0) {
                $attrs[$map] = gws_sync_tax_attribute($map, $val, $pos++);
            } else {
                $a = new WC_Product_Attribute();
                $a->set_id(0);
                $a->set_name($map);
                $a->set_options([$val]);
                $a->set_position($pos++);
                $a->set_visible(true);
                $a->set_variation(false);
                $attrs[sanitize_title($map)] = $a;
            }
        }
        $product->set_attributes($attrs);
        $newId = $product->save();

        // Series-level content is carried on each product as post meta, as the imported products do.
        update_post_meta($newId, 'series_bullets', (string) $s['desc_bullets']);
        update_post_meta($newId, 'series_description', (string) $s['series_description']);
        update_post_meta($newId, 'table_description', (string) $s['table_description']);
        foreach (['p1','p2','p3','m1','m2','k1','k2','n1','n2','s1','s2','h1','h2','materials','app_icons','feat_icons'] as $k) {
            update_post_meta($newId, $k, (string) ($s[$k] ?? ''));
        }

        $id ? $updated++ : $created++;
        WP_CLI::log(sprintf('  %-14s %s (post %d)', $sku, $id ? 'updated' : 'created', $newId));
    }
}

WP_CLI::success(sprintf('%s: %d to create/created, %d to update/updated', $apply ? 'Applied' : 'Dry run', $created, $updated));
