<?php
/**
 * WPNest functions and definitions.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

if ( ! defined( '_THEME_VERSION' ) ) {
	define( '_THEME_VERSION', wp_get_theme()->get( 'Version' ) );
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 * This value is used by WordPress for sizing images, videos, and oEmbed frames.
 *
 * Priority 0 to make it available early to other code.
 *
 * @global int $content_width
 */
function wpnest_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wpnest_content_width', 1170 );
}
add_action( 'after_setup_theme', 'wpnest_content_width', 0 );

/**
 * Define the default constant that will be used throughout your theme.
 */
define( 'THEME_PREFIX', 'wpnest' );
define( 'TEXT_DOMAIN', 'wpnest' );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wpnest_setup() {

	/**
	 * Add default posts and comments RSS feed links to head.
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Let WordPress manage the document title.
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * Register custom image sizes.
	 */
	add_image_size( 'wpnest-banner', 1920, 600, true );
	add_image_size( 'wpnest-card', 640, 420, true );
	add_image_size( 'wpnest-card-sm', 400, 270, true );

	/**
	 * Enable responsive embeds (YouTube, Vimeo, etc).
	 */
	add_theme_support( 'responsive-embeds' );

	/**
	 * Enable support for wide alignment.
	 */
	add_theme_support( 'align-wide' );

	/**
	 * Enable support for Spacing.
	 */
	add_theme_support( 'custom-spacing' );

	/**
	* Switch default core markup for search form, comment form, and comments to output valid HTML5.
	*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', TEXT_DOMAIN ),
			'menu-2' => esc_html__( 'Secondary', TEXT_DOMAIN ),
		)
	);

	/**
	 * Add support for wp_body_open() — allows plugins like GTM to inject
	 * code immediately after the opening body tag.
	 */
	add_theme_support( 'body-open' );
}
add_action( 'after_setup_theme', 'wpnest_setup' );

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/includes/theme-scripts.php';

/**
 * Implement the Custom Action for this theme.
 */
require get_template_directory() . '/includes/theme-action.php';

/**
 * Implement the Custom Filter for this theme.
 */
require get_template_directory() . '/includes/theme-filter.php';

/**
 * Add the Custom functions for this theme.
 */
require get_template_directory() . '/includes/theme-functions.php';

/**
 * Add the Custom functions for this theme.
 */
require get_template_directory() . '/includes/theme-security.php';

/**
 * Register the Custom ACF Block for this theme.
 */
require get_template_directory() . '/includes/acf-block-register.php';

/**
 * Register Custom Post Types and Taxonomies.
 */
require get_template_directory() . '/includes/theme-cpt.php';

/**
 * Set custom ACF JSON save point.
 *
 * @return string Custom path.
 */
function wpnest_acf_json_save_point() {
	return get_template_directory() . '/includes/acf-json';
}
add_filter( 'acf/settings/save_json', 'wpnest_acf_json_save_point' );

/**
 * Set custom ACF JSON load point.
 *
 * @param array $paths Default ACF JSON load paths.
 * @return array Modified paths.
 */
function wpnest_acf_json_load_point( $paths ) {
	unset( $paths[0] );
	$paths[] = get_template_directory() . '/includes/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'wpnest_acf_json_load_point' );
