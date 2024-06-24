
<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/templates/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();
$timber_post     = Timber::get_post();
$context['search_form'] = do_shortcode('[searchwp_form id=1]');
$context['post'] = $timber_post;


// Check if this is the password reset confirmation page
if ( isset( $_GET['checkemail'] ) && $_GET['checkemail'] === 'confirm' ) {
    // Render the custom page-checkemail.twig template
    Timber::render( 'page-checkemail.twig', $context );
} else {
    // Render other page templates as usual
    $context['search_form'] = do_shortcode('[searchwp_form id=1]');
    $context['post'] = $timber_post;
    Timber::render( array( 'page-' . $timber_post->post_name . '.twig', 'page.twig' ), $context);
}
