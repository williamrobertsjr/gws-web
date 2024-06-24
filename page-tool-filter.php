<?php 
/**
 * Template Name: Tool Filter
 * Description: Tool Filter Template
 */

// Connect to the Database
include 'db_connection.php';
$templateName = 'page-tool-filter.twig';

// Check if it's an AJAX request for SUB TYPES 
if (isset($_GET['ajax']) && $_GET['ajax'] == 'getSubTypes') {
    // Get the toolType from the request
    $toolType = $_GET['toolType'];
    // If it is inserts run this query immediately
    if($toolType === 'Inserts') {
        $inserts_stmt = $conn->prepare("SELECT thickness,width,ic,radius_in_display,edge_prep,iso_code FROM `master_product_data` WHERE tool_type = 'Inserts'");
        $inserts_stmt->execute();

        // Fetch the results
        $inserts_result = $inserts_stmt->get_result();
        $inserts = $inserts_result->fetch_all(MYSQLI_ASSOC);

        echo json_encode($inserts);
        exit;      
    } else {
        // If it's any other tool type - proceed with the sub types query
        // Prepare the SQL statement
        $subType_stmt = $conn->prepare("SELECT DISTINCT sub_type FROM `master_product_data` WHERE tool_type = ?");
        $subType_stmt->bind_param("s", $toolType);
        $subType_stmt->execute();

        // Fetch the results
        $subType_result = $subType_stmt->get_result();
        $subTypes = $subType_result->fetch_all(MYSQLI_ASSOC);

        // Return the data as JSON (to be use in tool-filter.js)
        echo json_encode($subTypes);
        exit;
    }
}

// Check if it's an AJAX request for the SUB SUB TYPES
if (isset($_GET['ajax']) && $_GET['ajax'] == 'getSubSubType') {
    // Get the toolType and subType from the request
    $toolType = $_GET['toolType'];
    $subType = $_GET['subType'];

    // Initialize an empty filters array
    $filters = [];

    // Prepare and execute your SQL queries based on toolType and subType
    $filters_stmt = $conn->prepare("SELECT sub_type, sub_sub_type, data_filters FROM `filters_config` WHERE tool_type = ? AND sub_type = ?");

    $filters_stmt->bind_param("ss", $toolType, $subType);
    if ($filters_stmt === false) {
        // Handle error - for debugging, you might want to output the error
        die("Error preparing statement: " . $conn->error);
    }
    $filters_stmt->execute();
    
    // Fetch the results
    $filters_result = $filters_stmt->get_result();
    $filters = $filters_result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($filters);
    exit;
}

