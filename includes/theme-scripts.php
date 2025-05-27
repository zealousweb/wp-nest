<?php

/**
* Load the theme scripts.
*/

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

/**
 * Enqueue scripts and styles.
 */

function themeManifest()
{
    static $manifest = null;

    if (is_null($manifest)) {
        $manifest_path = get_template_directory() . '/assets/manifest.json';

        if (file_exists($manifest_path)) {
            $json = file_get_contents($manifest_path);
            $manifest = json_decode($json, true);
        } else {
            $manifest = array();
        }
    }

    return $manifest;
}

function themeCSS($key, $deps = array(), $media = 'all')
{
    $manifest = themeManifest();
    $filename = isset($manifest[$key]) ? ltrim($manifest[$key], '/') : 'css/' . ltrim($key, '/');

    $filepath = get_template_directory() . '/assets/' . $filename;
    $fileuri  = get_template_directory_uri() . '/assets/' . $filename;

    if (file_exists($filepath)) {
        $handle = THEME_PREFIX . '-' . sanitize_title(pathinfo($filename, PATHINFO_FILENAME));
        wp_enqueue_style($handle, $fileuri, $deps, _THEME_VERSION, $media);
    }
}

function themeJS($key, $deps = array('jquery'), $in_footer = true)
{
    $manifest = themeManifest();
    $filename = isset($manifest[$key]) ? ltrim($manifest[$key], '/') : 'js/' . ltrim($key, '/');

    $filepath = get_template_directory() . '/assets/' . $filename;
    $fileuri  = get_template_directory_uri() . '/assets/' . $filename;

    if (file_exists($filepath)) {
        $handle = THEME_PREFIX . '-' . sanitize_title(pathinfo($filename, PATHINFO_FILENAME));
        wp_enqueue_script($handle, $fileuri, $deps, _THEME_VERSION, $in_footer);
    }
}

function wpnestScripts()
{
    // Default theme style.
    wp_enqueue_style(THEME_PREFIX . '-wp-style', get_stylesheet_uri(), array());

    // Compiled main styles.
    themeCSS('main.css');

    // library styles.
    themeCSS('library/common.css');

    // library js.
    themeJS('library/swiper.js');
    themeJS('library/fancyapps.js');

    // Compiled main scripts.
    themeJS('main.js');

    // Localize the script to pass AJAX URL and nonce to JavaScript
    wp_enqueue_script(THEME_PREFIX . '-ajax-script', get_template_directory_uri() . '/sources/js/modules/ajax-scripts.js', array(), _THEME_VERSION, true);

    // Enqueue the custom AJAX handling script
    wp_localize_script(
        THEME_PREFIX . '-ajax-script',
        'wpnest_ajax_object',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce')
        )
    );

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'wpnestScripts');
