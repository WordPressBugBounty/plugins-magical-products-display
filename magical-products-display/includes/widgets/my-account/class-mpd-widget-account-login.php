<?php
/**
 * Account Login Widget
 *
 * Displays the WooCommerce login and registration forms for logged-out users on the My Account page.
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
 * Class Account_Login
 *
 * @since 2.0.0
 */
class Account_Login extends Widget_Base {

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
	protected $widget_icon = 'eicon-lock-user';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-account-login';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Account Login', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'login', 'register', 'registration', 'sign in', 'sign up', 'account', 'my account', 'woocommerce', 'form' );
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
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'side-by-side' => esc_html__( 'Side by Side', 'magical-products-display' ),
					'stacked'      => esc_html__( 'Stacked', 'magical-products-display' ),
					'tabs'         => esc_html__( 'Tabs', 'magical-products-display' ),
				),
				'default' => 'side-by-side',
			)
		);

		$this->add_control(
			'show_login',
			array(
				'label'        => esc_html__( 'Show Login Form', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_register',
			array(
				'label'        => esc_html__( 'Show Registration Form', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		// Check if registration is enabled in WooCommerce.
		$registration_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
		
		if ( ! $registration_enabled ) {
			$this->add_control(
				'register_wc_notice',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
						'<div style="padding: 10px; background: #fff3cd; border-left: 4px solid #ffc107; color: #856404; font-size: 12px;">
							<strong>%s</strong><br>%s <a href="%s" target="_blank" style="color: #856404;">%s</a>
						</div>',
						esc_html__( 'Registration Disabled', 'magical-products-display' ),
						esc_html__( 'Enable registration in', 'magical-products-display' ),
						esc_url( admin_url( 'admin.php?page=wc-settings&tab=account' ) ),
						esc_html__( 'WooCommerce → Settings → Accounts & Privacy', 'magical-products-display' )
					),
					'content_classes' => 'elementor-panel-alert',
					'condition'       => array(
						'show_register' => 'yes',
					),
				)
			);
		}

		$this->end_controls_section();

		// Login Form Section.
		$this->start_controls_section(
			'section_login_form',
			array(
				'label'     => esc_html__( 'Login Form', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'show_login' => 'yes',
				),
			)
		);

		$this->add_control(
			'login_title',
			array(
				'label'   => esc_html__( 'Title', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Login', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_login_title',
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
			'username_label',
			array(
				'label'   => esc_html__( 'Username Label', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Username or email address', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'password_label',
			array(
				'label'   => esc_html__( 'Password Label', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Password', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_remember_me',
			array(
				'label'        => esc_html__( 'Show Remember Me', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_lost_password',
			array(
				'label'        => esc_html__( 'Show Lost Password Link', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'login_button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Log in', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Registration Form Section.
		$this->start_controls_section(
			'section_register_form',
			array(
				'label'     => esc_html__( 'Registration Form', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'show_register' => 'yes',
				),
			)
		);

		$this->add_control(
			'register_title',
			array(
				'label'   => esc_html__( 'Title', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Register', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_register_title',
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
			'register_button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Register', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Redirect Section.
		$this->start_controls_section(
			'section_redirect',
			array(
				'label' => esc_html__( 'Redirect', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'redirect_after_login',
			array(
				'label'       => esc_html__( 'Redirect After Login', 'magical-products-display' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'Leave empty for default', 'magical-products-display' ),
				'default'     => array(
					'url' => '',
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
				'label' => esc_html__( 'Container', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'columns_gap',
			array(
				'label'      => esc_html__( 'Columns Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 30,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-login__columns' => 'gap: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => 'side-by-side',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-account-login',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-login' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-account-login',
			)
		);

		$this->add_responsive_control(
			'container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-login' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-account-login',
			)
		);

		$this->end_controls_section();

		// Form Box Style.
		$this->start_controls_section(
			'section_form_box_style',
			array(
				'label' => esc_html__( 'Form Box', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'form_box_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-account-login__form-box',
			)
		);

		$this->add_responsive_control(
			'form_box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-login__form-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'form_box_border',
				'selector' => '{{WRAPPER}} .mpd-account-login__form-box',
			)
		);

		$this->add_responsive_control(
			'form_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-login__form-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'form_box_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-account-login__form-box',
			)
		);

		$this->end_controls_section();

		// Title Style.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Title', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-login__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-account-login__title',
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
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-login__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Labels Style.
		$this->start_controls_section(
			'section_labels_style',
			array(
				'label' => esc_html__( 'Labels', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-login label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .mpd-account-login label',
			)
		);

		$this->add_responsive_control(
			'label_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-login label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Input Fields Style.
		$this->start_controls_section(
			'section_input_style',
			array(
				'label' => esc_html__( 'Input Fields', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'input_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-login input.input-text,
					 {{WRAPPER}} .mpd-account-login input[type="text"],
					 {{WRAPPER}} .mpd-account-login input[type="email"],
					 {{WRAPPER}} .mpd-account-login input[type="password"]' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_placeholder_color',
			array(
				'label'     => esc_html__( 'Placeholder Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-login input::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .mpd-account-login input.input-text,
				              {{WRAPPER}} .mpd-account-login input[type="text"],
				              {{WRAPPER}} .mpd-account-login input[type="email"],
				              {{WRAPPER}} .mpd-account-login input[type="password"]',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'input_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-account-login input.input-text,
				              {{WRAPPER}} .mpd-account-login input[type="text"],
				              {{WRAPPER}} .mpd-account-login input[type="email"],
				              {{WRAPPER}} .mpd-account-login input[type="password"]',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'input_border',
				'selector' => '{{WRAPPER}} .mpd-account-login input.input-text,
				              {{WRAPPER}} .mpd-account-login input[type="text"],
				              {{WRAPPER}} .mpd-account-login input[type="email"],
				              {{WRAPPER}} .mpd-account-login input[type="password"]',
			)
		);

		$this->add_responsive_control(
			'input_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-login input.input-text,
					 {{WRAPPER}} .mpd-account-login input[type="text"],
					 {{WRAPPER}} .mpd-account-login input[type="email"],
					 {{WRAPPER}} .mpd-account-login input[type="password"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-login input.input-text,
					 {{WRAPPER}} .mpd-account-login input[type="text"],
					 {{WRAPPER}} .mpd-account-login input[type="email"],
					 {{WRAPPER}} .mpd-account-login input[type="password"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'input_focus_heading',
			array(
				'label'     => esc_html__( 'Focus State', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'input_focus_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-login input:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'input_focus_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-account-login input:focus',
			)
		);

		$this->end_controls_section();

		// Button Style.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => esc_html__( 'Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .mpd-account-login button[type="submit"]' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-account-login button[type="submit"]',
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
					'{{WRAPPER}} .mpd-account-login button[type="submit"]:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-account-login button[type="submit"]:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .mpd-account-login button[type="submit"]',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-account-login button[type="submit"]',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-login button[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-account-login button[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
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
				'default'      => 'no',
				'selectors'    => array(
					'{{WRAPPER}} .mpd-account-login button[type="submit"]' => 'width: 100%;',
				),
			)
		);

		$this->end_controls_section();

		// Notices Style.
		$this->start_controls_section(
			'section_notice_style',
			array(
				'label' => esc_html__( 'Notices', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'show_notice_preview',
			array(
				'label'              => esc_html__( 'Show Notice Preview', 'magical-products-display' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'          => esc_html__( 'No', 'magical-products-display' ),
				'return_value'       => 'yes',
				'default'            => 'no',
				'description'        => esc_html__( 'Enable to preview and style notices in the editor.', 'magical-products-display' ),
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'notice_type',
			array(
				'label'              => esc_html__( 'Notice Type to Style', 'magical-products-display' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'error',
				'options'            => array(
					'error'   => esc_html__( 'Error Notice', 'magical-products-display' ),
					'success' => esc_html__( 'Success Notice', 'magical-products-display' ),
					'info'    => esc_html__( 'Info Notice', 'magical-products-display' ),
				),
				'render_type'        => 'template',
				'frontend_available' => true,
				'condition' => array(
					'show_notice_preview' => 'yes',
				),
			)
		);

		// Error Notice Styles
		$this->add_control(
			'notice_error_heading',
			array(
				'label'     => esc_html__( 'Error Notice', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_notice_preview' => 'yes',
					'notice_type'         => 'error',
				),
			)
		);

		$this->add_control(
			'notice_error_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-error,
					 {{WRAPPER}} .woocommerce-error li,
					 {{WRAPPER}} .mpd-notice--error' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'error',
				),
			)
		);

		$this->add_control(
			'notice_error_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-error,
					 {{WRAPPER}} .mpd-notice--error' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'error',
				),
			)
		);

		$this->add_control(
			'notice_error_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-error,
					 {{WRAPPER}} .mpd-notice--error' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'error',
				),
			)
		);

		$this->add_control(
			'notice_error_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-error::before,
					 {{WRAPPER}} .mpd-notice--error .mpd-notice__icon' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'error',
				),
			)
		);

		// Success Notice Styles
		$this->add_control(
			'notice_success_heading',
			array(
				'label'     => esc_html__( 'Success Notice', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'notice_type' => 'success',
				),
			)
		);

		$this->add_control(
			'notice_success_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-message,
					 {{WRAPPER}} .mpd-notice--success' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'success',
				),
			)
		);

		$this->add_control(
			'notice_success_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-message,
					 {{WRAPPER}} .mpd-notice--success' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'success',
				),
			)
		);

		$this->add_control(
			'notice_success_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-message,
					 {{WRAPPER}} .mpd-notice--success' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'success',
				),
			)
		);

		$this->add_control(
			'notice_success_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-message::before,
					 {{WRAPPER}} .mpd-notice--success .mpd-notice__icon' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'success',
				),
			)
		);

		// Info Notice Styles
		$this->add_control(
			'notice_info_heading',
			array(
				'label'     => esc_html__( 'Info Notice', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'notice_type' => 'info',
				),
			)
		);

		$this->add_control(
			'notice_info_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-info,
					 {{WRAPPER}} .mpd-notice--info' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'info',
				),
			)
		);

		$this->add_control(
			'notice_info_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-info,
					 {{WRAPPER}} .mpd-notice--info' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'info',
				),
			)
		);

		$this->add_control(
			'notice_info_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-info,
					 {{WRAPPER}} .mpd-notice--info' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'info',
				),
			)
		);

		$this->add_control(
			'notice_info_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-info::before,
					 {{WRAPPER}} .mpd-notice--info .mpd-notice__icon' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'info',
				),
			)
		);

		// Common Notice Styles
		$this->add_control(
			'notice_common_heading',
			array(
				'label'     => esc_html__( 'Common Styles', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'notice_typography',
				'selector' => '{{WRAPPER}} .woocommerce-message, {{WRAPPER}} .woocommerce-error, {{WRAPPER}} .woocommerce-info, {{WRAPPER}} .mpd-notice',
			)
		);

		$this->add_responsive_control(
			'notice_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 40,
					),
				),
				'default'    => array(
					'size' => 18,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-notice__icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-notice__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'notice_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => 15,
					'right'  => 20,
					'bottom' => 15,
					'left'   => 20,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-message, {{WRAPPER}} .woocommerce-error, {{WRAPPER}} .woocommerce-info, {{WRAPPER}} .mpd-notice' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'notice_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => 0,
					'right'  => 0,
					'bottom' => 15,
					'left'   => 0,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-message, {{WRAPPER}} .woocommerce-error, {{WRAPPER}} .woocommerce-info, {{WRAPPER}} .mpd-notice' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'notice_border_radius',
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
					'{{WRAPPER}} .woocommerce-message, {{WRAPPER}} .woocommerce-error, {{WRAPPER}} .woocommerce-info, {{WRAPPER}} .mpd-notice' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'notice_border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'default'    => array(
					'size' => 1,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-message, {{WRAPPER}} .woocommerce-error, {{WRAPPER}} .woocommerce-info, {{WRAPPER}} .mpd-notice' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
				),
			)
		);

		$this->end_controls_section();

		// Links Style.
		$this->start_controls_section(
			'section_links_style',
			array(
				'label' => esc_html__( 'Links', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-login a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-login a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} .mpd-account-login a',
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
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// If user is logged in, show message or hide (except in editor).
		if ( is_user_logged_in() && ! $is_editor ) {
			return;
		}

		// Check if we're on lost-password endpoint.
		$is_lost_password = $this->is_lost_password_endpoint();

		// Check if we're on reset-password endpoint (after clicking email link).
		$is_reset_password = $this->is_reset_password_endpoint();

		// If on lost-password or reset-password page, show appropriate form.
		if ( ! $is_editor && ( $is_lost_password || $is_reset_password ) ) {
			$this->render_lost_password_page( $settings, $is_reset_password );
			return;
		}

		// Check if registration is enabled in WooCommerce.
		$registration_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
		
		// In editor, always show register form if user enabled it (for styling purposes)
		// On frontend, respect WooCommerce setting
		$show_register = 'yes' === $settings['show_register'] && ( $is_editor || $registration_enabled );
		$show_login    = 'yes' === $settings['show_login'];

		// Layout classes.
		$layout_class  = 'mpd-account-login--' . esc_attr( $settings['layout'] );
		$columns_class = ( $show_login && $show_register && 'side-by-side' === $settings['layout'] ) ? 'mpd-account-login__columns' : '';

		// Get selected notice type for preview.
		$notice_type         = ! empty( $settings['show_notice_preview'] ) ? $settings['notice_type'] : 'error';
		$show_notice_preview = isset( $settings['show_notice_preview'] ) && 'yes' === $settings['show_notice_preview'];

		?>
		<div class="mpd-account-login <?php echo esc_attr( $layout_class ); ?>">
			<?php
			// Display WooCommerce notices on frontend.
			if ( function_exists( 'wc_print_notices' ) && ! $is_editor ) {
				wc_print_notices();
			}

			// Show sample notices in editor when preview is enabled.
			if ( $is_editor && $show_notice_preview ) {
				$this->render_editor_notices( $notice_type );
			}
			?>

			<?php if ( 'tabs' === $settings['layout'] && $show_login && $show_register ) : ?>
				<?php $this->render_tabs_layout( $settings, $show_login, $show_register ); ?>
			<?php else : ?>
				<div class="<?php echo esc_attr( $columns_class ); ?>">
					<?php if ( $show_login ) : ?>
						<div class="mpd-account-login__form-box mpd-account-login__login">
							<?php $this->render_login_form( $settings ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $show_register ) : ?>
						<div class="mpd-account-login__form-box mpd-account-login__register">
							<?php $this->render_register_form( $settings ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Check if current page is lost-password endpoint.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function is_lost_password_endpoint() {
		global $wp;

		// Check WooCommerce endpoint.
		if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'lost-password' ) ) {
			return true;
		}

		// Fallback check.
		if ( isset( $wp->query_vars['lost-password'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if current page is reset-password endpoint (with key and login params).
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function is_reset_password_endpoint() {
		// Check for reset password parameters.
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['show-reset-form'] ) && 'true' === $_GET['show-reset-form'] ) {
			return true;
		}

		if ( isset( $_GET['key'] ) && isset( $_GET['login'] ) ) {
			return true;
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		return false;
	}

	/**
	 * Render lost password page.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings         Widget settings.
	 * @param bool  $is_reset_password Whether this is the reset password form.
	 * @return void
	 */
	private function render_lost_password_page( $settings, $is_reset_password = false ) {
		?>
		<div class="mpd-account-login mpd-account-login--lost-password">
			<?php
			// Display WooCommerce notices.
			if ( function_exists( 'wc_print_notices' ) ) {
				wc_print_notices();
			}
			?>

			<div class="mpd-account-login__form-box">
				<?php
				if ( $is_reset_password ) {
					$this->render_reset_password_form( $settings );
				} else {
					$this->render_lost_password_form( $settings );
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render lost password form.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_lost_password_form( $settings ) {
		?>
		<h2 class="mpd-account-login__title"><?php esc_html_e( 'Lost password', 'magical-products-display' ); ?></h2>

		<form method="post" class="woocommerce-ResetPassword lost_reset_password">
			<p><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'magical-products-display' ) ); ?></p>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="user_login"><?php esc_html_e( 'Username or email', 'magical-products-display' ); ?></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" required aria-required="true" />
			</p>

			<?php do_action( 'woocommerce_lostpassword_form' ); ?>

			<p class="woocommerce-form-row form-row">
				<input type="hidden" name="wc_reset_password" value="true" />
				<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
				<button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" value="<?php esc_attr_e( 'Reset password', 'magical-products-display' ); ?>">
					<?php esc_html_e( 'Reset password', 'magical-products-display' ); ?>
				</button>
			</p>

			<p class="woocommerce-form-row form-row">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>"><?php esc_html_e( '&larr; Back to login', 'magical-products-display' ); ?></a>
			</p>
		</form>
		<?php
	}

	/**
	 * Render reset password form (after clicking email link).
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_reset_password_form( $settings ) {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$reset_key   = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
		$reset_login = isset( $_GET['login'] ) ? sanitize_text_field( wp_unslash( $_GET['login'] ) ) : '';
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		// Check if we have valid reset key and login.
		if ( empty( $reset_key ) || empty( $reset_login ) ) {
			?>
			<div class="woocommerce-error" role="alert">
				<?php esc_html_e( 'This password reset link is invalid or has expired.', 'magical-products-display' ); ?>
			</div>
			<p>
				<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php esc_html_e( 'Request a new password reset link', 'magical-products-display' ); ?></a>
			</p>
			<?php
			return;
		}

		// Verify the reset key.
		$user = check_password_reset_key( $reset_key, $reset_login );

		if ( is_wp_error( $user ) ) {
			?>
			<div class="woocommerce-error" role="alert">
				<?php esc_html_e( 'This password reset link is invalid or has expired.', 'magical-products-display' ); ?>
			</div>
			<p>
				<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php esc_html_e( 'Request a new password reset link', 'magical-products-display' ); ?></a>
			</p>
			<?php
			return;
		}
		?>
		<h2 class="mpd-account-login__title"><?php esc_html_e( 'Reset password', 'magical-products-display' ); ?></h2>

		<form method="post" class="woocommerce-ResetPassword lost_reset_password">
			<p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'magical-products-display' ) ); ?></p>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password_1"><?php esc_html_e( 'New password', 'magical-products-display' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" required aria-required="true" />
			</p>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password_2"><?php esc_html_e( 'Re-enter new password', 'magical-products-display' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" required aria-required="true" />
			</p>

			<?php do_action( 'woocommerce_resetpassword_form' ); ?>

			<input type="hidden" name="reset_key" value="<?php echo esc_attr( $reset_key ); ?>" />
			<input type="hidden" name="reset_login" value="<?php echo esc_attr( $reset_login ); ?>" />

			<p class="woocommerce-form-row form-row">
				<input type="hidden" name="wc_reset_password" value="true" />
				<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>
				<button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" value="<?php esc_attr_e( 'Save', 'magical-products-display' ); ?>">
					<?php esc_html_e( 'Save', 'magical-products-display' ); ?>
				</button>
			</p>
		</form>
		<?php
	}

	/**
	 * Render tabs layout.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings      Widget settings.
	 * @param bool  $show_login    Show login form.
	 * @param bool  $show_register Show register form.
	 * @return void
	 */
	private function render_tabs_layout( $settings, $show_login, $show_register ) {
		?>
		<div class="mpd-account-login__tabs">
			<div class="mpd-account-login__tabs-nav">
				<?php if ( $show_login ) : ?>
					<button type="button" class="mpd-account-login__tab-btn mpd-account-login__tab-btn--active" data-tab="login">
						<?php echo esc_html( $settings['login_title'] ); ?>
					</button>
				<?php endif; ?>
				<?php if ( $show_register ) : ?>
					<button type="button" class="mpd-account-login__tab-btn" data-tab="register">
						<?php echo esc_html( $settings['register_title'] ); ?>
					</button>
				<?php endif; ?>
			</div>

			<div class="mpd-account-login__tabs-content">
				<?php if ( $show_login ) : ?>
					<div class="mpd-account-login__tab-panel mpd-account-login__tab-panel--active" data-panel="login">
						<div class="mpd-account-login__form-box">
							<?php $this->render_login_form( $settings, false ); ?>
						</div>
					</div>
				<?php endif; ?>
				<?php if ( $show_register ) : ?>
					<div class="mpd-account-login__tab-panel" data-panel="register">
						<div class="mpd-account-login__form-box">
							<?php $this->render_register_form( $settings, false ); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render login form.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings   Widget settings.
	 * @param bool  $show_title Whether to show title.
	 * @return void
	 */
	private function render_login_form( $settings, $show_title = true ) {
		$show_title = $show_title && 'yes' === $settings['show_login_title'];
		?>
		<?php if ( $show_title && ! empty( $settings['login_title'] ) ) : ?>
			<h2 class="mpd-account-login__title"><?php echo esc_html( $settings['login_title'] ); ?></h2>
		<?php endif; ?>

		<form class="woocommerce-form woocommerce-form-login login" method="post">
			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username"><?php echo esc_html( $settings['username_label'] ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="" required aria-required="true" />
			</p>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password"><?php echo esc_html( $settings['password_label'] ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<p class="form-row">
				<?php if ( 'yes' === $settings['show_remember_me'] ) : ?>
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
						<span><?php esc_html_e( 'Remember me', 'magical-products-display' ); ?></span>
					</label>
				<?php endif; ?>
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php echo esc_attr( $settings['login_button_text'] ); ?>">
					<?php echo esc_html( $settings['login_button_text'] ); ?>
				</button>
			</p>

			<?php if ( 'yes' === $settings['show_lost_password'] ) : ?>
				<p class="woocommerce-LostPassword lost_password">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'magical-products-display' ); ?></a>
				</p>
			<?php endif; ?>

			<?php do_action( 'woocommerce_login_form_end' ); ?>
		</form>
		<?php
	}

	/**
	 * Render register form.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings   Widget settings.
	 * @param bool  $show_title Whether to show title.
	 * @return void
	 */
	private function render_register_form( $settings, $show_title = true ) {
		$show_title = $show_title && 'yes' === $settings['show_register_title'];
		?>
		<?php if ( $show_title && ! empty( $settings['register_title'] ) ) : ?>
			<h2 class="mpd-account-login__title"><?php echo esc_html( $settings['register_title'] ); ?></h2>
		<?php endif; ?>

		<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>
			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_username"><?php esc_html_e( 'Username', 'magical-products-display' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="" required aria-required="true" />
				</p>
			<?php endif; ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="reg_email"><?php esc_html_e( 'Email address', 'magical-products-display' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="" required aria-required="true" />
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_password"><?php esc_html_e( 'Password', 'magical-products-display' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
				</p>
			<?php else : ?>
				<p><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'magical-products-display' ); ?></p>
			<?php endif; ?>

			<?php do_action( 'woocommerce_register_form' ); ?>

			<p class="woocommerce-form-row form-row">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button type="submit" class="woocommerce-Button woocommerce-button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php echo esc_attr( $settings['register_button_text'] ); ?>">
					<?php echo esc_html( $settings['register_button_text'] ); ?>
				</button>
			</p>

			<?php do_action( 'woocommerce_register_form_end' ); ?>
		</form>
		<?php
	}

	/**
	 * Render editor notices for preview.
	 *
	 * @since 2.0.0
	 *
	 * @param string $notice_type The type of notice to display (error, success, info).
	 * @return void
	 */
	private function render_editor_notices( $notice_type ) {
		$notices = array(
			'error'   => array(
				'class'   => 'mpd-notice mpd-notice--error',
				'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
				'message' => esc_html__( 'Invalid username or password.', 'magical-products-display' ),
			),
			'success' => array(
				'class'   => 'mpd-notice mpd-notice--success',
				'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
				'message' => esc_html__( 'Account details changed successfully.', 'magical-products-display' ),
			),
			'info'    => array(
				'class'   => 'mpd-notice mpd-notice--info',
				'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
				'message' => esc_html__( 'Please check your email to verify your account.', 'magical-products-display' ),
			),
		);

		$notice = isset( $notices[ $notice_type ] ) ? $notices[ $notice_type ] : $notices['error'];
		?>
		<div class="<?php echo esc_attr( $notice['class'] ); ?>" role="alert" style="display: flex !important; visibility: visible !important; opacity: 1 !important;">
			<span class="mpd-notice__icon" style="display: inline-flex !important; visibility: visible !important;"><?php echo $notice['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<span class="mpd-notice__text" style="display: inline-block !important; visibility: visible !important;"><?php echo esc_html( $notice['message'] ); ?></span>
			<span class="mpd-notice__preview-badge"><?php esc_html_e( 'Preview', 'magical-products-display' ); ?></span>
		</div>
		<?php
	}
}
