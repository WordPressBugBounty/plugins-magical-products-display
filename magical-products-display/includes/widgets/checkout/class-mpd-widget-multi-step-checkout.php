<?php
/**
 * Multi-Step Checkout Widget (Pro)
 *
 * A comprehensive multi-step checkout experience with step navigation,
 * form validation, and animated transitions.
 *
 * @package Magical_Shop_Builder
 * @since   2.5.0
 */

namespace MPD\MagicalShopBuilder\Widgets\Checkout;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Multi_Step_Checkout
 *
 * @since 2.5.0
 */
class Multi_Step_Checkout extends Widget_Base {

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
	protected $widget_icon = 'eicon-form-vertical';

	/**
	 * Whether widget is pro only.
	 *
	 * @var bool
	 */
	protected $is_pro_widget = true;

	/**
	 * Current settings for WooCommerce filter.
	 *
	 * @var array
	 */
	private $current_settings = array();

	/**
	 * Static settings for AJAX calls.
	 *
	 * @var array
	 */
	private static $ajax_settings = array();

	/**
	 * Get widget name.
	 *
	 * @since 2.5.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-multi-step-checkout';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.5.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Multi-Step Checkout', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.5.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'multi-step', 'checkout', 'wizard', 'steps', 'woocommerce', 'billing', 'shipping', 'payment', 'form' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.5.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'wc-checkout', 'wc-country-select', 'wc-address-i18n', 'mpd-multi-step-checkout' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.5.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-checkout-widgets', 'mpd-multi-step-checkout' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.5.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		// Steps Configuration Section.
		$this->start_controls_section(
			'section_steps_config',
			array(
				'label' => __( 'Steps Configuration', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'pro_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->is_pro() 
					? '<div class="mpd-pro-active-notice"><i class="eicon-check-circle"></i> ' . __( 'Pro Features Active', 'magical-products-display' ) . '</div>'
					: $this->get_pro_notice( __( 'Multi-Step Checkout is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'step_layout',
			array(
				'label'   => __( 'Step Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'3-steps' => __( '3 Steps (Billing → Shipping & Review → Payment)', 'magical-products-display' ),
					'4-steps' => __( '4 Steps (Billing → Shipping → Review → Payment)', 'magical-products-display' ),
					'2-steps' => __( '2 Steps (Information → Payment)', 'magical-products-display' ),
				),
				'default' => '3-steps',
			)
		);

		$this->add_control(
			'step_1_title',
			array(
				'label'       => __( 'Step 1 Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Billing', 'magical-products-display' ),
				'placeholder' => __( 'Enter step title', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'step_1_icon',
			array(
				'label'   => __( 'Step 1 Icon', 'magical-products-display' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-user',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'step_2_title',
			array(
				'label'       => __( 'Step 2 Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Shipping', 'magical-products-display' ),
				'placeholder' => __( 'Enter step title', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'step_2_icon',
			array(
				'label'   => __( 'Step 2 Icon', 'magical-products-display' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-truck',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'step_3_title',
			array(
				'label'       => __( 'Step 3 Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Review', 'magical-products-display' ),
				'placeholder' => __( 'Enter step title', 'magical-products-display' ),
				'condition'   => array(
					'step_layout' => '4-steps',
				),
			)
		);

		$this->add_control(
			'step_3_icon',
			array(
				'label'     => __( 'Step 3 Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-clipboard-list',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'step_layout' => '4-steps',
				),
			)
		);

		$this->add_control(
			'step_final_title',
			array(
				'label'       => __( 'Final Step Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Payment', 'magical-products-display' ),
				'placeholder' => __( 'Enter step title', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'step_final_icon',
			array(
				'label'   => __( 'Final Step Icon', 'magical-products-display' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-credit-card',
					'library' => 'fa-solid',
				),
			)
		);

		$this->end_controls_section();

		// Progress Bar Section.
		$this->start_controls_section(
			'section_progress',
			array(
				'label' => __( 'Progress Indicator', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'progress_style',
			array(
				'label'   => __( 'Progress Style', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'circles'   => __( 'Circles with Numbers', 'magical-products-display' ),
					'icons'     => __( 'Circles with Icons', 'magical-products-display' ),
					'bar'       => __( 'Progress Bar', 'magical-products-display' ),
					'pills'     => __( 'Pills/Tabs', 'magical-products-display' ),
					'arrows'    => __( 'Arrow Steps', 'magical-products-display' ),
					'minimalist' => __( 'Minimalist Dots', 'magical-products-display' ),
				),
				'default' => 'circles',
			)
		);

		$this->add_control(
			'show_step_labels',
			array(
				'label'        => __( 'Show Step Labels', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_step_numbers',
			array(
				'label'        => __( 'Show Step Numbers', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'progress_style!' => array( 'bar', 'minimalist' ),
				),
			)
		);

		$this->add_control(
			'clickable_steps',
			array(
				'label'        => __( 'Clickable Steps', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Allow users to click on completed steps to go back.', 'magical-products-display' ),
			)
		);

		$this->add_responsive_control(
			'progress_position',
			array(
				'label'   => __( 'Progress Position', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'top'   => __( 'Top', 'magical-products-display' ),
					'left'  => __( 'Left Sidebar', 'magical-products-display' ),
					'right' => __( 'Right Sidebar', 'magical-products-display' ),
				),
				'default' => 'top',
			)
		);

		$this->end_controls_section();

		// Navigation Section.
		$this->start_controls_section(
			'section_navigation',
			array(
				'label' => __( 'Navigation', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'prev_button_text',
			array(
				'label'   => __( 'Previous Button Text', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Back', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'next_button_text',
			array(
				'label'   => __( 'Next Button Text', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Continue', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'place_order_text',
			array(
				'label'   => __( 'Place Order Button Text', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Place Order', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_nav_icons',
			array(
				'label'        => __( 'Show Navigation Icons', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'nav_button_style',
			array(
				'label'   => __( 'Button Style', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'filled'   => __( 'Filled', 'magical-products-display' ),
					'outline'  => __( 'Outline', 'magical-products-display' ),
					'text'     => __( 'Text Only', 'magical-products-display' ),
				),
				'default' => 'filled',
			)
		);

		$this->end_controls_section();

		// Form Fields Section.
		$this->start_controls_section(
			'section_form_fields',
			array(
				'label' => __( 'Form Fields', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_login_form',
			array(
				'label'        => __( 'Show Login Form', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Show login form for returning customers on first step.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_coupon_form',
			array(
				'label'        => __( 'Show Coupon Form', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'coupon_position',
			array(
				'label'     => __( 'Coupon Position', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'step-1' => __( 'Step 1 (Billing)', 'magical-products-display' ),
					'review' => __( 'Review Step', 'magical-products-display' ),
					'payment' => __( 'Payment Step', 'magical-products-display' ),
				),
				'default'   => 'review',
				'condition' => array(
					'show_coupon_form' => 'yes',
				),
			)
		);

		$this->add_control(
			'ship_to_different_default',
			array(
				'label'        => __( 'Ship to Different Address by Default', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_order_notes',
			array(
				'label'        => __( 'Show Order Notes', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		// Billing Fields Heading.
		$this->add_control(
			'billing_fields_heading',
			array(
				'label'     => __( 'Billing Fields', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'billing_name_mode',
			array(
				'label'   => __( 'Name Field Mode', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default'   => __( 'First & Last Name (Default)', 'magical-products-display' ),
					'full_name' => __( 'Single Full Name Field', 'magical-products-display' ),
				),
				'default' => 'default',
			)
		);

		$this->add_control(
			'billing_full_name_label',
			array(
				'label'     => __( 'Full Name Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Full Name', 'magical-products-display' ),
				'condition' => array(
					'billing_name_mode' => 'full_name',
				),
			)
		);

		$this->add_control(
			'show_billing_company',
			array(
				'label'        => __( 'Show Company Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'billing_company_required',
			array(
				'label'        => __( 'Company Required', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'show_billing_company' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_billing_address_2',
			array(
				'label'        => __( 'Show Address Line 2', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_billing_phone',
			array(
				'label'        => __( 'Show Phone Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'billing_phone_required',
			array(
				'label'        => __( 'Phone Required', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'show_billing_phone' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_billing_country',
			array(
				'label'        => __( 'Show Country Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Hide country selector if selling to single country only.', 'magical-products-display' ),
			)
		);

		// Shipping Fields Heading.
		$this->add_control(
			'shipping_fields_heading',
			array(
				'label'     => __( 'Shipping Fields', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'shipping_name_mode',
			array(
				'label'   => __( 'Name Field Mode', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default'   => __( 'First & Last Name (Default)', 'magical-products-display' ),
					'full_name' => __( 'Single Full Name Field', 'magical-products-display' ),
				),
				'default' => 'default',
			)
		);

		$this->add_control(
			'shipping_full_name_label',
			array(
				'label'       => __( 'Full Name Label', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Full Name', 'magical-products-display' ),
				'placeholder' => __( 'Full Name', 'magical-products-display' ),
				'condition'   => array(
					'shipping_name_mode' => 'full_name',
				),
			)
		);

		$this->add_control(
			'show_shipping_company',
			array(
				'label'        => __( 'Show Company Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_shipping_address_2',
			array(
				'label'        => __( 'Show Address Line 2', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_shipping_country',
			array(
				'label'        => __( 'Show Country Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Hide country selector if shipping to single country only.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Order Summary Section.
		$this->start_controls_section(
			'section_order_summary',
			array(
				'label' => __( 'Order Summary', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_order_summary_sidebar',
			array(
				'label'        => __( 'Show Sticky Order Summary', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Show a sticky order summary sidebar on desktop.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_product_images',
			array(
				'label'        => __( 'Show Product Images', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'product_image_size',
			array(
				'label'     => __( 'Product Image Size', 'magical-products-display' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 30,
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 50,
					'unit' => 'px',
				),
				'condition' => array(
					'show_product_images' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-product-image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'show_quantity_controls',
			array(
				'label'        => __( 'Show Quantity Controls', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'Allow customers to update quantities during checkout.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Validation Section.
		$this->start_controls_section(
			'section_validation',
			array(
				'label' => __( 'Validation & UX', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'validation_mode',
			array(
				'label'   => __( 'Validation Mode', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'on-next'   => __( 'On Next Button Click', 'magical-products-display' ),
					'real-time' => __( 'Real-time (On Field Blur)', 'magical-products-display' ),
					'both'      => __( 'Both', 'magical-products-display' ),
				),
				'default' => 'both',
			)
		);

		$this->add_control(
			'scroll_to_error',
			array(
				'label'        => __( 'Scroll to First Error', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'transition_effect',
			array(
				'label'   => __( 'Step Transition', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'slide'  => __( 'Slide', 'magical-products-display' ),
					'fade'   => __( 'Fade', 'magical-products-display' ),
					'none'   => __( 'None', 'magical-products-display' ),
				),
				'default' => 'slide',
			)
		);

		$this->add_control(
			'transition_duration',
			array(
				'label'     => __( 'Transition Duration (ms)', 'magical-products-display' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 1000,
				'step'      => 50,
				'default'   => 300,
				'condition' => array(
					'transition_effect!' => 'none',
				),
			)
		);

		$this->add_control(
			'keyboard_navigation',
			array(
				'label'        => __( 'Keyboard Navigation', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Allow Enter key to proceed and Escape to go back.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @since 2.5.0
	 *
	 * @return void
	 */
	protected function register_style_controls() {
		// Container Style Section.
		$this->start_controls_section(
			'section_container_style',
			array(
				'label' => __( 'Container', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'container_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-multi-step-checkout' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-multi-step-checkout' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-multi-step-checkout',
			)
		);

		$this->add_responsive_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-multi-step-checkout' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_shadow',
				'selector' => '{{WRAPPER}} .mpd-multi-step-checkout',
			)
		);

