<?php
/**
 * Checkout Coupon Widget
 *
 * Displays the checkout coupon field with inline validation.
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
 * Class Checkout_Coupon
 *
 * @since 2.0.0
 */
class Checkout_Coupon extends Widget_Base {

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
	protected $widget_icon = 'eicon-barcode';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-checkout-coupon';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Checkout Coupon', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'coupon', 'checkout', 'discount', 'promo', 'code', 'woocommerce', 'voucher' );
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
			'coupon_toggle_text',
			array(
				'label'       => __( 'Toggle Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Have a coupon? Click here to enter your code', 'magical-products-display' ),
				'placeholder' => __( 'Enter toggle text', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_toggle',
			array(
				'label'        => __( 'Show as Collapsible', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'placeholder_text',
			array(
				'label'       => __( 'Placeholder Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Coupon code', 'magical-products-display' ),
				'placeholder' => __( 'Enter placeholder text', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => __( 'Button Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Apply coupon', 'magical-products-display' ),
				'placeholder' => __( 'Enter button text', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Editor Preview Section.
		$this->start_controls_section(
			'section_editor_preview',
			array(
				'label' => __( 'Editor Preview', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'editor_preview_mode',
			array(
				'label'       => __( 'Editor Preview Mode', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'demo'  => __( 'Demo Content', 'magical-products-display' ),
					'empty' => __( 'Empty State', 'magical-products-display' ),
				),
				'default'     => 'demo',
				'description' => __( 'Choose how to preview this widget in the editor. Demo shows sample coupons for styling, Empty shows the actual state.', 'magical-products-display' ),
				'render_type' => 'template',
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
			'inline_validation_pro',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( __( 'Inline validation with real-time coupon feedback is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'enable_inline_validation',
			array(
				'label'        => __( 'Enable Inline Validation', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
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
				'prefix_class' => 'mpd-coupon-layout-',
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
					'{{WRAPPER}} .mpd-checkout-coupon__input' => 'width: {{SIZE}}{{UNIT}};',
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
		// Toggle Style Section.
		$this->start_controls_section(
			'section_toggle_style',
			array(
				'label'     => __( 'Toggle', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_toggle' => 'yes',
				),
			)
		);

		$this->add_control(
			'toggle_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-coupon__toggle' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-coupon__toggle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_link_color',
			array(
				'label'     => __( 'Link Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-coupon__toggle a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_typography',
				'selector' => '{{WRAPPER}} .mpd-checkout-coupon__toggle',
			)
		);

		$this->add_responsive_control(
			'toggle_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-coupon__toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'toggle_border',
				'selector' => '{{WRAPPER}} .mpd-checkout-coupon__toggle',
			)
		);

		$this->end_controls_section();

		// Input Style Section.
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
					'{{WRAPPER}} .mpd-checkout-coupon__input' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_placeholder_color',
			array(
				'label'     => __( 'Placeholder Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-coupon__input::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .mpd-checkout-coupon__input',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'input_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-checkout-coupon__input',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'input_border',
				'selector' => '{{WRAPPER}} .mpd-checkout-coupon__input',
			)
		);

		$this->add_responsive_control(
			'input_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-coupon__input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-checkout-coupon__input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-checkout-coupon__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-checkout-coupon__button',
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
					'{{WRAPPER}} .mpd-checkout-coupon__button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background_hover',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-checkout-coupon__button:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .mpd-checkout-coupon__button',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-checkout-coupon__button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-coupon__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-checkout-coupon__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Applied Coupons Style.
		$this->start_controls_section(
			'section_applied_style',
			array(
				'label'      => __( 'Applied Coupons', 'magical-products-display' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'show_applied_coupons',
							'operator' => '===',
							'value'    => 'yes',
						),
						array(
							'name'     => 'editor_preview_mode',
							'operator' => '===',
							'value'    => 'demo',
						),
					),
				),
			)
		);

		$this->add_control(
			'applied_background',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-coupon__applied-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'applied_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-coupon__applied-item' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'applied_remove_color',
			array(
				'label'     => __( 'Remove Button Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-checkout-coupon__remove' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'applied_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-coupon__applied-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'applied_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-coupon__applied-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
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
				'selector' => '{{WRAPPER}} .mpd-checkout-coupon',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-checkout-coupon',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-checkout-coupon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-checkout-coupon',
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

		// Check if coupons are enabled.
		if ( ! wc_coupons_enabled() ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		// Layout class is now handled via prefix_class
		?>
		<div class="mpd-checkout-coupon">
			<?php if ( 'yes' === $settings['show_toggle'] ) : ?>
				<div class="woocommerce-form-coupon-toggle">
					<div class="mpd-checkout-coupon__toggle woocommerce-info">
						<?php
						echo wp_kses_post(
							apply_filters(
								'woocommerce_checkout_coupon_message',
								$settings['coupon_toggle_text']
							)
						);
						?>
						<a href="#" class="showcoupon"><?php esc_html_e( 'Click here', 'magical-products-display' ); ?></a>
					</div>
				</div>
			<?php endif; ?>

			<form class="checkout_coupon woocommerce-form-coupon mpd-checkout-coupon__form" method="post" <?php echo 'yes' === $settings['show_toggle'] ? 'style="display:none;"' : ''; ?>>
				<p class="form-row form-row-first">
					<input type="text" name="coupon_code" class="input-text mpd-checkout-coupon__input" placeholder="<?php echo esc_attr( $settings['placeholder_text'] ); ?>" id="coupon_code" value="" />
				</p>

				<p class="form-row form-row-last">
					<button type="submit" class="button mpd-checkout-coupon__button" name="apply_coupon" value="<?php echo esc_attr( $settings['button_text'] ); ?>">
						<?php echo esc_html( $settings['button_text'] ); ?>
					</button>
				</p>

				<div class="clear"></div>
			</form>

			<?php if ( 'yes' === $settings['show_applied_coupons'] ) : ?>
				<?php $coupons = WC()->cart ? WC()->cart->get_applied_coupons() : array(); ?>
				<div class="mpd-checkout-coupon__applied"<?php echo empty( $coupons ) ? ' style="display:none;"' : ''; ?>>
					<?php foreach ( $coupons as $coupon_code ) : ?>
						<span class="mpd-checkout-coupon__applied-item">
							<?php echo esc_html( strtoupper( $coupon_code ) ); ?>
							<a href="<?php echo esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon_code ), wc_get_checkout_url() ) ); ?>" class="mpd-checkout-coupon__remove" data-coupon="<?php echo esc_attr( $coupon_code ); ?>">×</a>
						</span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
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
		// Layout class is handled via prefix_class
		var showDemo = 'demo' === settings.editor_preview_mode;
		var showEmpty = 'empty' === settings.editor_preview_mode;
		#>
		<div class="mpd-checkout-coupon">
			<# if ( 'yes' === settings.show_toggle ) { #>
				<div class="woocommerce-form-coupon-toggle">
					<div class="mpd-checkout-coupon__toggle woocommerce-info">
						{{{ settings.coupon_toggle_text }}}
						<a href="#" class="showcoupon"><?php esc_html_e( 'Click here', 'magical-products-display' ); ?></a>
					</div>
				</div>
			<# } #>

			<# 
			// In editor, always show the form for styling (collapsible toggle is preview only)
			// Add visibility class for styling when toggle is enabled
			var formVisibilityClass = ( 'yes' === settings.show_toggle ) ? ' mpd-coupon-form-preview' : '';
			#>
			<form class="checkout_coupon woocommerce-form-coupon mpd-checkout-coupon__form{{ formVisibilityClass }}" method="post">
				<p class="form-row form-row-first">
					<input type="text" name="coupon_code" class="input-text mpd-checkout-coupon__input" placeholder="{{ settings.placeholder_text }}" id="coupon_code" value="" />
				</p>

				<p class="form-row form-row-last">
					<button type="submit" class="button mpd-checkout-coupon__button" name="apply_coupon" value="{{ settings.button_text }}">
						{{ settings.button_text }}
					</button>
				</p>

				<div class="clear"></div>
			</form>

			<# if ( 'yes' === settings.show_applied_coupons && showDemo ) { #>
				<div class="mpd-checkout-coupon__applied">
					<span class="mpd-checkout-coupon__applied-item">
						SUMMER20
						<a href="#" class="mpd-checkout-coupon__remove">×</a>
					</span>
					<span class="mpd-checkout-coupon__applied-item">
						FREESHIP
						<a href="#" class="mpd-checkout-coupon__remove">×</a>
					</span>
				</div>
			<# } else if ( 'yes' === settings.show_applied_coupons && showEmpty ) { #>
				<div class="mpd-checkout-coupon__applied mpd-checkout-coupon__applied--empty">
					<span class="mpd-checkout-coupon__applied-empty-text"><?php esc_html_e( 'No coupons applied', 'magical-products-display' ); ?></span>
				</div>
			<# } #>

			<# if ( showDemo ) { #>
				<div class="mpd-editor-preview-notice" style="margin-top: 15px; padding: 10px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; font-size: 12px; color: #856404;">
					<strong><?php esc_html_e( 'Editor Preview:', 'magical-products-display' ); ?></strong>
					<?php esc_html_e( 'Demo coupons shown. On frontend, actual applied coupons will display. Collapsible toggle works on frontend only.', 'magical-products-display' ); ?>
				</div>
			<# } #>
		</div>
		<?php
	}
}
