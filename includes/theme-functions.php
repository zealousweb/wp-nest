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
	return '<img src="' . esc_url( $placeholder_url ) . '" alt="' . esc_attr( $title ) . '">';
}

/**
 * Return the raw SVG markup for an attachment.
 *
 * @param int $attachment_id The image attachment ID.
 * @return string SVG markup or empty string.
 */
function acf_svg( $attachment_id ) {
	$file_path = get_attached_file( $attachment_id );

	if ( ! $file_path || ! file_exists( $file_path ) ) {
		return '';
	}

	$mime = mime_content_type( $file_path );
	if ( strpos( $mime, 'svg' ) !== false ) {
		return file_get_contents( $file_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	return '';
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

	echo '<section class="entry-banner">';
	if ( ! empty( $banner_image_id ) ) {
		echo wp_get_attachment_image( $banner_image_id, 'full' );
	} else {
		echo wp_kses_post( placeholder_image( $title ) );
	}
	echo '<div class="entry-header">';
	echo '<h1 class="entry-title">' . esc_html( $title ) . '</h1>';
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
 * @param string $text  The text to trim.
 * @param int    $limit The word limit.
 * @return string Trimmed and escaped text.
 */
function trim_excerpt( $text, $limit = 55 ) {
	if ( str_word_count( $text, 0 ) > $limit ) {
		$words = str_word_count( $text, 2 );
		$pos   = array_keys( $words );
		$text  = substr( $text, 0, $pos[ $limit ] ) . '...';
	}
	return esc_html( $text );
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
	echo '<div>' . esc_html__( 'Search', TEXT_DOMAIN ) . '</div>';

	get_search_form();

	// Fetch categories excluding 'Uncategorized'.
	$categories = get_categories(
		array(
			'exclude' => 1,
		)
	);

	// Display category dropdown if categories exist.
	if ( ! empty( $categories ) ) {
		echo '<div>' . esc_html__( 'Filter by Category', TEXT_DOMAIN ) . '</div>';
		echo "<select name='postcategory' id='postcategory'>
            <option value=''>" . esc_html__( 'Select Category', TEXT_DOMAIN ) . '</option>';
		foreach ( $categories as $category ) {
			echo '<option value="' . esc_attr( $category->slug ) . '">' . esc_html( $category->name ) . '</option>';
		}
		echo '</select>';
	}

	// Loading indicator for AJAX requests.
	echo "
    <div class='loading' style='display:none;'>" . esc_html__( 'Loading...', TEXT_DOMAIN ) . "</div>
    <div class='blog-listing'>";

	// Check if there are posts available.
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			echo '
            <article>
                <h2><a href="' . esc_url( get_the_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h2>
                <p>' . esc_html( get_the_excerpt() ) . '</p>
            </article>';
		endwhile;

		// Pagination section.
		echo "<div class='pagination'>";

		global $wp_query;
		$big = 999999999;
		echo wp_kses_post(
			paginate_links(
				array(
					'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'    => '?paged=%#%',
					'current'   => max( 1, get_query_var( 'paged' ) ),
					'total'     => $wp_query->max_num_pages,
					'mid_size'  => 2,
					'end_size'  => 1,
					'prev_next' => false,
				)
			)
		);

		echo '</div>';
	else :
		echo '<p>' . esc_html__( 'No posts found.', TEXT_DOMAIN ) . '</p>';
	endif;

	echo '</div>';

	// Reset post data after custom query.
	wp_reset_postdata();

	return ob_get_clean();
}
