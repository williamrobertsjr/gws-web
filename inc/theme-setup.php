<?php
/**
 * Stylesheet enqueue, Timber context additions, WooCommerce support, Yoast/SEO tweaks.
 *
 * Split out of functions.php. Loaded from there in the original order,
 * so hook registration order is unchanged.
 */


/**
 * Stylesheets live in assets/css/, except style.css.
 *
 * style.css has to stay at the theme root: WordPress reads the theme header
 * (Theme Name, etc.) from <theme>/style.css, and it is also the Tailwind input
 * that package.json's build:css compiles into assets/css/output.css.
 *
 * Because Tailwind copies non-directive CSS straight through, output.css already
 * contains everything in style.css. Both are enqueued below, so those rules ship
 * twice. Dropping the 'custom-style' enqueue would fix that, but only after
 * running `npm run build:css` -- output.css is gitignored and can lag style.css,
 * and loading it second is currently what masks a stale build.
 */
function enqueue_tailwind_output_styles() {
    wp_enqueue_style( 'tailwind-output', get_template_directory_uri() . '/assets/css/output.css', array(), filemtime( get_template_directory() . '/assets/css/output.css' ) );
    wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/style.css', array('tailwind-output'), filemtime( get_template_directory() . '/style.css' ) );
    wp_enqueue_style( 'mega-menu', get_template_directory_uri() . '/assets/css/mega-menu.css', array('custom-style'), filemtime( get_template_directory() . '/assets/css/mega-menu.css' ) );
}
add_action( 'wp_enqueue_scripts', 'enqueue_tailwind_output_styles' );

add_filter('timber/context', function($context) {
    $context['top_banner'] = get_field('top_banner', 'option');
    $context['banner_start_date'] = get_field('banner_start_date', 'option');
    $context['banner_end_date'] = get_field('banner_end_date', 'option');
    return $context;
});


// Woocommerce integration with Timber
function theme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'theme_add_woocommerce_support');

function timber_set_product($post)
{
    global $product;

    if (is_woocommerce()) {
        $product = wc_get_product($post->ID);
    }
}

remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail');

// Customize SEO title for product category pages to show just Category name without 'Archives' 
add_filter('wpseo_title', function($title) {
    if (is_product_category()) {
        $term = get_queried_object();
        return $term->name . ' | ' . get_bloginfo('name');
    }
    return $title;
});

// Set Global Options conetext from ACF Pro options page
add_filter('timber/context', function($context) {
    $context['options'] = get_fields('option');
    return $context;
});

add_filter( 'timber/twig', function( $twig ) {
    $twig->addFilter( new \Twig\TwigFilter( 'custom_excerpt', function( $text, $length = 20 ) {
        return wp_trim_words( $text, $length );
    } ) );
    return $twig;
} );

add_filter( 'wpseo_metabox_prio', 'lower_yoast_metabox_priority' );

/**
 * Lowers the metabox priority to 'core' for Yoast SEO's metabox.
 *
 * @param string $priority The current priority.
 *
 * @return string $priority The potentially altered priority.
 */
function lower_yoast_metabox_priority( $priority ) {
  return 'low';
}

