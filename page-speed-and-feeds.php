<?php 
/**
 * Template Name: Speed and Feeds Template
 * Description: Speed and Feeds Template
 */

// Connect to the Database
include 'db_connection.php';

// Fetch data from the database
$stmt = $conn->prepare("SELECT DISTINCT s.series, s.family, s.subtitle, s.tool_type
                        FROM master_series_data s
                        WHERE s.speed_feed_page IS NOT NULL
                          AND s.catalog = 'y'");
$stmt->execute();
$result = $stmt->get_result();

// Initialize an empty array to hold the results
$series_list = [];

if ($result->num_rows > 0) {
    // Fetch all data as an array of associative arrays
    $series_list = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $series_list = [];
}

// Prepare Timber context
$context = Timber::context();
$context['series_list'] = $series_list;
// Render with Twig template
Timber::render('page-speed-and-feeds.twig', $context);

?>
