<?php
/**
 * Free Shipping Progress Bar Widget
 *
 * Displays a dynamic, conversion-optimized free shipping progress bar
 * with live cart recalculation.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

namespace MPD\MagicalShopBuilder\Widgets\GlobalWidgets;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use MPD\MagicalShopBuilder\Frontend\Cart_Drawer;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Free_Shipping_Bar
 *
 * @since 2.1.0
 */
class Free_Shipping_Bar extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_GLOBAL;

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	protected $widget_icon = 'eicon-shipping';

	/**
	 * Get widget name.
	 *
	 * @since 2.1.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-free-shipping-bar';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.1.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Free Shipping Bar', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.1.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'shipping', 'free shipping', 'progress', 'bar', 'threshold', 'cart', 'checkout', 'magical' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.1.0
	 *
	 * @return array Style handles.
	 */
	public function get_style_depends() {
		return array( 'mpd-free-shipping-bar' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		// Threshold Section.
		$this->start_controls_section(
			'section_threshold',
			array(
				'label' => esc_html__( 'Shipping Threshold', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'threshold_source',
			array(
				'label'   => esc_html__( 'Threshold Source', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => array(
					'auto'   => esc_html__( 'Auto (WooCommerce Free Shipping Method)', 'magical-products-display' ),
					'custom' => esc_html__( 'Custom Amount', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'custom_threshold',
			array(
				'label'     => esc_html__( 'Minimum Amount', 'magical-products-display' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 50,
				'min'       => 1,
				'step'      => 1,
				'condition' => array(
					'threshold_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => esc_html__( 'Show Icon', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'shipping_icon',
			array(
				'label'     => esc_html__( 'Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-truck',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Messages Section.
		$this->start_controls_section(
			'section_messages',
			array(
				'label' => esc_html__( 'Messages & Text', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'msg_remaining',
			array(
				'label'       => esc_html__( 'Progress Message', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Add {remaining} more to get FREE shipping!', 'magical-products-display' ),
				'description' => esc_html__( 'Placeholders: {remaining}, {threshold}, {current}', 'magical-products-display' ),
				'rows'        => 2,
			)
		);

		$this->add_control(
			'msg_achieved',
			array(
				'label'   => esc_html__( 'Success Message', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( '🎉 Congratulations! You have unlocked FREE shipping!', 'magical-products-display' ),
				'rows'    => 2,
			)
		);

		$this->add_control(
			'msg_empty',
			array(
				'label'       => esc_html__( 'Empty Cart Message', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'Free shipping on orders over {threshold}!', 'magical-products-display' ),
				'description' => esc_html__( 'Displayed when cart has 0 items.', 'magical-products-display' ),
				'rows'        => 2,
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
		// Box Container Style.
		$this->start_controls_section(
			'section_style_box',
			array(
				'label' => esc_html__( 'Container Box', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'box_background',
				'label'    => esc_html__( 'Background', 'magical-products-display' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-fsb-container',
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => 14,
					'right'    => 18,
					'bottom'   => 14,
					'left'     => 18,
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-fsb-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'box_border',
				'selector' => '{{WRAPPER}} .mpd-fsb-container',
			)
		);

		$this->add_control(
			'box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => 8,
					'right'    => 8,
					'bottom'   => 8,
					'left'     => 8,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-fsb-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-fsb-container',
			)
		);

		$this->end_controls_section();

		// Progress Bar Style.
		$this->start_controls_section(
			'section_style_bar',
			array(
				'label' => esc_html__( 'Progress Bar', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'bar_height',
			array(
				'label'     => esc_html__( 'Bar Height (px)', 'magical-products-display' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 8,
				'min'       => 4,
				'max'       => 30,
				'selectors' => array(
					'{{WRAPPER}} .mpd-fsb-progress-track' => 'height: {{VALUE}}px;',
				),
			)
		);

		$this->add_control(
			'bar_track_color',
			array(
				'label'     => esc_html__( 'Track Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e2e8f0',
				'selectors' => array(
					'{{WRAPPER}} .mpd-fsb-progress-track' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bar_fill_color',
			array(
				'label'     => esc_html__( 'Fill Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#10b981',
				'selectors' => array(
					'{{WRAPPER}} .mpd-fsb-progress-fill' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bar_success_fill_color',
			array(
				'label'     => esc_html__( 'Success Fill Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#16a34a',
				'selectors' => array(
					'{{WRAPPER}} .mpd-fsb-container.is-achieved .mpd-fsb-progress-fill' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Typography Style.
		$this->start_controls_section(
			'section_style_typography',
			array(
				'label' => esc_html__( 'Typography & Colors', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .mpd-fsb-message',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1e293b',
				'selectors' => array(
					'{{WRAPPER}} .mpd-fsb-message' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'success_text_color',
			array(
				'label'     => esc_html__( 'Success Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#15803d',
				'selectors' => array(
					'{{WRAPPER}} .mpd-fsb-container.is-achieved .mpd-fsb-message' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'text_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .mpd-fsb-container' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on frontend.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	protected function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		// Calculate threshold.
		$threshold = 0;
		if ( 'custom' === $settings['threshold_source'] ) {
			$threshold = ! empty( $settings['custom_threshold'] ) ? (float) $settings['custom_threshold'] : 50;
		} else {
			$threshold = Cart_Drawer::get_free_shipping_threshold();
		}

		if ( $threshold <= 0 ) {
			$threshold = 50; // Sane fallback.
		}

		$cart_subtotal = ( is_object( WC()->cart ) ) ? (float) WC()->cart->get_displayed_subtotal() : 0.0;
		$cart_count    = ( is_object( WC()->cart ) ) ? WC()->cart->get_cart_contents_count() : 0;
		$is_achieved   = ( $cart_subtotal >= $threshold && $cart_count > 0 );
		$percent       = min( 100, ( $cart_subtotal / $threshold ) * 100 );
		$remaining     = max( 0, $threshold - $cart_subtotal );

		// Prepare message with placeholder replacements.
		if ( 0 === $cart_count ) {
			$raw_msg = ! empty( $settings['msg_empty'] ) ? $settings['msg_empty'] : __( 'Free shipping on orders over {threshold}!', 'magical-products-display' );
		} elseif ( $is_achieved ) {
			$raw_msg = ! empty( $settings['msg_achieved'] ) ? $settings['msg_achieved'] : __( '🎉 Congratulations! You have unlocked FREE shipping!', 'magical-products-display' );
		} else {
			$raw_msg = ! empty( $settings['msg_remaining'] ) ? $settings['msg_remaining'] : __( 'Add {remaining} more to get FREE shipping!', 'magical-products-display' );
		}

		$formatted_remaining = wc_price( $remaining );
		$formatted_threshold = wc_price( $threshold );
		$formatted_current   = wc_price( $cart_subtotal );

		$final_msg = str_replace(
			array( '{remaining}', '{threshold}', '{current}' ),
			array( '<strong>' . $formatted_remaining . '</strong>', '<strong>' . $formatted_threshold . '</strong>', '<strong>' . $formatted_current . '</strong>' ),
			$raw_msg
		);

		?>
		<div class="mpd-fsb-container <?php echo esc_attr( $is_achieved ? 'is-achieved' : '' ); ?>"
			data-threshold="<?php echo esc_attr( $threshold ); ?>"
			data-msg-remaining="<?php echo esc_attr( $settings['msg_remaining'] ); ?>"
			data-msg-achieved="<?php echo esc_attr( $settings['msg_achieved'] ); ?>"
			data-msg-empty="<?php echo esc_attr( $settings['msg_empty'] ); ?>">

			<div class="mpd-fsb-header">
				<?php if ( 'yes' === $settings['show_icon'] && ! empty( $settings['shipping_icon']['value'] ) ) : ?>
					<span class="mpd-fsb-icon" aria-hidden="true">
						<?php Icons_Manager::render_icon( $settings['shipping_icon'] ); ?>
					</span>
				<?php endif; ?>

				<div class="mpd-fsb-message">
					<?php echo wp_kses_post( $final_msg ); ?>
				</div>
			</div>

			<div class="mpd-fsb-progress-track" role="progressbar" aria-valuenow="<?php echo esc_attr( round( $percent ) ); ?>" aria-valuemin="0" aria-valuemax="100">
				<div class="mpd-fsb-progress-fill" style="width: <?php echo esc_attr( $percent ); ?>%;"></div>
			</div>
		</div>
		<?php
	}
}
