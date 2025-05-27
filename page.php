<?php

/**
 * The template for displaying all pages
 *
 * @package WPNest
 */

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

get_header();
?>

<?php echo entry_banner(); ?>

<main id="content" class="main-content">

    <?php while (have_posts()) :
        the_post(); ?>

        <?php
        the_content();

        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'textdomain'),
            'after'  => '</div>',
        ));
        ?>

        <?php wpnest_edit_post_link(); ?>

    <?php endwhile; ?>

</main><!-- #main -->

<?php
get_footer();
