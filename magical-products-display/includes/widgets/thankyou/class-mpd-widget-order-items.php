<?php
/**
 * Order Items Widget
 *
 * Displays the order items table with products, quantities and prices.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

namespace MPD\MagicalShopBuilder\Widgets\ThankYou;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Order_Items
 *
 * Displays order items in a table format.
 *
 * @since 2.1.0
 */
class Order_Items extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_THANKYOU;

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	protected $widget_icon = 'eicon-table';

	/**
	 * Get widget name.
	 *
	 * @since 2.1.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-order-items';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.1.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Order Items', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.1.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'order', 'items', 'products', 'table', 'cart', 'woocommerce', 'thankyou', 'list' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'section_title',
			array(
				'label'       => __( 'Section Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Order Items', 'magical-products-display' ),
				'placeholder' => __( 'Order Items', 'magical-products-display' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'p'    => 'p',
					'span' => 'span',
				),
				'default' => 'h3',
			)
		);

		$this->add_control(
			'heading_columns',
			array(
				'label'     => __( 'Table Columns', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_thumbnail',
			array(
				'label'        => __( 'Product Image', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_sku',
			array(
				'label'        => __( 'SKU', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_quantity',
			array(
				'label'        => __( 'Quantity', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_totals_section',
			array(
				'label'        => __( 'Order Totals', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'heading_labels',
			array(
				'label'     => __( 'Column Headers', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'header_product',
			array(
				'label'   => __( 'Product Column', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Product', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'header_quantity',
			array(
				'label'     => __( 'Quantity Column', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Qty', 'magical-products-display' ),
				'condition' => array(
					'show_quantity' => 'yes',
				),
			)
		);

		$this->add_control(
			'header_price',
			array(
				'label'   => __( 'Price Column', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Total', 'magical-products-display' ),
			)
		);

		$this->add_responsive_control(
			'thumbnail_size',
			array(
				'label'      => __( 'Thumbnail Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 30,
						'max' => 150,
					),
				),
				'default'    => array(
					'size' => 60,
					'unit' => 'px',
				),
				'condition'  => array(
					'show_thumbnail' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-items-thumbnail img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Product Links, Download Buttons & Reorder', 'magical-products-display' ) );
		}
			$this->add_control(
				'show_product_links',
				array(
					'label'        => __( 'Product Links', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'description'  => __( 'Link product names to product pages.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_download_links',
				array(
					'label'        => __( 'Download Links', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'description'  => __( 'Show download buttons for digital products.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_reorder_button',
				array(
					'label'        => __( 'Reorder Button', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Add quick reorder button for each item.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_review_button',
				array(
					'label'        => __( 'Review Button', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Add quick review link for each product.', 'magical-products-display' ),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	protected function register_style_controls() {
		// Container Style.
		$this->start_controls_section(
			'section_container_style',
			array(
				'label' => __( 'Container', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'container_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-items' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-order-items',
			)
		);

		$this->add_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-items' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Title Style.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => __( 'Title', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-items-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-order-items-title',
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-items-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Table Header Style.
		$this->start_controls_section(
			'section_table_header_style',
			array(
				'label' => __( 'Table Header', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'header_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-items-table thead th' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'header_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-items-table thead th' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'header_typography',
				'selector' => '{{WRAPPER}} .mpd-order-items-table thead th',
			)
		);

		$this->add_responsive_control(
			'header_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => '12',
					'right'  => '15',
					'bottom' => '12',
					'left'   => '15',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-items-table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Table Body Style.
		$this->start_controls_section(
			'section_table_body_style',
			array(
				'label' => __( 'Table Body', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'row_bg_color',
			array(
				'label'     => __( 'Row Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-items-table tbody tr' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'row_alt_bg_color',
			array(
				'label'     => __( 'Alternate Row Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-items-table tbody tr:nth-child(even)' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'cell_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-items-table tbody td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cell_typography',
				'selector' => '{{WRAPPER}} .mpd-order-items-table tbody td',
			)
		);

		$this->add_responsive_control(
			'cell_padding',
			array(
				'label'      => __( 'Cell Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => '12',
					'right'  => '15',
					'bottom' => '12',
					'left'   => '15',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-items-table tbody td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'cell_border',
				'selector' => '{{WRAPPER}} .mpd-order-items-table tbody td, {{WRAPPER}} .mpd-order-items-table thead th',
			)
		);

		$this->end_controls_section();

		// Totals Section Style.
		$this->start_controls_section(
			'section_totals_style',
			array(
				'label'     => __( 'Order Totals', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_totals_section' => 'yes',
				),
			)
		);

		$this->add_control(
			'totals_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-items-totals' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'totals_label_color',
			array(
				'label'     => __( 'Label Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-items-totals th' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'totals_value_color',
			array(
				'label'     => __( 'Value Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-items-totals td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'totals_typography',
				'selector' => '{{WRAPPER}} .mpd-order-items-totals th, {{WRAPPER}} .mpd-order-items-totals td',
			)
		);

		$this->add_control(
			'total_row_bold',
			array(
				'label'        => __( 'Bold Total Row', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors'    => array(
					'{{WRAPPER}} .mpd-order-items-totals .order-total th, {{WRAPPER}} .mpd-order-items-totals .order-total td' => 'font-weight: 700;',
				),
			)
		);

		$this->end_controls_section();

		// Product Name Style.
		$this->start_controls_section(
			'section_product_name_style',
			array(
				'label' => __( 'Product Name', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_name_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-items-product-name' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-order-items-product-name a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'product_name_hover_color',
			array(
				'label'     => __( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-items-product-name a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_name_typography',
				'selector' => '{{WRAPPER}} .mpd-order-items-product-name',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	protected function render_widget( $settings ) {
		$order     = $this->get_current_order();
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Calculate number of columns.
		$col_count = 2; // Product and Price are always shown.
		if ( 'yes' === $settings['show_thumbnail'] ) {
			++$col_count;
		}
		if ( 'yes' === $settings['show_quantity'] ) {
			++$col_count;
		}

		// Check if any pro features are enabled.
		$has_pro_features = $this->is_pro() && (
			'yes' === ( $settings['show_download_links'] ?? 'yes' ) ||
			'yes' === ( $settings['show_reorder_button'] ?? '' ) ||
			'yes' === ( $settings['show_review_button'] ?? '' )
		);
		?>
		<div class="mpd-order-items">
			<?php if ( ! empty( $settings['section_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mpd-order-items-title">
					<?php echo esc_html( $settings['section_title'] ); ?>
				</<?php echo esc_attr( $settings['title_tag'] ); ?>>
			<?php endif; ?>

			<?php if ( $order || $is_editor ) : ?>
				<table class="mpd-order-items-table">
					<thead>
						<tr>
							<?php if ( 'yes' === $settings['show_thumbnail'] ) : ?>
								<th class="mpd-order-items-col-image">&nbsp;</th>
							<?php endif; ?>
							<th class="mpd-order-items-col-product"><?php echo esc_html( $settings['header_product'] ); ?></th>
							<?php if ( 'yes' === $settings['show_quantity'] ) : ?>
								<th class="mpd-order-items-col-quantity"><?php echo esc_html( $settings['header_quantity'] ); ?></th>
							<?php endif; ?>
							<th class="mpd-order-items-col-price"><?php echo esc_html( $settings['header_price'] ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ( $order ) {
							$this->render_order_items( $order, $settings );
						} else {
							$this->render_demo_items( $settings );
						}
						?>
					</tbody>
					<?php if ( 'yes' === $settings['show_totals_section'] ) : ?>
						<tfoot class="mpd-order-items-totals">
							<?php
							if ( $order ) {
								$this->render_order_totals( $order, $col_count );
							} else {
								$this->render_demo_totals( $col_count );
							}
							?>
						</tfoot>
					<?php endif; ?>
				</table>
			<?php else : ?>
				<div class="mpd-order-items-empty">
					<p><?php esc_html_e( 'No order found.', 'magical-products-display' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
		<?php
		// Pro features styles.
		if ( $has_pro_features ) :
			?>
			<style>
			.mpd-order-item-downloads { margin-top: 8px; }
			.mpd-download-link { display: inline-flex; align-items: center; gap: 4px; font-size: 13px; color: #0073aa; text-decoration: none; margin-right: 10px; }
			.mpd-download-link:hover { color: #005177; text-decoration: underline; }
			.mpd-order-item-actions { margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap; }
			.mpd-reorder-btn, .mpd-review-btn { font-size: 12px !important; padding: 5px 12px !important; line-height: 1.4 !important; }
			.mpd-reorder-btn { background: #0073aa; color: #fff !important; border: none; }
			.mpd-reorder-btn:hover { background: #005177; }
			.mpd-review-btn { background: #f0f0f0; color: #333 !important; border: 1px solid #ddd; }
			.mpd-review-btn:hover { background: #e0e0e0; }
			</style>
			<?php
		endif;
	}

	/**
	 * Render order items.
	 *
	 * @since 2.1.0
	 *
	 * @param \WC_Order $order    Order object.
	 * @param array     $settings Widget settings.
	 *
	 * @return void
	 */
	private function render_order_items( $order, $settings ) {
		$order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );

		// Pro features.
		$show_product_links  = $this->is_pro() && 'yes' === ( $settings['show_product_links'] ?? 'yes' );
		$show_download_links = $this->is_pro() && 'yes' === ( $settings['show_download_links'] ?? 'yes' );
		$show_reorder_button = $this->is_pro() && 'yes' === ( $settings['show_reorder_button'] ?? '' );
		$show_review_button  = $this->is_pro() && 'yes' === ( $settings['show_review_button'] ?? '' );

		foreach ( $order_items as $item_id => $item ) {
			$product = $item->get_product();
			?>
			<tr class="mpd-order-items-row">
				<?php if ( 'yes' === $settings['show_thumbnail'] ) : ?>
					<td class="mpd-order-items-thumbnail">
						<?php
						$thumbnail = '';
						if ( $product ) {
							$thumbnail = $product->get_image( 'thumbnail' );
						}
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo wp_kses_post( apply_filters( 'woocommerce_order_item_thumbnail', $thumbnail, $item ) );
						?>
					</td>
				<?php endif; ?>
				<td class="mpd-order-items-product">
					<span class="mpd-order-items-product-name">
						<?php
						$item_name = $item->get_name();
						// Pro feature: Product links control.
						if ( $show_product_links && $product && $product->is_visible() ) {
							echo '<a href="' . esc_url( get_permalink( $product->get_id() ) ) . '">' . esc_html( $item_name ) . '</a>';
						} elseif ( ! $this->is_pro() && $product && $product->is_visible() ) {
							// Default behavior for non-pro (show links).
							echo '<a href="' . esc_url( get_permalink( $product->get_id() ) ) . '">' . esc_html( $item_name ) . '</a>';
						} else {
							echo esc_html( $item_name );
						}
						?>
					</span>
					<?php if ( 'yes' === $settings['show_sku'] && $product && $product->get_sku() ) : ?>
						<span class="mpd-order-items-sku">
							<?php
							/* translators: %s: SKU */
							echo esc_html( sprintf( __( 'SKU: %s', 'magical-products-display' ), $product->get_sku() ) );
							?>
						</span>
					<?php endif; ?>
					<?php
					// Display variation details.
					do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

					wc_display_item_meta( $item );

					do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );

					// Pro feature: Download links for digital products.
					if ( $show_download_links && $product && $product->is_downloadable() ) {
						$downloads = $order->get_item_downloads( $item );
						if ( ! empty( $downloads ) ) {
							echo '<div class="mpd-order-item-downloads">';
							foreach ( $downloads as $download ) {
								echo '<a href="' . esc_url( $download['download_url'] ) . '" class="mpd-download-link"><span class="dashicons dashicons-download"></span> ' . esc_html( $download['name'] ) . '</a>';
							}
							echo '</div>';
						}
					}

					// Pro feature: Action buttons (reorder, review).
					if ( $show_reorder_button || $show_review_button ) {
						echo '<div class="mpd-order-item-actions">';
						
						if ( $show_reorder_button && $product ) {
							$add_to_cart_url = add_query_arg( 'add-to-cart', $product->get_id(), wc_get_cart_url() );
							echo '<a href="' . esc_url( $add_to_cart_url ) . '" class="mpd-reorder-btn button">' . esc_html__( 'Reorder', 'magical-products-display' ) . '</a>';
						}
						
						if ( $show_review_button && $product ) {
							$review_url = get_permalink( $product->get_id() ) . '#reviews';
							echo '<a href="' . esc_url( $review_url ) . '" class="mpd-review-btn button">' . esc_html__( 'Write Review', 'magical-products-display' ) . '</a>';
						}
						
						echo '</div>';
					}
					?>
				</td>
				<?php if ( 'yes' === $settings['show_quantity'] ) : ?>
					<td class="mpd-order-items-quantity">
						<?php
						$qty = $item->get_quantity();
						echo esc_html( $qty );
						?>
					</td>
				<?php endif; ?>
				<td class="mpd-order-items-price">
					<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
				</td>
			</tr>
			<?php
		}
	}

	/**
	 * Render demo items for editor preview.
	 *
	 * @since 2.1.0
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return void
	 */
	private function render_demo_items( $settings ) {
		$demo_items = array(
			array(
				'name'     => __( 'Sample Product 1', 'magical-products-display' ),
				'sku'      => 'SKU-001',
				'quantity' => 2,
				'price'    => 49.99,
			),
			array(
				'name'     => __( 'Sample Product 2', 'magical-products-display' ),
				'sku'      => 'SKU-002',
				'quantity' => 1,
				'price'    => 29.99,
			),
		);

		foreach ( $demo_items as $item ) :
			// Pro features for demo.
			$show_reorder_button = $this->is_pro() && 'yes' === ( $settings['show_reorder_button'] ?? '' );
			$show_review_button  = $this->is_pro() && 'yes' === ( $settings['show_review_button'] ?? '' );
			$show_download_links = $this->is_pro() && 'yes' === ( $settings['show_download_links'] ?? 'yes' );
			?>
			<tr class="mpd-order-items-row">
				<?php if ( 'yes' === $settings['show_thumbnail'] ) : ?>
					<td class="mpd-order-items-thumbnail">
						<div style="width: 60px; height: 60px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
							<span class="dashicons dashicons-format-image" style="font-size: 24px; color: #94a3b8;"></span>
						</div>
					</td>
				<?php endif; ?>
				<td class="mpd-order-items-product">
					<span class="mpd-order-items-product-name"><?php echo esc_html( $item['name'] ); ?></span>
					<?php if ( 'yes' === $settings['show_sku'] ) : ?>
						<span class="mpd-order-items-sku"><?php echo esc_html( 'SKU: ' . $item['sku'] ); ?></span>
					<?php endif; ?>
					<?php
					// Pro feature: Demo download link.
					if ( $show_download_links ) {
						echo '<div class="mpd-order-item-downloads">';
						echo '<a href="#" class="mpd-download-link"><span class="dashicons dashicons-download"></span> ' . esc_html__( 'Download File', 'magical-products-display' ) . '</a>';
						echo '</div>';
					}

					// Pro feature: Demo action buttons.
					if ( $show_reorder_button || $show_review_button ) {
						echo '<div class="mpd-order-item-actions">';
						if ( $show_reorder_button ) {
							echo '<a href="#" class="mpd-reorder-btn button">' . esc_html__( 'Reorder', 'magical-products-display' ) . '</a>';
						}
						if ( $show_review_button ) {
							echo '<a href="#" class="mpd-review-btn button">' . esc_html__( 'Write Review', 'magical-products-display' ) . '</a>';
						}
						echo '</div>';
					}
					?>
				</td>
				<?php if ( 'yes' === $settings['show_quantity'] ) : ?>
					<td class="mpd-order-items-quantity"><?php echo esc_html( $item['quantity'] ); ?></td>
				<?php endif; ?>
				<td class="mpd-order-items-price"><?php echo wp_kses_post( wc_price( $item['price'] * $item['quantity'] ) ); ?></td>
			</tr>
			<?php
		endforeach;
	}

	/**
	 * Render order totals.
	 *
	 * @since 2.1.0
	 *
	 * @param \WC_Order $order     Order object.
	 * @param int       $col_count Number of columns.
	 *
	 * @return void
	 */
	private function render_order_totals( $order, $col_count ) {
		$totals = $order->get_order_item_totals();

		if ( $totals ) {
			foreach ( $totals as $key => $total ) {
				?>
				<tr class="<?php echo esc_attr( sanitize_html_class( $key ) ); ?>">
					<th scope="row" colspan="<?php echo esc_attr( $col_count - 1 ); ?>"><?php echo esc_html( $total['label'] ); ?></th>
					<td><?php echo wp_kses_post( $total['value'] ); ?></td>
				</tr>
				<?php
			}
		}
	}

	/**
	 * Render demo totals for editor preview.
	 *
	 * @since 2.1.0
	 *
	 * @param int $col_count Number of columns.
	 *
	 * @return void
	 */
	private function render_demo_totals( $col_count ) {
		$demo_totals = array(
			array(
				'label' => __( 'Subtotal:', 'magical-products-display' ),
				'value' => wc_price( 129.97 ),
				'class' => 'cart-subtotal',
			),
			array(
				'label' => __( 'Shipping:', 'magical-products-display' ),
				'value' => wc_price( 10.00 ),
				'class' => 'shipping',
			),
			array(
				'label' => __( 'Tax:', 'magical-products-display' ),
				'value' => wc_price( 12.00 ),
				'class' => 'tax-rate',
			),
			array(
				'label' => __( 'Total:', 'magical-products-display' ),
				'value' => wc_price( 151.97 ),
				'class' => 'order-total',
			),
		);

		foreach ( $demo_totals as $total ) :
			?>
			<tr class="<?php echo esc_attr( $total['class'] ); ?>">
				<th scope="row" colspan="<?php echo esc_attr( $col_count - 1 ); ?>"><?php echo esc_html( $total['label'] ); ?></th>
				<td><?php echo wp_kses_post( $total['value'] ); ?></td>
			</tr>
			<?php
		endforeach;
	}

	/**
	 * Get current order from URL or session.
	 *
	 * @since 2.1.0
	 *
	 * @return \WC_Order|false Order object or false.
	 */
	private function get_current_order() {
		if ( ! function_exists( 'wc_get_order' ) ) {
			return false;
		}

		// Check for order ID in URL (thank you page).
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$order_id = isset( $_GET['order-received'] ) ? absint( wp_unslash( $_GET['order-received'] ) ) : 0;

		if ( ! $order_id ) {
			global $wp;
			if ( isset( $wp->query_vars['order-received'] ) ) {
				$order_id = absint( $wp->query_vars['order-received'] );
			}
		}

		if ( $order_id ) {
			$order = wc_get_order( $order_id );

			// Verify order key for security.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$order_key = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
			if ( $order && $order->get_order_key() === $order_key ) {
				return $order;
			}
		}

		return false;
	}
}
