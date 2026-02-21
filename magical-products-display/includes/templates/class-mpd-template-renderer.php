<?php
/**
 * Template Renderer
 *
 * Handles rendering of custom templates for WooCommerce pages.
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
 * Class Template_Renderer
 *
 * Renders custom templates using Elementor.
 *
 * @since 2.0.0
 */
class Template_Renderer {

	/**
	 * Instance of the class.
	 *
	 * @var Template_Renderer|null
	 */
	private static $instance = null;

	/**
	 * Current template being rendered.
	 *
	 * @var int|null
	 */
	private $current_template = null;

	/**
	 * Whether we're currently rendering.
	 *
	 * @var bool
	 */
	private $is_rendering = false;

	/**
	 * Get instance.
	 *
	 * @since 2.0.0
	 *
	 * @return Template_Renderer
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
	 * Initialize the renderer.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init() {
		// Hook into template_include for main template override.
		add_filter( 'template_include', array( $this, 'template_include' ), 998 );
	}

	/**
	 * Filter template_include to use our custom templates.
	 *
	 * @since 2.0.0
	 *
	 * @param string $template Current template path.
	 * @return string Modified template path.
	 */
	public function template_include( $template ) {
		// Don't override if templates are disabled.
		if ( ! Template_Manager::instance()->is_enabled() ) {
			return $template;
		}

		// Don't override if using Elementor Pro Theme Builder.
		if ( Template_Manager::instance()->has_elementor_pro_theme_builder() ) {
			return $template;
		}

		// Check if we have a custom template for this page.
		$template_type = $this->get_current_page_type();

		if ( ! $template_type ) {
			return $template;
		}

		$custom_template_id = Template_Manager::instance()->get_template_for_current_page( $template_type );

		if ( ! $custom_template_id ) {
			return $template;
		}

		// Store current template for rendering.
		$this->current_template = $custom_template_id;

		// Setup WooCommerce hooks immediately since we have a custom template.
		$this->setup_wc_hooks();

		// Return our canvas template.
		return $this->get_canvas_template();
	}

	/**
	 * Get the current WooCommerce page type.
	 *
	 * @since 2.0.0
	 *
	 * @return string|false Template type or false.
	 */
	public function get_current_page_type() {
		// Cache result per request.
		static $cached_type = null;
		if ( null !== $cached_type ) {
			return $cached_type;
		}

		if ( ! class_exists( 'WooCommerce' ) ) {
			$cached_type = false;
			return false;
		}

		// Single product page.
		if ( is_singular( 'product' ) ) {
			$cached_type = 'single-product';
			return 'single-product';
		}

		// Shop/Archive pages.
		if ( is_shop() || is_product_taxonomy() ) {
			$cached_type = 'archive-product';
			return 'archive-product';
		}

		// Cart page.
		if ( is_cart() ) {
			// Check if cart is empty.
			if ( function_exists( 'WC' ) && WC()->cart && WC()->cart->is_empty() ) {
				$cached_type = 'empty-cart';
				return 'empty-cart';
			}
			$cached_type = 'cart';
			return 'cart';
		}

		// Checkout page.
		if ( is_checkout() ) {
			// Check if this is the order-received (thank you) page.
			if ( is_wc_endpoint_url( 'order-received' ) ) {
				$cached_type = 'thankyou';
				return 'thankyou';
			}
			$cached_type = 'checkout';
			return 'checkout';
		}

		// My Account page.
		if ( is_account_page() ) {
			$cached_type = 'my-account';
			return 'my-account';
		}

		$cached_type = false;
		return false;
	}

	/**
	 * Get the canvas template path.
	 *
	 * @since 2.0.0
	 *
	 * @return string Template path.
	 */
	private function get_canvas_template() {
		// Check the page template setting on the MPD template.
		$page_template = '';
		if ( $this->current_template ) {
			$page_template = get_post_meta( $this->current_template, '_wp_page_template', true );
		}
		
		// For Full Width or Header-Footer templates, use our header-footer.php with theme header/footer.
		if ( in_array( $page_template, array( 'elementor_theme', 'elementor_header_footer' ), true ) ) {
			return MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/templates/views/header-footer.php';
		}
		
		// For Canvas template or default, use our canvas template.
		// This gives us full control over the rendering.
		return MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/templates/views/canvas.php';
	}

