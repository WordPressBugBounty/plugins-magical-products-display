<?php
/**
 * Price Filter Widget
 *
 * Displays a price range filter for WooCommerce products.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\ShopArchive;

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
 * Class Price_Filter
 *
 * @since 2.0.0
 */
class Price_Filter extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_SHOP_ARCHIVE;

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	protected $widget_icon = 'eicon-price-list';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-price-filter';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Price Filter', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'price', 'filter', 'range', 'slider', 'products', 'shop', 'magical-products-display' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-shop-archive-widgets' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'wc-price-slider', 'mpd-shop-archive' );
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
				'label' => esc_html__( 'Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
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
			'title_text',
			array(
				'label'     => esc_html__( 'Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Filter by Price', 'magical-products-display' ),
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'     => esc_html__( 'Title HTML Tag', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h3',
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'filter_style',
			array(
				'label'   => esc_html__( 'Filter Style', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slider',
				'options' => array(
					'slider' => esc_html__( 'Range Slider', 'magical-products-display' ),
					'inputs' => esc_html__( 'Min/Max Inputs', 'magical-products-display' ),
					'both'   => esc_html__( 'Slider + Inputs', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'ajax_filter_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<div style="background: #e8f4fc; padding: 10px; border-radius: 4px; font-size: 12px;"><strong>' . esc_html__( 'Auto-refresh Enabled', 'magical-products-display' ) . '</strong><br>' . esc_html__( 'Products will automatically filter when the price range changes. No button needed.', 'magical-products-display' ) . '</div>',
				'content_classes' => 'mpd-ajax-notice',
			)
		);

		$this->add_control(
			'show_price_range',
			array(
				'label'        => esc_html__( 'Show Price Range Text', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'price_range_text',
			array(
				'label'       => esc_html__( 'Price Range Format', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Price: {min} — {max}', 'magical-products-display' ),
				'description' => esc_html__( 'Use {min} and {max} as placeholders.', 'magical-products-display' ),
				'condition'   => array(
					'show_price_range' => 'yes',
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
		// Title Style Section.
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => esc_html__( 'Title', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-price-filter__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-price-filter__title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-price-filter__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Slider Style Section.
		$this->start_controls_section(
			'section_slider_style',
			array(
				'label'     => esc_html__( 'Range Slider', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'filter_style!' => 'inputs',
				),
			)
		);

		$this->add_control(
			'slider_bar_color',
			array(
				'label'     => esc_html__( 'Bar Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-price-filter .ui-slider' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'slider_range_color',
			array(
				'label'     => esc_html__( 'Range Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-price-filter .ui-slider .ui-slider-range' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'slider_handle_color',
			array(
				'label'     => esc_html__( 'Handle Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-price-filter .ui-slider .ui-slider-handle' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'slider_bar_height',
			array(
				'label'      => esc_html__( 'Bar Height', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-price-filter .ui-slider' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'slider_handle_size',
			array(
				'label'      => esc_html__( 'Handle Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-price-filter .ui-slider .ui-slider-handle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; top: calc(-{{SIZE}}{{UNIT}} / 2 + 4px); margin-left: calc(-{{SIZE}}{{UNIT}} / 2);',
				),
			)
		);

		$this->add_responsive_control(
			'slider_handle_border_radius',
			array(
				'label'      => esc_html__( 'Handle Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-price-filter .ui-slider .ui-slider-handle' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'slider_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-price-filter .price_slider_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Inputs Style Section.
		$this->start_controls_section(
			'section_inputs_style',
			array(
				'label'     => esc_html__( 'Price Inputs', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'filter_style!' => 'slider',
				),
			)
		);

		$this->add_control(
			'inputs_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-price-filter__input' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'inputs_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-price-filter__input' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'inputs_typography',
				'selector' => '{{WRAPPER}} .mpd-price-filter__input',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'inputs_border',
				'selector' => '{{WRAPPER}} .mpd-price-filter__input',
			)
		);

		$this->add_responsive_control(
			'inputs_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-price-filter__input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'inputs_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-price-filter__input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'inputs_width',
			array(
				'label'      => esc_html__( 'Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 200,
					),
					'%'  => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-price-filter__input' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Price Range Text Style Section.
		$this->start_controls_section(
			'section_price_range_style',
			array(
				'label'     => esc_html__( 'Price Range Text', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_price_range' => 'yes',
				),
			)
		);

		$this->add_control(
			'price_range_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-price-filter__amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_range_typography',
				'selector' => '{{WRAPPER}} .mpd-price-filter__amount',
			)
		);

		$this->add_responsive_control(
			'price_range_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-price-filter__amount' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		// Get prices for filter.
		$prices = $this->get_filtered_prices();

		if ( empty( $prices['min'] ) && empty( $prices['max'] ) ) {
			// Show placeholder in editor.
			if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				?>
				<div class="mpd-price-filter">
					<?php if ( 'yes' === $settings['show_title'] ) : ?>
						<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mpd-price-filter__title">
							<?php echo esc_html( $settings['title_text'] ); ?>
						</<?php echo esc_attr( $settings['title_tag'] ); ?>>
					<?php endif; ?>
					<p class="mpd-price-filter__notice"><?php esc_html_e( 'Price filter will appear when products are available.', 'magical-products-display' ); ?></p>
				</div>
				<?php
			}
			return;
		}

		$min_price = $prices['min'];
		$max_price = $prices['max'];

		// Get current filter values.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_min_price = isset( $_GET['min_price'] ) ? floor( floatval( wp_unslash( $_GET['min_price'] ) ) ) : $min_price;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_max_price = isset( $_GET['max_price'] ) ? ceil( floatval( wp_unslash( $_GET['max_price'] ) ) ) : $max_price;

		// Form action URL.
		$form_action = $this->get_current_page_url();
		?>
		<div class="mpd-price-filter widget_price_filter">
			<?php if ( 'yes' === $settings['show_title'] ) : ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mpd-price-filter__title">
					<?php echo esc_html( $settings['title_text'] ); ?>
				</<?php echo esc_attr( $settings['title_tag'] ); ?>>
			<?php endif; ?>

			<form method="get" action="<?php echo esc_url( $form_action ); ?>" class="mpd-price-filter__form">
				<?php if ( in_array( $settings['filter_style'], array( 'slider', 'both' ), true ) ) : ?>
					<?php
					// In Elementor editor, show a static preview slider instead of the WC-initialized one.
					$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();
					?>
					<div class="price_slider_wrapper">
						<?php if ( $is_editor ) : ?>
							<!-- Static preview slider for Elementor editor -->
							<div class="mpd-price-filter__preview-slider ui-slider ui-slider-horizontal">
								<div class="ui-slider-range ui-widget-header" style="left: 15%; width: 60%;"></div>
								<span class="ui-slider-handle" style="left: 15%;"></span>
								<span class="ui-slider-handle" style="left: 75%;"></span>
							</div>
						<?php else : ?>
							<div class="price_slider" style="display:none;"></div>
						<?php endif; ?>
						<div class="price_slider_amount" data-step="<?php echo esc_attr( apply_filters( 'woocommerce_price_filter_widget_step', 10 ) ); ?>">
							<input type="hidden" id="min_price" name="min_price" value="<?php echo esc_attr( $current_min_price ); ?>" data-min="<?php echo esc_attr( $min_price ); ?>" placeholder="<?php echo esc_attr__( 'Min price', 'magical-products-display' ); ?>" />
							<input type="hidden" id="max_price" name="max_price" value="<?php echo esc_attr( $current_max_price ); ?>" data-max="<?php echo esc_attr( $max_price ); ?>" placeholder="<?php echo esc_attr__( 'Max price', 'magical-products-display' ); ?>" />

							<?php if ( 'yes' === $settings['show_price_range'] ) : ?>
								<div class="mpd-price-filter__amount price_label"<?php echo esc_attr( $is_editor ? '' : ' style="display:none;"' ); ?>>
									<?php
									if ( $is_editor ) {
										// Show sample values in editor.
										$from_display = wc_price( $min_price );
										$to_display   = wc_price( $max_price );
										$price_range_text = str_replace(
											array( '{min}', '{max}' ),
											array( '<span class="from">' . $from_display . '</span>', '<span class="to">' . $to_display . '</span>' ),
											$settings['price_range_text']
										);
									} else {
										$price_range_text = str_replace(
											array( '{min}', '{max}' ),
											array( '<span class="from"></span>', '<span class="to"></span>' ),
											$settings['price_range_text']
										);
									}
									echo wp_kses_post( $price_range_text );
									?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( in_array( $settings['filter_style'], array( 'inputs', 'both' ), true ) ) : ?>
					<div class="mpd-price-filter__inputs">
						<div class="mpd-price-filter__input-wrap">
							<label for="mpd-min-price-<?php echo esc_attr( $this->get_id() ); ?>" class="screen-reader-text">
								<?php esc_html_e( 'Minimum price', 'magical-products-display' ); ?>
							</label>
							<input 
								type="number" 
								id="mpd-min-price-<?php echo esc_attr( $this->get_id() ); ?>"
								class="mpd-price-filter__input mpd-price-filter__input--min" 
								name="min_price" 
								value="<?php echo esc_attr( $current_min_price ); ?>"
								min="<?php echo esc_attr( $min_price ); ?>"
								max="<?php echo esc_attr( $max_price ); ?>"
								placeholder="<?php esc_attr_e( 'Min', 'magical-products-display' ); ?>"
							/>
						</div>
						<span class="mpd-price-filter__separator">—</span>
						<div class="mpd-price-filter__input-wrap">
							<label for="mpd-max-price-<?php echo esc_attr( $this->get_id() ); ?>" class="screen-reader-text">
								<?php esc_html_e( 'Maximum price', 'magical-products-display' ); ?>
							</label>
							<input 
								type="number" 
								id="mpd-max-price-<?php echo esc_attr( $this->get_id() ); ?>"
								class="mpd-price-filter__input mpd-price-filter__input--max" 
								name="max_price" 
								value="<?php echo esc_attr( $current_max_price ); ?>"
								min="<?php echo esc_attr( $min_price ); ?>"
								max="<?php echo esc_attr( $max_price ); ?>"
								placeholder="<?php esc_attr_e( 'Max', 'magical-products-display' ); ?>"
							/>
						</div>
					</div>
				<?php endif; ?>

				<?php wc_query_string_form_fields( null, array( 'min_price', 'max_price', 'paged' ) ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Get filtered price ranges using WooCommerce native approach.
	 *
	 * @since 2.0.0
	 *
	 * @return array Price ranges.
	 */
	protected function get_filtered_prices() {
		global $wpdb;

		// Use direct SQL for performance instead of loading all products.
		$sql = "SELECT MIN( CAST( pm.meta_value AS DECIMAL(10,2) ) ) AS min_price,
		               MAX( CAST( pm.meta_value AS DECIMAL(10,2) ) ) AS max_price
		        FROM {$wpdb->postmeta} pm
		        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
		        WHERE pm.meta_key = '_price'
		          AND pm.meta_value != ''
		          AND pm.meta_value IS NOT NULL
		          AND p.post_type = 'product'
		          AND p.post_status = 'publish'";

		// Add category filter if on category page.
		if ( is_product_category() ) {
			$term_id = get_queried_object_id();
			$sql .= $wpdb->prepare(
				" AND p.ID IN ( SELECT object_id FROM {$wpdb->term_relationships} tr
				  INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
				  WHERE tt.taxonomy = 'product_cat' AND tt.term_id = %d )",
				$term_id
			);
		}

		// Add tag filter if on tag page.
		if ( is_product_tag() ) {
			$term_id = get_queried_object_id();
			$sql .= $wpdb->prepare(
				" AND p.ID IN ( SELECT object_id FROM {$wpdb->term_relationships} tr
				  INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
				  WHERE tt.taxonomy = 'product_tag' AND tt.term_id = %d )",
				$term_id
			);
		}

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Dynamic parts use $wpdb->prepare above.
		$result = $wpdb->get_row( $sql );

		if ( ! $result || null === $result->min_price ) {
			return array(
				'min' => 0,
				'max' => 0,
			);
		}

		return array(
			'min' => floor( floatval( $result->min_price ) ),
			'max' => ceil( floatval( $result->max_price ) ),
		);
	}

	/**
	 * Get current page URL.
	 *
	 * @since 2.0.0
	 *
	 * @return string Current page URL.
	 */
	protected function get_current_page_url() {
		if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url();
		} elseif ( is_shop() ) {
			$link = get_permalink( wc_get_page_id( 'shop' ) );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} else {
			$queried_object = get_queried_object();
			if ( $queried_object && isset( $queried_object->taxonomy ) ) {
				$link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			} else {
				$link = get_permalink( wc_get_page_id( 'shop' ) );
			}
		}

		if ( is_wp_error( $link ) ) {
			$link = get_permalink( wc_get_page_id( 'shop' ) );
		}

		return $link;
	}
}
