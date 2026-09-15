<?php
/**
 * Shared part-number normalization.
 *
 * Plain PHP, no WordPress dependency, so it can be required from both
 * WP-bootstrapped code (inc/rest-quote.php) and standalone scripts
 * (part-search.php) that don't load WP.
 */

if ( ! function_exists( 'gws_normalize_part_number' ) ) {
    function gws_normalize_part_number( $value ) {
        return strtoupper( preg_replace( '/[\s-]+/', '', (string) $value ) );
    }
}
