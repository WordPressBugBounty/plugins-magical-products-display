<?php
/**
 * Payment Methods Widget
 *
 * Displays the checkout payment methods with gateway options.
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
 * Class Payment_Methods
 *
 * @since 2.0.0
 */
class Payment_Methods extends Widget_Base {

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
	protected $widget_icon = 'eicon-credit-card';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-payment-methods';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Payment Methods', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'payment', 'methods', 'checkout', 'gateway', 'woocommerce', 'credit', 'card' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'mpd-checkout-widgets' );
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
					'demo'  => __( 'Demo Payment Methods', 'magical-products-display' ),
					'empty' => __( 'No Payment Methods Notice', 'magical-products-display' ),
				),
				'default'     => 'demo',
				'render_type' => 'template',
				'description' => __( 'Choose what to display in the editor for styling. Frontend always shows real payment methods.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'section_title',
			array(
				'label'       => __( 'Section Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Payment', 'magical-products-display' ),
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
			'show_icons',
			array(
				'label'        => __( 'Show Payment Icons', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => $this->get_pro_notice( __( 'Custom payment icons is a Pro feature.', 'magical-products-display' ) ),
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_descriptions',
			array(
				'label'        => __( 'Show Descriptions', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => 'mpd-payment-show-desc-',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default' => __( 'Default (List)', 'magical-products-display' ),
					'tabs'    => __( 'Tabs', 'magical-products-display' ),
					'accordion' => __( 'Accordion', 'magical-products-display' ),
				),
				'default' => 'default',
				'prefix_class' => 'mpd-payment-layout-',
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
					'{{WRAPPER}} .mpd-payment-methods__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-payment-methods__title',
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
					'{{WRAPPER}} .mpd-payment-methods__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Payment Method Items Style.
		$this->start_controls_section(
			'section_method_style',
			array(
				'label'     => __( 'Payment Methods', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'editor_preview_mode' => 'demo',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'method_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wc_payment_method',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'method_border',
				'selector' => '{{WRAPPER}} .wc_payment_method',
			)
		);

		$this->add_responsive_control(
			'method_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wc_payment_method' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'method_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wc_payment_method' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'method_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wc_payment_method' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Radio Button Style.
		$this->start_controls_section(
			'section_radio_style',
			array(
				'label'     => __( 'Radio Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'editor_preview_mode' => 'demo',
				),
			)
		);

		$this->add_responsive_control(
			'radio_spacing',
			array(
				'label'      => __( 'Spacing from Label', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wc_payment_method input[type="radio"]' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'radio_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wc_payment_method input[type="radio"]:checked' => 'accent-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'radio_size',
			array(
				'label'      => __( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wc_payment_method input[type="radio"]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Label Style.
		$this->start_controls_section(
			'section_label_style',
			array(
				'label'     => __( 'Label', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'editor_preview_mode' => 'demo',
				),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wc_payment_method label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .wc_payment_method label',
			)
		);

		$this->end_controls_section();

		// Description Style.
		$this->start_controls_section(
			'section_description_style',
			array(
				'label'     => __( 'Description', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'  => 'show_descriptions',
							'value' => 'yes',
						),
						array(
							'name'  => 'editor_preview_mode',
							'value' => 'demo',
						),
					),
				),
			)
		);

		$this->add_control(
			'description_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .payment_box' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .payment_box' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .payment_box',
			)
		);

		$this->add_responsive_control(
			'description_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .payment_box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'description_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .payment_box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Accordion Style (when layout is accordion).
		$this->start_controls_section(
			'section_accordion_style',
			array(
				'label'     => __( 'Accordion Style', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'  => 'layout',
							'value' => 'accordion',
						),
						array(
							'name'  => 'editor_preview_mode',
							'value' => 'demo',
						),
					),
				),
			)
		);

		$this->add_control(
			'accordion_header_bg',
			array(
				'label'     => __( 'Header Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.mpd-payment-layout-accordion .wc_payment_method > label' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accordion_header_active_bg',
			array(
				'label'     => __( 'Active Header Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.mpd-payment-layout-accordion .wc_payment_method input:checked + label' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accordion_header_color',
			array(
				'label'     => __( 'Header Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.mpd-payment-layout-accordion .wc_payment_method > label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accordion_header_active_color',
			array(
				'label'     => __( 'Active Header Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.mpd-payment-layout-accordion .wc_payment_method input:checked + label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'accordion_header_padding',
			array(
				'label'      => __( 'Header Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}.mpd-payment-layout-accordion .wc_payment_method > label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'accordion_border',
				'selector' => '{{WRAPPER}}.mpd-payment-layout-accordion .wc_payment_method',
			)
		);

		$this->end_controls_section();

		// Tabs Style (when layout is tabs).
		$this->start_controls_section(
			'section_tabs_style',
			array(
				'label'     => __( 'Tabs Style', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'  => 'layout',
							'value' => 'tabs',
						),
						array(
							'name'  => 'editor_preview_mode',
							'value' => 'demo',
						),
					),
				),
			)
		);

		$this->add_control(
			'tab_bg',
			array(
				'label'     => __( 'Tab Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.mpd-payment-layout-tabs .wc_payment_method > label' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_active_bg',
			array(
				'label'     => __( 'Active Tab Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.mpd-payment-layout-tabs .wc_payment_method input:checked + label' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_color',
			array(
				'label'     => __( 'Tab Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.mpd-payment-layout-tabs .wc_payment_method > label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_active_color',
			array(
				'label'     => __( 'Active Tab Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}.mpd-payment-layout-tabs .wc_payment_method input:checked + label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'tab_padding',
			array(
				'label'      => __( 'Tab Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}.mpd-payment-layout-tabs .wc_payment_method > label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'tab_border',
				'selector' => '{{WRAPPER}}.mpd-payment-layout-tabs .wc_payment_method > label',
			)
		);

		$this->add_responsive_control(
			'tab_border_radius',
			array(
				'label'      => __( 'Tab Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}}.mpd-payment-layout-tabs .wc_payment_method > label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tab_gap',
			array(
				'label'      => __( 'Gap Between Tabs', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.mpd-payment-layout-tabs .wc_payment_methods' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tab_min_height',
			array(
				'label'      => __( 'Tab Min Height', 'magical-products-display' ),
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
					'{{WRAPPER}}.mpd-payment-layout-tabs .wc_payment_method > label' => 'min-height: {{SIZE}}{{UNIT}}; display: inline-flex; align-items: center; justify-content: center;',
				),
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
				'selector' => '{{WRAPPER}} .mpd-payment-methods',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-payment-methods',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-payment-methods' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-payment-methods',
			)
		);

		$this->end_controls_section();

		// No Payment Methods Notice Style.
		$this->start_controls_section(
			'section_notice_style',
			array(
				'label'     => __( 'No Payment Methods Notice', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'editor_preview_mode' => 'empty',
				),
			)
		);

		$this->add_control(
			'notice_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-info' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'notice_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-info' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'notice_icon_color',
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
				'name'     => 'notice_typography',
				'selector' => '{{WRAPPER}} .woocommerce-info',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'notice_border',
				'selector' => '{{WRAPPER}} .woocommerce-info',
			)
		);

		$this->add_responsive_control(
			'notice_border_radius',
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
			'notice_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		// In editor, show based on preview mode selection.
		$show_demo  = $is_editor && 'demo' === $preview_mode;
		$show_empty = $is_editor && 'empty' === $preview_mode;

		// Classes are now handled via prefix_class on controls
		?>
		<div class="mpd-payment-methods">
			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['section_title'] ) ) : ?>
				<<?php echo esc_html( $settings['title_tag'] ); ?> class="mpd-payment-methods__title">
					<?php echo esc_html( $settings['section_title'] ); ?>
				</<?php echo esc_html( $settings['title_tag'] ); ?>>
			<?php endif; ?>

			<div class="mpd-payment-methods__content">
				<?php
				if ( $show_demo ) {
					// Show demo payment methods in editor.
					$this->render_demo_payment_methods( $settings );
				} elseif ( $show_empty ) {
					// Show empty notice for styling.
					echo '<div class="woocommerce-notice woocommerce-notice--info woocommerce-info">';
					echo esc_html__( 'Sorry, it seems that there are no available payment methods. Please contact us if you require assistance.', 'magical-products-display' );
					echo '</div>';
				} else {
					// Frontend: Show real payment methods.
					$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
					WC()->payment_gateways->set_current_gateway( $available_gateways );

					if ( ! empty( $available_gateways ) ) {
						?>
						<ul class="wc_payment_methods payment_methods methods">
							<?php
							$first = true;
							foreach ( $available_gateways as $gateway ) {
								$checked = $first ? 'checked="checked"' : '';
								$first   = false;
								?>
								<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
									<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php echo $checked; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

									<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
										<?php echo esc_html( $gateway->get_title() ); ?>
										<?php echo wp_kses_post( $gateway->get_icon() ); ?>
									</label>

									<?php if ( 'yes' === $settings['show_descriptions'] && $gateway->has_fields() || $gateway->get_description() ) : ?>
										<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php echo ! $checked ? 'style="display:none;"' : ''; ?>>
											<?php $gateway->payment_fields(); ?>
										</div>
									<?php endif; ?>
								</li>
								<?php
							}
							?>
						</ul>
						<?php
					} else {
						echo '<div class="woocommerce-notice woocommerce-notice--info woocommerce-info">';
						$has_billing_country = WC()->customer && WC()->customer->get_billing_country();
						echo wp_kses_post( apply_filters( 'woocommerce_no_available_payment_methods_message', $has_billing_country ? __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'magical-products-display' ) : __( 'Please fill in your details above to see available payment methods.', 'magical-products-display' ) ) );
						echo '</div>';
					}
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render demo payment methods for editor preview.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_demo_payment_methods( $settings ) {
		?>
		<ul class="wc_payment_methods payment_methods methods">
			<li class="wc_payment_method payment_method_bacs">
				<input id="payment_method_bacs" type="radio" class="input-radio" name="payment_method" value="bacs" checked="checked" />
				<label for="payment_method_bacs">
					<?php esc_html_e( 'Direct bank transfer', 'magical-products-display' ); ?>
				</label>
				<?php if ( 'yes' === $settings['show_descriptions'] ) : ?>
					<div class="payment_box payment_method_bacs">
						<p><?php esc_html_e( 'Make your payment directly into our bank account. Please use your Order ID as the payment reference.', 'magical-products-display' ); ?></p>
					</div>
				<?php endif; ?>
			</li>
			<li class="wc_payment_method payment_method_cheque">
				<input id="payment_method_cheque" type="radio" class="input-radio" name="payment_method" value="cheque" />
				<label for="payment_method_cheque">
					<?php esc_html_e( 'Check payments', 'magical-products-display' ); ?>
				</label>
				<?php if ( 'yes' === $settings['show_descriptions'] ) : ?>
					<div class="payment_box payment_method_cheque" style="display:none;">
						<p><?php esc_html_e( 'Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.', 'magical-products-display' ); ?></p>
					</div>
				<?php endif; ?>
			</li>
			<li class="wc_payment_method payment_method_cod">
				<input id="payment_method_cod" type="radio" class="input-radio" name="payment_method" value="cod" />
				<label for="payment_method_cod">
					<?php esc_html_e( 'Cash on delivery', 'magical-products-display' ); ?>
				</label>
				<?php if ( 'yes' === $settings['show_descriptions'] ) : ?>
					<div class="payment_box payment_method_cod" style="display:none;">
						<p><?php esc_html_e( 'Pay with cash upon delivery.', 'magical-products-display' ); ?></p>
					</div>
				<?php endif; ?>
			</li>
			<li class="wc_payment_method payment_method_paypal">
				<input id="payment_method_paypal" type="radio" class="input-radio" name="payment_method" value="paypal" />
				<label for="payment_method_paypal">
					<?php esc_html_e( 'PayPal', 'magical-products-display' ); ?>
					<img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" alt="PayPal" style="max-height: 24px; margin-left: 10px; vertical-align: middle;" />
				</label>
				<?php if ( 'yes' === $settings['show_descriptions'] ) : ?>
					<div class="payment_box payment_method_paypal" style="display:none;">
						<p><?php esc_html_e( 'Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.', 'magical-products-display' ); ?></p>
					</div>
				<?php endif; ?>
			</li>
		</ul>
		<p class="mpd-editor-notice" style="text-align: center; padding: 10px; background: #e8f4fd; color: #0c5460; margin-top: 15px; border-radius: 4px;">
			<strong>📝 <?php esc_html_e( 'Editor Preview:', 'magical-products-display' ); ?></strong>
			<?php esc_html_e( 'This is a preview with sample payment methods. Actual payment options will display on the frontend.', 'magical-products-display' ); ?>
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
		<div class="mpd-payment-methods">
			<# if ( 'yes' === settings.show_title && settings.section_title ) { #>
				<{{{ settings.title_tag }}} class="mpd-payment-methods__title">
					{{{ settings.section_title }}}
				</{{{ settings.title_tag }}}>
			<# } #>
			
			<div class="mpd-payment-methods__content">
				<# if ( 'empty' === previewMode ) { #>
					<div class="woocommerce-notice woocommerce-notice--info woocommerce-info">
						<?php esc_html_e( 'Sorry, it seems that there are no available payment methods. Please contact us if you require assistance.', 'magical-products-display' ); ?>
					</div>
				<# } else { #>
				<ul class="wc_payment_methods payment_methods methods">
					<li class="wc_payment_method payment_method_bacs">
						<input id="payment_method_bacs" type="radio" class="input-radio" name="payment_method" value="bacs" checked="checked" />
						<label for="payment_method_bacs">
							<?php esc_html_e( 'Direct bank transfer', 'magical-products-display' ); ?>
						</label>
						<# if ( 'yes' === settings.show_descriptions ) { #>
						<div class="payment_box payment_method_bacs">
							<p><?php esc_html_e( 'Make your payment directly into our bank account. Please use your Order ID as the payment reference.', 'magical-products-display' ); ?></p>
						</div>
						<# } #>
					</li>
					<li class="wc_payment_method payment_method_cheque">
						<input id="payment_method_cheque" type="radio" class="input-radio" name="payment_method" value="cheque" />
						<label for="payment_method_cheque">
							<?php esc_html_e( 'Check payments', 'magical-products-display' ); ?>
						</label>
						<# if ( 'yes' === settings.show_descriptions ) { #>
						<div class="payment_box payment_method_cheque" style="display:none;">
							<p><?php esc_html_e( 'Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.', 'magical-products-display' ); ?></p>
						</div>
						<# } #>
					</li>
					<li class="wc_payment_method payment_method_cod">
						<input id="payment_method_cod" type="radio" class="input-radio" name="payment_method" value="cod" />
						<label for="payment_method_cod">
							<?php esc_html_e( 'Cash on delivery', 'magical-products-display' ); ?>
						</label>
						<# if ( 'yes' === settings.show_descriptions ) { #>
						<div class="payment_box payment_method_cod" style="display:none;">
							<p><?php esc_html_e( 'Pay with cash upon delivery.', 'magical-products-display' ); ?></p>
						</div>
						<# } #>
					</li>
					<li class="wc_payment_method payment_method_paypal">
						<input id="payment_method_paypal" type="radio" class="input-radio" name="payment_method" value="paypal" />
						<label for="payment_method_paypal">
							<?php esc_html_e( 'PayPal', 'magical-products-display' ); ?>
							<img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" alt="PayPal" style="max-height: 24px; margin-left: 10px; vertical-align: middle;" />
						</label>
						<# if ( 'yes' === settings.show_descriptions ) { #>
						<div class="payment_box payment_method_paypal" style="display:none;">
							<p><?php esc_html_e( 'Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.', 'magical-products-display' ); ?></p>
						</div>
						<# } #>
					</li>
				</ul>
				<p class="mpd-editor-notice" style="text-align: center; padding: 10px; background: #e8f4fd; color: #0c5460; margin-top: 15px; border-radius: 4px;">
					<strong>📝 <?php esc_html_e( 'Editor Preview:', 'magical-products-display' ); ?></strong>
					<?php esc_html_e( 'This is a preview with sample payment methods. Actual payment options will display on the frontend.', 'magical-products-display' ); ?>
				</p>
				<# } #>
			</div>
		</div>
		<?php
	}
}
