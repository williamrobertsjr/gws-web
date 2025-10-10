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

$context['cart'] = $cart;
$context['cart_items'] = $cart_items;
$context['cart_discounted_total'] = $discounted_total;
$context['cart_original_total'] = $original_total;

Timber::render('woo/cart/cart.twig', $context);