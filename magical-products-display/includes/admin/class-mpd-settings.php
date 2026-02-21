<?php
/**
 * Settings API for Magical Shop Builder.
 *
 * Handles plugin settings registration and management.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Admin;

use MPD\MagicalShopBuilder\Core\Pro;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Settings
 *
 * Manages plugin settings.
 *
 * @since 2.0.0
 */
class Settings {

	/**
	 * Option name for general settings.
	 *
	 * @var string
	 */
	const OPTION_GENERAL = 'mpd_general_settings';

	/**
	 * Option name for single product settings.
	 *
	 * @var string
	 */
	const OPTION_SINGLE_PRODUCT = 'mpd_single_product_settings';

	/**
	 * Option name for cart/checkout settings.
	 *
	 * @var string
	 */
	const OPTION_CART_CHECKOUT = 'mpd_cart_checkout_settings';

	/**
	 * Option name for my account settings.
	 *
	 * @var string
	 */
	const OPTION_MY_ACCOUNT = 'mpd_my_account_settings';

	/**
	 * Option name for performance settings.
	 *
	 * @var string
	 */
	const OPTION_PERFORMANCE = 'mpd_performance_settings';

	/**
	 * Option name for preloader settings.
	 *
	 * @var string
	 */
	const OPTION_PRELOADER = 'mpd_preloader_settings';



	/**
	 * Instance of the class.
	 *
	 * @var Settings|null
	 */
	private static $instance = null;

	/**
	 * Default settings.
	 *
	 * @var array
	 */
	private $defaults = array();

