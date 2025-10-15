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