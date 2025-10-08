<?php
// views/woo/discounts.php

// Cache user tier per request
function gws_get_user_tier() {
    static $tier = null;

    if ( $tier !== null ) return $tier;
    
    // Allow dropdown-selected tier via cookie to override view pricing
    if ( ! empty( $_COOKIE['gws_selected_tier'] ) ) {
        $candidate = sanitize_text_field( $_COOKIE['gws_selected_tier'] );
        $allowed   = [ 't1', 't2', 't3', '57_5', 'direct', 'default', 'none' ];
        if ( in_array( $candidate, $allowed, true ) ) {
            $tier = ( $candidate === 'default' ) ? 'none' : $candidate;
            return $tier;
        }
    }

    $user = wp_get_current_user();
    if ( ! $user || ! $user->exists() ) return 'none';

    $roles = (array) $user->roles;
    $role = $roles[0] ?? 'none';

    if ( in_array($role, ['administrator', 'sales'], true) ) {
        $tier = get_user_meta($user->ID, 'distributor_level', true) ?: $role;
    } else {
        $tier = $role;
    }

    return $tier;
}

function gws_get_discounted_price_from_product(WC_Product $product) {
    return gws_calculate_discounted_price(gws_get_user_tier(), $product);
}

add_filter( 'woocommerce_product_get_price', 'gws_dynamic_discount_price', 10, 2 );
// add_filter( 'woocommerce_product_get_regular_price', 'gws_dynamic_discount_price', 10, 2 );
function gws_dynamic_discount_price( $price, $product ) {
    if (
        is_admin() ||
        defined('DOING_CRON') ||
        ( function_exists('wp_doing_ajax') && wp_doing_ajax() ) ||
        ( function_exists('wp_doing_rest') && wp_doing_rest() ) ||
        ! is_user_logged_in()
    ) {
        return $price;
    }

    if ( ! $product instanceof WC_Product ) return $price;

    $custom_price = gws_get_discounted_price_from_product( $product );
    return is_numeric( $custom_price ) ? $custom_price : $price;
}

/**
 * Filter product price shown in the side cart plugin
 */
add_filter('woocommerce_sidecart_product_price', 'gws_sidecart_discounted_price', 10, 2);

function gws_sidecart_discounted_price($price_html, $product) {
    if ( ! is_user_logged_in() || ! $product instanceof WC_Product ) {
        return $price_html;
    }

    $regular_price = (float) $product->get_meta('_regular_price', true);
    $discounted    = gws_calculate_discounted_price( gws_get_user_tier(), $product );

    if ( $discounted < $regular_price ) {
        return '<s class="text-gray-400 me-2">' . wc_price($regular_price) . '</s> ' . wc_price($discounted);
    }

    return wc_price($discounted);
}


// Override cart item price to show discounted price
// This is used in the cart and checkout pages to show the price for each item
// It replaces the default WooCommerce price with the discounted price
// This is necessary to ensure the cart reflects the discounted prices
add_filter('woocommerce_cart_item_price', 'gws_override_cart_item_price_html', 10, 3);

function gws_override_cart_item_price_html($price_html, $cart_item, $cart_item_key) {
    if (! is_user_logged_in()) return $price_html;

    $product = $cart_item['data'];
    if (! $product instanceof WC_Product) return $price_html;

    $regular_price = (float) $product->get_meta('_regular_price', true);
    $discounted = gws_get_discounted_price_from_product($product);

    if ( $discounted < $regular_price ) {
        return wc_price($discounted);
    }

    return wc_price($discounted);
}


// Override cart item subtotal to show discounted price
// This is used in the cart and checkout pages to show the subtotal for each item
// It replaces the default WooCommerce subtotal with the discounted price
// This is necessary to ensure the cart reflects the discounted prices
add_filter('woocommerce_cart_item_subtotal', 'gws_override_cart_item_subtotal_html', 10, 3);

function gws_override_cart_item_subtotal_html($subtotal_html, $cart_item, $cart_item_key) {
    if (! is_user_logged_in()) return $subtotal_html;

    $product = $cart_item['data'];
    if (! $product instanceof WC_Product) return $subtotal_html;

    $regular_price = (float) $product->get_meta('_regular_price', true);
    $discounted = gws_get_discounted_price_from_product($product);
    $qty = (int) $cart_item['quantity'];

    if ( $discounted < $regular_price ) {
        return wc_price($discounted * $qty);
    }

    return wc_price($discounted * $qty);
}


