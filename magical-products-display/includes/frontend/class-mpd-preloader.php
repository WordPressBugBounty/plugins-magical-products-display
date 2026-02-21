<?php
/**
 * Preloader handler for Magical Shop Builder.
 *
 * Handles page preloader rendering and settings.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Frontend;

use MPD\MagicalShopBuilder\Admin\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Preloader
 *
 * Manages page preloader functionality.
 *
 * @since 2.0.0
 */
class Preloader {

	/**
	 * Instance of the class.
	 *
	 * @var Preloader|null
	 */
	private static $instance = null;

	/**
	 * Preloader settings.
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * Get instance.
	 *
	 * @since 2.0.0
	 *
	 * @return Preloader
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
		$this->load_settings();
	}

	/**
	 * Initialize the preloader.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init() {
		// Only run on frontend.
		if ( is_admin() ) {
			return;
		}

		// Check if preloader is enabled in settings (basic check, no WC functions needed).
		if ( empty( $this->settings['enable'] ) ) {
			// Preloader is OFF — add lightweight FOUC prevention via opacity fade-in.
			add_action( 'wp_head', array( $this, 'render_fouc_prevention_style' ), 1 );
			add_action( 'wp_footer', array( $this, 'render_fouc_prevention_script' ), 100 );
			return;
		}

		// Add preloader hooks - actual page checks done in render methods.
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_assets' ), 5 );
		add_action( 'wp_body_open', array( $this, 'maybe_render_preloader' ), 1 );
		add_action( 'wp_footer', array( $this, 'maybe_render_preloader_script' ), 100 );
	}

	/**
	 * Render inline style to hide body until window load (FOUC prevention fallback).
	 *
	 * Used when the preloader is disabled to still prevent flash of unstyled content.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function render_fouc_prevention_style() {
		// Skip in Elementor editor/preview.
		if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance ) {
			$editor  = \Elementor\Plugin::$instance->editor;
			$preview = \Elementor\Plugin::$instance->preview;

			if ( $editor && method_exists( $editor, 'is_edit_mode' ) && $editor->is_edit_mode() ) {
				return;
			}

			if ( $preview && method_exists( $preview, 'is_preview_mode' ) && $preview->is_preview_mode() ) {
				return;
			}
		}

		// Only apply on WooCommerce pages.
		if ( ! $this->is_wc_page() ) {
			return;
		}

		?>
		<style id="mpd-fouc-prevention">
			body:not(.mpd-page-loaded) {
				opacity: 0 !important;
			}
			body.mpd-page-loaded {
				opacity: 1 !important;
				transition: opacity 0.3s ease-in-out;
			}
		</style>
		<?php
	}

	/**
	 * Render script to reveal body on window load (FOUC prevention fallback).
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function render_fouc_prevention_script() {
		// Skip in Elementor editor/preview.
		if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance ) {
			$editor  = \Elementor\Plugin::$instance->editor;
			$preview = \Elementor\Plugin::$instance->preview;

			if ( $editor && method_exists( $editor, 'is_edit_mode' ) && $editor->is_edit_mode() ) {
				return;
			}

			if ( $preview && method_exists( $preview, 'is_preview_mode' ) && $preview->is_preview_mode() ) {
				return;
			}
		}

		// Only apply on WooCommerce pages.
		if ( ! $this->is_wc_page() ) {
			return;
		}

		?>
		<script>
		(function() {
			function revealPage() {
				document.body.classList.add('mpd-page-loaded');
			}
			if (document.readyState === 'complete') {
				revealPage();
			} else {
				window.addEventListener('load', revealPage);
			}
			// Safety fallback: reveal after 4 seconds even if load event stalls.
			setTimeout(revealPage, 4000);
		})();
		</script>
		<?php
	}

	/**
	 * Check if current page is a WooCommerce page (lightweight, safe for wp_head).
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function is_wc_page() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}

		// These WC conditional functions are available after 'wp' action.
		if ( ! did_action( 'wp' ) ) {
			return true; // Cannot determine yet, assume yes to be safe.
		}

		return is_shop()
			|| is_product_taxonomy()
			|| is_singular( 'product' )
			|| is_cart()
			|| is_checkout()
			|| is_checkout_pay_page()
			|| is_account_page()
			|| is_wc_endpoint_url( 'order-received' );
	}

	/**
	 * Maybe enqueue preloader assets.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function maybe_enqueue_assets() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		$this->enqueue_assets();
	}

	/**
	 * Maybe render preloader.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function maybe_render_preloader() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		$this->render_preloader();
	}

	/**
	 * Maybe render preloader script.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function maybe_render_preloader_script() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		$this->render_preloader_script();
	}

	/**
	 * Load preloader settings.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function load_settings() {
		$this->settings = get_option( 'mpd_preloader_settings', $this->get_defaults() );
		$this->settings = wp_parse_args( $this->settings, $this->get_defaults() );
	}

	/**
	 * Get default settings.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_defaults() {
		return array(
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
		);
	}

	/**
	 * Check if preloader is enabled.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_enabled() {
		if ( empty( $this->settings['enable'] ) ) {
			return false;
		}

		// Don't show in Elementor editor or preview.
		if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance ) {
			$editor = \Elementor\Plugin::$instance->editor;
			$preview = \Elementor\Plugin::$instance->preview;
			
			if ( $editor && method_exists( $editor, 'is_edit_mode' ) && $editor->is_edit_mode() ) {
				return false;
			}
			
			if ( $preview && method_exists( $preview, 'is_preview_mode' ) && $preview->is_preview_mode() ) {
				return false;
			}
		}

		// Check if current page should show preloader.
		return $this->should_show_on_current_page();
	}

	/**
	 * Check if preloader should show on current page.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function should_show_on_current_page() {
		$pages = isset( $this->settings['pages'] ) ? $this->settings['pages'] : array();

		// If no pages selected, don't show.
		if ( empty( $pages ) ) {
			return false;
		}

		// Check for all pages option.
		if ( in_array( 'all', $pages, true ) ) {
			return true;
		}

		// Check WooCommerce pages.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}

		// Shop page.
		if ( in_array( 'shop', $pages, true ) && ( is_shop() || is_product_taxonomy() ) ) {
			return true;
		}

		// Single product page.
		if ( in_array( 'product', $pages, true ) && is_singular( 'product' ) ) {
			return true;
		}

		// Cart page.
		if ( in_array( 'cart', $pages, true ) && is_cart() ) {
			return true;
		}

		// Checkout page.
		if ( in_array( 'checkout', $pages, true ) && ( is_checkout() || is_checkout_pay_page() ) ) {
			return true;
		}

		// My Account page.
		if ( in_array( 'my-account', $pages, true ) && is_account_page() ) {
			return true;
		}

		// Thank you page.
		if ( in_array( 'thank-you', $pages, true ) && is_wc_endpoint_url( 'order-received' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Enqueue preloader assets.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		wp_enqueue_style(
			'mpd-preloader',
			MAGICAL_PRODUCTS_DISPLAY_ASSETS . 'css/mpd-preloader.css',
			array(),
			MAGICAL_PRODUCTS_DISPLAY_VERSION,
			'all'
		);

		// Add inline CSS for custom colors.
		$custom_css = $this->get_custom_css();
		if ( $custom_css ) {
			wp_add_inline_style( 'mpd-preloader', $custom_css );
		}
	}

	/**
	 * Get custom CSS for preloader colors.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	private function get_custom_css() {
		$primary    = ! empty( $this->settings['primary_color'] ) ? $this->settings['primary_color'] : '#0073aa';
		$secondary  = ! empty( $this->settings['secondary_color'] ) ? $this->settings['secondary_color'] : '#f3f3f3';
		$background = ! empty( $this->settings['background_color'] ) ? $this->settings['background_color'] : '#ffffff';
		$text       = ! empty( $this->settings['text_color'] ) ? $this->settings['text_color'] : '#666666';

		return sprintf(
			':root {
				--mpd-preloader-primary: %s;
				--mpd-preloader-secondary: %s;
				--mpd-preloader-bg: %s;
				--mpd-preloader-text: %s;
			}',
			esc_attr( $primary ),
			esc_attr( $secondary ),
			esc_attr( $background ),
			esc_attr( $text )
		);
	}

	/**
	 * Render the preloader HTML.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function render_preloader() {
		$style    = ! empty( $this->settings['style'] ) ? $this->settings['style'] : 'spinner';
		$show_logo = ! empty( $this->settings['show_logo'] ) && ! empty( $this->settings['logo_url'] );
		$loading_text = ! empty( $this->settings['loading_text'] ) ? $this->settings['loading_text'] : '';

		?>
		<div id="mpd-preloader" class="mpd-preloader mpd-preloader--<?php echo esc_attr( $style ); ?>">
			<div class="mpd-preloader__inner">
				<?php if ( $show_logo ) : ?>
					<div class="mpd-preloader__logo">
						<img src="<?php echo esc_url( $this->settings['logo_url'] ); ?>" alt="<?php esc_attr_e( 'Loading...', 'magical-products-display' ); ?>">
						<?php if ( 'logo-ring' === $style ) : ?>
							<span class="mpd-logo-ring"></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php $this->render_spinner( $style ); ?>

				<?php if ( $loading_text ) : ?>
					<div class="mpd-preloader__text"><?php echo esc_html( $loading_text ); ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render spinner based on style.
	 *
	 * @since 2.0.0
	 *
	 * @param string $style Preloader style.
	 * @return void
	 */
	private function render_spinner( $style ) {
		switch ( $style ) {
			case 'double-bounce':
				?>
				<div class="mpd-preloader__spinner">
					<div class="mpd-bounce1"></div>
					<div class="mpd-bounce2"></div>
				</div>
				<?php
				break;

			case 'pulse':
				?>
				<div class="mpd-preloader__spinner"></div>
				<?php
				break;

			case 'three-dots':
				?>
				<div class="mpd-preloader__spinner">
					<div class="mpd-dot"></div>
					<div class="mpd-dot"></div>
					<div class="mpd-dot"></div>
				</div>
				<?php
				break;

			case 'wave':
				?>
				<div class="mpd-preloader__spinner">
					<div class="mpd-bar"></div>
					<div class="mpd-bar"></div>
					<div class="mpd-bar"></div>
					<div class="mpd-bar"></div>
					<div class="mpd-bar"></div>
				</div>
				<?php
				break;

			case 'cube-grid':
				?>
				<div class="mpd-preloader__spinner">
					<?php for ( $i = 0; $i < 9; $i++ ) : ?>
						<div class="mpd-cube"></div>
					<?php endfor; ?>
				</div>
				<?php
				break;

			case 'ring':
				?>
				<div class="mpd-preloader__spinner"></div>
				<?php
				break;

			case 'folding-cube':
				?>
				<div class="mpd-preloader__spinner">
					<div class="mpd-cube"></div>
					<div class="mpd-cube"></div>
					<div class="mpd-cube"></div>
					<div class="mpd-cube"></div>
				</div>
				<?php
				break;

			case 'circle-dots':
				?>
				<div class="mpd-preloader__spinner">
					<div class="mpd-dot"></div>
					<div class="mpd-dot"></div>
				</div>
				<?php
				break;

			case 'progress':
				?>
				<div class="mpd-preloader__spinner">
					<div class="mpd-progress-bar"></div>
				</div>
				<?php
				break;

			case 'logo-fade':
			case 'logo-ring':
				// Logo-only styles don't need additional spinner.
				break;

			case 'spinner':
			default:
				?>
				<div class="mpd-preloader__spinner"></div>
				<?php
				break;
		}
	}

