<?php
/**
 * Order Notes Widget
 *
 * Displays the checkout order notes textarea.
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
 * Class Order_Notes
 *
 * @since 2.0.0
 */
class Order_Notes extends Widget_Base {

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
	protected $widget_icon = 'eicon-document-file';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-order-notes';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Order Notes', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'order', 'notes', 'checkout', 'comments', 'woocommerce', 'textarea', 'message' );
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
				'default'     => __( 'Additional Information', 'magical-products-display' ),
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
			'label_text',
			array(
				'label'       => __( 'Label Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Order notes', 'magical-products-display' ),
				'placeholder' => __( 'Enter label text', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_label',
			array(
				'label'        => __( 'Show Label', 'magical-products-display' ),
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
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Notes about your order, e.g. special notes for delivery.', 'magical-products-display' ),
				'placeholder' => __( 'Enter placeholder text', 'magical-products-display' ),
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
			'custom_fields_pro',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( __( 'Custom additional fields (like gift wrapping, delivery date picker) is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'enable_gift_wrapping',
			array(
				'label'        => __( 'Enable Gift Wrapping', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->end_controls_section();

		// Textarea Settings.
		$this->start_controls_section(
			'section_textarea',
			array(
				'label' => __( 'Textarea Settings', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'textarea_rows',
			array(
				'label'   => __( 'Rows', 'magical-products-display' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 2,
				'max'     => 15,
			)
		);

		$this->add_control(
			'textarea_resize',
			array(
				'label'   => __( 'Allow Resize', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'both'       => __( 'Both', 'magical-products-display' ),
					'horizontal' => __( 'Horizontal', 'magical-products-display' ),
					'vertical'   => __( 'Vertical', 'magical-products-display' ),
					'none'       => __( 'None', 'magical-products-display' ),
				),
				'default' => 'vertical',
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-notes__textarea' => 'resize: {{VALUE}};',
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
					'{{WRAPPER}} .mpd-order-notes__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-order-notes__title',
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
					'{{WRAPPER}} .mpd-order-notes__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Label Style Section.
		$this->start_controls_section(
			'section_label_style',
			array(
				'label'     => __( 'Label', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_label' => 'yes',
				),
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-notes__label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .mpd-order-notes__label',
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
					'{{WRAPPER}} .mpd-order-notes__label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Textarea Style Section.
		$this->start_controls_section(
			'section_textarea_style',
			array(
				'label' => __( 'Textarea', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'textarea_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-notes__textarea' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'textarea_placeholder_color',
			array(
				'label'     => __( 'Placeholder Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-notes__textarea::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'textarea_typography',
				'selector' => '{{WRAPPER}} .mpd-order-notes__textarea',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'textarea_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-order-notes__textarea',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'textarea_border',
				'selector' => '{{WRAPPER}} .mpd-order-notes__textarea',
			)
		);

		$this->add_responsive_control(
			'textarea_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-notes__textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'textarea_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-notes__textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'textarea_focus_heading',
			array(
				'label'     => __( 'Focus State', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'textarea_focus_border_color',
			array(
				'label'     => __( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-notes__textarea:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'textarea_focus_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-order-notes__textarea:focus',
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
				'selector' => '{{WRAPPER}} .mpd-order-notes',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-order-notes',
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-notes' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-order-notes',
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

		$rows = isset( $settings['textarea_rows'] ) ? absint( $settings['textarea_rows'] ) : 4;
		?>
		<div class="mpd-order-notes woocommerce-additional-fields">
			<?php if ( 'yes' === $settings['show_title'] && ! empty( $settings['section_title'] ) ) : ?>
				<<?php echo esc_html( $settings['title_tag'] ); ?> class="mpd-order-notes__title">
					<?php echo esc_html( $settings['section_title'] ); ?>
				</<?php echo esc_html( $settings['title_tag'] ); ?>>
			<?php endif; ?>

			<div class="woocommerce-additional-fields__field-wrapper">
				<p class="form-row notes" id="order_comments_field">
					<?php if ( 'yes' === $settings['show_label'] && ! empty( $settings['label_text'] ) ) : ?>
						<label for="order_comments" class="mpd-order-notes__label">
							<?php echo esc_html( $settings['label_text'] ); ?>
							<span class="optional">(<?php esc_html_e( 'optional', 'magical-products-display' ); ?>)</span>
						</label>
					<?php endif; ?>
					<span class="woocommerce-input-wrapper">
						<textarea name="order_comments" class="input-text mpd-order-notes__textarea" id="order_comments" placeholder="<?php echo esc_attr( $settings['placeholder_text'] ); ?>" rows="<?php echo esc_attr( $rows ); ?>"></textarea>
					</span>
				</p>

				<?php
				// Pro: Gift wrapping option.
				if ( $this->is_pro() && 'yes' === $settings['enable_gift_wrapping'] ) {
					?>
					<p class="form-row gift-wrapping">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
							<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="gift_wrapping" value="1" />
							<span><?php esc_html_e( 'This is a gift - add gift wrapping', 'magical-products-display' ); ?></span>
						</label>
					</p>
					<?php
				}

				do_action( 'woocommerce_after_order_notes', WC()->checkout() );
				?>
			</div>
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
		var rows = settings.textarea_rows || 4;
		#>
		<div class="mpd-order-notes woocommerce-additional-fields">
			<# if ( 'yes' === settings.show_title && settings.section_title ) { #>
				<{{{ settings.title_tag }}} class="mpd-order-notes__title">
					{{{ settings.section_title }}}
				</{{{ settings.title_tag }}}>
			<# } #>

			<div class="woocommerce-additional-fields__field-wrapper">
				<p class="form-row notes" id="order_comments_field">
					<# if ( 'yes' === settings.show_label && settings.label_text ) { #>
						<label for="order_comments" class="mpd-order-notes__label">
							{{{ settings.label_text }}}
							<span class="optional">(<?php esc_html_e( 'optional', 'magical-products-display' ); ?>)</span>
						</label>
					<# } #>
					<span class="woocommerce-input-wrapper">
						<textarea name="order_comments" class="input-text mpd-order-notes__textarea" id="order_comments" placeholder="{{ settings.placeholder_text }}" rows="{{ rows }}"></textarea>
					</span>
				</p>
			</div>
		</div>
		<?php
	}
}
