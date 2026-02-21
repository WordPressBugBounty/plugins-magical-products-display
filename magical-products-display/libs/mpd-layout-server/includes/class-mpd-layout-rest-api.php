<?php
/**
 * Layout REST API
 *
 * Provides REST API endpoints for distributing layouts.
 *
 * @package MPD_Layout_Server
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MPD_Layout_REST_API
 *
 * Handles REST API registration and endpoints.
 *
 * @since 1.0.0
 */
class MPD_Layout_REST_API {

	/**
	 * REST namespace.
	 *
	 * @var string
	 */
	const REST_NAMESPACE = 'mpd-layout-server/v1';

	/**
	 * Single instance.
	 *
	 * @var MPD_Layout_REST_API|null
	 */
	private static $instance = null;

	/**
	 * Get single instance.
	 *
	 * @since 1.0.0
	 *
	 * @return MPD_Layout_REST_API
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
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST routes.
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {
		// Get all layouts.
		register_rest_route(
			self::REST_NAMESPACE,
			'/layouts',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_layouts' ),
				'permission_callback' => array( $this, 'public_permission_check' ),
				'args'                => array(
					'type'     => array(
						'description'       => __( 'Filter by layout type.', 'magical-products-display' ),
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
					'category' => array(
						'description'       => __( 'Filter by category.', 'magical-products-display' ),
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
					'pro'      => array(
						'description'       => __( 'Filter by pro status.', 'magical-products-display' ),
						'type'              => 'boolean',
						'sanitize_callback' => 'rest_sanitize_boolean',
					),
				),
			)
		);

		// Get single layout.
		register_rest_route(
			self::REST_NAMESPACE,
			'/layouts/(?P<layout_id>[a-zA-Z0-9-]+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_layout' ),
				'permission_callback' => array( $this, 'public_permission_check' ),
				'args'                => array(
					'layout_id' => array(
						'description'       => __( 'Layout ID.', 'magical-products-display' ),
						'type'              => 'string',
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);

		// Get layout structure (JSON).
		register_rest_route(
			self::REST_NAMESPACE,
			'/layouts/(?P<layout_id>[a-zA-Z0-9-]+)/structure',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_layout_structure' ),
				'permission_callback' => array( $this, 'public_permission_check' ),
				'args'                => array(
					'layout_id' => array(
						'description'       => __( 'Layout ID.', 'magical-products-display' ),
						'type'              => 'string',
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);

		// Get layouts by type.
		register_rest_route(
			self::REST_NAMESPACE,
			'/layouts/type/(?P<type>[a-zA-Z0-9-]+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_layouts_by_type' ),
				'permission_callback' => array( $this, 'public_permission_check' ),
				'args'                => array(
					'type' => array(
						'description'       => __( 'Layout type.', 'magical-products-display' ),
						'type'              => 'string',
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					),
				),
			)
		);

		// Verify license/API key (for future use).
		register_rest_route(
			self::REST_NAMESPACE,
			'/verify',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'verify_license' ),
				'permission_callback' => array( $this, 'public_permission_check' ),
				'args'                => array(
					'license_key' => array(
						'description'       => __( 'License key.', 'magical-products-display' ),
						'type'              => 'string',
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					),
					'site_url'    => array(
						'description'       => __( 'Site URL.', 'magical-products-display' ),
						'type'              => 'string',
						'required'          => true,
						'sanitize_callback' => 'esc_url_raw',
					),
				),
			)
		);

		// Server info.
		register_rest_route(
			self::REST_NAMESPACE,
			'/info',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_server_info' ),
				'permission_callback' => array( $this, 'public_permission_check' ),
			)
		);
	}

	/**
	 * Public permission check.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function public_permission_check() {
		// Allow public access to layout endpoints.
		// In production, implement API key validation here.
		return true;
	}

	/**
	 * Get all layouts.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_layouts( $request ) {
		$args = array(
			'post_type'      => MPD_Layout_Post_Type::POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		);

		// Meta query for filters.
		$meta_query = array();

		// Filter by type.
		$type = $request->get_param( 'type' );
		if ( ! empty( $type ) ) {
			$meta_query[] = array(
				'key'   => '_mpd_layout_type',
				'value' => $type,
			);
		}

		// Filter by category.
		$category = $request->get_param( 'category' );
		if ( ! empty( $category ) ) {
			$meta_query[] = array(
				'key'   => '_mpd_category',
				'value' => $category,
			);
		}

		// Filter by pro status.
		$pro = $request->get_param( 'pro' );
		if ( null !== $pro ) {
			$meta_query[] = array(
				'key'   => '_mpd_is_pro',
				'value' => $pro ? '1' : '0',
			);
		}

		if ( ! empty( $meta_query ) ) {
			$args['meta_query'] = $meta_query;
		}

		$posts   = get_posts( $args );
		$layouts = array();

		foreach ( $posts as $post ) {
			$layouts[] = $this->format_layout_response( $post );
		}

		return rest_ensure_response(
			array(
				'success' => true,
				'count'   => count( $layouts ),
				'layouts' => $layouts,
			)
		);
	}

	/**
	 * Get single layout.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_layout( $request ) {
		$layout_id = $request->get_param( 'layout_id' );
		$post      = $this->get_layout_post_by_id( $layout_id );

		if ( ! $post ) {
			return new WP_Error(
				'layout_not_found',
				__( 'Layout not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}

		$layout = $this->format_layout_response( $post, true );

		return rest_ensure_response(
			array(
				'success' => true,
				'layout'  => $layout,
			)
		);
	}

	/**
	 * Get layout structure.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_layout_structure( $request ) {
		$layout_id = $request->get_param( 'layout_id' );
		$post      = $this->get_layout_post_by_id( $layout_id );

		if ( ! $post ) {
			return new WP_Error(
				'layout_not_found',
				__( 'Layout not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}

		$structure = get_post_meta( $post->ID, '_mpd_layout_structure', true );

		if ( empty( $structure ) ) {
			return new WP_Error(
				'structure_not_found',
				__( 'Layout structure not found.', 'magical-products-display' ),
				array( 'status' => 404 )
			);
		}

		$decoded = json_decode( $structure, true );

		return rest_ensure_response(
			array(
				'success'   => true,
				'layout_id' => $layout_id,
				'structure' => $decoded,
			)
		);
	}

	/**
	 * Get layouts by type.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_layouts_by_type( $request ) {
		$type = $request->get_param( 'type' );

		$valid_types = array( 'single-product', 'archive-product', 'cart', 'checkout', 'my-account', 'empty-cart', 'thankyou' );
		if ( ! in_array( $type, $valid_types, true ) ) {
			return new WP_Error(
				'invalid_type',
				__( 'Invalid layout type.', 'magical-products-display' ),
				array( 'status' => 400 )
			);
		}

		$args = array(
			'post_type'      => MPD_Layout_Post_Type::POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'   => '_mpd_layout_type',
					'value' => $type,
				),
			),
		);

		$posts   = get_posts( $args );
		$layouts = array();

		foreach ( $posts as $post ) {
			$layouts[] = $this->format_layout_response( $post );
		}

		return rest_ensure_response(
			array(
				'success' => true,
				'type'    => $type,
				'count'   => count( $layouts ),
				'layouts' => $layouts,
			)
		);
	}

	/**
	 * Verify license.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function verify_license( $request ) {
		$license_key = $request->get_param( 'license_key' );
		$site_url    = $request->get_param( 'site_url' );

		// Implement license verification logic here.
		// For now, return a placeholder response.
		return rest_ensure_response(
			array(
				'success'   => true,
				'valid'     => true,
				'message'   => __( 'License verified successfully.', 'magical-products-display' ),
				'pro_access' => true,
			)
		);
	}

	/**
	 * Get server info.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_REST_Response
	 */
	public function get_server_info() {
		$layout_count = wp_count_posts( MPD_Layout_Post_Type::POST_TYPE );

		return rest_ensure_response(
			array(
				'success'  => true,
				'name'     => 'MPD Layout Server',
				'version'  => MPD_LAYOUT_SERVER_VERSION,
				'layouts'  => array(
					'published' => isset( $layout_count->publish ) ? (int) $layout_count->publish : 0,
					'draft'     => isset( $layout_count->draft ) ? (int) $layout_count->draft : 0,
				),
				'endpoints' => array(
					'layouts'           => rest_url( self::REST_NAMESPACE . '/layouts' ),
					'layouts_by_type'   => rest_url( self::REST_NAMESPACE . '/layouts/type/{type}' ),
					'single_layout'     => rest_url( self::REST_NAMESPACE . '/layouts/{layout_id}' ),
					'layout_structure'  => rest_url( self::REST_NAMESPACE . '/layouts/{layout_id}/structure' ),
					'verify'            => rest_url( self::REST_NAMESPACE . '/verify' ),
				),
			)
		);
	}

