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
    
    // First check if part is a retired/obsolete part
    $retired_stmt = $conn->prepare("SELECT * FROM `obsolete_staging` WHERE delist_pn = ?");
    $retired_stmt->bind_param("s", $part);
    $retired_stmt->execute();
    $retired_result = $retired_stmt->get_result();

    if ($retired_result->num_rows > 0) {
        // Part is retired
        $retired_data = $retired_result->fetch_assoc();

        // Prepare Timber context for retired part
        $context = Timber::context();
        $context['retired_part'] = $part;
        $context['replacement_part'] = $retired_data['replacement_pn'];
        $context['templateName'] = 'page-retired-part.twig'; // A new Twig template for retired parts

        // Render with Twig template for retired part
        Timber::render('page-retired-part.twig', $context);
    } else {
        // Prepare the SQL statement
        $stmt = $conn->prepare(
            "SELECT p.*, s.subtitle, s.data_fields, s.feat_icons, s.app_icons, 
                    s.p1, s.p2, s.p3, s.h1, s.h2, s.n1, s.n2, s.k1, s.k2, 
                    s.m1, s.m2, s.speed_feed_page, r.LIST_PRICE, r.QTY_ON_HAND, r.DURRIE_QTY_ON_HAND
            FROM `master_product_data` p
            LEFT JOIN `master_series_data` s ON p.series = s.series
            LEFT JOIN `rapid_quote` r ON r.PN = p.part
            WHERE p.part = ? AND p.web = 'Y'"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameter
        $stmt->bind_param("s", $part);

        // Execute the statement
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();


        if ($result->num_rows > 0) {
            $product_data = $result->fetch_assoc();

            $show_extra_stock_content = (
                isset($product_data['DURRIE_QTY_ON_HAND']) &&
                $product_data['DURRIE_QTY_ON_HAND'] > 0
            );
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
        $context['show_extra_stock_content'] = $show_extra_stock_content;
        
        // Render with Twig template
        Timber::render($templateName, $context);
    }
} else {
    // Handle the case where 'part' is not set in the URL
    status_header(400);
    wp_redirect(home_url('400'));
    exit();
}
?>