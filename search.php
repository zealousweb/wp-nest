<?php
/**
 * The template for displaying search results pages.
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

		<div class="pt-8">

			<?php if ( have_posts() ) : ?>

				<header class="flex flex-wrap gap-5 items-center justify-between mb-10">
					<h2 class="text-2xl font-bold text-black">
						<?php
						echo wp_kses_post(
							sprintf(
								/* translators: 1: number of results, 2: search query. */
								_n( '%1$s result for &ldquo;%2$s&rdquo;', '%1$s results for &ldquo;%2$s&rdquo;', (int) $wp_query->found_posts, TEXT_DOMAIN ),
								esc_html( number_format_i18n( $wp_query->found_posts ) ),
								'<em class="not-italic text-primary">' . esc_html( get_search_query() ) . '</em>'
							)
						);
						?>
					</h2>
					<div class="shrink-0 w-full max-w-xs">
						<?php get_search_form(); ?>
					</div>
				</header>

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

				<div class="py-16 text-center">
					<p class="mb-8 text-lg text-black/75"><?php esc_html_e( 'Sorry, no results were found. Please try a different search.', TEXT_DOMAIN ); ?></p>
					<div class="max-w-lg mx-auto">
						<?php get_search_form(); ?>
					</div>
				</div>

			<?php endif; ?>

		</div><!-- .search-results-wrap -->
	</div><!-- .container -->
</main><!-- #content -->

<?php get_footer(); ?>