	/**
	 * Setup WooCommerce hooks for template rendering.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function setup_wc_hooks() {
		if ( ! $this->current_template ) {
			return;
		}

		// Ensure Elementor frontend assets are loaded for this template.
		$this->ensure_elementor_assets();

		// Setup global product for single product pages.
		$page_type = $this->get_current_page_type();
		if ( 'single-product' === $page_type ) {
			$this->setup_product_data();
			// Enqueue single product widget styles.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_single_product_styles' ), 20 );
		}

		// Remove default WooCommerce content.
		$this->remove_default_wc_content();

		// Add our template content.
		add_action( 'mpd_render_template', array( $this, 'render_template_content' ) );
	}

	/**
	 * Enqueue single product widget styles.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function enqueue_single_product_styles() {
		wp_enqueue_style( 'mpd-single-product' );
	}

	/**
	 * Ensure Elementor frontend assets load for the MPD template.
	 *
	 * When our template_include filter returns the canvas/header-footer
	 * template, Elementor's normal "has builder content?" check looks at the
	 * queried object (e.g. the WooCommerce shop archive) which has no
	 * _elementor_data, so Elementor decides to skip its frontend CSS/JS.
	 * That in turn prevents the `elementor/frontend/after_enqueue_styles`
	 * hook from firing, which means our own widget styles never register.
	 *
	 * Additionally, Elementor only enqueues widget style/script dependencies
	 * (via get_style_depends / get_script_depends) during
	 * get_builder_content_for_display() which runs in the <body>. This causes
	 * widget CSS to appear in the footer instead of <head>, or not load at all
	 * on some sites due to Elementor's CSS caching.
	 *
	 * The fix:
	 * 1. Force Elementor's frontend CSS/JS by calling enqueue_styles / enqueue_scripts.
	 * 2. Enqueue the generated post-CSS for our MPD template.
	 * 3. Parse the template's Elementor data, find all widgets used, and
	 *    proactively enqueue their CSS dependencies so they print in <head>.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function ensure_elementor_assets() {
		$template_id = $this->current_template;

		if ( ! $template_id || ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		// Store renderer reference for the closure.
		$renderer = $this;

		// Hook after register_widget_styles (5) and register_widget_scripts (5)
		// but before wp_print_styles in wp_head (priority 8).
		add_action( 'wp_enqueue_scripts', function () use ( $template_id, $renderer ) {
			$frontend = \Elementor\Plugin::$instance->frontend;

			// Force Elementor to register & enqueue its frontend CSS/JS.
			// This also fires elementor/frontend/after_enqueue_styles and
			// elementor/frontend/after_enqueue_scripts which register our
			// remaining handles and add script localizations.
			if ( method_exists( $frontend, 'enqueue_styles' ) ) {
				$frontend->enqueue_styles();
			}
			if ( method_exists( $frontend, 'enqueue_scripts' ) ) {
				$frontend->enqueue_scripts();
			}

			// Enqueue the generated Elementor CSS for this specific template post.
			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
				$css_file = new \Elementor\Core\Files\CSS\Post( $template_id );
				$css_file->enqueue();
			}

			// Proactively enqueue widget CSS/JS dependencies so CSS prints
			// in <head> instead of footer (or not loading at all).
			$renderer->enqueue_template_widget_assets( $template_id );
		}, 8 );
	}

	/**
	 * Parse template Elementor data and proactively enqueue widget CSS/JS.
	 *
	 * Normally Elementor enqueues widget style/script dependencies during
	 * get_builder_content_for_display() which runs in the <body>. That causes
	 * CSS to appear in the footer instead of <head>, or not load at all on
	 * some sites due to Elementor's CSS file caching.
	 *
	 * By parsing the template data and enqueuing dependencies early (during
	 * wp_enqueue_scripts), we ensure CSS is in <head> and JS is in footer.
	 *
	 * @since 2.0.0
	 *
	 * @param int $template_id The template post ID.
	 * @return void
	 */
	public function enqueue_template_widget_assets( $template_id ) {
		$elementor_data = get_post_meta( $template_id, '_elementor_data', true );

		if ( empty( $elementor_data ) ) {
			return;
		}

		$data = json_decode( $elementor_data, true );

		if ( ! is_array( $data ) ) {
			return;
		}

		// Extract all widget types from the template data.
		$widget_types = array();
		$this->extract_widget_types( $data, $widget_types );

		if ( empty( $widget_types ) ) {
			return;
		}

		// Always enqueue the main display style when a template is active.
		if ( wp_style_is( 'mgproducts-style', 'registered' ) ) {
			wp_enqueue_style( 'mgproducts-style' );
		}

		// Use Elementor's widget manager to get style/script dependencies.
		$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;

		foreach ( array_keys( $widget_types ) as $widget_type ) {
			$widget = $widgets_manager->get_widget_types( $widget_type );

			if ( ! $widget ) {
				continue;
			}

			// Enqueue CSS dependencies so they print in <head>.
			if ( method_exists( $widget, 'get_style_depends' ) ) {
				foreach ( $widget->get_style_depends() as $handle ) {
					if ( wp_style_is( $handle, 'registered' ) ) {
						wp_enqueue_style( $handle );
					}
				}
			}

			// Enqueue JS dependencies (prints in footer since registered with footer=true).
			if ( method_exists( $widget, 'get_script_depends' ) ) {
				foreach ( $widget->get_script_depends() as $handle ) {
					if ( wp_script_is( $handle, 'registered' ) ) {
						wp_enqueue_script( $handle );
					}
				}
			}
		}
	}

