<?php
/**
 * Theme security functions.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/*
 * REST API access restriction (disabled — uncomment to enable).
 * Restricts REST API to logged-in users only.
 *
 * add_filter( 'rest_authentication_errors', function( $result ) {
 *     if ( ! is_user_logged_in() ) {
 *         return new WP_Error( 'rest_disabled', __( 'REST API restricted.', 'wpnest' ), array( 'status' => 403 ) );
 *     }
 *     return $result;
 * } );
 */

/**
 * Restrict access to the `/wp/v2/users` REST API endpoint for non-logged-in users.
 *
 * Removes the users endpoint from the REST API for unauthenticated requests
 * to enhance privacy and security.
 *
 * @param array $endpoints Array of available REST API endpoints.
 * @return array Modified endpoints array.
 */
add_filter(
	'rest_endpoints',
	function ( $endpoints ) {

		if ( ! is_user_logged_in() && isset( $endpoints['/wp/v2/users'] ) ) {
			unset( $endpoints['/wp/v2/users'] );
		}

		return $endpoints;
	}
);

/**
 * Redirect users away from author archive pages.
 *
 * This function checks if the current page is an author archive and, if so,
 * redirects the user to the homepage.
 *
 * @return void
 */
function disable_author_archive() {

	if ( is_author() ) {
		wp_safe_redirect( home_url() );
		exit;
	}
}
add_action( 'template_redirect', 'disable_author_archive' );
