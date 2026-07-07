<?php
/**
 * Custom ACF block template.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	exit;
}

$heading = get_field( 'heading' );
$content = get_field( 'content' );

if ( empty( $heading ) && empty( $content ) ) {
	return;
}

// Support custom anchor values.
$block_id = 'custom-block-' . $block['id'];

if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// Support custom class names.
$class_name = 'custom-block';

if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
?>
<section
	id="<?php echo esc_attr( $block_id ); ?>"
	class="<?php echo esc_attr( $class_name ); ?>"
>
	<div class="container">
		<?php if ( $heading ) : ?>
			<h2><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<?php if ( $content ) : ?>
			<p><?php echo esc_html( $content ); ?></p>
		<?php endif; ?>
	</div>
</section>