// Check if it's an AJAX request for FILTERS
if (isset($_GET['ajax']) && $_GET['ajax'] == 'getFilters') {
    $toolType = $_GET['toolType'];
    $subType = $_GET['subType'];
    $subSubType = isset($_GET['subSubType']) ? $_GET['subSubType'] : null;

    if ($subSubType) {
        // SQL query when subSubType is provided
        $filters_stmt = $conn->prepare("SELECT * FROM `filters_config` WHERE tool_type = ? AND sub_type = ? AND sub_sub_type = ?");
        $filters_stmt->bind_param("sss", $toolType, $subType, $subSubType);
    } else {
        // SQL query when only toolType and subType are provided
        $filters_stmt = $conn->prepare("SELECT * FROM `filters_config` WHERE tool_type = ? AND sub_type = ?");
        $filters_stmt->bind_param("ss", $toolType, $subType);
    }
    if ($filters_stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $filters_stmt->execute();
    // Fetch the results
    $filters_result = $filters_stmt->get_result();
    $filters = $filters_result->fetch_all(MYSQLI_ASSOC);
    // echo json_encode($filters);
    
    $allDataFields = [];
    foreach ($filters as $filter) {
        $splitFields = explode(',', $filter['data_filters']);
        $allDataFields = array_merge($allDataFields, $splitFields);
    }
    $allDataFields = array_unique($allDataFields);

    // Fetch distinct values for each field in $allDataFields
    $distinctValues = [];
    foreach ($allDataFields as $field) {
        if ($field) {
            if ($subSubType) {
                $stmt = $conn->prepare("SELECT DISTINCT $field FROM `master_product_data` WHERE tool_type = ? AND sub_type = ? AND sub_sub_type = ? ORDER BY $field ASC");
                error_log("SQL Query: $field, $toolType, $subType, $subSubType");
                $stmt->bind_param("sss", $toolType, $subType, $subSubType);
                $stmt->execute();
            } else {
                $stmt = $conn->prepare("SELECT DISTINCT $field FROM `master_product_data` WHERE tool_type = ? AND sub_type = ? ORDER BY $field ASC");
                $stmt->bind_param("ss", $toolType, $subType);
                $stmt->execute();
            }
            
            $result = $stmt->get_result();
            $distinctValues[$field] = $result->fetch_all(MYSQLI_ASSOC);
        }
    }
    // Combine $filters and distinct values into one array
    $response = [
        'filters' => $filters,
        'distinctValues' => $distinctValues
    ];
    // Send a single JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Grab selected values from filter to pass data back to JS to build table
if (isset($_GET['ajax']) && $_GET['ajax'] == 'getTableData') {
    $queryParams = $_GET;
    unset($queryParams['ajax']); // Remove 'ajax' parameter
    $toolType = $queryParams['toolType'];
    $subType = $queryParams['subType'] ?? null;
    $subSubType = $queryParams['subSubType'] ?? null;

    // Decode URL-encoded query parameters
    foreach ($queryParams as $key => $value) {
        $queryParams[$key] = urldecode($value);
    }

    // Fetching the filter configuration
    $stmt = $conn->prepare("SELECT data_filters FROM filters_config WHERE tool_type = ?" . ($subType ? " AND sub_type = ?" : "") . ($subSubType ? " AND sub_sub_type = ?" : ""));
    $bindParams = [$toolType];
    $bindTypes = 's';
    if ($subType) {
        $bindParams[] = $subType;
        $bindTypes .= 's';
    } 
    if ($subSubType) {
        $bindParams[] = $subSubType;
        $bindTypes .= 's';
    }
    $stmt->bind_param($bindTypes, ...$bindParams);
    $stmt->execute();
    $result = $stmt->get_result();
    $filtersData = $result->fetch_assoc();
    $dataFilters = explode(',', $filtersData['data_filters']);

    // Constructing the query for master_product_data
    $sql = "SELECT p.part as Part, p.series as Series, " . implode(', ', $dataFilters) . ", ROUND(r.QTY_ON_HAND, 0) as Stock FROM master_product_data p LEFT JOIN rapid_quote r on p.part = r.PN  WHERE p.tool_type = ?" . ($subType ? " AND p.sub_type = ?" : "") . ($subSubType ? " AND p.sub_sub_type = ?" : "");
    $bindParams = [$toolType];
    $bindTypes = 's';
    if ($subType) {
        $bindParams[] = $subType;
        $bindTypes .= 's';
    } 
    if ($subSubType) {
        $bindParams[] = $subSubType;
        $bindTypes .= 's';
    }

    // Appending conditions for each filter
    foreach ($queryParams as $key => $value) {
        if (in_array($key, $dataFilters) && $value !== '') {
            $sql .= " AND $key = ?";
            $bindTypes .= 's';
            $bindParams[] = $value;
        }
    }

    // Prepare and execute the query
    try {
        $query_stmt = $conn->prepare($sql);
        if (!$query_stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $query_stmt->bind_param($bindTypes, ...$bindParams);
        $query_stmt->execute();
        $query_result = $query_stmt->get_result();
        $data = $query_result->fetch_all(MYSQLI_ASSOC);
        // Include toolType in the response
        $response = [
            'toolType' => $toolType,
            'subType'  => $subType,
            'subSubType' => $subSubType,
            'data' => $data
        ];
        echo json_encode($response);
    } catch (Exception $e) {
        // Handle any errors
        error_log('Error in getTableData: ' . $e->getMessage());
        echo 'Error processing request.';
    }
    exit;
}









    // Prepare Timber context
    $context = Timber::context();
    $context['product_data'] = $product_data;
    $context['products'] = $products; // Pass the product data to Twig
    $context['templateName'] = $templateName;
    // Render with Twig template
    Timber::render($templateName, $context);
