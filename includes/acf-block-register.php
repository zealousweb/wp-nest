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
 * Register the ACF block and sort blocks alphabetically.
 */
if ( function_exists( 'acf_register_block' ) ) {
	add_filter( 'block_categories_all', 'add_custom_block_category', 10, 2 );
	add_filter( 'block_editor_settings_all', 'sort_blocks_alphabetically', 10, 2 );
}

/**
 * Add custom block category in second position and sort all categories alphabetically.
 *
 * @param array   $categories List of block categories.
 * @param WP_Post $post       Current post object.
 * @return array Sorted categories with custom category added.
 */
function add_custom_block_category( $categories, $post ) {

    // Define your new categories
    $new_categories = array(
        array(
            'slug'  => 'hero',
            'title' => __( 'Hero', 'wpnest' ),
			'icon'  => 'cover-image',
        ),
        array(
            'slug'  => 'custom-blocks',
            'title' => __( 'Page Blocks', 'wpnest' ),
			'icon'  => 'layout',
        ),
		array(
            'slug'  => 'listing-blocks',
            'title' => __( 'Listing Blocks', 'wpnest' ),
			'icon'  => 'list-view',
        ),
    );

    // Insert your categories after the first item (position 1)
    array_splice( $categories, 1, 0, $new_categories );

    // Sort categories alphabetically by title
    usort(
        $categories,
        function ( $a, $b ) {
            return strcmp( $a['title'], $b['title'] );
        }
    );

    return $categories;
}


/**
 * Sort blocks alphabetically within the block inserter.
 *
 * @param array   $editor_settings Editor settings.
 * @param WP_Post $post            Current post object.
 * @return array Modified editor settings.
 */
function sort_blocks_alphabetically( $editor_settings, $post ) {
	if ( isset( $editor_settings['allowedBlockTypes'] ) && is_array( $editor_settings['allowedBlockTypes'] ) ) {
		usort(
			$editor_settings['allowedBlockTypes'],
			function ( $a, $b ) {
				if ( isset( $a['title'], $b['title'] ) ) {
					return strcmp( $a['title'], $b['title'] );
				}
				return 0;
			}
		);
	}

	return $editor_settings;
}

/**
 * Register custom ACF block types.
 *
 * @return void
 */
function register_acf_blocks() {
	if ( function_exists( 'acf_register_block_type' ) ) {

		/**
		 * Custom Block — used on home page for displaying custom elements.
		 */
		acf_register_block_type(
			array(
				'name'            => 'custom_block',
				'title'           => __( 'Custom Block', 'wpnest' ),
				'description'     => __( 'A dynamically rendered ACF block.', 'wpnest' ),
				'render_callback' => 'theme_acf_block_render_callback',
				'category'        => 'formatting',
				'icon'            => 'admin-comments',
				'keywords'        => array( 'custom', 'acf' ),
				'supports'        => array(
					'align' => true,
				),
			)
		);
	}
}
add_action( 'acf/init', 'register_acf_blocks' );

/**
 * ACF block render callback.
 *
 * Loads the corresponding PHP template from template-parts/blocks/.
 *
 * @param array $block The ACF block settings array.
 * @return void
 */
function theme_acf_block_render_callback( $block ) {
	// Get block name without "acf/" prefix.
	$block_name = str_replace( 'acf/', '', $block['name'] );

	// Construct template path.
	$template_path = get_theme_file_path( "/template-parts/blocks/{$block_name}.php" );

	// Load template if it exists.
	if ( file_exists( $template_path ) ) {
		include $template_path;
	} else {
		echo '<p>Template file for "' . esc_html( $block_name ) . '" not found.</p>';
	}
}
