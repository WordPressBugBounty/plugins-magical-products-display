<?php
/**
 * Product Description Widget
 *
 * Displays the full product description on single product pages.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\SingleProduct;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Product_Description
 *
 * @since 2.0.0
 */
class Product_Description extends Widget_Base {

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
		return 'mpd-product-description';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Product Description', 'magical-products-display' );
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
		return array( 'product', 'description', 'content', 'text', 'woocommerce', 'single' );
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

		$this->add_control(
			'show_heading',
			array(
				'label'        => __( 'Show Heading', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'magical-products-display' ),
				'label_off'    => __( 'Hide', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'heading_text',
			array(
				'label'     => __( 'Heading Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Description', 'magical-products-display' ),
				'condition' => array(
					'show_heading' => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_tag',
			array(
				'label'     => __( 'Heading Tag', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
				'default'   => 'h3',
				'condition' => array(
					'show_heading' => 'yes',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Toggle Expand & Read More', 'magical-products-display' ) );
		}
			$this->add_control(
				'enable_toggle',
				array(
					'label'        => __( 'Enable Toggle Expand', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'max_height',
				array(
					'label'      => __( 'Initial Height (px)', 'magical-products-display' ),
					'type'       => Controls_Manager::SLIDER,
					'range'      => array(
						'px' => array(
							'min' => 50,
							'max' => 500,
						),
					),
					'default'    => array(
						'size' => 150,
						'unit' => 'px',
					),
					'condition'  => array(
						'enable_toggle' => 'yes',
					),
				)
			);

			$this->add_control(
				'read_more_text',
				array(
					'label'     => __( 'Read More Text', 'magical-products-display' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Read More', 'magical-products-display' ),
					'condition' => array(
						'enable_toggle' => 'yes',
					),
				)
			);

			$this->add_control(
				'read_less_text',
				array(
					'label'     => __( 'Read Less Text', 'magical-products-display' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => __( 'Read Less', 'magical-products-display' ),
					'condition' => array(
						'enable_toggle' => 'yes',
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
		// Heading Style.
		$this->start_controls_section(
			'section_style_heading',
			array(
				'label'     => __( 'Heading', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_heading' => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => __( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-description-heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .mpd-description-heading',
			)
		);

		$this->add_responsive_control(
			'heading_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-description-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Content Style.
		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => __( 'Content', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .mpd-product-description',
			)
		);

		$this->add_responsive_control(
			'text_align',
			array(
				'label'     => __( 'Text Alignment', 'magical-products-display' ),
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
					'{{WRAPPER}} .mpd-product-description' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Read More Style (Pro).
		if ( $this->is_pro() ) {
			$this->start_controls_section(
				'section_style_read_more',
				array(
					'label'     => __( 'Read More Button', 'magical-products-display' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => array(
						'enable_toggle' => 'yes',
					),
				)
			);

			// Typography.
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'read_more_typography',
					'selector' => '{{WRAPPER}} .mpd-toggle-btn',
				)
			);

			// Normal/Hover Tabs.
			$this->start_controls_tabs( 'read_more_tabs' );

			// Normal Tab.
			$this->start_controls_tab(
				'read_more_normal_tab',
				array(
					'label' => __( 'Normal', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'read_more_color',
				array(
					'label'     => __( 'Text Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-toggle-btn' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'read_more_background',
					'types'    => array( 'classic', 'gradient' ),
					'exclude'  => array( 'image' ),
					'selector' => '{{WRAPPER}} .mpd-toggle-btn',
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'read_more_border',
					'selector' => '{{WRAPPER}} .mpd-toggle-btn',
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'read_more_box_shadow',
					'selector' => '{{WRAPPER}} .mpd-toggle-btn',
				)
			);

			$this->end_controls_tab();

			// Hover Tab.
			$this->start_controls_tab(
				'read_more_hover_tab',
				array(
					'label' => __( 'Hover', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'read_more_hover_color',
				array(
					'label'     => __( 'Text Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-toggle-btn:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'read_more_hover_background',
					'types'    => array( 'classic', 'gradient' ),
					'exclude'  => array( 'image' ),
					'selector' => '{{WRAPPER}} .mpd-toggle-btn:hover',
				)
			);

			$this->add_control(
				'read_more_hover_border_color',
				array(
					'label'     => __( 'Border Color', 'magical-products-display' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .mpd-toggle-btn:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'read_more_hover_box_shadow',
					'selector' => '{{WRAPPER}} .mpd-toggle-btn:hover',
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			// Border Radius.
			$this->add_responsive_control(
				'read_more_border_radius',
				array(
					'label'      => __( 'Border Radius', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'separator'  => 'before',
					'selectors'  => array(
						'{{WRAPPER}} .mpd-toggle-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			// Padding.
			$this->add_responsive_control(
				'read_more_padding',
				array(
					'label'      => __( 'Padding', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-toggle-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			// Margin.
			$this->add_responsive_control(
				'read_more_margin',
				array(
					'label'      => __( 'Margin', 'magical-products-display' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .mpd-toggle-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				__( 'Product Description', 'magical-products-display' ),
				__( 'This widget displays the product description. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		$description = $product->get_description();

		if ( empty( $description ) ) {
			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'mpd-product-description-wrapper' );

		// Pro: Toggle expand attributes.
		if ( $this->is_pro() && 'yes' === ( $settings['enable_toggle'] ?? '' ) ) {
			$this->add_render_attribute( 'content', 'class', 'mpd-toggleable' );
			$this->add_render_attribute( 'content', 'style', 'max-height: ' . absint( $settings['max_height']['size'] ) . 'px;' );
			$this->add_render_attribute( 'content', 'data-expanded', 'false' );
		}

		$this->add_render_attribute( 'content', 'class', 'mpd-product-description' );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			// Show heading if enabled.
			if ( 'yes' === $settings['show_heading'] && ! empty( $settings['heading_text'] ) ) {
				printf(
					'<%1$s class="mpd-description-heading">%2$s</%1$s>',
					esc_attr( $settings['heading_tag'] ),
					esc_html( $settings['heading_text'] )
				);
			}
			?>
			<div <?php $this->print_render_attribute_string( 'content' ); ?>>
				<?php echo wp_kses_post( wpautop( $description ) ); ?>
			</div>
			<?php
			// Pro: Toggle button.
			if ( $this->is_pro() && 'yes' === ( $settings['enable_toggle'] ?? '' ) ) {
				$full_height = 'none';
				printf(
					'<button class="mpd-toggle-btn" data-read-more="%s" data-read-less="%s" data-max-height="%s">%s</button>',
					esc_attr( $settings['read_more_text'] ),
					esc_attr( $settings['read_less_text'] ),
					esc_attr( $settings['max_height']['size'] ),
					esc_html( $settings['read_more_text'] )
				);
				?>
				<script>
				(function() {
					var wrapper = document.currentScript.parentElement;
					var content = wrapper.querySelector('.mpd-toggleable');
					var btn = wrapper.querySelector('.mpd-toggle-btn');
					
					if (!content || !btn) return;
					
					var maxHeight = parseInt(btn.getAttribute('data-max-height')) || 150;
					var readMore = btn.getAttribute('data-read-more') || 'Read More';
					var readLess = btn.getAttribute('data-read-less') || 'Read Less';
					var isExpanded = false;
					
					// Check if content needs toggle
					var fullHeight = content.scrollHeight;
					if (fullHeight <= maxHeight) {
						content.style.maxHeight = 'none';
						btn.style.display = 'none';
						return;
					}
					
					// Set initial state
					content.style.maxHeight = maxHeight + 'px';
					content.style.overflow = 'hidden';
					content.classList.add('mpd-collapsed');
					
					btn.addEventListener('click', function() {
						isExpanded = !isExpanded;
						
						if (isExpanded) {
							content.style.maxHeight = fullHeight + 'px';
							content.classList.remove('mpd-collapsed');
							content.classList.add('mpd-expanded');
							btn.textContent = readLess;
							btn.classList.add('active');
						} else {
							content.style.maxHeight = maxHeight + 'px';
							content.classList.remove('mpd-expanded');
							content.classList.add('mpd-collapsed');
							btn.textContent = readMore;
							btn.classList.remove('active');
						}
					});
				})();
				</script>
				<?php
			}
			?>
		</div>
		<?php
	}
}
