<?php
/**
 * Admin orchestrator for Magical Shop Builder.
 *
 * Handles admin menu registration, page rendering, and asset enqueuing
 * for the React-based admin dashboard.
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
 * Class Admin
 *
 * Main admin class for the plugin.
 *
 * @since 2.0.0
 */
class Admin {

	/**
	 * Admin page slug.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'magical-shop-builder';

	/**
	 * Minimum capability required.
	 *
	 * @var string
	 */
	const CAPABILITY = 'manage_options';

	/**
	 * Instance of the class.
	 *
	 * @var Admin|null
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @since 2.0.0
	 *
	 * @return Admin
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
	 * Initialize the admin.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ) );
	}

	/**
	 * Register admin menu.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function register_admin_menu() {
		// Main menu.
		add_menu_page(
			__( 'Magical Shop Builder', 'magical-products-display' ),
			__( 'Magical Shop Builder', 'magical-products-display' ),
			self::CAPABILITY,
			self::PAGE_SLUG,
			array( $this, 'render_admin_page' ),
			$this->get_menu_icon(),
			54
		);

		// Dashboard submenu (same as parent).
		add_submenu_page(
			self::PAGE_SLUG,
			__( 'Dashboard', 'magical-products-display' ),
			__( 'Dashboard', 'magical-products-display' ),
			self::CAPABILITY,
			self::PAGE_SLUG,
			array( $this, 'render_admin_page' )
		);

		// Templates submenu.
		add_submenu_page(
			self::PAGE_SLUG,
			__( 'Templates', 'magical-products-display' ),
			__( 'Templates', 'magical-products-display' ),
			self::CAPABILITY,
			self::PAGE_SLUG . '#/templates',
			array( $this, 'render_admin_page' )
		);

		// Settings submenu.
		add_submenu_page(
			self::PAGE_SLUG,
			__( 'Settings', 'magical-products-display' ),
			__( 'Settings', 'magical-products-display' ),
			self::CAPABILITY,
			self::PAGE_SLUG . '#/settings',
			array( $this, 'render_admin_page' )
		);

		// Widgets submenu.
		add_submenu_page(
			self::PAGE_SLUG,
			__( 'Widgets', 'magical-products-display' ),
			__( 'Widgets', 'magical-products-display' ),
			self::CAPABILITY,
			self::PAGE_SLUG . '#/widgets',
			array( $this, 'render_admin_page' )
		);

		// Upgrade to Pro submenu (only show when pro plugin is not active).
		if ( ! Pro::is_active() ) {
			add_submenu_page(
				self::PAGE_SLUG,
				__( 'Upgrade to Pro', 'magical-products-display' ),
				'<span style="color:#f0c000;">' . __( 'Upgrade to Pro', 'magical-products-display' ) . '</span>',
				self::CAPABILITY,
				self::PAGE_SLUG . '#/pro',
				array( $this, 'render_admin_page' )
			);
		}
	}

	/**
	 * Render admin page.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function render_admin_page() {
		?>
		<div id="mpd-admin-root" class="mpd-admin-wrap">
			<div class="mpd-admin-loading">
				<div class="mpd-admin-loading-spinner"></div>
				<p><?php esc_html_e( 'Loading Magical Shop Builder...', 'magical-products-display' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @since 2.0.0
	 *
	 * @param string $hook_suffix The current admin page hook suffix.
	 * @return void
	 */
	public function enqueue_admin_assets( $hook_suffix ) {
		// Only load on our admin pages.
		if ( ! $this->is_admin_page( $hook_suffix ) ) {
			return;
		}

		// Enqueue WordPress media library for logo uploads.
		wp_enqueue_media();

		$asset_file = MAGICAL_PRODUCTS_DISPLAY_DIR . 'build/admin/index.asset.php';
		
		// Check if build exists.
		if ( ! file_exists( $asset_file ) ) {
			$this->show_build_notice();
			return;
		}

		$asset = include $asset_file;

		// Enqueue main admin script.
		wp_enqueue_script(
			'mpd-admin',
			MAGICAL_PRODUCTS_DISPLAY_URL . '/build/admin/index.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		// Enqueue admin styles.
		wp_enqueue_style(
			'mpd-admin',
			MAGICAL_PRODUCTS_DISPLAY_URL . '/build/admin/index.css',
			array( 'wp-components' ),
			$asset['version']
		);

		// Localize script with necessary data.
		wp_localize_script(
			'mpd-admin',
			'mpdAdmin',
			$this->get_localized_data()
		);

		// Set script translations.
		wp_set_script_translations(
			'mpd-admin',
			'magical-products-display',
			MAGICAL_PRODUCTS_DISPLAY_DIR . 'languages'
		);
	}

	/**
	 * Check if current page is our admin page.
	 *
	 * @since 2.0.0
	 *
	 * @param string $hook_suffix The current admin page hook suffix.
	 * @return bool
	 */
	private function is_admin_page( $hook_suffix ) {
		return strpos( $hook_suffix, self::PAGE_SLUG ) !== false;
	}

	/**
	 * Show notice when build is not available.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function show_build_notice() {
		add_action( 'admin_notices', function() {
			?>
			<div class="notice notice-error">
				<p>
					<strong><?php esc_html_e( 'Magical Shop Builder:', 'magical-products-display' ); ?></strong>
					<?php 
					printf(
						/* translators: 1: npm install command, 2: npm run build command */
						esc_html__( 'Admin assets not found. Please run %1$s and %2$s in the plugin directory.', 'magical-products-display' ),
						'<code>npm install</code>',
						'<code>npm run build</code>'
					);
					?>
				</p>
			</div>
			<?php
		} );
	}

	/**
	 * Get localized data for admin script.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_localized_data() {
		return array(
			'apiUrl'       => esc_url_raw( rest_url( 'mpd/v1' ) ),
			'nonce'        => wp_create_nonce( 'wp_rest' ),
			'adminUrl'     => esc_url( admin_url() ),
			'pluginUrl'    => esc_url( MAGICAL_PRODUCTS_DISPLAY_URL ),
			'version'      => MAGICAL_PRODUCTS_DISPLAY_VERSION,
			'isPro'        => Pro::is_active(),
			'proUrl'       => esc_url( 'https://wpthemespace.com/product/magical-shop-builder/#pricing' ),
			'proAdminUrl'  => Pro::is_active() ? esc_url( admin_url( 'admin.php?page=magical-products-display-pro' ) ) : '',
			'supportUrl'   => esc_url( 'https://wordpress.org/support/plugin/magical-products-display/' ),
			'docsUrl'      => esc_url( 'https://mp.wpcolors.net/doc/' ),
			'strings'      => array(
				'dashboard'   => __( 'Dashboard', 'magical-products-display' ),
				'templates'   => __( 'Templates', 'magical-products-display' ),
				'settings'    => __( 'Settings', 'magical-products-display' ),
				'widgets'     => __( 'Widgets', 'magical-products-display' ),
				'proFeatures' => __( 'Upgrade to Pro', 'magical-products-display' ),
				'saveChanges' => __( 'Save Changes', 'magical-products-display' ),
				'saving'      => __( 'Saving...', 'magical-products-display' ),
				'saved'       => __( 'Settings saved!', 'magical-products-display' ),
				'error'       => __( 'An error occurred. Please try again.', 'magical-products-display' ),
			),
		);
	}

	/**
	 * Add body class to admin page.
	 *
	 * @since 2.0.0
	 *
	 * @param string $classes Existing body classes.
	 * @return string Modified body classes.
	 */
	public function add_admin_body_class( $classes ) {
		$screen = get_current_screen();
		
		if ( $screen && strpos( $screen->id, self::PAGE_SLUG ) !== false ) {
			$classes .= ' mpd-admin-page';
			
			if ( Pro::is_active() ) {
				$classes .= ' mpd-pro-active';
			}
		}
		
		return $classes;
	}

	/**
	 * Get menu icon SVG.
	 *
	 * @since 2.0.0
	 *
	 * @return string Base64 encoded SVG icon.
	 */
	private function get_menu_icon() {
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>';
		
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Used for admin menu SVG icon, standard WP practice.
		return 'data:image/svg+xml;base64,' . base64_encode( $svg );
	}
}
