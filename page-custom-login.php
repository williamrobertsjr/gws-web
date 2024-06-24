<?php 
// Template Name: Custom Login Page


$context = Timber::context();

// Add query parameters to the context for login failures
$context['login_failed'] = isset($_GET['login']) && $_GET['login'] == 'failed';


Timber::render('page-custom-login.twig', $context); 

?>

