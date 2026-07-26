<?php
/**
 * The template for displaying all pages.
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
	<div class="max-w-container mx-auto px-5 lg:px-12 w-full">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', TEXT_DOMAIN ),
					'after'  => '</div>',
				)
			);
			?>

			<?php wpnest_edit_post_link(); ?>

		<?php endwhile; ?>

	</div><!-- .container -->
</main><!-- #main -->

<?php
get_footer();
