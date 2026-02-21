<?php
/**
 * Category Filter Widget
 *
 * Displays product category filters with hierarchy and counts.
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
 * Class Category_Filter
 *
 * @since 2.0.0
 */
class Category_Filter extends Widget_Base {

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
	protected $widget_icon = 'eicon-folder';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-category-filter';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Category Filter', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'category', 'filter', 'products', 'shop', 'woocommerce', 'taxonomy', 'hierarchy' );
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
				'default'   => esc_html__( 'Product Categories', 'magical-products-display' ),
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
			'display_type',
			array(
				'label'   => esc_html__( 'Display Type', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'list',
				'options' => array(
					'list'       => esc_html__( 'List', 'magical-products-display' ),
					'dropdown'   => esc_html__( 'Dropdown', 'magical-products-display' ),
					'accordion'  => esc_html__( 'Accordion (Pro)', 'magical-products-display' ),
					'tree'       => esc_html__( 'Tree View', 'magical-products-display' ),
				),
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
					'{{WRAPPER}} .mpd-category-filter__list' => 'display: flex; flex-direction: {{VALUE}}; flex-wrap: wrap;',
				),
			)
		);

		// Pro Feature Notice for Accordion.
		$this->add_control(
			'accordion_pro_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Accordion Style is a Pro feature.', 'magical-products-display' ) ),
				'content_classes' => 'mpd-pro-notice',
				'condition'       => array(
					'display_type' => 'accordion',
				),
			)
		);

		$this->add_control(
			'order_by',
			array(
				'label'   => esc_html__( 'Order By', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => array(
					'name'  => esc_html__( 'Name', 'magical-products-display' ),
					'slug'  => esc_html__( 'Slug', 'magical-products-display' ),
					'count' => esc_html__( 'Count', 'magical-products-display' ),
					'order' => esc_html__( 'Category Order', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'asc',
				'options' => array(
					'asc'  => esc_html__( 'ASC', 'magical-products-display' ),
					'desc' => esc_html__( 'DESC', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'show_hierarchy',
			array(
				'label'        => esc_html__( 'Show Hierarchy', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'display_type!' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => esc_html__( 'Show Product Count', 'magical-products-display' ),
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
				'label'        => esc_html__( 'Hide Empty Categories', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'max_depth',
			array(
				'label'     => esc_html__( 'Maximum Depth', 'magical-products-display' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0,
				'min'       => 0,
				'max'       => 10,
				'condition' => array(
					'show_hierarchy' => 'yes',
					'display_type!'  => 'dropdown',
				),
				'description' => esc_html__( '0 = unlimited depth.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'show_children_only',
			array(
				'label'        => esc_html__( 'Show Children of Current Category', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'description'  => esc_html__( 'Only show child categories of the current category.', 'magical-products-display' ),
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
					'{{WRAPPER}} .mpd-category-filter__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-category-filter__title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-category-filter__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-category-filter__list' => 'list-style-type: {{VALUE}};',
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
					'{{WRAPPER}} .mpd-category-filter__list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-category-filter__list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-category-filter__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-category-filter__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Categories Style Section.
		$this->start_controls_section(
			'section_categories_style',
			array(
				'label'     => esc_html__( 'Categories', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'display_type!' => 'dropdown',
				),
			)
		);

		$this->add_responsive_control(
			'categories_spacing',
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
					'{{WRAPPER}} .mpd-category-filter__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'child_indent',
			array(
				'label'      => esc_html__( 'Child Indent', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-category-filter__children' => 'padding-left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_hierarchy' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_categories_style' );

		$this->start_controls_tab(
			'tab_categories_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'categories_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-category-filter__item a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_categories_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'categories_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-category-filter__item a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_categories_active',
			array(
				'label' => esc_html__( 'Active', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'categories_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-category-filter__item.is-current a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'categories_typography',
				'selector'  => '{{WRAPPER}} .mpd-category-filter__item a',
				'separator' => 'before',
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
					'{{WRAPPER}} .mpd-category-filter__dropdown' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dropdown_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-category-filter__dropdown' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'dropdown_typography',
				'selector' => '{{WRAPPER}} .mpd-category-filter__dropdown',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'dropdown_border',
				'selector' => '{{WRAPPER}} .mpd-category-filter__dropdown',
			)
		);

		$this->add_responsive_control(
			'dropdown_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-category-filter__dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-category-filter__dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-category-filter__dropdown' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-category-filter__count' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_typography',
				'selector' => '{{WRAPPER}} .mpd-category-filter__count',
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
		// Handle accordion type.
		$display_type = $settings['display_type'];
		if ( 'accordion' === $display_type && ! $this->is_pro() ) {
			$display_type = 'list';
		}

		// Get categories.
		$orderby = 'order' === $settings['order_by'] ? 'meta_value_num' : $settings['order_by'];

		$args = array(
			'taxonomy'   => 'product_cat',
			'orderby'    => $orderby,
			'order'      => strtoupper( $settings['order'] ),
			'hide_empty' => 'yes' === $settings['hide_empty'],
		);

		// Handle children only option.
		if ( 'yes' === $settings['show_children_only'] ) {
			$current_cat = $this->get_current_category();
			if ( $current_cat ) {
				$args['parent'] = $current_cat->term_id;
			}
		} elseif ( 'yes' === $settings['show_hierarchy'] ) {
			$args['parent'] = 0;
		}

		$categories = get_terms( $args );

		if ( is_wp_error( $categories ) || empty( $categories ) ) {
			if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				?>
				<div class="mpd-category-filter">
					<?php if ( 'yes' === $settings['show_title'] ) : ?>
						<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mpd-category-filter__title">
							<?php echo esc_html( $settings['title_text'] ); ?>
						</<?php echo esc_attr( $settings['title_tag'] ); ?>>
					<?php endif; ?>
					<p class="mpd-category-filter__notice"><?php esc_html_e( 'No categories found.', 'magical-products-display' ); ?></p>
				</div>
				<?php
			}
			return;
		}

		// Get current category.
		$current_cat = $this->get_current_category();
		?>
		<div class="mpd-category-filter widget_product_categories">
			<?php if ( 'yes' === $settings['show_title'] ) : ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mpd-category-filter__title">
					<?php echo esc_html( $settings['title_text'] ); ?>
				</<?php echo esc_attr( $settings['title_tag'] ); ?>>
			<?php endif; ?>

			<?php
			switch ( $display_type ) {
				case 'dropdown':
					$this->render_dropdown( $categories, $settings, $current_cat );
					break;

				case 'tree':
					$this->render_tree( $categories, $settings, $current_cat );
					break;

				case 'list':
				default:
					$this->render_list( $categories, $settings, $current_cat );
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
	 * @param array       $categories  Categories.
	 * @param array       $settings    Widget settings.
	 * @param object|null $current_cat Current category.
	 * @param int         $depth       Current depth.
	 * @return void
	 */
	protected function render_list( $categories, $settings, $current_cat, $depth = 0 ) {
		$max_depth = absint( $settings['max_depth'] );
		?>
		<ul class="mpd-category-filter__list<?php echo 0 === $depth ? '' : ' mpd-category-filter__children'; ?>">
			<?php foreach ( $categories as $category ) : ?>
				<?php
				$is_current = $current_cat && $current_cat->term_id === $category->term_id;
				$class      = $is_current ? 'mpd-category-filter__item is-current' : 'mpd-category-filter__item';
				?>
				<li class="<?php echo esc_attr( $class ); ?>">
					<a href="<?php echo esc_url( $this->get_category_filter_url( $category ) ); ?>">
						<?php echo esc_html( $category->name ); ?>
						<?php if ( 'yes' === $settings['show_count'] ) : ?>
							<span class="mpd-category-filter__count">(<?php echo esc_html( $category->count ); ?>)</span>
						<?php endif; ?>
					</a>

					<?php
					// Render children if hierarchy is enabled.
					if ( 'yes' === $settings['show_hierarchy'] && ( 0 === $max_depth || $depth < $max_depth ) ) {
						$children = get_terms(
							array(
								'taxonomy'   => 'product_cat',
								'parent'     => $category->term_id,
								'hide_empty' => 'yes' === $settings['hide_empty'],
								'orderby'    => 'order' === $settings['order_by'] ? 'meta_value_num' : $settings['order_by'],
								'order'      => strtoupper( $settings['order'] ),
							)
						);

						if ( ! is_wp_error( $children ) && ! empty( $children ) ) {
							$this->render_list( $children, $settings, $current_cat, $depth + 1 );
						}
					}
					?>
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
	 * @param array       $categories  Categories.
	 * @param array       $settings    Widget settings.
	 * @param object|null $current_cat Current category.
	 * @return void
	 */
	protected function render_dropdown( $categories, $settings, $current_cat ) {
		$dropdown_args = array(
			'taxonomy'         => 'product_cat',
			'orderby'          => 'order' === $settings['order_by'] ? 'meta_value_num' : $settings['order_by'],
			'order'            => strtoupper( $settings['order'] ),
			'show_count'       => 'yes' === $settings['show_count'],
			'hide_empty'       => 'yes' === $settings['hide_empty'],
			'hierarchical'     => true,
			'class'            => 'mpd-category-filter__dropdown',
			'name'             => 'product_cat',
			'id'               => 'mpd-product-cat-' . $this->get_id(),
			'show_option_none' => esc_html__( 'Select a category', 'magical-products-display' ),
			'value_field'      => 'slug',
			'selected'         => $current_cat ? $current_cat->slug : '',
		);

		wp_dropdown_categories( apply_filters( 'woocommerce_product_categories_widget_dropdown_args', $dropdown_args ) );

		// Get current query params without product_cat and pagination.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_params = array_map( 'sanitize_text_field', wp_unslash( $_GET ) );
		unset( $current_params['product_cat'] );
		unset( $current_params['paged'] );
		unset( $current_params['product-page'] );

		// Build base URL.
		$base_url = wc_get_page_permalink( 'shop' );
		if ( ! $base_url ) {
			$base_url = home_url( '/' );
		}
		$base_url_with_params = add_query_arg( $current_params, $base_url );
		?>
		<script type="text/javascript">
			document.getElementById('mpd-product-cat-<?php echo esc_js( $this->get_id() ); ?>').onchange = function() {
				if (this.value !== '') {
					var baseUrl = '<?php echo esc_js( $base_url_with_params ); ?>';
					var separator = baseUrl.indexOf('?') !== -1 ? '&' : '?';
					location.href = baseUrl + separator + 'product_cat=' + this.value;
				}
			};
		</script>
		<?php
	}

	/**
	 * Render tree display.
	 *
	 * @since 2.0.0
	 *
	 * @param array       $categories  Categories.
	 * @param array       $settings    Widget settings.
	 * @param object|null $current_cat Current category.
	 * @param int         $depth       Current depth.
	 * @return void
	 */
	protected function render_tree( $categories, $settings, $current_cat, $depth = 0 ) {
		$max_depth = absint( $settings['max_depth'] );
		?>
		<ul class="mpd-category-filter__list mpd-category-filter__list--tree<?php echo 0 === $depth ? '' : ' mpd-category-filter__children'; ?>">
			<?php foreach ( $categories as $category ) : ?>
				<?php
				$is_current = $current_cat && $current_cat->term_id === $category->term_id;
				$has_children = false;

				// Check for children.
				if ( 'yes' === $settings['show_hierarchy'] && ( 0 === $max_depth || $depth < $max_depth ) ) {
					$children = get_terms(
						array(
							'taxonomy'   => 'product_cat',
							'parent'     => $category->term_id,
							'hide_empty' => 'yes' === $settings['hide_empty'],
							'number'     => 1,
						)
					);
					$has_children = ! is_wp_error( $children ) && ! empty( $children );
				}

				$class = array( 'mpd-category-filter__item' );
				if ( $is_current ) {
					$class[] = 'is-current';
				}
				if ( $has_children ) {
					$class[] = 'has-children';
				}
				?>
				<li class="<?php echo esc_attr( implode( ' ', $class ) ); ?>">
					<?php if ( $has_children ) : ?>
						<span class="mpd-category-filter__toggle">
							<span class="mpd-category-filter__toggle-icon">+</span>
						</span>
					<?php endif; ?>
					<a href="<?php echo esc_url( $this->get_category_filter_url( $category ) ); ?>">
						<?php echo esc_html( $category->name ); ?>
						<?php if ( 'yes' === $settings['show_count'] ) : ?>
							<span class="mpd-category-filter__count">(<?php echo esc_html( $category->count ); ?>)</span>
						<?php endif; ?>
					</a>

					<?php
					// Render children if hierarchy is enabled.
					if ( $has_children ) {
						$all_children = get_terms(
							array(
								'taxonomy'   => 'product_cat',
								'parent'     => $category->term_id,
								'hide_empty' => 'yes' === $settings['hide_empty'],
								'orderby'    => 'order' === $settings['order_by'] ? 'meta_value_num' : $settings['order_by'],
								'order'      => strtoupper( $settings['order'] ),
							)
						);

						if ( ! is_wp_error( $all_children ) && ! empty( $all_children ) ) {
							$this->render_tree( $all_children, $settings, $current_cat, $depth + 1 );
						}
					}
					?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * Get current category.
	 *
	 * @since 2.0.0
	 *
	 * @return object|null Current category term object or null.
	 */
	protected function get_current_category() {
		// First check URL parameter for filtered category.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['product_cat'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$cat_slug = sanitize_text_field( wp_unslash( $_GET['product_cat'] ) );
			$term     = get_term_by( 'slug', $cat_slug, 'product_cat' );
			if ( $term && ! is_wp_error( $term ) ) {
				return $term;
			}
		}

		// Fallback to queried object for category archive pages.
		if ( is_product_category() ) {
			$queried_object = get_queried_object();
			if ( $queried_object && 'product_cat' === $queried_object->taxonomy ) {
				return $queried_object;
			}
		}

		return null;
	}

	/**
	 * Get filter URL for a category.
	 *
	 * Generates a URL that filters products by category on the current page
	 * instead of redirecting to the category archive.
	 *
	 * @since 2.0.0
	 *
	 * @param object $category Category term object.
	 * @return string Filter URL.
	 */
	protected function get_category_filter_url( $category ) {
		// Get current URL parameters.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_params = array_map( 'sanitize_text_field', wp_unslash( $_GET ) );

		// Set or update the product_cat parameter.
		$current_params['product_cat'] = $category->slug;

		// Remove pagination when filtering.
		unset( $current_params['paged'] );
		unset( $current_params['product-page'] );

		// Build the URL - use shop URL or current page.
		$base_url = wc_get_page_permalink( 'shop' );
		if ( ! $base_url ) {
			$base_url = home_url( '/' );
		}

		return add_query_arg( $current_params, $base_url );
	}
}
