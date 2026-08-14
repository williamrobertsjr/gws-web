<?php
/**
 * Cart AJAX handlers: update, remove, clear, and tier-discounted pricing.
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */


add_action('wp_ajax_update_cart_item', 'ajax_update_cart_item');
add_action('wp_ajax_nopriv_update_cart_item', 'ajax_update_cart_item');

function ajax_update_cart_item() {
    $key = sanitize_text_field($_POST['cart_item_key']);
    $quantity = max(0, intval($_POST['quantity']));

    if ($quantity === 0) {
        WC()->cart->remove_cart_item($key);
    } else {
        WC()->cart->set_quantity($key, $quantity, true);
    }

    WC()->cart->calculate_totals();

    // Prepare updated totals for JS
    $item = WC()->cart->get_cart_item($key);

    $totals = [];
    if ($item) {
        $totals[$key] = [
            'subtotal' => wc_price($item['line_subtotal']),
        ];
    }

    $totals['cart_subtotal'] = WC()->cart->get_cart_subtotal();
    ob_start();
    wc_cart_totals_order_total_html();
    $totals['cart_total'] = ob_get_clean();

    wp_send_json_success([
        'cart_items' => $totals,
        'original_total_html' => $totals['cart_subtotal'],
        'discounted_total_html' => $totals['cart_total'],
    ]);
}

add_action('wp_ajax_remove_cart_item', 'ajax_remove_cart_item');
add_action('wp_ajax_nopriv_remove_cart_item', 'ajax_remove_cart_item');

function ajax_remove_cart_item() {
    $key = sanitize_text_field($_POST['cart_item_key']);
    WC()->cart->remove_cart_item($key);

    wp_send_json_success();
}

// Custom logic section

/**
 * Get discounted price by tier.
 *
 * @param float $price
 * @param string $tier
 * @return float
 */
function gws_get_discounted_price($price, $tier) {
    switch ($tier) {
        case 't1':
            return $price * 0.90;
        case 't2':
            return $price * 0.75;
        case 't3':
            return $price * 0.60;
        default:
            return $price;
    }
}

// Discounted product prices by tier AJAX handler
add_action('wp_ajax_get_discounted_product_prices_by_tier', 'get_discounted_product_prices_by_tier');
// SECURITY FIX: Removed unauthenticated access to pricing;

function get_discounted_product_prices_by_tier() {
    if (empty($_GET['tier']) || empty($_GET['product_ids'])) {
        wp_send_json_error('Missing tier or product_ids', 400);
    }

    $tier = sanitize_text_field($_GET['tier']);
    $product_ids = array_map('intval', explode(',', $_GET['product_ids']));

    $discounted_prices = [];

    foreach ($product_ids as $product_id) {
        $product = wc_get_product($product_id);
        if (!$product) {
            continue;
        }

        $price = (float) $product->get_price();
        $discounted_price = gws_get_discounted_price($price, $tier);

        $discounted_prices[$product_id] = [
            'discounted_price_html' => wc_price($discounted_price),
        ];
    }

    wp_send_json_success(['discounted_prices' => $discounted_prices]);
}

add_action('wp_enqueue_scripts', function () {
    if (is_cart()) {
        wp_enqueue_script('custom-cart-ajax', get_template_directory_uri() . '/assets/js/cart-ajax.js', ['jquery'], null, true);
        wp_localize_script('custom-cart-ajax', 'wc_cart_params', ['ajax_url' => admin_url('admin-ajax.php')]);
    }
});

// Clear cart via AJAX
add_action('wp_ajax_clear_cart', 'gws_clear_cart');
add_action('wp_ajax_nopriv_clear_cart', 'gws_clear_cart');

function gws_clear_cart() {
    WC()->cart->empty_cart();
    wp_send_json_success('Cart cleared');
}
