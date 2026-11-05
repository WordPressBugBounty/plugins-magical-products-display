<?php
/**
 * Funnel & Direct Checkout Engine
 *
 * Handles direct landing page checkout initialization, skip-cart logic,
 * order bump toggling, and quick COD checkout field management.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.1
 */

namespace MPD\MagicalShopBuilder\Frontend;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Funnel_Checkout
 *
 * @since 2.2.0
 */
class Funnel_Checkout {

	/**
	 * Single instance of the class.
	 *
	 * @var Funnel_Checkout|null
	 */
	private static $instance = null;

	/**
	 * Active One-Page Checkout settings on current page.
	 *
	 * @var array|null
	 */
	private static $active_opc_settings = null;

	/**
	 * Get class instance.
	 *
	 * @return Funnel_Checkout
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Alias for get_instance().
	 *
	 * @return Funnel_Checkout
	 */
	public static function instance() {
		return self::get_instance();
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		// Direct Buy URL parameter support (?mpd_buy_now=123&qty=1).
		add_action( 'template_redirect', array( $this, 'handle_direct_buy_url' ), 10 );

		// AJAX: Toggle Order Bump.
		add_action( 'wp_ajax_mpd_toggle_order_bump', array( $this, 'ajax_toggle_order_bump' ) );
		add_action( 'wp_ajax_nopriv_mpd_toggle_order_bump', array( $this, 'ajax_toggle_order_bump' ) );

		// AJAX: Update OPC Product Quantity or Variation.
		add_action( 'wp_ajax_mpd_opc_update_product', array( $this, 'ajax_update_product' ) );
		add_action( 'wp_ajax_nopriv_mpd_opc_update_product', array( $this, 'ajax_update_product' ) );

		// Re-validate bump prices against server-side OPC settings before totals.
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'validate_bump_prices' ), 5, 1 );

		// Quick COD Mode Field Filtering.
		add_filter( 'woocommerce_checkout_fields', array( $this, 'filter_quick_cod_fields' ), 999 );

		// Custom Thank You page redirect (Pro).
		add_filter( 'woocommerce_get_checkout_order_received_url', array( $this, 'filter_thankyou_redirect' ), 10, 2 );

		// Custom Place Order button text filter.
		add_filter( 'woocommerce_order_button_text', array( $this, 'filter_order_button_text' ), 99 );

		// Privacy Policy & Terms and Conditions customizations.
		add_action( 'woocommerce_checkout_before_terms_and_conditions', array( $this, 'before_terms_and_conditions' ), 10 );
		add_filter( 'woocommerce_get_privacy_policy_text', array( $this, 'filter_privacy_policy_text' ), 99, 2 );
		add_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', array( $this, 'filter_terms_checkbox_text' ), 99 );
		add_filter( 'woocommerce_terms_to_conditions_page_id', array( $this, 'filter_terms_page_id' ), 99 );
		add_filter( 'woocommerce_checkout_show_terms', array( $this, 'filter_show_terms_wrapper' ), 99 );
		add_filter( 'woocommerce_terms_is_checked_default', array( $this, 'filter_terms_is_checked_default' ), 99 );
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'filter_terms_validation' ), 99, 2 );

		// Apply custom bump price if active in session.
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'apply_custom_bump_prices' ), 10, 1 );
	}

	/**
	 * Check if Pro features are active.
	 *
	 * @return bool
	 */
	public static function is_pro() {
		if ( class_exists( '\MPD\MagicalShopBuilder\Core\Pro' ) ) {
			return \MPD\MagicalShopBuilder\Core\Pro::is_active();
		}
		$pro_plugin_slug     = 'magical-shop-builder-pro/magical-shop-builder-pro.php';
		$old_pro_plugin_slug = 'magical-products-display-pro/magical-products-display-pro.php';
		$active_plugins      = (array) apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) );

		$has_pro_plugin = in_array( $pro_plugin_slug, $active_plugins, true ) || in_array( $old_pro_plugin_slug, $active_plugins, true );
		$has_valid_lic  = ( 'yes' === get_option( 'mgppro_has_valid_lic', 'no' ) );

		return (bool) apply_filters( 'mpd_is_pro_active', $has_pro_plugin && $has_valid_lic );
	}

	/**
	 * Set active OPC settings for current request.
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	public static function set_active_opc_settings( $settings ) {
		self::$active_opc_settings = $settings;

		if ( function_exists( 'WC' ) && is_object( WC()->session ) ) {
			if ( ! WC()->session->has_session() && ! headers_sent() && method_exists( WC()->session, 'set_customer_session_cookie' ) ) {
				WC()->session->set_customer_session_cookie( true );
			}
			if ( method_exists( WC()->session, 'set' ) && WC()->session->has_session() ) {
				WC()->session->set( 'mpd_opc_settings', $settings );
			}
		}
	}

	/**
	 * Get active OPC settings.
	 *
	 * @return array|null
	 */
	public static function get_active_opc_settings() {
		if ( null !== self::$active_opc_settings ) {
			return self::$active_opc_settings;
		}

		if ( function_exists( 'WC' ) && is_object( WC()->session ) && method_exists( WC()->session, 'get' ) ) {
			$stored = WC()->session->get( 'mpd_opc_settings' );
			if ( ! empty( $stored ) && is_array( $stored ) ) {
				self::$active_opc_settings = $stored;
				return self::$active_opc_settings;
			}
		}

		return null;
	}

	/**
	 * Resolve the server-side bump price for a given bump product.
	 *
	 * The client-supplied custom_price is never trusted directly. We
	 * look up the price from the active OPC widget settings rendered on
	 * the current page (stored server-side via set_active_opc_settings()).
	 * If no OPC settings are active, we also accept the bump product's
	 * regular WooCommerce price (no custom discount). Returns null when
	 * no reliable price can be determined, meaning the regular product
	 * price applies.
	 *
	 * @param int $bump_id Bump product ID.
	 * @param \WC_Product $product Bump product object.
	 * @return float|null Server-verified bump price or null for regular price.
	 */
	private static function resolve_bump_price( $bump_id, $product ) {
		$settings = self::get_active_opc_settings();

		if ( is_array( $settings ) && ! empty( $settings['bump_product_id'] ) && (int) $settings['bump_product_id'] === (int) $bump_id ) {
			if ( ! empty( $settings['bump_custom_price'] ) ) {
				$price = (float) $settings['bump_custom_price'];
				if ( $price >= 0 ) {
					return $price;
				}
			}
			return null;
		}

		// No active OPC context for this bump product — fall back to the
		// product's regular price. Any client-supplied custom price is ignored.
		$regular = $product ? (float) $product->get_price() : 0;
		return $regular > 0 ? $regular : null;
	}

	/**
	 * Handle direct buy now URL parameter.
	 *
	 * E.g., https://example.com/checkout/?mpd_buy_now=42&qty=2
	 *
	 * @return void
	 */
	public function handle_direct_buy_url() {
		if ( ! isset( $_GET['mpd_buy_now'] ) || ! function_exists( 'WC' ) || ! WC()->cart ) {
			return;
		}

		$product_id = absint( $_GET['mpd_buy_now'] );
		if ( ! $product_id ) {
			return;
		}

		$product = wc_get_product( $product_id );
		if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
			return;
		}

		$qty          = isset( $_GET['qty'] ) ? max( 1, absint( $_GET['qty'] ) ) : 1;
		$variation_id = isset( $_GET['variation_id'] ) ? absint( $_GET['variation_id'] ) : 0;
		$clear_cart   = ! isset( $_GET['keep_cart'] );

		// Validate variation belongs to this parent product.
		if ( $variation_id > 0 ) {
			$variation = wc_get_product( $variation_id );
			if ( ! $variation || ! $variation instanceof \WC_Product_Variation || (int) $variation->get_parent_id() !== (int) $product_id || ! $variation->is_purchasable() || ! $variation->is_in_stock() ) {
				return;
			}
		}

		if ( $clear_cart ) {
			WC()->cart->empty_cart();
		}

		if ( $variation_id > 0 ) {
			WC()->cart->add_to_cart( $product_id, $qty, $variation_id );
		} else {
			WC()->cart->add_to_cart( $product_id, $qty );
		}

		// If a specific redirect target is provided, redirect there; otherwise reload current page without parameter.
		$redirect_url = remove_query_arg( array( 'mpd_buy_now', 'qty', 'variation_id', 'keep_cart' ) );
		wp_safe_redirect( $redirect_url );
		exit;
	}

	/**
	 * AJAX handler: Toggle Order Bump in Cart.
	 *
	 * @return void
	 */
	public function ajax_toggle_order_bump() {
		check_ajax_referer( 'mpd_funnel_nonce', 'nonce' );

		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			wp_send_json_error( array( 'message' => esc_html__( 'WooCommerce cart unavailable.', 'magical-products-display' ) ) );
		}

		$bump_id      = isset( $_POST['bump_id'] ) ? absint( $_POST['bump_id'] ) : 0;
		$action_type  = isset( $_POST['action_type'] ) ? sanitize_key( $_POST['action_type'] ) : 'add';

		if ( ! $bump_id ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid offer product.', 'magical-products-display' ) ) );
		}

		$product = wc_get_product( $bump_id );
		if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Offer product is currently unavailable.', 'magical-products-display' ) ) );
		}

		// Resolve price server-side from OPC widget settings. Client
		// supplied custom_price is intentionally ignored to prevent
		// price tampering (e.g. adding a bump product at $0.01).
		$custom_price = self::resolve_bump_price( $bump_id, $product );

		// Find if bump item is already in cart.
		$existing_cart_key = '';
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			if ( (int) $cart_item['product_id'] === $bump_id || ( ! empty( $cart_item['variation_id'] ) && (int) $cart_item['variation_id'] === $bump_id ) ) {
				$existing_cart_key = $cart_item_key;
				break;
			}
		}

		if ( 'remove' === $action_type || ( 'toggle' === $action_type && $existing_cart_key ) ) {
			if ( $existing_cart_key ) {
				WC()->cart->remove_cart_item( $existing_cart_key );
			}
			$is_added = false;
		} else {
			// Add to cart with custom cart item data if custom price is set.
			$cart_item_data = array( 'mpd_is_order_bump' => true );
			if ( null !== $custom_price && $custom_price >= 0 ) {
				$cart_item_data['mpd_bump_custom_price'] = $custom_price;
			}

			if ( ! $existing_cart_key ) {
				WC()->cart->add_to_cart( $bump_id, 1, 0, array(), $cart_item_data );
			}
			$is_added = true;

			// Pro: Replace Primary Cart Product (Upsell Upgrade).
			$bump_action_mode = isset( $_POST['bump_action_mode'] ) ? sanitize_key( $_POST['bump_action_mode'] ) : 'add';
			if ( self::is_pro() && 'replace' === $bump_action_mode ) {
				foreach ( WC()->cart->get_cart() as $c_key => $c_item ) {
					if ( (int) $c_item['product_id'] !== $bump_id ) {
						WC()->cart->remove_cart_item( $c_key );
					}
				}
			}
		}

		WC()->cart->calculate_totals();

		// Save session.
		if ( is_object( WC()->session ) && method_exists( WC()->session, 'set' ) ) {
			WC()->session->set( 'cart', WC()->cart->get_cart_for_session() );
		}

		wp_send_json_success( array(
			'is_added'    => $is_added,
			'cart_count'  => WC()->cart->get_cart_contents_count(),
			'subtotal'    => WC()->cart->get_cart_subtotal(),
			'total'       => WC()->cart->get_total(),
			'cart_hash'   => WC()->cart->get_cart_hash(),
			'fragments'   => apply_filters( 'woocommerce_add_to_cart_fragments', array() ),
		) );
	}

	/**
	 * AJAX handler: Update One-Page Checkout Product (Quantity or Variation).
	 *
	 * @return void
	 */
	public function ajax_update_product() {
		check_ajax_referer( 'mpd_funnel_nonce', 'nonce' );

		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			wp_send_json_error( array( 'message' => esc_html__( 'WooCommerce cart unavailable.', 'magical-products-display' ) ) );
		}

		$product_id   = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
		$quantity     = isset( $_POST['quantity'] ) ? max( 1, absint( $_POST['quantity'] ) ) : 1;
		$clear_cart   = isset( $_POST['clear_cart'] ) && 'yes' === $_POST['clear_cart'];

		if ( ! $product_id ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid product.', 'magical-products-display' ) ) );
		}

		$product = wc_get_product( $variation_id > 0 ? $variation_id : $product_id );
		if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Product is currently unavailable.', 'magical-products-display' ) ) );
		}

		// Validate variation belongs to this parent product.
		if ( $variation_id > 0 ) {
			if ( ! $product instanceof \WC_Product_Variation || (int) $product->get_parent_id() !== (int) $product_id ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Product is currently unavailable.', 'magical-products-display' ) ) );
			}
		}

		// Keep any existing order bump products if clearing cart.
		$bump_items = array();
		if ( $clear_cart ) {
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				if ( ! empty( $cart_item['mpd_is_order_bump'] ) ) {
					$bump_items[] = array(
						'product_id'   => $cart_item['product_id'],
						'quantity'     => $cart_item['quantity'],
						'variation_id' => $cart_item['variation_id'] ?? 0,
						'data'         => $cart_item,
					);
				}
			}
			WC()->cart->empty_cart();

			// Add fresh primary product.
			if ( $variation_id > 0 ) {
				WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );
			} else {
				WC()->cart->add_to_cart( $product_id, $quantity );
			}

			// Re-add preserved order bump items.
			foreach ( $bump_items as $b_item ) {
				$b_data = array( 'mpd_is_order_bump' => true );
				if ( isset( $b_item['data']['mpd_bump_custom_price'] ) ) {
					$b_data['mpd_bump_custom_price'] = $b_item['data']['mpd_bump_custom_price'];
				}
				WC()->cart->add_to_cart( $b_item['product_id'], $b_item['quantity'], $b_item['variation_id'], array(), $b_data );
			}
		} else {
			// Find existing primary product in cart.
			$existing_cart_key = '';
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				if ( ! empty( $cart_item['mpd_is_order_bump'] ) ) {
					continue;
				}
				$matches_var  = ( $variation_id > 0 && isset( $cart_item['variation_id'] ) && (int) $cart_item['variation_id'] === $variation_id );
				$matches_prod = ( 0 === $variation_id && (int) $cart_item['product_id'] === $product_id );
				if ( $matches_var || $matches_prod ) {
					$existing_cart_key = $cart_item_key;
					break;
				}
			}

			if ( $existing_cart_key ) {
				// Set exact quantity.
				WC()->cart->set_quantity( $existing_cart_key, $quantity, true );
			} else {
				if ( $variation_id > 0 ) {
					WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );
				} else {
					WC()->cart->add_to_cart( $product_id, $quantity );
				}
			}
		}

		WC()->cart->calculate_totals();

		// Save session.
		if ( is_object( WC()->session ) && method_exists( WC()->session, 'set' ) ) {
			WC()->session->set( 'cart', WC()->cart->get_cart_for_session() );
		}

		wp_send_json_success( array(
			'cart_count' => WC()->cart->get_cart_contents_count(),
			'subtotal'   => WC()->cart->get_cart_subtotal(),
			'total'      => WC()->cart->get_total(),
			'cart_hash'  => WC()->cart->get_cart_hash(),
			'fragments'  => apply_filters( 'woocommerce_add_to_cart_fragments', array() ),
		) );
	}

	/**
	 * Apply custom order bump prices on cart totals calculation.
	 *
	 * @param \WC_Cart $cart_obj Cart object.
	 * @return void
	 */
	public function apply_custom_bump_prices( $cart_obj ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) && ! ( defined( 'WP_CLI' ) || 'cli' === php_sapi_name() ) ) {
			return;
		}

		foreach ( $cart_obj->get_cart() as $cart_item ) {
			if ( ! empty( $cart_item['mpd_is_order_bump'] ) && isset( $cart_item['mpd_bump_custom_price'] ) ) {
				$cart_item['data']->set_price( (float) $cart_item['mpd_bump_custom_price'] );
			}
		}
	}

	/**
	 * Re-validate stored bump prices against server-side OPC settings.
	 *
	 * Defense-in-depth: cart item data can be tampered with via session
	 * manipulation or stale client-set values. On every totals
	 * calculation, overwrite any stored bump price that does not match
	 * the price resolved from the active OPC widget settings. If there
	 * is no active OPC context, reset to the product's regular price.
	 *
	 * @param \WC_Cart $cart_obj Cart object.
	 * @return void
	 */
	public function validate_bump_prices( $cart_obj ) {
		if ( ! $cart_obj || ! method_exists( $cart_obj, 'get_cart' ) ) {
			return;
		}

		$settings = self::get_active_opc_settings();
		$has_opc  = is_array( $settings ) && ! empty( $settings['bump_product_id'] );

		foreach ( $cart_obj->get_cart() as $cart_item_key => $cart_item ) {
			if ( empty( $cart_item['mpd_is_order_bump'] ) ) {
				continue;
			}

			$bump_id = (int) ( $cart_item['product_id'] ?? 0 );
			if ( ! $bump_id ) {
				continue;
			}

			$product = isset( $cart_item['data'] ) && is_object( $cart_item['data'] ) ? $cart_item['data'] : wc_get_product( $bump_id );
			if ( ! $product ) {
				continue;
			}

			if ( $has_opc && (int) $settings['bump_product_id'] === $bump_id ) {
				$verified = self::resolve_bump_price( $bump_id, $product );
				// Store verified price back into cart item data via filter-free update.
				if ( null !== $verified ) {
					$cart_obj->cart_contents[ $cart_item_key ]['mpd_bump_custom_price'] = $verified;
				} else {
					unset( $cart_obj->cart_contents[ $cart_item_key ]['mpd_bump_custom_price'] );
				}
			} elseif ( ! $has_opc && isset( $cart_item['mpd_bump_custom_price'] ) ) {
				// No OPC context — a stored custom price cannot be trusted.
				// Keep the cart item but drop the custom price so regular
				// product pricing applies on this calculation.
				$regular = (float) $product->get_price();
				if ( $regular > 0 ) {
					$cart_obj->cart_contents[ $cart_item_key ]['mpd_bump_custom_price'] = $regular;
				} else {
					unset( $cart_obj->cart_contents[ $cart_item_key ]['mpd_bump_custom_price'] );
				}
			}
		}
	}

	/**
	 * Filter checkout fields for Quick COD / Minimal Checkout Mode.
	 *
	 * In Quick Mode, only Name, Phone, and Delivery Address are presented,
	 * drastically reducing form abandonment.
	 *
	 * @param array $fields WooCommerce checkout fields.
	 * @return array Modified fields.
	 */
	public function filter_quick_cod_fields( $fields ) {
		$settings = self::get_active_opc_settings();

		// Pro Field Manager for standard One-Page Checkout.
		if ( self::is_pro() && $settings ) {
			if ( ! empty( $settings['hide_company_field'] ) && 'yes' === $settings['hide_company_field'] && isset( $fields['billing']['billing_company'] ) ) {
				$fields['billing']['billing_company']['required'] = false;
				unset( $fields['billing']['billing_company'] );
			}
			if ( ! empty( $settings['hide_address_2_field'] ) && 'yes' === $settings['hide_address_2_field'] && isset( $fields['billing']['billing_address_2'] ) ) {
				$fields['billing']['billing_address_2']['required'] = false;
				unset( $fields['billing']['billing_address_2'] );
			}
			if ( ! empty( $settings['hide_postcode_field'] ) && 'yes' === $settings['hide_postcode_field'] && isset( $fields['billing']['billing_postcode'] ) ) {
				$fields['billing']['billing_postcode']['required'] = false;
				unset( $fields['billing']['billing_postcode'] );
			}
		}

		// Only apply quick cod replacements when quick mode is active on this page.
		if ( ! $settings || empty( $settings['checkout_form_mode'] ) || 'quick_cod' !== $settings['checkout_form_mode'] ) {
			return $fields;
		}

		// Keep only essentials: Full Name, Phone, Address 1, City.
		if ( isset( $fields['billing'] ) ) {
			$allowed = array( 'billing_first_name', 'billing_phone', 'billing_address_1', 'billing_city' );

			// Modify First Name to act as Full Name.
			if ( isset( $fields['billing']['billing_first_name'] ) ) {
				$fields['billing']['billing_first_name']['label']       = ! empty( $settings['quick_name_label'] ) ? $settings['quick_name_label'] : esc_html__( 'Full Name', 'magical-products-display' );
				$fields['billing']['billing_first_name']['placeholder'] = ! empty( $settings['quick_name_placeholder'] ) ? $settings['quick_name_placeholder'] : esc_html__( 'Your full name', 'magical-products-display' );
				$fields['billing']['billing_first_name']['class']       = array( 'form-row-wide' );
			}

			if ( isset( $fields['billing']['billing_phone'] ) ) {
				$fields['billing']['billing_phone']['label']       = ! empty( $settings['quick_phone_label'] ) ? $settings['quick_phone_label'] : esc_html__( 'Mobile Number', 'magical-products-display' );
				$fields['billing']['billing_phone']['placeholder'] = ! empty( $settings['quick_phone_placeholder'] ) ? $settings['quick_phone_placeholder'] : esc_html__( 'e.g. 01700000000', 'magical-products-display' );
				$fields['billing']['billing_phone']['class']       = array( 'form-row-wide' );
			}

			if ( isset( $fields['billing']['billing_address_1'] ) ) {
				$fields['billing']['billing_address_1']['label']       = ! empty( $settings['quick_address_label'] ) ? $settings['quick_address_label'] : esc_html__( 'Delivery Address', 'magical-products-display' );
				$fields['billing']['billing_address_1']['placeholder'] = ! empty( $settings['quick_address_placeholder'] ) ? $settings['quick_address_placeholder'] : esc_html__( 'House / Road / Area details', 'magical-products-display' );
				$fields['billing']['billing_address_1']['class']       = array( 'form-row-wide' );
			}

			// Remove all other non-essential billing fields in quick mode.
			foreach ( $fields['billing'] as $key => $field_config ) {
				if ( ! in_array( $key, $allowed, true ) ) {
					// Make them non-required so WooCommerce validation passes.
					$fields['billing'][ $key ]['required'] = false;
					// Unset so they don't render.
					unset( $fields['billing'][ $key ] );
				}
			}
		}

		// Disable separate shipping fields in quick mode.
		if ( isset( $fields['shipping'] ) ) {
			foreach ( $fields['shipping'] as $key => $val ) {
				$fields['shipping'][ $key ]['required'] = false;
				unset( $fields['shipping'][ $key ] );
			}
		}

		return $fields;
	}

	/**
	 * Custom Thank You Page Redirect (Pro).
	 *
	 * @param string    $return_url Default thank you page URL.
	 * @param \WC_Order $order      Order object.
	 * @return string Modified return URL.
	 */
	public function filter_thankyou_redirect( $return_url, $order ) {
		if ( self::is_pro() && self::$active_opc_settings ) {
			$settings = self::$active_opc_settings;
			if ( ! empty( $settings['enable_custom_thankyou'] ) && 'yes' === $settings['enable_custom_thankyou'] ) {
				if ( ! empty( $settings['custom_thankyou_url']['url'] ) ) {
					return esc_url( $settings['custom_thankyou_url']['url'] );
				}
			}
		}
		return $return_url;
	}

	/**
	 * Custom Place Order button text.
	 *
	 * @param string $text Default button text.
	 * @return string Modified button text.
	 */
	public function filter_order_button_text( $text ) {
		$settings = self::get_active_opc_settings();
		if ( $settings && ! empty( $settings['place_order_text'] ) ) {
			return esc_html( $settings['place_order_text'] );
		}
		return $text;
	}

	/**
	 * Hook before terms and conditions to remove privacy text action if disabled (Pro).
	 *
	 * @return void
	 */
	public function before_terms_and_conditions() {
		if ( ! self::is_pro() ) {
			return;
		}

		$settings = self::get_active_opc_settings();
		if ( $settings && isset( $settings['show_privacy_policy'] ) && 'no' === $settings['show_privacy_policy'] ) {
			remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
		}
	}

	/**
	 * Custom Privacy Policy text or hide (Pro).
	 *
	 * @param string $text Default policy text.
	 * @param string $type Policy type.
	 * @return string Modified policy text.
	 */
	public function filter_privacy_policy_text( $text, $type = '' ) {
		if ( 'checkout' !== $type || ! self::is_pro() ) {
			return $text;
		}

		$settings = self::get_active_opc_settings();
		if ( ! $settings ) {
			return $text;
		}

		if ( isset( $settings['show_privacy_policy'] ) && 'no' === $settings['show_privacy_policy'] ) {
			return '';
		}

		if ( ! empty( $settings['custom_privacy_policy_text'] ) ) {
			return $settings['custom_privacy_policy_text'];
		}

		return $text;
	}

	/**
	 * Custom Terms & Conditions Checkbox text or hide (Pro).
	 *
	 * @param string $text Default terms checkbox text.
	 * @return string Modified terms checkbox text.
	 */
	public function filter_terms_checkbox_text( $text ) {
		if ( ! self::is_pro() ) {
			return $text;
		}

		$settings = self::get_active_opc_settings();
		if ( ! $settings ) {
			return $text;
		}

		if ( isset( $settings['show_terms_checkbox'] ) && 'no' === $settings['show_terms_checkbox'] ) {
			return '';
		}

		if ( ! empty( $settings['custom_terms_text'] ) ) {
			return $settings['custom_terms_text'];
		}

		return $text;
	}

	/**
	 * Filter terms page ID so wc_terms_and_conditions_checkbox_enabled() returns false when disabled (Pro).
	 *
	 * @param int $page_id Terms page ID.
	 * @return int Modified page ID.
	 */
	public function filter_terms_page_id( $page_id ) {
		if ( ! self::is_pro() ) {
			return $page_id;
		}

		$settings = self::get_active_opc_settings();
		if ( ! $settings ) {
			return $page_id;
		}

		if ( isset( $settings['show_terms_checkbox'] ) && 'no' === $settings['show_terms_checkbox'] ) {
			return 0;
		}

		if ( ( ! isset( $settings['show_terms_checkbox'] ) || 'yes' === $settings['show_terms_checkbox'] ) && ! $page_id ) {
			$fallback = function_exists( 'wc_privacy_policy_page_id' ) ? wc_privacy_policy_page_id() : 0;
			return $fallback ? $fallback : ( get_the_ID() ? get_the_ID() : 1 );
		}

		return $page_id;
	}

	/**
	 * Filter whether to show the terms and conditions wrapper (Pro).
	 *
	 * @param bool $show Default show value.
	 * @return bool Modified show value.
	 */
	public function filter_show_terms_wrapper( $show ) {
		if ( ! self::is_pro() ) {
			return $show;
		}

		$settings = self::get_active_opc_settings();
		if ( ! $settings ) {
			return $show;
		}

		$hide_privacy = isset( $settings['show_privacy_policy'] ) && 'no' === $settings['show_privacy_policy'];
		$hide_terms   = isset( $settings['show_terms_checkbox'] ) && 'no' === $settings['show_terms_checkbox'];

		if ( $hide_privacy && $hide_terms ) {
			return false;
		}

		return $show;
	}

	/**
	 * Filter terms checkbox default checked state (Pro).
	 *
	 * @param bool $is_checked Default state.
	 * @return bool Modified checked state.
	 */
	public function filter_terms_is_checked_default( $is_checked ) {
		if ( ! self::is_pro() ) {
			return $is_checked;
		}

		$settings = self::get_active_opc_settings();
		if ( $settings && ! empty( $settings['terms_checked_by_default'] ) && 'yes' === $settings['terms_checked_by_default'] ) {
			return true;
		}
		return $is_checked;
	}

	/**
	 * Filter terms validation on checkout process (Pro).
	 *
	 * @param array     $data   Posted checkout data.
	 * @param \WP_Error $errors Validation errors object.
	 * @return void
	 */
	public function filter_terms_validation( $data, $errors ) {
		if ( ! self::is_pro() ) {
			return;
		}

		$settings = self::get_active_opc_settings();
		if ( ! $settings ) {
			return;
		}

		$hide_terms   = isset( $settings['show_terms_checkbox'] ) && 'no' === $settings['show_terms_checkbox'];
		$not_required = isset( $settings['terms_checkbox_required'] ) && 'no' === $settings['terms_checkbox_required'];

		if ( ( $hide_terms || $not_required ) && is_wp_error( $errors ) ) {
			$errors->remove( 'terms' );
		}
	}
}

// Backwards-compatible initializer: get_instance() is a singleton, so
// calling it here (on woocommerce_init) plus explicitly from the main
// plugin's init_funnel_checkout() does not double-register hooks.
add_action( 'woocommerce_init', array( __NAMESPACE__ . '\\Funnel_Checkout', 'get_instance' ) );
