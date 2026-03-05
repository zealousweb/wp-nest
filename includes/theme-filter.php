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
 * Allow SVG uploads for all users.
 *
 * @param array $mimes Allowed MIME types.
 * @return array Modified MIME types array.
 */
function allow_svg_upload( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
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
	$ext = pathinfo( $filename, PATHINFO_EXTENSION );
	if ( strtolower( $ext ) === 'svg' ) {
		$data['type'] = 'image/svg+xml';
		$data['ext']  = 'svg';
	}
	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'fix_svg_filetype_check', 10, 4 );
