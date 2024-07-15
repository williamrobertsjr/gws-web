<?php 
/**
 * Template Name: GWS Blog
 * Description: GWS Blog
 */

$context = Timber::context();

// Get blog loop
$args = [
    'post_type' => array('post','case_studies', 'employee_spotlight'),
    'posts_per_page' => -1,
];
// Set the context variable of posts to use the $args wp_query arguments
$context['posts'] = Timber::get_posts($args); 



// Render with Twig template
Timber::render('page-gws-blog.twig', $context);

?>