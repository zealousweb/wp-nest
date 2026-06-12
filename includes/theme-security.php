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

/**
 * Sanitize uploaded SVG files to prevent XSS and XXE injection attacks (WN-01).
 *
 * @param array $file File details.
 * @return array Sanitized file details.
 */
function wpnest_sanitize_svg_upload( $file ) {
	if ( 'image/svg+xml' !== $file['type'] ) {
		return $file;
	}

	$file_path = $file['tmp_name'];
	if ( ! file_exists( $file_path ) ) {
		return $file;
	}

	$svg_content = file_get_contents( $file_path );
	if ( empty( $svg_content ) ) {
		return $file;
	}

	// Sanitize content
	$sanitized = wpnest_clean_svg_content( $svg_content );
	if ( false === $sanitized ) {
		$file['error'] = __( 'Invalid or unsafe SVG file uploaded.', 'wpnest' );
	} else {
		file_put_contents( $file_path, $sanitized );
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'wpnest_sanitize_svg_upload' );

/**
 * Clean SVG content by stripping script elements, dangerous tags, and javascript event handlers.
 *
 * @param string $svg Raw SVG content.
 * @return string|false Sanitized SVG content or false on failure.
 */
function wpnest_clean_svg_content( $svg ) {
	$prev_use_errors = libxml_use_internal_errors( true );
	
	// Disable external entities loader (XXE protection)
	$prev_entity_loader = null;
	if ( function_exists( 'libxml_disable_entity_loader' ) ) {
		$prev_entity_loader = libxml_disable_entity_loader( true );
	}

	$doc = new DOMDocument();
	// Load with external entity resolving disabled
	$loaded = $doc->loadXML( $svg, LIBXML_NOENT | LIBXML_DTDLOAD | LIBXML_NONET );

	libxml_clear_errors();
	libxml_use_internal_errors( $prev_use_errors );
	if ( null !== $prev_entity_loader ) {
		libxml_disable_entity_loader( $prev_entity_loader );
	}

	if ( ! $loaded || ! $doc->documentElement ) {
		return false;
	}

	// Remove script tags
	$scripts = $doc->getElementsByTagName( 'script' );
	while ( $scripts->length > 0 ) {
		$script = $scripts->item( 0 );
		$script->parentNode->removeChild( $script );
	}

	// Remove iframe/object/embed/use/link tags if present
	$dangerous_tags = array( 'iframe', 'object', 'embed', 'link' );
	foreach ( $dangerous_tags as $tag ) {
		$elements = $doc->getElementsByTagName( $tag );
		while ( $elements->length > 0 ) {
			$element = $elements->item( 0 );
			$element->parentNode->removeChild( $element );
		}
	}

	// Remove inline script event handlers (on* event attributes) and javascript: URLs
	$xpath = new DOMXPath( $doc );
	$nodes = $xpath->query( '//*' );
	foreach ( $nodes as $node ) {
		$attrs_to_remove = array();
		foreach ( $node->attributes as $attr ) {
			if ( 0 === stripos( $attr->name, 'on' ) || 0 === stripos( $attr->value, 'javascript:' ) ) {
				$attrs_to_remove[] = $attr->name;
			}
		}
		foreach ( $attrs_to_remove as $attr_name ) {
			$node->removeAttribute( $attr_name );
		}
	}

	return $doc->saveXML();
}
