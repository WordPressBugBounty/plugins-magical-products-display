<?php
/**
 * Elementor Integration
 *
 * Handles all Elementor widget registration, category registration,
 * and page-type detection for Magical Shop Builder.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MPD_Elementor
 *
 * Manages Elementor widget categories and widget registration.
 *
 * @since 2.0.0
 */
class MPD_Elementor {

	/**
	 * Singleton instance.
	 *
	 * @since 2.0.0
	 *
	 * @var MPD_Elementor|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @since 2.0.0
	 *
	 * @return MPD_Elementor
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialize Elementor integration.
	 *
	 * Hooks into Elementor for widget and category registration.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_categories' ) );
	}

	/**
	 * Register widget categories in the Elementor panel.
	 *
	 * @since 2.0.0
	 *
	 * @param \Elementor\Elements_Manager $categories_manager Elements manager.
	 * @return void
	 */
	public function register_categories( $categories_manager ) {
		$is_pro     = 'yes' === get_option( 'mgppro_is_active', 'no' );
		$pro_suffix = $is_pro ? ' Pro' : '';

		// Detect page type for smart category expansion.
		$page_type = $this->detect_page_type();

		// Main category for general product widgets - always active.
		$categories_manager->add_category(
			'mpd-productwoo',
			array(
				'title'  => esc_html__( 'Magical Shop Builder', 'magical-products-display' ) . $pro_suffix,
				'icon'   => 'eicon-products',
				'active' => true,
			)
		);

		// Shop & Archive widgets category.
		$categories_manager->add_category(
			'mpd-shop-archive',
			array(
				'title'  => esc_html__( 'Magical Shop & Archive', 'magical-products-display' ) . $pro_suffix,
				'icon'   => 'eicon-archive',
				'active' => ( 'archive-product' === $page_type ),
			)
		);

		// Single Product widgets category.
		$categories_manager->add_category(
			'mpd-single-product',
			array(
				'title'  => esc_html__( 'Magical Single Product', 'magical-products-display' ) . $pro_suffix,
				'icon'   => 'eicon-single-product',
				'active' => ( 'single-product' === $page_type ),
			)
		);

		// Cart & Checkout widgets category.
		$categories_manager->add_category(
			'mpd-cart-checkout',
			array(
				'title'  => esc_html__( 'Magical Cart & Checkout', 'magical-products-display' ) . $pro_suffix,
				'icon'   => 'eicon-cart',
				'active' => in_array( $page_type, array( 'cart', 'checkout' ), true ),
			)
		);

		// My Account widgets category.
		$categories_manager->add_category(
			'mpd-my-account',
			array(
				'title'  => esc_html__( 'Magical My Account', 'magical-products-display' ) . $pro_suffix,
				'icon'   => 'eicon-person',
				'active' => ( 'my-account' === $page_type ),
			)
		);

		// Thank You / Order Received widgets category.
		$categories_manager->add_category(
			'mpd-thankyou',
			array(
				'title'  => esc_html__( 'Magical Thank You', 'magical-products-display' ) . $pro_suffix,
				'icon'   => 'eicon-check-circle',
				'active' => ( 'thankyou' === $page_type ),
			)
		);

		// Global/Utility widgets category.
		$categories_manager->add_category(
			'mpd-global',
			array(
				'title'  => esc_html__( 'Magical Global', 'magical-products-display' ) . $pro_suffix,
				'icon'   => 'eicon-globe',
				'active' => false,
			)
		);
	}

