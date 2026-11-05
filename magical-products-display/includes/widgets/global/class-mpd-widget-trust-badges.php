<?php
/**
 * Trust Badges & Guarantee Icons Widget
 *
 * Displays customizable payment trust badges, security seals, and guarantee icons.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.1
 */

namespace MPD\MagicalShopBuilder\Widgets\GlobalWidgets;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Trust_Badges
 *
 * @since 2.1.1
 */
class Trust_Badges extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_GLOBAL;

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	protected $widget_icon = 'eicon-lock';

	/**
	 * Get widget icon.
	 *
	 * @since 2.1.1
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-lock';
	}

	/**
	 * Get widget name.
	 *
	 * @since 2.1.1
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-trust-badges';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.1.1
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Trust Badges & Guarantees', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.1.1
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array(
			'trust',
			'badges',
			'security',
			'guarantee',
			'payment',
			'visa',
			'mastercard',
			'paypal',
			'ssl',
			'checkout',
			'magical-products-display',
		);
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.1.1
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-trust-badges' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.1.1
	 * @return void
	 */
	protected function register_content_controls() {
		// Header Section.
		$this->start_controls_section(
			'section_header',
			array(
				'label' => esc_html__( 'Header & Title', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Show Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'title_text',
			array(
				'label'       => esc_html__( 'Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Guaranteed Safe & Secure Checkout', 'magical-products-display' ),
				'placeholder' => esc_html__( 'Enter section title', 'magical-products-display' ),
				'condition'   => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_title_icon',
			array(
				'label'        => esc_html__( 'Show Lock/Shield Icon', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'subtitle_text',
			array(
				'label'       => esc_html__( 'Subtitle / Disclaimer', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Your payment information is processed securely.', 'magical-products-display' ),
				'placeholder' => esc_html__( 'Enter subtitle or disclaimer', 'magical-products-display' ),
				'rows'        => 2,
			)
		);

		$this->end_controls_section();

		// Badges Section.
		$this->start_controls_section(
			'section_badges',
			array(
				'label' => esc_html__( 'Badges List', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'badge_type',
			array(
				'label'   => esc_html__( 'Badge Type', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'visa',
				'options' => array(
					'visa'          => esc_html__( 'Visa', 'magical-products-display' ),
					'mastercard'    => esc_html__( 'Mastercard', 'magical-products-display' ),
					'amex'          => esc_html__( 'American Express', 'magical-products-display' ),
					'paypal'        => esc_html__( 'PayPal', 'magical-products-display' ),
					'apple_pay'     => esc_html__( 'Apple Pay', 'magical-products-display' ),
					'google_pay'    => esc_html__( 'Google Pay', 'magical-products-display' ),
					'discover'      => esc_html__( 'Discover', 'magical-products-display' ),
					'stripe'        => esc_html__( 'Stripe', 'magical-products-display' ),
					'cod'           => esc_html__( 'Cash on Delivery (COD)', 'magical-products-display' ),
					'ssl'           => esc_html__( '256-Bit SSL Encryption', 'magical-products-display' ),
					'norton'        => esc_html__( 'Norton Secured Seal', 'magical-products-display' ),
					'mcafee'        => esc_html__( 'McAfee Secure Seal', 'magical-products-display' ),
					'money_back'    => esc_html__( '30-Day Money Back Guarantee', 'magical-products-display' ),
					'free_shipping' => esc_html__( 'Free / Fast Shipping', 'magical-products-display' ),
					'support'       => esc_html__( '24/7 Customer Support', 'magical-products-display' ),
					'satisfaction'  => esc_html__( '100% Satisfaction Guarantee', 'magical-products-display' ),
					'custom'        => esc_html__( 'Custom Image / Icon', 'magical-products-display' ),
				),
			)
		);

		$repeater->add_control(
			'custom_image',
			array(
				'label'     => esc_html__( 'Custom Image', 'magical-products-display' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'badge_type' => 'custom',
				),
			)
		);

		$repeater->add_control(
			'badge_label',
			array(
				'label'       => esc_html__( 'Label (Optional)', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'e.g. Visa', 'magical-products-display' ),
			)
		);

		$repeater->add_control(
			'badge_url',
			array(
				'label'       => esc_html__( 'Link URL (Optional)', 'magical-products-display' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://...',
			)
		);

		$this->add_control(
			'badges_list',
			array(
				'label'       => esc_html__( 'Badges', 'magical-products-display' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'badge_type'  => 'visa',
						'badge_label' => 'Visa',
					),
					array(
						'badge_type'  => 'mastercard',
						'badge_label' => 'Mastercard',
					),
					array(
						'badge_type'  => 'amex',
						'badge_label' => 'Amex',
					),
					array(
						'badge_type'  => 'paypal',
						'badge_label' => 'PayPal',
					),
					array(
						'badge_type'  => 'apple_pay',
						'badge_label' => 'Apple Pay',
					),
					array(
						'badge_type'  => 'ssl',
						'badge_label' => '256-Bit SSL',
					),
				),
				'title_field' => '{{{ badge_type.toUpperCase() }}}',
			)
		);

		$this->end_controls_section();

		// Layout & Display Section.
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout & Preset Styles', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'badge_style',
			array(
				'label'   => esc_html__( 'Visual Style', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default'   => esc_html__( 'Full Color (Default)', 'magical-products-display' ),
					'grayscale' => esc_html__( 'Monochrome / Grayscale (Color on Hover)', 'magical-products-display' ),
					'outline'   => esc_html__( 'Outlined Box', 'magical-products-display' ),
					'card'      => esc_html__( 'Soft Card & Shadow', 'magical-products-display' ),
				),
			)
		);

		$this->add_responsive_control(
			'alignment',
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
			)
		);

		$this->add_control(
			'show_labels',
			array(
				'label'        => esc_html__( 'Show Badge Labels', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_responsive_control(
			'badge_height',
			array(
				'label'      => esc_html__( 'Badge Height (px)', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 18,
						'max'  => 80,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 34,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-trust-badges-wrapper' => '--mpd-badge-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_gap',
			array(
				'label'      => esc_html__( 'Spacing Between Badges (px)', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 4,
						'max'  => 40,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-trust-badges-list' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label'   => esc_html__( 'Hover Effect', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'scale',
				'options' => array(
					'none'  => esc_html__( 'None', 'magical-products-display' ),
					'scale' => esc_html__( 'Scale Up (Subtle)', 'magical-products-display' ),
					'lift'  => esc_html__( 'Float / Lift', 'magical-products-display' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @since 2.1.1
	 * @return void
	 */
	protected function register_style_controls() {
		// Header Style Section.
		$this->start_controls_section(
			'section_style_header',
			array(
				'label' => esc_html__( 'Header & Typography', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1e293b',
				'selectors' => array(
					'{{WRAPPER}} .mpd-trust-badges-title' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'selector'  => '{{WRAPPER}} .mpd-trust-badges-title',
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Lock/Shield Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#10b981',
				'selectors' => array(
					'{{WRAPPER}} .mpd-trust-badges-title-icon' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_title'      => 'yes',
					'show_title_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => esc_html__( 'Subtitle Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#64748b',
				'selectors' => array(
					'{{WRAPPER}} .mpd-trust-badges-subtitle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .mpd-trust-badges-subtitle',
			)
		);

		$this->end_controls_section();

		// Badge Cards Style Section.
		$this->start_controls_section(
			'section_style_cards',
			array(
				'label' => esc_html__( 'Badge Cards', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'badge_card_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-trust-badge-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'badge_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-trust-badge-card',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Label Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#64748b',
				'selectors' => array(
					'{{WRAPPER}} .mpd-trust-badge-label' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_labels' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'label_typography',
				'selector'  => '{{WRAPPER}} .mpd-trust-badge-label',
				'condition' => array(
					'show_labels' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Main Container Style Section.
		$this->start_controls_section(
			'section_style_container',
			array(
				'label' => esc_html__( 'Box Container', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-trust-badges-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_background',
				'selector' => '{{WRAPPER}} .mpd-trust-badges-wrapper',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-trust-badges-wrapper',
			)
		);

		$this->add_responsive_control(
			'container_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-trust-badges-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-trust-badges-wrapper',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get pre-designed vector SVG for each badge type.
	 *
	 * @since 2.1.1
	 * @param string $type Badge type key.
	 * @return string SVG markup.
	 */
	public static function get_badge_svg( $type ) {
		switch ( $type ) {
			case 'visa':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#1A1F71"/><path d="M25.7 27.5L28.3 12.5H32.4L29.8 27.5H25.7ZM42.5 12.8C41.7 12.5 40.4 12.2 38.8 12.2C34.6 12.2 31.7 14.4 31.7 17.5C31.7 19.8 33.8 21.1 35.4 21.9C37 22.7 37.6 23.2 37.6 23.9C37.6 25 36.3 25.5 35.1 25.5C33.4 25.5 32.5 25.2 31.3 24.7L30.7 24.4L30.1 28.2C31.2 28.7 33 29.1 34.8 29.1C39.3 29.1 42.2 26.9 42.2 23.5C42.2 21.6 41 20.1 38.3 18.8C37 18.2 36.2 17.7 36.2 17C36.2 16.3 37 15.6 38.4 15.6C39.6 15.6 40.6 15.9 41.3 16.2L41.7 16.4L42.5 12.8ZM51.4 12.5H48.2C47.2 12.5 46.4 12.8 46 13.7L39.3 27.5H43.6L44.5 25H49.8L50.3 27.5H54.2L51.4 12.5ZM45.7 21.8C46.1 20.8 47.7 16.3 47.7 16.3C47.7 16.3 48.1 15.2 48.3 14.6L48.6 16.2C48.6 16.2 49.4 20.4 49.7 21.8H45.7ZM21.9 12.5L18 22.8L17.6 20.8C16.9 18.4 14.7 15.7 12.1 14.3L15.7 27.5H20.1L26.6 12.5H21.9Z" fill="white"/><path d="M14.5 12.5H8L8 12.7C13 14 17.3 17.4 18.7 21.1L17.2 13.9C16.9 12.8 15.8 12.5 14.5 12.5Z" fill="#F7B600"/></svg>';

			case 'mastercard':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#0A0A0A"/><circle cx="25" cy="20" r="12" fill="#EB001B"/><circle cx="39" cy="20" r="12" fill="#F79E1B"/><path d="M32 10.7C34.7 13.2 36.4 16.4 36.4 20C36.4 23.6 34.7 26.8 32 29.3C29.3 26.8 27.6 23.6 27.6 20C27.6 16.4 29.3 13.2 32 10.7Z" fill="#FF5F00"/></svg>';

			case 'amex':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#006FCF"/><g transform="translate(4, 0)"><path d="M4 25l4.3-10h4.3l4.3 10h-3.3l-.8-2h-4.8l-.8 2H4zm4.4-4.5h3.2l-1.6-4.2-1.6 4.2zm9.6 4.5v-10h4.2l2.5 5.8 2.5-5.8h4.2v10h-3v-6.3l-2.7 6.3h-2l-2.7-6.3v6.3h-3zm15 0v-10h9.2v2.5h-6.3v1.2h5.3v2.4h-5.3v1.4h6.5V25H33zm10.7 0l3.7-5-3.5-5h3.7l1.8 2.8 1.8-2.8h3.7l-3.5 5 3.7 5h-3.9l-2-3-2 3h-3.7z" fill="#FFFFFF"/></g></svg>';

			case 'paypal':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.2"/><g transform="translate(4, 12.5) scale(0.45)"><path fill="#253B80" d="M46.211,6.749h-6.839c-0.468,0-0.866,0.34-0.939,0.802l-2.766,17.537c-0.055,0.346,0.213,0.658,0.564,0.658h3.265c0.468,0,0.866-0.34,0.939-0.803l0.746-4.73c0.072-0.463,0.471-0.803,0.938-0.803h2.165c4.505,0,7.105-2.18,7.784-6.5c0.306-1.89,0.013-3.375-0.872-4.415C50.224,7.353,48.5,6.749,46.211,6.749z M47,13.154c-0.374,2.454-2.249,2.454-4.062,2.454h-1.032l0.724-4.583c0.043-0.277,0.283-0.481,0.563-0.481h0.473c1.235,0,2.4,0,3.002,0.704C47.027,11.668,47.137,12.292,47,13.154z"/><path fill="#253B80" d="M66.654,13.075h-3.275c-0.279,0-0.52,0.204-0.563,0.481l-0.145,0.916l-0.229-0.332c-0.709-1.029-2.29-1.373-3.868-1.373c-3.619,0-6.71,2.741-7.312,6.586c-0.313,1.918,0.132,3.752,1.22,5.031c0.998,1.176,2.426,1.666,4.125,1.666c2.916,0,4.533-1.875,4.533-1.875l-0.146,0.91c-0.055,0.348,0.213,0.66,0.562,0.66h2.95c0.469,0,0.865-0.34,0.939-0.803l1.77-11.209C67.271,13.388,67.004,13.075,66.654,13.075z M62.089,19.449c-0.316,1.871-1.801,3.127-3.695,3.127c-0.951,0-1.711-0.305-2.199-0.883c-0.484-0.574-0.668-1.391-0.514-2.301c0.295-1.855,1.805-3.152,3.67-3.152c0.93,0,1.686,0.309,2.184,0.892C62.034,17.721,62.232,18.543,62.089,19.449z"/><path fill="#253B80" d="M84.096,13.075h-3.291c-0.314,0-0.609,0.156-0.787,0.417l-4.539,6.686l-1.924-6.425c-0.121-0.402-0.492-0.678-0.912-0.678h-3.234c-0.393,0-0.666,0.384-0.541,0.754l3.625,10.638l-3.408,4.811c-0.268,0.379,0.002,0.9,0.465,0.9h3.287c0.312,0,0.604-0.152,0.781-0.408L84.564,13.97C84.826,13.592,84.557,13.075,84.096,13.075z"/><path fill="#179BD7" d="M94.992,6.749h-6.84c-0.467,0-0.865,0.34-0.938,0.802l-2.766,17.537c-0.055,0.346,0.213,0.658,0.562,0.658h3.51c0.326,0,0.605-0.238,0.656-0.562l0.785-4.971c0.072-0.463,0.471-0.803,0.938-0.803h2.164c4.506,0,7.105-2.18,7.785-6.5c0.307-1.89,0.012-3.375-0.873-4.415C99.004,7.353,97.281,6.749,94.992,6.749z M95.781,13.154c-0.373,2.454-2.248,2.454-4.062,2.454h-1.031l0.725-4.583c0.043-0.277,0.281-0.481,0.562-0.481h0.473c1.234,0,2.4,0,3.002,0.704C95.809,11.668,95.918,12.292,95.781,13.154z"/><path fill="#179BD7" d="M115.434,13.075h-3.273c-0.281,0-0.52,0.204-0.562,0.481l-0.145,0.916l-0.23-0.332c-0.709-1.029-2.289-1.373-3.867-1.373c-3.619,0-6.709,2.741-7.311,6.586c-0.312,1.918,0.131,3.752,1.219,5.031c1,1.176,2.426,1.666,4.125,1.666c2.916,0,4.533-1.875,4.533-1.875l-0.146,0.91c-0.055,0.348,0.213,0.66,0.564,0.66h2.949c0.467,0,0.865-0.34,0.938-0.803l1.771-11.209C116.053,13.388,115.785,13.075,115.434,13.075z M110.869,19.449c-0.314,1.871-1.801,3.127-3.695,3.127c-0.949,0-1.711-0.305-2.199-0.883c-0.484-0.574-0.666-1.391-0.514-2.301c0.297-1.855,1.805-3.152,3.67-3.152c0.93,0,1.686,0.309,2.184,0.892C110.816,17.721,111.014,18.543,110.869,19.449z"/><path fill="#179BD7" d="M119.295,7.23l-2.807,17.858c-0.055,0.346,0.213,0.658,0.562,0.658h2.822c0.469,0,0.867-0.34,0.939-0.803l2.768-17.536c0.055-0.346-0.213-0.659-0.562-0.659h-3.16C119.578,6.749,119.338,6.953,119.295,7.23z"/><path fill="#253B80" d="M7.266,29.154l0.523-3.322l-1.165-0.027H1.061L4.927,1.292C4.939,1.218,4.978,1.149,5.035,1.1c0.057-0.049,0.13-0.076,0.206-0.076h9.38c3.114,0,5.263,0.648,6.385,1.927c0.526,0.6,0.861,1.227,1.023,1.917c0.17,0.724,0.173,1.589,0.007,2.644l-0.012,0.077v0.676l0.526,0.298c0.443,0.235,0.795,0.504,1.065,0.812c0.45,0.513,0.741,1.165,0.864,1.938c0.127,0.795,0.085,1.741-0.123,2.812c-0.24,1.232-0.628,2.305-1.152,3.183c-0.482,0.809-1.096,1.48-1.825,2c-0.696,0.494-1.523,0.869-2.458,1.109c-0.906,0.236-1.939,0.355-3.072,0.355h-0.73c-0.522,0-1.029,0.188-1.427,0.525c-0.399,0.344-0.663,0.814-0.744,1.328l-0.055,0.299l-0.924,5.855l-0.042,0.215c-0.011,0.068-0.03,0.102-0.058,0.125c-0.025,0.021-0.061,0.035-0.096,0.035H7.266z"/><path fill="#179BD7" d="M23.048,7.667c-0.028,0.179-0.06,0.362-0.096,0.55c-1.237,6.351-5.469,8.545-10.874,8.545H9.326c-0.661,0-1.218,0.48-1.321,1.132L6.596,26.83l-0.399,2.533c-0.067,0.428,0.263,0.814,0.695,0.814h4.881c0.578,0,1.069-0.42,1.16-0.99l0.048-0.248l0.919-5.832l0.059-0.32c0.09-0.572,0.582-0.992,1.16-0.992h0.73c4.729,0,8.431-1.92,9.513-7.476c0.452-2.321,0.218-4.259-0.978-5.622C24.022,8.286,23.573,7.945,23.048,7.667z"/><path fill="#222D65" d="M21.754,7.151c-0.189-0.055-0.384-0.105-0.584-0.15c-0.201-0.044-0.407-0.083-0.619-0.117c-0.742-0.12-1.555-0.177-2.426-0.177h-7.352c-0.181,0-0.353,0.041-0.507,0.115C9.927,6.985,9.675,7.306,9.614,7.699L8.05,17.605l-0.045,0.289c0.103-0.652,0.66-1.132,1.321-1.132h2.752c5.405,0,9.637-2.195,10.874-8.545c0.037-0.188,0.068-0.371,0.096-0.55c-0.313-0.166-0.652-0.308-1.017-0.429C21.941,7.208,21.848,7.179,21.754,7.151z"/><path fill="#253B80" d="M9.614,7.699c0.061-0.393,0.313-0.714,0.652-0.876c0.155-0.074,0.326-0.115,0.507-0.115h7.352c0.871,0,1.684,0.057,2.426,0.177c0.212,0.034,0.418,0.073,0.619,0.117c0.2,0.045,0.395,0.095,0.584,0.15c0.094,0.028,0.187,0.057,0.278,0.086c0.365,0.121,0.704,0.264,1.017,0.429c0.368-2.347-0.003-3.945-1.272-5.392C20.378,0.682,17.853,0,14.622,0h-9.38c-0.66,0-1.223,0.48-1.325,1.133L0.01,25.898c-0.077,0.49,0.301,0.932,0.795,0.932h5.791l1.454-9.225L9.614,7.699z"/></g></svg>';

			case 'apple_pay':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#000000"/><g transform="translate(8.5, 10.5) scale(0.092)" fill="#FFFFFF"><path d="M93.6,27.1C87.6,34.2,78,39.8,68.4,39c-1.2-9.6,3.5-19.8,9-26.1c6-7.3,16.5-12.5,25-12.9C103.4,10,99.5,19.8,93.6,27.1 M102.3,40.9c-13.9-0.8-25.8,7.9-32.4,7.9c-6.7,0-16.8-7.5-27.8-7.3c-14.3,0.2-27.6,8.3-34.9,21.2c-15,25.8-3.9,64,10.6,85c7.1,10.4,15.6,21.8,26.8,21.4c10.6-0.4,14.8-6.9,27.6-6.9c12.9,0,16.6,6.9,27.8,6.7c11.6-0.2,18.9-10.4,26-20.8c8.1-11.8,11.4-23.3,11.6-23.9c-0.2-0.2-22.4-8.7-22.6-34.3c-0.2-21.4,17.5-31.6,18.3-32.2C123.3,42.9,107.7,41.3,102.3,40.9 M182.6,11.9v155.9h24.2v-53.3h33.5c30.6,0,52.1-21,52.1-51.4c0-30.4-21.1-51.2-51.3-51.2H182.6z M206.8,32.3h27.9c21,0,33,11.2,33,30.9c0,19.7-12,31-33.1,31h-27.8V32.3z M336.6,169c15.2,0,29.3-7.7,35.7-19.9h0.5v18.7h22.4V90.2c0-22.5-18-37-45.7-37c-25.7,0-44.7,14.7-45.4,34.9h21.8c1.8-9.6,10.7-15.9,22.9-15.9c14.8,0,23.1,6.9,23.1,19.6v8.6l-30.2,1.8c-28.1,1.7-43.3,13.2-43.3,33.2C298.4,155.6,314.1,169,336.6,169z M343.1,150.5c-12.9,0-21.1-6.2-21.1-15.7c0-9.8,7.9-15.5,23-16.4l26.9-1.7v8.8C371.9,140.1,359.5,150.5,343.1,150.5z M425.1,210.2c23.6,0,34.7-9,44.4-36.3L512,54.7h-24.6l-28.5,92.1h-0.5l-28.5-92.1h-25.3l41,113.5l-2.2,6.9c-3.7,11.7-9.7,16.2-20.4,16.2c-1.9,0-5.6-0.2-7.1-0.4v18.7C417.3,210,423.3,210.2,425.1,210.2z"/></g></svg>';

			case 'google_pay':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.2"/><g transform="translate(6, 11) scale(0.75)"><path d="M21.3 10.2H12v3.6h5.4c-.2 1.3-1 2.5-2 3.2v2.4h3.3c1.9-1.7 3-4.3 3-7.2 0-.7-.1-1.4-.2-2z" fill="#4285F4"/><path d="M12 19.8c2.7 0 4.9-.9 6.6-2.4l-3.3-2.4c-.9.6-2 1-3.3 1-2.6 0-4.8-1.8-5.6-4.1H3.1v2.5C4.7 17.6 8.1 19.8 12 19.8z" fill="#34A853"/><path d="M6.4 11.9c-.2-.6-.3-1.3-.3-1.9s.1-1.3.3-1.9V5.6H3.1C2.4 7 2 8.5 2 10s.4 3 1.1 4.4l3.3-2.5z" fill="#FBBC05"/><path d="M12 4.1c1.5 0 2.8.5 3.8 1.5l2.9-2.9C16.9 1.1 14.6 0 12 0 8.1 0 4.7 2.2 3.1 5.6l3.3 2.5c.8-2.3 3-4 5.6-4z" fill="#EA4335"/><path d="M26 18.7V1h6c1.1 0 2 .2 2.9.7.9.4 1.5 1.1 2 1.9.5.8.8 1.7.8 2.8s-.3 2-.8 2.8c-.5.8-1.2 1.5-2 1.9-.9.4-1.8.7-2.9.7h-3.3v6.9H26zm2.7-9.4h3.4c.6 0 1.2-.1 1.6-.4.4-.3.8-.6 1-1.1.2-.4.4-.9.4-1.4s-.1-.9-.4-1.4c-.2-.4-.6-.8-1-1.1-.4-.3-1-.4-1.6-.4h-3.4v5.8z" fill="#3C4043"/><path d="M43.4 19.1c-.9 0-1.7-.2-2.4-.5-.7-.4-1.2-.9-1.6-1.5-.4-.6-.6-1.4-.6-2.2 0-.9.2-1.7.7-2.3.5-.6 1.1-1.1 1.9-1.4.8-.3 1.6-.5 2.6-.5.5 0 1 0 1.5.1.4.1.8.1 1.2.2.3.1.6.2.8.3v-.7c0-.8-.3-1.5-.9-2-.6-.5-1.3-.7-2.3-.7-.6 0-1.2.1-1.8.4-.6.3-1 .7-1.4 1.2l-1.8-1.4c.4-.5.8-.9 1.3-1.3.5-.4 1.1-.6 1.7-.8.6-.2 1.3-.3 2-.3 1.8 0 3.2.5 4.1 1.4 1 .9 1.5 2.1 1.5 3.8v7.9h-2.5v-1.6h-.1c-.2.3-.5.6-.9.9-.4.3-.8.5-1.3.7-.5.2-1 .3-1.6.3zm.5-2.2c.7 0 1.3-.2 1.8-.5.5-.3.9-.8 1.2-1.3.3-.5.5-1.1.5-1.8-.4-.2-.8-.4-1.3-.5-.5-.1-1-.2-1.6-.2-1.1 0-1.8.2-2.3.6-.5.4-.7 1-.7 1.6 0 .6.2 1.1.6 1.5.5.4 1.1.6 1.8.6z" fill="#3C4043"/><path d="M56.6 23.4l-.2.3-.1.3h-2.8l.3-.8.5-1 .3-.7.3-.8.4-.8.9-2-5.2-11.9h2.9l3.6 8.6h.1L61.1 6H64l-6.4 15.1c-.1.3-.2.6-.4.9-.1.3-.3.6-.4.8-.1.3-.2.5-.2.6z" fill="#3C4043"/></g></svg>';

			case 'discover':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.2"/><g transform="translate(3.5, 12.5)"><text x="0" y="15" font-size="12" font-weight="900" fill="#231F20" font-family="-apple-system,BlinkMacSystemFont,sans-serif" letter-spacing="0.5">DISC</text><circle cx="37" cy="11" r="5" fill="#F47216"/><text x="44" y="15" font-size="12" font-weight="900" fill="#231F20" font-family="-apple-system,BlinkMacSystemFont,sans-serif" letter-spacing="0.5">VER</text></g></svg>';

			case 'stripe':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#635BFF"/><path d="M28.4 17.7C28.4 16.5 29.4 15.7 31 15.7C32.7 15.7 34.6 16.3 35.9 17L37.1 13.9C35.5 13.2 33.4 12.8 31 12.8C26.4 12.8 23.3 15.2 23.3 18.9C23.3 24.6 31.3 23.7 31.3 26.8C31.3 28.2 29.9 28.9 28.2 28.9C26.1 28.9 23.9 28 22.3 27L21 30.2C22.9 31.3 25.5 31.9 28.2 31.9C33 31.9 36.4 29.5 36.4 25.6C36.4 19.5 28.4 20.6 28.4 17.7Z" fill="white"/></svg>';

			case 'cod':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#059669"/><rect x="15" y="6" width="34" height="17" rx="2" stroke="white" stroke-width="1.8" fill="none"/><circle cx="32" cy="14.5" r="4" stroke="white" stroke-width="1.6" fill="none"/><circle cx="19" cy="14.5" r="1.3" fill="white"/><circle cx="45" cy="14.5" r="1.3" fill="white"/><text x="32" y="31.5" font-size="5.5" fill="white" font-weight="800" letter-spacing="0.3" text-anchor="middle" font-family="-apple-system,BlinkMacSystemFont,sans-serif">CASH ON DELIVERY</text></svg>';

			case 'ssl':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.2"/><g transform="translate(5, 8)"><path d="M10 1L2 4.5V10.5C2 16 5.5 21 10 22.5C14.5 21 18 16 18 10.5V4.5L10 1Z" fill="#10B981"/><path d="M8 11V8.8C8 7.6 8.9 6.8 10 6.8C11.1 6.8 12 7.6 12 8.8V11" stroke="white" stroke-width="1.6" stroke-linecap="round" fill="none"/><rect x="6.8" y="10.5" width="6.4" height="5.2" rx="1.2" fill="white"/><circle cx="10" cy="13" r="0.8" fill="#10B981"/></g><text x="26" y="17" font-size="6" fill="#0F172A" font-weight="900" font-family="-apple-system,BlinkMacSystemFont,sans-serif">256-BIT</text><text x="26" y="25" font-size="5" fill="#059669" font-weight="800" font-family="-apple-system,BlinkMacSystemFont,sans-serif">SSL SECURE</text></svg>';

			case 'norton':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.2"/><g transform="translate(6, 10)"><circle cx="10" cy="10" r="10" fill="#FCB316"/><path d="M5.5 10.5L8.5 13.5L14.5 6.5" stroke="#1A1A1A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></g><text x="30" y="18" font-size="7" fill="#1A1A1A" font-weight="900" font-family="-apple-system,BlinkMacSystemFont,sans-serif">Norton</text><text x="30" y="27" font-size="5.5" fill="#666666" font-weight="800" font-family="-apple-system,BlinkMacSystemFont,sans-serif">SECURED</text></svg>';

			case 'mcafee':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="1.2"/><g transform="translate(6, 9)"><path d="M10 1L2 4.5V11C2 16.5 5.5 21 10 22.5C14.5 21 18 16.5 18 11V4.5L10 1Z" fill="#C01818"/><path d="M6 7.5V15L8 13.5V9.5L10 11.5L12 9.5V13.5L14 15V7.5L10 10.5L6 7.5Z" fill="white"/></g><text x="27" y="17" font-size="6.5" fill="#C01818" font-weight="900" font-family="-apple-system,BlinkMacSystemFont,sans-serif">McAfee</text><text x="27" y="26" font-size="5.5" fill="#1A1A1A" font-weight="800" font-family="-apple-system,BlinkMacSystemFont,sans-serif">SECURE</text></svg>';

			case 'money_back':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#0F172A"/><circle cx="32" cy="17" r="9" stroke="#EAB308" stroke-width="2" fill="none"/><path d="M28 25L30.5 22L32 23.5L33.5 22L36 25L32 24.2L28 25Z" fill="#EAB308"/><text x="32" y="20.5" font-size="8.5" fill="#EAB308" font-weight="900" text-anchor="middle" font-family="sans-serif">30</text><text x="32" y="34.5" font-size="5.5" fill="#E2E8F0" font-weight="700" letter-spacing="0.3" text-anchor="middle" font-family="sans-serif">MONEY BACK</text></svg>';

			case 'free_shipping':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#0F172A"/><path d="M16 11H36V23H16V11Z" stroke="#38BDF8" stroke-width="2" stroke-linejoin="round" fill="none"/><path d="M36 14H44L48 18V23H36V14Z" stroke="#38BDF8" stroke-width="2" stroke-linejoin="round" fill="none"/><circle cx="22" cy="23" r="2.8" fill="#38BDF8"/><circle cx="42" cy="23" r="2.8" fill="#38BDF8"/><path d="M11 14H13M9 17H13M11 20H13" stroke="#38BDF8" stroke-width="2" stroke-linecap="round"/><text x="32" y="34.5" font-size="5.5" fill="#E2E8F0" font-weight="700" letter-spacing="0.3" text-anchor="middle" font-family="sans-serif">FAST SHIPPING</text></svg>';

			case 'support':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#0F172A"/><path d="M20 18V16C20 9.4 25.4 4 32 4C38.6 4 44 9.4 44 16V18" stroke="#A855F7" stroke-width="2" fill="none"/><rect x="18" y="16" width="5" height="8" rx="2" fill="#A855F7"/><rect x="41" y="16" width="5" height="8" rx="2" fill="#A855F7"/><path d="M43 23V26C43 27.1 42.1 28 41 28H35" stroke="#A855F7" stroke-width="2" stroke-linecap="round" fill="none"/><circle cx="33" cy="28" r="1.8" fill="#A855F7"/><text x="32" y="35" font-size="5.5" fill="#E2E8F0" font-weight="700" letter-spacing="0.3" text-anchor="middle" font-family="sans-serif">24/7 SUPPORT</text></svg>';

			case 'satisfaction':
				return '<svg viewBox="0 0 64 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="64" height="40" rx="4" fill="#0F172A"/><path d="M32 7L34.5 12.5L40.5 13.5L36 17.5L37.2 23.5L32 20.5L26.8 23.5L28 17.5L23.5 13.5L29.5 12.5L32 7Z" fill="#F59E0B" stroke="#F59E0B" stroke-width="1.5" stroke-linejoin="round"/><text x="32" y="34.5" font-size="5.5" fill="#E2E8F0" font-weight="700" letter-spacing="0.3" text-anchor="middle" font-family="sans-serif">100% QUALITY</text></svg>';

			default:
				return '';
		}
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 2.1.1
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$style_class     = 'mpd-trust-badges--' . sanitize_html_class( $settings['badge_style'] ?? 'default' );
		$align_class     = 'mpd-trust-badges--align-' . sanitize_html_class( $settings['alignment'] ?? 'center' );
		$hover_class     = 'none' !== ( $settings['hover_animation'] ?? 'none' ) ? 'mpd-trust-badges--hover-' . sanitize_html_class( $settings['hover_animation'] ) : '';
		$show_labels     = 'yes' === ( $settings['show_labels'] ?? 'no' );
		$badges          = $settings['badges_list'] ?? array();
		$show_title      = 'yes' === ( $settings['show_title'] ?? 'no' );
		$show_title_icon = 'yes' === ( $settings['show_title_icon'] ?? 'no' );

		?>
		<div class="mpd-trust-badges-wrapper <?php echo esc_attr( $style_class . ' ' . $align_class . ' ' . $hover_class ); ?>">
			<?php if ( $show_title && ! empty( $settings['title_text'] ) ) : ?>
				<div class="mpd-trust-badges-header">
					<h4 class="mpd-trust-badges-title">
						<?php if ( $show_title_icon ) : ?>
							<span class="mpd-trust-badges-title-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-5c0-.28.22-.5.5-.5s.5.22.5.5v5c0 .28-.22.5-.5.5s-.5-.22-.5-.5zm.5-7.75c-.41 0-.75-.34-.75-.75s.34-.75.75-.75.75.34.75.75-.34.75-.75.75z"/><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
							</span>
						<?php endif; ?>
						<span><?php echo esc_html( $settings['title_text'] ); ?></span>
					</h4>
					<?php if ( ! empty( $settings['subtitle_text'] ) ) : ?>
						<p class="mpd-trust-badges-subtitle"><?php echo esc_html( $settings['subtitle_text'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php elseif ( ! empty( $settings['subtitle_text'] ) ) : ?>
				<div class="mpd-trust-badges-header">
					<p class="mpd-trust-badges-subtitle"><?php echo esc_html( $settings['subtitle_text'] ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $badges ) && is_array( $badges ) ) : ?>
				<ul class="mpd-trust-badges-list">
					<?php
					foreach ( $badges as $index => $badge ) :
						$type      = $badge['badge_type'] ?? 'visa';
						$label     = $badge['badge_label'] ?? '';
						$link_data = $badge['badge_url'] ?? array();
						$has_link  = ! empty( $link_data['url'] );

						$tag       = $has_link ? 'a' : 'div';
						$tag_attrs = $has_link ? 'href="' . esc_url( $link_data['url'] ) . '"' : '';

						if ( $has_link && ! empty( $link_data['is_external'] ) ) {
							$tag_attrs .= ' target="_blank" rel="noopener noreferrer"';
						}
						if ( $has_link && ! empty( $link_data['nofollow'] ) ) {
							$tag_attrs .= ' rel="nofollow"';
						}
						?>
						<li class="mpd-trust-badge-item elementor-repeater-item-<?php echo esc_attr( $badge['_id'] ?? $index ); ?>">
							<<?php echo tag_escape( $tag ); ?> class="<?php echo $has_link ? 'mpd-trust-badge-link' : 'mpd-trust-badge-container'; ?>" <?php echo $tag_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<div class="mpd-trust-badge-card" title="<?php echo esc_attr( $label ?: ucfirst( str_replace( '_', ' ', $type ) ) ); ?>">
									<?php
									if ( 'custom' === $type && ! empty( $badge['custom_image']['url'] ) ) {
										echo '<img src="' . esc_url( $badge['custom_image']['url'] ) . '" alt="' . esc_attr( $label ?: 'Trust Badge' ) . '" loading="lazy" />';
									} else {
										echo self::get_badge_svg( $type ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									}
									?>
								</div>
								<?php if ( $show_labels && ! empty( $label ) ) : ?>
									<span class="mpd-trust-badge-label"><?php echo esc_html( $label ); ?></span>
								<?php endif; ?>
							</<?php echo tag_escape( $tag ); ?>>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php
	}
}
