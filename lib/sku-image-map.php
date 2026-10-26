<?php
/**
 * Cached wrapper around inc/data/sku_image_map.php.
 *
 * That file is a ~21k-entry array literal. PHP's opcache caches the bytecode
 * that builds it, but not the resulting array value itself, so the array gets
 * reconstructed in memory on every single product/archive page request.
 * Caching the built result in a transient (Redis-backed via Object Cache Pro
 * where that's active, options-table-backed otherwise) means it's built once
 * per cache lifetime instead of on every page view.
 */
function gws_get_sku_image_map() {
    $cached = get_transient('gws_sku_image_map');
    if ($cached !== false) {
        return $cached;
    }

    require get_template_directory() . '/inc/data/sku_image_map.php';

    set_transient('gws_sku_image_map', $sku_image_map, 12 * HOUR_IN_SECONDS);

    return $sku_image_map;
}
