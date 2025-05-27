<?php

/**
 * The template for displaying site header
 *
 * @package WPNest
 */

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes() ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset') ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2" />
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
    <?php wp_head(); ?>
</head>
<body <?php body_class() ?>>
<div class="wrapper">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'textdomain'); ?></a>
    <?php $brand_logo = get_template_directory_uri() . '/sources/images/logo.svg'; ?>
    <header class="main-header">
        <div class="container d-flex justify-content-between align-items-center flex-nowrap">
            <?php if ($brand_logo) { ?>
                <a href="<?php echo home_url('/'); ?>" class="brand">
                    <img src="<?php echo $brand_logo; ?>" alt="<?php echo get_bloginfo('name'); ?>">
                </a>
            <?php } ?>
            
            <div class="nav-menu d-flex justify-content-end align-items-center flex-nowrap">
                <?php if (has_nav_menu('menu-1')) { ?>
                    <div class="navigation">
                        <nav class="d-flex justify-content-end align-items-center flex-nowrap">
                            <?php wp_nav_menu(array('theme_location' => 'menu-1','container' => 'ul')); ?>
                        </nav>
                    </div>
                <?php } ?>
                <a href="#" role="button" aria-label="Button Title" class="btn">Button</a>
                <!-- Hamburger -->
                <a href="javascript:;" class="hamburger" role="button" aria-label="Hamburger"><span></span></a>
            </div>
        </div>
        
        <!-- Device menu -->
        <?php if (has_nav_menu('menu-1')) { ?>
            <div class="mbnav d-md-none">
                <div class="mbnav__backdrop"></div>
                <div class="mbnav__state" data-clickable="true">
                    <div class="mbnav__inner">
                        <?php wp_nav_menu(array('theme_location' => 'menu-1','container' => 'ul')); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </header>
