<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 */

// Load Composer dependencies.
$autoload = get_template_directory() . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
} else {
    error_log('[WARNING] Autoloader missing: ' . $autoload);
    // Optional: fallback or error-handling logic
}

// Initialize Timber
require_once __DIR__ . '/src/StarterSite.php';

// Include custom pricing logic
require_once get_template_directory() . '/lib/part-pricing.php';

// Include AJAX product lookup logic for product tables
require_once get_template_directory() . '/inc/products-ajax.php';

// Include Parts & Pricing data-download page logic (DataTables feed + CSV export)
require_once get_template_directory() . '/inc/data-download.php';

// Include Rapid Quote API logic
// for getting part list prices from external API
// and logging for admin users
// used for Rapid Quote form and bulk add-to-cart
$quote_api = get_template_directory() . '/inc/gws-quote-api.php';
if ( file_exists($quote_api) ) {
    require_once $quote_api;
} else {
    error_log('[WARNING] Missing gws-quote-api.php');
}

add_action('init', function() {
    if (is_user_logged_in() && current_user_can('manage_options')) {
        $test_part = 'XYZ123'; // Replace with a real part number in your DB
        $price = gws_get_part_list_price($test_part);
        error_log("Price for part {$test_part}: " . $price);
    }
});

// Shared company pricing-exemption lists, used by both the Rapid Quote JS flags below
// and inc/discounts.php. Must load first so both call sites see the same values.
require_once get_template_directory() . '/inc/pricing-exempt-companies.php';

// Load discounts logic for WooCommerce and distributor tiers sitewide
require_once get_template_directory() . '/inc/discounts.php';

// Add cart count and URL to Timber context
add_filter('timber/context', function ($context) {
    if ( class_exists('WooCommerce') && ! is_admin() ) {
        WC()->initialize_cart();
        $context['cart_count'] = WC()->cart->get_cart_contents_count();
        $context['cart_url'] = wc_get_cart_url();
    }
    return $context;
});

// AJAX handler to get cart count when products added to cart
add_action('wp_ajax_get_cart_count', 'gws_get_cart_count');
add_action('wp_ajax_nopriv_get_cart_count', 'gws_get_cart_count');

function gws_get_cart_count() {
    wp_send_json_success([
        'count' => WC()->cart->get_cart_contents_count()
    ]);
}

add_filter('timber/twig/environment/options', function ($options) {
    $options['debug'] = true;
    return $options;
});


Timber\Timber::init();

new StarterSite();

if ( ! class_exists( 'Timber' ) ) {
    add_action( 'admin_notices', function () {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
    } );

    return;
}

// Sets the directories (inside your theme) to find .twig files.
Timber::$dirname = array( 'views' );

/**
 * Everything below this point used to live inline in this file (955 lines).
 *
 * The modules are required in the same order the code originally appeared, so
 * hook registration order -- which matters for same-priority filters like
 * timber/context and manage_users_columns -- is unchanged.
 */
require_once get_template_directory() . '/inc/theme-setup.php';
require_once get_template_directory() . '/inc/rewrites.php';
require_once get_template_directory() . '/inc/rest-quote.php';
require_once get_template_directory() . '/inc/user-roles.php';
require_once get_template_directory() . '/inc/admin-users.php';
require_once get_template_directory() . '/inc/login.php';
require_once get_template_directory() . '/inc/search-integrations.php';
require_once get_template_directory() . '/inc/cart-ajax.php';
require_once get_template_directory() . '/inc/enqueue-scripts.php';
require_once get_template_directory() . '/inc/bulk-add-to-cart.php';
require_once get_template_directory() . '/inc/integrations.php';
require_once get_template_directory() . '/inc/distributor.php';
