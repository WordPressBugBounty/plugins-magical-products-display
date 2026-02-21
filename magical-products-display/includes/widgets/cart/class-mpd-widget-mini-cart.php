<?php
/**
 * Mini Cart Widget
 *
 * Displays a compact mini cart with slide-out panel option.
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
use Elementor\Icons_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Mini_Cart
 *
 * @since 2.0.0
 */
class Mini_Cart extends Widget_Base {

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
	protected $widget_icon = 'eicon-cart-medium';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-mini-cart';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Mini Cart', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-cart-medium';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'mini cart', 'cart', 'basket', 'shopping', 'header', 'magical-products-display' );
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
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'mpd-mini-cart' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		// Icon Section.
		$this->start_controls_section(
			'section_icon',
			array(
				'label' => __( 'Cart Icon', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'icon_type',
			array(
				'label'   => __( 'Icon Type', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'icon'  => __( 'Icon', 'magical-products-display' ),
					'image' => __( 'Image', 'magical-products-display' ),
					'text'  => __( 'Text Only', 'magical-products-display' ),
				),
				'default' => 'icon',
			)
		);

		$this->add_control(
			'cart_icon',
			array(
				'label'     => __( 'Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'icon_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'cart_image',
			array(
				'label'     => __( 'Image', 'magical-products-display' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'icon_type' => 'image',
				),
			)
		);

		$this->add_control(
			'cart_text',
			array(
				'label'   => __( 'Cart Text', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Cart', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_counter',
			array(
				'label'        => __( 'Show Items Counter', 'magical-products-display' ),
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
			'icon_alignment',
			array(
				'label'   => __( 'Alignment', 'magical-products-display' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
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
				'default' => 'right',
			)
		);

		$this->end_controls_section();

		// Dropdown Section.
		$this->start_controls_section(
			'section_dropdown',
			array(
				'label' => __( 'Cart Dropdown', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_dropdown',
			array(
				'label'        => __( 'Show Dropdown on Hover', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'dropdown_position',
			array(
				'label'     => __( 'Dropdown Position', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'bottom-left'   => __( 'Bottom Left', 'magical-products-display' ),
					'bottom-center' => __( 'Bottom Center', 'magical-products-display' ),
					'bottom-right'  => __( 'Bottom Right', 'magical-products-display' ),
				),
				'default'   => 'bottom-right',
				'condition' => array(
					'show_dropdown' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'dropdown_width',
			array(
				'label'      => __( 'Dropdown Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 200,
						'max' => 500,
					),
				),
				'default'    => array(
					'size' => 300,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-mini-cart-dropdown' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_dropdown' => 'yes',
				),
			)
		);

		$this->add_control(
			'max_products',
			array(
				'label'     => __( 'Max Products to Show', 'magical-products-display' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 10,
				'default'   => 5,
				'condition' => array(
					'show_dropdown' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_view_cart_button',
			array(
				'label'        => __( 'Show View Cart Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_dropdown' => 'yes',
				),
			)
		);

		$this->add_control(
			'view_cart_text',
			array(
				'label'     => __( 'View Cart Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'View Cart', 'magical-products-display' ),
				'condition' => array(
					'show_dropdown'          => 'yes',
					'show_view_cart_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_checkout_button',
			array(
				'label'        => __( 'Show Checkout Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_dropdown' => 'yes',
				),
			)
		);

		$this->add_control(
			'checkout_text',
			array(
				'label'     => __( 'Checkout Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Checkout', 'magical-products-display' ),
				'condition' => array(
					'show_dropdown'         => 'yes',
					'show_checkout_button' => 'yes',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Slide-Out Panel & Floating Cart', 'magical-products-display' ) );
		}
			$this->add_control(
				'cart_style',
				array(
					'label'   => __( 'Cart Style', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'dropdown'  => __( 'Dropdown', 'magical-products-display' ),
						'slide_out' => __( 'Slide-Out Panel', 'magical-products-display' ),
					),
					'default' => 'dropdown',
				)
			);

			$this->add_control(
				'slide_direction',
				array(
					'label'     => __( 'Slide Direction', 'magical-products-display' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'left'  => __( 'From Left', 'magical-products-display' ),
						'right' => __( 'From Right', 'magical-products-display' ),
					),
					'default'   => 'right',
					'condition' => array(
						'cart_style' => 'slide_out',
					),
				)
			);

			$this->add_responsive_control(
				'slide_panel_width',
				array(
					'label'      => __( 'Panel Width', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min' => 200,
							'max' => 600,
						),
						'%'  => array(
							'min' => 20,
							'max' => 50,
						),
					),
					'default'    => array(
						'size' => 400,
						'unit' => 'px',
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-mini-cart-panel' => 'width: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'cart_style' => 'slide_out',
					),
				)
			);

			$this->add_control(
				'show_overlay',
				array(
					'label'        => __( 'Show Overlay', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'condition'    => array(
						'cart_style' => 'slide_out',
					),
				)
			);

			$this->add_control(
				'floating_cart',
				array(
					'label'        => __( 'Floating Cart Icon', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Show a fixed floating cart icon.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'floating_position',
				array(
					'label'     => __( 'Floating Position', 'magical-products-display' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'bottom-left'  => __( 'Bottom Left', 'magical-products-display' ),
						'bottom-right' => __( 'Bottom Right', 'magical-products-display' ),
					),
					'default'   => 'bottom-right',
					'condition' => array(
						'floating_cart' => 'yes',
					),
				)
			);

			$this->add_control(
				'ajax_update',
				array(
					'label'        => __( 'AJAX Update', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'description'  => __( 'Update cart without page reload.', 'magical-products-display' ),
				)
			);

		$this->end_controls_section();

		// Empty Cart Message.
		$this->start_controls_section(
			'section_empty_cart',
			array(
				'label' => __( 'Empty Cart', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'empty_cart_message',
			array(
				'label'   => __( 'Empty Cart Message', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Your cart is empty', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'hide_empty_counter',
			array(
				'label'        => __( 'Hide Counter When Empty', 'magical-products-display' ),
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
		// Icon Style.
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => __( 'Cart Icon', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-mini-cart-toggle' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-mini-cart-toggle svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Icon Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-mini-cart-toggle i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-mini-cart-toggle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_hover_color',
			array(
				'label'     => __( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-mini-cart-toggle:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-mini-cart-toggle:hover svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Counter Style.
		$this->start_controls_section(
			'section_counter_style',
			array(
				'label'     => __( 'Items Counter', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_counter' => 'yes',
				),
			)
		);

		$this->add_control(
			'counter_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-mini-cart-counter' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'counter_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-mini-cart-counter' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'counter_typography',
				'selector' => '{{WRAPPER}} .mpd-mini-cart-counter',
			)
		);

		$this->add_responsive_control(
			'counter_size',
			array(
				'label'      => __( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 15,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-mini-cart-counter' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'counter_position_x',
			array(
				'label'      => __( 'Position X', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => -20,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-mini-cart-counter' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'counter_position_y',
			array(
				'label'      => __( 'Position Y', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => -20,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-mini-cart-counter' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Subtotal Style.
		$this->start_controls_section(
			'section_subtotal_style',
			array(
				'label'     => __( 'Subtotal', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_subtotal' => 'yes',
				),
			)
		);

		$this->add_control(
			'subtotal_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-mini-cart-subtotal' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtotal_typography',
				'selector' => '{{WRAPPER}} .mpd-mini-cart-subtotal',
			)
		);

		$this->end_controls_section();

		// Dropdown Style.
		$this->start_controls_section(
			'section_dropdown_style',
			array(
				'label'     => __( 'Dropdown', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_dropdown' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'dropdown_background',
				'selector' => '{{WRAPPER}} .mpd-mini-cart-dropdown',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'dropdown_border',
				'selector' => '{{WRAPPER}} .mpd-mini-cart-dropdown',
			)
		);

		$this->add_responsive_control(
			'dropdown_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-mini-cart-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'dropdown_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-mini-cart-dropdown',
			)
		);

		$this->add_responsive_control(
			'dropdown_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-mini-cart-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Product Item Style.
		$this->start_controls_section(
			'section_product_style',
			array(
				'label' => __( 'Product Item', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_title_color',
			array(
				'label'     => __( 'Title Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-mini-cart-product-name' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-mini-cart-product-name a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_title_typography',
				'label'    => __( 'Title Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-mini-cart-product-name',
			)
		);

		$this->add_control(
			'product_price_color',
			array(
				'label'     => __( 'Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-mini-cart-product-price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_price_typography',
				'label'    => __( 'Price Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-mini-cart-product-price',
			)
		);

		$this->add_responsive_control(
			'product_image_size',
			array(
				'label'      => __( 'Image Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 30,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 50,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-mini-cart-product-image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'remove_button_color',
			array(
				'label'     => __( 'Remove Button Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-mini-cart-remove' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Buttons Style.
		$this->start_controls_section(
			'section_buttons_style',
			array(
				'label'     => __( 'Buttons', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_dropdown' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .mpd-mini-cart-buttons .button',
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_normal',
			array(
				'label' => __( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-mini-cart-buttons .button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background',
			array(
				'label'     => __( 'Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-mini-cart-buttons .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-mini-cart-buttons .button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_background',
			array(
				'label'     => __( 'Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-mini-cart-buttons .button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} .mpd-mini-cart-buttons .button',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-mini-cart-buttons .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-mini-cart-buttons .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_widget( $settings ) {
		// Check if WooCommerce is active.
		if ( ! $this->is_woocommerce_active() ) {
			return;
		}

		$this->render_mini_cart( $settings );
	}

	/**
	 * Render mini cart.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_mini_cart( $settings ) {
		$cart            = WC()->cart;
		$cart_count      = $cart ? $cart->get_cart_contents_count() : 0;
		$cart_subtotal   = $cart ? $cart->get_cart_subtotal() : wc_price( 0 );
		$cart_url        = wc_get_cart_url();
		$checkout_url    = wc_get_checkout_url();

		$show_counter    = 'yes' === $settings['show_counter'];
		$show_subtotal   = 'yes' === $settings['show_subtotal'];
		$show_dropdown   = 'yes' === $settings['show_dropdown'];
		$hide_empty      = 'yes' === $settings['hide_empty_counter'];

		// Pro features.
		$cart_style      = $this->is_pro() && isset( $settings['cart_style'] ) ? $settings['cart_style'] : 'dropdown';
		$floating_cart   = $this->is_pro() && isset( $settings['floating_cart'] ) && 'yes' === $settings['floating_cart'];
		$floating_pos    = $this->is_pro() && isset( $settings['floating_position'] ) ? $settings['floating_position'] : 'bottom-right';

		$wrapper_class = 'mpd-mini-cart';
		$wrapper_class .= ' mpd-mini-cart-align-' . $settings['icon_alignment'];
		if ( $floating_cart ) {
			$wrapper_class .= ' mpd-mini-cart-floating mpd-floating-' . $floating_pos;
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<a href="<?php echo esc_url( $cart_url ); ?>" class="mpd-mini-cart-toggle">
				<?php if ( 'text' !== $settings['icon_type'] ) : ?>
					<span class="mpd-mini-cart-icon">
						<?php if ( 'icon' === $settings['icon_type'] && ! empty( $settings['cart_icon']['value'] ) ) : ?>
							<?php Icons_Manager::render_icon( $settings['cart_icon'], array( 'aria-hidden' => 'true' ) ); ?>
						<?php elseif ( 'image' === $settings['icon_type'] && ! empty( $settings['cart_image']['url'] ) ) : ?>
							<img src="<?php echo esc_url( $settings['cart_image']['url'] ); ?>" alt="<?php esc_attr_e( 'Cart', 'magical-products-display' ); ?>" />
						<?php else : ?>
							<i class="fas fa-shopping-cart" aria-hidden="true"></i>
						<?php endif; ?>
					</span>
				<?php endif; ?>

				<?php if ( $show_counter && ( $cart_count > 0 || ! $hide_empty ) ) : ?>
					<span class="mpd-mini-cart-counter"><?php echo esc_html( $cart_count ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $settings['cart_text'] ) ) : ?>
					<span class="mpd-mini-cart-text"><?php echo esc_html( $settings['cart_text'] ); ?></span>
				<?php endif; ?>

				<?php if ( $show_subtotal ) : ?>
					<span class="mpd-mini-cart-subtotal"><?php echo wp_kses_post( $cart_subtotal ); ?></span>
				<?php endif; ?>
			</a>

			<?php if ( $show_dropdown && 'dropdown' === $cart_style ) : ?>
				<div class="mpd-mini-cart-dropdown mpd-dropdown-<?php echo esc_attr( $settings['dropdown_position'] ); ?>">
					<?php $this->render_cart_dropdown( $settings, $cart ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $this->is_pro() && 'slide_out' === $cart_style ) : ?>
				<?php
				$panel_class = 'mpd-mini-cart-panel';
				$panel_class .= ' mpd-panel-' . ( isset( $settings['slide_direction'] ) ? $settings['slide_direction'] : 'right' );
				?>
				<div class="<?php echo esc_attr( $panel_class ); ?>">
					<div class="mpd-panel-header">
						<h3><?php esc_html_e( 'Shopping Cart', 'magical-products-display' ); ?></h3>
						<button class="mpd-panel-close">&times;</button>
					</div>
					<div class="mpd-panel-content">
						<?php $this->render_cart_dropdown( $settings, $cart ); ?>
					</div>
				</div>
				<?php if ( isset( $settings['show_overlay'] ) && 'yes' === $settings['show_overlay'] ) : ?>
					<div class="mpd-mini-cart-overlay"></div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render cart dropdown content.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $settings Widget settings.
	 * @param object $cart     WooCommerce cart object.
	 * @return void
	 */
	private function render_cart_dropdown( $settings, $cart ) {
		$cart_items = $cart ? $cart->get_cart() : array();
		$max_items  = isset( $settings['max_products'] ) ? absint( $settings['max_products'] ) : 5;

		if ( empty( $cart_items ) ) {
			?>
			<div class="mpd-mini-cart-empty">
				<p><?php echo esc_html( $settings['empty_cart_message'] ); ?></p>
			</div>
			<?php
			return;
		}

		$count = 0;
		?>
		<div class="mpd-mini-cart-products">
			<?php foreach ( $cart_items as $cart_item_key => $cart_item ) : ?>
				<?php
				if ( ++$count > $max_items ) {
					break;
				}

				$product = $cart_item['data'];
				if ( ! $product || ! $product instanceof \WC_Product ) {
					continue;
				}

				$product_id = $cart_item['product_id'];
				$quantity   = $cart_item['quantity'];
				$price      = WC()->cart->get_product_price( $product );
				$thumbnail  = $product->get_image( 'woocommerce_thumbnail' );
				?>
				<div class="mpd-mini-cart-product">
					<div class="mpd-mini-cart-product-image">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<?php echo wp_kses_post( $thumbnail ); ?>
						</a>
					</div>
					<div class="mpd-mini-cart-product-details">
						<h4 class="mpd-mini-cart-product-name">
							<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
								<?php echo esc_html( $product->get_name() ); ?>
							</a>
						</h4>
						<span class="mpd-mini-cart-product-price">
							<?php echo wp_kses_post( $price ); ?> × <?php echo esc_html( $quantity ); ?>
						</span>
					</div>
					<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="mpd-mini-cart-remove" title="<?php esc_attr_e( 'Remove', 'magical-products-display' ); ?>">&times;</a>
				</div>
			<?php endforeach; ?>

			<?php if ( count( $cart_items ) > $max_items ) : ?>
				<div class="mpd-mini-cart-more">
					<?php
					printf(
						/* translators: %d: number of additional items */
						esc_html__( '+ %d more items', 'magical-products-display' ),
						count( $cart_items ) - $max_items
					);
					?>
				</div>
			<?php endif; ?>
		</div>

		<div class="mpd-mini-cart-subtotal-row">
			<strong><?php esc_html_e( 'Subtotal:', 'magical-products-display' ); ?></strong>
			<span><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></span>
		</div>

		<div class="mpd-mini-cart-buttons">
			<?php if ( isset( $settings['show_view_cart_button'] ) && 'yes' === $settings['show_view_cart_button'] ) : ?>
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="button view-cart">
					<?php echo esc_html( $settings['view_cart_text'] ); ?>
				</a>
			<?php endif; ?>

			<?php if ( isset( $settings['show_checkout_button'] ) && 'yes' === $settings['show_checkout_button'] ) : ?>
				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="button checkout">
					<?php echo esc_html( $settings['checkout_text'] ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}
}
