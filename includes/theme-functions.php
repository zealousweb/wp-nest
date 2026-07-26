<?php
/**
 * Theme utility functions.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
 * Display an edit link for the current page or post.
 *
 * @return void
 */
function wpnest_edit_post_link() {
	if ( get_edit_post_link() && ! is_admin_bar_showing() ) {
		echo '<div class="entry-footer">';
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post. */
				__( 'Edit <span class="screen-reader-text">%s</span>', TEXT_DOMAIN ),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
		echo '</div>';
	}
}

/**
 * Return a placeholder image tag.
 *
 * @param string $title Alt text for the placeholder image.
 * @return string HTML img tag.
 */
function placeholder_image( $title = 'Banner' ) {
	$placeholder_url = get_template_directory_uri() . '/sources/images/placeholder-image.jpg';
	return '<img src="' . esc_url( $placeholder_url ) . '" alt="' . esc_attr( $title ) . '" class="block w-full h-full object-cover">';
}

/**
 * Return the sanitised SVG markup for an attachment.
 *
 * Strips script tags and unsafe event-handler attributes to prevent XSS.
 *
 * @param int $attachment_id The image attachment ID.
 * @return string Sanitised SVG markup or empty string.
 */
function acf_svg( $attachment_id ) {
	$file_path = get_attached_file( $attachment_id );

	if ( ! $file_path || ! file_exists( $file_path ) ) {
		return '';
	}

	$mime = mime_content_type( $file_path );
	if ( strpos( $mime, 'svg' ) === false ) {
		return '';
	}

	$svg = file_get_contents( $file_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( empty( $svg ) ) {
		return '';
	}

	// Strip <script> tags and on* event attributes for XSS safety.
	$svg = preg_replace( '/<script[\s\S]*?<\/script>/i', '', $svg );
	$svg = preg_replace( '/\s+on\w+="[^"]*"/i', '', $svg );
	$svg = preg_replace( "/\s+on\w+='[^']*'/i", '', $svg );

	return $svg;
}

/**
 * Output the entry banner section with image and title.
 *
 * @return void
 */
function entry_banner() {
	$banner_image_id = 0;
	if ( is_singular() ) {
		$banner_image_id = get_post_thumbnail_id( get_the_ID() );
		$title           = get_the_title();
	} elseif ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	} elseif ( is_archive() ) {
		$title = get_the_archive_title();
	} elseif ( is_home() ) {
		$title = get_the_title( get_option( 'page_for_posts' ) );
	} elseif ( is_search() ) {
		$title = sprintf(
			/* translators: %s: search query. */
			esc_html__( 'Search Results for: %s', TEXT_DOMAIN ),
			get_search_query()
		);
	} elseif ( is_404() ) {
		$title = esc_html__( 'Page Not Found', TEXT_DOMAIN );
	} else {
		$title = get_the_title();
	}

	echo '<section class="relative overflow-hidden h-banner">';
	if ( ! empty( $banner_image_id ) ) {
		echo wp_get_attachment_image( $banner_image_id, 'wpnest-banner', false, array( 'class' => 'block w-full h-full object-cover' ) );
	} else {
		echo wp_kses_post( placeholder_image( $title ) );
	}
	echo '<div class="absolute inset-0 flex items-center justify-center p-3.5 bg-black/75">';
	echo '<h1 class="m-0 text-3xl font-bold text-white uppercase">' . esc_html( $title ) . '</h1>';
	echo '</div>';
	echo '</section>';
}

/**
 * Generate an accessible ACF link with fallback title.
 *
 * @param array  $link       The ACF link array.
 * @param string $css_class  Additional CSS classes for styling.
 * @return string HTML anchor tag or empty string.
 */
function acf_link( $link, $css_class = '' ) {
	if ( ! is_array( $link ) || empty( $link['url'] ) ) {
		return '';
	}

	$link_url    = esc_url( $link['url'] );
	$link_title  = ! empty( $link['title'] ) ? esc_html( $link['title'] ) : __( 'Read More', TEXT_DOMAIN );
	$link_target = ! empty( $link['target'] ) ? '_blank' : '_self';
	$rel_attr    = ( '_blank' === $link_target ) ? 'noopener noreferrer' : 'nofollow';
	$link_class  = esc_attr( $css_class ? $css_class : 'btn' );

	return sprintf(
		'<a class="%s" href="%s" target="%s" rel="%s" aria-label="%s">%s</a>',
		$link_class,
		$link_url,
		esc_attr( $link_target ),
		esc_attr( $rel_attr ),
		esc_attr( $link_title ),
		$link_title
	);
}

