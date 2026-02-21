<?php
/**
 * Place Order Widget
 *
 * Displays the checkout place order button with terms and conditions.
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
 * Class Place_Order
 *
 * @since 2.0.0
 */
class Place_Order extends Widget_Base {

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
	protected $widget_icon = 'eicon-button';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-place-order';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Place Order', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'place', 'order', 'checkout', 'submit', 'buy', 'woocommerce', 'button', 'purchase' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'wc-checkout', 'mpd-checkout-widgets' );
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
			'button_text',
			array(
				'label'       => __( 'Button Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Place Order', 'magical-products-display' ),
				'placeholder' => __( 'Enter button text', 'magical-products-display' ),
				'description' => __( 'This may be overridden by payment gateway settings.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_terms',
			array(
				'label'        => __( 'Show Terms & Conditions', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Show if Terms page is set in WooCommerce settings.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_privacy',
			array(
				'label'        => __( 'Show Privacy Policy', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Show if Privacy Policy page is set in WordPress settings.', 'magical-products-display' ),
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
			'loading_states_pro',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( __( 'Custom loading states and button animations are Pro features.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'enable_loading_animation',
			array(
				'label'        => __( 'Enable Loading Animation', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'loading_text',
			array(
				'label'       => __( 'Loading Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Processing...', 'magical-products-display' ),
				'placeholder' => __( 'Enter loading text', 'magical-products-display' ),
				'condition'   => array(
					'enable_loading_animation' => 'yes',
				),
				'classes'     => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->end_controls_section();

		// Layout Section.
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'Layout', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'button_align',
			array(
				'label'     => __( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .mpd-place-order__button-wrapper' => 'justify-content: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					'left'    => 'flex-start',
					'center'  => 'center',
					'right'   => 'flex-end',
					'justify' => 'stretch',
				),
			)
		);

		$this->add_control(
			'button_full_width',
			array(
				'label'        => __( 'Full Width Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'mpd-place-order-full-width-',
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
		// Button Style Section.
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
				'selector' => '{{WRAPPER}} #place_order',
			)
		);

		$this->start_controls_tabs( 'button_style_tabs' );

		$this->start_controls_tab(
			'button_normal_tab',
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
					'{{WRAPPER}} #place_order' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} #place_order',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} #place_order',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #place_order:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background_hover',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} #place_order:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} #place_order:hover',
			)
		);

		$this->add_control(
			'button_hover_transition',
			array(
				'label'     => __( 'Transition Duration', 'magical-products-display' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} #place_order' => 'transition-duration: {{SIZE}}s;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} #place_order',
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
					'{{WRAPPER}} #place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Terms Style Section.
		$this->start_controls_section(
			'section_terms_style',
			array(
				'label'     => __( 'Terms & Conditions', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_terms' => 'yes',
				),
			)
		);

		$this->add_control(
			'terms_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'terms_link_color',
			array(
				'label'     => __( 'Link Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'terms_link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'terms_typography',
				'selector' => '{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper',
			)
		);

		$this->add_responsive_control(
			'terms_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-terms-and-conditions-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		// Checkbox Style.
		$this->add_control(
			'checkbox_heading',
			array(
				'label'     => __( 'Checkbox', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'checkbox_color',
			array(
				'label'     => __( 'Checkbox Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input[name="terms"]:checked' => 'accent-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'checkbox_size',
			array(
				'label'      => __( 'Checkbox Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} input[name="terms"]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Privacy Style Section.
		$this->start_controls_section(
			'section_privacy_style',
			array(
				'label'     => __( 'Privacy Policy', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_privacy' => 'yes',
				),
			)
		);

		$this->add_control(
			'privacy_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-privacy-policy-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'privacy_link_color',
			array(
				'label'     => __( 'Link Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-privacy-policy-text a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'privacy_typography',
				'selector' => '{{WRAPPER}} .woocommerce-privacy-policy-text',
			)
		);

		$this->end_controls_section();

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
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-place-order',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-place-order',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-place-order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-place-order',
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

		$settings = $this->get_settings_for_display();

		// Check if we're inside a checkout form already.
		$is_inside_form = $this->is_inside_checkout_form();

		// Get checkout URL.
		$checkout_url = wc_get_checkout_url();
		?>
		<div class="mpd-place-order">
			<?php if ( ! $is_inside_form ) : ?>
				<form name="checkout" method="post" class="checkout woocommerce-checkout mpd-checkout-form" action="<?php echo esc_url( $checkout_url ); ?>" enctype="multipart/form-data" novalidate="novalidate">
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_terms'] || 'yes' === $settings['show_privacy'] ) : ?>
				<div class="woocommerce-terms-and-conditions-wrapper">
					<?php if ( 'yes' === $settings['show_privacy'] ) : ?>
						<?php wc_privacy_policy_text( 'checkout' ); ?>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_terms'] ) : ?>
						<?php
						// Get terms page ID.
						$terms_page_id = wc_terms_and_conditions_page_id();
						$terms_enabled = wc_terms_and_conditions_checkbox_enabled();

						if ( $terms_enabled && $terms_page_id ) :
							?>
							<p class="form-row validate-required">
								<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
									<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // phpcs:ignore WordPress.Security.NonceVerification.Missing ?> id="terms" />
									<span class="woocommerce-terms-and-conditions-checkbox-text">
										<?php wc_terms_and_conditions_checkbox_text(); ?>
									</span>
									<span class="required">*</span>
								</label>
								<input type="hidden" name="terms-field" value="1" />
							</p>
						<?php elseif ( 'yes' === $settings['show_terms'] && ! $terms_page_id ) : ?>
							<?php if ( current_user_can( 'manage_options' ) ) : ?>
								<p class="woocommerce-info mpd-admin-notice">
									<?php
									printf(
										/* translators: %s: WooCommerce settings URL */
										esc_html__( 'Terms & Conditions page is not set. Please configure it in %s.', 'magical-products-display' ),
										'<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=advanced' ) ) . '">' . esc_html__( 'WooCommerce Settings → Advanced', 'magical-products-display' ) . '</a>'
									);
									?>
								</p>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="mpd-place-order__button-wrapper">
				<?php
				$order_button_text = apply_filters( 'woocommerce_order_button_text', $settings['button_text'] );
				?>
				<button type="submit" class="button alt <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ); ?>" name="woocommerce_checkout_place_order" id="place_order" value="<?php echo esc_attr( $order_button_text ); ?>" data-value="<?php echo esc_attr( $order_button_text ); ?>">
					<?php echo esc_html( $order_button_text ); ?>
				</button>

				<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
			</div>

			<?php if ( ! $is_inside_form ) : ?>
				</form>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Check if we're already inside a checkout form.
	 *
	 * @since 2.0.0
	 *
	 * @return bool True if inside a checkout form.
	 */
	protected function is_inside_checkout_form() {
		// Check if we're on a page with WooCommerce checkout shortcode.
		global $post;
		if ( $post && has_shortcode( $post->post_content, 'woocommerce_checkout' ) ) {
			return true;
		}

		// Check if this is being rendered inside the WooCommerce checkout template.
		if ( is_checkout() && did_action( 'woocommerce_before_checkout_form' ) ) {
			return true;
		}

		return false;
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
		// Full width class is handled via prefix_class
		#>
		<div class="mpd-place-order">
			<# if ( 'yes' === settings.show_terms || 'yes' === settings.show_privacy ) { #>
				<div class="woocommerce-terms-and-conditions-wrapper">
					<# if ( 'yes' === settings.show_privacy ) { #>
						<div class="woocommerce-privacy-policy-text">
							<p><?php esc_html_e( 'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our', 'magical-products-display' ); ?> <a href="#"><?php esc_html_e( 'privacy policy', 'magical-products-display' ); ?></a>.</p>
						</div>
					<# } #>

					<# if ( 'yes' === settings.show_terms ) { #>
						<p class="form-row validate-required">
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
								<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" id="terms" />
								<span class="woocommerce-terms-and-conditions-checkbox-text">
									<?php esc_html_e( 'I have read and agree to the website', 'magical-products-display' ); ?> <a href="#"><?php esc_html_e( 'terms and conditions', 'magical-products-display' ); ?></a>
								</span>
								<span class="required">*</span>
							</label>
						</p>
					<# } #>
				</div>
			<# } #>

			<div class="mpd-place-order__button-wrapper">
				<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="{{ settings.button_text }}">
					{{ settings.button_text }}
				</button>
			</div>
		</div>
		<?php
	}
}
