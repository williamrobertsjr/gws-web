<?php 

// Connect to the Database
include 'db_connection.php';

// Find the position of the tool sub type
$currentUrl = home_url($wp->request);
$subTypePosition = strrpos($currentUrl, "/");
$pageSubType = substr($currentUrl, $subTypePosition + 1);

function formatString($pageSubType) {
    $pageSubType = str_replace('-', ' ', $pageSubType);
    $pageSubType = ucwords($pageSubType);
    // Add or adjust mappings as needed
    $mappings = [
        'Drill Mills' => 'Drill/Mills',
        'Drills Countersinks' => 'drills/countersinks',
        'Aerospace Drills' => 'Drills - Aerospace',
        'Drill Taps' => 'Drill/Taps',
        'Aerospace Countersinks' => 'Countersinks - Aerospace',
        'Aerospace Rivet Shavers' => 'Rivet Shavers - Aerospace',
        'Aerospace Reverse Spot Facers' => 'Reverse Spot Facers - Aerospace',
        'Whiskered Ceramic' => 'Inserts'
    ];

    return $mappings[$pageSubType] ?? $pageSubType;
}   

$pageSubType = formatString($pageSubType);

// Fetch all series with the same tool_sub_type
$series_list = [];
if ($pageSubType) {
    $stmt = $conn->prepare("SELECT * FROM `master_series_data` WHERE tool_sub_type = ? AND `catalog` = 'Y'");
    $stmt->bind_param("s", $pageSubType);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $series_list = $result->fetch_all(MYSQLI_ASSOC);
    }

    // Special handling for 'Square' and 'Radius'
    if ($pageSubType === 'Square' || $pageSubType === 'Radius') {
        $additionalStmt = $conn->prepare("SELECT * FROM `master_series_data` WHERE tool_sub_type = 'Square and Radius' AND `catalog` = 'Y'");
        $additionalStmt->execute();
        $additionalResult = $additionalStmt->get_result();

        if ($additionalResult->num_rows > 0) {
            $additionalSeries = $additionalResult->fetch_all(MYSQLI_ASSOC);
            $series_list = array_merge($series_list, $additionalSeries);
        }
    }
}

// Sort the combined series list by the 'series' key
usort($series_list, function ($item1, $item2) {
    return $item1['series'] <=> $item2['series'];
});

// Prepare Timber context
$context = Timber::context();
$context['currentURL'] = $currentUrl;
$context['pageSubType'] = $pageSubType;
$context['series_list'] = $series_list; // Pass all series data to Twig

// Render with Twig template
Timber::render('single-custom_tools.twig', $context);

?>
