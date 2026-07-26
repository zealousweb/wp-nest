<?php
/**
 * The template for displaying taxonomy archive pages.
 *
 * Works for any custom taxonomy registered with show_ui => true.
 * Falls back gracefully to the archive.php template if not found.
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

		<header class="mb-10">
			<?php
			the_archive_title( '<h2 class="text-3xl font-bold mb-3">', '</h2>' );
			the_archive_description( '<div class="text-base text-black/70">', '</div>' );
			?>
		</header>

		<?php if ( have_posts() ) : ?>

			<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'overflow-hidden flex flex-col rounded-lg bg-white shadow-md hover:-translate-y-1 hover:shadow-lg transition-all duration-300 ease-in-out p-6 border border-gray-200/50' ); ?>>
						<header class="mb-2.5">
							<?php the_title( '<h2 class="text-xl font-bold leading-tight"><a href="' . esc_url( get_permalink() ) . '" class="text-black hover:text-primary transition-colors duration-250" rel="bookmark">', '</a></h2>' ); ?>
						</header>

						<div class="flex-1 text-sm leading-normal text-black/75 mb-5">
							<?php the_excerpt(); ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="pagination">
				<?php
				global $wp_query;
				$big = 999999999;
				echo wp_kses_post(
					paginate_links(
						array(
							'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
							'format'    => '?paged=%#%',
							'current'   => max( 1, get_query_var( 'paged' ) ),
							'total'     => $wp_query->max_num_pages,
							'mid_size'  => 2,
							'end_size'  => 1,
							'prev_next' => true,
							'prev_text' => '&laquo; ' . esc_html__( 'Previous', TEXT_DOMAIN ),
							'next_text' => esc_html__( 'Next', TEXT_DOMAIN ) . ' &raquo;',
						)
					)
				);
				?>
			</div>

		<?php else : ?>
			<p><?php esc_html_e( 'No items found in this archive.', TEXT_DOMAIN ); ?></p>
		<?php endif; ?>

	</div><!-- .container -->
</main><!-- #content -->

<?php get_footer(); ?>
