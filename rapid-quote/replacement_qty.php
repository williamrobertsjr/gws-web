<?php
include 'db_connection.php';
header('Content-Type: application/json'); // Set the content type to JSON for API responses

$partNumber = isset($_GET['partNumber']) ? $_GET['partNumber'] : '';
if (empty($partNumber)) {
    echo json_encode(['error' => 'No part number provided']);
    exit;
}

// Check for obsolete part number
$stmt = $conn->prepare("SELECT replacement_pn, obsolete_description FROM obsolete_staging WHERE delist_pn = ?");
$stmt->bind_param("s", $partNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $replacement_pn = $row['replacement_pn'];
    $obsolete_description = $row['obsolete_description'];

    // Fetch replacement part details including list price
    $stmt = $conn->prepare("SELECT qty_on_hand, list_price FROM rapid_quote WHERE PN = ?");
    $stmt->bind_param("s", $replacement_pn);
    $stmt->execute();
    $inventoryResult = $stmt->get_result();

    if ($inventoryResult->num_rows > 0) {
        $inventoryRow = $inventoryResult->fetch_assoc();
        echo json_encode([
            'replacement_pn' => $replacement_pn,
            'qty_on_hand' => $inventoryRow['qty_on_hand'],
            'obsolete_description' => $obsolete_description,
            'list_price' => $inventoryRow['list_price']
        ]);
    } else {
        echo json_encode([
            'replacement_pn' => $replacement_pn,
            'qty_on_hand' => 0,
            'list_price' => '0.00'  // Provide a default list price if not found
        ]);
    }
} else {
    echo json_encode(['message' => 'Part number not found or not obsolete']);
}