	/**
	 * Recursively extract widget types from Elementor element data.
	 *
	 * @since 2.0.0
	 *
	 * @param array $elements Elementor elements array.
	 * @param array $types    Reference to array collecting widget types as keys.
	 * @return void
	 */
	private function extract_widget_types( $elements, &$types ) {
		foreach ( $elements as $element ) {
			if ( ! empty( $element['widgetType'] ) ) {
				$types[ $element['widgetType'] ] = true;
			}

			if ( ! empty( $element['elements'] ) ) {
				$this->extract_widget_types( $element['elements'], $types );
			}
		}
	}

	/**
	 * Setup global product data for single product pages.
	 *
	 * Sets up the $product and $post globals that WooCommerce and MPD widgets
	 * depend on. Does NOT fire woocommerce_before_single_product because:
	 * 1. It runs during template_include (before <!DOCTYPE html>), so theme
	 *    callbacks would output HTML in the wrong place.
	 * 2. WC core only adds wc_print_notices() to that hook — MPD handles
	 *    notices via its own widgets.
	 * 3. Themes add breadcrumbs/wrappers there, which conflict with MPD templates.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function setup_product_data() {
		global $product, $post;

		// Get the current post.
		$current_post = get_queried_object();

		if ( ! $current_post || ! isset( $current_post->ID ) ) {
			return;
		}

		// Setup post data.
		$post = get_post( $current_post->ID ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $post );

		// Setup product global.
		$product = wc_get_product( $current_post->ID ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	/**
	 * Remove ALL callbacks from WooCommerce structural display hooks.
	 *
	 * WHY remove_all_actions() instead of individual remove_action() calls?
	 * -----------------------------------------------------------------
	 * The old approach only removed known WooCommerce core callbacks by name:
	 *   remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
	 *
	 * But WooCommerce-compatible themes (Storefront, Astra, OceanWP, Flatsome,
	 * Kadence, Flavor, Hello Elementor, etc.) add their OWN callbacks on the
	 * SAME hooks — breadcrumbs, wrappers, sidebars, custom markup — at
	 * unpredictable priorities. Since there are hundreds of themes, removing
	 * by name+priority is impossible.
	 *
	 * Instead, we use remove_all_actions() on STRUCTURAL/DISPLAY hooks that
	 * MPD's Elementor templates completely replace. This eliminates ALL theme
	 * conflicts on these hooks regardless of the active theme.
	 *
	 * FUNCTIONAL hooks (cart internals, checkout, payment, login) are LEFT
	 * UNTOUCHED because MPD widgets call do_action() on them and need
	 * third-party callbacks (PayPal, Stripe, shipping calculators) to work.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function remove_default_wc_content() {
		$page_type = $this->get_current_page_type();

		// ──────────────────────────────────────────────────────────────
		// Common structural hooks — ALL page types.
		// Page wrappers, sidebars — fully replaced by canvas/header-footer template.
		// ──────────────────────────────────────────────────────────────
		$common_hooks = array(
			'woocommerce_before_main_content',
			'woocommerce_after_main_content',
			'woocommerce_sidebar',
		);

		// ──────────────────────────────────────────────────────────────
		// Page-type specific display hooks.
		// ──────────────────────────────────────────────────────────────
		$page_hooks = array();

		switch ( $page_type ) {
			case 'single-product':
				$page_hooks = array(
					// Product page wrappers & breadcrumbs.
					'woocommerce_before_single_product',
					'woocommerce_after_single_product',
					// Product image/gallery area.
					'woocommerce_before_single_product_summary',
					'woocommerce_product_thumbnails',
					// Product summary (title, price, rating, add-to-cart, meta, sharing).
					'woocommerce_single_product_summary',
					// After summary (tabs, upsells, related products).
					'woocommerce_after_single_product_summary',
				);
				break;

			case 'archive-product':
				$page_hooks = array(
					// Archive header / description.
					'woocommerce_archive_description',
					// Before/after products loop (result count, ordering, pagination).
					'woocommerce_before_shop_loop',
					'woocommerce_after_shop_loop',
					// Individual loop items (thumbnails, titles, prices, add-to-cart).
					'woocommerce_before_shop_loop_item',
					'woocommerce_after_shop_loop_item',
					'woocommerce_before_shop_loop_item_title',
					'woocommerce_shop_loop_item_title',
					'woocommerce_after_shop_loop_item_title',
					// No products found message.
					'woocommerce_no_products_found',
				);
				break;

			case 'cart':
			case 'empty-cart':
				// ONLY clear outer wrappers. Internal cart hooks (before_cart_table,
				// cart_actions, proceed_to_checkout, etc.) are called by MPD's own
				// cart widgets and need third-party callbacks for payment gateways.
				$page_hooks = array(
					'woocommerce_before_cart',
					'woocommerce_after_cart',
				);
				break;

			case 'checkout':
				// Checkout hooks are called by MPD widgets and critical for payment
				// gateways (PayPal, Stripe, etc.) — leave them ALL intact.
				break;

			case 'my-account':
				$page_hooks = array(
					// Navigation and content framework.
					'woocommerce_account_content',
					'woocommerce_account_dashboard',
					'woocommerce_account_navigation',
					'woocommerce_before_account_navigation',
					'woocommerce_after_account_navigation',
					// Endpoint-specific content — widgets replace these entirely.
					'woocommerce_account_edit-account_endpoint',
					'woocommerce_account_orders_endpoint',
					'woocommerce_account_downloads_endpoint',
					'woocommerce_account_edit-address_endpoint',
					'woocommerce_account_payment-methods_endpoint',
					'woocommerce_account_view-order_endpoint',
				);
				break;

			case 'thankyou':
				// Thank you hooks are called by MPD widgets and payment gateways
				// for order confirmation — leave them intact.
				break;
		}

		$hooks_to_clear = array_merge( $common_hooks, $page_hooks );

		/**
		 * Filter the WooCommerce hooks to isolate when MPD templates are active.
		 *
		 * Use this filter to add or remove hooks from the isolation list.
		 * Hooks in this array will have ALL their callbacks removed via
		 * remove_all_actions() to prevent theme conflicts.
		 *
		 * @since 2.0.0
		 *
		 * @param string[] $hooks_to_clear Hook names whose callbacks will be removed.
		 * @param string   $page_type      Current page type (single-product, archive-product, etc.).
		 */
		$hooks_to_clear = apply_filters( 'mpd_isolated_display_hooks', $hooks_to_clear, $page_type );

