<?php
/**
 * Cart Table Widget
 *
 * Displays the cart table with products, quantities, and remove buttons.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\Cart;

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
 * Class Cart_Table
 *
 * @since 2.0.0
 */
class Cart_Table extends Widget_Base {

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
	protected $widget_icon = 'eicon-table';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-cart-table';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Cart Table', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-table';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'cart', 'table', 'products', 'woocommerce', 'checkout', 'shopping' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'wc-cart', 'mpd-cart-table' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-cart-widgets' );
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
			'table_style',
			array(
				'label'   => __( 'Table Style', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default' => __( 'Default', 'magical-products-display' ),
					'modern'  => __( 'Modern', 'magical-products-display' ),
					'minimal' => __( 'Minimal', 'magical-products-display' ),
				),
				'default' => 'default',
			)
		);

		$this->add_control(
			'show_thumbnail',
			array(
				'label'        => __( 'Show Product Thumbnail', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'thumbnail_size',
			array(
				'label'     => __( 'Thumbnail Size', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'thumbnail'    => __( 'Thumbnail', 'magical-products-display' ),
					'woocommerce_thumbnail' => __( 'WooCommerce Thumbnail', 'magical-products-display' ),
					'medium'       => __( 'Medium', 'magical-products-display' ),
					'large'        => __( 'Large', 'magical-products-display' ),
				),
				'default'   => 'woocommerce_thumbnail',
				'condition' => array(
					'show_thumbnail' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_sku',
			array(
				'label'        => __( 'Show SKU', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_quantity',
			array(
				'label'        => __( 'Show Quantity Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
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
			'show_remove_button',
			array(
				'label'        => __( 'Show Remove Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'update_cart_button_text',
			array(
				'label'       => __( 'Update Cart Button Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Update cart', 'magical-products-display' ),
				'placeholder' => __( 'Update cart', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_coupon_form',
			array(
				'label'        => __( 'Show Coupon Form', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Advanced Quantity Styles & AJAX Updates', 'magical-products-display' ) );
		}
			$this->add_control(
				'quantity_style',
				array(
					'label'   => __( 'Quantity Style', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'default'  => __( 'Default', 'magical-products-display' ),
						'modern'   => __( 'Modern (+/-)', 'magical-products-display' ),
						'buttons'  => __( 'Buttons', 'magical-products-display' ),
						'dropdown' => __( 'Dropdown', 'magical-products-display' ),
					),
					'default' => 'default',
				)
			);

			$this->add_control(
				'ajax_update',
				array(
					'label'        => __( 'AJAX Quantity Update', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Update cart automatically when quantity changes.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_stock_status',
				array(
					'label'        => __( 'Show Stock Status', 'magical-products-display' ),
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
		// Table Style.
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
				'selector' => '{{WRAPPER}} .mpd-cart-table',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'table_border',
				'selector' => '{{WRAPPER}} .mpd-cart-table',
			)
		);

		$this->add_responsive_control(
			'table_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'table_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-cart-table',
			)
		);

		$this->add_responsive_control(
			'table_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Header Style.
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
					'{{WRAPPER}} .mpd-cart-table thead th' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'header_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-table thead th' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'header_typography',
				'selector' => '{{WRAPPER}} .mpd-cart-table thead th',
			)
		);

		$this->add_responsive_control(
			'header_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Cell Style.
		$this->start_controls_section(
			'section_cell_style',
			array(
				'label' => __( 'Table Cells', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'cell_background_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-table tbody td' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'cell_alternate_background',
			array(
				'label'     => __( 'Alternate Row Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-table tbody tr:nth-child(even) td' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'cell_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-table tbody td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cell_typography',
				'selector' => '{{WRAPPER}} .mpd-cart-table tbody td',
			)
		);

		$this->add_responsive_control(
			'cell_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-table tbody td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'cell_border_color',
			array(
				'label'     => __( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-table td, {{WRAPPER}} .mpd-cart-table th' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Product Style.
		$this->start_controls_section(
			'section_product_style',
			array(
				'label' => __( 'Product Info', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'thumbnail_width',
			array(
				'label'      => __( 'Thumbnail Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 30,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 70,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-table .product-thumbnail img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'product_name_color',
			array(
				'label'     => __( 'Product Name Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-table .product-name a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'product_name_hover_color',
			array(
				'label'     => __( 'Product Name Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-table .product-name a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_name_typography',
				'label'    => __( 'Product Name Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-cart-table .product-name a',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => __( 'Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-table .product-price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'label'    => __( 'Price Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-cart-table .product-price',
			)
		);

		$this->end_controls_section();

		// Remove Button Style.
		$this->start_controls_section(
			'section_remove_button_style',
			array(
				'label' => __( 'Remove Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'remove_button_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-table .product-remove a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'remove_button_hover_color',
			array(
				'label'     => __( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-table .product-remove a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'remove_button_size',
			array(
				'label'      => __( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-table .product-remove a' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Update Button Style.
		$this->start_controls_section(
			'section_update_button_style',
			array(
				'label' => __( 'Update Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'update_button_typography',
				'selector' => '{{WRAPPER}} .mpd-update-cart-wrapper button[name="update_cart"], {{WRAPPER}} .mpd-cart-table-wrapper .actions button[name="update_cart"]',
			)
		);

		$this->start_controls_tabs( 'update_button_tabs' );

		$this->start_controls_tab(
			'update_button_normal',
			array(
				'label' => __( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'update_button_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-update-cart-wrapper button[name="update_cart"]' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .actions button[name="update_cart"]' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'update_button_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-update-cart-wrapper button[name="update_cart"]' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .actions button[name="update_cart"]' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'update_button_hover',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'update_button_hover_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-update-cart-wrapper button[name="update_cart"]:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .actions button[name="update_cart"]:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'update_button_hover_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-update-cart-wrapper button[name="update_cart"]:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .actions button[name="update_cart"]:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'update_button_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .mpd-update-cart-wrapper button[name="update_cart"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .actions button[name="update_cart"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'update_button_border',
				'selector' => '{{WRAPPER}} .mpd-update-cart-wrapper button[name="update_cart"], {{WRAPPER}} .mpd-cart-table-wrapper .actions button[name="update_cart"]',
			)
		);

		$this->add_responsive_control(
			'update_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-update-cart-wrapper button[name="update_cart"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .actions button[name="update_cart"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Coupon Form Style.
		$this->start_controls_section(
			'section_coupon_style',
			array(
				'label' => __( 'Coupon Form', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'coupon_layout',
			array(
				'label'   => __( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'inline'  => __( 'Inline', 'magical-products-display' ),
					'stacked' => __( 'Stacked', 'magical-products-display' ),
				),
				'default' => 'inline',
				'selectors_dictionary' => array(
					'inline'  => 'flex-direction: row !important; align-items: center !important;',
					'stacked' => 'flex-direction: column !important; align-items: stretch !important;',
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper' => 'display: flex !important; {{VALUE}}',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon' => 'display: flex !important; {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'coupon_gap',
			array(
				'label'      => __( 'Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'coupon_input_heading',
			array(
				'label'     => __( 'Input Field', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'coupon_input_width',
			array(
				'label'      => __( 'Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 400,
					),
					'%' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper input.input-text' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon input.input-text' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'coupon_input_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper input.input-text' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon input.input-text' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'coupon_input_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper input.input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon input.input-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'coupon_input_border',
				'selector' => '{{WRAPPER}} .mpd-coupon-form-wrapper input.input-text, {{WRAPPER}} .mpd-cart-table-wrapper .coupon input.input-text',
			)
		);

		$this->add_responsive_control(
			'coupon_input_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'coupon_input_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'coupon_button_heading',
			array(
				'label'     => __( 'Apply Button', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'coupon_button_typography',
				'selector' => '{{WRAPPER}} .mpd-coupon-form-wrapper button, {{WRAPPER}} .mpd-cart-table-wrapper .coupon button',
			)
		);

		$this->start_controls_tabs( 'coupon_button_tabs' );

		$this->start_controls_tab(
			'coupon_button_normal',
			array(
				'label' => __( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'coupon_button_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'coupon_button_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper button' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'coupon_button_hover',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'coupon_button_hover_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'coupon_button_hover_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper button:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'coupon_button_border',
				'selector'  => '{{WRAPPER}} .mpd-coupon-form-wrapper button, {{WRAPPER}} .mpd-cart-table-wrapper .coupon button',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'coupon_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'coupon_button_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-coupon-form-wrapper button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mpd-cart-table-wrapper .coupon button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Quantity Style (Pro).
		if ( $this->is_pro() ) {
			$this->start_controls_section(
				'section_quantity_style',
				array(
					'label' => __( 'Quantity Field', 'magical-products-display' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'quantity_width',
				array(
					'label'      => __( 'Width', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 50,
							'max' => 200,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-cart-table .quantity input.qty' => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .mpd-cart-table-wrapper .quantity input.qty' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'quantity_bg_color',
				array(
					'label'     => __( 'Background Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-cart-table .quantity input.qty' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .mpd-cart-table-wrapper .quantity input.qty' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .mpd-cart-table .mpd-quantity-wrapper' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'quantity_text_color',
				array(
					'label'     => __( 'Text Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-cart-table .quantity input.qty' => 'color: {{VALUE}};',
						'{{WRAPPER}} .mpd-cart-table-wrapper .quantity input.qty' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'quantity_border',
					'selector' => '{{WRAPPER}} .mpd-cart-table .quantity input.qty, {{WRAPPER}} .mpd-cart-table-wrapper .quantity input.qty, {{WRAPPER}} .mpd-cart-table .mpd-quantity-wrapper',
				)
			);

			$this->add_responsive_control(
				'quantity_border_radius',
				array(
					'label'      => __( 'Border Radius', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-cart-table .quantity input.qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .mpd-cart-table-wrapper .quantity input.qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .mpd-cart-table .mpd-quantity-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'quantity_button_heading',
				array(
					'label'     => __( 'Plus/Minus Buttons', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						'quantity_style' => array( 'modern', 'buttons' ),
					),
				)
			);

			$this->add_control(
				'quantity_btn_bg_color',
				array(
					'label'     => __( 'Button Background', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-cart-table .mpd-quantity-wrapper button' => 'background-color: {{VALUE}};',
					),
					'condition' => array(
						'quantity_style' => array( 'modern', 'buttons' ),
					),
				)
			);

			$this->add_control(
				'quantity_btn_color',
				array(
					'label'     => __( 'Button Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-cart-table .mpd-quantity-wrapper button' => 'color: {{VALUE}};',
					),
					'condition' => array(
						'quantity_style' => array( 'modern', 'buttons' ),
					),
				)
			);

			$this->add_control(
				'quantity_btn_hover_bg_color',
				array(
					'label'     => __( 'Button Hover Background', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-cart-table .mpd-quantity-wrapper button:hover' => 'background-color: {{VALUE}};',
					),
					'condition' => array(
						'quantity_style' => array( 'modern', 'buttons' ),
					),
				)
			);

			$this->end_controls_section();
		}

		// Stock Status Style (Pro).
		if ( $this->is_pro() ) {
			$this->start_controls_section(
				'section_stock_style',
				array(
					'label'     => __( 'Stock Status', 'magical-products-display' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'show_stock_status' => 'yes',
					),
				)
			);

			$this->add_control(
				'stock_in_stock_color',
				array(
					'label'     => __( 'In Stock Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-cart-table .product-stock .in-stock' => 'color: {{VALUE}};',
						'{{WRAPPER}} .mpd-cart-table .product-stock .stock.in-stock' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'stock_out_of_stock_color',
				array(
					'label'     => __( 'Out of Stock Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-cart-table .product-stock .out-of-stock' => 'color: {{VALUE}};',
						'{{WRAPPER}} .mpd-cart-table .product-stock .stock.out-of-stock' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'stock_backorder_color',
				array(
					'label'     => __( 'Backorder Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-cart-table .product-stock .available-on-backorder' => 'color: {{VALUE}};',
						'{{WRAPPER}} .mpd-cart-table .product-stock .stock.available-on-backorder' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'stock_typography',
					'selector' => '{{WRAPPER}} .mpd-cart-table .product-stock',
				)
			);

			$this->add_responsive_control(
				'stock_margin',
				array(
					'label'      => __( 'Margin', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-cart-table .product-stock' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		// Check if WooCommerce is active.
		if ( ! $this->is_woocommerce_active() ) {
			return;
		}

		// Get cart contents.
		$cart = WC()->cart;

		// Check if we're in editor mode.
		if ( $this->is_editor_mode() ) {
			$this->render_editor_preview( $settings );
			return;
		}

		// Check if cart is empty.
		if ( ! $cart || $cart->is_empty() ) {
			echo '<div class="mpd-cart-empty">';
			echo '<p>' . esc_html__( 'Your cart is currently empty.', 'magical-products-display' ) . '</p>';
			echo '</div>';
			return;
		}

		$this->render_cart_table( $settings, $cart );
	}

	/**
	 * Render cart table.
	 *
	 * @since 2.0.0
	 *
	 * @param array   $settings Widget settings.
	 * @param WC_Cart $cart     Cart object.
	 * @return void
	 */
	private function render_cart_table( $settings, $cart ) {
		$show_thumbnail    = 'yes' === $settings['show_thumbnail'];
		$show_quantity     = 'yes' === $settings['show_quantity'];
		$show_subtotal     = 'yes' === $settings['show_subtotal'];
		$show_remove       = 'yes' === $settings['show_remove_button'];
		$show_sku          = 'yes' === $settings['show_sku'];
		$show_coupon       = 'yes' === ( $settings['show_coupon_form'] ?? 'yes' );
		$thumbnail_size    = $settings['thumbnail_size'];
		$table_style       = $settings['table_style'] ?? 'default';
		$update_button_text = ! empty( $settings['update_cart_button_text'] ) ? $settings['update_cart_button_text'] : __( 'Update cart', 'magical-products-display' );

		// Pro features.
		$quantity_style   = $this->is_pro() && isset( $settings['quantity_style'] ) ? $settings['quantity_style'] : 'default';
		$ajax_update      = $this->is_pro() && isset( $settings['ajax_update'] ) && 'yes' === $settings['ajax_update'];
		$show_stock       = $this->is_pro() && isset( $settings['show_stock_status'] ) && 'yes' === $settings['show_stock_status'];

		$wrapper_class = 'mpd-cart-table-wrapper';
		$wrapper_class .= ' mpd-cart-table-style-' . $table_style;
		if ( $ajax_update ) {
			$wrapper_class .= ' mpd-ajax-cart';
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
				<?php do_action( 'woocommerce_before_cart_table' ); ?>

				<table class="mpd-cart-table shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
					<thead>
						<tr>
							<?php if ( $show_remove ) : ?>
								<th class="product-remove">&nbsp;</th>
							<?php endif; ?>
							<?php if ( $show_thumbnail ) : ?>
								<th class="product-thumbnail">&nbsp;</th>
							<?php endif; ?>
							<th class="product-name"><?php esc_html_e( 'Product', 'magical-products-display' ); ?></th>
							<th class="product-price"><?php esc_html_e( 'Price', 'magical-products-display' ); ?></th>
							<?php if ( $show_quantity ) : ?>
								<th class="product-quantity"><?php esc_html_e( 'Quantity', 'magical-products-display' ); ?></th>
							<?php endif; ?>
							<?php if ( $show_subtotal ) : ?>
								<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'magical-products-display' ); ?></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php do_action( 'woocommerce_before_cart_contents' ); ?>

						<?php
						foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
								?>
								<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

									<?php if ( $show_remove ) : ?>
										<td class="product-remove">
											<?php
											echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												'woocommerce_cart_item_remove_link',
												sprintf(
													'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
													esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
													esc_html__( 'Remove this item', 'magical-products-display' ),
													esc_attr( $product_id ),
													esc_attr( $_product->get_sku() )
												),
												$cart_item_key
											);
											?>
										</td>
									<?php endif; ?>

									<?php if ( $show_thumbnail ) : ?>
										<td class="product-thumbnail">
											<?php
											$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( $thumbnail_size ), $cart_item, $cart_item_key );

											if ( ! $product_permalink ) {
												echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											} else {
												printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											}
											?>
										</td>
									<?php endif; ?>

									<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'magical-products-display' ); ?>">
										<?php
										if ( ! $product_permalink ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
										}

										do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

										// Meta data.
										echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

										// SKU.
										if ( $show_sku && $_product->get_sku() ) {
											echo '<span class="product-sku">' . esc_html__( 'SKU:', 'magical-products-display' ) . ' ' . esc_html( $_product->get_sku() ) . '</span>';
										}

										// Stock status (Pro).
										if ( $show_stock ) {
											$stock_html = wc_get_stock_html( $_product );
											if ( empty( $stock_html ) ) {
												// Fallback when stock management is disabled.
												$stock_status = $_product->get_stock_status();
												$stock_class = 'instock' === $stock_status ? 'in-stock' : ( 'outofstock' === $stock_status ? 'out-of-stock' : 'on-backorder' );
												$stock_text = 'instock' === $stock_status ? __( 'In stock', 'magical-products-display' ) : ( 'outofstock' === $stock_status ? __( 'Out of stock', 'magical-products-display' ) : __( 'On backorder', 'magical-products-display' ) );
												echo '<span class="product-stock"><p class="stock ' . esc_attr( $stock_class ) . '">' . esc_html( $stock_text ) . '</p></span>';
											} else {
												echo '<span class="product-stock">' . wp_kses_post( $stock_html ) . '</span>';
											}
										}

										// Backorder notification.
										if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'magical-products-display' ) . '</p>', $product_id ) );
										}
										?>
									</td>

									<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'magical-products-display' ); ?>">
										<?php
										echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										?>
									</td>

									<?php if ( $show_quantity ) : ?>
										<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'magical-products-display' ); ?>">
											<?php
											if ( $_product->is_sold_individually() ) {
												$min_quantity = 1;
												$max_quantity = 1;
											} else {
												$min_quantity = 0;
												$max_quantity = $_product->get_max_purchase_quantity();
											}

											// Render quantity based on style.
											$product_quantity = $this->render_quantity_input(
												$quantity_style,
												$cart_item_key,
												$cart_item['quantity'],
												$min_quantity,
												$max_quantity,
												$_product
											);

											echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											?>
										</td>
									<?php endif; ?>

									<?php if ( $show_subtotal ) : ?>
										<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'magical-products-display' ); ?>">
											<?php
											echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											?>
										</td>
									<?php endif; ?>
								</tr>
								<?php
							}
						}
						?>

						<?php do_action( 'woocommerce_cart_contents' ); ?>

						<?php do_action( 'woocommerce_after_cart_contents' ); ?>
					</tbody>
				</table>

				<?php do_action( 'woocommerce_after_cart_table' ); ?>

				<div class="mpd-cart-actions-wrapper">
					<?php if ( $show_coupon && wc_coupons_enabled() ) : ?>
						<div class="mpd-coupon-form-wrapper">
							<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'magical-products-display' ); ?></label>
							<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'magical-products-display' ); ?>" />
							<button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'magical-products-display' ); ?>"><?php esc_html_e( 'Apply coupon', 'magical-products-display' ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php endif; ?>

					<div class="mpd-update-cart-wrapper">
						<button type="submit" class="button" name="update_cart" value="<?php echo esc_attr( $update_button_text ); ?>"><?php echo esc_html( $update_button_text ); ?></button>
						<?php do_action( 'woocommerce_cart_actions' ); ?>
					</div>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Render quantity input based on style.
	 *
	 * @since 2.0.0
	 *
	 * @param string     $style         Quantity style (default, modern, buttons, dropdown).
	 * @param string     $cart_item_key Cart item key.
	 * @param int        $quantity      Current quantity.
	 * @param int        $min           Minimum quantity.
	 * @param int        $max           Maximum quantity.
	 * @param WC_Product $product       Product object.
	 * @return string HTML output.
	 */
	private function render_quantity_input( $style, $cart_item_key, $quantity, $min, $max, $product ) {
		$input_name = "cart[{$cart_item_key}][qty]";
		$max_attr   = ( $max > 0 ) ? ' max="' . esc_attr( $max ) . '"' : '';

		switch ( $style ) {
			case 'modern':
				return sprintf(
					'<div class="quantity mpd-quantity-wrapper">
						<button type="button" class="mpd-qty-minus" aria-label="%s">−</button>
						<input type="number" class="input-text qty text" name="%s" value="%s" min="%s"%s step="1" inputmode="numeric" autocomplete="off" />
						<button type="button" class="mpd-qty-plus" aria-label="%s">+</button>
					</div>',
					esc_attr__( 'Decrease quantity', 'magical-products-display' ),
					esc_attr( $input_name ),
					esc_attr( $quantity ),
					esc_attr( $min ),
					$max_attr,
					esc_attr__( 'Increase quantity', 'magical-products-display' )
				);

			case 'buttons':
				return sprintf(
					'<div class="quantity mpd-quantity-style-buttons">
						<button type="button" class="mpd-qty-minus" aria-label="%s">−</button>
						<input type="number" class="input-text qty text" name="%s" value="%s" min="%s"%s step="1" inputmode="numeric" autocomplete="off" />
						<button type="button" class="mpd-qty-plus" aria-label="%s">+</button>
					</div>',
					esc_attr__( 'Decrease quantity', 'magical-products-display' ),
					esc_attr( $input_name ),
					esc_attr( $quantity ),
					esc_attr( $min ),
					$max_attr,
					esc_attr__( 'Increase quantity', 'magical-products-display' )
				);

			case 'dropdown':
				$max_dropdown = ( $max > 0 && $max <= 20 ) ? $max : 20;
				$options      = '';
				for ( $i = $min; $i <= $max_dropdown; $i++ ) {
					$selected = ( $i === (int) $quantity ) ? ' selected' : '';
					$options .= sprintf( '<option value="%d"%s>%d</option>', $i, $selected, $i );
				}
				return sprintf(
					'<div class="quantity mpd-quantity-style-dropdown">
						<select name="%s" class="qty-dropdown">%s</select>
					</div>',
					esc_attr( $input_name ),
					$options
				);

			default:
				// Use WooCommerce default quantity input.
				// Keep WC's default classes (input-text, qty, text) so that
				// cart.js can detect quantity changes via 'input.qty'.
				return woocommerce_quantity_input(
					array(
						'input_name'   => $input_name,
						'input_value'  => $quantity,
						'max_value'    => $max,
						'min_value'    => $min,
						'product_name' => $product->get_name(),
						'classes'      => array( 'input-text', 'qty', 'text', 'mpd-quantity-style-default' ),
					),
					$product,
					false
				);
		}
	}

	/**
	 * Render quantity input for editor preview.
	 *
	 * @since 2.0.0
	 *
	 * @param string $style    Quantity style.
	 * @param int    $quantity Sample quantity value.
	 * @return string HTML output.
	 */
	private function render_editor_quantity_input( $style, $quantity ) {
		switch ( $style ) {
			case 'modern':
				return sprintf(
					'<div class="quantity mpd-quantity-wrapper">
						<button type="button" class="mpd-qty-minus" disabled aria-label="%s">−</button>
						<input type="number" class="input-text qty text" value="%s" min="0" disabled />
						<button type="button" class="mpd-qty-plus" disabled aria-label="%s">+</button>
					</div>',
					esc_attr__( 'Decrease quantity', 'magical-products-display' ),
					esc_attr( $quantity ),
					esc_attr__( 'Increase quantity', 'magical-products-display' )
				);

			case 'buttons':
				return sprintf(
					'<div class="quantity mpd-quantity-style-buttons">
						<button type="button" class="mpd-qty-minus" disabled aria-label="%s">−</button>
						<input type="number" class="input-text qty text" value="%s" min="0" disabled />
						<button type="button" class="mpd-qty-plus" disabled aria-label="%s">+</button>
					</div>',
					esc_attr__( 'Decrease quantity', 'magical-products-display' ),
					esc_attr( $quantity ),
					esc_attr__( 'Increase quantity', 'magical-products-display' )
				);

			case 'dropdown':
				$options = '';
				for ( $i = 1; $i <= 10; $i++ ) {
					$selected = ( $i === (int) $quantity ) ? ' selected' : '';
					$options .= sprintf( '<option value="%d"%s>%d</option>', $i, $selected, $i );
				}
				return sprintf(
					'<div class="quantity mpd-quantity-style-dropdown">
						<select class="qty-dropdown" disabled>%s</select>
					</div>',
					$options
				);

			default:
				return sprintf(
					'<div class="quantity">
						<input type="number" class="input-text qty text" value="%s" min="0" disabled />
					</div>',
					esc_attr( $quantity )
				);
		}
	}

	/**
	 * Render editor preview.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_editor_preview( $settings ) {
		$show_thumbnail = 'yes' === $settings['show_thumbnail'];
		$show_quantity  = 'yes' === $settings['show_quantity'];
		$show_subtotal  = 'yes' === $settings['show_subtotal'];
		$show_remove    = 'yes' === $settings['show_remove_button'];
		$show_sku       = isset( $settings['show_sku'] ) && 'yes' === $settings['show_sku'];
		$show_coupon    = 'yes' === ( $settings['show_coupon_form'] ?? 'yes' );
		$table_style    = $settings['table_style'] ?? 'default';
		$show_stock     = $this->is_pro() && isset( $settings['show_stock_status'] ) && 'yes' === $settings['show_stock_status'];
		$quantity_style = $this->is_pro() && isset( $settings['quantity_style'] ) ? $settings['quantity_style'] : 'default';
		
		$wrapper_class = 'mpd-cart-table-wrapper mpd-cart-table-style-' . esc_attr( $table_style ) . ' mpd-editor-preview';
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<table class="mpd-cart-table shop_table" cellspacing="0">
				<thead>
					<tr>
						<?php if ( $show_remove ) : ?>
							<th class="product-remove">&nbsp;</th>
						<?php endif; ?>
						<?php if ( $show_thumbnail ) : ?>
							<th class="product-thumbnail">&nbsp;</th>
						<?php endif; ?>
						<th class="product-name"><?php esc_html_e( 'Product', 'magical-products-display' ); ?></th>
						<th class="product-price"><?php esc_html_e( 'Price', 'magical-products-display' ); ?></th>
						<?php if ( $show_quantity ) : ?>
							<th class="product-quantity"><?php esc_html_e( 'Quantity', 'magical-products-display' ); ?></th>
						<?php endif; ?>
						<?php if ( $show_subtotal ) : ?>
							<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'magical-products-display' ); ?></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					// Get sample products for preview.
					$products = wc_get_products( array(
						'status' => 'publish',
						'limit'  => 3,
					) );

					if ( empty( $products ) ) {
						?>
						<tr>
							<td colspan="6" style="text-align: center; padding: 30px;">
								<?php esc_html_e( 'No products found. Add some products to see the cart preview.', 'magical-products-display' ); ?>
							</td>
						</tr>
						<?php
					} else {
						foreach ( $products as $product ) {
							$sample_qty = wp_rand( 1, 3 );
							?>
							<tr class="cart_item">
								<?php if ( $show_remove ) : ?>
									<td class="product-remove">
										<a href="#" class="remove">&times;</a>
									</td>
								<?php endif; ?>

								<?php if ( $show_thumbnail ) : ?>
									<td class="product-thumbnail">
										<?php echo $product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</td>
								<?php endif; ?>

								<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'magical-products-display' ); ?>">
									<a href="#"><?php echo esc_html( $product->get_name() ); ?></a>
									<?php
									// SKU.
									if ( $show_sku && $product->get_sku() ) {
										echo '<span class="product-sku">' . esc_html__( 'SKU:', 'magical-products-display' ) . ' ' . esc_html( $product->get_sku() ) . '</span>';
									}
									// Stock status (Pro).
									if ( $show_stock ) {
										$stock_html = wc_get_stock_html( $product );
										if ( empty( $stock_html ) ) {
											// Fallback when stock management is disabled.
											$stock_status = $product->get_stock_status();
											$stock_class = 'instock' === $stock_status ? 'in-stock' : ( 'outofstock' === $stock_status ? 'out-of-stock' : 'on-backorder' );
											$stock_text = 'instock' === $stock_status ? __( 'In stock', 'magical-products-display' ) : ( 'outofstock' === $stock_status ? __( 'Out of stock', 'magical-products-display' ) : __( 'On backorder', 'magical-products-display' ) );
											echo '<span class="product-stock"><p class="stock ' . esc_attr( $stock_class ) . '">' . esc_html( $stock_text ) . '</p></span>';
										} else {
											echo '<span class="product-stock">' . wp_kses_post( $stock_html ) . '</span>';
										}
									}
									?>
								</td>

								<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'magical-products-display' ); ?>">
									<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</td>

								<?php if ( $show_quantity ) : ?>
									<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'magical-products-display' ); ?>">
										<?php echo $this->render_editor_quantity_input( $quantity_style, $sample_qty ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</td>
								<?php endif; ?>

								<?php if ( $show_subtotal ) : ?>
									<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'magical-products-display' ); ?>">
										<?php echo wc_price( $product->get_price() * $sample_qty ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</td>
								<?php endif; ?>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>

			<div class="mpd-cart-actions-wrapper">
				<?php if ( $show_coupon ) : ?>
				<div class="mpd-coupon-form-wrapper">
					<label for="coupon_code_preview" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'magical-products-display' ); ?></label>
					<input type="text" name="coupon_code" id="coupon_code_preview" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'magical-products-display' ); ?>" disabled />
					<button type="button" class="button" disabled><?php esc_html_e( 'Apply coupon', 'magical-products-display' ); ?></button>
				</div>
				<?php endif; ?>
				<div class="mpd-update-cart-wrapper">
					<button type="button" class="button" name="update_cart" disabled><?php echo esc_html( $settings['update_cart_button_text'] ); ?></button>
				</div>
			</div>
			<p class="mpd-editor-notice" style="text-align: center; padding: 10px; background: #e8f4fd; color: #0c5460; margin-top: 10px; border-radius: 4px;">
				<strong>📝 <?php esc_html_e( 'Editor Preview:', 'magical-products-display' ); ?></strong>
				<?php esc_html_e( 'This is a preview with sample products. The actual cart will display on the frontend.', 'magical-products-display' ); ?>
			</p>
		</div>
		<?php
	}
}
