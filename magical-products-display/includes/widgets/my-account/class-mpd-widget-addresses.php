<?php
/**
 * Addresses Widget
 *
 * Displays the WooCommerce customer addresses with inline editing.
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
 * Class Addresses
 *
 * @since 2.0.0
 */
class Addresses extends Widget_Base {

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
	protected $widget_icon = 'eicon-map-pin';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-account-addresses';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Addresses', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'address', 'addresses', 'billing', 'shipping', 'my account', 'woocommerce', 'user' );
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
			'section_title',
			array(
				'label'       => esc_html__( 'Section Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'My Addresses', 'magical-products-display' ),
				'placeholder' => esc_html__( 'Enter section title', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Show Section Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_description',
			array(
				'label'        => esc_html__( 'Show Description', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'description',
			array(
				'label'     => esc_html__( 'Description', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => esc_html__( 'The following addresses will be used on the checkout page by default.', 'magical-products-display' ),
				'condition' => array(
					'show_description' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_billing',
			array(
				'label'        => esc_html__( 'Show Billing Address', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_shipping',
			array(
				'label'        => esc_html__( 'Show Shipping Address', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'billing_title',
			array(
				'label'     => esc_html__( 'Billing Address Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Billing Address', 'magical-products-display' ),
				'condition' => array(
					'show_billing' => 'yes',
				),
			)
		);

		$this->add_control(
			'shipping_title',
			array(
				'label'     => esc_html__( 'Shipping Address Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Shipping Address', 'magical-products-display' ),
				'condition' => array(
					'show_shipping' => 'yes',
				),
			)
		);

		$this->add_control(
			'edit_button_text',
			array(
				'label'   => esc_html__( 'Edit Button Text', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Edit', 'magical-products-display' ),
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
					'side-by-side' => esc_html__( 'Side by Side', 'magical-products-display' ),
					'stacked'      => esc_html__( 'Stacked', 'magical-products-display' ),
				),
				'default' => 'side-by-side',
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
					'{{WRAPPER}} .mpd-addresses__container' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Inline Editing Section (Pro).
		$this->start_controls_section(
			'section_inline_editing',
			array(
				'label' => esc_html__( 'Inline Editing', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'inline_editing_pro',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Inline editing allows customers to edit addresses without leaving the page. This is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'enable_inline_editing',
			array(
				'label'        => esc_html__( 'Enable Inline Editing', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
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
		// Section Title Style.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => esc_html__( 'Section Title', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-addresses__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-addresses__title',
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
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-addresses__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Description Style.
		$this->start_controls_section(
			'section_description_style',
			array(
				'label'     => esc_html__( 'Description', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_description' => 'yes',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-addresses__description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .mpd-addresses__description',
			)
		);

		$this->add_responsive_control(
			'description_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-addresses__description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Address Card Style.
		$this->start_controls_section(
			'section_card_style',
			array(
				'label' => esc_html__( 'Address Card', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'card_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-addresses__card',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .mpd-addresses__card',
			)
		);

		$this->add_responsive_control(
			'card_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-addresses__card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-addresses__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-addresses__card',
			)
		);

		$this->end_controls_section();

		// Card Title Style.
		$this->start_controls_section(
			'section_card_title_style',
			array(
				'label' => esc_html__( 'Card Title', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_title_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-addresses__card-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'card_title_typography',
				'selector' => '{{WRAPPER}} .mpd-addresses__card-title',
			)
		);

		$this->add_responsive_control(
			'card_title_spacing',
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
					'{{WRAPPER}} .mpd-addresses__card-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Address Text Style.
		$this->start_controls_section(
			'section_address_text_style',
			array(
				'label' => esc_html__( 'Address Text', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'address_text_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-addresses__content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'address_text_typography',
				'selector' => '{{WRAPPER}} .mpd-addresses__content',
			)
		);

		$this->end_controls_section();

		// Edit Button Style.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => esc_html__( 'Edit Button', 'magical-products-display' ),
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
					'{{WRAPPER}} .mpd-addresses__edit-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-addresses__edit-button',
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
					'{{WRAPPER}} .mpd-addresses__edit-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-addresses__edit-button:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .mpd-addresses__edit-button',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-addresses__edit-button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-addresses__edit-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-addresses__edit-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Empty Address Style.
		$this->start_controls_section(
			'section_empty_style',
			array(
				'label' => esc_html__( 'Empty Address', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'empty_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-addresses__empty' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'empty_text_typography',
				'selector' => '{{WRAPPER}} .mpd-addresses__empty',
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
		// Check if user is logged in.
		if ( ! is_user_logged_in() ) {
			// In editor mode, show placeholder.
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				$this->render_editor_placeholder(
					esc_html__( 'Addresses', 'magical-products-display' ),
					esc_html__( 'This widget displays addresses for logged-in users.', 'magical-products-display' )
				);
			}
			return;
		}

		// Only show on edit-address endpoint (skip in editor for preview).
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() && ! is_wc_endpoint_url( 'edit-address' ) ) {
			return;
		}

		// Check if we're editing a specific address type.
		global $wp;
		$address_type = isset( $wp->query_vars['edit-address'] ) ? sanitize_text_field( $wp->query_vars['edit-address'] ) : '';

		// If editing a specific address, show the WooCommerce edit form.
		if ( ! empty( $address_type ) && in_array( $address_type, array( 'billing', 'shipping' ), true ) ) {
			$this->render_edit_form( $address_type, $settings );
			return;
		}

		$customer_id = get_current_user_id();
		$layout_class = 'mpd-addresses--' . esc_attr( $settings['layout'] );
		?>
		<div class="mpd-addresses <?php echo esc_attr( $layout_class ); ?>">
			<?php
			// Display WooCommerce notices (e.g. after address saved and redirected back).
			if ( function_exists( 'wc_print_notices' ) && ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				wc_print_notices();
			}
			?>

			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['section_title'] ) ) : ?>
				<h3 class="mpd-addresses__title"><?php echo esc_html( $settings['section_title'] ); ?></h3>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_description'] && ! empty( $settings['description'] ) ) : ?>
				<p class="mpd-addresses__description"><?php echo esc_html( $settings['description'] ); ?></p>
			<?php endif; ?>

			<div class="mpd-addresses__container">
				<?php if ( 'yes' === $settings['show_billing'] ) : ?>
					<?php $this->render_address_card( 'billing', $settings, $customer_id ); ?>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_shipping'] ) : ?>
					<?php
					// Only show shipping if enabled in WooCommerce.
					if ( wc_shipping_enabled() ) {
						$this->render_address_card( 'shipping', $settings, $customer_id );
					}
					?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render the address edit form.
	 *
	 * @since 2.0.0
	 *
	 * @param string $address_type Address type (billing/shipping).
	 * @param array  $settings     Widget settings.
	 * @return void
	 */
	protected function render_edit_form( $address_type, $settings ) {
		$page_title = 'billing' === $address_type
			? esc_html__( 'Billing address', 'magical-products-display' )
			: esc_html__( 'Shipping address', 'magical-products-display' );

		$customer_id = get_current_user_id();

		// Get country for address fields.
		$country = get_user_meta( $customer_id, $address_type . '_country', true );
		if ( ! $country ) {
			$country = WC()->countries->get_base_country();
		}

		// Get the address fields - use the locale-based approach.
		$address_fields = WC()->countries->get_address_fields( $country, $address_type . '_' );

		// If WC fields are empty, use fallback.
		if ( empty( $address_fields ) ) {
			$address_fields = $this->get_address_form_fields( $address_type, $country );
		}

		// Load customer data for the address.
		$customer = new \WC_Customer( $customer_id );

		// Set field values from customer data.
		foreach ( $address_fields as $key => $field ) {
			$field_key = str_replace( $address_type . '_', '', $key );
			$getter = "get_{$address_type}_{$field_key}";
			
			if ( is_callable( array( $customer, $getter ) ) ) {
				$address_fields[ $key ]['value'] = $customer->$getter();
			} else {
				$address_fields[ $key ]['value'] = get_user_meta( $customer_id, $key, true );
			}
		}

		if ( empty( $address_fields ) ) {
			echo '<p>' . esc_html__( 'Unable to load address fields.', 'magical-products-display' ) . '</p>';
			return;
		}
		?>
		<div class="mpd-addresses mpd-addresses--edit-form">
			<?php
			// Display WooCommerce notices (success/error messages after form submit).
			if ( function_exists( 'wc_print_notices' ) && ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				wc_print_notices();
			}
			?>

			<?php do_action( 'woocommerce_before_edit_account_address_form' ); ?>
			
			<form method="post" class="mpd-addresses__form woocommerce-address-fields">
				<h3 class="mpd-addresses__form-title"><?php echo wp_kses_post( apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $address_type ) ); ?></h3>

				<div class="woocommerce-address-fields">
					<?php do_action( "woocommerce_before_edit_address_form_{$address_type}" ); ?>

					<div class="woocommerce-address-fields__field-wrapper">
						<?php
						foreach ( $address_fields as $key => $field ) {
							$value = isset( $field['value'] ) ? $field['value'] : '';
							woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $value ) );
						}
						?>
					</div>

					<?php do_action( "woocommerce_after_edit_address_form_{$address_type}" ); ?>

					<p class="mpd-addresses__form-buttons">
						<button type="submit" class="button mpd-addresses__save-button" name="save_address" value="<?php esc_attr_e( 'Save address', 'magical-products-display' ); ?>">
							<?php esc_html_e( 'Save address', 'magical-products-display' ); ?>
						</button>
						<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
						<input type="hidden" name="action" value="edit_address" />
					</p>
				</div>
			</form>

			<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
		</div>
		<?php
	}

	/**
	 * Get address form fields for a specific address type.
	 *
	 * @since 2.0.0
	 *
	 * @param string $address_type Address type (billing/shipping).
	 * @param string $country      Country code.
	 * @return array Address fields.
	 */
	protected function get_address_form_fields( $address_type, $country ) {
		// Define default address fields.
		$default_fields = array(
			'first_name' => array(
				'label'        => __( 'First name', 'magical-products-display' ),
				'required'     => true,
				'class'        => array( 'form-row-first' ),
				'autocomplete' => 'given-name',
				'priority'     => 10,
			),
			'last_name'  => array(
				'label'        => __( 'Last name', 'magical-products-display' ),
				'required'     => true,
				'class'        => array( 'form-row-last' ),
				'autocomplete' => 'family-name',
				'priority'     => 20,
			),
			'company'    => array(
				'label'        => __( 'Company name', 'magical-products-display' ),
				'class'        => array( 'form-row-wide' ),
				'autocomplete' => 'organization',
				'priority'     => 30,
				'required'     => false,
			),
			'country'    => array(
				'type'         => 'country',
				'label'        => __( 'Country / Region', 'magical-products-display' ),
				'required'     => true,
				'class'        => array( 'form-row-wide', 'address-field', 'update_totals_on_change' ),
				'autocomplete' => 'country',
				'priority'     => 40,
			),
			'address_1'  => array(
				'label'        => __( 'Street address', 'magical-products-display' ),
				'placeholder'  => esc_attr__( 'House number and street name', 'magical-products-display' ),
				'required'     => true,
				'class'        => array( 'form-row-wide', 'address-field' ),
				'autocomplete' => 'address-line1',
				'priority'     => 50,
			),
			'address_2'  => array(
				'label'        => __( 'Apartment, suite, unit, etc.', 'magical-products-display' ),
				'label_class'  => array( 'screen-reader-text' ),
				'placeholder'  => esc_attr__( 'Apartment, suite, unit, etc. (optional)', 'magical-products-display' ),
				'class'        => array( 'form-row-wide', 'address-field' ),
				'autocomplete' => 'address-line2',
				'priority'     => 60,
				'required'     => false,
			),
			'city'       => array(
				'label'        => __( 'Town / City', 'magical-products-display' ),
				'required'     => true,
				'class'        => array( 'form-row-wide', 'address-field' ),
				'autocomplete' => 'address-level2',
				'priority'     => 70,
			),
			'state'      => array(
				'type'         => 'state',
				'label'        => __( 'State / County', 'magical-products-display' ),
				'required'     => true,
				'class'        => array( 'form-row-wide', 'address-field' ),
				'validate'     => array( 'state' ),
				'autocomplete' => 'address-level1',
				'priority'     => 80,
			),
			'postcode'   => array(
				'label'        => __( 'Postcode / ZIP', 'magical-products-display' ),
				'required'     => true,
				'class'        => array( 'form-row-wide', 'address-field' ),
				'validate'     => array( 'postcode' ),
				'autocomplete' => 'postal-code',
				'priority'     => 90,
			),
		);

		// Add billing-specific fields.
		if ( 'billing' === $address_type ) {
			$default_fields['phone'] = array(
				'label'        => __( 'Phone', 'magical-products-display' ),
				'required'     => true,
				'type'         => 'tel',
				'class'        => array( 'form-row-wide' ),
				'validate'     => array( 'phone' ),
				'autocomplete' => 'tel',
				'priority'     => 100,
			);
			$default_fields['email'] = array(
				'label'        => __( 'Email address', 'magical-products-display' ),
				'required'     => true,
				'type'         => 'email',
				'class'        => array( 'form-row-wide' ),
				'validate'     => array( 'email' ),
				'autocomplete' => 'email',
				'priority'     => 110,
			);
		}

		// Add prefix to field keys.
		$prefixed_fields = array();
		foreach ( $default_fields as $key => $field ) {
			$prefixed_fields[ $address_type . '_' . $key ] = $field;
		}

		// Try to get WooCommerce's address fields first.
		$wc_fields = WC()->countries->get_address_fields( $country, $address_type . '_' );
		if ( ! empty( $wc_fields ) ) {
			return $wc_fields;
		}

		// Sort by priority.
		uasort( $prefixed_fields, function( $a, $b ) {
			$a_priority = isset( $a['priority'] ) ? $a['priority'] : 50;
			$b_priority = isset( $b['priority'] ) ? $b['priority'] : 50;
			return $a_priority - $b_priority;
		} );

		return $prefixed_fields;
	}

	/**
	 * Render address card.
	 *
	 * @since 2.0.0
	 *
	 * @param string $type        Address type (billing/shipping).
	 * @param array  $settings    Widget settings.
	 * @param int    $customer_id Customer ID.
	 * @return void
	 */
	protected function render_address_card( $type, $settings, $customer_id ) {
		$title = 'billing' === $type ? $settings['billing_title'] : $settings['shipping_title'];
		$address = wc_get_account_formatted_address( $type );
		$edit_url = wc_get_endpoint_url( 'edit-address', $type );
		?>
		<div class="mpd-addresses__card mpd-addresses__card--<?php echo esc_attr( $type ); ?>">
			<div class="mpd-addresses__card-header">
				<h4 class="mpd-addresses__card-title"><?php echo esc_html( $title ); ?></h4>
				<a href="<?php echo esc_url( $edit_url ); ?>" class="mpd-addresses__edit-button">
					<?php echo esc_html( $settings['edit_button_text'] ); ?>
				</a>
			</div>
			<div class="mpd-addresses__card-body">
				<?php if ( $address ) : ?>
					<address class="mpd-addresses__content">
						<?php echo wp_kses_post( $address ); ?>
					</address>
				<?php else : ?>
					<p class="mpd-addresses__empty">
						<?php
						echo esc_html(
							sprintf(
								/* translators: %s: address type */
								__( 'You have not set up this %s address yet.', 'magical-products-display' ),
								'billing' === $type ? __( 'billing', 'magical-products-display' ) : __( 'shipping', 'magical-products-display' )
							)
						);
						?>
					</p>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
