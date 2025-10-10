<?php
/**
 * Third party plugins that hijack the theme will call wp_head() to get the header template.
 * We use this to start our output buffer and render into the view/page-plugin.twig template in footer.php
 *
 * If you're not using a plugin that requries this behavior (ones that do include Events Calendar Pro and
 * WooCommerce) you can delete this file and footer.php
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$GLOBALS['timberContext'] = Timber::context();
ob_start();
woocommerce_mini_cart();
$context['mini_cart'] = ob_get_clean();
$context['cart_url'] = wc_get_cart_url();
$context['cart_count'] = WC()->cart->get_cart_contents_count();

// wp_nav_menu( array('theme_location' => 'primary', 'menu_class' => 'nav-menu'));