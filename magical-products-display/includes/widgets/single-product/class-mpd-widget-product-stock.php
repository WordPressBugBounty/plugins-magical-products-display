<?php
/**
 * Product Stock Widget
 *
 * Displays the product stock status on single product pages.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\SingleProduct;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Product_Stock
 *
 * @since 2.0.0
 */
class Product_Stock extends Widget_Base {

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
		return 'mpd-product-stock';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Product Stock', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-stock';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'stock', 'inventory', 'availability', 'woocommerce', 'single' );
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
				'label' => __( 'Stock', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => __( 'Show Icon', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'in_stock_icon',
			array(
				'label'     => __( 'In Stock Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-check-circle',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'out_of_stock_icon',
			array(
				'label'     => __( 'Out of Stock Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-times-circle',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'backorder_icon',
			array(
				'label'     => __( 'Backorder Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-truck',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'in_stock_text',
			array(
				'label'       => __( 'In Stock Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Leave empty for default', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'out_of_stock_text',
			array(
				'label'       => __( 'Out of Stock Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Leave empty for default', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_quantity',
			array(
				'label'        => __( 'Show Quantity', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'Show remaining stock quantity if available.', 'magical-products-display' ),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-stock' => 'align-items: {{VALUE}};',
					'{{WRAPPER}} .mpd-stock-status' => 'justify-content: {{VALUE}};',
				),
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Progress Bar & Urgency Text', 'magical-products-display' ) );
		}
			$this->add_control(
				'show_progress_bar',
				array(
					'label'        => __( 'Show Progress Bar', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Display a visual progress bar for stock levels.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'total_stock',
				array(
					'label'       => __( 'Total Stock (for progress)', 'magical-products-display' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => 100,
					'min'         => 1,
					'description' => __( 'Enter total stock for calculating progress percentage.', 'magical-products-display' ),
					'condition'   => array(
						'show_progress_bar' => 'yes',
					),
				)
			);

			$this->add_control(
				'show_urgency',
				array(
					'label'        => __( 'Show Urgency Message', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'urgency_threshold',
				array(
					'label'     => __( 'Urgency Threshold', 'magical-products-display' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 10,
					'min'       => 1,
					'condition' => array(
						'show_urgency' => 'yes',
					),
				)
			);

			$this->add_control(
				'urgency_message',
				array(
					'label'     => __( 'Urgency Message', 'magical-products-display' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Only {stock} left - order soon!', 'magical-products-display' ),
					'condition' => array(
						'show_urgency' => 'yes',
					),
				)
			);

			$this->add_control(
				'show_sold_count',
				array(
					'label'        => __( 'Show Sold Count', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'sold_text',
				array(
					'label'     => __( 'Sold Text', 'magical-products-display' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( '{sold} sold', 'magical-products-display' ),
					'condition' => array(
						'show_sold_count' => 'yes',
					),
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
		// In Stock Style.
		$this->start_controls_section(
			'section_style_in_stock',
			array(
				'label' => __( 'In Stock', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'in_stock_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-stock-in-stock' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'in_stock_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-stock-in-stock' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'in_stock_typography',
				'selector' => '{{WRAPPER}} .mpd-stock-in-stock',
			)
		);

		$this->end_controls_section();

		// Out of Stock Style.
		$this->start_controls_section(
			'section_style_out_of_stock',
			array(
				'label' => __( 'Out of Stock', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'out_of_stock_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-stock-out-of-stock' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'out_of_stock_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-stock-out-of-stock' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'out_of_stock_typography',
				'selector' => '{{WRAPPER}} .mpd-stock-out-of-stock',
			)
		);

		$this->end_controls_section();

		// Backorder Style.
		$this->start_controls_section(
			'section_style_backorder',
			array(
				'label' => __( 'Backorder', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'backorder_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-stock-on-backorder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'backorder_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-stock-on-backorder' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'backorder_typography',
				'selector' => '{{WRAPPER}} .mpd-stock-on-backorder',
			)
		);

		$this->end_controls_section();

		// Icon Style.
		$this->start_controls_section(
			'section_style_icon',
			array(
				'label'     => __( 'Icon', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'size' => 18,
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
					'em' => array(
						'min' => 0.5,
						'max' => 3,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-stock-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-stock-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-stock-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-stock-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Container Style.
		$this->start_controls_section(
			'section_style_container',
			array(
				'label' => __( 'Container', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-stock-wrapper',
			)
		);

		$this->add_responsive_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-stock-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-stock-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Progress Bar Style (Pro).
		if ( $this->is_pro() ) {
			$this->start_controls_section(
				'section_style_progress',
				array(
					'label'     => __( 'Progress Bar', 'magical-products-display' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'show_progress_bar' => 'yes',
					),
				)
			);

			$this->add_responsive_control(
				'progress_height',
				array(
					'label'      => __( 'Height', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 5,
							'max' => 30,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-stock-progress' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'progress_bg_color',
				array(
					'label'     => __( 'Background Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-stock-progress' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'progress_fill_color',
				array(
					'label'     => __( 'Fill Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-stock-progress-bar' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'progress_low_color',
				array(
					'label'     => __( 'Low Stock Fill Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-stock-progress.low-stock .mpd-stock-progress-bar' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'progress_border_radius',
				array(
					'label'      => __( 'Border Radius', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 20,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-stock-progress, {{WRAPPER}} .mpd-stock-progress-bar' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'progress_spacing',
				array(
					'label'      => __( 'Spacing', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 30,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-stock-progress' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();
		}
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
				__( 'Product Stock', 'magical-products-display' ),
				__( 'This widget displays product stock status. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-product-stock' );

		$stock_status = $product->get_stock_status();
		$stock_qty    = $product->get_stock_quantity();

		// Determine the stock class and text.
		switch ( $stock_status ) {
			case 'instock':
				$status_class = 'mpd-stock-in-stock';
				$status_text  = ! empty( $settings['in_stock_text'] ) ? $settings['in_stock_text'] : __( 'In Stock', 'magical-products-display' );
				$icon         = $settings['in_stock_icon'] ?? array();
				break;

			case 'outofstock':
				$status_class = 'mpd-stock-out-of-stock';
				$status_text  = ! empty( $settings['out_of_stock_text'] ) ? $settings['out_of_stock_text'] : __( 'Out of Stock', 'magical-products-display' );
				$icon         = $settings['out_of_stock_icon'] ?? array();
				break;

			case 'onbackorder':
				$status_class = 'mpd-stock-on-backorder';
				$status_text  = __( 'Available on Backorder', 'magical-products-display' );
				$icon         = $settings['backorder_icon'] ?? array();
				break;

			default:
				$status_class = 'mpd-stock-in-stock';
				$status_text  = __( 'In Stock', 'magical-products-display' );
				$icon         = $settings['in_stock_icon'] ?? array();
				break;
		}

		// Add quantity to text if enabled and available.
		if ( 'yes' === $settings['show_quantity'] && null !== $stock_qty && 'outofstock' !== $stock_status ) {
			$status_text .= ' (' . $stock_qty . ')';
		}
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="mpd-stock-wrapper <?php echo esc_attr( $status_class ); ?>">
				<?php if ( 'yes' === $settings['show_icon'] && ! empty( $icon['value'] ) ) : ?>
					<span class="mpd-stock-icon">
						<?php \Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) ); ?>
					</span>
				<?php endif; ?>

				<span class="mpd-stock-text"><?php echo esc_html( $status_text ); ?></span>

				<?php
				// Pro: Sold count.
				if ( $this->is_pro() && 'yes' === ( $settings['show_sold_count'] ?? '' ) ) {
					$this->render_sold_count( $product, $settings );
				}
				?>
			</div>

			<?php
			// Calculate effective stock for Pro features.
			// If WooCommerce stock management is disabled, calculate from total_stock - sold.
			$total_stock    = absint( $settings['total_stock'] ?? 100 );
			$sold_count     = $product->get_total_sales();
			$effective_stock = null !== $stock_qty ? $stock_qty : max( 0, $total_stock - $sold_count );

			// Pro: Progress bar.
			if ( $this->is_pro() && 'yes' === ( $settings['show_progress_bar'] ?? '' ) ) {
				$this->render_progress_bar( $effective_stock, $settings, $product );
			}

			// Pro: Urgency message.
			if ( $this->is_pro() && 'yes' === ( $settings['show_urgency'] ?? '' ) && 'instock' === $stock_status ) {
				$this->render_urgency_message( $effective_stock, $settings );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render sold count (Pro).
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  The product object.
	 * @param array       $settings Widget settings.
	 * @return void
	 */
	private function render_sold_count( $product, $settings ) {
		$sold_count = $product->get_total_sales();
		$sold_text  = $settings['sold_text'] ?? __( '{sold} sold', 'magical-products-display' );
		$sold_text  = str_replace( '{sold}', $sold_count, $sold_text );
		?>
		<span class="mpd-stock-sold"><?php echo esc_html( $sold_text ); ?></span>
		<?php
	}

	/**
	 * Render progress bar (Pro).
	 *
	 * @since 2.0.0
	 *
	 * @param int         $stock_qty Current stock quantity.
	 * @param array       $settings  Widget settings.
	 * @param \WC_Product $product   The product object.
	 * @return void
	 */
	private function render_progress_bar( $stock_qty, $settings, $product = null ) {
		$total_stock = absint( $settings['total_stock'] ?? 100 );
		$sold_count  = $product ? $product->get_total_sales() : 0;
		$percentage  = $total_stock > 0 ? min( 100, ( $stock_qty / $total_stock ) * 100 ) : 0;
		$threshold   = absint( $settings['urgency_threshold'] ?? 10 );
		$low_stock   = $stock_qty <= $threshold;
		?>
		<div class="mpd-stock-progress-wrapper<?php echo esc_attr( $low_stock ? ' mpd-low-stock' : '' ); ?>">
			<div class="mpd-stock-progress-bar">
				<div class="mpd-stock-progress-fill" style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
			</div>
			<div class="mpd-stock-progress-text">
				<span class="mpd-stock-available"><?php echo esc_html( sprintf( __( '%d available', 'magical-products-display' ), $stock_qty ) ); ?></span>
				<span class="mpd-stock-sold-count"><?php echo esc_html( sprintf( __( '%d sold', 'magical-products-display' ), $sold_count ) ); ?></span>
			</div>
		</div>
		<?php
	}

	/**
	 * Render urgency message (Pro).
	 *
	 * @since 2.0.0
	 *
	 * @param int   $stock_qty Current stock quantity.
	 * @param array $settings  Widget settings.
	 * @return void
	 */
	private function render_urgency_message( $stock_qty, $settings ) {
		$threshold = absint( $settings['urgency_threshold'] ?? 10 );

		// In editor, always show for preview. On frontend, only show when stock is low.
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode();
		
		if ( ! $is_editor && $stock_qty > $threshold ) {
			return;
		}

		$message = $settings['urgency_message'] ?? __( 'Only {stock} left - order soon!', 'magical-products-display' );
		$message = str_replace( '{stock}', $stock_qty, $message );
		
		// Show a note in editor if stock is above threshold.
		$editor_note = '';
		if ( $is_editor && $stock_qty > $threshold ) {
			$editor_note = ' <small style="opacity: 0.7;">(' . sprintf( __( 'Preview only - shows when stock ≤ %d', 'magical-products-display' ), $threshold ) . ')</small>';
		}
		?>
		<div class="mpd-stock-urgency">
			<?php echo esc_html( $message ); ?>
			<?php echo wp_kses_post( $editor_note ); ?>
		</div>
		<?php
	}
}
