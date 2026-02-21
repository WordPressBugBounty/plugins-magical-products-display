<?php
/**
 * Wishlist Widget
 *
 * Displays user's wishlist with multiple layout and style options.
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
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Wishlist
 */
class Wishlist extends Widget_Base {

	protected $widget_category = self::CATEGORY_GLOBAL;
	protected $widget_icon = 'eicon-heart-o';

	public function get_name() {
		return 'mpd-wishlist';
	}

	public function get_title() {
		return esc_html__( 'MPD Wishlist', 'magical-products-display' );
	}

	public function get_keywords() {
		return array( 'wishlist', 'favorites', 'heart', 'save', 'products' );
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
				'label' => $this->pro_label( esc_html__( 'Wishlist Settings', 'magical-products-display' ) ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_features_notice', 'Wishlist Page' );
		}

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'My Wishlist', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'     => esc_html__( 'Title Tag', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h3',
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
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_control(
			'empty_message',
			array(
				'label'   => esc_html__( 'Empty Message', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Your wishlist is empty. Browse products and click the heart icon to add items.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => esc_html__( 'Show Items Count', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		// Layout Section
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid'  => esc_html__( 'Grid', 'magical-products-display' ),
					'list'  => esc_html__( 'List', 'magical-products-display' ),
					'table' => esc_html__( 'Table', 'magical-products-display' ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'     => esc_html__( 'Columns', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'options'   => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'condition' => array(
					'layout' => 'grid',
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array( 'min' => 0, 'max' => 60 ),
					'em' => array( 'min' => 0, 'max' => 4 ),
				),
				'default'    => array( 'size' => 20, 'unit' => 'px' ),
				'condition'  => array(
					'layout!' => 'table',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-grid' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-wishlist-list .mpd-wishlist-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
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

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image',
				'default'   => 'woocommerce_thumbnail',
				'condition' => array(
					'show_image' => 'yes',
					'layout'     => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'image_width',
			array(
				'label'      => esc_html__( 'Image Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array( 'min' => 40, 'max' => 300 ),
				),
				'default'    => array( 'size' => 70, 'unit' => 'px' ),
				'condition'  => array(
					'show_image' => 'yes',
					'layout'     => array( 'list', 'table' ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-table .mpd-wishlist-table-image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-wishlist-list .mpd-wishlist-item-image' => 'width: {{SIZE}}{{UNIT}}; flex-shrink: 0;',
					'{{WRAPPER}} .mpd-wishlist-list .mpd-wishlist-item-image img' => 'width: 100%; height: auto;',
				),
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
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_stock',
			array(
				'label'        => esc_html__( 'Stock Status', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_date_added',
			array(
				'label'        => esc_html__( 'Date Added', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
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

		// Actions Section
		$this->start_controls_section(
			'section_actions',
			array(
				'label' => esc_html__( 'Actions', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_share',
			array(
				'label'        => esc_html__( 'Show Share Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_add_all_to_cart',
			array(
				'label'        => esc_html__( 'Add All to Cart Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'continue_shopping_url',
			array(
				'label'       => esc_html__( 'Continue Shopping URL', 'magical-products-display' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'magical-products-display' ),
				'default'     => array(
					'url' => '',
				),
			)
		);

		$this->add_control(
			'continue_shopping_text',
			array(
				'label'   => esc_html__( 'Continue Shopping Text', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Continue Shopping', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		// Title Style
		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Title', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-wishlist-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Item Style
		$this->start_controls_section(
			'section_item_style',
			array(
				'label' => esc_html__( 'Item Box', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'item_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'item_border',
				'selector' => '{{WRAPPER}} .mpd-wishlist-item',
			)
		);

		$this->add_responsive_control(
			'item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_shadow',
				'selector' => '{{WRAPPER}} .mpd-wishlist-item',
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Image Style
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => esc_html__( 'Image', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-item-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Product Title Style
		$this->start_controls_section(
			'section_product_title_style',
			array(
				'label' => esc_html__( 'Product Title', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_title_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-item-title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'product_title_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-item-title a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_title_typography',
				'selector' => '{{WRAPPER}} .mpd-wishlist-item-title',
			)
		);

		$this->end_controls_section();

		// Price Style
		$this->start_controls_section(
			'section_price_style',
			array(
				'label' => esc_html__( 'Price', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-item-price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .mpd-wishlist-item-price',
			)
		);

		$this->end_controls_section();

		// Button Style
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => esc_html__( 'Add to Cart Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-item .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-item .button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_bg',
			array(
				'label'     => esc_html__( 'Hover Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-item .button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_text',
			array(
				'label'     => esc_html__( 'Hover Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-item .button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-item .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-item .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Actions Style (Add All to Cart + Share)
		$this->start_controls_section(
			'section_actions_style',
			array(
				'label' => esc_html__( 'Action Buttons', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'actions_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-actions' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'actions_gap',
			array(
				'label'      => esc_html__( 'Gap Between Buttons', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array( 'min' => 0, 'max' => 40 ),
				),
				'default'    => array( 'size' => 12, 'unit' => 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-actions' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'actions_bg_color',
			array(
				'label'     => esc_html__( 'Button Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-actions .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'actions_text_color',
			array(
				'label'     => esc_html__( 'Button Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-actions .button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'actions_hover_bg',
			array(
				'label'     => esc_html__( 'Hover Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-actions .button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'actions_hover_text',
			array(
				'label'     => esc_html__( 'Hover Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-actions .button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'actions_typography',
				'selector' => '{{WRAPPER}} .mpd-wishlist-actions .button',
			)
		);

		$this->add_responsive_control(
			'actions_padding',
			array(
				'label'      => esc_html__( 'Button Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-actions .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'actions_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-actions .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'actions_border',
				'selector' => '{{WRAPPER}} .mpd-wishlist-actions .button',
			)
		);

		$this->end_controls_section();

		// Date Style
		$this->start_controls_section(
			'section_date_style',
			array(
				'label'     => esc_html__( 'Date Added', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_date_added' => 'yes',
				),
			)
		);

		$this->add_control(
			'date_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-item-date' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-wishlist-table-date' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'date_label_color',
			array(
				'label'     => esc_html__( 'Label Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'layout!' => 'table',
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-date-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'date_typography',
				'selector' => '{{WRAPPER}} .mpd-wishlist-item-date, {{WRAPPER}} .mpd-wishlist-table-date',
			)
		);

		$this->add_responsive_control(
			'date_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'condition'  => array(
					'layout!' => 'table',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-item-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Empty Wishlist Style
		$this->start_controls_section(
			'section_empty_style',
			array(
				'label' => esc_html__( 'Empty Wishlist', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'empty_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-empty' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'empty_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-empty' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'empty_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-empty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'empty_border',
				'selector' => '{{WRAPPER}} .mpd-wishlist-empty',
			)
		);

		$this->add_responsive_control(
			'empty_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-empty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'empty_heading_message',
			array(
				'label'     => esc_html__( 'Message Text', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'empty_text_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-empty p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'empty_text_typography',
				'selector' => '{{WRAPPER}} .mpd-wishlist-empty p',
			)
		);

		$this->add_responsive_control(
			'empty_text_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-empty p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'empty_heading_button',
			array(
				'label'     => esc_html__( 'Button', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'empty_btn_bg_color',
			array(
				'label'     => esc_html__( 'Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-empty .button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'empty_btn_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-empty .button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'empty_btn_hover_bg',
			array(
				'label'     => esc_html__( 'Hover Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-empty .button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'empty_btn_hover_text',
			array(
				'label'     => esc_html__( 'Hover Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-empty .button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'empty_btn_typography',
				'selector' => '{{WRAPPER}} .mpd-wishlist-empty .button',
			)
		);

		$this->add_responsive_control(
			'empty_btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-empty .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'empty_btn_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-empty .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'empty_btn_border',
				'selector' => '{{WRAPPER}} .mpd-wishlist-empty .button',
			)
		);

		$this->end_controls_section();

		// Remove Button Style
		$this->start_controls_section(
			'section_remove_style',
			array(
				'label' => esc_html__( 'Remove Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'remove_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-remove' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'remove_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-wishlist-remove:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'remove_size',
			array(
				'label'      => esc_html__( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array( 'min' => 12, 'max' => 30 ),
				),
				'default'    => array( 'size' => 20, 'unit' => 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-wishlist-remove' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render_widget( $settings ) {
		if ( ! $this->is_pro() ) {
			$this->render_pro_message( 'Wishlist Page' );
			return;
		}

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$product_ids = $this->get_wishlist();
		$title_tag   = $settings['title_tag'];

		// Title
		if ( ! empty( $settings['title'] ) ) {
			printf(
				'<%1$s class="mpd-wishlist-title">%2$s%3$s</%1$s>',
				esc_attr( $title_tag ),
				esc_html( $settings['title'] ),
				'yes' === $settings['show_count'] ? ' <span class="mpd-wishlist-count">(' . count( $product_ids ) . ')</span>' : ''
			);
		}

		if ( empty( $product_ids ) ) {
			echo '<div class="mpd-wishlist-empty">';
			echo '<p>' . esc_html( $settings['empty_message'] ) . '</p>';
			
			$continue_url  = ! empty( $settings['continue_shopping_url']['url'] ) ? $settings['continue_shopping_url']['url'] : wc_get_page_permalink( 'shop' );
			$continue_text = ! empty( $settings['continue_shopping_text'] ) ? $settings['continue_shopping_text'] : esc_html__( 'Continue Shopping', 'magical-products-display' );
			echo '<a href="' . esc_url( $continue_url ) . '" class="button">' . esc_html( $continue_text ) . '</a>';
			echo '</div>';
			return;
		}

		$layout     = $settings['layout'];
		$image_size = $settings['image_size'] ?? 'woocommerce_thumbnail';

		// Table layout uses proper <table> markup
		if ( 'table' === $layout ) {
			$this->render_table_layout( $settings, $product_ids, $image_size );
		} else {
			$this->render_card_layout( $settings, $product_ids, $image_size, $layout );
		}

		// Bottom actions (Add All to Cart + Share)
		$this->render_bottom_actions( $settings, $product_ids );
	}

	/**
	 * Render grid or list card layout
	 */
	private function render_card_layout( $settings, $product_ids, $image_size, $layout ) {
		$layout_class = 'mpd-wishlist-' . $layout;
		?>
		<div class="mpd-wishlist-wrapper">
			<div class="<?php echo esc_attr( $layout_class ); ?>">
			<?php foreach ( $product_ids as $product_id ) :
				$product = wc_get_product( $product_id );
				if ( ! $product ) continue;
			?>
			<div class="mpd-wishlist-item" data-product-id="<?php echo esc_attr( $product_id ); ?>">
				<?php if ( 'yes' === $settings['show_remove'] ) : ?>
				<a href="#" class="mpd-wishlist-remove" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'Remove', 'magical-products-display' ); ?>">&times;</a>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_image'] ) : ?>
				<div class="mpd-wishlist-item-image">
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
						<?php echo wp_kses_post( $product->get_image( $image_size ) ); ?>
					</a>
				</div>
				<?php endif; ?>

				<div class="mpd-wishlist-item-content">
					<?php $this->render_product_fields( $product, $settings ); ?>
				</div>
			</div>
			<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render table layout with proper <table> markup
	 */
	private function render_table_layout( $settings, $product_ids, $image_size ) {
		?>
		<div class="mpd-wishlist-wrapper">
			<table class="mpd-wishlist-table">
				<thead>
					<tr>
						<?php if ( 'yes' === $settings['show_remove'] ) : ?>
						<th class="mpd-wishlist-table-col-remove">&nbsp;</th>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_image'] ) : ?>
						<th class="mpd-wishlist-table-col-image"><?php esc_html_e( 'Image', 'magical-products-display' ); ?></th>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_title'] ) : ?>
						<th class="mpd-wishlist-table-col-name"><?php esc_html_e( 'Product', 'magical-products-display' ); ?></th>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_price'] ) : ?>
						<th class="mpd-wishlist-table-col-price"><?php esc_html_e( 'Price', 'magical-products-display' ); ?></th>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_rating'] ) : ?>
						<th class="mpd-wishlist-table-col-rating"><?php esc_html_e( 'Rating', 'magical-products-display' ); ?></th>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_stock'] ) : ?>
						<th class="mpd-wishlist-table-col-stock"><?php esc_html_e( 'Stock', 'magical-products-display' ); ?></th>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_date_added'] ) : ?>
						<th class="mpd-wishlist-table-col-date"><?php esc_html_e( 'Date Added', 'magical-products-display' ); ?></th>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_add_to_cart'] ) : ?>
						<th class="mpd-wishlist-table-col-action"><?php esc_html_e( 'Action', 'magical-products-display' ); ?></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
				<?php foreach ( $product_ids as $product_id ) :
					$product = wc_get_product( $product_id );
					if ( ! $product ) continue;
				?>
					<tr class="mpd-wishlist-table-row mpd-wishlist-item" data-product-id="<?php echo esc_attr( $product_id ); ?>">
						<?php if ( 'yes' === $settings['show_remove'] ) : ?>
						<td class="mpd-wishlist-table-remove">
							<a href="#" class="mpd-wishlist-remove mpd-wishlist-table-remove-btn" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'Remove', 'magical-products-display' ); ?>">&times;</a>
						</td>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_image'] ) : ?>
						<td class="mpd-wishlist-table-image">
							<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
								<?php echo wp_kses_post( $product->get_image( $image_size ) ); ?>
							</a>
						</td>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_title'] ) : ?>
						<td class="mpd-wishlist-table-name">
							<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
								<?php echo esc_html( $product->get_name() ); ?>
							</a>
						</td>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_price'] ) : ?>
						<td class="mpd-wishlist-table-price">
							<?php echo wp_kses_post( $product->get_price_html() ); ?>
						</td>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_rating'] ) : ?>
						<td class="mpd-wishlist-table-rating">
							<?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating() ) ); ?>
						</td>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_stock'] ) : ?>
						<td class="mpd-wishlist-table-stock">
							<?php if ( $product->is_in_stock() ) : ?>
							<span class="in-stock"><?php esc_html_e( 'In Stock', 'magical-products-display' ); ?></span>
							<?php else : ?>
							<span class="out-of-stock"><?php esc_html_e( 'Out of Stock', 'magical-products-display' ); ?></span>
							<?php endif; ?>
						</td>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_date_added'] ) : ?>
						<td class="mpd-wishlist-table-date">
							<?php echo esc_html( $this->get_date_added( $product_id ) ); ?>
						</td>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_add_to_cart'] ) : ?>
						<td class="mpd-wishlist-table-action">
							<?php $this->render_add_to_cart_button( $product ); ?>
						</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Render product fields for card layouts (grid/list)
	 */
	private function render_product_fields( $product, $settings ) {
		if ( 'yes' === $settings['show_title'] ) : ?>
			<h4 class="mpd-wishlist-item-title">
				<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
					<?php echo esc_html( $product->get_name() ); ?>
				</a>
			</h4>
		<?php endif;

		if ( 'yes' === $settings['show_rating'] ) : ?>
			<div class="mpd-wishlist-item-rating">
				<?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating() ) ); ?>
			</div>
		<?php endif;

		if ( 'yes' === $settings['show_price'] ) : ?>
			<div class="mpd-wishlist-item-price">
				<?php echo wp_kses_post( $product->get_price_html() ); ?>
			</div>
		<?php endif;

		if ( 'yes' === $settings['show_stock'] ) : ?>
			<div class="mpd-wishlist-item-stock">
				<?php if ( $product->is_in_stock() ) : ?>
				<span class="in-stock"><?php esc_html_e( 'In Stock', 'magical-products-display' ); ?></span>
				<?php else : ?>
				<span class="out-of-stock"><?php esc_html_e( 'Out of Stock', 'magical-products-display' ); ?></span>
				<?php endif; ?>
			</div>
		<?php endif;

		if ( 'yes' === $settings['show_date_added'] ) : ?>
			<div class="mpd-wishlist-item-date">
				<span class="mpd-wishlist-date-label"><?php esc_html_e( 'Added:', 'magical-products-display' ); ?></span>
				<?php echo esc_html( $this->get_date_added( $product->get_id() ) ); ?>
			</div>
		<?php endif;

		if ( 'yes' === $settings['show_add_to_cart'] ) : ?>
			<div class="mpd-wishlist-item-action">
				<?php $this->render_add_to_cart_button( $product ); ?>
			</div>
		<?php endif;
	}

	/**
	 * Render a proper WooCommerce AJAX add-to-cart button
	 */
	private function render_add_to_cart_button( $product ) {
		if ( ! $product ) {
			return;
		}

		$product_type = $product->get_type();

		// Build classes for WooCommerce AJAX compatibility
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

	/**
	 * Render bottom actions: Add All to Cart + Share
	 */
	private function render_bottom_actions( $settings, $product_ids ) {
		$show_add_all = 'yes' === $settings['show_add_all_to_cart'] && count( $product_ids ) > 1;
		$show_share   = 'yes' === $settings['show_share'];

		if ( ! $show_add_all && ! $show_share ) {
			return;
		}
		?>
		<div class="mpd-wishlist-actions">
			<?php if ( $show_add_all ) : ?>
			<button type="button" class="mpd-wishlist-add-all button" data-product-ids="<?php echo esc_attr( implode( ',', $product_ids ) ); ?>">
				<?php esc_html_e( 'Add All to Cart', 'magical-products-display' ); ?>
			</button>
			<?php endif; ?>

			<?php if ( $show_share ) : ?>
			<div class="mpd-wishlist-share">
				<button type="button" class="mpd-wishlist-share-toggle button" aria-expanded="false">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:5px"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
					<?php esc_html_e( 'Share Wishlist', 'magical-products-display' ); ?>
				</button>
				<div class="mpd-wishlist-share-links" style="display:none;">
					<?php
					$share_url   = add_query_arg( 'mpd_wishlist', implode( ',', $product_ids ), home_url( '/' ) );
					$share_title = ! empty( $settings['title'] ) ? $settings['title'] : esc_html__( 'My Wishlist', 'magical-products-display' );
					?>
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( $share_url ); ?>" target="_blank" rel="noopener noreferrer" class="mpd-wishlist-share-link mpd-wishlist-share-facebook" aria-label="Facebook">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
					</a>
					<a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( $share_url ); ?>&text=<?php echo rawurlencode( $share_title ); ?>" target="_blank" rel="noopener noreferrer" class="mpd-wishlist-share-link mpd-wishlist-share-twitter" aria-label="Twitter">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
					</a>
					<a href="mailto:?subject=<?php echo rawurlencode( $share_title ); ?>&body=<?php echo rawurlencode( $share_url ); ?>" class="mpd-wishlist-share-link mpd-wishlist-share-email" aria-label="Email">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
					</a>
					<button type="button" class="mpd-wishlist-share-link mpd-wishlist-copy-link" data-url="<?php echo esc_url( $share_url ); ?>" aria-label="<?php esc_attr_e( 'Copy Link', 'magical-products-display' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
					</button>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get date when product was added to wishlist.
	 * Falls back to current date if not tracked.
	 */
	private function get_date_added( $product_id ) {
		$dates = array();

		if ( isset( $_COOKIE['mpd_wishlist_dates'] ) ) {
			$raw = sanitize_text_field( wp_unslash( $_COOKIE['mpd_wishlist_dates'] ) );
			foreach ( explode( ',', $raw ) as $entry ) {
				$parts = explode( ':', $entry, 2 );
				if ( count( $parts ) === 2 ) {
					$dates[ absint( $parts[0] ) ] = absint( $parts[1] );
				}
			}
		}

		if ( ! empty( $dates[ $product_id ] ) ) {
			return date_i18n( get_option( 'date_format' ), $dates[ $product_id ] );
		}

		// Fallback: show current date
		return date_i18n( get_option( 'date_format' ) );
	}

	private function get_wishlist() {
		$list = array();

		if ( isset( $_COOKIE['mpd_wishlist'] ) ) {
			$list = array_filter( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_COOKIE['mpd_wishlist'] ) ) ) ) );
		}

		// Editor preview
		if ( empty( $list ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => 4,
				'post_status'    => 'publish',
				'fields'         => 'ids',
			);
			$list = get_posts( $args );
		}

		return $list;
	}
}
