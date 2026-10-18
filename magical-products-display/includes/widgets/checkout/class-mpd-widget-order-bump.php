<?php
/**
 * Standalone Order Bump Widget
 *
 * Displays a standalone pre-purchase upsell offer box with 1-click dynamic AJAX cart addition.
 *
 * @package Magical_Shop_Builder
 * @since   2.2.0
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
 * Class Order_Bump
 *
 * @since 2.2.0
 */
class Order_Bump extends Widget_Base {

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
	protected $widget_icon = 'eicon-cart-medium';

	/**
	 * Get widget name.
	 *
	 * @since 2.2.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-order-bump';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.2.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Order Bump (Upsell Offer)', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.2.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'order bump', 'upsell', 'cross sell', 'checkout offer', 'cartflows', 'funnelkit', 'special offer' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.2.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'mpd-one-page-checkout' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.2.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-one-page-checkout' );
	}

	/**
	 * Helper: Get product options for dropdowns.
	 *
	 * @param int $limit Max products to fetch.
	 * @return array [ id => title ]
	 */
	private function get_product_list_options( $limit = 50 ) {
		$options = array( '' => esc_html__( '-- Select Offer Product --', 'magical-products-display' ) );
		if ( ! function_exists( 'wc_get_products' ) ) {
			return $options;
		}

		$products = wc_get_products( array(
			'status'  => 'publish',
			'limit'   => $limit,
			'orderby' => 'date',
			'order'   => 'DESC',
		) );

		foreach ( $products as $product ) {
			$price_label = wc_price( $product->get_price() );
			$options[ $product->get_id() ] = sprintf( '%s (%s)', $product->get_name(), wp_strip_all_tags( $price_label ) );
		}

		return $options;
	}

