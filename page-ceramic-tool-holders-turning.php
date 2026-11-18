<?php
/**
 * Template Name: Ceramic Tool Holders – Turning
 *
 * Landing page for GWS ceramic turning toolholders (Figma: "Ceramic Toolholders – Landing Page v2").
 * Assigned to the "Ceramic Tool Holders for Turning" page. Content and image paths live in
 * inc/data/ceramic-tool-holders-turning.php; helpers and the "Further reading" ACF field are in
 * inc/ceramic-tool-holders.php. Views: views/page-ceramic-tool-holders-turning.twig and
 * views/partials/ceramic-holders/.
 */

// Gravity Forms form 47 (Application Specialist) is embedded via AJAX; enqueue its assets up front.
if (function_exists('gravity_form_enqueue_scripts')) {
    gravity_form_enqueue_scripts(47, true);
}

$context = Timber::context();
$timber_post = Timber::get_post();

$context['post'] = $timber_post;
$context['cth'] = gws_cth_prepare();
$context['cth']['further_reading'] = gws_cth_further_reading($timber_post->ID);

Timber::render('page-ceramic-tool-holders-turning.twig', $context);
