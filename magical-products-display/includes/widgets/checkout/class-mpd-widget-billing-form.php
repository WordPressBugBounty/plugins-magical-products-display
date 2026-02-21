<?php
/**
 * Billing Form Widget
 *
 * Displays the checkout billing form with address fields.
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
 * Class Billing_Form
 *
 * @since 2.0.0
 */
class Billing_Form extends Widget_Base {

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
		return 'mpd-billing-form';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Billing Form', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'billing', 'form', 'checkout', 'address', 'woocommerce', 'payment' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'wc-checkout', 'wc-country-select', 'wc-address-i18n' );
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
			'section_title',
			array(
				'label'       => __( 'Section Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Billing Details', 'magical-products-display' ),
				'placeholder' => __( 'Enter section title', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => __( 'Show Section Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
				'default'   => 'h3',
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Field Customization Section (Pro).
		$this->start_controls_section(
			'section_fields',
			array(
				'label' => __( 'Field Customization', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		// Pro feature notice.
		$this->add_control(
			'field_customization_pro',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( __( 'Field customization is a Pro feature. Upgrade to show/hide and reorder billing fields.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		// Name Fields Section.
		$this->add_control(
			'name_fields_heading',
			array(
				'label'     => __( 'Name Fields', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'name_field_mode',
			array(
				'label'   => __( 'Name Field Mode', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'separate'  => __( 'Separate (First & Last Name)', 'magical-products-display' ),
					'full_name' => __( 'Combined (Full Name Only)', 'magical-products-display' ),
				),
				'default' => 'separate',
				'classes' => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'full_name_label',
			array(
				'label'       => __( 'Full Name Label', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Full Name', 'magical-products-display' ),
				'condition'   => array(
					'name_field_mode' => 'full_name',
				),
				'classes'     => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		// Company Field.
		$this->add_control(
			'company_field_heading',
			array(
				'label'     => __( 'Company Field', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_company_field',
			array(
				'label'        => __( 'Show Company Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'company_required',
			array(
				'label'        => __( 'Required', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'show_company_field' => 'yes',
				),
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		// Address Fields.
		$this->add_control(
			'address_fields_heading',
			array(
				'label'     => __( 'Address Fields', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_country_field',
			array(
				'label'        => __( 'Show Country Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_address_1_field',
			array(
				'label'        => __( 'Show Street Address', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_address_2_field',
			array(
				'label'        => __( 'Show Address Line 2', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_city_field',
			array(
				'label'        => __( 'Show City Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_state_field',
			array(
				'label'        => __( 'Show State/County Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_postcode_field',
			array(
				'label'        => __( 'Show Postcode/ZIP Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		// Contact Fields.
		$this->add_control(
			'contact_fields_heading',
			array(
				'label'     => __( 'Contact Fields', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_phone_field',
			array(
				'label'        => __( 'Show Phone Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'phone_required',
			array(
				'label'        => __( 'Phone Required', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_phone_field' => 'yes',
				),
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'show_email_field',
			array(
				'label'        => __( 'Show Email Field', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
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
			'field_layout',
			array(
				'label'   => __( 'Field Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default'   => __( 'Default (WooCommerce)', 'magical-products-display' ),
					'stacked'   => __( 'Stacked', 'magical-products-display' ),
					'inline'    => __( 'Inline', 'magical-products-display' ),
				),
				'default' => 'default',
				'prefix_class' => 'mpd-billing-layout-',
			)
		);

		$this->add_responsive_control(
			'fields_gap',
			array(
				'label'      => __( 'Fields Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
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
					'{{WRAPPER}} .woocommerce-billing-fields__field-wrapper .form-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
		// Title Style Section.
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
					'{{WRAPPER}} .mpd-billing-form__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-billing-form__title',
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-billing-form__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
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
					'{{WRAPPER}} .woocommerce-billing-fields label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .woocommerce-billing-fields label',
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
					'{{WRAPPER}} .woocommerce-billing-fields label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Input Fields Style Section.
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
					'{{WRAPPER}} .woocommerce-billing-fields input.input-text,
					 {{WRAPPER}} .woocommerce-billing-fields select,
					 {{WRAPPER}} .woocommerce-billing-fields textarea' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_placeholder_color',
			array(
				'label'     => __( 'Placeholder Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-billing-fields input.input-text::placeholder,
					 {{WRAPPER}} .woocommerce-billing-fields textarea::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .woocommerce-billing-fields input.input-text,
				              {{WRAPPER}} .woocommerce-billing-fields select,
				              {{WRAPPER}} .woocommerce-billing-fields textarea',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'input_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woocommerce-billing-fields input.input-text,
				              {{WRAPPER}} .woocommerce-billing-fields select,
				              {{WRAPPER}} .woocommerce-billing-fields textarea',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'input_border',
				'selector' => '{{WRAPPER}} .woocommerce-billing-fields input.input-text,
				              {{WRAPPER}} .woocommerce-billing-fields select,
				              {{WRAPPER}} .woocommerce-billing-fields textarea',
			)
		);

		$this->add_responsive_control(
			'input_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-billing-fields input.input-text,
					 {{WRAPPER}} .woocommerce-billing-fields select,
					 {{WRAPPER}} .woocommerce-billing-fields textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .woocommerce-billing-fields input.input-text,
					 {{WRAPPER}} .woocommerce-billing-fields select,
					 {{WRAPPER}} .woocommerce-billing-fields textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .woocommerce-billing-fields input.input-text:focus,
					 {{WRAPPER}} .woocommerce-billing-fields select:focus,
					 {{WRAPPER}} .woocommerce-billing-fields textarea:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'input_focus_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-billing-fields input.input-text:focus,
				              {{WRAPPER}} .woocommerce-billing-fields select:focus,
				              {{WRAPPER}} .woocommerce-billing-fields textarea:focus',
			)
		);

		$this->end_controls_section();

		// Form Container Style.
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
				'selector' => '{{WRAPPER}} .mpd-billing-form',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-billing-form',
			)
		);

		$this->add_responsive_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-billing-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-billing-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-billing-form',
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

		// Store settings globally for the checkout fields filter.
		// This ensures field visibility works both during render and checkout validation.
		if ( function_exists( 'mpd_store_billing_form_settings' ) ) {
			mpd_store_billing_form_settings( $settings );
		}

		// Layout class is now handled via prefix_class
		?>
		<div class="mpd-billing-form">
			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['section_title'] ) ) : ?>
				<<?php echo esc_html( $settings['title_tag'] ); ?> class="mpd-billing-form__title">
					<?php echo esc_html( $settings['section_title'] ); ?>
				</<?php echo esc_html( $settings['title_tag'] ); ?>>
			<?php endif; ?>

			<div class="woocommerce-billing-fields">
				<?php
				// Apply our filter to get the correct fields.
				$all_fields = apply_filters( 'woocommerce_checkout_fields', WC()->checkout()->checkout_fields );
				$fields = isset( $all_fields['billing'] ) ? $all_fields['billing'] : array();
				
				if ( ! empty( $fields ) ) :
					$checkout = WC()->checkout();
					?>
					<div class="woocommerce-billing-fields__field-wrapper">
						<?php
						foreach ( $fields as $key => $field ) {
							woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
						}
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Customize billing fields for Pro users.
	 *
	 * @since 2.0.0
	 *
	 * @param array $fields Checkout fields.
	 * @return array Modified checkout fields.
	 */
	public function customize_billing_fields( $fields ) {
		$settings = $this->get_settings_for_display();

		// Handle name field mode (Full Name vs First/Last).
		if ( 'full_name' === $settings['name_field_mode'] ) {
			// Remove last name field.
			if ( isset( $fields['billing']['billing_last_name'] ) ) {
				unset( $fields['billing']['billing_last_name'] );
			}
			// Modify first name to be full name.
			if ( isset( $fields['billing']['billing_first_name'] ) ) {
				$fields['billing']['billing_first_name']['label'] = ! empty( $settings['full_name_label'] ) 
					? $settings['full_name_label'] 
					: __( 'Full Name', 'magical-products-display' );
				$fields['billing']['billing_first_name']['class'] = array( 'form-row-wide' );
			}
		}

		// Hide company field if disabled.
		if ( 'yes' !== $settings['show_company_field'] ) {
			if ( isset( $fields['billing']['billing_company'] ) ) {
				unset( $fields['billing']['billing_company'] );
			}
		} else {
			// Set required status for company.
			if ( isset( $fields['billing']['billing_company'] ) ) {
				$fields['billing']['billing_company']['required'] = ( 'yes' === $settings['company_required'] );
			}
		}

		// Hide country field if disabled.
		if ( 'yes' !== $settings['show_country_field'] && isset( $fields['billing']['billing_country'] ) ) {
			unset( $fields['billing']['billing_country'] );
		}

		// Hide address line 1 if disabled.
		if ( 'yes' !== $settings['show_address_1_field'] && isset( $fields['billing']['billing_address_1'] ) ) {
			unset( $fields['billing']['billing_address_1'] );
		}

		// Show/hide address line 2.
		if ( 'yes' !== $settings['show_address_2_field'] && isset( $fields['billing']['billing_address_2'] ) ) {
			unset( $fields['billing']['billing_address_2'] );
		}

		// Hide city field if disabled.
		if ( 'yes' !== $settings['show_city_field'] && isset( $fields['billing']['billing_city'] ) ) {
			unset( $fields['billing']['billing_city'] );
		}

		// Hide state field if disabled.
		if ( 'yes' !== $settings['show_state_field'] && isset( $fields['billing']['billing_state'] ) ) {
			unset( $fields['billing']['billing_state'] );
		}

		// Hide postcode field if disabled.
		if ( 'yes' !== $settings['show_postcode_field'] && isset( $fields['billing']['billing_postcode'] ) ) {
			unset( $fields['billing']['billing_postcode'] );
		}

		// Handle phone field.
		if ( 'yes' !== $settings['show_phone_field'] ) {
			if ( isset( $fields['billing']['billing_phone'] ) ) {
				unset( $fields['billing']['billing_phone'] );
			}
		} else {
			// Set required status for phone.
			if ( isset( $fields['billing']['billing_phone'] ) ) {
				$fields['billing']['billing_phone']['required'] = ( 'yes' === $settings['phone_required'] );
			}
		}

		// Hide email field if disabled.
		if ( 'yes' !== $settings['show_email_field'] && isset( $fields['billing']['billing_email'] ) ) {
			unset( $fields['billing']['billing_email'] );
		}

		return $fields;
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
		var isFullName = settings.name_field_mode === 'full_name';
		var fullNameLabel = settings.full_name_label || '<?php esc_html_e( 'Full Name', 'magical-products-display' ); ?>';
		#>
		<div class="mpd-billing-form">
			<# if ( 'yes' === settings.show_title && settings.section_title ) { #>
				<{{{ settings.title_tag }}} class="mpd-billing-form__title">
					{{{ settings.section_title }}}
				</{{{ settings.title_tag }}}>
			<# } #>
			
			<div class="woocommerce-billing-fields">
				<div class="woocommerce-billing-fields__field-wrapper">
					<# if ( isFullName ) { #>
						<p class="form-row form-row-wide">
							<label for="billing_first_name">{{{ fullNameLabel }}} <span class="required">*</span></label>
							<input type="text" class="input-text" name="billing_first_name" id="billing_first_name" placeholder="">
						</p>
					<# } else { #>
						<p class="form-row form-row-first">
							<label for="billing_first_name"><?php esc_html_e( 'First name', 'magical-products-display' ); ?> <span class="required">*</span></label>
							<input type="text" class="input-text" name="billing_first_name" id="billing_first_name" placeholder="">
						</p>
						<p class="form-row form-row-last">
							<label for="billing_last_name"><?php esc_html_e( 'Last name', 'magical-products-display' ); ?> <span class="required">*</span></label>
							<input type="text" class="input-text" name="billing_last_name" id="billing_last_name" placeholder="">
						</p>
					<# } #>

					<# if ( 'yes' === settings.show_company_field ) { #>
						<p class="form-row form-row-wide">
							<label for="billing_company"><?php esc_html_e( 'Company name', 'magical-products-display' ); ?> <# if ( 'yes' === settings.company_required ) { #><span class="required">*</span><# } else { #><span class="optional">(<?php esc_html_e( 'optional', 'magical-products-display' ); ?>)</span><# } #></label>
							<input type="text" class="input-text" name="billing_company" id="billing_company" placeholder="">
						</p>
					<# } #>

					<# if ( 'yes' === settings.show_country_field ) { #>
						<p class="form-row form-row-wide">
							<label for="billing_country"><?php esc_html_e( 'Country / Region', 'magical-products-display' ); ?> <span class="required">*</span></label>
							<select name="billing_country" id="billing_country" class="country_select">
								<option value=""><?php esc_html_e( 'Select a country / region…', 'magical-products-display' ); ?></option>
							</select>
						</p>
					<# } #>

					<# if ( 'yes' === settings.show_address_1_field ) { #>
						<p class="form-row form-row-wide">
							<label for="billing_address_1"><?php esc_html_e( 'Street address', 'magical-products-display' ); ?> <span class="required">*</span></label>
							<input type="text" class="input-text" name="billing_address_1" id="billing_address_1" placeholder="<?php esc_attr_e( 'House number and street name', 'magical-products-display' ); ?>">
						</p>
					<# } #>

					<# if ( 'yes' === settings.show_address_2_field ) { #>
						<p class="form-row form-row-wide">
							<label for="billing_address_2"><?php esc_html_e( 'Apartment, suite, unit, etc.', 'magical-products-display' ); ?> <span class="optional">(<?php esc_html_e( 'optional', 'magical-products-display' ); ?>)</span></label>
							<input type="text" class="input-text" name="billing_address_2" id="billing_address_2" placeholder="">
						</p>
					<# } #>

					<# if ( 'yes' === settings.show_city_field ) { #>
						<p class="form-row form-row-wide">
							<label for="billing_city"><?php esc_html_e( 'Town / City', 'magical-products-display' ); ?> <span class="required">*</span></label>
							<input type="text" class="input-text" name="billing_city" id="billing_city" placeholder="">
						</p>
					<# } #>

					<# if ( 'yes' === settings.show_state_field ) { #>
						<p class="form-row form-row-wide">
							<label for="billing_state"><?php esc_html_e( 'State / County', 'magical-products-display' ); ?></label>
							<input type="text" class="input-text" name="billing_state" id="billing_state" placeholder="">
						</p>
					<# } #>

					<# if ( 'yes' === settings.show_postcode_field && 'yes' === settings.show_phone_field ) { #>
						<p class="form-row form-row-first">
							<label for="billing_postcode"><?php esc_html_e( 'Postcode / ZIP', 'magical-products-display' ); ?> <span class="required">*</span></label>
							<input type="text" class="input-text" name="billing_postcode" id="billing_postcode" placeholder="">
						</p>
						<p class="form-row form-row-last">
							<label for="billing_phone"><?php esc_html_e( 'Phone', 'magical-products-display' ); ?> <# if ( 'yes' === settings.phone_required ) { #><span class="required">*</span><# } else { #><span class="optional">(<?php esc_html_e( 'optional', 'magical-products-display' ); ?>)</span><# } #></label>
							<input type="tel" class="input-text" name="billing_phone" id="billing_phone" placeholder="">
						</p>
					<# } else { #>
						<# if ( 'yes' === settings.show_postcode_field ) { #>
							<p class="form-row form-row-wide">
								<label for="billing_postcode"><?php esc_html_e( 'Postcode / ZIP', 'magical-products-display' ); ?> <span class="required">*</span></label>
								<input type="text" class="input-text" name="billing_postcode" id="billing_postcode" placeholder="">
							</p>
						<# } #>
						<# if ( 'yes' === settings.show_phone_field ) { #>
							<p class="form-row form-row-wide">
								<label for="billing_phone"><?php esc_html_e( 'Phone', 'magical-products-display' ); ?> <# if ( 'yes' === settings.phone_required ) { #><span class="required">*</span><# } else { #><span class="optional">(<?php esc_html_e( 'optional', 'magical-products-display' ); ?>)</span><# } #></label>
								<input type="tel" class="input-text" name="billing_phone" id="billing_phone" placeholder="">
							</p>
						<# } #>
					<# } #>

					<# if ( 'yes' === settings.show_email_field ) { #>
						<p class="form-row form-row-wide">
							<label for="billing_email"><?php esc_html_e( 'Email address', 'magical-products-display' ); ?> <span class="required">*</span></label>
							<input type="email" class="input-text" name="billing_email" id="billing_email" placeholder="">
						</p>
					<# } #>
				</div>
			</div>
		</div>
		<?php
	}
}
