<?php
/**
 * WooCommerce Functions
 *
 * WooCommerce specific helper functions.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get current WooCommerce page type.
 *
 * @since 2.0.0
 *
 * @return string The page type or empty string.
 */
if ( ! function_exists( 'mpd_get_wc_page_type' ) ) {
function mpd_get_wc_page_type() {
	if ( ! function_exists( 'is_woocommerce' ) ) {
		return '';
	}

	if ( is_product() ) {
		return 'single-product';
	}

	if ( is_shop() ) {
		return 'shop';
	}

	if ( is_product_category() ) {
		return 'product-category';
	}

	if ( is_product_tag() ) {
		return 'product-tag';
	}

	if ( is_cart() ) {
		return 'cart';
	}

	if ( is_checkout() && ! is_order_received_page() ) {
		return 'checkout';
	}

	if ( is_order_received_page() ) {
		return 'thankyou';
	}

	if ( is_account_page() ) {
		return 'my-account';
	}

	if ( is_woocommerce() ) {
		return 'woocommerce';
	}

	return '';
}
}

/**
 * Check if cart is empty.
 *
 * @since 2.0.0
 *
 * @return bool Whether cart is empty.
 */
if ( ! function_exists( 'mpd_is_cart_empty' ) ) {
function mpd_is_cart_empty() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return true;
	}

	return WC()->cart->is_empty();
}
}

/**
 * Get cart item count.
 *
 * @since 2.0.0
 *
 * @return int Cart item count.
 */
if ( ! function_exists( 'mpd_get_cart_count' ) ) {
function mpd_get_cart_count() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return 0;
	}

	return WC()->cart->get_cart_contents_count();
}
}

/**
 * Get cart total.
 *
 * @since 2.0.0
 *
 * @param bool $formatted Whether to return formatted price.
 * @return string|float Cart total.
 */
if ( ! function_exists( 'mpd_get_cart_total' ) ) {
function mpd_get_cart_total( $formatted = true ) {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $formatted ? '$0.00' : 0;
	}

	$total = WC()->cart->get_cart_contents_total();

	return $formatted ? wc_price( $total ) : $total;
}
}

/**
 * Get mini cart HTML.
 *
 * @since 2.0.0
 *
 * @return string Mini cart HTML.
 */
if ( ! function_exists( 'mpd_get_mini_cart' ) ) {
function mpd_get_mini_cart() {
	ob_start();

	if ( function_exists( 'woocommerce_mini_cart' ) ) {
		woocommerce_mini_cart();
	}

	return ob_get_clean();
}
}

/**
 * Get product by ID.
 *
 * @since 2.0.0
 *
 * @param int $product_id Product ID.
 * @return \WC_Product|false Product object or false.
 */
if ( ! function_exists( 'mpd_get_product' ) ) {
function mpd_get_product( $product_id ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return false;
	}

	return wc_get_product( $product_id );
}
}

/**
 * Get product categories.
 *
 * @since 2.0.0
 *
 * @param array $args Query arguments.
 * @return array Array of terms.
 */
if ( ! function_exists( 'mpd_get_product_categories' ) ) {
function mpd_get_product_categories( $args = array() ) {
	$defaults = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	);

	$args  = wp_parse_args( $args, $defaults );
	$terms = get_terms( $args );

	if ( is_wp_error( $terms ) ) {
		return array();
	}

	return $terms;
}
}

/**
 * Get product tags.
 *
 * @since 2.0.0
 *
 * @param array $args Query arguments.
 * @return array Array of terms.
 */
if ( ! function_exists( 'mpd_get_product_tags' ) ) {
function mpd_get_product_tags( $args = array() ) {
	$defaults = array(
		'taxonomy'   => 'product_tag',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	);

	$args  = wp_parse_args( $args, $defaults );
	$terms = get_terms( $args );

	if ( is_wp_error( $terms ) ) {
		return array();
	}

	return $terms;
}
}

/**
 * Get products on sale IDs.
 *
 * @since 2.0.0
 *
 * @return array Array of product IDs on sale.
 */
if ( ! function_exists( 'mpd_get_sale_product_ids' ) ) {
function mpd_get_sale_product_ids() {
	if ( ! function_exists( 'wc_get_product_ids_on_sale' ) ) {
		return array();
	}

	return wc_get_product_ids_on_sale();
}
}

