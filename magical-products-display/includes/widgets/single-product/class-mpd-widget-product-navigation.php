<?php
/**
 * Product Navigation Widget
 *
 * Displays previous/next product navigation on single product pages.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\SingleProduct;

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
 * Class Product_Navigation
 *
 * @since 2.0.0
 */
class Product_Navigation extends Widget_Base {

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
		return 'mpd-product-navigation';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Product Navigation', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-navigation';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'navigation', 'prev', 'next', 'woocommerce', 'single' );
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
				'label' => __( 'Navigation', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_prev',
			array(
				'label'        => __( 'Show Previous', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_next',
			array(
				'label'        => __( 'Show Next', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'prev_label',
			array(
				'label'     => __( 'Previous Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Previous', 'magical-products-display' ),
				'condition' => array(
					'show_prev' => 'yes',
				),
			)
		);

		$this->add_control(
			'next_label',
			array(
				'label'     => __( 'Next Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Next', 'magical-products-display' ),
				'condition' => array(
					'show_next' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_icon',
			array(
				'label'        => __( 'Show Icons', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'prev_icon',
			array(
				'label'     => __( 'Previous Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-chevron-left',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_icon' => 'yes',
					'show_prev' => 'yes',
				),
			)
		);

		$this->add_control(
			'next_icon',
			array(
				'label'     => __( 'Next Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'show_icon' => 'yes',
					'show_next' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => __( 'Show Product Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Same Category Navigation & Thumbnails', 'magical-products-display' ) );
		}
			$this->add_control(
				'same_category',
				array(
					'label'        => __( 'Same Category Only', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Navigate only within the same product category.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_thumbnail',
				array(
					'label'        => __( 'Show Thumbnail', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_responsive_control(
				'thumbnail_size',
				array(
					'label'      => __( 'Thumbnail Size', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 30,
							'max' => 150,
						),
					),
					'default'    => array(
						'size' => 60,
						'unit' => 'px',
					),
					'condition'  => array(
						'show_thumbnail' => 'yes',
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-nav-thumbnail img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'show_price',
				array(
					'label'        => __( 'Show Price', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'popup_preview',
				array(
					'label'        => __( 'Popup Preview', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Show product preview on hover.', 'magical-products-display' ),
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
		// Container Style.
		$this->start_controls_section(
			'section_style_container',
			array(
				'label' => __( 'Container', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'container_layout',
			array(
				'label'   => __( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'row'    => __( 'Horizontal', 'magical-products-display' ),
					'column' => __( 'Vertical', 'magical-products-display' ),
				),
				'default' => 'row',
			)
		);

		$this->add_responsive_control(
			'container_justify',
			array(
				'label'     => __( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start'    => array(
						'title' => __( 'Start', 'magical-products-display' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'        => array(
						'title' => __( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'      => array(
						'title' => __( 'End', 'magical-products-display' ),
						'icon'  => 'eicon-h-align-right',
					),
					'space-between' => array(
						'title' => __( 'Space Between', 'magical-products-display' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-navigation' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_gap',
			array(
				'label'      => __( 'Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-product-navigation' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Nav Link Style.
		$this->start_controls_section(
			'section_style_link',
			array(
				'label' => __( 'Navigation Link', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_link_style' );

		$this->start_controls_tab(
			'tab_link_normal',
			array(
				'label' => __( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-nav-link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-nav-link' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_link_hover',
			array(
				'label' => __( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-nav-link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-nav-link:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'link_typography',
				'selector'  => '{{WRAPPER}} .mpd-nav-link',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'link_border',
				'selector' => '{{WRAPPER}} .mpd-nav-link',
			)
		);

		$this->add_responsive_control(
			'link_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-nav-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'link_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'link_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-nav-link',
			)
		);

		$this->end_controls_section();

		// Icon Style.
		$this->start_controls_section(
			'section_style_icon',
			array(
				'label'     => __( 'Icon', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_icon' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-nav-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-nav-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-nav-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-nav-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_hover_color',
			array(
				'label'     => __( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-nav-link:hover .mpd-nav-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-nav-link:hover .mpd-nav-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
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
				'selectors'  => array(
					'{{WRAPPER}} .mpd-nav-prev .mpd-nav-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-nav-next .mpd-nav-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Product Title Style.
		$this->start_controls_section(
			'section_style_product_title',
			array(
				'label'     => __( 'Product Title', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'product_title_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-nav-product-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_title_typography',
				'selector' => '{{WRAPPER}} .mpd-nav-product-title',
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
		$product = $this->get_current_product();

		if ( ! $product ) {
			$this->render_editor_placeholder(
				__( 'Product Navigation', 'magical-products-display' ),
				__( 'This widget displays product navigation. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		$same_category = $this->is_pro() && 'yes' === ( $settings['same_category'] ?? '' );

		// Get previous and next products.
		$prev_product = $this->get_adjacent_product( $product, 'prev', $same_category );
		$next_product = $this->get_adjacent_product( $product, 'next', $same_category );

		if ( ! $prev_product && ! $next_product ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				$this->render_editor_placeholder(
					__( 'Product Navigation', 'magical-products-display' ),
					__( 'No adjacent products found for navigation.', 'magical-products-display' )
				);
			}
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-product-navigation' );
		$this->add_render_attribute( 'wrapper', 'class', 'mpd-nav-layout-' . ( $settings['container_layout'] ?? 'row' ) );

		// Pro: Popup preview.
		if ( $this->is_pro() && 'yes' === ( $settings['popup_preview'] ?? '' ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'mpd-nav-popup-preview' );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( 'yes' === $settings['show_prev'] && $prev_product ) : ?>
				<?php $this->render_nav_link( $prev_product, 'prev', $settings ); ?>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_next'] && $next_product ) : ?>
				<?php $this->render_nav_link( $next_product, 'next', $settings ); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get adjacent product.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product       Current product.
	 * @param string      $direction     Direction: 'prev' or 'next'.
	 * @param bool        $same_category Whether to limit to same category.
	 * @return \WC_Product|null Adjacent product or null.
	 */
	private function get_adjacent_product( $product, $direction, $same_category = false ) {
		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		);

		if ( 'prev' === $direction ) {
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
			$args['date_query'] = array(
				array(
					'before' => get_post_field( 'post_date', $product->get_id() ),
				),
			);
		} else {
			$args['orderby'] = 'date';
			$args['order']   = 'ASC';
			$args['date_query'] = array(
				array(
					'after' => get_post_field( 'post_date', $product->get_id() ),
				),
			);
		}

		if ( $same_category ) {
			$categories = wc_get_product_term_ids( $product->get_id(), 'product_cat' );
			if ( ! empty( $categories ) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $categories,
					),
				);
			}
		}

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			return wc_get_product( $query->posts[0] );
		}

		return null;
	}

	/**
	 * Render navigation link.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $nav_product The product to navigate to.
	 * @param string      $direction   Direction: 'prev' or 'next'.
	 * @param array       $settings    Widget settings.
	 * @return void
	 */
	private function render_nav_link( $nav_product, $direction, $settings ) {
		$label        = 'prev' === $direction ? $settings['prev_label'] : $settings['next_label'];
		$icon         = 'prev' === $direction ? $settings['prev_icon'] : $settings['next_icon'];
		$show_icon    = 'yes' === $settings['show_icon'] && ! empty( $icon['value'] );
		$show_title   = 'yes' === $settings['show_title'];
		$show_thumb   = $this->is_pro() && 'yes' === ( $settings['show_thumbnail'] ?? '' );
		$show_price   = $this->is_pro() && 'yes' === ( $settings['show_price'] ?? '' );
		$show_popup   = $this->is_pro() && 'yes' === ( $settings['popup_preview'] ?? '' );
		?>
		<a href="<?php echo esc_url( get_permalink( $nav_product->get_id() ) ); ?>" class="mpd-nav-link mpd-nav-<?php echo esc_attr( $direction ); ?>">
			<?php if ( 'prev' === $direction && $show_icon ) : ?>
				<span class="mpd-nav-icon">
					<?php \Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) ); ?>
				</span>
			<?php endif; ?>

			<?php if ( $show_thumb ) : ?>
				<span class="mpd-nav-thumbnail">
					<?php echo wp_kses_post( $nav_product->get_image( 'woocommerce_gallery_thumbnail' ) ); ?>
				</span>
			<?php endif; ?>

			<span class="mpd-nav-content">
				<?php if ( ! empty( $label ) ) : ?>
					<span class="mpd-nav-label"><?php echo esc_html( $label ); ?></span>
				<?php endif; ?>

				<?php if ( $show_title ) : ?>
					<span class="mpd-nav-product-title"><?php echo esc_html( $nav_product->get_name() ); ?></span>
				<?php endif; ?>

				<?php if ( $show_price ) : ?>
					<span class="mpd-nav-product-price"><?php echo wp_kses_post( $nav_product->get_price_html() ); ?></span>
				<?php endif; ?>
			</span>

			<?php if ( 'next' === $direction && $show_icon ) : ?>
				<span class="mpd-nav-icon">
					<?php \Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) ); ?>
				</span>
			<?php endif; ?>

			<?php if ( $show_popup ) : ?>
				<div class="mpd-nav-popup mpd-nav-popup-<?php echo esc_attr( $direction ); ?>">
					<div class="mpd-nav-popup-image">
						<?php echo wp_kses_post( $nav_product->get_image( 'woocommerce_thumbnail' ) ); ?>
					</div>
					<div class="mpd-nav-popup-content">
						<h4 class="mpd-nav-popup-title"><?php echo esc_html( $nav_product->get_name() ); ?></h4>
						<?php if ( $nav_product->get_average_rating() ) : ?>
							<div class="mpd-nav-popup-rating">
								<?php echo wc_get_rating_html( $nav_product->get_average_rating(), $nav_product->get_rating_count() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php endif; ?>
						<div class="mpd-nav-popup-price">
							<?php echo wp_kses_post( $nav_product->get_price_html() ); ?>
						</div>
						<?php if ( $nav_product->get_short_description() ) : ?>
							<div class="mpd-nav-popup-excerpt">
								<?php echo wp_kses_post( wp_trim_words( $nav_product->get_short_description(), 15 ) ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</a>
		<?php
	}
}
