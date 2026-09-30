<?php
/**
 * Sticky Add to Cart Bar Widget
 *
 * Displays a persistent, conversion-focused floating bar when scrolling past
 * the primary add to cart form on single product pages.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

namespace MPD\MagicalShopBuilder\Widgets\SingleProduct;

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
 * Class Sticky_Add_To_Cart
 *
 * @since 2.1.0
 */
class Sticky_Add_To_Cart extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_SINGLE_PRODUCT;

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	protected $widget_icon = 'eicon-anchor';

	/**
	 * Get widget name.
	 *
	 * @since 2.1.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-sticky-add-to-cart';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.1.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Sticky Add to Cart', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.1.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'sticky', 'add to cart', 'floating', 'bar', 'product', 'buy now', 'single', 'magical' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.1.0
	 *
	 * @return array Style handles.
	 */
	public function get_style_depends() {
		return array( 'mpd-sticky-add-to-cart' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.1.0
	 *
	 * @return array Script handles.
	 */
	public function get_script_depends() {
		return array( 'mpd-sticky-add-to-cart' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.1.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		// Layout Settings.
		$this->start_controls_section(
			'section_sticky_layout',
			array(
				'label' => esc_html__( 'Layout & Behavior', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'bar_position',
			array(
				'label'   => esc_html__( 'Bar Position', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => array(
					'bottom' => esc_html__( 'Bottom of Screen', 'magical-products-display' ),
					'top'    => esc_html__( 'Top of Screen', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'trigger_mode',
			array(
				'label'       => esc_html__( 'Display Trigger', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'scroll_form',
				'options'     => array(
					'scroll_form' => esc_html__( 'When Main Form Scrolls Out of View', 'magical-products-display' ),
					'scroll_px'   => esc_html__( 'After Scrolling Distance (px)', 'magical-products-display' ),
				),
				'description' => esc_html__( 'Determines when the floating bar becomes visible.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'scroll_distance',
			array(
				'label'     => esc_html__( 'Scroll Distance (px)', 'magical-products-display' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 400,
				'min'       => 100,
				'max'       => 2000,
				'step'      => 50,
				'condition' => array(
					'trigger_mode' => 'scroll_px',
				),
			)
		);

		$this->add_control(
			'show_on_mobile',
			array(
				'label'        => esc_html__( 'Show on Mobile', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		// Elements Visibility Section.
		$this->start_controls_section(
			'section_elements',
			array(
				'label' => esc_html__( 'Elements Visibility', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_thumbnail',
			array(
				'label'        => esc_html__( 'Product Image', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Product Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => esc_html__( 'Rating Stars', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_price',
			array(
				'label'        => esc_html__( 'Product Price', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_stock',
			array(
				'label'        => esc_html__( 'Stock Status Badge', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_quantity',
			array(
				'label'        => esc_html__( 'Quantity Selector', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'custom_button_text',
			array(
				'label'       => esc_html__( 'Custom Button Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add to Cart', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_icon',
			array(
				'label'   => esc_html__( 'Button Icon', 'magical-products-display' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-shopping-cart',
					'library' => 'fa-solid',
				),
			)
		);

		$this->end_controls_section();

		// Variations Selection Section (Pro).
		$this->start_controls_section(
			'section_sticky_variations',
			array(
				'label' => method_exists( $this, 'pro_label' ) ? $this->pro_label( esc_html__( 'Variations Selection', 'magical-products-display' ) ) : esc_html__( 'Variations Selection', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( method_exists( $this, 'is_pro' ) && ! $this->is_pro() && method_exists( $this, 'add_pro_notice' ) ) {
			$this->add_pro_notice( 'pro_sticky_variations_notice', esc_html__( 'Direct Variations Selection', 'magical-products-display' ) );
		}

		$this->add_control(
			'enable_sticky_variations',
			array(
				'label'        => method_exists( $this, 'pro_label' ) ? $this->pro_label( esc_html__( 'Direct Variations in Bar', 'magical-products-display' ) ) : esc_html__( 'Direct Variations in Bar', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Enable', 'magical-products-display' ),
				'label_off'    => esc_html__( 'Disable', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => ( method_exists( $this, 'pro_default' ) ? $this->pro_default( 'yes' ) : '' ),
				'description'  => esc_html__( 'Display attribute dropdowns (Size, Color, etc.) directly inside the sticky bar for variable products, allowing instant purchase without scrolling.', 'magical-products-display' ),
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
		// Bar Container Style.
		$this->start_controls_section(
			'section_style_bar',
			array(
				'label' => esc_html__( 'Sticky Bar Container', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'bar_background',
				'label'    => esc_html__( 'Background', 'magical-products-display' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-sticky-bar-wrapper',
			)
		);

		$this->add_responsive_control(
			'bar_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => 12,
					'right'    => 20,
					'bottom'   => 12,
					'left'     => 20,
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-sticky-bar-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'bar_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-sticky-bar-wrapper',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'bar_border',
				'selector' => '{{WRAPPER}} .mpd-sticky-bar-wrapper',
			)
		);

		$this->end_controls_section();

		// Add to Cart Button Style.
		$this->start_controls_section(
			'section_style_button',
			array(
				'label' => esc_html__( 'Add to Cart Button', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .mpd-sticky-btn',
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .mpd-sticky-btn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-sticky-btn svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2271b1',
				'selectors' => array(
					'{{WRAPPER}} .mpd-sticky-btn' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-sticky-btn:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-sticky-btn:hover svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-sticky-btn:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => 6,
					'right'    => 6,
					'bottom'   => 6,
					'left'     => 6,
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-sticky-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Rating Stars Style.
		$this->start_controls_section(
			'section_style_rating',
			array(
				'label'     => esc_html__( 'Rating Stars', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_rating' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rating_size',
			array(
				'label'      => esc_html__( 'Star Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 30,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 13,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-sticky-rating .star-rating' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'rating_color',
			array(
				'label'     => esc_html__( 'Star Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f59e0b',
				'selectors' => array(
					'{{WRAPPER}} .mpd-sticky-rating .star-rating span::before' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .mpd-sticky-rating .star-rating' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'rating_empty_color',
			array(
				'label'     => esc_html__( 'Empty Star Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d3ced2',
				'selectors' => array(
					'{{WRAPPER}} .mpd-sticky-rating .star-rating::before' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Helper to get product instance.
	 *
	 * @since 2.1.0
	 *
	 * @return \WC_Product|false
	 */
	public function get_product() {
		return $this->get_current_product();
	}

	/**
	 * Render widget content.
	 *
	 * @since 2.1.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_widget( $settings ) {
		$product = $this->get_current_product();

		if ( ! $product ) {
			$this->render_editor_placeholder(
				__( 'Sticky Add to Cart', 'magical-products-display' ),
				__( 'This widget displays a persistent sticky add to cart bar. Please use it on a single product page or template.', 'magical-products-display' )
			);
			return;
		}

		$position        = isset( $settings['bar_position'] ) ? $settings['bar_position'] : 'bottom';
		$trigger_mode    = isset( $settings['trigger_mode'] ) ? $settings['trigger_mode'] : 'scroll_form';
		$scroll_distance = isset( $settings['scroll_distance'] ) ? absint( $settings['scroll_distance'] ) : 400;
		$show_on_mobile  = isset( $settings['show_on_mobile'] ) && 'yes' === $settings['show_on_mobile'];

		$product_id   = $product->get_id();
		$product_type = $product->get_type();
		$is_in_stock  = $product->is_in_stock();

		// Custom button text fallback.
		$btn_text = ! empty( $settings['custom_button_text'] ) ? $settings['custom_button_text'] : $product->single_add_to_cart_text();
		if ( 'variable' === $product_type ) {
			$btn_text = ! empty( $settings['custom_button_text'] ) ? $settings['custom_button_text'] : esc_html__( 'Select Options', 'magical-products-display' );
		}

		$wrapper_classes = array(
			'mpd-sticky-add-to-cart-container',
			'woocommerce',
			'mpd-sticky-pos-' . esc_attr( $position ),
		);

		$is_editor = class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->editor->is_edit_mode();

		if ( $is_editor ) {
			$wrapper_classes[] = 'mpd-sticky-in-editor';
		}

		if ( ! $show_on_mobile ) {
			$wrapper_classes[] = 'mpd-sticky-hide-mobile';
		}

		?>
		<?php if ( $is_editor ) : ?>
			<div class="mpd-sticky-editor-placeholder">
				<span class="mpd-sticky-placeholder-icon">📌</span>
				<span class="mpd-sticky-placeholder-text">
					<strong><?php esc_html_e( 'Sticky Add to Cart Bar', 'magical-products-display' ); ?></strong>
					&mdash; <?php echo 'top' === $position ? esc_html__( 'Pinned to Top of Viewport', 'magical-products-display' ) : esc_html__( 'Pinned to Bottom of Viewport', 'magical-products-display' ); ?>
				</span>
			</div>
		<?php endif; ?>

		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
			data-position="<?php echo esc_attr( $position ); ?>"
			data-trigger="<?php echo esc_attr( $trigger_mode ); ?>"
			data-distance="<?php echo esc_attr( $scroll_distance ); ?>"
			data-product-id="<?php echo esc_attr( $product_id ); ?>"
			data-product-type="<?php echo esc_attr( $product_type ); ?>">

			<div class="mpd-sticky-bar-wrapper">
				<div class="mpd-sticky-bar-inner">

					<!-- Product Info Column (Thumbnail + Title + Rating) -->
					<div class="mpd-sticky-col-info">
						<?php if ( 'yes' === $settings['show_thumbnail'] ) : ?>
							<div class="mpd-sticky-thumb">
								<?php echo wp_kses_post( $product->get_image( 'thumbnail' ) ); ?>
							</div>
						<?php endif; ?>

						<div class="mpd-sticky-meta">
							<?php if ( 'yes' === $settings['show_title'] ) : ?>
								<h4 class="mpd-sticky-title"><?php echo wp_kses_post( $product->get_name() ); ?></h4>
							<?php endif; ?>

							<?php
							$show_rating = 'yes' === $settings['show_rating'];
							$has_rating  = $product->get_rating_count() > 0;
							if ( $show_rating && ( $has_rating || $is_editor ) ) :
								$rating_val = $has_rating ? $product->get_average_rating() : 4;
							?>
								<div class="mpd-sticky-rating">
									<?php echo wp_kses_post( wc_get_rating_html( $rating_val ) ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<!-- Pricing & Actions Column -->
					<div class="mpd-sticky-col-actions">
						<?php if ( 'yes' === $settings['show_stock'] ) : ?>
							<div class="mpd-sticky-stock <?php echo esc_attr( $is_in_stock ? 'in-stock' : 'out-of-stock' ); ?>">
								<?php echo esc_html( $is_in_stock ? __( 'In Stock', 'magical-products-display' ) : __( 'Out of Stock', 'magical-products-display' ) ); ?>
							</div>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['show_price'] ) : ?>
							<div class="mpd-sticky-price">
								<?php echo wp_kses_post( $product->get_price_html() ); ?>
							</div>
						<?php endif; ?>

						<?php if ( $is_in_stock ) : ?>
							<?php
							$enable_variations = method_exists( $this, 'should_use_pro_setting' )
								? $this->should_use_pro_setting( isset( $settings['enable_sticky_variations'] ) ? $settings['enable_sticky_variations'] : '' )
								: false;

							if ( 'variable' === $product_type && $enable_variations ) :
								$attributes           = $product->get_variation_attributes();
								$available_variations = $product->get_available_variations();
								?>
								<div class="mpd-sticky-variations-wrap" data-product_variations="<?php echo esc_attr( wp_json_encode( $available_variations ) ); ?>">
									<?php foreach ( $attributes as $attribute_name => $options ) : ?>
										<div class="mpd-sticky-variation-field">
											<select class="mpd-sticky-var-select" data-attribute_name="<?php echo esc_attr( 'attribute_' . sanitize_title( $attribute_name ) ); ?>" aria-label="<?php echo esc_attr( wc_attribute_label( $attribute_name ) ); ?>">
												<option value=""><?php echo esc_html( sprintf( __( 'Select %s', 'magical-products-display' ), wc_attribute_label( $attribute_name ) ) ); ?></option>
												<?php
												if ( is_array( $options ) ) {
													if ( taxonomy_is_product_attribute( $attribute_name ) ) {
														$terms = wc_get_product_terms( $product_id, $attribute_name, array( 'fields' => 'all' ) );
														foreach ( $terms as $term ) {
															if ( in_array( $term->slug, $options, true ) ) {
																echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute_name, $product ) ) . '</option>';
															}
														}
													} else {
														foreach ( $options as $option ) {
															echo '<option value="' . esc_attr( $option ) . '">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute_name, $product ) ) . '</option>';
														}
													}
												}
												?>
											</select>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<?php if ( 'yes' === $settings['show_quantity'] && ( 'simple' === $product_type || ( 'variable' === $product_type && $enable_variations ) ) ) : ?>
								<div class="mpd-sticky-quantity">
									<button type="button" class="mpd-sticky-qty-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'magical-products-display' ); ?>">&minus;</button>
									<input type="number" class="mpd-sticky-qty-input" value="1" min="1" max="<?php echo esc_attr( $product->get_max_purchase_quantity() > 0 ? $product->get_max_purchase_quantity() : '' ); ?>" step="1" aria-label="<?php esc_attr_e( 'Quantity', 'magical-products-display' ); ?>" />
									<button type="button" class="mpd-sticky-qty-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'magical-products-display' ); ?>">&plus;</button>
								</div>
							<?php endif; ?>

							<div class="mpd-sticky-button-wrap">
								<?php if ( 'variable' === $product_type && $enable_variations ) : ?>
									<button type="button" class="mpd-sticky-btn mpd-sticky-add-cart mpd-sticky-var-add-cart disabled" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-variation-id="0">
										<?php if ( ! empty( $settings['button_icon']['value'] ) ) : ?>
											<span class="mpd-sticky-btn-icon"><?php Icons_Manager::render_icon( $settings['button_icon'], array( 'aria-hidden' => 'true' ) ); ?></span>
										<?php endif; ?>
										<span class="mpd-sticky-btn-text"><?php esc_html_e( 'Select Options', 'magical-products-display' ); ?></span>
									</button>
								<?php elseif ( 'variable' === $product_type ) : ?>
									<button type="button" class="mpd-sticky-btn mpd-sticky-select-options">
										<?php if ( ! empty( $settings['button_icon']['value'] ) ) : ?>
											<span class="mpd-sticky-btn-icon"><?php Icons_Manager::render_icon( $settings['button_icon'], array( 'aria-hidden' => 'true' ) ); ?></span>
										<?php endif; ?>
										<span class="mpd-sticky-btn-text"><?php echo esc_html( $btn_text ); ?></span>
									</button>
								<?php elseif ( 'external' === $product_type ) : ?>
									<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="mpd-sticky-btn" target="_blank" rel="nofollow">
										<?php if ( ! empty( $settings['button_icon']['value'] ) ) : ?>
											<span class="mpd-sticky-btn-icon"><?php Icons_Manager::render_icon( $settings['button_icon'], array( 'aria-hidden' => 'true' ) ); ?></span>
										<?php endif; ?>
										<span class="mpd-sticky-btn-text"><?php echo esc_html( $btn_text ); ?></span>
									</a>
								<?php else : ?>
									<button type="button" class="mpd-sticky-btn mpd-sticky-add-cart" data-product-id="<?php echo esc_attr( $product_id ); ?>">
										<?php if ( ! empty( $settings['button_icon']['value'] ) ) : ?>
											<span class="mpd-sticky-btn-icon"><?php Icons_Manager::render_icon( $settings['button_icon'], array( 'aria-hidden' => 'true' ) ); ?></span>
										<?php endif; ?>
										<span class="mpd-sticky-btn-text"><?php echo esc_html( $btn_text ); ?></span>
									</button>
								<?php endif; ?>
							</div>
						<?php endif; ?>

					</div>

				</div>
			</div>
		</div>
		<?php
	}
}
