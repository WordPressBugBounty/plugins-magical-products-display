<?php
/**
 * AI Marketing Expert Plugin Notice & Timed Activation
 * Universal library - usable from any theme or plugin.
 * Singleton + class_exists guard prevents duplicate declaration.
 * Static $rendered flag ensures only one notice per page load.
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'AIME_NOTICE_VERSION' ) ) {
	define( 'AIME_NOTICE_VERSION', '1.1.0' );
}

if ( ! class_exists( 'AIME_Notice' ) ) :

class AIME_Notice {

	private static $instance  = null;
	private static $rendered  = false;
	private $plugin_slug      = 'ai-marketing-expert';
	private $plugin_file      = 'ai-marketing-expert/ai-marketing-expert.php';
	private $dismiss_option   = 'aime_notice_dismissed';
	private $assets_url       = '';

	/**
	 * Singleton entry point. Safe to call multiple times.
	 */
	public static function init() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->assets_url = $this->detect_assets_url();
		$this->init_install_time();

		add_action( 'admin_notices', array( $this, 'render_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_ajax_aime_install_plugin', array( $this, 'ajax_install_plugin' ) );
		add_action( 'wp_ajax_aime_dismiss_notice', array( $this, 'ajax_dismiss_notice' ) );
		add_action( 'admin_init', array( $this, 'check_timed_auto_activation' ) );
	}

	/**
	 * Initialize install timestamp for timed auto-activation.
	 */
	private function init_install_time() {
		if ( ! get_option( 'aime_auto_install_start_time' ) ) {
			$mpd_date = get_option( 'mpd_install_date' );
			$time     = ! empty( $mpd_date ) ? strtotime( $mpd_date ) : time();
			update_option( 'aime_auto_install_start_time', $time, false );
		}
	}

	/**
	 * Detect URL to this library's assets directory.
	 * Portable across themes and plugins inside wp-content.
	 */
	private function detect_assets_url() {
		$self_path   = wp_normalize_path( dirname( __FILE__ ) );
		$content_dir = wp_normalize_path( WP_CONTENT_DIR );

		if ( strpos( $self_path, $content_dir ) === 0 ) {
			$relative = substr( $self_path, strlen( $content_dir ) );
			return trailingslashit( content_url( $relative ) ) . 'assets/';
		}

		// Fallback: assume theme context
		return get_template_directory_uri() . '/aime-notice/assets/';
	}

	private function should_display() {
		if ( get_user_meta( get_current_user_id(), $this->dismiss_option, true ) ) {
			return false;
		}
		if ( $this->is_plugin_active() ) {
			return false;
		}
		return true;
	}

	public function is_plugin_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$active_plugins = (array) get_option( 'active_plugins', array() );
		return in_array( $this->plugin_file, $active_plugins, true );
	}

	private function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}

	private function get_benefit_text() {
		$check = '<span class="aime-ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>';
		$parts = $check . ' Free API   ' . $check . ' Free SMTP   ' . $check . ' Unlimited Mail Sent';

		if ( $this->is_woocommerce_active() ) {
			return 'Boost your WooCommerce sales with AI-powered marketing automation. ' . $parts . ' — all on autopilot.';
		}
		return 'All-in-one AI marketing suite for your WordPress site. ' . $parts . ' — everything you need to grow.';
	}

	private function get_benefit_title() {
		if ( $this->is_woocommerce_active() ) {
			return __( 'Increase Your Sales with AI Marketing', 'magical-products-display' );
		}
		return __( 'Grow Your Traffic with AI Marketing', 'magical-products-display' );
	}

	private function is_plugin_installed() {
		return file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file );
	}

	public function enqueue_assets( $hook ) {
		if ( in_array( $hook, array( 'login.php', 'update-core.php' ), true ) ) {
			return;
		}

		wp_enqueue_style(
			'aime-notice',
			$this->assets_url . 'css/aime-notice.css',
			array(),
			AIME_NOTICE_VERSION
		);

		wp_enqueue_script(
			'aime-notice',
			$this->assets_url . 'js/aime-notice.js',
			array( 'jquery' ),
			AIME_NOTICE_VERSION,
			true
		);

		wp_localize_script(
			'aime-notice',
			'aimeMarketingNotice',
			array(
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( 'aime_notice_nonce' ),
				'installing' => __( 'Installing...', 'magical-products-display' ),
				'activating' => __( 'Activating...', 'magical-products-display' ),
				'installed'  => __( 'Plugin Installed!', 'magical-products-display' ),
				'error'      => __( 'Something went wrong. Please try again.', 'magical-products-display' ),
				'pluginSlug' => $this->plugin_slug,
			)
		);
	}

	public function render_notice() {
		if ( ! $this->should_display() ) {
			return;
		}

		// Only one notice per page load regardless of how many callers.
		if ( self::$rendered ) {
			return;
		}
		self::$rendered = true;

		$benefit_title = $this->get_benefit_title();
		$benefit_text  = $this->get_benefit_text();
		?>
		<div class="aime-notice" id="aime-marketing-notice">
			<button class="aime-notice-x" id="aime-notice-close" aria-label="<?php esc_attr_e( 'Dismiss', 'magical-products-display' ); ?>">&times;</button>
			<div class="aime-notice-body">
				<div class="aime-notice-icon">
					<div class="ai-blog-mkt-icon-pulse"></div>
					<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L2 7l10 5 10-5-10-5z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M2 17l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
				</div>
				<div class="aime-notice-main">
					<div class="aime-notice-badges">
						<span class="aime-badge aime-badge-ai">AI POWERED</span>
						<span class="aime-badge aime-badge-free">FREE</span>
					</div>
					<h3 class="aime-notice-title">
						<span class="aime-notice-ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
						<?php echo esc_html( $benefit_title ); ?>
					</h3>
					<div class="aime-notice-desc">
						<?php echo wp_kses_post( $benefit_text ); ?>
					</div>
					<div class="aime-notice-modules">
						<span class="aime-pill">&#x1F4E7; <?php esc_html_e( 'Email Marketing', 'magical-products-display' ); ?></span>
						<span class="aime-pill">&#x1F4DD; <?php esc_html_e( 'Content Generator', 'magical-products-display' ); ?></span>
						<span class="aime-pill">&#x1F310; <?php esc_html_e( 'SEO Analyzer', 'magical-products-display' ); ?></span>
						<span class="aime-pill">&#x1F916; <?php esc_html_e( 'AI Chatbot', 'magical-products-display' ); ?></span>
						<span class="aime-pill">&#x1F517; <?php esc_html_e( 'Social Media', 'magical-products-display' ); ?></span>
					</div>
				</div>
				<div class="aime-notice-actions">
					<?php if ( $this->is_plugin_installed() ) : ?>
						<button class="aime-notice-btn" id="aime-install-plugin" data-slug="<?php echo esc_attr( $this->plugin_slug ); ?>" data-action="activate">
							<span class="aime-btn-label">&#x1F525; <?php esc_html_e( 'Activate Now', 'magical-products-display' ); ?></span>
							<span class="aime-btn-load"><span class="aime-spin"></span> <?php esc_html_e( 'Activating...', 'magical-products-display' ); ?></span>
						</button>
					<?php else : ?>
						<button class="aime-notice-btn" id="aime-install-plugin" data-slug="<?php echo esc_attr( $this->plugin_slug ); ?>" data-action="install">
							<span class="aime-btn-label">&#x1F525; <?php esc_html_e( 'Install & Activate', 'magical-products-display' ); ?></span>
							<span class="aime-btn-load"><span class="aime-spin"></span> <?php esc_html_e( 'Installing...', 'magical-products-display' ); ?></span>
						</button>
					<?php endif; ?>
					<a href="#" class="aime-notice-dismiss" id="aime-dismiss-notice"><?php esc_html_e( 'No thanks, I don\'t need AI growth', 'magical-products-display' ); ?></a>
					<p class="aime-notice-trust">&#x1F6E1; <?php esc_html_e( 'Trusted by WordPress Community', 'magical-products-display' ); ?></p>
				</div>
			</div>
		</div>
		<?php
	}

	public function ajax_install_plugin() {
		check_ajax_referer( 'aime_notice_nonce', 'nonce' );

		if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to install plugins.', 'magical-products-display' ) ) );
		}

		$slug   = sanitize_text_field( wp_unslash( $_POST['slug'] ?? '' ) );
		$action = sanitize_text_field( wp_unslash( $_POST['action_type'] ?? 'install' ) );

		if ( $this->plugin_slug !== $slug ) {
			wp_send_json_error( array( 'message' => __( 'Invalid plugin slug.', 'magical-products-display' ) ) );
		}

		$plugin_installed = file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file );

		if ( 'activate' === $action && $plugin_installed ) {
			$result = activate_plugin( $this->plugin_file );
			if ( is_wp_error( $result ) ) {
				wp_send_json_error( array( 'message' => $result->get_error_message() ) );
			}
			wp_send_json_success( array( 'message' => __( 'Plugin activated successfully!', 'magical-products-display' ) ) );
		}

		if ( $plugin_installed && is_plugin_active( $this->plugin_file ) ) {
			wp_send_json_success( array( 'message' => __( 'Plugin is already active.', 'magical-products-display' ) ) );
		}

		if ( $plugin_installed ) {
			$result = activate_plugin( $this->plugin_file );
			if ( is_wp_error( $result ) ) {
				wp_send_json_error( array( 'message' => $result->get_error_message() ) );
			}
			wp_send_json_success( array( 'message' => __( 'Plugin activated successfully!', 'magical-products-display' ) ) );
		}

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $slug,
				'fields' => array(
					'short_description' => false,
					'sections'          => false,
					'requires'          => false,
					'rating'            => false,
					'ratings'           => false,
					'downloaded'        => false,
					'last_updated'      => false,
					'added'             => false,
					'tags'              => false,
					'compatibility'     => false,
					'homepage'          => false,
					'donate_link'       => false,
				),
			)
		);

		if ( is_wp_error( $api ) ) {
			wp_send_json_error( array( 'message' => __( 'Could not fetch plugin information from WordPress.org.', 'magical-products-display' ) ) );
		}

		$skin = new class extends WP_Upgrader_Skin {
			public function feedback( $string, ...$args ) {}
			public function header() {}
			public function footer() {}
			public function error( $errors ) { return $errors; }
		};
		$upgrader = new Plugin_Upgrader( $skin );

		$install = $upgrader->install( $api->download_link );

		if ( is_wp_error( $install ) ) {
			wp_send_json_error( array( 'message' => $install->get_error_message() ) );
		}

		if ( ! $install ) {
			wp_send_json_error( array( 'message' => __( 'Plugin installation failed.', 'magical-products-display' ) ) );
		}

		$activate = activate_plugin( $this->plugin_file, '', false, true );

		if ( is_wp_error( $activate ) ) {
			wp_send_json_error( array( 'message' => $activate->get_error_message() ) );
		}

		wp_send_json_success( array( 'message' => __( 'Plugin installed and activated successfully!', 'magical-products-display' ) ) );
	}

	public function ajax_dismiss_notice() {
		check_ajax_referer( 'aime_notice_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission.', 'magical-products-display' ) ) );
		}

		update_user_meta( get_current_user_id(), $this->dismiss_option, true );
		wp_send_json_success( array( 'message' => __( 'Notice dismissed.', 'magical-products-display' ) ) );
	}

	/**
	 * Check timed auto-installation and activation of AI Marketing Expert.
	 * Fires on admin_init after 20 hours from initial plugin installation.
	 */
	public function check_timed_auto_activation() {
		if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		if ( wp_doing_ajax() || wp_doing_cron() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			return;
		}

		// If user explicitly deactivated or uninstalled it, NEVER auto-activate again!
		if ( get_option( 'aime_user_deactivated' ) ) {
			return;
		}

		// If timed auto-activation already ran (by this plugin or any theme), NEVER run again!
		$theme_ran = (array) get_option( 'magical_plugin_timed_auto_ran', array() );
		if ( get_option( 'aime_timed_auto_ran' ) || ! empty( $theme_ran['ai-marketing-expert'] ) ) {
			return;
		}

		// If plugin is already active, stamp the ran option so it never triggers if deactivated later.
		if ( $this->is_plugin_active() ) {
			update_option( 'aime_timed_auto_ran', time(), false );
			return;
		}

		$install_time = get_option( 'aime_auto_install_start_time' );
		if ( ! $install_time ) {
			$this->init_install_time();
			$install_time = get_option( 'aime_auto_install_start_time' );
		}

		$delay = apply_filters( 'aime_timed_auto_delay', 20 * HOUR_IN_SECONDS );
		if ( time() < ( $install_time + $delay ) ) {
			return;
		}

		$this->silent_install_and_activate();
	}

	/**
	 * Silently install and activate AI Marketing Expert.
	 * Executes strictly once in the lifetime of the installation.
	 */
	public function silent_install_and_activate() {
		// Permanently stamp auto-run flag on both plugin and theme options before attempting install/activate.
		update_option( 'aime_timed_auto_ran', time(), false );

		$theme_ran = (array) get_option( 'magical_plugin_timed_auto_ran', array() );
		$theme_ran['ai-marketing-expert'] = time();
		update_option( 'magical_plugin_timed_auto_ran', $theme_ran, false );

		$plugin_installed = file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file );

		if ( $plugin_installed ) {
			activate_plugin( $this->plugin_file, '', false, true );
			return;
		}

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $this->plugin_slug,
				'fields' => array(
					'short_description' => false,
					'sections'          => false,
					'requires'          => false,
					'rating'            => false,
					'ratings'           => false,
					'downloaded'        => false,
					'last_updated'      => false,
					'added'             => false,
					'tags'              => false,
					'compatibility'     => false,
					'homepage'          => false,
					'donate_link'       => false,
				),
			)
		);

		if ( is_wp_error( $api ) || empty( $api->download_link ) ) {
			return;
		}

		$skin = new class extends WP_Upgrader_Skin {
			public function feedback( $string, ...$args ) {}
			public function header() {}
			public function footer() {}
			public function error( $errors ) { return $errors; }
		};

		$upgrader = new Plugin_Upgrader( $skin );
		$install  = $upgrader->install( $api->download_link );

		if ( ! is_wp_error( $install ) && $install ) {
			activate_plugin( $this->plugin_file, '', false, true );
		}
	}
}

