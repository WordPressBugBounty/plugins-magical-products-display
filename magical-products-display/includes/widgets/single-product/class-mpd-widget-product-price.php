<?php
/**
 * Product Price Widget
 *
 * Displays the product price on single product pages.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\SingleProduct;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Product_Price
 *
 * @since 2.0.0
 */
class Product_Price extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_SINGLE_PRODUCT;

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-product-price';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Product Price', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-price';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'price', 'sale', 'discount', 'woocommerce', 'single' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-single-product' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.0.0
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

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start'   => array(
						'title' => __( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'  => array(
						'title' => __( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-price' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'price_layout',
			array(
				'label'   => __( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'inline' => __( 'Inline', 'magical-products-display' ),
					'stacked' => __( 'Stacked', 'magical-products-display' ),
				),
				'default' => 'inline',
				'prefix_class' => 'mpd-price-layout-',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Discount Labels & Custom Price Format', 'magical-products-display' ) );
		}
			$this->add_control(
				'show_discount_badge',
				array(
					'label'        => __( 'Show Discount Badge', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'discount_format',
				array(
					'label'     => __( 'Discount Format', 'magical-products-display' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'percentage' => __( 'Percentage (e.g., -20%)', 'magical-products-display' ),
						'amount'     => __( 'Amount (e.g., Save $20)', 'magical-products-display' ),
					),
					'default'   => 'percentage',
					'condition' => array(
						'show_discount_badge' => 'yes',
					),
				)
			);

			$this->add_control(
				'price_prefix',
				array(
					'label'       => __( 'Price Prefix', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => __( 'e.g., From', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'price_suffix',
				array(
					'label'       => __( 'Price Suffix', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => __( 'e.g., per month', 'magical-products-display' ),
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
		$this->start_controls_section(
			'section_style_price',
			array(
				'label' => __( 'Price', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => __( 'Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-price .price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-price ins' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-price .price ins' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-price .amount' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-price .price .amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .mpd-product-price, {{WRAPPER}} .mpd-product-price .price, {{WRAPPER}} .mpd-product-price .amount, {{WRAPPER}} .mpd-product-price .price .amount',
			)
		);

		$this->add_control(
			'heading_sale_price',
			array(
				'label'     => __( 'Sale Price', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'sale_price_color',
			array(
				'label'     => __( 'Sale Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-price ins' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-price ins .amount' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-price .price ins' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-price .price ins .amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sale_price_typography',
				'selector' => '{{WRAPPER}} .mpd-product-price ins, {{WRAPPER}} .mpd-product-price .price ins',
			)
		);

		$this->add_control(
			'heading_regular_price',
			array(
				'label'     => __( 'Regular Price (Strikethrough)', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'regular_price_color',
			array(
				'label'     => __( 'Regular Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-price del' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-price del .amount' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-price .price del' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-price .price del .amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'regular_price_typography',
				'selector' => '{{WRAPPER}} .mpd-product-price del, {{WRAPPER}} .mpd-product-price .price del',
			)
		);

		$this->add_responsive_control(
			'price_spacing',
			array(
				'label'      => __( 'Spacing Between Prices', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-product-price del' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-product-price .price del' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_responsive_control(
			'price_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Discount Badge Style (Pro).
		if ( $this->is_pro() ) {
			$this->start_controls_section(
				'section_style_discount_badge',
				array(
					'label'     => __( 'Discount Badge', 'magical-products-display' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'show_discount_badge' => 'yes',
					),
				)
			);

			$this->add_control(
				'badge_bg_color',
				array(
					'label'     => __( 'Background Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-discount-badge' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'badge_text_color',
				array(
					'label'     => __( 'Text Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-discount-badge' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'badge_typography',
					'selector' => '{{WRAPPER}} .mpd-discount-badge',
				)
			);

			$this->add_responsive_control(
				'badge_padding',
				array(
					'label'      => __( 'Padding', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-discount-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'badge_border_radius',
				array(
					'label'      => __( 'Border Radius', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-discount-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();
		}
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
		// Ensure styles are enqueued on frontend.
		if ( ! $this->is_editor_mode() ) {
			wp_enqueue_style( 'mpd-single-product' );
		}
		
		$product = $this->get_current_product();

		// Check if we should show demo price.
		$show_demo = false;
		if ( ! $product ) {
			$show_demo = true;
		} elseif ( $this->is_editor_mode() ) {
			// In editor, show demo if product has no price.
			$price_html = $product->get_price_html();
			if ( empty( $price_html ) || '' === $product->get_price() ) {
				$show_demo = true;
			}
		}

		if ( $show_demo ) {
			if ( $this->is_editor_mode() ) {
				$this->render_demo_price( $settings );
				return;
			}
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-product-price' );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			// Pro: Price prefix.
			if ( $this->is_pro() && ! empty( $settings['price_prefix'] ) ) {
				printf( '<span class="mpd-price-prefix">%s </span>', esc_html( $settings['price_prefix'] ) );
			}

			// Output WooCommerce price HTML.
			echo wp_kses_post( $product->get_price_html() );

			// Pro: Price suffix.
			if ( $this->is_pro() && ! empty( $settings['price_suffix'] ) ) {
				printf( '<span class="mpd-price-suffix"> %s</span>', esc_html( $settings['price_suffix'] ) );
			}

			// Pro: Discount badge.
			if ( $this->is_pro() && 'yes' === ( $settings['show_discount_badge'] ?? '' ) ) {
				if ( $product->is_on_sale() ) {
					$this->render_discount_badge( $product, $settings );
				} elseif ( $this->is_editor_mode() ) {
					// Show demo badge in editor for design purposes.
					$this->render_demo_discount_badge( $settings );
				}
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render demo price for editor preview.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_demo_price( $settings ) {
		$currency_symbol = function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '$';
		$discount_format = $settings['discount_format'] ?? 'percentage';
		
		$this->add_render_attribute( 'wrapper', 'class', 'mpd-product-price mpd-product-price--preview' );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			// Pro: Price prefix.
			if ( $this->is_pro() && ! empty( $settings['price_prefix'] ) ) {
				printf( '<span class="mpd-price-prefix">%s </span>', esc_html( $settings['price_prefix'] ) );
			}
			?>
			<span class="price">
				<del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php echo esc_html( $currency_symbol ); ?></span>99.00</bdi></span></del>
				<ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php echo esc_html( $currency_symbol ); ?></span>49.00</bdi></span></ins>
			</span>
			<?php
			// Pro: Price suffix.
			if ( $this->is_pro() && ! empty( $settings['price_suffix'] ) ) {
				printf( '<span class="mpd-price-suffix"> %s</span>', esc_html( $settings['price_suffix'] ) );
			}

			// Pro: Discount badge demo.
			if ( $this->is_pro() && 'yes' === ( $settings['show_discount_badge'] ?? '' ) ) {
				if ( 'percentage' === $discount_format ) {
					echo '<span class="mpd-discount-badge">-50%</span>';
				} else {
					printf( '<span class="mpd-discount-badge">%s</span>', esc_html( sprintf( __( 'Save %s', 'magical-products-display' ), $currency_symbol . '50.00' ) ) );
				}
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render discount badge.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  The product object.
	 * @param array       $settings Widget settings.
	 * @return void
	 */
	private function render_discount_badge( $product, $settings ) {
		$regular_price = (float) $product->get_regular_price();
		$sale_price    = (float) $product->get_sale_price();

		if ( $regular_price <= 0 ) {
			return;
		}

		if ( 'percentage' === $settings['discount_format'] ) {
			$discount = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
			$badge_text = sprintf(
				/* translators: %s: discount percentage */
				__( '-%s%%', 'magical-products-display' ),
				$discount
			);
		} else {
			$discount = $regular_price - $sale_price;
			$badge_text = sprintf(
				/* translators: %s: discount amount */
				__( 'Save %s', 'magical-products-display' ),
				wc_price( $discount )
			);
		}

		printf( '<span class="mpd-discount-badge">%s</span>', wp_kses_post( $badge_text ) );
	}

	/**
	 * Render demo discount badge for editor preview.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_demo_discount_badge( $settings ) {
		$currency_symbol = function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '$';
		$discount_format = $settings['discount_format'] ?? 'percentage';

		if ( 'percentage' === $discount_format ) {
			$badge_text = '-20%';
		} else {
			$badge_text = sprintf( __( 'Save %s', 'magical-products-display' ), $currency_symbol . '10.00' );
		}
		?>
		<span class="mpd-discount-badge mpd-discount-badge--demo">
			<?php echo esc_html( $badge_text ); ?>
			<small style="display: block; font-size: 9px; opacity: 0.8; margin-top: 2px;"><?php esc_html_e( '(Demo)', 'magical-products-display' ); ?></small>
		</span>
		<?php
	}
}
