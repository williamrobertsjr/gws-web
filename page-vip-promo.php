<?php 

// Connect to the Database
include 'db_connection.php';

// First, get the current user role
$userRole = get_current_user_role();

// Check if the user role is 'none' and redirect
if ( !is_user_logged_in()) {
    wp_redirect('https://www.gwstoolgroup.com/sign-in');
    exit; // Always call exit after wp_redirect
}

$promoItems = array("420703","420725","420503","420713","420729","310-002121","310-002075","310-002118","310-002021","310-002154","310100","310303","100955","311619","311381","100972","118043","QC29012","QC29012-TIN","QC29009","QC29006","QC29016","15518","QC29008","QC29014","12002500","12001285","12001250","12003125","11001875","12001610","12001590","427154","427225","427161","427141","427239","11006562","11007500","15007188","10283-010","18349-01T","10843-010","11283-01T","11304-000","11623-010","10723-010");

// Create a comma-separated placeholder string for the array values
$placeholders = implode(',', array_fill(0, count($promoItems), '?'));

// Prepare the SQL statement with placeholders
$stmt = $conn->prepare("SELECT p.*, r.QTY_ON_HAND, r.LIST_PRICE 
                        FROM `master_product_data` p 
                        JOIN `rapid_quote` r ON p.part = r.PN 
                        WHERE p.part IN ($placeholders)");

// Bind each item in $promoItems to the statement
$stmt->bind_param(str_repeat('s', count($promoItems)), ...$promoItems);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch results as an array
$promoResults = $result->fetch_all(MYSQLI_ASSOC);



$context = Timber::context();
$context['promoItems'] = $promoResults;
$context['userRole'] = get_current_user_role();

Timber::render('page-vip-promo.twig', $context); 

