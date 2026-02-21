<?php
/**
 * Recently Viewed Widget
 *
 * Displays recently viewed products with carousel and cookie-based tracking.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\GlobalWidgets;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use MPD\MagicalShopBuilder\Traits\Product_Query;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Recently_Viewed
 *
 * @since 2.0.0
 */
class Recently_Viewed extends Widget_Base {

	use Product_Query;

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
	protected $widget_icon = 'eicon-history';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-recently-viewed';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Recently Viewed', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'recently', 'viewed', 'products', 'history', 'magical-products-display' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-global-widgets', 'swiper' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'mpd-global-widgets', 'mg-swiper' );
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
				'label' => esc_html__( 'Recently Viewed', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Recently Viewed', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
			)
		);

		$this->add_control(
			'products_count',
			array(
				'label'   => esc_html__( 'Products Count', 'magical-products-display' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 20,
			)
		);

		$this->add_control(
			'cookie_duration',
			array(
				'label'       => esc_html__( 'Cookie Duration (days)', 'magical-products-display' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 30,
				'min'         => 1,
				'max'         => 365,
				'description' => esc_html__( 'How long to remember viewed products.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'exclude_current',
			array(
				'label'        => esc_html__( 'Exclude Current Product', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Exclude the current product from the list on single product pages.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'empty_message',
			array(
				'label'   => esc_html__( 'Empty Message', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'No products viewed yet.', 'magical-products-display' ),
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
				'default'      => '',
			)
		);

		$this->end_controls_section();

		// Layout Section.
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid'     => esc_html__( 'Grid', 'magical-products-display' ),
					'carousel' => esc_html__( 'Carousel', 'magical-products-display' ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'     => esc_html__( 'Columns', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'options'   => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'condition' => array(
					'layout' => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'slides_per_view',
			array(
				'label'     => esc_html__( 'Slides Per View', 'magical-products-display' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 4,
				'min'       => 1,
				'max'       => 10,
				'condition' => array(
					'layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'slides_to_scroll',
			array(
				'label'     => esc_html__( 'Slides to Scroll', 'magical-products-display' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'condition' => array(
					'layout' => 'carousel',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'magical-products-display' ),
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
					'{{WRAPPER}} .mpd-recently-viewed__grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Product Elements Section.
		$this->start_controls_section(
			'section_product_elements',
			array(
				'label' => esc_html__( 'Product Elements', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'        => esc_html__( 'Show Image', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'image_size',
			array(
				'label'     => esc_html__( 'Image Size', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'woocommerce_thumbnail',
				'options'   => $this->get_image_sizes(),
				'condition' => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Show Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_price',
			array(
				'label'        => esc_html__( 'Show Price', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => esc_html__( 'Show Rating', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_add_to_cart',
			array(
				'label'        => esc_html__( 'Show Add to Cart', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		// Carousel Options Section.
		$this->start_controls_section(
			'section_carousel_options',
			array(
				'label'     => esc_html__( 'Carousel Options', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'layout' => 'carousel',
				),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => esc_html__( 'Autoplay', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => esc_html__( 'Autoplay Speed (ms)', 'magical-products-display' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'min'       => 1000,
				'max'       => 20000,
				'step'      => 500,
				'condition' => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'        => esc_html__( 'Loop', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_navigation',
			array(
				'label'        => esc_html__( 'Show Navigation', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_pagination',
			array(
				'label'        => esc_html__( 'Show Pagination', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
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
		// Title Style Section.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Title', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-recently-viewed__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-recently-viewed__title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-recently-viewed__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Product Card Style Section.
		$this->start_controls_section(
			'section_product_style',
			array(
				'label' => esc_html__( 'Product Card', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-recently-viewed__product' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'product_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-recently-viewed__product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'product_border',
				'selector' => '{{WRAPPER}} .mpd-recently-viewed__product',
			)
		);

		$this->add_responsive_control(
			'product_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-recently-viewed__product' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'product_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-recently-viewed__product',
			)
		);

		$this->end_controls_section();

		// Product Title Style Section.
		$this->start_controls_section(
			'section_product_title_style',
			array(
				'label'     => esc_html__( 'Product Title', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'product_title_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-recently-viewed__product-title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'product_title_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-recently-viewed__product-title a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'product_title_typography',
				'selector' => '{{WRAPPER}} .mpd-recently-viewed__product-title',
			)
		);

		$this->end_controls_section();

		// Price Style Section.
		$this->start_controls_section(
			'section_price_style',
			array(
				'label'     => esc_html__( 'Price', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_price' => 'yes',
				),
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-recently-viewed__price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sale_price_color',
			array(
				'label'     => esc_html__( 'Sale Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-recently-viewed__price ins' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .mpd-recently-viewed__price',
			)
		);

		$this->end_controls_section();

		// Navigation Style Section.
		$this->start_controls_section(
			'section_navigation_style',
			array(
				'label'     => esc_html__( 'Navigation', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout'          => 'carousel',
					'show_navigation' => 'yes',
				),
			)
		);

		$this->add_control(
			'nav_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'nav_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'nav_size',
			array(
				'label'      => esc_html__( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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

		// Get recently viewed product IDs.
		$viewed_products = $this->get_recently_viewed_products( $settings );

		// Hide if empty.
		if ( empty( $viewed_products ) && 'yes' === $settings['hide_empty'] ) {
			return;
		}

		$widget_id = $this->get_id();
		$is_carousel = 'carousel' === $settings['layout'];
		?>
		<div class="mpd-recently-viewed" data-cookie-days="<?php echo esc_attr( $settings['cookie_duration'] ); ?>">
			<?php if ( ! empty( $settings['title'] ) ) : ?>
				<<?php echo esc_html( $settings['title_tag'] ); ?> class="mpd-recently-viewed__title">
					<?php echo esc_html( $settings['title'] ); ?>
				</<?php echo esc_html( $settings['title_tag'] ); ?>>
			<?php endif; ?>

			<?php if ( empty( $viewed_products ) ) : ?>
				<p class="mpd-recently-viewed__empty"><?php echo esc_html( $settings['empty_message'] ); ?></p>
			<?php else : ?>
				<?php if ( $is_carousel ) : ?>
					<?php $this->render_carousel( $viewed_products, $settings, $widget_id ); ?>
				<?php else : ?>
					<?php $this->render_grid( $viewed_products, $settings ); ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render grid layout.
	 *
	 * @since 2.0.0
	 *
	 * @param array $products Product IDs.
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_grid( $products, $settings ) {
		$columns = isset( $settings['columns'] ) ? $settings['columns'] : '4';
		?>
		<div class="mpd-recently-viewed__grid mpd-grid mpd-grid--cols-<?php echo esc_attr( $columns ); ?>">
			<?php foreach ( $products as $product_id ) : ?>
				<?php $this->render_product( $product_id, $settings ); ?>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Render carousel layout.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $products Product IDs.
	 * @param array  $settings Widget settings.
	 * @param string $widget_id Widget ID.
	 * @return void
	 */
	protected function render_carousel( $products, $settings, $widget_id ) {
		$slides_per_view = isset( $settings['slides_per_view'] ) ? $settings['slides_per_view'] : 4;
		$slides_per_view_tablet = isset( $settings['slides_per_view_tablet'] ) ? $settings['slides_per_view_tablet'] : 2;
		$slides_per_view_mobile = isset( $settings['slides_per_view_mobile'] ) ? $settings['slides_per_view_mobile'] : 1;

		$carousel_options = array(
			'slidesPerView'  => (int) $slides_per_view_mobile,
			'slidesPerGroup' => (int) $settings['slides_to_scroll'],
			'spaceBetween'   => isset( $settings['gap']['size'] ) ? (int) $settings['gap']['size'] : 20,
			'loop'           => 'yes' === $settings['loop'],
			'autoplay'       => 'yes' === $settings['autoplay'] ? array( 'delay' => (int) $settings['autoplay_speed'] ) : false,
			'navigation'     => 'yes' === $settings['show_navigation'] ? array(
				'nextEl' => '.mpd-recently-viewed-' . $widget_id . ' .swiper-button-next',
				'prevEl' => '.mpd-recently-viewed-' . $widget_id . ' .swiper-button-prev',
			) : false,
			'pagination'     => 'yes' === $settings['show_pagination'] ? array(
				'el'        => '.mpd-recently-viewed-' . $widget_id . ' .swiper-pagination',
				'clickable' => true,
			) : false,
			'breakpoints'    => array(
				768  => array(
					'slidesPerView' => (int) $slides_per_view_tablet,
				),
				1024 => array(
					'slidesPerView' => (int) $slides_per_view,
				),
			),
		);
		?>
		<div class="mpd-recently-viewed-<?php echo esc_attr( $widget_id ); ?> mpd-recently-viewed__carousel swiper" data-swiper='<?php echo wp_json_encode( $carousel_options ); ?>'>
			<div class="swiper-wrapper">
				<?php foreach ( $products as $product_id ) : ?>
					<div class="swiper-slide">
						<?php $this->render_product( $product_id, $settings ); ?>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( 'yes' === $settings['show_navigation'] ) : ?>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_pagination'] ) : ?>
				<div class="swiper-pagination"></div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render single product.
	 *
	 * @since 2.0.0
	 *
	 * @param int   $product_id Product ID.
	 * @param array $settings   Widget settings.
	 * @return void
	 */
	protected function render_product( $product_id, $settings ) {
		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return;
		}

		// Setup global product for WooCommerce template functions.
		global $post;
		$original_post = $post;
		$post = get_post( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		setup_postdata( $post );
		?>
		<div class="mpd-recently-viewed__product">
			<?php if ( 'yes' === $settings['show_image'] ) : ?>
				<div class="mpd-recently-viewed__product-image">
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
						<?php echo $product->get_image( $settings['image_size'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				</div>
			<?php endif; ?>

			<div class="mpd-recently-viewed__product-content">
				<?php if ( 'yes' === $settings['show_title'] ) : ?>
					<h4 class="mpd-recently-viewed__product-title">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<?php echo esc_html( $product->get_name() ); ?>
						</a>
					</h4>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_rating'] ) : ?>
					<div class="mpd-recently-viewed__rating">
						<?php
						$rating = $product->get_average_rating();
						$rating_percent = ( $rating / 5 ) * 100;
						?>
						<div class="star-rating" role="img" aria-label="<?php echo esc_attr( sprintf( __( 'Rated %s out of 5', 'magical-products-display' ), $rating ) ); ?>">
							<span style="width:<?php echo esc_attr( $rating_percent ); ?>%"></span>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_price'] ) : ?>
					<div class="mpd-recently-viewed__price">
						<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_add_to_cart'] ) : ?>
					<div class="mpd-recently-viewed__add-to-cart">
						<?php woocommerce_template_loop_add_to_cart( array( 'quantity' => 1 ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
		// Restore original post.
		$post = $original_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		if ( $original_post ) {
			setup_postdata( $original_post );
		} else {
			wp_reset_postdata();
		}
	}

	/**
	 * Get recently viewed products from cookie.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return array Product IDs.
	 */
	protected function get_recently_viewed_products( $settings ) {
		$viewed_products = array();

		// Get from cookie.
		if ( isset( $_COOKIE['woocommerce_recently_viewed'] ) ) {
			$viewed_products = array_filter( array_map( 'absint', explode( '|', sanitize_text_field( wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ) ) );
		}

		// For editor preview, show some products.
		if ( empty( $viewed_products ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => $settings['products_count'],
				'post_status'    => 'publish',
				'fields'         => 'ids',
			);

			$query = new \WP_Query( $args );
			$viewed_products = $query->posts;
		}

		// Exclude current product.
		if ( 'yes' === $settings['exclude_current'] && is_singular( 'product' ) ) {
			global $post;
			$viewed_products = array_diff( $viewed_products, array( $post->ID ) );
		}

		// Limit count.
		$viewed_products = array_slice( $viewed_products, 0, $settings['products_count'] );

		return $viewed_products;
	}

	/**
	 * Get image sizes.
	 *
	 * @since 2.0.0
	 *
	 * @return array Image sizes.
	 */
	protected function get_image_sizes() {
		$sizes = array();
		$registered_sizes = get_intermediate_image_sizes();

		foreach ( $registered_sizes as $size ) {
			$sizes[ $size ] = ucwords( str_replace( array( '_', '-' ), ' ', $size ) );
		}

		return $sizes;
	}
}
