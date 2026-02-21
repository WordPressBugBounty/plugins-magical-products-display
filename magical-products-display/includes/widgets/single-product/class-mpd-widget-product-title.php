<?php
/**
 * Product Title Widget
 *
 * Displays the product title on single product pages.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\SingleProduct;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Product_Title
 *
 * @since 2.0.0
 */
class Product_Title extends Widget_Base {

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
	protected $widget_icon = 'eicon-product-title';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-product-title';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'MPD Product Title', 'magical-products-display' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-title';
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'product', 'title', 'name', 'heading', 'woocommerce', 'single' );
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
			'header_size',
			array(
				'label'   => __( 'HTML Tag', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
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
				'default' => 'h1',
			)
		);

		$this->add_responsive_control(
			'align',
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
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-title' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_to_product',
			array(
				'label'        => __( 'Link to Product', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
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
			$this->add_pro_notice( 'pro_features_notice', __( 'Dynamic Typography & Conditions', 'magical-products-display' ) );
		}
			$this->add_control(
				'show_category_prefix',
				array(
					'label'        => __( 'Show Category Prefix', 'magical-products-display' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'magical-products-display' ),
					'label_off'    => __( 'No', 'magical-products-display' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->add_control(
				'custom_prefix',
				array(
					'label'       => __( 'Custom Prefix', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => __( 'e.g., Buy', 'magical-products-display' ),
				)
			);

			$this->add_control(
				'custom_suffix',
				array(
					'label'       => __( 'Custom Suffix', 'magical-products-display' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => __( 'e.g., Now!', 'magical-products-display' ),
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
				'label' => __( 'Title', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-product-title',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'title_text_shadow',
				'selector' => '{{WRAPPER}} .mpd-product-title',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			array(
				'name'     => 'title_text_stroke',
				'selector' => '{{WRAPPER}} .mpd-product-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Link styles.
		$this->add_control(
			'link_heading',
			array(
				'label'     => __( 'Link Style', 'magical-products-display' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'link_to_product' => 'yes',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => __( 'Link Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-title a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'link_to_product' => 'yes',
				),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => __( 'Link Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-product-title a:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'link_to_product' => 'yes',
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
		// Ensure styles are enqueued on frontend.
		if ( ! $this->is_editor_mode() ) {
			wp_enqueue_style( 'mpd-single-product' );
		}
		
		$product = $this->get_current_product();

		if ( ! $product ) {
			$this->render_editor_placeholder(
				__( 'Product Title', 'magical-products-display' ),
				__( 'This widget displays the product title. Please use it on a single product page or inside a product loop.', 'magical-products-display' )
			);
			return;
		}

		$tag   = $settings['header_size'];
		$title = $product->get_title();

		// Pro features: prefix/suffix.
		if ( $this->is_pro() ) {
			$prefix = '';
			$suffix = '';

			if ( 'yes' === $settings['show_category_prefix'] ) {
				$categories = wc_get_product_category_list( $product->get_id(), ', ' );
				if ( $categories ) {
					$prefix = wp_strip_all_tags( $categories ) . ' - ';
				}
			}

			if ( ! empty( $settings['custom_prefix'] ) ) {
				$prefix = esc_html( $settings['custom_prefix'] ) . ' ';
			}

			if ( ! empty( $settings['custom_suffix'] ) ) {
				$suffix = ' ' . esc_html( $settings['custom_suffix'] );
			}

			$title = $prefix . $title . $suffix;
		}

		$this->add_render_attribute( 'title', 'class', 'mpd-product-title' );

		if ( 'yes' === $settings['link_to_product'] ) {
			$title = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $product->get_permalink() ),
				esc_html( $title )
			);
		} else {
			$title = esc_html( $title );
		}

		printf(
			'<%1$s %2$s>%3$s</%1$s>',
			esc_attr( $tag ),
			$this->get_render_attribute_string( 'title' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$title // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
}
