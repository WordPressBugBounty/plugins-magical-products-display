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
		// Timed auto-activation removed — silent plugin install is not permitted per WordPress.org guidelines.
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
	 * Timed auto-activation has been removed.
	 * Silent plugin installation without explicit administrator consent is not
	 * permitted under WordPress.org plugin guidelines (section 7).
	 * Users who wish to install AI Marketing Expert may do so manually from
	 * the notification banner displayed in wp-admin.
	 *
	 * @deprecated Removed in 2.1.3
	 */
	public function check_timed_auto_activation() {
		// Intentionally disabled — no silent install.
	}

	/**
	 * @deprecated Removed in 2.1.3 — silent install is not permitted.
	 */
	public function silent_install_and_activate() {
		// Intentionally disabled — no silent install.
	}
}

endif;

// Safe to call multiple times - singleton handles duplicates.
AIME_Notice::init();

/**
 * Global timed auto-activation has been removed in 2.1.3.
 * Silent plugin installation without explicit consent is not permitted
 * per WordPress.org plugin directory guidelines.
 *
 * @deprecated Removed in 2.1.3
 */
if ( ! function_exists( 'aime_global_check_timed_auto_activation' ) ) {
	function aime_global_check_timed_auto_activation() {
		// Intentionally disabled — no silent install.
	}
}