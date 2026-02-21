<?php
/**
 * Upsells Widget
 *
 * Displays upsell products on single product pages.
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
use Elementor\Group_Control_Background;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Upsells
 *
 * @since 2.0.0
 */
class Upsells extends Widget_Base {

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
		return 'mpd-upsells';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Upsells', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-upsell';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'upsells', 'upsell', 'woocommerce', 'single', 'recommendation' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-single-product', 'swiper' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'mg-swiper', 'mpd-global-widgets' );
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
				'label' => __( 'Upsells', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Section Heading', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'You may also like...', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'products_count',
			array(
				'label'   => __( 'Products Count', 'magical-products-display' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 12,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'   => __( 'Columns', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'default' => '4',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-grid' => '--mpd-columns: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'rand'       => __( 'Random', 'magical-products-display' ),
					'date'       => __( 'Date', 'magical-products-display' ),
					'title'      => __( 'Title', 'magical-products-display' ),
					'price'      => __( 'Price', 'magical-products-display' ),
					'menu_order' => __( 'Menu Order', 'magical-products-display' ),
				),
				'default' => 'rand',
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'ASC'  => __( 'Ascending', 'magical-products-display' ),
					'DESC' => __( 'Descending', 'magical-products-display' ),
				),
				'default' => 'DESC',
			)
		);

		$this->end_controls_section();

		// Layout Section.
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'        => __( 'Show Image', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => __( 'Show Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_price',
			array(
				'label'        => __( 'Show Price', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => __( 'Show Rating', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_add_to_cart',
			array(
				'label'        => __( 'Show Add to Cart', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Carousel Mode & Custom Layout', 'magical-products-display' ) );
		}
			$this->add_control(
				'layout_mode',
				array(
					'label'   => __( 'Layout Mode', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'grid'     => __( 'Grid', 'magical-products-display' ),
						'carousel' => __( 'Carousel', 'magical-products-display' ),
						'list'     => __( 'List', 'magical-products-display' ),
					),
					'default' => 'grid',
				)
			);

			// Carousel settings.
			$this->add_control(
				'carousel_heading',
				array(
					'label'     => __( 'Carousel Settings', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						'layout_mode' => 'carousel',
					),
				)
			);

			$this->add_control(
				'autoplay',
				array(
					'label'        => __( 'Autoplay', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'condition'    => array(
						'layout_mode' => 'carousel',
					),
				)
			);

			$this->add_control(
				'autoplay_speed',
				array(
					'label'     => __( 'Autoplay Speed (ms)', 'magical-products-display' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 3000,
					'min'       => 1000,
					'max'       => 10000,
					'condition' => array(
						'layout_mode' => 'carousel',
						'autoplay'    => 'yes',
					),
				)
			);

			$this->add_control(
				'loop',
				array(
					'label'        => __( 'Infinite Loop', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'condition'    => array(
						'layout_mode' => 'carousel',
					),
				)
			);

			$this->add_control(
				'show_arrows',
				array(
					'label'        => __( 'Show Arrows', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'condition'    => array(
						'layout_mode' => 'carousel',
					),
				)
			);

			$this->add_control(
				'show_dots',
				array(
					'label'        => __( 'Show Dots', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'condition'    => array(
						'layout_mode' => 'carousel',
					),
				)
			);

			// List layout settings.
			$this->add_control(
				'list_heading',
				array(
					'label'     => __( 'List Layout Settings', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						'layout_mode' => 'list',
					),
				)
			);

			$this->add_control(
				'image_position',
				array(
					'label'     => __( 'Image Position', 'magical-products-display' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'left'  => __( 'Left', 'magical-products-display' ),
						'right' => __( 'Right', 'magical-products-display' ),
					),
					'default'   => 'left',
					'condition' => array(
						'layout_mode' => 'list',
					),
				)
			);

			$this->add_responsive_control(
				'image_width',
				array(
					'label'      => __( 'Image Width', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min' => 50,
							'max' => 300,
						),
						'%' => array(
							'min' => 10,
							'max' => 50,
						),
					),
					'default'    => array(
						'unit' => 'px',
						'size' => 100,
					),
					'condition'  => array(
						'layout_mode' => 'list',
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-upsell-list .mpd-upsell-image' => 'width: {{SIZE}}{{UNIT}};',
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
		// Heading Style.
		$this->start_controls_section(
			'section_style_heading',
			array(
				'label' => __( 'Section Heading', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-upsells-heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .mpd-upsells-heading',
			)
		);

		$this->add_responsive_control(
			'heading_align',
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
					'{{WRAPPER}} .mpd-upsells-heading' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'heading_spacing',
			array(
				'label'      => __( 'Bottom Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-upsells-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Product Card Style.
		$this->start_controls_section(
			'section_style_product',
			array(
				'label' => __( 'Product Card', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'product_bg',
				'label'    => __( 'Background', 'magical-products-display' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .mpd-upsell-item, {{WRAPPER}} ul.products li.product',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'product_border',
				'selector' => '{{WRAPPER}} .mpd-product-item, {{WRAPPER}} .mpd-upsell-item, {{WRAPPER}} ul.products li.product',
			)
		);

		$this->add_responsive_control(
			'product_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-product-item, {{WRAPPER}} .mpd-upsell-item, {{WRAPPER}} ul.products li.product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'product_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-product-item, {{WRAPPER}} .mpd-upsell-item, {{WRAPPER}} ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'product_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-product-item, {{WRAPPER}} .mpd-upsell-item, {{WRAPPER}} ul.products li.product',
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'      => __( 'Column Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-grid, {{WRAPPER}} .mpd-upsells-grid, {{WRAPPER}} ul.products' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Product Title Style.
		$this->start_controls_section(
			'section_style_title',
			array(
				'label'     => __( 'Product Title', 'magical-products-display' ),
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
					'{{WRAPPER}} .mpd-product-item .mpd-product-title, {{WRAPPER}} .mpd-product-item .mpd-product-title a, {{WRAPPER}} .mpd-upsell-item .woocommerce-loop-product__title, {{WRAPPER}} ul.products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-product-item .mpd-product-title, {{WRAPPER}} .mpd-upsell-item .woocommerce-loop-product__title, {{WRAPPER}} ul.products li.product .woocommerce-loop-product__title',
			)
		);

		$this->end_controls_section();

		// Price Style.
		$this->start_controls_section(
			'section_style_price',
			array(
				'label'     => __( 'Price', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_price' => 'yes',
				),
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-item .mpd-product-price, {{WRAPPER}} .mpd-upsell-item .price, {{WRAPPER}} ul.products li.product .price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sale_price_color',
			array(
				'label'     => __( 'Sale Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-item .mpd-product-price ins, {{WRAPPER}} .mpd-upsell-item .price ins, {{WRAPPER}} ul.products li.product .price ins' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .mpd-product-item .mpd-product-price, {{WRAPPER}} .mpd-upsell-item .price, {{WRAPPER}} ul.products li.product .price',
			)
		);

		$this->end_controls_section();

		// Button Style.
		$this->start_controls_section(
			'section_style_button',
			array(
				'label'     => __( 'Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_add_to_cart' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'button_style_tabs' );

		// Normal Tab.
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
					'{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-upsell-item .button, {{WRAPPER}} .mpd-upsell-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'label'    => __( 'Background', 'magical-products-display' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-upsell-item .button, {{WRAPPER}} .mpd-upsell-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button',
			)
		);

		$this->end_controls_tab();

		// Hover Tab.
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
					'{{WRAPPER}} .mpd-product-item .mpd-product-button .button:hover, {{WRAPPER}} .mpd-upsell-item .button:hover, {{WRAPPER}} .mpd-upsell-item .add_to_cart_button:hover, {{WRAPPER}} ul.products li.product .button:hover, {{WRAPPER}} ul.products li.product .add_to_cart_button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background_hover',
				'label'    => __( 'Background', 'magical-products-display' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .mpd-product-item .mpd-product-button .button:hover, {{WRAPPER}} .mpd-upsell-item .button:hover, {{WRAPPER}} .mpd-upsell-item .add_to_cart_button:hover, {{WRAPPER}} ul.products li.product .button:hover, {{WRAPPER}} ul.products li.product .add_to_cart_button:hover',
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-item .mpd-product-button .button:hover, {{WRAPPER}} .mpd-upsell-item .button:hover, {{WRAPPER}} .mpd-upsell-item .add_to_cart_button:hover, {{WRAPPER}} ul.products li.product .button:hover, {{WRAPPER}} ul.products li.product .add_to_cart_button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-upsell-item .button, {{WRAPPER}} .mpd-upsell-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-upsell-item .button, {{WRAPPER}} .mpd-upsell-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-upsell-item .button, {{WRAPPER}} .mpd-upsell-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-upsell-item .button, {{WRAPPER}} .mpd-upsell-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-upsell-item .button, {{WRAPPER}} .mpd-upsell-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button',
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

		// Editor mode: Show demo preview for design
		if ( $this->is_editor_mode() ) {
			$this->render_editor_preview( $settings );
			return;
		}

		if ( ! $product ) {
			return;
		}

		$upsell_ids = $product->get_upsell_ids();

		if ( empty( $upsell_ids ) ) {
			return;
		}

		// Limit products.
		$limit       = absint( $settings['products_count'] );
		$upsell_ids  = array_slice( $upsell_ids, 0, $limit );

		// Order products.
		$args = array(
			'post_type'      => 'product',
			'post__in'       => $upsell_ids,
			'posts_per_page' => $limit,
			'orderby'        => $settings['orderby'],
			'order'          => $settings['order'],
			'fields'         => 'ids',
		);

		$query       = new \WP_Query( $args );
		$upsell_ids  = $query->posts;

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-upsells-products' );

		// Pro: Layout mode.
		$layout_mode = 'grid';
		if ( $this->is_pro() && isset( $settings['layout_mode'] ) ) {
			$layout_mode = $settings['layout_mode'];
			$this->add_render_attribute( 'wrapper', 'class', 'mpd-upsells-' . $layout_mode );
		}

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( ! empty( $settings['heading'] ) ) : ?>
				<h2 class="mpd-upsells-heading"><?php echo esc_html( $settings['heading'] ); ?></h2>
			<?php endif; ?>

			<?php
			switch ( $layout_mode ) {
				case 'carousel':
					if ( $this->is_pro() ) {
						$this->render_carousel( $upsell_ids, $settings );
					} else {
						$this->render_grid( $upsell_ids, $settings );
					}
					break;

				case 'list':
					if ( $this->is_pro() ) {
						$this->render_list( $upsell_ids, $settings );
					} else {
						$this->render_grid( $upsell_ids, $settings );
					}
					break;

				default:
					$this->render_grid( $upsell_ids, $settings );
					break;
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render editor preview with demo products.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_editor_preview( $settings ) {
		$columns = absint( $settings['columns'] ?? 4 );
		$count   = absint( $settings['products_count'] ?? 4 );

		// Pro: Layout mode.
		$layout_mode = 'grid';
		if ( $this->is_pro() && isset( $settings['layout_mode'] ) ) {
			$layout_mode = $settings['layout_mode'];
		}

		// Demo product data.
		$demo_products = array(
			array(
				'name'     => __( 'Premium Wireless Headphones', 'magical-products-display' ),
				'price'    => '$149.99',
				'sale'     => '$129.99',
				'rating'   => 4.5,
				'image'    => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop',
			),
			array(
				'name'     => __( 'Smart Watch Pro', 'magical-products-display' ),
				'price'    => '$299.99',
				'sale'     => '',
				'rating'   => 5,
				'image'    => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=300&h=300&fit=crop',
			),
			array(
				'name'     => __( 'Portable Bluetooth Speaker', 'magical-products-display' ),
				'price'    => '$79.99',
				'sale'     => '$59.99',
				'rating'   => 4,
				'image'    => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=300&h=300&fit=crop',
			),
			array(
				'name'     => __( 'Wireless Charging Pad', 'magical-products-display' ),
				'price'    => '$39.99',
				'sale'     => '',
				'rating'   => 4.5,
				'image'    => 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=300&h=300&fit=crop',
			),
			array(
				'name'     => __( 'USB-C Hub Adapter', 'magical-products-display' ),
				'price'    => '$69.99',
				'sale'     => '$54.99',
				'rating'   => 4,
				'image'    => 'https://images.unsplash.com/photo-1625723044792-44de16ccb4e9?w=300&h=300&fit=crop',
			),
			array(
				'name'     => __( 'Mechanical Keyboard', 'magical-products-display' ),
				'price'    => '$129.99',
				'sale'     => '',
				'rating'   => 5,
				'image'    => 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?w=300&h=300&fit=crop',
			),
		);

		// Limit demo products to count.
		$demo_products = array_slice( $demo_products, 0, $count );

		?>
		<div class="mpd-upsells-products mpd-upsells-<?php echo esc_attr( $layout_mode ); ?> mpd-editor-preview">
			<?php if ( ! empty( $settings['heading'] ) ) : ?>
				<h2 class="mpd-upsells-heading"><?php echo esc_html( $settings['heading'] ); ?></h2>
			<?php endif; ?>

			<?php if ( 'list' === $layout_mode && $this->is_pro() ) : ?>
				<?php $this->render_editor_list_preview( $demo_products, $settings ); ?>
			<?php elseif ( 'carousel' === $layout_mode && $this->is_pro() ) : ?>
				<?php $this->render_editor_carousel_preview( $demo_products, $settings ); ?>
			<?php else : ?>
				<?php $this->render_editor_grid_preview( $demo_products, $settings, $columns ); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render editor grid preview.
	 *
	 * @since 2.0.0
	 *
	 * @param array $products Demo products.
	 * @param array $settings Widget settings.
	 * @param int   $columns  Number of columns.
	 * @return void
	 */
	private function render_editor_grid_preview( $products, $settings, $columns ) {
		?>
		<ul class="products columns-<?php echo esc_attr( $columns ); ?>">
			<?php foreach ( $products as $product ) : ?>
				<li class="product type-product">
					<?php if ( 'yes' === ( $settings['show_image'] ?? 'yes' ) ) : ?>
						<a href="#" class="woocommerce-LoopProduct-link">
							<img src="<?php echo esc_url( $product['image'] ); ?>" alt="<?php echo esc_attr( $product['name'] ); ?>" class="attachment-woocommerce_thumbnail">
						</a>
					<?php endif; ?>

					<?php if ( 'yes' === ( $settings['show_title'] ?? 'yes' ) ) : ?>
						<h2 class="woocommerce-loop-product__title">
							<a href="#"><?php echo esc_html( $product['name'] ); ?></a>
						</h2>
					<?php endif; ?>

					<?php if ( 'yes' === ( $settings['show_rating'] ?? 'yes' ) && $product['rating'] ) : ?>
						<div class="star-rating" role="img" aria-label="<?php printf( esc_attr__( 'Rated %s out of 5', 'magical-products-display' ), $product['rating'] ); ?>">
							<span style="width:<?php echo esc_attr( ( $product['rating'] / 5 ) * 100 ); ?>%"></span>
						</div>
					<?php endif; ?>

					<?php if ( 'yes' === ( $settings['show_price'] ?? 'yes' ) ) : ?>
						<span class="price">
							<?php if ( ! empty( $product['sale'] ) ) : ?>
								<del><span class="woocommerce-Price-amount"><?php echo esc_html( $product['price'] ); ?></span></del>
								<ins><span class="woocommerce-Price-amount"><?php echo esc_html( $product['sale'] ); ?></span></ins>
							<?php else : ?>
								<span class="woocommerce-Price-amount"><?php echo esc_html( $product['price'] ); ?></span>
							<?php endif; ?>
						</span>
					<?php endif; ?>

					<?php if ( 'yes' === ( $settings['show_add_to_cart'] ?? 'yes' ) ) : ?>
						<a href="#" class="button add_to_cart_button"><?php esc_html_e( 'Add to cart', 'magical-products-display' ); ?></a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * Render editor list preview.
	 *
	 * @since 2.0.0
	 *
	 * @param array $products Demo products.
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_editor_list_preview( $products, $settings ) {
		$image_position = $settings['image_position'] ?? 'left';
		$image_width    = isset( $settings['image_width']['size'] ) ? absint( $settings['image_width']['size'] ) : 120;
		?>
		<div class="mpd-upsell-list mpd-list-image-<?php echo esc_attr( $image_position ); ?>">
			<?php foreach ( $products as $product ) : ?>
				<div class="mpd-upsell-item">
					<?php if ( 'yes' === ( $settings['show_image'] ?? 'yes' ) ) : ?>
						<div class="mpd-upsell-image" style="width: <?php echo esc_attr( $image_width ); ?>px; flex-shrink: 0;">
							<a href="#">
								<img src="<?php echo esc_url( $product['image'] ); ?>" alt="<?php echo esc_attr( $product['name'] ); ?>">
							</a>
						</div>
					<?php endif; ?>

					<div class="mpd-upsell-content">
						<?php if ( 'yes' === ( $settings['show_title'] ?? 'yes' ) ) : ?>
							<h3 class="mpd-upsell-title">
								<a href="#"><?php echo esc_html( $product['name'] ); ?></a>
							</h3>
						<?php endif; ?>

						<?php if ( 'yes' === ( $settings['show_rating'] ?? 'yes' ) && $product['rating'] ) : ?>
							<div class="star-rating" role="img">
								<span style="width:<?php echo esc_attr( ( $product['rating'] / 5 ) * 100 ); ?>%"></span>
							</div>
						<?php endif; ?>

						<?php if ( 'yes' === ( $settings['show_price'] ?? 'yes' ) ) : ?>
							<span class="price">
								<?php if ( ! empty( $product['sale'] ) ) : ?>
									<del><?php echo esc_html( $product['price'] ); ?></del>
									<ins><?php echo esc_html( $product['sale'] ); ?></ins>
								<?php else : ?>
									<?php echo esc_html( $product['price'] ); ?>
								<?php endif; ?>
							</span>
						<?php endif; ?>

						<?php if ( 'yes' === ( $settings['show_add_to_cart'] ?? 'yes' ) ) : ?>
							<a href="#" class="button add_to_cart_button"><?php esc_html_e( 'Add to cart', 'magical-products-display' ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Render editor carousel preview.
	 *
	 * @since 2.0.0
	 *
	 * @param array $products Demo products.
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_editor_carousel_preview( $products, $settings ) {
		$columns        = absint( $settings['columns'] ?? 4 );
		$autoplay       = 'yes' === ( $settings['autoplay'] ?? 'yes' );
		$autoplay_speed = absint( $settings['autoplay_speed'] ?? 3000 );
		$loop           = 'yes' === ( $settings['loop'] ?? 'yes' );
		$show_arrows    = 'yes' === ( $settings['show_arrows'] ?? 'yes' );
		$show_dots      = 'yes' === ( $settings['show_dots'] ?? 'yes' );
		$unique_id      = 'mpd-upsells-swiper-' . uniqid();
		?>
		<div class="mpd-upsells-carousel swiper" id="<?php echo esc_attr( $unique_id ); ?>">
			<div class="swiper-wrapper">
				<?php foreach ( $products as $index => $product ) : ?>
					<div class="swiper-slide">
						<div class="mpd-upsell-item">
							<?php if ( 'yes' === ( $settings['show_image'] ?? 'yes' ) ) : ?>
								<a href="#" class="mpd-upsell-image">
									<img src="<?php echo esc_url( $product['image'] ); ?>" alt="<?php echo esc_attr( $product['name'] ); ?>">
								</a>
							<?php endif; ?>

							<?php if ( 'yes' === ( $settings['show_title'] ?? 'yes' ) ) : ?>
								<h3 class="mpd-upsell-title">
									<a href="#"><?php echo esc_html( $product['name'] ); ?></a>
								</h3>
							<?php endif; ?>

							<?php if ( 'yes' === ( $settings['show_rating'] ?? 'yes' ) && $product['rating'] ) : ?>
								<div class="star-rating" role="img">
									<span style="width:<?php echo esc_attr( ( $product['rating'] / 5 ) * 100 ); ?>%"></span>
								</div>
							<?php endif; ?>

							<?php if ( 'yes' === ( $settings['show_price'] ?? 'yes' ) ) : ?>
								<span class="price">
									<?php if ( ! empty( $product['sale'] ) ) : ?>
										<del><?php echo esc_html( $product['price'] ); ?></del>
										<ins><?php echo esc_html( $product['sale'] ); ?></ins>
									<?php else : ?>
										<?php echo esc_html( $product['price'] ); ?>
									<?php endif; ?>
								</span>
							<?php endif; ?>

							<?php if ( 'yes' === ( $settings['show_add_to_cart'] ?? 'yes' ) ) : ?>
								<a href="#" class="button add_to_cart_button"><?php esc_html_e( 'Add to cart', 'magical-products-display' ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $show_arrows ) : ?>
				<div class="swiper-button-prev mpd-carousel-prev">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</div>
				<div class="swiper-button-next mpd-carousel-next">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 6 15 12 9 18"></polyline></svg>
				</div>
			<?php endif; ?>

			<?php if ( $show_dots ) : ?>
				<div class="swiper-pagination mpd-carousel-dots"></div>
			<?php endif; ?>
		</div>

		<script>
		(function() {
			var initSwiper = function() {
				if (typeof Swiper === 'undefined') {
					setTimeout(initSwiper, 100);
					return;
				}
				
				var swiperEl = document.getElementById('<?php echo esc_js( $unique_id ); ?>');
				if (!swiperEl) return;
				
				new Swiper(swiperEl, {
					slidesPerView: <?php echo esc_js( $columns ); ?>,
					spaceBetween: 20,
					loop: <?php echo esc_attr( $loop ? 'true' : 'false' ); ?>,
					<?php if ( $autoplay ) : ?>
					autoplay: {
						delay: <?php echo esc_js( $autoplay_speed ); ?>,
						disableOnInteraction: false,
					},
					<?php endif; ?>
					<?php if ( $show_arrows ) : ?>
					navigation: {
						nextEl: '#<?php echo esc_js( $unique_id ); ?> .swiper-button-next',
						prevEl: '#<?php echo esc_js( $unique_id ); ?> .swiper-button-prev',
					},
					<?php endif; ?>
					<?php if ( $show_dots ) : ?>
					pagination: {
						el: '#<?php echo esc_js( $unique_id ); ?> .swiper-pagination',
						clickable: true,
					},
					<?php endif; ?>
					breakpoints: {
						320: { slidesPerView: 1 },
						480: { slidesPerView: 2 },
						768: { slidesPerView: Math.min(3, <?php echo esc_js( $columns ); ?>) },
						1024: { slidesPerView: <?php echo esc_js( $columns ); ?> },
					}
				});
			};
			
			initSwiper();
		})();
		</script>
		<?php
	}

	/**
	 * Render grid layout.
	 *
	 * @since 2.0.0
	 *
	 * @param array $product_ids Product IDs.
	 * @param array $settings    Widget settings.
	 * @return void
	 */
	private function render_grid( $product_ids, $settings ) {
		?>
		<div class="mpd-products-grid mpd-upsells-grid">
			<?php
			foreach ( $product_ids as $product_id ) {
				$upsell_product = wc_get_product( $product_id );
				if ( ! $upsell_product ) {
					continue;
				}
				?>
				<div class="mpd-product-item mpd-upsell-item">
					<?php if ( 'yes' === ( $settings['show_image'] ?? 'yes' ) ) : ?>
						<div class="mpd-product-image-wrapper">
							<?php if ( $upsell_product->is_on_sale() ) : ?>
								<span class="mpd-sale-badge"><?php esc_html_e( 'Sale!', 'magical-products-display' ); ?></span>
							<?php endif; ?>
							<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="mpd-product-image">
								<?php echo wp_kses_post( $upsell_product->get_image( 'woocommerce_thumbnail' ) ); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="mpd-product-content">
						<?php if ( 'yes' === ( $settings['show_title'] ?? 'yes' ) ) : ?>
							<h2 class="mpd-product-title">
								<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
									<?php echo esc_html( $upsell_product->get_name() ); ?>
								</a>
							</h2>
						<?php endif; ?>

						<?php if ( 'yes' === ( $settings['show_rating'] ?? 'yes' ) && $upsell_product->get_average_rating() ) : ?>
							<div class="mpd-product-rating">
								<?php echo wc_get_rating_html( $upsell_product->get_average_rating(), $upsell_product->get_rating_count() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php endif; ?>

						<?php if ( 'yes' === ( $settings['show_price'] ?? 'yes' ) ) : ?>
							<div class="mpd-product-price">
								<?php echo wp_kses_post( $upsell_product->get_price_html() ); ?>
							</div>
						<?php endif; ?>

						<?php if ( 'yes' === ( $settings['show_add_to_cart'] ?? 'yes' ) ) : ?>
							<div class="mpd-product-button">
								<?php
								echo apply_filters(
									'woocommerce_loop_add_to_cart_link',
									sprintf(
										'<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
										esc_url( $upsell_product->add_to_cart_url() ),
										esc_attr( implode( ' ', array_filter( array(
											'button',
											'mpd-add-to-cart',
											$upsell_product->is_purchasable() && $upsell_product->is_in_stock() ? 'add_to_cart_button' : '',
											$upsell_product->supports( 'ajax_add_to_cart' ) && $upsell_product->is_purchasable() && $upsell_product->is_in_stock() ? 'ajax_add_to_cart' : '',
										) ) ) ),
										wc_implode_html_attributes( array(
											'data-product_id'  => $upsell_product->get_id(),
											'data-product_sku' => $upsell_product->get_sku(),
											'aria-label'       => $upsell_product->add_to_cart_description(),
											'rel'              => 'nofollow',
										) ),
										esc_html( $upsell_product->add_to_cart_text() )
									),
									$upsell_product
								);
								?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render carousel layout (Pro).
	 *
	 * @since 2.0.0
	 *
	 * @param array $product_ids Product IDs.
	 * @param array $settings    Widget settings.
	 * @return void
	 */
	private function render_carousel( $product_ids, $settings ) {
		// Get responsive column values.
		$columns_desktop = absint( $settings['columns'] ?? 4 );
		$columns_tablet  = absint( $settings['columns_tablet'] ?? min( 3, $columns_desktop ) );
		$columns_mobile  = absint( $settings['columns_mobile'] ?? min( 2, $columns_tablet ) );

		$carousel_data = array(
			'autoplay'       => 'yes' === ( $settings['autoplay'] ?? 'yes' ),
			'autoplaySpeed'  => absint( $settings['autoplay_speed'] ?? 3000 ),
			'loop'           => 'yes' === ( $settings['loop'] ?? 'yes' ),
			'columns'        => $columns_desktop,
			'columnsTablet'  => $columns_tablet,
			'columnsMobile'  => $columns_mobile,
		);
		?>
		<div class="mpd-upsells-carousel swiper" data-carousel="<?php echo esc_attr( wp_json_encode( $carousel_data ) ); ?>">
			<div class="swiper-wrapper">
				<?php
				foreach ( $product_ids as $product_id ) {
					$upsell_product = wc_get_product( $product_id );
					if ( ! $upsell_product ) {
						continue;
					}
					?>
					<div class="swiper-slide">
						<div class="mpd-upsell-item">
							<?php if ( 'yes' === $settings['show_image'] ) : ?>
								<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="mpd-upsell-image">
									<?php echo wp_kses_post( $upsell_product->get_image( 'woocommerce_thumbnail' ) ); ?>
								</a>
							<?php endif; ?>

							<?php if ( 'yes' === $settings['show_title'] ) : ?>
								<h3 class="mpd-upsell-title">
									<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
										<?php echo esc_html( $upsell_product->get_name() ); ?>
									</a>
								</h3>
							<?php endif; ?>

							<?php if ( 'yes' === $settings['show_rating'] && $upsell_product->get_average_rating() ) : ?>
								<?php echo wc_get_rating_html( $upsell_product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php endif; ?>

							<?php if ( 'yes' === $settings['show_price'] ) : ?>
								<span class="price"><?php echo wp_kses_post( $upsell_product->get_price_html() ); ?></span>
							<?php endif; ?>

							<?php if ( 'yes' === $settings['show_add_to_cart'] ) : ?>
								<?php woocommerce_template_loop_add_to_cart( array( 'quantity' => 1 ) ); ?>
							<?php endif; ?>
						</div>
					</div>
					<?php
				}
				?>
			</div>

			<?php if ( 'yes' === ( $settings['show_arrows'] ?? 'yes' ) ) : ?>
				<div class="swiper-button-prev mpd-carousel-prev">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</div>
				<div class="swiper-button-next mpd-carousel-next">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 6 15 12 9 18"></polyline></svg>
				</div>
			<?php endif; ?>

			<?php if ( 'yes' === ( $settings['show_dots'] ?? 'yes' ) ) : ?>
				<div class="swiper-pagination mpd-carousel-dots"></div>
			<?php endif; ?>
		</div>

		<script>
		(function() {
			var initUpsellsSwiper = function() {
				if (typeof Swiper === 'undefined') {
					setTimeout(initUpsellsSwiper, 100);
					return;
				}
				
				document.querySelectorAll('.mpd-upsells-carousel.swiper:not(.swiper-initialized)').forEach(function(swiperEl) {
					var carouselData = swiperEl.dataset.carousel ? JSON.parse(swiperEl.dataset.carousel) : {};
					var columns = carouselData.columns || 4;
					var columnsTablet = carouselData.columnsTablet || Math.min(3, columns);
					var columnsMobile = carouselData.columnsMobile || Math.min(2, columnsTablet);
					
					new Swiper(swiperEl, {
						slidesPerView: columns,
						spaceBetween: 20,
						loop: carouselData.loop !== false,
						autoplay: carouselData.autoplay ? {
							delay: carouselData.autoplaySpeed || 3000,
							disableOnInteraction: false,
						} : false,
						navigation: {
							nextEl: swiperEl.querySelector('.swiper-button-next'),
							prevEl: swiperEl.querySelector('.swiper-button-prev'),
						},
						pagination: {
							el: swiperEl.querySelector('.swiper-pagination'),
							clickable: true,
						},
						breakpoints: {
							0: { slidesPerView: columnsMobile, spaceBetween: 15 },
							768: { slidesPerView: columnsTablet, spaceBetween: 20 },
							1025: { slidesPerView: columns, spaceBetween: 20 },
						}
					});
				});
			};
			
			// Initialize on DOM ready
			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', initUpsellsSwiper);
			} else {
				initUpsellsSwiper();
			}
			
			// Also try after a short delay for Elementor preview
			setTimeout(initUpsellsSwiper, 500);
		})();
		</script>
		<?php
	}

	/**
	 * Render list layout (Pro).
	 *
	 * @since 2.0.0
	 *
	 * @param array $product_ids Product IDs.
	 * @param array $settings    Widget settings.
	 * @return void
	 */
	private function render_list( $product_ids, $settings ) {
		$image_position = $settings['image_position'] ?? 'left';
		$image_width    = isset( $settings['image_width']['size'] ) ? absint( $settings['image_width']['size'] ) : 120;
		?>
		<div class="mpd-upsell-list mpd-list-image-<?php echo esc_attr( $image_position ); ?>">
			<?php
			foreach ( $product_ids as $product_id ) {
				$upsell_product = wc_get_product( $product_id );
				if ( ! $upsell_product ) {
					continue;
				}
				?>
				<div class="mpd-upsell-item">
					<?php if ( 'yes' === $settings['show_image'] ) : ?>
						<div class="mpd-upsell-image" style="width: <?php echo esc_attr( $image_width ); ?>px; flex-shrink: 0;">
							<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
								<?php echo wp_kses_post( $upsell_product->get_image( 'woocommerce_thumbnail' ) ); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="mpd-upsell-content">
						<?php if ( 'yes' === $settings['show_title'] ) : ?>
							<h3 class="mpd-upsell-title">
								<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
									<?php echo esc_html( $upsell_product->get_name() ); ?>
								</a>
							</h3>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['show_rating'] && $upsell_product->get_average_rating() ) : ?>
							<?php echo wc_get_rating_html( $upsell_product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['show_price'] ) : ?>
							<span class="price"><?php echo wp_kses_post( $upsell_product->get_price_html() ); ?></span>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['show_add_to_cart'] ) : ?>
							<?php woocommerce_template_loop_add_to_cart( array( 'quantity' => 1 ) ); ?>
						<?php endif; ?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
}
