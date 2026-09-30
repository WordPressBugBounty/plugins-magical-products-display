<?php
/**
 * Off-Canvas Sliding Cart Drawer for Magical Shop Builder.
 *
 * Provides a modern slide-in cart drawer with real-time AJAX quantity updates,
 * item removal, free shipping progress bar, and checkout CTA.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

namespace MPD\MagicalShopBuilder\Frontend;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cart_Drawer
 *
 * Manages rendering and AJAX actions for the slide-in cart drawer.
 *
 * @since 2.1.0
 */
class Cart_Drawer {

	/**
	 * Single instance.
	 *
	 * @var Cart_Drawer|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @since 2.1.0
	 *
	 * @return Cart_Drawer
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
	 * @since 2.1.0
	 */
	private function __construct() {}

	/**
	 * Check if cart drawer is enabled.
	 *
	 * @since 2.1.0
	 *
	 * @return bool
	 */
	public static function is_enabled() {
		return (bool) apply_filters( 'mpd_enable_cart_drawer', true );
	}

	/**
	 * Check if auto open on add to cart is enabled by default.
	 *
	 * Individual widgets can override this via their `drawer_auto_open` setting.
	 *
	 * @since 2.1.0
	 *
	 * @return bool
	 */
	public static function is_auto_open() {
		return (bool) apply_filters( 'mpd_auto_open_cart_drawer', true );
	}

	/**
	 * Check if Pro features are active.
	 *
	 * @since 2.1.0
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
	 * Initialize cart drawer hooks.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	public function init() {
		// Only run if WooCommerce is active and feature is enabled.
		if ( ! class_exists( 'WooCommerce' ) || ! self::is_enabled() ) {
			return;
		}

		// Render drawer in footer on frontend.
		add_action( 'wp_footer', array( $this, 'render_drawer' ), 20 );

		// Hook into cart fragments to keep drawer updated on native WC AJAX events.
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'add_drawer_fragments' ), 30 );

		// AJAX endpoints for drawer quantity update and item removal.
		add_action( 'wp_ajax_mpd_drawer_update_quantity', array( $this, 'ajax_update_quantity' ) );
		add_action( 'wp_ajax_nopriv_mpd_drawer_update_quantity', array( $this, 'ajax_update_quantity' ) );
		add_action( 'wp_ajax_mpd_drawer_remove_item', array( $this, 'ajax_remove_item' ) );
		add_action( 'wp_ajax_nopriv_mpd_drawer_remove_item', array( $this, 'ajax_remove_item' ) );

		// AJAX endpoints for in-drawer coupon apply and remove (Pro).
		add_action( 'wp_ajax_mpd_drawer_apply_coupon', array( $this, 'ajax_apply_coupon' ) );
		add_action( 'wp_ajax_nopriv_mpd_drawer_apply_coupon', array( $this, 'ajax_apply_coupon' ) );
		add_action( 'wp_ajax_mpd_drawer_remove_coupon', array( $this, 'ajax_remove_coupon' ) );
		add_action( 'wp_ajax_nopriv_mpd_drawer_remove_coupon', array( $this, 'ajax_remove_coupon' ) );
	}

	/**
	 * Get the free shipping minimum amount.
	 *
	 * Checks active WooCommerce free shipping methods in zones, or fallback option.
	 *
	 * @since 2.1.0
	 *
	 * @return float Free shipping threshold or 0 if none.
	 */
	public static function get_free_shipping_threshold() {
		$min_amount = 0;

		if ( class_exists( '\WC_Shipping_Zones' ) ) {
			$zones = \WC_Shipping_Zones::get_zones();
			// Also check the default zone (Rest of the World) defensively.
			$default_zone = \WC_Shipping_Zones::get_zone( 0 );
			if ( $default_zone && method_exists( $default_zone, 'get_shipping_methods' ) ) {
				$zones[] = array(
					'zone_id'          => 0,
					'shipping_methods' => $default_zone->get_shipping_methods(),
				);
			}

			foreach ( $zones as $zone ) {
				$methods = isset( $zone['shipping_methods'] ) ? $zone['shipping_methods'] : array();
				foreach ( $methods as $method ) {
					if ( 'free_shipping' === $method->id && 'yes' === $method->enabled ) {
						$requires = $method->get_option( 'requires' );
						if ( in_array( $requires, array( 'min_amount', 'either', 'both' ), true ) ) {
							$amount = (float) $method->get_option( 'min_amount' );
							if ( $amount > 0 && ( 0 === $min_amount || $amount < $min_amount ) ) {
								$min_amount = $amount;
							}
						}
					}
				}
			}
		}

		// Fallback to option or sensible default (e.g. 100) if no zone threshold is configured.
		if ( $min_amount <= 0 ) {
			$min_amount = (float) get_option( 'mpd_free_shipping_threshold', 100 );
		}

		// Filter for developers or manual threshold settings.
		return (float) apply_filters( 'mpd_free_shipping_threshold', $min_amount );
	}

