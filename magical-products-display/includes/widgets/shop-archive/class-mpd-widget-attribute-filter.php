<?php
/**
 * Attribute Filter Widget
 *
 * Displays product attribute filters with various display styles.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\ShopArchive;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Attribute_Filter
 *
 * @since 2.0.0
 */
class Attribute_Filter extends Widget_Base {

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
	protected $widget_icon = 'eicon-bullet-list';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-attribute-filter';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Attribute Filter', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'attribute', 'filter', 'color', 'size', 'swatch', 'products', 'shop', 'magical-products-display' );
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
		return array( 'mpd-shop-archive' );
	}

	/**
	 * Get WooCommerce product attributes.
	 *
	 * @since 2.0.0
	 *
	 * @return array Attribute options for select control.
	 */
	protected function get_wc_attributes() {
		$attribute_taxonomies = array();

		if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
			return $attribute_taxonomies;
		}

		$attributes = wc_get_attribute_taxonomies();

		if ( ! empty( $attributes ) ) {
			foreach ( $attributes as $attribute ) {
				$attribute_taxonomies[ $attribute->attribute_name ] = $attribute->attribute_label;
			}
		}

		return $attribute_taxonomies;
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
				'default'   => esc_html__( 'Filter by', 'magical-products-display' ),
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

		// Get available attributes.
		$attribute_taxonomies = $this->get_wc_attributes();

		$this->add_control(
			'attribute',
			array(
				'label'       => esc_html__( 'Attribute', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array_merge(
					array( '' => esc_html__( 'Select Attribute', 'magical-products-display' ) ),
					$attribute_taxonomies
				),
				'description' => empty( $attribute_taxonomies ) ? esc_html__( 'No product attributes found. Please create attributes in WooCommerce.', 'magical-products-display' ) : '',
			)
		);

		$this->add_control(
			'display_type',
			array(
				'label'   => esc_html__( 'Display Type', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'list',
				'options' => array(
					'list'     => esc_html__( 'List', 'magical-products-display' ),
					'dropdown' => esc_html__( 'Dropdown', 'magical-products-display' ),
					'checkbox' => esc_html__( 'Checkboxes', 'magical-products-display' ),
					'swatch'   => esc_html__( 'Color Swatches (Pro)', 'magical-products-display' ),
					'label'    => esc_html__( 'Label/Button', 'magical-products-display' ),
				),
			)
		);

		// Pro Feature Notice for Swatches.
		$this->add_control(
			'swatch_pro_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Color Swatches is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'mpd-pro-notice',
				'condition'       => array(
					'display_type' => 'swatch',
				),
			)
		);

		$this->add_control(
			'query_type',
			array(
				'label'   => esc_html__( 'Query Type', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'and',
				'options' => array(
					'and' => esc_html__( 'AND', 'magical-products-display' ),
					'or'  => esc_html__( 'OR', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => esc_html__( 'Show Count', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'hide_empty',
			array(
				'label'        => esc_html__( 'Hide Empty', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_responsive_control(
			'layout',
			array(
				'label'     => esc_html__( 'Layout', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'column' => array(
						'title' => esc_html__( 'Vertical', 'magical-products-display' ),
						'icon'  => 'eicon-v-align-stretch',
					),
					'row'    => array(
						'title' => esc_html__( 'Horizontal', 'magical-products-display' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
				'default'   => 'column',
				'condition' => array(
					'display_type!' => 'dropdown',
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-filter__list' => 'flex-direction: {{VALUE}};',
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
					'{{WRAPPER}} .mpd-attribute-filter__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-attribute-filter__title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attribute-filter__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// List Style Section.
		$this->start_controls_section(
			'section_list_style',
			array(
				'label'     => esc_html__( 'List Style', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'display_type!' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'list_style_type',
			array(
				'label'     => esc_html__( 'List Style Type', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'   => esc_html__( 'None', 'magical-products-display' ),
					'disc'   => esc_html__( 'Disc', 'magical-products-display' ),
					'circle' => esc_html__( 'Circle', 'magical-products-display' ),
					'square' => esc_html__( 'Square', 'magical-products-display' ),
					'decimal' => esc_html__( 'Decimal', 'magical-products-display' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-filter__list' => 'list-style-type: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_margin',
			array(
				'label'      => esc_html__( 'List Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attribute-filter__list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_padding',
			array(
				'label'      => esc_html__( 'List Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attribute-filter__list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_item_margin',
			array(
				'label'      => esc_html__( 'Item Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attribute-filter__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'list_item_padding',
			array(
				'label'      => esc_html__( 'Item Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attribute-filter__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Items Style Section.
		$this->start_controls_section(
			'section_items_style',
			array(
				'label'     => esc_html__( 'Filter Items', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'display_type!' => 'dropdown',
				),
			)
		);

		$this->add_responsive_control(
			'items_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 8,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attribute-filter__list' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_items_style' );

		$this->start_controls_tab(
			'tab_items_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'items_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-filter__item a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-attribute-filter__item label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'items_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-filter__item a' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-attribute-filter__item label' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'display_type' => 'label',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_items_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'items_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-filter__item a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-attribute-filter__item label:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'items_background_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-filter__item a:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-attribute-filter__item label:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'display_type' => 'label',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_items_active',
			array(
				'label' => esc_html__( 'Active', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'items_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-filter__item.is-chosen a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-attribute-filter__item input:checked + label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'items_background_active',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-filter__item.is-chosen a' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mpd-attribute-filter__item input:checked + label' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'display_type' => 'label',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'items_typography',
				'selector'  => '{{WRAPPER}} .mpd-attribute-filter__item a, {{WRAPPER}} .mpd-attribute-filter__item label',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'items_border',
				'selector'  => '{{WRAPPER}} .mpd-attribute-filter__item a, {{WRAPPER}} .mpd-attribute-filter__item label',
				'condition' => array(
					'display_type' => 'label',
				),
			)
		);

		$this->add_responsive_control(
			'items_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attribute-filter__item a, {{WRAPPER}} .mpd-attribute-filter__item label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'display_type' => 'label',
				),
			)
		);

		$this->add_responsive_control(
			'items_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attribute-filter__item a, {{WRAPPER}} .mpd-attribute-filter__item label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'display_type' => 'label',
				),
			)
		);

		$this->end_controls_section();

		// Dropdown Style Section.
		$this->start_controls_section(
			'section_dropdown_style',
			array(
				'label'     => esc_html__( 'Dropdown', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'display_type' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'dropdown_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-filter__dropdown' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dropdown_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-filter__dropdown' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'dropdown_typography',
				'selector' => '{{WRAPPER}} .mpd-attribute-filter__dropdown',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'dropdown_border',
				'selector' => '{{WRAPPER}} .mpd-attribute-filter__dropdown',
			)
		);

		$this->add_responsive_control(
			'dropdown_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attribute-filter__dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'dropdown_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attribute-filter__dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'dropdown_width',
			array(
				'label'      => esc_html__( 'Width', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 400,
					),
					'%'  => array(
						'min' => 20,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-attribute-filter__dropdown' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Count Style Section.
		$this->start_controls_section(
			'section_count_style',
			array(
				'label'     => esc_html__( 'Count', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_count' => 'yes',
				),
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-attribute-filter__count' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_typography',
				'selector' => '{{WRAPPER}} .mpd-attribute-filter__count',
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
		if ( empty( $settings['attribute'] ) ) {
			if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				?>
				<div class="mpd-attribute-filter">
					<p class="mpd-attribute-filter__notice"><?php esc_html_e( 'Please select an attribute to filter.', 'magical-products-display' ); ?></p>
				</div>
				<?php
			}
			return;
		}

		$taxonomy   = wc_attribute_taxonomy_name( $settings['attribute'] );
		$hide_empty = 'yes' === $settings['hide_empty'];

		// Get terms.
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => $hide_empty,
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				?>
				<div class="mpd-attribute-filter">
					<?php if ( 'yes' === $settings['show_title'] ) : ?>
						<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mpd-attribute-filter__title">
							<?php echo esc_html( $settings['title_text'] ); ?> <?php echo esc_html( wc_attribute_label( $settings['attribute'] ) ); ?>
						</<?php echo esc_attr( $settings['title_tag'] ); ?>>
					<?php endif; ?>
					<p class="mpd-attribute-filter__notice"><?php esc_html_e( 'No terms found for this attribute.', 'magical-products-display' ); ?></p>
				</div>
				<?php
			}
			return;
		}

		// Get current filter value.
		$filter_name   = 'filter_' . $settings['attribute'];
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$raw_value     = isset( $_GET[ $filter_name ] ) ? wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) : '';
		// Ensure we have a string before exploding (wc_clean can return array)
		$current_value = is_array( $raw_value ) ? $raw_value : ( ! empty( $raw_value ) ? explode( ',', $raw_value ) : array() );

		// Handle swatch type.
		$display_type = $settings['display_type'];
		if ( 'swatch' === $display_type && ! $this->is_pro() ) {
			$display_type = 'list';
		}

		$base_link = $this->get_current_page_url();
		?>
		<div class="mpd-attribute-filter widget_layered_nav">
			<?php if ( 'yes' === $settings['show_title'] ) : ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mpd-attribute-filter__title">
					<?php echo esc_html( $settings['title_text'] ); ?> <?php echo esc_html( wc_attribute_label( $settings['attribute'] ) ); ?>
				</<?php echo esc_attr( $settings['title_tag'] ); ?>>
			<?php endif; ?>

			<?php
			switch ( $display_type ) {
				case 'dropdown':
					$this->render_dropdown( $terms, $settings, $current_value, $filter_name, $base_link );
					break;

				case 'checkbox':
					$this->render_checkboxes( $terms, $settings, $current_value, $filter_name, $base_link );
					break;

				case 'label':
					$this->render_labels( $terms, $settings, $current_value, $filter_name, $base_link );
					break;

				case 'list':
				default:
					$this->render_list( $terms, $settings, $current_value, $filter_name, $base_link );
					break;
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render list display.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $terms         Terms.
	 * @param array  $settings      Widget settings.
	 * @param array  $current_value Current filter values.
	 * @param string $filter_name   Filter parameter name.
	 * @param string $base_link     Base URL.
	 * @return void
	 */
	protected function render_list( $terms, $settings, $current_value, $filter_name, $base_link ) {
		?>
		<ul class="mpd-attribute-filter__list">
			<?php foreach ( $terms as $term ) : ?>
				<?php
				$is_chosen = in_array( $term->slug, $current_value, true );
				$link      = $this->get_filter_link( $term->slug, $current_value, $filter_name, $base_link, $settings['query_type'] );
				$class     = $is_chosen ? 'mpd-attribute-filter__item is-chosen' : 'mpd-attribute-filter__item';
				?>
				<li class="<?php echo esc_attr( $class ); ?>">
					<a href="<?php echo esc_url( $link ); ?>">
						<?php echo esc_html( $term->name ); ?>
						<?php if ( 'yes' === $settings['show_count'] ) : ?>
							<span class="mpd-attribute-filter__count">(<?php echo esc_html( $term->count ); ?>)</span>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * Render dropdown display.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $terms         Terms.
	 * @param array  $settings      Widget settings.
	 * @param array  $current_value Current filter values.
	 * @param string $filter_name   Filter parameter name.
	 * @param string $base_link     Base URL.
	 * @return void
	 */
	protected function render_dropdown( $terms, $settings, $current_value, $filter_name, $base_link ) {
		?>
		<form method="get" action="<?php echo esc_url( $base_link ); ?>" class="mpd-attribute-filter__form">
			<select 
				name="<?php echo esc_attr( $filter_name ); ?>" 
				class="mpd-attribute-filter__dropdown"
			>
				<option value=""><?php esc_html_e( 'Any', 'magical-products-display' ); ?></option>
				<?php foreach ( $terms as $term ) : ?>
					<option 
						value="<?php echo esc_attr( $term->slug ); ?>"
						<?php selected( in_array( $term->slug, $current_value, true ) ); ?>
					>
						<?php
						echo esc_html( $term->name );
						if ( 'yes' === $settings['show_count'] ) {
							echo esc_html( ' (' . $term->count . ')' );
						}
						?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php wc_query_string_form_fields( null, array( $filter_name, 'paged' ) ); ?>
		</form>
		<?php
	}

	/**
	 * Render checkboxes display.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $terms         Terms.
	 * @param array  $settings      Widget settings.
	 * @param array  $current_value Current filter values.
	 * @param string $filter_name   Filter parameter name.
	 * @param string $base_link     Base URL.
	 * @return void
	 */
	protected function render_checkboxes( $terms, $settings, $current_value, $filter_name, $base_link ) {
		?>
		<form method="get" action="<?php echo esc_url( $base_link ); ?>" class="mpd-attribute-filter__form mpd-attribute-filter__checkboxes">
			<ul class="mpd-attribute-filter__list mpd-attribute-filter__list--checkbox">
				<?php foreach ( $terms as $term ) : ?>
					<?php
					$is_checked = in_array( $term->slug, $current_value, true );
					$input_id   = 'mpd-attr-' . $this->get_id() . '-' . $term->slug;
					?>
					<li class="mpd-attribute-filter__item">
						<input 
							type="checkbox" 
							id="<?php echo esc_attr( $input_id ); ?>"
							class="mpd-attribute-filter__checkbox"
							name="<?php echo esc_attr( $filter_name ); ?>[]"
							value="<?php echo esc_attr( $term->slug ); ?>"
							<?php checked( $is_checked ); ?>
						/>
						<label for="<?php echo esc_attr( $input_id ); ?>">
							<?php echo esc_html( $term->name ); ?>
							<?php if ( 'yes' === $settings['show_count'] ) : ?>
								<span class="mpd-attribute-filter__count">(<?php echo esc_html( $term->count ); ?>)</span>
							<?php endif; ?>
						</label>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php wc_query_string_form_fields( null, array( $filter_name, 'paged' ) ); ?>
		</form>
		<?php
	}

	/**
	 * Render labels display.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $terms         Terms.
	 * @param array  $settings      Widget settings.
	 * @param array  $current_value Current filter values.
	 * @param string $filter_name   Filter parameter name.
	 * @param string $base_link     Base URL.
	 * @return void
	 */
	protected function render_labels( $terms, $settings, $current_value, $filter_name, $base_link ) {
		?>
		<ul class="mpd-attribute-filter__list mpd-attribute-filter__list--label">
			<?php foreach ( $terms as $term ) : ?>
				<?php
				$is_chosen = in_array( $term->slug, $current_value, true );
				$link      = $this->get_filter_link( $term->slug, $current_value, $filter_name, $base_link, $settings['query_type'] );
				$class     = $is_chosen ? 'mpd-attribute-filter__item is-chosen' : 'mpd-attribute-filter__item';
				?>
				<li class="<?php echo esc_attr( $class ); ?>">
					<a href="<?php echo esc_url( $link ); ?>">
						<?php echo esc_html( $term->name ); ?>
						<?php if ( 'yes' === $settings['show_count'] ) : ?>
							<span class="mpd-attribute-filter__count">(<?php echo esc_html( $term->count ); ?>)</span>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * Get filter link.
	 *
	 * @since 2.0.0
	 *
	 * @param string $term_slug     Term slug.
	 * @param array  $current_value Current filter values.
	 * @param string $filter_name   Filter parameter name.
	 * @param string $base_link     Base URL.
	 * @param string $query_type    Query type (and/or).
	 * @return string Filter link.
	 */
	protected function get_filter_link( $term_slug, $current_value, $filter_name, $base_link, $query_type ) {
		if ( in_array( $term_slug, $current_value, true ) ) {
			// Remove from filter.
			$new_value = array_diff( $current_value, array( $term_slug ) );
		} else {
			// Add to filter.
			if ( 'or' === $query_type ) {
				$new_value   = $current_value;
				$new_value[] = $term_slug;
			} else {
				$new_value = array( $term_slug );
			}
		}

		if ( empty( $new_value ) ) {
			return remove_query_arg( $filter_name, $base_link );
		}

		return add_query_arg( $filter_name, implode( ',', $new_value ), $base_link );
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
