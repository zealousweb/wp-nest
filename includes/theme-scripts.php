<?php
/**
 * Theme enqueue scripts and styles.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
 * Read and cache the webpack asset manifest.
 *
 * @return array Manifest data as associative array.
 */
function themeManifest() {
	static $manifest = null;

	if ( is_null( $manifest ) ) {
		$manifest_path = get_template_directory() . '/assets/manifest.json';

		if ( file_exists( $manifest_path ) ) {
			$json     = file_get_contents( $manifest_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$manifest = json_decode( $json, true );
		} else {
			$manifest = array();
		}
	}

	return $manifest;
}

/**
 * Enqueue a compiled CSS file using the asset manifest.
 *
 * @param string $key   Manifest key (e.g. 'main.css').
 * @param array  $deps  Array of dependency handles.
 * @param string $media Media type for the stylesheet.
 * @return void
 */
function themeCSS( $key, $deps = array(), $media = 'all' ) {
	$manifest = themeManifest();
	$filename = isset( $manifest[ $key ] ) ? ltrim( $manifest[ $key ], '/' ) : 'css/' . ltrim( $key, '/' );

	$filepath = get_template_directory() . '/assets/' . $filename;
	$fileuri  = get_template_directory_uri() . '/assets/' . $filename;

	if ( file_exists( $filepath ) ) {
		$handle = THEME_PREFIX . '-' . sanitize_title( pathinfo( $filename, PATHINFO_FILENAME ) );
		wp_enqueue_style( $handle, $fileuri, $deps, _THEME_VERSION, $media );
	}
}

/**
 * Enqueue a compiled JS file using the asset manifest.
 *
 * @param string $key       Manifest key (e.g. 'main.js').
 * @param array  $deps      Array of dependency handles.
 * @param bool   $in_footer Whether to load script in footer.
 * @return void
 */
function themeJS( $key, $deps = array( 'jquery' ), $in_footer = true ) {
	$manifest = themeManifest();
	$filename = isset( $manifest[ $key ] ) ? ltrim( $manifest[ $key ], '/' ) : 'js/' . ltrim( $key, '/' );

	$filepath = get_template_directory() . '/assets/' . $filename;
	$fileuri  = get_template_directory_uri() . '/assets/' . $filename;

	if ( file_exists( $filepath ) ) {
		$handle = THEME_PREFIX . '-' . sanitize_title( pathinfo( $filename, PATHINFO_FILENAME ) );
		wp_enqueue_script( $handle, $fileuri, $deps, _THEME_VERSION, $in_footer );
	}
}

/**
 * Enqueue all theme scripts and styles.
 *
 * @return void
 */
function wpnestScripts() {
	// Default WordPress theme style.
	wp_enqueue_style( THEME_PREFIX . '-wp-style', get_stylesheet_uri(), array(), _THEME_VERSION );

	// Compiled main styles.
	themeCSS( 'main.css' );

	// Library styles.
	themeCSS( 'library/common.css' );

	// Library scripts.
	themeJS( 'runtime.js' );
	themeJS( 'library/swiper.js' );
	themeJS( 'library/fancyapps.js' );

	// Compiled main script.
	themeJS( 'main.js' );

	// Custom AJAX handling script from compiled modules.
	themeJS( 'modules/ajax-scripts.js' );

	// Localize the script to pass AJAX URL and nonce to JavaScript.
	wp_localize_script(
		THEME_PREFIX . '-ajax-scripts',
		'wpnest_ajax_object',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'ajax-nonce' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'wpnestScripts' );