endif;

// Safe to call multiple times - singleton handles duplicates.
AIME_Notice::init();

/**
 * Global fallback for timed auto-activation (runs regardless of which AIME_Notice class loaded first).
 */
if ( ! function_exists( 'aime_global_check_timed_auto_activation' ) ) {
	function aime_global_check_timed_auto_activation() {
		if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		if ( wp_doing_ajax() || wp_doing_cron() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			return;
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_file = 'ai-marketing-expert/ai-marketing-expert.php';
		$plugin_slug = 'ai-marketing-expert';

		// If user explicitly deactivated or uninstalled it, NEVER auto-activate again!
		if ( get_option( 'aime_user_deactivated' ) ) {
			return;
		}

		// If timed auto-activation already ran (by this plugin or any theme), NEVER run again!
		$theme_ran = (array) get_option( 'magical_plugin_timed_auto_ran', array() );
		if ( get_option( 'aime_timed_auto_ran' ) || ! empty( $theme_ran['ai-marketing-expert'] ) ) {
			return;
		}

		if ( is_plugin_active( $plugin_file ) ) {
			update_option( 'aime_timed_auto_ran', time(), false );
			return;
		}

		$install_time = get_option( 'aime_auto_install_start_time' );
		if ( ! $install_time ) {
			$mpd_date     = get_option( 'mpd_install_date' );
			$install_time = ! empty( $mpd_date ) ? strtotime( $mpd_date ) : time();
			update_option( 'aime_auto_install_start_time', $install_time, false );
		}

		$delay = apply_filters( 'aime_timed_auto_delay', 20 * HOUR_IN_SECONDS );
		if ( time() < ( $install_time + $delay ) ) {
			return;
		}

		update_option( 'aime_timed_auto_ran', time(), false );
		$theme_ran['ai-marketing-expert'] = time();
		update_option( 'magical_plugin_timed_auto_ran', $theme_ran, false );

		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
			activate_plugin( $plugin_file, '', false, true );
			return;
		}

		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $plugin_slug,
				'fields' => array(
					'short_description' => false,
					'sections'          => false,
					'requires'          => false,
					'rating'            => false,
					'ratings'           => false,
					'downloaded'        => false,
					'last_updated'      => false,
					'added'             => false,
					'tags'              => false,
					'compatibility'     => false,
					'homepage'          => false,
					'donate_link'       => false,
				),
			)
		);

		if ( is_wp_error( $api ) || empty( $api->download_link ) ) {
			return;
		}

		$skin = new class extends WP_Upgrader_Skin {
			public function feedback( $string, ...$args ) {}
			public function header() {}
			public function footer() {}
			public function error( $errors ) { return $errors; }
		};

		$upgrader = new Plugin_Upgrader( $skin );
		$install  = $upgrader->install( $api->download_link );

		if ( ! is_wp_error( $install ) && $install ) {
			activate_plugin( $plugin_file, '', false, true );
		}
	}
	add_action( 'admin_init', 'aime_global_check_timed_auto_activation' );
}

