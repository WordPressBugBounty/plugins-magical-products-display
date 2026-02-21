<?php
/**
 * Related Products Widget
 *
 * Displays related products on single product pages.
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
 * Class Related_Products
 *
 * @since 2.0.0
 */
class Related_Products extends Widget_Base {

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
		return 'mpd-related-products';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Related Products', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'related', 'similar', 'woocommerce', 'single', 'recommendation' );
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
				'label' => __( 'Related Products', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'Section Heading', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Related Products', 'magical-products-display' ),
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
					'rand'  => __( 'Random', 'magical-products-display' ),
					'date'  => __( 'Date', 'magical-products-display' ),
					'title' => __( 'Title', 'magical-products-display' ),
					'price' => __( 'Price', 'magical-products-display' ),
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Carousel Mode & Custom Query', 'magical-products-display' ) );
		}
			$this->add_control(
				'layout_mode',
				array(
					'label'   => __( 'Layout Mode', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'grid'     => __( 'Grid', 'magical-products-display' ),
						'carousel' => __( 'Carousel', 'magical-products-display' ),
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

			// Custom query settings.
			$this->add_control(
				'custom_query_heading',
				array(
					'label'     => __( 'Custom Query', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'query_type',
				array(
					'label'   => __( 'Query Type', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'related'      => __( 'Related Products', 'magical-products-display' ),
						'same_cat'     => __( 'Same Category', 'magical-products-display' ),
						'same_tag'     => __( 'Same Tag', 'magical-products-display' ),
						'same_price'   => __( 'Similar Price', 'magical-products-display' ),
						'custom'       => __( 'Custom Selection', 'magical-products-display' ),
					),
					'default' => 'related',
				)
			);

			$this->add_control(
				'exclude_out_of_stock',
				array(
					'label'        => __( 'Exclude Out of Stock', 'magical-products-display' ),
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
					'{{WRAPPER}} .mpd-related-heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .mpd-related-heading',
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
					'{{WRAPPER}} .mpd-related-heading' => 'text-align: {{VALUE}};',
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
					'{{WRAPPER}} .mpd-related-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .mpd-product-item, {{WRAPPER}} .mpd-related-item, {{WRAPPER}} ul.products li.product',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'product_border',
				'selector' => '{{WRAPPER}} .mpd-product-item, {{WRAPPER}} .mpd-related-item, {{WRAPPER}} ul.products li.product',
			)
		);

		$this->add_responsive_control(
			'product_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-product-item, {{WRAPPER}} .mpd-related-item, {{WRAPPER}} ul.products li.product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-product-item, {{WRAPPER}} .mpd-related-item, {{WRAPPER}} ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'product_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-product-item, {{WRAPPER}} .mpd-related-item, {{WRAPPER}} ul.products li.product',
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
					'{{WRAPPER}} .mpd-products-grid, {{WRAPPER}} .mpd-related-grid, {{WRAPPER}} ul.products' => 'gap: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-product-item .mpd-product-title, {{WRAPPER}} .mpd-product-item .mpd-product-title a, {{WRAPPER}} .mpd-related-item .woocommerce-loop-product__title, {{WRAPPER}} ul.products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-product-item .mpd-product-title, {{WRAPPER}} .mpd-related-item .woocommerce-loop-product__title, {{WRAPPER}} ul.products li.product .woocommerce-loop-product__title',
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
					'{{WRAPPER}} .mpd-product-item .mpd-product-price, {{WRAPPER}} .mpd-related-item .price, {{WRAPPER}} ul.products li.product .price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sale_price_color',
			array(
				'label'     => __( 'Sale Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-item .mpd-product-price ins, {{WRAPPER}} .mpd-related-item .price ins, {{WRAPPER}} ul.products li.product .price ins' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .mpd-product-item .mpd-product-price, {{WRAPPER}} .mpd-related-item .price, {{WRAPPER}} ul.products li.product .price',
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
					'{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-related-item .button, {{WRAPPER}} .mpd-related-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-related-item .button, {{WRAPPER}} .mpd-related-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button',
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
					'{{WRAPPER}} .mpd-product-item .mpd-product-button .button:hover, {{WRAPPER}} .mpd-related-item .button:hover, {{WRAPPER}} .mpd-related-item .add_to_cart_button:hover, {{WRAPPER}} ul.products li.product .button:hover, {{WRAPPER}} ul.products li.product .add_to_cart_button:hover' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .mpd-product-item .mpd-product-button .button:hover, {{WRAPPER}} .mpd-related-item .button:hover, {{WRAPPER}} .mpd-related-item .add_to_cart_button:hover, {{WRAPPER}} ul.products li.product .button:hover, {{WRAPPER}} ul.products li.product .add_to_cart_button:hover',
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-item .mpd-product-button .button:hover, {{WRAPPER}} .mpd-related-item .button:hover, {{WRAPPER}} .mpd-related-item .add_to_cart_button:hover, {{WRAPPER}} ul.products li.product .button:hover, {{WRAPPER}} ul.products li.product .add_to_cart_button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-related-item .button, {{WRAPPER}} .mpd-related-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-related-item .button, {{WRAPPER}} .mpd-related-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-related-item .button, {{WRAPPER}} .mpd-related-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-related-item .button, {{WRAPPER}} .mpd-related-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-product-item .mpd-product-button .button, {{WRAPPER}} .mpd-related-item .button, {{WRAPPER}} .mpd-related-item .add_to_cart_button, {{WRAPPER}} ul.products li.product .button, {{WRAPPER}} ul.products li.product .add_to_cart_button',
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
				__( 'Related Products', 'magical-products-display' ),
				__( 'This widget displays related products. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		$args = array(
			'posts_per_page' => absint( $settings['products_count'] ),
			'columns'        => absint( $settings['columns'] ?? 4 ),
			'orderby'        => $settings['orderby'],
			'order'          => $settings['order'],
		);

		// Pro: Custom query types.
		if ( $this->is_pro() && isset( $settings['query_type'] ) && 'related' !== $settings['query_type'] ) {
			$related_products = $this->get_custom_related_products( $product, $settings, $args );
		} else {
			$related_products = wc_get_related_products( $product->get_id(), absint( $settings['products_count'] ), $product->get_upsell_ids() );
		}

		if ( empty( $related_products ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-related-products' );

		// Pro: Layout mode.
		$layout_mode = 'grid';
		if ( $this->is_pro() && isset( $settings['layout_mode'] ) ) {
			$layout_mode = $settings['layout_mode'];
			$this->add_render_attribute( 'wrapper', 'class', 'mpd-related-' . $layout_mode );
		}

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( ! empty( $settings['heading'] ) ) : ?>
				<h2 class="mpd-related-heading"><?php echo esc_html( $settings['heading'] ); ?></h2>
			<?php endif; ?>

			<?php
			if ( 'carousel' === $layout_mode && $this->is_pro() ) {
				$this->render_carousel( $related_products, $settings );
			} else {
				$this->render_grid( $related_products, $settings );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render grid layout.
	 *
	 * @since 2.0.0
	 *
	 * @param array $products Product IDs.
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_grid( $products, $settings ) {
		?>
		<div class="mpd-products-grid mpd-related-products-grid">
			<?php
			foreach ( $products as $product_id ) {
				$related_product = wc_get_product( $product_id );
				if ( ! $related_product ) {
					continue;
				}
				?>
				<div class="mpd-product-item mpd-related-item">
					<?php if ( 'yes' === ( $settings['show_image'] ?? 'yes' ) ) : ?>
						<div class="mpd-product-image-wrapper">
							<?php if ( $related_product->is_on_sale() ) : ?>
								<span class="mpd-sale-badge"><?php esc_html_e( 'Sale!', 'magical-products-display' ); ?></span>
							<?php endif; ?>
							<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="mpd-product-image">
								<?php echo wp_kses_post( $related_product->get_image( 'woocommerce_thumbnail' ) ); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="mpd-product-content">
						<?php if ( 'yes' === ( $settings['show_title'] ?? 'yes' ) ) : ?>
							<h2 class="mpd-product-title">
								<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
									<?php echo esc_html( $related_product->get_name() ); ?>
								</a>
							</h2>
						<?php endif; ?>

						<?php if ( 'yes' === ( $settings['show_rating'] ?? 'yes' ) && $related_product->get_average_rating() ) : ?>
							<div class="mpd-product-rating">
								<?php echo wc_get_rating_html( $related_product->get_average_rating(), $related_product->get_rating_count() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php endif; ?>

						<?php if ( 'yes' === ( $settings['show_price'] ?? 'yes' ) ) : ?>
							<div class="mpd-product-price">
								<?php echo wp_kses_post( $related_product->get_price_html() ); ?>
							</div>
						<?php endif; ?>

						<?php if ( 'yes' === ( $settings['show_add_to_cart'] ?? 'yes' ) ) : ?>
							<div class="mpd-product-button">
								<?php
								echo apply_filters(
									'woocommerce_loop_add_to_cart_link',
									sprintf(
										'<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
										esc_url( $related_product->add_to_cart_url() ),
										esc_attr( implode( ' ', array_filter( array(
											'button',
											'mpd-add-to-cart',
											$related_product->is_purchasable() && $related_product->is_in_stock() ? 'add_to_cart_button' : '',
											$related_product->supports( 'ajax_add_to_cart' ) && $related_product->is_purchasable() && $related_product->is_in_stock() ? 'ajax_add_to_cart' : '',
										) ) ) ),
										wc_implode_html_attributes( array(
											'data-product_id'  => $related_product->get_id(),
											'data-product_sku' => $related_product->get_sku(),
											'aria-label'       => $related_product->add_to_cart_description(),
											'rel'              => 'nofollow',
										) ),
										esc_html( $related_product->add_to_cart_text() )
									),
									$related_product
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
	 * @param array $products Product IDs.
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_carousel( $products, $settings ) {
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
		<div class="mpd-related-carousel swiper" data-carousel="<?php echo esc_attr( wp_json_encode( $carousel_data ) ); ?>">
			<div class="swiper-wrapper">
				<?php
				foreach ( $products as $product_id ) {
					$related_product = wc_get_product( $product_id );
					if ( ! $related_product ) {
						continue;
					}
					?>
					<div class="swiper-slide">
						<div class="mpd-product-item mpd-related-item">
							<?php if ( 'yes' === $settings['show_image'] ) : ?>
								<div class="mpd-product-image-wrapper">
									<?php if ( $related_product->is_on_sale() ) : ?>
										<span class="mpd-sale-badge"><?php esc_html_e( 'Sale!', 'magical-products-display' ); ?></span>
									<?php endif; ?>
									<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="mpd-product-image">
										<?php echo wp_kses_post( $related_product->get_image( 'woocommerce_thumbnail' ) ); ?>
									</a>
								</div>
							<?php endif; ?>

							<div class="mpd-product-content">
								<?php if ( 'yes' === $settings['show_title'] ) : ?>
									<h2 class="mpd-product-title">
										<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
											<?php echo esc_html( $related_product->get_name() ); ?>
										</a>
									</h2>
								<?php endif; ?>

								<?php if ( 'yes' === $settings['show_rating'] && $related_product->get_average_rating() ) : ?>
									<div class="mpd-product-rating">
										<?php echo wc_get_rating_html( $related_product->get_average_rating(), $related_product->get_rating_count() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								<?php endif; ?>

								<?php if ( 'yes' === $settings['show_price'] ) : ?>
									<div class="mpd-product-price">
										<?php echo wp_kses_post( $related_product->get_price_html() ); ?>
									</div>
								<?php endif; ?>

								<?php if ( 'yes' === $settings['show_add_to_cart'] ) : ?>
									<div class="mpd-product-button">
										<?php
										echo apply_filters(
											'woocommerce_loop_add_to_cart_link',
											sprintf(
												'<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
												esc_url( $related_product->add_to_cart_url() ),
												esc_attr( implode( ' ', array_filter( array(
													'button',
													'mpd-add-to-cart',
													$related_product->is_purchasable() && $related_product->is_in_stock() ? 'add_to_cart_button' : '',
													$related_product->supports( 'ajax_add_to_cart' ) && $related_product->is_purchasable() && $related_product->is_in_stock() ? 'ajax_add_to_cart' : '',
												) ) ) ),
												wc_implode_html_attributes( array(
													'data-product_id'  => $related_product->get_id(),
													'data-product_sku' => $related_product->get_sku(),
													'aria-label'       => $related_product->add_to_cart_description(),
													'rel'              => 'nofollow',
												) ),
												esc_html( $related_product->add_to_cart_text() )
											),
											$related_product
										);
										?>
									</div>
								<?php endif; ?>
							</div>
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
			var initRelatedSwiper = function() {
				if (typeof Swiper === 'undefined') {
					setTimeout(initRelatedSwiper, 100);
					return;
				}
				
				document.querySelectorAll('.mpd-related-carousel.swiper:not(.swiper-initialized)').forEach(function(swiperEl) {
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
				document.addEventListener('DOMContentLoaded', initRelatedSwiper);
			} else {
				initRelatedSwiper();
			}
			
			// Also try after a short delay for Elementor preview
			setTimeout(initRelatedSwiper, 500);
		})();
		</script>
		<?php
	}

	/**
	 * Get custom related products (Pro).
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  Current product.
	 * @param array       $settings Widget settings.
	 * @param array       $args     Query arguments.
	 * @return array Product IDs.
	 */
	private function get_custom_related_products( $product, $settings, $args ) {
		$query_args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $args['posts_per_page'],
			'orderby'        => $args['orderby'],
			'order'          => $args['order'],
			'post__not_in'   => array( $product->get_id() ),
			'fields'         => 'ids',
		);

		switch ( $settings['query_type'] ) {
			case 'same_cat':
				$query_args['tax_query'] = array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => wc_get_product_term_ids( $product->get_id(), 'product_cat' ),
					),
				);
				break;

			case 'same_tag':
				$query_args['tax_query'] = array(
					array(
						'taxonomy' => 'product_tag',
						'field'    => 'term_id',
						'terms'    => wc_get_product_term_ids( $product->get_id(), 'product_tag' ),
					),
				);
				break;

			case 'same_price':
				$price              = floatval( $product->get_price() );
				$price_range        = $price * 0.2; // 20% range.
				$query_args['meta_query'] = array(
					array(
						'key'     => '_price',
						'value'   => array( $price - $price_range, $price + $price_range ),
						'type'    => 'NUMERIC',
						'compare' => 'BETWEEN',
					),
				);
				break;
		}

		// Exclude out of stock.
		if ( 'yes' === ( $settings['exclude_out_of_stock'] ?? '' ) ) {
			$query_args['meta_query'][] = array(
				'key'     => '_stock_status',
				'value'   => 'instock',
				'compare' => '=',
			);
		}

		$query = new \WP_Query( $query_args );

		return $query->posts;
	}
}
