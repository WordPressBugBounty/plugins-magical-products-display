<?php
/**
 * WooCommerce Helpers Trait
 *
 * Provides common WooCommerce helper methods for widgets.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Traits;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait WC_Helpers
 *
 * Use this trait in widgets to access common WooCommerce functionality.
 *
 * @since 2.0.0
 */
trait WC_Helpers {

	/**
	 * Check if WooCommerce is active.
	 *
	 * @since 2.0.0
	 *
	 * @return bool Whether WooCommerce is active.
	 */
	protected function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Get the current product.
	 *
	 * Works in loops and on single product pages.
	 * In Elementor editor, returns a preview product for designing.
	 *
	 * @since 2.0.0
	 *
	 * @return \WC_Product|false The product object or false.
	 */
	protected function get_current_product() {
		global $product;

		// Check if we're in Elementor editor mode.
		if ( $this->is_editor_mode() ) {
			return $this->get_preview_product();
		}

		if ( $product instanceof \WC_Product ) {
			return $product;
		}

		// Try to get from the loop.
		if ( in_the_loop() ) {
			return wc_get_product( get_the_ID() );
		}

		// Try to get from global post.
		global $post;
		if ( $post && 'product' === $post->post_type ) {
			return wc_get_product( $post->ID );
		}

		return false;
	}

	/**
	 * Check if we're in Elementor editor mode.
	 *
	 * @since 2.0.0
	 *
	 * @return bool Whether in editor mode.
	 */
	protected function is_editor_mode() {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return false;
		}

