<?php
/**
 * REST API endpoints for Magical Shop Builder.
 *
 * Handles all REST API endpoints for the admin dashboard.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Admin;

use MPD\MagicalShopBuilder\Core\Pro;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class REST_API
 *
 * Handles REST API endpoint registration and callbacks.
 *
 * @since 2.0.0
 */
class REST_API {

	/**
	 * REST API namespace.
	 *
	 * @var string
	 */
	const NAMESPACE = 'mpd/v1';

	/**
	 * Instance of the class.
	 *
	 * @var REST_API|null
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @since 2.0.0
	 *
	 * @return REST_API
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
	 * @since 2.0.0
	 */
	public function __construct() {
		// Empty constructor.
	}

	/**
	 * Initialize the REST API.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST API routes.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function register_routes() {
		// Settings endpoints.
		register_rest_route(
			self::NAMESPACE,
			'/settings',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_settings' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_settings' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
					'args'                => $this->get_settings_args(),
				),
			)
		);

		// Templates endpoints.
		register_rest_route(
			self::NAMESPACE,
			'/templates',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_templates' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_template' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
					'args'                => $this->get_template_args(),
				),
			)
		);

		// Single template endpoint.
		register_rest_route(
			self::NAMESPACE,
			'/templates/(?P<id>\d+)',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_template' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
					'args'                => array(
						'id' => array(
							'required'          => true,
							'validate_callback' => function( $param ) {
								return is_numeric( $param );
							},
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_template' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
					'args'                => $this->get_template_args(),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_template' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
			)
		);

		// Template conditions endpoint.
		register_rest_route(
			self::NAMESPACE,
			'/templates/(?P<id>\d+)/conditions',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_template_conditions' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_template_conditions' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
			)
		);

		// Template duplicate endpoint.
		register_rest_route(
			self::NAMESPACE,
			'/templates/(?P<id>\d+)/duplicate',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'duplicate_template' ),
				'permission_callback' => array( $this, 'check_admin_permission' ),
			)
		);

		// Template priority endpoint.
		register_rest_route(
			self::NAMESPACE,
			'/templates/(?P<id>\d+)/priority',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'update_template_priority' ),
				'permission_callback' => array( $this, 'check_admin_permission' ),
				'args'                => array(
					'priority' => array(
						'required'          => true,
						'type'              => 'integer',
						'sanitize_callback' => 'absint',
					),
				),
			)
		);

		// Widgets endpoints.
		register_rest_route(
			self::NAMESPACE,
			'/widgets',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_widgets' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_widgets' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
			)
		);

		// Stats endpoint.
		register_rest_route(
			self::NAMESPACE,
			'/stats',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_stats' ),
				'permission_callback' => array( $this, 'check_admin_permission' ),
			)
		);

		// License endpoint.
		register_rest_route(
			self::NAMESPACE,
			'/license',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_license_status' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'activate_license' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
					'args'                => array(
						'license_key' => array(
							'required'          => true,
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'deactivate_license' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
			)
		);
	}

	/**
	 * Check if user has admin permission.
	 *
	 * @since 2.0.0
	 *
	 * @return bool|WP_Error
	 */
	public function check_admin_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'rest_forbidden',
				__( 'You do not have permission to access this resource.', 'magical-products-display' ),
				array( 'status' => 403 )
			);
		}
		return true;
	}

	/**
	 * Get settings.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public function get_settings( $request ) {
		$settings = Settings::instance()->get_all_settings();

		return new WP_REST_Response( $settings, 200 );
	}

	/**
	 * Update settings.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function update_settings( $request ) {
		$params = $request->get_params();
		
		// Remove non-setting params.
		unset( $params['_locale'] );

		$result = Settings::instance()->update_settings( $params );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return new WP_REST_Response(
			array(
				'success'  => true,
				'message'  => __( 'Settings saved successfully.', 'magical-products-display' ),
				'settings' => Settings::instance()->get_all_settings(),
			),
			200
		);
	}

	/**
	 * Get templates.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public function get_templates( $request ) {
		$type = $request->get_param( 'type' );
		
		$args = array(
			'post_type'      => 'mpd_template',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		);

		if ( $type ) {
			$args['meta_query'] = array(
				array(
					'key'   => '_mpd_template_type',
					'value' => sanitize_text_field( $type ),
				),
			);
		}

		$templates = get_posts( $args );
		$data      = array();

		foreach ( $templates as $template ) {
			$data[] = $this->prepare_template_for_response( $template );
		}

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Get single template.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_template( $request ) {
		$id       = absint( $request->get_param( 'id' ) );
		$template = get_post( $id );

		if ( ! $template || 'mpd_template' !== $template->post_type ) {
			return new WP_Error(
				'template_not_found',
				__( 'Template not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}

		return new WP_REST_Response( $this->prepare_template_for_response( $template ), 200 );
	}

	/**
	 * Create template.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function create_template( $request ) {
		$title  = sanitize_text_field( $request->get_param( 'title' ) );
		$type   = sanitize_key( $request->get_param( 'type' ) );
		$layout = sanitize_key( $request->get_param( 'layout' ) );

		// Default to canvas if not provided.
		if ( empty( $layout ) || ! in_array( $layout, array( 'elementor_canvas', 'elementor_header_footer' ), true ) ) {
			$layout = 'elementor_canvas';
		}

		if ( empty( $title ) || empty( $type ) ) {
			return new WP_Error(
				'missing_params',
				__( 'Title and type are required.', 'magical-products-display' ),
				array( 'status' => 400 )
			);
		}

		$post_id = wp_insert_post(
			array(
				'post_title'  => $title,
				'post_type'   => 'mpd_template',
				'post_status' => 'publish',
			)
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		// Set template meta.
		update_post_meta( $post_id, '_mpd_template_type', $type );
		update_post_meta( $post_id, '_mpd_template_conditions', array() );
		update_post_meta( $post_id, '_mpd_template_layout', $layout );

		// Initialize Elementor data for the template.
		// Set Elementor edit mode - this tells Elementor this post uses the builder.
		update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		// Set empty data to allow Elementor to edit.
		update_post_meta( $post_id, '_elementor_data', '[]' );
		// Set page template based on layout selection.
		update_post_meta( $post_id, '_wp_page_template', $layout );

		$template = get_post( $post_id );

		return new WP_REST_Response(
			array(
				'success'  => true,
				'message'  => __( 'Template created successfully.', 'magical-products-display' ),
				'template' => $this->prepare_template_for_response( $template ),
			),
			201
		);
	}

	/**
	 * Update template.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function update_template( $request ) {
		$id       = absint( $request->get_param( 'id' ) );
		$template = get_post( $id );

		if ( ! $template || 'mpd_template' !== $template->post_type ) {
			return new WP_Error(
				'template_not_found',
				__( 'Template not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}

		$title = $request->get_param( 'title' );
		$type  = $request->get_param( 'type' );

		if ( $title ) {
			wp_update_post(
				array(
					'ID'         => $id,
					'post_title' => sanitize_text_field( $title ),
				)
			);
		}

		if ( $type ) {
			update_post_meta( $id, '_mpd_template_type', sanitize_key( $type ) );
		}

		$template = get_post( $id );

		return new WP_REST_Response(
			array(
				'success'  => true,
				'message'  => __( 'Template updated successfully.', 'magical-products-display' ),
				'template' => $this->prepare_template_for_response( $template ),
			),
			200
		);
	}

	/**
	 * Delete template.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function delete_template( $request ) {
		$id       = absint( $request->get_param( 'id' ) );
		$template = get_post( $id );

		if ( ! $template || 'mpd_template' !== $template->post_type ) {
			return new WP_Error(
				'template_not_found',
				__( 'Template not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}

		wp_delete_post( $id, true );

		return new WP_REST_Response(
			array(
				'success' => true,
				'message' => __( 'Template deleted successfully.', 'magical-products-display' ),
			),
			200
		);
	}

	/**
	 * Get template conditions.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_template_conditions( $request ) {
		$id       = absint( $request->get_param( 'id' ) );
		$template = get_post( $id );

		if ( ! $template || 'mpd_template' !== $template->post_type ) {
			return new WP_Error(
				'template_not_found',
				__( 'Template not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}

		$conditions = get_post_meta( $id, '_mpd_template_conditions', true );
		
		if ( ! is_array( $conditions ) ) {
			$conditions = array();
		}

		return new WP_REST_Response( $conditions, 200 );
	}

	/**
	 * Update template conditions.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function update_template_conditions( $request ) {
		$id       = absint( $request->get_param( 'id' ) );
		$template = get_post( $id );

		if ( ! $template || 'mpd_template' !== $template->post_type ) {
			return new WP_Error(
				'template_not_found',
				__( 'Template not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}

		$conditions = $request->get_param( 'conditions' );
		
		if ( ! is_array( $conditions ) ) {
			$conditions = array();
		}

		// Sanitize conditions.
		$sanitized = array();
		foreach ( $conditions as $condition ) {
			$sanitized[] = array(
				'type'      => isset( $condition['type'] ) ? sanitize_key( $condition['type'] ) : 'include',
				'condition' => isset( $condition['condition'] ) ? sanitize_key( $condition['condition'] ) : '',
				'value'     => isset( $condition['value'] ) ? $this->sanitize_condition_value( $condition['value'] ) : '',
			);
		}

		update_post_meta( $id, '_mpd_template_conditions', $sanitized );

		return new WP_REST_Response(
			array(
				'success'    => true,
				'message'    => __( 'Conditions updated successfully.', 'magical-products-display' ),
				'conditions' => $sanitized,
			),
			200
		);
	}

	/**
	 * Duplicate a template.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function duplicate_template( $request ) {
		$id       = absint( $request->get_param( 'id' ) );
		$template = get_post( $id );

		if ( ! $template || 'mpd_template' !== $template->post_type ) {
			return new WP_Error(
				'template_not_found',
				__( 'Template not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}

		// Create new post with duplicated data.
		$new_post_id = wp_insert_post(
			array(
				'post_title'   => sprintf(
					/* translators: %s: Original template title */
					__( '%s (Copy)', 'magical-products-display' ),
					$template->post_title
				),
				'post_type'    => 'mpd_template',
				'post_status'  => 'draft',
				'post_content' => $template->post_content,
			)
		);

		if ( is_wp_error( $new_post_id ) ) {
			return new WP_Error(
				'template_duplicate_failed',
				__( 'Failed to duplicate template.', 'magical-products-display' ),
				array( 'status' => 500 )
			);
		}

		// Copy meta data.
		$meta_keys = array(
			'_mpd_template_type',
			'_mpd_template_conditions',
			'_mpd_template_priority',
			'_elementor_data',
			'_elementor_edit_mode',
			'_wp_page_template',
		);

		foreach ( $meta_keys as $meta_key ) {
			$meta_value = get_post_meta( $id, $meta_key, true );
			if ( $meta_value ) {
				update_post_meta( $new_post_id, $meta_key, $meta_value );
			}
		}

		$new_template = get_post( $new_post_id );

		return new WP_REST_Response(
			array(
				'success'  => true,
				'message'  => __( 'Template duplicated successfully.', 'magical-products-display' ),
				'template' => $this->prepare_template_for_response( $new_template ),
			),
			201
		);
	}

	/**
	 * Update template priority.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function update_template_priority( $request ) {
		$id       = absint( $request->get_param( 'id' ) );
		$priority = absint( $request->get_param( 'priority' ) );
		$template = get_post( $id );

		if ( ! $template || 'mpd_template' !== $template->post_type ) {
			return new WP_Error(
				'template_not_found',
				__( 'Template not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}

		update_post_meta( $id, '_mpd_template_priority', $priority );

		return new WP_REST_Response(
			array(
				'success'  => true,
				'message'  => __( 'Priority updated successfully.', 'magical-products-display' ),
				'priority' => $priority,
			),
			200
		);
	}

	/**
	 * Get widgets.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public function get_widgets( $request ) {
		$widgets       = $this->get_available_widgets();
		$enabled       = get_option( 'mpd_enabled_widgets', array() );
		$known_widgets = get_option( 'mpd_known_widgets', array() );

		// If no widgets have been configured yet (fresh install), enable all by default.
		if ( empty( $known_widgets ) ) {
			$enabled       = array_keys( $widgets );
			$known_widgets = array_keys( $widgets );
			update_option( 'mpd_known_widgets', $known_widgets );
		} else {
			// For existing installations, check if there are truly new widgets (not in known list).
			// Enable them by default (except Pro widgets for free users).
			$all_widget_ids = array_keys( $widgets );
			$new_widgets    = array_diff( $all_widget_ids, $known_widgets );
			
			// Add new non-Pro widgets as enabled and mark them as known.
			if ( ! empty( $new_widgets ) ) {
				foreach ( $new_widgets as $widget_id ) {
					if ( isset( $widgets[ $widget_id ] ) && ! $widgets[ $widget_id ]['is_pro'] ) {
						$enabled[] = $widget_id;
					}
					$known_widgets[] = $widget_id;
				}
				update_option( 'mpd_known_widgets', $known_widgets );
			}
		}

		$data = array();
		foreach ( $widgets as $id => $widget ) {
			$data[] = array(
				'id'          => $id,
				'name'        => $widget['name'],
				'description' => $widget['description'],
				'category'    => $widget['category'],
				'isPro'       => $widget['is_pro'],
				'enabled'     => in_array( $id, $enabled, true ),
			);
		}

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Update widgets.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function update_widgets( $request ) {
		$widgets = $request->get_param( 'widgets' );

		if ( ! is_array( $widgets ) ) {
			return new WP_Error(
				'invalid_widgets',
				__( 'Invalid widgets data.', 'magical-products-display' ),
				array( 'status' => 400 )
			);
		}

		$enabled       = array();
		$known_widgets = array();
		
		foreach ( $widgets as $widget ) {
			if ( isset( $widget['id'] ) ) {
				$widget_id       = sanitize_key( $widget['id'] );
				$known_widgets[] = $widget_id;
				
				if ( isset( $widget['enabled'] ) && $widget['enabled'] ) {
					$enabled[] = $widget_id;
				}
			}
		}

		update_option( 'mpd_enabled_widgets', $enabled );
		update_option( 'mpd_known_widgets', $known_widgets );

		return new WP_REST_Response(
			array(
				'success' => true,
				'message' => __( 'Widgets updated successfully.', 'magical-products-display' ),
			),
			200
		);
	}

	/**
	 * Get stats.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public function get_stats( $request ) {
		// Get template counts.
		$template_counts = wp_count_posts( 'mpd_template' );
		
		// Get product counts.
		$product_counts = wp_count_posts( 'product' );
		
		// Get WooCommerce stats.
		$wc_stats = array(
			'orders_today'     => $this->get_orders_count( 'today' ),
			'orders_this_week' => $this->get_orders_count( 'week' ),
			'revenue_today'    => $this->get_revenue( 'today' ),
		);

		$data = array(
			'templates'     => array(
				'total'     => isset( $template_counts->publish ) ? (int) $template_counts->publish : 0,
				'draft'     => isset( $template_counts->draft ) ? (int) $template_counts->draft : 0,
			),
			'products'      => array(
				'total'     => isset( $product_counts->publish ) ? (int) $product_counts->publish : 0,
			),
			'woocommerce'   => $wc_stats,
			'plugin'        => array(
				'version'      => MAGICAL_PRODUCTS_DISPLAY_VERSION,
				'is_pro'       => Pro::is_active(),
				'install_date' => get_option( 'mpd_install_date', '' ),
			),
		);

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Get license status.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public function get_license_status( $request ) {
		return new WP_REST_Response(
			array(
				'is_active'     => Pro::is_active(),
				'has_license'   => Pro::has_valid_license(),
				'license_key'   => $this->get_masked_license_key(),
			),
			200
		);
	}

	/**
	 * Activate license.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function activate_license( $request ) {
		$license_key = sanitize_text_field( $request->get_param( 'license_key' ) );

		if ( empty( $license_key ) ) {
			return new WP_Error(
				'invalid_license',
				__( 'Please enter a valid license key.', 'magical-products-display' ),
				array( 'status' => 400 )
			);
		}

		/**
		 * Filter license activation.
		 *
		 * Pro plugin hooks into this to handle actual license validation.
		 *
		 * @since 2.0.0
		 *
		 * @param bool   $activated   Whether license was activated.
		 * @param string $license_key The license key.
		 */
		$result = apply_filters( 'mpd_activate_license', false, $license_key );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		if ( ! $result ) {
			return new WP_Error(
				'activation_failed',
				__( 'License activation failed. Please check your license key and try again.', 'magical-products-display' ),
				array( 'status' => 400 )
			);
		}

		// Refresh pro status.
		Pro::refresh_status();

		return new WP_REST_Response(
			array(
				'success'   => true,
				'message'   => __( 'License activated successfully!', 'magical-products-display' ),
				'is_active' => Pro::is_active(),
			),
			200
		);
	}

	/**
	 * Deactivate license.
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function deactivate_license( $request ) {
		/**
		 * Filter license deactivation.
		 *
		 * Pro plugin hooks into this to handle license deactivation.
		 *
		 * @since 2.0.0
		 *
		 * @param bool $deactivated Whether license was deactivated.
		 */
		$result = apply_filters( 'mpd_deactivate_license', false );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Refresh pro status.
		Pro::refresh_status();

		return new WP_REST_Response(
			array(
				'success'   => true,
				'message'   => __( 'License deactivated.', 'magical-products-display' ),
				'is_active' => Pro::is_active(),
			),
			200
		);
	}

	/**
	 * Prepare template for response.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_Post $template Template post object.
	 * @return array
	 */
	private function prepare_template_for_response( $template ) {
		$type       = get_post_meta( $template->ID, '_mpd_template_type', true );
		$conditions = get_post_meta( $template->ID, '_mpd_template_conditions', true );
		$layout     = get_post_meta( $template->ID, '_mpd_template_layout', true );

		return array(
			'id'         => $template->ID,
			'title'      => $template->post_title,
			'type'       => $type ? $type : 'single-product',
			'layout'     => $layout ? $layout : 'elementor_canvas',
			'conditions' => is_array( $conditions ) ? $conditions : array(),
			'editUrl'    => add_query_arg(
				array(
					'post'   => $template->ID,
					'action' => 'elementor',
				),
				admin_url( 'post.php' )
			),
			'status'     => $template->post_status,
			'created'    => $template->post_date,
			'modified'   => $template->post_modified,
		);
	}

	/**
	 * Get available widgets.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_available_widgets() {
		return array(
			// Product Display widgets.
			'products-grid'     => array(
				'name'        => __( 'Products Grid', 'magical-products-display' ),
				'description' => __( 'Display products in a grid layout.', 'magical-products-display' ),
				'category'    => 'product-display',
				'is_pro'      => false,
			),
			'products-list'     => array(
				'name'        => __( 'Products List', 'magical-products-display' ),
				'description' => __( 'Display products in a list layout.', 'magical-products-display' ),
				'category'    => 'product-display',
				'is_pro'      => false,
			),
			'products-carousel' => array(
				'name'        => __( 'Products Carousel', 'magical-products-display' ),
				'description' => __( 'Display products in a carousel.', 'magical-products-display' ),
				'category'    => 'product-display',
				'is_pro'      => false,
			),
			'products-slider'   => array(
				'name'        => __( 'Products Slider', 'magical-products-display' ),
				'description' => __( 'Display products in a slider.', 'magical-products-display' ),
				'category'    => 'product-display',
				'is_pro'      => false,
			),
			'products-tab'      => array(
				'name'        => __( 'Products Tab', 'magical-products-display' ),
				'description' => __( 'Display products in tabs.', 'magical-products-display' ),
				'category'    => 'product-display',
				'is_pro'      => false,
			),
			'products-categories' => array(
				'name'        => __( 'Products Categories', 'magical-products-display' ),
				'description' => __( 'Display product categories.', 'magical-products-display' ),
				'category'    => 'product-display',
				'is_pro'      => false,
			),
			'products-awesome-list' => array(
				'name'        => __( 'Products Awesome List', 'magical-products-display' ),
				'description' => __( 'Display products in an awesome list.', 'magical-products-display' ),
				'category'    => 'product-display',
				'is_pro'      => false,
			),
			'shop-products'     => array(
				'name'        => __( 'Shop Products', 'magical-products-display' ),
				'description' => __( 'Display shop products with filters.', 'magical-products-display' ),
				'category'    => 'product-display',
				'is_pro'      => false,
			),

			// Single Product widgets (Phase 4).
			'product-title'     => array(
				'name'        => __( 'Product Title', 'magical-products-display' ),
				'description' => __( 'Display product title.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'product-images'    => array(
				'name'        => __( 'Product Images', 'magical-products-display' ),
				'description' => __( 'Display product gallery images.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'product-price'     => array(
				'name'        => __( 'Product Price', 'magical-products-display' ),
				'description' => __( 'Display product price.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'product-add-to-cart' => array(
				'name'        => __( 'Add to Cart', 'magical-products-display' ),
				'description' => __( 'Display add to cart button.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'product-short-description' => array(
				'name'        => __( 'Short Description', 'magical-products-display' ),
				'description' => __( 'Display product short description.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'product-description' => array(
				'name'        => __( 'Product Description', 'magical-products-display' ),
				'description' => __( 'Display product full description.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'product-meta'      => array(
				'name'        => __( 'Product Meta', 'magical-products-display' ),
				'description' => __( 'Display SKU, categories, tags.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'product-rating'    => array(
				'name'        => __( 'Product Rating', 'magical-products-display' ),
				'description' => __( 'Display product rating stars.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'product-stock'     => array(
				'name'        => __( 'Stock Status', 'magical-products-display' ),
				'description' => __( 'Display product stock status.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'product-tabs'      => array(
				'name'        => __( 'Product Tabs', 'magical-products-display' ),
				'description' => __( 'Display product data tabs.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'related-products'  => array(
				'name'        => __( 'Related Products', 'magical-products-display' ),
				'description' => __( 'Display related products.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'upsell-products'   => array(
				'name'        => __( 'Upsell Products', 'magical-products-display' ),
				'description' => __( 'Display upsell products.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'product-additional-info' => array(
				'name'        => __( 'Additional Info', 'magical-products-display' ),
				'description' => __( 'Display product attributes.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),
			'product-reviews'   => array(
				'name'        => __( 'Product Reviews', 'magical-products-display' ),
				'description' => __( 'Display product reviews.', 'magical-products-display' ),
				'category'    => 'single-product',
				'is_pro'      => false,
			),

			// Cart widgets (Phase 5).
			'cart-table'        => array(
				'name'        => __( 'Cart Table', 'magical-products-display' ),
				'description' => __( 'Display cart items table.', 'magical-products-display' ),
				'category'    => 'cart',
				'is_pro'      => false,
			),
			'cart-totals'       => array(
				'name'        => __( 'Cart Totals', 'magical-products-display' ),
				'description' => __( 'Display cart totals and checkout button.', 'magical-products-display' ),
				'category'    => 'cart',
				'is_pro'      => false,
			),
			'cart-coupon'       => array(
				'name'        => __( 'Cart Coupon', 'magical-products-display' ),
				'description' => __( 'Display coupon form.', 'magical-products-display' ),
				'category'    => 'cart',
				'is_pro'      => false,
			),
			'cross-sells'       => array(
				'name'        => __( 'Cross-Sells', 'magical-products-display' ),
				'description' => __( 'Display cross-sell products.', 'magical-products-display' ),
				'category'    => 'cart',
				'is_pro'      => false,
			),
			'empty-cart'        => array(
				'name'        => __( 'Empty Cart', 'magical-products-display' ),
				'description' => __( 'Display empty cart message.', 'magical-products-display' ),
				'category'    => 'cart',
				'is_pro'      => false,
			),
			'return-to-shop'    => array(
				'name'        => __( 'Return to Shop', 'magical-products-display' ),
				'description' => __( 'Display return to shop button.', 'magical-products-display' ),
				'category'    => 'cart',
				'is_pro'      => false,
			),

			// Checkout widgets (Phase 6).
			'billing-form'      => array(
				'name'        => __( 'Billing Form', 'magical-products-display' ),
				'description' => __( 'Display billing address form.', 'magical-products-display' ),
				'category'    => 'checkout',
				'is_pro'      => false,
			),
			'shipping-form'     => array(
				'name'        => __( 'Shipping Form', 'magical-products-display' ),
				'description' => __( 'Display shipping address form.', 'magical-products-display' ),
				'category'    => 'checkout',
				'is_pro'      => false,
			),
			'order-review'      => array(
				'name'        => __( 'Order Review', 'magical-products-display' ),
				'description' => __( 'Display order review table.', 'magical-products-display' ),
				'category'    => 'checkout',
				'is_pro'      => false,
			),
			'payment-methods'   => array(
				'name'        => __( 'Payment Methods', 'magical-products-display' ),
				'description' => __( 'Display payment method options.', 'magical-products-display' ),
				'category'    => 'checkout',
				'is_pro'      => false,
			),
			'checkout-coupon'   => array(
				'name'        => __( 'Checkout Coupon', 'magical-products-display' ),
				'description' => __( 'Display coupon form on checkout.', 'magical-products-display' ),
				'category'    => 'checkout',
				'is_pro'      => false,
			),
			'place-order'       => array(
				'name'        => __( 'Place Order', 'magical-products-display' ),
				'description' => __( 'Display place order button.', 'magical-products-display' ),
				'category'    => 'checkout',
				'is_pro'      => false,
			),
			'order-notes'       => array(
				'name'        => __( 'Order Notes', 'magical-products-display' ),
				'description' => __( 'Display order notes field.', 'magical-products-display' ),
				'category'    => 'checkout',
				'is_pro'      => false,
			),
			'checkout-login'    => array(
				'name'        => __( 'Checkout Login', 'magical-products-display' ),
				'description' => __( 'Display login form on checkout.', 'magical-products-display' ),
				'category'    => 'checkout',
				'is_pro'      => false,
			),

			// My Account widgets (Phase 7).
			'account-navigation' => array(
				'name'        => __( 'Account Navigation', 'magical-products-display' ),
				'description' => __( 'Display account menu.', 'magical-products-display' ),
				'category'    => 'my-account',
				'is_pro'      => false,
			),
			'account-dashboard' => array(
				'name'        => __( 'Account Dashboard', 'magical-products-display' ),
				'description' => __( 'Display account dashboard.', 'magical-products-display' ),
				'category'    => 'my-account',
				'is_pro'      => false,
			),
			'account-orders'    => array(
				'name'        => __( 'My Orders', 'magical-products-display' ),
				'description' => __( 'Display customer orders.', 'magical-products-display' ),
				'category'    => 'my-account',
				'is_pro'      => false,
			),
			'account-addresses' => array(
				'name'        => __( 'My Addresses', 'magical-products-display' ),
				'description' => __( 'Display billing/shipping addresses.', 'magical-products-display' ),
				'category'    => 'my-account',
				'is_pro'      => false,
			),
			'account-details'   => array(
				'name'        => __( 'Account Details', 'magical-products-display' ),
				'description' => __( 'Display account edit form.', 'magical-products-display' ),
				'category'    => 'my-account',
				'is_pro'      => false,
			),
			'account-downloads' => array(
				'name'        => __( 'My Downloads', 'magical-products-display' ),
				'description' => __( 'Display downloadable products.', 'magical-products-display' ),
				'category'    => 'my-account',
				'is_pro'      => false,
			),
			'account-logout'    => array(
				'name'        => __( 'Logout', 'magical-products-display' ),
				'description' => __( 'Display logout button.', 'magical-products-display' ),
				'category'    => 'my-account',
				'is_pro'      => false,
			),

			// Shop Archive widgets (Phase 8).
			'products-archive'  => array(
				'name'        => __( 'Products Archive', 'magical-products-display' ),
				'description' => __( 'Display archive products grid.', 'magical-products-display' ),
				'category'    => 'shop-archive',
				'is_pro'      => false,
			),
			'archive-title'     => array(
				'name'        => __( 'Archive Title', 'magical-products-display' ),
				'description' => __( 'Display shop/category title.', 'magical-products-display' ),
				'category'    => 'shop-archive',
				'is_pro'      => false,
			),
			'archive-description' => array(
				'name'        => __( 'Archive Description', 'magical-products-display' ),
				'description' => __( 'Display shop/category description.', 'magical-products-display' ),
				'category'    => 'shop-archive',
				'is_pro'      => false,
			),
			'result-count'      => array(
				'name'        => __( 'Result Count', 'magical-products-display' ),
				'description' => __( 'Display showing X of Y results.', 'magical-products-display' ),
				'category'    => 'shop-archive',
				'is_pro'      => false,
			),
			'catalog-ordering'  => array(
				'name'        => __( 'Catalog Ordering', 'magical-products-display' ),
				'description' => __( 'Display sort dropdown.', 'magical-products-display' ),
				'category'    => 'shop-archive',
				'is_pro'      => false,
			),
			'pagination'        => array(
				'name'        => __( 'Pagination', 'magical-products-display' ),
				'description' => __( 'Display pagination links.', 'magical-products-display' ),
				'category'    => 'shop-archive',
				'is_pro'      => false,
			),
			'active-filters'    => array(
				'name'        => __( 'Active Filters', 'magical-products-display' ),
				'description' => __( 'Display active filter tags.', 'magical-products-display' ),
				'category'    => 'shop-archive',
				'is_pro'      => false,
			),
			'price-filter'      => array(
				'name'        => __( 'Price Filter', 'magical-products-display' ),
				'description' => __( 'Display price range slider.', 'magical-products-display' ),
				'category'    => 'shop-archive',
				'is_pro'      => false,
			),
			'attribute-filter'  => array(
				'name'        => __( 'Attribute Filter', 'magical-products-display' ),
				'description' => __( 'Display attribute filter.', 'magical-products-display' ),
				'category'    => 'shop-archive',
				'is_pro'      => false,
			),
			'category-filter'   => array(
				'name'        => __( 'Category Filter', 'magical-products-display' ),
				'description' => __( 'Display category filter.', 'magical-products-display' ),
				'category'    => 'shop-archive',
				'is_pro'      => false,
			),

			// Global/Utility widgets (Phase 9).
			'header-cart'       => array(
				'name'        => __( 'Header Cart', 'magical-products-display' ),
				'description' => __( 'Display mini cart icon.', 'magical-products-display' ),
				'category'    => 'global',
				'is_pro'      => false,
			),
			'breadcrumbs'       => array(
				'name'        => __( 'Breadcrumbs', 'magical-products-display' ),
				'description' => __( 'Display breadcrumb navigation.', 'magical-products-display' ),
				'category'    => 'global',
				'is_pro'      => false,
			),
			'store-notice'      => array(
				'name'        => __( 'Store Notice', 'magical-products-display' ),
				'description' => __( 'Display store notice banner.', 'magical-products-display' ),
				'category'    => 'global',
				'is_pro'      => false,
			),
			'recently-viewed'   => array(
				'name'        => __( 'Recently Viewed', 'magical-products-display' ),
				'description' => __( 'Display recently viewed products.', 'magical-products-display' ),
				'category'    => 'global',
				'is_pro'      => false,
			),
			'product-comparison' => array(
				'name'        => __( 'Product Comparison', 'magical-products-display' ),
				'description' => __( 'Display product comparison table.', 'magical-products-display' ),
				'category'    => 'global',
				'is_pro'      => false,
			),
			'wishlist'          => array(
				'name'        => __( 'Wishlist', 'magical-products-display' ),
				'description' => __( 'Display wishlist products.', 'magical-products-display' ),
				'category'    => 'global',
				'is_pro'      => false,
			),
			'ajax-search'       => array(
				'name'        => __( 'AJAX Search', 'magical-products-display' ),
				'description' => __( 'AJAX-powered product search.', 'magical-products-display' ),
				'category'    => 'global',
				'is_pro'      => false,
			),
			'pricing-table'     => array(
				'name'        => __( 'Pricing Table', 'magical-products-display' ),
				'description' => __( 'Display pricing tables.', 'magical-products-display' ),
				'category'    => 'global',
				'is_pro'      => false,
			),
			'accordion'         => array(
				'name'        => __( 'Accordion', 'magical-products-display' ),
				'description' => __( 'Display content in accordion.', 'magical-products-display' ),
				'category'    => 'global',
				'is_pro'      => false,
			),
			'testimonial-carousel' => array(
				'name'        => __( 'Testimonial Carousel', 'magical-products-display' ),
				'description' => __( 'Display testimonials in a carousel.', 'magical-products-display' ),
				'category'    => 'global',
				'is_pro'      => false,
			),
		);
	}

	/**
	 * Get settings args for validation.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_settings_args() {
		return array(
			'general'       => array(
				'type' => 'object',
			),
			'single_product' => array(
				'type' => 'object',
			),
			'cart_checkout' => array(
				'type' => 'object',
			),
			'my_account'    => array(
				'type' => 'object',
			),
			'performance'   => array(
				'type' => 'object',
			),
			'preloader'     => array(
				'type' => 'object',
			),
		);
	}

	/**
	 * Get template args for validation.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_template_args() {
		return array(
			'title' => array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'type'  => array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_key',
				'enum'              => array(
					'single-product',
					'archive-product',
					'cart',
					'checkout',
					'my-account',
					'empty-cart',
					'thankyou',
				),
			),
		);
	}

	/**
	 * Sanitize condition value.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return mixed
	 */
	private function sanitize_condition_value( $value ) {
		if ( is_array( $value ) ) {
			return array_map( 'sanitize_text_field', $value );
		}
		return sanitize_text_field( $value );
	}

	/**
	 * Get date boundary for a given period.
	 *
	 * @since 2.0.0
	 *
	 * @param string $period Period (today, week, month).
	 * @return string Date string.
	 */
	private function get_date_after( $period ) {
		switch ( $period ) {
			case 'today':
				return gmdate( 'Y-m-d 00:00:00' );
			case 'week':
				return gmdate( 'Y-m-d 00:00:00', strtotime( '-7 days' ) );
			case 'month':
				return gmdate( 'Y-m-d 00:00:00', strtotime( '-30 days' ) );
			default:
				return gmdate( 'Y-m-d 00:00:00' );
		}
	}

	/**
	 * Get orders count for period.
	 *
	 * @since 2.0.0
	 *
	 * @param string $period Period (today, week, month).
	 * @return int
	 */
	private function get_orders_count( $period ) {
		if ( ! function_exists( 'wc_get_orders' ) ) {
			return 0;
		}

		$args = array(
			'status'       => array( 'wc-completed', 'wc-processing' ),
			'date_created' => '>=' . $this->get_date_after( $period ),
			'return'       => 'ids',
		);

		$orders = wc_get_orders( $args );

		return count( $orders );
	}

	/**
	 * Get revenue for period.
	 *
	 * @since 2.0.0
	 *
	 * @param string $period Period (today, week, month).
	 * @return float
	 */
	private function get_revenue( $period ) {
		if ( ! function_exists( 'wc_get_orders' ) ) {
			return 0;
		}

		$args = array(
			'status'       => array( 'wc-completed', 'wc-processing' ),
			'date_created' => '>=' . $this->get_date_after( $period ),
		);

		$orders  = wc_get_orders( $args );
		$revenue = 0;

		foreach ( $orders as $order ) {
			$revenue += (float) $order->get_total();
		}

		return round( $revenue, 2 );
	}

	/**
	 * Get masked license key.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	private function get_masked_license_key() {
		$license_key = get_option( 'mgppro_license_key', '' );

		if ( empty( $license_key ) ) {
			return '';
		}

		// Show first 4 and last 4 characters.
		$length = strlen( $license_key );
		if ( $length <= 8 ) {
			return str_repeat( '*', $length );
		}

		return substr( $license_key, 0, 4 ) . str_repeat( '*', $length - 8 ) . substr( $license_key, -4 );
	}
}
