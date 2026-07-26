<?php
/**
 * Card ACF block template.
 *
 * Renders a repeatable grid of cards, each containing:
 * an image, title, date, tag pills, and a CTA link.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	exit;
}

// ── Field assignment ─────────────────────────────────────────────────────────
$cd_heading = get_field( 'cd_heading' );
$cd_cards   = get_field( 'cd_cards' );

// ── Early return if no cards ─────────────────────────────────────────────────
if ( empty( $cd_cards ) ) {
	return;
}

// ── Wrapper attributes ───────────────────────────────────────────────────────
$block_id = 'card-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => $block_id,
		'class' => 'card-block',
	)
);
?>
<!-- Card Block -->
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="max-w-container mx-auto px-5 lg:px-12 w-full py-12 md:py-16">

		<?php if ( $cd_heading ) : ?>
			<h2 class="text-3xl font-bold text-black mb-8"><?php echo esc_html( $cd_heading ); ?></h2>
		<?php endif; ?>

		<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

			<?php foreach ( $cd_cards as $card ) :
				$image_id  = $card['cd_card_image'] ?? 0;
				$title     = $card['cd_card_title'] ?? '';
				$date      = $card['cd_card_date'] ?? '';
				$tags      = $card['cd_card_tags'] ?? array();
				$link      = $card['cd_card_link'] ?? array();

				if ( empty( $title ) ) {
					continue;
				}
				?>

				<article class="flex flex-col bg-white rounded-xl overflow-hidden border border-gray-200/60 shadow-sm hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out">

					<?php if ( ! empty( $image_id ) ) : ?>
						<div class="relative overflow-hidden aspect-video">
							<?php echo acf_image( absint( $image_id ), 'medium_large', 'block w-full h-full object-cover transition-transform duration-500 hover:scale-105' ); ?>
						</div>
					<?php endif; ?>

					<div class="flex flex-col flex-1 p-6">

						<?php if ( $title ) : ?>
							<h3 class="text-xl font-bold text-black leading-snug mb-3">
								<?php if ( ! empty( $link['url'] ) ) : ?>
									<a href="<?php echo esc_url( $link['url'] ); ?>"
									   class="hover:text-primary transition-colors duration-250"
									   <?php if ( ! empty( $link['target'] ) ) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>>
										<?php echo esc_html( $title ); ?>
									</a>
								<?php else : ?>
									<?php echo esc_html( $title ); ?>
								<?php endif; ?>
							</h3>
						<?php endif; ?>

						<?php if ( $date ) : ?>
							<p class="text-sm text-black/55 mb-4"><?php echo esc_html( $date ); ?></p>
						<?php endif; ?>

						<?php if ( ! empty( $tags ) ) : ?>
							<div class="flex flex-wrap gap-2 mb-5">
								<?php foreach ( $tags as $tag ) :
									if ( empty( $tag['cd_card_tag_label'] ) ) {
										continue;
									}
									?>
									<span class="inline-block px-3 py-1 text-xs font-medium text-primary bg-primary/8 border border-primary/20 rounded-full">
										<?php echo esc_html( $tag['cd_card_tag_label'] ); ?>
									</span>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $link['url'] ) ) : ?>
							<div class="mt-auto pt-4 border-t border-gray-200/60">
								<a href="<?php echo esc_url( $link['url'] ); ?>"
								   class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-secondary transition-colors duration-250"
								   <?php if ( ! empty( $link['target'] ) ) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>
								   aria-label="<?php echo esc_attr( $link['title'] ?? __( 'Browse Posts', TEXT_DOMAIN ) ); ?>">
									<?php echo esc_html( $link['title'] ?? __( 'Browse Posts', TEXT_DOMAIN ) ); ?>
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
										 fill="none" stroke="currentColor" stroke-width="2"
										 stroke-linecap="round" stroke-linejoin="round"
										 aria-hidden="true" focusable="false">
										<line x1="5" y1="12" x2="19" y2="12"></line>
										<polyline points="12 5 19 12 12 19"></polyline>
									</svg>
								</a>
							</div>
						<?php endif; ?>

					</div>
				</article>

			<?php endforeach; ?>

		</div>
	</div>
</section>
