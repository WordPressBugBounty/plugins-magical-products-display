<?php
/**
 * Coupon Form Widget
 *
 * Displays a standalone coupon form for the cart.
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

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Coupon_Form
 *
 * @since 2.0.0
 */
class Coupon_Form extends Widget_Base {

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
	protected $widget_icon = 'eicon-form-horizontal';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-coupon-form';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Coupon Form', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'coupon', 'discount', 'promo', 'code', 'cart', 'magical-products-display' );
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
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'mpd-coupon-form' );
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
			'title',
			array(
				'label'       => __( 'Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Have a coupon?', 'magical-products-display' ),
				'placeholder' => __( 'Have a coupon?', 'magical-products-display' ),
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
			)
		);

		$this->add_control(
			'input_placeholder',
			array(
				'label'       => __( 'Input Placeholder', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Coupon code', 'magical-products-display' ),
				'placeholder' => __( 'Coupon code', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => __( 'Button Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Apply coupon', 'magical-products-display' ),
				'placeholder' => __( 'Apply coupon', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'inline'  => __( 'Inline', 'magical-products-display' ),
					'stacked' => __( 'Stacked', 'magical-products-display' ),
				),
				'default' => 'inline',
			)
		);

		$this->add_responsive_control(
			'input_width',
			array(
				'label'      => __( 'Input Width', 'magical-products-display' ),
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
				'selectors'  => array(
					'{{WRAPPER}} .mpd-coupon-form input.input-text' => 'width: {{SIZE}}{{UNIT}};',
				),
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Auto-Apply & Coupon Suggestions', 'magical-products-display' ) );
		}
			$this->add_control(
				'ajax_apply',
				array(
					'label'        => __( 'AJAX Apply', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Apply coupon without page reload.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_suggestions',
				array(
					'label'        => __( 'Show Coupon Suggestions', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Display available public coupons. Note: Coupon codes will be visible in the page source.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'suggestions_title',
				array(
					'label'       => __( 'Suggestions Title', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Available Coupons', 'magical-products-display' ),
					'condition'   => array(
						'show_suggestions' => 'yes',
					),
				)
			);

			$this->add_control(
				'auto_apply_url',
				array(
					'label'        => __( 'Auto-Apply from URL', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Automatically apply coupon from URL parameter (e.g., ?coupon=CODE).', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_applied_coupons',
				array(
					'label'        => __( 'Show Applied Coupons', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'description'  => __( 'Display list of applied coupons with remove option.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'success_message',
				array(
					'label'       => __( 'Success Message', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Coupon applied successfully!', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'error_message',
				array(
					'label'       => __( 'Error Message', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Invalid coupon code.', 'magical-products-display' ),
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
				'selector' => '{{WRAPPER}} .mpd-coupon-form',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-coupon-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-coupon-form',
			)
		);

		$this->add_responsive_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-coupon-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-coupon-form',
			)
		);

		$this->end_controls_section();

		// Title Style.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => __( 'Title', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-coupon-form-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-coupon-form-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-coupon-form-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Input Style.
		$this->start_controls_section(
			'section_input_style',
			array(
				'label' => __( 'Input Field', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'input_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-coupon-form input.input-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_placeholder_color',
			array(
				'label'     => __( 'Placeholder Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-coupon-form input.input-text::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_background_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-coupon-form input.input-text' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .mpd-coupon-form input.input-text',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'input_border',
				'selector' => '{{WRAPPER}} .mpd-coupon-form input.input-text',
			)
		);

		$this->add_responsive_control(
			'input_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-coupon-form input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-coupon-form input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label'     => __( 'Focus Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-coupon-form input.input-text:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'input_focus_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-coupon-form input.input-text:focus',
			)
		);

		$this->end_controls_section();

		// Button Style.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => __( 'Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .mpd-coupon-form button',
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
					'{{WRAPPER}} .mpd-coupon-form button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'selector' => '{{WRAPPER}} .mpd-coupon-form button',
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
					'{{WRAPPER}} .mpd-coupon-form button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'selector' => '{{WRAPPER}} .mpd-coupon-form button:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} .mpd-coupon-form button',
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
					'{{WRAPPER}} .mpd-coupon-form button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-coupon-form button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Applied Coupons Style (Pro).
		if ( $this->is_pro() ) {
			$this->start_controls_section(
				'section_applied_coupons_style',
				array(
					'label' => __( 'Applied Coupons', 'magical-products-display' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_control(
				'applied_coupon_background',
				array(
					'label'     => __( 'Background Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-applied-coupon' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'applied_coupon_text_color',
				array(
					'label'     => __( 'Text Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-applied-coupon' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'applied_coupon_remove_color',
				array(
					'label'     => __( 'Remove Button Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-applied-coupon .remove-coupon' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'applied_coupon_typography',
					'selector' => '{{WRAPPER}} .mpd-applied-coupon',
				)
			);

			$this->add_responsive_control(
				'applied_coupon_padding',
				array(
					'label'      => __( 'Padding', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-applied-coupon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'applied_coupon_border_radius',
				array(
					'label'      => __( 'Border Radius', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-applied-coupon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		// Check if coupons are enabled.
		if ( ! wc_coupons_enabled() ) {
			if ( $this->is_editor_mode() ) {
				$this->render_editor_placeholder(
					__( 'Coupon Form', 'magical-products-display' ),
					__( 'Coupons are disabled in WooCommerce settings.', 'magical-products-display' )
				);
			}
			return;
		}

		$this->render_coupon_form( $settings );
	}

	/**
	 * Render coupon form.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_coupon_form( $settings ) {
		$show_title         = 'yes' === $settings['show_title'];
		$title              = $settings['title'];
		$placeholder        = $settings['input_placeholder'];
		$button_text        = $settings['button_text'];
		$layout             = $settings['layout'];

		// Pro features.
		$ajax_apply         = $this->is_pro() && isset( $settings['ajax_apply'] ) && 'yes' === $settings['ajax_apply'];
		$show_suggestions   = $this->is_pro() && isset( $settings['show_suggestions'] ) && 'yes' === $settings['show_suggestions'];
		$show_applied       = $this->is_pro() && isset( $settings['show_applied_coupons'] ) && 'yes' === $settings['show_applied_coupons'];
		$suggestions_title  = $this->is_pro() && isset( $settings['suggestions_title'] ) ? $settings['suggestions_title'] : '';
		$is_editor          = $this->is_editor_mode();

		$wrapper_class = 'mpd-coupon-form';
		$wrapper_class .= ' mpd-coupon-form-' . $layout;
		if ( $ajax_apply ) {
			$wrapper_class .= ' mpd-ajax-coupon';
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<?php if ( $show_title && ! empty( $title ) ) : ?>
				<h3 class="mpd-coupon-form-title"><?php echo esc_html( $title ); ?></h3>
			<?php endif; ?>

			<?php
			// Show applied coupons (Pro).
			if ( $show_applied ) {
				$applied_coupons = array();
				
				// In editor, show sample coupons
				if ( $is_editor ) {
					$applied_coupons = array( 'SUMMER20', 'FREESHIP' );
				} elseif ( WC()->cart && ! WC()->cart->is_empty() ) {
					$applied_coupons = WC()->cart->get_applied_coupons();
				}
				
				if ( ! empty( $applied_coupons ) ) {
					?>
					<div class="mpd-applied-coupons">
						<?php foreach ( $applied_coupons as $coupon_code ) : ?>
							<div class="mpd-applied-coupon">
								<span class="coupon-code"><?php echo esc_html( $coupon_code ); ?></span>
								<a href="<?php echo esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon_code ), wc_get_cart_url() ) ); ?>" class="remove-coupon" title="<?php esc_attr_e( 'Remove coupon', 'magical-products-display' ); ?>">&times;</a>
							</div>
						<?php endforeach; ?>
					</div>
					<?php
				}
			}
			?>

			<form class="mpd-coupon-form-inner" method="post" action="<?php echo esc_url( wc_get_cart_url() ); ?>">
				<div class="mpd-coupon-field">
					<label for="mpd_coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon code', 'magical-products-display' ); ?></label>
					<input type="text" name="coupon_code" class="input-text" id="mpd_coupon_code" value="" placeholder="<?php echo esc_attr( $placeholder ); ?>" />
				</div>
				<div class="mpd-coupon-button">
					<button type="submit" class="button" name="apply_coupon" value="<?php echo esc_attr( $button_text ); ?>">
						<?php echo esc_html( $button_text ); ?>
					</button>
				</div>
				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
			</form>

			<?php
			// Coupon suggestions (Pro).
			if ( $show_suggestions ) {
				// In editor, show sample suggestions
				if ( $is_editor ) {
					?>
					<div class="mpd-coupon-suggestions">
						<?php if ( ! empty( $suggestions_title ) ) : ?>
							<h4 class="mpd-coupon-suggestions-title"><?php echo esc_html( $suggestions_title ); ?></h4>
						<?php endif; ?>
						<div class="mpd-coupon-suggestions-list">
							<div class="mpd-coupon-suggestion" data-coupon="SAVE10">
								<span class="coupon-code">SAVE10</span>
								<span class="coupon-description"><?php esc_html_e( 'Get 10% off your order', 'magical-products-display' ); ?></span>
								<button type="button" class="apply-suggestion"><?php esc_html_e( 'Apply', 'magical-products-display' ); ?></button>
							</div>
							<div class="mpd-coupon-suggestion" data-coupon="FREESHIP">
								<span class="coupon-code">FREESHIP</span>
								<span class="coupon-description"><?php esc_html_e( 'Free shipping on orders over $50', 'magical-products-display' ); ?></span>
								<button type="button" class="apply-suggestion"><?php esc_html_e( 'Apply', 'magical-products-display' ); ?></button>
							</div>
						</div>
					</div>
					<?php
				} else {
					$available_coupons = $this->get_available_coupons();
					if ( ! empty( $available_coupons ) ) {
						?>
						<div class="mpd-coupon-suggestions">
							<?php if ( ! empty( $suggestions_title ) ) : ?>
								<h4 class="mpd-coupon-suggestions-title"><?php echo esc_html( $suggestions_title ); ?></h4>
							<?php endif; ?>
							<div class="mpd-coupon-suggestions-list">
								<?php foreach ( $available_coupons as $coupon ) : ?>
									<div class="mpd-coupon-suggestion" data-coupon="<?php echo esc_attr( $coupon->get_code() ); ?>">
										<span class="coupon-code"><?php echo esc_html( $coupon->get_code() ); ?></span>
										<?php if ( $coupon->get_description() ) : ?>
											<span class="coupon-description"><?php echo esc_html( $coupon->get_description() ); ?></span>
										<?php endif; ?>
										<button type="button" class="apply-suggestion"><?php esc_html_e( 'Apply', 'magical-products-display' ); ?></button>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<?php
					}
				}
			}
			?>

			<?php if ( $is_editor ) : ?>
				<p class="mpd-editor-notice" style="text-align: center; padding: 10px; background: #e8f4fd; color: #0c5460; margin-top: 15px; border-radius: 4px;">
					<strong>📝 <?php esc_html_e( 'Editor Preview:', 'magical-products-display' ); ?></strong>
					<?php esc_html_e( 'This is a preview. The form will be fully functional on the frontend.', 'magical-products-display' ); ?>
				</p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get available public coupons.
	 *
	 * @since 2.0.0
	 *
	 * @return array Array of WC_Coupon objects.
	 */
	private function get_available_coupons() {
		$coupons = array();

		// Get published coupons.
		$coupon_posts = get_posts( array(
			'post_type'      => 'shop_coupon',
			'post_status'    => 'publish',
			'posts_per_page' => 5,
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					'key'     => 'date_expires',
					'value'   => '',
					'compare' => '=',
				),
				array(
					'key'     => 'date_expires',
					'value'   => time(),
					'compare' => '>',
					'type'    => 'NUMERIC',
				),
			),
		) );

		foreach ( $coupon_posts as $coupon_post ) {
			$coupon = new \WC_Coupon( $coupon_post->ID );

			// Check if coupon is valid.
			if ( $coupon->is_valid() ) {
				$coupons[] = $coupon;
			}
		}

		return $coupons;
	}
}
