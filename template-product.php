<?php 
if (!class_exists('Timber')) {
    echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
    return;
}
include 'db_connection.php';

$context = Timber::context();
$context['sidebar'] = Timber::get_widgets('shop-sidebar');

// Handle single product pages
if (is_singular('product')) {
    $context['post'] = Timber::get_post();
    $product = wc_get_product($context['post']->ID);
    $context['product'] = $product;

    // Add WooCommerce attributes
    $attributes = [];
    foreach ($product->get_attributes() as $attribute_name => $attribute) {
        $attributes[$attribute_name] = $attribute->is_taxonomy()
            ? wc_get_product_terms($product->get_id(), $attribute_name, ['fields' => 'names'])
            : $attribute->get_options();
    }
    $context['attributes'] = $attributes;

    // Get WooCommerce related products
    $related_limit = wc_get_loop_prop('columns');
    $related_ids = wc_get_related_products($context['post']->id, $related_limit);
    $context['related_products'] = Timber::get_posts($related_ids);

    // Fetch series data using db_connection
    $series = get_post_meta($product->get_id(), 'series', true); // Assuming 'series' is stored in post meta
    if ($series) {
        // Fetch product-specific data
        $stmt = $conn->prepare(
            "SELECT p.*, s.subtitle, s.data_fields, s.feat_icons, s.app_icons, s.p1, s.p2, s.p3, s.h1, s.h2, s.n1, s.n2, s.k1, s.k2, s.h1, s.h2, s.m1, s.m2, s.speed_feed_page, r.QTY_ON_HAND
             FROM `master_product_data` p
             LEFT JOIN `master_series_data` s ON p.series = s.series
             LEFT JOIN `rapid_quote` r ON r.PN = p.part
             WHERE p.part = ?
             AND p.web = 'Y'"
        );
        $stmt->bind_param("s", $product->get_sku());
        $stmt->execute();
        $product_result = $stmt->get_result();
        $product_data = $product_result->fetch_assoc();
        $context['custom_product_data'] = $product_data;

        // Fetch series products
        $series_stmt = $conn->prepare(
            "SELECT p.*, r.QTY_ON_HAND
             FROM `master_product_data` p
             LEFT JOIN `rapid_quote` r ON r.PN = p.part
             WHERE p.series = ?"
        );
        $series_stmt->bind_param("s", $series);
        $series_stmt->execute();
        $series_products = $series_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $context['series_products'] = $series_products;

        // Clean up statements
        $stmt->close();
        $series_stmt->close();
    } else {
        $context['custom_product_data'] = null;
        $context['series_products'] = [];
    }

    // Set templateName for compatibility
    $context['templateName'] = 'single-product.twig';

    Timber::render('views/woo/single-product.twig', $context);
} else {
    $posts = Timber::get_posts();
    $context['products'] = $posts;

    if (is_product_category()) {
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;
        $context['category'] = get_term($term_id, 'product_cat');
        $context['title'] = single_term_title('', false);
    }

    Timber::render('views/woo/archive.twig', $context);
}
