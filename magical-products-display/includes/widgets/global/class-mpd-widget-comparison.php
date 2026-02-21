<?php
/**
 * Product Comparison Widget
 *
 * Displays a product comparison table with multiple features.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\GlobalWidgets;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Product_Comparison
 */
class Product_Comparison extends Widget_Base {

	protected $widget_category = self::CATEGORY_GLOBAL;
	protected $widget_icon = 'eicon-exchange';

	public function get_name() {
		return 'mpd-product-comparison';
	}

	public function get_title() {
		return esc_html__( 'MPD Product Comparison', 'magical-products-display' );
	}

	public function get_keywords() {
		return array( 'comparison', 'compare', 'products', 'table', 'magical-products-display' );
	}

	public function get_style_depends() {
		return array( 'mpd-global-widgets' );
	}

	public function get_script_depends() {
		return array( 'wc-add-to-cart', 'mpd-global-widgets' );
	}

	protected function register_content_controls() {
		// Content Section
		$this->start_controls_section(
			'section_content',
			array(
				'label' => $this->pro_label( esc_html__( 'Comparison Settings', 'magical-products-display' ) ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_features_notice', 'Product Comparison' );
		}

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Compare Products', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'max_products',
			array(
				'label'   => esc_html__( 'Max Products', 'magical-products-display' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 2,
				'max'     => 8,
			)
		);

		$this->add_control(
			'empty_message',
			array(
				'label'   => esc_html__( 'Empty Message', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'No products to compare. Add products using the compare button.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Display Fields Section
		$this->start_controls_section(
			'section_fields',
			array(
				'label' => esc_html__( 'Display Fields', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'        => esc_html__( 'Product Image', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Product Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_price',
			array(
				'label'        => esc_html__( 'Price', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => esc_html__( 'Rating', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_description',
			array(
				'label'        => esc_html__( 'Short Description', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_stock',
			array(
				'label'        => esc_html__( 'Stock Status', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_sku',
			array(
				'label'        => esc_html__( 'SKU', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_weight',
			array(
				'label'        => esc_html__( 'Weight', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_dimensions',
			array(
				'label'        => esc_html__( 'Dimensions', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_attributes',
			array(
				'label'        => esc_html__( 'Attributes', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_add_to_cart',
			array(
				'label'        => esc_html__( 'Add to Cart Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_remove',
			array(
				'label'        => esc_html__( 'Remove Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		// Table Options
		$this->start_controls_section(
			'section_options',
			array(
				'label' => esc_html__( 'Table Options', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'sticky_header',
			array(
				'label'        => esc_html__( 'Sticky Header', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'highlight_differences',
			array(
				'label'        => esc_html__( 'Highlight Differences', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => esc_html__( 'Highlight cells where values differ.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_clear_all',
			array(
				'label'        => esc_html__( 'Show Clear All Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		// Table Style
		$this->start_controls_section(
			'section_table_style',
			array(
				'label' => esc_html__( 'Table', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'table_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-comparison-table' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'table_border',
				'selector' => '{{WRAPPER}} .mpd-comparison-table',
			)
		);

		$this->add_responsive_control(
			'table_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-comparison-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'table_shadow',
				'selector' => '{{WRAPPER}} .mpd-comparison-table',
			)
		);

		$this->end_controls_section();

		// Header Style
		$this->start_controls_section(
			'section_header_style',
			array(
				'label' => esc_html__( 'Header Row', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'header_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-comparison-header' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'header_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-comparison-header th, {{WRAPPER}} .mpd-comparison-header td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'header_typography',
				'selector' => '{{WRAPPER}} .mpd-comparison-header',
			)
		);

		$this->end_controls_section();

		// Cell Style
		$this->start_controls_section(
			'section_cell_style',
			array(
				'label' => esc_html__( 'Table Cells', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'cell_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-comparison-table td, {{WRAPPER}} .mpd-comparison-table th' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'cell_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => 15,
					'right'  => 15,
					'bottom' => 15,
					'left'   => 15,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-comparison-table td, {{WRAPPER}} .mpd-comparison-table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'highlight_color',
			array(
				'label'     => esc_html__( 'Highlight Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-comparison-table .highlight' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Button Style
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => esc_html__( 'Buttons', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-comparison-table .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-comparison-table .button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_bg',
			array(
				'label'     => esc_html__( 'Hover Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-comparison-table .button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render_widget( $settings ) {
		if ( ! $this->is_pro() ) {
			$this->render_pro_message( 'Product Comparison' );
			return;
		}

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$product_ids = $this->get_comparison_list( $settings );

		$wrapper_class = 'mpd-comparison-wrapper';
		if ( 'yes' === $settings['sticky_header'] ) {
			$wrapper_class .= ' mpd-comparison--sticky-header';
		}
		if ( 'yes' === $settings['highlight_differences'] ) {
			$wrapper_class .= ' mpd-comparison--highlight';
		}

		// Title
		if ( ! empty( $settings['title'] ) ) {
			echo '<h3 class="mpd-comparison-title">' . esc_html( $settings['title'] ) . '</h3>';
		}

		// Clear all button
		if ( 'yes' === $settings['show_clear_all'] && ! empty( $product_ids ) ) {
			echo '<button type="button" class="mpd-comparison-clear button">' . esc_html__( 'Clear All', 'magical-products-display' ) . '</button>';
		}

		if ( empty( $product_ids ) ) {
			echo '<div class="mpd-comparison-empty">' . esc_html( $settings['empty_message'] ) . '</div>';
			return;
		}

		$products = array();
		foreach ( $product_ids as $id ) {
			$product = wc_get_product( $id );
			if ( $product ) {
				$products[] = $product;
			}
		}

		if ( empty( $products ) ) {
			echo '<div class="mpd-comparison-empty">' . esc_html( $settings['empty_message'] ) . '</div>';
			return;
		}

		$highlight = 'yes' === $settings['highlight_differences'];
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<table class="mpd-comparison-table">
				<?php if ( 'yes' === $settings['show_image'] || 'yes' === $settings['show_title'] ) : ?>
				<thead>
				<tr class="mpd-comparison-header">
					<th><?php esc_html_e( 'Product', 'magical-products-display' ); ?></th>
					<?php foreach ( $products as $product ) : ?>
					<td>
						<?php if ( 'yes' === $settings['show_remove'] ) : ?>
						<button type="button" class="mpd-comparison-remove" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Remove', 'magical-products-display' ); ?>">&times;</button>
						<?php endif; ?>
						
						<?php if ( 'yes' === $settings['show_image'] ) : ?>
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="mpd-comparison-image">
							<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
						</a>
						<?php endif; ?>
						
						<?php if ( 'yes' === $settings['show_title'] ) : ?>
						<h4 class="mpd-comparison-product-title">
							<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
						</h4>
						<?php endif; ?>
					</td>
					<?php endforeach; ?>
				</tr>
				</thead>
				<?php endif; ?>

				<tbody>
				<?php if ( 'yes' === $settings['show_price'] ) : ?>
				<tr>
					<th><?php esc_html_e( 'Price', 'magical-products-display' ); ?></th>
					<?php 
					$prices = array();
					foreach ( $products as $product ) {
						$prices[] = $product->get_price();
					}
					$has_diff = $highlight && count( array_unique( $prices ) ) > 1;
					
					foreach ( $products as $i => $product ) : ?>
					<td class="<?php echo esc_attr( $has_diff ? 'highlight' : '' ); ?>"><?php echo wp_kses_post( $product->get_price_html() ); ?></td>
					<?php endforeach; ?>
				</tr>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_rating'] ) : ?>
				<tr>
					<th><?php esc_html_e( 'Rating', 'magical-products-display' ); ?></th>
					<?php foreach ( $products as $product ) : ?>
					<td>
						<?php 
						$rating = $product->get_average_rating();
						echo wp_kses_post( wc_get_rating_html( $rating ) ); 
						echo '<span class="rating-value">(' . esc_html( $rating ?: '0' ) . ')</span>';
						?>
					</td>
					<?php endforeach; ?>
				</tr>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_description'] ) : ?>
				<tr>
					<th><?php esc_html_e( 'Description', 'magical-products-display' ); ?></th>
					<?php foreach ( $products as $product ) : ?>
					<td><?php echo wp_kses_post( $product->get_short_description() ); ?></td>
					<?php endforeach; ?>
				</tr>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_stock'] ) : ?>
				<tr>
					<th><?php esc_html_e( 'Availability', 'magical-products-display' ); ?></th>
					<?php 
					$stocks = array();
					foreach ( $products as $product ) {
						$stocks[] = $product->is_in_stock() ? 'in' : 'out';
					}
					$has_diff = $highlight && count( array_unique( $stocks ) ) > 1;
					
					foreach ( $products as $i => $product ) : ?>
					<td class="<?php echo esc_attr( $has_diff ? 'highlight' : '' ); ?>">
						<?php if ( $product->is_in_stock() ) : ?>
						<span class="in-stock"><?php esc_html_e( 'In Stock', 'magical-products-display' ); ?></span>
						<?php else : ?>
						<span class="out-of-stock"><?php esc_html_e( 'Out of Stock', 'magical-products-display' ); ?></span>
						<?php endif; ?>
					</td>
					<?php endforeach; ?>
				</tr>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_sku'] ) : ?>
				<tr>
					<th><?php esc_html_e( 'SKU', 'magical-products-display' ); ?></th>
					<?php foreach ( $products as $product ) : ?>
					<td><?php echo esc_html( $product->get_sku() ?: '-' ); ?></td>
					<?php endforeach; ?>
				</tr>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_weight'] ) : ?>
				<tr>
					<th><?php esc_html_e( 'Weight', 'magical-products-display' ); ?></th>
					<?php foreach ( $products as $product ) : ?>
					<td><?php echo $product->has_weight() ? esc_html( wc_format_weight( $product->get_weight() ) ) : '-'; ?></td>
					<?php endforeach; ?>
				</tr>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_dimensions'] ) : ?>
				<tr>
					<th><?php esc_html_e( 'Dimensions', 'magical-products-display' ); ?></th>
					<?php foreach ( $products as $product ) : ?>
					<td><?php echo $product->has_dimensions() ? esc_html( wc_format_dimensions( $product->get_dimensions( false ) ) ) : '-'; ?></td>
					<?php endforeach; ?>
				</tr>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_attributes'] ) : 
					$all_attributes = array();
					foreach ( $products as $product ) {
						$attrs = $product->get_attributes();
						foreach ( $attrs as $attr ) {
							if ( is_object( $attr ) && method_exists( $attr, 'get_name' ) ) {
								$all_attributes[ $attr->get_name() ] = wc_attribute_label( $attr->get_name() );
							}
						}
					}
					
					foreach ( $all_attributes as $attr_name => $attr_label ) : ?>
				<tr>
					<th><?php echo esc_html( $attr_label ); ?></th>
					<?php 
					$attr_values = array();
					foreach ( $products as $product ) {
						$attr_values[] = $product->get_attribute( $attr_name );
					}
					$has_diff = $highlight && count( array_unique( array_filter( $attr_values ) ) ) > 1;
					
					foreach ( $products as $i => $product ) : ?>
					<td class="<?php echo esc_attr( $has_diff ? 'highlight' : '' ); ?>"><?php echo esc_html( $product->get_attribute( $attr_name ) ?: '-' ); ?></td>
					<?php endforeach; ?>
				</tr>
				<?php endforeach; endif; ?>

				<?php if ( 'yes' === $settings['show_add_to_cart'] ) : ?>
				<tr class="mpd-comparison-actions">
					<th><?php esc_html_e( 'Action', 'magical-products-display' ); ?></th>
					<?php foreach ( $products as $product ) : ?>
					<td>
						<?php $this->render_add_to_cart_button( $product ); ?>
					</td>
					<?php endforeach; ?>
				</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Render add to cart button with WooCommerce AJAX support.
	 */
	private function render_add_to_cart_button( $product ) {
		if ( ! $product ) {
			return;
		}

		$product_type = $product->get_type();

		$classes = array( 'button', 'add_to_cart_button' );

		// Only simple products support AJAX add-to-cart
		if ( 'simple' === $product_type && $product->is_purchasable() && $product->is_in_stock() ) {
			$classes[] = 'ajax_add_to_cart';
		}

		$classes[] = 'product_type_' . $product_type;

		printf(
			'<a href="%s" data-quantity="1" class="%s" data-product_id="%d" data-product_sku="%s" rel="nofollow">%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( $product->get_id() ),
			esc_attr( $product->get_sku() ),
			esc_html( $product->add_to_cart_text() )
		);
	}

	private function get_comparison_list( $settings ) {
		$list = array();

		if ( isset( $_COOKIE['mpd_comparison_list'] ) ) {
			$list = array_filter( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_COOKIE['mpd_comparison_list'] ) ) ) ) );
		}

		// Editor preview
		if ( empty( $list ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => $settings['max_products'],
				'post_status'    => 'publish',
				'fields'         => 'ids',
			);
			$list = get_posts( $args );
		}

		return array_slice( $list, 0, $settings['max_products'] );
	}
}
