<?php
/**
 * Pre-Layout Importer
 *
 * Handles importing pre-defined layouts into Elementor templates.
 * Imports only structure without demo data.
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
 * Class PreLayout_Importer
 *
 * Imports pre-layouts into Elementor templates.
 *
 * @since 2.1.0
 */
class PreLayout_Importer {

	/**
	 * Layout library instance.
	 *
	 * @var PreLayout_Library|null
	 */
	private $library = null;

	/**
	 * Constructor.
	 *
	 * @since 2.1.0
	 */
	public function __construct() {
		$this->library = new PreLayout_Library();
	}

	/**
	 * Import a layout into a template.
	 *
	 * @since 2.1.0
	 *
	 * @param string $layout_id   Layout ID to import.
	 * @param int    $template_id Template post ID.
	 * @return bool|\WP_Error
	 */
	public function import_layout( $layout_id, $template_id ) {
		// Verify template exists.
		$template = get_post( $template_id );
		
		if ( ! $template || 'mpd_template' !== $template->post_type ) {
			return new \WP_Error(
				'invalid_template',
				__( 'Invalid template ID.', 'magical-products-display' )
			);
		}

		// Get layout data.
		$layout = $this->library->get_layout( $layout_id );
		
		if ( ! $layout ) {
			return new \WP_Error(
				'layout_not_found',
				__( 'Layout not found.', 'magical-products-display' )
			);
		}

		// Get Elementor data for this layout.
		$elementor_data = $this->library->get_layout_elementor_data( $layout_id );
		
		if ( empty( $elementor_data ) ) {
			// Generate from widgets list.
			$elementor_data = $this->generate_elementor_data( $layout );
		}

		// Process the data to ensure no demo content.
		$elementor_data = $this->sanitize_layout_data( $elementor_data );

		// Regenerate element IDs for uniqueness.
		$elementor_data = $this->regenerate_element_ids( $elementor_data );

		// Import to Elementor.
		$result = $this->save_elementor_data( $template_id, $elementor_data );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Update template meta with layout info.
		update_post_meta( $template_id, '_mpd_prelayout_id', $layout_id );
		update_post_meta( $template_id, '_mpd_prelayout_imported', current_time( 'mysql' ) );

		/**
		 * Fires after a layout is imported.
		 *
		 * @since 2.1.0
		 *
		 * @param int    $template_id Template post ID.
		 * @param string $layout_id   Layout ID.
		 * @param array  $layout      Layout data.
		 */
		do_action( 'mpd_prelayout_imported', $template_id, $layout_id, $layout );

		return true;
	}

	/**
	 * Generate Elementor data from layout configuration.
	 *
	 * @since 2.1.0
	 *
	 * @param array $layout Layout configuration.
	 * @return array
	 */
	private function generate_elementor_data( $layout ) {
		$template_type = $layout['type'] ?? 'single-product';
		$widgets       = $layout['widgets'] ?? array();

		// Get layout structure generator.
		$generator = $this->get_structure_generator( $template_type );

		return $generator->generate( $widgets, $layout );
	}

	/**
	 * Get structure generator for template type.
	 *
	 * @since 2.1.0
	 *
	 * @param string $template_type Template type.
	 * @return Layout_Structure_Generator
	 */
	private function get_structure_generator( $template_type ) {
		return new Layout_Structure_Generator( $template_type );
	}

	/**
	 * Sanitize layout data to ensure no demo content.
	 *
	 * @since 2.1.0
	 *
	 * @param array $data Elementor data.
	 * @return array
	 */
	private function sanitize_layout_data( $data ) {
		if ( ! is_array( $data ) ) {
			return $data;
		}

		foreach ( $data as $key => &$element ) {
			if ( is_array( $element ) ) {
				// Remove demo-specific settings.
				if ( isset( $element['settings'] ) ) {
					$element['settings'] = $this->sanitize_settings( $element['settings'] );
				}

				// Recursively process child elements.
				if ( isset( $element['elements'] ) ) {
					$element['elements'] = $this->sanitize_layout_data( $element['elements'] );
				}
			}
		}

		return $data;
	}

