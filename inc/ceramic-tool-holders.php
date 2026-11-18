<?php
/**
 * Ceramic Tool Holders for Turning landing page support.
 *
 * - gws_cth_upload_image(): resolves a fixed image by its path under wp-content/uploads/.
 * - gws_cth_prepare(): resolves every image in inc/data/ceramic-tool-holders-turning.php.
 * - gws_cth_further_reading(): the posts picked in the page's "Further reading" ACF field.
 * - The ACF field group for that field, registered in PHP so it deploys with the theme.
 *
 * Used by page-ceramic-tool-holders-turning.php.
 */

const GWS_CTH_TEMPLATE = 'page-ceramic-tool-holders-turning.php';

/**
 * Returns url/width/height/srcset/alt for an image at uploads/{$relative_path}, or null when the
 * file is missing (so a missing upload drops the image instead of rendering a broken <img>).
 * When the file is a Media Library attachment, its generated sizes are offered as a srcset and
 * its alt text is used.
 */
function gws_cth_upload_image($relative_path, $alt = '') {
    if (!$relative_path) {
        return null;
    }

    $uploads = wp_get_upload_dir();
    $file = trailingslashit($uploads['basedir']) . ltrim($relative_path, '/');
    if (!is_file($file)) {
        return null;
    }

    $url = trailingslashit($uploads['baseurl']) . ltrim($relative_path, '/');
    $size = wp_getimagesize($file);
    $image = [
        'url' => $url,
        'width' => $size ? $size[0] : null,
        'height' => $size ? $size[1] : null,
        'srcset' => '',
        'alt' => $alt,
    ];

    $attachment_id = attachment_url_to_postid($url);
    if ($attachment_id) {
        $image['srcset'] = wp_get_attachment_image_srcset($attachment_id, 'full') ?: '';
        if ($alt === '') {
            $image['alt'] = (string) get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
        }
    }

    return $image;
}

/** Loads the page data file and swaps each image path for its resolved image (or null). */
function gws_cth_prepare() {
    $data = include get_template_directory() . '/inc/data/ceramic-tool-holders-turning.php';

    $data['hero_image'] = gws_cth_upload_image($data['hero_image'], 'GWS ceramic turning toolholders');
    $data['inserts_image'] = gws_cth_upload_image($data['inserts_image'], 'GWS ceramic inserts');

    foreach ($data['clamping_systems'] as &$clamp) {
        $clamp['image'] = gws_cth_upload_image($clamp['image'], $clamp['name'] . ' clamp with ceramic insert');
    }
    unset($clamp);

    foreach ($data['families'] as &$family) {
        $family['image'] = gws_cth_upload_image($family['image'], $family['code'] . ' ceramic turning toolholder');
    }
    unset($family);

    foreach ($data['industries'] as &$industry) {
        // Decorative: the tile's heading already names the industry.
        $industry['image'] = gws_cth_upload_image($industry['image'], '');
    }
    unset($industry);

    return $data;
}

/** Just the hero image (for the homepage, which doesn't need the rest of the page data). */
function gws_cth_hero_image() {
    $data = include get_template_directory() . '/inc/data/ceramic-tool-holders-turning.php';
    return gws_cth_upload_image($data['hero_image'], 'GWS ceramic turning toolholders');
}

/** Posts chosen in the page's "Further reading" field, in the order picked. */
function gws_cth_further_reading($post_id) {
    if (!function_exists('get_field')) {
        return [];
    }

    $ids = array_filter(array_map('intval', (array) get_field('further_reading', $post_id)));
    if (!$ids) {
        return [];
    }

    return Timber::get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        'post__in' => $ids,
        'orderby' => 'post__in',
        'posts_per_page' => count($ids),
        'ignore_sticky_posts' => true,
    ]);
}

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_gws_cth_further_reading',
        'title' => 'Ceramic Tool Holders: Further Reading',
        'fields' => [
            [
                'key' => 'field_gws_cth_further_reading',
                'label' => 'Further reading',
                'name' => 'further_reading',
                'type' => 'relationship',
                'instructions' => 'Up to two posts shown as article cards. Each card uses the post\'s featured image, first category, title and excerpt. The section is hidden when empty.',
                'post_type' => ['post'],
                'post_status' => ['publish'],
                'filters' => ['search', 'taxonomy'],
                'return_format' => 'id',
                'min' => 0,
                'max' => 2,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => GWS_CTH_TEMPLATE,
                ],
            ],
        ],
        'position' => 'normal',
        'active' => true,
    ]);
});
