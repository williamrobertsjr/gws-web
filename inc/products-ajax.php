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
    
    if (!$series) {
        wp_send_json_error('Missing series');
    }
    
    // Get attribute mapping
    $attribute_mapping = include get_template_directory() . '/views/woo/attribute-mapping.php';
    
    // Base query for total count
    $where_clause = $wpdb->prepare(
        "WHERE p.post_type = 'product'
         AND p.post_status = 'publish'
         AND tt.taxonomy = 'pa_series'
         AND t.name = %s",
        $series
    );
    
    // Add search if provided
    if ($search) {
        $where_clause .= $wpdb->prepare(
            " AND (pm_sku.meta_value LIKE %s)",
            '%' . $wpdb->esc_like($search) . '%'
        );
    }
    
    // Get total count
    $total_sql = "SELECT COUNT(DISTINCT p.ID)
                  FROM {$wpdb->posts} p
                  LEFT JOIN {$wpdb->postmeta} pm_sku ON p.ID = pm_sku.post_id AND pm_sku.meta_key = '_sku'
                  INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
                  INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                  INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
                  $where_clause";
    
    $total = (int)$wpdb->get_var($total_sql);
    
    // Get paginated products with attributes
    $products_sql = "SELECT p.ID, pm_sku.meta_value as sku
                     FROM {$wpdb->posts} p
                     LEFT JOIN {$wpdb->postmeta} pm_sku ON p.ID = pm_sku.post_id AND pm_sku.meta_key = '_sku'
                     INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
                     INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                     INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
                     $where_clause
                     GROUP BY p.ID
                     ORDER BY pm_sku.meta_value
                     LIMIT $start, $length";
    
    $products = $wpdb->get_results($products_sql);
    
    // Format data for DataTables
    $data = [];
    foreach ($products as $row) {
        $product = wc_get_product($row->ID);
        if (!$product) continue;
        
        $row_data = [$row->sku]; // First column: SKU
        
        // Get table columns from request
        $table_cols = explode(',', sanitize_text_field($_GET['table_cols'] ?? ''));
        
        // Add attribute columns
        foreach ($table_cols as $col) {
            $col = trim($col);
            $taxonomy = $attribute_mapping[$col] ?? '';
            $value = $taxonomy ? $product->get_attribute($taxonomy) : '-';
            $row_data[] = $value ?: '-';
        }
        
        // Add stock column (placeholder - will be loaded via JS)
        $row_data[] = '<span class="gws-live-stock" data-gws-sku="' . esc_attr($row->sku) . '">...</span>';
        
        $data[] = $row_data;
    }
    
    wp_send_json([
        'draw' => (int)($_GET['draw'] ?? 1),
        'recordsTotal' => $total,
        'recordsFiltered' => $total,
        'data' => $data,
    ]);
}

?>