// Modify cart item prices before totals are calculated
// This ensures the cart reflects the discounted prices
add_action('woocommerce_before_calculate_totals', 'gws_modify_cart_item_prices', 10, 1);
function gws_modify_cart_item_prices($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;
    if (!is_user_logged_in()) return;

    foreach ($cart->get_cart() as $cart_item) {
        if (!isset($cart_item['data']) || !is_a($cart_item['data'], 'WC_Product')) continue;

        $product = $cart_item['data'];
        $discounted = gws_get_discounted_price_from_product($product);

        if ($discounted !== null && $discounted < $product->get_regular_price()) {
            $product->set_price($discounted);
        }
    }
}

// Add a Twig function to get user role display
// This is used in Twig templates to display the user role in percentage rathter than tier name
add_filter('timber/twig', function ($twig) {
    $twig->addFunction(new \Twig\TwigFunction('get_user_role_display', 'get_user_role_display'));
    return $twig;
});
function get_user_role_display($role) {
    $map = [
        't1' => '55%',
        't2' => '52.5%',
        't3' => '50%',
        '57_5' => '57.5%',
        'sales' => 'Sales Team',
        'administrator' => 'Administrator',
    ];
    return $map[$role] ?? 'None';
}
// Handle AJAX tier switch to re-calculate prices in cart
add_action('wp_ajax_get_discounted_prices_by_tier', 'gws_handle_ajax_tier_price_switch');
add_action('wp_ajax_get_discounted_product_prices_by_tier', 'gws_handle_ajax_product_price_request');

function gws_handle_ajax_tier_price_switch() {
    if (!is_user_logged_in()) {
        wp_send_json_error('Not logged in');
        return;
    }

    if (!isset($_GET['tier'])) {
        wp_send_json_error('Missing tier');
        return;
    }

    $tier = sanitize_text_field($_GET['tier']);
    $items = [];
    $original_total = 0;
    $discounted_total = 0;

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        $qty = (int) $cart_item['quantity'];
        $regular_price = (float) $product->get_meta('_regular_price', true);

        // Get discounted price for the selected tier
        $discounted_price = gws_calculate_discounted_price($tier, $product);
        $line_total = $discounted_price * $qty;

        $items[$cart_item_key] = [
            'discounted_price_html' => wc_price($discounted_price),
            'discounted_subtotal_html' => wc_price($line_total),
        ];

        $original_total += $regular_price * $qty;
        $discounted_total += $line_total;
    }

    wp_send_json_success([
        'cart_items' => $items,
        'original_total_html' => wc_price($original_total),
        'discounted_total_html' => wc_price($discounted_total),
    ]);
}

function gws_calculate_discounted_price($tier, WC_Product $product) {
    $regular_price = (float) $product->get_meta('_regular_price', true);
    if ($regular_price <= 0) return $regular_price;

    switch ($tier) {
        case 't1':     $rate = 0.55; break;
        case 't2':     $rate = 0.525; break;
        case 't3':     $rate = 0.50; break;
        case '57_5':   $rate = 0.575; break;
        case 'direct': $rate = 0.30; break;
        default:       $rate = 0.0; break;
    }

    return round($regular_price * (1 - $rate), 2);
}

function gws_handle_ajax_product_price_request() {
    if (!is_user_logged_in()) {
        wp_send_json_error('Not logged in');
        return;
    }

    if (!isset($_GET['tier'], $_GET['product_ids'])) {
        wp_send_json_error('Missing parameters');
        return;
    }

    $tier = sanitize_text_field($_GET['tier']);
    $ids = array_map('absint', explode(',', $_GET['product_ids'] ?? ''));

    if (empty($ids)) {
        wp_send_json_error('No valid product IDs');
        return;
    }

    $results = [];

    foreach ($ids as $id) {
        $product = wc_get_product($id);
        if (! $product instanceof WC_Product) continue;

        $regular = (float) $product->get_meta('_regular_price', true);
        $discounted = gws_calculate_discounted_price($tier, $product);

        $results[$id] = [
            'discounted_price_html' => wc_price($discounted),
            'regular_price_html' => wc_price($regular),
        ];
    }

    wp_send_json_success($results);
}