/**
 * Get featured product IDs.
 *
 * @since 2.0.0
 *
 * @param int $limit Number of products to return.
 * @return array Array of featured product IDs.
 */
if ( ! function_exists( 'mpd_get_featured_product_ids' ) ) {
function mpd_get_featured_product_ids( $limit = -1 ) {
	$query_args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'fields'         => 'ids',
		'tax_query'      => array(
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
			),
		),
	);

	$query = new WP_Query( $query_args );

	return $query->posts;
}
}

/**
 * Get best selling product IDs.
 *
 * @since 2.0.0
 *
 * @param int $limit Number of products to return.
 * @return array Array of best selling product IDs.
 */
if ( ! function_exists( 'mpd_get_best_selling_product_ids' ) ) {
function mpd_get_best_selling_product_ids( $limit = 10 ) {
	$query_args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'fields'         => 'ids',
		'meta_key'       => 'total_sales',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC',
	);

	$query = new WP_Query( $query_args );

	return $query->posts;
}
}

/**
 * Get top rated product IDs.
 *
 * @since 2.0.0
 *
 * @param int $limit Number of products to return.
 * @return array Array of top rated product IDs.
 */
if ( ! function_exists( 'mpd_get_top_rated_product_ids' ) ) {
function mpd_get_top_rated_product_ids( $limit = 10 ) {
	$query_args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'fields'         => 'ids',
		'meta_key'       => '_wc_average_rating',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC',
	);

	$query = new WP_Query( $query_args );

	return $query->posts;
}
}

/**
 * Check if HPOS (High-Performance Order Storage) is enabled.
 *
 * @since 2.0.0
 *
 * @return bool Whether HPOS is enabled.
 */
if ( ! function_exists( 'mpd_is_hpos_enabled' ) ) {
function mpd_is_hpos_enabled() {
	if ( ! class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) ) {
		return false;
	}

	return \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
}
}

/**
 * Get order from ID (HPOS compatible).
 *
 * @since 2.0.0
 *
 * @param int $order_id Order ID.
 * @return \WC_Order|false Order object or false.
 */
if ( ! function_exists( 'mpd_get_order' ) ) {
function mpd_get_order( $order_id ) {
	if ( ! function_exists( 'wc_get_order' ) ) {
		return false;
	}

	return wc_get_order( $order_id );
}
}

/**
 * Get customer orders.
 *
 * @since 2.0.0
 *
 * @param int   $customer_id Customer ID.
 * @param array $args        Query arguments.
 * @return array Array of order objects.
 */
if ( ! function_exists( 'mpd_get_customer_orders' ) ) {
function mpd_get_customer_orders( $customer_id, $args = array() ) {
	$defaults = array(
		'customer_id' => $customer_id,
		'limit'       => 10,
		'orderby'     => 'date',
		'order'       => 'DESC',
	);

	$args = wp_parse_args( $args, $defaults );

	return wc_get_orders( $args );
}
}

/**
 * Format order status for display.
 *
 * @since 2.0.0
 *
 * @param string $status The order status.
 * @return string Formatted status.
 */
if ( ! function_exists( 'mpd_format_order_status' ) ) {
function mpd_format_order_status( $status ) {
	$status = str_replace( 'wc-', '', $status );

	return wc_get_order_status_name( $status );
}
}

/**
 * Get WooCommerce currency symbol.
 *
 * @since 2.0.0
 *
 * @return string Currency symbol.
 */
if ( ! function_exists( 'mpd_get_currency_symbol' ) ) {
function mpd_get_currency_symbol() {
	if ( ! function_exists( 'get_woocommerce_currency_symbol' ) ) {
		return '$';
	}

	return get_woocommerce_currency_symbol();
}
}

/**
 * Add product to cart via AJAX.
 *
 * @since 2.0.0
 *
 * @return void
 */
