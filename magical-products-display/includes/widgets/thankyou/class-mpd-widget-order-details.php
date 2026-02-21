<?php
/**
 * Order Details Widget
 *
 * Displays order details like order number, date, total, payment method.
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
 * Class Order_Details
 *
 * Displays order details including order number, date, total, and payment method.
 *
 * @since 2.1.0
 */
class Order_Details extends Widget_Base {

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
	protected $widget_icon = 'eicon-info-circle';

	/**
	 * Get widget name.
	 *
	 * @since 2.1.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-order-details';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.1.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Order Details', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.1.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'order', 'details', 'info', 'summary', 'woocommerce', 'thankyou', 'date', 'total', 'payment' );
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
				'default'     => __( 'Order Details', 'magical-products-display' ),
				'placeholder' => __( 'Order Details', 'magical-products-display' ),
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
			'heading_visibility',
			array(
				'label'     => __( 'Show/Hide Details', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'show_order_number',
			array(
				'label'        => __( 'Order Number', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label'        => __( 'Order Date', 'magical-products-display' ),
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
				'label'        => __( 'Email', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_total',
			array(
				'label'        => __( 'Order Total', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_payment_method',
			array(
				'label'        => __( 'Payment Method', 'magical-products-display' ),
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
			'label_order_number',
			array(
				'label'       => __( 'Order Number Label', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Order number:', 'magical-products-display' ),
				'condition'   => array(
					'show_order_number' => 'yes',
				),
			)
		);

		$this->add_control(
			'label_date',
			array(
				'label'     => __( 'Date Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Date:', 'magical-products-display' ),
				'condition' => array(
					'show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'label_email',
			array(
				'label'     => __( 'Email Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Email:', 'magical-products-display' ),
				'condition' => array(
					'show_email' => 'yes',
				),
			)
		);

		$this->add_control(
			'label_total',
			array(
				'label'     => __( 'Total Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Total:', 'magical-products-display' ),
				'condition' => array(
					'show_total' => 'yes',
				),
			)
		);

		$this->add_control(
			'label_payment_method',
			array(
				'label'     => __( 'Payment Method Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Payment method:', 'magical-products-display' ),
				'condition' => array(
					'show_payment_method' => 'yes',
				),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'     => __( 'Layout', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'list'    => __( 'List', 'magical-products-display' ),
					'grid'    => __( 'Grid', 'magical-products-display' ),
					'inline'  => __( 'Inline', 'magical-products-display' ),
				),
				'default'   => 'grid',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'grid_columns',
			array(
				'label'     => __( 'Columns', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				),
				'default'   => '3',
				'condition' => array(
					'layout' => 'grid',
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-details-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Custom Fields, QR Code & Entrance Animation', 'magical-products-display' ) );
		}
			$this->add_control(
				'show_custom_fields',
				array(
					'label'        => __( 'Show Custom Order Fields', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'show_qr_code',
				array(
					'label'        => __( 'Show Order QR Code', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Display QR code for quick order lookup.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'entrance_animation',
				array(
					'label'   => __( 'Entrance Animation', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						''           => __( 'None', 'magical-products-display' ),
						'fade-in'    => __( 'Fade In', 'magical-products-display' ),
						'slide-up'   => __( 'Slide Up', 'magical-products-display' ),
						'zoom-in'    => __( 'Zoom In', 'magical-products-display' ),
					),
					'default' => '',
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
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-details' => 'background-color: {{VALUE}};',
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
					'top'    => '20',
					'right'  => '20',
					'bottom' => '20',
					'left'   => '20',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-order-details',
			)
		);

		$this->add_control(
			'container_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-order-details',
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
					'{{WRAPPER}} .mpd-order-details-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-order-details-title',
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
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-details-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Label Style.
		$this->start_controls_section(
			'section_label_style',
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
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-details-label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .mpd-order-details-label',
			)
		);

		$this->end_controls_section();

		// Value Style.
		$this->start_controls_section(
			'section_value_style',
			array(
				'label' => __( 'Values', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'value_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-details-value' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'value_typography',
				'selector' => '{{WRAPPER}} .mpd-order-details-value',
			)
		);

		$this->end_controls_section();

		// Items Style.
		$this->start_controls_section(
			'section_items_style',
			array(
				'label' => __( 'Items', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'item_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-order-details-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-details-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_spacing',
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
					'{{WRAPPER}} .mpd-order-details-list .mpd-order-details-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-order-details-grid' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-order-details-inline .mpd-order-details-item' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'item_border',
				'selector' => '{{WRAPPER}} .mpd-order-details-item',
			)
		);

		$this->add_control(
			'item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-order-details-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		// Get layout class.
		$layout_class = 'mpd-order-details-' . esc_attr( $settings['layout'] );

		// Pro feature: Entrance animation.
		$entrance_animation = '';
		if ( $this->is_pro() && ! empty( $settings['entrance_animation'] ) ) {
			$entrance_animation = ' mpd-animate mpd-animate-' . esc_attr( $settings['entrance_animation'] );
		}

		// Pro feature: QR Code.
		$show_qr_code = $this->is_pro() && 'yes' === ( $settings['show_qr_code'] ?? '' );
		?>
		<div class="mpd-order-details<?php echo esc_attr( $entrance_animation ); ?>">
			<?php if ( ! empty( $settings['section_title'] ) ) : ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mpd-order-details-title">
					<?php echo esc_html( $settings['section_title'] ); ?>
				</<?php echo esc_attr( $settings['title_tag'] ); ?>>
			<?php endif; ?>

			<?php if ( $order || $is_editor ) : ?>
				<div class="<?php echo esc_attr( $layout_class ); ?>">
					<?php
					$details = $this->get_order_details_data( $order, $settings, $is_editor );
					foreach ( $details as $detail ) :
						if ( ! $detail['show'] ) {
							continue;
						}
						?>
						<div class="mpd-order-details-item">
							<span class="mpd-order-details-label"><?php echo esc_html( $detail['label'] ); ?></span>
							<span class="mpd-order-details-value"><?php echo wp_kses_post( $detail['value'] ); ?></span>
						</div>
					<?php endforeach; ?>

					<?php
					// Pro feature: Custom Fields.
					if ( $this->is_pro() && 'yes' === ( $settings['show_custom_fields'] ?? '' ) && $order ) {
						$custom_fields = $order->get_meta_data();
						if ( ! empty( $custom_fields ) ) {
							foreach ( $custom_fields as $field ) {
								$key   = $field->key;
								$value = $field->value;
								// Skip private meta (starting with _).
								if ( strpos( $key, '_' ) === 0 || ! is_string( $value ) ) {
									continue;
								}
								?>
								<div class="mpd-order-details-item mpd-order-details-custom-field">
									<span class="mpd-order-details-label"><?php echo esc_html( ucwords( str_replace( array( '_', '-' ), ' ', $key ) ) ); ?></span>
									<span class="mpd-order-details-value"><?php echo esc_html( $value ); ?></span>
								</div>
								<?php
							}
						}
					} elseif ( $this->is_pro() && 'yes' === ( $settings['show_custom_fields'] ?? '' ) && $is_editor ) {
						// Demo custom field in editor.
						?>
						<div class="mpd-order-details-item mpd-order-details-custom-field">
							<span class="mpd-order-details-label"><?php esc_html_e( 'Custom Field', 'magical-products-display' ); ?></span>
							<span class="mpd-order-details-value"><?php esc_html_e( 'Custom Value', 'magical-products-display' ); ?></span>
						</div>
						<?php
					}
					?>
				</div>

				<?php if ( $show_qr_code ) : ?>
					<div class="mpd-order-details-qr">
						<?php
						$order_id = $order ? $order->get_id() : '12345';
						$qr_url   = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' . rawurlencode( home_url( '/my-account/view-order/' . $order_id ) );
						?>
						<img src="<?php echo esc_url( $qr_url ); ?>" alt="<?php esc_attr_e( 'Order QR Code', 'magical-products-display' ); ?>" class="mpd-qr-code-image" />
						<p class="mpd-qr-code-label"><?php esc_html_e( 'Scan to view order', 'magical-products-display' ); ?></p>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="mpd-order-details-empty">
					<p><?php esc_html_e( 'No order found.', 'magical-products-display' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
		<?php
		// Pro features styles.
		$has_pro_features = $this->is_pro() && (
			'yes' === ( $settings['show_qr_code'] ?? '' ) ||
			! empty( $settings['entrance_animation'] ) ||
			'yes' === ( $settings['show_custom_fields'] ?? '' )
		);

		if ( $has_pro_features ) :
			?>
			<style>
			.mpd-order-details-qr { text-align: center; margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 8px; }
			.mpd-qr-code-image { max-width: 120px; height: auto; border-radius: 4px; }
			.mpd-qr-code-label { font-size: 12px; color: #64748b; margin-top: 8px; margin-bottom: 0; }
			.mpd-order-details-custom-field { background: #f1f5f9; border-radius: 4px; }
			.mpd-animate { animation-duration: 0.8s; animation-fill-mode: both; }
			.mpd-animate-fadeIn { animation-name: mpd-fadeIn; }
			.mpd-animate-fadeInUp { animation-name: mpd-fadeInUp; }
			.mpd-animate-fadeInDown { animation-name: mpd-fadeInDown; }
			.mpd-animate-zoomIn { animation-name: mpd-zoomIn; }
			.mpd-animate-slideInLeft { animation-name: mpd-slideInLeft; }
			.mpd-animate-slideInRight { animation-name: mpd-slideInRight; }
			@keyframes mpd-fadeIn { from { opacity: 0; } to { opacity: 1; } }
			@keyframes mpd-fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
			@keyframes mpd-fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
			@keyframes mpd-zoomIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
			@keyframes mpd-slideInLeft { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
			@keyframes mpd-slideInRight { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
			</style>
			<?php
		endif;
	}

	/**
	 * Get order details data.
	 *
	 * @since 2.1.0
	 *
	 * @param \WC_Order|false $order     Order object.
	 * @param array           $settings  Widget settings.
	 * @param bool            $is_editor Whether in editor mode.
	 *
	 * @return array Order details.
	 */
	private function get_order_details_data( $order, $settings, $is_editor ) {
		$details = array();

		// Order Number.
		$details[] = array(
			'show'  => 'yes' === $settings['show_order_number'],
			'label' => $settings['label_order_number'],
			'value' => $order ? $order->get_order_number() : '12345',
		);

		// Date.
		$details[] = array(
			'show'  => 'yes' === $settings['show_date'],
			'label' => $settings['label_date'],
			'value' => $order ? wc_format_datetime( $order->get_date_created() ) : gmdate( 'F j, Y' ),
		);

		// Email.
		$details[] = array(
			'show'  => 'yes' === $settings['show_email'],
			'label' => $settings['label_email'],
			'value' => $order ? $order->get_billing_email() : 'customer@example.com',
		);

		// Total.
		$details[] = array(
			'show'  => 'yes' === $settings['show_total'],
			'label' => $settings['label_total'],
			'value' => $order ? $order->get_formatted_order_total() : wc_price( 99.99 ),
		);

		// Payment Method.
		$details[] = array(
			'show'  => 'yes' === $settings['show_payment_method'],
			'label' => $settings['label_payment_method'],
			'value' => $order ? $order->get_payment_method_title() : __( 'Credit Card', 'magical-products-display' ),
		);

		return $details;
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
