<?php

/**
 * Product-related AJAX handlers
 * - Series product tables
 * - Product search/filtering
 */

if (!defined('ABSPATH')) exit;

// AJAX endpoint for server-side DataTables
add_action('wp_ajax_get_series_products_dt', 'get_series_products_datatables');
add_action('wp_ajax_nopriv_get_series_products_dt', 'get_series_products_datatables');

function get_series_products_datatables() {
    global $wpdb;

    $series = sanitize_text_field($_GET['series'] ?? '');
    $start = (int)($_GET['start'] ?? 0);
    $length = (int)($_GET['length'] ?? 25);
    $search = sanitize_text_field($_GET['search']['value'] ?? '');
    $table_cols = array_map('trim', explode(',', sanitize_text_field($_GET['table_cols'] ?? '')));

    if (!$series) {
        wp_send_json_error('Missing series');
    }

    // Get attribute mapping
    $attribute_mapping = include get_template_directory() . '/inc/attribute-mapping.php';

    $joins = "FROM {$wpdb->posts} p
              LEFT JOIN {$wpdb->postmeta} pm_sku ON p.ID = pm_sku.post_id AND pm_sku.meta_key = '_sku'
              INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
              INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
              INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id";

    $where_sql = "WHERE p.post_type = 'product'
         AND pm_sku.meta_value IN (SELECT part FROM master_product_data)
         AND p.post_status = 'publish'
         AND tt.taxonomy = 'pa_series'
         AND t.name = %s";
    $where_args = [$series];

    // Unfiltered total (before search is applied)
    $recordsTotal = (int)$wpdb->get_var(
        $wpdb->prepare("SELECT COUNT(DISTINCT p.ID) $joins $where_sql", $where_args)
    );

    // Add search across SKU, description, and any attribute columns shown in this table
    if ($search) {
        $like = '%' . $wpdb->esc_like($search) . '%';
        $search_parts = ['pm_sku.meta_value LIKE %s', 'p.post_excerpt LIKE %s'];
        $where_args[] = $like;
        $where_args[] = $like;

        foreach ($table_cols as $col) {
            if ($col === 'part_description') continue; // covered by post_excerpt above
            $taxonomy = $attribute_mapping[$col] ?? '';
            if (!$taxonomy) continue;

            $search_parts[] = "EXISTS (
                SELECT 1 FROM {$wpdb->term_relationships} str
                INNER JOIN {$wpdb->term_taxonomy} stt ON str.term_taxonomy_id = stt.term_taxonomy_id
                INNER JOIN {$wpdb->terms} sterm ON stt.term_id = sterm.term_id
                WHERE str.object_id = p.ID AND stt.taxonomy = %s AND sterm.name LIKE %s
            )";
            $where_args[] = $taxonomy;
            $where_args[] = $like;
        }

        $where_sql .= ' AND (' . implode(' OR ', $search_parts) . ')';
    }

    // Get filtered count
    $recordsFiltered = (int)$wpdb->get_var(
        $wpdb->prepare("SELECT COUNT(DISTINCT p.ID) $joins $where_sql", $where_args)
    );

    // Get paginated products with attributes
    $products_sql = $wpdb->prepare(
        "SELECT p.ID, pm_sku.meta_value as sku
         $joins
         $where_sql
         GROUP BY p.ID
         ORDER BY pm_sku.meta_value
         LIMIT %d, %d",
        array_merge($where_args, [$start, $length])
    );

    $products = $wpdb->get_results($products_sql);

    // Format data for DataTables
    $data = [];
    foreach ($products as $row) {
        $product = wc_get_product($row->ID);
        if (!$product) continue;
        
        $row_data = [$row->sku]; // First column: SKU
        
        
        
        // Add attribute columns
        foreach ($table_cols as $col) {
            $col = trim($col);
            if ($col === 'part_description') {
                $value = $product->get_short_description() ?: '-';
            } else {
                $taxonomy = $attribute_mapping[$col] ?? '';
                $value = $taxonomy ? $product->get_attribute($taxonomy) : '-';
            }
            $row_data[] = $value ?: '-';
        }
        
        // Add stock column (placeholder - will be loaded via JS)
        if (is_user_logged_in()) {
            $row_data[] = '<span class="gws-live-stock" data-gws-sku="' . esc_attr($row->sku) . '">...</span>';
        } else {
            $row_data[] = 'Log in to view stock.';
        }
        
        $data[] = $row_data;
    }
    
    wp_send_json([
        'draw' => (int)($_GET['draw'] ?? 1),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data,
    ]);
}

?>