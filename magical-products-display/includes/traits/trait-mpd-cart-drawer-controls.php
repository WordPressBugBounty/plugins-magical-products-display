<?php
/**
 * Cart Drawer Controls Trait
 *
 * Provides reusable Elementor controls and styling settings for
 * the Off-Canvas Cart Drawer across all cart widgets.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

namespace MPD\MagicalShopBuilder\Traits;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait Cart_Drawer_Controls
 */
trait Cart_Drawer_Controls {

	/**
	 * Determine the active cart action from widget settings.
	 *
	 * @since 2.1.0
	 *
	 * @param array $settings Widget settings.
	 * @return string 'drawer'|'dropdown'|'link'
	 */
	protected function get_cart_action( $settings ) {
		if ( ! empty( $settings['cart_action'] ) ) {
			return $settings['cart_action'];
		}
		// Backwards compatibility: Mini Cart slide_out panel.
		if ( isset( $settings['cart_style'] ) && 'slide_out' === $settings['cart_style'] ) {
			return 'drawer';
		}
		// Backwards compatibility: show_dropdown switcher.
		if ( isset( $settings['show_dropdown'] ) && 'yes' === $settings['show_dropdown'] ) {
			return 'dropdown';
		}
		return 'drawer';
	}

	/**
	 * Register Content tab controls for Cart Drawer.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	protected function register_cart_drawer_content_controls() {
		$this->start_controls_section(
			'section_drawer_settings',
			array(
				'label'     => esc_html__( 'Cart Drawer Settings', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'cart_action' => 'drawer',
				),
			)
		);

		$this->add_control(
			'drawer_info_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<div style="padding: 10px 12px; background: #e0f2fe; border-left: 4px solid #0284c7; color: #0369a1; border-radius: 4px; font-size: 12px; line-height: 1.5;">' .
					'<strong>' . esc_html__( 'Off-Canvas Cart Drawer Active', 'magical-products-display' ) . '</strong><br>' .
					esc_html__( 'Clicking this cart icon slides out the modern off-canvas cart drawer with real-time quantity steppers, AJAX item removal, and free shipping progress bar.', 'magical-products-display' ) .
					'</div>',
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->add_control(
			'drawer_heading_behavior',
			array(
				'label'     => esc_html__( 'Behavior', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'drawer_auto_open',
			array(
				'label'        => esc_html__( 'Auto Open on Add to Cart', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Automatically open the side drawer when a customer adds any product to cart.', 'magical-products-display' ),
			)
		);

		// Header Elements Section.
		$this->add_control(
			'drawer_heading_header_elements',
			array(
				'label'     => esc_html__( 'Header Elements', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'drawer_show_icon',
			array(
				'label'        => esc_html__( 'Show Cart Icon', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors_dictionary' => array(
					'yes' => 'display: inline-flex;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-icon' => '{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'drawer_show_count_badge',
			array(
				'label'        => esc_html__( 'Show Items Count Badge', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors_dictionary' => array(
					'yes' => 'display: inline-flex;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-count-badge' => '{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'drawer_show_close_button',
			array(
				'label'        => esc_html__( 'Show Close (×) Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors_dictionary' => array(
					'yes' => 'display: flex;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-close' => '{{VALUE}}',
				),
			)
		);

		// Free Shipping Bar (Pro).
		$this->add_control(
			'drawer_heading_shipping',
			array(
				'label'     => method_exists( $this, 'pro_label' ) ? $this->pro_label( esc_html__( 'Free Shipping Bar', 'magical-products-display' ) ) : esc_html__( 'Free Shipping Bar', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		if ( method_exists( $this, 'is_pro' ) && ! $this->is_pro() && method_exists( $this, 'add_pro_notice' ) ) {
			$this->add_pro_notice( 'drawer_pro_shipping_notice', esc_html__( 'Free Shipping Goal Bar', 'magical-products-display' ) );
		}

		$this->add_control(
			'drawer_show_shipping',
			array(
				'label'        => method_exists( $this, 'pro_label' ) ? $this->pro_label( esc_html__( 'Show Free Shipping Bar', 'magical-products-display' ) ) : esc_html__( 'Show Free Shipping Bar', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => ( method_exists( $this, 'pro_default' ) ? $this->pro_default( 'yes' ) : '' ),
				'description'  => esc_html__( 'Display the dynamic free shipping progress goal bar inside the drawer.', 'magical-products-display' ),
				'selectors_dictionary' => array(
					'yes' => 'display: block;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-drawer-shipping-bar-wrap' => '{{VALUE}}',
				),
			)
		);

		// In-Drawer Coupon Code Box (Pro).
		$this->add_control(
			'drawer_heading_coupon',
			array(
				'label'     => method_exists( $this, 'pro_label' ) ? $this->pro_label( esc_html__( 'Coupon Code Box', 'magical-products-display' ) ) : esc_html__( 'Coupon Code Box', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		if ( method_exists( $this, 'is_pro' ) && ! $this->is_pro() && method_exists( $this, 'add_pro_notice' ) ) {
			$this->add_pro_notice( 'drawer_pro_coupon_notice', esc_html__( 'In-Drawer Coupon Box', 'magical-products-display' ) );
		}

		$this->add_control(
			'drawer_show_coupon',
			array(
				'label'        => method_exists( $this, 'pro_label' ) ? $this->pro_label( esc_html__( 'Show Coupon Box', 'magical-products-display' ) ) : esc_html__( 'Show Coupon Box', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => ( method_exists( $this, 'pro_default' ) ? $this->pro_default( 'yes' ) : '' ),
				'description'  => esc_html__( 'Allow customers to apply or remove discount coupon codes directly inside the drawer without leaving the page.', 'magical-products-display' ),
				'selectors_dictionary' => array(
					'yes' => 'display: block;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-coupon-wrap' => '{{VALUE}}',
				),
			)
		);

		// Cart Items List Elements.
		$this->add_control(
			'drawer_heading_items',
			array(
				'label'     => esc_html__( 'Cart Items Elements', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'drawer_show_item_image',
			array(
				'label'        => esc_html__( 'Show Product Thumbnail', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors_dictionary' => array(
					'yes' => 'display: block;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-item-thumb' => '{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'drawer_show_item_price',
			array(
				'label'        => esc_html__( 'Show Product Price', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors_dictionary' => array(
					'yes' => 'display: block;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-item-price' => '{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'drawer_show_item_qty',
			array(
				'label'        => esc_html__( 'Show Quantity Stepper (+ / −)', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors_dictionary' => array(
					'yes' => 'display: inline-flex;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-qty-spinner' => '{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'drawer_show_item_remove',
			array(
				'label'        => esc_html__( 'Show Remove Button (×)', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors_dictionary' => array(
					'yes' => 'display: inline-flex;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-item-remove' => '{{VALUE}}',
				),
			)
		);

		// Footer & Action Buttons.
		$this->add_control(
			'drawer_heading_footer_buttons',
			array(
				'label'     => esc_html__( 'Footer & Action Buttons', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'drawer_show_subtotal',
			array(
				'label'        => esc_html__( 'Show Subtotal Row', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors_dictionary' => array(
					'yes' => 'display: flex;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-subtotal-row' => '{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'drawer_show_view_cart',
			array(
				'label'        => esc_html__( 'Show View Cart Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors_dictionary' => array(
					'yes' => 'display: inline-flex;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-btn-view-cart' => '{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'drawer_show_checkout',
			array(
				'label'        => esc_html__( 'Show Checkout Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors_dictionary' => array(
					'yes' => 'display: inline-flex;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-btn-checkout' => '{{VALUE}}',
				),
			)
		);

		// Empty Cart State.
		$this->add_control(
			'drawer_heading_empty_state',
			array(
				'label'     => esc_html__( 'Empty Cart State', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'drawer_show_continue_shopping',
			array(
				'label'        => esc_html__( 'Show Start Shopping Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Shown when cart is empty.', 'magical-products-display' ),
				'selectors_dictionary' => array(
					'yes' => 'display: inline-flex;',
					''    => 'display: none !important;',
				),
				'selectors'    => array(
					'body #mpd-cart-drawer .mpd-btn-continue-shopping' => '{{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style tab controls for Cart Drawer.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	protected function register_cart_drawer_style_controls() {
		// Drawer Panel Section.
		$this->start_controls_section(
			'section_drawer_panel_style',
			array(
				'label'     => esc_html__( 'Cart Drawer - Panel', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'cart_action' => 'drawer',
				),
			)
		);

		$this->add_responsive_control(
			'drawer_panel_width',
			array(
				'label'      => esc_html__( 'Panel Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 280,
						'max' => 800,
					),
					'%'  => array(
						'min' => 20,
						'max' => 90,
					),
					'vw' => array(
						'min' => 20,
						'max' => 90,
					),
				),
				'default'    => array(
					'size' => 440,
					'unit' => 'px',
				),
				'selectors'  => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-panel' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'drawer_panel_bg',
			array(
				'label'     => esc_html__( 'Panel Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-panel' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_backdrop_color',
			array(
				'label'     => esc_html__( 'Overlay Backdrop Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 0.5)',
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-backdrop' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'drawer_panel_shadow',
				'selector' => 'body #mpd-cart-drawer .mpd-cart-drawer-panel',
			)
		);

		$this->end_controls_section();

		// Drawer Header Section.
		$this->start_controls_section(
			'section_drawer_header_style',
			array(
				'label'     => esc_html__( 'Cart Drawer - Header', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'cart_action' => 'drawer',
				),
			)
		);

		$this->add_control(
			'drawer_header_bg',
			array(
				'label'     => esc_html__( 'Header Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-header' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_header_border_color',
			array(
				'label'     => esc_html__( 'Header Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-header' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_header_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-heading' => 'color: {{VALUE}};',
					'body #mpd-cart-drawer .mpd-cart-drawer-icon'    => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'drawer_header_title_typography',
				'selector' => 'body #mpd-cart-drawer .mpd-cart-drawer-heading',
			)
		);

		$this->add_control(
			'drawer_badge_bg',
			array(
				'label'     => esc_html__( 'Count Badge Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'drawer_show_count_badge' => 'yes',
				),
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-count-badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_badge_color',
			array(
				'label'     => esc_html__( 'Count Badge Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'drawer_show_count_badge' => 'yes',
				),
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-count-badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_close_icon_color',
			array(
				'label'     => esc_html__( 'Close Button Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'drawer_show_close_button' => 'yes',
				),
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-close' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_close_icon_hover',
			array(
				'label'     => esc_html__( 'Close Button Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'drawer_show_close_button' => 'yes',
				),
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-close:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Free Shipping Bar Style Section (Pro).
		$this->start_controls_section(
			'section_drawer_shipping_style',
			array(
				'label'     => method_exists( $this, 'pro_label' ) ? $this->pro_label( esc_html__( 'Cart Drawer - Free Shipping Bar', 'magical-products-display' ) ) : esc_html__( 'Cart Drawer - Free Shipping Bar', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'cart_action'          => 'drawer',
					'drawer_show_shipping' => 'yes',
				),
			)
		);

		$this->add_control(
			'drawer_shipping_bg',
			array(
				'label'     => esc_html__( 'Container Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-drawer-shipping-bar-wrap' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_shipping_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-drawer-shipping-bar-msg' => 'color: {{VALUE}};',
					'body #mpd-cart-drawer .mpd-drawer-shipping-bar-msg span' => 'color: {{VALUE}};',
					'body #mpd-cart-drawer .mpd-drawer-shipping-bar-msg strong' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'drawer_shipping_typography',
				'selector' => 'body #mpd-cart-drawer .mpd-drawer-shipping-bar-msg',
			)
		);

		$this->add_control(
			'drawer_shipping_fill_color',
			array(
				'label'     => esc_html__( 'Progress Bar Fill Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-drawer-shipping-fill' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_shipping_track_color',
			array(
				'label'     => esc_html__( 'Progress Track Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-drawer-shipping-progress' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// In-Drawer Coupon Box Style Section (Pro).
		$this->start_controls_section(
			'section_drawer_coupon_style',
			array(
				'label'     => method_exists( $this, 'pro_label' ) ? $this->pro_label( esc_html__( 'Cart Drawer - Coupon Box', 'magical-products-display' ) ) : esc_html__( 'Cart Drawer - Coupon Box', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'cart_action'        => 'drawer',
					'drawer_show_coupon' => 'yes',
				),
			)
		);

		$this->add_control(
			'drawer_coupon_input_bg',
			array(
				'label'     => esc_html__( 'Input Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-coupon-input' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_coupon_input_color',
			array(
				'label'     => esc_html__( 'Input Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-coupon-input' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_coupon_input_border',
			array(
				'label'     => esc_html__( 'Input Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-coupon-input' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_coupon_btn_bg',
			array(
				'label'     => esc_html__( 'Button Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-apply-coupon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_coupon_btn_color',
			array(
				'label'     => esc_html__( 'Button Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-apply-coupon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Cart Drawer Items Style Section.
		$this->start_controls_section(
			'section_drawer_items_style',
			array(
				'label'     => esc_html__( 'Cart Drawer - Items & Price', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'cart_action' => 'drawer',
				),
			)
		);

		$this->add_control(
			'drawer_item_title_color',
			array(
				'label'     => esc_html__( 'Product Title Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-item-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_item_title_hover',
			array(
				'label'     => esc_html__( 'Product Title Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-item-title:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'drawer_item_title_typography',
				'selector' => 'body #mpd-cart-drawer .mpd-cart-drawer-item-title',
			)
		);

		$this->add_control(
			'drawer_item_price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'drawer_show_item_price' => 'yes',
				),
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-item-price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'drawer_item_price_typography',
				'selector'  => 'body #mpd-cart-drawer .mpd-cart-drawer-item-price',
				'condition' => array(
					'drawer_show_item_price' => 'yes',
				),
			)
		);

		$this->add_control(
			'drawer_qty_btn_bg',
			array(
				'label'     => esc_html__( 'Quantity Buttons Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'drawer_show_item_qty' => 'yes',
				),
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-qty-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_qty_btn_color',
			array(
				'label'     => esc_html__( 'Quantity Buttons Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'drawer_show_item_qty' => 'yes',
				),
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-qty-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_qty_btn_hover',
			array(
				'label'     => esc_html__( 'Quantity Buttons Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'drawer_show_item_qty' => 'yes',
				),
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-qty-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_remove_icon_color',
			array(
				'label'     => esc_html__( 'Remove Item Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'drawer_show_item_remove' => 'yes',
				),
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-item-remove' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_remove_icon_hover',
			array(
				'label'     => esc_html__( 'Remove Item Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'drawer_show_item_remove' => 'yes',
				),
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-item-remove:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Cart Drawer Footer & Buttons Style Section.
		$this->start_controls_section(
			'section_drawer_footer_style',
			array(
				'label'     => esc_html__( 'Cart Drawer - Footer & Buttons', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'cart_action' => 'drawer',
				),
			)
		);

		$this->add_control(
			'drawer_footer_bg',
			array(
				'label'     => esc_html__( 'Footer Background', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-footer' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_subtotal_label_color',
			array(
				'label'     => esc_html__( 'Subtotal Label Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'drawer_show_subtotal' => 'yes',
				),
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-subtotal-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_subtotal_val_color',
			array(
				'label'     => esc_html__( 'Subtotal Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'drawer_show_subtotal' => 'yes',
				),
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-subtotal-val' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'drawer_subtotal_val_typography',
				'selector'  => 'body #mpd-cart-drawer .mpd-cart-drawer-subtotal-val',
				'condition' => array(
					'drawer_show_subtotal' => 'yes',
				),
			)
		);

		// Checkout Button Heading.
		$this->add_control(
			'drawer_heading_checkout_btn',
			array(
				'label'     => esc_html__( 'Checkout Button', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'drawer_show_checkout' => 'yes',
				),
			)
		);

		$this->start_controls_tabs(
			'tabs_drawer_checkout_btn',
			array(
				'condition' => array(
					'drawer_show_checkout' => 'yes',
				),
			)
		);

		$this->start_controls_tab(
			'tab_drawer_checkout_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'drawer_checkout_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-btn-checkout' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_checkout_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-btn-checkout' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_drawer_checkout_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'drawer_checkout_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-btn-checkout:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_checkout_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-btn-checkout:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// View Cart Button Heading.
		$this->add_control(
			'drawer_heading_view_cart_btn',
			array(
				'label'     => esc_html__( 'View Cart Button', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'drawer_show_view_cart' => 'yes',
				),
			)
		);

		$this->start_controls_tabs(
			'tabs_drawer_view_cart_btn',
			array(
				'condition' => array(
					'drawer_show_view_cart' => 'yes',
				),
			)
		);

		$this->start_controls_tab(
			'tab_drawer_view_cart_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'drawer_view_cart_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-btn-view-cart' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_view_cart_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-btn-view-cart' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_drawer_view_cart_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'drawer_view_cart_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-btn-view-cart:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'drawer_view_cart_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'body #mpd-cart-drawer .mpd-btn-view-cart:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Buttons Common Dimensions.
		$this->add_responsive_control(
			'drawer_btn_border_radius',
			array(
				'label'      => esc_html__( 'Buttons Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'drawer_btn_padding',
			array(
				'label'      => esc_html__( 'Buttons Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'body #mpd-cart-drawer .mpd-cart-drawer-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}
}
