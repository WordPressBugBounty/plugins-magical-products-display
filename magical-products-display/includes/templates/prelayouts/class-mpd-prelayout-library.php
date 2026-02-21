<?php
/**
 * Pre-Layout Library
 *
 * Contains all pre-defined layout configurations for WooCommerce template types.
 * Each layout includes only structure - no demo data.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

namespace MPD\MagicalShopBuilder\Templates\PreLayouts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PreLayout_Library
 *
 * Library of pre-defined layouts.
 *
 * @since 2.1.0
 */
class PreLayout_Library {

	/**
	 * Layout definitions cache.
	 *
	 * @var array
	 */
	private $layouts = array();

	/**
	 * Base URL for thumbnails.
	 *
	 * @var string
	 */
	private $thumbnail_base_url = '';

	/**
	 * Base path for thumbnails.
	 *
	 * @var string
	 */
	private $thumbnail_base_path = '';

	/**
	 * Cache buster version.
	 *
	 * @var string
	 */
	private $cache_version = '';

	/**
	 * Constructor.
	 *
	 * @since 2.1.0
	 */
	public function __construct() {
		$this->thumbnail_base_url  = MAGICAL_PRODUCTS_DISPLAY_URL . '/assets/images/prelayouts/';
		$this->thumbnail_base_path = MAGICAL_PRODUCTS_DISPLAY_DIR . 'assets/images/prelayouts/';
		
		// Use file modification time for cache busting in development, version in production.
		$this->cache_version = $this->get_cache_version();
	}

	/**
	 * Get cache version string.
	 *
	 * Uses file modification time in development (WP_DEBUG) or plugin version in production.
	 *
	 * @since 2.2.0
	 *
	 * @return string Cache version string.
	 */
	private function get_cache_version() {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			// In development, use current timestamp to bust cache.
			return (string) time();
		}
		
