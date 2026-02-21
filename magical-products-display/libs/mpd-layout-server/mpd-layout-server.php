<?php
/**
 * Plugin Name: MPD Layout Server
 * Plugin URI: https://developer.developer
 * Description: Server-side plugin for managing and distributing Magical Products Display page layouts via REST API.
 * Version: 1.0.0
 * Author: developer Developer
 * Author URI: https://developer.developer
 * Text Domain: mpd-layout-server
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package MPD_Layout_Server
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants.
define( 'MPD_LAYOUT_SERVER_VERSION', '1.0.0' );
define( 'MPD_LAYOUT_SERVER_FILE', __FILE__ );
define( 'MPD_LAYOUT_SERVER_DIR', plugin_dir_path( __FILE__ ) );
define( 'MPD_LAYOUT_SERVER_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main plugin class.
 *
 * @since 1.0.0
 */
final class MPD_Layout_Server {

	/**
	 * Single instance of the class.
	 *
	 * @var MPD_Layout_Server|null
	 */
	private static $instance = null;

	/**
	 * Get single instance.
	 *
	 * @since 1.0.0
	 *
	 * @return MPD_Layout_Server
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
		$this->load_dependencies();
		$this->init_hooks();
	}

	/**
	 * Load required files.
	 *
	 * @since 1.0.0
	 */
	private function load_dependencies() {
		require_once MPD_LAYOUT_SERVER_DIR . 'includes/class-mpd-layout-post-type.php';
		require_once MPD_LAYOUT_SERVER_DIR . 'includes/class-mpd-layout-metabox.php';
		require_once MPD_LAYOUT_SERVER_DIR . 'includes/class-mpd-layout-rest-api.php';
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		// Initialize components early on init.
		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		// Activation/deactivation hooks.
		register_activation_hook( MPD_LAYOUT_SERVER_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( MPD_LAYOUT_SERVER_FILE, array( $this, 'deactivate' ) );
	}

	/**
	 * Initialize plugin components.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		// Register post type directly here.
		MPD_Layout_Post_Type::instance()->register_post_type();
		MPD_Layout_Post_Type::instance()->register_taxonomy();

		// Register metaboxes.
		MPD_Layout_Metabox::instance();

		// Register REST API.
		MPD_Layout_REST_API::instance();
	}

	/**
	 * Load plugin textdomain.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'magical-products-display',
			false,
			dirname( plugin_basename( MPD_LAYOUT_SERVER_FILE ) ) . '/languages'
		);
	}

	/**
	 * Plugin activation.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		// Register post type on activation for flush.
		MPD_Layout_Post_Type::instance()->register_post_type();

		// Flush rewrite rules.
		flush_rewrite_rules();

		// Set activation flag.
		update_option( 'mpd_layout_server_activated', true );
	}

	/**
	 * Plugin deactivation.
	 *
	 * @since 1.0.0
	 */
	public function deactivate() {
		// Flush rewrite rules.
		flush_rewrite_rules();

		// Remove activation flag.
		delete_option( 'mpd_layout_server_activated' );
	}
}

/**
 * Get plugin instance.
 *
 * @since 1.0.0
 *
 * @return MPD_Layout_Server
 */
if ( ! function_exists( 'mpd_layout_server' ) ) {
function mpd_layout_server() {
	return MPD_Layout_Server::instance();
}
}

// Initialize plugin.
mpd_layout_server();
