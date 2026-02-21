<?php
/**
 * Cross-Sells Widget
 *
 * Displays cross-sell products on the cart page.
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
use Elementor\Group_Control_Image_Size;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cross_Sells
 *
 * @since 2.0.0
 */
class Cross_Sells extends Widget_Base {

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
	protected $widget_icon = 'eicon-products-archive';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-cross-sells';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Cross-Sells', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-products-archive';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'cross', 'sell', 'products', 'cart', 'woocommerce', 'recommendations' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		$scripts = array();
		if ( $this->is_pro() ) {
			$scripts[] = 'swiper';
			$scripts[] = 'mpd-cross-sells';
		}
		return $scripts;
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		$styles = array( 'mpd-cart-widgets' );
		if ( $this->is_pro() ) {
			$styles[] = 'swiper';
		}
		return $styles;
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
			'title',
			array(
				'label'       => __( 'Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'You may be interested in...', 'magical-products-display' ),
				'placeholder' => __( 'You may be interested in...', 'magical-products-display' ),
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
					'span' => 'span',
				),
				'default' => 'h2',
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Products to Show', 'magical-products-display' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 12,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'           => __( 'Columns', 'magical-products-display' ),
				'type'            => Controls_Manager::SELECT,
				'options'         => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'default'         => '4',
				'tablet_default'  => '3',
				'mobile_default'  => '2',
				'selectors' => array(
					'{{WRAPPER}} .mpd-cross-sells-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'rand'     => __( 'Random', 'magical-products-display' ),
					'date'     => __( 'Date', 'magical-products-display' ),
					'title'    => __( 'Title', 'magical-products-display' ),
					'price'    => __( 'Price', 'magical-products-display' ),
					'popularity' => __( 'Popularity', 'magical-products-display' ),
					'rating'   => __( 'Rating', 'magical-products-display' ),
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
		$this->add_responsive_control(
			'content_align',
			array(
				'label'     => __( 'Content Alignment', 'magical-products-display' ),
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
				'default'   => 'center',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		// Product Display Section.
		$this->start_controls_section(
			'section_product_display',
			array(
				'label' => __( 'Product Display', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'        => __( 'Show Image', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image',
				'default'   => 'woocommerce_thumbnail',
				'condition' => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => __( 'Show Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_price',
			array(
				'label'        => __( 'Show Price', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => __( 'Show Rating', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_add_to_cart',
			array(
				'label'        => __( 'Show Add to Cart', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_sale_badge',
			array(
				'label'        => __( 'Show Sale Badge', 'magical-products-display' ),
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Carousel & Custom Query', 'magical-products-display' ) );
		}
			$this->add_control(
				'layout',
				array(
					'label'   => __( 'Layout', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'grid'     => __( 'Grid', 'magical-products-display' ),
						'carousel' => __( 'Carousel', 'magical-products-display' ),
					),
					'default' => 'grid',
				)
			);

			// Carousel options.
			$this->add_control(
				'carousel_heading',
				array(
					'label'     => __( 'Carousel Settings', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						'layout' => 'carousel',
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
					'default'      => '',
					'condition'    => array(
						'layout' => 'carousel',
					),
				)
			);

			$this->add_control(
				'autoplay_speed',
				array(
					'label'     => __( 'Autoplay Speed (ms)', 'magical-products-display' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 5000,
					'min'       => 1000,
					'max'       => 10000,
					'condition' => array(
						'layout'   => 'carousel',
						'autoplay' => 'yes',
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
						'layout' => 'carousel',
					),
				)
			);

			$this->add_control(
				'navigation',
				array(
					'label'        => __( 'Navigation Arrows', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'condition'    => array(
						'layout' => 'carousel',
					),
				)
			);

			$this->add_control(
				'pagination',
				array(
					'label'        => __( 'Pagination Dots', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition'    => array(
						'layout' => 'carousel',
					),
				)
			);

			// Custom query.
			$this->add_control(
				'custom_query_heading',
				array(
					'label'     => __( 'Custom Query', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'use_custom_products',
				array(
					'label'        => __( 'Use Custom Products', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Override cross-sells with specific products.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'custom_products',
				array(
					'label'       => __( 'Select Products', 'magical-products-display' ),
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'options'     => $this->get_products_options(),
					'condition'   => array(
						'use_custom_products' => 'yes',
					),
				)
			);

			$this->add_control(
				'fallback_products',
				array(
					'label'        => __( 'Fallback to Featured', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'description'  => __( 'Show featured products when no cross-sells found.', 'magical-products-display' ),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Get products options for select control.
	 *
	 * @since 2.0.0
	 *
	 * @return array Products options.
	 */
	private function get_products_options() {
		$options = array();

		$products = wc_get_products( array(
			'status' => 'publish',
			'limit'  => 100,
		) );

		foreach ( $products as $product ) {
			$options[ $product->get_id() ] = $product->get_name();
		}

		return $options;
	}

	/**
	 * Register style controls.
	 *
	 * @since 2.0.0
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

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_background',
				'selector' => '{{WRAPPER}} .mpd-cross-sells',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sells' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-cross-sells',
			)
		);

		$this->add_responsive_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sells' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-cross-sells-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-cross-sells-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sells-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Product Card Style.
		$this->start_controls_section(
			'section_product_style',
			array(
				'label' => __( 'Product Card', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'product_background',
				'selector' => '{{WRAPPER}} .mpd-cross-sell-product',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'product_border',
				'selector' => '{{WRAPPER}} .mpd-cross-sell-product',
			)
		);

		$this->add_responsive_control(
			'product_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sell-product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'product_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-cross-sell-product',
			)
		);

		$this->add_responsive_control(
			'product_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sell-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'products_gap',
			array(
				'label'      => __( 'Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sells-grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Product Item Content Style.
		$this->start_controls_section(
			'section_product_item_style',
			array(
				'label' => __( 'Product Item Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'product_info_background',
				'selector' => '{{WRAPPER}} .mpd-cross-sell-product .mpd-product-info',
			)
		);

		$this->add_responsive_control(
			'product_info_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sell-product .mpd-product-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'product_info_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sell-product .mpd-product-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'product_info_border',
				'selector' => '{{WRAPPER}} .mpd-cross-sell-product .mpd-product-info',
			)
		);

		$this->add_responsive_control(
			'product_info_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sell-product .mpd-product-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Product Name Style.
		$this->start_controls_section(
			'section_product_name_style',
			array(
				'label'     => __( 'Product Name', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'product_name_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cross-sell-product .woocommerce-loop-product__title, {{WRAPPER}} .mpd-cross-sell-product .woocommerce-loop-product__title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'product_name_hover_color',
			array(
				'label'     => __( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cross-sell-product .woocommerce-loop-product__title:hover, {{WRAPPER}} .mpd-cross-sell-product .woocommerce-loop-product__title a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_name_typography',
				'selector' => '{{WRAPPER}} .mpd-cross-sell-product .woocommerce-loop-product__title',
			)
		);

		$this->end_controls_section();

		// Price Style.
		$this->start_controls_section(
			'section_price_style',
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
				'label'     => __( 'Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cross-sell-product .price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sale_price_color',
			array(
				'label'     => __( 'Sale Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cross-sell-product .price ins' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'regular_price_color',
			array(
				'label'     => __( 'Regular Price Color (On Sale)', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cross-sell-product .price del' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .mpd-cross-sell-product .price',
			)
		);

		$this->end_controls_section();

		// Rating Style.
		$this->start_controls_section(
			'section_rating_style',
			array(
				'label'     => __( 'Rating', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_rating' => 'yes',
				),
			)
		);

		$this->add_control(
			'star_color',
			array(
				'label'     => __( 'Star Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-star-full, {{WRAPPER}} .mpd-star-half' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'star_empty_color',
			array(
				'label'     => __( 'Empty Star Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-star-empty' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'star_size',
			array(
				'label'      => __( 'Star Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-star-rating' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rating_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-product-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Image Style.
		$this->start_controls_section(
			'section_image_style',
			array(
				'label'     => __( 'Image', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'image_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sell-product .mpd-cross-sell-image-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} .mpd-cross-sell-product .mpd-cross-sell-image-link',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sell-product .mpd-cross-sell-image-link, {{WRAPPER}} .mpd-cross-sell-product .mpd-cross-sell-image-link img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-cross-sell-product .mpd-cross-sell-image-link',
			)
		);

		$this->end_controls_section();

		// Add to Cart Button Style.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => __( 'Add to Cart Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_add_to_cart' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .mpd-cross-sell-product .add_to_cart_button',
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
					'{{WRAPPER}} .mpd-cross-sell-product .add_to_cart_button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cross-sell-product .add_to_cart_button' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .mpd-cross-sell-product .add_to_cart_button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cross-sell-product .add_to_cart_button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sell-product .add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-cross-sell-product .add_to_cart_button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cross-sell-product .add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Carousel Navigation Style (Pro).
		if ( $this->is_pro() ) {
			$this->start_controls_section(
				'section_carousel_nav_style',
				array(
					'label'     => __( 'Carousel Navigation', 'magical-products-display' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'layout'     => 'carousel',
						'navigation' => 'yes',
					),
				)
			);

			$this->add_control(
				'nav_arrow_color',
				array(
					'label'     => __( 'Arrow Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .swiper-button-prev:after, {{WRAPPER}} .swiper-button-next:after' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'nav_arrow_hover_color',
				array(
					'label'     => __( 'Arrow Hover Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .swiper-button-prev:hover:after, {{WRAPPER}} .swiper-button-next:hover:after' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'nav_bg_color',
				array(
					'label'     => __( 'Background Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'nav_bg_hover_color',
				array(
					'label'     => __( 'Background Hover Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'nav_size',
				array(
					'label'      => __( 'Size', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 30,
							'max' => 60,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'nav_border',
					'selector' => '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next',
				)
			);

			$this->add_responsive_control(
				'nav_border_radius',
				array(
					'label'      => __( 'Border Radius', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			// Carousel Dots Style.
			$this->start_controls_section(
				'section_carousel_dots_style',
				array(
					'label'     => __( 'Carousel Dots', 'magical-products-display' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'layout'     => 'carousel',
						'pagination' => 'yes',
					),
				)
			);

			$this->add_control(
				'dots_color',
				array(
					'label'     => __( 'Dot Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'dots_active_color',
				array(
					'label'     => __( 'Active Dot Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'dots_size',
				array(
					'label'      => __( 'Dot Size', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 6,
							'max' => 20,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'dots_spacing',
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
						'{{WRAPPER}} .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'dots_margin_top',
				array(
					'label'      => __( 'Top Margin', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
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

		// Get cross-sell products.
		$cross_sells = $this->get_cross_sell_products( $settings );

		// Check if we're in editor mode and no products found.
		if ( empty( $cross_sells ) && $this->is_editor_mode() ) {
			$this->render_editor_placeholder(
				__( 'Cross-Sells', 'magical-products-display' ),
				__( 'No cross-sell products found. Add products to cart or configure cross-sells in your products.', 'magical-products-display' )
			);
			return;
		}

		if ( empty( $cross_sells ) ) {
			return;
		}

		$this->render_cross_sells( $settings, $cross_sells );
	}

	/**
	 * Get cross-sell products.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return array Cross-sell products.
	 */
	private function get_cross_sell_products( $settings ) {
		$limit   = absint( $settings['posts_per_page'] );
		$orderby = $settings['orderby'];
		$order   = $settings['order'];

		// Pro: Use custom products.
		if ( $this->is_pro() && isset( $settings['use_custom_products'] ) && 'yes' === $settings['use_custom_products'] && ! empty( $settings['custom_products'] ) ) {
			$product_ids = array_map( 'absint', $settings['custom_products'] );
			return wc_get_products( array(
				'include' => $product_ids,
				'status'  => 'publish',
				'limit'   => $limit,
			) );
		}

		// Get cart cross-sells.
		$cross_sell_ids = WC()->cart ? WC()->cart->get_cross_sells() : array();

		// In editor mode, get sample products.
		if ( empty( $cross_sell_ids ) && $this->is_editor_mode() ) {
			$cross_sell_ids = $this->get_sample_product_ids( $limit );
		}

		// Pro: Fallback to featured products.
		if ( empty( $cross_sell_ids ) && $this->is_pro() && isset( $settings['fallback_products'] ) && 'yes' === $settings['fallback_products'] ) {
			return wc_get_products( array(
				'status'   => 'publish',
				'limit'    => $limit,
				'featured' => true,
				'orderby'  => $orderby,
				'order'    => $order,
			) );
		}

		if ( empty( $cross_sell_ids ) ) {
			return array();
		}

		return wc_get_products( array(
			'include' => $cross_sell_ids,
			'status'  => 'publish',
			'limit'   => $limit,
			'orderby' => $orderby,
			'order'   => $order,
		) );
	}

	/**
	 * Get sample product IDs for editor preview.
	 *
	 * @since 2.0.0
	 *
	 * @param int $limit Number of products.
	 * @return array Product IDs.
	 */
	private function get_sample_product_ids( $limit ) {
		$products = wc_get_products( array(
			'status' => 'publish',
			'limit'  => $limit,
		) );

		$ids = array();
		foreach ( $products as $product ) {
			$ids[] = $product->get_id();
		}

		return $ids;
	}

	/**
	 * Render cross-sells.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings    Widget settings.
	 * @param array $cross_sells Cross-sell products.
	 * @return void
	 */
	private function render_cross_sells( $settings, $cross_sells ) {
		$title     = $settings['title'];
		$title_tag = $settings['title_tag'];
		$layout    = $this->is_pro() && isset( $settings['layout'] ) ? $settings['layout'] : 'grid';

		$show_image       = 'yes' === $settings['show_image'];
		$show_title       = 'yes' === $settings['show_title'];
		$show_price       = 'yes' === $settings['show_price'];
		$show_rating      = 'yes' === $settings['show_rating'];
		$show_add_to_cart = 'yes' === $settings['show_add_to_cart'];
		$show_sale_badge  = 'yes' === $settings['show_sale_badge'];
		$image_size       = $settings['image_size'];

		// Carousel settings (Pro).
		$carousel_settings = array();
		if ( $this->is_pro() && 'carousel' === $layout ) {
			$carousel_settings = array(
				'autoplay'       => 'yes' === $settings['autoplay'],
				'autoplay_speed' => isset( $settings['autoplay_speed'] ) ? $settings['autoplay_speed'] : 5000,
				'loop'           => 'yes' === $settings['loop'],
				'navigation'     => 'yes' === $settings['navigation'],
				'pagination'     => 'yes' === $settings['pagination'],
				'slides_per_view' => $settings['columns'],
			);
		}

		$wrapper_class = 'mpd-cross-sells';
		if ( 'carousel' === $layout ) {
			$wrapper_class .= ' mpd-cross-sells-carousel';
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>" <?php echo 'carousel' === $layout ? 'data-carousel-settings="' . esc_attr( wp_json_encode( $carousel_settings ) ) . '"' : ''; ?>>
			<?php if ( ! empty( $title ) ) : ?>
				<<?php echo esc_attr( $title_tag ); ?> class="mpd-cross-sells-title">
					<?php echo esc_html( $title ); ?>
				</<?php echo esc_attr( $title_tag ); ?>>
			<?php endif; ?>

			<?php if ( 'carousel' === $layout && $this->is_pro() ) : ?>
				<div class="swiper mpd-cross-sells-swiper">
					<div class="swiper-wrapper">
						<?php foreach ( $cross_sells as $product ) : ?>
							<div class="swiper-slide">
								<?php $this->render_product_card( $product, $settings ); ?>
							</div>
						<?php endforeach; ?>
					</div>
					<?php if ( isset( $settings['navigation'] ) && 'yes' === $settings['navigation'] ) : ?>
						<div class="swiper-button-prev"></div>
						<div class="swiper-button-next"></div>
					<?php endif; ?>
					<?php if ( isset( $settings['pagination'] ) && 'yes' === $settings['pagination'] ) : ?>
						<div class="swiper-pagination"></div>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<div class="mpd-cross-sells-grid">
					<?php foreach ( $cross_sells as $product ) : ?>
						<?php $this->render_product_card( $product, $settings ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $this->is_editor_mode() ) : ?>
				<p class="mpd-editor-notice" style="text-align: center; padding: 10px; background: #e8f4fd; color: #0c5460; margin-top: 15px; border-radius: 4px;">
					<strong>📝 <?php esc_html_e( 'Editor Preview:', 'magical-products-display' ); ?></strong>
					<?php esc_html_e( 'Showing sample products. Cross-sells from cart will display on the frontend.', 'magical-products-display' ); ?>
				</p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render product card.
	 *
	 * @since 2.0.0
	 *
	 * @param WC_Product $product  Product object.
	 * @param array      $settings Widget settings.
	 * @return void
	 */
	private function render_product_card( $product, $settings ) {
		$show_image       = 'yes' === $settings['show_image'];
		$show_title       = 'yes' === $settings['show_title'];
		$show_price       = 'yes' === $settings['show_price'];
		$show_rating      = 'yes' === $settings['show_rating'];
		$show_add_to_cart = 'yes' === $settings['show_add_to_cart'];
		$show_sale_badge  = 'yes' === $settings['show_sale_badge'];
		$image_size       = $settings['image_size'];
		$content_align    = isset( $settings['content_align'] ) ? $settings['content_align'] : 'center';

		// Set up global product for WooCommerce template functions.
		global $post;
		$original_post = $post;
		$post = get_post( $product->get_id() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $post );

		// Set global product.
		$GLOBALS['product'] = $product;
		?>
		<div class="mpd-cross-sell-product product mpd-align-<?php echo esc_attr( $content_align ); ?>">
			<?php if ( $show_sale_badge && $product->is_on_sale() ) : ?>
				<span class="onsale"><?php esc_html_e( 'Sale!', 'magical-products-display' ); ?></span>
			<?php endif; ?>

			<?php if ( $show_image ) : ?>
				<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="woocommerce-LoopProduct-link mpd-cross-sell-image-link">
					<?php echo $product->get_image( $image_size ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			<?php endif; ?>

			<div class="mpd-product-info">
				<?php if ( $show_title ) : ?>
					<h2 class="woocommerce-loop-product__title">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<?php echo esc_html( $product->get_name() ); ?>
						</a>
					</h2>
				<?php endif; ?>

				<?php if ( $show_rating && wc_review_ratings_enabled() ) : ?>
					<div class="mpd-product-rating">
						<?php $this->render_star_rating( $product->get_average_rating(), $product->get_review_count() ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $show_price ) : ?>
					<span class="price"><?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>

				<?php if ( $show_add_to_cart ) : ?>
					<div class="mpd-add-to-cart-wrap">
						<?php woocommerce_template_loop_add_to_cart(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
		// Restore original post data.
		$post = $original_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		wp_reset_postdata();
	}

	/**
	 * Render star rating with icons.
	 *
	 * @since 2.0.0
	 *
	 * @param float $rating       Product rating.
	 * @param int   $review_count Number of reviews.
	 * @return void
	 */
	private function render_star_rating( $rating, $review_count = 0 ) {
		$rating = floatval( $rating );
		?>
		<div class="mpd-star-rating" title="<?php echo esc_attr( sprintf( __( 'Rated %s out of 5', 'magical-products-display' ), $rating ) ); ?>">
			<?php
			for ( $i = 1; $i <= 5; $i++ ) {
				if ( $i <= floor( $rating ) ) {
					echo '<span class="mpd-star mpd-star-full">★</span>';
				} elseif ( $i - 0.5 <= $rating ) {
					echo '<span class="mpd-star mpd-star-half">★</span>';
				} else {
					echo '<span class="mpd-star mpd-star-empty">☆</span>';
				}
			}
			?>
		</div>
		<?php
	}
}
