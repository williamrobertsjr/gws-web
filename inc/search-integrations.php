<?php
/**
 * FacetWP and SearchWP query integration.
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */


// Force FacetWP to ignore the archive query, and use the custom query instead
add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
    if ( $query->is_archive() && $query->is_main_query() ) {
      $is_main_query = false;
    }
    return $is_main_query;
  }, 10, 2 );

add_action( 'after_setup_theme', function() {
    add_theme_support( 'woocommerce' );
} );

add_filter( 'facetwp_render_output', function( $output ) {
    $output['settings']['milling_types']['showSearch'] = false;
    return $output;
  });

//   temporarily disable SearchWP integration with WP All Import
add_filter( 'searchwp\integration\wp_all_import\enabled', '__return_false' );

//  Only index 'product' post type for facetwp to speed up indexing and improve performance
add_filter( 'facetwp_indexer_query_args', function( $args ) {
  $args['post_type'] = ['product']; // Index only these post types
  return $args;
});

// // delete extra skus from woocommerce products
// if ( defined( 'WP_CLI' ) && WP_CLI ) {
//     require_once ABSPATH . 'wp-content/delete-duplicate-skus.php';
// }

