<?php
/**
 * Account Navigation Widget
 *
 * Displays the WooCommerce My Account navigation menu.
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
use Elementor\Repeater;
use Elementor\Icons_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Account_Nav
 *
 * @since 2.0.0
 */
class Account_Nav extends Widget_Base {

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
	protected $widget_icon = 'eicon-nav-menu';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-account-nav';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Account Navigation', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'account', 'navigation', 'menu', 'my account', 'woocommerce', 'user' );
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

		// Editor notice.
		$this->add_control(
			'editor_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					'<div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; padding: 12px; margin-bottom: 10px;">
						<strong style="color: #856404;">%s</strong>
						<p style="color: #856404; margin: 8px 0 0 0; font-size: 12px;">%s</p>
					</div>',
					esc_html__( '📋 Editor Preview Notice', 'magical-products-display' ),
					esc_html__( 'Frontend shows tab-based navigation where clicking a tab loads that section. In the editor, all navigation items are displayed for styling purposes only.', 'magical-products-display' )
				),
				'content_classes' => 'elementor-panel-alert',
			)
		);

		$this->add_control(
			'show_icons',
			array(
				'label'        => esc_html__( 'Show Icons', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_logout',
			array(
				'label'        => esc_html__( 'Show Logout Link', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Layout Section.
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'vertical'   => esc_html__( 'Vertical', 'magical-products-display' ),
					'horizontal' => esc_html__( 'Horizontal', 'magical-products-display' ),
				),
				'default' => 'vertical',
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'magical-products-display' ),
						'icon'  => 'eicon-align-start-h',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-align-center-h',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'magical-products-display' ),
						'icon'  => 'eicon-align-end-h',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav--horizontal .mpd-account-nav__list' => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'layout' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'items_gap',
			array(
				'label'      => esc_html__( 'Items Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav__list' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Pro Features Section.
		$this->start_controls_section(
			'section_pro_features',
			array(
				'label' => esc_html__( 'Pro Features', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'pro_features_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Advanced features like sticky navigation, user avatar, order badges, and more are Pro features.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		// Sticky Navigation.
		$this->add_control(
			'sticky_heading',
			array(
				'label'     => esc_html__( 'Sticky Navigation', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'enable_sticky',
			array(
				'label'        => esc_html__( 'Enable Sticky', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_responsive_control(
			'sticky_offset',
			array(
				'label'      => esc_html__( 'Sticky Offset (Top)', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav--sticky' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'enable_sticky' => 'yes',
				),
				'classes'    => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'sticky_zindex',
			array(
				'label'     => esc_html__( 'Z-Index', 'magical-products-display' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 9999,
				'default'   => 100,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav--sticky' => 'z-index: {{VALUE}};',
				),
				'condition' => array(
					'enable_sticky' => 'yes',
				),
				'classes'   => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		// User Avatar Section.
		$this->add_control(
			'avatar_heading',
			array(
				'label'     => esc_html__( 'User Avatar', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_avatar',
			array(
				'label'        => esc_html__( 'Show User Avatar', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_username',
			array(
				'label'        => esc_html__( 'Show Username', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_avatar' => 'yes',
				),
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_email',
			array(
				'label'        => esc_html__( 'Show Email', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'show_avatar' => 'yes',
				),
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_responsive_control(
			'avatar_size',
			array(
				'label'      => esc_html__( 'Avatar Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 30,
						'max' => 150,
					),
				),
				'default'    => array(
					'size' => 60,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav__avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_avatar' => 'yes',
				),
				'classes'    => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_responsive_control(
			'avatar_alignment',
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
				'condition' => array(
					'show_avatar' => 'yes',
				),
				'classes'   => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		// Order Badge.
		$this->add_control(
			'badge_heading',
			array(
				'label'     => esc_html__( 'Order Badge', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_order_badge',
			array(
				'label'        => esc_html__( 'Show Order Count Badge', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Shows pending orders count on Orders menu item.', 'magical-products-display' ),
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_download_badge',
			array(
				'label'        => esc_html__( 'Show Download Count Badge', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Shows available downloads count on Downloads menu item.', 'magical-products-display' ),
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		// Hide Menu Items.
		$this->add_control(
			'hide_items_heading',
			array(
				'label'     => esc_html__( 'Hide Menu Items', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'hide_dashboard',
			array(
				'label'        => esc_html__( 'Hide Dashboard', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'hide_orders',
			array(
				'label'        => esc_html__( 'Hide Orders', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'hide_downloads',
			array(
				'label'        => esc_html__( 'Hide Downloads', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'hide_addresses',
			array(
				'label'        => esc_html__( 'Hide Addresses', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'hide_account_details',
			array(
				'label'        => esc_html__( 'Hide Account Details', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		// Active Indicator.
		$this->add_control(
			'indicator_heading',
			array(
				'label'     => esc_html__( 'Active Indicator', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'active_indicator',
			array(
				'label'   => esc_html__( 'Indicator Style', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'      => esc_html__( 'None', 'magical-products-display' ),
					'bar-left'  => esc_html__( 'Bar (Left)', 'magical-products-display' ),
					'bar-right' => esc_html__( 'Bar (Right)', 'magical-products-display' ),
					'underline' => esc_html__( 'Underline', 'magical-products-display' ),
					'dot'       => esc_html__( 'Dot', 'magical-products-display' ),
					'arrow'     => esc_html__( 'Arrow', 'magical-products-display' ),
				),
				'default' => 'none',
				'classes' => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'indicator_color',
			array(
				'label'     => esc_html__( 'Indicator Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav__item--active .mpd-account-nav__indicator' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-account-nav--indicator-arrow .mpd-account-nav__item--active .mpd-account-nav__link::after' => 'border-left-color: {{VALUE}};',
				),
				'condition' => array(
					'active_indicator!' => 'none',
				),
				'classes'   => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_responsive_control(
			'indicator_size',
			array(
				'label'      => esc_html__( 'Indicator Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 2,
						'max' => 20,
					),
				),
				'default'    => array(
					'size' => 4,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav--indicator-bar-left .mpd-account-nav__indicator' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-account-nav--indicator-bar-right .mpd-account-nav__indicator' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-account-nav--indicator-underline .mpd-account-nav__indicator' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-account-nav--indicator-dot .mpd-account-nav__indicator' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'active_indicator!' => array( 'none', 'arrow' ),
				),
				'classes'    => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		// Dividers.
		$this->add_control(
			'divider_heading',
			array(
				'label'     => esc_html__( 'Dividers', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_dividers',
			array(
				'label'        => esc_html__( 'Show Dividers', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'divider_color',
			array(
				'label'     => esc_html__( 'Divider Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav--dividers .mpd-account-nav__item:not(:last-child)::after' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'show_dividers' => 'yes',
				),
				'classes'   => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		// Logout Confirmation.
		$this->add_control(
			'logout_heading',
			array(
				'label'     => esc_html__( 'Logout Options', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'logout_confirmation',
			array(
				'label'        => esc_html__( 'Logout Confirmation Popup', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Show a confirmation dialog before logout.', 'magical-products-display' ),
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'logout_message',
			array(
				'label'       => esc_html__( 'Confirmation Message', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Are you sure you want to logout?', 'magical-products-display' ),
				'label_block' => true,
				'condition'   => array(
					'logout_confirmation' => 'yes',
				),
				'classes'     => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->end_controls_section();

		// Custom Icons Section (Pro).
		$this->start_controls_section(
			'section_custom_icons',
			array(
				'label' => esc_html__( 'Custom Icons', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'custom_icons_pro',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Custom icons for each menu item is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'dashboard_icon',
			array(
				'label'   => esc_html__( 'Dashboard Icon', 'magical-products-display' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-tachometer-alt',
					'library' => 'fa-solid',
				),
				'classes' => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'orders_icon',
			array(
				'label'   => esc_html__( 'Orders Icon', 'magical-products-display' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-shopping-bag',
					'library' => 'fa-solid',
				),
				'classes' => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'downloads_icon',
			array(
				'label'   => esc_html__( 'Downloads Icon', 'magical-products-display' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-download',
					'library' => 'fa-solid',
				),
				'classes' => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'addresses_icon',
			array(
				'label'   => esc_html__( 'Addresses Icon', 'magical-products-display' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-map-marker-alt',
					'library' => 'fa-solid',
				),
				'classes' => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'account_details_icon',
			array(
				'label'   => esc_html__( 'Account Details Icon', 'magical-products-display' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-user',
					'library' => 'fa-solid',
				),
				'classes' => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'logout_icon',
			array(
				'label'   => esc_html__( 'Logout Icon', 'magical-products-display' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-sign-out-alt',
					'library' => 'fa-solid',
				),
				'classes' => $this->is_pro() ? '' : 'mpd-pro-disabled',
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
		// Navigation Style Section.
		$this->start_controls_section(
			'section_nav_style',
			array(
				'label' => esc_html__( 'Navigation', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'nav_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-account-nav',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'nav_border',
				'selector' => '{{WRAPPER}} .mpd-account-nav',
			)
		);

		$this->add_responsive_control(
			'nav_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'nav_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'nav_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-account-nav',
			)
		);

		$this->end_controls_section();

		// Menu Items Style Section.
		$this->start_controls_section(
			'section_items_style',
			array(
				'label' => esc_html__( 'Menu Items', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_typography',
				'selector' => '{{WRAPPER}} .mpd-account-nav__link',
			)
		);

		$this->start_controls_tabs( 'item_tabs' );

		$this->start_controls_tab(
			'item_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'item_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'item_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-account-nav__link',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'item_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav__link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'item_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-account-nav__link:hover',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'item_active_tab',
			array(
				'label' => esc_html__( 'Active', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'item_active_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav__item--active .mpd-account-nav__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'item_active_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-account-nav__item--active .mpd-account-nav__link',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'item_border',
				'selector'  => '{{WRAPPER}} .mpd-account-nav__link',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav__link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav__link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'item_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav__link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Icons Style Section.
		$this->start_controls_section(
			'section_icons_style',
			array(
				'label'     => esc_html__( 'Icons', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_icons' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav__icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-account-nav__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'      => esc_html__( 'Icon Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav__icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-account-nav__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_hover_color',
			array(
				'label'     => esc_html__( 'Icon Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav__link:hover .mpd-account-nav__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-account-nav__link:hover .mpd-account-nav__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Avatar Style Section (Pro).
		$this->start_controls_section(
			'section_avatar_style',
			array(
				'label'     => esc_html__( 'User Avatar', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_avatar' => 'yes',
				),
			)
		);

		$this->add_control(
			'avatar_style_pro',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Avatar styling is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'avatar_border',
				'selector' => '{{WRAPPER}} .mpd-account-nav__avatar img',
				'classes'  => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'avatar_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 50,
					'unit' => '%',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav__avatar img' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'classes'    => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'avatar_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-account-nav__avatar img',
				'classes'  => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'avatar_section_heading',
			array(
				'label'     => esc_html__( 'Avatar Section', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'avatar_section_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-account-nav__avatar-section',
				'classes'  => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_responsive_control(
			'avatar_section_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav__avatar-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'classes'    => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'username_heading',
			array(
				'label'     => esc_html__( 'Username', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'username_typography',
				'selector' => '{{WRAPPER}} .mpd-account-nav__username',
				'classes'  => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'username_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav__username' => 'color: {{VALUE}};',
				),
				'classes'   => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'email_heading',
			array(
				'label'     => esc_html__( 'Email', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'email_typography',
				'selector' => '{{WRAPPER}} .mpd-account-nav__email',
				'classes'  => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'email_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav__email' => 'color: {{VALUE}};',
				),
				'classes'   => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->end_controls_section();

		// Badge Style Section (Pro).
		$this->start_controls_section(
			'section_badge_style',
			array(
				'label' => esc_html__( 'Badge', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'badge_style_pro',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Badge styling is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav__badge' => 'color: {{VALUE}};',
				),
				'classes'   => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'badge_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-nav__badge' => 'background-color: {{VALUE}};',
				),
				'classes'   => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_responsive_control(
			'badge_size',
			array(
				'label'      => esc_html__( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 14,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav__badge' => 'min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'classes'    => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'badge_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-nav__badge' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'classes'    => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .mpd-account-nav__badge',
				'classes'  => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get default icons for menu items.
	 *
	 * @since 2.0.0
	 *
	 * @return array Default icons.
	 */
	protected function get_default_icons() {
		return array(
			'dashboard'       => 'fas fa-tachometer-alt',
			'orders'          => 'fas fa-shopping-bag',
			'downloads'       => 'fas fa-download',
			'edit-address'    => 'fas fa-map-marker-alt',
			'edit-account'    => 'fas fa-user',
			'customer-logout' => 'fas fa-sign-out-alt',
		);
	}

	/**
	 * Get icon for menu item.
	 *
	 * @since 2.0.0
	 *
	 * @param string $endpoint  Menu endpoint.
	 * @param array  $settings  Widget settings.
	 * @return string Icon HTML.
	 */
	protected function get_item_icon( $endpoint, $settings ) {
		$icons = $this->get_default_icons();

		// Pro feature: custom icons.
		if ( $this->is_pro() ) {
			$icon_map = array(
				'dashboard'       => 'dashboard_icon',
				'orders'          => 'orders_icon',
				'downloads'       => 'downloads_icon',
				'edit-address'    => 'addresses_icon',
				'edit-account'    => 'account_details_icon',
				'customer-logout' => 'logout_icon',
			);

			if ( isset( $icon_map[ $endpoint ] ) && ! empty( $settings[ $icon_map[ $endpoint ] ]['value'] ) ) {
				ob_start();
				Icons_Manager::render_icon( $settings[ $icon_map[ $endpoint ] ], array( 'aria-hidden' => 'true' ) );
				return ob_get_clean();
			}
		}

		// Return default icon.
		if ( isset( $icons[ $endpoint ] ) ) {
			return '<i class="' . esc_attr( $icons[ $endpoint ] ) . '"></i>';
		}

		return '';
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
					esc_html__( 'Account Navigation', 'magical-products-display' ),
					esc_html__( 'This widget displays My Account navigation for logged-in users.', 'magical-products-display' )
				);
			}
			return;
		}

		// Get menu items.
		$menu_items = wc_get_account_menu_items();

		// Remove logout if disabled.
		if ( 'yes' !== $settings['show_logout'] && isset( $menu_items['customer-logout'] ) ) {
			unset( $menu_items['customer-logout'] );
		}

		// Pro feature: Hide specific menu items.
		if ( $this->is_pro() ) {
			$hide_map = array(
				'dashboard'       => 'hide_dashboard',
				'orders'          => 'hide_orders',
				'downloads'       => 'hide_downloads',
				'edit-address'    => 'hide_addresses',
				'edit-account'    => 'hide_account_details',
			);

			foreach ( $hide_map as $endpoint => $setting_key ) {
				if ( 'yes' === $settings[ $setting_key ] && isset( $menu_items[ $endpoint ] ) ) {
					unset( $menu_items[ $endpoint ] );
				}
			}
		}

		// Get current endpoint.
		$current_endpoint = $this->get_current_endpoint();

		// Build nav classes.
		$nav_classes = array( 'mpd-account-nav' );
		$nav_classes[] = 'mpd-account-nav--' . esc_attr( $settings['layout'] );

		// Pro feature: Sticky navigation.
		if ( $this->is_pro() && 'yes' === $settings['enable_sticky'] ) {
			$nav_classes[] = 'mpd-account-nav--sticky';
		}

		// Pro feature: Dividers.
		if ( $this->is_pro() && 'yes' === $settings['show_dividers'] ) {
			$nav_classes[] = 'mpd-account-nav--dividers';
		}

		// Pro feature: Active indicator.
		if ( $this->is_pro() && 'none' !== $settings['active_indicator'] ) {
			$nav_classes[] = 'mpd-account-nav--indicator-' . esc_attr( $settings['active_indicator'] );
		}

		// Pro feature: Logout confirmation data attribute.
		$logout_data = '';
		if ( $this->is_pro() && 'yes' === $settings['logout_confirmation'] ) {
			$nav_classes[] = 'mpd-account-nav--logout-confirm';
			$logout_data = ' data-logout-message="' . esc_attr( $settings['logout_message'] ) . '"';
		}

		// Pro feature: Sticky data attributes.
		$sticky_data = '';
		if ( $this->is_pro() && 'yes' === $settings['enable_sticky'] ) {
			$sticky_offset = isset( $settings['sticky_offset']['size'] ) ? absint( $settings['sticky_offset']['size'] ) : 20;
			$sticky_zindex = isset( $settings['sticky_zindex'] ) ? absint( $settings['sticky_zindex'] ) : 100;
			$sticky_data   = sprintf(
				' data-sticky-offset="%d" data-sticky-zindex="%d"',
				$sticky_offset,
				$sticky_zindex
			);
		}
		?>
		<nav class="<?php echo esc_attr( implode( ' ', $nav_classes ) ); ?>"<?php echo $logout_data . $sticky_data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
			// Pro feature: User Avatar.
			if ( $this->is_pro() && 'yes' === $settings['show_avatar'] ) {
				$this->render_user_avatar( $settings );
			}
			?>
			<ul class="mpd-account-nav__list">
				<?php foreach ( $menu_items as $endpoint => $label ) : ?>
					<?php
					$item_classes = array( 'mpd-account-nav__item' );
					if ( $endpoint === $current_endpoint ) {
						$item_classes[] = 'mpd-account-nav__item--active';
					}

					// Pro feature: Get badge count.
					$badge_html = '';
					if ( $this->is_pro() ) {
						$badge_html = $this->get_item_badge( $endpoint, $settings );
					}

					// Is this the logout item?
					$is_logout = ( 'customer-logout' === $endpoint );
					$link_class = 'mpd-account-nav__link';
					if ( $is_logout && $this->is_pro() && 'yes' === $settings['logout_confirmation'] ) {
						$link_class .= ' mpd-account-nav__logout-link';
					}
					?>
					<li class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>">
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="<?php echo esc_attr( $link_class ); ?>">
							<?php if ( 'yes' === $settings['show_icons'] ) : ?>
								<span class="mpd-account-nav__icon">
									<?php echo $this->get_item_icon( $endpoint, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</span>
							<?php endif; ?>
							<span class="mpd-account-nav__text"><?php echo esc_html( $label ); ?></span>
							<?php echo $badge_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
						<?php
						// Pro feature: Active indicator.
						if ( $this->is_pro() && 'none' !== $settings['active_indicator'] && $endpoint === $current_endpoint ) {
							echo '<span class="mpd-account-nav__indicator"></span>';
						}
						?>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<?php
	}

	/**
	 * Render user avatar section.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_user_avatar( $settings ) {
		// Get current user or use placeholder in editor mode.
		$current_user = wp_get_current_user();
		$is_editor    = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// If no user and not in editor, return.
		if ( ! $current_user->exists() && ! $is_editor ) {
			return;
		}

		$avatar_size = isset( $settings['avatar_size']['size'] ) ? absint( $settings['avatar_size']['size'] ) : 60;
		$alignment   = isset( $settings['avatar_alignment'] ) ? $settings['avatar_alignment'] : 'center';

		// Get user data or placeholder for editor.
		if ( $current_user->exists() ) {
			$display_name = $current_user->display_name;
			$user_email   = $current_user->user_email;
			$avatar_html  = get_avatar( $current_user->ID, $avatar_size );
		} else {
			// Editor placeholder data.
			$display_name = esc_html__( 'Username', 'magical-products-display' );
			$user_email   = 'user@example.com';
			$avatar_html  = get_avatar( 0, $avatar_size );
		}

		$section_classes = array( 'mpd-account-nav__avatar-section' );
		$section_classes[] = 'mpd-account-nav__avatar-section--' . esc_attr( $alignment );
		?>
		<div class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
			<div class="mpd-account-nav__avatar">
				<?php echo $avatar_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<?php if ( 'yes' === $settings['show_username'] || 'yes' === $settings['show_email'] ) : ?>
				<div class="mpd-account-nav__user-info">
					<?php if ( 'yes' === $settings['show_username'] ) : ?>
						<span class="mpd-account-nav__username"><?php echo esc_html( $display_name ); ?></span>
					<?php endif; ?>
					<?php if ( 'yes' === $settings['show_email'] ) : ?>
						<span class="mpd-account-nav__email"><?php echo esc_html( $user_email ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get badge HTML for menu item.
	 *
	 * @since 2.0.0
	 *
	 * @param string $endpoint Menu endpoint.
	 * @param array  $settings Widget settings.
	 * @return string Badge HTML.
	 */
	protected function get_item_badge( $endpoint, $settings ) {
		$badge_count = 0;

		// Order badge.
		if ( 'orders' === $endpoint && 'yes' === $settings['show_order_badge'] ) {
			$badge_count = $this->get_pending_orders_count();
		}

		// Downloads badge.
		if ( 'downloads' === $endpoint && 'yes' === $settings['show_download_badge'] ) {
			$badge_count = $this->get_downloads_count();
		}

		if ( $badge_count > 0 ) {
			return '<span class="mpd-account-nav__badge">' . esc_html( $badge_count ) . '</span>';
		}

		return '';
	}

	/**
	 * Get pending orders count for current user.
	 *
	 * @since 2.0.0
	 *
	 * @return int Orders count.
	 */
	protected function get_pending_orders_count() {
		$customer_orders = wc_get_orders(
			array(
				'customer' => get_current_user_id(),
				'status'   => array( 'wc-pending', 'wc-processing', 'wc-on-hold' ),
				'limit'    => -1,
				'return'   => 'ids',
			)
		);

		return count( $customer_orders );
	}

	/**
	 * Get downloads count for current user.
	 *
	 * @since 2.0.0
	 *
	 * @return int Downloads count.
	 */
	protected function get_downloads_count() {
		$downloads = wc_get_customer_available_downloads( get_current_user_id() );
		return count( $downloads );
	}

	/**
	 * Get current endpoint.
	 *
	 * @since 2.0.0
	 *
	 * @return string Current endpoint.
	 */
	protected function get_current_endpoint() {
		global $wp;

		$endpoints = wc_get_account_menu_items();

		foreach ( $endpoints as $endpoint => $label ) {
			if ( 'dashboard' === $endpoint ) {
				// Dashboard is not an endpoint, it's the base account page.
				if ( empty( $wp->query_vars ) || ( isset( $wp->query_vars['pagename'] ) && 1 === count( $wp->query_vars ) ) ) {
					return 'dashboard';
				}
			} elseif ( isset( $wp->query_vars[ $endpoint ] ) ) {
				return $endpoint;
			}
		}

		return 'dashboard';
	}
}
