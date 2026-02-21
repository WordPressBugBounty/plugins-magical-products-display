<?php
/**
 * Order Review Widget
 *
 * Displays the checkout order review table with products and totals.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\Checkout;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Order_Review
 *
 * @since 2.0.0
 */
class Order_Review extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_CART_CHECKOUT;

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	protected $widget_icon = 'eicon-checkout';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-order-review';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Order Review', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'order', 'review', 'checkout', 'summary', 'woocommerce', 'cart', 'total' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'wc-checkout', 'mpd-checkout-widgets' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-checkout-widgets' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		// Content Section.
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'editor_preview_mode',
			array(
				'label'       => __( 'Editor Preview Mode', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'demo'  => __( 'Demo Order Data', 'magical-products-display' ),
					'empty' => __( 'Empty Cart Message', 'magical-products-display' ),
				),
				'default'     => 'demo',
				'render_type' => 'template',
				'description' => __( 'Choose what to display in the editor for styling. Frontend always shows real cart data.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'section_title',
			array(
				'label'       => __( 'Section Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Your Order', 'magical-products-display' ),
				'placeholder' => __( 'Enter section title', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => __( 'Show Section Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
				'default'   => 'h3',
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Display Options Section.
		$this->start_controls_section(
			'section_display',
			array(
				'label' => __( 'Display Options', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_product_image',
			array(
				'label'        => __( 'Show Product Image', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'render_type'  => 'template',
				'description'  => $this->get_pro_notice( __( 'Product images in order review is a Pro feature.', 'magical-products-display' ) ),
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_responsive_control(
			'image_size',
			array(
				'label'      => __( 'Image Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 30,
						'max' => 150,
					),
				),
				'default'    => array(
					'size' => 50,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-review__product-image img,
					 {{WRAPPER}} .mpd-order-review__product-thumbnail,
					 {{WRAPPER}} .mpd-order-review .product-name img' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; object-fit: cover;',
				),
				'condition'  => array(
					'show_product_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_subtotal',
			array(
				'label'        => __( 'Show Subtotal', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_shipping',
			array(
				'label'        => __( 'Show Shipping', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_tax',
			array(
				'label'        => __( 'Show Tax', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_coupons',
			array(
				'label'        => __( 'Show Applied Coupons', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
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
		// Title Style Section.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => __( 'Title', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-review__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-order-review__title',
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-review__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Table Style Section.
		$this->start_controls_section(
			'section_table_style',
			array(
				'label' => __( 'Table', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'table_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'table_border',
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table',
			)
		);

		$this->add_responsive_control(
			'table_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_padding',
			array(
				'label'      => __( 'Cell Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table th,
					 {{WRAPPER}} .woocommerce-checkout-review-order-table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Header Style Section.
		$this->start_controls_section(
			'section_header_style',
			array(
				'label' => __( 'Table Header', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'header_background_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table thead th' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'header_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table thead th' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'header_typography',
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table thead th',
			)
		);

		$this->end_controls_section();

		// Products Style Section.
		$this->start_controls_section(
			'section_products_style',
			array(
				'label' => __( 'Products', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_name_color',
			array(
				'label'     => __( 'Product Name Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table .product-name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_name_typography',
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table .product-name',
			)
		);

		$this->add_control(
			'product_total_color',
			array(
				'label'     => __( 'Product Total Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table .product-total' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_total_typography',
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table .product-total',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'product_row_border',
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table tbody tr',
			)
		);

		$this->end_controls_section();

		// Totals Style Section.
		$this->start_controls_section(
			'section_totals_style',
			array(
				'label' => __( 'Totals', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'totals_label_color',
			array(
				'label'     => __( 'Label Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table tfoot th' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'totals_value_color',
			array(
				'label'     => __( 'Value Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table tfoot td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'totals_typography',
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table tfoot th,
				              {{WRAPPER}} .woocommerce-checkout-review-order-table tfoot td',
			)
		);

		$this->add_control(
			'order_total_heading',
			array(
				'label'     => __( 'Order Total', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'order_total_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table tfoot .order-total th,
					 {{WRAPPER}} .woocommerce-checkout-review-order-table tfoot .order-total td' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'order_total_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-checkout-review-order-table tfoot .order-total th,
					 {{WRAPPER}} .woocommerce-checkout-review-order-table tfoot .order-total td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_total_typography',
				'selector' => '{{WRAPPER}} .woocommerce-checkout-review-order-table tfoot .order-total th,
				              {{WRAPPER}} .woocommerce-checkout-review-order-table tfoot .order-total td',
			)
		);

		$this->end_controls_section();

		// Container Style.
		$this->start_controls_section(
			'section_container_style',
			array(
				'label' => __( 'Container', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-order-review',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-order-review',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-review' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-order-review',
			)
		);

		$this->end_controls_section();

		// Empty Cart Notice Style.
		$this->start_controls_section(
			'section_empty_notice_style',
			array(
				'label' => __( 'Empty Cart Notice', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'empty_notice_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-info' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'empty_notice_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-info' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'empty_notice_icon_color',
			array(
				'label'     => __( 'Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-info::before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'empty_notice_typography',
				'selector' => '{{WRAPPER}} .woocommerce-info',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'empty_notice_border',
				'selector' => '{{WRAPPER}} .woocommerce-info',
			)
		);

		$this->add_responsive_control(
			'empty_notice_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'empty_notice_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'empty_notice_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'empty_notice_text_align',
			array(
				'label'     => __( 'Text Alignment', 'magical-products-display' ),
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
					'{{WRAPPER}} .woocommerce-info' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function render() {
		// Check WooCommerce.
		if ( ! $this->is_woocommerce_active() ) {
			$this->render_wc_required_notice();
			return;
		}

		$settings     = $this->get_settings_for_display();
		$is_editor    = \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode();
		$preview_mode = isset( $settings['editor_preview_mode'] ) ? $settings['editor_preview_mode'] : 'demo';

		// Get cart contents.
		$cart          = WC()->cart;
		$cart_is_empty = ! $cart || $cart->is_empty();

		// In editor, show based on preview mode selection.
		// On frontend, show real cart data.
		$show_demo  = $is_editor && 'demo' === $preview_mode;
		$show_empty = $is_editor && 'empty' === $preview_mode;

		// Add product image filter for Pro.
		if ( $this->is_pro() && 'yes' === $settings['show_product_image'] ) {
			add_filter( 'woocommerce_cart_item_name', array( $this, 'add_product_image_to_name' ), 10, 3 );
		}
		?>
		<div class="mpd-order-review">
			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['section_title'] ) ) : ?>
				<<?php echo esc_html( $settings['title_tag'] ); ?> class="mpd-order-review__title">
					<?php echo esc_html( $settings['section_title'] ); ?>
				</<?php echo esc_html( $settings['title_tag'] ); ?>>
			<?php endif; ?>

			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php
				// In editor: show based on preview mode selection.
				// On frontend: use WooCommerce's native function for proper AJAX updates.
				if ( $show_demo ) {
					$this->render_demo_order_review( $settings );
				} elseif ( $show_empty || $cart_is_empty ) {
					// Show empty cart notice (either by choice in editor or actual empty cart).
					echo '<p class="woocommerce-info">' . esc_html__( 'Your cart is currently empty.', 'magical-products-display' ) . '</p>';
				} elseif ( ! $cart_is_empty ) {
					// Use WooCommerce's native order review for proper AJAX fragment updates.
					// This ensures coupons, shipping, and totals update correctly.
					woocommerce_order_review();
				}
				?>
			</div>
		</div>
		<?php

		// Remove filter.
		if ( $this->is_pro() && 'yes' === $settings['show_product_image'] ) {
			remove_filter( 'woocommerce_cart_item_name', array( $this, 'add_product_image_to_name' ), 10 );
		}
	}

	/**
	 * Add product image to product name in order review.
	 *
	 * @since 2.0.0
	 *
	 * @param string $name        Product name.
	 * @param array  $cart_item   Cart item data.
	 * @param string $cart_item_key Cart item key.
	 * @return string Modified product name with image.
	 */
	public function add_product_image_to_name( $name, $cart_item, $cart_item_key ) {
		$_product = $cart_item['data'];

		if ( $_product && $_product->exists() ) {
			$thumbnail = $_product->get_image( 'thumbnail', array( 'class' => 'mpd-order-review__product-thumbnail' ) );
			$name      = '<span class="mpd-order-review__product-image">' . $thumbnail . '</span>' . $name;
		}

		return $name;
	}

	/**
	 * Render demo order review for editor preview.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_demo_order_review( $settings ) {
		$placeholder_img = wc_placeholder_img_src( 'thumbnail' );
		?>
		<table class="shop_table woocommerce-checkout-review-order-table">
			<thead>
				<tr>
					<th class="product-name"><?php esc_html_e( 'Product', 'magical-products-display' ); ?></th>
					<th class="product-total"><?php esc_html_e( 'Subtotal', 'magical-products-display' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr class="cart_item">
					<td class="product-name">
						<?php if ( 'yes' === $settings['show_product_image'] ) : ?>
							<span class="mpd-order-review__product-image">
								<img src="<?php echo esc_url( $placeholder_img ); ?>" class="mpd-order-review__product-thumbnail" alt="">
							</span>
						<?php endif; ?>
						<?php esc_html_e( 'Sample Product', 'magical-products-display' ); ?>
						<strong class="product-quantity">&times;&nbsp;2</strong>
					</td>
					<td class="product-total">
						<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>49.99</bdi></span>
					</td>
				</tr>
				<tr class="cart_item">
					<td class="product-name">
						<?php if ( 'yes' === $settings['show_product_image'] ) : ?>
							<span class="mpd-order-review__product-image">
								<img src="<?php echo esc_url( $placeholder_img ); ?>" class="mpd-order-review__product-thumbnail" alt="">
							</span>
						<?php endif; ?>
						<?php esc_html_e( 'Another Product', 'magical-products-display' ); ?>
						<strong class="product-quantity">&times;&nbsp;1</strong>
					</td>
					<td class="product-total">
						<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>29.99</bdi></span>
					</td>
				</tr>
				<tr class="cart_item">
					<td class="product-name">
						<?php if ( 'yes' === $settings['show_product_image'] ) : ?>
							<span class="mpd-order-review__product-image">
								<img src="<?php echo esc_url( $placeholder_img ); ?>" class="mpd-order-review__product-thumbnail" alt="">
							</span>
						<?php endif; ?>
						<?php esc_html_e( 'Premium Item', 'magical-products-display' ); ?>
						<strong class="product-quantity">&times;&nbsp;1</strong>
					</td>
					<td class="product-total">
						<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>49.99</bdi></span>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<?php if ( 'yes' === $settings['show_subtotal'] ) : ?>
					<tr class="cart-subtotal">
						<th><?php esc_html_e( 'Subtotal', 'magical-products-display' ); ?></th>
						<td><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>129.97</bdi></span></td>
					</tr>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_coupons'] ) : ?>
					<tr class="cart-discount coupon-demo">
						<th><?php esc_html_e( 'Coupon:', 'magical-products-display' ); ?> <span class="mpd-coupon-code">SAVE10</span></th>
						<td>-<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>10.00</bdi></span> <a href="#" class="woocommerce-remove-coupon" data-coupon="save10">[<?php esc_html_e( 'Remove', 'magical-products-display' ); ?>]</a></td>
					</tr>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_shipping'] ) : ?>
					<tr class="woocommerce-shipping-totals shipping">
						<th><?php esc_html_e( 'Shipping', 'magical-products-display' ); ?></th>
						<td><?php esc_html_e( 'Flat rate: $10.00', 'magical-products-display' ); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_tax'] ) : ?>
					<tr class="tax-total">
						<th><?php esc_html_e( 'Tax', 'magical-products-display' ); ?></th>
						<td><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>14.00</bdi></span></td>
					</tr>
				<?php endif; ?>

				<tr class="order-total">
					<th><?php esc_html_e( 'Total', 'magical-products-display' ); ?></th>
					<td><strong><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>153.97</bdi></span></strong></td>
				</tr>
			</tfoot>
		</table>
		<p class="mpd-editor-notice" style="text-align: center; padding: 10px; background: #e8f4fd; color: #0c5460; margin-top: 15px; border-radius: 4px;">
			<strong>📝 <?php esc_html_e( 'Editor Preview:', 'magical-products-display' ); ?></strong>
			<?php esc_html_e( 'This is a preview with sample data. Actual order details will display on the frontend.', 'magical-products-display' ); ?>
		</p>
		<?php
	}

	/**
	 * Render widget output in the editor.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function content_template() {
		?>
		<#
		var previewMode = settings.editor_preview_mode || 'demo';
		#>
		<div class="mpd-order-review">
			<# if ( 'yes' === settings.show_title && settings.section_title ) { #>
				<{{{ settings.title_tag }}} class="mpd-order-review__title">
					{{{ settings.section_title }}}
				</{{{ settings.title_tag }}}>
			<# } #>
			
			<div id="order_review" class="woocommerce-checkout-review-order">
				<# if ( 'empty' === previewMode ) { #>
					<p class="woocommerce-info"><?php esc_html_e( 'Your cart is currently empty.', 'magical-products-display' ); ?></p>
					<p class="mpd-editor-notice" style="text-align: center; padding: 10px; background: #e8f4fd; color: #0c5460; margin-top: 15px; border-radius: 4px;">
						<strong>📝 <?php esc_html_e( 'Editor Preview:', 'magical-products-display' ); ?></strong>
						<?php esc_html_e( 'Change "Editor Preview Mode" to "Demo Order Data" to style this widget.', 'magical-products-display' ); ?>
					</p>
				<# } else { #>
				<table class="shop_table woocommerce-checkout-review-order-table">
					<thead>
						<tr>
							<th class="product-name"><?php esc_html_e( 'Product', 'magical-products-display' ); ?></th>
							<th class="product-total"><?php esc_html_e( 'Subtotal', 'magical-products-display' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr class="cart_item">
							<td class="product-name">
								<# if ( 'yes' === settings.show_product_image ) { #>
								<span class="mpd-order-review__product-image"><img src="<?php echo esc_url( wc_placeholder_img_src( 'thumbnail' ) ); ?>" class="mpd-order-review__product-thumbnail" alt=""></span>
								<# } #>
								<?php esc_html_e( 'Sample Product', 'magical-products-display' ); ?> <strong class="product-quantity">&times;&nbsp;2</strong>
							</td>
							<td class="product-total">
								<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>49.99</bdi></span>
							</td>
						</tr>
						<tr class="cart_item">
							<td class="product-name">
								<# if ( 'yes' === settings.show_product_image ) { #>
								<span class="mpd-order-review__product-image"><img src="<?php echo esc_url( wc_placeholder_img_src( 'thumbnail' ) ); ?>" class="mpd-order-review__product-thumbnail" alt=""></span>
								<# } #>
								<?php esc_html_e( 'Another Product', 'magical-products-display' ); ?> <strong class="product-quantity">&times;&nbsp;1</strong>
							</td>
							<td class="product-total">
								<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>29.99</bdi></span>
							</td>
						</tr>
						<tr class="cart_item">
							<td class="product-name">
								<# if ( 'yes' === settings.show_product_image ) { #>
								<span class="mpd-order-review__product-image"><img src="<?php echo esc_url( wc_placeholder_img_src( 'thumbnail' ) ); ?>" class="mpd-order-review__product-thumbnail" alt=""></span>
								<# } #>
								<?php esc_html_e( 'Premium Item', 'magical-products-display' ); ?> <strong class="product-quantity">&times;&nbsp;1</strong>
							</td>
							<td class="product-total">
								<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>49.99</bdi></span>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<# if ( 'yes' === settings.show_subtotal ) { #>
						<tr class="cart-subtotal">
							<th><?php esc_html_e( 'Subtotal', 'magical-products-display' ); ?></th>
							<td><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>129.97</bdi></span></td>
						</tr>
						<# } #>
						<# if ( 'yes' === settings.show_coupons ) { #>
						<tr class="cart-discount coupon-demo">
							<th><?php esc_html_e( 'Coupon:', 'magical-products-display' ); ?> <span class="mpd-coupon-code">SAVE10</span></th>
							<td>-<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>10.00</bdi></span> <a href="#" class="woocommerce-remove-coupon" data-coupon="save10">[<?php esc_html_e( 'Remove', 'magical-products-display' ); ?>]</a></td>
						</tr>
						<# } #>
						<# if ( 'yes' === settings.show_shipping ) { #>
						<tr class="woocommerce-shipping-totals shipping">
							<th><?php esc_html_e( 'Shipping', 'magical-products-display' ); ?></th>
							<td><?php esc_html_e( 'Flat rate: $10.00', 'magical-products-display' ); ?></td>
						</tr>
						<# } #>
						<# if ( 'yes' === settings.show_tax ) { #>
						<tr class="tax-total">
							<th><?php esc_html_e( 'Tax', 'magical-products-display' ); ?></th>
							<td><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>14.00</bdi></span></td>
						</tr>
						<# } #>
						<tr class="order-total">
							<th><?php esc_html_e( 'Total', 'magical-products-display' ); ?></th>
							<td><strong><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>153.97</bdi></span></strong></td>
						</tr>
					</tfoot>
				</table>
				<p class="mpd-editor-notice" style="text-align: center; padding: 10px; background: #e8f4fd; color: #0c5460; margin-top: 15px; border-radius: 4px;">
					<strong>📝 <?php esc_html_e( 'Editor Preview:', 'magical-products-display' ); ?></strong>
					<?php esc_html_e( 'This is a preview with sample data. Actual order details will display on the frontend.', 'magical-products-display' ); ?>
				</p>
				<# } #>
			</div>
		</div>
		<?php
	}
}
