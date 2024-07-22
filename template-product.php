<?php 
/**
 * Template Name: Product Template
 * Description: Product Page Template
 */

// Connect to the Database
include 'db_connection.php';
$templateName = 'page-product.twig';
if (isset($_GET['part'])) {
    $part = filter_var($_GET['part'], FILTER_SANITIZE_STRING);

    $stmt = $conn->prepare(
        "SELECT p.*, s.subtitle, s.data_fields, s.feat_icons, s.app_icons, s.p1, s.p2, s.p3, s.h1, s.h2, s.n1, s.n2, s.k1, s.k2, s.h1, s.h2, s.m1, s.m2, s.speed_feed_page, r.QTY_ON_HAND
         FROM `master_product_data` p
         LEFT JOIN `master_series_data` s on p.series = s.series
         LEFT JOIN `rapid_quote` r on r.PN = p.part
         WHERE part = ?
         AND web = 'Y'");
    $stmt->bind_param("s", $part);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product_data = $result->fetch_assoc();
    } else {
        $product_data = null;
        status_header(404);
        wp_redirect(home_url('404'));
        exit();
    }
    $series_id = $product_data['series'];

    // Fetch associated products
    $product_stmt = $conn->prepare(
        "SELECT p.*, r.QTY_ON_HAND
         FROM `master_product_data` p
         LEFT JOIN `rapid_quote` r on p.part = r.PN
         WHERE p.series = ?");
    $product_stmt->bind_param("s", $series_id);
    $product_stmt->execute();
    $product_result = $product_stmt->get_result();
    $products = $product_result->fetch_all(MYSQLI_ASSOC);




    // Prepare Timber context
    $context = Timber::context();
    $context['product_data'] = $product_data;
    $context['products'] = $products; // Pass the product data to Twig
    $context['templateName'] = $templateName;
    // Render with Twig template
    Timber::render($templateName, $context);
} else {
    // Handle the case where 'part' is not set in the URL
}
