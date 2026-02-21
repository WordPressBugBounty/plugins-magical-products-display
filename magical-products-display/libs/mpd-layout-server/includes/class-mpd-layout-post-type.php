<?php
/**
 * Layout Post Type Registration
 *
 * Registers the mpd-page-layout custom post type.
 *
 * @package MPD_Layout_Server
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MPD_Layout_Post_Type
 *
 * Handles the custom post type registration for layouts.
 *
 * @since 1.0.0
 */
class MPD_Layout_Post_Type {

	/**
	 * Post type name.
	 *
	 * @var string
	 */
	const POST_TYPE = 'mpd-page-layout';

	/**
	 * Single instance.
	 *
	 * @var MPD_Layout_Post_Type|null
	 */
	private static $instance = null;

	/**
	 * Get single instance.
	 *
	 * @since 1.0.0
	 *
	 * @return MPD_Layout_Post_Type
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		// Admin columns hooks.
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( $this, 'add_columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'render_columns' ), 10, 2 );
	}

	/**
	 * Register custom post type.
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Page Layouts', 'Post type general name', 'magical-products-display' ),
			'singular_name'         => _x( 'Page Layout', 'Post type singular name', 'magical-products-display' ),
			'menu_name'             => _x( 'MPD Layouts', 'Admin Menu text', 'magical-products-display' ),
			'name_admin_bar'        => _x( 'Page Layout', 'Add New on Toolbar', 'magical-products-display' ),
			'add_new'               => __( 'Add New', 'magical-products-display' ),
			'add_new_item'          => __( 'Add New Layout', 'magical-products-display' ),
			'new_item'              => __( 'New Layout', 'magical-products-display' ),
			'edit_item'             => __( 'Edit Layout', 'magical-products-display' ),
			'view_item'             => __( 'View Layout', 'magical-products-display' ),
			'all_items'             => __( 'All Layouts', 'magical-products-display' ),
			'search_items'          => __( 'Search Layouts', 'magical-products-display' ),
			'parent_item_colon'     => __( 'Parent Layouts:', 'magical-products-display' ),
			'not_found'             => __( 'No layouts found.', 'magical-products-display' ),
			'not_found_in_trash'    => __( 'No layouts found in Trash.', 'magical-products-display' ),
			'featured_image'        => _x( 'Layout Thumbnail', 'Overrides the "Featured Image"', 'magical-products-display' ),
			'set_featured_image'    => _x( 'Set layout thumbnail', 'Overrides "Set featured image"', 'magical-products-display' ),
			'remove_featured_image' => _x( 'Remove layout thumbnail', 'Overrides "Remove featured image"', 'magical-products-display' ),
			'use_featured_image'    => _x( 'Use as layout thumbnail', 'Overrides "Use as featured image"', 'magical-products-display' ),
			'archives'              => _x( 'Layout archives', 'The post type archive label', 'magical-products-display' ),
			'insert_into_item'      => _x( 'Insert into layout', 'Overrides "Insert into post"', 'magical-products-display' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this layout', 'Overrides "Uploaded to this post"', 'magical-products-display' ),
			'filter_items_list'     => _x( 'Filter layouts list', 'Screen reader text', 'magical-products-display' ),
			'items_list_navigation' => _x( 'Layouts list navigation', 'Screen reader text', 'magical-products-display' ),
			'items_list'            => _x( 'Layouts list', 'Screen reader text', 'magical-products-display' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 30,
			'menu_icon'          => 'dashicons-layout',
			'supports'           => array( 'title', 'thumbnail' ),
			'show_in_rest'       => true,
			'rest_base'          => 'mpd-layouts',
		);

		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Register taxonomy for layout types.
	 *
	 * @since 1.0.0
	 */
	public function register_taxonomy() {
		$labels = array(
			'name'              => _x( 'Layout Types', 'taxonomy general name', 'magical-products-display' ),
			'singular_name'     => _x( 'Layout Type', 'taxonomy singular name', 'magical-products-display' ),
			'search_items'      => __( 'Search Layout Types', 'magical-products-display' ),
			'all_items'         => __( 'All Layout Types', 'magical-products-display' ),
			'parent_item'       => __( 'Parent Layout Type', 'magical-products-display' ),
			'parent_item_colon' => __( 'Parent Layout Type:', 'magical-products-display' ),
			'edit_item'         => __( 'Edit Layout Type', 'magical-products-display' ),
			'update_item'       => __( 'Update Layout Type', 'magical-products-display' ),
			'add_new_item'      => __( 'Add New Layout Type', 'magical-products-display' ),
			'new_item_name'     => __( 'New Layout Type Name', 'magical-products-display' ),
			'menu_name'         => __( 'Layout Types', 'magical-products-display' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => false,
			'rewrite'           => false,
			'show_in_rest'      => true,
			'rest_base'         => 'mpd-layout-types',
		);

		register_taxonomy( 'mpd-layout-type', array( self::POST_TYPE ), $args );

		// Register default layout types.
		$this->register_default_terms();
	}

	/**
	 * Register default taxonomy terms.
	 *
	 * @since 1.0.0
	 */
	private function register_default_terms() {
		$default_types = array(
			'single-product'  => __( 'Single Product', 'magical-products-display' ),
			'archive-product' => __( 'Archive/Shop', 'magical-products-display' ),
			'cart'            => __( 'Cart', 'magical-products-display' ),
			'checkout'        => __( 'Checkout', 'magical-products-display' ),
			'my-account'      => __( 'My Account', 'magical-products-display' ),
			'empty-cart'      => __( 'Empty Cart', 'magical-products-display' ),
			'thankyou'        => __( 'Thank You', 'magical-products-display' ),
		);

		foreach ( $default_types as $slug => $name ) {
			if ( ! term_exists( $slug, 'mpd-layout-type' ) ) {
				wp_insert_term( $name, 'mpd-layout-type', array( 'slug' => $slug ) );
			}
		}
	}

	/**
	 * Add custom columns to list table.
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public function add_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			$new_columns[ $key ] = $value;

			if ( 'title' === $key ) {
				$new_columns['layout_id']   = __( 'Layout ID', 'magical-products-display' );
				$new_columns['layout_type'] = __( 'Type', 'magical-products-display' );
				$new_columns['is_pro']      = __( 'Pro', 'magical-products-display' );
			}
		}

		return $new_columns;
	}

	/**
	 * Render custom columns.
	 *
	 * @since 1.0.0
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 */
	public function render_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'layout_id':
				$layout_id = get_post_meta( $post_id, '_mpd_layout_id', true );
				echo esc_html( $layout_id ? $layout_id : '—' );
				break;

			case 'layout_type':
				$type = get_post_meta( $post_id, '_mpd_layout_type', true );
				echo esc_html( $type ? $type : '—' );
				break;

			case 'is_pro':
				$is_pro = get_post_meta( $post_id, '_mpd_is_pro', true );
				echo $is_pro ? '<span class="dashicons dashicons-yes" style="color:#46b450;"></span>' : '<span class="dashicons dashicons-no-alt" style="color:#dc3232;"></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static HTML
				break;
		}
	}

	/**
	 * Get post type name.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_post_type() {
		return self::POST_TYPE;
	}
}
