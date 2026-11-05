<?php
/**
 * One-Page Direct Checkout Widget
 *
 * All-in-one sales funnel checkout widget for landing pages with embedded product card,
 * streamlined form (quick COD or standard), integrated order bump, and instant payment.
 *
 * @package Magical_Shop_Builder
 * @since   2.2.0
 */

namespace MPD\MagicalShopBuilder\Widgets\Checkout;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use MPD\MagicalShopBuilder\Frontend\Funnel_Checkout;
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
 * Class One_Page_Checkout
 *
 * @since 2.2.0
 */
class One_Page_Checkout extends Widget_Base {

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
	protected $widget_icon = 'eicon-checkout';

	/**
	 * Get widget name.
	 *
	 * @since 2.2.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-one-page-checkout';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.2.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD One-Page Direct Checkout', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.2.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'checkout', 'one page', 'landing page', 'direct checkout', 'sales funnel', 'cartflows', 'order bump', 'cod', 'cash on delivery' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.2.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'wc-checkout', 'wc-country-select', 'wc-address-i18n', 'mpd-one-page-checkout' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.2.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-one-page-checkout' );
	}

	/**
	 * Helper: Get product options for dropdowns.
	 *
	 * @param int $limit Max products to fetch.
	 * @return array [ id => title ]
	 */
	private function get_product_list_options( $limit = 50 ) {
		$options = array( '' => esc_html__( '-- Select Product (or Auto-Detect) --', 'magical-products-display' ) );
		if ( ! function_exists( 'wc_get_products' ) ) {
			return $options;
		}

		$products = wc_get_products( array(
			'status'  => 'publish',
			'limit'   => $limit,
			'orderby' => 'date',
			'order'   => 'DESC',
		) );

		foreach ( $products as $product ) {
			$price_label = wc_price( $product->get_price() );
			$options[ $product->get_id() ] = sprintf( '%s (%s)', $product->get_name(), wp_strip_all_tags( $price_label ) );
		}

		return $options;
	}

