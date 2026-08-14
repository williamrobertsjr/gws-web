<?php
/**
 * URL rewrites, legacy redirects, permalink structure, mega-menu context.
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */


// ─────────────────────────────────────────────────────────────
// Rewrite /series-slug → index.php?pagename=series&series_id=slug
// ─────────────────────────────────────────────────────────────
add_action('init', function () {
  add_rewrite_rule(
    '^series-([^/]+)/?$',
    'index.php?pagename=series&series_id=$matches[1]',
    'top'
  );
});

// Register the custom query variable "series_id"
add_filter('query_vars', function ($vars) {
  $vars[] = 'series_id';
  return $vars;
});


// ─────────────────────────────────────────────────────────────
// Redirect legacy ?series_id=Smart%20Cut URLs to /series-smart-cut
// ─────────────────────────────────────────────────────────────
add_action('template_redirect', function () {
  if (is_page('series') && isset($_GET['series_id'])) {
    $raw = $_GET['series_id'];

    // If URL contains spaces (e.g. from %20), redirect to proper hyphenated slug
    if (strpos($raw, ' ') !== false) {
      $hyphenated = str_replace(' ', '-', $raw);
      $redirect_url = home_url("/series-$hyphenated");
      wp_redirect($redirect_url, 301);
      exit;
    }
  }
});

// ─────────────────────────────────────────────────────────────
// Redirect legacy /product-index URL to /products, now that the
// Product Index content lives on the Products page itself.
// ─────────────────────────────────────────────────────────────
add_action('template_redirect', function () {
  if (is_page('product-index')) {
    wp_redirect(home_url('/products'), 301);
    exit;
  }
});

// Function to modify permalink structure for sub type custom post types
function tooltype_permalink_structure($post_link, $post, $leavename) {
    if (strpos($post_link, '%tool_type%') === FALSE) return $post_link;

    // Get taxonomy terms
    $terms = wp_get_object_terms($post->ID, 'tool_type');
    if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) {
        $taxonomy_slug = $terms[0]->slug;
    } else {
        $taxonomy_slug = 'general';  // default slug if no term is found
    }

    return str_replace('%tool_type%', $taxonomy_slug, $post_link);
}
add_filter('post_type_link', 'tooltype_permalink_structure', 1, 3);



// Function to add Max Mega Menu plugin to base.twig
function get_my_menu() {
    return wp_nav_menu(array(
        'theme_location' => 'max_mega_menu_1',
        'echo' => false
    ));
}
add_filter('timber/context', function ($context) {
    $context['my_menu'] = get_my_menu();
    return $context;
});
