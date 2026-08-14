<?php
/**
 * Distributor autocomplete REST routes and its script enqueue.
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */


// Distributor autocomplete REST API routes and handlers
require_once get_template_directory() . '/inc/distributor-autocomplete.php';
function gws_enqueue_distributor_autocomplete() {
    if (is_page('distributor-registration')) { // adjust slug to match your page
        wp_enqueue_script(
            'gws-distributor-autocomplete',
            get_template_directory_uri() . '/assets/js/distributor-autocomplete.js',
            [],
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'gws_enqueue_distributor_autocomplete');
