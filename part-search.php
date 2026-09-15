<?php
// part-search.php
// This file grab the native WP Search query string - ex: ?s=150012 - and checks if it's a part number and redirects accordingly

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php'; // Make sure this path is correct
require_once __DIR__ . '/inc/part-number.php';

// Get the part number from the query string
$partNumber = $_GET['s'] ?? '';

// Match case- and dash-insensitively (e.g. "450-100781a" and "450100781A"
// both match "450-100781A"), but redirect using the canonical part number
// as stored in master_product_data.
$normalizedPartNumber = gws_normalize_part_number($partNumber);

// Prepare and execute the query
$stmt = $conn->prepare("SELECT part FROM master_product_data WHERE REPLACE(REPLACE(UPPER(part), '-', ''), ' ', '') = ?");
$stmt->bind_param("s", $normalizedPartNumber);
$stmt->execute();

// Check for results
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // If the part number exists, redirect using the canonical stored value
    $row = $result->fetch_assoc();
    header('Location: /product/?part=' . urlencode($row['part']));
    exit;
} else {
    // Redirect to WordPress search if the part number does not exist
    header('Location: /?s=' . urlencode($partNumber));
    exit;
}

// Close statement and connection
$stmt->close();
$conn->close();
