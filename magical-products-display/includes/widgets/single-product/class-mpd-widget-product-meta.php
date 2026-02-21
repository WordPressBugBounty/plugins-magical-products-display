<?php
/**
 * Product Meta Widget
 *
 * Displays product meta information (SKU, categories, tags) on single product pages.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\SingleProduct;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Product_Meta
 *
 * @since 2.0.0
 */
class Product_Meta extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_SINGLE_PRODUCT;

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-product-meta';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Product Meta', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-meta';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'meta', 'sku', 'category', 'tag', 'woocommerce', 'single' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-single-product' );
	}

	/**
	 * Get available product meta keys.
	 *
	 * Retrieves all unique meta keys from published products that have non-empty values.
	 *
	 * @since 2.0.0
	 *
	 * @return array Associative array of meta keys.
	 */
	private function get_available_meta_keys() {
		static $meta_keys = null;

		if ( null !== $meta_keys ) {
			return $meta_keys;
		}

		$meta_keys = array(
			'' => __( '— Select Meta Key —', 'magical-products-display' ),
		);

		global $wpdb;

		// Common WooCommerce meta keys - only add if they have non-empty values.
		$common_keys = array(
			'_sku'              => __( 'SKU', 'magical-products-display' ),
			'_regular_price'    => __( 'Regular Price', 'magical-products-display' ),
			'_sale_price'       => __( 'Sale Price', 'magical-products-display' ),
			'_weight'           => __( 'Weight', 'magical-products-display' ),
			'_length'           => __( 'Length', 'magical-products-display' ),
			'_width'            => __( 'Width', 'magical-products-display' ),
			'_height'           => __( 'Height', 'magical-products-display' ),
			'_stock'            => __( 'Stock Quantity', 'magical-products-display' ),
			'_low_stock_amount' => __( 'Low Stock Amount', 'magical-products-display' ),
			'total_sales'       => __( 'Total Sales', 'magical-products-display' ),
		);

		// Check which common keys have non-empty values.
		$common_keys_list = array_keys( $common_keys );
		$common_placeholders = implode( ',', array_fill( 0, count( $common_keys_list ), '%s' ) );

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$existing_common_keys = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT pm.meta_key
				FROM {$wpdb->postmeta} pm
				INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				WHERE p.post_type IN ('product', 'product_variation')
				AND p.post_status = 'publish'
				AND pm.meta_key IN ($common_placeholders)
				AND pm.meta_value IS NOT NULL
				AND pm.meta_value != ''
				AND pm.meta_value != '0'",
				$common_keys_list
			)
		);

		// Add only common keys that have values.
		$has_common = false;
		foreach ( $common_keys as $key => $label ) {
			if ( in_array( $key, $existing_common_keys, true ) ) {
				if ( ! $has_common ) {
					$meta_keys['wc_separator'] = '── ' . __( 'WooCommerce Fields', 'magical-products-display' ) . ' ──';
					$has_common = true;
				}
				$meta_keys[ $key ] = $label . ' (' . $key . ')';
			}
		}

		// Get custom meta keys from products (excluding internal WC keys).
		$exclude_keys = array(
			'_edit_lock',
			'_edit_last',
			'_wp_old_slug',
			'_thumbnail_id',
			'_product_image_gallery',
			'_product_attributes',
			'_downloadable_files',
			'_children',
			'_variation_description',
			'_manage_stock',
			'_backorders',
			'_sold_individually',
			'_virtual',
			'_downloadable',
			'_download_limit',
			'_download_expiry',
			'_stock_status',
			'_wc_average_rating',
			'_wc_review_count',
			'_product_version',
			'_price',
			'_tax_status',
			'_tax_class',
			'_purchase_note',
			'_upsell_ids',
			'_crosssell_ids',
			'_default_attributes',
		);

		// Also exclude already listed common keys.
		$exclude_keys = array_merge( $exclude_keys, array_keys( $common_keys ) );

		$exclude_placeholders = implode( ',', array_fill( 0, count( $exclude_keys ), '%s' ) );

		// Get custom meta keys that have non-empty values.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$custom_keys = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT pm.meta_key
				FROM {$wpdb->postmeta} pm
				INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
				WHERE p.post_type IN ('product', 'product_variation')
				AND p.post_status = 'publish'
				AND pm.meta_key NOT IN ($exclude_placeholders)
				AND pm.meta_key NOT LIKE %s
				AND pm.meta_value IS NOT NULL
				AND pm.meta_value != ''
				ORDER BY pm.meta_key
				LIMIT 50",
				array_merge( $exclude_keys, array( '\_oembed%' ) )
			)
		);

		if ( ! empty( $custom_keys ) ) {
			$meta_keys['custom_separator'] = '── ' . __( 'Custom Fields', 'magical-products-display' ) . ' ──';
			foreach ( $custom_keys as $key ) {
				// Create a readable label from the key.
				$label = ucwords( str_replace( array( '_', '-' ), ' ', ltrim( $key, '_' ) ) );
				$meta_keys[ $key ] = $label . ' (' . $key . ')';
			}
		}

		// Add option for manual entry.
		$meta_keys['__custom__'] = '── ' . __( 'Enter Custom Key...', 'magical-products-display' ) . ' ──';

		return $meta_keys;
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Meta Items', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_sku',
			array(
				'label'        => __( 'Show SKU', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_category',
			array(
				'label'        => __( 'Show Category', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_tags',
			array(
				'label'        => __( 'Show Tags', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'        => __( 'Layout', 'magical-products-display' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'stacked' => __( 'Stacked', 'magical-products-display' ),
					'inline'  => __( 'Inline', 'magical-products-display' ),
				),
				'default'      => 'stacked',
				'prefix_class' => 'mpd-product-meta-layout-',
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'       => __( 'Separator', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ', ',
				'description' => __( 'Separator between multiple categories/tags.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Pro Features Section.
		$this->start_controls_section(
			'section_pro_features',
			array(
				'label' => $this->pro_label( __( 'Pro Features', 'magical-products-display' ) ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_features_notice', __( 'Custom Meta Fields Display', 'magical-products-display' ) );
		}
			$this->add_control(
				'show_custom_meta',
				array(
					'label'        => __( 'Show Custom Meta', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$repeater = new Repeater();

			$repeater->add_control(
				'meta_key_select',
				array(
					'label'   => __( 'Select Meta Field', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => $this->get_available_meta_keys(),
					'default' => '',
				)
			);

			$repeater->add_control(
				'meta_key_custom',
				array(
					'label'       => __( 'Custom Meta Key', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => __( 'e.g., _brand', 'magical-products-display' ),
					'condition'   => array(
						'meta_key_select' => '__custom__',
					),
				)
			);

			$repeater->add_control(
				'custom_label',
				array(
					'label'       => __( 'Custom Label (Optional)', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'placeholder' => __( 'Leave empty for auto-label', 'magical-products-display' ),
					'description' => __( 'Override the auto-generated label.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'custom_meta_items',
				array(
					'label'       => __( 'Meta Fields', 'magical-products-display' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'default'     => array(),
					'title_field' => '{{{ meta_key_select === "__custom__" ? meta_key_custom : meta_key_select }}}',
					'condition'   => array(
						'show_custom_meta' => 'yes',
					),
				)
			);

			$this->add_control(
				'show_weight',
				array(
					'label'        => __( 'Show Weight', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'show_dimensions',
				array(
					'label'        => __( 'Show Dimensions', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'section_style_meta',
			array(
				'label' => __( 'Meta', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'text_align',
			array(
				'label'     => __( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-meta' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_spacing',
			array(
				'label'      => __( 'Item Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.mpd-product-meta-layout-stacked .mpd-product-meta-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.mpd-product-meta-layout-inline .mpd-product-meta-item' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_label',
			array(
				'label'     => __( 'Label', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-meta-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .mpd-product-meta-label',
			)
		);

		$this->add_control(
			'heading_value',
			array(
				'label'     => __( 'Value', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-meta-value, {{WRAPPER}} .mpd-product-meta-value a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-meta-value a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typography',
				'selector' => '{{WRAPPER}} .mpd-product-meta-value',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_widget( $settings ) {
		$product = $this->get_current_product();

		if ( ! $product ) {
			$this->render_editor_placeholder(
				__( 'Product Meta', 'magical-products-display' ),
				__( 'This widget displays product meta information. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-product-meta' );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			// SKU.
			if ( 'yes' === $settings['show_sku'] ) {
				$sku = $product->get_sku();
				if ( $sku ) {
					$this->render_meta_item(
						__( 'SKU:', 'magical-products-display' ),
						esc_html( $sku )
					);
				} elseif ( $is_editor ) {
					$this->render_meta_item(
						__( 'SKU:', 'magical-products-display' ),
						'<em style="color:#999;">' . esc_html__( 'No SKU set', 'magical-products-display' ) . '</em>'
					);
				}
			}

			// Categories.
			if ( 'yes' === $settings['show_category'] ) {
				$categories = wc_get_product_category_list( $product->get_id(), $settings['separator'] );
				if ( $categories ) {
					$this->render_meta_item(
						_n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'magical-products-display' ),
						$categories
					);
				}
			}

			// Tags.
			if ( 'yes' === $settings['show_tags'] ) {
				$tags = wc_get_product_tag_list( $product->get_id(), $settings['separator'] );
				if ( $tags ) {
					$this->render_meta_item(
						_n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'magical-products-display' ),
						$tags
					);
				}
			}

			// Pro: Weight.
			if ( $this->is_pro() && 'yes' === ( $settings['show_weight'] ?? '' ) ) {
				if ( $product->has_weight() ) {
					$this->render_meta_item(
						__( 'Weight:', 'magical-products-display' ),
						wc_format_weight( $product->get_weight() )
					);
				} elseif ( $is_editor ) {
					$this->render_meta_item(
						__( 'Weight:', 'magical-products-display' ),
						'<em style="color:#999;">' . esc_html__( 'No weight set. Add in Product → Shipping tab.', 'magical-products-display' ) . '</em>'
					);
				}
			}

			// Pro: Dimensions.
			if ( $this->is_pro() && 'yes' === ( $settings['show_dimensions'] ?? '' ) ) {
				if ( $product->has_dimensions() ) {
					$this->render_meta_item(
						__( 'Dimensions:', 'magical-products-display' ),
						wc_format_dimensions( $product->get_dimensions( false ) )
					);
				} elseif ( $is_editor ) {
					$this->render_meta_item(
						__( 'Dimensions:', 'magical-products-display' ),
						'<em style="color:#999;">' . esc_html__( 'No dimensions set. Add in Product → Shipping tab.', 'magical-products-display' ) . '</em>'
					);
				}
			}

			// Pro: Custom meta.
			if ( $this->is_pro() && 'yes' === ( $settings['show_custom_meta'] ?? '' ) ) {
				$custom_meta_items = $settings['custom_meta_items'] ?? array();
				if ( ! empty( $custom_meta_items ) ) {
					$found_any = false;
					foreach ( $custom_meta_items as $item ) {
						// Determine which meta key to use.
						$meta_key_select = $item['meta_key_select'] ?? '';
						
						// Skip separator options.
						if ( empty( $meta_key_select ) || 'custom_separator' === $meta_key_select ) {
							continue;
						}

						// Get the actual meta key.
						$meta_key = ( '__custom__' === $meta_key_select ) 
							? ( $item['meta_key_custom'] ?? '' ) 
							: $meta_key_select;

						if ( empty( $meta_key ) ) {
							continue;
						}

						$meta_value = get_post_meta( $product->get_id(), $meta_key, true );
						
						if ( $meta_value ) {
							$found_any = true;
							
							// Use custom label if provided, otherwise auto-generate.
							$custom_label = $item['custom_label'] ?? '';
							if ( ! empty( $custom_label ) ) {
								$label = rtrim( $custom_label, ':' ) . ':';
							} else {
								// Format the label nicely from meta key.
								$label = ucwords( str_replace( array( '_', '-' ), ' ', ltrim( $meta_key, '_' ) ) ) . ':';
							}

							// Handle array values.
							if ( is_array( $meta_value ) ) {
								$meta_value = implode( ', ', $meta_value );
							}
							$this->render_meta_item( $label, esc_html( $meta_value ) );
						}
					}
					// Show message in editor if no meta found.
					if ( ! $found_any && $is_editor && ! empty( $custom_meta_items ) ) {
						$this->render_meta_item(
							__( 'Custom Meta:', 'magical-products-display' ),
							'<em style="color:#999;">' . esc_html__( 'No values found for selected meta fields on this product.', 'magical-products-display' ) . '</em>'
						);
					}
				} elseif ( $is_editor ) {
					$this->render_meta_item(
						__( 'Custom Meta:', 'magical-products-display' ),
						'<em style="color:#999;">' . esc_html__( 'Click "Add Item" above to add custom meta fields.', 'magical-products-display' ) . '</em>'
					);
				}
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render a single meta item.
	 *
	 * @since 2.0.0
	 *
	 * @param string $label Label text.
	 * @param string $value Value (may contain HTML).
	 * @return void
	 */
	private function render_meta_item( $label, $value ) {
		?>
		<div class="mpd-product-meta-item">
			<span class="mpd-product-meta-label"><?php echo esc_html( $label ); ?></span>
			<span class="mpd-product-meta-value"><?php echo wp_kses_post( $value ); ?></span>
		</div>
		<?php
	}
}
