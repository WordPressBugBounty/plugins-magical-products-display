<?php
/**
 * Shipping Form Widget
 *
 * Displays the checkout shipping form with address fields.
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
 * Class Shipping_Form
 *
 * @since 2.0.0
 */
class Shipping_Form extends Widget_Base {

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
	protected $widget_icon = 'eicon-map-pin';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-shipping-form';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Shipping Form', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'shipping', 'form', 'checkout', 'address', 'woocommerce', 'delivery' );
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
				'default'     => __( 'Ship to a different address?', 'magical-products-display' ),
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

		$this->add_control(
			'default_expanded',
			array(
				'label'        => __( 'Default Expanded', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'Show shipping fields by default.', 'magical-products-display' ),
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
				'raw'             => $this->get_pro_notice( __( 'Field customization is a Pro feature. Upgrade to show/hide and customize shipping fields.', 'magical-products-display' ) ),
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

		$this->end_controls_section();

		// Conditional Fields Section (Pro).
		$this->start_controls_section(
			'section_conditional',
			array(
				'label' => __( 'Conditional Fields', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		// Pro feature notice.
		$this->add_control(
			'conditional_fields_pro',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( __( 'Conditional field display is a Pro feature. Upgrade to show/hide fields based on shipping method or other conditions.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'hide_if_virtual',
			array(
				'label'        => __( 'Hide for Virtual Products', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Hide shipping form when cart only contains virtual products.', 'magical-products-display' ),
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
				'prefix_class' => 'mpd-shipping-layout-',
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
					'{{WRAPPER}} .woocommerce-shipping-fields__field-wrapper .form-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
		// Checkbox Style Section.
		$this->start_controls_section(
			'section_checkbox_style',
			array(
				'label' => __( 'Toggle Checkbox', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'checkbox_color',
			array(
				'label'     => __( 'Checkbox Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #ship-to-different-address-checkbox:checked' => 'accent-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'checkbox_size',
			array(
				'label'      => __( 'Checkbox Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} #ship-to-different-address-checkbox' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'checkbox_label_color',
			array(
				'label'     => __( 'Label Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #ship-to-different-address' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'checkbox_label_typography',
				'selector' => '{{WRAPPER}} #ship-to-different-address',
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
					'{{WRAPPER}} .woocommerce-shipping-fields label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .woocommerce-shipping-fields label',
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
					'{{WRAPPER}} .woocommerce-shipping-fields input.input-text,
					 {{WRAPPER}} .woocommerce-shipping-fields select,
					 {{WRAPPER}} .woocommerce-shipping-fields textarea' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'input_placeholder_color',
			array(
				'label'     => __( 'Placeholder Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-shipping-fields input.input-text::placeholder,
					 {{WRAPPER}} .woocommerce-shipping-fields textarea::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .woocommerce-shipping-fields input.input-text,
				              {{WRAPPER}} .woocommerce-shipping-fields select,
				              {{WRAPPER}} .woocommerce-shipping-fields textarea',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'input_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .woocommerce-shipping-fields input.input-text,
				              {{WRAPPER}} .woocommerce-shipping-fields select,
				              {{WRAPPER}} .woocommerce-shipping-fields textarea',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'input_border',
				'selector' => '{{WRAPPER}} .woocommerce-shipping-fields input.input-text,
				              {{WRAPPER}} .woocommerce-shipping-fields select,
				              {{WRAPPER}} .woocommerce-shipping-fields textarea',
			)
		);

		$this->add_responsive_control(
			'input_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-shipping-fields input.input-text,
					 {{WRAPPER}} .woocommerce-shipping-fields select,
					 {{WRAPPER}} .woocommerce-shipping-fields textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .woocommerce-shipping-fields input.input-text,
					 {{WRAPPER}} .woocommerce-shipping-fields select,
					 {{WRAPPER}} .woocommerce-shipping-fields textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .mpd-shipping-form',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-shipping-form',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-shipping-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-shipping-form',
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

		// Check if shipping is enabled.
		if ( ! WC()->cart || ! WC()->cart->needs_shipping() ) {
			// For Pro: Hide if virtual only.
			if ( $this->is_pro() && 'yes' === $settings['hide_if_virtual'] ) {
				return;
			}
		}

		// Store settings globally for the checkout fields filter.
		// This ensures field visibility works both during render and checkout validation.
		if ( function_exists( 'mpd_store_shipping_form_settings' ) ) {
			mpd_store_shipping_form_settings( $settings );
		}

		// Layout class is now handled via prefix_class
		$default_checked = 'yes' === $settings['default_expanded'];
		?>
		<div class="mpd-shipping-form">
			<div class="woocommerce-shipping-fields">
				<?php if ( 'yes' === $settings['show_title'] ) : ?>
					<h3 id="ship-to-different-address">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
							<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" 
								<?php checked( $default_checked ); ?> type="checkbox" name="ship_to_different_address" value="1" />
							<span><?php echo esc_html( $settings['section_title'] ); ?></span>
						</label>
					</h3>
				<?php endif; ?>

				<div class="shipping_address" <?php echo ! $default_checked ? 'style="display: none;"' : ''; ?>>
					<?php
					// Apply our filter to get the correct fields.
					$all_fields = apply_filters( 'woocommerce_checkout_fields', WC()->checkout()->checkout_fields );
					$fields = isset( $all_fields['shipping'] ) ? $all_fields['shipping'] : array();
					
					if ( ! empty( $fields ) ) :
						$checkout = WC()->checkout();
						?>
						<div class="woocommerce-shipping-fields__field-wrapper">
							<?php
							foreach ( $fields as $key => $field ) {
								woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
							}
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		(function() {
			// Handle shipping address toggle
			var checkbox = document.getElementById('ship-to-different-address-checkbox');
			if (checkbox) {
				var shippingAddress = checkbox.closest('.woocommerce-shipping-fields').querySelector('.shipping_address');
				if (shippingAddress) {
					// Set initial state
					shippingAddress.style.display = checkbox.checked ? '' : 'none';
					
					// Handle checkbox change
					checkbox.addEventListener('change', function() {
						if (this.checked) {
							shippingAddress.style.display = '';
							// Trigger WooCommerce update if available
							if (typeof jQuery !== 'undefined') {
								jQuery(document.body).trigger('country_to_state_changed');
							}
						} else {
							shippingAddress.style.display = 'none';
						}
					});
				}
			}
		})();
		</script>
		<?php
	}

	/**
	 * Customize shipping fields for Pro users.
	 *
	 * @since 2.0.0
	 *
	 * @param array $fields Checkout fields.
	 * @return array Modified checkout fields.
	 */
	public function customize_shipping_fields( $fields ) {
		$settings = $this->get_settings_for_display();

		// Handle name field mode (Full Name vs First/Last).
		if ( 'full_name' === $settings['name_field_mode'] ) {
			// Remove last name field.
			if ( isset( $fields['shipping']['shipping_last_name'] ) ) {
				unset( $fields['shipping']['shipping_last_name'] );
			}
			// Modify first name to be full name.
			if ( isset( $fields['shipping']['shipping_first_name'] ) ) {
				$fields['shipping']['shipping_first_name']['label'] = ! empty( $settings['full_name_label'] ) 
					? $settings['full_name_label'] 
					: __( 'Full Name', 'magical-products-display' );
				$fields['shipping']['shipping_first_name']['class'] = array( 'form-row-wide' );
			}
		}

		// Hide company field if disabled.
		if ( 'yes' !== $settings['show_company_field'] && isset( $fields['shipping']['shipping_company'] ) ) {
			unset( $fields['shipping']['shipping_company'] );
		}

		// Hide country field if disabled.
		if ( 'yes' !== $settings['show_country_field'] && isset( $fields['shipping']['shipping_country'] ) ) {
			unset( $fields['shipping']['shipping_country'] );
		}

		// Hide address line 1 if disabled.
		if ( 'yes' !== $settings['show_address_1_field'] && isset( $fields['shipping']['shipping_address_1'] ) ) {
			unset( $fields['shipping']['shipping_address_1'] );
		}

		// Show/hide address line 2.
		if ( 'yes' !== $settings['show_address_2_field'] && isset( $fields['shipping']['shipping_address_2'] ) ) {
			unset( $fields['shipping']['shipping_address_2'] );
		}

		// Hide city field if disabled.
		if ( 'yes' !== $settings['show_city_field'] && isset( $fields['shipping']['shipping_city'] ) ) {
			unset( $fields['shipping']['shipping_city'] );
		}

		// Hide state field if disabled.
		if ( 'yes' !== $settings['show_state_field'] && isset( $fields['shipping']['shipping_state'] ) ) {
			unset( $fields['shipping']['shipping_state'] );
		}

		// Hide postcode field if disabled.
		if ( 'yes' !== $settings['show_postcode_field'] && isset( $fields['shipping']['shipping_postcode'] ) ) {
			unset( $fields['shipping']['shipping_postcode'] );
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
		<div class="mpd-shipping-form">
			<div class="woocommerce-shipping-fields">
				<# if ( 'yes' === settings.show_title ) { #>
					<h3 id="ship-to-different-address">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
							<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" 
								<# if ( 'yes' === settings.default_expanded ) { #>checked<# } #> type="checkbox" name="ship_to_different_address" value="1" />
							<span>{{{ settings.section_title }}}</span>
						</label>
					</h3>
				<# } #>
				
				<div class="shipping_address" <# if ( 'yes' !== settings.default_expanded ) { #>style="display: none;"<# } #>>
					<div class="woocommerce-shipping-fields__field-wrapper">
						<# if ( isFullName ) { #>
							<p class="form-row form-row-wide">
								<label for="shipping_first_name">{{{ fullNameLabel }}} <span class="required">*</span></label>
								<input type="text" class="input-text" name="shipping_first_name" id="shipping_first_name">
							</p>
						<# } else { #>
							<p class="form-row form-row-first">
								<label for="shipping_first_name"><?php esc_html_e( 'First name', 'magical-products-display' ); ?> <span class="required">*</span></label>
								<input type="text" class="input-text" name="shipping_first_name" id="shipping_first_name">
							</p>
							<p class="form-row form-row-last">
								<label for="shipping_last_name"><?php esc_html_e( 'Last name', 'magical-products-display' ); ?> <span class="required">*</span></label>
								<input type="text" class="input-text" name="shipping_last_name" id="shipping_last_name">
							</p>
						<# } #>

						<# if ( 'yes' === settings.show_company_field ) { #>
							<p class="form-row form-row-wide">
								<label for="shipping_company"><?php esc_html_e( 'Company name', 'magical-products-display' ); ?> <span class="optional">(<?php esc_html_e( 'optional', 'magical-products-display' ); ?>)</span></label>
								<input type="text" class="input-text" name="shipping_company" id="shipping_company">
							</p>
						<# } #>

						<# if ( 'yes' === settings.show_country_field ) { #>
							<p class="form-row form-row-wide">
								<label for="shipping_country"><?php esc_html_e( 'Country / Region', 'magical-products-display' ); ?> <span class="required">*</span></label>
								<select name="shipping_country" id="shipping_country" class="country_select">
									<option value=""><?php esc_html_e( 'Select a country / region…', 'magical-products-display' ); ?></option>
								</select>
							</p>
						<# } #>

						<# if ( 'yes' === settings.show_address_1_field ) { #>
							<p class="form-row form-row-wide">
								<label for="shipping_address_1"><?php esc_html_e( 'Street address', 'magical-products-display' ); ?> <span class="required">*</span></label>
								<input type="text" class="input-text" name="shipping_address_1" id="shipping_address_1" placeholder="<?php esc_attr_e( 'House number and street name', 'magical-products-display' ); ?>">
							</p>
						<# } #>

						<# if ( 'yes' === settings.show_address_2_field ) { #>
							<p class="form-row form-row-wide">
								<label for="shipping_address_2"><?php esc_html_e( 'Apartment, suite, unit, etc.', 'magical-products-display' ); ?> <span class="optional">(<?php esc_html_e( 'optional', 'magical-products-display' ); ?>)</span></label>
								<input type="text" class="input-text" name="shipping_address_2" id="shipping_address_2">
							</p>
						<# } #>

						<# if ( 'yes' === settings.show_city_field ) { #>
							<p class="form-row form-row-wide">
								<label for="shipping_city"><?php esc_html_e( 'Town / City', 'magical-products-display' ); ?> <span class="required">*</span></label>
								<input type="text" class="input-text" name="shipping_city" id="shipping_city">
							</p>
						<# } #>

						<# if ( 'yes' === settings.show_state_field ) { #>
							<p class="form-row form-row-wide">
								<label for="shipping_state"><?php esc_html_e( 'State / County', 'magical-products-display' ); ?></label>
								<input type="text" class="input-text" name="shipping_state" id="shipping_state">
							</p>
						<# } #>

						<# if ( 'yes' === settings.show_postcode_field ) { #>
							<p class="form-row form-row-wide">
								<label for="shipping_postcode"><?php esc_html_e( 'Postcode / ZIP', 'magical-products-display' ); ?> <span class="required">*</span></label>
								<input type="text" class="input-text" name="shipping_postcode" id="shipping_postcode">
							</p>
						<# } #>
					</div>
				</div>
			</div>
		</div>
		<#
		// Add inline script for checkbox toggle in editor
		view.addRenderAttribute( 'wrapper', 'data-shipping-toggle', 'true' );
		#>
		<script type="text/javascript">
		(function() {
			setTimeout(function() {
				var checkbox = document.getElementById('ship-to-different-address-checkbox');
				if (checkbox && !checkbox.dataset.mpdBound) {
					checkbox.dataset.mpdBound = 'true';
					var shippingAddress = checkbox.closest('.woocommerce-shipping-fields').querySelector('.shipping_address');
					if (shippingAddress) {
						checkbox.addEventListener('change', function() {
							shippingAddress.style.display = this.checked ? '' : 'none';
						});
					}
				}
			}, 100);
		})();
		</script>
		<?php
	}
}
