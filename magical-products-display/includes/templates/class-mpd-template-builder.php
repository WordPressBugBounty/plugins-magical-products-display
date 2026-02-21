<?php
/**
 * Template Builder
 *
 * Main orchestrator for the template builder system.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Templates;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Template_Builder
 *
 * Main class for initializing and orchestrating the template builder.
 *
 * @since 2.0.0
 */
class Template_Builder {

	/**
	 * Instance of the class.
	 *
	 * @var Template_Builder|null
	 */
	private static $instance = null;

	/**
	 * Template Manager instance.
	 *
	 * @var Template_Manager|null
	 */
	private $manager = null;

	/**
	 * Template Renderer instance.
	 *
	 * @var Template_Renderer|null
	 */
	private $renderer = null;

	/**
	 * Template Conditions instance.
	 *
	 * @var Template_Conditions|null
	 */
	private $conditions = null;

	/**
	 * PreLayout Manager instance.
	 *
	 * @var PreLayouts\PreLayout_Manager|null
	 */
	private $prelayout_manager = null;

	/**
	 * Get instance.
	 *
	 * @since 2.0.0
	 *
	 * @return Template_Builder
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
		$this->manager    = Template_Manager::instance();
		$this->renderer   = Template_Renderer::instance();
		$this->conditions = Template_Conditions::instance();

		// Load pre-layout classes.
		require_once __DIR__ . '/prelayouts/autoload.php';
		$this->prelayout_manager = PreLayouts\PreLayout_Manager::instance();
	}

	/**
	 * Initialize the template builder.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init() {
		// Initialize manager first (registers CPT).
		$this->manager->init();

		// Initialize pre-layout manager.
		$this->prelayout_manager->init();

		// Add preview mode support.
		add_action( 'template_redirect', array( $this, 'setup_preview_mode' ) );

		// Enqueue preview scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_preview_scripts' ) );

		// Register REST routes for template builder.
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );

		// Add body class for templates.
		add_filter( 'body_class', array( $this, 'add_body_class' ) );

		// Maybe flush rewrite rules after CPT is registered.
		add_action( 'init', array( $this, 'maybe_flush_rewrite_rules' ), 99 );

		/**
		 * Fires after template builder is initialized.
		 *
		 * @since 2.0.0
		 *
		 * @param Template_Builder $builder Template builder instance.
		 */
		do_action( 'mpd_template_builder_init', $this );
	}

	/**
	 * Maybe flush rewrite rules if needed.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function maybe_flush_rewrite_rules() {
		$version = get_option( 'mpd_template_cpt_version', '0' );
		
		// Flush if version changed or first time.
		if ( version_compare( $version, '2.0.0', '<' ) ) {
			flush_rewrite_rules();
			update_option( 'mpd_template_cpt_version', '2.0.0' );
		}
	}

	/**
	 * Setup preview mode for template editing.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function setup_preview_mode() {
		// Check if we're in preview mode.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['mpd_preview'] ) ) {
			return;
		}

		// Verify nonce and permissions.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'mpd_preview' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$template_type = isset( $_GET['mpd_preview'] ) ? sanitize_key( $_GET['mpd_preview'] ) : '';

		if ( empty( $template_type ) ) {
			return;
		}

		// Setup preview data based on template type.
		$this->setup_preview_data( $template_type );
	}

	/**
	 * Setup preview data for template editing.
	 *
	 * @since 2.0.0
	 *
	 * @param string $template_type Template type.
	 * @return void
	 */
	private function setup_preview_data( $template_type ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		global $product, $post;

		switch ( $template_type ) {
			case 'single-product':
				// Get a sample product for preview.
				$products = wc_get_products( array(
					'limit'  => 1,
					'status' => 'publish',
				) );

				if ( ! empty( $products ) ) {
					$product = $products[0];
					$post    = get_post( $product->get_id() );
					setup_postdata( $post );
				}
				break;

			case 'archive-product':
				// Archive preview setup.
				break;

			case 'cart':
			case 'empty-cart':
				// Cart preview - WC()->cart should be available.
				break;

			case 'checkout':
				// Checkout preview.
				break;

			case 'my-account':
				// My Account preview.
				break;

			case 'thankyou':
				// Thank you page preview.
				break;
		}

		/**
		 * Fires after preview data is setup.
		 *
		 * @since 2.0.0
		 *
		 * @param string $template_type Template type.
		 */
		do_action( 'mpd_preview_data_setup', $template_type );
	}

	/**
	 * Enqueue preview scripts.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function enqueue_preview_scripts() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['mpd_preview'] ) ) {
			return;
		}

		wp_enqueue_style(
			'mpd-preview',
			MAGICAL_PRODUCTS_DISPLAY_URL . '/assets/css/mpd-preview.css',
			array(),
			MAGICAL_PRODUCTS_DISPLAY_VERSION
		);
	}

	/**
	 * Register REST routes for template builder.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		register_rest_route(
			'mpd/v1',
			'/templates/types',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_template_types' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			'mpd/v1',
			'/templates/conditions/types',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_condition_types' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			'mpd/v1',
			'/templates/conditions/options',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_condition_options' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
				'args'                => array(
					'condition' => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_key',
					),
				),
			)
		);

		register_rest_route(
			'mpd/v1',
			'/templates/preview-url',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_preview_url' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
				'args'                => array(
					'type' => array(
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_key',
					),
				),
			)
		);
	}

	/**
	 * REST callback: Get template types.
	 *
	 * @since 2.0.0
	 *
	 * @return \WP_REST_Response
	 */
	public function get_template_types() {
		$types = $this->manager->get_template_types();

		$data = array();
		foreach ( $types as $key => $type ) {
			$data[] = array(
				'value'       => $key,
				'label'       => $type['label'],
				'description' => $type['description'],
				'icon'        => $type['icon'],
			);
		}

		return new \WP_REST_Response( $data, 200 );
	}

	/**
	 * REST callback: Get condition types.
	 *
	 * @since 2.0.0
	 *
	 * @return \WP_REST_Response
	 */
	public function get_condition_types() {
		$types = $this->conditions->get_condition_types();

		$data = array();
		foreach ( $types as $key => $type ) {
			$data[] = array(
				'value'       => $key,
				'label'       => $type['label'],
				'description' => $type['description'],
				'hasOptions'  => false !== $type['options'],
			);
		}

		return new \WP_REST_Response( $data, 200 );
	}

	/**
	 * REST callback: Get condition options.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_condition_options( $request ) {
		$condition = $request->get_param( 'condition' );
		$types     = $this->conditions->get_condition_types();

		if ( ! isset( $types[ $condition ] ) ) {
			return new \WP_REST_Response( array(), 200 );
		}

		$options_callback = $types[ $condition ]['options'];

		if ( ! $options_callback ) {
			return new \WP_REST_Response( array(), 200 );
		}

		if ( is_callable( array( $this->conditions, $options_callback ) ) ) {
			$options = call_user_func( array( $this->conditions, $options_callback ) );

			// Format for React select.
			$data = array();
			foreach ( $options as $value => $label ) {
				$data[] = array(
					'value' => (string) $value,
					'label' => $label,
				);
			}

			return new \WP_REST_Response( $data, 200 );
		}

		return new \WP_REST_Response( array(), 200 );
	}

	/**
	 * REST callback: Get preview URL.
	 *
	 * @since 2.0.0
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_preview_url( $request ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return new \WP_REST_Response( array( 'url' => '' ), 200 );
		}

		$type = $request->get_param( 'type' );
		$url  = '';

		switch ( $type ) {
			case 'single-product':
				$products = wc_get_products( array(
					'limit'  => 1,
					'status' => 'publish',
				) );
				if ( ! empty( $products ) ) {
					$url = get_permalink( $products[0]->get_id() );
				}
				break;

			case 'archive-product':
				$url = wc_get_page_permalink( 'shop' );
				break;

			case 'cart':
			case 'empty-cart':
				$url = wc_get_cart_url();
				break;

			case 'checkout':
				$url = wc_get_checkout_url();
				break;

			case 'my-account':
				$url = wc_get_page_permalink( 'myaccount' );
				break;

			case 'thankyou':
				// Get a recent completed order for preview.
				$orders = wc_get_orders( array(
					'limit'  => 1,
					'status' => array( 'completed', 'processing' ),
				) );
				if ( ! empty( $orders ) ) {
					$url = $orders[0]->get_checkout_order_received_url();
				} else {
					$url = wc_get_checkout_url();
				}
				break;
		}

		return new \WP_REST_Response(
			array(
				'url' => $url,
			),
			200
		);
	}

	/**
	 * Add body class for templates.
	 *
	 * @since 2.0.0
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function add_body_class( $classes ) {
		$template_id = $this->renderer->get_current_template();

		if ( $template_id ) {
			$classes[] = 'mpd-template';
			$classes[] = 'mpd-template-' . $template_id;

			$type = get_post_meta( $template_id, '_mpd_template_type', true );
			if ( $type ) {
				$classes[] = 'mpd-template-type-' . $type;
			}
		}

		return $classes;
	}

	/**
	 * Get manager instance.
	 *
	 * @since 2.0.0
	 *
	 * @return Template_Manager
	 */
	public function get_manager() {
		return $this->manager;
	}

	/**
	 * Get renderer instance.
	 *
	 * @since 2.0.0
	 *
	 * @return Template_Renderer
	 */
	public function get_renderer() {
		return $this->renderer;
	}

	/**
	 * Get conditions instance.
	 *
	 * @since 2.0.0
	 *
	 * @return Template_Conditions
	 */
	public function get_conditions() {
		return $this->conditions;
	}

	/**
	 * Get pre-layout manager instance.
	 *
	 * @since 2.1.0
	 *
	 * @return PreLayouts\PreLayout_Manager
	 */
	public function get_prelayout_manager() {
		return $this->prelayout_manager;
	}
}