if ( ! function_exists( 'mpd_ajax_add_to_cart' ) ) {
function mpd_ajax_add_to_cart() {
	check_ajax_referer( 'mpd_add_to_cart', 'nonce' );

	if ( function_exists( 'wc_load_cart' ) && function_exists( 'WC' ) && WC() && ! WC()->cart ) {
		wc_load_cart();
	}

	if ( ! function_exists( 'WC' ) || ! WC() || ! WC()->cart ) {
		wp_send_json_error( array( 'message' => __( 'Cart not available.', 'magical-products-display' ) ) );
	}

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	$quantity   = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
	$variation_id = ! empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
	$variations   = array();

	foreach ( $_POST as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified above.
		if ( 0 === strpos( $key, 'attribute_' ) ) {
			$variations[ sanitize_title( wp_unslash( $key ) ) ] = sanitize_text_field( wp_unslash( $value ) );
		}
	}

	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product.', 'magical-products-display' ) ) );
	}

	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		wp_send_json_error( array( 'message' => __( 'Product not found.', 'magical-products-display' ) ) );
	}

	$added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations );

	if ( $added ) {
		wp_send_json_success(
			array(
				'message'    => __( 'Product added to cart.', 'magical-products-display' ),
				'cart_count' => WC()->cart->get_cart_contents_count(),
				'cart_total' => WC()->cart->get_cart_total(),
			)
		);
	} else {
		wp_send_json_error( array( 'message' => __( 'Could not add product to cart.', 'magical-products-display' ) ) );
	}
}
add_action( 'wp_ajax_mpd_add_to_cart', 'mpd_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_mpd_add_to_cart', 'mpd_ajax_add_to_cart' );
}

/**
 * Single product AJAX add to cart handler.
 *
 * Supports all product types including variable products with variation data.
 * Returns WooCommerce cart fragments for mini-cart updates.
 *
 * @since 2.0.0
 * @return void
 */
if ( ! function_exists( 'mpd_single_product_add_to_cart' ) ) {
function mpd_single_product_add_to_cart() {
	check_ajax_referer( 'mpd_single_add_to_cart', 'nonce' );

	if ( function_exists( 'wc_load_cart' ) && function_exists( 'WC' ) && WC() && ! WC()->cart ) {
		wc_load_cart();
	}

	if ( ! function_exists( 'WC' ) || ! WC() || ! WC()->cart ) {
		wp_send_json( array( 'error' => true, 'notices' => esc_html__( 'Cart not available.', 'magical-products-display' ) ) );
		return;
	}

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

	// Fallback: WC variable form uses add-to-cart hidden input for the parent product ID.
	if ( ! $product_id && isset( $_POST['add-to-cart'] ) ) {
		$product_id = absint( $_POST['add-to-cart'] );
	}

	$quantity     = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
	$variation_id = ! empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
	$variations   = array();

	// Collect attribute_* fields for variable products.
	foreach ( $_POST as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified above.
		if ( strpos( $key, 'attribute_' ) === 0 ) {
			$variations[ sanitize_title( wp_unslash( $key ) ) ] = sanitize_text_field( wp_unslash( $value ) );
		}
	}

	if ( ! $product_id ) {
		wp_send_json( array( 'error' => true, 'notices' => esc_html__( 'Invalid product.', 'magical-products-display' ) ) );
		return;
	}

	$product_status    = get_post_status( $product_id );
	$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );

	if ( $passed_validation && 'publish' === $product_status && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) ) {
		do_action( 'woocommerce_ajax_added_to_cart', $product_id );

		if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
			wc_add_to_cart_message( array( $product_id => $quantity ), true );
		}

		wp_send_json( array(
			'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array() ),
			'cart_hash' => WC()->cart->get_cart_hash(),
		) );
	} else {
		$notices        = wc_get_notices( 'error' );
		$error_messages = array();
		if ( ! empty( $notices ) ) {
			foreach ( $notices as $notice ) {
				$error_messages[] = isset( $notice['notice'] ) ? $notice['notice'] : $notice;
			}
			wc_clear_notices();
		}

		wp_send_json( array(
			'error'   => true,
			'notices' => ! empty( $error_messages ) ? implode( '<br>', $error_messages ) : esc_html__( 'Could not add product to cart.', 'magical-products-display' ),
		) );
	}
}
add_action( 'wp_ajax_mpd_single_add_to_cart', 'mpd_single_product_add_to_cart' );
add_action( 'wp_ajax_nopriv_mpd_single_add_to_cart', 'mpd_single_product_add_to_cart' );
}

