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
 * Register custom ACF block types.
 *
 * @return void
 */
function register_acf_blocks() {
	if ( function_exists( 'acf_register_block_type' ) ) {

		$blocks = array(
			array(
				'name'        => 'custom_block',
				'title'       => __( 'Custom Block', 'wpnest' ),
				'description' => __( 'A dynamically rendered ACF block.', 'wpnest' ),
				'category'    => 'wpnest-blocks',
				'icon'        => 'admin-comments',
				'keywords'    => array( 'custom', 'acf' ),
				'example'     => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'heading' => 'Sample Heading',
							'content' => 'Sample content for the custom block preview.',
						),
					),
				),
			),
			array(
				'name'        => 'wysiwyg',
				'title'       => __( 'WYSIWYG', 'wpnest' ),
				'description' => __( 'A WYSIWYG content block.', 'wpnest' ),
				'category'    => 'wpnest-blocks',
				'icon'        => 'editor-paragraph',
				'keywords'    => array( 'wysiwyg', 'content', 'text' ),
				'example'     => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'wys_heading'     => 'WYSIWYG Sample',
							'wys_description' => '<p>This is a sample preview for the WYSIWYG block.</p>',
						),
					),
				),
			),
		);

		foreach ( $blocks as $block ) {
			$block['render_callback'] = 'theme_acf_block_render_callback';
			$block['supports']        = array( 'align' => true );

			acf_register_block_type( $block );
		}
	}
}
add_action( 'acf/init', 'register_acf_blocks' );

/**
 * ACF block render callback.
 *
 * Loads the corresponding PHP template from template-parts/blocks/.
 * If in preview mode and a preview image is set, renders the image instead.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block inner HTML (empty).
 * @param bool   $is_preview True during background AJAX preview.
 * @return void
 */
function theme_acf_block_render_callback( $block, $content = '', $is_preview = false ) {
	// Get block name without "acf/" prefix.
	$block_name = str_replace( 'acf/', '', $block['name'] );

	// Automatically load preview image if it exists.
	if ( $is_preview ) {
		$preview_image_url = '';

		if ( file_exists( get_template_directory() . '/includes/acf-block-preview/' . $block_name . '.png' ) ) {
			$preview_image_url = get_template_directory_uri() . '/includes/acf-block-preview/' . $block_name . '.png';
		} elseif ( file_exists( get_template_directory() . '/includes/acf-block-preview/' . $block_name . '.jpg' ) ) {
			$preview_image_url = get_template_directory_uri() . '/includes/acf-block-preview/' . $block_name . '.jpg';
		} elseif ( ! empty( $block['data']['preview_image'] ) ) {
			$preview_image_url = get_template_directory_uri() . '/includes/acf-block-preview/' . $block['data']['preview_image'];
		}

		if ( $preview_image_url ) {
			echo '<img src="' . esc_url( $preview_image_url ) . '" style="width:100%; height:auto; display:block;" alt="' . esc_attr( $block['title'] ) . '">';
			return;
		}
	}

	// Construct template path.
	$template_path = get_theme_file_path( "/template-parts/blocks/{$block_name}.php" );

	// Load template if it exists.
	if ( file_exists( $template_path ) ) {
		include $template_path;
	} else {
		echo '<p>Template file for "' . esc_html( $block_name ) . '" not found.</p>';
	}
}

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
				'title' => __( 'WPNest Blocks', 'wpnest' ),
				'icon'  => 'layout', // Dashicon.
			),
		)
	);
}
add_filter( 'block_categories_all', 'wpnest_block_categories', 10, 2 );
