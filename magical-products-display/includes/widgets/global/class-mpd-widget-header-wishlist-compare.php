<?php
/**
 * MPD Header Wishlist & Compare Widget
 *
 * Displays wishlist and compare icons with item counts in the header.
 * Similar to mini-cart, shows a dropdown/popup with items.
 *
 * @package Magical_Products_Display
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\GlobalWidgets;

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
 * Header Wishlist Compare Widget.
 *
 * @since 2.0.0
 */
class Header_Wishlist_Compare extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-header-wishlist-compare';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Wishlist & Compare', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-heart';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 2.0.0
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'mpd-global' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'wishlist', 'compare', 'header', 'menu', 'icon', 'count', 'badge' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-header-wishlist-compare', 'mpd-global-widgets' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'mpd-global-widgets' );
	}

	/**
	 * Register widget controls.
	 *
	 * @since 2.0.0
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.0.0
	 */
	protected function register_content_controls() {
		// Display Section.
		$this->start_controls_section(
			'section_display',
			array(
				'label' => $this->pro_label( esc_html__( 'Display', 'magical-products-display' ) ),
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_features_notice', 'Header Wishlist & Compare' );
		}

		$this->add_control(
			'show_wishlist',
			array(
				'label'        => esc_html__( 'Show Wishlist', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_compare',
			array(
				'label'        => esc_html__( 'Show Compare', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => esc_html__( 'Show Item Count', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_label',
			array(
				'label'        => esc_html__( 'Show Labels', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'wishlist_label',
			array(
				'label'     => esc_html__( 'Wishlist Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Wishlist', 'magical-products-display' ),
				'condition' => array(
					'show_wishlist' => 'yes',
					'show_label'    => 'yes',
				),
			)
		);

		$this->add_control(
			'compare_label',
			array(
				'label'     => esc_html__( 'Compare Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Compare', 'magical-products-display' ),
				'condition' => array(
					'show_compare' => 'yes',
					'show_label'   => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_dropdown',
			array(
				'label'        => esc_html__( 'Enable Dropdown', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Show items in a dropdown on hover.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Links Section.
		$this->start_controls_section(
			'section_links',
			array(
				'label' => esc_html__( 'Links & Buttons', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'wishlist_link_heading',
			array(
				'label'     => esc_html__( 'Wishlist', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'show_wishlist' => 'yes',
				),
			)
		);

		$this->add_control(
			'wishlist_link',
			array(
				'label'       => esc_html__( 'Wishlist Page URL', 'magical-products-display' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-site.com/wishlist/', 'magical-products-display' ),
				'default'     => array(
					'url' => '',
				),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'show_wishlist' => 'yes',
				),
			)
		);

		$this->add_control(
			'wishlist_button_text',
			array(
				'label'     => esc_html__( 'Button Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'View Wishlist', 'magical-products-display' ),
				'condition' => array(
					'show_wishlist'   => 'yes',
					'enable_dropdown' => 'yes',
				),
			)
		);

		$this->add_control(
			'compare_link_heading',
			array(
				'label'     => esc_html__( 'Compare', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_compare' => 'yes',
				),
			)
		);

		$this->add_control(
			'compare_link',
			array(
				'label'       => esc_html__( 'Compare Page URL', 'magical-products-display' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-site.com/compare/', 'magical-products-display' ),
				'default'     => array(
					'url' => '',
				),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'show_compare' => 'yes',
				),
			)
		);

		$this->add_control(
			'compare_button_text',
			array(
				'label'     => esc_html__( 'Button Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Compare Now', 'magical-products-display' ),
				'condition' => array(
					'show_compare'    => 'yes',
					'enable_dropdown' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_link_behavior',
			array(
				'label'       => esc_html__( 'Icon Click Behavior', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'dropdown',
				'options'     => array(
					'dropdown' => esc_html__( 'Show Dropdown', 'magical-products-display' ),
					'link'     => esc_html__( 'Go to Page', 'magical-products-display' ),
				),
				'separator'   => 'before',
				'description' => esc_html__( 'What happens when clicking the icon.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Layout Section.
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => esc_html__( 'Horizontal', 'magical-products-display' ),
					'vertical'   => esc_html__( 'Vertical', 'magical-products-display' ),
				),
			)
		);

		$this->add_responsive_control(
			'alignment',
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
				'default'   => 'flex-end',
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-wc-wrapper' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'items_gap',
			array(
				'label'      => esc_html__( 'Gap Between Items', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 15,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-wc-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @since 2.0.0
	 */
	protected function register_style_controls() {
		// Icon Style Section.
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => esc_html__( 'Icons', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 60,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 22,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-wc-item i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'icon_tabs' );

		$this->start_controls_tab(
			'icon_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-wc-item i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-wc-item:hover i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Badge/Count Style Section.
		$this->start_controls_section(
			'section_badge_style',
			array(
				'label'     => esc_html__( 'Count Badge', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_count' => 'yes',
				),
			)
		);

		$this->add_control(
			'badge_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-wc-count' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-wc-count' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_size',
			array(
				'label'      => esc_html__( 'Badge Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 14,
						'max' => 30,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 18,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-wc-count' => 'min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_font_size',
			array(
				'label'      => esc_html__( 'Font Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 18,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 11,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-wc-count' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_position_top',
			array(
				'label'      => esc_html__( 'Position Top', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => -20,
						'max' => 20,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => -8,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-wc-count' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_position_right',
			array(
				'label'      => esc_html__( 'Position Right', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => -20,
						'max' => 20,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => -8,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-wc-count' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Label Style Section.
		$this->start_controls_section(
			'section_label_style',
			array(
				'label'     => esc_html__( 'Labels', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_label' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .mpd-header-wc-label',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-wc-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'label_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 5,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-wc-item' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Dropdown Style Section.
		$this->start_controls_section(
			'section_dropdown_style',
			array(
				'label'     => esc_html__( 'Dropdown', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_dropdown' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'dropdown_width',
			array(
				'label'      => esc_html__( 'Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 200,
						'max' => 500,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 300,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-wc-dropdown' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dropdown_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-wc-dropdown' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'dropdown_border',
				'selector' => '{{WRAPPER}} .mpd-header-wc-dropdown',
			)
		);

		$this->add_responsive_control(
			'dropdown_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-wc-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'dropdown_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-header-wc-dropdown',
			)
		);

		$this->add_responsive_control(
			'dropdown_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-wc-dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Button Style Section.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => esc_html__( 'Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_dropdown' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .mpd-view-all-btn',
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-view-all-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-view-all-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-view-all-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-view-all-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'separator'  => 'before',
				'default'    => array(
					'top'    => 10,
					'right'  => 20,
					'bottom' => 10,
					'left'   => 20,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-view-all-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => 4,
					'right'  => 4,
					'bottom' => 4,
					'left'   => 4,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-view-all-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-view-all-btn',
			)
		);

		$this->add_control(
			'button_full_width',
			array(
				'label'        => esc_html__( 'Full Width', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors'    => array(
					'{{WRAPPER}} .mpd-view-all-btn' => 'display: block; width: 100%; text-align: center;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @since 2.0.0
	 */
	protected function render_widget( $settings ) {
		if ( ! $this->is_pro() ) {
			$this->render_pro_message( 'Header Wishlist & Compare' );
			return;
		}

		$show_wishlist   = 'yes' === $settings['show_wishlist'];
		$show_compare    = 'yes' === $settings['show_compare'];
		$show_count      = 'yes' === $settings['show_count'];
		$show_label      = 'yes' === $settings['show_label'];
		$enable_dropdown = 'yes' === $settings['enable_dropdown'];
		$layout          = $settings['layout'];
		$icon_behavior   = isset( $settings['icon_link_behavior'] ) ? $settings['icon_link_behavior'] : 'dropdown';

		// Get link URLs.
		$wishlist_url = ! empty( $settings['wishlist_link']['url'] ) ? $settings['wishlist_link']['url'] : '#';
		$compare_url  = ! empty( $settings['compare_link']['url'] ) ? $settings['compare_link']['url'] : '#';

		// Get button text.
		$wishlist_btn_text = ! empty( $settings['wishlist_button_text'] ) ? $settings['wishlist_button_text'] : esc_html__( 'View Wishlist', 'magical-products-display' );
		$compare_btn_text  = ! empty( $settings['compare_button_text'] ) ? $settings['compare_button_text'] : esc_html__( 'Compare Now', 'magical-products-display' );

		if ( ! $show_wishlist && ! $show_compare ) {
			return;
		}

		$wrapper_classes = array(
			'mpd-header-wc-wrapper',
			'mpd-header-wc-layout--' . $layout,
		);

		if ( $enable_dropdown ) {
			$wrapper_classes[] = 'mpd-header-wc-has-dropdown';
		}

		if ( 'link' === $icon_behavior ) {
			$wrapper_classes[] = 'mpd-header-wc-icon-link';
		}
		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
			<?php if ( $show_wishlist ) : ?>
				<?php
				$wishlist_tag   = 'link' === $icon_behavior && '#' !== $wishlist_url ? 'a' : 'div';
				$wishlist_attrs = 'a' === $wishlist_tag ? ' href="' . esc_url( $wishlist_url ) . '"' : '';
				if ( ! empty( $settings['wishlist_link']['is_external'] ) ) {
					$wishlist_attrs .= ' target="_blank"';
				}
				if ( ! empty( $settings['wishlist_link']['nofollow'] ) ) {
					$wishlist_attrs .= ' rel="nofollow"';
				}
				?>
				<<?php echo esc_html( $wishlist_tag ); ?> class="mpd-header-wc-item mpd-header-wishlist" data-type="wishlist"<?php echo $wishlist_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="mpd-header-wc-icon-wrap">
						<i class="eicon-heart-o" aria-hidden="true"></i>
						<?php if ( $show_count ) : ?>
							<span class="mpd-header-wc-count mpd-wishlist-count">0</span>
						<?php endif; ?>
					</div>
					<?php if ( $show_label ) : ?>
						<span class="mpd-header-wc-label"><?php echo esc_html( $settings['wishlist_label'] ); ?></span>
					<?php endif; ?>

					<?php if ( $enable_dropdown && 'dropdown' === $icon_behavior ) : ?>
						<div class="mpd-header-wc-dropdown mpd-wishlist-dropdown">
							<div class="mpd-header-wc-dropdown-header">
								<span class="mpd-dropdown-title"><?php esc_html_e( 'My Wishlist', 'magical-products-display' ); ?></span>
								<button type="button" class="mpd-clear-wishlist" title="<?php esc_attr_e( 'Clear All', 'magical-products-display' ); ?>">
									<i class="eicon-trash-o"></i>
								</button>
							</div>
							<div class="mpd-header-wc-dropdown-content mpd-wishlist-items">
								<p class="mpd-empty-message"><?php esc_html_e( 'Your wishlist is empty.', 'magical-products-display' ); ?></p>
							</div>
							<div class="mpd-header-wc-dropdown-footer">
								<a href="<?php echo esc_url( $wishlist_url ); ?>" class="mpd-view-all-btn mpd-view-wishlist"><?php echo esc_html( $wishlist_btn_text ); ?></a>
							</div>
						</div>
					<?php endif; ?>
				</<?php echo esc_html( $wishlist_tag ); ?>>
			<?php endif; ?>

			<?php if ( $show_compare ) : ?>
				<?php
				$compare_tag   = 'link' === $icon_behavior && '#' !== $compare_url ? 'a' : 'div';
				$compare_attrs = 'a' === $compare_tag ? ' href="' . esc_url( $compare_url ) . '"' : '';
				if ( ! empty( $settings['compare_link']['is_external'] ) ) {
					$compare_attrs .= ' target="_blank"';
				}
				if ( ! empty( $settings['compare_link']['nofollow'] ) ) {
					$compare_attrs .= ' rel="nofollow"';
				}
				?>
				<<?php echo esc_html( $compare_tag ); ?> class="mpd-header-wc-item mpd-header-compare" data-type="compare"<?php echo $compare_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="mpd-header-wc-icon-wrap">
						<i class="eicon-exchange" aria-hidden="true"></i>
						<?php if ( $show_count ) : ?>
							<span class="mpd-header-wc-count mpd-compare-count">0</span>
						<?php endif; ?>
					</div>
					<?php if ( $show_label ) : ?>
						<span class="mpd-header-wc-label"><?php echo esc_html( $settings['compare_label'] ); ?></span>
					<?php endif; ?>

					<?php if ( $enable_dropdown && 'dropdown' === $icon_behavior ) : ?>
						<div class="mpd-header-wc-dropdown mpd-compare-dropdown">
							<div class="mpd-header-wc-dropdown-header">
								<span class="mpd-dropdown-title"><?php esc_html_e( 'Compare Products', 'magical-products-display' ); ?></span>
								<button type="button" class="mpd-clear-compare" title="<?php esc_attr_e( 'Clear All', 'magical-products-display' ); ?>">
									<i class="eicon-trash-o"></i>
								</button>
							</div>
							<div class="mpd-header-wc-dropdown-content mpd-compare-items">
								<p class="mpd-empty-message"><?php esc_html_e( 'No products to compare.', 'magical-products-display' ); ?></p>
							</div>
							<div class="mpd-header-wc-dropdown-footer">
								<a href="<?php echo esc_url( $compare_url ); ?>" class="mpd-view-all-btn mpd-compare-now"><?php echo esc_html( $compare_btn_text ); ?></a>
							</div>
						</div>
					<?php endif; ?>
				</<?php echo esc_html( $compare_tag ); ?>>
			<?php endif; ?>
		</div>
		<?php
	}
}
