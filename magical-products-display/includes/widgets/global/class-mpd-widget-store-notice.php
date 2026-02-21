<?php
/**
 * Store Notice Widget
 *
 * Displays a customizable store notice with dismissible and countdown features.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\GlobalWidgets;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Store_Notice
 *
 * @since 2.0.0
 */
class Store_Notice extends Widget_Base {

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
	protected $widget_icon = 'eicon-alert';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-store-notice';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Store Notice', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'notice', 'alert', 'announcement', 'banner', 'message', 'magical-products-display' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-global-widgets' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'mpd-global-widgets' );
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
				'label' => esc_html__( 'Notice Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'notice_source',
			array(
				'label'   => esc_html__( 'Source', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => array(
					'woocommerce' => esc_html__( 'WooCommerce Store Notice', 'magical-products-display' ),
					'custom'      => esc_html__( 'Custom', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'custom_notice',
			array(
				'label'       => esc_html__( 'Notice Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( '🎉 Free shipping on orders over $50! Limited time offer.', 'magical-products-display' ),
				'placeholder' => esc_html__( 'Enter your notice text...', 'magical-products-display' ),
				'condition'   => array(
					'notice_source' => 'custom',
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
				'default'      => '',
			)
		);

		$this->add_control(
			'notice_icon',
			array(
				'label'     => esc_html__( 'Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-bullhorn',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'notice_link',
			array(
				'label'       => esc_html__( 'Link', 'magical-products-display' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'magical-products-display' ),
				'options'     => array( 'url', 'is_external', 'nofollow' ),
				'default'     => array(
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				),
			)
		);

		$this->end_controls_section();

		// Behavior Section.
		$this->start_controls_section(
			'section_behavior',
			array(
				'label' => esc_html__( 'Behavior', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'is_dismissible',
			array(
				'label'        => esc_html__( 'Dismissible', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'dismiss_duration',
			array(
				'label'       => esc_html__( 'Remember Dismissal (days)', 'magical-products-display' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'min'         => 0,
				'max'         => 365,
				'description' => esc_html__( 'How many days to remember dismissal. Set to 0 for session only.', 'magical-products-display' ),
				'condition'   => array(
					'is_dismissible' => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_countdown',
			array(
				'label'        => esc_html__( 'Enable Countdown', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'countdown_date',
			array(
				'label'     => esc_html__( 'Countdown End Date', 'magical-products-display' ),
				'type'      => Controls_Manager::DATE_TIME,
				'default'   => gmdate( 'Y-m-d H:i', strtotime( '+7 days' ) ),
				'condition' => array(
					'enable_countdown' => 'yes',
				),
			)
		);

		$this->add_control(
			'countdown_expired_action',
			array(
				'label'     => esc_html__( 'When Expired', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'hide',
				'options'   => array(
					'hide'    => esc_html__( 'Hide Notice', 'magical-products-display' ),
					'message' => esc_html__( 'Show Message', 'magical-products-display' ),
				),
				'condition' => array(
					'enable_countdown' => 'yes',
				),
			)
		);

		$this->add_control(
			'countdown_expired_message',
			array(
				'label'     => esc_html__( 'Expired Message', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'This offer has expired.', 'magical-products-display' ),
				'condition' => array(
					'enable_countdown'          => 'yes',
					'countdown_expired_action' => 'message',
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
		// Notice Style Section.
		$this->start_controls_section(
			'section_notice_style',
			array(
				'label' => esc_html__( 'Notice', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'notice_type',
			array(
				'label'   => esc_html__( 'Notice Type', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'info',
				'options' => array(
					'info'    => esc_html__( 'Info', 'magical-products-display' ),
					'success' => esc_html__( 'Success', 'magical-products-display' ),
					'warning' => esc_html__( 'Warning', 'magical-products-display' ),
					'error'   => esc_html__( 'Error', 'magical-products-display' ),
					'custom'  => esc_html__( 'Custom', 'magical-products-display' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'notice_background',
				'label'     => esc_html__( 'Background', 'magical-products-display' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .mpd-store-notice',
				'condition' => array(
					'notice_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'notice_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-store-notice' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-store-notice a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'notice_type' => 'custom',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'notice_typography',
				'selector' => '{{WRAPPER}} .mpd-store-notice__text',
			)
		);

		$this->add_responsive_control(
			'notice_alignment',
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
					'{{WRAPPER}} .mpd-store-notice' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'notice_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => 15,
					'right'  => 20,
					'bottom' => 15,
					'left'   => 20,
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-store-notice' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'notice_border',
				'selector' => '{{WRAPPER}} .mpd-store-notice',
			)
		);

		$this->add_responsive_control(
			'notice_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-store-notice' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'notice_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-store-notice',
			)
		);

		$this->end_controls_section();

		// Icon Style Section.
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label'     => esc_html__( 'Icon', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-store-notice__icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-store-notice__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 60,
					),
				),
				'default'    => array(
					'size' => 20,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-store-notice__icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-store-notice__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
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
					'{{WRAPPER}} .mpd-store-notice__icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Close Button Style Section.
		$this->start_controls_section(
			'section_close_style',
			array(
				'label'     => esc_html__( 'Close Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'is_dismissible' => 'yes',
				),
			)
		);

		$this->add_control(
			'close_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-store-notice__close' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'close_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-store-notice__close:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'close_size',
			array(
				'label'      => esc_html__( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 40,
					),
				),
				'default'    => array(
					'size' => 16,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-store-notice__close' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Countdown Style Section.
		$this->start_controls_section(
			'section_countdown_style',
			array(
				'label'     => esc_html__( 'Countdown', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'enable_countdown' => 'yes',
				),
			)
		);

		$this->add_control(
			'countdown_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-store-notice__countdown' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'countdown_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-store-notice__countdown-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'countdown_typography',
				'selector' => '{{WRAPPER}} .mpd-store-notice__countdown-value',
			)
		);

		$this->add_responsive_control(
			'countdown_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-store-notice__countdown' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
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
		// Get notice text.
		if ( 'woocommerce' === $settings['notice_source'] ) {
			if ( ! class_exists( 'WooCommerce' ) ) {
				return;
			}
			$notice_text = get_option( 'woocommerce_demo_store_notice', '' );
			if ( empty( $notice_text ) ) {
				return;
			}
		} else {
			$notice_text = $settings['custom_notice'];
		}

		if ( empty( $notice_text ) ) {
			return;
		}

		// Check countdown expiration.
		$is_expired = false;
		if ( 'yes' === $settings['enable_countdown'] && ! empty( $settings['countdown_date'] ) ) {
			$end_time = strtotime( $settings['countdown_date'] );
			if ( $end_time && $end_time < time() ) {
				$is_expired = true;
				if ( 'hide' === $settings['countdown_expired_action'] ) {
					return;
				}
			}
		}

		// Generate unique ID for dismissible.
		$notice_id   = md5( $notice_text . $settings['dismiss_duration'] );
		$notice_type = 'custom' !== $settings['notice_type'] ? $settings['notice_type'] : '';

		// Build wrapper class.
		$wrapper_class = 'mpd-store-notice';
		if ( $notice_type ) {
			$wrapper_class .= ' mpd-store-notice--' . $notice_type;
		}
		if ( 'yes' === $settings['is_dismissible'] ) {
			$wrapper_class .= ' mpd-store-notice--dismissible';
		}

		// Data attributes for JS.
		$data_attrs = '';
		if ( 'yes' === $settings['is_dismissible'] ) {
			$data_attrs .= ' data-notice-id="' . esc_attr( $notice_id ) . '"';
			$data_attrs .= ' data-dismiss-days="' . esc_attr( $settings['dismiss_duration'] ) . '"';
		}
		if ( 'yes' === $settings['enable_countdown'] && ! $is_expired && ! empty( $settings['countdown_date'] ) ) {
			$data_attrs .= ' data-countdown="' . esc_attr( $settings['countdown_date'] ) . '"';
			$data_attrs .= ' data-expired-action="' . esc_attr( $settings['countdown_expired_action'] ) . '"';
			$data_attrs .= ' data-expired-message="' . esc_attr( $settings['countdown_expired_message'] ) . '"';
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>" role="alert"<?php echo $data_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<div class="mpd-store-notice__content">
				<?php if ( 'yes' === $settings['show_icon'] && ! empty( $settings['notice_icon']['value'] ) ) : ?>
					<span class="mpd-store-notice__icon">
						<?php Icons_Manager::render_icon( $settings['notice_icon'], array( 'aria-hidden' => 'true' ) ); ?>
					</span>
				<?php endif; ?>

				<span class="mpd-store-notice__text">
					<?php if ( $is_expired && 'message' === $settings['countdown_expired_action'] ) : ?>
						<?php echo esc_html( $settings['countdown_expired_message'] ); ?>
					<?php elseif ( ! empty( $settings['notice_link']['url'] ) ) : ?>
						<a href="<?php echo esc_url( $settings['notice_link']['url'] ); ?>"
						<?php echo $settings['notice_link']['is_external'] ? ' target="_blank"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attribute ?>
						<?php echo $settings['notice_link']['nofollow'] ? ' rel="nofollow"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attribute ?>>
							<?php echo wp_kses_post( $notice_text ); ?>
						</a>
					<?php else : ?>
						<?php echo wp_kses_post( $notice_text ); ?>
					<?php endif; ?>
				</span>

				<?php if ( 'yes' === $settings['enable_countdown'] && ! $is_expired && ! empty( $settings['countdown_date'] ) ) : ?>
					<span class="mpd-store-notice__countdown">
						<span class="mpd-store-notice__countdown-item">
							<span class="mpd-store-notice__countdown-value mpd-countdown-days">00</span>
							<span class="mpd-store-notice__countdown-label"><?php esc_html_e( 'D', 'magical-products-display' ); ?></span>
						</span>
						<span class="mpd-store-notice__countdown-separator">:</span>
						<span class="mpd-store-notice__countdown-item">
							<span class="mpd-store-notice__countdown-value mpd-countdown-hours">00</span>
							<span class="mpd-store-notice__countdown-label"><?php esc_html_e( 'H', 'magical-products-display' ); ?></span>
						</span>
						<span class="mpd-store-notice__countdown-separator">:</span>
						<span class="mpd-store-notice__countdown-item">
							<span class="mpd-store-notice__countdown-value mpd-countdown-minutes">00</span>
							<span class="mpd-store-notice__countdown-label"><?php esc_html_e( 'M', 'magical-products-display' ); ?></span>
						</span>
						<span class="mpd-store-notice__countdown-separator">:</span>
						<span class="mpd-store-notice__countdown-item">
							<span class="mpd-store-notice__countdown-value mpd-countdown-seconds">00</span>
							<span class="mpd-store-notice__countdown-label"><?php esc_html_e( 'S', 'magical-products-display' ); ?></span>
						</span>
					</span>
				<?php endif; ?>
			</div>

			<?php if ( 'yes' === $settings['is_dismissible'] ) : ?>
				<button type="button" class="mpd-store-notice__close" aria-label="<?php esc_attr_e( 'Dismiss notice', 'magical-products-display' ); ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			<?php endif; ?>
		</div>
		<?php
	}
}
