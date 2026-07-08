<?php
/**
 * The template for displaying the search form.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

$unique_id = wp_unique_id( 'search-form-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $unique_id ); ?>" class="screen-reader-text">
		<?php echo esc_html_x( 'Search for:', 'label', TEXT_DOMAIN ); ?>
	</label>
	<div class="search-form-inner">
		<input
			type="search"
			id="<?php echo esc_attr( $unique_id ); ?>"
			class="search-field"
			placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', TEXT_DOMAIN ); ?>"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			name="s"
			aria-label="<?php echo esc_attr_x( 'Search', 'aria-label', TEXT_DOMAIN ); ?>"
		/>
		<button type="submit" class="search-submit btn" aria-label="<?php echo esc_attr_x( 'Submit Search', 'aria-label', TEXT_DOMAIN ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
				<circle cx="11" cy="11" r="8"></circle>
				<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
			</svg>
			<span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', TEXT_DOMAIN ); ?></span>
		</button>
	</div>
</form>