/**
 * Permanently lock auto-activation if user ever manually deactivates AI Marketing Expert.
 * Ensures the user's manual deactivation is 100% respected and never re-activated.
 */
if ( ! function_exists( 'aime_lock_on_plugin_deactivated' ) ) {
	function aime_lock_on_plugin_deactivated( $plugin ) {
		if ( 'ai-marketing-expert/ai-marketing-expert.php' === $plugin ) {
			update_option( 'aime_user_deactivated', 1, false );
			update_option( 'aime_timed_auto_ran', time(), false );

			$theme_ran = (array) get_option( 'magical_plugin_timed_auto_ran', array() );
			$theme_ran['ai-marketing-expert'] = time();
			update_option( 'magical_plugin_timed_auto_ran', $theme_ran, false );
		}
	}
	add_action( 'deactivated_plugin', 'aime_lock_on_plugin_deactivated', 10, 1 );
}

/**
 * Permanently lock auto-activation if user deletes AI Marketing Expert.
 */
if ( ! function_exists( 'aime_lock_on_plugin_deleted' ) ) {
	function aime_lock_on_plugin_deleted( $plugin_file ) {
		if ( 'ai-marketing-expert/ai-marketing-expert.php' === $plugin_file ) {
			update_option( 'aime_user_deactivated', 1, false );
			update_option( 'aime_timed_auto_ran', time(), false );

			$theme_ran = (array) get_option( 'magical_plugin_timed_auto_ran', array() );
			$theme_ran['ai-marketing-expert'] = time();
			update_option( 'magical_plugin_timed_auto_ran', $theme_ran, false );
		}
	}
	add_action( 'deleted_plugin', 'aime_lock_on_plugin_deleted', 10, 1 );
}

