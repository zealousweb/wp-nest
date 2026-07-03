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
 * Get Vite Dev Server URL from .env
 */
function viteDevServerUrl() {
	$host = getenv( 'VITE_DEV_SERVER' ) ? getenv( 'VITE_DEV_SERVER' ) : 'http://localhost';
	$port = getenv( 'VITE_DEV_SERVER_PORT' ) ? getenv( 'VITE_DEV_SERVER_PORT' ) : '5173';

	return rtrim( $host, '/' ) . ':' . $port;
}

/**
 * Detect if Vite dev server is running
 */
function isViteDev() {
	static $is_dev = null;

	if ( is_null( $is_dev ) ) {
		$is_dev = false;

		$url  = viteDevServerUrl();
		$host = wp_parse_url( $url, PHP_URL_HOST );
		$port = wp_parse_url( $url, PHP_URL_PORT );

		$handle = @fsockopen( $host, $port, $errno, $errstr, 0.1 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged,WordPress.WP.AlternativeFunctions.file_system_operations_fsockopen

		if ( $handle ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
			fclose( $handle );
			$is_dev = true;
		}
	}

	return $is_dev;
}

/**
 * Read and cache Vite manifest
 */
function themeManifest() {
	static $manifest = null;

	if ( is_null( $manifest ) ) {

		$manifest_path = get_template_directory() . '/assets/.vite/manifest.json';

		if ( ! file_exists( $manifest_path ) ) {
			$manifest_path = get_template_directory() . '/assets/manifest.json';
		}

		if ( file_exists( $manifest_path ) ) {
			$json   = file_get_contents( $manifest_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$parsed = json_decode( $json, true );

			// Normalise: convert flat string values (legacy Webpack format)
			// to the Vite object format { "file": "...", "css": [] }.
			$manifest = array();
			if ( is_array( $parsed ) ) {
				foreach ( $parsed as $entry_key => $entry_value ) {
					if ( is_string( $entry_value ) ) {
						$manifest[ $entry_key ] = array( 'file' => ltrim( $entry_value, '/' ) );
					} else {
						$manifest[ $entry_key ] = $entry_value;
					}
				}
			}
		} else {
			$manifest = array();
		}
	}

	return $manifest;
}

/**
 * Enqueue CSS
 *
 * @param string $key   The script key.
 * @param array  $deps  The dependencies.
 * @param string $media The media type.
 */
function themeCSS( $key, $deps = array(), $media = 'all' ) {

	$handle = THEME_PREFIX . '-' . sanitize_title( pathinfo( $key, PATHINFO_FILENAME ) );

	$source_key = 'main.css' === $key
		? 'sources/scss/style.scss'
		: $key;

	// DEV MODE.
	if ( isViteDev() ) {
		wp_enqueue_style(
			$handle,
			viteDevServerUrl() . '/' . ltrim( $source_key, '/' ),
			$deps,
			_THEME_VERSION,
			$media
		);
		return;
	}

	// PRODUCTION.
	$manifest = themeManifest();

	if ( isset( $manifest[ $source_key ] ) ) {
		wp_enqueue_style(
			$handle,
			get_template_directory_uri() . '/assets/' . $manifest[ $source_key ]['file'],
			$deps,
			_THEME_VERSION,
			$media
		);
	}
}

/**
 * Enqueue JS
 *
 * @param string $key       The script key.
 * @param array  $deps      The dependencies.
 * @param bool   $in_footer Whether to load in footer.
 */
function themeJS( $key, $deps = array( 'jquery' ), $in_footer = true ) {

	$handle = THEME_PREFIX . '-' . sanitize_title( pathinfo( $key, PATHINFO_FILENAME ) );

	$source_key = 'main.js' === $key
		? 'sources/js/script.js'
		: ( str_starts_with( $key, 'modules/' )
			? 'sources/js/' . $key
			: $key );

	// DEV MODE.
	if ( isViteDev() ) {
		wp_enqueue_script(
			$handle,
			viteDevServerUrl() . '/' . ltrim( $source_key, '/' ),
			$deps,
			_THEME_VERSION,
			$in_footer
		);

		return;
	}

	// PRODUCTION.
	$manifest = themeManifest();

	if ( isset( $manifest[ $source_key ] ) ) {

		wp_enqueue_script(
			$handle,
			get_template_directory_uri() . '/assets/' . $manifest[ $source_key ]['file'],
			$deps,
			_THEME_VERSION,
			$in_footer
		);

		// Load related CSS.
		if ( isset( $manifest[ $source_key ]['css'] ) ) {
			foreach ( $manifest[ $source_key ]['css'] as $index => $css_file ) {
				wp_enqueue_style(
					$handle . '-' . $index,
					get_template_directory_uri() . '/assets/' . $css_file,
					array(),
					_THEME_VERSION
				);
			}
		}
	}
}

/**
 * Enqueue all scripts
 */
function themeScripts() {

	// Vite client (DEV only).
	if ( isViteDev() ) {
		wp_enqueue_script(
			'vite-client',
			viteDevServerUrl() . '/@vite/client',
			array(),
			_THEME_VERSION,
			true
		);
	}

	// WP default style.
	wp_enqueue_style(
		THEME_PREFIX . '-wp-style',
		get_stylesheet_uri(),
		array(),
		_THEME_VERSION
	);

	// Main styles.
	themeCSS( 'main.css' );

	// Scripts.
	themeJS( 'main.js' );
	themeJS( 'modules/ajax-scripts.js' );

	// Localize AJAX.
	wp_localize_script(
		THEME_PREFIX . '-ajax-scripts',
		'wpnest_ajax_object',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'ajax-nonce' ),
		)
	);

	// Comments.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'themeScripts' );

/**
 * Add type="module"
 */
add_filter(
	'script_loader_tag',
	function ( $tag, $handle, $src ) {

		if ( str_contains( $src, viteDevServerUrl() ) || str_contains( $handle, THEME_PREFIX ) ) {
			if ( ! str_contains( $tag, 'type="module"' ) ) {
				$tag = str_replace( 'src=', 'type="module" src=', $tag );
			}
		}

		return $tag;
	},
	10,
	3
);
