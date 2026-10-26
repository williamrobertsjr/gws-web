<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$context          = Timber::context();
$context['posts'] = Timber::get_posts();
$context['foo']   = 'bar';
$templates        = array( 'index.twig' );
woocommerce_mini_cart();
$context['mini_cart'] = ob_get_clean();
$context['cart_url'] = wc_get_cart_url();
// Real count is fetched client-side on load (see assets/js/auto-price-fetch.js
// + functions.php's get_cart_count ajax action) so this page's HTML stays the
// same for every visitor and can be page-cached.
$context['cart_count'] = 0;
if ( is_front_page() ) {
	array_unshift( $templates, 'front-page.twig', 'home.twig' );
}
// ...

// Debugging statement to log the templates being used
error_log( print_r( $templates, true ) );

// Render the templates
Timber::render( $templates, $context );

