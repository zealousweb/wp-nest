<?php
/**
 * The template for displaying archive pages.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

get_header();
?>

<?php entry_banner(); ?>

<main id="content" class="grow w-full pt-12 pb-16 md:pt-8 md:pb-10">

	<?php echo wp_kses_post( wpnest_archive_post() ); ?>

</main><!-- #main -->

<?php
get_footer();
