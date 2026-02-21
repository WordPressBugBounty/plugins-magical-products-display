<?php
/**
 * Empty Cart Widget
 *
 * Displays a customizable empty cart message with CTA.
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
 * Class Empty_Cart
 *
 * @since 2.0.0
 */
class Empty_Cart extends Widget_Base {

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
	protected $widget_icon = 'eicon-cart';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-empty-cart';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Empty Cart', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-cart';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'empty', 'cart', 'message', 'cta', 'shop', 'magical-products-display' );
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
			'show_icon',
			array(
				'label'        => __( 'Show Icon', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'     => __( 'Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Your cart is empty', 'magical-products-display' ),
				'placeholder' => __( 'Your cart is empty', 'magical-products-display' ),
				'label_block' => true,
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
					'p'    => 'p',
					'div'  => 'div',
					'span' => 'span',
				),
				'default' => 'h2',
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => __( 'Description', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Looks like you haven\'t added any items to your cart yet. Browse our products and find something you like!', 'magical-products-display' ),
				'placeholder' => __( 'Add a description...', 'magical-products-display' ),
				'rows'        => 4,
			)
		);

		$this->add_responsive_control(
			'alignment',
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .mpd-empty-cart' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Button Section.
		$this->start_controls_section(
			'section_button',
			array(
				'label' => __( 'Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_button',
			array(
				'label'        => __( 'Show Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'     => __( 'Button Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Return to Shop', 'magical-products-display' ),
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'       => __( 'Button Link', 'magical-products-display' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'magical-products-display' ),
				'default'     => array(
					'url' => '',
				),
				'condition'   => array(
					'show_button' => 'yes',
				),
				'description' => __( 'Leave empty to use the shop page URL.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_icon',
			array(
				'label'     => __( 'Button Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => '',
					'library' => '',
				),
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_icon_position',
			array(
				'label'     => __( 'Icon Position', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'before' => __( 'Before Text', 'magical-products-display' ),
					'after'  => __( 'After Text', 'magical-products-display' ),
				),
				'default'   => 'before',
				'condition' => array(
					'show_button'  => 'yes',
					'button_icon[value]!' => '',
				),
			)
		);

		$this->end_controls_section();

		// Display Conditions.
		$this->start_controls_section(
			'section_display',
			array(
				'label' => __( 'Display Conditions', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_only_empty',
			array(
				'label'        => __( 'Show Only When Cart is Empty', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Hide this widget when the cart has items.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'preview_in_editor',
			array(
				'label'        => __( 'Preview in Editor', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Always show preview in the editor regardless of cart status.', 'magical-products-display' ),
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Product Suggestions & Animations', 'magical-products-display' ) );
		}
			$this->add_control(
				'show_suggestions',
				array(
					'label'        => __( 'Show Product Suggestions', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Display featured products below the empty cart message.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'suggestions_title',
				array(
					'label'       => __( 'Suggestions Title', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'You might like these', 'magical-products-display' ),
					'condition'   => array(
						'show_suggestions' => 'yes',
					),
				)
			);

			$this->add_control(
				'suggestions_count',
				array(
					'label'     => __( 'Number of Suggestions', 'magical-products-display' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => 1,
					'max'       => 8,
					'default'   => 4,
					'condition' => array(
						'show_suggestions' => 'yes',
					),
				)
			);

			$this->add_control(
				'suggestions_source',
				array(
					'label'     => __( 'Product Source', 'magical-products-display' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'featured' => __( 'Featured Products', 'magical-products-display' ),
						'recent'   => __( 'Recent Products', 'magical-products-display' ),
						'sale'     => __( 'On Sale Products', 'magical-products-display' ),
						'popular'  => __( 'Popular Products', 'magical-products-display' ),
					),
					'default'   => 'featured',
					'condition' => array(
						'show_suggestions' => 'yes',
					),
				)
			);

			$this->add_control(
				'enable_animation',
				array(
					'label'        => __( 'Enable Animation', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'separator'    => 'before',
				)
			);

			$this->add_control(
				'animation_type',
				array(
					'label'     => __( 'Animation Type', 'magical-products-display' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'fade'   => __( 'Fade In', 'magical-products-display' ),
						'slide'  => __( 'Slide Up', 'magical-products-display' ),
						'bounce' => __( 'Bounce', 'magical-products-display' ),
						'pulse'  => __( 'Pulse', 'magical-products-display' ),
					),
					'default'   => 'fade',
					'condition' => array(
						'enable_animation' => 'yes',
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
				'selector' => '{{WRAPPER}} .mpd-empty-cart',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => 40,
					'right'    => 40,
					'bottom'   => 40,
					'left'     => 40,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-empty-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-empty-cart',
			)
		);

		$this->add_responsive_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-empty-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-empty-cart',
			)
		);

		$this->end_controls_section();

		// Icon Style.
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'     => __( 'Icon', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-empty-cart-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-empty-cart-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 80,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-empty-cart-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-empty-cart-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
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
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-empty-cart-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-empty-cart-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-empty-cart-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-empty-cart-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Description Style.
		$this->start_controls_section(
			'section_description_style',
			array(
				'label' => __( 'Description', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-empty-cart-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .mpd-empty-cart-description',
			)
		);

		$this->add_responsive_control(
			'description_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-empty-cart-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'description_max_width',
			array(
				'label'      => __( 'Max Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 200,
						'max' => 800,
					),
					'%'  => array(
						'min' => 30,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-empty-cart-description' => 'max-width: {{SIZE}}{{UNIT}}; margin-left: auto; margin-right: auto;',
				),
			)
		);

		$this->end_controls_section();

		// Button Style.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => __( 'Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .mpd-empty-cart-button',
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
					'{{WRAPPER}} .mpd-empty-cart-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'selector' => '{{WRAPPER}} .mpd-empty-cart-button',
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
					'{{WRAPPER}} .mpd-empty-cart-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'selector' => '{{WRAPPER}} .mpd-empty-cart-button:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} .mpd-empty-cart-button',
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
					'{{WRAPPER}} .mpd-empty-cart-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-empty-cart-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-empty-cart-button',
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-empty-cart-button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_icon_spacing',
			array(
				'label'      => __( 'Icon Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 8,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-empty-cart-button .mpd-button-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-empty-cart-button .mpd-button-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Product Suggestions Item Style.
		if ( $this->is_pro() ) {
			$this->start_controls_section(
				'section_suggestions_item_style',
				array(
					'label'     => __( 'Product Suggestions Item', 'magical-products-display' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'show_suggestions' => 'yes',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'suggestion_item_background',
					'selector' => '{{WRAPPER}} .mpd-suggestion-product',
				)
			);

			$this->add_responsive_control(
				'suggestion_item_padding',
				array(
					'label'      => __( 'Padding', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-suggestion-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'suggestion_item_margin',
				array(
					'label'      => __( 'Margin', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-suggestion-product' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'suggestion_item_border',
					'selector' => '{{WRAPPER}} .mpd-suggestion-product',
				)
			);

			$this->add_responsive_control(
				'suggestion_item_border_radius',
				array(
					'label'      => __( 'Border Radius', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-suggestion-product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'suggestion_item_box_shadow',
					'selector' => '{{WRAPPER}} .mpd-suggestion-product',
				)
			);

			$this->add_control(
				'suggestion_title_heading',
				array(
					'label'     => __( 'Product Title', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'suggestion_title_color',
				array(
					'label'     => __( 'Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-suggestion-product .mpd-suggestion-title' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'suggestion_title_typography',
					'selector' => '{{WRAPPER}} .mpd-suggestion-product .mpd-suggestion-title',
				)
			);

			$this->add_responsive_control(
				'suggestion_title_margin',
				array(
					'label'      => __( 'Margin', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-suggestion-product .mpd-suggestion-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'suggestion_price_heading',
				array(
					'label'     => __( 'Product Price', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'suggestion_price_color',
				array(
					'label'     => __( 'Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-suggestion-product .mpd-suggestion-price' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'suggestion_price_typography',
					'selector' => '{{WRAPPER}} .mpd-suggestion-product .mpd-suggestion-price',
				)
			);

			$this->add_control(
				'suggestion_image_heading',
				array(
					'label'     => __( 'Product Image', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'suggestion_image_border_radius',
				array(
					'label'      => __( 'Border Radius', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-suggestion-product img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'suggestion_image_spacing',
				array(
					'label'      => __( 'Spacing', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-suggestion-product img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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

		$cart          = WC()->cart;
		$is_cart_empty = ! $cart || $cart->is_empty();
		$show_only_empty = 'yes' === $settings['show_only_empty'];
		$preview_in_editor = 'yes' === $settings['preview_in_editor'];

		// Determine whether to show the widget.
		$should_show = false;

		if ( $this->is_editor_mode() && $preview_in_editor ) {
			$should_show = true;
		} elseif ( $is_cart_empty ) {
			$should_show = true;
		} elseif ( ! $show_only_empty ) {
			$should_show = true;
		}

		if ( ! $should_show ) {
			return;
		}

		$this->render_empty_cart( $settings );
	}

	/**
	 * Render empty cart content.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_empty_cart( $settings ) {
		$show_icon     = 'yes' === $settings['show_icon'];
		$title         = $settings['title'];
		$title_tag     = $settings['title_tag'];
		$description   = $settings['description'];
		$show_button   = 'yes' === $settings['show_button'];
		$button_text   = $settings['button_text'];

		// Pro features.
		$enable_animation = $this->is_pro() && isset( $settings['enable_animation'] ) && 'yes' === $settings['enable_animation'];
		$animation_type   = $this->is_pro() && isset( $settings['animation_type'] ) ? $settings['animation_type'] : 'fade';
		$show_suggestions = $this->is_pro() && isset( $settings['show_suggestions'] ) && 'yes' === $settings['show_suggestions'];

		$wrapper_class = 'mpd-empty-cart';
		if ( $enable_animation ) {
			$wrapper_class .= ' mpd-animation-' . $animation_type;
		}

		// Get button URL.
		$button_url = '#';
		if ( ! empty( $settings['button_link']['url'] ) ) {
			$button_url = $settings['button_link']['url'];
		} elseif ( function_exists( 'wc_get_page_permalink' ) ) {
			$button_url = wc_get_page_permalink( 'shop' );
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<?php if ( $show_icon ) : ?>
				<div class="mpd-empty-cart-icon">
					<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $title ) ) : ?>
				<<?php echo esc_attr( $title_tag ); ?> class="mpd-empty-cart-title">
					<?php echo esc_html( $title ); ?>
				</<?php echo esc_attr( $title_tag ); ?>>
			<?php endif; ?>

			<?php if ( ! empty( $description ) ) : ?>
				<p class="mpd-empty-cart-description"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>

			<?php if ( $show_button && ! empty( $button_text ) ) : ?>
				<?php
				$this->add_render_attribute( 'button', 'class', 'mpd-empty-cart-button' );
				$this->add_render_attribute( 'button', 'href', esc_url( $button_url ) );

				if ( ! empty( $settings['button_link']['is_external'] ) ) {
					$this->add_render_attribute( 'button', 'target', '_blank' );
				}

				if ( ! empty( $settings['button_link']['nofollow'] ) ) {
					$this->add_render_attribute( 'button', 'rel', 'nofollow' );
				}
				?>
				<div class="mpd-empty-cart-button-wrapper">
					<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'button' ) ); ?>>
						<?php if ( ! empty( $settings['button_icon']['value'] ) && 'before' === $settings['button_icon_position'] ) : ?>
							<span class="mpd-button-icon mpd-button-icon-before">
								<?php Icons_Manager::render_icon( $settings['button_icon'], array( 'aria-hidden' => 'true' ) ); ?>
							</span>
						<?php endif; ?>
						<span class="mpd-button-text"><?php echo esc_html( $button_text ); ?></span>
						<?php if ( ! empty( $settings['button_icon']['value'] ) && 'after' === $settings['button_icon_position'] ) : ?>
							<span class="mpd-button-icon mpd-button-icon-after">
								<?php Icons_Manager::render_icon( $settings['button_icon'], array( 'aria-hidden' => 'true' ) ); ?>
							</span>
						<?php endif; ?>
					</a>
				</div>
			<?php endif; ?>

			<?php
			// Product suggestions (Pro).
			if ( $show_suggestions ) {
				$this->render_product_suggestions( $settings );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render product suggestions.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_product_suggestions( $settings ) {
		$title  = isset( $settings['suggestions_title'] ) ? $settings['suggestions_title'] : '';
		$count  = isset( $settings['suggestions_count'] ) ? absint( $settings['suggestions_count'] ) : 4;
		$source = isset( $settings['suggestions_source'] ) ? $settings['suggestions_source'] : 'featured';

		$query_args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $count,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		// Adjust query based on source.
		switch ( $source ) {
			case 'featured':
				$query_args['tax_query'] = array(
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
						'operator' => 'IN',
					),
				);
				break;

			case 'sale':
				$product_ids_on_sale = wc_get_product_ids_on_sale();
				$query_args['post__in'] = ! empty( $product_ids_on_sale ) ? $product_ids_on_sale : array( 0 );
				break;

			case 'popular':
				$query_args['meta_key'] = 'total_sales';
				$query_args['orderby']  = 'meta_value_num';
				break;

			case 'recent':
			default:
				// Default ordering by date is already set.
				break;
		}

		$products = new \WP_Query( $query_args );

		if ( ! $products->have_posts() ) {
			return;
		}
		?>
		<div class="mpd-empty-cart-suggestions">
			<?php if ( ! empty( $title ) ) : ?>
				<h3 class="mpd-suggestions-title"><?php echo esc_html( $title ); ?></h3>
			<?php endif; ?>

			<div class="mpd-suggestions-products">
				<?php
				while ( $products->have_posts() ) :
					$products->the_post();
					global $product;

					if ( ! $product || ! $product instanceof \WC_Product ) {
						continue;
					}
					?>
					<div class="mpd-suggestion-product">
						<a href="<?php the_permalink(); ?>">
							<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
							<h4 class="mpd-suggestion-title"><?php the_title(); ?></h4>
							<span class="mpd-suggestion-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
						</a>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
		<?php
		wp_reset_postdata();
	}
}