	/**
	 * Sanitize widget settings.
	 *
	 * Removes demo data and keeps only structural settings.
	 *
	 * @since 2.1.0
	 *
	 * @param array $settings Widget settings.
	 * @return array
	 */
	private function sanitize_settings( $settings ) {
		// List of settings to preserve (structural, not content).
		$preserve_keys = array(
			// Layout settings.
			'content_width',
			'width',
			'min_height',
			'height',
			'column_gap',
			'row_gap',
			'gap',
			'columns',
			'flex_direction',
			'flex_wrap',
			'justify_content',
			'align_items',
			'align_content',
			
			// Styling settings.
			'background_background',
			'background_color',
			'border_border',
			'border_width',
			'border_color',
			'border_radius',
			'box_shadow_box_shadow',
			'padding',
			'margin',
			
			// Typography.
			'typography_typography',
			'typography_font_family',
			'typography_font_size',
			'typography_font_weight',
			'text_align',
			'color',
			
			// Widget-specific structural settings.
			'layout',
			'style',
			'columns_count',
			'rows_gap',
			'gallery_layout',
			'enable_thumbnails',
			'thumbnail_position',
			'show_arrows',
			'show_dots',
			'autoplay',
			
			// Visibility and conditions.
			'hide_on',
			'hide_desktop',
			'hide_tablet',
			'hide_mobile',
			
			// Container settings.
			'container_type',
			'content_position',
			'overflow',
			'html_tag',
			
			// Grid container settings.
			'grid_columns_grid',
			'grid_rows_grid',
			'grid_gaps',
			'grid_auto_flow',
			
			// Widget grid settings.
			'posts_per_page',
			'orderby',
			'order',
			
			// Product-specific structural settings.
			'gallery_columns',
			'sale_flash',
			'variation_display',
			'quantity_label',
			'tabs_layout',
			'reviews_display',
		);

		// Content keys to remove (demo data).
		$remove_keys = array(
			'title',
			'description',
			'content',
			'text',
			'html',
			'image',
			'link',
			'url',
			'product_id',
			'category_id',
			'custom_product_ids',
			'selected_products',
			'demo_',
			'sample_',
		);

		$sanitized = array();

		// Explicitly preserve critical layout settings.
		$critical_settings = array( 
			'content_width', 
			'width', 
			'width_tablet', 
			'width_mobile',
			'flex_direction',
			'flex_direction_tablet', 
			'flex_direction_mobile',
			'_element_width',
			'_element_width_tablet',
			'_element_width_mobile',
		);
		foreach ( $critical_settings as $setting ) {
			if ( isset( $settings[ $setting ] ) ) {
				$sanitized[ $setting ] = $settings[ $setting ];
			}
		}

		foreach ( $settings as $key => $value ) {
			// Check if key should be removed.
			$should_remove = false;
			foreach ( $remove_keys as $remove_key ) {
				if ( strpos( $key, $remove_key ) === 0 ) {
					$should_remove = true;
					break;
				}
			}

			if ( $should_remove ) {
				continue;
			}

			// Keep structural settings.
			foreach ( $preserve_keys as $preserve_key ) {
				if ( strpos( $key, $preserve_key ) !== false ) {
					$sanitized[ $key ] = $value;
					break;
				}
			}
		}

		return $sanitized;
	}

	/**
	 * Regenerate element IDs for uniqueness.
	 *
	 * @since 2.1.0
	 *
	 * @param array $data Elementor data.
	 * @return array
	 */
	private function regenerate_element_ids( $data ) {
		if ( ! is_array( $data ) ) {
			return $data;
		}

		foreach ( $data as &$element ) {
			if ( is_array( $element ) ) {
				// Generate new ID.
				if ( isset( $element['id'] ) ) {
					$element['id'] = $this->generate_unique_id();
				}

				// Process child elements.
				if ( isset( $element['elements'] ) ) {
					$element['elements'] = $this->regenerate_element_ids( $element['elements'] );
				}
			}
		}

		return $data;
	}

	/**
	 * Generate unique element ID.
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */
	private function generate_unique_id() {
		return substr( md5( uniqid( wp_rand(), true ) ), 0, 8 );
	}

	/**
	 * Save Elementor data to template.
	 *
	 * @since 2.1.0
	 *
	 * @param int   $template_id Template post ID.
	 * @param array $data        Elementor data.
	 * @return bool|\WP_Error
	 */
	private function save_elementor_data( $template_id, $data ) {
		// Ensure Elementor is loaded.
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return new \WP_Error(
				'elementor_not_found',
				__( 'Elementor is required to import layouts.', 'magical-products-display' )
			);
		}