	/**
	 * Register Content Controls.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		$this->start_controls_section(
			'section_bump_content',
			array(
				'label' => esc_html__( 'Offer Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'bump_product_id',
			array(
				'label'   => esc_html__( 'Offer Product', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_product_list_options(),
				'default' => '',
			)
		);

		$this->add_control(
			'bump_custom_price',
			array(
				'label'       => esc_html__( 'Discounted Offer Price (Optional)', 'magical-products-display' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'step'        => 0.01,
				'description' => esc_html__( 'Leave empty to use the standard product price.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'bump_badge_text',
			array(
				'label'   => esc_html__( 'Badge Text', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'EXCLUSIVE ONE TIME OFFER', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'bump_title',
			array(
				'label'   => esc_html__( 'Offer Title', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Yes! Add Premium Delivery & Warranty', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'bump_description',
			array(
				'label'   => esc_html__( 'Offer Description', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Protect your purchase with our VIP warranty package. Free instant replacement if damaged.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'bump_action',
			array(
				'label'       => esc_html__( 'Action Type', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $this->get_pro_options(
					array(
						'add'     => esc_html__( 'Add to Order (Additional Item)', 'magical-products-display' ),
						'replace' => esc_html__( 'Replace Primary Product (Upsell Upgrade)', 'magical-products-display' ),
					),
					array( 'replace' )
				),
				'default'     => 'add',
				'description' => esc_html__( 'Choose whether to add this item alongside the order, or upgrade/replace the main product.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();

		// Section: Pro Bump Features.
		$this->start_controls_section(
			'section_pro_bump_features',
			array(
				'label' => $this->pro_label( esc_html__( 'Pro Bump Features', 'magical-products-display' ) ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_bump_notice', esc_html__( 'Attention Grabber Animations & Pre-Checked Bumps', 'magical-products-display' ) );
		}

		$this->add_control(
			'bump_precheck',
			array(
				'label'        => $this->pro_label( esc_html__( 'Pre-Check Offer by Default', 'magical-products-display' ) ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Automatically check the offer box on initial page load to boost conversion.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'bump_animation',
			array(
				'label'       => $this->pro_label( esc_html__( 'Attention Grabber Effect', 'magical-products-display' ) ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $this->get_pro_options(
					array(
						'none'  => esc_html__( 'None', 'magical-products-display' ),
						'pulse' => esc_html__( 'Pulsing Glow Highlight', 'magical-products-display' ),
						'arrow' => esc_html__( 'Blinking Pointing Hand', 'magical-products-display' ),
					),
					array( 'pulse', 'arrow' )
				),
				'default'     => 'none',
				'description' => esc_html__( 'Visual animation that draws customer attention to the special offer.', 'magical-products-display' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Controls.
	 *
	 * @since 2.2.0
	 *
	 * @return void
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'section_bump_style',
			array(
				'label' => esc_html__( 'Box Styling', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bump_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fffbeb',
				'selectors' => array(
					'{{WRAPPER}} .mpd-opc-bump-box' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bump_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f59e0b',
				'selectors' => array(
					'{{WRAPPER}} .mpd-opc-bump-box' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bump_badge_bg',
			array(
				'label'     => esc_html__( 'Badge Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d97706',
				'selectors' => array(
					'{{WRAPPER}} .mpd-opc-bump-badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'bump_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 16,
					'right'    => 16,
					'bottom'   => 16,
					'left'     => 16,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-opc-bump-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'bump_radius',
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
					'{{WRAPPER}} .mpd-opc-bump-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @since 2.2.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_widget( $settings ) {
		if ( ! $this->is_woocommerce_active() ) {
			$this->render_wc_required_notice();
			return;
		}

		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
		$bump_id   = ! empty( $settings['bump_product_id'] ) ? absint( $settings['bump_product_id'] ) : 0;

		$bump_product = $bump_id ? wc_get_product( $bump_id ) : null;
		if ( ! $bump_product && $is_editor ) {
			$sample = wc_get_products( array( 'status' => 'publish', 'limit' => 1 ) );
			if ( ! empty( $sample ) ) {
				$bump_product = $sample[0];
				$bump_id      = $bump_product->get_id();
			}
		}

		if ( ! $bump_product ) {
			if ( $is_editor ) {
				echo '<div class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . esc_html__( 'Please select a bump offer product.', 'magical-products-display' ) . '</div>';
			}
			return;
		}

		// Check if already in cart.
		$is_in_cart = false;
		if ( function_exists( 'WC' ) && is_object( WC()->cart ) ) {
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				if ( (int) $cart_item['product_id'] === $bump_id ) {
					$is_in_cart = true;
					break;
				}
			}
		}

		$is_pro      = $this->is_pro();
		$bump_action = ( $is_pro && ! empty( $settings['bump_action'] ) ) ? $settings['bump_action'] : 'add';
		$animation   = ( $is_pro && ! empty( $settings['bump_animation'] ) ) ? $settings['bump_animation'] : 'none';
		$is_precheck = ( $is_pro && ! empty( $settings['bump_precheck'] ) && 'yes' === $settings['bump_precheck'] );

		if ( $is_precheck && ! $is_in_cart ) {
			$is_in_cart = true;
		}

		$anim_class = '';
		if ( 'pulse' === $animation ) {
			$anim_class = 'mpd-bump-pulse';
		}

		$bump_price = ! empty( $settings['bump_custom_price'] ) ? wc_price( $settings['bump_custom_price'] ) : $bump_product->get_price_html();
		$nonce      = wp_create_nonce( 'mpd_funnel_nonce' );
		?>
		<div class="mpd-standalone-bump-wrap woocommerce" data-nonce="<?php echo esc_attr( $nonce ); ?>">
			<div class="mpd-opc-bump-box <?php echo $is_in_cart ? 'is-bump-active' : ''; ?> <?php echo esc_attr( $anim_class ); ?>"
				data-bump-id="<?php echo esc_attr( $bump_id ); ?>"
				data-bump-action="<?php echo esc_attr( $bump_action ); ?>"
				data-custom-price="<?php echo esc_attr( $settings['bump_custom_price'] ?? '' ); ?>">

				<?php if ( ! empty( $settings['bump_badge_text'] ) ) : ?>
					<div class="mpd-opc-bump-badge">
						<span>⚡ <?php echo esc_html( $settings['bump_badge_text'] ); ?></span>
					</div>
				<?php endif; ?>

				<div class="mpd-opc-bump-content">
					<label class="mpd-opc-bump-checkbox-label">
						<?php if ( 'arrow' === $animation ) : ?>
							<span class="mpd-bump-arrow-pointer">👉</span>
						<?php endif; ?>
						<input type="checkbox" class="mpd-opc-bump-checkbox" name="mpd_order_bump_check" value="1" <?php checked( $is_in_cart, true ); ?> />
						<span class="mpd-opc-bump-check-custom"></span>
						<span class="mpd-opc-bump-title">
							<strong><?php echo esc_html( $settings['bump_title'] ?? '' ); ?></strong> &mdash; <span class="mpd-bump-price-highlight"><?php echo wp_kses_post( $bump_price ); ?></span>
						</span>
					</label>

					<div class="mpd-opc-bump-body">
						<div class="mpd-opc-bump-thumb">
							<?php echo wp_kses_post( $bump_product->get_image( 'thumbnail' ) ); ?>
						</div>
						<p class="mpd-opc-bump-desc"><?php echo esc_html( $settings['bump_description'] ?? '' ); ?></p>
					</div>
				</div>

			</div>
		</div>
		<?php
	}
}