	/**
	 * Register all Elementor widgets.
	 *
	 * @since 2.0.0
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	public function register_widgets( $widgets_manager ) {
		// Load base widget class.
		require_once MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/widgets/base/class-mpd-widget-base.php';

		// Register legacy widgets (backward compatible).
		$this->register_legacy_widgets( $widgets_manager );

		// Register Single Product Page Widgets.
		$this->register_single_product_widgets( $widgets_manager );

		// Register Cart Widgets.
		$this->register_cart_widgets( $widgets_manager );

		// Register Checkout Widgets.
		$this->register_checkout_widgets( $widgets_manager );

		// Register My Account Widgets.
		$this->register_my_account_widgets( $widgets_manager );

		// Register Shop Archive Widgets.
		$this->register_shop_archive_widgets( $widgets_manager );

		// Register Global/Utility Widgets.
		$this->register_global_widgets( $widgets_manager );

		// Register Thank You Page Widgets.
		$this->register_thankyou_widgets( $widgets_manager );

		/**
		 * Action hook to register additional widgets.
		 *
		 * Third-party plugins or pro add-ons can use this hook
		 * to register their own widgets.
		 *
		 * @since 2.0.0
		 *
		 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
		 */
		do_action( 'mpd_register_widgets', $widgets_manager );
	}

	/**
	 * Register legacy widgets that don't use the new namespace system.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	private function register_legacy_widgets( $widgets_manager ) {
		$legacy_widgets = array(
			'products-grid.php'            => 'mgProducts_Grid',
			'products-list.php'            => 'mgProducts_List',
			'shop-products.php'            => 'mgProducts_Shop',
			'products-slider.php'          => 'mgProducts_slider',
			'products-carousel.php'        => 'mgProducts_carousel',
			'testimonial-carousel.php'     => 'mgp_TestimonialCarousel',
			'products-tab.php'             => 'mgProducts_Tab',
			'products-cat.php'             => 'mgProducts_cats',
			'products-awesome-list.php'    => 'mgProducts_AwesomeList',
			'pricing-table.php'            => 'mgProduct_Pricing_Table',
			'accordion-widget.php'         => 'mgProduct_Accordion',
			'ajax-search/ajax-search-widget.php' => 'mgProducts_AJAX_Search',
		);

		$widget_path = MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/widgets/';

		foreach ( $legacy_widgets as $file => $class_name ) {
			$file_path = $widget_path . $file;

			if ( file_exists( $file_path ) ) {
				require_once $file_path;
				$full_class = '\\' . $class_name;

				if ( class_exists( $full_class ) ) {
					$widgets_manager->register( new $full_class() );
				}
			}
		}
	}

	/**
	 * Register Single Product Page Widgets.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	private function register_single_product_widgets( $widgets_manager ) {
		$widgets = array(
			'class-mpd-widget-product-title.php'             => 'Product_Title',
			'class-mpd-widget-product-price.php'             => 'Product_Price',
			'class-mpd-widget-product-gallery.php'           => 'Product_Gallery',
			'class-mpd-widget-add-to-cart.php'               => 'Add_To_Cart',
			'class-mpd-widget-product-description.php'       => 'Product_Description',
			'class-mpd-widget-product-short-description.php' => 'Product_Short_Description',
			'class-mpd-widget-product-meta.php'              => 'Product_Meta',
			'class-mpd-widget-product-rating.php'            => 'Product_Rating',
			'class-mpd-widget-product-reviews.php'           => 'Product_Reviews',
			'class-mpd-widget-product-tabs.php'              => 'Product_Tabs',
			'class-mpd-widget-related-products.php'          => 'Related_Products',
			'class-mpd-widget-upsells.php'                   => 'Upsells',
			'class-mpd-widget-product-stock.php'             => 'Product_Stock',
			'class-mpd-widget-product-attributes.php'        => 'Product_Attributes',
			'class-mpd-widget-product-navigation.php'        => 'Product_Navigation',
			'action-buttons-widget.php'                      => 'Action_Buttons',
		);

		$this->safe_register_widgets(
			$widgets_manager,
			$widgets,
			MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/widgets/single-product/',
			'SingleProduct'
		);
	}

	/**
	 * Register Cart Widgets.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	private function register_cart_widgets( $widgets_manager ) {
		$widgets = array(
			'class-mpd-widget-cart-table.php'  => 'Cart_Table',
			'class-mpd-widget-cart-totals.php' => 'Cart_Totals',
			'class-mpd-widget-cross-sells.php' => 'Cross_Sells',
			'class-mpd-widget-coupon-form.php' => 'Coupon_Form',
			'class-mpd-widget-mini-cart.php'   => 'Mini_Cart',
			'class-mpd-widget-empty-cart.php'  => 'Empty_Cart',
		);

		$this->safe_register_widgets(
			$widgets_manager,
			$widgets,
			MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/widgets/cart/',
			'Cart'
		);
	}

	/**
	 * Register Checkout Widgets.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	private function register_checkout_widgets( $widgets_manager ) {
		$widgets = array(
			'class-mpd-widget-billing-form.php'        => 'Billing_Form',
			'class-mpd-widget-shipping-form.php'       => 'Shipping_Form',
			'class-mpd-widget-order-review.php'        => 'Order_Review',
			'class-mpd-widget-payment-methods.php'     => 'Payment_Methods',
			'class-mpd-widget-checkout-coupon.php'     => 'Checkout_Coupon',
			'class-mpd-widget-place-order.php'         => 'Place_Order',
			'class-mpd-widget-order-notes.php'         => 'Order_Notes',
			'class-mpd-widget-checkout-login.php'      => 'Checkout_Login',
			'class-mpd-widget-multi-step-checkout.php' => 'Multi_Step_Checkout',
			'class-mpd-widget-express-checkout.php'    => 'Express_Checkout',
		);

		$this->safe_register_widgets(
			$widgets_manager,
			$widgets,
			MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/widgets/checkout/',
			'Checkout'
		);
	}

	/**
	 * Register My Account Widgets.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	private function register_my_account_widgets( $widgets_manager ) {
		$widgets = array(
			'class-mpd-widget-account-nav.php'       => 'Account_Nav',
			'class-mpd-widget-account-dashboard.php' => 'Account_Dashboard',
			'class-mpd-widget-orders.php'            => 'Orders',
			'class-mpd-widget-addresses.php'         => 'Addresses',
			'class-mpd-widget-account-details.php'   => 'Account_Details',
			'class-mpd-widget-downloads.php'         => 'Downloads',
			'class-mpd-widget-logout.php'            => 'Logout',
			'class-mpd-widget-account-login.php'     => 'Account_Login',
		);

		$this->safe_register_widgets(
			$widgets_manager,
			$widgets,
			MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/widgets/my-account/',
			'MyAccount'
		);
	}

	/**
	 * Register Shop Archive Widgets.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	private function register_shop_archive_widgets( $widgets_manager ) {
		$widgets = array(
			'class-mpd-widget-products-archive.php'    => 'Products_Archive',
			'class-mpd-widget-archive-title.php'       => 'Archive_Title',
			'class-mpd-widget-archive-description.php' => 'Archive_Description',
			'class-mpd-widget-result-count.php'        => 'Result_Count',
			'class-mpd-widget-ordering.php'            => 'Ordering',
			'class-mpd-widget-pagination.php'          => 'Pagination',
			'class-mpd-widget-active-filters.php'      => 'Active_Filters',
			'class-mpd-widget-price-filter.php'        => 'Price_Filter',
			'class-mpd-widget-attribute-filter.php'    => 'Attribute_Filter',
			'class-mpd-widget-category-filter.php'     => 'Category_Filter',
			'class-mpd-widget-advanced-filter.php'     => 'Advanced_Filter',
		);

		$this->safe_register_widgets(
			$widgets_manager,
			$widgets,
			MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/widgets/shop-archive/',
			'ShopArchive'
		);
	}

	/**
	 * Register Global/Utility Widgets.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	private function register_global_widgets( $widgets_manager ) {
		$widgets = array(
			'class-mpd-widget-header-cart.php'             => 'Header_Cart',
			'class-mpd-widget-breadcrumbs.php'             => 'Breadcrumbs',
			'class-mpd-widget-store-notice.php'            => 'Store_Notice',
			'class-mpd-widget-recently-viewed.php'         => 'Recently_Viewed',
			'class-mpd-widget-comparison.php'              => 'Product_Comparison',
			'class-mpd-widget-wishlist.php'                => 'Wishlist',
			'class-mpd-widget-header-wishlist-compare.php' => 'Header_Wishlist_Compare',
		);

		$this->safe_register_widgets(
			$widgets_manager,
			$widgets,
			MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/widgets/global/',
			'GlobalWidgets'
		);
	}

	/**
	 * Register Thank You Page Widgets.
	 *
	 * @since 2.1.0
	 *
	 * @access private
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	private function register_thankyou_widgets( $widgets_manager ) {
		$widgets = array(
			'class-mpd-widget-order-confirmation.php' => 'Order_Confirmation',
			'class-mpd-widget-order-details.php'      => 'Order_Details',
			'class-mpd-widget-order-items.php'        => 'Order_Items',
			'class-mpd-widget-customer-details.php'   => 'Customer_Details',
		);

		$this->safe_register_widgets(
			$widgets_manager,
			$widgets,
			MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/widgets/thankyou/',
			'ThankYou'
		);
	}

	/**
	 * Safely register a group of widgets with error handling.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @param array                      $widgets         Array of file => class_name pairs.
	 * @param string                     $widget_path     Base directory path for widget files.
	 * @param string                     $namespace       Widget namespace segment (e.g., 'Cart', 'Checkout').
	 * @return void
	 */
	private function safe_register_widgets( $widgets_manager, $widgets, $widget_path, $namespace ) {
		foreach ( $widgets as $file => $class_name ) {
			$file_path = $widget_path . $file;

			if ( file_exists( $file_path ) ) {
				try {
					require_once $file_path;

					$full_class_name = '\\MPD\\MagicalShopBuilder\\Widgets\\' . $namespace . '\\' . $class_name;

					if ( class_exists( $full_class_name ) ) {
						$widgets_manager->register( new $full_class_name() );
					}
				} catch ( \Exception $e ) {
					// Silently skip widgets that fail to load.
					unset( $e );
				} catch ( \Error $e ) {
					// Silently skip widgets with fatal errors.
					unset( $e );
				}
			}
		}
	}

	/**
	 * Detect the page type being edited in Elementor.
	 *
	 * Used to auto-expand the relevant widget category in the panel.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 *
	 * @return string Page type identifier (e.g., 'single-product', 'cart', 'checkout').
	 */
	private function detect_page_type() {
		// Check if we're in Elementor editor.
		if ( ! did_action( 'elementor/loaded' ) ) {
			return '';
		}

		// Get the post ID being edited.
		$post_id = 0;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['post'] ) ) {
			$post_id = absint( $_GET['post'] );
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		} elseif ( isset( $_GET['elementor-preview'] ) ) {
			$post_id = absint( $_GET['elementor-preview'] );
		}

		if ( ! $post_id ) {
			return '';
		}

		// Check if this is one of our templates.
		$post_type = get_post_type( $post_id );

		if ( 'mpd_template' === $post_type ) {
			$template_type = get_post_meta( $post_id, '_mpd_template_type', true );
			return $template_type ? $template_type : '';
		}

		// Check WooCommerce page types.
		if ( function_exists( 'wc_get_page_id' ) ) {
			if ( $post_id === wc_get_page_id( 'shop' ) ) {
				return 'archive-product';
			}
			if ( $post_id === wc_get_page_id( 'cart' ) ) {
				return 'cart';
			}
			if ( $post_id === wc_get_page_id( 'checkout' ) ) {
				return 'checkout';
			}
			if ( $post_id === wc_get_page_id( 'myaccount' ) ) {
				return 'my-account';
			}
		}

		// Check for product post type.
		if ( 'product' === $post_type ) {
			return 'single-product';
		}

		return '';
	}
}
