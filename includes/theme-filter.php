<?php

/**
 * Load the theme filters.
 */

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

/**
 * Allow SVG uploads for all users.
 */
function allow_svg_upload($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_upload');

/**
 * Fix MIME type check for SVG files (bypass "not allowed" error).
 */
function fix_svg_filetype_check($data, $file, $filename, $mimes)
{
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (strtolower($ext) === 'svg') {
        $data['type'] = 'image/svg+xml';
        $data['ext']  = 'svg';
    }
    return $data;
}
add_filter('wp_check_filetype_and_ext', 'fix_svg_filetype_check', 10, 4);