	/**
	 * Get instance.
	 *
	 * @since 2.0.0
	 *
	 * @return Settings
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
		$this->set_defaults();
	}

	/**
	 * Initialize the settings.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Set default settings.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function set_defaults() {
		$this->defaults = array(
			'general'        => array(
				'enable_custom_archive'     => true,
				'lazy_load_images'          => true,
				'add_to_cart_ajax'          => true,
			),
			'single_product' => array(
				'enable_custom_template'    => false,
			),
			'cart_checkout'  => array(
				'enable_custom_cart'        => false,
				'enable_custom_checkout'    => false,
			),
			'my_account'     => array(
				'enable_custom_my_account'  => false,
			),
			'performance'    => array(
				'lazy_load_widgets'         => true,
				'minify_css'                => false,
				'minify_js'                 => false,
				'defer_js'                  => false,
				'cache_templates'           => true,
				'cache_duration'            => 3600,
			),
			'preloader'      => array(
				'enable'           => true,
				'style'            => 'spinner',
				'primary_color'    => '#0073aa',
				'secondary_color'  => '#f3f3f3',
				'background_color' => '#ffffff',
				'text_color'       => '#666666',
				'show_logo'        => false,
				'logo_url'         => '',
				'loading_text'     => '',
				'pages'            => array( 'shop', 'product', 'cart', 'checkout', 'my-account' ),
			),
		);
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function register_settings() {
		// General settings.
		register_setting(
			'mpd_settings',
			self::OPTION_GENERAL,
			array(
				'type'              => 'object',
				'sanitize_callback' => array( $this, 'sanitize_general_settings' ),
				'default'           => $this->defaults['general'],
				'show_in_rest'      => array(
					'schema' => array(
						'type'       => 'object',
						'properties' => $this->get_general_schema(),
					),
				),
			)
		);

		// Single product settings.
		register_setting(
			'mpd_settings',
			self::OPTION_SINGLE_PRODUCT,
			array(
				'type'              => 'object',
				'sanitize_callback' => array( $this, 'sanitize_single_product_settings' ),
				'default'           => $this->defaults['single_product'],
				'show_in_rest'      => array(
					'schema' => array(
						'type'       => 'object',
						'properties' => $this->get_single_product_schema(),
					),
				),
			)
		);

		// Cart/checkout settings.
		register_setting(
			'mpd_settings',
			self::OPTION_CART_CHECKOUT,
			array(
				'type'              => 'object',
				'sanitize_callback' => array( $this, 'sanitize_cart_checkout_settings' ),
				'default'           => $this->defaults['cart_checkout'],
				'show_in_rest'      => array(
					'schema' => array(
						'type'       => 'object',
						'properties' => $this->get_cart_checkout_schema(),
					),
				),
			)
		);

		// My account settings.
		register_setting(
			'mpd_settings',
			self::OPTION_MY_ACCOUNT,
			array(
				'type'              => 'object',
				'sanitize_callback' => array( $this, 'sanitize_my_account_settings' ),
				'default'           => $this->defaults['my_account'],
				'show_in_rest'      => array(
					'schema' => array(
						'type'       => 'object',
						'properties' => $this->get_my_account_schema(),
					),
				),
			)
		);

		// Performance settings.
		register_setting(
			'mpd_settings',
			self::OPTION_PERFORMANCE,
			array(
				'type'              => 'object',
				'sanitize_callback' => array( $this, 'sanitize_performance_settings' ),
				'default'           => $this->defaults['performance'],
				'show_in_rest'      => array(
					'schema' => array(
						'type'       => 'object',
						'properties' => $this->get_performance_schema(),
					),
				),
			)
		);

		// Preloader settings.
		register_setting(
			'mpd_settings',
			self::OPTION_PRELOADER,
			array(
				'type'              => 'object',
				'sanitize_callback' => array( $this, 'sanitize_preloader_settings' ),
				'default'           => $this->defaults['preloader'],
				'show_in_rest'      => array(
					'schema' => array(
						'type'       => 'object',
						'properties' => $this->get_preloader_schema(),
					),
				),
			)
		);

	}

	/**
	 * Get all settings.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_all_settings() {
		return array(
			'general'        => $this->get_general_settings(),
			'single_product' => $this->get_single_product_settings(),
			'cart_checkout'  => $this->get_cart_checkout_settings(),
			'my_account'     => $this->get_my_account_settings(),
			'performance'    => $this->get_performance_settings(),
			'preloader'      => $this->get_preloader_settings(),
		);
	}

	/**
	 * Get general settings.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_general_settings() {
		$settings = get_option( self::OPTION_GENERAL, array() );
		return wp_parse_args( $settings, $this->defaults['general'] );
	}

	/**
	 * Get single product settings.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_single_product_settings() {
		$settings = get_option( self::OPTION_SINGLE_PRODUCT, array() );
		return wp_parse_args( $settings, $this->defaults['single_product'] );
	}

	/**
	 * Get cart/checkout settings.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_cart_checkout_settings() {
		$settings = get_option( self::OPTION_CART_CHECKOUT, array() );
		return wp_parse_args( $settings, $this->defaults['cart_checkout'] );
	}

	/**
	 * Get my account settings.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_my_account_settings() {
		$settings = get_option( self::OPTION_MY_ACCOUNT, array() );
		return wp_parse_args( $settings, $this->defaults['my_account'] );
	}

	/**
	 * Get performance settings.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_performance_settings() {
		$settings = get_option( self::OPTION_PERFORMANCE, array() );
		return wp_parse_args( $settings, $this->defaults['performance'] );
	}

	/**
	 * Get preloader settings.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_preloader_settings() {
		$settings = get_option( self::OPTION_PRELOADER, array() );
		return wp_parse_args( $settings, $this->defaults['preloader'] );
	}

	/**
	 * Update settings.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Settings data.
	 * @return bool|\WP_Error
	 */
	public function update_settings( $settings ) {
		// Save each group independently — do NOT chain with && to avoid
		// short-circuit evaluation skipping later update_option() calls when
		// an earlier group is unchanged (update_option returns false).

		if ( isset( $settings['general'] ) ) {
			$sanitized = $this->sanitize_general_settings( $settings['general'] );
			update_option( self::OPTION_GENERAL, $sanitized );
		}

		if ( isset( $settings['single_product'] ) ) {
			$sanitized = $this->sanitize_single_product_settings( $settings['single_product'] );
			update_option( self::OPTION_SINGLE_PRODUCT, $sanitized );
		}

		if ( isset( $settings['cart_checkout'] ) ) {
			$sanitized = $this->sanitize_cart_checkout_settings( $settings['cart_checkout'] );
			update_option( self::OPTION_CART_CHECKOUT, $sanitized );
		}

		if ( isset( $settings['my_account'] ) ) {
			$sanitized = $this->sanitize_my_account_settings( $settings['my_account'] );
			update_option( self::OPTION_MY_ACCOUNT, $sanitized );
		}

		if ( isset( $settings['performance'] ) ) {
			$sanitized = $this->sanitize_performance_settings( $settings['performance'] );
			update_option( self::OPTION_PERFORMANCE, $sanitized );
		}

		if ( isset( $settings['preloader'] ) ) {
			$sanitized = $this->sanitize_preloader_settings( $settings['preloader'] );
			update_option( self::OPTION_PRELOADER, $sanitized );
		}

		/**
		 * Fires after settings are updated.
		 *
		 * @since 2.0.0
		 *
		 * @param array $settings Updated settings.
		 */
		do_action( 'mpd_settings_updated', $settings );

		return true;
	}

