<?php
// part-search.php
// This file grab the native WP Search query string - ex: ?s=150012 - and checks if it's a part number and redirects accordingly

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php'; // Make sure this path is correct

// Get the part number from the query string
$partNumber = $_GET['s'] ?? '';

// Prepare and execute the query
$stmt = $conn->prepare("SELECT part FROM master_product_data WHERE part = ?");
$stmt->bind_param("s", $partNumber);
$stmt->execute();

// Check for results
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // If the part number exists, redirect
    header('Location: /product/?part=' . urlencode($partNumber));
    exit;
} else {
    // Redirect to WordPress search if the part number does not exist
    header('Location: /?s=' . urlencode($partNumber));
    exit;
}

// Close statement and connection
$stmt->close();
$conn->close();
