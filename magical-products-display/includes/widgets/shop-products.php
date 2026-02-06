<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class mgProducts_Shop extends \Elementor\Widget_Base
{
	use mpdProHelpLink;

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name()
	{
		return 'mg_shop_products';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title()
	{
		return __('MPD Shop Products', 'magical-products-display');
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon()
	{
		return 'eicon-products';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_categories()
	{
		return ['mpd-productwoo'];
	}

	public function get_keywords()
	{
		return ['mpd', 'woo', 'shop', 'product', 'shortcode', 'ecommerce'];
	}

	/**
	 * Retrieve the list of styles the widget depended on.
	 *
	 * @access public
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends()
	{
		return [
			'bootstrap-grid',
		];
	}

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls()
	{
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	function register_content_controls()
	{
		// Products Query Section
		$this->start_controls_section(
			'mgpshop_query',
			[
				'label' => esc_html__('Products Query', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_filter_type',
			[
				'label' => esc_html__('Filter Type', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__('None (Default)', 'magical-products-display'),
					'on_sale' => esc_html__('On Sale Products', 'magical-products-display'),
					'best_selling' => esc_html__('Best Selling Products', 'magical-products-display'),
					'top_rated' => esc_html__('Top Rated Products', 'magical-products-display'),
				],
				'description' => esc_html__('Special product filters. Cannot be used together with other filters.', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_limit',
			[
				'label' => __('Products Limit', 'magical-products-display'),
				'description' => esc_html__('Number of products to display (-1 for all)', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 8,
				'min' => -1,
				'step' => 1,
			]
		);

		$this->add_control(
			'mgpshop_columns',
			[
				'label' => __('Columns', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 1,
				'max' => 6,
				'step' => 1,
			]
		);

		$this->add_control(
			'mgpshop_paginate',
			[
				'label' => esc_html__('Enable Pagination', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => esc_html__('Yes', 'magical-products-display'),
				'label_off' => esc_html__('No', 'magical-products-display'),
				'description' => esc_html__('Enable pagination for products', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_orderby',
			[
				'label' => esc_html__('Order By', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'menu_order',
				'options' => [
					'title' => esc_html__('Title', 'magical-products-display'),
					'date' => esc_html__('Date', 'magical-products-display'),
					'id' => esc_html__('ID', 'magical-products-display'),
					'menu_order' => esc_html__('Menu Order', 'magical-products-display'),
					'popularity' => esc_html__('Popularity', 'magical-products-display'),
					'rand' => esc_html__('Random', 'magical-products-display'),
					'rating' => esc_html__('Rating', 'magical-products-display'),
				],
			]
		);

		$this->add_control(
			'mgpshop_order',
			[
				'label' => esc_html__('Order', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'ASC',
				'options' => [
					'ASC' => esc_html__('Ascending', 'magical-products-display'),
					'DESC' => esc_html__('Descending', 'magical-products-display'),
				],
			]
		);
		$this->add_control(
			'mgpshop_editor_notice',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<div class="elementor-panel-alert elementor-panel-alert-warning">' . esc_html__('⚠️ Products may not display properly in editor mode, but will show perfectly on the frontend.', 'magical-products-display') . '</div>',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
		$this->end_controls_section();

		// Product Selection Section
		$this->start_controls_section(
			'mgpshop_selection',
			[
				'label' => esc_html__('Product Selection', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_ids',
			[
				'label' => __('Product IDs', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => '123, 456, 789',
				'description' => esc_html__('Comma-separated list of product IDs', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_skus',
			[
				'label' => __('Product SKUs', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'SKU1, SKU2, SKU3',
				'description' => esc_html__('Comma-separated list of product SKUs', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_visibility',
			[
				'label' => esc_html__('Product Visibility', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'visible',
				'options' => [
					'visible' => esc_html__('Visible (Shop & Search)', 'magical-products-display'),
					'catalog' => esc_html__('Catalog Only', 'magical-products-display'),
					'search' => esc_html__('Search Only', 'magical-products-display'),
					'hidden' => esc_html__('Hidden', 'magical-products-display'),
					'featured' => esc_html__('Featured', 'magical-products-display'),
				],
			]
		);

		$this->end_controls_section();

		// Categories & Tags Section
		$this->start_controls_section(
			'mgpshop_taxonomy',
			[
				'label' => esc_html__('Categories & Tags', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_category',
			[
				'label' => esc_html__('Product Categories', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => mgproducts_display_taxonomy_list(),
				'description' => esc_html__('Select product categories', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_cat_operator',
			[
				'label' => esc_html__('Category Operator', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'IN',
				'options' => [
					'IN' => esc_html__('IN (Products in selected categories)', 'magical-products-display'),
					'NOT IN' => esc_html__('NOT IN (Exclude selected categories)', 'magical-products-display'),
					'AND' => esc_html__('AND (Products in all selected categories)', 'magical-products-display'),
				],
				'condition' => [
					'mgpshop_category!' => '',
				],
			]
		);

		$this->add_control(
			'mgpshop_tag',
			[
				'label' => __('Product Tags', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'tag1, tag2, tag3',
				'description' => esc_html__('Comma-separated list of tag slugs', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_tag_operator',
			[
				'label' => esc_html__('Tag Operator', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'IN',
				'options' => [
					'IN' => esc_html__('IN (Products with selected tags)', 'magical-products-display'),
					'NOT IN' => esc_html__('NOT IN (Exclude selected tags)', 'magical-products-display'),
					'AND' => esc_html__('AND (Products with all selected tags)', 'magical-products-display'),
				],
				'condition' => [
					'mgpshop_tag!' => '',
				],
			]
		);

		$this->end_controls_section();

		// Product Attributes Section
		$this->start_controls_section(
			'mgpshop_attributes',
			[
				'label' => esc_html__('Product Attributes', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_attribute',
			[
				'label' => __('Attribute Slug', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'color',
				'description' => esc_html__('Enter attribute slug (e.g., color, size)', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_terms',
			[
				'label' => __('Attribute Terms', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'red, blue, green',
				'description' => esc_html__('Comma-separated list of attribute terms', 'magical-products-display'),
				'condition' => [
					'mgpshop_attribute!' => '',
				],
			]
		);

		$this->add_control(
			'mgpshop_terms_operator',
			[
				'label' => esc_html__('Terms Operator', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'IN',
				'options' => [
					'IN' => esc_html__('IN (Products with selected terms)', 'magical-products-display'),
					'NOT IN' => esc_html__('NOT IN (Exclude selected terms)', 'magical-products-display'),
					'AND' => esc_html__('AND (Products with all selected terms)', 'magical-products-display'),
				],
				'condition' => [
					'mgpshop_attribute!' => '',
				],
			]
		);

		$this->end_controls_section();

		// Display Settings Section
		$this->start_controls_section(
			'mgpshop_display',
			[
				'label' => esc_html__('Display Settings', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_class',
			[
				'label' => __('Custom CSS Class', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'description' => esc_html__('Add custom CSS class to wrapper', 'magical-products-display'),
			]
		);

		$this->add_responsive_control(
			'mgpshop_align',
			[
				'label' => __('Alignment', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'magical-products-display'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'magical-products-display'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __('Right', 'magical-products-display'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	function register_style_controls()
	{
		// Container Style
		$this->start_controls_section(
			'mgpshop_container_style',
			[
				'label' => esc_html__('Container', 'magical-products-display'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'mgpshop_container_padding',
			[
				'label' => __('Padding', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mgpshop_container_margin',
			[
				'label' => __('Margin', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'mgpshop_container_bg',
			[
				'label' => __('Background Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'mgpshop_container_border',
				'selector' => '{{WRAPPER}} .mgpshop-products-wrapper',
			]
		);

		$this->add_responsive_control(
			'mgpshop_container_radius',
			[
				'label' => __('Border Radius', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'mgpshop_container_shadow',
				'selector' => '{{WRAPPER}} .mgpshop-products-wrapper',
			]
		);

		$this->end_controls_section();

		// Products Style
		$this->start_controls_section(
			'mgpshop_products_style',
			[
				'label' => esc_html__('Products', 'magical-products-display'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'mgpshop_products_spacing',
			[
				'label' => __('Products Spacing', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'mgpshop_product_bg',
			[
				'label' => __('Product Background', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'mgpshop_product_border',
				'selector' => '{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product',
			]
		);

		$this->add_responsive_control(
			'mgpshop_product_padding',
			[
				'label' => __('Product Padding', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mgpshop_product_radius',
			[
				'label' => __('Product Border Radius', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'mgpshop_product_shadow',
				'selector' => '{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product',
			]
		);

		$this->end_controls_section();

		// Title Style
		$this->start_controls_section(
			'mgpshop_title_style',
			[
				'label' => esc_html__('Product Title', 'magical-products-display'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'mgpshop_title_color',
			[
				'label' => __('Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mgpshop_title_hover_color',
			[
				'label' => __('Hover Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .woocommerce-loop-product__title:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'mgpshop_title_typography',
				'selector' => '{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .woocommerce-loop-product__title',
			]
		);

		$this->add_responsive_control(
			'mgpshop_title_margin',
			[
				'label' => __('Margin', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Price Style
		$this->start_controls_section(
			'mgpshop_price_style',
			[
				'label' => esc_html__('Product Price', 'magical-products-display'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'mgpshop_price_color',
			[
				'label' => __('Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'mgpshop_price_typography',
				'selector' => '{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .price',
			]
		);

		$this->add_responsive_control(
			'mgpshop_price_margin',
			[
				'label' => __('Margin', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Button Style
		$this->start_controls_section(
			'mgpshop_button_style',
			[
				'label' => esc_html__('Add to Cart Button', 'magical-products-display'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('mgpshop_button_tabs');

		$this->start_controls_tab(
			'mgpshop_button_normal',
			[
				'label' => __('Normal', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_button_color',
			[
				'label' => __('Text Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mgpshop_button_bg',
			[
				'label' => __('Background Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'mgpshop_button_hover',
			[
				'label' => __('Hover', 'magical-products-display'),
			]
		);

		$this->add_control(
			'mgpshop_button_hover_color',
			[
				'label' => __('Text Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'mgpshop_button_hover_bg',
			[
				'label' => __('Background Color', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'mgpshop_button_typography',
				'selector' => '{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .button',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'mgpshop_button_border',
				'selector' => '{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .button',
			]
		);

		$this->add_responsive_control(
			'mgpshop_button_radius',
			[
				'label' => __('Border Radius', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'mgpshop_button_padding',
			[
				'label' => __('Padding', 'magical-products-display'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors' => [
					'{{WRAPPER}} .mgpshop-products-wrapper ul.products li.product .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();

		// Build shortcode attributes array
		$shortcode_atts = [];

		// Limit
		if (!empty($settings['mgpshop_limit'])) {
			$shortcode_atts['limit'] = $settings['mgpshop_limit'];
		}

		// Columns
		if (!empty($settings['mgpshop_columns'])) {
			$shortcode_atts['columns'] = $settings['mgpshop_columns'];
		}

		// Pagination
		if ($settings['mgpshop_paginate'] === 'yes') {
			$shortcode_atts['paginate'] = 'true';
		}

		// Order By
		if (!empty($settings['mgpshop_orderby'])) {
			$shortcode_atts['orderby'] = $settings['mgpshop_orderby'];
		}

		// Order
		if (!empty($settings['mgpshop_order'])) {
			$shortcode_atts['order'] = $settings['mgpshop_order'];
		}

		// Special Filters (mutually exclusive)
		if (!empty($settings['mgpshop_filter_type']) && $settings['mgpshop_filter_type'] !== 'none') {
			switch ($settings['mgpshop_filter_type']) {
				case 'on_sale':
					$shortcode_atts['on_sale'] = 'true';
					break;
				case 'best_selling':
					$shortcode_atts['best_selling'] = 'true';
					break;
				case 'top_rated':
					$shortcode_atts['top_rated'] = 'true';
					break;
			}
		}

		// Product IDs
		if (!empty($settings['mgpshop_ids'])) {
			$shortcode_atts['ids'] = $settings['mgpshop_ids'];
		}

		// Product SKUs
		if (!empty($settings['mgpshop_skus'])) {
			$shortcode_atts['skus'] = $settings['mgpshop_skus'];
		}

		// Categories
		if (!empty($settings['mgpshop_category'])) {
			$categories = is_array($settings['mgpshop_category']) 
				? implode(',', $settings['mgpshop_category']) 
				: $settings['mgpshop_category'];
			$shortcode_atts['category'] = $categories;
		}

		// Category Operator
		if (!empty($settings['mgpshop_cat_operator']) && !empty($settings['mgpshop_category'])) {
			$shortcode_atts['cat_operator'] = $settings['mgpshop_cat_operator'];
		}

		// Tags
		if (!empty($settings['mgpshop_tag'])) {
			$shortcode_atts['tag'] = $settings['mgpshop_tag'];
		}

		// Tag Operator
		if (!empty($settings['mgpshop_tag_operator']) && !empty($settings['mgpshop_tag'])) {
			$shortcode_atts['tag_operator'] = $settings['mgpshop_tag_operator'];
		}

		// Attribute
		if (!empty($settings['mgpshop_attribute'])) {
			$shortcode_atts['attribute'] = $settings['mgpshop_attribute'];
		}

		// Attribute Terms
		if (!empty($settings['mgpshop_terms']) && !empty($settings['mgpshop_attribute'])) {
			$shortcode_atts['terms'] = $settings['mgpshop_terms'];
		}

		// Terms Operator
		if (!empty($settings['mgpshop_terms_operator']) && !empty($settings['mgpshop_attribute'])) {
			$shortcode_atts['terms_operator'] = $settings['mgpshop_terms_operator'];
		}

		// Visibility
		if (!empty($settings['mgpshop_visibility']) && $settings['mgpshop_visibility'] !== 'visible') {
			$shortcode_atts['visibility'] = $settings['mgpshop_visibility'];
		}

		// Custom CSS Class
		if (!empty($settings['mgpshop_class'])) {
			$shortcode_atts['class'] = $settings['mgpshop_class'];
		}

		// Build shortcode string
		$shortcode_string = '[products';
		foreach ($shortcode_atts as $key => $value) {
			$shortcode_string .= ' ' . $key . '="' . esc_attr($value) . '"';
		}
		$shortcode_string .= ']';

		// Remove WooCommerce result count and ordering
		remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
		remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

		// Output wrapper
		?>
		<div class="mgpshop-products-wrapper">
			<?php
			// Execute WooCommerce shortcode
			echo do_shortcode($shortcode_string);
			?>
		</div>
		<?php

		// Restore WooCommerce result count and ordering for other areas
		add_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
		add_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
	}


}
