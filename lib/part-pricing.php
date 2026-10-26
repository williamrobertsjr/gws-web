<?php
function gws_get_part_list_price($part) {
    global $wpdb;

    if (! $part) return null;

    return $wpdb->get_var(
        $wpdb->prepare(
            "SELECT LIST_PRICE FROM master_price_data WHERE part = %s LIMIT 1",
            $part
        )
    );
    error_log("LOOKUP PART: " . $part);
}

/**
 * Batched version of gws_get_part_list_price() for product loops (archive
 * pages) -- replaces one uncached query per product with a single query for
 * the whole page. Returns a [part => price] map; parts not found in the
 * table are simply absent from the result.
 */
function gws_get_part_list_prices( array $parts ) {
    global $wpdb;

    $parts = array_values( array_unique( array_filter( $parts ) ) );
    if ( empty( $parts ) ) {
        return [];
    }

    $placeholders = implode( ',', array_fill( 0, count( $parts ), '%s' ) );
    $rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT part, LIST_PRICE FROM master_price_data WHERE part IN ($placeholders)",
            $parts
        ),
        ARRAY_A
    );

    $prices = [];
    foreach ( $rows as $row ) {
        $prices[ $row['part'] ] = $row['LIST_PRICE'];
    }

    return $prices;
}