<?php 
/**
 * Template Name: Speed and Feeds Template
 * Description: Speed and Feeds Template
 */

// Connect to the Database
include 'db_connection.php';



$stmt = $conn->prepare("SELECT series, family, subtitle, tool_type, tool_sub_type from master_series_data WHERE speed_feed_page IS NOT NULL");
$stmt->execute();
$result = $stmt->get_result();

$series_list = [];

if ($result->num_rows > 0) {
    // Fetch all data as an array of associative arrays
    $series_list = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $series_list = [];
}

// Group series into the same top-level categories used on the Products page
// (Milling, Holemaking, Burrs, Miscellaneous). Burrs is pulled out of Specialty
// to match how /products treats it as its own category.
foreach ($series_list as &$series) {
    $tool_type = strtoupper($series['tool_type'] ?? '');
    if ($tool_type === 'MILLING') {
        $category = 'milling';
    } elseif ($tool_type === 'HOLEMAKING') {
        $category = 'holemaking';
    } elseif ($tool_type === 'SPECIALTY' && $series['tool_sub_type'] === 'Burrs') {
        $category = 'burrs';
    } else {
        $category = 'miscellaneous';
    }
    $series['category'] = $category;
}
unset($series);

// Prepare Timber context
$context = Timber::context();
$context['series_list'] = $series_list;

// Render with Twig template
Timber::render('page-speed-and-feeds.twig', $context);

?>
