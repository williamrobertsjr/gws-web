<?php
/**
 * Template Name: Cart
 */

$context = Timber::context();
$context['cart'] = WC()->cart;
Timber::render('woo/cart/cart.twig', $context);