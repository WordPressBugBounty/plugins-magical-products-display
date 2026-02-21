<?php
/**
 * Header Cart Icon Widget
 *
 * Displays a mini cart icon with count badge and dropdown preview.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\GlobalWidgets;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
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
 * Class Header_Cart
 *
 * @since 2.0.0
 */
class Header_Cart extends Widget_Base {

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
	protected $widget_icon = 'eicon-cart';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-header-cart';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Header Cart', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'cart', 'header', 'mini cart', 'basket', 'icon', 'woocommerce', 'shop' );
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
		return array( 'wc-cart-fragments', 'mpd-global-widgets' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		// Icon Section.
		$this->start_controls_section(
			'section_icon',
			array(
				'label' => esc_html__( 'Cart Icon', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'cart_icon',
			array(
				'label'   => esc_html__( 'Icon', 'magical-products-display' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'eicon-cart-medium',
					'library' => 'eicons',
				),
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => esc_html__( 'Show Count Badge', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'        => esc_html__( 'Hide When Empty', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'show_count' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_subtotal',
			array(
				'label'        => esc_html__( 'Show Subtotal', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'subtotal_position',
			array(
				'label'     => esc_html__( 'Subtotal Position', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after',
				'options'   => array(
					'before' => esc_html__( 'Before Icon', 'magical-products-display' ),
					'after'  => esc_html__( 'After Icon', 'magical-products-display' ),
				),
				'condition' => array(
					'show_subtotal' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Dropdown Section.
		$this->start_controls_section(
			'section_dropdown',
			array(
				'label' => esc_html__( 'Dropdown', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_dropdown',
			array(
				'label'        => esc_html__( 'Show Dropdown Preview', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'dropdown_trigger',
			array(
				'label'     => esc_html__( 'Trigger', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'hover',
				'options'   => array(
					'hover' => esc_html__( 'Hover', 'magical-products-display' ),
					'click' => esc_html__( 'Click', 'magical-products-display' ),
				),
				'condition' => array(
					'show_dropdown' => 'yes',
				),
			)
		);

		$this->add_control(
			'dropdown_position',
			array(
				'label'     => esc_html__( 'Position', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bottom-end',
				'options'   => array(
					'bottom-start' => esc_html__( 'Bottom Left', 'magical-products-display' ),
					'bottom-end'   => esc_html__( 'Bottom Right', 'magical-products-display' ),
				),
				'condition' => array(
					'show_dropdown' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_view_cart_btn',
			array(
				'label'        => esc_html__( 'Show View Cart Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_dropdown' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_checkout_btn',
			array(
				'label'        => esc_html__( 'Show Checkout Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_dropdown' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Link Section.
		$this->start_controls_section(
			'section_link',
			array(
				'label' => esc_html__( 'Link', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'link_type',
			array(
				'label'   => esc_html__( 'Link To', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'cart',
				'options' => array(
					'cart'     => esc_html__( 'Cart Page', 'magical-products-display' ),
					'checkout' => esc_html__( 'Checkout Page', 'magical-products-display' ),
					'custom'   => esc_html__( 'Custom URL', 'magical-products-display' ),
					'none'     => esc_html__( 'No Link', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'custom_link',
			array(
				'label'       => esc_html__( 'Custom URL', 'magical-products-display' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'magical-products-display' ),
				'condition'   => array(
					'link_type' => 'custom',
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
		// Icon Style Section.
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => esc_html__( 'Icon', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
						'min' => 10,
						'max' => 100,
					),
					'em' => array(
						'min' => 0.5,
						'max' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-cart__icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-header-cart__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-cart__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-header-cart__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-cart__wrapper:hover .mpd-header-cart__icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-header-cart__wrapper:hover .mpd-header-cart__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Badge Style Section.
		$this->start_controls_section(
			'section_badge_style',
			array(
				'label'     => esc_html__( 'Count Badge', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_count' => 'yes',
				),
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-cart__badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-cart__badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .mpd-header-cart__badge',
			)
		);

		$this->add_responsive_control(
			'badge_size',
			array(
				'label'      => esc_html__( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 14,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-cart__badge' => 'min-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_position_x',
			array(
				'label'      => esc_html__( 'Horizontal Position', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => -20,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-cart__badge' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_position_y',
			array(
				'label'      => esc_html__( 'Vertical Position', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => -20,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-cart__badge' => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Subtotal Style Section.
		$this->start_controls_section(
			'section_subtotal_style',
			array(
				'label'     => esc_html__( 'Subtotal', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_subtotal' => 'yes',
				),
			)
		);

		$this->add_control(
			'subtotal_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-cart__subtotal' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtotal_typography',
				'selector' => '{{WRAPPER}} .mpd-header-cart__subtotal',
			)
		);

		$this->add_responsive_control(
			'subtotal_spacing',
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
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-cart__subtotal--before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-header-cart__subtotal--after' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Dropdown Style Section.
		$this->start_controls_section(
			'section_dropdown_style',
			array(
				'label'     => esc_html__( 'Dropdown', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_dropdown' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'dropdown_width',
			array(
				'label'      => esc_html__( 'Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 200,
						'max' => 500,
					),
					'%' => array(
						'min' => 50,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-cart__dropdown' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dropdown_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-header-cart__dropdown' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'dropdown_border',
				'selector' => '{{WRAPPER}} .mpd-header-cart__dropdown',
			)
		);

		$this->add_responsive_control(
			'dropdown_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-cart__dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'dropdown_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-header-cart__dropdown',
			)
		);

		$this->add_responsive_control(
			'dropdown_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-header-cart__dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$cart_count    = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
		$cart_subtotal = WC()->cart ? WC()->cart->get_cart_subtotal() : '';

		// Get link.
		$link = $this->get_cart_link( $settings );

		// Wrapper classes.
		$wrapper_classes = array( 'mpd-header-cart__wrapper' );

		// Container classes - includes dropdown settings
		$container_classes = array( 'mpd-header-cart' );
		if ( 'yes' === $settings['show_dropdown'] ) {
			$container_classes[] = 'mpd-header-cart--has-dropdown';
			$container_classes[] = 'mpd-header-cart--trigger-' . esc_attr( $settings['dropdown_trigger'] );
			$container_classes[] = 'mpd-header-cart--position-' . esc_attr( $settings['dropdown_position'] );
		}

		$tag        = $link ? 'a' : 'div';
		$link_attrs = $link ? ' href="' . esc_url( $link ) . '"' : '';
		?>
		<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>">
			<<?php echo esc_html( $tag ); ?> class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php if ( 'yes' === $settings['show_subtotal'] && 'before' === $settings['subtotal_position'] ) : ?>
					<span class="mpd-header-cart__subtotal mpd-header-cart__subtotal--before">
						<?php echo wp_kses_post( $cart_subtotal ); ?>
					</span>
				<?php endif; ?>

				<span class="mpd-header-cart__icon-wrapper">
					<span class="mpd-header-cart__icon">
						<?php Icons_Manager::render_icon( $settings['cart_icon'], array( 'aria-hidden' => 'true' ) ); ?>
					</span>

					<?php if ( 'yes' === $settings['show_count'] ) : ?>
						<?php
						$badge_classes = array( 'mpd-header-cart__badge', 'cart-contents-count' );
						if ( 'yes' === $settings['hide_empty'] && 0 === $cart_count ) {
							$badge_classes[] = 'mpd-header-cart__badge--hidden';
						}
						?>
						<span class="<?php echo esc_attr( implode( ' ', $badge_classes ) ); ?>" data-cart-count="<?php echo esc_attr( $cart_count ); ?>">
							<?php echo esc_html( $cart_count ); ?>
						</span>
					<?php endif; ?>
				</span>

				<?php if ( 'yes' === $settings['show_subtotal'] && 'after' === $settings['subtotal_position'] ) : ?>
					<span class="mpd-header-cart__subtotal mpd-header-cart__subtotal--after">
						<?php echo wp_kses_post( $cart_subtotal ); ?>
					</span>
				<?php endif; ?>
			</<?php echo esc_html( $tag ); ?>>

			<?php if ( 'yes' === $settings['show_dropdown'] ) : ?>
				<div class="mpd-header-cart__dropdown widget_shopping_cart">
					<div class="mpd-header-cart__dropdown-content widget_shopping_cart_content">
						<?php
						if ( $cart_count > 0 ) {
							// WooCommerce mini cart includes buttons
							woocommerce_mini_cart();
						} else {
							?>
							<p class="mpd-header-cart__empty-message woocommerce-mini-cart__empty-message">
								<?php esc_html_e( 'No products in the cart.', 'magical-products-display' ); ?>
							</p>
							<?php
						}
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get cart link URL.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return string|false Cart link URL or false if no link.
	 */
	protected function get_cart_link( $settings ) {
		switch ( $settings['link_type'] ) {
			case 'cart':
				return wc_get_cart_url();
			case 'checkout':
				return wc_get_checkout_url();
			case 'custom':
				return ! empty( $settings['custom_link']['url'] ) ? $settings['custom_link']['url'] : false;
			default:
				return false;
		}
	}
}
