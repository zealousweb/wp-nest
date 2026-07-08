<?php
/**
 * The template for displaying category archive pages.
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

		<header class="archive-header">
			<?php
			the_archive_title( '<h2 class="archive-title">', '</h2>' );
			the_archive_description( '<div class="archive-description">', '</div>' );
			?>
		</header>

		<?php if ( have_posts() ) : ?>

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
			<p><?php esc_html_e( 'No posts found in this category.', TEXT_DOMAIN ); ?></p>
		<?php endif; ?>

	</div><!-- .container -->
</main><!-- #content -->

<?php get_footer(); ?>