		$this->end_controls_section();

		// Progress Indicator Style Section.
		$this->start_controls_section(
			'section_progress_style',
			array(
				'label' => __( 'Progress Indicator', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'progress_spacing',
			array(
				'label'      => __( 'Bottom Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 40,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-msc-progress' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'step_circle_size',
			array(
				'label'     => __( 'Step Circle Size', 'magical-products-display' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 24,
						'max' => 80,
					),
				),
				'default'   => array(
					'size' => 40,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-step-circle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'progress_style' => array( 'circles', 'icons' ),
				),
			)
		);

		// Active Step Colors.
		$this->add_control(
			'step_colors_heading',
			array(
				'label'     => __( 'Step Colors', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'step_active_bg',
			array(
				'label'     => __( 'Active Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-step.is-active .mpd-msc-step-circle,
					 {{WRAPPER}} .mpd-msc-step.is-completed .mpd-msc-step-circle' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-msc-progress-bar-fill' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'step_active_color',
			array(
				'label'     => __( 'Active Text/Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-step.is-active .mpd-msc-step-circle,
					 {{WRAPPER}} .mpd-msc-step.is-completed .mpd-msc-step-circle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'step_inactive_bg',
			array(
				'label'     => __( 'Inactive Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-step:not(.is-active):not(.is-completed) .mpd-msc-step-circle' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'step_inactive_color',
			array(
				'label'     => __( 'Inactive Text/Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-step:not(.is-active):not(.is-completed) .mpd-msc-step-circle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'step_connector_color',
			array(
				'label'     => __( 'Connector Line Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-step-connector' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-msc-progress-bar' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'step_label_typography',
				'label'    => __( 'Step Label Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-msc-step-label',
			)
		);

		$this->end_controls_section();

		// Step Content Style Section.
		$this->start_controls_section(
			'section_step_content_style',
			array(
				'label' => __( 'Step Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'step_content_bg',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-step-content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'step_content_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => 30,
					'right'  => 30,
					'bottom' => 30,
					'left'   => 30,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-msc-step-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'step_content_border',
				'selector' => '{{WRAPPER}} .mpd-msc-step-content',
			)
		);

		$this->add_responsive_control(
			'step_content_border_radius',
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
					'{{WRAPPER}} .mpd-msc-step-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'step_content_shadow',
				'selector' => '{{WRAPPER}} .mpd-msc-step-content',
			)
		);

		$this->add_control(
			'step_title_heading',
			array(
				'label'     => __( 'Step Title', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'step_title_color',
			array(
				'label'     => __( 'Title Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-step-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'step_title_typography',
				'selector' => '{{WRAPPER}} .mpd-msc-step-title',
			)
		);

		$this->end_controls_section();

		// Form Fields Style Section.
		$this->start_controls_section(
			'section_form_style',
			array(
				'label' => __( 'Form Fields', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Label Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-multi-step-checkout label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => __( 'Label Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-multi-step-checkout label',
			)
		);

		$this->add_responsive_control(
			'label_spacing',
			array(
				'label'      => __( 'Label Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'default'    => array(
					'size' => 5,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-multi-step-checkout label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'input_style_heading',
			array(
				'label'     => __( 'Input Fields', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'input_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-multi-step-checkout input.input-text,
					 {{WRAPPER}} .mpd-multi-step-checkout select,
					 {{WRAPPER}} .mpd-multi-step-checkout textarea' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-multi-step-checkout input.input-text,
					 {{WRAPPER}} .mpd-multi-step-checkout select,
					 {{WRAPPER}} .mpd-multi-step-checkout textarea' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_border_color',
			array(
				'label'     => __( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-multi-step-checkout input.input-text,
					 {{WRAPPER}} .mpd-multi-step-checkout select,
					 {{WRAPPER}} .mpd-multi-step-checkout textarea' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_focus_border_color',
			array(
				'label'     => __( 'Focus Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-multi-step-checkout input.input-text:focus,
					 {{WRAPPER}} .mpd-multi-step-checkout select:focus,
					 {{WRAPPER}} .mpd-multi-step-checkout textarea:focus' => 'border-color: {{VALUE}}; box-shadow: 0 0 0 3px {{VALUE}}26;',
				),
			)
		);

		$this->add_responsive_control(
			'input_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => 8,
					'right'  => 8,
					'bottom' => 8,
					'left'   => 8,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-multi-step-checkout input.input-text,
					 {{WRAPPER}} .mpd-multi-step-checkout select,
					 {{WRAPPER}} .mpd-multi-step-checkout textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'input_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => 12,
					'right'  => 14,
					'bottom' => 12,
					'left'   => 14,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-multi-step-checkout input.input-text,
					 {{WRAPPER}} .mpd-multi-step-checkout select,
					 {{WRAPPER}} .mpd-multi-step-checkout textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Navigation Buttons Style Section.
		$this->start_controls_section(
			'section_nav_buttons_style',
			array(
				'label' => __( 'Navigation Buttons', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'nav_button_typography',
				'selector' => '{{WRAPPER}} .mpd-msc-nav-btn',
			)
		);

		$this->add_responsive_control(
			'nav_button_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => 14,
					'right'  => 28,
					'bottom' => 14,
					'left'   => 28,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-msc-nav-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'nav_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => 8,
					'right'  => 8,
					'bottom' => 8,
					'left'   => 8,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-msc-nav-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Next Button Colors.
		$this->add_control(
			'next_btn_heading',
			array(
				'label'     => __( 'Next/Submit Button', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'next_btn_tabs' );

		$this->start_controls_tab(
			'next_btn_normal',
			array(
				'label' => __( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'next_btn_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-nav-next,
					 {{WRAPPER}} .mpd-msc-nav-submit' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'next_btn_bg',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-nav-next,
					 {{WRAPPER}} .mpd-msc-nav-submit' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'next_btn_hover',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'next_btn_hover_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-nav-next:hover,
					 {{WRAPPER}} .mpd-msc-nav-submit:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'next_btn_hover_bg',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-nav-next:hover,
					 {{WRAPPER}} .mpd-msc-nav-submit:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Previous Button Colors.
		$this->add_control(
			'prev_btn_heading',
			array(
				'label'     => __( 'Previous Button', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->start_controls_tabs( 'prev_btn_tabs' );

		$this->start_controls_tab(
			'prev_btn_normal',
			array(
				'label' => __( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'prev_btn_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-nav-prev' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'prev_btn_bg',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-nav-prev' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'prev_btn_hover',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'prev_btn_hover_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-nav-prev:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'prev_btn_hover_bg',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-nav-prev:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Order Summary Sidebar Style.
		$this->start_controls_section(
			'section_sidebar_style',
			array(
				'label'     => __( 'Order Summary Sidebar', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_order_summary_sidebar' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar_bg',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-order-summary' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'sidebar_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => 25,
					'right'  => 25,
					'bottom' => 25,
					'left'   => 25,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-msc-order-summary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'sidebar_border_radius',
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
					'{{WRAPPER}} .mpd-msc-order-summary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'sidebar_shadow',
				'selector' => '{{WRAPPER}} .mpd-msc-order-summary',
			)
		);

		$this->end_controls_section();

		// Error Messages Style.
		$this->start_controls_section(
			'section_error_style',
			array(
				'label' => __( 'Error Messages', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'error_color',
			array(
				'label'     => __( 'Error Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-msc-field-error,
					 {{WRAPPER}} .woocommerce-error,
					 {{WRAPPER}} .mpd-multi-step-checkout .form-row.woocommerce-invalid label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'error_border_color',
			array(
				'label'     => __( 'Error Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-multi-step-checkout .form-row.woocommerce-invalid input,
					 {{WRAPPER}} .mpd-multi-step-checkout .form-row.woocommerce-invalid select,
					 {{WRAPPER}} .mpd-multi-step-checkout .form-row.woocommerce-invalid textarea' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'error_bg_color',
			array(
				'label'     => __( 'Error Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-error' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @since 2.5.0
	 *
	 * @return void
	 */
	protected function render() {
		// Check WooCommerce.
		if ( ! $this->is_woocommerce_active() ) {
			$this->render_wc_required_notice();
			return;
		}

		// Check if Pro is active.
		if ( ! $this->is_pro() ) {
			$this->render_pro_message( __( 'Multi-Step Checkout', 'magical-products-display' ) );
			return;
		}

		$settings = $this->get_settings_for_display();
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Store settings for WooCommerce filter.
		$this->current_settings = $settings;
		self::$ajax_settings    = $settings;

		// Store image settings in transient for AJAX calls.
		if ( 'yes' === $settings['show_product_images'] ) {
			$image_settings = array(
				'show_images'      => 'yes',
				'image_size'       => isset( $settings['product_image_size']['size'] ) ? absint( $settings['product_image_size']['size'] ) : 50,
				'show_qty_controls' => $settings['show_quantity_controls'] ?? 'no',
			);
			set_transient( 'mpd_msc_image_settings', $image_settings, HOUR_IN_SECONDS );
		}

		// Add WooCommerce filter to modify checkout fields for full name mode.
		add_filter( 'woocommerce_checkout_fields', array( $this, 'filter_checkout_fields_for_validation' ), 999 );

		// Add WooCommerce filter to add product images in order review.
		if ( 'yes' === $settings['show_product_images'] ) {
			add_filter( 'woocommerce_cart_item_name', array( __CLASS__, 'add_product_image_to_cart_item_static' ), 10, 3 );
		}

		// Get step configuration.
		$step_layout = $settings['step_layout'];
		$steps = $this->get_steps_config( $settings );
		$progress_position = ! empty( $settings['progress_position'] ) ? $settings['progress_position'] : 'top';

		// Widget ID for unique targeting.
		$widget_id = $this->get_id();

		// Data attributes for JS.
		$data_attrs = array(
			'step-layout'         => $step_layout,
			'validation-mode'     => $settings['validation_mode'],
			'scroll-to-error'     => $settings['scroll_to_error'],
			'transition'          => $settings['transition_effect'],
			'transition-duration' => $settings['transition_duration'],
			'keyboard-nav'        => $settings['keyboard_navigation'],
			'clickable-steps'     => $settings['clickable_steps'],
			'checkout-nonce'      => wp_create_nonce( 'mpd-checkout-nonce' ),
		);

		$data_string = '';
		foreach ( $data_attrs as $key => $value ) {
			$data_string .= ' data-' . $key . '="' . esc_attr( $value ) . '"';
		}

		// Build wrapper classes.
		$wrapper_classes = array(
			'mpd-multi-step-checkout',
			'mpd-msc-layout-' . $step_layout,
			'mpd-msc-progress-pos-' . $progress_position,
		);

		// Add editor mode class for styling.
		if ( $is_editor ) {
			$wrapper_classes[] = 'is-editor-mode';
		}
		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"<?php echo $data_string; ?>>
			
			<?php // Progress Indicator. ?>
			<div class="mpd-msc-progress mpd-msc-progress-<?php echo esc_attr( $settings['progress_style'] ); ?>">
				<?php $this->render_progress_indicator( $steps, $settings ); ?>
			</div>

			<?php // Editor mode notice. ?>
			<?php if ( $is_editor ) : ?>
			<div class="mpd-msc-editor-notice">
				<span class="mpd-msc-editor-notice-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
				</span>
				<span class="mpd-msc-editor-notice-text">
					<?php esc_html_e( 'Editor Preview: All steps are displayed simultaneously for design purposes. On the frontend, only one step will be visible at a time with step-by-step navigation.', 'magical-products-display' ); ?>
				</span>
			</div>
			<?php endif; ?>

			<?php // Main Content Area. ?>
			<div class="mpd-msc-main <?php echo 'yes' === $settings['show_order_summary_sidebar'] ? 'has-sidebar' : ''; ?>">
				
				<?php // Steps Container. ?>
				<div class="mpd-msc-steps-wrapper">
					<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
						
						<?php
						// Render each step.
						foreach ( $steps as $index => $step ) {
							$step_class = 'mpd-msc-step-panel';
							$step_class .= $index === 0 ? ' is-active' : '';
							$panel_id = 'mpd-step-panel-' . $this->get_id() . '-' . ( $index + 1 );
							?>
							<div class="<?php echo esc_attr( $step_class ); ?>" 
								 id="<?php echo esc_attr( $panel_id ); ?>"
								 data-step="<?php echo esc_attr( $index + 1 ); ?>"
								 role="tabpanel"
								 <?php echo $index !== 0 ? 'hidden' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static HTML attribute ?>>
								<div class="mpd-msc-step-content">
									<h3 class="mpd-msc-step-title" tabindex="-1"><?php echo esc_html( $step['title'] ); ?></h3>
									<?php $this->render_step_content( $step['type'], $settings, $is_editor ); ?>
								</div>
							</div>
							<?php
						}

						// Hidden fields for WooCommerce.
						wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' );
						?>
						<input type="hidden" name="mpd_multi_step" value="1" />
						
						<?php // Navigation Buttons. ?>
						<div class="mpd-msc-navigation">
							<button type="button" class="mpd-msc-nav-btn mpd-msc-nav-prev" style="display: none;">
								<?php if ( 'yes' === $settings['show_nav_icons'] ) : ?>
									<span class="mpd-msc-nav-icon mpd-msc-nav-icon-prev">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<polyline points="15,18 9,12 15,6"></polyline>
										</svg>
									</span>
								<?php endif; ?>
								<span class="mpd-msc-nav-text"><?php echo esc_html( $settings['prev_button_text'] ); ?></span>
							</button>
							
							<button type="button" class="mpd-msc-nav-btn mpd-msc-nav-next">
								<span class="mpd-msc-nav-text"><?php echo esc_html( $settings['next_button_text'] ); ?></span>
								<?php if ( 'yes' === $settings['show_nav_icons'] ) : ?>
									<span class="mpd-msc-nav-icon mpd-msc-nav-icon-next">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<polyline points="9,18 15,12 9,6"></polyline>
										</svg>
									</span>
								<?php endif; ?>
							</button>
							
							<button type="submit" class="mpd-msc-nav-btn mpd-msc-nav-submit" style="display: none;">
								<span class="mpd-msc-nav-text"><?php echo esc_html( $settings['place_order_text'] ); ?></span>
								<?php if ( 'yes' === $settings['show_nav_icons'] ) : ?>
									<span class="mpd-msc-nav-icon mpd-msc-nav-icon-submit">
										<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
											<polyline points="20,6 9,17 4,12"></polyline>
										</svg>
									</span>
								<?php endif; ?>
							</button>
						</div>
					</form>
				</div>

				<?php // Order Summary Sidebar. ?>
				<?php if ( 'yes' === $settings['show_order_summary_sidebar'] ) : ?>
					<div class="mpd-msc-order-summary">
						<h4 class="mpd-msc-sidebar-title"><?php esc_html_e( 'Order Summary', 'magical-products-display' ); ?></h4>
						<?php $this->render_order_summary( $settings, $is_editor ); ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
		<?php
	}

	/**
	 * Get steps configuration based on layout.
	 *
	 * @since 2.5.0
	 *
	 * @param array $settings Widget settings.
	 * @return array Steps configuration.
	 */
	private function get_steps_config( $settings ) {
		$layout = $settings['step_layout'];
		$steps = array();

		switch ( $layout ) {
			case '2-steps':
				$steps = array(
					array(
						'title' => $settings['step_1_title'],
						'icon'  => $settings['step_1_icon'],
						'type'  => 'billing_shipping',
					),
					array(
						'title' => $settings['step_final_title'],
						'icon'  => $settings['step_final_icon'],
						'type'  => 'payment',
					),
				);
				break;

			case '3-steps':
				$steps = array(
					array(
						'title' => $settings['step_1_title'],
						'icon'  => $settings['step_1_icon'],
						'type'  => 'billing',
					),
					array(
						'title' => $settings['step_2_title'],
						'icon'  => $settings['step_2_icon'],
						'type'  => 'shipping_review',
					),
					array(
						'title' => $settings['step_final_title'],
						'icon'  => $settings['step_final_icon'],
						'type'  => 'payment',
					),
				);
				break;

			case '4-steps':
			default:
				$steps = array(
					array(
						'title' => $settings['step_1_title'],
						'icon'  => $settings['step_1_icon'],
						'type'  => 'billing',
					),
					array(
						'title' => $settings['step_2_title'],
						'icon'  => $settings['step_2_icon'],
						'type'  => 'shipping',
					),
					array(
						'title' => $settings['step_3_title'],
						'icon'  => $settings['step_3_icon'],
						'type'  => 'review',
					),
					array(
						'title' => $settings['step_final_title'],
						'icon'  => $settings['step_final_icon'],
						'type'  => 'payment',
					),
				);
				break;
		}

		return $steps;
	}

	/**
	 * Render progress indicator.
	 *
	 * @since 2.5.0
	 *
	 * @param array $steps    Steps configuration.
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_progress_indicator( $steps, $settings ) {
		$style = $settings['progress_style'];
		$total_steps = count( $steps );

		if ( 'bar' === $style ) {
			// Progress Bar Style.
			?>
			<div class="mpd-msc-progress-bar">
				<div class="mpd-msc-progress-bar-fill" style="width: <?php echo esc_attr( 100 / $total_steps ); ?>%;"></div>
			</div>
			<div class="mpd-msc-progress-text">
				<span class="mpd-msc-progress-current">1</span> / <span class="mpd-msc-progress-total"><?php echo esc_html( $total_steps ); ?></span>
			</div>
			<?php
		} elseif ( 'minimalist' === $style ) {
			// Minimalist Dots Style.
			?>
			<div class="mpd-msc-dots">
				<?php foreach ( $steps as $index => $step ) : ?>
					<span class="mpd-msc-dot <?php echo esc_attr( $index === 0 ? 'is-active' : '' ); ?>" data-step="<?php echo esc_attr( $index + 1 ); ?>"></span>
				<?php endforeach; ?>
			</div>
			<?php
		} else {
			// Circles, Icons, Pills, Arrows Styles.
			?>
			<div class="mpd-msc-steps" role="tablist" aria-label="<?php esc_attr_e( 'Checkout steps', 'magical-products-display' ); ?>">
				<?php foreach ( $steps as $index => $step ) : ?>
					<?php if ( $index > 0 ) : ?>
						<div class="mpd-msc-step-connector" aria-hidden="true"></div>
					<?php endif; ?>
					<div class="mpd-msc-step <?php echo esc_attr( $index === 0 ? 'is-active' : '' ); ?>" 
						 data-step="<?php echo esc_attr( $index + 1 ); ?>"
						 role="tab"
						 aria-selected="<?php echo esc_attr( $index === 0 ? 'true' : 'false' ); ?>"
						 <?php echo $index === 0 ? 'aria-current="step"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static safe value ?>>
						<div class="mpd-msc-step-circle">
							<?php
							$has_icon = 'icons' === $style && ! empty( $step['icon'] ) && ! empty( $step['icon']['value'] );
							if ( $has_icon ) :
								// Use Icons_Manager to render any icon type - it handles Font Awesome and SVG properly.
								\Elementor\Icons_Manager::render_icon( $step['icon'], array( 'aria-hidden' => 'true', 'class' => 'mpd-msc-step-icon' ) );
							elseif ( 'yes' === $settings['show_step_numbers'] || 'icons' !== $style ) : ?>
								<span class="mpd-msc-step-number" aria-hidden="true"><?php echo esc_html( $index + 1 ); ?></span>
							<?php endif; ?>
							<span class="mpd-msc-step-check" aria-hidden="true">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
									<polyline points="20,6 9,17 4,12"></polyline>
								</svg>
							</span>
						</div>
						<?php if ( 'yes' === $settings['show_step_labels'] ) : ?>
							<span class="mpd-msc-step-label"><?php echo esc_html( $step['title'] ); ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
		}
	}

	/**
	 * Render step content.
	 *
	 * @since 2.5.0
	 *
	 * @param string $step_type Step type.
	 * @param array  $settings  Widget settings.
	 * @param bool   $is_editor Whether in editor mode.
	 * @return void
	 */
	private function render_step_content( $step_type, $settings, $is_editor ) {
		switch ( $step_type ) {
			case 'billing':
				$this->render_billing_step( $settings, $is_editor );
				break;

			case 'billing_shipping':
				$this->render_billing_step( $settings, $is_editor );
				$this->render_shipping_step( $settings, $is_editor );
				break;

			case 'shipping':
				$this->render_shipping_step( $settings, $is_editor );
				break;

			case 'shipping_review':
				$this->render_shipping_step( $settings, $is_editor );
				if ( 'yes' === $settings['show_order_notes'] ) {
					$this->render_order_notes( $settings );
				}
				$this->render_order_review_inline( $settings, $is_editor );
				break;

			case 'review':
				if ( 'yes' === $settings['show_order_notes'] ) {
					$this->render_order_notes( $settings );
				}
				$this->render_order_review_inline( $settings, $is_editor );
				break;

			case 'payment':
				if ( 'review' !== $settings['coupon_position'] && 'yes' === $settings['show_coupon_form'] ) {
					$this->render_coupon_form( $settings );
				}
				$this->render_payment_step( $settings, $is_editor );
				break;
		}
	}

	/**
	 * Render billing step.
	 *
	 * @since 2.5.0
	 *
	 * @param array $settings  Widget settings.
	 * @param bool  $is_editor Whether in editor mode.
	 * @return void
	 */
	private function render_billing_step( $settings, $is_editor ) {
		?>
		<div class="mpd-msc-billing-wrapper">
			<?php if ( 'yes' === $settings['show_login_form'] && ! is_user_logged_in() && 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) : ?>
				<div class="mpd-msc-login-reminder">
					<p class="mpd-msc-login-text">
						<?php esc_html_e( 'Returning customer?', 'magical-products-display' ); ?>
						<a href="#" class="mpd-msc-login-toggle showlogin"><?php esc_html_e( 'Click here to login', 'magical-products-display' ); ?></a>
					</p>
					<div class="mpd-msc-login-form" style="display: none;">
						<?php woocommerce_login_form( array( 'redirect' => wc_get_checkout_url() ) ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( 'step-1' === $settings['coupon_position'] && 'yes' === $settings['show_coupon_form'] ) : ?>
				<?php $this->render_coupon_form( $settings ); ?>
			<?php endif; ?>

			<div class="woocommerce-billing-fields">
				<?php
				$checkout = WC()->checkout();
				$fields = $checkout->get_checkout_fields( 'billing' );

				// Apply field customizations.
				$fields = $this->customize_billing_fields( $fields, $settings );

				if ( $fields ) :
					?>
					<div class="woocommerce-billing-fields__field-wrapper">
						<?php
						foreach ( $fields as $key => $field ) {
							woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
						}
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Customize billing fields based on widget settings.
	 *
	 * @since 2.5.0
	 *
	 * @param array $fields   Billing fields.
	 * @param array $settings Widget settings.
	 * @return array Modified billing fields.
	 */
	private function customize_billing_fields( $fields, $settings ) {
		// Handle name field mode.
		if ( 'full_name' === $settings['billing_name_mode'] ) {
			// Modify first name to be full name.
			if ( isset( $fields['billing_first_name'] ) ) {
				$fields['billing_first_name']['label'] = ! empty( $settings['billing_full_name_label'] ) 
					? $settings['billing_full_name_label'] 
					: __( 'Full Name', 'magical-products-display' );
				$fields['billing_first_name']['class'] = array( 'form-row-wide' );
			}
			// Make last name not required and hide it.
			if ( isset( $fields['billing_last_name'] ) ) {
				$fields['billing_last_name']['required'] = false;
				$fields['billing_last_name']['class'] = array( 'form-row-wide', 'mpd-hidden-field' );
				$fields['billing_last_name']['default'] = ' '; // Provide space to pass validation.
			}
		}

		// Handle company field - add if user wants to show but doesn't exist.
		if ( 'yes' === $settings['show_billing_company'] ) {
			if ( ! isset( $fields['billing_company'] ) ) {
				// Find position after first_name/last_name
				$new_fields = array();
				foreach ( $fields as $key => $field ) {
					$new_fields[ $key ] = $field;
					if ( 'billing_last_name' === $key || ( 'full_name' === $settings['billing_name_mode'] && 'billing_first_name' === $key ) ) {
						$new_fields['billing_company'] = array(
							'label'       => __( 'Company name', 'magical-products-display' ),
							'class'       => array( 'form-row-wide' ),
							'autocomplete' => 'organization',
							'priority'    => 30,
							'required'    => 'yes' === $settings['billing_company_required'],
						);
					}
				}
				$fields = $new_fields;
			} elseif ( 'yes' === $settings['billing_company_required'] ) {
				$fields['billing_company']['required'] = true;
			}
		} elseif ( isset( $fields['billing_company'] ) ) {
			unset( $fields['billing_company'] );
		}

		// Hide country field if disabled.
		if ( 'yes' !== $settings['show_billing_country'] && isset( $fields['billing_country'] ) ) {
			$fields['billing_country']['class'] = array( 'form-row-wide', 'mpd-hidden-field' );
		}

		// Hide address line 2 if disabled.
		if ( 'yes' !== $settings['show_billing_address_2'] && isset( $fields['billing_address_2'] ) ) {
			unset( $fields['billing_address_2'] );
		}

		// Handle phone field.
		if ( 'yes' !== $settings['show_billing_phone'] && isset( $fields['billing_phone'] ) ) {
			unset( $fields['billing_phone'] );
		} elseif ( 'yes' === $settings['billing_phone_required'] && isset( $fields['billing_phone'] ) ) {
			$fields['billing_phone']['required'] = true;
		}

		return $fields;
	}

	/**
	 * Render shipping step.
	 *
	 * @since 2.5.0
	 *
	 * @param array $settings  Widget settings.
	 * @param bool  $is_editor Whether in editor mode.
	 * @return void
	 */
	private function render_shipping_step( $settings, $is_editor ) {
		$default_checked = 'yes' === $settings['ship_to_different_default'];
		?>
		<div class="mpd-msc-shipping-wrapper woocommerce-shipping-fields">
			<h4 id="ship-to-different-address">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input id="ship-to-different-address-checkbox" 
						   class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" 
						   <?php checked( $default_checked ); ?> 
						   type="checkbox" 
						   name="ship_to_different_address" 
						   value="1" />
					<span><?php esc_html_e( 'Ship to a different address?', 'magical-products-display' ); ?></span>
				</label>
			</h4>

			<div class="shipping_address" <?php echo ! $default_checked ? 'style="display: none;"' : ''; ?>>
				<?php
				$checkout = WC()->checkout();
				$fields = $checkout->get_checkout_fields( 'shipping' );

				// Apply field customizations from widget settings.
				$fields = $this->customize_shipping_fields( $fields, $settings );

				if ( $fields ) :
					?>
					<div class="woocommerce-shipping-fields__field-wrapper">
						<?php
						foreach ( $fields as $key => $field ) {
							woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
						}
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Customize shipping fields based on widget settings.
	 *
	 * @since 2.5.0
	 *
	 * @param array $fields   Shipping fields.
	 * @param array $settings Widget settings.
	 * @return array Modified fields.
	 */
	private function customize_shipping_fields( $fields, $settings ) {
		// Handle name mode (full name vs separate first/last).
		$name_mode = ! empty( $settings['shipping_name_mode'] ) ? $settings['shipping_name_mode'] : 'default';

		if ( 'full_name' === $name_mode ) {
			// Merge first and last name into a single full name field.
			if ( isset( $fields['shipping_first_name'] ) ) {
				$full_name_label = ! empty( $settings['shipping_full_name_label'] ) ? $settings['shipping_full_name_label'] : __( 'Full Name', 'magical-products-display' );

				$fields['shipping_first_name']['label'] = $full_name_label;
				$fields['shipping_first_name']['class'] = array( 'form-row-wide' );
				$fields['shipping_first_name']['placeholder'] = $full_name_label;
			}

			// Make last name not required and hide it.
			if ( isset( $fields['shipping_last_name'] ) ) {
				$fields['shipping_last_name']['required'] = false;
				$fields['shipping_last_name']['class'] = array( 'form-row-wide', 'mpd-hidden-field' );
				$fields['shipping_last_name']['default'] = ' '; // Provide space to pass validation.
			}
		}

		// Handle company field - add if user wants to show but doesn't exist.
		$show_company = isset( $settings['show_shipping_company'] ) ? $settings['show_shipping_company'] : 'yes';
		if ( 'yes' === $show_company ) {
			if ( ! isset( $fields['shipping_company'] ) ) {
				// Find position after first_name/last_name
				$new_fields = array();
				foreach ( $fields as $key => $field ) {
					$new_fields[ $key ] = $field;
					if ( 'shipping_last_name' === $key || ( 'full_name' === $name_mode && 'shipping_first_name' === $key ) ) {
						$new_fields['shipping_company'] = array(
							'label'       => __( 'Company name', 'magical-products-display' ),
							'class'       => array( 'form-row-wide' ),
							'autocomplete' => 'organization',
							'priority'    => 30,
							'required'    => false,
						);
					}
				}
				$fields = $new_fields;
			}
		} elseif ( isset( $fields['shipping_company'] ) ) {
			unset( $fields['shipping_company'] );
		}

		// Hide country field if disabled.
		$show_country = isset( $settings['show_shipping_country'] ) ? $settings['show_shipping_country'] : 'yes';
		if ( 'yes' !== $show_country && isset( $fields['shipping_country'] ) ) {
			$fields['shipping_country']['class'] = array( 'form-row-wide', 'mpd-hidden-field' );
		}

		// Handle address line 2 visibility.
		$show_address_2 = isset( $settings['show_shipping_address_2'] ) ? $settings['show_shipping_address_2'] : 'yes';
		if ( 'yes' !== $show_address_2 && isset( $fields['shipping_address_2'] ) ) {
			unset( $fields['shipping_address_2'] );
		}

		return $fields;
	}

	/**
	 * Filter WooCommerce checkout fields for validation.
	 *
	 * This filter is applied during checkout to modify field requirements
	 * based on widget settings (e.g., making last name not required in full name mode).
	 *
	 * @since 2.5.0
	 *
	 * @param array $fields Checkout fields.
	 * @return array Modified checkout fields.
	 */
	public function filter_checkout_fields_for_validation( $fields ) {
		$settings = $this->current_settings;

		if ( empty( $settings ) ) {
			return $fields;
		}

		// Handle billing full name mode.
		if ( 'full_name' === ( $settings['billing_name_mode'] ?? 'default' ) ) {
			if ( isset( $fields['billing']['billing_last_name'] ) ) {
				$fields['billing']['billing_last_name']['required'] = false;
				$fields['billing']['billing_last_name']['class'][] = 'mpd-hidden-field';
			}
		}

		// Handle shipping full name mode.
		if ( 'full_name' === ( $settings['shipping_name_mode'] ?? 'default' ) ) {
			if ( isset( $fields['shipping']['shipping_last_name'] ) ) {
				$fields['shipping']['shipping_last_name']['required'] = false;
				$fields['shipping']['shipping_last_name']['class'][] = 'mpd-hidden-field';
			}
		}

		return $fields;
	}

	/**
	 * Add product image to cart item name in order review (static version).
	 *
	 * This static filter runs during WooCommerce AJAX order review updates,
	 * ensuring product images persist after page updates.
	 *
	 * @since 2.5.0
	 *
	 * @param string $name      Product name HTML.
	 * @param array  $cart_item Cart item data.
	 * @param string $cart_item_key Cart item key.
	 * @return string Modified product name with image.
	 */
	public static function add_product_image_to_cart_item_static( $name, $cart_item, $cart_item_key ) {
		// Only add image on checkout page or during AJAX.
		if ( ! is_checkout() && ! wp_doing_ajax() ) {
			return $name;
		}

		// Avoid double-wrapping if already processed.
		if ( strpos( $name, 'mpd-msc-product-image' ) !== false ) {
			return $name;
		}

		$_product = $cart_item['data'] ?? null;
		if ( ! $_product || ! is_object( $_product ) ) {
			return $name;
		}

		// Get settings from static or transient.
		$settings = self::$ajax_settings;
		$image_size = 50; // Default.
		$show_qty_controls = 'no';

		if ( ! empty( $settings ) && isset( $settings['product_image_size']['size'] ) ) {
			$image_size = absint( $settings['product_image_size']['size'] );
			$show_qty_controls = $settings['show_quantity_controls'] ?? 'no';
		} else {
			// Try to get from transient during AJAX.
			$transient_settings = get_transient( 'mpd_msc_image_settings' );
			if ( $transient_settings ) {
				if ( isset( $transient_settings['image_size'] ) ) {
					$image_size = absint( $transient_settings['image_size'] );
				}
				if ( isset( $transient_settings['show_qty_controls'] ) ) {
					$show_qty_controls = $transient_settings['show_qty_controls'];
				}
			}
		}

		// Get product image.
		$thumbnail = $_product->get_image( array( $image_size * 2, $image_size * 2 ) );

		// Fallback to placeholder if no image.
		if ( empty( $thumbnail ) || strpos( $thumbnail, 'src=""' ) !== false ) {
			$thumbnail = '<img src="' . esc_url( wc_placeholder_img_src( 'thumbnail' ) ) . '" alt="' . esc_attr( $_product->get_name() ) . '">';
		}

		// Build the product name with image.
		$output = '<span class="mpd-msc-product-image">' . $thumbnail . '</span>';
		$output .= '<span class="mpd-msc-product-info">';
		$output .= '<span class="mpd-msc-product-name">' . $name . '</span>';
		
		// Only add quantity if NOT using quantity controls (those are added separately).
		if ( 'yes' !== $show_qty_controls || $_product->is_sold_individually() ) {
			$quantity = isset( $cart_item['quantity'] ) ? absint( $cart_item['quantity'] ) : 1;
			$output .= '<span class="mpd-msc-product-qty">× ' . $quantity . '</span>';
		}
		
		$output .= '</span>';

		return $output;
	}

	/**
	 * Add product image to cart item name in order review.
	 *
	 * This filter runs during WooCommerce AJAX order review updates,
	 * ensuring product images persist after page updates.
	 *
	 * @since 2.5.0
	 *
	 * @param string $name      Product name HTML.
	 * @param array  $cart_item Cart item data.
	 * @param string $cart_item_key Cart item key.
	 * @return string Modified product name with image.
	 */
	public function add_product_image_to_cart_item( $name, $cart_item, $cart_item_key ) {
		return self::add_product_image_to_cart_item_static( $name, $cart_item, $cart_item_key );
	}

	/**
	 * Render order notes.
	 *
	 * @since 2.5.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_order_notes( $settings ) {
		?>
		<div class="mpd-msc-order-notes woocommerce-additional-fields">
			<h4><?php esc_html_e( 'Additional Information', 'magical-products-display' ); ?></h4>
			<div class="woocommerce-additional-fields__field-wrapper">
				<?php
				$checkout = WC()->checkout();
				$fields = $checkout->get_checkout_fields( 'order' );

				foreach ( $fields as $key => $field ) {
					woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render order review inline (for step content).
	 *
	 * @since 2.5.0
	 *
	 * @param array $settings  Widget settings.
	 * @param bool  $is_editor Whether in editor mode.
	 * @return void
	 */
	private function render_order_review_inline( $settings, $is_editor ) {
		if ( 'review' === $settings['coupon_position'] && 'yes' === $settings['show_coupon_form'] ) {
			$this->render_coupon_form( $settings );
		}
		?>
		<div class="mpd-msc-order-review-inline">
			<h4><?php esc_html_e( 'Your Order', 'magical-products-display' ); ?></h4>
			<?php $this->render_order_table( $settings, $is_editor ); ?>
		</div>
		<?php
	}

	/**
	 * Render payment step.
	 *
	 * @since 2.5.0
	 *
	 * @param array $settings  Widget settings.
	 * @param bool  $is_editor Whether in editor mode.
	 * @return void
	 */
	private function render_payment_step( $settings, $is_editor ) {
		if ( 'payment' === $settings['coupon_position'] && 'yes' === $settings['show_coupon_form'] ) {
			$this->render_coupon_form( $settings );
		}
		?>
		<div class="mpd-msc-payment-wrapper" id="payment">
			<?php if ( WC()->cart && WC()->cart->needs_payment() ) : ?>
				<ul class="wc_payment_methods payment_methods methods">
					<?php
					if ( ! $is_editor && WC()->payment_gateways() ) {
						$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
						WC()->payment_gateways()->set_current_gateway( $available_gateways );
					} else {
						// Demo gateways for editor.
						$available_gateways = array(
							'bacs' => (object) array(
								'id'                 => 'bacs',
								'title'              => __( 'Direct Bank Transfer', 'magical-products-display' ),
								'description'        => __( 'Make your payment directly into our bank account.', 'magical-products-display' ),
								'has_fields'         => false,
								'chosen'             => true,
								'order_button_text'  => '',
							),
							'cod'  => (object) array(
								'id'                 => 'cod',
								'title'              => __( 'Cash on Delivery', 'magical-products-display' ),
								'description'        => __( 'Pay with cash upon delivery.', 'magical-products-display' ),
								'has_fields'         => false,
								'chosen'             => false,
								'order_button_text'  => '',
							),
						);
					}

					if ( ! empty( $available_gateways ) ) {
						$first = true;
						foreach ( $available_gateways as $gateway ) {
							$gateway_id = is_object( $gateway ) ? $gateway->id : $gateway['id'];
							$gateway_title = is_object( $gateway ) ? $gateway->title : $gateway['title'];
							$gateway_desc = is_object( $gateway ) && isset( $gateway->description ) ? $gateway->description : '';
							$is_chosen = $first;
							?>
							<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway_id ); ?>">
								<input id="payment_method_<?php echo esc_attr( $gateway_id ); ?>" 
									   type="radio" 
									   class="input-radio" 
									   name="payment_method" 
									   value="<?php echo esc_attr( $gateway_id ); ?>" 
									   <?php checked( $is_chosen ); ?> 
									   data-order_button_text="" />
								<label for="payment_method_<?php echo esc_attr( $gateway_id ); ?>">
									<?php echo esc_html( $gateway_title ); ?>
								</label>
								<?php if ( $gateway_desc ) : ?>
									<div class="payment_box payment_method_<?php echo esc_attr( $gateway_id ); ?>" <?php echo ! $is_chosen ? 'style="display:none;"' : ''; ?>>
										<p><?php echo wp_kses_post( $gateway_desc ); ?></p>
									</div>
								<?php endif; ?>
							</li>
							<?php
							$first = false;
						}
					} else {
						echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . esc_html__( 'No payment methods available.', 'magical-products-display' ) . '</li>';
					}
					?>
				</ul>
			<?php endif; ?>

			<div class="mpd-msc-privacy-policy">
				<?php wc_get_template( 'checkout/terms.php' ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render coupon form.
	 *
	 * @since 2.5.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_coupon_form( $settings ) {
		if ( ! wc_coupons_enabled() ) {
			return;
		}
		?>
		<div class="mpd-msc-coupon-wrapper">
			<p class="mpd-msc-coupon-toggle">
				<?php esc_html_e( 'Have a coupon?', 'magical-products-display' ); ?>
				<a href="#" class="mpd-msc-coupon-link showcoupon"><?php esc_html_e( 'Click here to enter your code', 'magical-products-display' ); ?></a>
			</p>
			<div class="mpd-msc-coupon-form checkout_coupon woocommerce-form-coupon" style="display: none;">
				<p><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'magical-products-display' ); ?></p>
				<p class="form-row form-row-first">
					<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'magical-products-display' ); ?></label>
					<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'magical-products-display' ); ?>" id="coupon_code" value="" />
				</p>
				<p class="form-row form-row-last">
					<button type="button" class="button mpd-msc-apply-coupon" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'magical-products-display' ); ?>">
						<?php esc_html_e( 'Apply coupon', 'magical-products-display' ); ?>
					</button>
				</p>
				<div class="clear"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render order summary sidebar.
	 *
	 * @since 2.5.0
	 *
	 * @param array $settings  Widget settings.
	 * @param bool  $is_editor Whether in editor mode.
	 * @return void
	 */
	private function render_order_summary( $settings, $is_editor ) {
		?>
		<div class="mpd-msc-summary-products">
			<?php $this->render_order_table( $settings, $is_editor ); ?>
		</div>
		<?php
	}

	/**
	 * Render order table.
	 *
	 * @since 2.5.0
	 *
	 * @param array $settings  Widget settings.
	 * @param bool  $is_editor Whether in editor mode.
	 * @return void
	 */
	private function render_order_table( $settings, $is_editor ) {
		$cart_items = array();
		$subtotal = '$0.00';
		$total = '$0.00';
		$is_cart_empty = true;

		if ( ! $is_editor && WC()->cart ) {
			$cart_items = WC()->cart->get_cart();
			$is_cart_empty = WC()->cart->is_empty();
			$subtotal = WC()->cart->get_cart_subtotal();
			$total = WC()->cart->get_total();
		} else {
			// Demo data for editor.
			$is_cart_empty = false;
			$cart_items = array(
				'demo1' => array(
					'product_id' => 1,
					'quantity'   => 1,
					'data'       => null,
				),
				'demo2' => array(
					'product_id' => 2,
					'quantity'   => 2,
					'data'       => null,
				),
			);
			$subtotal = '$99.00';
			$total = '$99.00';
		}

		// Handle empty cart
		if ( $is_cart_empty && ! $is_editor ) {
			?>
			<div class="mpd-msc-empty-cart-notice woocommerce-info">
				<?php esc_html_e( 'Your cart is currently empty.', 'magical-products-display' ); ?>
				<a class="button wc-forward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
					<?php esc_html_e( 'Return to shop', 'magical-products-display' ); ?>
				</a>
			</div>
			<?php
			return;
		}
		?>
		<table class="mpd-msc-order-table woocommerce-checkout-review-order-table">
			<thead>
				<tr>
					<th class="product-name"><?php esc_html_e( 'Product', 'magical-products-display' ); ?></th>
					<th class="product-total"><?php esc_html_e( 'Subtotal', 'magical-products-display' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( ! $is_editor && WC()->cart ) {
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
							?>
							<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
								<td class="product-name">
									<?php 
									// Product name with image and basic quantity handled by filter.
									echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); 
									?>
									<?php if ( 'yes' === $settings['show_quantity_controls'] && $_product->is_sold_individually() === false ) : ?>
										<span class="mpd-msc-product-qty-controls" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
											<button type="button" class="mpd-msc-qty-btn mpd-msc-qty-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'magical-products-display' ); ?>">−</button>
											<span class="mpd-msc-qty-value"><?php echo esc_html( $cart_item['quantity'] ); ?></span>
											<button type="button" class="mpd-msc-qty-btn mpd-msc-qty-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'magical-products-display' ); ?>">+</button>
										</span>
									<?php elseif ( 'yes' !== $settings['show_product_images'] ) : ?>
										<?php // Only show static qty if images disabled (filter adds it when images enabled). ?>
										<span class="mpd-msc-product-qty"><?php echo sprintf( '× %d', $cart_item['quantity'] ); ?></span>
									<?php endif; ?>
								</td>
								<td class="product-total">
									<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</td>
							</tr>
							<?php
						}
					}
				} else {
					// Demo products for editor.
					?>
					<tr class="cart_item">
						<td class="product-name">
							<?php if ( 'yes' === $settings['show_product_images'] ) : ?>
								<span class="mpd-msc-product-image">
									<img src="<?php echo esc_url( wc_placeholder_img_src( 'thumbnail' ) ); ?>" alt="Product">
								</span>
							<?php endif; ?>
							<span class="mpd-msc-product-info">
								<span class="mpd-msc-product-name"><?php esc_html_e( 'Sample Product', 'magical-products-display' ); ?></span>
								<span class="mpd-msc-product-qty">× 1</span>
							</span>
						</td>
						<td class="product-total">$39.00</td>
					</tr>
					<tr class="cart_item">
						<td class="product-name">
							<?php if ( 'yes' === $settings['show_product_images'] ) : ?>
								<span class="mpd-msc-product-image">
									<img src="<?php echo esc_url( wc_placeholder_img_src( 'thumbnail' ) ); ?>" alt="Product">
								</span>
							<?php endif; ?>
							<span class="mpd-msc-product-info">
								<span class="mpd-msc-product-name"><?php esc_html_e( 'Another Product', 'magical-products-display' ); ?></span>
								<span class="mpd-msc-product-qty">× 2</span>
							</span>
						</td>
						<td class="product-total">$50.00</td>
					</tr>
					<?php
				}
				?>
			</tbody>
			<tfoot>
				<tr class="cart-subtotal">
					<th><?php esc_html_e( 'Subtotal', 'magical-products-display' ); ?></th>
					<td><?php echo wp_kses_post( $subtotal ); ?></td>
				</tr>
				<?php if ( ! $is_editor && WC()->cart ) : ?>
					<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
						<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
							<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
							<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
						</tr>
					<?php endforeach; ?>
					<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
						<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
						<?php wc_cart_totals_shipping_html(); ?>
						<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
					<?php endif; ?>
					<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
						<tr class="fee">
							<th><?php echo esc_html( $fee->name ); ?></th>
							<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
						</tr>
					<?php endforeach; ?>
					<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
						<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
							<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
								<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
									<th><?php echo esc_html( $tax->label ); ?></th>
									<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr class="tax-total">
								<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
								<td><?php wc_cart_totals_taxes_total_html(); ?></td>
							</tr>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
				<tr class="order-total">
					<th><?php esc_html_e( 'Total', 'magical-products-display' ); ?></th>
					<td><?php echo wp_kses_post( $total ); ?></td>
				</tr>
			</tfoot>
		</table>
		<?php
	}
}