		foreach ( $hooks_to_clear as $hook ) {
			remove_all_actions( $hook );
		}
	}

	/**
	 * Render the template content.
	 *
	 * Wraps cart and my-account pages in a .woocommerce div so that
	 * WooCommerce core scripts (cart.js, etc.) can find their expected
	 * ancestor element for delegated event handlers.
	 *
	 * For My Account pages, renders a native WooCommerce endpoint fallback
	 * when the Elementor template does not contain a widget for the current
	 * endpoint (e.g. the user built a template with only the Dashboard widget
	 * but visits /my-account/orders/).
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function render_template_content() {
		if ( ! $this->current_template || $this->is_rendering ) {
			return;
		}

		$this->is_rendering = true;

		$page_type = $this->get_current_page_type();

		// Wrap WooCommerce page types in a .woocommerce div.
		// WC's cart.js delegates events on $( '.woocommerce' ) — without this
		// wrapper the remove-item and update-cart buttons silently fail.
		$needs_wc_wrapper = in_array( $page_type, array( 'cart', 'empty-cart', 'checkout', 'my-account' ), true );
		if ( $needs_wc_wrapper ) {
			echo '<div class="woocommerce">';
		}

		// On checkout: clear stale cart notices (e.g. "X removed. Undo?",
		// "Cart updated.") so they don't leak onto the Place Order form.
		if ( 'checkout' === $page_type && function_exists( 'wc_clear_notices' ) ) {
			wc_clear_notices();
		}

		// Dynamic WooCommerce pages must NOT be cached because the same
		// template renders different content per endpoint / cart state / user.
		$allow_cache = ! $needs_wc_wrapper;

		// Get Elementor content.
		$content = $this->get_elementor_content( $this->current_template, $allow_cache );

		if ( $content ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $content;
		}

		// For My Account: render native WC endpoint content when the
		// Elementor template does not include a widget for the active endpoint.
		if ( 'my-account' === $page_type ) {
			$this->maybe_render_account_endpoint_fallback();
		}

		if ( $needs_wc_wrapper ) {
			echo '</div>';
		}

		$this->is_rendering = false;
	}

	/**
	 * Get Elementor content for a template.
	 *
	 * @since 2.0.0
	 *
	 * @param int  $template_id Template post ID.
	 * @param bool $use_cache   Whether to use the performance cache.
	 *                          Dynamic pages (cart, my-account, checkout) must
	 *                          pass false because their rendered HTML varies by
	 *                          endpoint, cart contents, or user state.
	 * @return string|false Rendered content or false.
	 */
	public function get_elementor_content( $template_id, $use_cache = true ) {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return false;
		}

		// Check if the template has Elementor data.
		$elementor_data = get_post_meta( $template_id, '_elementor_data', true );

		if ( empty( $elementor_data ) ) {
			return false;
		}

		// Try to get cached content if performance caching is enabled.
		// Dynamic WooCommerce pages are never cached because the same template
		// ID may produce different HTML depending on the active endpoint,
		// cart contents, or logged-in user.
		$performance = null;
		if ( $use_cache && class_exists( '\MPD\MagicalShopBuilder\Frontend\Performance' ) ) {
			$performance = new \MPD\MagicalShopBuilder\Frontend\Performance();
			$performance->init();

			$cached = $performance->get_cached_template( $template_id );
			if ( false !== $cached ) {
				return $cached;
			}
		}

		// Render with Elementor.
		$content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id );

		// Cache the rendered content.
		if ( $content && $performance ) {
			$performance->set_cached_template( $template_id, $content );
		}

		return $content;
	}

	/**
	 * Get current template ID.
	 *
	 * @since 2.0.0
	 *
	 * @return int|null
	 */
	public function get_current_template() {
		return $this->current_template;
	}

	/**
	 * Check if currently rendering a template.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_rendering() {
		return $this->is_rendering;
	}

	/**
	 * Render native WooCommerce endpoint content as fallback.
	 *
	 * Called on My Account pages when the active Elementor template does NOT
	 * contain a widget for the current endpoint. Without this, endpoints like
	 * /my-account/orders/ or /my-account/downloads/ would show a blank page
	 * because the Dashboard widget hides itself on endpoints and no Orders or
	 * Downloads widget exists in the template.
	 *
	 * The method:
	 * 1. Detects which WC endpoint the visitor is on.
	 * 2. Parses the template's Elementor data to see which MPD widgets it contains.
	 * 3. If the matching widget is present → does nothing (widget renders itself).
	 * 4. If the matching widget is absent → calls WC's native template function
	 *    so the visitor still sees useful content.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function maybe_render_account_endpoint_fallback() {
		if ( ! function_exists( 'is_wc_endpoint_url' ) || ! is_user_logged_in() ) {
			return;
		}

		// Parse the template's Elementor data to see which widgets it includes.
		$widget_types   = array();
		$elementor_data = get_post_meta( $this->current_template, '_elementor_data', true );

		if ( ! empty( $elementor_data ) ) {
			$data = json_decode( $elementor_data, true );
			if ( is_array( $data ) ) {
				$this->extract_widget_types( $data, $widget_types );
			}
		}

		// Map: WC endpoint slug => [ MPD widget name, WC template function ].
		$endpoint_widget_map = array(
			'orders'             => array( 'mpd-account-orders', 'woocommerce_account_orders' ),
			'view-order'         => array( 'mpd-account-orders', 'woocommerce_account_view_order' ),
			'downloads'          => array( 'mpd-account-downloads', 'woocommerce_account_downloads' ),
			'edit-address'       => array( 'mpd-account-addresses', 'woocommerce_account_edit_address' ),
			'edit-account'       => array( 'mpd-account-details', 'woocommerce_account_edit_account' ),
			'payment-methods'    => array( 'mpd-account-payment-methods', 'woocommerce_account_payment_methods' ),
			'add-payment-method' => array( 'mpd-account-payment-methods', 'woocommerce_account_add_payment_method' ),
			'lost-password'      => array( 'mpd-account-login', null ),
		);

		// Determine which endpoint we're currently on.
		$current_endpoint = '';
		$endpoint_value   = '';

		foreach ( array_keys( $endpoint_widget_map ) as $ep ) {
			if ( is_wc_endpoint_url( $ep ) ) {
				$current_endpoint = $ep;
				global $wp;
				$endpoint_value = isset( $wp->query_vars[ $ep ] ) ? $wp->query_vars[ $ep ] : '';
				break;
			}
		}

		// Dashboard (no specific endpoint): if the template lacks a
		// dashboard widget, render WC's native dashboard content.
		if ( empty( $current_endpoint ) ) {
			if ( ! isset( $widget_types['mpd-account-dashboard'] ) ) {
				$current_user = get_user_by( 'id', get_current_user_id() );
				if ( $current_user ) {
					echo '<div class="mpd-account-endpoint-fallback">';
					wc_get_template(
						'myaccount/dashboard.php',
						array(
							'current_user' => $current_user,
						)
					);
					echo '</div>';
				}
			}
			return;
		}

		$required_widget = $endpoint_widget_map[ $current_endpoint ][0];

		// Template HAS the matching widget → it renders itself; nothing to do.
		if ( isset( $widget_types[ $required_widget ] ) ) {
			return;
		}

		// Template does NOT have the widget → render WC's native content.
		$wc_function = $endpoint_widget_map[ $current_endpoint ][1];

		if ( $wc_function && function_exists( $wc_function ) ) {
			echo '<div class="mpd-account-endpoint-fallback">';
			call_user_func( $wc_function, $endpoint_value );
			echo '</div>';
		}
	}

	/**
	 * Manually render a specific template.
	 *
	 * @since 2.0.0
	 *
	 * @param int  $template_id Template ID.
	 * @param bool $echo        Whether to echo or return.
	 * @return string|void
	 */
	public function render( $template_id, $echo = true ) {
		$content = $this->get_elementor_content( $template_id );

		if ( ! $content ) {
			return '';
		}

		if ( $echo ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $content;
		} else {
			return $content;
		}
	}
}
