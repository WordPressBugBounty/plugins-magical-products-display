<?php
/**
 * Plugin Name:       Magical Shop Builder
 * Plugin URI:        https://wpthemespace.com/magical-shop-builder
 * Description:       Complete WooCommerce Shop Builder with Elementor - Build custom product pages, cart, checkout, my-account, Thank you page and more.
 * Version:           2.0.0
 * Author:            Noor alam
 * Author URI:        https://wpthemespace.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       magical-products-display
 * Domain Path:       /languages
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * WC requires at least: 8.0
 * WC tested up to:   9.5
 *
 * @link              https://wpthemespace.com
 * @since             1.0.0
 * @package           Magical_Shop_Builder
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Magical Shop Builder Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Magical_Shop_Builder {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '2.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.15.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.4';

	/**
	 * Minimum WordPress Version
	 *
	 * @since 2.0.0
	 *
	 * @var string Minimum WordPress version required.
	 */
	const MINIMUM_WP_VERSION = '6.0';

	/**
	 * Minimum WooCommerce Version
	 *
	 * @since 2.0.0
	 *
	 * @var string Minimum WooCommerce version required.
	 */
	const MINIMUM_WC_VERSION = '8.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Magical_Shop_Builder The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Magical_Shop_Builder An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->define_constants();
		$this->load_autoloader();
		
		add_action( 'init', array( $this, 'i18n' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		// Register custom cron schedules (must be available on every load for WP-Cron to work).
		add_filter( 'cron_schedules', array( '\MPD\MagicalShopBuilder\Core\Activator', 'add_cron_schedules' ) );

		// Run upgrade check early — BEFORE dependency checks in init().
		// This ensures DB tables, capabilities, and cron events are created
		// even when an existing user updates the plugin (activation hook
		// does NOT fire on updates, only on first activation).
		add_action( 'admin_init', array( '\MPD\MagicalShopBuilder\Core\Upgrader', 'init' ) );

		// Flush rewrite rules when flagged (deferred from Activator/Upgrader).
		add_action( 'init', array( $this, 'maybe_flush_rewrite_rules' ) );

		// Register activation/deactivation hooks.
		register_activation_hook( MAGICAL_PRODUCTS_DISPLAY_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( MAGICAL_PRODUCTS_DISPLAY_FILE, array( $this, 'deactivate' ) );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n() {
		// Since WordPress 4.6, translations are automatically loaded from
		// wp-content/languages/plugins/magical-products-display-{locale}.mo
		// so load_plugin_textdomain() is no longer needed.
	}

	/**
	 * Define plugin constants.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function define_constants() {
		define( 'MAGICAL_PRODUCTS_DISPLAY_VERSION', self::VERSION );
		define( 'MAGICAL_PRODUCTS_DISPLAY_FILE', __FILE__ );
		define( 'MAGICAL_PRODUCTS_DISPLAY_DIR', plugin_dir_path( __FILE__ ) );
		define( 'MAGICAL_PRODUCTS_DISPLAY_URL', plugins_url( '', MAGICAL_PRODUCTS_DISPLAY_FILE ) );
		define( 'MAGICAL_PRODUCTS_DISPLAY_ASSETS', MAGICAL_PRODUCTS_DISPLAY_URL . '/assets/' );
		define( 'MAGICAL_PRODUCTS_DISPLAY_BASENAME', plugin_basename( __FILE__ ) );
	}

	/**
	 * Load the autoloader.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 */
	private function load_autoloader() {
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/class-mpd-loader.php';
		\MPD\MagicalShopBuilder\Loader::init( MAGICAL_PRODUCTS_DISPLAY_DIR );
	}

	/**
	 * Plugin activation.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function activate() {
		\MPD\MagicalShopBuilder\Core\Activator::activate();
	}

	/**
	 * Plugin deactivation.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function deactivate() {
		\MPD\MagicalShopBuilder\Core\Deactivator::deactivate();
	}

	/**
	 * Flush rewrite rules if flagged by Activator or Upgrader.
	 *
	 * Hooked to `init` so it runs at the correct point in the
	 * WordPress lifecycle (after post types are registered).
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function maybe_flush_rewrite_rules() {
		if ( get_option( 'mpd_flush_rewrite_rules' ) ) {
			flush_rewrite_rules();
			delete_option( 'mpd_flush_rewrite_rules' );
		}
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init() {
		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check if WooCommerce installed and activated.
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_woo_plugin' ) );
			return;
		}

		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Check for required WooCommerce version.
		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, self::MINIMUM_WC_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_wc_version' ) );
			return;
		}

		// Check if old pro plugin (magical-products-display-pro) exists.
		$old_pro_plugin_file = WP_PLUGIN_DIR . '/magical-products-display-pro/magical-products-display-pro.php';
		if ( file_exists( $old_pro_plugin_file ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_old_pro_plugin' ) );
		}

		// Note: Upgrade routine now runs via Upgrader::init() on admin_init,
		// BEFORE these dependency checks, ensuring DB tables/capabilities
		// are always created even when Elementor or WooCommerce aren't loaded.

		// Set activation options.
		$is_plugin_activated = get_option( 'mpd_plugin_activated' );
		if ( 'yes' !== $is_plugin_activated ) {
			update_option( 'mpd_plugin_activated', 'yes' );
		}

		$mpd_install_date = get_option( 'mpd_install_date' );
		if ( empty( $mpd_install_date ) ) {
			update_option( 'mpd_install_date', gmdate( 'Y-m-d H:i:s' ) );
		}

		// Check pro version - uses existing option names for backward compatibility.
		$pro_plugin_slug   = 'magical-shop-builder-pro/magical-shop-builder-pro.php';
		$current_pro_state = get_option( 'mgppro_is_active', 'no' );
		$new_pro_state     = 'no';

		if ( ( in_array( $pro_plugin_slug, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) && 'yes' === get_option( 'mgppro_has_valid_lic' ) ) || ( 'yes' === get_option( 'space_has_pro' ) ) ) {
			$new_pro_state = 'yes';
		}

		if ( $current_pro_state !== $new_pro_state ) {
			update_option( 'mgppro_is_active', $new_pro_state );
		}

		// Load new function files.
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/functions/helper-functions.php';
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/functions/template-functions.php';
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/functions/woocommerce-functions.php';

		// Load traits for widgets.
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/traits/trait-mpd-pro-lock.php';
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/traits/trait-mpd-product-query.php';
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/traits/trait-mpd-wc-helpers.php';
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/traits/trait-mpd-action-buttons.php';

		// Load all style & scripts.
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/assets-managment.php';

		// Load legacy function file (for backward compatibility).
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/functions.php';

		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/helplink.php';

		// Load AJAX handlers early.
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/widgets/ajax-search/ajax-search-handler.php';
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/ajax/products-tab-ajax.php';
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/ajax/products-archive-ajax.php';

		if ( 'yes' !== get_option( 'mgppro_is_active' ) ) {
			// Load admin info.
			require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/admin-info.php';
			require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/pro-widgets.php';
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'admin_adminpro_link' ) );
		}

		// Initialize admin dashboard (React-based).
		$this->init_admin();

		// Initialize Template Builder System (Phase 3).
		$this->init_template_builder();

		// Initialize Preloader.
		$this->init_preloader();

		// Initialize Performance optimizations.
		$this->init_performance();

		// Initialize Elementor integration (widgets & categories).
		$this->init_elementor();
	}
	

	/**
	 * Initialize Elementor integration.
	 *
	 * Loads the Elementor handler class which manages widget categories
	 * and widget registration.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 */
	private function init_elementor() {
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/class-mpd-elementor.php';

		$elementor = MPD_Elementor::instance();
		$elementor->init();
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['activate'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$_GET['activate'] = sanitize_text_field( wp_unslash( $_GET['activate'] ) );
			unset( $_GET['activate'] );
		}

		if ( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) {
			$magial_eactive_url = wp_nonce_url( 'plugins.php?action=activate&plugin=elementor/elementor.php&plugin_status=all&paged=1', 'activate-plugin_elementor/elementor.php' );
			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Elementor installation link */
				esc_html__( '%1$s requires %2$s plugin, which is currently NOT RUNNING  %3$s', 'magical-products-display' ),
				'<strong>' . esc_html__( 'Magical Shop Builder', 'magical-products-display' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'magical-products-display' ) . '</strong>',
				'<a class="button button-primary" style="margin-left:20px" href="' . esc_url( $magial_eactive_url ) . '">' . esc_html__( 'Activate Elementor', 'magical-products-display' ) . '</a>'
			);
		} else {
			$magial_einstall_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Elementor installation link */
				esc_html__( '%1$s requires %2$s plugin, which is currently NOT RUNNING  %3$s', 'magical-products-display' ),
				'<strong>' . esc_html__( 'Magical Shop Builder', 'magical-products-display' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'magical-products-display' ) . '</strong>',
				'<a class="button button-primary" style="margin-left:20px" href="' . esc_url( $magial_einstall_url ) . '">' . esc_html__( 'Install Elementor', 'magical-products-display' ) . '</a>'
			);
		}

		printf( '<div class="notice notice-warning is-dismissible"><p style="padding: 13px 0">%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have WooCommerce installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_woo_plugin() {
		if ( file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
			$magial_eactive_url = wp_nonce_url( 'plugins.php?action=activate&plugin=woocommerce/woocommerce.php&plugin_status=all&paged=1', 'activate-plugin_woocommerce/woocommerce.php' );
			$message = sprintf(
				/* translators: 1: Plugin name 2: WooCommerce 3: WooCommerce installation link */
				esc_html__( '%1$s requires %2$s plugin, which is currently NOT RUNNING  %3$s', 'magical-products-display' ),
				'<strong>' . esc_html__( 'Magical Shop Builder', 'magical-products-display' ) . '</strong>',
				'<strong>' . esc_html__( 'WooCommerce', 'magical-products-display' ) . '</strong>',
				'<a class="button button-primary" style="margin-left:20px" href="' . esc_url( $magial_eactive_url ) . '">' . esc_html__( 'Activate WooCommerce', 'magical-products-display' ) . '</a>'
			);
		} else {
			$magial_einstall_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' );
			$message = sprintf(
				/* translators: 1: Plugin name 2: WooCommerce 3: WooCommerce installation link */
				esc_html__( '%1$s requires %2$s plugin, which is currently NOT RUNNING  %3$s', 'magical-products-display' ),
				'<strong>' . esc_html__( 'Magical Shop Builder', 'magical-products-display' ) . '</strong>',
				'<strong>' . esc_html__( 'WooCommerce', 'magical-products-display' ) . '</strong>',
				'<a class="button button-primary" style="margin-left:20px" href="' . esc_url( $magial_einstall_url ) . '">' . esc_html__( 'Install WooCommerce', 'magical-products-display' ) . '</a>'
			);
		}

		printf(
			'<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>',
			wp_kses_post( $message )
		);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['activate'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$_GET['activate'] = sanitize_text_field( wp_unslash( $_GET['activate'] ) );
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'magical-products-display' ),
			'<strong>' . esc_html__( 'Magical Shop Builder', 'magical-products-display' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'magical-products-display' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['activate'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$_GET['activate'] = sanitize_text_field( wp_unslash( $_GET['activate'] ) );
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'magical-products-display' ),
			'<strong>' . esc_html__( 'Magical Shop Builder', 'magical-products-display' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'magical-products-display' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required WooCommerce version.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_wc_version() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['activate'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$_GET['activate'] = sanitize_text_field( wp_unslash( $_GET['activate'] ) );
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: WooCommerce 3: Required WooCommerce version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'magical-products-display' ),
			'<strong>' . esc_html__( 'Magical Shop Builder', 'magical-products-display' ) . '</strong>',
			'<strong>' . esc_html__( 'WooCommerce', 'magical-products-display' ) . '</strong>',
			self::MINIMUM_WC_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}


	/**
	 * Admin notice
	 *
	 * Warning when the old pro plugin (magical-products-display-pro) is still installed.
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 */
	public function admin_notice_old_pro_plugin() {
		$message = sprintf(
			'<strong>%1$s</strong><br>%2$s<br><a href="%3$s" target="_blank" class="button button-primary" style="margin-top:8px;">%4$s</a>',
			esc_html__( 'Action Required: Old Pro Plugin Detected!', 'magical-products-display' ),
			esc_html__( 'The old "Magical Products Display Pro" plugin is no longer supported. Please deactivate and delete the old pro version, then go to wpthemespace.com/my-account page and download the latest "Magical Shop Builder Pro" version. Upload and activate it to enjoy huge new features. Your website data will remain unchanged.', 'magical-products-display' ),
			esc_url( 'https://wpthemespace.com/my-account/downloads/' ),
			esc_html__( 'Go to My Account & Download New Pro', 'magical-products-display' )
		);

		printf( '<div class="notice notice-error"><p style="padding: 13px 0">%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Add pro upgrade link to plugins page.
	 *
	 * @since 1.0.0
	 *
	 * @param array $links Plugin action links.
	 * @return array Modified links.
	 */
	public function admin_adminpro_link( $links ) {
		$newlink = sprintf(
			"<a target='_blank' href='%s'><span style='color:red;font-weight:bold'>%s</span></a>",
			esc_url( 'https://wpthemespace.com/product/magical-shop-builder/#pricing' ),
			esc_html__( 'Upgrade Now', 'magical-products-display' )
		);

		if ( 'yes' !== get_option( 'mgppro_is_active' ) ) {
			$links[] = $newlink;
		}

		return $links;
	}

	/**
	 * Initialize admin dashboard.
	 *
	 * Loads the React-based admin dashboard with REST API endpoints
	 * and settings management.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 */
	private function init_admin() {
		// Initialize REST API (needs to run on frontend for AJAX calls).
		$rest_api = new \MPD\MagicalShopBuilder\Admin\REST_API();
		$rest_api->init();

		// Only load admin UI on admin pages.
		if ( ! is_admin() ) {
			return;
		}

		// Initialize Settings API.
		$settings = new \MPD\MagicalShopBuilder\Admin\Settings();
		$settings->init();

		// Initialize Admin pages.
		$admin = new \MPD\MagicalShopBuilder\Admin\Admin();
		$admin->init();

		// Initialize Product Video Metabox (Pro feature).
		$product_video_metabox = \MPD\MagicalShopBuilder\Admin\Product_Video_Metabox::instance();
		$product_video_metabox->init();
	}

	/**
	 * Initialize Template Builder System.
	 *
	 * Sets up the template builder with hybrid support for
	 * Elementor Free (custom templates) and Elementor Pro (Theme Builder integration).
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 */
	private function init_template_builder() {
		// Check if templates are enabled in settings.
		// Default to 'yes' if setting doesn't exist (for backward compatibility).
		$settings = get_option( 'mpd_general_settings', array() );
		
		// Ensure enable_templates setting exists for existing installations.
		if ( ! isset( $settings['enable_templates'] ) ) {
			$settings['enable_templates'] = 'yes';
			update_option( 'mpd_general_settings', $settings );
		}

		$enabled = $settings['enable_templates'];

		if ( 'yes' !== $enabled ) {
			return;
		}

		// Initialize the Template Builder.
		$template_builder = \MPD\MagicalShopBuilder\Templates\Template_Builder::instance();
		$template_builder->init();
	}

	/**
	 * Initialize the preloader.
	 *
	 * Loads the preloader for frontend pages to prevent FOUC.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 */
	private function init_preloader() {
		// Only run on frontend.
		if ( is_admin() ) {
			return;
		}

		// Load and initialize preloader.
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/frontend/class-mpd-preloader.php';
		
		$preloader = \MPD\MagicalShopBuilder\Frontend\Preloader::instance();
		$preloader->init();
	}

	/**
	 * Initialize performance optimizations.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 */
	private function init_performance() {
		// Only run on frontend.
		if ( is_admin() ) {
			return;
		}

		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/frontend/class-mpd-performance.php';

		$performance = new \MPD\MagicalShopBuilder\Frontend\Performance();
		$performance->init();
	}
}

/**
 * Initialize the plugin.
 *
 * @since 2.0.0
 *
 * @return Magical_Shop_Builder
 */
if ( ! function_exists( 'magical_shop_builder' ) ) {
function magical_shop_builder() {
	return Magical_Shop_Builder::instance();
}
}

// Initialize the plugin.
magical_shop_builder();

// Backward compatibility: Keep old class name accessible.
class_alias( 'Magical_Shop_Builder', 'magicalProductsDisplay' );
