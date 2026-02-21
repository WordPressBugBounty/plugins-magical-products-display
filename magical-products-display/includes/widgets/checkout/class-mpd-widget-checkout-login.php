<?php
/**
 * Checkout Login Widget
 *
 * Displays the checkout login form for returning customers.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\Checkout;

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
 * Class Checkout_Login
 *
 * @since 2.0.0
 */
class Checkout_Login extends Widget_Base {

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
	protected $widget_icon = 'eicon-lock-user';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-checkout-login';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Checkout Login', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'checkout', 'login', 'returning', 'customer', 'woocommerce', 'account', 'sign in' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-checkout-widgets' );
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
			'layout',
			array(
				'label'   => __( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'toggle'   => __( 'Collapsible Toggle', 'magical-products-display' ),
					'expanded' => __( 'Always Expanded', 'magical-products-display' ),
				),
				'default' => 'toggle',
			)
		);

		$this->add_control(
			'toggle_text',
			array(
				'label'       => __( 'Toggle Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Returning customer?', 'magical-products-display' ),
				'placeholder' => __( 'Enter toggle text', 'magical-products-display' ),
				'condition'   => array(
					'layout' => 'toggle',
				),
			)
		);

		$this->add_control(
			'toggle_link_text',
			array(
				'label'       => __( 'Toggle Link Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Click here to login', 'magical-products-display' ),
				'placeholder' => __( 'Enter link text', 'magical-products-display' ),
				'condition'   => array(
					'layout' => 'toggle',
				),
			)
		);

		$this->add_control(
			'form_title',
			array(
				'label'       => __( 'Form Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Login', 'magical-products-display' ),
				'placeholder' => __( 'Enter form title', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_form_title',
			array(
				'label'        => __( 'Show Form Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		// Labels Section.
		$this->start_controls_section(
			'section_labels',
			array(
				'label' => __( 'Labels', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'username_label',
			array(
				'label'       => __( 'Username Label', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Username or email address', 'magical-products-display' ),
				'placeholder' => __( 'Enter username label', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'password_label',
			array(
				'label'       => __( 'Password Label', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Password', 'magical-products-display' ),
				'placeholder' => __( 'Enter password label', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => __( 'Button Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Login', 'magical-products-display' ),
				'placeholder' => __( 'Enter button text', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_remember_me',
			array(
				'label'        => __( 'Show Remember Me', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'remember_me_text',
			array(
				'label'       => __( 'Remember Me Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Remember me', 'magical-products-display' ),
				'placeholder' => __( 'Enter remember me text', 'magical-products-display' ),
				'condition'   => array(
					'show_remember_me' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_lost_password',
			array(
				'label'        => __( 'Show Lost Password', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'lost_password_text',
			array(
				'label'       => __( 'Lost Password Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Lost your password?', 'magical-products-display' ),
				'placeholder' => __( 'Enter lost password text', 'magical-products-display' ),
				'condition'   => array(
					'show_lost_password' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Pro Features Section.
		$this->start_controls_section(
			'section_pro_features',
			array(
				'label' => __( 'Pro Features', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'social_login_pro',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( __( 'Social login buttons (Google, Facebook) is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'enable_social_login',
			array(
				'label'        => __( 'Enable Social Login', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'social_providers',
			array(
				'label'       => __( 'Social Providers', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => array(
					'google'   => __( 'Google', 'magical-products-display' ),
					'facebook' => __( 'Facebook', 'magical-products-display' ),
					'twitter'  => __( 'Twitter', 'magical-products-display' ),
					'apple'    => __( 'Apple', 'magical-products-display' ),
				),
				'default'     => array( 'google', 'facebook' ),
				'condition'   => array(
					'enable_social_login' => 'yes',
				),
				'classes'     => $this->is_pro() ? '' : 'mpd-pro-disabled',
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
		// Toggle Style Section.
		$this->start_controls_section(
			'section_toggle_style',
			array(
				'label'     => __( 'Toggle', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout' => 'toggle',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'toggle_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-checkout-login__toggle',
			)
		);

		$this->add_control(
			'toggle_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-login__toggle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_link_color',
			array(
				'label'     => __( 'Link Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-login__toggle-link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_typography',
				'selector' => '{{WRAPPER}} .mpd-checkout-login__toggle',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'toggle_border',
				'selector' => '{{WRAPPER}} .mpd-checkout-login__toggle',
			)
		);

		$this->add_responsive_control(
			'toggle_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-login__toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Form Style Section.
		$this->start_controls_section(
			'section_form_style',
			array(
				'label' => __( 'Form', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'form_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-checkout-login__form',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'form_border',
				'selector' => '{{WRAPPER}} .mpd-checkout-login__form',
			)
		);

		$this->add_responsive_control(
			'form_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-login__form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-login__form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'form_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-checkout-login__form',
			)
		);

		$this->end_controls_section();

		// Labels Style Section.
		$this->start_controls_section(
			'section_labels_style',
			array(
				'label' => __( 'Labels', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-login__label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .mpd-checkout-login__label',
			)
		);

		$this->add_responsive_control(
			'label_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-login__label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Input Style Section.
		$this->start_controls_section(
			'section_input_style',
			array(
				'label' => __( 'Input Fields', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'input_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-login__input' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_placeholder_color',
			array(
				'label'     => __( 'Placeholder Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-login__input::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .mpd-checkout-login__input',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'input_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-checkout-login__input',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'input_border',
				'selector' => '{{WRAPPER}} .mpd-checkout-login__input',
			)
		);

		$this->add_responsive_control(
			'input_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-login__input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'input_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-login__input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'input_focus_heading',
			array(
				'label'     => __( 'Focus State', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'input_focus_border_color',
			array(
				'label'     => __( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-login__input:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Button Style Section.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => __( 'Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-login__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-checkout-login__button',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-checkout-login__button',
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
			'button_hover_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-login__button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-checkout-login__button:hover',
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-login__button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .mpd-checkout-login__button',
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
					'{{WRAPPER}} .mpd-checkout-login__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-checkout-login__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_width',
			array(
				'label'   => __( 'Button Width', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'auto'    => __( 'Auto', 'magical-products-display' ),
					'full'    => __( 'Full Width', 'magical-products-display' ),
					'custom'  => __( 'Custom', 'magical-products-display' ),
				),
				'default' => 'auto',
				'prefix_class' => 'mpd-checkout-login-btn-width-',
			)
		);

		$this->add_responsive_control(
			'button_custom_width',
			array(
				'label'      => __( 'Custom Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 500,
					),
					'%'  => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-login__button' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'button_width' => 'custom',
				),
			)
		);

		$this->add_responsive_control(
			'button_full_width_style',
			array(
				'label'     => '',
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'full',
				'selectors' => array(
					'{{WRAPPER}}.mpd-checkout-login-btn-width-full .mpd-checkout-login__button' => 'width: 100%; display: block;',
				),
				'condition' => array(
					'button_width' => 'full',
				),
			)
		);

		$this->end_controls_section();

		// Links Style Section.
		$this->start_controls_section(
			'section_links_style',
			array(
				'label' => __( 'Links', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'links_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-login__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'links_hover_color',
			array(
				'label'     => __( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-login__link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'links_typography',
				'selector' => '{{WRAPPER}} .mpd-checkout-login__link',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function render() {
		// Check WooCommerce.
		if ( ! $this->is_woocommerce_active() ) {
			$this->render_wc_required_notice();
			return;
		}

		// Skip for logged-in users.
		if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="mpd-checkout-login mpd-checkout-login--editor-notice">';
				echo '<p>' . esc_html__( 'Login form is hidden for logged-in users or when checkout login reminder is disabled.', 'magical-products-display' ) . '</p>';
				echo '</div>';
			}
			return;
		}

		$settings = $this->get_settings_for_display();

		$button_width_class = '';
		if ( 'full' === $settings['button_width'] ) {
			$button_width_class = ' mpd-checkout-login__button--full';
		}
		?>
		<div class="mpd-checkout-login woocommerce-form-login-toggle">
			<?php if ( 'toggle' === $settings['layout'] ) : ?>
				<div class="woocommerce-info mpd-checkout-login__toggle">
					<?php echo esc_html( $settings['toggle_text'] ); ?>
					<a href="#" class="showlogin mpd-checkout-login__toggle-link"><?php echo esc_html( $settings['toggle_link_text'] ); ?></a>
				</div>
			<?php endif; ?>

			<form class="woocommerce-form woocommerce-form-login login mpd-checkout-login__form" method="post" <?php echo 'expanded' === $settings['layout'] ? '' : 'style="display:none;"'; ?>>

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<?php if ( 'yes' === $settings['show_form_title'] && ! empty( $settings['form_title'] ) ) : ?>
					<h3 class="mpd-checkout-login__form-title"><?php echo esc_html( $settings['form_title'] ); ?></h3>
				<?php endif; ?>

				<p class="form-row form-row-first">
					<label for="username" class="mpd-checkout-login__label"><?php echo esc_html( $settings['username_label'] ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="input-text mpd-checkout-login__input" name="username" id="username" autocomplete="username" />
				</p>

				<p class="form-row form-row-last">
					<label for="password" class="mpd-checkout-login__label"><?php echo esc_html( $settings['password_label'] ); ?>&nbsp;<span class="required">*</span></label>
					<input class="input-text woocommerce-Input mpd-checkout-login__input" type="password" name="password" id="password" autocomplete="current-password" />
				</p>

				<div class="clear"></div>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<p class="form-row mpd-checkout-login__actions">
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit mpd-checkout-login__button<?php echo esc_attr( $button_width_class ); ?>" name="login" value="<?php echo esc_attr( $settings['button_text'] ); ?>">
						<?php echo esc_html( $settings['button_text'] ); ?>
					</button>
				</p>

				<p class="form-row form-row-wide mpd-checkout-login__options">
					<?php if ( 'yes' === $settings['show_remember_me'] ) : ?>
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
							<span><?php echo esc_html( $settings['remember_me_text'] ); ?></span>
						</label>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_lost_password'] ) : ?>
						<a class="woocommerce-LostPassword lost_password mpd-checkout-login__link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>">
							<?php echo esc_html( $settings['lost_password_text'] ); ?>
						</a>
					<?php endif; ?>
				</p>

				<div class="clear"></div>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>
		</div>
		<?php
	}

	/**
	 * Render widget output in the editor.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function content_template() {
		?>
		<#
		var buttonWidthClass = '';
		if ( 'full' === settings.button_width ) {
			buttonWidthClass = ' mpd-checkout-login__button--full';
		}
		#>
		<div class="mpd-checkout-login woocommerce-form-login-toggle">
			<# if ( 'toggle' === settings.layout ) { #>
				<div class="woocommerce-info mpd-checkout-login__toggle">
					{{{ settings.toggle_text }}}
					<a href="#" class="showlogin mpd-checkout-login__toggle-link">{{{ settings.toggle_link_text }}}</a>
				</div>
			<# } #>

			<form class="woocommerce-form woocommerce-form-login login mpd-checkout-login__form" method="post" <# if ( 'toggle' === settings.layout ) { #>style="display:block;"<# } #>>

				<# if ( 'yes' === settings.show_form_title && settings.form_title ) { #>
					<h3 class="mpd-checkout-login__form-title">{{{ settings.form_title }}}</h3>
				<# } #>

				<p class="form-row form-row-first">
					<label for="username" class="mpd-checkout-login__label">{{{ settings.username_label }}}&nbsp;<span class="required">*</span></label>
					<input type="text" class="input-text mpd-checkout-login__input" name="username" id="username" />
				</p>

				<p class="form-row form-row-last">
					<label for="password" class="mpd-checkout-login__label">{{{ settings.password_label }}}&nbsp;<span class="required">*</span></label>
					<input class="input-text woocommerce-Input mpd-checkout-login__input" type="password" name="password" id="password" />
				</p>

				<div class="clear"></div>

				<p class="form-row mpd-checkout-login__actions">
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit mpd-checkout-login__button{{ buttonWidthClass }}" name="login">
						{{{ settings.button_text }}}
					</button>
				</p>

				<p class="form-row form-row-wide mpd-checkout-login__options">
					<# if ( 'yes' === settings.show_remember_me ) { #>
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
							<span>{{{ settings.remember_me_text }}}</span>
						</label>
					<# } #>

					<# if ( 'yes' === settings.show_lost_password ) { #>
						<a class="woocommerce-LostPassword lost_password mpd-checkout-login__link" href="#">
							{{{ settings.lost_password_text }}}
						</a>
					<# } #>
				</p>

				<div class="clear"></div>

			</form>
		</div>
		<?php
	}
}
