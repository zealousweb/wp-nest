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

// List of script handles to defer
add_filter('script_loader_tag', function($tag, $handle) {

	// Only run on frontend
    if ( is_admin() ) {
        return $tag;
    }

    $defer_scripts = [
        THEME_PREFIX . '-custom-block',
    ];

    $async_scripts = [
		THEME_PREFIX . '-custom-block',
    ];

    if( !empty($defer_scripts) ) {
        if ( in_array($handle, $defer_scripts) ) {
            // Add defer if not already present
            if ( false === strpos($tag, 'defer') ) {
                return str_replace(' src', ' defer src', $tag);
            }
        }
    }

    if( !empty($async_scripts) ) {
        if ( in_array($handle, $async_scripts) ) {
            // Add defer if not already present
            if ( false === strpos($tag, 'async') ) {
                return str_replace(' src', ' async src', $tag);
            }
        }
    }

    return $tag;
}, 10, 2);
