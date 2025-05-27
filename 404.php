<?php

/**
 * The template for displaying 404
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

    <div class="error-404 not-found space-lg text-center">

        <div class="container">

            <h1><?php esc_html_e('404', 'textdomain'); ?></h1>
            
            <p><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'textdomain'); ?></p>

        </div>

    </div>

</main><!-- #main -->

<?php
get_footer();