	/**
	 * Sanitize general settings.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Settings to sanitize.
	 * @return array
	 */
	public function sanitize_general_settings( $settings ) {
		$sanitized = array();

		$sanitized['enable_custom_archive']     = ! empty( $settings['enable_custom_archive'] );
		$sanitized['lazy_load_images']          = ! empty( $settings['lazy_load_images'] );
		$sanitized['add_to_cart_ajax']          = ! empty( $settings['add_to_cart_ajax'] );

		return $sanitized;
	}

	/**
	 * Sanitize single product settings.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Settings to sanitize.
	 * @return array
	 */
	public function sanitize_single_product_settings( $settings ) {
		$sanitized = array();

		$sanitized['enable_custom_template']    = ! empty( $settings['enable_custom_template'] );

		return $sanitized;
	}

	/**
	 * Sanitize cart/checkout settings.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Settings to sanitize.
	 * @return array
	 */
	public function sanitize_cart_checkout_settings( $settings ) {
		$sanitized = array();

		$sanitized['enable_custom_cart']     = ! empty( $settings['enable_custom_cart'] );
		$sanitized['enable_custom_checkout'] = ! empty( $settings['enable_custom_checkout'] );

		return $sanitized;
	}

	/**
	 * Sanitize my account settings.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Settings to sanitize.
	 * @return array
	 */
	public function sanitize_my_account_settings( $settings ) {
		$sanitized = array();

		$sanitized['enable_custom_my_account'] = ! empty( $settings['enable_custom_my_account'] );

		return $sanitized;
	}

	/**
	 * Sanitize performance settings.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Settings to sanitize.
	 * @return array
	 */
	public function sanitize_performance_settings( $settings ) {
		$sanitized = array();
		$defaults  = $this->defaults['performance'];

		$sanitized['lazy_load_widgets'] = ! empty( $settings['lazy_load_widgets'] );
		$sanitized['minify_css']        = ! empty( $settings['minify_css'] );
		$sanitized['minify_js']         = ! empty( $settings['minify_js'] );
		$sanitized['defer_js']          = ! empty( $settings['defer_js'] );
		$sanitized['cache_templates']   = ! empty( $settings['cache_templates'] );
		$sanitized['cache_duration']    = isset( $settings['cache_duration'] ) 
			? absint( $settings['cache_duration'] ) 
			: $defaults['cache_duration'];

		return $sanitized;
	}

