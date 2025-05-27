<?php

/**
 * Load the theme functions.
 */

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

/**
 * edit page/post.
 */
function wpnest_edit_post_link()
{
    if (get_edit_post_link() && ! is_admin_bar_showing()) {
        echo '<div class="entry-footer">';
        edit_post_link(
            sprintf(
                __('Edit <span class="screen-reader-text">%s</span>', 'textdomain'),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
        echo '</div>';
    }

    /**
     * Example Usage
     * echo wpnest_edit_post_link();
     */
}

/**
 * Placeholder image.
 */
function placeholder_image($title = 'Banner')
{
    $placeholder_url = get_template_directory_uri() . '/sources/images/placeholder-image.jpg';
    return '<img src="' . esc_url($placeholder_url) . '" alt="' . esc_attr($title) . '">';

    /**
     * Example Usage
     * echo placeholder_image();
     * echo placeholder_image('Title');
     */
}

/**
 * SVG image.
 *
 * * @param int $attachment_id  The image attachment ID.
 */
function acf_svg($attachment_id)
{
    $file_path = get_attached_file($attachment_id);

    if (! $file_path || ! file_exists($file_path)) {
        return '';
    }

    $mime = mime_content_type($file_path);
    if (strpos($mime, 'svg') !== false) {
        return file_get_contents($file_path);
    }

    return '';

    /**
     * Example Usage
     * echo acf_svg( $attachment_id );
     */
}

/**
 * Entry banner.
 */
function entry_banner()
{
    if (is_singular()) {
        $banner_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        $title = get_the_title();
    } elseif (is_category()) {
        $banner_image = '';
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $banner_image = '';
        $title = single_tag_title('', false);
    } elseif (is_post_type_archive()) {
        $banner_image = '';
        $title = post_type_archive_title('', false);
    } elseif (is_archive()) {
        $banner_image = '';
        $title = get_the_archive_title();
    } elseif (is_home()) {
        $banner_image = '';
        $title = get_the_title(get_option('page_for_posts'));
    } elseif (is_search()) {
        $banner_image = '';
        $title = 'Search Results for: ' . get_search_query();
    } elseif (is_404()) {
        $banner_image = '';
        $title = 'Page Not Found';
    } else {
        $banner_image = '';
        $title = get_the_title();
    }

    echo '<section class="entry-banner">';
    if (!empty($banner_image)) {
        echo '<img src="' . esc_url($banner_image) . '" alt="' . esc_attr($title) . '">';
    } else {
        echo placeholder_image($title);
    }
        echo '<div class="entry-header">';
            echo '<h1 class="entry-title">' . esc_html($title) . '</h1>';
        echo '</div>';
    echo '</section>';

    /**
     * Example Usage
     * echo hero_innerbanner();
     */
}

/**
 * Generate an accessible ACF link with fallback title.
 *
 * @param array  $link        The ACF link array.
 * @param string $link_class  Additional CSS classes for styling.
 * @return string             HTML anchor tag or empty string.
 */
function acf_link($link, $link_class = '')
{
    if (! is_array($link) || empty($link['url'])) {
        return ''; // Return empty string if the link is invalid.
    }

    // Extract link values.
    $link_url    = esc_url($link['url']);
    $link_title  = ! empty($link['title']) ? esc_html($link['title']) : __('Read More', 'textdomain'); // Fallback title.
    $link_target = ! empty($link['target']) ? '_blank' : '_self';
    $rel_attr    = ('_blank' === $link_target) ? 'noopener noreferrer' : 'nofollow';
    $link_class  = esc_attr($link_class ? $link_class : 'btn');

    // Return formatted accessible link.
    return sprintf(
        '<a class="%s" href="%s" target="%s" rel="%s" aria-label="%s">%s</a>',
        $link_class,
        $link_url,
        esc_attr($link_target),
        esc_attr($rel_attr),
        esc_attr($link_title), // `aria-label` improves accessibility.
        $link_title
    );
    /**
     * Example Usage
     * echo acf_link( $link, 'custom-button' );
     */
}

/**
 * Optimized function for rendering an ACF image using Image ID.
 *
 * @param int    $image_id   The image attachment ID.
 * @param string $size       Image size ('full', 'medium_large', etc.).
 * @param string $class      Additional CSS classes.
 * @return string            HTML img tag or empty string.
 */
function acf_image($image_id, $size = 'medium_large', $class = '')
{
    if (empty($image_id)) {
        return ''; // Return empty string if image ID is not valid.
    }

    // Get image attributes.
    $image_url  = wp_get_attachment_image_url($image_id, $size);
    $image_alt  = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    $image_alt  = ! empty($image_alt) ? esc_attr($image_alt) : esc_attr(get_the_title()) . ' Image';
    $srcset     = wp_get_attachment_image_srcset($image_id, $size);

    // Return optimized image tag.
    return sprintf(
        '<img src="%s" srcset="%s" alt="%s" class="%s" loading="lazy" decoding="async" width="%s" height="%s">',
        esc_url($image_url),
        esc_attr($srcset),
        $image_alt,
        esc_attr($class),
        esc_attr(wp_get_attachment_metadata($image_id)['width'] ?? ''),
        esc_attr(wp_get_attachment_metadata($image_id)['height'] ?? '')
    );

    /**
     * Example Usage
     * echo acf_image( $image_id, 'full', 'banner-image' );
     */
}

/**
 * Trim text to a specific word count.
 *
 * @param string $text   The text to trim.
 * @param int    $limit  The word limit.
 * @return string        Trimmed text.
 */
function trim_excerpt($text, $limit = 55)
{
    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos   = array_keys($words);
        $text  = substr($text, 0, $pos[$limit]) . '...';
    }
    return esc_html($text);
}

function wpnest_archive_post()
{
    ob_start();

    // Display search heading and search form
    echo "<div>" . esc_html__('Search', 'textdomain') . "</div>";

    get_search_form();

    // Fetch categories excluding 'Uncategorized'
    $categories = get_categories(
        array(
            'exclude' => 1 // Replace 1 with the actual ID of "Uncategorized" if different
        )
    );

    // Display category dropdown if categories exist
    if (!empty($categories)) {
        echo "<div>" . esc_html__('Filter by Category', 'textdomain') . "</div>";
        echo "
        <select name='postcategory' id='postcategory'>
            <option value=''>" . esc_html__('Select Category', 'textdomain') . "</option>";
        foreach ($categories as $category) {
            echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
        }
        echo "</select>";
    }

    // Loading indicator for AJAX requests
    echo "
    <div class='loading' style='display:none;'>" . esc_html__('Loading...', 'textdomain') . "</div>
    <div class='blog-listing'>";

    // Check if there are posts available
    if (have_posts()) :
        while (have_posts()) :
            the_post();
            echo '
            <article>
                <h2><a href="' . esc_url(get_the_permalink()) . '">' . esc_html(get_the_title()) . '</a></h2>
                <p>' . esc_html(get_the_excerpt()) . '</p>
            </article>';
        endwhile;

        // Pagination Section
        echo "<div class='pagination'>";

        global $wp_query;
        $big = 999999999; // Need an unlikely integer to replace with the actual page number
        echo paginate_links(array (
            'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))), // Base URL format
            'format'    => '?paged=%#%', // Pagination format
            'current'   => max(1, get_query_var('paged')), // Current page number
            'total'     => $wp_query->max_num_pages, // Total number of pages
            'mid_size'  => 2, // Number of pagination links to show around the current page
            'end_size'  => 1, // Number of pagination links to show at the beginning and end
            'prev_next' => false, // Removes "Prev" and "Next" links
        ));

        echo "</div>"; // End of pagination
    else :
        // Display message if no posts are found
        echo "<p>" . esc_html__('No posts found.', 'textdomain') . "</p>";
    endif;

    echo "</div>"; // End of blog listing container

    // Reset post data after custom query
    wp_reset_postdata();

    // Return the buffered content
    return ob_get_clean();
}
