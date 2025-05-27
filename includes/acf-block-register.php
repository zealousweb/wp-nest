<?php

/**
 * Register ACF Blocks
 */

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

function register_acf_blocks()
{
    if (function_exists('acf_register_block_type')) {

        /** Registered Custom Block
         * used on home page
         * displaying custom elements
         */
        acf_register_block_type(array(
            'name'              => 'custom_block',
            'title'             => __('Custom Block'),
            'description'       => __('A dynamically rendered ACF block.'),
            'render_callback'   => 'theme_acf_block_render_callback',
            'category'          => 'formatting',
            'icon'              => 'admin-comments',
            'keywords'          => array('custom', 'acf'),
            'supports'          => array(
                'align' => true
            ),
        ));
    }
}
add_action('acf/init', 'register_acf_blocks');

/**
 * ACF Block Render Callback function
 */
function theme_acf_block_render_callback($block)
{
    // Get block name without "acf/" prefix
    $block_name = str_replace('acf/', '', $block['name']);

    // Construct template path
    $template_path = get_theme_file_path("/template-parts/blocks/{$block_name}.php");

    // Check if the template exists
    if (file_exists($template_path)) {
        include $template_path;
    } else {
        echo '<p>Template file for "' . esc_html($block_name) . '" not found.</p>';
    }
}