	/**
	 * Sanitize preloader settings.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Settings to sanitize.
	 * @return array
	 */
	public function sanitize_preloader_settings( $settings ) {
		$sanitized = array();
		$defaults  = $this->defaults['preloader'];

		// Free preloader styles (first 4 only).
		$free_styles = array( 'spinner', 'double-bounce', 'pulse', 'three-dots' );
		$is_pro      = function_exists( 'mpd_is_pro_active' ) && mpd_is_pro_active();

		$sanitized['enable']           = ! empty( $settings['enable'] );
		$sanitized['style']            = isset( $settings['style'] ) 
			? sanitize_text_field( $settings['style'] ) 
			: $defaults['style'];

		// Enforce free style restriction for non-pro users.
		if ( ! $is_pro && ! in_array( $sanitized['style'], $free_styles, true ) ) {
			$sanitized['style'] = $defaults['style'];
		}

		$sanitized['primary_color']    = isset( $settings['primary_color'] ) 
			? sanitize_hex_color( $settings['primary_color'] ) 
			: $defaults['primary_color'];
		$sanitized['secondary_color']  = isset( $settings['secondary_color'] ) 
			? sanitize_hex_color( $settings['secondary_color'] ) 
			: $defaults['secondary_color'];
		$sanitized['background_color'] = isset( $settings['background_color'] ) 
			? sanitize_hex_color( $settings['background_color'] ) 
			: $defaults['background_color'];
		$sanitized['text_color']       = isset( $settings['text_color'] ) 
			? sanitize_hex_color( $settings['text_color'] ) 
			: $defaults['text_color'];

		// Logo & Text are pro-only features.
		if ( $is_pro ) {
			$sanitized['show_logo']    = ! empty( $settings['show_logo'] );
			$sanitized['logo_url']     = isset( $settings['logo_url'] ) 
				? esc_url_raw( $settings['logo_url'] ) 
				: '';
			$sanitized['loading_text'] = isset( $settings['loading_text'] ) 
				? sanitize_text_field( $settings['loading_text'] ) 
				: '';
		} else {
			$sanitized['show_logo']    = $defaults['show_logo'];
			$sanitized['logo_url']     = $defaults['logo_url'];
			$sanitized['loading_text'] = $defaults['loading_text'];
		}
		
		// Sanitize pages array.
		$sanitized['pages'] = array();
		$valid_pages = array( 'all', 'shop', 'product', 'cart', 'checkout', 'my-account', 'thank-you' );
		if ( ! empty( $settings['pages'] ) && is_array( $settings['pages'] ) ) {
			foreach ( $settings['pages'] as $page ) {
				if ( in_array( $page, $valid_pages, true ) ) {
					$sanitized['pages'][] = $page;
				}
			}
		}

		return $sanitized;
	}

	/**
	 * Get general settings schema.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_general_schema() {
		return array(
			'enable_custom_archive'    => array( 'type' => 'boolean' ),
			'lazy_load_images'         => array( 'type' => 'boolean' ),
			'add_to_cart_ajax'         => array( 'type' => 'boolean' ),
		);
	}

	/**
	 * Get single product settings schema.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_single_product_schema() {
		return array(
			'enable_custom_template'    => array( 'type' => 'boolean' ),
		);
	}

	/**
	 * Get cart/checkout settings schema.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_cart_checkout_schema() {
		return array(
			'enable_custom_cart'     => array( 'type' => 'boolean' ),
			'enable_custom_checkout' => array( 'type' => 'boolean' ),
		);
	}

	/**
	 * Get my account settings schema.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_my_account_schema() {
		return array(
			'enable_custom_my_account' => array( 'type' => 'boolean' ),
		);
	}

	/**
	 * Get performance settings schema.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_performance_schema() {
		return array(
			'lazy_load_widgets' => array( 'type' => 'boolean' ),
			'minify_css'        => array( 'type' => 'boolean' ),
			'minify_js'         => array( 'type' => 'boolean' ),
			'defer_js'          => array( 'type' => 'boolean' ),
			'cache_templates'   => array( 'type' => 'boolean' ),
			'cache_duration'    => array( 'type' => 'integer' ),
		);
	}

	/**
	 * Get preloader settings schema.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_preloader_schema() {
		return array(
			'enable'           => array( 'type' => 'boolean' ),
			'style'            => array( 'type' => 'string' ),
			'primary_color'    => array( 'type' => 'string' ),
			'secondary_color'  => array( 'type' => 'string' ),
			'background_color' => array( 'type' => 'string' ),
			'text_color'       => array( 'type' => 'string' ),
			'show_logo'        => array( 'type' => 'boolean' ),
			'logo_url'         => array( 'type' => 'string' ),
			'loading_text'     => array( 'type' => 'string' ),
			'pages'            => array(
				'type'  => 'array',
				'items' => array( 'type' => 'string' ),
			),
		);
	}

	/**
	 * Get a single setting value.
	 *
	 * @since 2.0.0
	 *
	 * @param string $group Setting group (general, single_product, etc.).
	 * @param string $key   Setting key.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	public function get( $group, $key, $default = null ) {
		$method = "get_{$group}_settings";
		
		if ( ! method_exists( $this, $method ) ) {
			return $default;
		}

		$settings = $this->$method();
		
		return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
	}
}