	/**
	 * Render the cart drawer markup in footer.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	public function render_drawer() {
		// Do not render on checkout or pay-for-order pages to avoid distractions.
		if ( function_exists( 'is_checkout' ) && is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
			return;
		}

		?>
		<div id="mpd-cart-drawer" class="mpd-cart-drawer-container" aria-hidden="true">
			<div class="mpd-cart-drawer-backdrop" data-mpd-cart-close></div>
			<div class="mpd-cart-drawer-panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Shopping Cart Drawer', 'magical-products-display' ); ?>">
				<div class="mpd-cart-drawer-header">
					<div class="mpd-cart-drawer-header-title">
						<span class="mpd-cart-drawer-icon"><i class="eicon-cart-medium" aria-hidden="true"></i></span>
						<span class="mpd-cart-drawer-heading"><?php esc_html_e( 'Your Cart', 'magical-products-display' ); ?></span>
						<span class="mpd-cart-drawer-count-badge">
							<?php echo is_object( WC()->cart ) ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?>
						</span>
					</div>
					<button type="button" class="mpd-cart-drawer-close" data-mpd-cart-close aria-label="<?php esc_attr_e( 'Close cart drawer', 'magical-products-display' ); ?>">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="mpd-cart-drawer-content-wrap">
					<?php $this->render_drawer_content(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render inner content of the cart drawer (items, totals, free shipping bar).
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	public function render_drawer_content() {
		if ( ! is_object( WC()->cart ) ) {
			return;
		}

		$cart        = WC()->cart;
		$cart_items  = $cart->get_cart();
		$cart_count  = $cart->get_cart_contents_count();
		$subtotal    = $cart->get_cart_subtotal();
		$threshold   = self::get_free_shipping_threshold();
		$cart_amount = (float) $cart->get_displayed_subtotal();

		$is_pro            = self::is_pro();
		$show_shipping_bar = apply_filters( 'mpd_drawer_show_shipping_bar', $is_pro );
		$show_coupon_box   = apply_filters( 'mpd_drawer_show_coupon_box', $is_pro && wc_coupons_enabled() );

		?>
		<div class="mpd-cart-drawer-content">

			<?php if ( $show_shipping_bar && $threshold > 0 ) : ?>
				<div class="mpd-drawer-shipping-bar-wrap">
					<?php
					$percent   = min( 100, ( $cart_amount / $threshold ) * 100 );
					$remaining = max( 0, $threshold - $cart_amount );
					?>
					<div class="mpd-drawer-shipping-bar-msg">
						<?php if ( $cart_amount >= $threshold ) : ?>
							<span class="mpd-shipping-unlocked">
								🎉 <?php esc_html_e( 'You have unlocked FREE shipping!', 'magical-products-display' ); ?>
							</span>
						<?php else : ?>
							<span class="mpd-shipping-needed">
								<?php
								printf(
									/* translators: %s is the remaining amount needed for free shipping */
									esc_html__( 'Add %s more for FREE shipping!', 'magical-products-display' ),
									'<strong>' . wp_kses_post( wc_price( $remaining ) ) . '</strong>'
								);
								?>
							</span>
						<?php endif; ?>
					</div>
					<div class="mpd-drawer-shipping-progress" role="progressbar" aria-valuenow="<?php echo esc_attr( round( $percent ) ); ?>" aria-valuemin="0" aria-valuemax="100">
						<div class="mpd-drawer-shipping-fill" style="width: <?php echo esc_attr( $percent ); ?>%;"></div>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( empty( $cart_items ) ) : ?>
				<div class="mpd-cart-drawer-empty">
					<div class="mpd-cart-drawer-empty-icon">
						<i class="eicon-cart-medium" aria-hidden="true"></i>
					</div>
					<p class="mpd-cart-drawer-empty-text"><?php esc_html_e( 'Your shopping cart is currently empty.', 'magical-products-display' ); ?></p>
					<?php
					$shop_url = wc_get_page_permalink( 'shop' );
					if ( $shop_url ) :
						?>
						<a href="<?php echo esc_url( $shop_url ); ?>" class="mpd-cart-drawer-btn mpd-btn-continue-shopping">
							<?php esc_html_e( 'Start Shopping', 'magical-products-display' ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<div class="mpd-cart-drawer-items">
					<?php
					foreach ( $cart_items as $cart_item_key => $cart_item ) :
						$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

						if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) {
							continue;
						}

						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
						$product_price     = apply_filters( 'woocommerce_cart_item_price', $cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						$item_quantity     = $cart_item['quantity'];
						$max_qty           = $_product->get_max_purchase_quantity();
						$min_qty           = $_product->get_min_purchase_quantity();
						?>
						<div class="mpd-cart-drawer-item" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
							<div class="mpd-cart-drawer-item-thumb">
								<?php if ( ! empty( $product_permalink ) ) : ?>
									<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $thumbnail ); ?></a>
								<?php else : ?>
									<?php echo wp_kses_post( $thumbnail ); ?>
								<?php endif; ?>
							</div>

							<div class="mpd-cart-drawer-item-details">
								<div class="mpd-cart-drawer-item-title-wrap">
									<?php if ( ! empty( $product_permalink ) ) : ?>
										<a href="<?php echo esc_url( $product_permalink ); ?>" class="mpd-cart-drawer-item-title"><?php echo wp_kses_post( $product_name ); ?></a>
									<?php else : ?>
										<span class="mpd-cart-drawer-item-title"><?php echo wp_kses_post( $product_name ); ?></span>
									<?php endif; ?>

									<button type="button" class="mpd-cart-drawer-item-remove" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Remove %s from cart', 'magical-products-display' ), wp_strip_all_tags( $product_name ) ) ); ?>">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>

								<?php
								// Variation data if variable product.
								if ( ! empty( $cart_item['variation'] ) ) {
									echo '<div class="mpd-cart-drawer-item-meta">' . wp_kses_post( wc_get_formatted_cart_item_data( $cart_item ) ) . '</div>';
								}
								?>

								<div class="mpd-cart-drawer-item-footer">
									<div class="mpd-cart-drawer-qty-spinner" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
										<button type="button" class="mpd-qty-btn mpd-qty-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'magical-products-display' ); ?>">&minus;</button>
										<input type="number" class="mpd-qty-input" value="<?php echo esc_attr( $item_quantity ); ?>" min="<?php echo esc_attr( $min_qty > 0 ? $min_qty : 1 ); ?>" max="<?php echo esc_attr( $max_qty > 0 ? $max_qty : '' ); ?>" step="1" aria-label="<?php esc_attr_e( 'Product quantity', 'magical-products-display' ); ?>" />
										<button type="button" class="mpd-qty-btn mpd-qty-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'magical-products-display' ); ?>">&plus;</button>
									</div>

									<div class="mpd-cart-drawer-item-price">
										<?php echo wp_kses_post( $product_price ); ?>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="mpd-cart-drawer-footer">
					<?php if ( $show_coupon_box ) : ?>
						<div class="mpd-cart-drawer-coupon-wrap">
							<div class="mpd-cart-drawer-coupon-form">
								<input type="text" class="mpd-cart-drawer-coupon-input" placeholder="<?php esc_attr_e( 'Coupon code', 'magical-products-display' ); ?>" aria-label="<?php esc_attr_e( 'Coupon code', 'magical-products-display' ); ?>" />
								<button type="button" class="mpd-cart-drawer-btn mpd-cart-drawer-apply-coupon">
									<span class="mpd-coupon-btn-text"><?php esc_html_e( 'Apply', 'magical-products-display' ); ?></span>
									<span class="mpd-coupon-spinner" style="display:none;"><i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i></span>
								</button>
							</div>
							<div class="mpd-cart-drawer-coupon-msg" style="display:none;"></div>
							<?php
							$applied_coupons = $cart->get_applied_coupons();
							if ( ! empty( $applied_coupons ) ) :
								?>
								<div class="mpd-cart-drawer-applied-coupons">
									<?php foreach ( $applied_coupons as $code ) : ?>
										<span class="mpd-cart-drawer-coupon-tag">
											<i class="eicon-tag" aria-hidden="true"></i> <?php echo esc_html( $code ); ?>
											<button type="button" class="mpd-cart-drawer-remove-coupon" data-coupon="<?php echo esc_attr( $code ); ?>" aria-label="<?php esc_attr_e( 'Remove coupon', 'magical-products-display' ); ?>">&times;</button>
										</span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="mpd-cart-drawer-subtotal-row">
						<span class="mpd-cart-drawer-subtotal-label"><?php esc_html_e( 'Subtotal:', 'magical-products-display' ); ?></span>
						<span class="mpd-cart-drawer-subtotal-val mpd-mini-cart-subtotal"><?php echo wp_kses_post( $subtotal ); ?></span>
					</div>

					<div class="mpd-cart-drawer-actions">
						<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="mpd-cart-drawer-btn mpd-btn-view-cart">
							<?php esc_html_e( 'View Cart', 'magical-products-display' ); ?>
						</a>
						<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="mpd-cart-drawer-btn mpd-btn-checkout">
							<?php esc_html_e( 'Checkout', 'magical-products-display' ); ?>
						</a>
					</div>
				</div>
			<?php endif; ?>

		</div>
		<?php
	}

	/**
	 * Append drawer fragment to WooCommerce cart fragments response.
	 *
	 * @since 2.1.0
	 *
	 * @param array $fragments Cart fragments.
	 * @return array Modified fragments.
	 */
	public function add_drawer_fragments( $fragments ) {
		ob_start();
		$this->render_drawer_content();
		$fragments['.mpd-cart-drawer-content-wrap'] = '<div class="mpd-cart-drawer-content-wrap">' . ob_get_clean() . '</div>';

		$count = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
		$fragments['.mpd-cart-drawer-count-badge'] = '<span class="mpd-cart-drawer-count-badge">' . esc_html( $count ) . '</span>';

		return $fragments;
	}

	/**
	 * AJAX handler: Update cart item quantity inside drawer.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	public function ajax_update_quantity() {
		$nonce_verified = check_ajax_referer( 'mpd_cart_drawer_nonce', 'nonce', false );

		if ( function_exists( 'wc_load_cart' ) && ( ! WC()->session || ! WC()->cart ) ) {
			wc_load_cart();
		}

		if ( ! $nonce_verified && ( ! is_object( WC()->session ) || ! WC()->session->has_session() ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Security check failed. Please refresh the page.', 'magical-products-display' ) ) );
		}

		if ( ! is_object( WC()->cart ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Cart unavailable.', 'magical-products-display' ) ) );
		}

		$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';
		$quantity      = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 0;

		if ( empty( $cart_item_key ) || ! isset( WC()->cart->get_cart()[ $cart_item_key ] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Item not found in cart.', 'magical-products-display' ) ) );
		}

		if ( $quantity <= 0 ) {
			WC()->cart->remove_cart_item( $cart_item_key );
		} else {
			WC()->cart->set_quantity( $cart_item_key, $quantity, true );
		}

		WC()->cart->calculate_totals();

		// Ensure cart data is persisted immediately in session.
		if ( is_object( WC()->session ) && method_exists( WC()->session, 'set' ) ) {
			WC()->session->set( 'cart', WC()->cart->get_cart_for_session() );
		}

		ob_start();
		$this->render_drawer_content();
		$drawer_html = ob_get_clean();

		$count = WC()->cart->get_cart_contents_count();

		wp_send_json_success( array(
			'count'       => $count,
			'subtotal'    => WC()->cart->get_cart_subtotal(),
			'drawer_html' => $drawer_html,
			'fragments'   => apply_filters( 'woocommerce_add_to_cart_fragments', array(
				'.mpd-cart-drawer-content-wrap' => '<div class="mpd-cart-drawer-content-wrap">' . $drawer_html . '</div>',
				'.mpd-cart-drawer-count-badge'  => '<span class="mpd-cart-drawer-count-badge mpd-mini-cart-counter">' . esc_html( $count ) . '</span>',
			) ),
			'cart_hash'   => WC()->cart->get_cart_hash(),
		) );
	}

	/**
	 * AJAX handler: Remove item from cart inside drawer.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	public function ajax_remove_item() {
		$nonce_verified = check_ajax_referer( 'mpd_cart_drawer_nonce', 'nonce', false );

		if ( function_exists( 'wc_load_cart' ) && ( ! WC()->session || ! WC()->cart ) ) {
			wc_load_cart();
		}

		if ( ! $nonce_verified && ( ! is_object( WC()->session ) || ! WC()->session->has_session() ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Security check failed. Please refresh the page.', 'magical-products-display' ) ) );
		}

		if ( ! is_object( WC()->cart ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Cart unavailable.', 'magical-products-display' ) ) );
		}

		$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';

		if ( empty( $cart_item_key ) || ! isset( WC()->cart->get_cart()[ $cart_item_key ] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Item not found in cart.', 'magical-products-display' ) ) );
		}

		WC()->cart->remove_cart_item( $cart_item_key );
		WC()->cart->calculate_totals();

		// Ensure cart data is persisted immediately in session.
		if ( is_object( WC()->session ) && method_exists( WC()->session, 'set' ) ) {
			WC()->session->set( 'cart', WC()->cart->get_cart_for_session() );
		}

		ob_start();
		$this->render_drawer_content();
		$drawer_html = ob_get_clean();

		$count = WC()->cart->get_cart_contents_count();

		wp_send_json_success( array(
			'count'       => $count,
			'subtotal'    => WC()->cart->get_cart_subtotal(),
			'drawer_html' => $drawer_html,
			'fragments'   => apply_filters( 'woocommerce_add_to_cart_fragments', array(
				'.mpd-cart-drawer-content-wrap' => '<div class="mpd-cart-drawer-content-wrap">' . $drawer_html . '</div>',
				'.mpd-cart-drawer-count-badge'  => '<span class="mpd-cart-drawer-count-badge">' . esc_html( $count ) . '</span>',
			) ),
			'cart_hash'   => WC()->cart->get_cart_hash(),
		) );
	}

	/**
	 * AJAX handler: Apply coupon code inside drawer (Pro).
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	public function ajax_apply_coupon() {
		check_ajax_referer( 'mpd_cart_drawer_nonce', 'nonce' );

		if ( function_exists( 'wc_load_cart' ) && function_exists( 'WC' ) && WC() && ! WC()->cart ) {
			wc_load_cart();
		}

		if ( ! is_object( WC()->cart ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Cart not found.', 'magical-products-display' ) ) );
			return;
		}

		$coupon_code = isset( $_POST['coupon_code'] ) ? sanitize_text_field( wp_unslash( $_POST['coupon_code'] ) ) : '';

		if ( empty( $coupon_code ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Please enter a coupon code.', 'magical-products-display' ) ) );
			return;
		}

		if ( WC()->cart->has_discount( $coupon_code ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Coupon code already applied!', 'magical-products-display' ) ) );
			return;
		}

		$result = WC()->cart->apply_coupon( $coupon_code );

		if ( $result ) {
			wc_clear_notices();
			WC()->cart->calculate_totals();

			if ( is_object( WC()->session ) && method_exists( WC()->session, 'set' ) ) {
				WC()->session->set( 'cart', WC()->cart->get_cart_for_session() );
			}

			ob_start();
			$this->render_drawer_content();
			$drawer_html = ob_get_clean();

			wp_send_json_success(
				array(
					'message'     => sprintf( esc_html__( 'Coupon "%s" applied!', 'magical-products-display' ), $coupon_code ),
					'drawer_html' => $drawer_html,
					'fragments'   => apply_filters(
						'woocommerce_add_to_cart_fragments',
						array(
							'.mpd-cart-drawer-content-wrap' => '<div class="mpd-cart-drawer-content-wrap">' . $drawer_html . '</div>',
							'.mpd-cart-drawer-count-badge'  => '<span class="mpd-cart-drawer-count-badge">' . esc_html( WC()->cart->get_cart_contents_count() ) . '</span>',
						)
					),
					'cart_hash'   => WC()->cart->get_cart_hash(),
				)
			);
		} else {
			$notices = wc_get_notices( 'error' );
			$error_message = ! empty( $notices ) ? wp_strip_all_tags( $notices[0]['notice'] ) : esc_html__( 'Invalid coupon code.', 'magical-products-display' );
			wc_clear_notices();
			wp_send_json_error( array( 'message' => $error_message ) );
		}
	}

	/**
	 * AJAX handler: Remove coupon code inside drawer (Pro).
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	public function ajax_remove_coupon() {
		check_ajax_referer( 'mpd_cart_drawer_nonce', 'nonce' );

		if ( function_exists( 'wc_load_cart' ) && function_exists( 'WC' ) && WC() && ! WC()->cart ) {
			wc_load_cart();
		}

		if ( ! is_object( WC()->cart ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Cart not found.', 'magical-products-display' ) ) );
			return;
		}

		$coupon_code = isset( $_POST['coupon_code'] ) ? sanitize_text_field( wp_unslash( $_POST['coupon_code'] ) ) : '';

		if ( empty( $coupon_code ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid coupon.', 'magical-products-display' ) ) );
			return;
		}

		$result = WC()->cart->remove_coupon( $coupon_code );

		if ( $result ) {
			wc_clear_notices();
			WC()->cart->calculate_totals();

			if ( is_object( WC()->session ) && method_exists( WC()->session, 'set' ) ) {
				WC()->session->set( 'cart', WC()->cart->get_cart_for_session() );
			}

			ob_start();
			$this->render_drawer_content();
			$drawer_html = ob_get_clean();

			wp_send_json_success(
				array(
					'message'     => sprintf( esc_html__( 'Coupon "%s" removed.', 'magical-products-display' ), $coupon_code ),
					'drawer_html' => $drawer_html,
					'fragments'   => apply_filters(
						'woocommerce_add_to_cart_fragments',
						array(
							'.mpd-cart-drawer-content-wrap' => '<div class="mpd-cart-drawer-content-wrap">' . $drawer_html . '</div>',
							'.mpd-cart-drawer-count-badge'  => '<span class="mpd-cart-drawer-count-badge">' . esc_html( WC()->cart->get_cart_contents_count() ) . '</span>',
						)
					),
					'cart_hash'   => WC()->cart->get_cart_hash(),
				)
			);
		} else {
			wp_send_json_error( array( 'message' => esc_html__( 'Unable to remove coupon.', 'magical-products-display' ) ) );
		}
	}
}
