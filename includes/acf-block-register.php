<?php
/**
 * Register ACF blocks for this theme.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
 * Register custom ACF block types using block.json (V2).
 *
 * @return void
 */
function register_acf_blocks() {
	if ( function_exists( 'register_block_type' ) ) {
		// Define the paths to block folders containing block.json.
		$blocks = array(
			'/template-parts/blocks/wysiwyg',
		);

		foreach ( $blocks as $block ) {
			register_block_type( get_theme_file_path( $block ) );
		}
	}
}
add_action( 'init', 'register_acf_blocks' );

/**
 * Register custom block category.
 *
 * @param array                   $categories Array of block categories.
 * @param WP_Block_Editor_Context $block_editor_context The current block editor context.
 * @return array Modified array of categories.
 */
function wpnest_block_categories( $categories, $block_editor_context ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'wpnest-blocks',
				'title' => __( 'WPNest Blocks', TEXT_DOMAIN ),
				'icon'  => 'layout', // Dashicon.
			),
		)
	);
}
add_filter( 'block_categories_all', 'wpnest_block_categories', 10, 2 );
