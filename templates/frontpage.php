<?php
/**
 * Template Name: Front Page.
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

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<?php the_content(); ?>

	<?php endwhile; ?>

</main><!-- #main -->

<?php
get_footer();