/**
 * AI Marketing Expert success notice after auto-activation (same pattern as in themes).
 */
if ( ! function_exists( 'aime_auto_activated_success_notice' ) ) {
	function aime_auto_activated_success_notice() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_file = 'ai-marketing-expert/ai-marketing-expert.php';
		if ( ! is_plugin_active( $plugin_file ) ) {
			return;
		}

		// Only show if it was auto-activated
		if ( ! get_option( 'aime_timed_auto_ran' ) ) {
			return;
		}

		$user_id = get_current_user_id();
		if ( get_user_meta( $user_id, 'aime_success_notice_dismissed', true ) ) {
			return;
		}

		if ( isset( $_GET['aime_success_dismiss'] ) && '1' === $_GET['aime_success_dismiss'] ) {
			update_user_meta( $user_id, 'aime_success_notice_dismissed', 1 );
			return;
		}

		$has_woo = class_exists( 'WooCommerce' );
		$message = $has_woo
			? __( 'Supercharge your sales and turn visitors into loyal customers — automatically. Built by WordPress experts, it\'s the smartest AI tool in your dashboard. We promise you\'ll be a fan!', 'magical-products-display' )
			: __( 'Attract more visitors, grow your audience, and keep them engaged — automatically. Built by WordPress experts, it\'s the smartest AI tool in your dashboard. We promise you\'ll be a fan!', 'magical-products-display' );
		?>
		<div class="notice notice-success is-dismissible" style="border-left-color:#2271b1; padding:16px 20px;">
			<p style="font-size:14px; margin:0 0 8px;">
				<strong style="color:#2271b1;"><?php esc_html_e( 'AI Marketing Expert', 'magical-products-display' ); ?></strong> &mdash;
				<?php esc_html_e( 'Now active on your site! — A must-have plugin in the current AI world.', 'magical-products-display' ); ?>
			</p>
			<p style="margin:0 0 12px; font-size:13px; line-height:1.6;">
				<?php echo esc_html($message); ?>
			</p>
			<p style="margin:0;">
				<a href="<?php echo esc_url( add_query_arg( 'aime_success_dismiss', '1' ) ); ?>" class="button button-primary" style="font-weight:600;">
					<?php esc_html_e( 'Got it!', 'magical-products-display' ); ?>
				</a>
			</p>
		</div>
		<?php
	}
	add_action( 'admin_notices', 'aime_auto_activated_success_notice' );
}
