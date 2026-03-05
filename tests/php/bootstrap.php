<?php
/**
 * PHPUnit bootstrap file.
 * We are using a simple bootstrap just to load composer autoload
 * or mock any essential functions for simple tests if we don't
 * have a full WP test suite loaded.
 */

if ( file_exists( dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php' ) ) {
	require_once dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php';
}

// Optionally, mock WordPress functions here if needed for unit tests
if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}
