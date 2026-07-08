<?php
/**
 * WYSIWYG ACF block template.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	exit;
}

$wys_heading     = get_field( 'wys_heading' );
$wys_description = get_field( 'wys_description' );

if ( empty( $wys_heading ) && empty( $wys_description ) ) {
	return;
}

// Support custom anchor values.
$block_id = 'wysiwyg-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// Prepare wrapper attributes (This natively handles ID, Classes, and Inline Styles from block.json supports).
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => $block_id,
		'class' => 'wysiwyg',
	)
);
?>
<!-- WYSIWYG -->
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="container">
		<div class="wc-wrap">
			<?php if ( $wys_heading ) : ?>
				<h2><?php echo esc_html( $wys_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $wys_description ) : ?>
				<div class="wc-decs bullet-styled">
					<?php echo wp_kses_post( $wys_description ); ?>
				</div>
			<?php endif; ?>

			<div class="wc-inner-blocks">
				<InnerBlocks />
			</div>
		</div>
	</div>
</section>