		try {
			// Encode data as JSON.
			$json_data = wp_json_encode( $data );

			if ( false === $json_data ) {
				return new \WP_Error(
					'json_encode_error',
					__( 'Failed to encode layout data.', 'magical-products-display' )
				);
			}

			// Save Elementor data.
			update_post_meta( $template_id, '_elementor_data', $json_data );
			update_post_meta( $template_id, '_elementor_edit_mode', 'builder' );
			update_post_meta( $template_id, '_elementor_version', ELEMENTOR_VERSION );

			// Ensure page template is set.
			$template = get_post_meta( $template_id, '_wp_page_template', true );
			if ( empty( $template ) ) {
				update_post_meta( $template_id, '_wp_page_template', 'elementor_canvas' );
			}

			// Clear Elementor cache for this post.
			if ( class_exists( '\Elementor\Plugin' ) ) {
				\Elementor\Plugin::$instance->files_manager->clear_cache();
			}

			return true;

		} catch ( \Exception $e ) {
			return new \WP_Error(
				'import_error',
				$e->getMessage()
			);
		}
	}

	/**
	 * Get import preview.
	 *
	 * @since 2.1.0
	 *
	 * @param string $layout_id Layout ID.
	 * @return array
	 */
	public function get_import_preview( $layout_id ) {
		$layout = $this->library->get_layout( $layout_id );
		
		if ( ! $layout ) {
			return array();
		}

		return array(
			'widgets'     => $layout['widgets'] ?? array(),
			'widget_info' => $this->get_widgets_info( $layout['widgets'] ?? array() ),
		);
	}

	/**
	 * Get widgets information.
	 *
	 * @since 2.1.0
	 *
	 * @param array $widgets Widget names.
	 * @return array
	 */
	private function get_widgets_info( $widgets ) {
		$widget_labels = array(
			'mpd-product-gallery'           => __( 'Product Gallery', 'magical-products-display' ),
			'mpd-product-title'             => __( 'Product Title', 'magical-products-display' ),
			'mpd-product-price'             => __( 'Product Price', 'magical-products-display' ),
			'mpd-product-rating'            => __( 'Product Rating', 'magical-products-display' ),
			'mpd-product-short-description' => __( 'Short Description', 'magical-products-display' ),
			'mpd-product-description'       => __( 'Full Description', 'magical-products-display' ),
			'mpd-product-add-to-cart'       => __( 'Add to Cart', 'magical-products-display' ),
			'mpd-product-meta'              => __( 'Product Meta', 'magical-products-display' ),
			'mpd-product-stock'             => __( 'Stock Status', 'magical-products-display' ),
			'mpd-product-tabs'              => __( 'Product Tabs', 'magical-products-display' ),
			'mpd-product-attributes'        => __( 'Product Attributes', 'magical-products-display' ),
			'mpd-product-reviews'           => __( 'Customer Reviews', 'magical-products-display' ),
			'mpd-product-navigation'        => __( 'Product Navigation', 'magical-products-display' ),
			'mpd-related-products'          => __( 'Related Products', 'magical-products-display' ),
			'mpd-upsells'                   => __( 'Upsell Products', 'magical-products-display' ),
			'mpd-cross-sells'               => __( 'Cross-sell Products', 'magical-products-display' ),
			'mpd-accordion-widget'          => __( 'Accordion', 'magical-products-display' ),
			'mpd-products-grid'             => __( 'Products Grid', 'magical-products-display' ),
			'mpd-products-list'             => __( 'Products List', 'magical-products-display' ),
			'mpd-shop-header'               => __( 'Shop Header', 'magical-products-display' ),
			'mpd-shop-filters'              => __( 'Shop Filters', 'magical-products-display' ),
			'mpd-shop-pagination'           => __( 'Shop Pagination', 'magical-products-display' ),
			'mpd-cart-table'                => __( 'Cart Table', 'magical-products-display' ),
			'mpd-cart-items'                => __( 'Cart Items', 'magical-products-display' ),
			'mpd-cart-totals'               => __( 'Cart Totals', 'magical-products-display' ),
			'mpd-cart-summary'              => __( 'Cart Summary', 'magical-products-display' ),
			'mpd-coupon-form'               => __( 'Coupon Form', 'magical-products-display' ),
			'mpd-checkout-billing'          => __( 'Billing Details', 'magical-products-display' ),
			'mpd-checkout-shipping'         => __( 'Shipping Details', 'magical-products-display' ),
			'mpd-checkout-order-review'     => __( 'Order Review', 'magical-products-display' ),
			'mpd-checkout-payment'          => __( 'Payment Methods', 'magical-products-display' ),
			'mpd-checkout-steps'            => __( 'Checkout Steps', 'magical-products-display' ),
			'mpd-checkout-express'          => __( 'Express Checkout', 'magical-products-display' ),
			'mpd-account-navigation'        => __( 'Account Navigation', 'magical-products-display' ),
			'mpd-account-content'           => __( 'Account Content', 'magical-products-display' ),
			'mpd-account-dashboard'         => __( 'Account Dashboard', 'magical-products-display' ),
			'mpd-account-orders'            => __( 'Account Orders', 'magical-products-display' ),
			'mpd-account-details'           => __( 'Account Details', 'magical-products-display' ),
			'mpd-account-tabs'              => __( 'Account Tabs', 'magical-products-display' ),
			'mpd-empty-cart-message'        => __( 'Empty Cart Message', 'magical-products-display' ),
			'mpd-empty-cart-illustration'   => __( 'Empty Cart Illustration', 'magical-products-display' ),
			'mpd-return-to-shop'            => __( 'Return to Shop', 'magical-products-display' ),
			'mpd-order-confirmation'        => __( 'Order Confirmation', 'magical-products-display' ),
			'mpd-order-details'             => __( 'Order Details', 'magical-products-display' ),
			'mpd-order-customer-details'    => __( 'Customer Details', 'magical-products-display' ),
			'mpd-order-progress'            => __( 'Order Progress', 'magical-products-display' ),
			'mpd-order-map'                 => __( 'Order Map', 'magical-products-display' ),
			'mpd-newsletter-signup'         => __( 'Newsletter Signup', 'magical-products-display' ),
		);

		$info = array();

		foreach ( $widgets as $widget ) {
			$info[] = array(
				'id'    => $widget,
				'label' => $widget_labels[ $widget ] ?? $widget,
			);
		}

		return $info;
	}
}


