<?php
/**
 * Product Short Description Widget
 *
 * Displays the short product description on single product pages.
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
 * Class Product_Short_Description
 *
 * @since 2.0.0
 */
class Product_Short_Description extends Widget_Base {

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
		return 'mpd-product-short-description';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Short Description', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-description';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'short description', 'excerpt', 'summary', 'woocommerce', 'single' );
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
			'text_align',
			array(
				'label'     => __( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-short-description' => 'text-align: {{VALUE}};',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Read More Link & Custom Length', 'magical-products-display' ) );
		}
			$this->add_control(
				'limit_words',
				array(
					'label'        => __( 'Limit Words', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'word_count',
				array(
					'label'     => __( 'Word Count', 'magical-products-display' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 25,
					'min'       => 5,
					'max'       => 200,
					'condition' => array(
						'limit_words' => 'yes',
					),
				)
			);

			$this->add_control(
				'show_read_more',
				array(
					'label'        => __( 'Show Read More Link', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'read_more_text',
				array(
					'label'     => __( 'Read More Text', 'magical-products-display' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( '...Read More', 'magical-products-display' ),
					'condition' => array(
						'show_read_more' => 'yes',
					),
				)
			);

			$this->add_control(
				'read_more_link',
				array(
					'label'     => __( 'Read More Link', 'magical-products-display' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'description_tab' => __( 'Scroll to Description Tab', 'magical-products-display' ),
						'product_page'    => __( 'Product Page', 'magical-products-display' ),
						'custom'          => __( 'Custom URL', 'magical-products-display' ),
					),
					'default'   => 'description_tab',
					'condition' => array(
						'show_read_more' => 'yes',
					),
				)
			);

			$this->add_control(
				'custom_link',
				array(
					'label'       => __( 'Custom URL', 'magical-products-display' ),
					'type'        => Controls_Manager::URL,
					'placeholder' => __( 'https://your-link.com', 'magical-products-display' ),
					'condition'   => array(
						'show_read_more'  => 'yes',
						'read_more_link'  => 'custom',
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
		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Short Description', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-short-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .mpd-short-description',
			)
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-short-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Read More Style (Pro).
		if ( $this->is_pro() ) {
			$this->start_controls_section(
				'section_style_read_more',
				array(
					'label'     => __( 'Read More', 'magical-products-display' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'show_read_more' => 'yes',
					),
				)
			);

			$this->add_control(
				'read_more_color',
				array(
					'label'     => __( 'Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-read-more-link' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_control(
				'read_more_hover_color',
				array(
					'label'     => __( 'Hover Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-read-more-link:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'read_more_typography',
					'selector' => '{{WRAPPER}} .mpd-read-more-link',
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
				__( 'Short Description', 'magical-products-display' ),
				__( 'This widget displays the short description. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		$short_description = $product->get_short_description();

		if ( empty( $short_description ) ) {
			return;
		}

		$full_description    = $short_description;
		$display_description = $short_description;
		$is_truncated        = false;

		// Pro: Limit words.
		if ( $this->is_pro() && 'yes' === ( $settings['limit_words'] ?? '' ) ) {
			$word_count          = absint( $settings['word_count'] ?? 25 );
			$display_description = wp_trim_words( $short_description, $word_count, '' );
			$is_truncated        = ( $display_description !== wp_strip_all_tags( $short_description ) );
		}

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-short-description' );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="mpd-description-content"><?php echo wp_kses_post( wpautop( $display_description ) ); ?></div>
			<?php if ( $is_truncated ) : ?>
				<div class="mpd-description-full" style="display:none;"><?php echo wp_kses_post( wpautop( $full_description ) ); ?></div>
			<?php endif; ?>
			<?php
			// Pro: Read more link.
			if ( $this->is_pro() && 'yes' === ( $settings['show_read_more'] ?? '' ) && $is_truncated ) {
				$this->render_read_more_link( $product, $settings );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render read more link.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  The product object.
	 * @param array       $settings Widget settings.
	 * @return void
	 */
	private function render_read_more_link( $product, $settings ) {
		$link_type = $settings['read_more_link'] ?? 'description_tab';
		$text      = $settings['read_more_text'] ?? __( '...Read More', 'magical-products-display' );

		$attributes = array(
			'class' => 'mpd-read-more-link',
		);

		switch ( $link_type ) {
			case 'description_tab':
				// Expand text inline - no URL change.
				$attributes['href']                    = 'javascript:void(0)';
				$attributes['data-expand-description'] = 'true';
				break;

			case 'product_page':
				$attributes['href'] = esc_url( $product->get_permalink() );
				break;

			case 'custom':
				$attributes['href'] = esc_url( $settings['custom_link']['url'] ?? '#' );
				break;

			default:
				$attributes['href'] = '#';
		}

		if ( 'custom' === $link_type && ! empty( $settings['custom_link']['is_external'] ) ) {
			$attributes['target'] = '_blank';
		}

		if ( 'custom' === $link_type && ! empty( $settings['custom_link']['nofollow'] ) ) {
			$attributes['rel'] = 'nofollow';
		}

		$attr_string = '';
		foreach ( $attributes as $key => $value ) {
			$attr_string .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}

		printf( '<a%s>%s</a>', $attr_string, esc_html( $text ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// Render inline script for expand functionality.
		if ( 'description_tab' === $link_type ) {
			$this->render_expand_script();
		}
	}

	/**
	 * Render inline script for expanding description.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function render_expand_script() {
		static $script_rendered = false;

		if ( $script_rendered ) {
			return;
		}
		$script_rendered = true;
		?>
		<style>
		.mpd-description-full {
			opacity: 0;
			max-height: 0;
			overflow: hidden;
			transition: opacity 0.4s ease, max-height 0.5s ease;
		}
		.mpd-description-full.mpd-expanded {
			opacity: 1;
			max-height: 2000px;
		}
		.mpd-description-content {
			transition: opacity 0.3s ease;
		}
		.mpd-description-content.mpd-hidden {
			opacity: 0;
			max-height: 0;
			overflow: hidden;
		}
		.mpd-read-more-link {
			transition: opacity 0.3s ease;
		}
		.mpd-read-more-link.mpd-hidden {
			opacity: 0;
			pointer-events: none;
		}
		</style>
		<script>
		(function() {
			document.addEventListener('click', function(e) {
				var link = e.target.closest('[data-expand-description]');
				if (!link) return;
				
				e.preventDefault();
				
				var wrapper = link.closest('.mpd-short-description');
				if (!wrapper) return;
				
				var truncated = wrapper.querySelector('.mpd-description-content');
				var full = wrapper.querySelector('.mpd-description-full');
				
				if (truncated && full) {
					// Fade out truncated content and link
					truncated.classList.add('mpd-hidden');
					link.classList.add('mpd-hidden');
					
					// Show and animate full content
					full.style.display = 'block';
					// Trigger reflow for animation
					full.offsetHeight;
					full.classList.add('mpd-expanded');
					
					// Hide elements after animation
					setTimeout(function() {
						truncated.style.display = 'none';
						link.style.display = 'none';
					}, 300);
				}
			});
		})();
		</script>
		<?php
	}
}
