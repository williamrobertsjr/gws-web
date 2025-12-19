<?php
/**
 * Template Name: Survey Results
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php 
// Include your HTML file
include( get_template_directory() . '/survey-results.html' );
?>

<?php wp_footer(); ?>
</body>
</html>