/**
 * Class Layout_Structure_Generator
 *
 * Generates Elementor-compatible layout structures.
 * 
 * NOTE: This class is now a minimal fallback. All layouts are defined in JSON files
 * located at includes/templates/prelayouts/structures/*.json
 * 
 * JSON files are loaded first by PreLayout_Library::get_layout_elementor_data().
 * This generator is only used when a JSON file doesn't exist.
 *
 * @since 2.1.0
 */
class Layout_Structure_Generator {

	/**
	 * Template type.
	 *
	 * @var string
	 */
	private $template_type;

	/**
	 * Constructor.
	 *
	 * @since 2.1.0
	 *
	 * @param string $template_type Template type.
	 */
	public function __construct( $template_type ) {
		$this->template_type = $template_type;
	}

	/**
	 * Generate layout structure.
	 *
	 * This is a fallback method. All layouts should have JSON files.
	 * If you're seeing this fallback used, add a JSON file for the layout.
	 *
	 * @since 2.1.0
	 *
	 * @param array $widgets Widget names.
	 * @param array $layout  Layout configuration.
	 * @return array
	 */
	public function generate( $widgets, $layout ) {
		// All layouts should have JSON files. This is a basic fallback.
		return $this->generate_basic_fallback( $widgets );
	}

	/**
	 * Generate basic fallback structure.
	 *
	 * Creates a simple vertical container with all widgets stacked.
	 *
	 * @since 2.1.0
	 *
	 * @param array $widgets Widget names.
	 * @return array
	 */
	private function generate_basic_fallback( $widgets ) {
		$widget_elements = array();

		foreach ( $widgets as $widget_name ) {
			$widget_elements[] = array(
				'id'         => $this->generate_id(),
				'elType'     => 'widget',
				'isInner'    => false,
				'widgetType' => $widget_name,
				'settings'   => array(),
				'elements'   => array(),
			);
		}

		// Create a simple vertical container structure.
		return array(
			array(
				'id'       => $this->generate_id(),
				'elType'   => 'container',
				'isInner'  => false,
				'settings' => array(
					'flex_direction'        => 'column',
					'content_width'         => 'boxed',
					'flex_gap'              => array(
						'column'   => '20',
						'row'      => '20',
						'isLinked' => true,
						'unit'     => 'px',
						'size'     => 20,
					),
					'flex_direction_mobile' => 'column',
					'flex_wrap_mobile'      => 'wrap',
				),
				'elements' => $widget_elements,
			),
		);
	}

	/**
	 * Generate unique ID.
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */
	private function generate_id() {
		return substr( md5( uniqid( wp_rand(), true ) ), 0, 8 );
	}
}
