<?php
/**
 * Customer Details Widget
 *
 * Displays billing and shipping address details.
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
 * Class Customer_Details
 *
 * Displays customer billing and shipping addresses.
 *
 * @since 2.1.0
 */
class Customer_Details extends Widget_Base {

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
	protected $widget_icon = 'eicon-user-circle-o';

	/**
	 * Get widget name.
	 *
	 * @since 2.1.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-customer-details';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.1.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Customer Details', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.1.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'customer', 'address', 'billing', 'shipping', 'details', 'woocommerce', 'thankyou', 'user' );
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
			'section_title',
			array(
				'label'       => __( 'Section Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Customer Details', 'magical-products-display' ),
				'placeholder' => __( 'Customer Details', 'magical-products-display' ),
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
				'default' => 'h3',
			)
		);

		$this->add_control(
			'heading_sections',
			array(
				'label'     => __( 'Display Sections', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_billing',
			array(
				'label'        => __( 'Billing Address', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_shipping',
			array(
				'label'        => __( 'Shipping Address', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Only shown for orders that need shipping.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_phone',
			array(
				'label'        => __( 'Phone Number', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_email',
			array(
				'label'        => __( 'Email Address', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'heading_labels',
			array(
				'label'     => __( 'Custom Labels', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'billing_title',
			array(
				'label'     => __( 'Billing Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Billing Address', 'magical-products-display' ),
				'condition' => array(
					'show_billing' => 'yes',
				),
			)
		);

		$this->add_control(
			'shipping_title',
			array(
				'label'     => __( 'Shipping Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Shipping Address', 'magical-products-display' ),
				'condition' => array(
					'show_shipping' => 'yes',
				),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'     => __( 'Layout', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'columns' => __( 'Two Columns', 'magical-products-display' ),
					'stacked' => __( 'Stacked', 'magical-products-display' ),
				),
				'default'   => 'columns',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'columns_gap',
			array(
				'label'      => __( 'Columns Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'    => array(
					'size' => 30,
					'unit' => 'px',
				),
				'condition'  => array(
					'layout' => 'columns',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-customer-details-columns' => 'gap: {{SIZE}}{{UNIT}};',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Google Maps, Edit Address & Copy Button', 'magical-products-display' ) );
		}
			$this->add_control(
				'show_map_link',
				array(
					'label'        => __( 'Google Maps Link', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Add "View on Map" link to addresses.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_edit_link',
				array(
					'label'        => __( 'Edit Address Link', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Show edit link for logged-in users.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_copy_button',
				array(
					'label'        => __( 'Copy Address Button', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Allow customers to copy address to clipboard.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_whatsapp_link',
				array(
					'label'        => __( 'WhatsApp Link', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Add WhatsApp contact link for phone numbers.', 'magical-products-display' ),
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
					'{{WRAPPER}} .mpd-customer-details' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .mpd-customer-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-customer-details',
			)
		);

		$this->add_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-customer-details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-customer-details',
			)
		);

		$this->end_controls_section();

		// Main Title Style.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => __( 'Section Title', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-customer-details-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-customer-details-title',
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
					'size' => 20,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-customer-details-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Address Box Style.
		$this->start_controls_section(
			'section_address_box_style',
			array(
				'label' => __( 'Address Box', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'address_box_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-customer-details-address' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'address_box_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => '20',
					'right'  => '20',
					'bottom' => '20',
					'left'   => '20',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-customer-details-address' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'address_box_border',
				'selector' => '{{WRAPPER}} .mpd-customer-details-address',
			)
		);

		$this->add_control(
			'address_box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'    => '8',
					'right'  => '8',
					'bottom' => '8',
					'left'   => '8',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-customer-details-address' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'address_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-customer-details-address',
			)
		);

		$this->end_controls_section();

		// Address Title Style.
		$this->start_controls_section(
			'section_address_title_style',
			array(
				'label' => __( 'Address Title', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'address_title_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-customer-details-address-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'address_title_typography',
				'selector' => '{{WRAPPER}} .mpd-customer-details-address-title',
			)
		);

		$this->add_responsive_control(
			'address_title_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-customer-details-address-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Address Content Style.
		$this->start_controls_section(
			'section_address_content_style',
			array(
				'label' => __( 'Address Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'address_text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-customer-details-address address' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'address_text_typography',
				'selector' => '{{WRAPPER}} .mpd-customer-details-address address',
			)
		);

		$this->add_responsive_control(
			'address_line_height',
			array(
				'label'      => __( 'Line Height', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'em', 'px' ),
				'range'      => array(
					'em' => array(
						'min'  => 1,
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 1.6,
					'unit' => 'em',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-customer-details-address address' => 'line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Contact Info Style.
		$this->start_controls_section(
			'section_contact_style',
			array(
				'label' => __( 'Contact Info', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'contact_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-customer-details-contact' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'contact_link_color',
			array(
				'label'     => __( 'Link Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-customer-details-contact a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'contact_link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-customer-details-contact a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'contact_typography',
				'selector' => '{{WRAPPER}} .mpd-customer-details-contact',
			)
		);

		$this->add_responsive_control(
			'contact_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-customer-details-contact p' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
		$order     = $this->get_current_order();
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		$layout_class = 'columns' === $settings['layout'] ? 'mpd-customer-details-columns' : 'mpd-customer-details-stacked';

		// Check if any pro features need styles/scripts.
		$has_pro_features = $this->is_pro() && (
			'yes' === ( $settings['show_map_link'] ?? '' ) ||
			'yes' === ( $settings['show_edit_link'] ?? '' ) ||
			'yes' === ( $settings['show_copy_button'] ?? '' ) ||
			'yes' === ( $settings['show_whatsapp_link'] ?? '' )
		);
		?>
		<div class="mpd-customer-details">
			<?php if ( ! empty( $settings['section_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mpd-customer-details-title">
					<?php echo esc_html( $settings['section_title'] ); ?>
				</<?php echo esc_attr( $settings['title_tag'] ); ?>>
			<?php endif; ?>

			<?php if ( $order || $is_editor ) : ?>
				<div class="<?php echo esc_attr( $layout_class ); ?>">
					<?php
					// Billing Address.
					if ( 'yes' === $settings['show_billing'] ) {
						$this->render_billing_address( $order, $settings, $is_editor );
					}

					// Shipping Address.
					if ( 'yes' === $settings['show_shipping'] ) {
						$needs_shipping = $order ? $order->needs_shipping_address() : true;
						if ( $needs_shipping || $is_editor ) {
							$this->render_shipping_address( $order, $settings, $is_editor );
						}
					}
					?>
				</div>

				<?php
				// Contact info.
				if ( 'yes' === $settings['show_phone'] || 'yes' === $settings['show_email'] ) {
					$this->render_contact_info( $order, $settings, $is_editor );
				}
				?>
			<?php else : ?>
				<div class="mpd-customer-details-empty">
					<p><?php esc_html_e( 'No order found.', 'magical-products-display' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
		<?php
		// Pro features styles and scripts.
		if ( $has_pro_features ) :
			?>
			<style>
			.mpd-address-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
			.mpd-edit-address-link { font-size: 13px; color: #0073aa; text-decoration: none; display: inline-flex; align-items: center; gap: 3px; }
			.mpd-edit-address-link:hover { color: #005177; }
			.mpd-address-actions { margin-top: 12px; display: flex; gap: 10px; flex-wrap: wrap; }
			.mpd-map-link, .mpd-copy-address-btn { font-size: 13px; color: #0073aa; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; background: none; border: none; cursor: pointer; padding: 0; }
			.mpd-map-link:hover, .mpd-copy-address-btn:hover { color: #005177; }
			.mpd-copy-address-btn.copied { color: #22c55e; }
			.mpd-whatsapp-link { margin-left: 8px; display: inline-flex; align-items: center; }
			.mpd-whatsapp-link svg { vertical-align: middle; }
			</style>
			<script>
			document.addEventListener('DOMContentLoaded', function() {
				document.querySelectorAll('.mpd-copy-address-btn').forEach(function(btn) {
					btn.addEventListener('click', function() {
						var address = this.getAttribute('data-address');
						if (navigator.clipboard) {
							navigator.clipboard.writeText(address).then(function() {
								btn.classList.add('copied');
								var originalText = btn.innerHTML;
								btn.innerHTML = '<span class="dashicons dashicons-yes"></span> <?php echo esc_js( __( 'Copied!', 'magical-products-display' ) ); ?>';
								setTimeout(function() {
									btn.classList.remove('copied');
									btn.innerHTML = originalText;
								}, 2000);
							});
						}
					});
				});
			});
			</script>
			<?php
		endif;
	}

	/**
	 * Render billing address.
	 *
	 * @since 2.1.0
	 *
	 * @param \WC_Order|false $order     Order object.
	 * @param array           $settings  Widget settings.
	 * @param bool            $is_editor Whether in editor mode.
	 *
	 * @return void
	 */
	private function render_billing_address( $order, $settings, $is_editor ) {
		// Pro features.
		$show_map_link    = $this->is_pro() && 'yes' === ( $settings['show_map_link'] ?? '' );
		$show_edit_link   = $this->is_pro() && 'yes' === ( $settings['show_edit_link'] ?? '' );
		$show_copy_button = $this->is_pro() && 'yes' === ( $settings['show_copy_button'] ?? '' );

		// Build address for map/copy.
		$address_text = '';
		if ( $order ) {
			$address_text = wp_strip_all_tags( str_replace( '<br/>', ', ', $order->get_formatted_billing_address() ) );
		} else {
			$address_text = '123 Main Street, Suite 100, New York, NY 10001, United States';
		}
		?>
		<div class="mpd-customer-details-address mpd-customer-details-billing">
			<div class="mpd-address-header">
				<h4 class="mpd-customer-details-address-title"><?php echo esc_html( $settings['billing_title'] ); ?></h4>
				<?php if ( $show_edit_link && is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing', wc_get_page_permalink( 'myaccount' ) ) ); ?>" class="mpd-edit-address-link">
						<span class="dashicons dashicons-edit"></span> <?php esc_html_e( 'Edit', 'magical-products-display' ); ?>
					</a>
				<?php elseif ( $show_edit_link && $is_editor ) : ?>
					<a href="#" class="mpd-edit-address-link">
						<span class="dashicons dashicons-edit"></span> <?php esc_html_e( 'Edit', 'magical-products-display' ); ?>
					</a>
				<?php endif; ?>
			</div>
			<address>
				<?php
				if ( $order ) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'magical-products-display' ) ) );
				} else {
					// Demo data for editor.
					echo wp_kses_post( implode( '<br>', array(
						esc_html__( 'John Doe', 'magical-products-display' ),
						esc_html__( '123 Main Street', 'magical-products-display' ),
						esc_html__( 'Suite 100', 'magical-products-display' ),
						esc_html__( 'New York, NY 10001', 'magical-products-display' ),
						esc_html__( 'United States', 'magical-products-display' ),
					) ) );
				}
				?>
			</address>
			<?php if ( $show_map_link || $show_copy_button ) : ?>
				<div class="mpd-address-actions">
					<?php if ( $show_map_link ) : ?>
						<a href="https://www.google.com/maps/search/?api=1&query=<?php echo rawurlencode( $address_text ); ?>" target="_blank" class="mpd-map-link">
							<span class="dashicons dashicons-location"></span> <?php esc_html_e( 'View on Map', 'magical-products-display' ); ?>
						</a>
					<?php endif; ?>
					<?php if ( $show_copy_button ) : ?>
						<button type="button" class="mpd-copy-address-btn" data-address="<?php echo esc_attr( $address_text ); ?>">
							<span class="dashicons dashicons-clipboard"></span> <?php esc_html_e( 'Copy', 'magical-products-display' ); ?>
						</button>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render shipping address.
	 *
	 * @since 2.1.0
	 *
	 * @param \WC_Order|false $order     Order object.
	 * @param array           $settings  Widget settings.
	 * @param bool            $is_editor Whether in editor mode.
	 *
	 * @return void
	 */
	private function render_shipping_address( $order, $settings, $is_editor ) {
		// Pro features.
		$show_map_link    = $this->is_pro() && 'yes' === ( $settings['show_map_link'] ?? '' );
		$show_edit_link   = $this->is_pro() && 'yes' === ( $settings['show_edit_link'] ?? '' );
		$show_copy_button = $this->is_pro() && 'yes' === ( $settings['show_copy_button'] ?? '' );

		// Build address for map/copy.
		$address_text = '';
		if ( $order ) {
			$shipping_address = $order->get_formatted_shipping_address();
			$address_text     = wp_strip_all_tags( str_replace( '<br/>', ', ', $shipping_address ) );
		} else {
			$address_text = '456 Oak Avenue, Apt 2B, Los Angeles, CA 90001, United States';
		}
		?>
		<div class="mpd-customer-details-address mpd-customer-details-shipping">
			<div class="mpd-address-header">
				<h4 class="mpd-customer-details-address-title"><?php echo esc_html( $settings['shipping_title'] ); ?></h4>
				<?php if ( $show_edit_link && is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping', wc_get_page_permalink( 'myaccount' ) ) ); ?>" class="mpd-edit-address-link">
						<span class="dashicons dashicons-edit"></span> <?php esc_html_e( 'Edit', 'magical-products-display' ); ?>
					</a>
				<?php elseif ( $show_edit_link && $is_editor ) : ?>
					<a href="#" class="mpd-edit-address-link">
						<span class="dashicons dashicons-edit"></span> <?php esc_html_e( 'Edit', 'magical-products-display' ); ?>
					</a>
				<?php endif; ?>
			</div>
			<address>
				<?php
				if ( $order ) {
					$shipping_address = $order->get_formatted_shipping_address();
					if ( $shipping_address ) {
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo wp_kses_post( $shipping_address );
					} else {
						esc_html_e( 'Same as billing address', 'magical-products-display' );
					}
				} else {
					// Demo data for editor.
					echo wp_kses_post( implode( '<br>', array(
						esc_html__( 'Jane Doe', 'magical-products-display' ),
						esc_html__( '456 Oak Avenue', 'magical-products-display' ),
						esc_html__( 'Apt 2B', 'magical-products-display' ),
						esc_html__( 'Los Angeles, CA 90001', 'magical-products-display' ),
						esc_html__( 'United States', 'magical-products-display' ),
					) ) );
				}
				?>
			</address>
			<?php if ( $show_map_link || $show_copy_button ) : ?>
				<div class="mpd-address-actions">
					<?php if ( $show_map_link ) : ?>
						<a href="https://www.google.com/maps/search/?api=1&query=<?php echo rawurlencode( $address_text ); ?>" target="_blank" class="mpd-map-link">
							<span class="dashicons dashicons-location"></span> <?php esc_html_e( 'View on Map', 'magical-products-display' ); ?>
						</a>
					<?php endif; ?>
					<?php if ( $show_copy_button ) : ?>
						<button type="button" class="mpd-copy-address-btn" data-address="<?php echo esc_attr( $address_text ); ?>">
							<span class="dashicons dashicons-clipboard"></span> <?php esc_html_e( 'Copy', 'magical-products-display' ); ?>
						</button>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render contact info.
	 *
	 * @since 2.1.0
	 *
	 * @param \WC_Order|false $order     Order object.
	 * @param array           $settings  Widget settings.
	 * @param bool            $is_editor Whether in editor mode.
	 *
	 * @return void
	 */
	private function render_contact_info( $order, $settings, $is_editor ) {
		$phone = $order ? $order->get_billing_phone() : '+1 (555) 123-4567';
		$email = $order ? $order->get_billing_email() : 'customer@example.com';

		// Pro feature: WhatsApp link.
		$show_whatsapp = $this->is_pro() && 'yes' === ( $settings['show_whatsapp_link'] ?? '' );
		$phone_clean   = preg_replace( '/[^0-9+]/', '', $phone );
		?>
		<div class="mpd-customer-details-contact">
			<?php if ( 'yes' === $settings['show_phone'] && $phone ) : ?>
				<p class="mpd-customer-details-phone">
					<strong><?php esc_html_e( 'Phone:', 'magical-products-display' ); ?></strong>
					<a href="tel:<?php echo esc_attr( $phone_clean ); ?>">
						<?php echo esc_html( $phone ); ?>
					</a>
					<?php if ( $show_whatsapp ) : ?>
						<a href="https://wa.me/<?php echo esc_attr( ltrim( $phone_clean, '+' ) ); ?>" target="_blank" class="mpd-whatsapp-link" title="<?php esc_attr_e( 'Contact via WhatsApp', 'magical-products-display' ); ?>">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
						</a>
					<?php endif; ?>
				</p>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_email'] && $email ) : ?>
				<p class="mpd-customer-details-email">
					<strong><?php esc_html_e( 'Email:', 'magical-products-display' ); ?></strong>
					<a href="mailto:<?php echo esc_attr( $email ); ?>">
						<?php echo esc_html( $email ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div>
		<?php
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
