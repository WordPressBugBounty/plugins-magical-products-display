<?php
/**
 * Product Reviews Widget
 *
 * Displays the product reviews on single product pages.
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
 * Class Product_Reviews
 *
 * @since 2.0.0
 */
class Product_Reviews extends Widget_Base {

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
		return 'mpd-product-reviews';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Product Reviews', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-review';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'reviews', 'comments', 'testimonial', 'woocommerce', 'single' );
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
				'label' => __( 'Reviews', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_review_form',
			array(
				'label'        => __( 'Show Review Form', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_avatar',
			array(
				'label'        => __( 'Show Avatar', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label'        => __( 'Show Date', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'reviews_per_page',
			array(
				'label'   => __( 'Reviews Per Page', 'magical-products-display' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 10,
				'min'     => 1,
				'max'     => 50,
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Review Form Customization & Filters', 'magical-products-display' ) );
		}
			$this->add_control(
				'show_filters',
				array(
					'label'        => __( 'Show Filters', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => __( 'Allow filtering reviews by star rating.', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'show_verified_badge',
				array(
					'label'        => __( 'Show Verified Badge', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);

			$this->add_control(
				'sort_options',
				array(
					'label'        => __( 'Show Sort Options', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'review_style',
				array(
					'label'   => __( 'Review Style', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'modern'   => __( 'Modern', 'magical-products-display' ),
						'classic'  => __( 'Classic', 'magical-products-display' ),
						'card'     => __( 'Card', 'magical-products-display' ),
						'minimal'  => __( 'Minimal', 'magical-products-display' ),
					),
					'default' => 'modern',
				)
			);

			$this->add_control(
				'layout',
				array(
					'label'   => __( 'Layout', 'magical-products-display' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'list'    => __( 'List', 'magical-products-display' ),
						'grid'    => __( 'Grid', 'magical-products-display' ),
						'masonry' => __( 'Masonry', 'magical-products-display' ),
					),
					'default' => 'list',
				)
			);

			$this->add_responsive_control(
				'grid_columns',
				array(
					'label'     => __( 'Columns', 'magical-products-display' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
					),
					'default'   => '2',
					'condition' => array(
						'layout!' => 'list',
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
		// Review Item Style.
		$this->start_controls_section(
			'section_style_review',
			array(
				'label' => __( 'Review Item', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'review_bg_color',
			array(
				'label'     => __( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-review-item, {{WRAPPER}} #reviews .commentlist li' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'review_border',
				'selector' => '{{WRAPPER}} .mpd-review-item, {{WRAPPER}} #reviews .commentlist li',
			)
		);

		$this->add_responsive_control(
			'review_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-review-item, {{WRAPPER}} #reviews .commentlist li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'review_padding',
			array(
				'label'      => __( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-review-item, {{WRAPPER}} #reviews .commentlist li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'review_spacing',
			array(
				'label'      => __( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-review-item, {{WRAPPER}} #reviews .commentlist li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'review_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-review-item, {{WRAPPER}} #reviews .commentlist li',
			)
		);

		$this->end_controls_section();

		// Author Name Style.
		$this->start_controls_section(
			'section_style_author',
			array(
				'label' => __( 'Author Name', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'author_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-review-author, {{WRAPPER}} #reviews .comment-text .woocommerce-review__author' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'author_typography',
				'selector' => '{{WRAPPER}} .mpd-review-author, {{WRAPPER}} #reviews .comment-text .woocommerce-review__author',
			)
		);

		$this->end_controls_section();

		// Review Content Style.
		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => __( 'Review Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-review-content, {{WRAPPER}} #reviews .comment-text .description p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .mpd-review-content, {{WRAPPER}} #reviews .comment-text .description p',
			)
		);

		$this->end_controls_section();

		// Date Style.
		$this->start_controls_section(
			'section_style_date',
			array(
				'label'     => __( 'Date', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'date_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-review-date, {{WRAPPER}} #reviews .comment-text .woocommerce-review__published-date' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'date_typography',
				'selector' => '{{WRAPPER}} .mpd-review-date, {{WRAPPER}} #reviews .comment-text .woocommerce-review__published-date',
			)
		);

		$this->end_controls_section();

		// Avatar Style.
		$this->start_controls_section(
			'section_style_avatar',
			array(
				'label'     => __( 'Avatar', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_avatar' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'avatar_size',
			array(
				'label'      => __( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-review-avatar img, {{WRAPPER}} #reviews .commentlist li img.avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'avatar_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-review-avatar img, {{WRAPPER}} #reviews .commentlist li img.avatar' => 'border-radius: {{SIZE}}{{UNIT}};',
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
		global $product, $post;

		$product = $this->get_current_product();

		if ( ! $product ) {
			$this->render_editor_placeholder(
				__( 'Product Reviews', 'magical-products-display' ),
				__( 'This widget displays product reviews. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-product-reviews' );

		// Add layout and style classes for Pro.
		if ( $this->is_pro() ) {
			if ( isset( $settings['layout'] ) ) {
				$this->add_render_attribute( 'wrapper', 'class', 'mpd-reviews-layout-' . $settings['layout'] );
			}
			if ( isset( $settings['review_style'] ) ) {
				$this->add_render_attribute( 'wrapper', 'class', 'mpd-reviews-style-' . $settings['review_style'] );
			}
		}

		// Setup global post for WooCommerce.
		$post = get_post( $product->get_id() );
		setup_postdata( $post );

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			// Pro: Filters and sort.
			if ( $this->is_pro() ) {
				if ( 'yes' === ( $settings['show_filters'] ?? '' ) || 'yes' === ( $settings['sort_options'] ?? '' ) ) {
					$this->render_filters_bar( $product, $settings );
				}
			}

			// In editor mode, render our own reviews template.
			if ( $this->is_editor_mode() ) {
				$this->render_editor_reviews( $product, $settings );
			} else {
				// Hide avatar if disabled.
				if ( 'yes' !== $settings['show_avatar'] ) {
					add_filter( 'woocommerce_review_gravatar_size', '__return_zero' );
				}

				// Filter for reviews per page.
				$reviews_per_page_cb = function() use ( $settings ) {
					return absint( $settings['reviews_per_page'] );
				};
				add_filter( 'comments_per_page', $reviews_per_page_cb );

				// WooCommerce reviews template.
				comments_template();

				// Restore filters.
				remove_filter( 'comments_per_page', $reviews_per_page_cb );

				// Restore avatar setting.
				if ( 'yes' !== $settings['show_avatar'] ) {
					remove_filter( 'woocommerce_review_gravatar_size', '__return_zero' );
				}
			}
			?>
		</div>
		<?php

		wp_reset_postdata();
	}

	/**
	 * Render reviews in editor mode.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  The product object.
	 * @param array       $settings Widget settings.
	 * @return void
	 */
	private function render_editor_reviews( $product, $settings ) {
		$review_count  = $product->get_review_count();
		$average       = $product->get_average_rating();
		?>
		<div id="reviews" class="woocommerce-Reviews">
			<div id="comments">
				<h2 class="woocommerce-Reviews-title">
					<?php
					printf(
						esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $review_count, 'magical-products-display' ) ),
						esc_html( $review_count ),
						'<span>' . esc_html( $product->get_name() ) . '</span>'
					);
					?>
				</h2>

				<?php if ( $review_count > 0 ) : ?>
					<ol class="commentlist">
						<?php
						$reviews = get_comments( array(
							'post_id' => $product->get_id(),
							'status'  => 'approve',
							'type'    => 'review',
							'number'  => absint( $settings['reviews_per_page'] ?? 5 ),
						) );

						foreach ( $reviews as $review ) :
							$rating = intval( get_comment_meta( $review->comment_ID, 'rating', true ) );
							?>
							<li class="review">
								<div class="comment_container">
									<?php if ( 'yes' === ( $settings['show_avatar'] ?? 'yes' ) ) : ?>
										<?php echo get_avatar( $review->comment_author_email, 60 ); ?>
									<?php endif; ?>
									<div class="comment-text">
										<?php if ( $rating ) : ?>
											<div class="star-rating" role="img" aria-label="<?php printf( esc_attr__( 'Rated %d out of 5', 'magical-products-display' ), $rating ); ?>">
												<?php echo wc_get_star_rating_html( $rating ); ?>
											</div>
										<?php endif; ?>
										<p class="meta">
											<strong class="woocommerce-review__author"><?php echo esc_html( $review->comment_author ); ?></strong>
											<?php if ( 'yes' === ( $settings['show_date'] ?? 'yes' ) ) : ?>
												<span class="woocommerce-review__dash">–</span>
												<time class="woocommerce-review__published-date" datetime="<?php echo esc_attr( get_comment_date( 'c', $review ) ); ?>">
													<?php echo esc_html( get_comment_date( wc_date_format(), $review ) ); ?>
												</time>
											<?php endif; ?>
										</p>
										<div class="description">
											<p><?php echo wp_kses_post( $review->comment_content ); ?></p>
										</div>
									</div>
								</div>
							</li>
						<?php endforeach; ?>
					</ol>
				<?php else : ?>
					<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'magical-products-display' ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( 'yes' === ( $settings['show_form'] ?? 'yes' ) ) : ?>
				<div id="review_form_wrapper">
					<div id="review_form">
						<div id="respond" class="comment-respond">
							<span id="reply-title" class="comment-reply-title">
								<?php
								if ( $review_count > 0 ) {
									esc_html_e( 'Add a review', 'magical-products-display' );
								} else {
									printf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'magical-products-display' ), esc_html( $product->get_name() ) );
								}
								?>
							</span>
							<p class="comment-notes">
								<span id="email-notes"><?php esc_html_e( 'Your email address will not be published.', 'magical-products-display' ); ?></span>
							</p>
							<p class="comment-form-rating">
								<label for="rating"><?php esc_html_e( 'Your rating', 'magical-products-display' ); ?> <span class="required">*</span></label>
								<span class="stars">
									<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
										<a class="star-<?php echo esc_attr( $i ); ?>" href="#"><?php echo esc_html( $i ); ?></a>
									<?php endfor; ?>
								</span>
							</p>
							<p class="comment-form-comment">
								<label for="comment"><?php esc_html_e( 'Your review', 'magical-products-display' ); ?> <span class="required">*</span></label>
								<textarea id="comment" name="comment" cols="45" rows="8" required></textarea>
							</p>
							<p class="form-submit">
								<input name="submit" type="submit" id="submit" class="submit" value="<?php esc_attr_e( 'Submit', 'magical-products-display' ); ?>">
							</p>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render filters bar (Pro).
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  The product object.
	 * @param array       $settings Widget settings.
	 * @return void
	 */
	private function render_filters_bar( $product, $settings ) {
		$counts = $product->get_rating_counts();
		?>
		<div class="mpd-reviews-toolbar">
			<?php if ( 'yes' === ( $settings['show_filters'] ?? '' ) && ! empty( $counts ) ) : ?>
				<div class="mpd-reviews-filters">
					<span class="mpd-filter-label"><?php esc_html_e( 'Filter:', 'magical-products-display' ); ?></span>
					<button class="mpd-filter-btn active" data-filter="all"><?php esc_html_e( 'All', 'magical-products-display' ); ?></button>
					<?php for ( $i = 5; $i >= 1; $i-- ) : ?>
						<?php if ( isset( $counts[ $i ] ) && $counts[ $i ] > 0 ) : ?>
							<button class="mpd-filter-btn" data-filter="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?> ★</button>
						<?php endif; ?>
					<?php endfor; ?>
				</div>
			<?php endif; ?>

			<?php if ( 'yes' === ( $settings['sort_options'] ?? '' ) ) : ?>
				<div class="mpd-reviews-sort">
					<label for="mpd-sort-reviews"><?php esc_html_e( 'Sort by:', 'magical-products-display' ); ?></label>
					<select id="mpd-sort-reviews" class="mpd-sort-select">
						<option value="newest"><?php esc_html_e( 'Newest', 'magical-products-display' ); ?></option>
						<option value="oldest"><?php esc_html_e( 'Oldest', 'magical-products-display' ); ?></option>
						<option value="highest"><?php esc_html_e( 'Highest Rated', 'magical-products-display' ); ?></option>
						<option value="lowest"><?php esc_html_e( 'Lowest Rated', 'magical-products-display' ); ?></option>
					</select>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
