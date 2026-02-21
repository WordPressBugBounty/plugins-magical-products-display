<?php
/**
 * Active Filters Widget
 *
 * Displays active WooCommerce filters with clear options.
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
 * Class Active_Filters
 *
 * @since 2.0.0
 */
class Active_Filters extends Widget_Base {

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
	protected $widget_icon = 'eicon-filter';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-active-filters';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Active Filters', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'active', 'filters', 'products', 'shop', 'woocommerce', 'clear', 'chips' );
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
				'default'   => esc_html__( 'Active Filters', 'magical-products-display' ),
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
			'show_clear_all',
			array(
				'label'        => esc_html__( 'Show Clear All Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'clear_all_text',
			array(
				'label'     => esc_html__( 'Clear All Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Clear All', 'magical-products-display' ),
				'condition' => array(
					'show_clear_all' => 'yes',
				),
			)
		);

		$this->add_control(
			'remove_icon',
			array(
				'label'       => esc_html__( 'Remove Icon', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '×',
				'description' => esc_html__( 'Icon or text for remove button.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'display_type',
			array(
				'label'   => esc_html__( 'Display Type', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'chips',
				'options' => array(
					'chips' => esc_html__( 'Chips', 'magical-products-display' ),
					'list'  => esc_html__( 'List', 'magical-products-display' ),
				),
			)
		);

		$this->add_responsive_control(
			'filter_layout',
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
				'default'   => 'row',
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__list' => 'flex-direction: {{VALUE}}; flex-wrap: wrap;',
				),
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__list' => 'justify-content: {{VALUE}};',
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
					'{{WRAPPER}} .mpd-active-filters__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-active-filters__title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-active-filters__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'display_type' => 'list',
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
					'none'    => esc_html__( 'None', 'magical-products-display' ),
					'disc'    => esc_html__( 'Disc', 'magical-products-display' ),
					'circle'  => esc_html__( 'Circle', 'magical-products-display' ),
					'square'  => esc_html__( 'Square', 'magical-products-display' ),
					'decimal' => esc_html__( 'Decimal', 'magical-products-display' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__list' => 'list-style-type: {{VALUE}};',
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
					'{{WRAPPER}} .mpd-active-filters__list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-active-filters__list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-active-filters__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mpd-active-filters__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'list_item_border_bottom',
			array(
				'label'        => esc_html__( 'Item Border Bottom', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors'    => array(
					'{{WRAPPER}} .mpd-active-filters__item' => 'border-bottom: 1px solid #f0f0f0;',
				),
			)
		);

		$this->add_control(
			'list_item_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'list_item_border_bottom' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__item' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Filter Chips Style Section.
		$this->start_controls_section(
			'section_chips_style',
			array(
				'label' => esc_html__( 'Filter Chips', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'chips_spacing',
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
					'{{WRAPPER}} .mpd-active-filters__list' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_chips_style' );

		$this->start_controls_tab(
			'tab_chips_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'chips_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__chip' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'chips_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__chip' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_chips_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'chips_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__chip:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'chips_background_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__chip:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'chips_typography',
				'selector'  => '{{WRAPPER}} .mpd-active-filters__chip',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'chips_border',
				'selector' => '{{WRAPPER}} .mpd-active-filters__chip',
			)
		);

		$this->add_responsive_control(
			'chips_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-active-filters__chip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'chips_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-active-filters__chip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Remove Icon Style Section.
		$this->start_controls_section(
			'section_remove_icon_style',
			array(
				'label' => esc_html__( 'Remove Icon', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'remove_icon_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__remove' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'remove_icon_color_hover',
			array(
				'label'     => esc_html__( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__chip:hover .mpd-active-filters__remove' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'remove_icon_size',
			array(
				'label'      => esc_html__( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-active-filters__remove' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'remove_icon_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-active-filters__remove' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Clear All Button Style Section.
		$this->start_controls_section(
			'section_clear_all_style',
			array(
				'label'     => esc_html__( 'Clear All Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_clear_all' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_clear_all_style' );

		$this->start_controls_tab(
			'tab_clear_all_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'clear_all_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__clear-all' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'clear_all_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__clear-all' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_clear_all_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'clear_all_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__clear-all:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'clear_all_background_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-active-filters__clear-all:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'clear_all_typography',
				'selector'  => '{{WRAPPER}} .mpd-active-filters__clear-all',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'clear_all_border',
				'selector' => '{{WRAPPER}} .mpd-active-filters__clear-all',
			)
		);

		$this->add_responsive_control(
			'clear_all_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-active-filters__clear-all' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'clear_all_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-active-filters__clear-all' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		// Get active filters.
		$active_filters = $this->get_active_filters();

		// Show placeholder in editor if no filters.
		if ( empty( $active_filters ) ) {
			if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				$display_type = isset( $settings['display_type'] ) ? $settings['display_type'] : 'chips';
				$list_class   = 'list' === $display_type ? 'mpd-active-filters--list' : 'mpd-active-filters--chips';
				$item_class   = 'list' === $display_type ? 'mpd-active-filters__item' : 'mpd-active-filters__chip';
				?>
				<div class="mpd-active-filters <?php echo esc_attr( $list_class ); ?>">
					<?php if ( 'yes' === $settings['show_title'] ) : ?>
						<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mpd-active-filters__title">
							<?php echo esc_html( $settings['title_text'] ); ?>
						</<?php echo esc_attr( $settings['title_tag'] ); ?>>
					<?php endif; ?>
					<ul class="mpd-active-filters__list">
						<li class="<?php echo esc_attr( $item_class ); ?>">
							<?php if ( 'list' === $display_type ) : ?>
								<a href="#" class="mpd-active-filters__link">
									<span class="mpd-active-filters__label"><?php esc_html_e( 'Sample Filter', 'magical-products-display' ); ?></span>
									<span class="mpd-active-filters__remove"><?php echo esc_html( $settings['remove_icon'] ); ?></span>
								</a>
							<?php else : ?>
								<span class="mpd-active-filters__label"><?php esc_html_e( 'Sample Filter', 'magical-products-display' ); ?></span>
								<span class="mpd-active-filters__remove"><?php echo esc_html( $settings['remove_icon'] ); ?></span>
							<?php endif; ?>
						</li>
						<li class="<?php echo esc_attr( $item_class ); ?>">
							<?php if ( 'list' === $display_type ) : ?>
								<a href="#" class="mpd-active-filters__link">
									<span class="mpd-active-filters__label"><?php esc_html_e( 'Another Filter', 'magical-products-display' ); ?></span>
									<span class="mpd-active-filters__remove"><?php echo esc_html( $settings['remove_icon'] ); ?></span>
								</a>
							<?php else : ?>
								<span class="mpd-active-filters__label"><?php esc_html_e( 'Another Filter', 'magical-products-display' ); ?></span>
								<span class="mpd-active-filters__remove"><?php echo esc_html( $settings['remove_icon'] ); ?></span>
							<?php endif; ?>
						</li>
					</ul>
					<?php if ( 'yes' === $settings['show_clear_all'] ) : ?>
						<a href="#" class="mpd-active-filters__clear-all">
							<?php echo esc_html( $settings['clear_all_text'] ); ?>
						</a>
					<?php endif; ?>
				</div>
				<?php
			}
			return;
		}

		// Get base URL for clearing filters.
		$base_link    = $this->get_current_page_url();
		$display_type = isset( $settings['display_type'] ) ? $settings['display_type'] : 'chips';
		$list_class   = 'list' === $display_type ? 'mpd-active-filters--list' : 'mpd-active-filters--chips';
		$item_class   = 'list' === $display_type ? 'mpd-active-filters__item' : 'mpd-active-filters__chip';
		?>
		<div class="mpd-active-filters widget_layered_nav_filters <?php echo esc_attr( $list_class ); ?>">
			<?php if ( 'yes' === $settings['show_title'] ) : ?>
				<<?php echo esc_attr( $settings['title_tag'] ); ?> class="mpd-active-filters__title">
					<?php echo esc_html( $settings['title_text'] ); ?>
				</<?php echo esc_attr( $settings['title_tag'] ); ?>>
			<?php endif; ?>

			<ul class="mpd-active-filters__list">
				<?php foreach ( $active_filters as $filter ) : ?>
					<li class="<?php echo esc_attr( $item_class ); ?>">
						<a href="<?php echo esc_url( $filter['link'] ); ?>" class="mpd-active-filters__link">
							<span class="mpd-active-filters__label"><?php echo esc_html( $filter['label'] ); ?></span>
							<span class="mpd-active-filters__remove"><?php echo esc_html( $settings['remove_icon'] ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php if ( 'yes' === $settings['show_clear_all'] && count( $active_filters ) > 1 ) : ?>
				<a href="<?php echo esc_url( $base_link ); ?>" class="mpd-active-filters__clear-all">
					<?php echo esc_html( $settings['clear_all_text'] ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get active filters from URL.
	 *
	 * @since 2.0.0
	 *
	 * @return array Active filters.
	 */
	protected function get_active_filters() {
		$filters   = array();
		$base_link = $this->get_current_page_url();

		// Get min price filter.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['min_price'] ) ) {
			$link = remove_query_arg( 'min_price', $base_link );
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$min_price = floatval( wc_clean( wp_unslash( $_GET['min_price'] ) ) );
			$filters[] = array(
				'label' => sprintf(
					/* translators: %s: minimum price */
					esc_html__( 'Min %s', 'magical-products-display' ),
					get_woocommerce_currency_symbol() . number_format( $min_price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() )
				),
				'link'  => $link,
			);
		}

		// Get max price filter.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['max_price'] ) ) {
			$link = remove_query_arg( 'max_price', $base_link );
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$max_price = floatval( wc_clean( wp_unslash( $_GET['max_price'] ) ) );
			$filters[] = array(
				'label' => sprintf(
					/* translators: %s: maximum price */
					esc_html__( 'Max %s', 'magical-products-display' ),
					get_woocommerce_currency_symbol() . number_format( $max_price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() )
				),
				'link'  => $link,
			);
		}

		// Get rating filter.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['rating_filter'] ) ) {
			$link = remove_query_arg( 'rating_filter', $base_link );
			$filters[] = array(
				'label' => sprintf(
					/* translators: %s: rating */
					esc_html__( '%s Star', 'magical-products-display' ),
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					wc_clean( wp_unslash( $_GET['rating_filter'] ) )
				),
				'link'  => $link,
			);
		}

		// Get attribute filters.
		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( $attribute_taxonomies ) {
				foreach ( $attribute_taxonomies as $tax ) {
					$taxonomy    = wc_attribute_taxonomy_name( $tax->attribute_name );
					$filter_name = 'filter_' . $tax->attribute_name;

					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					if ( isset( $_GET[ $filter_name ] ) && taxonomy_exists( $taxonomy ) ) {
						// phpcs:ignore WordPress.Security.NonceVerification.Recommended
						$raw_value = wc_clean( wp_unslash( $_GET[ $filter_name ] ) );
						// Handle both array and string formats
						$terms = is_array( $raw_value ) ? $raw_value : explode( ',', $raw_value );

						foreach ( $terms as $term_slug ) {
							$term = get_term_by( 'slug', $term_slug, $taxonomy );
							if ( $term ) {
								// Build link without this term.
								$remaining_terms = array_diff( $terms, array( $term_slug ) );
								if ( ! empty( $remaining_terms ) ) {
									$link = add_query_arg( $filter_name, implode( ',', $remaining_terms ), $base_link );
								} else {
									$link = remove_query_arg( $filter_name, $base_link );
								}

								$filters[] = array(
									'label' => $term->name,
									'link'  => $link,
								);
							}
						}
					}
				}
			}
		}

		return $filters;
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
			if ( $queried_object && isset( $queried_object->taxonomy, $queried_object->slug ) ) {
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
