<?php
/**
 * Add SearchWP Redirects rules for the ceramic-insert turning toolholders
 * (series CD, CX, DN): "CD" and "Series CD" -> /series-CD, and each part
 * number -> /product/<part>, matching the existing rule format.
 *
 * Idempotent: skips queries that already have a rule (case-insensitive).
 *
 *   wp eval-file scripts/add-toolholder-search-redirects.php          # dry run
 *   wp eval-file scripts/add-toolholder-search-redirects.php apply    # write
 */
global $wpdb;
$apply  = in_array( 'apply', $args ?? array(), true );
$series = array( 'CD', 'CX', 'DN' );

$opt   = get_option( 'searchwp_redirects' );
$rules = $opt['redirects'] ?? array();
$have  = array();
foreach ( $rules as $r ) { $have[ strtolower( $r['query'] ) ] = true; }

$new = array();
$add = function ( $query, $target ) use ( &$new, &$have ) {
    if ( isset( $have[ strtolower( $query ) ] ) ) { return; }
    $have[ strtolower( $query ) ] = true;
    $new[] = array( 'query' => $query, 'partial' => false, 'engines' => null, 'redirect' => $target );
};

foreach ( $series as $s ) {
    $add( $s, '/series-' . $s );
    $add( 'Series ' . $s, '/series-' . $s );
}
$in    = implode( ',', array_fill( 0, count( $series ), '%s' ) );
$parts = $wpdb->get_col( $wpdb->prepare( "SELECT part FROM master_product_data WHERE series IN ($in) ORDER BY part", $series ) );
foreach ( $parts as $p ) { $add( $p, '/product/' . $p ); }

echo count( $rules ) . " existing rules; " . count( $new ) . " to add\n";
if ( ! $apply ) { echo "Dry run. Re-run with 'apply' to write.\n"; return; }
if ( ! $new )   { return; }

$opt['redirects'] = array_merge( $rules, $new );
update_option( 'searchwp_redirects', $opt );
echo "Added. Total now " . count( $opt['redirects'] ) . "\n";
