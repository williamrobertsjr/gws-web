<?php 
$context = Timber::context();

// Get what's new loop
$args = [
    'post_type' => array('post','case_studies', 'employee_spotlight'),
    'posts_per_page' => 10,
];


// Set the context variable of posts to use the $args wp_query arguments
$context['posts'] = Timber::get_posts($args); 

Timber::render('page-home.twig', $context); 

?>