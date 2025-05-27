<?php

/**
 * The template for displaying blog page.
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

    <?php echo wpnest_archive_post(); ?>

</main><!-- #main -->

<?php
get_footer();