		return \Elementor\Plugin::$instance->editor->is_edit_mode() 
			|| \Elementor\Plugin::$instance->preview->is_preview_mode();
	}

	/**
	 * Get the preview product for Elementor editor.
	 *
	 * @since 2.0.0
	 *
	 * @return \WC_Product|false The preview product or false.
	 */
	protected function get_preview_product() {
		// First, check if a preview product is set in the template settings.
		$preview_product_id = $this->get_preview_product_id();

		if ( $preview_product_id ) {
			$product = wc_get_product( $preview_product_id );
			if ( $product ) {
				return $product;
			}
		}

		// Fallback: get the first published product.
		$products = wc_get_products( array(
			'status' => 'publish',
			'limit'  => 1,
			'orderby' => 'date',
			'order'  => 'DESC',
		) );

		if ( ! empty( $products ) ) {
			return $products[0];
		}

		return false;
	}

	/**
	 * Get the preview product ID from template settings.
	 *
	 * @since 2.0.0
	 *
	 * @return int|false The product ID or false.
	 */
	protected function get_preview_product_id() {
		// Check URL parameter first (for instant preview switching).
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['mpd_preview_product'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return absint( $_GET['mpd_preview_product'] );
		}

		// Check if we're editing a template and get its preview product setting.
		$post_id = get_the_ID();
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! $post_id && isset( $_GET['post'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_id = absint( $_GET['post'] );
		}

		if ( $post_id ) {
			$preview_id = get_post_meta( $post_id, '_mpd_preview_product_id', true );
			if ( $preview_id ) {
				return absint( $preview_id );
			}
		}

		// Check global default preview product.
		$default_preview = get_option( 'mpd_default_preview_product', 0 );
		if ( $default_preview ) {
			return absint( $default_preview );
		}

		return false;
	}

	/**
	 * Get product price HTML.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product The product object.
	 * @return string The price HTML.
	 */
	protected function get_product_price_html( $product ) {
		if ( ! $product ) {
			return '';
		}

		return $product->get_price_html();
	}

	/**
	 * Get product rating HTML.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product The product object.
	 * @param string      $class   Additional CSS class.
	 * @return string The rating HTML.
	 */
	protected function get_product_rating_html( $product, $class = '' ) {
		if ( ! $product ) {
			return '';
		}

		if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
			return '';
		}

		$rating_count = $product->get_rating_count();
		$average      = $product->get_average_rating();

		if ( $rating_count > 0 ) {
			$rating_html = wc_get_rating_html( $average, $rating_count );
			
			if ( ! empty( $class ) ) {
				$rating_html = str_replace( 'star-rating', 'star-rating ' . esc_attr( $class ), $rating_html );
			}

			return $rating_html;
		}

		return '';
	}

	/**
	 * Get product custom rating HTML with custom icons.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product The product object.
	 * @param string      $class   Additional CSS class.
	 * @return string The custom rating HTML.
	 */
	protected function get_product_custom_rating_html( $product, $class = '' ) {
		if ( ! $product ) {
			return '';
		}

		if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
			return '';
		}

		$average      = $product->get_average_rating();
		$rating_whole = ( $average / 5 ) * 100;

		ob_start();
		?>
		<div class="mpd-rating <?php echo esc_attr( $class ); ?>">
			<div class="mpd-product-rating">
				<span class="mpd-product-stars">
					<span class="mpd-product-stars-filled" style="width: <?php echo esc_attr( $rating_whole ); ?>%;">
						<i class="eicon-star"></i>
						<i class="eicon-star"></i>
						<i class="eicon-star"></i>
						<i class="eicon-star"></i>
						<i class="eicon-star"></i>
					</span>
					<i class="eicon-star-o"></i>
					<i class="eicon-star-o"></i>
					<i class="eicon-star-o"></i>
					<i class="eicon-star-o"></i>
					<i class="eicon-star-o"></i>
				</span>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get product sale badge HTML.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product    The product object.
	 * @param string      $text       Badge text (default: 'Sale!').
	 * @param bool        $percentage Whether to show percentage.
	 * @return string The sale badge HTML.
	 */
	protected function get_sale_badge_html( $product, $text = '', $percentage = false ) {
		if ( ! $product || ! $product->is_on_sale() ) {
			return '';
		}

		$badge_text = ! empty( $text ) ? $text : __( 'Sale!', 'magical-products-display' );

		if ( $percentage && $product->is_type( 'simple' ) ) {
			$regular_price = (float) $product->get_regular_price();
			$sale_price    = (float) $product->get_sale_price();

			if ( $regular_price > 0 ) {
				$percentage_off = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
				$badge_text     = '-' . $percentage_off . '%';
			}
		}

		return sprintf(
			'<span class="mpd-sale-badge">%s</span>',
			esc_html( $badge_text )
		);
	}

	/**
	 * Get product stock status HTML.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product The product object.
	 * @return string The stock status HTML.
	 */
	protected function get_stock_status_html( $product ) {
		if ( ! $product ) {
			return '';
		}

		$availability = $product->get_availability();
		$class        = isset( $availability['class'] ) ? $availability['class'] : '';
		$text         = isset( $availability['availability'] ) ? $availability['availability'] : '';

		if ( empty( $text ) ) {
			return '';
		}

		return sprintf(
			'<span class="mpd-stock-status %s">%s</span>',
			esc_attr( $class ),
			esc_html( $text )
		);
	}

	/**
	 * Get add to cart button HTML.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product The product object.
	 * @param array       $args    Button arguments.
	 * @return string The add to cart button HTML.
	 */
	protected function get_add_to_cart_html( $product, $args = array() ) {
		if ( ! $product ) {
			return '';
		}

		$defaults = array(
			'quantity'   => 1,
			'class'      => implode(
				' ',
				array_filter(
					array(
						'button',
						'product_type_' . $product->get_type(),
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
					)
				)
			),
			'attributes' => array(
				'data-product_id'  => $product->get_id(),
				'data-product_sku' => $product->get_sku(),
				'aria-label'       => $product->add_to_cart_description(),
				'rel'              => 'nofollow',
			),
		);

		$args = wp_parse_args( $args, $defaults );

		return apply_filters(
			'mpd_loop_add_to_cart_link',
			sprintf(
				'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				esc_html( $product->add_to_cart_text() )
			),
			$product,
			$args
		);
	}

	/**
	 * Get product categories as links.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product The product object.
	 * @param string      $sep     Separator between categories.
	 * @param int         $limit   Number of categories to show.
	 * @return string The categories HTML.
	 */
	protected function get_product_categories_html( $product, $sep = ', ', $limit = 0 ) {
		if ( ! $product ) {
			return '';
		}

		$terms = get_the_terms( $product->get_id(), 'product_cat' );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return '';
		}

		$links = array();
		$count = 0;

		foreach ( $terms as $term ) {
			$count++;
			if ( $limit > 0 && $count > $limit ) {
				break;
			}

			$link = get_term_link( $term, 'product_cat' );
			if ( ! is_wp_error( $link ) ) {
				$links[] = sprintf(
					'<a href="%s">%s</a>',
					esc_url( $link ),
					esc_html( $term->name )
				);
			}
		}

		return implode( $sep, $links );
	}

	/**
	 * Get product image HTML.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product   The product object.
	 * @param string      $size      Image size.
	 * @param array       $attr      Image attributes.
	 * @param bool        $secondary Whether to show secondary image on hover.
	 * @return string The image HTML.
	 */
	protected function get_product_image_html( $product, $size = 'woocommerce_thumbnail', $attr = array(), $secondary = false ) {
		if ( ! $product ) {
			return '';
		}

		$image_id = $product->get_image_id();
		$html     = '';

		if ( $image_id ) {
			$html = wp_get_attachment_image( $image_id, $size, false, $attr );
		} else {
			$html = wc_placeholder_img( $size, $attr );
		}

		// Add secondary image for hover effect.
		if ( $secondary ) {
			$gallery_ids = $product->get_gallery_image_ids();
			if ( ! empty( $gallery_ids ) ) {
				$secondary_html = wp_get_attachment_image(
					$gallery_ids[0],
					$size,
					false,
					array_merge(
						$attr,
						array( 'class' => 'mpd-secondary-image' )
					)
				);
				$html          .= $secondary_html;
			}
		}

		return $html;
	}

	/**
	 * Get wishlist button HTML (placeholder for pro feature).
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product The product object.
	 * @return string The wishlist button HTML.
	 */
	protected function get_wishlist_button_html( $product ) {
		if ( ! $product ) {
			return '';
		}

		return apply_filters(
			'mpd_wishlist_button_html',
			sprintf(
				'<button class="mpd-wishlist-btn" data-product-id="%d" title="%s">
					<i class="eicon-heart-o"></i>
				</button>',
				esc_attr( $product->get_id() ),
				esc_attr__( 'Add to Wishlist', 'magical-products-display' )
			),
			$product
		);
	}

	/**
	 * Get quick view button HTML (placeholder for pro feature).
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product The product object.
	 * @return string The quick view button HTML.
	 */
	protected function get_quick_view_button_html( $product ) {
		if ( ! $product ) {
			return '';
		}

		return apply_filters(
			'mpd_quick_view_button_html',
			sprintf(
				'<button class="mpd-quick-view-btn" data-product-id="%d" title="%s">
					<i class="eicon-zoom-in-bold"></i>
				</button>',
				esc_attr( $product->get_id() ),
				esc_attr__( 'Quick View', 'magical-products-display' )
			),
			$product
		);
	}

	/**
	 * Get compare button HTML (placeholder for pro feature).
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product The product object.
	 * @return string The compare button HTML.
	 */
	protected function get_compare_button_html( $product ) {
		if ( ! $product ) {
			return '';
		}

		return apply_filters(
			'mpd_compare_button_html',
			sprintf(
				'<button class="mpd-compare-btn" data-product-id="%d" title="%s">
					<i class="eicon-exchange"></i>
				</button>',
				esc_attr( $product->get_id() ),
				esc_attr__( 'Compare', 'magical-products-display' )
			),
			$product
		);
	}

	/**
	 * Check if we're on a WooCommerce page.
	 *
	 * @since 2.0.0
	 *
	 * @return bool Whether we're on a WooCommerce page.
	 */
	protected function is_woocommerce_page() {
		if ( ! function_exists( 'is_woocommerce' ) ) {
			return false;
		}

		return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
	}

	/**
	 * Get WooCommerce page type.
	 *
	 * @since 2.0.0
	 *
	 * @return string The page type.
	 */
	protected function get_wc_page_type() {
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
			return 'category';
		}

		if ( is_product_tag() ) {
			return 'tag';
		}

		if ( is_cart() ) {
			return 'cart';
		}

		if ( is_checkout() ) {
			return 'checkout';
		}

		if ( is_account_page() ) {
			return 'my-account';
		}

		return '';
	}
}
