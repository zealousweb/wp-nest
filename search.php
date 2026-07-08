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

<main id="content" class="main-content">
	<div class="container">

		<div class="search-results-wrap">

			<?php if ( have_posts() ) : ?>

				<header class="search-results-header">
					<h2 class="search-results-title">
						<?php
						echo wp_kses_post(
							sprintf(
								/* translators: 1: number of results, 2: search query. */
								_n( '%1$s result for &ldquo;%2$s&rdquo;', '%1$s results for &ldquo;%2$s&rdquo;', (int) $wp_query->found_posts, TEXT_DOMAIN ),
								esc_html( number_format_i18n( $wp_query->found_posts ) ),
								'<em>' . esc_html( get_search_query() ) . '</em>'
							)
						);
						?>
					</h2>
					<div class="search-form-inline">
						<?php get_search_form(); ?>
					</div>
				</header>

				<div class="post-list">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<header class="entry-header">
								<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
							</header>

							<div class="entry-summary">
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

				<div class="search-no-results">
					<p><?php esc_html_e( 'Sorry, no results were found. Please try a different search.', TEXT_DOMAIN ); ?></p>
					<?php get_search_form(); ?>
				</div>

			<?php endif; ?>

		</div><!-- .search-results-wrap -->
	</div><!-- .container -->
</main><!-- #content -->

<?php get_footer(); ?>
