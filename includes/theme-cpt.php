<?php
/**
 * Register Custom Post Types and Taxonomies.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
 * Register Custom Post Types.
 *
 * @return void
 */
function wpnest_register_custom_post_types() {
	// ---------------------------------------------------------
	// EXAMPLE: Portfolio Custom Post Type
	// ---------------------------------------------------------

	/*
	$labels = array(
		'name'               => _x( 'Portfolios', 'post type general name', TEXT_DOMAIN ),
		'singular_name'      => _x( 'Portfolio', 'post type singular name', TEXT_DOMAIN ),
		'menu_name'          => _x( 'Portfolios', 'admin menu', TEXT_DOMAIN ),
		'name_admin_bar'     => _x( 'Portfolio', 'add new on admin bar', TEXT_DOMAIN ),
		'add_new'            => _x( 'Add New', 'portfolio', TEXT_DOMAIN ),
		'add_new_item'       => __( 'Add New Portfolio', TEXT_DOMAIN ),
		'new_item'           => __( 'New Portfolio', TEXT_DOMAIN ),
		'edit_item'          => __( 'Edit Portfolio', TEXT_DOMAIN ),
		'view_item'          => __( 'View Portfolio', TEXT_DOMAIN ),
		'all_items'          => __( 'All Portfolios', TEXT_DOMAIN ),
		'search_items'       => __( 'Search Portfolios', TEXT_DOMAIN ),
		'parent_item_colon'  => __( 'Parent Portfolios:', TEXT_DOMAIN ),
		'not_found'          => __( 'No portfolios found.', TEXT_DOMAIN ),
		'not_found_in_trash' => __( 'No portfolios found in Trash.', TEXT_DOMAIN ),
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', TEXT_DOMAIN ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'portfolio' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'          => 'dashicons-portfolio',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
	);

	register_post_type( 'portfolio', $args );
	*/
}
add_action( 'init', 'wpnest_register_custom_post_types' );

/**
 * Register Custom Taxonomies.
 *
 * @return void
 */
function wpnest_register_custom_taxonomies() {
	// ---------------------------------------------------------
	// EXAMPLE: Portfolio Category Taxonomy
	// ---------------------------------------------------------

	/*
	$labels = array(
		'name'              => _x( 'Portfolio Categories', 'taxonomy general name', TEXT_DOMAIN ),
		'singular_name'     => _x( 'Portfolio Category', 'taxonomy singular name', TEXT_DOMAIN ),
		'search_items'      => __( 'Search Categories', TEXT_DOMAIN ),
		'all_items'         => __( 'All Categories', TEXT_DOMAIN ),
		'parent_item'       => __( 'Parent Category', TEXT_DOMAIN ),
		'parent_item_colon' => __( 'Parent Category:', TEXT_DOMAIN ),
		'edit_item'         => __( 'Edit Category', TEXT_DOMAIN ),
		'update_item'       => __( 'Update Category', TEXT_DOMAIN ),
		'add_new_item'      => __( 'Add New Category', TEXT_DOMAIN ),
		'new_item_name'     => __( 'New Category Name', TEXT_DOMAIN ),
		'menu_name'         => __( 'Category', TEXT_DOMAIN ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'portfolio-category' ),
		'show_in_rest'      => true,
	);

	register_taxonomy( 'portfolio_category', array( 'portfolio' ), $args );
	*/
}
add_action( 'init', 'wpnest_register_custom_taxonomies' );
