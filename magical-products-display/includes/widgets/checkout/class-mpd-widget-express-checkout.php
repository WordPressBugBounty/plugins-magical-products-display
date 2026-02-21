<?php
/**
 * Express Checkout Widget
 *
 * Standalone widget for express payment methods (PayPal, Apple Pay, Google Pay, etc.)
 * Can be used independently or combined with Classic/Multi-Step checkout.
 *
 * @package Magical_Products_Display
 * @since 2.6.0
 */

namespace MPD\MagicalShopBuilder\Widgets\Checkout;

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
 * Express Checkout Widget Class
 */
class Express_Checkout extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-express-checkout';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Express Checkout', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-flash';
	}


	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_CART_CHECKOUT;

	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array(
			'express',
			'checkout',
			'paypal',
			'apple pay',
			'google pay',
			'stripe',
			'payment',
			'quick',
			'fast',
			'woocommerce',
		);
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-express-checkout' );
	}

	/**
	 * Register content controls.
	 */
	protected function register_content_controls() {
		// General Settings Section.
		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General Settings', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_express_checkout',
			array(
				'label'        => __( 'Show Express Checkout', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'display_context',
			array(
				'label'       => __( 'Display Context', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'checkout',
				'options'     => array(
					'checkout' => __( 'Checkout Page', 'magical-products-display' ),
					'cart'     => __( 'Cart Page', 'magical-products-display' ),
					'product'  => __( 'Product Page (Buy Now)', 'magical-products-display' ),
					'anywhere' => __( 'Anywhere (Sidebar/Popup)', 'magical-products-display' ),
				),
				'description' => __( 'Select where this widget will be placed. Different contexts use different payment gateway hooks.', 'magical-products-display' ),
				'condition'   => array(
					'show_express_checkout' => 'yes',
				),
			)
		);

		$this->add_control(
			'context_info',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<div class="elementor-control-field-description">' .
					'<strong>' . __( 'Context Guide:', 'magical-products-display' ) . '</strong><br>' .
					__( '• Checkout: Standard checkout page', 'magical-products-display' ) . '<br>' .
					__( '• Cart: Cart page before proceed to checkout', 'magical-products-display' ) . '<br>' .
					__( '• Product: Single product "Buy Now" button', 'magical-products-display' ) . '<br>' .
					__( '• Anywhere: Sidebar, popup, or custom locations', 'magical-products-display' ) .
					'</div>',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					'show_express_checkout' => 'yes',
				),
			)
		);

		$this->add_control(
			'express_title',
			array(
				'label'       => __( 'Section Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Express Checkout', 'magical-products-display' ),
				'placeholder' => __( 'Enter title', 'magical-products-display' ),
				'condition'   => array(
					'show_express_checkout' => 'yes',
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
				'condition'    => array(
					'show_express_checkout' => 'yes',
				),
			)
		);

		$this->add_control(
			'express_description',
			array(
				'label'       => __( 'Description', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Pay quickly with your saved payment methods.', 'magical-products-display' ),
				'placeholder' => __( 'Enter description', 'magical-products-display' ),
				'rows'        => 2,
				'condition'   => array(
					'show_express_checkout' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_description',
			array(
				'label'        => __( 'Show Description', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'show_express_checkout' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_layout',
			array(
				'label'     => __( 'Button Layout', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'horizontal',
				'options'   => array(
					'horizontal' => __( 'Horizontal (Side by Side)', 'magical-products-display' ),
					'vertical'   => __( 'Vertical (Stacked)', 'magical-products-display' ),
				),
				'condition' => array(
					'show_express_checkout' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_alignment',
			array(
				'label'     => __( 'Button Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .mpd-express-buttons ' => 'align-items: {{VALUE}};',
				),
				'condition' => array(
					'show_express_checkout' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Divider Section.
		$this->start_controls_section(
			'section_divider',
			array(
				'label'     => __( 'Divider', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'show_express_checkout' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_divider',
			array(
				'label'        => __( 'Show Divider', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'divider_text',
			array(
				'label'       => __( 'Divider Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'OR', 'magical-products-display' ),
				'placeholder' => __( 'e.g., OR, or continue below', 'magical-products-display' ),
				'condition'   => array(
					'show_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_position',
			array(
				'label'     => __( 'Divider Position', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bottom',
				'options'   => array(
					'top'    => __( 'Above Buttons', 'magical-products-display' ),
					'bottom' => __( 'Below Buttons', 'magical-products-display' ),
				),
				'condition' => array(
					'show_divider' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Payment Methods Section.
		$this->start_controls_section(
			'section_payment_methods',
			array(
				'label'     => __( 'Payment Methods', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'show_express_checkout' => 'yes',
				),
			)
		);

		$this->add_control(
			'payment_methods_info',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<div class="elementor-control-field-description">' .
					__( 'Express payment buttons are automatically displayed based on the payment gateways you have installed and enabled in WooCommerce. Supported gateways include:', 'magical-products-display' ) .
					'<ul style="margin:10px 0 0 15px;list-style:disc;">' .
					'<li>PayPal (PayPal Payments, PayPal for WooCommerce)</li>' .
					'<li>Stripe (Apple Pay, Google Pay, Link)</li>' .
					'<li>Square (Apple Pay, Google Pay)</li>' .
					'<li>Amazon Pay</li>' .
					'<li>Klarna</li>' .
					'</ul></div>',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'fallback_message',
			array(
				'label'       => __( 'No Methods Message', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'placeholder' => __( 'Message when no express methods available', 'magical-products-display' ),
				'description' => __( 'Leave empty to hide the entire section when no express methods are available.', 'magical-products-display' ),
				'rows'        => 2,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 */
	protected function register_style_controls() {
		// Container Style.
		$this->start_controls_section(
			'section_style_container',
			array(
				'label'     => __( 'Container', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_express_checkout' => 'yes',
				),
			)
		);

		$this->add_control(
			'container_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-express-checkout' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => 25,
					'right'  => 25,
					'bottom' => 25,
					'left'   => 25,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-express-checkout' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => 0,
					'right'  => 0,
					'bottom' => 30,
					'left'   => 0,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-express-checkout' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'label'    => __( 'Border', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-express-checkout',
			)
		);

		$this->add_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => 12,
					'right'  => 12,
					'bottom' => 12,
					'left'   => 12,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-express-checkout' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_shadow',
				'label'    => __( 'Box Shadow', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-express-checkout',
			)
		);

		$this->end_controls_section();

		// Title Style.
		$this->start_controls_section(
			'section_style_title',
			array(
				'label'     => __( 'Title', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_express_checkout' => 'yes',
					'show_title'            => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-express-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-express-title',
			)
		);

		$this->add_control(
			'title_alignment',
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
					'{{WRAPPER}} .mpd-express-title' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => 0,
					'right'  => 0,
					'bottom' => 15,
					'left'   => 0,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-express-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Description Style.
		$this->start_controls_section(
			'section_style_description',
			array(
				'label'     => __( 'Description', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_express_checkout' => 'yes',
					'show_description'      => 'yes',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-express-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'label'    => __( 'Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-express-description',
			)
		);

		$this->add_control(
			'description_alignment',
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
					'{{WRAPPER}} .mpd-express-description' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Buttons Style.
		$this->start_controls_section(
			'section_style_buttons',
			array(
				'label'     => __( 'Buttons Area', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_express_checkout' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'buttons_gap',
			array(
				'label'      => __( 'Gap Between Buttons', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
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
					'{{WRAPPER}} .mpd-express-buttons' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'buttons_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => 15,
					'right'  => 0,
					'bottom' => 15,
					'left'   => 0,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-express-buttons' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_min_width',
			array(
				'label'      => __( 'Button Min Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 500,
					),
					'%'  => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 200,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-express-buttons > div,
					 {{WRAPPER}} .mpd-express-buttons > button,
					 {{WRAPPER}} .mpd-express-buttons > a' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Divider Style.
		$this->start_controls_section(
			'section_style_divider',
			array(
				'label'     => __( 'Divider', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_express_checkout' => 'yes',
					'show_divider'          => 'yes',
				),
			)
		);

		$this->add_control(
			'divider_line_color',
			array(
				'label'     => __( 'Line Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-express-divider::before,
					 {{WRAPPER}} .mpd-express-divider::after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-express-divider span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_text_background',
			array(
				'label'     => __( 'Text Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-express-divider span' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'divider_typography',
				'label'    => __( 'Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-express-divider span',
			)
		);

		$this->add_responsive_control(
			'divider_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => 20,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 0,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-express-divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Check if HTML contains actual express payment buttons (not regular links).
	 *
	 * @param string $html The HTML to check.
	 * @return bool True if contains express buttons, false otherwise.
	 */
	protected function contains_express_buttons( $html ) {
		if ( empty( trim( $html ) ) ) {
			return false;
		}

		// Patterns that indicate actual express payment buttons.
		$express_patterns = array(
			'ppcp-button',           // PayPal Payments.
			'paypal-button',         // PayPal.
			'paypal-buttons',        // PayPal Smart Buttons.
			'stripe-payment-request', // Stripe Apple/Google Pay.
			'payment-request-button', // Generic payment request.
			'apple-pay',             // Apple Pay.
			'google-pay',            // Google Pay.
			'gpay-button',           // Google Pay.
			'sq-payment-request',    // Square.
			'amazon-pay',            // Amazon Pay.
			'pay_with_amazon',       // Amazon Pay.
			'klarna',                // Klarna.
			'wc-stripe-payment',     // Stripe.
			'express-checkout',      // Generic express.
			'quick-checkout',        // Generic quick.
		);

		foreach ( $express_patterns as $pattern ) {
			if ( stripos( $html, $pattern ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Filter out non-express content from HTML.
	 *
	 * @param string $html The HTML to filter.
	 * @return string Filtered HTML.
	 */
	protected function filter_express_content( $html ) {
		if ( empty( $html ) ) {
			return '';
		}

		// Remove common non-express links/buttons.
		$patterns_to_remove = array(
			// View cart links.
			'/<a[^>]*class=["\'][^"\']*(wc-forward|view-cart)[^"\']["\'][^>]*>.*?<\/a>/is',
			// Checkout links (not express).
			'/<a[^>]*href=["\'][^"\']*(cart|checkout)[^"\']["\'][^>]*>\s*(View cart|Checkout|View Cart|Go to checkout)\s*<\/a>/is',
			// Button wrappers without express content.
			'/<p[^>]*class=["\'][^"\']*(woocommerce-mini-cart__buttons|buttons)[^"\']["\'][^>]*>[^<]*<a[^>]*>(View cart|Checkout)<\/a>[^<]*<a[^>]*>(View cart|Checkout)<\/a>[^<]*<\/p>/is',
		);

		$filtered = $html;
		foreach ( $patterns_to_remove as $pattern ) {
			$filtered = preg_replace( $pattern, '', $filtered );
		}

		return trim( $filtered );
	}

	/**
	 * Check if any express payment methods are available.
	 *
	 * @return bool
	 */
	protected function has_express_payment_methods() {
		// Check for common express checkout plugins/gateways.
		$has_methods = false;

		// PayPal for WooCommerce (WooCommerce PayPal Payments).
		if ( class_exists( 'WooCommerce\PayPalCommerce\PluginModule' ) || 
			 class_exists( 'WC_Gateway_PPEC_Plugin' ) ||
			 defined( 'PPCP_FLAG_SUBSCRIPTION' ) ) {
			$has_methods = true;
		}

		// Stripe (with Payment Request API - Apple Pay, Google Pay).
		if ( class_exists( 'WC_Stripe' ) || class_exists( 'WC_Gateway_Stripe' ) ) {
			$has_methods = true;
		}

		// Square.
		if ( class_exists( 'WooCommerce\Square\Plugin' ) ) {
			$has_methods = true;
		}

		// Amazon Pay.
		if ( class_exists( 'WC_Amazon_Payments_Advanced' ) ) {
			$has_methods = true;
		}

		// Klarna.
		if ( class_exists( 'WC_Klarna_Payments' ) || class_exists( 'Klarna_Checkout_For_WooCommerce' ) ) {
			$has_methods = true;
		}

		// Allow filtering.
		return apply_filters( 'mpd_has_express_payment_methods', $has_methods );
	}

	/**
	 * Render express checkout buttons based on context.
	 *
	 * @param string $context The display context (checkout, cart, product, anywhere).
	 * @return string HTML of express buttons.
	 */
	protected function render_express_buttons( $context = 'checkout' ) {
		$buttons_html = '';

		switch ( $context ) {
			case 'cart':
				// Cart page hooks used by payment gateways.
				ob_start();
				do_action( 'woocommerce_proceed_to_checkout' );
				$buttons_html .= ob_get_clean();

				ob_start();
				do_action( 'woocommerce_before_cart_totals' );
				$buttons_html .= ob_get_clean();

				ob_start();
				do_action( 'woocommerce_cart_totals_after_order_total' );
				$buttons_html .= ob_get_clean();

				// PayPal Smart Buttons on cart.
				ob_start();
				do_action( 'woocommerce_after_cart_totals' );
				$buttons_html .= ob_get_clean();
				break;

			case 'product':
				// Single product page hooks.
				ob_start();
				do_action( 'woocommerce_after_add_to_cart_button' );
				$buttons_html .= ob_get_clean();

				ob_start();
				do_action( 'woocommerce_after_add_to_cart_form' );
				$buttons_html .= ob_get_clean();

				// PayPal Buy Now button.
				ob_start();
				do_action( 'woocommerce_single_product_summary' );
				$buttons_html .= ob_get_clean();
				break;

			case 'anywhere':
				// For sidebar/popup - try multiple hooks.
				ob_start();
				// Custom hook for anywhere placement.
				do_action( 'mpd_express_checkout_buttons' );
				$buttons_html .= ob_get_clean();

				// Also try cart hooks as fallback.
				if ( empty( trim( $buttons_html ) ) ) {
					ob_start();
					do_action( 'woocommerce_widget_shopping_cart_buttons' );
					$buttons_html .= ob_get_clean();
				}

				// Mini-cart express checkout.
				ob_start();
				do_action( 'woocommerce_widget_shopping_cart_after_buttons' );
				$buttons_html .= ob_get_clean();
				break;

			case 'checkout':
			default:
				// Standard checkout page hooks.
				ob_start();
				do_action( 'woocommerce_checkout_before_customer_details' );
				$buttons_html .= ob_get_clean();

				ob_start();
				do_action( 'woocommerce_before_checkout_form_cart_notices' );
				$buttons_html .= ob_get_clean();

				ob_start();
				do_action( 'woocommerce_checkout_before_order_review' );
				$buttons_html .= ob_get_clean();
				break;
		}

		// Filter out non-express content.
		$buttons_html = $this->filter_express_content( $buttons_html );

		// Allow filtering of express buttons.
		return apply_filters( 'mpd_express_checkout_buttons_html', $buttons_html, $context );
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		// Check WooCommerce.
		if ( ! $this->is_woocommerce_active() ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="mpd-wc-required-notice">';
				echo '<p>' . esc_html__( 'WooCommerce is required for the Express Checkout widget.', 'magical-products-display' ) . '</p>';
				echo '</div>';
			}
			return;
		}

		$settings  = $this->get_settings_for_display();
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Check if express checkout is enabled.
		if ( 'yes' !== $settings['show_express_checkout'] ) {
			return;
		}

		// Get display context.
		$context = isset( $settings['display_context'] ) ? $settings['display_context'] : 'checkout';

		// Get express buttons HTML.
		$express_buttons = '';
		$has_buttons     = false;

		if ( ! $is_editor ) {
			$express_buttons = $this->render_express_buttons( $context );
			// Check if we have ACTUAL express payment buttons (not just View Cart/Checkout links).
			$has_buttons = $this->contains_express_buttons( $express_buttons ) && ! empty( trim( $express_buttons ) );
		} else {
			$has_buttons = true; // Always show demo in editor.
		}

		// If no express buttons and no fallback message, hide entire section.
		if ( ! $has_buttons && empty( $settings['fallback_message'] ) ) {
			return;
		}

		$layout_class = 'mpd-express-layout-' . $settings['button_layout'];
		$context_class = 'mpd-express-context-' . $context;
		?>
		<div class="mpd-express-checkout <?php echo esc_attr( $layout_class ); ?> <?php echo esc_attr( $context_class ); ?>">
			
			<?php // Title. ?>
			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['express_title'] ) ) : ?>
				<h3 class="mpd-express-title"><?php echo esc_html( $settings['express_title'] ); ?></h3>
			<?php endif; ?>

			<?php // Description. ?>
			<?php if ( 'yes' === $settings['show_description'] && ! empty( $settings['express_description'] ) ) : ?>
				<p class="mpd-express-description"><?php echo esc_html( $settings['express_description'] ); ?></p>
			<?php endif; ?>

			<?php // Top Divider. ?>
			<?php if ( 'yes' === $settings['show_divider'] && 'top' === $settings['divider_position'] ) : ?>
				<?php $this->render_divider( $settings ); ?>
			<?php endif; ?>

			<?php // Express Buttons. ?>
			<div class="mpd-express-buttons">
				<?php if ( $is_editor ) : ?>
					<?php // Demo buttons for editor. ?>
					<div class="mpd-express-demo-buttons">
						<div class="mpd-express-demo-btn mpd-express-demo-paypal">
							<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.93 4.778-4.005 7.201-9.138 7.201h-2.19a.563.563 0 0 0-.556.479l-1.187 7.527h-.506l-.24 1.516a.56.56 0 0 0 .554.647h3.882c.46 0 .85-.334.922-.788.06-.26.76-4.852.816-5.09a.932.932 0 0 1 .923-.788h.58c3.76 0 6.705-1.528 7.565-5.946.36-1.847.174-3.388-.777-4.471z"/></svg>
							<span>PayPal</span>
						</div>
						<div class="mpd-express-demo-btn mpd-express-demo-applepay">
							<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M17.0425 7.7041c-.0498-.0415-.9297-.5346-1.8965-.5346-1.1075 0-1.6426.5307-2.4457.5307-.8281 0-1.5049-.5156-2.3936-.5156-.9756 0-2.0127.5936-2.6729 1.6064-.9316 1.4287-.7729 4.1152.7354 6.4062.5439.8281 1.2705 1.7578 2.2129 1.7686.8398.0107 1.0791-.5371 2.2363-.543 1.1592-.0078 1.373.5527 2.2129.541.9443-.0098 1.7109-.8418 2.2539-1.6689.3906-.5957.5469-.8965.8477-1.5713-2.2266-.8457-2.5859-4.0039-.3906-5.2695zm-3.0059-2.7598c.5303-.6826.9297-1.6475.7852-2.6348-.8672.0615-1.8809.6123-2.4707 1.3311-.5352.6533-.9756 1.6289-.8027 2.5723.9473.0293 1.9277-.5273 2.4883-1.2686z"/></svg>
							<span>Apple Pay</span>
						</div>
						<div class="mpd-express-demo-btn mpd-express-demo-googlepay">
							<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12.545 10.239v3.821h5.445c-.712 2.315-2.647 3.972-5.445 3.972a6.033 6.033 0 110-12.064c1.498 0 2.866.549 3.921 1.453l2.814-2.814A9.969 9.969 0 0012.545 2C7.021 2 2.543 6.477 2.543 12s4.478 10 10.002 10c8.396 0 10.249-7.85 9.426-11.748l-9.426-.013z"/></svg>
							<span>Google Pay</span>
						</div>
					</div>
					<p class="mpd-express-editor-note">
						<em>
						<?php
						switch ( $context ) {
							case 'cart':
								esc_html_e( 'Cart Context: Express buttons will use cart page payment hooks.', 'magical-products-display' );
								break;
							case 'product':
								esc_html_e( 'Product Context: "Buy Now" express buttons for single products.', 'magical-products-display' );
								break;
							case 'anywhere':
								esc_html_e( 'Anywhere Context: Use in sidebar, popup, or custom locations.', 'magical-products-display' );
								break;
							default:
								esc_html_e( 'Checkout Context: Standard checkout page express buttons.', 'magical-products-display' );
						}
						?>
						</em>
					</p>
				<?php elseif ( $has_buttons ) : ?>
					<?php echo $express_buttons; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<p class="mpd-express-fallback"><?php echo esc_html( $settings['fallback_message'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php // Bottom Divider. ?>
			<?php if ( 'yes' === $settings['show_divider'] && 'bottom' === $settings['divider_position'] ) : ?>
				<?php $this->render_divider( $settings ); ?>
			<?php endif; ?>

		</div>
		<?php
	}

	/**
	 * Render divider.
	 *
	 * @param array $settings Widget settings.
	 */
	protected function render_divider( $settings ) {
		?>
		<div class="mpd-express-divider">
			<span><?php echo esc_html( $settings['divider_text'] ); ?></span>
		</div>
		<?php
	}
}
