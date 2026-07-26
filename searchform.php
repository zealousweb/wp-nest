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
<form role="search" method="get" class="w-full" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $unique_id ); ?>" class="screen-reader-text">
		<?php echo esc_html_x( 'Search for:', 'label', TEXT_DOMAIN ); ?>
	</label>
	<div class="flex items-stretch overflow-hidden border border-gray-200 rounded-md transition-all duration-250 focus-within:border-primary focus-within:ring-3 focus-within:ring-primary/20">
		<input
			type="search"
			id="<?php echo esc_attr( $unique_id ); ?>"
			class="flex-grow h-10 px-4 text-base text-black bg-white outline-none placeholder-black/55"
			placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', TEXT_DOMAIN ); ?>"
			value="<?php echo esc_attr( get_search_query() ); ?>"
			name="s"
			aria-label="<?php echo esc_attr_x( 'Search', 'aria-label', TEXT_DOMAIN ); ?>"
		/>
		<button type="submit" class="inline-flex items-center justify-center h-10 px-4.5 border-l border-gray-200 rounded-none bg-primary text-white hover:bg-secondary transition-colors cursor-pointer" aria-label="<?php echo esc_attr_x( 'Submit Search', 'aria-label', TEXT_DOMAIN ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
				<circle cx="11" cy="11" r="8"></circle>
				<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
			</svg>
			<span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', TEXT_DOMAIN ); ?></span>
		</button>
	</div>
</form>
