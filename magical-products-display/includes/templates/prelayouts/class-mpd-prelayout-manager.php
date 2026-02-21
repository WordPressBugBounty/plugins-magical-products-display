<?php
/**
 * Pre-Layout Manager
 *
 * Manages pre-defined layout templates for WooCommerce pages.
 * Provides fast layout selection without importing demo data.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

namespace MPD\MagicalShopBuilder\Templates\PreLayouts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PreLayout_Manager
 *
 * Main manager class for pre-defined layouts.
 *
 * @since 2.1.0
 */
class PreLayout_Manager {

	/**
	 * Instance of the class.
	 *
	 * @var PreLayout_Manager|null
	 */
	private static $instance = null;

	/**
	 * Registered layouts.
	 *
	 * @var array
	 */
	private $layouts = array();

	/**
	 * Layout library instance.
	 *
	 * @var PreLayout_Library|null
	 */
	private $library = null;

	/**
	 * Layout importer instance.
	 *
	 * @var PreLayout_Importer|null
	 */
	private $importer = null;

	/**
	 * Get instance.
	 *
	 * @since 2.1.0
	 *
	 * @return PreLayout_Manager
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 2.1.0
	 */
	public function __construct() {
		$this->library  = new PreLayout_Library();
		$this->importer = new PreLayout_Importer();
	}

