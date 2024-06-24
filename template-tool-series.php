<?php 
/**
 * Template Name: Tool Type Series Page
 * Description: Tool Type Series Page Template
 */

// Connect to the Database
include 'db_connection.php';

// Find the position of '/series/'
$currentUrl = home_url( $wp->request );
$position = strpos($currentUrl, "/series/");

if ($position !== false) {
    // Extract the part after '/series/'
    $pageToolType = substr($currentUrl, $position + strlen("/series/"));
} else {
    $pageToolType = null;
}

// Initialize the toolSubTypes array
$toolSubTypes = [];

if ($pageToolType) {
    // Fetch distinct tool_sub_type for the given tool_type
    $stmt = $conn->prepare("SELECT DISTINCT tool_sub_type FROM `master_series_data` WHERE tool_type = ? AND `catalog` = 'Y'");
    $stmt->bind_param("s", $pageToolType);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $toolSubTypes[] = $row['tool_sub_type'];
        }
    }

    // Check if 'Square' or 'Radius' exists and add 'Square and Radius' if necessary
    if (in_array('Square', $toolSubTypes) || in_array('Radius', $toolSubTypes)) {
        if (!in_array('Square and Radius', $toolSubTypes)) {
            $toolSubTypes[] = 'Square and Radius';
        }
    }
}

// Fetch all series with the same tool_type
if ($pageToolType) {
    $stmt = $conn->prepare("SELECT * FROM `master_series_data` WHERE tool_type = ? AND `catalog` = 'Y'");
    $stmt->bind_param("s", $pageToolType);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch all data as an array of associative arrays
        $series_list = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $series_list = [];
    }
} else {
    $series_list = [];
}

// Prepare Timber context
$context = Timber::context();
$context['pageToolType'] = $pageToolType;
$context['series_list'] = $series_list; // Pass all series data to Twig
$context['toolSubTypes'] = $toolSubTypes; // Pass the distinct sub-types to Twig
$context['page_title'] = 'Series List for ' . $pageToolType;

// Render with Twig template
Timber::render('tool-series.twig', $context);

?>
