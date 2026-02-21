<?php
/**
 * Product Tabs Widget
 *
 * Displays the product information tabs on single product pages.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\SingleProduct;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Repeater;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Product_Tabs
 *
 * @since 2.0.0
 */
class Product_Tabs extends Widget_Base {

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
		return 'mpd-product-tabs';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Product Tabs', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'tabs', 'description', 'reviews', 'information', 'woocommerce', 'single' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'wc-single-product' );
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
				'label' => __( 'Tabs', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'horizontal' => __( 'Horizontal', 'magical-products-display' ),
					'vertical'   => __( 'Vertical', 'magical-products-display' ),
				),
				'default' => 'horizontal',
			)
		);

		$this->add_control(
			'show_description_tab',
			array(
				'label'        => __( 'Description Tab', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_additional_tab',
			array(
				'label'        => __( 'Additional Information Tab', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_reviews_tab',
			array(
				'label'        => __( 'Reviews Tab', 'magical-products-display' ),
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Custom Tabs & Accordion Mode', 'magical-products-display' ) );
		}
			$this->add_control(
				'display_mode',
				array(
					'label'   => __( 'Display Mode', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'tabs'      => __( 'Tabs', 'magical-products-display' ),
						'accordion' => __( 'Accordion', 'magical-products-display' ),
					),
					'default' => 'tabs',
				)
			);

			$this->add_control(
				'enable_custom_tabs',
				array(
					'label'        => __( 'Enable Custom Tabs', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$repeater = new Repeater();

			$repeater->add_control(
				'tab_title',
				array(
					'label'   => __( 'Tab Title', 'magical-products-display' ),
					'type'    => Controls_Manager::TEXT,
					'default' => __( 'Custom Tab', 'magical-products-display' ),
				)
			);

			$repeater->add_control(
				'tab_content',
				array(
					'label'   => __( 'Tab Content', 'magical-products-display' ),
					'type'    => Controls_Manager::WYSIWYG,
					'default' => __( 'Tab content goes here...', 'magical-products-display' ),
				)
			);

			$repeater->add_control(
				'tab_icon',
				array(
					'label' => __( 'Tab Icon', 'magical-products-display' ),
					'type'  => Controls_Manager::ICONS,
				)
			);

			$repeater->add_control(
				'tab_position',
				array(
					'label'   => __( 'Position', 'magical-products-display' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 10,
					'min'     => 1,
					'max'     => 100,
				)
			);

			$this->add_control(
				'custom_tabs',
				array(
					'label'     => __( 'Custom Tabs', 'magical-products-display' ),
					'type'      => Controls_Manager::REPEATER,
					'fields'    => $repeater->get_controls(),
					'default'   => array(),
					'condition' => array(
						'enable_custom_tabs' => 'yes',
					),
				)
			);

			$this->add_control(
				'tabs_order_heading',
				array(
					'label'     => __( 'Tab Order', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'description_position',
				array(
					'label'   => __( 'Description Position', 'magical-products-display' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 10,
					'min'     => 1,
					'max'     => 100,
				)
			);

			$this->add_control(
				'additional_position',
				array(
					'label'   => __( 'Additional Info Position', 'magical-products-display' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 20,
					'min'     => 1,
					'max'     => 100,
				)
			);

			$this->add_control(
				'reviews_position',
				array(
					'label'   => __( 'Reviews Position', 'magical-products-display' ),
					'type'    => Controls_Manager::NUMBER,
					'default' => 30,
					'min'     => 1,
					'max'     => 100,
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
		// Tab Navigation Style.
		$this->start_controls_section(
			'section_style_tabs',
			array(
				'label' => __( 'Tab Navigation', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'tabs_align',
			array(
				'label'     => __( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Start', 'magical-products-display' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'End', 'magical-products-display' ),
						'icon'  => 'eicon-h-align-right',
					),
					'stretch'    => array(
						'title' => __( 'Stretch', 'magical-products-display' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs .tabs, {{WRAPPER}} .mpd-tabs-nav' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'tabs_spacing',
			array(
				'label'      => __( 'Tab Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs .tabs li, {{WRAPPER}} .mpd-tab-item' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_style_tabs' );

		$this->start_controls_tab(
			'tab_normal',
			array(
				'label' => __( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'tab_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs .tabs li a, {{WRAPPER}} .mpd-tab-item' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs .tabs li a, {{WRAPPER}} .mpd-tab-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_hover',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'tab_hover_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs .tabs li a:hover, {{WRAPPER}} .mpd-tab-item:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs .tabs li a:hover, {{WRAPPER}} .mpd-tab-item:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_active',
			array(
				'label' => __( 'Active', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'tab_active_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs .tabs li.active a, {{WRAPPER}} .mpd-tab-item.active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_active_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs .tabs li.active a, {{WRAPPER}} .mpd-tab-item.active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'tab_typography',
				'selector'  => '{{WRAPPER}} .woocommerce-tabs .tabs li a, {{WRAPPER}} .mpd-tab-item',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'tab_border',
				'selector' => '{{WRAPPER}} .woocommerce-tabs .tabs li a, {{WRAPPER}} .mpd-tab-item',
			)
		);

		$this->add_responsive_control(
			'tab_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs .tabs li a, {{WRAPPER}} .mpd-tab-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tab_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs .tabs li a, {{WRAPPER}} .mpd-tab-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Tab Content Style.
		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => __( 'Tab Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-tabs .panel, {{WRAPPER}} .mpd-tab-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'content_background',
				'selector' => '{{WRAPPER}} .woocommerce-tabs .panel, {{WRAPPER}} .mpd-tab-content',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .woocommerce-tabs .panel, {{WRAPPER}} .mpd-tab-content',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'content_border',
				'selector' => '{{WRAPPER}} .woocommerce-tabs .panel, {{WRAPPER}} .mpd-tab-content',
			)
		);

		$this->add_responsive_control(
			'content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs .panel, {{WRAPPER}} .mpd-tab-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-tabs .panel, {{WRAPPER}} .mpd-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Accordion Style Section (Pro).
		$this->start_controls_section(
			'section_style_accordion',
			array(
				'label'     => __( 'Accordion Style', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'display_mode' => 'accordion',
				),
			)
		);

		// Accordion Header Styles.
		$this->add_control(
			'accordion_header_heading',
			array(
				'label'     => __( 'Header', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'accordion_header_tabs' );

		// Normal State.
		$this->start_controls_tab(
			'accordion_header_normal',
			array(
				'label' => __( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'accordion_header_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-header' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accordion_header_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-header' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accordion_icon_color',
			array(
				'label'     => __( 'Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		// Hover State.
		$this->start_controls_tab(
			'accordion_header_hover',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'accordion_header_hover_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-header:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-header:hover .mpd-accordion-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accordion_header_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-header:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accordion_icon_hover_color',
			array(
				'label'     => __( 'Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-header:hover .mpd-accordion-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		// Active State.
		$this->start_controls_tab(
			'accordion_header_active',
			array(
				'label' => __( 'Active', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'accordion_header_active_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-item.active .mpd-accordion-header' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-item.active .mpd-accordion-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accordion_header_active_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-item.active .mpd-accordion-header' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accordion_icon_active_color',
			array(
				'label'     => __( 'Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-item.active .mpd-accordion-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'accordion_header_typography',
				'label'     => __( 'Header Typography', 'magical-products-display' ),
				'selector'  => '{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-title',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'accordion_header_padding',
			array(
				'label'      => __( 'Header Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Accordion Item Border & Spacing.
		$this->add_control(
			'accordion_item_heading',
			array(
				'label'     => __( 'Accordion Item', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'accordion_item_border',
				'selector' => '{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-item',
			)
		);

		$this->add_responsive_control(
			'accordion_item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'accordion_item_spacing',
			array(
				'label'      => __( 'Space Between Items', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Accordion Content Styles.
		$this->add_control(
			'accordion_content_heading',
			array(
				'label'     => __( 'Content', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'accordion_content_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accordion_content_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'accordion_content_typography',
				'label'    => __( 'Content Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-content',
			)
		);

		$this->add_responsive_control(
			'accordion_content_padding',
			array(
				'label'      => __( 'Content Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Icon Size.
		$this->add_control(
			'accordion_icon_heading',
			array(
				'label'     => __( 'Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'accordion_icon_size',
			array(
				'label'      => __( 'Icon Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-tabs-accordion .mpd-accordion-icon' => 'font-size: {{SIZE}}{{UNIT}};',
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
		global $product, $post;

		$product = $this->get_current_product();

		if ( ! $product ) {
			$this->render_editor_placeholder(
				__( 'Product Tabs', 'magical-products-display' ),
				__( 'This widget displays product tabs. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-product-tabs' );
		$this->add_render_attribute( 'wrapper', 'class', 'mpd-tabs-layout-' . $settings['layout'] );

		// Get the display mode.
		$display_mode = 'tabs';
		if ( $this->is_pro() && isset( $settings['display_mode'] ) ) {
			$display_mode = $settings['display_mode'];
			$this->add_render_attribute( 'wrapper', 'class', 'mpd-tabs-mode-' . $display_mode );
		}

		// Setup global post for WooCommerce.
		$post = get_post( $product->get_id() );
		setup_postdata( $post );

		// Filter tabs based on settings.
		add_filter( 'woocommerce_product_tabs', array( $this, 'filter_product_tabs' ), 99, 1 );

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( 'accordion' === $display_mode && $this->is_pro() ) {
				$this->render_accordion_tabs( $product, $settings );
			} elseif ( $this->is_editor_mode() ) {
				// Render custom tabs in editor mode for better preview.
				$this->render_editor_tabs( $product, $settings );
			} else {
				// Default WooCommerce tabs on frontend.
				woocommerce_output_product_data_tabs();
			}
			?>
		</div>
		<?php

		// Remove filter.
		remove_filter( 'woocommerce_product_tabs', array( $this, 'filter_product_tabs' ), 99 );

		wp_reset_postdata();
	}

	/**
	 * Render tabs in editor mode for proper preview.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  The product object.
	 * @param array       $settings Widget settings.
	 * @return void
	 */
	private function render_editor_tabs( $product, $settings ) {
		// Build tabs array manually for editor.
		$tabs = array();

		// Description tab.
		if ( 'yes' === ( $settings['show_description_tab'] ?? 'yes' ) && $product->get_description() ) {
			$tabs['description'] = array(
				'title'    => __( 'Description', 'magical-products-display' ),
				'priority' => 10,
				'callback' => 'woocommerce_product_description_tab',
			);
		}

		// Additional information tab.
		if ( 'yes' === ( $settings['show_additional_tab'] ?? 'yes' ) ) {
			$attributes = $product->get_attributes();
			if ( $product->has_weight() || $product->has_dimensions() || ! empty( $attributes ) ) {
				$tabs['additional_information'] = array(
					'title'    => __( 'Additional information', 'magical-products-display' ),
					'priority' => 20,
					'callback' => 'woocommerce_product_additional_information_tab',
				);
			}
		}

		// Reviews tab.
		if ( 'yes' === ( $settings['show_reviews_tab'] ?? 'yes' ) && comments_open() ) {
			$tabs['reviews'] = array(
				'title'    => sprintf( __( 'Reviews (%d)', 'magical-products-display' ), $product->get_review_count() ),
				'priority' => 30,
				'callback' => 'comments_template',
			);
		}

		// Apply filters.
		$tabs = apply_filters( 'woocommerce_product_tabs', $tabs );

		if ( empty( $tabs ) ) {
			echo '<p style="padding: 20px; background: #f5f5f5; text-align: center;">' . esc_html__( 'No tabs available for this product.', 'magical-products-display' ) . '</p>';
			return;
		}

		// Sort tabs.
		uasort( $tabs, function( $a, $b ) {
			return ( $a['priority'] ?? 10 ) - ( $b['priority'] ?? 10 );
		} );

		?>
		<div class="woocommerce-tabs wc-tabs-wrapper">
			<ul class="tabs wc-tabs" role="tablist">
				<?php $first = true; ?>
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>_tab<?php echo esc_attr( $first ? ' active' : '' ); ?>" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
						<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $tab['title'] ); ?></a>
					</li>
					<?php $first = false; ?>
				<?php endforeach; ?>
			</ul>
			<?php $first = true; ?>
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>" <?php echo ! $first ? 'style="display: none;"' : ''; ?>>
					<?php
					if ( isset( $tab['callback'] ) ) {
						if ( 'comments_template' === $tab['callback'] ) {
							// Simplified reviews display for editor.
							echo '<h2>' . esc_html__( 'Reviews', 'magical-products-display' ) . '</h2>';
							echo '<p>' . sprintf( esc_html__( 'This product has %d reviews.', 'magical-products-display' ), $product->get_review_count() ) . '</p>';
						} else {
							call_user_func( $tab['callback'], $key, $tab );
						}
					}
					?>
				</div>
				<?php $first = false; ?>
			<?php endforeach; ?>
		</div>
		<script>
		(function() {
			var wrapper = document.querySelector('.mpd-product-tabs .wc-tabs-wrapper');
			if (!wrapper) return;
			var tabLinks = wrapper.querySelectorAll('.wc-tabs li a');
			tabLinks.forEach(function(link) {
				link.addEventListener('click', function(e) {
					e.preventDefault();
					var targetId = this.getAttribute('href').substring(1);
					var tabItem = this.closest('li');

					// Update active tab
					wrapper.querySelectorAll('.wc-tabs li').forEach(function(li) { li.classList.remove('active'); });
					tabItem.classList.add('active');

					// Fade out current panel, fade in new
					wrapper.querySelectorAll('.wc-tab').forEach(function(panel) {
						if (panel.id === targetId) {
							panel.style.display = 'block';
							panel.style.opacity = '0';
							panel.style.transition = 'opacity 0.3s ease';
							panel.offsetHeight; // force reflow
							panel.style.opacity = '1';
							setTimeout(function() { panel.style.removeProperty('transition'); }, 300);
						} else {
							panel.style.display = 'none';
							panel.style.opacity = '0';
						}
					});
				});
			});
		})();
		</script>
		<?php
	}

	/**
	 * Filter product tabs based on settings.
	 *
	 * @since 2.0.0
	 *
	 * @param array $tabs Product tabs.
	 * @return array Modified tabs.
	 */
	public function filter_product_tabs( $tabs ) {
		$settings = $this->get_settings_for_display();

		// Hide tabs based on settings.
		if ( 'yes' !== $settings['show_description_tab'] && isset( $tabs['description'] ) ) {
			unset( $tabs['description'] );
		}

		if ( 'yes' !== $settings['show_additional_tab'] && isset( $tabs['additional_information'] ) ) {
			unset( $tabs['additional_information'] );
		}

		if ( 'yes' !== $settings['show_reviews_tab'] && isset( $tabs['reviews'] ) ) {
			unset( $tabs['reviews'] );
		}

		// Pro: Reorder tabs.
		if ( $this->is_pro() ) {
			if ( isset( $tabs['description'] ) && ! empty( $settings['description_position'] ) ) {
				$tabs['description']['priority'] = absint( $settings['description_position'] );
			}
			if ( isset( $tabs['additional_information'] ) && ! empty( $settings['additional_position'] ) ) {
				$tabs['additional_information']['priority'] = absint( $settings['additional_position'] );
			}
			if ( isset( $tabs['reviews'] ) && ! empty( $settings['reviews_position'] ) ) {
				$tabs['reviews']['priority'] = absint( $settings['reviews_position'] );
			}

			// Pro: Add custom tabs.
			if ( 'yes' === ( $settings['enable_custom_tabs'] ?? '' ) && ! empty( $settings['custom_tabs'] ) ) {
				foreach ( $settings['custom_tabs'] as $index => $custom_tab ) {
					$tab_id           = 'mpd_custom_tab_' . $index;
					$tabs[ $tab_id ] = array(
						'title'    => $custom_tab['tab_title'],
						'priority' => absint( $custom_tab['tab_position'] ?? 50 ),
						'callback' => function() use ( $custom_tab ) {
							echo '<div class="mpd-custom-tab-content">';
							echo wp_kses_post( $custom_tab['tab_content'] );
							echo '</div>';
						},
					);
				}
			}
		}

		return $tabs;
	}

	/**
	 * Render accordion tabs (Pro).
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  The product object.
	 * @param array       $settings Widget settings.
	 * @return void
	 */
	private function render_accordion_tabs( $product, $settings ) {
		$tabs = apply_filters( 'woocommerce_product_tabs', array() );

		if ( empty( $tabs ) ) {
			return;
		}

		// Sort tabs by priority.
		uasort( $tabs, function( $a, $b ) {
			$a_priority = $a['priority'] ?? 10;
			$b_priority = $b['priority'] ?? 10;
			return $a_priority - $b_priority;
		} );

		?>
		<div class="mpd-tabs-accordion">
			<?php
			$first = true;
			foreach ( $tabs as $key => $tab ) :
				?>
				<div class="mpd-accordion-item<?php echo esc_attr( $first ? ' active' : '' ); ?>">
					<div class="mpd-accordion-header" data-target="<?php echo esc_attr( $key ); ?>">
						<span class="mpd-accordion-title"><?php echo esc_html( $tab['title'] ); ?></span>
						<span class="mpd-accordion-icon"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
					</div>
					<div class="mpd-accordion-content" id="mpd-accordion-<?php echo esc_attr( $key ); ?>">
						<?php
						if ( isset( $tab['callback'] ) ) {
							call_user_func( $tab['callback'], $key, $tab );
						}
						?>
					</div>
				</div>
				<?php
				$first = false;
			endforeach;
			?>
		</div>
		<script>
		(function() {
			function mpdSlideUp(el, duration) {
				duration = duration || 300;
				el.style.overflow = 'hidden';
				el.style.height = el.scrollHeight + 'px';
				el.offsetHeight; // force reflow
				el.style.transition = 'height ' + duration + 'ms ease, opacity ' + duration + 'ms ease';
				el.style.height = '0';
				el.style.opacity = '0';
				setTimeout(function() {
					el.style.display = 'none';
					el.style.removeProperty('height');
					el.style.removeProperty('overflow');
					el.style.removeProperty('transition');
					el.style.removeProperty('opacity');
				}, duration);
			}

			function mpdSlideDown(el, duration) {
				duration = duration || 300;
				el.style.display = 'block';
				el.style.overflow = 'hidden';
				var height = el.scrollHeight;
				el.style.height = '0';
				el.style.opacity = '0';
				el.offsetHeight; // force reflow
				el.style.transition = 'height ' + duration + 'ms ease, opacity ' + duration + 'ms ease';
				el.style.height = height + 'px';
				el.style.opacity = '1';
				setTimeout(function() {
					el.style.removeProperty('height');
					el.style.removeProperty('overflow');
					el.style.removeProperty('transition');
					el.style.removeProperty('opacity');
				}, duration);
			}

			document.querySelectorAll('.mpd-tabs-accordion .mpd-accordion-header').forEach(function(header) {
				header.addEventListener('click', function() {
					var item = this.closest('.mpd-accordion-item');
					var accordion = item.closest('.mpd-tabs-accordion');
					var isActive = item.classList.contains('active');

					// Close all other accordion items with animation
					accordion.querySelectorAll('.mpd-accordion-item.active').forEach(function(el) {
						if (el !== item) {
							el.classList.remove('active');
							mpdSlideUp(el.querySelector('.mpd-accordion-content'), 300);
						}
					});

					// Toggle clicked item with animation
					if (isActive) {
						item.classList.remove('active');
						mpdSlideUp(item.querySelector('.mpd-accordion-content'), 300);
					} else {
						item.classList.add('active');
						mpdSlideDown(item.querySelector('.mpd-accordion-content'), 300);
					}
				});
			});
		})();
		</script>
		<?php
	}
}
