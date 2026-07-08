<?php
/**
 * Theme filter hooks.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
 * Allow SVG uploads for administrators only.
 *
 * Restricting SVG uploads to users with manage_options capability
 * prevents XSS attacks from malicious SVG files uploaded by lower-role users.
 *
 * @param array $mimes Allowed MIME types.
 * @return array Modified MIME types array.
 */
function allow_svg_upload( $mimes ) {
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 'allow_svg_upload' );

/**
 * Fix MIME type check for SVG files (bypass "not allowed" error).
 *
 * @param array  $data     File data array.
 * @param string $file     Full path to the file.
 * @param string $filename Filename being checked.
 * @param array  $mimes    Array of permitted MIME types.
 * @return array Modified file data array.
 */
function fix_svg_filetype_check( $data, $file, $filename, $mimes ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return $data;
	}
	$ext = pathinfo( $filename, PATHINFO_EXTENSION );
	if ( strtolower( $ext ) === 'svg' ) {
		$data['type'] = 'image/svg+xml';
		$data['ext']  = 'svg';
	}
	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'fix_svg_filetype_check', 10, 4 );

/**
 * Clean up WordPress head bloat.
 *
 * Removes unnecessary tags, emoji scripts, and endpoints from the <head>.
 *
 * @return void
 */
function wpnest_head_cleanup() {
	// Remove the WordPress version generator tag (security).
	remove_action( 'wp_head', 'wp_generator' );

	// Remove unnecessary link tags.
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );

	// Remove REST API link tag from head.
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

	// Remove Emoji scripts and styles.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'wpnest_head_cleanup' );
