<?php
/**
 * Product Rating Widget
 *
 * Displays the product rating on single product pages.
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
 * Class Product_Rating
 *
 * @since 2.0.0
 */
class Product_Rating extends Widget_Base {

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
		return 'mpd-product-rating';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Product Rating', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-rating';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'rating', 'review', 'star', 'woocommerce', 'single' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array();
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		// Elementor Font Awesome is loaded by default
		return array( 'mpd-single-product', 'elementor-icons-fa-solid', 'elementor-icons-fa-regular' );
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
				'label' => __( 'Rating', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_review_count',
			array(
				'label'        => __( 'Show Review Count', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_average_number',
			array(
				'label'        => __( 'Show Average Number', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'link_to_reviews',
			array(
				'label'        => __( 'Link to Reviews', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-rating' => 'align-items: {{VALUE}};',
				),
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Custom Icons & Distribution Bar', 'magical-products-display' ) );
		}
			$this->add_control(
				'rating_icon',
				array(
					'label'   => __( 'Rating Icon', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'star'     => __( 'Star', 'magical-products-display' ),
						'star-o'   => __( 'Star Outline', 'magical-products-display' ),
						'heart'    => __( 'Heart', 'magical-products-display' ),
						'heart-o'  => __( 'Heart Outline', 'magical-products-display' ),
						'circle'   => __( 'Circle', 'magical-products-display' ),
						'circle-o' => __( 'Circle Outline', 'magical-products-display' ),
					),
					'default' => 'star',
				)
			);

			$this->add_control(
				'show_distribution',
				array(
					'label'        => __( 'Show Rating Distribution', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Show breakdown of 5/4/3/2/1 star ratings.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'distribution_position',
				array(
					'label'     => __( 'Distribution Position', 'magical-products-display' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'bottom' => __( 'Bottom', 'magical-products-display' ),
						'top'    => __( 'Top', 'magical-products-display' ),
					),
					'default'   => 'bottom',
					'condition' => array(
						'show_distribution' => 'yes',
					),
				)
			);

			$this->add_control(
				'distribution_hide_review_count',
				array(
					'label'        => __( 'Hide Review Count with Distribution', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Hide', 'magical-products-display' ),
					'label_off'    => __( 'Show', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Hide the review count text when distribution is shown.', 'magical-products-display' ),
					'condition'    => array(
						'show_distribution'  => 'yes',
						'show_review_count'  => 'yes',
					),
				)
			);

			$this->add_control(
				'distribution_hide_stars',
				array(
					'label'        => __( 'Hide Stars with Distribution', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Hide', 'magical-products-display' ),
					'label_off'    => __( 'Show', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Hide the star rating when distribution is shown.', 'magical-products-display' ),
					'condition'    => array(
						'show_distribution' => 'yes',
					),
				)
			);

			$this->add_responsive_control(
				'distribution_align',
				array(
					'label'     => __( 'Distribution Alignment', 'magical-products-display' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'flex-start' => array(
							'title' => __( 'Left', 'magical-products-display' ),
							'icon'  => 'eicon-text-align-left',
						),
						'center'     => array(
							'title' => __( 'Center', 'magical-products-display' ),
							'icon'  => 'eicon-text-align-center',
						),
						'flex-end'   => array(
							'title' => __( 'Right', 'magical-products-display' ),
							'icon'  => 'eicon-text-align-right',
						),
					),
					'selectors' => array(
						'{{WRAPPER}} .mpd-rating-distribution' => 'align-self: {{VALUE}};',
					),
					'condition' => array(
						'show_distribution' => 'yes',
					),
				)
			);

			$this->add_control(
				'no_rating_text',
				array(
					'label'       => __( 'No Rating Text', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'No reviews yet', 'magical-products-display' ),
					'placeholder' => __( 'Text to show when no ratings', 'magical-products-display' ),
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
			'section_style_stars',
			array(
				'label' => __( 'Stars', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'star_color',
			array(
				'label'     => __( 'Filled Star Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-rating .star-rating span:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-rating .mpd-star-filled' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'star_empty_color',
			array(
				'label'     => __( 'Empty Star Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-rating .star-rating:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-product-rating .mpd-star-empty' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'star_size',
			array(
				'label'      => __( 'Star Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-product-rating .star-rating' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-product-rating .mpd-custom-stars i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'star_spacing',
			array(
				'label'      => __( 'Star Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-product-rating .star-rating' => 'letter-spacing: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-product-rating .mpd-custom-stars i' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Review Count Style.
		$this->start_controls_section(
			'section_style_count',
			array(
				'label'     => __( 'Review Count', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_review_count' => 'yes',
				),
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-review-count' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-review-count a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'count_hover_color',
			array(
				'label'     => __( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-review-count a:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'link_to_reviews' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_typography',
				'selector' => '{{WRAPPER}} .mpd-review-count',
			)
		);

		$this->add_responsive_control(
			'count_spacing',
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
					'{{WRAPPER}} .mpd-review-count' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Average Number Style.
		$this->start_controls_section(
			'section_style_average',
			array(
				'label'     => __( 'Average Number', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_average_number' => 'yes',
				),
			)
		);

		$this->add_control(
			'average_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-average-rating' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'average_typography',
				'selector' => '{{WRAPPER}} .mpd-average-rating',
			)
		);

		$this->end_controls_section();

		// Rating Distribution Style - Pro Only.
		if ( $this->is_pro() ) {
			$this->start_controls_section(
				'section_style_distribution',
				array(
					'label'     => __( 'Rating Distribution', 'magical-products-display' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'show_distribution' => 'yes',
					),
				)
			);

			$this->add_responsive_control(
				'distribution_width',
				array(
					'label'      => __( 'Width', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min' => 150,
							'max' => 500,
						),
						'%'  => array(
							'min' => 50,
							'max' => 100,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-rating-distribution' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'distribution_spacing',
				array(
					'label'      => __( 'Top Spacing', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-rating-distribution' => 'margin-top: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'distribution_bar_heading',
				array(
					'label'     => __( 'Progress Bar', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'distribution_bar_color',
				array(
					'label'     => __( 'Bar Fill Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-distribution-bar-fill' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'distribution_bar_bg_color',
				array(
					'label'     => __( 'Bar Background Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-distribution-bar' => 'background-color: {{VALUE}};',
					),
				)
			);

			$this->add_responsive_control(
				'distribution_bar_height',
				array(
					'label'      => __( 'Bar Height', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 4,
							'max' => 30,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-distribution-bar' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'distribution_bar_radius',
				array(
					'label'      => __( 'Bar Border Radius', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 20,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-distribution-bar, {{WRAPPER}} .mpd-distribution-bar-fill' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'distribution_text_heading',
				array(
					'label'     => __( 'Labels & Count', 'magical-products-display' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'distribution_label_color',
				array(
					'label'     => __( 'Label Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-distribution-label' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'distribution_star_color',
				array(
					'label'     => __( 'Star Icon Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .mpd-dist-star' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'distribution_count_color',
				array(
					'label'     => __( 'Count Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-distribution-count' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'distribution_label_typography',
					'label'    => __( 'Label Typography', 'magical-products-display' ),
					'selector' => '{{WRAPPER}} .mpd-distribution-label',
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'distribution_count_typography',
					'label'    => __( 'Count Typography', 'magical-products-display' ),
					'selector' => '{{WRAPPER}} .mpd-distribution-count',
				)
			);

			$this->add_responsive_control(
				'distribution_row_gap',
				array(
					'label'      => __( 'Row Gap', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 20,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-rating-distribution' => 'gap: {{SIZE}}{{UNIT}};',
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
		$product = $this->get_current_product();

		if ( ! $product ) {
			$this->render_editor_placeholder(
				__( 'Product Rating', 'magical-products-display' ),
				__( 'This widget displays the product rating. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		// Check if reviews are enabled.
		if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
			return;
		}

		$rating_count = $product->get_rating_count();
		$average      = $product->get_average_rating();

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-product-rating' );

		// Check Pro distribution settings.
		$show_distribution      = $this->is_pro() && 'yes' === ( $settings['show_distribution'] ?? '' );
		$distribution_position  = $settings['distribution_position'] ?? 'bottom';
		$hide_stars_with_dist   = 'yes' === ( $settings['distribution_hide_stars'] ?? '' );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( $rating_count > 0 ) {
				// Pro: Distribution bar at top position.
				if ( $show_distribution && 'top' === $distribution_position ) {
					$this->render_distribution( $product, $settings );
				}
				
				// Show stars (hide if distribution is shown and hide stars option is enabled).
				$should_show_stars = ! ( $show_distribution && $hide_stars_with_dist );

				// Rating row wrapper.
				echo '<div class="mpd-rating-row">';

				// Show average number.
				if ( 'yes' === $settings['show_average_number'] ) {
					printf(
						'<span class="mpd-average-rating">%s</span>',
						esc_html( number_format( $average, 1 ) )
					);
				}

					if ( $should_show_stars ) {
						if ( $this->is_pro() && ! empty( $settings['rating_icon'] ) ) {
							$this->render_custom_stars( $average, $settings['rating_icon'] );
						} else {
							echo wc_get_rating_html( $average, $rating_count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
					}


				// Show review count (hide if distribution is shown and hide option is enabled).
				$should_show_review_count = 'yes' === $settings['show_review_count'];
				

				if ( $should_show_review_count ) {
					$count_text = sprintf(
						/* translators: %s: number of reviews */
						_n( '(%s review)', '(%s reviews)', $rating_count, 'magical-products-display' ),
						$rating_count
					);

					if ( 'yes' === $settings['link_to_reviews'] ) {
						printf(
							'<span class="mpd-review-count"><a href="%s">%s</a></span>',
							esc_url( $product->get_permalink() . '#reviews' ),
							esc_html( $count_text )
						);
					} else {
						printf( '<span class="mpd-review-count">%s</span>', esc_html( $count_text ) );
					}
				}

				echo '</div>'; // Close .mpd-rating-row.

				// Pro: Distribution bar at bottom position (default).
				if ( $show_distribution && 'bottom' === $distribution_position ) {
					$this->render_distribution( $product , $settings );
				}
			} else {
				// No ratings.
				$no_rating_text = $this->is_pro()
					? ( $settings['no_rating_text'] ?? __( 'No reviews yet', 'magical-products-display' ) )
					: __( 'No reviews yet', 'magical-products-display' );

				printf( '<span class="mpd-no-rating">%s</span>', esc_html( $no_rating_text ) );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render custom star icons.
	 *
	 * @since 2.0.0
	 *
	 * @param float  $average Average rating.
	 * @param string $icon    Icon type.
	 * @return void
	 */
	private function render_custom_stars( $average, $icon ) {
		// Map icon types to Font Awesome classes: [filled_class, empty_class]
		// Using Font Awesome 5 classes (fas = solid, far = regular/outline)
		$icon_class_map = array(
			'star'     => array( 'fas fa-star', 'far fa-star' ),           // Filled stars, outline empty
			'star-o'   => array( 'far fa-star', 'far fa-star' ),           // All outline (color differentiates)
			'heart'    => array( 'fas fa-heart', 'far fa-heart' ),         // Filled hearts, outline empty
			'heart-o'  => array( 'far fa-heart', 'far fa-heart' ),         // All outline (color differentiates)
			'circle'   => array( 'fas fa-circle', 'far fa-circle' ),       // Filled circles, outline empty
			'circle-o' => array( 'far fa-circle', 'far fa-circle' ),       // All outline (color differentiates)
		);

		$classes = $icon_class_map[ $icon ] ?? array( 'fas fa-star', 'far fa-star' );
		$filled_class = $classes[0];
		$empty_class  = $classes[1];

		echo '<span class="mpd-custom-stars">';
		for ( $i = 1; $i <= 5; $i++ ) {
			if ( $i <= $average ) {
				printf( '<i class="%s mpd-star-filled"></i>', esc_attr( $filled_class ) );
			} elseif ( $i - 0.5 <= $average ) {
				// Half star - use filled icon with half styling
				printf( '<i class="%s mpd-star-half"></i>', esc_attr( $filled_class ) );
			} else {
				printf( '<i class="%s mpd-star-empty"></i>', esc_attr( $empty_class ) );
			}
		}
		echo '</span>';
	}

	/**
	 * Render rating distribution.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product The product object.
	 * @return void
	 */
	private function render_distribution( $product, $settings ) {
		$counts = $product->get_rating_counts();
		$total  = array_sum( $counts );
		$hide_review_with_dist  = 'yes' === ( $settings['distribution_hide_review_count'] ?? '' );

		if ( $total <= 0 ) {
			return;
		}
		?>
		<div class="mpd-rating-distribution">
			<?php
			for ( $i = 5; $i >= 1; $i-- ) {
				$count      = isset( $counts[ $i ] ) ? $counts[ $i ] : 0;
				$percentage = ( $count / $total ) * 100;
				?>
				<div class="mpd-distribution-row">
					<span class="mpd-distribution-label"><?php echo esc_html( $i ); ?> <span class="mpd-dist-star">★</span></span>
					<div class="mpd-distribution-bar">
						<div class="mpd-distribution-bar-fill" style="width: <?php echo esc_attr( $percentage ); ?>%;"></div>
					</div>
					<?php if(!$hide_review_with_dist): ?>
					<span class="mpd-distribution-count"><?php echo esc_html( $count ); ?></span>
					<?php endif; ?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
}
