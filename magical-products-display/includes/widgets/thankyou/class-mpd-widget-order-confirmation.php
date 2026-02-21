<?php
/**
 * Order Confirmation Widget
 *
 * Displays the thank you / order confirmation message.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

namespace MPD\MagicalShopBuilder\Widgets\ThankYou;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Order_Confirmation
 *
 * Displays the order received/thank you message with order number.
 *
 * @since 2.1.0
 */
class Order_Confirmation extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_THANKYOU;

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	protected $widget_icon = 'eicon-check-circle';

	/**
	 * Get widget name.
	 *
	 * @since 2.1.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-order-confirmation';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.1.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Order Confirmation', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.1.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'order', 'confirmation', 'thank you', 'thankyou', 'received', 'woocommerce', 'message' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => __( 'Show Success Icon', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'icon_type',
			array(
				'label'     => __( 'Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-check-circle',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_text',
			array(
				'label'       => __( 'Title Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Thank you. Your order has been received.', 'magical-products-display' ),
				'placeholder' => __( 'Enter thank you message', 'magical-products-display' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'p'    => 'p',
					'span' => 'span',
				),
				'default' => 'h2',
			)
		);

		$this->add_control(
			'show_order_number',
			array(
				'label'        => __( 'Show Order Number', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'order_number_prefix',
			array(
				'label'       => __( 'Order Number Prefix', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Order Number:', 'magical-products-display' ),
				'placeholder' => __( 'Order Number:', 'magical-products-display' ),
				'condition'   => array(
					'show_order_number' => 'yes',
				),
			)
		);

		// My Account Button.
		$this->add_control(
			'heading_account_button',
			array(
				'label'     => __( 'Account Button', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_account_button',
			array(
				'label'        => __( 'Show My Account Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Show button to view order details in My Account.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'account_button_text',
			array(
				'label'       => __( 'Button Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'View Order Details', 'magical-products-display' ),
				'placeholder' => __( 'View Order Details', 'magical-products-display' ),
				'condition'   => array(
					'show_account_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'account_button_link_type',
			array(
				'label'     => __( 'Link To', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'order',
				'options'   => array(
					'order'   => __( 'This Order', 'magical-products-display' ),
					'orders'  => __( 'Orders List', 'magical-products-display' ),
					'account' => __( 'My Account Dashboard', 'magical-products-display' ),
				),
				'condition' => array(
					'show_account_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'account_button_guest_text',
			array(
				'label'       => __( 'Guest Button Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Track Your Order', 'magical-products-display' ),
				'placeholder' => __( 'Track Your Order', 'magical-products-display' ),
				'description' => __( 'Text for guest customers (not logged in).', 'magical-products-display' ),
				'condition'   => array(
					'show_account_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_failed_message',
			array(
				'label'        => __( 'Show Failed Order Message', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Show a message when order payment failed.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'failed_message',
			array(
				'label'       => __( 'Failed Order Message', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'magical-products-display' ),
				'condition'   => array(
					'show_failed_message' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => __( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-confirmation' => 'text-align: {{VALUE}};',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Confetti Animation, Social Share & Custom Messages', 'magical-products-display' ) );
		}
			$this->add_control(
				'enable_confetti',
				array(
					'label'        => __( 'Confetti Animation', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Show celebration confetti on page load.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_social_share',
				array(
					'label'        => __( 'Social Share Buttons', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Allow customers to share purchase on social media.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'dynamic_message',
				array(
					'label'        => __( 'Dynamic Customer Name', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Include customer first name in thank you message.', 'magical-products-display' ),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register style controls.
	 *
	 * @since 2.1.0
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

		$this->add_control(
			'container_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-confirmation' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '30',
					'right'  => '30',
					'bottom' => '30',
					'left'   => '30',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-confirmation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-order-confirmation',
			)
		);

		$this->add_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-confirmation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-order-confirmation',
			)
		);

		$this->end_controls_section();

		// Icon Style.
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'     => __( 'Icon', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-confirmation-icon i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-order-confirmation-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 200,
					),
					'em' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'default'    => array(
					'size' => 60,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-confirmation-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-order-confirmation-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-confirmation-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-order-confirmation-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-order-confirmation-title',
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
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
					'{{WRAPPER}} .mpd-order-confirmation-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Order Number Style.
		$this->start_controls_section(
			'section_order_number_style',
			array(
				'label'     => __( 'Order Number', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_order_number' => 'yes',
				),
			)
		);

		$this->add_control(
			'order_number_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-confirmation-number' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'order_number_typography',
				'selector' => '{{WRAPPER}} .mpd-order-confirmation-number',
			)
		);

		$this->add_control(
			'order_number_highlight_color',
			array(
				'label'     => __( 'Number Highlight Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-confirmation-number strong' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Account Button Style.
		$this->start_controls_section(
			'section_account_button_style',
			array(
				'label'     => __( 'Account Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_account_button' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'account_button_typography',
				'selector' => '{{WRAPPER}} .mpd-account-button',
			)
		);

		$this->start_controls_tabs( 'account_button_tabs' );

		$this->start_controls_tab(
			'account_button_normal',
			array(
				'label' => __( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'account_button_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'account_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'account_button_hover',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'account_button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'account_button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-account-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'account_button_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => '12',
					'right'  => '30',
					'bottom' => '12',
					'left'   => '30',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'account_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => '6',
					'right'  => '6',
					'bottom' => '6',
					'left'   => '6',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'account_button_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-account-button-wrapper' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	protected function render_widget( $settings ) {
		$order    = $this->get_current_order();

		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Pro feature: Confetti animation.
		$enable_confetti = $this->is_pro() && 'yes' === ( $settings['enable_confetti'] ?? '' );
		
		// Pro feature: Dynamic customer name.
		$dynamic_name = $this->is_pro() && 'yes' === ( $settings['dynamic_message'] ?? '' );
		$customer_name = '';
		if ( $dynamic_name && $order ) {
			$customer_name = $order->get_billing_first_name();
		} elseif ( $dynamic_name && $is_editor ) {
			$customer_name = __( 'John', 'magical-products-display' );
		}

		// Pro feature: Social share.
		$show_social_share = $this->is_pro() && 'yes' === ( $settings['show_social_share'] ?? '' );
		?>
		<div class="mpd-order-confirmation<?php echo esc_attr( $enable_confetti ? ' mpd-confetti-enabled' : '' ); ?>">
			<?php if ( $order || $is_editor ) : ?>
				<?php
				// Check if order failed.
				$order_failed = $order && $order->has_status( 'failed' );

				if ( $order_failed && 'yes' === $settings['show_failed_message'] ) :
					?>
					<div class="mpd-order-confirmation-failed">
						<p class="mpd-order-confirmation-message">
							<?php echo esc_html( $settings['failed_message'] ); ?>
						</p>
						<p class="mpd-order-confirmation-retry">
							<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay">
								<?php esc_html_e( 'Pay', 'magical-products-display' ); ?>
							</a>
						</p>
					</div>
				<?php else : ?>
					<?php if ( 'yes' === $settings['show_icon'] && ! empty( $settings['icon_type']['value'] ) ) : ?>
						<div class="mpd-order-confirmation-icon">
							<?php \Elementor\Icons_Manager::render_icon( $settings['icon_type'], array( 'aria-hidden' => 'true' ) ); ?>
						</div>
					<?php endif; ?>

					<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mpd-order-confirmation-title">
						<?php
						$title_text = $settings['title_text'];
						if ( $dynamic_name && $customer_name ) {
							$title_text = str_replace(
								array( '{customer_name}', 'Thank you' ),
								array( $customer_name, sprintf( __( 'Thank you, %s', 'magical-products-display' ), $customer_name ) ),
								$title_text
							);
						}
						echo esc_html( $title_text );
						?>
					</<?php echo esc_attr( $settings['title_tag'] ); ?>>

					<?php if ( 'yes' === $settings['show_order_number'] ) : ?>
						<p class="mpd-order-confirmation-number">
							<?php echo esc_html( $settings['order_number_prefix'] ); ?>
							<strong>
								<?php
								if ( $order ) {
									echo esc_html( $order->get_order_number() );
								} else {
									echo esc_html( '12345' ); // Demo for editor.
								}
								?>
							</strong>
						</p>
					<?php endif; ?>

					<?php
					// My Account Button.
					if ( 'yes' === $settings['show_account_button'] ) :
						$button_url  = '';
						$button_text = $settings['account_button_text'];
						$is_guest    = ! is_user_logged_in();

						// Adjust text for guests.
						if ( $is_guest && ! empty( $settings['account_button_guest_text'] ) ) {
							$button_text = $settings['account_button_guest_text'];
						}

						// Determine the link URL.
						if ( function_exists( 'wc_get_page_permalink' ) ) {
							$myaccount_url = wc_get_page_permalink( 'myaccount' );
							$link_type     = $settings['account_button_link_type'] ?? 'order';

							if ( 'order' === $link_type && $order ) {
								// Link to specific order view.
								$button_url = $order->get_view_order_url();
							} elseif ( 'orders' === $link_type ) {
								// Link to orders list.
								$button_url = wc_get_endpoint_url( 'orders', '', $myaccount_url );
							} else {
								// Link to My Account dashboard.
								$button_url = $myaccount_url;
							}

							// For guests, always link to My Account (will show login).
							if ( $is_guest ) {
								$button_url = $myaccount_url;
							}
						}

						// Demo URL for editor.
						if ( $is_editor && empty( $button_url ) ) {
							$button_url = '#';
						}

						if ( $button_url ) :
							?>
							<div class="mpd-account-button-wrapper">
								<a href="<?php echo esc_url( $button_url ); ?>" class="mpd-account-button">
									<?php echo esc_html( $button_text ); ?>
								</a>
							</div>
							<?php
						endif;
					endif;
					?>

					<?php if ( $show_social_share ) : ?>
						<div class="mpd-order-confirmation-social-share">
							<p class="mpd-social-share-label"><?php esc_html_e( 'Share your purchase:', 'magical-products-display' ); ?></p>
							<div class="mpd-social-share-buttons">
								<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( home_url() ); ?>" target="_blank" class="mpd-social-btn mpd-social-facebook" aria-label="<?php esc_attr_e( 'Share on Facebook', 'magical-products-display' ); ?>">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
								</a>
								<a href="https://x.com/intent/post?text=<?php echo esc_attr__( 'Just made a purchase!', 'magical-products-display' ); ?>" target="_blank" class="mpd-social-btn mpd-social-x" aria-label="<?php esc_attr_e( 'Share on X', 'magical-products-display' ); ?>">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
								</a>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			<?php else : ?>
				<div class="mpd-order-confirmation-empty">
					<p><?php esc_html_e( 'No order found.', 'magical-products-display' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
		<?php
		// Pro feature: Confetti animation script.
		if ( $enable_confetti ) :
			?>
			<style>
			.mpd-confetti-enabled { position: relative; overflow: hidden; }
			.mpd-confetti { position: absolute; width: 10px; height: 10px; opacity: 0; animation: mpd-confetti-fall 3s ease-out forwards; }
			@keyframes mpd-confetti-fall {
				0% { transform: translateY(-100%) rotate(0deg); opacity: 1; }
				100% { transform: translateY(400%) rotate(720deg); opacity: 0; }
			}
			</style>
			<script>
			(function() {
				var container = document.querySelector('.mpd-confetti-enabled');
				if (!container) return;
				var colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7', '#dfe6e9'];
				for (var i = 0; i < 50; i++) {
					var confetti = document.createElement('div');
					confetti.className = 'mpd-confetti';
					confetti.style.left = Math.random() * 100 + '%';
					confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
					confetti.style.animationDelay = Math.random() * 2 + 's';
					container.appendChild(confetti);
				}
			})();
			</script>
			<?php
		endif;

		// Pro feature: Social share styles.
		if ( $show_social_share ) :
			?>
			<style>
			.mpd-order-confirmation-social-share { margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
			.mpd-social-share-label { font-size: 14px; color: #64748b; margin-bottom: 10px; }
			.mpd-social-share-buttons { display: flex; gap: 10px; justify-content: center; }
			.mpd-social-btn { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; text-decoration: none; transition: transform 0.2s, opacity 0.2s; }
			.mpd-social-btn:hover { transform: scale(1.1); opacity: 0.9; }
			.mpd-social-facebook { background: #1877f2; color: #fff; }
			.mpd-social-x { background: #000000; color: #fff; }
			</style>
			<?php
		endif;

		// Account button base styles.
		if ( 'yes' === $settings['show_account_button'] ) :
			?>
			<style>
			.mpd-account-button-wrapper { margin-top: 20px; }
			.mpd-account-button { display: inline-block; text-decoration: none; font-weight: 500; transition: all 0.3s ease; cursor: pointer; }
			.mpd-account-button:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
			</style>
			<?php
		endif;
	}

	/**
	 * Get current order from URL or session.
	 *
	 * @since 2.1.0
	 *
	 * @return \WC_Order|false Order object or false.
	 */
	private function get_current_order() {
		if ( ! function_exists( 'wc_get_order' ) ) {
			return false;
		}

		// Check for order ID in URL (thank you page).
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$order_id = isset( $_GET['order-received'] ) ? absint( wp_unslash( $_GET['order-received'] ) ) : 0;

		if ( ! $order_id ) {
			global $wp;
			if ( isset( $wp->query_vars['order-received'] ) ) {
				$order_id = absint( $wp->query_vars['order-received'] );
			}
		}

		if ( $order_id ) {
			$order = wc_get_order( $order_id );

			// Verify order key for security.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$order_key = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
			if ( $order && $order->get_order_key() === $order_key ) {
				return $order;
			}
		}

		return false;
	}
}