/**
 * Add MPD Mini Cart elements to WooCommerce cart fragments.
 *
 * Registers the counter, subtotal, and products content so they update
 * instantly after any AJAX add-to-cart action.
 *
 * @since 2.0.0
 *
 * @param array $fragments Existing WooCommerce fragments.
 * @return array Updated fragments.
 */
if ( ! function_exists( 'mpd_add_mini_cart_fragments' ) ) {
function mpd_add_mini_cart_fragments( $fragments ) {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $fragments;
	}

	$cart     = WC()->cart;
	$count    = $cart->get_cart_contents_count();
	$subtotal = $cart->get_cart_subtotal();

	// Update counter badge.
	$fragments['.mpd-mini-cart-counter'] = '<span class="mpd-mini-cart-counter">' . esc_html( $count ) . '</span>';

	// Update subtotal text.
	$fragments['.mpd-mini-cart-subtotal'] = '<span class="mpd-mini-cart-subtotal">' . wp_kses_post( $subtotal ) . '</span>';

	// Update the products content area.
	ob_start();
	$cart_items = $cart->get_cart();
	$max_items  = 5;

	if ( empty( $cart_items ) ) {
		echo '<div class="mpd-mini-cart-products-wrap"><div class="mpd-mini-cart-empty"><p>' . esc_html__( 'Your cart is currently empty.', 'magical-products-display' ) . '</p></div></div>';
	} else {
		echo '<div class="mpd-mini-cart-products-wrap">';
		echo '<div class="mpd-mini-cart-products">';
		$count_items = 0;
		foreach ( $cart_items as $cart_item_key => $cart_item ) {
			if ( ++$count_items > $max_items ) {
				break;
			}
			$product = $cart_item['data'];
			if ( ! $product || ! $product instanceof \WC_Product ) {
				continue;
			}
			$price     = WC()->cart->get_product_price( $product );
			$quantity  = $cart_item['quantity'];
			$thumbnail = $product->get_image( 'woocommerce_thumbnail' );
			?>
			<div class="mpd-mini-cart-product">
				<div class="mpd-mini-cart-product-image">
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo wp_kses_post( $thumbnail ); ?></a>
				</div>
				<div class="mpd-mini-cart-product-details">
					<h4 class="mpd-mini-cart-product-name">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
					</h4>
					<span class="mpd-mini-cart-product-price"><?php echo wp_kses_post( $price ); ?> &times; <?php echo esc_html( $quantity ); ?></span>
				</div>
				<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="mpd-mini-cart-remove" title="<?php esc_attr_e( 'Remove', 'magical-products-display' ); ?>">&times;</a>
			</div>
			<?php
		}
		if ( count( $cart_items ) > $max_items ) {
			echo '<div class="mpd-mini-cart-more">' . sprintf(
				/* translators: %d: number of additional items */
				esc_html__( '+ %d more items', 'magical-products-display' ),
				count( $cart_items ) - $max_items
			) . '</div>';
		}
		echo '</div>';

		echo '<div class="mpd-mini-cart-subtotal-row"><strong>' . esc_html__( 'Subtotal:', 'magical-products-display' ) . '</strong><span>' . wp_kses_post( $cart->get_cart_subtotal() ) . '</span></div>';

		echo '<div class="mpd-mini-cart-buttons">';
		echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="button view-cart">' . esc_html__( 'View Cart', 'magical-products-display' ) . '</a>';
		echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="button checkout">' . esc_html__( 'Checkout', 'magical-products-display' ) . '</a>';
		echo '</div>';
		echo '</div>';
	}
	$fragments['.mpd-mini-cart-products-wrap'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'mpd_add_mini_cart_fragments' );
}

/**
 * Declare HPOS compatibility.
 *
 * @since 2.0.0
 *
 * @return void
 */
if ( ! function_exists( 'mpd_declare_hpos_compatibility' ) ) {
function mpd_declare_hpos_compatibility() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', MAGICAL_PRODUCTS_DISPLAY_FILE, true );
	}
}
add_action( 'before_woocommerce_init', 'mpd_declare_hpos_compatibility' );
}

