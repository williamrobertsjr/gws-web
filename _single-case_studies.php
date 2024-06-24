<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */


// Fetch ACF fields and add them to the context
$context         = Timber::context();
$timber_post     = Timber::get_post();
$context['post'] = $timber_post;


// $context = Timber::context();
// $context['post'] = $post;
$context['current_process'] = get_field('current_process', $post->ID);
echo var_dump($context);
// Render the Twig template
Timber::render('single-case_studies.twig', $context);