	/**
	 * Render preloader script.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function render_preloader_script() {
		?>
		<script>
		(function() {
			var preloader = document.getElementById('mpd-preloader');
			if (!preloader) return;

			function hidePreloader() {
				preloader.classList.add('mpd-preloader--loaded');
				// Remove from DOM after animation.
				setTimeout(function() {
					if (preloader.parentNode) {
						preloader.parentNode.removeChild(preloader);
					}
				}, 500);
			}

			// Hide when page is fully loaded.
			if (document.readyState === 'complete') {
				hidePreloader();
			} else {
				window.addEventListener('load', hidePreloader);
			}

			// Fallback: Hide after 5 seconds max.
			setTimeout(hidePreloader, 5000);
		})();
		</script>
		<?php
	}

	/**
	 * Get available preloader styles.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_styles() {
		return array(
			'spinner'       => __( 'Spinner Circle', 'magical-products-display' ),
			'double-bounce' => __( 'Double Bounce', 'magical-products-display' ),
			'pulse'         => __( 'Pulse', 'magical-products-display' ),
			'three-dots'    => __( 'Three Dots', 'magical-products-display' ),
			'wave'          => __( 'Wave Bars', 'magical-products-display' ),
			'cube-grid'     => __( 'Cube Grid', 'magical-products-display' ),
			'ring'          => __( 'Ring', 'magical-products-display' ),
			'folding-cube'  => __( 'Folding Cube', 'magical-products-display' ),
			'circle-dots'   => __( 'Circle Dots', 'magical-products-display' ),
			'progress'      => __( 'Progress Bar', 'magical-products-display' ),
			'logo-fade'     => __( 'Logo Fade (requires logo)', 'magical-products-display' ),
			'logo-ring'     => __( 'Logo with Ring (requires logo)', 'magical-products-display' ),
		);
	}

	/**
	 * Get available page options.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public static function get_page_options() {
		return array(
			'all'        => __( 'All Pages', 'magical-products-display' ),
			'shop'       => __( 'Shop / Archive', 'magical-products-display' ),
			'product'    => __( 'Single Product', 'magical-products-display' ),
			'cart'       => __( 'Cart', 'magical-products-display' ),
			'checkout'   => __( 'Checkout', 'magical-products-display' ),
			'my-account' => __( 'My Account', 'magical-products-display' ),
			'thank-you'  => __( 'Thank You Page', 'magical-products-display' ),
		);
	}

	/**
	 * Get preloader settings.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}
}
