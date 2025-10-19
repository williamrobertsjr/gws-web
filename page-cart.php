<?php
/**
 * Template Name: Cart
 */

$context = Timber::context();
$context['userRole'] = get_current_user_role();
$cart = WC()->cart;
$cart_items = $cart->get_cart();

$original_total = 0;
$discounted_total = 0;

foreach ( $cart_items as $key => &$item ) {
    $product = $item['data'];
    $quantity = $item['quantity'];

    $original_price = (float) $product->get_meta('_regular_price', true);
    $discounted_price = gws_get_discounted_price_from_product( $product );

    $item['original_price'] = $original_price;
    $item['discounted_price'] = $discounted_price;
    $item['discounted_subtotal'] = $discounted_price * $quantity;

    $original_total += $original_price * $quantity;
    $discounted_total += $item['discounted_subtotal'];
}

// Get raw table update time (likely in UTC)
global $wpdb;

// Get MySQL's last UPDATE_TIME for the rapid_quote table
$raw_time = $wpdb->get_var("
  SELECT UPDATE_TIME
  FROM information_schema.tables
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'rapid_quote'
");

if ($raw_time) {
    // Treat it as server-local (America/Chicago)
    $dt = new DateTime($raw_time, new DateTimeZone('America/Chicago'));

    // Format however you want before sending to Twig
    $context['rapid_quote_last_updated'] = $dt->format('m/d/Y \a\t g:i A');
} else {
    $context['rapid_quote_last_updated'] = 'Unknown';
}

$context['cart'] = $cart;
$context['cart_items'] = $cart_items;
$context['cart_discounted_total'] = $discounted_total;
$context['cart_original_total'] = $original_total;

Timber::render('woo/cart/cart.twig', $context);