/**
 * Quick View product via AJAX.
 *
 * Returns product HTML for quick view modal.
 *
 * @since 2.0.0
 *
 * @return void
 */
if ( ! function_exists( 'mpd_ajax_quick_view' ) ) {
function mpd_ajax_quick_view() {
	check_ajax_referer( 'mpd_global_widgets_nonce', 'nonce' );

	$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product.', 'magical-products-display' ) ) );
	}

	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		wp_send_json_error( array( 'message' => __( 'Product not found.', 'magical-products-display' ) ) );
	}

	// Set up post data for template functions.
	global $post;
	$post = get_post( $product_id );
	setup_postdata( $post );

	ob_start();
	?>
	<div class="mpd-quick-view-product woocommerce">
		<div class="mpd-quick-view-gallery">
			<?php
			$image_id  = $product->get_image_id();
			$image_url = wp_get_attachment_image_url( $image_id, 'large' );
			if ( $image_url ) {
				echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $product->get_name() ) . '">';
			} else {
				echo wc_placeholder_img( 'large' );
			}
			?>
		</div>
		<div class="mpd-quick-view-summary">
			<h2 class="product_title"><?php echo esc_html( $product->get_name() ); ?></h2>
			
			<p class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
			
			<?php if ( $product->get_short_description() ) : ?>
				<div class="woocommerce-product-details__short-description">
					<?php echo wp_kses_post( $product->get_short_description() ); ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $product->is_in_stock() ) : ?>
				<form class="cart" action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" method="post">
					<?php if ( $product->is_type( 'simple' ) ) : ?>
						<?php woocommerce_quantity_input( array( 'input_value' => 1 ), $product ); ?>
						<button type="submit" class="single_add_to_cart_button button alt" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>">
							<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
						</button>
					<?php else : ?>
						<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="button">
							<?php esc_html_e( 'Select options', 'magical-products-display' ); ?>
						</a>
					<?php endif; ?>
				</form>
			<?php else : ?>
				<p class="stock out-of-stock"><?php esc_html_e( 'Out of stock', 'magical-products-display' ); ?></p>
			<?php endif; ?>
			
			<div class="product_meta">
				<?php if ( $product->get_sku() ) : ?>
					<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'magical-products-display' ); ?> <span class="sku"><?php echo esc_html( $product->get_sku() ); ?></span></span>
				<?php endif; ?>
				
				<?php
				$categories = wc_get_product_category_list( $product_id, ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'magical-products-display' ) . ' ', '</span>' );
				echo wp_kses_post( $categories );
				?>
			</div>
			
			<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="mpd-quick-view-more-link">
				<?php esc_html_e( 'View full details', 'magical-products-display' ); ?> &rarr;
			</a>
		</div>
	</div>
	<?php
	$html = ob_get_clean();
	
	wp_reset_postdata();

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_mpd_quick_view', 'mpd_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_mpd_quick_view', 'mpd_ajax_quick_view' );
}

/**
 * AJAX handler for fetching wishlist items for header dropdown.
 *
 * @since 2.0.0
 */
