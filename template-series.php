<?php 
/**
 * Template Name: Series Template
 * Description: Series Page Template
 */

// Connect to the Database
include 'db_connection.php';

$series_slug = get_query_var('series_slug'); // e.g. "smart-cut" or "bal-tap"
$series_slug = strtolower(urldecode($series_slug));

if ($series_slug) {

    /* ─────────────────────────────
       Fetch the current series row
       (normalize DB value: spaces → hyphens, en-dashes → hyphens, lowercase)
    ───────────────────────────── */
    $stmt = $conn->prepare("
        SELECT *
        FROM `master_series_data`
        WHERE LOWER(REPLACE(REPLACE(series, ' ', '-'), '–', '-')) = ?
          AND `catalog` = 'Y'
        LIMIT 1");
    $stmt->bind_param("s", $series_slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $series_data = $result->fetch_assoc();
        $series_name = $series_data['series'];      // e.g. "Smart Cut" or "Bal-Tap"
        $family      = $series_data['family'];

        // Fetch all series in the same family
        $stmt = $conn->prepare("
            SELECT *
            FROM `master_series_data`
            WHERE family = ?
              AND `catalog` = 'Y'");
        $stmt->bind_param("s", $family);
        $stmt->execute();
        $family_series_result = $stmt->get_result();
        $family_series = $family_series_result->fetch_all(MYSQLI_ASSOC);

        // Fetch associated products
        $product_stmt = $conn->prepare("
            SELECT p.*, r.QTY_ON_HAND
            FROM `master_product_data` p
            LEFT JOIN `rapid_quote` r ON p.part = r.PN
            WHERE p.series = ?");
        $product_stmt->bind_param("s", $series_name);
        $product_stmt->execute();
        $product_result = $product_stmt->get_result();
        $products = $product_result->fetch_all(MYSQLI_ASSOC);

    } else {
        status_header(404);
        wp_redirect(home_url('404'));
        exit();
    }

    // Prepare Timber context
    $context = Timber::context();
    $context['seriesId']                 = $series_name;
    $context['series_data']              = $series_data;
    $context['series_data']['series_slug'] = $series_slug; // for image path
    $context['family_series']            = $family_series;
    $context['products']                 = $products;
    $context['page_title']               = "Series $series_name | GWS Tool Group";

    Timber::render('page-series-single.twig', $context);

} else {
    status_header(404);
    wp_redirect(home_url('404'));
    exit();
}