		// In production, use plugin version.
		return defined( 'MAGICAL_PRODUCTS_DISPLAY_VERSION' ) ? MAGICAL_PRODUCTS_DISPLAY_VERSION : '1.0.0';
	}

	/**
	 * Get thumbnail URL with format priority and cache busting.
	 *
	 * Checks for WebP first, then SVG, then falls back to placeholder.
	 *
	 * @since 2.2.0
	 *
	 * @param string $layout_id Layout ID (without extension).
	 * @return string Thumbnail URL with cache buster.
	 */
	public function get_thumbnail_url( $layout_id ) {
		// Check for WebP first (best for photographic/detailed previews).
		$webp_path = $this->thumbnail_base_path . $layout_id . '.webp';
		if ( file_exists( $webp_path ) ) {
			return $this->add_cache_buster( $this->thumbnail_base_url . $layout_id . '.webp', $webp_path );
		}

		// Then check for SVG (best for vector/simple previews).
		$svg_path = $this->thumbnail_base_path . $layout_id . '.svg';
		if ( file_exists( $svg_path ) ) {
			return $this->add_cache_buster( $this->thumbnail_base_url . $layout_id . '.svg', $svg_path );
		}

		// Check for PNG as fallback.
		$png_path = $this->thumbnail_base_path . $layout_id . '.png';
		if ( file_exists( $png_path ) ) {
			return $this->add_cache_buster( $this->thumbnail_base_url . $layout_id . '.png', $png_path );
		}

		// Return placeholder if no image found.
		return $this->add_cache_buster( $this->thumbnail_base_url . 'placeholder.svg', $this->thumbnail_base_path . 'placeholder.svg' );
	}

	/**
	 * Add cache buster to URL.
	 *
	 * In development mode (WP_DEBUG), uses file modification time.
	 * In production, uses plugin version.
	 *
	 * @since 2.2.0
	 *
	 * @param string $url       URL to add cache buster to.
	 * @param string $file_path Optional file path for modification time.
	 * @return string URL with cache buster query parameter.
	 */
	private function add_cache_buster( $url, $file_path = '' ) {
		$version = $this->cache_version;

		// In development, use file modification time for more precise cache busting.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && ! empty( $file_path ) && file_exists( $file_path ) ) {
			$version = (string) filemtime( $file_path );
		}

		return add_query_arg( 'ver', $version, $url );
	}

	/**
	 * Get all layouts.
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */
	public function get_all_layouts() {
		if ( empty( $this->layouts ) ) {
			$this->register_all_layouts();
		}
		
		// Process thumbnails with format detection and cache busting.
		return $this->process_layout_thumbnails( $this->layouts );
	}

	/**
	 * Process layout thumbnails with format detection and cache busting.
	 *
	 * Checks for WebP/SVG/PNG availability and adds cache buster.
	 *
	 * @since 2.2.0
	 *
	 * @param array $layouts Layouts to process.
	 * @return array Processed layouts with updated thumbnail URLs.
	 */
	private function process_layout_thumbnails( $layouts ) {
		$processed = array();

		foreach ( $layouts as $layout ) {
			// Use layout ID to get the best available thumbnail format.
			$layout['thumbnail'] = $this->get_thumbnail_url( $layout['id'] );
			$processed[]         = $layout;
		}

		return $processed;
	}

	/**
	 * Register all layouts.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	private function register_all_layouts() {
		// Register layouts for each template type.
		$this->register_single_product_layouts();
		$this->register_archive_product_layouts();
		$this->register_cart_layouts();
		$this->register_checkout_layouts();
		$this->register_my_account_layouts();
		$this->register_empty_cart_layouts();
		$this->register_thankyou_layouts();

		/**
		 * Fires after all layouts are registered.
		 *
		 * @since 2.1.0
		 *
		 * @param PreLayout_Library $library Library instance.
		 */
		do_action( 'mpd_prelayout_library_registered', $this );
	}

	/**
	 * Register Single Product layouts.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	private function register_single_product_layouts() {
		// Layout 1: Classic - Gallery Left, Details Right
		$this->layouts[] = array(
			'id'          => 'single-product-classic',
			'name'        => __( 'Classic Layout', 'magical-products-display' ),
			'description' => __( 'Traditional product page with gallery on the left and details on the right.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'single-product-classic.svg',
			'type'        => 'single-product',
			'category'    => 'basic',
			'is_pro'      => false,
			'widgets'     => array(
				'mpd-product-gallery',
				'mpd-product-title',
				'mpd-product-rating',
				'mpd-product-price',
				'mpd-product-short-description',
				'mpd-product-add-to-cart',
				'mpd-product-meta',
				'mpd-product-tabs',
				'mpd-related-products',
			),
		);

		// Layout 2: Modern - Full Width Gallery
		$this->layouts[] = array(
			'id'          => 'single-product-modern',
			'name'        => __( 'Modern Full Width', 'magical-products-display' ),
			'description' => __( 'Modern layout with full-width gallery and content below.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'single-product-modern.svg',
			'type'        => 'single-product',
			'category'    => 'modern',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-product-gallery',
				'mpd-product-title',
				'mpd-product-price',
				'mpd-product-add-to-cart',
				'mpd-product-description',
				'mpd-product-attributes',
				'mpd-related-products',
			),
		);

		// Layout 3: Minimal - Clean & Simple
		$this->layouts[] = array(
			'id'          => 'single-product-minimal',
			'name'        => __( 'Minimal Clean', 'magical-products-display' ),
			'description' => __( 'Clean, minimalist design focusing on the product.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'single-product-minimal.svg',
			'type'        => 'single-product',
			'category'    => 'minimal',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-product-gallery',
				'mpd-product-title',
				'mpd-product-price',
				'mpd-product-add-to-cart',
				'mpd-product-tabs',
			),
		);

		// Layout 4: Gallery Grid
		$this->layouts[] = array(
			'id'          => 'single-product-gallery-grid',
			'name'        => __( 'Gallery Grid', 'magical-products-display' ),
			'description' => __( 'Product gallery displayed in a grid layout with sticky cart.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'single-product-gallery-grid.svg',
			'type'        => 'single-product',
			'category'    => 'gallery',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-product-gallery',
				'mpd-product-title',
				'mpd-product-price',
				'mpd-product-short-description',
				'mpd-product-add-to-cart',
				'mpd-product-attributes',
				'mpd-product-tabs',
				'mpd-related-products',
			),
		);

		
	}

	/**
	 * Register Archive/Shop layouts.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	private function register_archive_product_layouts() {
		$this->layouts[] = array(
			'id'          => 'archive-product-grid',
			'name'        => __( 'Grid Layout', 'magical-products-display' ),
			'description' => __( 'Classic grid layout with sidebar filters.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'archive-product-grid.svg',
			'type'        => 'archive-product',
			'category'    => 'basic',
			'is_pro'      => false,
			'widgets'     => array(
				'mpd-shop-header',
				'mpd-products-grid',
				'mpd-shop-pagination',
			),
		);

		$this->layouts[] = array(
			'id'          => 'archive-product-list',
			'name'        => __( 'List Layout', 'magical-products-display' ),
			'description' => __( 'List view with detailed product information.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'archive-product-list.svg',
			'type'        => 'archive-product',
			'category'    => 'list',
			'is_pro'      => false,
			'widgets'     => array(
				'mpd-shop-header',
				'mpd-products-list',
				'mpd-shop-pagination',
			),
		);

		$this->layouts[] = array(
			'id'          => 'archive-product-masonry',
			'name'        => __( 'Masonry Grid', 'magical-products-display' ),
			'description' => __( 'Pinterest-style masonry layout.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'archive-product-masonry.svg',
			'type'        => 'archive-product',
			'category'    => 'creative',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-shop-header',
				'mpd-products-grid',
				'mpd-shop-pagination',
			),
		);

		$this->layouts[] = array(
			'id'          => 'archive-product-sidebar',
			'name'        => __( 'With Sidebar', 'magical-products-display' ),
			'description' => __( 'Products with sidebar for filters and categories.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'archive-product-sidebar.svg',
			'type'        => 'archive-product',
			'category'    => 'filtered',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-shop-header',
				'mpd-shop-filters',
				'mpd-products-grid',
				'mpd-shop-pagination',
			),
		);

		$this->layouts[] = array(
			'id'          => 'archive-product-fullwidth',
			'name'        => __( 'Full Width', 'magical-products-display' ),
			'description' => __( 'Full-width product showcase without sidebar.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'archive-product-fullwidth.svg',
			'type'        => 'archive-product',
			'category'    => 'modern',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-shop-header',
				'mpd-products-grid',
				'mpd-shop-pagination',
			),
		);
	}

	/**
	 * Register Cart layouts.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	private function register_cart_layouts() {
		$this->layouts[] = array(
			'id'          => 'cart-classic',
			'name'        => __( 'Classic Cart', 'magical-products-display' ),
			'description' => __( 'Traditional cart layout with table and totals.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'cart-classic.svg',
			'type'        => 'cart',
			'category'    => 'basic',
			'is_pro'      => false,
			'widgets'     => array(
				'mpd-cart-table',
				'mpd-cart-totals',
				'mpd-cross-sells',
			),
		);

		$this->layouts[] = array(
			'id'          => 'cart-modern',
			'name'        => __( 'Modern Cart', 'magical-products-display' ),
			'description' => __( 'Modern two-column cart layout.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'cart-modern.svg',
			'type'        => 'cart',
			'category'    => 'modern',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-cart-items',
				'mpd-cart-summary',
				'mpd-cross-sells',
			),
		);

		$this->layouts[] = array(
			'id'          => 'cart-minimal',
			'name'        => __( 'Minimal Cart', 'magical-products-display' ),
			'description' => __( 'Clean, minimalist cart design.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'cart-minimal.svg',
			'type'        => 'cart',
			'category'    => 'minimal',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-cart-table',
				'mpd-coupon-form',
				'mpd-cart-totals',
				'mpd-cross-sells',
			),
		);

		$this->layouts[] = array(
			'id'          => 'cart-sticky-summary',
			'name'        => __( 'Sticky Summary', 'magical-products-display' ),
			'description' => __( 'Cart with sticky order summary sidebar.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'cart-sticky-summary.svg',
			'type'        => 'cart',
			'category'    => 'modern',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-cart-table',
				'mpd-cart-totals',
				'mpd-coupon-form',
				'mpd-cross-sells',
			),
		);
	}

	/**
	 * Register Checkout layouts.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	private function register_checkout_layouts() {
		$this->layouts[] = array(
			'id'          => 'checkout-classic',
			'name'        => __( 'Classic Checkout', 'magical-products-display' ),
			'description' => __( 'Traditional checkout with billing and shipping forms.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'checkout-classic.svg',
			'type'        => 'checkout',
			'category'    => 'basic',
			'is_pro'      => false,
			'widgets'     => array(
				'mpd-checkout-billing',
				'mpd-checkout-shipping',
				'mpd-checkout-order-review',
				'mpd-checkout-payment',
			),
		);

		$this->layouts[] = array(
			'id'          => 'checkout-two-column',
			'name'        => __( 'Two Column', 'magical-products-display' ),
			'description' => __( 'Modern two-column checkout layout.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'checkout-two-column.svg',
			'type'        => 'checkout',
			'category'    => 'modern',
			'is_pro'      => false,
			'widgets'     => array(
				'mpd-checkout-billing',
				'mpd-checkout-shipping',
				'mpd-checkout-order-review',
				'mpd-checkout-payment',
			),
		);

		$this->layouts[] = array(
			'id'          => 'checkout-multi-step',
			'name'        => __( 'Multi-Step', 'magical-products-display' ),
			'description' => __( 'Step-by-step checkout wizard.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'checkout-multi-step.svg',
			'type'        => 'checkout',
			'category'    => 'wizard',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-checkout-steps',
				'mpd-checkout-billing',
				'mpd-checkout-shipping',
				'mpd-checkout-order-review',
				'mpd-checkout-payment',
			),
		);

		$this->layouts[] = array(
			'id'          => 'checkout-express',
			'name'        => __( 'Express Checkout', 'magical-products-display' ),
			'description' => __( 'Streamlined checkout for faster conversions.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'checkout-express.svg',
			'type'        => 'checkout',
			'category'    => 'express',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-checkout-express',
				'mpd-checkout-order-review',
				'mpd-checkout-payment',
			),
		);
	}

	/**
	 * Register My Account layouts.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	private function register_my_account_layouts() {
		$this->layouts[] = array(
			'id'          => 'my-account-classic',
			'name'        => __( 'Classic Account', 'magical-products-display' ),
			'description' => __( 'Traditional my account layout with sidebar navigation.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'my-account-classic.svg',
			'type'        => 'my-account',
			'category'    => 'basic',
			'is_pro'      => false,
			'widgets'     => array(
				'mpd-account-navigation',
				'mpd-account-content',
			),
		);

		$this->layouts[] = array(
			'id'          => 'my-account-dashboard',
			'name'        => __( 'Dashboard Style', 'magical-products-display' ),
			'description' => __( 'Modern dashboard-style account page.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'my-account-dashboard.svg',
			'type'        => 'my-account',
			'category'    => 'modern',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-account-dashboard',
				'mpd-account-orders',
				'mpd-account-details',
			),
		);

		$this->layouts[] = array(
			'id'          => 'my-account-tabs',
			'name'        => __( 'Tabbed Layout', 'magical-products-display' ),
			'description' => __( 'Account sections organized in tabs.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'my-account-tabs.svg',
			'type'        => 'my-account',
			'category'    => 'organized',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-account-tabs',
				'mpd-account-content',
			),
		);
	}

	/**
	 * Register Empty Cart layouts.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	private function register_empty_cart_layouts() {
		$this->layouts[] = array(
			'id'          => 'empty-cart-simple',
			'name'        => __( 'Simple Empty Cart', 'magical-products-display' ),
			'description' => __( 'Simple message with return to shop button.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'empty-cart-simple.svg',
			'type'        => 'empty-cart',
			'category'    => 'basic',
			'is_pro'      => false,
			'widgets'     => array(
				'mpd-empty-cart-message',
				'mpd-return-to-shop',
			),
		);

		$this->layouts[] = array(
			'id'          => 'empty-cart-with-products',
			'name'        => __( 'With Product Suggestions', 'magical-products-display' ),
			'description' => __( 'Empty cart with featured product suggestions.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'empty-cart-products.svg',
			'type'        => 'empty-cart',
			'category'    => 'promotional',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-empty-cart-message',
				'mpd-return-to-shop',
				'mpd-products-grid',
			),
		);

	}

	/**
	 * Register Thank You layouts.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	private function register_thankyou_layouts() {
		$this->layouts[] = array(
			'id'          => 'thankyou-classic',
			'name'        => __( 'Classic Thank You', 'magical-products-display' ),
			'description' => __( 'Traditional order confirmation page.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'thankyou-classic.svg',
			'type'        => 'thankyou',
			'category'    => 'basic',
			'is_pro'      => false,
			'widgets'     => array(
				'mpd-order-confirmation',
				'mpd-order-details',
				'mpd-order-customer-details',
			),
		);

		$this->layouts[] = array(
			'id'          => 'thankyou-modern',
			'name'        => __( 'Modern Confirmation', 'magical-products-display' ),
			'description' => __( 'Modern order confirmation with progress indicator.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'thankyou-modern.svg',
			'type'        => 'thankyou',
			'category'    => 'modern',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-order-confirmation',
				'mpd-order-progress',
				'mpd-order-details',
				'mpd-order-map',
			),
		);

		$this->layouts[] = array(
			'id'          => 'thankyou-promotional',
			'name'        => __( 'With Promotions', 'magical-products-display' ),
			'description' => __( 'Thank you page with upsells and promotions.', 'magical-products-display' ),
			'thumbnail'   => $this->thumbnail_base_url . 'thankyou-promotional.svg',
			'type'        => 'thankyou',
			'category'    => 'promotional',
			'is_pro'      => true,
			'widgets'     => array(
				'mpd-order-confirmation',
				'mpd-order-details',
				'mpd-upsells',
				'mpd-newsletter-signup',
			),
		);
	}

	/**
	 * Get layout by ID.
	 *
	 * @since 2.1.0
	 *
	 * @param string $layout_id Layout ID.
	 * @return array|null
	 */
	public function get_layout( $layout_id ) {
		$layouts = $this->get_all_layouts();
		
		foreach ( $layouts as $layout ) {
			if ( $layout['id'] === $layout_id ) {
				return $layout;
			}
		}
		
		return null;
	}

	/**
	 * Get layouts by type.
	 *
	 * @since 2.1.0
	 *
	 * @param string $type Template type.
	 * @return array
	 */
	public function get_layouts_by_type( $type ) {
		$layouts = $this->get_all_layouts();
		
		$local_layouts = array_filter( $layouts, function( $layout ) use ( $type ) {
			return $layout['type'] === $type;
		} );
		
		// Merge with remote layouts if available.
		return $this->merge_remote_layouts( array_values( $local_layouts ), $type );
	}

	/**
	 * Merge local layouts with remote layouts.
	 *
	 * @since 2.1.0
	 *
	 * @param array  $local_layouts Local layouts.
	 * @param string $type          Layout type.
	 * @return array
	 */
	private function merge_remote_layouts( $local_layouts, $type = '' ) {
		// Ensure remote client is loaded.
		if ( ! class_exists( 'MPD_Remote_Layout_Client' ) ) {
			$client_file = MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/templates/class-mpd-remote-layout-client.php';
			if ( file_exists( $client_file ) ) {
				require_once $client_file;
			} else {
				return $local_layouts;
			}
		}
		
		$client = mpd_remote_layout_client();
		
		// Skip if remote server not configured.
		if ( ! $client->is_configured() ) {
			return $local_layouts;
		}
		
		return $client->get_merged_layouts( $local_layouts, $type );
	}

	/**
	 * Get layout preview data.
	 *
	 * @since 2.1.0
	 *
	 * @param string $layout_id Layout ID.
	 * @return array
	 */
	public function get_layout_preview( $layout_id ) {
		$layout = $this->get_layout( $layout_id );
		
		if ( ! $layout ) {
			return array();
		}
		
		return array(
			'id'         => $layout['id'],
			'name'       => $layout['name'],
			'screenshot' => $this->get_layout_screenshot( $layout_id ),
			'widgets'    => $layout['widgets'] ?? array(),
			'features'   => $this->get_layout_features( $layout ),
		);
	}

	/**
	 * Get layout screenshot URL.
	 *
	 * Checks for WebP, PNG, JPG formats and adds cache busting.
	 *
	 * @since 2.1.0
	 *
	 * @param string $layout_id Layout ID.
	 * @return string
	 */
	private function get_layout_screenshot( $layout_id ) {
		$screenshot_base_path = MAGICAL_PRODUCTS_DISPLAY_DIR . 'assets/images/prelayouts/screenshots/';
		$screenshot_base_url  = MAGICAL_PRODUCTS_DISPLAY_URL . 'assets/images/prelayouts/screenshots/';
		
		// Check formats in priority order: WebP, PNG, JPG.
		$formats = array( 'webp', 'png', 'jpg', 'jpeg' );
		
		foreach ( $formats as $format ) {
			$screenshot_path = $screenshot_base_path . $layout_id . '.' . $format;
			
			if ( file_exists( $screenshot_path ) ) {
				return $this->add_cache_buster( $screenshot_base_url . $layout_id . '.' . $format, $screenshot_path );
			}
		}
		
		// Return placeholder if no screenshot found.
		$placeholder_path = $this->thumbnail_base_path . 'placeholder.svg';
		return $this->add_cache_buster( $this->thumbnail_base_url . 'placeholder.svg', $placeholder_path );
	}

	/**
	 * Get layout features list.
	 *
	 * @since 2.1.0
	 *
	 * @param array $layout Layout data.
	 * @return array
	 */
	private function get_layout_features( $layout ) {
		$features = array();
		
		$widget_features = array(
			'mpd-product-gallery'     => __( 'Product Gallery', 'magical-products-display' ),
			'mpd-product-tabs'        => __( 'Product Tabs', 'magical-products-display' ),
			'mpd-related-products'    => __( 'Related Products', 'magical-products-display' ),
			'mpd-upsells'             => __( 'Upsell Products', 'magical-products-display' ),
			'mpd-product-reviews'     => __( 'Customer Reviews', 'magical-products-display' ),
			'mpd-accordion-widget'    => __( 'Accordion Sections', 'magical-products-display' ),
			'mpd-product-attributes'  => __( 'Product Attributes', 'magical-products-display' ),
		);
		
		foreach ( $layout['widgets'] ?? array() as $widget ) {
			if ( isset( $widget_features[ $widget ] ) ) {
				$features[] = $widget_features[ $widget ];
			}
		}
		
		return array_slice( $features, 0, 5 ); // Max 5 features.
	}

	/**
	 * Get layout Elementor data.
	 *
	 * Returns the Elementor-compatible structure for the layout.
	 * First checks local files, then remote server if layout not found locally.
	 *
	 * @since 2.1.0
	 *
	 * @param string $layout_id Layout ID.
	 * @return array
	 */
	public function get_layout_elementor_data( $layout_id ) {
		$layout = $this->get_layout( $layout_id );
		
		if ( ! $layout ) {
			return array();
		}
		
		// Get the layout type to determine subfolder.
		$layout_type = $layout['type'] ?? '';
		
		// 1. First try local JSON file from type-specific subfolder.
		$structure_file = MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/templates/prelayouts/structures/' . $layout_type . '/' . $layout_id . '.json';
		
		if ( file_exists( $structure_file ) ) {
			global $wp_filesystem;
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			WP_Filesystem();
			$content = $wp_filesystem->get_contents( $structure_file );
			$data    = json_decode( $content, true );
			
			if ( json_last_error() === JSON_ERROR_NONE ) {
				return $data;
			}
		}
		
		// 2. If not local, try remote server.
		if ( isset( $layout['source'] ) && 'remote' === $layout['source'] ) {
			$remote_data = $this->get_remote_layout_structure( $layout_id );
			if ( ! empty( $remote_data ) ) {
				return $remote_data;
			}
		}
		
		// 3. Generate basic structure from widget list as fallback.
		return $this->generate_basic_structure( $layout );
	}

	/**
	 * Get layout structure from remote server.
	 *
	 * @since 2.1.0
	 *
	 * @param string $layout_id Layout ID.
	 * @return array
	 */
	private function get_remote_layout_structure( $layout_id ) {
		// Ensure remote client is loaded.
		if ( ! class_exists( 'MPD_Remote_Layout_Client' ) ) {
			$client_file = MAGICAL_PRODUCTS_DISPLAY_DIR . 'includes/templates/class-mpd-remote-layout-client.php';
			if ( file_exists( $client_file ) ) {
				require_once $client_file;
			} else {
				return array();
			}
		}
		
		$client   = mpd_remote_layout_client();
		$response = $client->get_layout_structure( $layout_id );
		
		if ( is_wp_error( $response ) || empty( $response['structure'] ) ) {
			return array();
		}
		
		return $response['structure'];
	}

	/**
	 * Generate basic Elementor structure from widget list.
	 *
	 * @since 2.1.0
	 *
	 * @param array $layout Layout data.
	 * @return array
	 */
	private function generate_basic_structure( $layout ) {
		$elements = array();
		
		foreach ( $layout['widgets'] ?? array() as $widget_name ) {
			$elements[] = array(
				'id'         => $this->generate_element_id(),
				'elType'     => 'widget',
				'widgetType' => $widget_name,
				'settings'   => $this->get_widget_default_settings( $widget_name ),
				'elements'   => array(),
			);
		}
		
		// Wrap in section and column structure.
		return array(
			array(
				'id'       => $this->generate_element_id(),
				'elType'   => 'container',
				'settings' => array(
					'content_width' => 'boxed',
				),
				'elements' => $elements,
			),
		);
	}

	/**
	 * Generate unique element ID.
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */
	private function generate_element_id() {
		return substr( md5( wp_generate_uuid4() ), 0, 8 );
	}

	/**
	 * Get widget default settings.
	 *
	 * @since 2.1.0
	 *
	 * @param string $widget_name Widget name.
	 * @return array
	 */
	private function get_widget_default_settings( $widget_name ) {
		// Return empty settings - widget will use its own defaults.
		// No demo data, just structure.
		return array();
	}
}
