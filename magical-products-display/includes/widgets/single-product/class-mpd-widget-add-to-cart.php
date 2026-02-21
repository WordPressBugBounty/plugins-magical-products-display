<?php
/**
 * Add to Cart Widget
 *
 * Displays the add to cart button on single product pages.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\SingleProduct;

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
 * Class Add_To_Cart
 *
 * @since 2.0.0
 */
class Add_To_Cart extends Widget_Base {

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
		return 'mpd-add-to-cart';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Add to Cart', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-add-to-cart';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'cart', 'add to cart', 'buy', 'button', 'woocommerce', 'single' );
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
				'label' => __( 'Add to Cart', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
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
				'default'      => 'yes',
			)
		);

		$this->add_responsive_control(
			'layout',
			array(
				'label'        => __( 'Layout', 'magical-products-display' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'inline'  => __( 'Inline', 'magical-products-display' ),
					'stacked' => __( 'Stacked', 'magical-products-display' ),
				),
				'default'      => 'inline',
				'prefix_class' => 'mpd-add-to-cart-layout%s-',
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
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .mpd-add-to-cart' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .mpd-add-to-cart form.cart' => 'justify-content: {{VALUE}};',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Quantity Style & Sticky Cart', 'magical-products-display' ) );
		}
			$this->add_control(
				'quantity_style',
				array(
					'label'   => __( 'Quantity Style', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'default' => __( 'Default', 'magical-products-display' ),
						'modern'  => __( 'Modern (+/-)', 'magical-products-display' ),
						'buttons' => __( 'Buttons', 'magical-products-display' ),
						'dropdown' => __( 'Dropdown', 'magical-products-display' ),
					),
					'default' => 'default',
					'condition' => array(
						'show_quantity' => 'yes',
					),
				)
			);

			$this->add_control(
				'sticky_cart',
				array(
					'label'        => __( 'Sticky Add to Cart', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Show a sticky bar at the bottom on mobile.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_stock_info',
				array(
					'label'        => __( 'Show Stock Info', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			// AJAX Add to Cart.
			$this->add_control(
				'ajax_add_to_cart_heading',
				array(
					'label'     => __( 'AJAX Add to Cart', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'ajax_add_to_cart',
				array(
					'label'        => __( 'AJAX Add to Cart', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'description'  => __( 'Add to cart without page reload with loading animation.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_view_cart',
				array(
					'label'        => __( 'Show View Cart Button', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'description'  => __( 'Show a View Cart button after adding to cart.', 'magical-products-display' ),
					'condition'    => array(
						'ajax_add_to_cart' => 'yes',
					),
				)
			);

			$this->add_control(
				'view_cart_text',
				array(
					'label'       => __( 'View Cart Text', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'View Cart', 'magical-products-display' ),
					'placeholder' => __( 'View Cart', 'magical-products-display' ),
					'condition'   => array(
						'ajax_add_to_cart' => 'yes',
						'show_view_cart'   => 'yes',
					),
				)
			);

			// Buy Now Mode.
			$this->add_control(
				'buy_now_heading',
				array(
					'label'     => __( 'Buy Now', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'show_buy_now',
				array(
					'label'        => __( 'Show Buy Now Button', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Add a Buy Now button that adds to cart and goes directly to checkout.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'buy_now_text',
				array(
					'label'       => __( 'Buy Now Text', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Buy Now', 'magical-products-display' ),
					'placeholder' => __( 'Buy Now', 'magical-products-display' ),
					'condition'   => array(
						'show_buy_now' => 'yes',
					),
				)
			);

			// Button Labels & Icon.
			$this->add_control(
				'button_labels_heading',
				array(
					'label'     => __( 'Button Labels & Icon', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'custom_button_text',
				array(
					'label'       => __( 'Custom Button Text', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => __( 'e.g., Add to Bag', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'added_to_cart_text',
				array(
					'label'       => __( 'Added to Cart Text', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => __( 'e.g., Added!', 'magical-products-display' ),
					'description' => __( 'Text shown on the button after successfully adding to cart.', 'magical-products-display' ),
					'condition'   => array(
						'ajax_add_to_cart' => 'yes',
					),
				)
			);

			$this->add_control(
				'show_icon',
				array(
					'label'        => __( 'Show Cart Icon', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'icon',
				array(
					'label'     => __( 'Icon', 'magical-products-display' ),
					'type'      => Controls_Manager::ICONS,
					'default'   => array(
						'value'   => 'eicon-cart-solid',
						'library' => 'elementor',
					),
					'condition' => array(
						'show_icon' => 'yes',
					),
				)
			);

			$this->add_control(
				'icon_position',
				array(
					'label'     => __( 'Icon Position', 'magical-products-display' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'before' => __( 'Before Text', 'magical-products-display' ),
						'after'  => __( 'After Text', 'magical-products-display' ),
					),
					'default'   => 'before',
					'condition' => array(
						'show_icon' => 'yes',
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
		// Button Style.
		$this->start_controls_section(
			'section_style_button',
			array(
				'label' => __( 'Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'button_style_tabs' );

		// Normal State.
		$this->start_controls_tab(
			'button_style_normal',
			array(
				'label' => __( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-add-to-cart .single_add_to_cart_button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-add-to-cart .single_add_to_cart_button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		// Hover State.
		$this->start_controls_tab(
			'button_style_hover',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-add-to-cart .single_add_to_cart_button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-add-to-cart .single_add_to_cart_button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-add-to-cart .single_add_to_cart_button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .mpd-add-to-cart .single_add_to_cart_button',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-add-to-cart .single_add_to_cart_button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-add-to-cart .single_add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-add-to-cart .single_add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-add-to-cart .single_add_to_cart_button',
			)
		);

		$this->end_controls_section();

		// Quantity Style.
		$this->start_controls_section(
			'section_style_quantity',
			array(
				'label'     => __( 'Quantity', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_quantity' => 'yes',
				),
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
					'{{WRAPPER}} .mpd-add-to-cart .quantity input.qty' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-add-to-cart .mpd-quantity-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'quantity_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-add-to-cart .quantity input.qty' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-add-to-cart .mpd-quantity-wrapper .mpd-qty-input' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-add-to-cart .mpd-quantity-wrapper .mpd-qty-btn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-add-to-cart .mpd-quantity-wrapper .mpd-qty-select' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'quantity_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-add-to-cart .quantity input.qty' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-add-to-cart .mpd-quantity-wrapper' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-add-to-cart .mpd-quantity-wrapper .mpd-qty-input' => 'background-color: transparent;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'quantity_border',
				'selector' => '{{WRAPPER}} .mpd-add-to-cart .quantity input.qty, {{WRAPPER}} .mpd-add-to-cart .mpd-quantity-wrapper',
			)
		);

		$this->add_responsive_control(
			'quantity_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-add-to-cart .quantity input.qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .mpd-add-to-cart .mpd-quantity-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'quantity_spacing',
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
				'selectors'  => array(
					'{{WRAPPER}} .mpd-add-to-cart .quantity' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-add-to-cart .mpd-quantity-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Variations Style.
		$this->start_controls_section(
			'section_style_variations',
			array(
				'label' => __( 'Variations', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'variation_label_color',
			array(
				'label'     => __( 'Label Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-add-to-cart .variations label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variation_label_typography',
				'selector' => '{{WRAPPER}} .mpd-add-to-cart .variations label',
			)
		);

		$this->add_responsive_control(
			'variation_spacing',
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
				'selectors'  => array(
					'{{WRAPPER}} .mpd-add-to-cart .variations tr' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// View Cart Button Style (Pro).
		$this->start_controls_section(
			'section_style_view_cart',
			array(
				'label'     => __( 'View Cart Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'ajax_add_to_cart' => 'yes',
					'show_view_cart'   => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'view_cart_style_tabs' );

		$this->start_controls_tab(
			'view_cart_style_normal',
			array(
				'label' => __( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'view_cart_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-view-cart-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-view-cart-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'view_cart_style_hover',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'view_cart_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-view-cart-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-view-cart-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-view-cart-btn:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'view_cart_typography',
				'selector'  => '{{WRAPPER}} .mpd-view-cart-btn',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'view_cart_border',
				'selector' => '{{WRAPPER}} .mpd-view-cart-btn',
			)
		);

		$this->add_responsive_control(
			'view_cart_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-view-cart-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'view_cart_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-view-cart-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'view_cart_spacing',
			array(
				'label'      => __( 'Spacing (Top)', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-view-cart-btn' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Buy Now Button Style (Pro).
		$this->start_controls_section(
			'section_style_buy_now',
			array(
				'label'     => __( 'Buy Now Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_buy_now' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'buy_now_style_tabs' );

		$this->start_controls_tab(
			'buy_now_style_normal',
			array(
				'label' => __( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'buy_now_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-buy-now-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'buy_now_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-buy-now-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'buy_now_style_hover',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'buy_now_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-buy-now-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'buy_now_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-buy-now-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'buy_now_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-buy-now-btn:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'buy_now_typography',
				'selector'  => '{{WRAPPER}} .mpd-buy-now-btn',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'buy_now_border',
				'selector' => '{{WRAPPER}} .mpd-buy-now-btn',
			)
		);

		$this->add_responsive_control(
			'buy_now_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-buy-now-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'buy_now_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-buy-now-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'buy_now_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-buy-now-btn',
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
		global $product;

		$product = $this->get_current_product();

		if ( ! $product ) {
			$this->render_editor_placeholder(
				__( 'Add to Cart', 'magical-products-display' ),
				__( 'This widget displays the add to cart form. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-add-to-cart' );

		// Pro: Add sticky cart class.
		if ( $this->is_pro() && 'yes' === ( $settings['sticky_cart'] ?? '' ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'mpd-sticky-cart-enabled' );
			$this->add_render_attribute( 'wrapper', 'data-sticky-cart', 'true' );
		}

		// Pro: Quantity style class.
		if ( $this->is_pro() && ! empty( $settings['quantity_style'] ) && 'default' !== $settings['quantity_style'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'mpd-qty-style-' . esc_attr( $settings['quantity_style'] ) );
		}

		// Pro: Custom button text filter.
		$custom_button_filter = null;
		if ( $this->is_pro() && ! empty( $settings['custom_button_text'] ) ) {
			$custom_text = esc_html( $settings['custom_button_text'] );
			$custom_button_filter = function() use ( $custom_text ) {
				return $custom_text;
			};
			add_filter( 'woocommerce_product_single_add_to_cart_text', $custom_button_filter );
		}

		// Pro: Add icon to button.
		$button_icon_filter = null;
		if ( $this->is_pro() && 'yes' === ( $settings['show_icon'] ?? '' ) && ! empty( $settings['icon']['value'] ) ) {
			$icon = $settings['icon'];
			$icon_position = $settings['icon_position'] ?? 'before';
			$button_icon_filter = function( $button, $product_obj ) use ( $icon, $icon_position ) {
				return $this->add_icon_to_button( $button, $icon, $icon_position );
			};
			add_filter( 'woocommerce_loop_add_to_cart_link', $button_icon_filter, 10, 2 );
			
			// Also filter the single add to cart button via action.
			add_action( 'woocommerce_before_add_to_cart_button', function() use ( $icon, $icon_position ) {
				if ( 'before' === $icon_position ) {
					echo '<span class="mpd-cart-icon mpd-cart-icon-before">';
					\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
					echo '</span>';
				}
			} );
			add_action( 'woocommerce_after_add_to_cart_button', function() use ( $icon, $icon_position ) {
				if ( 'after' === $icon_position ) {
					echo '<span class="mpd-cart-icon mpd-cart-icon-after">';
					\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
					echo '</span>';
				}
			} );
		}

		// Pro: AJAX Add to Cart data attributes.
		if ( $this->is_pro() && 'yes' === ( $settings['ajax_add_to_cart'] ?? '' ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'mpd-ajax-add-to-cart' );
			$this->add_render_attribute( 'wrapper', 'data-product-id', $product->get_id() );
			$this->add_render_attribute( 'wrapper', 'data-product-type', $product->get_type() );

			if ( 'yes' === ( $settings['show_view_cart'] ?? '' ) ) {
				$this->add_render_attribute( 'wrapper', 'data-show-view-cart', 'yes' );
				$view_cart_text = ! empty( $settings['view_cart_text'] ) ? $settings['view_cart_text'] : __( 'View Cart', 'magical-products-display' );
				$this->add_render_attribute( 'wrapper', 'data-view-cart-text', esc_attr( $view_cart_text ) );
				$this->add_render_attribute( 'wrapper', 'data-cart-url', esc_url( wc_get_cart_url() ) );
			}

			$added_text = ! empty( $settings['added_to_cart_text'] ) ? $settings['added_to_cart_text'] : __( 'Added!', 'magical-products-display' );
			$this->add_render_attribute( 'wrapper', 'data-added-text', esc_attr( $added_text ) );
		}

		// Pro: Buy Now button settings.
		$show_buy_now = $this->is_pro() && 'yes' === ( $settings['show_buy_now'] ?? '' );
		if ( $show_buy_now ) {
			$this->add_render_attribute( 'wrapper', 'data-checkout-url', esc_url( wc_get_checkout_url() ) );
		}

		// Hide quantity if disabled.
		if ( 'yes' !== $settings['show_quantity'] ) {
			add_filter( 'woocommerce_quantity_input_args', array( $this, 'hide_quantity_input' ), 999 );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			// Pro: Show stock info before add to cart.
			if ( $this->is_pro() && 'yes' === ( $settings['show_stock_info'] ?? '' ) ) {
				$this->render_stock_info( $product );
			}

			woocommerce_template_single_add_to_cart();

			// Pro: Buy Now button.
			if ( $show_buy_now && $product->is_purchasable() && $product->is_in_stock() ) {
				$buy_now_text = ! empty( $settings['buy_now_text'] ) ? $settings['buy_now_text'] : __( 'Buy Now', 'magical-products-display' );
				?>
				<button type="button" class="button mpd-buy-now-btn" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
					<svg class="mpd-btn-spinner mpd-spinner-hidden" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
					<span class="mpd-btn-text"><?php echo esc_html( $buy_now_text ); ?></span>
				</button>
				<?php
			}
			?>
		</div>
		<?php

		// Output inline quantity script for all styles
		if ( $this->is_pro() && 'yes' === $settings['show_quantity'] && ! empty( $settings['quantity_style'] ) && 'default' !== $settings['quantity_style'] ) {
			$this->render_quantity_inline_script( $settings['quantity_style'] );
		}

		// Remove quantity filter.
		if ( 'yes' !== $settings['show_quantity'] ) {
			remove_filter( 'woocommerce_quantity_input_args', array( $this, 'hide_quantity_input' ), 999 );
		}

		// Remove custom button text filter.
		if ( null !== $custom_button_filter ) {
			remove_filter( 'woocommerce_product_single_add_to_cart_text', $custom_button_filter );
		}

		// Remove button icon filter.
		if ( null !== $button_icon_filter ) {
			remove_filter( 'woocommerce_loop_add_to_cart_link', $button_icon_filter, 10 );
		}
	}

	/**
	 * Render quantity inline script.
	 *
	 * @since 2.0.0
	 *
	 * @param string $style Quantity style.
	 * @return void
	 */
	protected function render_quantity_inline_script( $style ) {
		$widget_id = $this->get_id();
		?>
		<script type="text/javascript">
		(function($) {
			function initMPDQuantity_<?php echo esc_js( $widget_id ); ?>() {
				var style = '<?php echo esc_js( $style ); ?>';
				
				// Find all mpd-add-to-cart wrappers that have the style class
				var $wrappers = $('.mpd-add-to-cart.mpd-qty-style-' + style);
				
				// Also check without style class as fallback
				if (!$wrappers.length) {
					$wrappers = $('.mpd-add-to-cart');
				}
				
				$wrappers.each(function() {
					var $wrapper = $(this);
					var $quantity = $wrapper.find('.quantity');
					
					if (!$quantity.length) return;
					
					// Skip if already processed
					if ($quantity.hasClass('mpd-qty-processed') || $wrapper.find('.mpd-quantity-wrapper').length) return;
					$quantity.addClass('mpd-qty-processed');
					
					var $input = $quantity.find('input.qty, input[name="quantity"]');
					if (!$input.length) return;
					
					var min = parseFloat($input.attr('min')) || 1;
					var max = parseFloat($input.attr('max')) || '';
					var step = parseFloat($input.attr('step')) || 1;
					var value = parseFloat($input.val()) || 1;
					var name = $input.attr('name') || 'quantity';
					var inputId = $input.attr('id') || 'quantity_' + Math.random().toString(36).substr(2, 9);
					
					if (style === 'dropdown') {
						// Create dropdown
						var maxDropdown = max ? Math.min(parseFloat(max), 20) : 20;
						var options = '';
						for (var i = min; i <= maxDropdown; i += step) {
							var selected = (i === value) ? ' selected' : '';
							options += '<option value="' + i + '"' + selected + '>' + i + '</option>';
						}
						
						var $newWrapper = $('<div class="mpd-quantity-wrapper mpd-qty-dropdown"></div>');
						var $select = $('<select id="' + inputId + '" name="' + name + '" class="mpd-qty-select">' + options + '</select>');
						$newWrapper.append($select);
						$quantity.replaceWith($newWrapper);
						
					} else {
						// Create +/- buttons (modern or buttons style)
						var $newWrapper = $('<div class="mpd-quantity-wrapper mpd-qty-' + style + '"></div>');
						var $minusBtn = $('<button type="button" class="mpd-qty-btn mpd-qty-minus" aria-label="Decrease quantity"><span class="mpd-qty-icon">−</span></button>');
						var $plusBtn = $('<button type="button" class="mpd-qty-btn mpd-qty-plus" aria-label="Increase quantity"><span class="mpd-qty-icon">+</span></button>');
						
						// Clone input and reset styles
						var $newInput = $input.clone();
						$newInput.removeAttr('style').attr('class', 'qty mpd-qty-input');
						$newInput.attr('type', 'number');
						
						$newWrapper.append($minusBtn).append($newInput).append($plusBtn);
						$quantity.replaceWith($newWrapper);
						
						// Bind click handlers
						$newWrapper.find('.mpd-qty-minus').on('click', function(e) {
							e.preventDefault();
							e.stopPropagation();
							var $inp = $newWrapper.find('.mpd-qty-input');
							var val = parseFloat($inp.val()) || 1;
							var minVal = parseFloat($inp.attr('min')) || 1;
							var stepVal = parseFloat($inp.attr('step')) || 1;
							if (val > minVal) {
								$inp.val(val - stepVal).trigger('change').trigger('input');
							}
							return false;
						});
						
						$newWrapper.find('.mpd-qty-plus').on('click', function(e) {
							e.preventDefault();
							e.stopPropagation();
							var $inp = $newWrapper.find('.mpd-qty-input');
							var val = parseFloat($inp.val()) || 1;
							var maxVal = parseFloat($inp.attr('max')) || '';
							var stepVal = parseFloat($inp.attr('step')) || 1;
							if (maxVal === '' || val < parseFloat(maxVal)) {
								$inp.val(val + stepVal).trigger('change').trigger('input');
							}
							return false;
						});
					}
				});
			}
			
			// Run immediately if DOM is ready
			if (document.readyState === 'complete' || document.readyState === 'interactive') {
				setTimeout(initMPDQuantity_<?php echo esc_js( $widget_id ); ?>, 100);
			} else {
				$(document).ready(function() {
					setTimeout(initMPDQuantity_<?php echo esc_js( $widget_id ); ?>, 100);
				});
			}
			
			// Also run on window load as backup
			$(window).on('load', function() {
				setTimeout(initMPDQuantity_<?php echo esc_js( $widget_id ); ?>, 200);
			});
			
			// Also run on Elementor frontend init (for editor preview)
			$(window).on('elementor/frontend/init', function() {
				if (typeof elementorFrontend !== 'undefined') {
					elementorFrontend.hooks.addAction('frontend/element_ready/mpd-add-to-cart.default', function($scope) {
						setTimeout(initMPDQuantity_<?php echo esc_js( $widget_id ); ?>, 100);
					});
				}
			});
			
			// For Elementor editor - listen for widget render complete
			if (typeof elementor !== 'undefined') {
				elementor.hooks.addAction('panel/open_editor/widget/mpd-add-to-cart', function() {
					setTimeout(initMPDQuantity_<?php echo esc_js( $widget_id ); ?>, 300);
				});
			}
		})(jQuery);
		</script>
		<?php
	}

	/**
	 * Render stock info.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product Product object.
	 * @return void
	 */
	protected function render_stock_info( $product ) {
		if ( ! $product->is_in_stock() ) {
			$status_class = 'out-of-stock';
			$status_text = __( 'Out of Stock', 'magical-products-display' );
			$icon = 'eicon-close-circle';
		} elseif ( $product->is_on_backorder() ) {
			$status_class = 'on-backorder';
			$status_text = __( 'Available on Backorder', 'magical-products-display' );
			$icon = 'eicon-clock-o';
		} else {
			$status_class = 'in-stock';
			$stock_qty = $product->get_stock_quantity();
			if ( $stock_qty ) {
				/* translators: %d: stock quantity */
				$status_text = sprintf( __( '%d in stock', 'magical-products-display' ), $stock_qty );
			} else {
				$status_text = __( 'In Stock', 'magical-products-display' );
			}
			$icon = 'eicon-check-circle';
		}
		?>
		<div class="mpd-stock-info mpd-stock-<?php echo esc_attr( $status_class ); ?>">
			<i class="<?php echo esc_attr( $icon ); ?>"></i>
			<span><?php echo esc_html( $status_text ); ?></span>
		</div>
		<?php
	}

	/**
	 * Add icon to button HTML.
	 *
	 * @since 2.0.0
	 *
	 * @param string $button Button HTML.
	 * @param array  $icon Icon settings.
	 * @param string $position Icon position (before|after).
	 * @return string Modified button HTML.
	 */
	protected function add_icon_to_button( $button, $icon, $position ) {
		ob_start();
		\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
		$icon_html = '<span class="mpd-btn-icon">' . ob_get_clean() . '</span>';

		// Insert icon into button.
		if ( 'before' === $position ) {
			$button = preg_replace( '/(<a[^>]*>)/', '$1' . $icon_html, $button );
		} else {
			$button = preg_replace( '/(<\/a>)/', $icon_html . '$1', $button );
		}

		return $button;
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'mpd-add-to-cart' );
	}

	/**
	 * Hide quantity input.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args Quantity input args.
	 * @return array Modified args.
	 */
	public function hide_quantity_input( $args ) {
		$args['min_value'] = 1;
		$args['max_value'] = 1;
		$args['input_value'] = 1;

		add_filter( 'woocommerce_quantity_input_classes', function( $classes ) {
			$classes[] = 'hidden';
			return $classes;
		} );

		return $args;
	}
}