if ( ! function_exists( 'mpd_ajax_get_wishlist_items' ) ) {
function mpd_ajax_get_wishlist_items() {
	check_ajax_referer( 'mpd_global_widgets_nonce', 'nonce' );

	$product_ids_str = isset( $_POST['product_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['product_ids'] ) ) : '';
	
	if ( empty( $product_ids_str ) ) {
		wp_send_json_success( array( 'html' => '<p class="mpd-empty-message">' . esc_html__( 'Your wishlist is empty.', 'magical-products-display' ) . '</p>' ) );
	}
	
	$product_ids = array_filter( array_map( 'absint', explode( ',', $product_ids_str ) ) );
	
	if ( empty( $product_ids ) ) {
		wp_send_json_success( array( 'html' => '<p class="mpd-empty-message">' . esc_html__( 'Your wishlist is empty.', 'magical-products-display' ) . '</p>' ) );
	}
	
	$html = '';
	foreach ( $product_ids as $product_id ) {
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			continue;
		}
		
		$thumbnail = $product->get_image( 'thumbnail', array( 'class' => 'mpd-header-wc-item-thumb' ) );
		$title     = $product->get_name();
		$price     = $product->get_price_html();
		$permalink = $product->get_permalink();
		
		$html .= '<div class="mpd-header-wc-item-row" data-product-id="' . esc_attr( $product_id ) . '">';
		$html .= '<a href="' . esc_url( $permalink ) . '" class="mpd-header-wc-item-link">';
		$html .= '<span class="mpd-header-wc-item-image">' . wp_kses_post( $thumbnail ) . '</span>';
		$html .= '<span class="mpd-header-wc-item-details">';
		$html .= '<span class="mpd-header-wc-item-name">' . esc_html( $title ) . '</span>';
		$html .= '<span class="mpd-header-wc-item-price">' . wp_kses_post( $price ) . '</span>';
		$html .= '</span>';
		$html .= '</a>';
		$html .= '<button type="button" class="mpd-header-wc-remove-item" data-product-id="' . esc_attr( $product_id ) . '" title="' . esc_attr__( 'Remove', 'magical-products-display' ) . '"><i class="eicon-close"></i></button>';
		$html .= '</div>';
	}
	
	if ( empty( $html ) ) {
		$html = '<p class="mpd-empty-message">' . esc_html__( 'Your wishlist is empty.', 'magical-products-display' ) . '</p>';
	}
	
	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_mpd_get_wishlist_items', 'mpd_ajax_get_wishlist_items' );
add_action( 'wp_ajax_nopriv_mpd_get_wishlist_items', 'mpd_ajax_get_wishlist_items' );
}

/**
 * AJAX handler for fetching compare items for header dropdown.
 *
 * @since 2.0.0
 */
if ( ! function_exists( 'mpd_ajax_get_compare_items' ) ) {
function mpd_ajax_get_compare_items() {
	check_ajax_referer( 'mpd_global_widgets_nonce', 'nonce' );

	$product_ids_str = isset( $_POST['product_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['product_ids'] ) ) : '';
	
	if ( empty( $product_ids_str ) ) {
		wp_send_json_success( array( 'html' => '<p class="mpd-empty-message">' . esc_html__( 'No products to compare.', 'magical-products-display' ) . '</p>' ) );
	}
	
	$product_ids = array_filter( array_map( 'absint', explode( ',', $product_ids_str ) ) );
	
	if ( empty( $product_ids ) ) {
		wp_send_json_success( array( 'html' => '<p class="mpd-empty-message">' . esc_html__( 'No products to compare.', 'magical-products-display' ) . '</p>' ) );
	}
	
	$html = '';
	foreach ( $product_ids as $product_id ) {
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			continue;
		}
		
		$thumbnail = $product->get_image( 'thumbnail', array( 'class' => 'mpd-header-wc-item-thumb' ) );
		$title     = $product->get_name();
		$price     = $product->get_price_html();
		$permalink = $product->get_permalink();
		
		$html .= '<div class="mpd-header-wc-item-row" data-product-id="' . esc_attr( $product_id ) . '">';
		$html .= '<a href="' . esc_url( $permalink ) . '" class="mpd-header-wc-item-link">';
		$html .= '<span class="mpd-header-wc-item-image">' . wp_kses_post( $thumbnail ) . '</span>';
		$html .= '<span class="mpd-header-wc-item-details">';
		$html .= '<span class="mpd-header-wc-item-name">' . esc_html( $title ) . '</span>';
		$html .= '<span class="mpd-header-wc-item-price">' . wp_kses_post( $price ) . '</span>';
		$html .= '</span>';
		$html .= '</a>';
		$html .= '<button type="button" class="mpd-header-compare-remove-item" data-product-id="' . esc_attr( $product_id ) . '" title="' . esc_attr__( 'Remove', 'magical-products-display' ) . '"><i class="eicon-close"></i></button>';
		$html .= '</div>';
	}
	
	if ( empty( $html ) ) {
		$html = '<p class="mpd-empty-message">' . esc_html__( 'No products to compare.', 'magical-products-display' ) . '</p>';
	}
	
	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_mpd_get_compare_items', 'mpd_ajax_get_compare_items' );
add_action( 'wp_ajax_nopriv_mpd_get_compare_items', 'mpd_ajax_get_compare_items' );
}