	/**
	 * Initialize the manager.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	public function init() {
		// Register REST API endpoints.
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );

		// Load layouts after theme setup.
		add_action( 'after_setup_theme', array( $this, 'load_layouts' ), 20 );

		/**
		 * Fires after pre-layout manager is initialized.
		 *
		 * @since 2.1.0
		 */
		do_action( 'mpd_prelayout_manager_init', $this );
	}

	/**
	 * Load all registered layouts.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	public function load_layouts() {
		$this->layouts = $this->library->get_all_layouts();

		/**
		 * Filter registered pre-layouts.
		 *
		 * @since 2.1.0
		 *
		 * @param array $layouts Registered layouts.
		 */
		$this->layouts = apply_filters( 'mpd_prelayouts', $this->layouts );
	}

	/**
	 * Register REST API routes.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		register_rest_route(
			'mpd/v1',
			'/prelayouts',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_layouts_endpoint' ),
				'permission_callback' => array( $this, 'check_admin_permission' ),
			)
		);

		register_rest_route(
			'mpd/v1',
			'/prelayouts/(?P<layout_id>[\w-]+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_layout_endpoint' ),
				'permission_callback' => array( $this, 'check_admin_permission' ),
				'args'                => array(
					'layout_id' => array(
						'required'          => true,
						'sanitize_callback' => 'sanitize_key',
					),
				),
			)
		);

		register_rest_route(
			'mpd/v1',
			'/prelayouts/(?P<layout_id>[\w-]+)/preview',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_layout_preview_endpoint' ),
				'permission_callback' => array( $this, 'check_admin_permission' ),
				'args'                => array(
					'layout_id' => array(
						'required'          => true,
						'sanitize_callback' => 'sanitize_key',
					),
				),
			)
		);

		register_rest_route(
			'mpd/v1',
			'/prelayouts/import',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'import_layout_endpoint' ),
				'permission_callback' => array( $this, 'check_admin_permission' ),
				'args'                => array(
					'layout_id'   => array(
						'required'          => true,
						'sanitize_callback' => 'sanitize_key',
					),
					'template_id' => array(
						'required'          => true,
						'sanitize_callback' => 'absint',
					),
				),
			)
		);

		register_rest_route(
			'mpd/v1',
			'/prelayouts/by-type/(?P<template_type>[\w-]+)',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_layouts_by_type_endpoint' ),
				'permission_callback' => array( $this, 'check_admin_permission' ),
				'args'                => array(
					'template_type' => array(
						'required'          => true,
						'sanitize_callback' => 'sanitize_key',
					),
				),
			)
		);
	}

	/**
	 * Check admin permission.
	 *
	 * @since 2.1.0
	 *
	 * @return bool
	 */
	public function check_admin_permission() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Get all layouts endpoint.
	 *
	 * @since 2.1.0
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_layouts_endpoint( $request ) {
		$layouts = $this->get_all_layouts();
		
		return rest_ensure_response( array(
			'success' => true,
			'layouts' => $layouts,
		) );
	}

	/**
	 * Get layouts by template type endpoint.
	 *
	 * @since 2.1.0
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_layouts_by_type_endpoint( $request ) {
		$template_type = $request->get_param( 'template_type' );
		$layouts       = $this->get_layouts_by_type( $template_type );
		
		// Add custom layout option at the beginning.
		$custom_layout = array(
			'id'          => 'custom',
			'name'        => __( 'Custom Layout', 'magical-products-display' ),
			'description' => __( 'Start with a blank canvas and build your own design.', 'magical-products-display' ),
			'thumbnail'   => $this->library->get_thumbnail_url( 'custom-layout' ),
			'type'        => $template_type,
			'is_custom'   => true,
			'is_pro'      => false,
		);
		
		array_unshift( $layouts, $custom_layout );
		
		return rest_ensure_response( array(
			'success' => true,
			'layouts' => $layouts,
		) );
	}

	/**
	 * Get single layout endpoint.
	 *
	 * @since 2.1.0
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_layout_endpoint( $request ) {
		$layout_id = $request->get_param( 'layout_id' );
		$layout    = $this->get_layout( $layout_id );
		
		if ( ! $layout ) {
			return new \WP_Error(
				'layout_not_found',
				__( 'Layout not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}
		
		return rest_ensure_response( array(
			'success' => true,
			'layout'  => $layout,
		) );
	}

	/**
	 * Get layout preview endpoint.
	 *
	 * @since 2.1.0
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_layout_preview_endpoint( $request ) {
		$layout_id = $request->get_param( 'layout_id' );
		$layout    = $this->get_layout( $layout_id );
		
		if ( ! $layout ) {
			return new \WP_Error(
				'layout_not_found',
				__( 'Layout not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}
		
		$preview = $this->library->get_layout_preview( $layout_id );
		
		return rest_ensure_response( array(
			'success' => true,
			'layout'  => $layout,
			'preview' => $preview,
		) );
	}

	/**
	 * Import layout endpoint.
	 *
	 * @since 2.1.0
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function import_layout_endpoint( $request ) {
		$layout_id   = $request->get_param( 'layout_id' );
		$template_id = $request->get_param( 'template_id' );
		
		// If custom layout, just return success (empty canvas).
		if ( 'custom' === $layout_id ) {
			return rest_ensure_response( array(
				'success'  => true,
				'message'  => __( 'Template created with custom layout.', 'magical-products-display' ),
				'template' => array(
					'id'      => $template_id,
					'editUrl' => $this->get_elementor_edit_url( $template_id ),
				),
			) );
		}
		
		$layout = $this->get_layout( $layout_id );
		
		if ( ! $layout ) {
			return new \WP_Error(
				'layout_not_found',
				__( 'Layout not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}
		
		// Check if pro layout.
		if ( ! empty( $layout['is_pro'] ) && ! $this->is_pro_active() ) {
			return new \WP_Error(
				'pro_required',
				__( 'This layout requires Magical Shop Builder Pro.', 'magical-products-display' ),
				array( 'status' => 403 )
			);
		}
		
		// Import the layout.
		$result = $this->importer->import_layout( $layout_id, $template_id );
		
		if ( is_wp_error( $result ) ) {
			return $result;
		}
		
		return rest_ensure_response( array(
			'success'  => true,
			'message'  => __( 'Layout imported successfully.', 'magical-products-display' ),
			'template' => array(
				'id'      => $template_id,
				'editUrl' => $this->get_elementor_edit_url( $template_id ),
			),
		) );
	}

	/**
	 * Get all registered layouts.
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */
	public function get_all_layouts() {
		if ( empty( $this->layouts ) ) {
			$this->load_layouts();
		}
		return $this->layouts;
	}

	/**
	 * Get layouts by template type.
	 *
	 * @since 2.1.0
	 *
	 * @param string $type Template type.
	 * @return array
	 */
	public function get_layouts_by_type( $type ) {
		// Use library method which includes remote layouts.
		return $this->library->get_layouts_by_type( $type );
	}

	/**
	 * Get single layout.
	 *
	 * @since 2.1.0
	 *
	 * @param string $layout_id Layout ID.
	 * @return array|null
	 */
	public function get_layout( $layout_id ) {
		$layouts = $this->get_all_layouts();
		
		foreach ( $layouts as $layout ) {
			if ( isset( $layout['id'] ) && $layout['id'] === $layout_id ) {
				return $layout;
			}
		}
		
		return null;
	}

	/**
	 * Register a new layout.
	 *
	 * @since 2.1.0
	 *
	 * @param array $layout Layout configuration.
	 * @return bool
	 */
	public function register_layout( $layout ) {
		if ( empty( $layout['id'] ) || empty( $layout['type'] ) ) {
			return false;
		}
		
		$this->layouts[] = $layout;
		return true;
	}

	/**
	 * Get Elementor edit URL for a template.
	 *
	 * @since 2.1.0
	 *
	 * @param int $template_id Template post ID.
	 * @return string
	 */
	private function get_elementor_edit_url( $template_id ) {
		return admin_url( 'post.php?post=' . $template_id . '&action=elementor' );
	}

	/**
	 * Check if pro version is active.
	 *
	 * @since 2.1.0
	 *
	 * @return bool
	 */
	private function is_pro_active() {
		return class_exists( 'MPD\MagicalShopBuilder\Core\Pro' ) && 
			   \MPD\MagicalShopBuilder\Core\Pro::is_active();
	}

	/**
	 * Get library instance.
	 *
	 * @since 2.1.0
	 *
	 * @return PreLayout_Library
	 */
	public function get_library() {
		return $this->library;
	}

	/**
	 * Get importer instance.
	 *
	 * @since 2.1.0
	 *
	 * @return PreLayout_Importer
	 */
	public function get_importer() {
		return $this->importer;
	}
}
