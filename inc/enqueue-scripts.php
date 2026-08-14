<?php
/**
 * Conditional script enqueues (tier pricing, price export, parts pricing table).
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */


function gws_enqueue_tier_scripts() {
    // Only load scripts for logged-in users - helps prevent bot overloading server by calling admin-ajax.php over and over
    if (!is_user_logged_in()) {
        return;
    }
    // Make sure wc_cart_params is available site-wide
    if (function_exists('wc_enqueue_js')) {
        wp_enqueue_script('wc-cart-fragments'); // ensures wc_cart_params is defined
    }

    wp_enqueue_script(
        'tier-selector',
        get_template_directory_uri() . '/assets/js/tier-selector.js',
        [],
        null,
        true
    );

    wp_enqueue_script(
        'cart-pricing',
        get_template_directory_uri() . '/assets/js/cart-pricing.js',
        ['tier-selector'], // depends on tier-selector
        null,
        true
    );

    // Ensure wc_cart_params is available in JS
    wp_localize_script('cart-pricing', 'wc_cart_params', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'gws_enqueue_tier_scripts');

// Shared spinner/download handler for price-export buttons & links, used on
// both the dashboard's Quick Links and the pricing page's button.
add_action('wp_enqueue_scripts', function () {
    if (!is_page('pricing') && !is_page('dashboard')) return;

    wp_enqueue_script(
        'gws-price-export-download',
        get_template_directory_uri() . '/assets/js/price-export-download.js',
        [],
        null,
        true
    );
});

// Load the parts/pricing DataTable + Excel download script only on the pricing page
add_action('wp_enqueue_scripts', function () {
    if (!is_page('pricing')) return;

    wp_enqueue_script(
        'gws-data-download',
        get_template_directory_uri() . '/assets/js/data-download.js',
        ['jquery', 'tier-selector', 'gws-price-export-download'],
        null,
        true
    );
});

add_filter('timber/context', function ($context) {
    $context['userRole'] = '';

    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $context['userRole'] = $user->roles[0] ?? '';
    }

    return $context;
});

// Disable WooCommerce cart fragments for performance
add_action('wp_enqueue_scripts', function() {
    wp_dequeue_script('wc-cart-fragments');
    wp_deregister_script('wc-cart-fragments');
});
