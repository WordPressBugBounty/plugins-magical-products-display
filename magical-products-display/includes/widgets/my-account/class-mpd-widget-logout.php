<?php
/**
 * Logout Button Widget
 *
 * Displays a logout button with optional confirmation modal.
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
use Elementor\Icons_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Logout
 *
 * @since 2.0.0
 */
class Logout extends Widget_Base {

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
	protected $widget_icon = 'eicon-lock-user';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-account-logout';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Logout Button', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'logout', 'sign out', 'exit', 'button', 'my account', 'user' );
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
		// Button Section.
		$this->start_controls_section(
			'section_button',
			array(
				'label' => esc_html__( 'Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Logout', 'magical-products-display' ),
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
			'button_icon',
			array(
				'label'     => esc_html__( 'Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-sign-out-alt',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => esc_html__( 'Before', 'magical-products-display' ),
					'right' => esc_html__( 'After', 'magical-products-display' ),
				),
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'alignment',
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
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .mpd-logout' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'redirect_url',
			array(
				'label'       => esc_html__( 'Redirect URL', 'magical-products-display' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'magical-products-display' ),
				'description' => esc_html__( 'Leave empty to redirect to home page.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Confirmation Modal Section (Pro).
		$this->start_controls_section(
			'section_confirmation',
			array(
				'label' => esc_html__( 'Confirmation Modal', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'confirmation_pro_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Confirmation modal is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'show_confirmation',
			array(
				'label'        => esc_html__( 'Show Confirmation', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'modal_title',
			array(
				'label'     => esc_html__( 'Modal Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Confirm Logout', 'magical-products-display' ),
				'condition' => array(
					'show_confirmation' => 'yes',
				),
				'classes'   => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'modal_message',
			array(
				'label'     => esc_html__( 'Modal Message', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => esc_html__( 'Are you sure you want to logout?', 'magical-products-display' ),
				'condition' => array(
					'show_confirmation' => 'yes',
				),
				'classes'   => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'confirm_button_text',
			array(
				'label'     => esc_html__( 'Confirm Button Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Yes, Logout', 'magical-products-display' ),
				'condition' => array(
					'show_confirmation' => 'yes',
				),
				'classes'   => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'cancel_button_text',
			array(
				'label'     => esc_html__( 'Cancel Button Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Cancel', 'magical-products-display' ),
				'condition' => array(
					'show_confirmation' => 'yes',
				),
				'classes'   => $this->is_pro() ? '' : 'mpd-pro-disabled',
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
		// Button Style Section.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label' => esc_html__( 'Button', 'magical-products-display' ),
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
					'{{WRAPPER}} .mpd-logout__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-logout__button',
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
					'{{WRAPPER}} .mpd-logout__button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-logout__button:hover',
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-logout__button:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .mpd-logout__button',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-logout__button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-logout__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-logout__button',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-logout__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
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

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-logout__icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-logout__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-logout__button--icon-left .mpd-logout__icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-logout__button--icon-right .mpd-logout__icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Modal Style Section (Pro).
		$this->start_controls_section(
			'section_modal_style',
			array(
				'label'     => esc_html__( 'Confirmation Modal', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_confirmation' => 'yes',
				),
			)
		);

		$this->add_control(
			'modal_style_pro_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Modal styling is available in Pro.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'modal_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-logout__modal-content',
				'classes'  => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_responsive_control(
			'modal_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-logout__modal-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'classes'    => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_responsive_control(
			'modal_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-logout__modal-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'classes'    => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'modal_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-logout__modal-content',
				'classes'  => $this->is_pro() ? '' : 'mpd-pro-disabled',
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
		// If user is not logged in, show login button instead or hide.
		if ( ! is_user_logged_in() ) {
			// In editor mode, show placeholder.
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				$this->render_editor_placeholder(
					esc_html__( 'Logout Button', 'magical-products-display' ),
					esc_html__( 'This widget displays a logout button for logged-in users.', 'magical-products-display' )
				);
			}
			return;
		}

		// Get logout URL.
		$redirect_url = ! empty( $settings['redirect_url']['url'] )
			? $settings['redirect_url']['url']
			: home_url();

		$logout_url = wp_logout_url( $redirect_url );

		// Build button classes.
		$button_classes = array( 'mpd-logout__button', 'button' );
		if ( 'yes' === $settings['show_icon'] && ! empty( $settings['button_icon']['value'] ) ) {
			$button_classes[] = 'mpd-logout__button--icon-' . $settings['icon_position'];
		}

		// Check for Pro confirmation modal.
		$show_confirmation = 'yes' === $settings['show_confirmation'] && $this->is_pro();
		?>
		<div class="mpd-logout">
			<?php if ( $show_confirmation ) : ?>
				<button type="button" class="<?php echo esc_attr( implode( ' ', $button_classes ) ); ?>" data-mpd-logout-trigger>
					<?php $this->render_button_content( $settings ); ?>
				</button>
				<?php $this->render_confirmation_modal( $logout_url, $settings ); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( $logout_url ); ?>" class="<?php echo esc_attr( implode( ' ', $button_classes ) ); ?>">
					<?php $this->render_button_content( $settings ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render button content.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_button_content( $settings ) {
		$show_icon    = 'yes' === $settings['show_icon'] && ! empty( $settings['button_icon']['value'] );
		$icon_left    = $show_icon && 'left' === $settings['icon_position'];
		$icon_right   = $show_icon && 'right' === $settings['icon_position'];

		if ( $icon_left ) :
			?>
			<span class="mpd-logout__icon">
				<?php Icons_Manager::render_icon( $settings['button_icon'], array( 'aria-hidden' => 'true' ) ); ?>
			</span>
			<?php
		endif;

		if ( ! empty( $settings['button_text'] ) ) :
			?>
			<span class="mpd-logout__text"><?php echo esc_html( $settings['button_text'] ); ?></span>
			<?php
		endif;

		if ( $icon_right ) :
			?>
			<span class="mpd-logout__icon">
				<?php Icons_Manager::render_icon( $settings['button_icon'], array( 'aria-hidden' => 'true' ) ); ?>
			</span>
			<?php
		endif;
	}

	/**
	 * Render confirmation modal.
	 *
	 * @since 2.0.0
	 *
	 * @param string $logout_url Logout URL.
	 * @param array  $settings   Widget settings.
	 * @return void
	 */
	protected function render_confirmation_modal( $logout_url, $settings ) {
		?>
		<div class="mpd-logout__modal" data-mpd-logout-modal style="display: none;">
			<div class="mpd-logout__modal-overlay" data-mpd-logout-close></div>
			<div class="mpd-logout__modal-content">
				<?php if ( ! empty( $settings['modal_title'] ) ) : ?>
					<h4 class="mpd-logout__modal-title"><?php echo esc_html( $settings['modal_title'] ); ?></h4>
				<?php endif; ?>

				<?php if ( ! empty( $settings['modal_message'] ) ) : ?>
					<p class="mpd-logout__modal-message"><?php echo esc_html( $settings['modal_message'] ); ?></p>
				<?php endif; ?>

				<div class="mpd-logout__modal-buttons">
					<a href="<?php echo esc_url( $logout_url ); ?>" class="button mpd-logout__confirm-button">
						<?php echo esc_html( $settings['confirm_button_text'] ); ?>
					</a>
					<button type="button" class="button mpd-logout__cancel-button" data-mpd-logout-close>
						<?php echo esc_html( $settings['cancel_button_text'] ); ?>
					</button>
				</div>
			</div>
		</div>
		<?php
	}
}
