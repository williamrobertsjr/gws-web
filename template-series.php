<?php 
/**
 * Template Name: Series Template
 * Description: Series Page Template
 */

// Connect to the Database
include 'db_connection.php';

$series_id = get_query_var('series_id');
// Now you can use $series_id to fetch and display the relevant series information
$series_id = urldecode($series_id);
if ($series_id) {
    // Fetch the current series data
    $stmt = $conn->prepare("SELECT * FROM `master_series_data` WHERE series = ? AND `catalog` = 'Y'");
    $stmt->bind_param("s", $series_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $series_data = $result->fetch_assoc();
        $family = $series_data['family']; // Assuming 'family' is a column in your table

        // Fetch all series in the same family
        $stmt = $conn->prepare("SELECT * FROM `master_series_data` WHERE family = ? AND `catalog` = 'Y'");
        $stmt->bind_param("s", $family);
        $stmt->execute();
        $family_series_result = $stmt->get_result();
        $family_series = $family_series_result->fetch_all(MYSQLI_ASSOC);

        // Don't load products here - DataTables will load via AJAX from WooCommerce
        $products = [];
    } else {
        // $series_data = null;
        // $family_series = [];
        // $products = [];
        // No series data found, redirect to the 404 page (set up to redirect to a series specific 404 page?)
        status_header(404);
        wp_redirect(home_url('404'));
        exit();
    }

    // Prepare Timber context
    $context = Timber::context();
    $context['seriesId'] = $series_id;
    $context['series_data'] = $series_data;
    $context['family_series'] = $family_series; // Pass the family series to Twig
    $context['products'] = $products; // Pass the product data to Twig
    $context['page_title'] = 'Testing';
    // Render with Twig template
    Timber::render('page-series-single.twig', $context);
} else {
    // Handle the case where 'series_id' is not set in the URL
    
}
?>
