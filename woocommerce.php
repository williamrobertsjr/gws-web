<?php 
error_log("=== Loaded woocommerce.php at the top ===");

if (!class_exists('Timber')) {
    echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
    return;
}

$context = Timber::context();
$context['sidebar'] = Timber::get_widgets('shop-sidebar');

// Load attribute mapping
// This is used to map the attribute names to the labels used in the product page
$attribute_mapping_path = get_template_directory() . '/views/woo/attribute-mapping.php';
$context['attribute_mapping'] = file_exists($attribute_mapping_path)
    ? include $attribute_mapping_path
    : [];

// Load column mapping
// This is used to map the attribute names to the columns used in the product table and the filter
$context['column_mapping'] = include get_template_directory() . '/views/woo/column-mapping.php';


// Load filter label mapping
// This is used to map the attribute names to the labels used in the filter
$filter_label_path = get_template_directory() . '/views/woo/filter-label-mapping.php';
$context['filter_labels'] = file_exists($filter_label_path)
    ? include $filter_label_path
    : [];

// Load tool data mapping (filters per tool_type)
// This is used to map the tool_type to the filters used in the filter
$tool_data_path = get_template_directory() . '/views/woo/tool-type-mapping.php';
$context['tool_data'] = file_exists($tool_data_path)
    ? include $tool_data_path
    : [];


// Set tool_type from category slug (used to render filters dynamically)
if (is_product_category()) {
    $queried_object = get_queried_object();
    if (isset($queried_object->slug)) {
        $context['tool_type'] = strtoupper($queried_object->slug);
    }
}

// -----------------------------
// SINGLE PRODUCT PAGE
// -----------------------------
if (is_singular('product')) {
    $context['post'] = Timber::get_post();
    $product = wc_get_product($context['post']->ID);
    $context['product'] = $product;

    // Extract product attributes
    $attributes = [];
    foreach ($product->get_attributes() as $attribute_name => $attribute) {
        $attributes[$attribute_name] = $attribute->is_taxonomy()
            ? wc_get_product_terms($product->get_id(), $attribute_name, ['fields' => 'names'])
            : $attribute->get_options();
    }
    $context['attributes'] = $attributes;

    // Related by series
    $current_series = $product->get_attribute('pa_series');
    if ($current_series) {
        $args = [
            'post_type' => 'product',
            'posts_per_page' => -1,
            'tax_query' => [[
                'taxonomy' => 'pa_series',
                'field' => 'name',
                'terms' => $current_series,
            ]],
        ];
        $context['related_products_table'] = wc_get_products($args);
    } else {
        $context['related_products_table'] = [];
    }

    // WooCommerce built-in related products
    $related_limit = wc_get_loop_prop('columns');
    $related_ids = wc_get_related_products($context['post']->ID, $related_limit);
    $context['related_products'] = Timber::get_posts($related_ids);

    $context['is_product_page'] = true;

    Timber::render('views/woo/single-product.twig', $context);


// -----------------------------
// ARCHIVE / CATEGORY PAGE
// -----------------------------
} else {
    $posts = Timber::get_posts();
    $context['products'] = $posts;

    if (is_product_category()) {
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;

        // Try to extract attributes from first product
        $attributes = [];
        if (!empty($posts)) {
            $first_product = wc_get_product($posts[0]->ID);
        
            if ($first_product instanceof WC_Product) {
                foreach ($first_product->get_attributes() as $attribute_name => $attribute) {
                    $attributes[$attribute_name] = $attribute->is_taxonomy()
                        ? wc_get_product_terms($first_product->get_id(), $attribute_name, ['fields' => 'names'])
                        : $attribute->get_options();
                }
            } else {
                error_log('⚠️ Invalid product object at woocommerce.php line 106 for post ID ' . $posts[0]->ID);
            }
        }
        
        $context['attributes'] = $attributes;

        // Custom FacetWP + series sorting query
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => 15,
            'paged'          => get_query_var('paged') ?: 1,
            'orderby'        => ['series' => 'DESC'],
            'tax_query'      => [[
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $term_id,
            ]],
            'facetwp'        => true,
        ];

        $my_query = new WP_Query($args);
        $timber_products = Timber::get_posts($my_query);

        // Attach WC product objects
        $products = [];
        foreach ($timber_products as $post) {
            $post->wc_product = wc_get_product($post->ID);
            $products[] = $post;
        }

        $context['products'] = $products;
        $context['category'] = get_term($term_id, 'product_cat');
        $context['title'] = single_term_title('', false);
    }

    Timber::render('views/woo/archive.twig', $context);
}
