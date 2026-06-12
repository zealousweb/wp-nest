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
	add_action( 'acf/init', 'register_acf_blocks' );
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
	$custom_category = array(
		'slug'  => 'custom-blocks',
		'title' => __( 'Custom Blocks', 'litmos' ),
	);

	// Insert custom category after the first existing category.
	array_splice( $categories, 1, 0, array( $custom_category ) );

	// Sort categories alphabetically by title.
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

		/**
		 * Custom Block — used on home page for displaying custom elements.
		 */
		acf_register_block_type(
			array(
				'name'            => 'home',
				'title'           => __( 'Home Block', 'wpnest' ),
				'description'     => __( 'A dynamically rendered ACF block.', 'wpnest' ),
				'render_callback' => 'theme_acf_block_render_callback',
				'category'        => 'formatting',
				'icon'            => 'admin-comments',
				'keywords'        => array( 'Home', 'acf' ),
				'supports'        => array(
					'align' => true,
				),
			)
		);
	}
}
add_action( 'acf/init', 'register_acf_blocks' );

/**
 * Renders an ACF block based on the block name.
 *
 * @param array $block The ACF block data.
 * @param string $content Optional. The block content.
 * @param bool $is_preview Optional. Whether this is a block preview or not.
 *
 * @return void
 *
 * @since 0.1.0
 */

function theme_acf_block_render_callback( $block, $content = '', $is_preview = false ) {

    // Normalize block slug
    $slug = str_replace( 'acf/', '', $block['name'] );
    $slug = str_replace( '_', '-', $slug );

    /**
     * ✅ PREVIEW IMAGE SUPPORT (ADD THIS)
     */
    if ( ! empty( $block['data']['is_example'] ) ) {
        $preview_image = theme_acf_block_preview_image( $slug );

        if ( $preview_image ) {
            echo '<img src="' . esc_url( $preview_image ) . '" style="width:100%;height:auto;" />';
            return;
        }
    }

    // Paths
    $block_dir = get_template_directory() . '/template-parts/blocks/';
    $scss_dir  = get_template_directory() . '/sources/scss/components/';
    $scss_core = $scss_dir . '_core.scss';

    $php_file  = $block_dir . $slug . '.php';
    $scss_file = $scss_dir . '_' . $slug . '.scss';

    // Ensure directories exist
    if ( ! is_dir( $block_dir ) ) {
        wp_mkdir_p( $block_dir );
    }

    if ( ! is_dir( $scss_dir ) ) {
        wp_mkdir_p( $scss_dir );
    }

    // DEV ONLY SCAFFOLDING - Restricted to local/development environment for security
    $is_local_env = function_exists( 'wp_get_environment_type' ) && in_array( wp_get_environment_type(), array( 'local', 'development' ), true );
    if ( WP_DEBUG && current_user_can( 'administrator' ) && $is_local_env ) {

        // Create PHP block file
        if ( ! file_exists( $php_file ) ) {
            file_put_contents(
                $php_file,
                "<?php\n" .
                "/**\n" .
                " * Block: {$block['title']}\n" .
                " *\n" .
                " * @package litmos\n" .
                " */\n\n" .
                "render_acf_block_preview( \$block );\n\n" .
                "echo '<!-- ' . esc_html( \$block['title'] ) . ' -->';\n" .
                "echo '<section class=\"{$slug}-section\"></section>';\n"
            );
        }

        // Create SCSS block file + auto-import
        if ( ! file_exists( $scss_file ) ) {

            file_put_contents(
                $scss_file,
                "@use \"@scss/abstracts/variables\" as *;\n" .
                "@use \"@scss/abstracts/function\" as *;\n" .
                "@use \"@scss/abstracts/mixins\" as *;\n\n" .
                ".{$slug}-section {\n}\n"
            );

            // Append import to _core.scss (avoid duplicates)
            if ( file_exists( $scss_core ) ) {
                $current = file_get_contents( $scss_core );

                if ( strpos( $current, "@use \"{$slug}\"" ) === false ) {
                    $current .= "@use \"{$slug}\";\n";
                    file_put_contents( $scss_core, $current );
                }
            }
        }
    }

    // Render block
    if ( file_exists( $php_file ) ) {
        include $php_file;
    } elseif ( current_user_can( 'administrator' ) ) {
        echo '<p style="color:red;">Missing block template: ' . esc_html( $slug ) . '</p>';
    }
}

/**
 * ACF Block Render Callback function
 */
function theme_acf_block_preview_image($block) {
    // Get block name without "acf/" prefix
    // $block_name = str_replace('acf/', '', $block['name']);
    $preview_dir = get_template_directory() . '/includes/acf-block-preview/';
    $preview_uri = get_template_directory_uri() . '/includes/acf-block-preview/';
    $extensions = ['webp', 'jpg', 'jpeg', 'png', 'gif', 'avif'];

    foreach ($extensions as $ext) {
        $file_path = $preview_dir . $block . '.' . $ext;
        if (file_exists($file_path)) {
            return $preview_uri . $block . '.' . $ext;
        }
    }

    return '';
}