	/**
	 * Get layout post by layout ID.
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout_id Layout ID.
	 * @return WP_Post|null
	 */
	private function get_layout_post_by_id( $layout_id ) {
		$args = array(
			'post_type'      => MPD_Layout_Post_Type::POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key'   => '_mpd_layout_id',
					'value' => $layout_id,
				),
			),
		);

		$posts = get_posts( $args );

		return ! empty( $posts ) ? $posts[0] : null;
	}

	/**
	 * Format layout response.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post            Post object.
	 * @param bool    $include_structure Include full structure.
	 * @return array
	 */
	private function format_layout_response( $post, $include_structure = false ) {
		$layout_id   = get_post_meta( $post->ID, '_mpd_layout_id', true );
		$layout_type = get_post_meta( $post->ID, '_mpd_layout_type', true );
		$category    = get_post_meta( $post->ID, '_mpd_category', true );
		$description = get_post_meta( $post->ID, '_mpd_description', true );
		$is_pro      = get_post_meta( $post->ID, '_mpd_is_pro', true );
		$widgets     = get_post_meta( $post->ID, '_mpd_layout_widgets', true );

		// Get thumbnail URL.
		$thumbnail_url = '';
		$thumbnail_id  = get_post_thumbnail_id( $post->ID );
		if ( $thumbnail_id ) {
			$thumbnail_url = wp_get_attachment_image_url( $thumbnail_id, 'medium' );
		}

		$response = array(
			'id'          => $layout_id,
			'name'        => $post->post_title,
			'description' => $description,
			'thumbnail'   => $thumbnail_url,
			'type'        => $layout_type,
			'category'    => $category,
			'is_pro'      => '1' === $is_pro,
			'widgets'     => is_array( $widgets ) ? $widgets : array(),
			'source'      => 'server',
			'updated_at'  => $post->post_modified_gmt,
		);

		if ( $include_structure ) {
			$structure = get_post_meta( $post->ID, '_mpd_layout_structure', true );
			if ( ! empty( $structure ) ) {
				$response['structure'] = json_decode( $structure, true );
			}
		}

		return $response;
	}
}
