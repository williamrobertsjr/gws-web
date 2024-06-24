<?php 
$context = Timber::context();

// Adding user role to the context
$context['userRole'] = get_current_user_role();

Timber::render('page-rapid-quote.twig', $context); 

?>