	/**
	 * Register Content Controls.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		// Section: Product & Funnel Settings.
		$this->start_controls_section(
			'section_funnel_product',
			array(
				'label' => esc_html__( 'Product & Funnel Settings', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'checkout_product_id',
			array(
				'label'       => esc_html__( 'Landing Page Product', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $this->get_product_list_options(),
				'default'     => '',
				'description' => esc_html__( 'Choose the product for this landing page checkout. If left blank, it auto-detects from the current post or latest product.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_product_card',
			array(
				'label'        => esc_html__( 'Show Product Summary Card', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_quantity_selector',
			array(
				'label'        => esc_html__( 'Show Quantity Selector', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_product_card' => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_skip_cart',
			array(
				'label'        => esc_html__( 'Direct Buy (Skip Cart)', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Automatically sets this product in the cart session so buyers checkout immediately without visiting the cart page.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'cart_clear_mode',
			array(
				'label'       => esc_html__( 'Cart Behavior', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'replace' => esc_html__( 'Replace cart (Only this landing product)', 'magical-products-display' ),
					'append'  => esc_html__( 'Append to existing cart', 'magical-products-display' ),
				),
				'default'     => 'replace',
				'condition'   => array(
					'enable_skip_cart' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Section: Checkout Form & Layout.
		$this->start_controls_section(
			'section_form_layout',
			array(
				'label' => esc_html__( 'Checkout Form & Layout', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'form_layout',
			array(
				'label'   => esc_html__( 'Layout Mode', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'1_column' => esc_html__( '1-Column (Stacked - Recommended for Mobile)', 'magical-products-display' ),
					'2_column' => esc_html__( '2-Columns (Form Left, Order & Payment Right)', 'magical-products-display' ),
				),
				'default' => '2_column',
			)
		);

		$this->add_control(
			'checkout_form_mode',
			array(
				'label'       => esc_html__( 'Fields Mode', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'quick_cod' => esc_html__( 'Quick Mode (Name, Phone, Address - High Conversion)', 'magical-products-display' ),
					'standard'  => esc_html__( 'Standard WooCommerce Fields (Full Address)', 'magical-products-display' ),
				),
				'default'     => 'quick_cod',
				'description' => esc_html__( 'Quick Mode removes friction by keeping only essential delivery fields, boosting conversion rates on Cash on Delivery orders.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'section_billing_title',
			array(
				'label'   => esc_html__( 'Billing Section Title', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Delivery Information', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'quick_name_label',
			array(
				'label'     => esc_html__( 'Full Name Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Full Name', 'magical-products-display' ),
				'condition' => array(
					'checkout_form_mode' => 'quick_cod',
				),
			)
		);

		$this->add_control(
			'quick_phone_label',
			array(
				'label'     => esc_html__( 'Phone Number Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Mobile Number', 'magical-products-display' ),
				'condition' => array(
					'checkout_form_mode' => 'quick_cod',
				),
			)
		);

		$this->add_control(
			'quick_address_label',
			array(
				'label'     => esc_html__( 'Delivery Address Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Delivery Address', 'magical-products-display' ),
				'condition' => array(
					'checkout_form_mode' => 'quick_cod',
				),
			)
		);

		$this->add_control(
			'show_order_notes',
			array(
				'label'        => esc_html__( 'Show Order Notes Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Section: Pre-Purchase Order Bump.
		$this->start_controls_section(
			'section_order_bump',
			array(
				'label' => esc_html__( 'Order Bump (Upsell Offer)', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'enable_order_bump',
			array(
				'label'        => esc_html__( 'Enable Order Bump', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'description'  => esc_html__( 'Displays a high-converting 1-click checkbox offer right before order confirmation.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'bump_product_id',
			array(
				'label'       => esc_html__( 'Bump Product', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $this->get_product_list_options(),
				'default'     => '',
				'condition'   => array(
					'enable_order_bump' => 'yes',
				),
			)
		);

		$this->add_control(
			'bump_custom_price',
			array(
				'label'       => esc_html__( 'Special Bump Price (Optional)', 'magical-products-display' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'step'        => 0.01,
				'description' => esc_html__( 'Leave blank to use the regular product price, or enter a discounted offer price.', 'magical-products-display' ),
				'condition'   => array(
					'enable_order_bump' => 'yes',
				),
			)
		);

		$this->add_control(
			'bump_badge_text',
			array(
				'label'     => esc_html__( 'Badge Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'ONE TIME SPECIAL OFFER', 'magical-products-display' ),
				'condition' => array(
					'enable_order_bump' => 'yes',
				),
			)
		);

		$this->add_control(
			'bump_title',
			array(
				'label'     => esc_html__( 'Offer Headline', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Yes! Add Extended Replacement Warranty', 'magical-products-display' ),
				'condition' => array(
					'enable_order_bump' => 'yes',
				),
			)
		);

		$this->add_control(
			'bump_description',
			array(
				'label'     => esc_html__( 'Offer Description', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => esc_html__( 'Guaranteed full accidental protection for 1 year with instant 24/7 priority support.', 'magical-products-display' ),
				'condition' => array(
					'enable_order_bump' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Section: Pro Checkout Layout (1-Step vs 2-Step).
		$this->start_controls_section(
			'section_pro_checkout_layout',
			array(
				'label' => $this->pro_label( esc_html__( 'Checkout Layout & Steps', 'magical-products-display' ) ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_layout_notice', esc_html__( 'Modern 2-Step Checkout (Step 1: Contact Info, Step 2: Payment & Review)', 'magical-products-display' ) );
		}

		$this->add_control(
			'checkout_layout_mode',
			array(
				'label'       => esc_html__( 'Checkout Process Mode', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $this->get_pro_options(
					array(
						'classic_1_step' => esc_html__( 'Classic 1-Step (Default)', 'magical-products-display' ),
						'modern_2_step'  => esc_html__( 'Modern 2-Step Funnel', 'magical-products-display' ),
					),
					array( 'modern_2_step' )
				),
				'default'     => 'classic_1_step',
				'description' => esc_html__( 'Modern 2-Step breaks checkout into Customer Information first, then Payment.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Section: Pro Checkout Field Manager.
		$this->start_controls_section(
			'section_pro_field_manager',
			array(
				'label' => $this->pro_label( esc_html__( 'Checkout Fields Manager', 'magical-products-display' ) ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_fields_notice', esc_html__( 'Streamline Checkout by Hiding Unnecessary Fields (Great for Cash-on-Delivery)', 'magical-products-display' ) );
		}

		$this->add_control(
			'hide_company_field',
			array(
				'label'        => $this->pro_label( esc_html__( 'Hide Company Name', 'magical-products-display' ) ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Removes the company name field from checkout.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'hide_address_2_field',
			array(
				'label'        => $this->pro_label( esc_html__( 'Hide Address Line 2', 'magical-products-display' ) ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Removes the apartment/suite/unit line 2 field.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'hide_postcode_field',
			array(
				'label'        => $this->pro_label( esc_html__( 'Hide Postal Code / ZIP', 'magical-products-display' ) ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Removes postcode validation and field.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Section: Pro Quantity Deals & Bundles.
		$this->start_controls_section(
			'section_pro_bundle_deals',
			array(
				'label' => $this->pro_label( esc_html__( 'Quantity Pricing Deals', 'magical-products-display' ) ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_bundle_notice', esc_html__( 'Multi-Quantity Discount Tiers (Buy 1, Buy 2 Save 10%, Buy 3 Save 20%)', 'magical-products-display' ) );
		}

		$this->add_control(
			'enable_quantity_deals',
			array(
				'label'        => $this->pro_label( esc_html__( 'Enable Quantity Package Deals', 'magical-products-display' ) ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Displays selectable quantity package cards (1 Pc, 2 Pcs with discount) to boost Average Order Value.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Section: Order Review & Button.
		$this->start_controls_section(
			'section_button_settings',
			array(
				'label' => esc_html__( 'Order Button & Guarantees', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'place_order_text',
			array(
				'label'   => esc_html__( 'Button Text', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Confirm Order Now', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_subtext',
			array(
				'label'   => esc_html__( 'Security Guarantee Subtext', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '🔒 256-Bit SSL Encrypted | 100% Satisfaction Guarantee', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'enable_button_pulse',
			array(
				'label'        => esc_html__( 'Pulsating Button Animation', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		// Pro Redirect Heading & Controls.
		$this->add_control(
			'pro_heading_redirect',
			array(
				'label'     => $this->pro_label( esc_html__( 'Custom Redirect', 'magical-products-display' ) ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_redirect_notice', esc_html__( 'Custom Thank You Page Redirect', 'magical-products-display' ) );
		}

		$this->add_control(
			'enable_custom_thankyou',
			array(
				'label'        => $this->pro_label( esc_html__( 'Custom Thank You Page', 'magical-products-display' ) ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'custom_thankyou_url',
			array(
				'label'       => esc_html__( 'Redirect URL', 'magical-products-display' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://yourstore.com/thank-you/', 'magical-products-display' ),
				'condition'   => array(
					'enable_custom_thankyou' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Section: Terms & Privacy Policy (Pro).
		$this->start_controls_section(
			'section_terms_privacy_settings',
			array(
				'label' => $this->pro_label( esc_html__( 'Terms & Privacy Policy', 'magical-products-display' ) ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_terms_notice', esc_html__( 'Terms & Privacy Customizer (Bypass Checkbox, 1-Click Fast Checkout, Custom Legal Text)', 'magical-products-display' ) );
		}

		$this->add_control(
			'heading_privacy_policy',
			array(
				'label' => $this->pro_label( esc_html__( 'Privacy Policy', 'magical-products-display' ) ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'show_privacy_policy',
			array(
				'label'        => $this->pro_label( esc_html__( 'Show Privacy Policy Text', 'magical-products-display' ) ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'custom_privacy_policy_text',
			array(
				'label'       => esc_html__( 'Privacy Policy Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => '',
				'placeholder' => esc_html__( 'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our [privacy_policy].', 'magical-products-display' ),
				'description' => esc_html__( 'Leave blank to use default. Use [privacy_policy] placeholder to link to your privacy policy page.', 'magical-products-display' ),
				'condition'   => array(
					'show_privacy_policy' => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_terms_conditions',
			array(
				'label'     => $this->pro_label( esc_html__( 'Terms & Conditions Checkbox', 'magical-products-display' ) ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_terms_checkbox',
			array(
				'label'        => $this->pro_label( esc_html__( 'Show Terms Checkbox', 'magical-products-display' ) ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'custom_terms_text',
			array(
				'label'       => esc_html__( 'Terms Checkbox Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'I have read and agree to the website [terms]', 'magical-products-display' ),
				'description' => esc_html__( 'Leave blank for default. Use [terms] placeholder to link to your terms page.', 'magical-products-display' ),
				'condition'   => array(
					'show_terms_checkbox' => 'yes',
				),
			)
		);

		$this->add_control(
			'terms_checkbox_required',
			array(
				'label'        => $this->pro_label( esc_html__( 'Required to Place Order', 'magical-products-display' ) ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'   => array(
					'show_terms_checkbox' => 'yes',
				),
			)
		);

		$this->add_control(
			'terms_checked_by_default',
			array(
				'label'        => $this->pro_label( esc_html__( 'Checked by Default', 'magical-products-display' ) ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'   => array(
					'show_terms_checkbox' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Controls.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	protected function register_style_controls() {
		// Style: Main Container.
		$this->start_controls_section(
			'section_style_container',
			array(
				'label' => esc_html__( 'Checkout Container', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_bg',
				'selector' => '{{WRAPPER}} .mpd-opc-wrapper',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => 24,
					'right'    => 24,
					'bottom'   => 24,
					'left'     => 24,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-opc-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-opc-wrapper',
			)
		);

		$this->add_control(
			'container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => 12,
					'right'    => 12,
					'bottom'   => 12,
					'left'     => 12,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-opc-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-opc-wrapper',
			)
		);

		$this->end_controls_section();

		// Style: Product Card.
		$this->start_controls_section(
			'section_style_product_card',
			array(
				'label'     => esc_html__( 'Product Summary Card', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_product_card' => 'yes',
				),
			)
		);

		$this->add_control(
			'card_bg_color',
			array(
				'label'     => esc_html__( 'Card Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f8fafc',
				'selectors' => array(
					'{{WRAPPER}} .mpd-opc-product-card' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0f172a',
				'selectors' => array(
					'{{WRAPPER}} .mpd-opc-product-name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2271b1',
				'selectors' => array(
					'{{WRAPPER}} .mpd-opc-product-price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Style: Order Bump Box.
		$this->start_controls_section(
			'section_style_bump_box',
			array(
				'label'     => esc_html__( 'Order Bump Box', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_order_bump' => 'yes',
				),
			)
		);

		$this->add_control(
			'bump_box_bg',
			array(
				'label'     => esc_html__( 'Box Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fffbeb',
				'selectors' => array(
					'{{WRAPPER}} .mpd-opc-bump-box' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bump_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f59e0b',
				'selectors' => array(
					'{{WRAPPER}} .mpd-opc-bump-box' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bump_badge_bg',
			array(
				'label'     => esc_html__( 'Badge Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d97706',
				'selectors' => array(
					'{{WRAPPER}} .mpd-opc-bump-badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Style: Place Order Button.
		$this->start_controls_section(
			'section_style_order_btn',
			array(
				'label' => esc_html__( 'Place Order Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_btn_typography',
				'selector' => '{{WRAPPER}} button#place_order, {{WRAPPER}} .mpd-opc-submit-btn',
			)
		);

		$this->add_control(
			'order_btn_bg',
			array(
				'label'     => esc_html__( 'Button Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#16a34a',
				'selectors' => array(
					'{{WRAPPER}} button#place_order, {{WRAPPER}} .mpd-opc-submit-btn' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'order_btn_color',
			array(
				'label'     => esc_html__( 'Button Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} button#place_order, {{WRAPPER}} .mpd-opc-submit-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'order_btn_bg_hover',
			array(
				'label'     => esc_html__( 'Hover Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#15803d',
				'selectors' => array(
					'{{WRAPPER}} button#place_order:hover, {{WRAPPER}} .mpd-opc-submit-btn:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'order_btn_padding',
			array(
				'label'      => esc_html__( 'Button Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 16,
					'right'    => 24,
					'bottom'   => 16,
					'left'     => 24,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} button#place_order, {{WRAPPER}} .mpd-opc-submit-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'order_btn_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => 8,
					'right'    => 8,
					'bottom'   => 8,
					'left'     => 8,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} button#place_order, {{WRAPPER}} .mpd-opc-submit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style: Terms & Privacy Policy (Pro).
		$this->start_controls_section(
			'section_style_terms_privacy',
			array(
				'label' => $this->pro_label( esc_html__( 'Terms & Privacy Policy', 'magical-products-display' ) ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_style_terms_notice', esc_html__( 'Terms & Privacy Typography, Accent Colors & Spacing', 'magical-products-display' ) );
		}

		$this->add_control(
			'style_heading_privacy',
			array(
				'label' => esc_html__( 'Privacy Policy Text', 'magical-products-display' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'privacy_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-privacy-policy-text, {{WRAPPER}} .woocommerce-privacy-policy-text p' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'privacy_typography',
				'selector' => '{{WRAPPER}} .woocommerce-privacy-policy-text, {{WRAPPER}} .woocommerce-privacy-policy-text p',
			)
		);

		$this->add_control(
			'privacy_link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-privacy-policy-text a' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'privacy_link_hover_color',
			array(
				'label'     => esc_html__( 'Link Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-privacy-policy-text a:hover' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'privacy_spacing',
			array(
				'label'      => esc_html__( 'Bottom Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-privacy-policy-text, {{WRAPPER}} .woocommerce-privacy-policy-text p' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'style_heading_terms',
			array(
				'label'     => esc_html__( 'Terms & Conditions Checkbox', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'terms_text_color',
			array(
				'label'     => esc_html__( 'Label Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-checkbox-text, {{WRAPPER}} .woocommerce-terms-and-conditions-wrapper label' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'terms_typography',
				'selector' => '{{WRAPPER}} .woocommerce-terms-and-conditions-checkbox-text, {{WRAPPER}} .woocommerce-terms-and-conditions-wrapper label',
			)
		);

		$this->add_control(
			'terms_link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper a' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'terms_link_hover_color',
			array(
				'label'     => esc_html__( 'Link Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper a:hover' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'terms_checkbox_accent',
			array(
				'label'     => esc_html__( 'Checkbox Accent Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper input[type="checkbox"]' => 'accent-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'terms_checkbox_size',
			array(
				'label'      => esc_html__( 'Checkbox Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 28,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper input[type="checkbox"]' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'terms_wrapper_margin',
			array(
				'label'      => esc_html__( 'Wrapper Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on frontend.
	 *
	 * @since 2.2.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_widget( $settings ) {
		if ( ! $this->is_woocommerce_active() ) {
			$this->render_wc_required_notice();
			return;
		}

		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Notify Funnel Checkout engine of active settings.
		if ( class_exists( '\MPD\MagicalShopBuilder\Frontend\Funnel_Checkout' ) ) {
			Funnel_Checkout::set_active_opc_settings( $settings );
		}

		// Resolve designated product.
		$product_id = ! empty( $settings['checkout_product_id'] ) ? absint( $settings['checkout_product_id'] ) : 0;
		if ( ! $product_id ) {
			$product_id = get_the_ID();
		}

		$product = wc_get_product( $product_id );
		if ( ! $product && $is_editor ) {
			// In editor, fetch first available published product as preview demo.
			$sample = wc_get_products( array( 'status' => 'publish', 'limit' => 1 ) );
			if ( ! empty( $sample ) ) {
				$product = $sample[0];
				$product_id = $product->get_id();
			}
		}

		// Direct Buy / Skip Cart: Ensure product is placed into cart for instant checkout.
		if ( ! $is_editor && 'yes' === $settings['enable_skip_cart'] && $product && $product->is_purchasable() ) {
			if ( function_exists( 'WC' ) && is_object( WC()->cart ) ) {
				if ( 'replace' === $settings['cart_clear_mode'] && ! WC()->cart->is_empty() ) {
					$has_landing_product = false;
					foreach ( WC()->cart->get_cart() as $c_item ) {
						if ( (int) $c_item['product_id'] === $product_id ) {
							$has_landing_product = true;
							break;
						}
					}
					if ( ! $has_landing_product ) {
						WC()->cart->empty_cart();
						WC()->cart->add_to_cart( $product_id, 1 );
						WC()->cart->calculate_totals();
					}
				} elseif ( WC()->cart->is_empty() ) {
					WC()->cart->add_to_cart( $product_id, 1 );
					WC()->cart->calculate_totals();
				}
			}
		}

		$is_pro        = $this->is_pro();
		$checkout_mode = ( $is_pro && ! empty( $settings['checkout_layout_mode'] ) ) ? $settings['checkout_layout_mode'] : 'classic_1_step';
		$layout_class  = '1_column' === $settings['form_layout'] ? 'mpd-opc-layout-1col' : 'mpd-opc-layout-2col';
		if ( 'modern_2_step' === $checkout_mode ) {
			$layout_class .= ' mpd-opc-2step-mode';
		}
		$pulse_class   = 'yes' === $settings['enable_button_pulse'] ? 'mpd-pulse-active' : '';
		$hide_privacy  = $is_pro && isset( $settings['show_privacy_policy'] ) && 'no' === $settings['show_privacy_policy'];
		$hide_terms    = $is_pro && isset( $settings['show_terms_checkbox'] ) && 'no' === $settings['show_terms_checkbox'];
		$privacy_class = $hide_privacy ? 'mpd-hide-privacy' : '';
		$terms_class   = $hide_terms ? 'mpd-hide-terms' : '';

		// Nonce for AJAX.
		$nonce = wp_create_nonce( 'mpd_funnel_nonce' );
		?>
		<div class="mpd-opc-wrapper woocommerce <?php echo esc_attr( trim( $layout_class . ' ' . $pulse_class . ' ' . $privacy_class . ' ' . $terms_class ) ); ?>"
			data-nonce="<?php echo esc_attr( $nonce ); ?>"
			data-product-id="<?php echo esc_attr( $product_id ); ?>"
			data-checkout-mode="<?php echo esc_attr( $checkout_mode ); ?>">

			<?php if ( $is_editor && ! $product ) : ?>
				<div class="woocommerce-notice woocommerce-notice--info woocommerce-info">
					<?php esc_html_e( 'Please select a product from widget settings to configure your One-Page Checkout.', 'magical-products-display' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( 'modern_2_step' === $checkout_mode ) : ?>
				<div class="mpd-opc-step-nav">
					<div class="mpd-opc-step-tab active" data-step="1">
						<span class="mpd-opc-tab-num">1</span>
						<span class="mpd-opc-tab-title"><?php esc_html_e( 'Customer Information', 'magical-products-display' ); ?></span>
					</div>
					<div class="mpd-opc-tab-connector"></div>
					<div class="mpd-opc-step-tab" data-step="2">
						<span class="mpd-opc-tab-num">2</span>
						<span class="mpd-opc-tab-title"><?php esc_html_e( 'Payment & Confirmation', 'magical-products-display' ); ?></span>
					</div>
				</div>
			<?php endif; ?>

			<form name="checkout" method="post" class="checkout woocommerce-checkout mpd-opc-form" action="<?php echo esc_url( function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '' ); ?>" enctype="multipart/form-data">

				<div class="mpd-opc-grid">

					<!-- Left Column: Product Card & Billing Form -->
					<div class="mpd-opc-col-left mpd-opc-step-1-content">

						<?php if ( 'yes' === $settings['show_product_card'] && $product ) : ?>
							<div class="mpd-opc-product-card">
								<div class="mpd-opc-product-thumb">
									<?php echo wp_kses_post( $product->get_image( 'thumbnail' ) ); ?>
								</div>
								<div class="mpd-opc-product-details">
									<h4 class="mpd-opc-product-name"><?php echo esc_html( $product->get_name() ); ?></h4>
									<div class="mpd-opc-product-price">
										<?php echo wp_kses_post( $product->get_price_html() ); ?>
									</div>
								</div>
								<?php if ( 'yes' === $settings['show_quantity_selector'] ) : ?>
									<div class="mpd-opc-qty-wrap">
										<button type="button" class="mpd-opc-qty-btn mpd-opc-qty-minus">&minus;</button>
										<input type="number" class="mpd-opc-qty-input" value="1" min="1" max="99" />
										<button type="button" class="mpd-opc-qty-btn mpd-opc-qty-plus">&plus;</button>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php
						// Pro Quantity Deals / Tiered Pricing.
						if ( $is_pro && ! empty( $settings['enable_quantity_deals'] ) && 'yes' === $settings['enable_quantity_deals'] && $product ) :
							$base_price = (float) $product->get_price();
						?>
							<div class="mpd-opc-deals-wrap">
								<h4 class="mpd-opc-deals-title"><?php esc_html_e( 'Select Package Deal:', 'magical-products-display' ); ?></h4>
								<div class="mpd-opc-deals-grid">
									<label class="mpd-opc-deal-card selected" data-qty="1">
										<input type="radio" name="mpd_package_deal" value="1" checked="checked" />
										<div class="mpd-deal-details">
											<span class="mpd-deal-qty"><?php esc_html_e( '1 Item', 'magical-products-display' ); ?></span>
											<span class="mpd-deal-price"><?php echo wp_kses_post( wc_price( $base_price ) ); ?></span>
										</div>
									</label>
									<label class="mpd-opc-deal-card" data-qty="2">
										<span class="mpd-deal-badge popular"><?php esc_html_e( 'SAVE 10%', 'magical-products-display' ); ?></span>
										<input type="radio" name="mpd_package_deal" value="2" />
										<div class="mpd-deal-details">
											<span class="mpd-deal-qty"><?php esc_html_e( '2 Items', 'magical-products-display' ); ?></span>
											<span class="mpd-deal-price"><?php echo wp_kses_post( wc_price( $base_price * 2 * 0.9 ) ); ?></span>
										</div>
									</label>
									<label class="mpd-opc-deal-card" data-qty="3">
										<span class="mpd-deal-badge best-value"><?php esc_html_e( 'BEST VALUE (20% OFF)', 'magical-products-display' ); ?></span>
										<input type="radio" name="mpd_package_deal" value="3" />
										<div class="mpd-deal-details">
											<span class="mpd-deal-qty"><?php esc_html_e( '3 Items', 'magical-products-display' ); ?></span>
											<span class="mpd-deal-price"><?php echo wp_kses_post( wc_price( $base_price * 3 * 0.8 ) ); ?></span>
										</div>
									</label>
								</div>
							</div>
						<?php endif; ?>

						<!-- Billing / Delivery Fields -->
						<div class="mpd-opc-billing-section">
							<?php if ( ! empty( $settings['section_billing_title'] ) ) : ?>
								<h3 class="mpd-opc-section-heading">
									<span class="mpd-opc-step-num">1</span>
									<?php echo esc_html( $settings['section_billing_title'] ); ?>
								</h3>
							<?php endif; ?>

							<div class="woocommerce-billing-fields">
								<div class="woocommerce-billing-fields__field-wrapper">
									<?php
									$checkout        = ( function_exists( 'WC' ) && method_exists( WC(), 'checkout' ) ) ? WC()->checkout() : null;
									$checkout_fields = ( is_object( $checkout ) && isset( $checkout->checkout_fields ) ) ? $checkout->checkout_fields : array();
									$all_fields      = apply_filters( 'woocommerce_checkout_fields', $checkout_fields );
									$billing_fields  = isset( $all_fields['billing'] ) ? $all_fields['billing'] : array();

									// Pro Field Manager: Streamline checkout fields.
									if ( $is_pro ) {
										if ( ! empty( $settings['hide_company_field'] ) && 'yes' === $settings['hide_company_field'] ) {
											unset( $billing_fields['billing_company'] );
										}
										if ( ! empty( $settings['hide_address_2_field'] ) && 'yes' === $settings['hide_address_2_field'] ) {
											unset( $billing_fields['billing_address_2'] );
										}
										if ( ! empty( $settings['hide_postcode_field'] ) && 'yes' === $settings['hide_postcode_field'] ) {
											unset( $billing_fields['billing_postcode'] );
										}
									}

									foreach ( $billing_fields as $key => $field ) {
										woocommerce_form_field( $key, $field, is_object( $checkout ) ? $checkout->get_value( $key ) : '' );
									}
									?>
								</div>
							</div>

							<?php if ( 'yes' === $settings['show_order_notes'] ) : ?>
								<div class="woocommerce-additional-fields">
									<div class="woocommerce-additional-fields__field-wrapper">
										<?php
										$order_comments = isset( $all_fields['order']['order_comments'] ) ? $all_fields['order']['order_comments'] : array(
											'type'        => 'textarea',
											'class'       => array( 'notes' ),
											'label'       => esc_html__( 'Special Delivery Instructions (Optional)', 'magical-products-display' ),
											'placeholder' => esc_html__( 'e.g. Leave with security or call on arrival', 'magical-products-display' ),
										);
										woocommerce_form_field( 'order_comments', $order_comments, is_object( $checkout ) ? $checkout->get_value( 'order_comments' ) : '' );
										?>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( 'modern_2_step' === $checkout_mode ) : ?>
								<div class="mpd-opc-next-step-wrap">
									<button type="button" class="mpd-opc-step-next-btn">
										<?php esc_html_e( 'Continue to Payment', 'magical-products-display' ); ?> &rarr;
									</button>
								</div>
							<?php endif; ?>
						</div>

					</div>

					<!-- Right Column: Order Bump, Order Review & Payment -->
					<div class="mpd-opc-col-right mpd-opc-step-2-content">

						<?php if ( 'modern_2_step' === $checkout_mode ) : ?>
							<div class="mpd-opc-prev-step-wrap">
								<a href="#" class="mpd-opc-step-prev-link">&larr; <?php esc_html_e( 'Return to Customer Information', 'magical-products-display' ); ?></a>
							</div>
						<?php endif; ?>

						<!-- Order Bump Box -->
						<?php if ( 'yes' === $settings['enable_order_bump'] && ! empty( $settings['bump_product_id'] ) ) :
							$bump_id      = absint( $settings['bump_product_id'] );
							$bump_product = wc_get_product( $bump_id );
							if ( $bump_product && $bump_product->is_purchasable() && $bump_product->is_in_stock() ) :
								$bump_price = ! empty( $settings['bump_custom_price'] ) ? wc_price( $settings['bump_custom_price'] ) : $bump_product->get_price_html();
						?>
							<div class="mpd-opc-bump-box" data-bump-id="<?php echo esc_attr( $bump_id ); ?>" data-custom-price="<?php echo esc_attr( $settings['bump_custom_price'] ?? '' ); ?>">
								<?php if ( ! empty( $settings['bump_badge_text'] ) ) : ?>
									<div class="mpd-opc-bump-badge">
										<span>🔥 <?php echo esc_html( $settings['bump_badge_text'] ); ?></span>
									</div>
								<?php endif; ?>

								<div class="mpd-opc-bump-content">
									<label class="mpd-opc-bump-checkbox-label">
										<input type="checkbox" class="mpd-opc-bump-checkbox" name="mpd_order_bump_check" value="1" />
										<span class="mpd-opc-bump-check-custom"></span>
										<span class="mpd-opc-bump-title">
											<strong><?php echo esc_html( $settings['bump_title'] ?? '' ); ?></strong> &mdash; <span class="mpd-bump-price-highlight"><?php echo wp_kses_post( $bump_price ); ?></span>
										</span>
									</label>

									<div class="mpd-opc-bump-body">
										<div class="mpd-opc-bump-thumb">
											<?php echo wp_kses_post( $bump_product->get_image( 'thumbnail' ) ); ?>
										</div>
										<p class="mpd-opc-bump-desc"><?php echo esc_html( $settings['bump_description'] ?? '' ); ?></p>
									</div>
								</div>
							</div>
						<?php endif; endif; ?>

						<!-- Order Review Section -->
						<div class="mpd-opc-review-section">
							<h3 class="mpd-opc-section-heading">
								<span class="mpd-opc-step-num">2</span>
								<?php esc_html_e( 'Order Summary & Payment', 'magical-products-display' ); ?>
							</h3>

							<div id="order_review" class="woocommerce-checkout-review-order">
								<?php
								if ( ! $is_editor && function_exists( 'WC' ) && is_object( WC()->cart ) && ! WC()->cart->is_empty() ) {
									woocommerce_order_review();
									woocommerce_checkout_payment();
								} else {
									$this->render_preview_order_review( $product, $settings );
								}
								?>
							</div>
						</div>

						<!-- Security & Guarantee -->
						<?php if ( ! empty( $settings['button_subtext'] ) ) : ?>
							<div class="mpd-opc-guarantee">
								<span><?php echo esc_html( $settings['button_subtext'] ); ?></span>
							</div>
						<?php endif; ?>

					</div>

				</div>

			</form>

		</div>
		<?php
	}

	/**
	 * Render preview order review table for editor or when cart is empty/uninitialized.
	 *
	 * @since 2.2.0
	 *
	 * @param \WC_Product|null $product Landing product.
	 * @param array            $settings Widget settings.
	 * @return void
	 */
	protected function render_preview_order_review( $product, $settings ) {
		$product_name  = $product ? $product->get_name() : esc_html__( 'Selected Product', 'magical-products-display' );
		$product_price = $product ? wc_price( $product->get_price() ) : wc_price( 49.99 );
		?>
		<table class="shop_table woocommerce-checkout-review-order-table">
			<thead>
				<tr>
					<th class="product-name"><?php esc_html_e( 'Product', 'magical-products-display' ); ?></th>
					<th class="product-total"><?php esc_html_e( 'Subtotal', 'magical-products-display' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr class="cart_item">
					<td class="product-name">
						<?php echo esc_html( $product_name ); ?>&nbsp;
						<strong class="product-quantity">&times;&nbsp;1</strong>
					</td>
					<td class="product-total">
						<?php echo wp_kses_post( $product_price ); ?>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr class="cart-subtotal">
					<th><?php esc_html_e( 'Subtotal', 'magical-products-display' ); ?></th>
					<td><?php echo wp_kses_post( $product_price ); ?></td>
				</tr>
				<tr class="order-total">
					<th><?php esc_html_e( 'Total', 'magical-products-display' ); ?></th>
					<td><strong><?php echo wp_kses_post( $product_price ); ?></strong></td>
				</tr>
			</tfoot>
		</table>

		<div id="payment" class="woocommerce-checkout-payment">
			<ul class="wc_payment_methods payment_methods methods">
				<li class="wc_payment_method payment_method_cod">
					<input id="payment_method_cod_preview" type="radio" class="input-radio" name="payment_method" value="cod" checked="checked" />
					<label for="payment_method_cod_preview"><?php esc_html_e( 'Cash on Delivery', 'magical-products-display' ); ?></label>
					<div class="payment_box payment_method_cod">
						<p><?php esc_html_e( 'Pay with cash upon delivery.', 'magical-products-display' ); ?></p>
					</div>
				</li>
			</ul>
			<div class="form-row place-order">
				<?php
				$is_pro       = $this->is_pro();
				$show_privacy = ! $is_pro || ! isset( $settings['show_privacy_policy'] ) || 'yes' === $settings['show_privacy_policy'];
				$show_terms   = ! $is_pro || ! isset( $settings['show_terms_checkbox'] ) || 'yes' === $settings['show_terms_checkbox'];

				if ( $show_privacy || $show_terms ) :
				?>
				<div class="woocommerce-terms-and-conditions-wrapper">
					<?php
					if ( $show_privacy ) :
						$privacy_text = ( $is_pro && ! empty( $settings['custom_privacy_policy_text'] ) )
							? $settings['custom_privacy_policy_text']
							: sprintf( esc_html__( 'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our %s.', 'magical-products-display' ), '[privacy_policy]' );
						$privacy_link = '<a href="#" class="woocommerce-privacy-policy-link">' . esc_html__( 'privacy policy', 'magical-products-display' ) . '</a>';
						$privacy_html = function_exists( 'wc_replace_policy_page_link_placeholders' )
							? wc_replace_policy_page_link_placeholders( $privacy_text )
							: str_replace( '[privacy_policy]', $privacy_link, $privacy_text );
					?>
						<div class="woocommerce-privacy-policy-text">
							<p><?php echo wp_kses_post( $privacy_html ); ?></p>
						</div>
					<?php endif; ?>

					<?php
					if ( $show_terms ) :
						$terms_text = ( $is_pro && ! empty( $settings['custom_terms_text'] ) )
							? $settings['custom_terms_text']
							: sprintf( esc_html__( 'I have read and agree to the website %s', 'magical-products-display' ), '[terms]' );
						$terms_link = '<a href="#" class="woocommerce-terms-and-conditions-link">' . esc_html__( 'terms and conditions', 'magical-products-display' ) . '</a>';
						$terms_html = function_exists( 'wc_replace_policy_page_link_placeholders' )
							? wc_replace_policy_page_link_placeholders( $terms_text )
							: str_replace( '[terms]', $terms_link, $terms_text );
						$is_checked  = $is_pro && ! empty( $settings['terms_checked_by_default'] ) && 'yes' === $settings['terms_checked_by_default'];
						$is_required = ! $is_pro || ! isset( $settings['terms_checkbox_required'] ) || 'yes' === $settings['terms_checkbox_required'];
					?>
						<p class="form-row validate-required">
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
								<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( $is_checked, true ); ?> id="terms" />
								<span class="woocommerce-terms-and-conditions-checkbox-text"><?php echo wp_kses_post( $terms_html ); ?></span>
								<?php if ( $is_required ) : ?>
									&nbsp;<abbr class="required" title="<?php esc_attr_e( 'required', 'magical-products-display' ); ?>">*</abbr>
								<?php endif; ?>
							</label>
						</p>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<button type="submit" class="button alt wp-element-button mpd-opc-submit-btn" name="woocommerce_checkout_place_order" id="place_order_preview" value="<?php echo esc_attr( $settings['place_order_text'] ?? __( 'Confirm Order Now', 'magical-products-display' ) ); ?>">
					<?php echo esc_html( $settings['place_order_text'] ?? __( 'Confirm Order Now', 'magical-products-display' ) ); ?>
				</button>
			</div>
		</div>
		<?php
	}
}
