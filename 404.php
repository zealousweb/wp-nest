<?php
/**
 * The template for displaying 404 error page.
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

	<div class="py-24 text-center">

		<div class="max-w-container mx-auto px-5 lg:px-12 w-full">

			<h2 class="text-6xl font-bold text-black mb-4"><?php esc_html_e( '404', TEXT_DOMAIN ); ?></h2>

			<p class="text-lg text-black/75"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', TEXT_DOMAIN ); ?></p>

		</div>

	</div>

</main><!-- #main -->

<?php
get_footer();
