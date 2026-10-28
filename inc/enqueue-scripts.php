<?php
/**
 * Conditional script enqueues (tier pricing, price export, parts pricing table).
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */


// Tier-pricing scripts load for every visitor, not just logged-in ones: once a
// page is served from the page cache, whichever visitor's request happened to
// populate that cache entry decides what's in the shared HTML. Gating the
// script tag itself on is_user_logged_in() would mean a logged-in user who
// gets served a guest-populated cache entry never receives the script that
// fixes their price. The real gate is the gws_logged_in cookie check inside
// the scripts themselves (see auto-price-fetch.js, inc/login.php) -- a
// guest's browser loads the same static JS but never fires the ajax calls.
function gws_enqueue_tier_scripts() {
    // filemtime() versioning so browsers fetch the new file as soon as it
    // changes, instead of serving a stale cached copy indefinitely (these had
    // no cache-busting version at all before).
    wp_enqueue_script(
        'tier-selector',
        get_template_directory_uri() . '/assets/js/tier-selector.js',
        [],
        filemtime(get_template_directory() . '/assets/js/tier-selector.js'),
        true
    );

    wp_enqueue_script(
        'cart-pricing',
        get_template_directory_uri() . '/assets/js/cart-pricing.js',
        ['tier-selector'], // depends on tier-selector
        filemtime(get_template_directory() . '/assets/js/cart-pricing.js'),
        true
    );

    wp_enqueue_script(
        'auto-price-fetch',
        get_template_directory_uri() . '/assets/js/auto-price-fetch.js',
        ['tier-selector'], // reuses tier-selector's window.gwsRefreshPrices
        filemtime(get_template_directory() . '/assets/js/auto-price-fetch.js'),
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

// wc-cart-pdf enqueues its checkout-block filter script on every page (its only
// guard is that wc-blocks-checkout is registered, which is always true), which
// drags ~40 WP/WooCommerce block scripts (React, wp-data, wp-components, ...)
// onto pages that have no cart or checkout block. Only keep it where it's used.
// Priority 100: the plugin enqueues during enqueue_block_assets, which fires
// inside wp_enqueue_scripts at priority 10.
add_action('wp_enqueue_scripts', function () {
    if (function_exists('is_cart') && (is_cart() || is_checkout())) return;

    wp_dequeue_script('wc-cart-pdf-checkout-filters');
}, 100);

add_filter('timber/context', function ($context) {
    $context['userRole'] = '';

    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $context['userRole'] = $user->roles[0] ?? '';
    }

    return $context;
});
