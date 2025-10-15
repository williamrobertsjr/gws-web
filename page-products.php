<?php 
// Products Page Template
// Description: A custom page template to display product categories and subcategories using Timber and WooCommerce. 
// template found in /views/page-products.twig
$context = Timber::context();

// Get top-level product categories
$context['tool_types'] = Timber::get_terms([
    'taxonomy'   => 'product_cat',
    'parent'     => 0, // top-level only
    'hide_empty' => true
]);

// Add subcategories to each parent category
foreach ($context['tool_types'] as $tool_type) {
    $tool_type->tool_sub_type = Timber::get_terms([
        'taxonomy'   => 'product_cat',
        'parent'     => $tool_type->term_id,
        'hide_empty' => false
    ]);
}

Timber::render('views/page-products.twig', $context);

?>