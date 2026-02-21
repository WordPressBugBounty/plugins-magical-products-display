<?php
/**
 * Orders List Widget
 *
 * Displays the WooCommerce customer orders list with filtering and search.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\MyAccount;

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
 * Class Orders
 *
 * @since 2.0.0
 */
class Orders extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_MY_ACCOUNT;

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	protected $widget_icon = 'eicon-product-pages';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-account-orders';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Orders List', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'orders', 'list', 'my account', 'woocommerce', 'user', 'history' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-my-account-widgets' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'mpd-my-account-widgets' );
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
				'label' => esc_html__( 'Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'section_title',
			array(
				'label'       => esc_html__( 'Section Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'My Orders', 'magical-products-display' ),
				'placeholder' => esc_html__( 'Enter section title', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Show Section Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'orders_per_page',
			array(
				'label'   => esc_html__( 'Orders Per Page', 'magical-products-display' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 10,
				'min'     => 1,
				'max'     => 50,
			)
		);

		$this->add_control(
			'show_order_number',
			array(
				'label'        => esc_html__( 'Show Order Number', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_order_date',
			array(
				'label'        => esc_html__( 'Show Order Date', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_order_status',
			array(
				'label'        => esc_html__( 'Show Order Status', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_order_total',
			array(
				'label'        => esc_html__( 'Show Order Total', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_actions',
			array(
				'label'        => esc_html__( 'Show Actions', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Filtering Section (Pro).
		$this->start_controls_section(
			'section_filtering',
			array(
				'label' => esc_html__( 'Filtering', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'filtering_pro',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Filtering and search options are Pro features.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'show_search',
			array(
				'label'        => esc_html__( 'Show Search', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_status_filter',
			array(
				'label'        => esc_html__( 'Show Status Filter', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_date_filter',
			array(
				'label'        => esc_html__( 'Show Date Filter', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->end_controls_section();

		// Empty State Section.
		$this->start_controls_section(
			'section_empty_state',
			array(
				'label' => esc_html__( 'Empty State', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'empty_message',
			array(
				'label'   => esc_html__( 'Empty Message', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'No orders have been made yet.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_shop_button',
			array(
				'label'        => esc_html__( 'Show Shop Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'shop_button_text',
			array(
				'label'     => esc_html__( 'Shop Button Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Browse Products', 'magical-products-display' ),
				'condition' => array(
					'show_shop_button' => 'yes',
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
		// Title Style Section.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => esc_html__( 'Title', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-orders__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-orders__title',
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-orders__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Table Style Section.
		$this->start_controls_section(
			'section_table_style',
			array(
				'label' => esc_html__( 'Table', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'table_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-orders__table',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'table_border',
				'selector' => '{{WRAPPER}} .mpd-orders__table',
			)
		);

		$this->add_responsive_control(
			'table_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-orders__table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'table_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-orders__table',
			)
		);

		$this->end_controls_section();

		// Table Header Style Section.
		$this->start_controls_section(
			'section_table_header_style',
			array(
				'label' => esc_html__( 'Table Header', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'header_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-orders__table thead th' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'header_typography',
				'selector' => '{{WRAPPER}} .mpd-orders__table thead th',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'header_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-orders__table thead th',
			)
		);

		$this->add_responsive_control(
			'header_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-orders__table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Table Row Style Section.
		$this->start_controls_section(
			'section_table_row_style',
			array(
				'label' => esc_html__( 'Table Rows', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'row_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-orders__table tbody td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'row_typography',
				'selector' => '{{WRAPPER}} .mpd-orders__table tbody td',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'row_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-orders__table tbody tr',
			)
		);

		$this->add_control(
			'row_alternate_background',
			array(
				'label'     => esc_html__( 'Alternate Row Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-orders__table tbody tr:nth-child(even)' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'row_hover_background',
			array(
				'label'     => esc_html__( 'Hover Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-orders__table tbody tr:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'row_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-orders__table tbody td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'row_border',
				'selector' => '{{WRAPPER}} .mpd-orders__table tbody td',
			)
		);

		$this->end_controls_section();

		// Status Badge Style Section.
		$this->start_controls_section(
			'section_status_style',
			array(
				'label'     => esc_html__( 'Status Badge', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_order_status' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'status_typography',
				'selector' => '{{WRAPPER}} .mpd-order-status',
			)
		);

		$this->add_responsive_control(
			'status_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-status' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'status_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-status' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Button Style Section.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => esc_html__( 'Buttons', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_actions' => 'yes',
				),
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
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-orders__action' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-orders__action',
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
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-orders__action:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-orders__action:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .mpd-orders__action',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-orders__action',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-orders__action' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-orders__action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Filter Style Section.
		$this->start_controls_section(
			'section_filter_style',
			array(
				'label' => esc_html__( 'Filters', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'filters_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-orders__filters',
			)
		);

		$this->add_responsive_control(
			'filters_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-orders__filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filters_gap',
			array(
				'label'      => esc_html__( 'Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-orders__filters' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filters_margin_bottom',
			array(
				'label'      => esc_html__( 'Margin Bottom', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-orders__filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'filters_border',
				'selector' => '{{WRAPPER}} .mpd-orders__filters',
			)
		);

		$this->add_responsive_control(
			'filters_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-orders__filters' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'filter_input_heading',
			array(
				'label'     => esc_html__( 'Input Fields', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'filter_input_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-orders__filters input,
					 {{WRAPPER}} .mpd-orders__filters select' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_input_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-orders__filters input,
					 {{WRAPPER}} .mpd-orders__filters select' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_input_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-orders__filters input,
					 {{WRAPPER}} .mpd-orders__filters select' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filter_input_focus_border_color',
			array(
				'label'     => esc_html__( 'Focus Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-orders__filters input:focus,
					 {{WRAPPER}} .mpd-orders__filters select:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_input_typography',
				'selector' => '{{WRAPPER}} .mpd-orders__filters input, {{WRAPPER}} .mpd-orders__filters select',
			)
		);

		$this->add_responsive_control(
			'filter_input_padding',
			array(
				'label'      => esc_html__( 'Input Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-orders__filters input,
					 {{WRAPPER}} .mpd-orders__filters select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_input_border_radius',
			array(
				'label'      => esc_html__( 'Input Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-orders__filters input,
					 {{WRAPPER}} .mpd-orders__filters select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Empty State Style Section.
		$this->start_controls_section(
			'section_empty_style',
			array(
				'label' => esc_html__( 'Empty State', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'empty_message_color',
			array(
				'label'     => esc_html__( 'Message Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-orders__empty-message' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'empty_message_typography',
				'selector' => '{{WRAPPER}} .mpd-orders__empty-message',
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
					'{{WRAPPER}} .mpd-orders__empty' => 'text-align: {{VALUE}};',
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
		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			// In editor mode, show placeholder.
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				$this->render_editor_placeholder(
					esc_html__( 'Orders List', 'magical-products-display' ),
					esc_html__( 'This widget displays orders for logged-in users.', 'magical-products-display' )
				);
			}
			return;
		}

		// Only show on orders endpoint (skip in editor for preview).
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() && ! is_wc_endpoint_url( 'orders' ) && ! is_wc_endpoint_url( 'view-order' ) ) {
			return;
		}

		// Get current page.
		$current_page = isset( $_GET['orders-page'] ) ? absint( wp_unslash( $_GET['orders-page'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		// Get customer orders.
		$customer_orders = wc_get_orders(
			array(
				'customer_id' => get_current_user_id(),
				'limit'       => absint( $settings['orders_per_page'] ),
				'paged'       => $current_page,
				'paginate'    => true,
				'orderby'     => 'date',
				'order'       => 'DESC',
			)
		);
		?>
		<div class="mpd-orders">
			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['section_title'] ) ) : ?>
				<h3 class="mpd-orders__title"><?php echo esc_html( $settings['section_title'] ); ?></h3>
			<?php endif; ?>

			<?php
			// Pro feature: Filtering.
			if ( $this->is_pro() && ( 'yes' === $settings['show_search'] || 'yes' === $settings['show_status_filter'] || 'yes' === $settings['show_date_filter'] ) ) {
				$this->render_filters( $settings );
			}

			if ( empty( $customer_orders->orders ) ) {
				$this->render_empty_state( $settings );
			} else {
				$this->render_orders_table( $customer_orders->orders, $settings );
				$this->render_pagination( $customer_orders, $current_page, $settings );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render filters.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_filters( $settings ) {
		?>
		<div class="mpd-orders__filters">
			<?php if ( 'yes' === $settings['show_search'] ) : ?>
				<div class="mpd-orders__search">
					<input type="text" class="mpd-orders__search-input" placeholder="<?php esc_attr_e( 'Search orders...', 'magical-products-display' ); ?>">
				</div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_status_filter'] ) : ?>
				<div class="mpd-orders__status-filter">
					<select class="mpd-orders__status-select">
						<option value=""><?php esc_html_e( 'All Statuses', 'magical-products-display' ); ?></option>
						<?php foreach ( wc_get_order_statuses() as $status => $label ) : ?>
							<option value="<?php echo esc_attr( $status ); ?>"><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_date_filter'] ) : ?>
				<div class="mpd-orders__date-filter">
					<input type="date" class="mpd-orders__date-from" placeholder="<?php esc_attr_e( 'From', 'magical-products-display' ); ?>">
					<input type="date" class="mpd-orders__date-to" placeholder="<?php esc_attr_e( 'To', 'magical-products-display' ); ?>">
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render orders table.
	 *
	 * @since 2.0.0
	 *
	 * @param array $orders   Orders.
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_orders_table( $orders, $settings ) {
		?>
		<table class="mpd-orders__table woocommerce-orders-table shop_table">
			<thead>
				<tr>
					<?php if ( 'yes' === $settings['show_order_number'] ) : ?>
						<th><?php esc_html_e( 'Order', 'magical-products-display' ); ?></th>
					<?php endif; ?>
					<?php if ( 'yes' === $settings['show_order_date'] ) : ?>
						<th><?php esc_html_e( 'Date', 'magical-products-display' ); ?></th>
					<?php endif; ?>
					<?php if ( 'yes' === $settings['show_order_status'] ) : ?>
						<th><?php esc_html_e( 'Status', 'magical-products-display' ); ?></th>
					<?php endif; ?>
					<?php if ( 'yes' === $settings['show_order_total'] ) : ?>
						<th><?php esc_html_e( 'Total', 'magical-products-display' ); ?></th>
					<?php endif; ?>
					<?php if ( 'yes' === $settings['show_actions'] ) : ?>
						<th><?php esc_html_e( 'Actions', 'magical-products-display' ); ?></th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $orders as $order ) : ?>
					<tr>
						<?php if ( 'yes' === $settings['show_order_number'] ) : ?>
							<td>
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									#<?php echo esc_html( $order->get_order_number() ); ?>
								</a>
							</td>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_order_date'] ) : ?>
							<td><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></td>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_order_status'] ) : ?>
							<td>
								<span class="mpd-order-status mpd-order-status--<?php echo esc_attr( $order->get_status() ); ?>">
									<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
								</span>
							</td>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_order_total'] ) : ?>
							<td><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></td>
						<?php endif; ?>
						<?php if ( 'yes' === $settings['show_actions'] ) : ?>
							<td>
								<?php
								$actions = wc_get_account_orders_actions( $order );
								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) {
										echo '<a href="' . esc_url( $action['url'] ) . '" class="mpd-orders__action mpd-orders__action--' . esc_attr( $key ) . ' button">' . esc_html( $action['name'] ) . '</a> ';
									}
								}
								?>
							</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Render pagination.
	 *
	 * @since 2.0.0
	 *
	 * @param object $orders       Orders result.
	 * @param int    $current_page Current page.
	 * @param array  $settings     Widget settings.
	 * @return void
	 */
	protected function render_pagination( $orders, $current_page, $settings ) {
		$total_pages = $orders->max_num_pages;

		if ( $total_pages <= 1 ) {
			return;
		}
		?>
		<nav class="mpd-orders__pagination woocommerce-pagination">
			<?php
			echo wp_kses_post(
				paginate_links(
					array(
						'base'      => esc_url( add_query_arg( 'orders-page', '%#%' ) ),
						'format'    => '',
						'current'   => $current_page,
						'total'     => $total_pages,
						'prev_text' => '&larr;',
						'next_text' => '&rarr;',
						'type'      => 'list',
					)
				)
			);
			?>
		</nav>
		<?php
	}

	/**
	 * Render empty state.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_empty_state( $settings ) {
		?>
		<div class="mpd-orders__empty">
			<p class="mpd-orders__empty-message"><?php echo esc_html( $settings['empty_message'] ); ?></p>
			<?php if ( 'yes' === $settings['show_shop_button'] ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button mpd-orders__shop-button">
					<?php echo esc_html( $settings['shop_button_text'] ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}
}
