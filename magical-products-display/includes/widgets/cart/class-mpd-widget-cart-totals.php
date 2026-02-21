<?php
/**
 * Cart Totals Widget
 *
 * Displays the cart totals section with subtotal, shipping, taxes, and total.
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
 * Class Cart_Totals
 *
 * @since 2.0.0
 */
class Cart_Totals extends Widget_Base {

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
	protected $widget_icon = 'eicon-cart-medium';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-cart-totals';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Cart Totals', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-cart-medium';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'cart', 'totals', 'subtotal', 'total', 'woocommerce', 'checkout', 'summary' );
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
				'default'     => __( 'Cart totals', 'magical-products-display' ),
				'placeholder' => __( 'Cart totals', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_subtotal',
			array(
				'label'        => __( 'Show Subtotal', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_shipping',
			array(
				'label'        => __( 'Show Shipping', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_taxes',
			array(
				'label'        => __( 'Show Taxes', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_coupons',
			array(
				'label'        => __( 'Show Applied Coupons', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_total',
			array(
				'label'        => __( 'Show Total', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_checkout_button',
			array(
				'label'        => __( 'Show Checkout Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'checkout_button_text',
			array(
				'label'       => __( 'Checkout Button Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Proceed to checkout', 'magical-products-display' ),
				'placeholder' => __( 'Proceed to checkout', 'magical-products-display' ),
				'condition'   => array(
					'show_checkout_button' => 'yes',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Tax Breakdown & Savings Display', 'magical-products-display' ) );
		}
			$this->add_control(
				'show_tax_breakdown',
				array(
					'label'        => __( 'Show Tax Breakdown', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Show detailed tax breakdown by rate.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_savings',
				array(
					'label'        => __( 'Show Savings', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Display total savings from discounts and coupons.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'savings_text',
				array(
					'label'       => __( 'Savings Text', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'You save', 'magical-products-display' ),
					'placeholder' => __( 'You save', 'magical-products-display' ),
					'condition'   => array(
						'show_savings' => 'yes',
					),
				)
			);

			$this->add_control(
				'show_free_shipping_notice',
				array(
					'label'        => __( 'Free Shipping Notice', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Show how much more to spend for free shipping.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'free_shipping_threshold',
				array(
					'label'       => __( 'Free Shipping Threshold', 'magical-products-display' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => 50,
					'min'         => 0,
					'description' => __( 'Minimum order amount for free shipping.', 'magical-products-display' ),
					'condition'   => array(
						'show_free_shipping_notice' => 'yes',
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
				'label' => __( 'Container', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'container_background',
				'selector' => '{{WRAPPER}} .mpd-cart-totals',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-cart-totals',
			)
		);

		$this->add_responsive_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-totals' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-cart-totals',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-totals' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Title Style.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => __( 'Title', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-totals-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-cart-totals-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-totals-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Table Style.
		$this->start_controls_section(
			'section_table_style',
			array(
				'label' => __( 'Totals Table', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'row_label_color',
			array(
				'label'     => __( 'Label Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-totals table th' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'row_label_typography',
				'label'    => __( 'Label Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-cart-totals table th',
			)
		);

		$this->add_control(
			'row_value_color',
			array(
				'label'     => __( 'Value Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-totals table td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'row_value_typography',
				'label'    => __( 'Value Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-cart-totals table td',
			)
		);

		$this->add_control(
			'row_border_color',
			array(
				'label'     => __( 'Row Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-totals table tr' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'row_padding',
			array(
				'label'      => __( 'Row Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-totals table th, {{WRAPPER}} .mpd-cart-totals table td, {{WRAPPER}} .mpd-cart-totals .shop_table th, {{WRAPPER}} .mpd-cart-totals .shop_table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Total row.
		$this->add_control(
			'total_heading',
			array(
				'label'     => __( 'Total Row', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'total_label_color',
			array(
				'label'     => __( 'Total Label Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-totals .order-total th' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'total_value_color',
			array(
				'label'     => __( 'Total Value Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-cart-totals .order-total td' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'total_typography',
				'label'    => __( 'Total Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-cart-totals .order-total th, {{WRAPPER}} .mpd-cart-totals .order-total td',
			)
		);

		$this->end_controls_section();

		// Button Style.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => __( 'Checkout Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_checkout_button' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .mpd-cart-totals .checkout-button',
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
					'{{WRAPPER}} .mpd-cart-totals .checkout-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'selector' => '{{WRAPPER}} .mpd-cart-totals .checkout-button',
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
					'{{WRAPPER}} .mpd-cart-totals .checkout-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'selector' => '{{WRAPPER}} .mpd-cart-totals .checkout-button:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-totals .checkout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-cart-totals .checkout-button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-totals .checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-cart-totals .checkout-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Savings Style (Pro).
		if ( $this->is_pro() ) {
			$this->start_controls_section(
				'section_savings_style',
				array(
					'label' => __( 'Savings Badge', 'magical-products-display' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_control(
				'savings_background_color',
				array(
					'label'     => __( 'Background Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-cart-savings' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'savings_text_color',
				array(
					'label'     => __( 'Text Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-cart-savings' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'savings_typography',
					'selector' => '{{WRAPPER}} .mpd-cart-savings',
				)
			);

			$this->add_responsive_control(
				'savings_padding',
				array(
					'label'      => __( 'Padding', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-cart-savings' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'savings_border_radius',
				array(
					'label'      => __( 'Border Radius', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-cart-savings' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		// Check if we're in editor mode.
		if ( $this->is_editor_mode() ) {
			$this->render_editor_preview( $settings );
			return;
		}

		// Get cart.
		$cart = WC()->cart;

		// Check if cart is empty.
		if ( ! $cart || $cart->is_empty() ) {
			return;
		}

		$this->render_cart_totals( $settings, $cart );
	}

	/**
	 * Render cart totals.
	 *
	 * @since 2.0.0
	 *
	 * @param array   $settings Widget settings.
	 * @param WC_Cart $cart     Cart object.
	 * @return void
	 */
	private function render_cart_totals( $settings, $cart ) {
		$show_subtotal       = 'yes' === $settings['show_subtotal'];
		$show_shipping       = 'yes' === $settings['show_shipping'];
		$show_taxes          = 'yes' === $settings['show_taxes'];
		$show_coupons        = 'yes' === $settings['show_coupons'];
		$show_total          = 'yes' === $settings['show_total'];
		$show_checkout       = 'yes' === $settings['show_checkout_button'];
		$checkout_text       = ! empty( $settings['checkout_button_text'] ) ? $settings['checkout_button_text'] : __( 'Proceed to checkout', 'magical-products-display' );
		$title               = $settings['title'];

		// Pro features.
		$show_tax_breakdown   = $this->is_pro() && isset( $settings['show_tax_breakdown'] ) && 'yes' === $settings['show_tax_breakdown'];
		$show_savings         = $this->is_pro() && isset( $settings['show_savings'] ) && 'yes' === $settings['show_savings'];
		$savings_text         = $this->is_pro() && isset( $settings['savings_text'] ) ? $settings['savings_text'] : __( 'You save', 'magical-products-display' );
		$show_free_shipping   = $this->is_pro() && isset( $settings['show_free_shipping_notice'] ) && 'yes' === $settings['show_free_shipping_notice'];
		$free_shipping_threshold = $this->is_pro() && isset( $settings['free_shipping_threshold'] ) ? floatval( $settings['free_shipping_threshold'] ) : 50;
		?>
		<div class="mpd-cart-totals cart_totals">
			<?php if ( ! empty( $title ) ) : ?>
				<h2 class="mpd-cart-totals-title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<?php do_action( 'woocommerce_before_cart_totals' ); ?>

			<?php
			// Free shipping notice (Pro).
			if ( $show_free_shipping && $free_shipping_threshold > 0 ) {
				$cart_total = $cart->get_cart_contents_total();
				if ( $cart_total < $free_shipping_threshold ) {
					$remaining = $free_shipping_threshold - $cart_total;
					?>
					<div class="mpd-free-shipping-notice">
						<?php
						printf(
							/* translators: %s: amount remaining for free shipping */
							esc_html__( 'Spend %s more for free shipping!', 'magical-products-display' ),
							wc_price( $remaining )
						);
						?>
						<div class="mpd-free-shipping-progress">
							<div class="mpd-free-shipping-progress-bar" style="width: <?php echo esc_attr( ( $cart_total / $free_shipping_threshold ) * 100 ); ?>%;"></div>
						</div>
					</div>
					<?php
				} else {
					?>
					<div class="mpd-free-shipping-notice mpd-free-shipping-achieved">
						<span class="dashicons dashicons-yes-alt"></span>
						<?php esc_html_e( 'You qualify for free shipping!', 'magical-products-display' ); ?>
					</div>
					<?php
				}
			}
			?>

			<table cellspacing="0" class="shop_table shop_table_responsive">
				<?php if ( $show_subtotal ) : ?>
					<tr class="cart-subtotal">
						<th><?php esc_html_e( 'Subtotal', 'magical-products-display' ); ?></th>
						<td data-title="<?php esc_attr_e( 'Subtotal', 'magical-products-display' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $show_coupons ) : ?>
					<?php foreach ( $cart->get_coupons() as $code => $coupon ) : ?>
						<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
							<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
							<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				<?php if ( $show_shipping && WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
					<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
					<?php wc_cart_totals_shipping_html(); ?>
					<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
				<?php endif; ?>

				<?php if ( $show_taxes ) : ?>
					<?php foreach ( $cart->get_fees() as $fee ) : ?>
						<tr class="fee">
							<th><?php echo esc_html( $fee->name ); ?></th>
							<td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
						</tr>
					<?php endforeach; ?>

					<?php
					if ( wc_tax_enabled() && ! $cart->display_prices_including_tax() ) {
						$taxable_address = WC()->customer->get_taxable_address();
						$estimated_text  = '';

						if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
							/* translators: %s: country name */
							$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'magical-products-display' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
						}

						if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) || $show_tax_breakdown ) {
							foreach ( $cart->get_tax_totals() as $code => $tax ) {
								?>
								<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
									<th><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
									<td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
								</tr>
								<?php
							}
						} else {
							?>
							<tr class="tax-total">
								<th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
								<td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
							</tr>
							<?php
						}
					}
					?>
				<?php endif; ?>

				<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

				<?php if ( $show_total ) : ?>
					<tr class="order-total">
						<th><?php esc_html_e( 'Total', 'magical-products-display' ); ?></th>
						<td data-title="<?php esc_attr_e( 'Total', 'magical-products-display' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
					</tr>
				<?php endif; ?>

				<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
			</table>

			<?php
			// Savings display (Pro).
			if ( $show_savings ) {
				$savings = $this->calculate_savings( $cart );
				if ( $savings > 0 ) {
					?>
					<div class="mpd-cart-savings">
						<span class="mpd-savings-icon">🎉</span>
						<span class="mpd-savings-text"><?php echo esc_html( $savings_text ); ?>: </span>
						<span class="mpd-savings-amount"><?php echo wc_price( $savings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</div>
					<?php
				}
			}
			?>

			<div class="wc-proceed-to-checkout">
				<?php
				// Only run WooCommerce hook if we're NOT showing our custom button
				// This prevents duplicate checkout buttons
				if ( ! $show_checkout ) {
					do_action( 'woocommerce_proceed_to_checkout' );
				}
				?>

				<?php if ( $show_checkout ) : ?>
					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward">
						<?php echo esc_html( $checkout_text ); ?>
					</a>
				<?php endif; ?>
			</div>

			<?php do_action( 'woocommerce_after_cart_totals' ); ?>
		</div>
		<?php
	}

	/**
	 * Calculate total savings.
	 *
	 * @since 2.0.0
	 *
	 * @param WC_Cart $cart Cart object.
	 * @return float Total savings.
	 */
	private function calculate_savings( $cart ) {
		$savings = 0;

		// Calculate savings from sale items.
		foreach ( $cart->get_cart() as $cart_item ) {
			$product = $cart_item['data'];
			if ( $product->is_on_sale() ) {
				$regular_price = (float) $product->get_regular_price();
				$sale_price    = (float) $product->get_price();
				$savings      += ( $regular_price - $sale_price ) * $cart_item['quantity'];
			}
		}

		// Add coupon discounts.
		$savings += $cart->get_discount_total();

		return $savings;
	}

	/**
	 * Render editor preview.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_editor_preview( $settings ) {
		$show_subtotal  = 'yes' === $settings['show_subtotal'];
		$show_shipping  = 'yes' === $settings['show_shipping'];
		$show_taxes     = 'yes' === $settings['show_taxes'];
		$show_total     = 'yes' === $settings['show_total'];
		$show_checkout  = 'yes' === $settings['show_checkout_button'];
		$checkout_text  = ! empty( $settings['checkout_button_text'] ) ? $settings['checkout_button_text'] : __( 'Proceed to checkout', 'magical-products-display' );
		$title          = $settings['title'];

		// Pro features.
		$show_tax_breakdown   = $this->is_pro() && isset( $settings['show_tax_breakdown'] ) && 'yes' === $settings['show_tax_breakdown'];
		$show_savings         = $this->is_pro() && isset( $settings['show_savings'] ) && 'yes' === $settings['show_savings'];
		$savings_text         = $this->is_pro() && isset( $settings['savings_text'] ) ? $settings['savings_text'] : __( 'You save', 'magical-products-display' );
		$show_free_shipping   = $this->is_pro() && isset( $settings['show_free_shipping_notice'] ) && 'yes' === $settings['show_free_shipping_notice'];
		$free_shipping_threshold = $this->is_pro() && isset( $settings['free_shipping_threshold'] ) ? floatval( $settings['free_shipping_threshold'] ) : 50;

		// Sample prices.
		$subtotal = 150.00;
		$shipping = 10.00;
		$tax      = 12.00;
		$total    = $subtotal + $shipping + $tax;
		$sample_savings = 25.00;
		?>
		<div class="mpd-cart-totals cart_totals mpd-editor-preview">
			<?php if ( ! empty( $title ) ) : ?>
				<h2 class="mpd-cart-totals-title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<?php
			// Free shipping notice (Pro).
			if ( $show_free_shipping && $free_shipping_threshold > 0 ) {
				if ( $subtotal < $free_shipping_threshold ) {
					$remaining = $free_shipping_threshold - $subtotal;
					?>
					<div class="mpd-free-shipping-notice">
						<?php
						printf(
							/* translators: %s: amount remaining for free shipping */
							esc_html__( 'Spend %s more for free shipping!', 'magical-products-display' ),
							wc_price( $remaining )
						);
						?>
						<div class="mpd-free-shipping-progress">
							<div class="mpd-free-shipping-progress-bar" style="width: <?php echo esc_attr( ( $subtotal / $free_shipping_threshold ) * 100 ); ?>%;"></div>
						</div>
					</div>
					<?php
				} else {
					?>
					<div class="mpd-free-shipping-notice mpd-free-shipping-achieved">
						<span class="dashicons dashicons-yes-alt"></span>
						<?php esc_html_e( 'You qualify for free shipping!', 'magical-products-display' ); ?>
					</div>
					<?php
				}
			}
			?>

			<table cellspacing="0" class="shop_table shop_table_responsive">
				<?php if ( $show_subtotal ) : ?>
					<tr class="cart-subtotal">
						<th><?php esc_html_e( 'Subtotal', 'magical-products-display' ); ?></th>
						<td><?php echo wc_price( $subtotal ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
					</tr>
				<?php endif; ?>

				<?php if ( $show_shipping ) : ?>
					<tr class="shipping">
						<th><?php esc_html_e( 'Shipping', 'magical-products-display' ); ?></th>
						<td>
							<span class="woocommerce-Price-amount amount"><?php echo wc_price( $shipping ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ( $show_taxes ) : ?>
					<?php if ( $show_tax_breakdown ) : ?>
						<tr class="tax-rate tax-rate-sample-1">
							<th><?php esc_html_e( 'VAT (10%)', 'magical-products-display' ); ?></th>
							<td><?php echo wc_price( 8.00 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
						<tr class="tax-rate tax-rate-sample-2">
							<th><?php esc_html_e( 'Local Tax (2.5%)', 'magical-products-display' ); ?></th>
							<td><?php echo wc_price( 4.00 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php else : ?>
						<tr class="tax-total">
							<th><?php esc_html_e( 'Tax', 'magical-products-display' ); ?></th>
							<td><?php echo wc_price( $tax ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( $show_total ) : ?>
					<tr class="order-total">
						<th><?php esc_html_e( 'Total', 'magical-products-display' ); ?></th>
						<td><strong><?php echo wc_price( $total ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong></td>
					</tr>
				<?php endif; ?>
			</table>

			<?php
			// Savings display (Pro).
			if ( $show_savings ) {
				?>
				<div class="mpd-cart-savings">
					<span class="mpd-savings-icon">🎉</span>
					<span class="mpd-savings-text"><?php echo esc_html( $savings_text ); ?>: </span>
					<span class="mpd-savings-amount"><?php echo wc_price( $sample_savings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</div>
				<?php
			}
			?>

			<?php if ( $show_checkout ) : ?>
				<div class="wc-proceed-to-checkout">
					<a href="#" class="checkout-button button alt wc-forward">
						<?php echo esc_html( $checkout_text ); ?>
					</a>
				</div>
			<?php endif; ?>

			<p class="mpd-editor-notice" style="text-align: center; padding: 10px; background: #e8f4fd; color: #0c5460; margin-top: 15px; border-radius: 4px;">
				<strong>📝 <?php esc_html_e( 'Editor Preview:', 'magical-products-display' ); ?></strong>
				<?php esc_html_e( 'This is a preview with sample data. Actual totals will display on the frontend.', 'magical-products-display' ); ?>
			</p>
		</div>
		<?php
	}
}