/**
 * Return an optimised img tag for an ACF image field.
 *
 * @param int    $image_id  The image attachment ID.
 * @param string $size      Image size slug ('full', 'medium_large', etc.).
 * @param string $css_class Additional CSS classes.
 * @return string HTML img tag or empty string.
 */
function acf_image( $image_id, $size = 'medium_large', $css_class = '' ) {
	if ( empty( $image_id ) ) {
		return '';
	}

	return wp_get_attachment_image( $image_id, $size, false, array( 'class' => $css_class ) );
}

/**
 * Trim text to a specific word count.
 *
 * Uses wp_trim_words() which is i18n-safe and handles multibyte character sets.
 *
 * @param string $text  The text to trim.
 * @param int    $limit The word limit.
 * @return string Trimmed text.
 */
function trim_excerpt( $text, $limit = 55 ) {
	return wp_trim_words( $text, $limit, '&hellip;' );
}


/**
 * Build and return the archive/blog listing HTML.
 *
 * Outputs a search form, category filter, post listing, and pagination.
 * Returns buffered HTML for use with wp_kses_post().
 *
 * @return string HTML output of the archive listing.
 */
function wpnest_archive_post() {
	ob_start();

	// Display search heading and search form.
	echo '<div class="text-sm font-semibold mb-2">' . esc_html__( 'Search', TEXT_DOMAIN ) . '</div>';

	get_search_form();

	// Fetch categories excluding 'Uncategorized'.
	$categories = get_categories(
		array(
			'exclude' => 1,
		)
	);

	// Display category dropdown if categories exist.
	if ( ! empty( $categories ) ) {
		echo '<div class="mt-4"><label for="postcategory" class="text-sm font-semibold mb-2 block">' . esc_html__( 'Filter by Category', TEXT_DOMAIN ) . '</label></div>';
		echo "<select name='postcategory' id='postcategory' class='border border-gray-200 rounded px-3 py-2 text-sm bg-white outline-none focus:border-primary mb-6 w-full max-w-xs'>
            <option value=''>" . esc_html__( 'Select Category', TEXT_DOMAIN ) . '</option>';
		foreach ( $categories as $category ) {
			echo '<option value="' . esc_attr( $category->slug ) . '">' . esc_html( $category->name ) . '</option>';
		}
		echo '</select>';
	}

	// Loading indicator for AJAX requests.
	echo "
    <div class='loading text-sm text-black/75 py-4' style='display:none;'>" . esc_html__( 'Loading...', TEXT_DOMAIN ) . "</div>
    <div class='blog-listing grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8'>";

	// Check if there are posts available.
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			echo '
            <article class="overflow-hidden flex flex-col rounded-lg bg-white shadow-md hover:-translate-y-1 hover:shadow-lg transition-all duration-300 ease-in-out p-6 border border-gray-200/50">
                <h2 class="text-xl font-bold leading-tight mb-2.5"><a href="' . esc_url( get_the_permalink() ) . '" class="text-black hover:text-primary transition-colors duration-250">' . esc_html( get_the_title() ) . '</a></h2>
                <p class="flex-1 text-sm leading-normal text-black/75 mb-5">' . esc_html( get_the_excerpt() ) . '</p>
            </article>';
		endwhile;

		// Pagination section.
		echo "<div class='pagination'>";

		global $wp_query;
		$big        = 999999999;
		$pagination = paginate_links(
			array(
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'    => '?paged=%#%',
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $wp_query->max_num_pages,
				'mid_size'  => 2,
				'end_size'  => 1,
				'prev_next' => false,
				'type'      => 'array',
			)
		);

		if ( ! empty( $pagination ) && is_array( $pagination ) ) {
			echo wp_kses_post( implode( '', $pagination ) );
		}

		echo '</div>';
	else :
		echo '<p>' . esc_html__( 'No posts found.', TEXT_DOMAIN ) . '</p>';
	endif;

	echo '</div>';

	// Reset post data after custom query.
	wp_reset_postdata();

	return ob_get_clean();
}
