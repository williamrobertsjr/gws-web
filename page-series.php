<?php 
/**
 * Template Name: All Series Template
 * Description: Series Page Template
 */

// Connect to the Database
include 'db_connection.php';

// Fetch all series data
$stmt = $conn->prepare("SELECT * FROM `master_series_data` WHERE catalog = 'Y' ORDER BY `order`");
$stmt->execute();
$result = $stmt->get_result();

$series_list = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $series_list[] = $row; // Store each row (series data) in series_list
    }
}

// Prepare Timber context
$context = Timber::context();
$context['series_list'] = $series_list;

// Render with Twig template
Timber::render('page-series.twig', $context);
?>
