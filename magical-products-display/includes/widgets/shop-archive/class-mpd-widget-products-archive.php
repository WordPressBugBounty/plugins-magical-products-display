<?php
/**
 * Products Archive Widget
 *
 * Displays WooCommerce products archive with various layout options.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\ShopArchive;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Products_Archive
 *
 * @since 2.0.0
 */
class Products_Archive extends Widget_Base {

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
	protected $widget_icon = 'eicon-products';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-products-archive';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Shop/Archive Products', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'products', 'archive', 'shop', 'grid', 'list', 'woocommerce', 'catalog' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-shop-archive-widgets', 'mpd-global-widgets' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		$scripts = array( 'mpd-global-widgets' );
		
		// Always include the script for Pro users - it only activates when data attributes are present
		if ( $this->is_pro() ) {
			$scripts[] = 'mpd-shop-archive';
		}
		
		return $scripts;
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		// Layout Section.
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout_type',
			array(
				'label'   => esc_html__( 'Layout', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid' => esc_html__( 'Grid', 'magical-products-display' ),
					'list' => esc_html__( 'List', 'magical-products-display' ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'     => esc_html__( 'Columns', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '4',
				'tablet_default' => '3',
				'mobile_default' => '2',
				'options'   => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'condition' => array(
					'layout_type' => 'grid',
				),
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
					'{{WRAPPER}} .mpd-products-archive__grid--masonry' => 'column-count: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__item' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .mpd-products-archive__content' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 30,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'size' => 20,
					'unit' => 'px',
				),
				'mobile_default' => array(
					'size' => 15,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__grid' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-products-archive__grid--masonry' => 'column-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-products-archive__grid--masonry .mpd-products-archive__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-products-archive__list' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Query Section.
		$this->start_controls_section(
			'section_query',
			array(
				'label' => esc_html__( 'Query', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'use_current_query',
			array(
				'label'        => esc_html__( 'Use Current Query', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Use the current archive query (category, tag, search results, etc.)', 'magical-products-display' ),
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'     => esc_html__( 'Products Per Page', 'magical-products-display' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 12,
				'min'       => 1,
				'max'       => 100,
				'condition' => array(
					'use_current_query!' => 'yes',
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'     => esc_html__( 'Order By', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'menu_order',
				'options'   => array(
					'menu_order' => esc_html__( 'Default', 'magical-products-display' ),
					'date'       => esc_html__( 'Date', 'magical-products-display' ),
					'title'      => esc_html__( 'Title', 'magical-products-display' ),
					'price'      => esc_html__( 'Price', 'magical-products-display' ),
					'popularity' => esc_html__( 'Popularity', 'magical-products-display' ),
					'rating'     => esc_html__( 'Rating', 'magical-products-display' ),
					'rand'       => esc_html__( 'Random', 'magical-products-display' ),
				),
				'condition' => array(
					'use_current_query!' => 'yes',
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'     => esc_html__( 'Order', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'ASC',
				'options'   => array(
					'ASC'  => esc_html__( 'Ascending', 'magical-products-display' ),
					'DESC' => esc_html__( 'Descending', 'magical-products-display' ),
				),
				'condition' => array(
					'use_current_query!' => 'yes',
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
				'default'      => 'yes',
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
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_sale_badge',
			array(
				'label'        => esc_html__( 'Show Sale Badge', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'badge_discount_type',
			array(
				'label'     => esc_html__( 'Discount Badge', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'hide',
				'options'   => array(
					'hide'       => esc_html__( 'Hide', 'magical-products-display' ),
					'percentage' => esc_html__( 'Percentage', 'magical-products-display' ),
					'number'     => esc_html__( 'Number', 'magical-products-display' ),
				),
				'condition' => array(
					'show_sale_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'badge_after_text',
			array(
				'label'       => esc_html__( 'After Text', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( ' OFF', 'magical-products-display' ),
				'placeholder' => esc_html__( 'e.g. OFF', 'magical-products-display' ),
				'condition'   => array(
					'show_sale_badge'     => 'yes',
					'badge_discount_type' => array( 'percentage', 'number' ),
				),
			)
		);

		$this->add_control(
			'show_category',
			array(
				'label'        => esc_html__( 'Show Category', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'category_display_type',
			array(
				'label'     => esc_html__( 'Category Display Type', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'first',
				'options'   => array(
					'first'  => esc_html__( 'First Category', 'magical-products-display' ),
					'random' => esc_html__( 'Random Category', 'magical-products-display' ),
					'all'    => esc_html__( 'All Categories', 'magical-products-display' ),
				),
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Pro Features Section.
		$this->start_controls_section(
			'section_pro_features',
			array(
				'label' => esc_html__( 'Advanced Features', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'pro_features_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => $this->get_pro_notice( esc_html__( 'Masonry layout and infinite scroll are Pro features.', 'magical-products-display' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'enable_masonry',
			array(
				'label'        => esc_html__( 'Enable Masonry', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'layout_type' => 'grid',
				),
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->add_control(
			'enable_infinite_scroll',
			array(
				'label'        => esc_html__( 'Enable Infinite Scroll', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'classes'      => $this->is_pro() ? '' : 'mpd-pro-disabled',
			)
		);

		$this->end_controls_section();

		// Compare & Wishlist Section (Pro).
		$this->start_controls_section(
			'section_compare_wishlist',
			array(
				'label' => $this->pro_label( esc_html__( 'Compare & Wishlist', 'magical-products-display' ) ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! $this->is_pro() ) {
			$this->add_pro_notice( 'pro_cw_notice', esc_html__( 'Compare & Wishlist Buttons', 'magical-products-display' ) );
		}

		$this->add_control(
			'show_compare_btn',
			array(
				'label'        => esc_html__( 'Show Compare Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_wishlist_btn',
			array(
				'label'        => esc_html__( 'Show Wishlist Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		// Action Buttons Section.
		$this->start_controls_section(
			'section_action_buttons',
			array(
				'label' => esc_html__( 'Action Buttons', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_quickview_btn',
			array(
				'label'        => esc_html__( 'Show Quick View Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'action_btn_position',
			array(
				'label'     => esc_html__( 'Button Position', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'image_center',
				'options'   => array(
					'image_center' => esc_html__( 'Image Center', 'magical-products-display' ),
					'top_right'    => esc_html__( 'Top Right', 'magical-products-display' ),
					'top_left'     => esc_html__( 'Top Left', 'magical-products-display' ),
					'below_image'  => esc_html__( 'Below Image', 'magical-products-display' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array( 'name' => 'show_compare_btn', 'operator' => '==', 'value' => 'yes' ),
						array( 'name' => 'show_wishlist_btn', 'operator' => '==', 'value' => 'yes' ),
						array( 'name' => 'show_quickview_btn', 'operator' => '==', 'value' => 'yes' ),
					),
				),
			)
		);

		$this->add_control(
			'action_btn_show_on_hover',
			array(
				'label'        => esc_html__( 'Show Only on Hover', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'action_btn_position!' => 'below_image',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array( 'name' => 'show_compare_btn', 'operator' => '==', 'value' => 'yes' ),
						array( 'name' => 'show_wishlist_btn', 'operator' => '==', 'value' => 'yes' ),
						array( 'name' => 'show_quickview_btn', 'operator' => '==', 'value' => 'yes' ),
					),
				),
			)
		);

		$this->add_control(
			'action_btn_style',
			array(
				'label'     => esc_html__( 'Button Style', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'icon_only',
				'options'   => array(
					'icon_only' => esc_html__( 'Icon Only', 'magical-products-display' ),
					'icon_text' => esc_html__( 'Icon + Text', 'magical-products-display' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array( 'name' => 'show_compare_btn', 'operator' => '==', 'value' => 'yes' ),
						array( 'name' => 'show_wishlist_btn', 'operator' => '==', 'value' => 'yes' ),
						array( 'name' => 'show_quickview_btn', 'operator' => '==', 'value' => 'yes' ),
					),
				),
			)
		);

		$this->end_controls_section();

		// No Results Section.
		$this->start_controls_section(
			'section_no_results',
			array(
				'label' => esc_html__( 'No Results', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'no_results_message',
			array(
				'label'   => esc_html__( 'No Results Message', 'magical-products-display' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'No products were found matching your selection.', 'magical-products-display' ),
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
		// Product Card Style Section.
		$this->start_controls_section(
			'section_product_card_style',
			array(
				'label' => esc_html__( 'Product Card', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'product_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-products-archive__item',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'product_border',
				'selector' => '{{WRAPPER}} .mpd-products-archive__item',
			)
		);

		$this->add_responsive_control(
			'product_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'product_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-products-archive__item',
			)
		);

		$this->add_responsive_control(
			'product_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Image Style Section.
		$this->start_controls_section(
			'section_image_style',
			array(
				'label'     => esc_html__( 'Image', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

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
					'{{WRAPPER}} .mpd-products-archive__title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__title a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-products-archive__title',
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
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
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'sale_price_color',
			array(
				'label'     => esc_html__( 'Sale Price Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__price ins' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .mpd-products-archive__price',
			)
		);

		$this->add_responsive_control(
			'price_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Category Style Section.
		$this->start_controls_section(
			'section_category_style',
			array(
				'label'     => esc_html__( 'Category', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_category' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__category, {{WRAPPER}} .mpd-products-archive__category a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'category_hover_color',
			array(
				'label'     => esc_html__( 'Hover Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__category a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'category_typography',
				'selector' => '{{WRAPPER}} .mpd-products-archive__category',
			)
		);

		$this->add_responsive_control(
			'category_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__category' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Rating Style Section.
		$this->start_controls_section(
			'section_rating_style',
			array(
				'label'     => esc_html__( 'Rating', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_rating' => 'yes',
				),
			)
		);

		$this->add_control(
			'star_empty_color',
			array(
				'label'     => esc_html__( 'Empty Star Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-star-rating__empty' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'star_filled_color',
			array(
				'label'     => esc_html__( 'Filled Star Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-star-rating__filled' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'star_size',
			array(
				'label'      => esc_html__( 'Star Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-star-rating' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'rating_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Button Style Section.
		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => esc_html__( 'Add to Cart Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_add_to_cart' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart a.button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-products-archive__add-to-cart a.button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart a.button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hover_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .mpd-products-archive__add-to-cart a.button:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'selector'  => '{{WRAPPER}} .mpd-products-archive__add-to-cart a.button',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-products-archive__add-to-cart a.button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// View Cart Style Section.
		$this->start_controls_section(
			'section_view_cart_style',
			array(
				'label'     => esc_html__( 'View Cart Link', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_add_to_cart' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'view_cart_tabs' );

		$this->start_controls_tab(
			'view_cart_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'view_cart_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart .added_to_cart' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart .added_to_cart' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart .added_to_cart' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'view_cart_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'view_cart_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart .added_to_cart:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_hover_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart .added_to_cart:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'view_cart_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart .added_to_cart:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'view_cart_typography',
				'selector'  => '{{WRAPPER}} .mpd-products-archive__add-to-cart .added_to_cart',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'view_cart_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart .added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'view_cart_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'view_cart_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__add-to-cart .added_to_cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Sale Badge Style Section.
		$this->start_controls_section(
			'section_badge_style',
			array(
				'label'     => esc_html__( 'Sale Badge', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_sale_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-products-archive__badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .mpd-products-archive__badge',
			)
		);

		$this->add_responsive_control(
			'badge_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badge_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-products-archive__badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Action Buttons Style Section.
		$this->start_controls_section(
			'section_action_buttons_style',
			array(
				'label'      => esc_html__( 'Action Buttons', 'magical-products-display' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array( 'name' => 'show_compare_btn', 'operator' => '==', 'value' => 'yes' ),
						array( 'name' => 'show_wishlist_btn', 'operator' => '==', 'value' => 'yes' ),
						array( 'name' => 'show_quickview_btn', 'operator' => '==', 'value' => 'yes' ),
					),
				),
			)
		);

		$this->add_control(
			'action_btn_general_heading',
			array(
				'label' => esc_html__( 'General', 'magical-products-display' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'action_btn_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 40,
					),
				),
				'default'    => array(
					'size' => 14,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-action-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'action_btn_typography',
				'label'     => esc_html__( 'Typography', 'magical-products-display' ),
				'selector'  => '{{WRAPPER}} .mpd-action-btn span',
				'condition' => array(
					'action_btn_style' => 'icon_text',
				),
			)
		);

		$this->add_responsive_control(
			'action_btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-action-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'action_btn_gap',
			array(
				'label'      => esc_html__( 'Buttons Gap', 'magical-products-display' ),
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
					'{{WRAPPER}} .mpd-action-buttons' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'action_btn_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-action-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Normal/Hover tabs
		$this->start_controls_tabs( 'action_btn_tabs' );

		$this->start_controls_tab(
			'action_btn_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'action_btn_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-action-btn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-action-btn i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'action_btn_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-action-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'action_btn_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'action_btn_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-action-btn:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-action-btn:hover i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'action_btn_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .mpd-action-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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
		$use_current_query = 'yes' === $settings['use_current_query'];

		// Get products.
		if ( $use_current_query && ( is_shop() || is_product_taxonomy() || is_search() ) ) {
			// Use WooCommerce's main query.
			global $wp_query;
			$products_query = $wp_query;
		} else {
			// Build custom query.
			$products_query = $this->get_products_query( $settings );
		}

		$layout_class = 'mpd-products-archive__' . esc_attr( $settings['layout_type'] );
		
		// Check for Pro features
		$enable_masonry = $this->is_pro() && 'yes' === ( $settings['enable_masonry'] ?? '' ) && 'grid' === $settings['layout_type'];
		$enable_infinite_scroll = $this->is_pro() && 'yes' === ( $settings['enable_infinite_scroll'] ?? '' );
		
		// Add masonry class to layout
		if ( $enable_masonry ) {
			$layout_class .= ' mpd-products-archive__grid--masonry';
		}
		
		// Get posts per page for AJAX
		$posts_per_page = $products_query->get( 'posts_per_page' );
		if ( empty( $posts_per_page ) || $posts_per_page < 1 ) {
			$posts_per_page = isset( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 12;
		}
		
		// Build container data attributes
		$container_attrs = array(
			'class' => 'mpd-products-archive',
		);
		
		// Always add widget ID and post ID for AJAX filtering
		$container_attrs['data-widget-id'] = $this->get_id();
		// Get the Elementor document ID (template ID) for AJAX
		$document = \Elementor\Plugin::$instance->documents->get_current();
		$container_attrs['data-post-id'] = $document ? $document->get_main_id() : get_the_ID();
		$container_attrs['data-posts-per-page'] = $posts_per_page;
		
		if ( $enable_masonry ) {
			$container_attrs['data-masonry'] = 'yes';
		}
		
		if ( $enable_infinite_scroll ) {
			$container_attrs['data-infinite-scroll'] = 'yes';
			$container_attrs['data-max-pages'] = $products_query->max_num_pages;
		}
		
		$container_attr_string = '';
		foreach ( $container_attrs as $attr => $value ) {
			$container_attr_string .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
		}
		?>
		<div<?php echo $container_attr_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( $products_query->have_posts() ) : ?>
				<div class="<?php echo esc_attr( $layout_class ); ?>">
					<?php
					while ( $products_query->have_posts() ) :
						$products_query->the_post();
						global $product;

						if ( ! $product || ! $product instanceof \WC_Product ) {
							continue;
						}

						$this->render_product_item( $product, $settings );
					endwhile;
					?>
				</div>
			<?php else : ?>
				<div class="mpd-products-archive__no-results">
					<p><?php echo esc_html( $settings['no_results_message'] ); ?></p>
				</div>
			<?php endif; ?>
		</div>
		<?php
		// Reset post data if we ran a custom query.
		if ( ! $use_current_query || ( ! is_shop() && ! is_product_taxonomy() && ! is_search() ) ) {
			wp_reset_postdata();
		}
	}

	/**
	 * Get products query.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return \WP_Query Products query.
	 */
	protected function get_products_query( $settings ) {
		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $settings['posts_per_page'],
			'orderby'        => $settings['orderby'],
			'order'          => $settings['order'],
		);

		// Check for orderby from URL (WooCommerce ordering widget)
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['orderby'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$args['orderby'] = sanitize_text_field( wp_unslash( $_GET['orderby'] ) );
		}

		// Handle special orderby cases.
		$orderby = $args['orderby'];
		if ( 'price' === $orderby ) {
			$args['meta_key'] = '_price';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'ASC';
		} elseif ( 'price-desc' === $orderby ) {
			$args['meta_key'] = '_price';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
		} elseif ( 'popularity' === $orderby ) {
			$args['meta_key'] = 'total_sales';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
		} elseif ( 'rating' === $orderby ) {
			$args['meta_key'] = '_wc_average_rating';
			$args['orderby']  = 'meta_value_num';
			$args['order']    = 'DESC';
		} elseif ( 'date' === $orderby ) {
			$args['orderby'] = 'date';
			$args['order']   = 'DESC';
		}

		// Initialize tax_query - only show visible products.
		$args['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => array( 'exclude-from-catalog', 'exclude-from-search' ),
				'operator' => 'NOT IN',
			),
		);

		// Apply WooCommerce attribute filters from URL
		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			foreach ( $attribute_taxonomies as $taxonomy ) {
				$filter_name = 'filter_' . $taxonomy->attribute_name;
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( isset( $_GET[ $filter_name ] ) ) {
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$filter_value = wc_clean( wp_unslash( $_GET[ $filter_name ] ) );
					$terms = is_array( $filter_value ) ? $filter_value : explode( ',', $filter_value );
					
					if ( ! empty( $terms ) ) {
						$args['tax_query'][] = array(
							'taxonomy' => 'pa_' . $taxonomy->attribute_name,
							'field'    => 'slug',
							'terms'    => $terms,
							'operator' => 'IN',
						);
					}
				}
			}
		}

		// Apply category filter from URL
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['product_cat'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$cat_value = wc_clean( wp_unslash( $_GET['product_cat'] ) );
			$cat_terms = is_array( $cat_value ) ? $cat_value : explode( ',', $cat_value );
			
			if ( ! empty( $cat_terms ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $cat_terms,
					'operator' => 'IN',
				);
			}
		}

		// Apply tag filter from URL
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['product_tag'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$tag_value = wc_clean( wp_unslash( $_GET['product_tag'] ) );
			$tag_terms = is_array( $tag_value ) ? $tag_value : explode( ',', $tag_value );
			
			if ( ! empty( $tag_terms ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_tag',
					'field'    => 'slug',
					'terms'    => $tag_terms,
					'operator' => 'IN',
				);
			}
		}

		// Apply product_brand filter from URL (custom brand taxonomy)
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['product_brand'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$brand_value = wc_clean( wp_unslash( $_GET['product_brand'] ) );
			$brand_terms = is_array( $brand_value ) ? $brand_value : explode( ',', $brand_value );
			
			if ( ! empty( $brand_terms ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_brand',
					'field'    => 'slug',
					'terms'    => $brand_terms,
					'operator' => 'IN',
				);
			}
		}

		// Apply price filter from URL using WooCommerce native meta query
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$has_min_price = isset( $_GET['min_price'] ) && '' !== sanitize_text_field( wp_unslash( $_GET['min_price'] ) );
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$has_max_price = isset( $_GET['max_price'] ) && '' !== sanitize_text_field( wp_unslash( $_GET['max_price'] ) );
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();
		
		if ( $has_min_price || $has_max_price ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$min_price = $has_min_price ? floatval( wc_clean( wp_unslash( $_GET['min_price'] ) ) ) : 0;
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$max_price = $has_max_price ? floatval( wc_clean( wp_unslash( $_GET['max_price'] ) ) ) : PHP_INT_MAX;
			
			// Use WooCommerce's native price meta query
			$meta_query['price_filter'] = array(
				'key'     => '_price',
				'value'   => array( $min_price, $max_price ),
				'compare' => 'BETWEEN',
				'type'    => 'DECIMAL(10,2)',
			);
		}

		// Apply rating filter from URL
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['rating_filter'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$rating = absint( wc_clean( wp_unslash( $_GET['rating_filter'] ) ) );
			$meta_query['rating_filter'] = array(
				'key'     => '_wc_average_rating',
				'value'   => $rating,
				'compare' => '>=',
				'type'    => 'DECIMAL(2,1)',
			);
		}

		// Apply stock status filter from URL
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['stock_status'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$stock_status = wc_clean( wp_unslash( $_GET['stock_status'] ) );
			if ( in_array( $stock_status, array( 'instock', 'outofstock', 'onbackorder' ), true ) ) {
				$meta_query['stock_filter'] = array(
					'key'     => '_stock_status',
					'value'   => $stock_status,
					'compare' => '=',
				);
			}
		}

		// Apply attribute taxonomies from URL (pa_brand, pa_color, etc.)
		if ( function_exists( 'wc_get_attribute_taxonomy_names' ) ) {
			$attribute_taxonomies = wc_get_attribute_taxonomy_names();
			foreach ( $attribute_taxonomies as $taxonomy ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( isset( $_GET[ $taxonomy ] ) ) {
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$tax_value = wc_clean( wp_unslash( $_GET[ $taxonomy ] ) );
					$tax_terms = is_array( $tax_value ) ? $tax_value : explode( ',', $tax_value );
					
					if ( ! empty( $tax_terms ) ) {
						$args['tax_query'][] = array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => $tax_terms,
							'operator' => 'IN',
						);
					}
				}
			}
		}

		// Apply any other custom product taxonomies from URL
		// This handles dynamically configured brand taxonomies
		$product_taxonomies = get_object_taxonomies( 'product', 'names' );
		$excluded_taxonomies = array( 'product_cat', 'product_tag', 'product_type', 'product_visibility', 'product_shipping_class', 'product_brand' );
		foreach ( $product_taxonomies as $taxonomy ) {
			// Skip already handled taxonomies and pa_* attributes
			if ( in_array( $taxonomy, $excluded_taxonomies, true ) || strpos( $taxonomy, 'pa_' ) === 0 ) {
				continue;
			}
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET[ $taxonomy ] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$tax_value = wc_clean( wp_unslash( $_GET[ $taxonomy ] ) );
				$tax_terms = is_array( $tax_value ) ? $tax_value : explode( ',', $tax_value );
				
				if ( ! empty( $tax_terms ) ) {
					$args['tax_query'][] = array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $tax_terms,
						'operator' => 'IN',
					);
				}
			}
		}

		// Apply on sale filter from URL
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['on_sale'] ) && 'yes' === wc_clean( wp_unslash( $_GET['on_sale'] ) ) ) {
			$on_sale_ids = wc_get_product_ids_on_sale();
			$args['post__in'] = ! empty( $args['post__in'] ) 
				? array_intersect( $args['post__in'], $on_sale_ids ) 
				: $on_sale_ids;
			// Ensure we have at least one product to avoid empty query
			if ( empty( $args['post__in'] ) ) {
				$args['post__in'] = array( 0 );
			}
		}

		// Apply featured filter from URL
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['featured'] ) && 'yes' === wc_clean( wp_unslash( $_GET['featured'] ) ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			);
		}

		if ( ! empty( $meta_query ) ) {
			$args['meta_query'] = $meta_query;
		}

		return new \WP_Query( $args );
	}

	/**
	 * Render product item.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  Product object.
	 * @param array       $settings Widget settings.
	 * @return void
	 */
	protected function render_product_item( $product, $settings ) {
		$show_compare   = $this->is_pro() && 'yes' === ( $settings['show_compare_btn'] ?? '' );
		$show_wishlist  = $this->is_pro() && 'yes' === ( $settings['show_wishlist_btn'] ?? '' );
		$show_quickview = 'yes' === ( $settings['show_quickview_btn'] ?? '' );
		$has_action_buttons = $show_compare || $show_wishlist || $show_quickview;
		$btn_position   = $settings['action_btn_position'] ?? 'image_center';
		?>
		<div class="mpd-products-archive__item" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
			<?php if ( 'yes' === $settings['show_sale_badge'] && $product->is_on_sale() ) : ?>
				<span class="mpd-products-archive__badge"><?php echo wp_kses_post( $this->get_sale_badge_text( $product, $settings ) ); ?></span>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_image'] ) : ?>
				<div class="mpd-products-archive__image">
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
						<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
					</a>
					<?php 
					// Render action buttons on image positions (center, top-right, top-left)
					if ( $has_action_buttons && in_array( $btn_position, array( 'image_center', 'top_right', 'top_left' ), true ) ) {
						$this->render_action_buttons( $product, $settings );
					}
					?>
				</div>
			<?php endif; ?>

			<?php 
			// Render action buttons below image
			if ( $has_action_buttons && 'below_image' === $btn_position ) {
				$this->render_action_buttons( $product, $settings );
			}
			?>

			<div class="mpd-products-archive__content">
				<?php if ( 'yes' === ( $settings['show_category'] ?? '' ) ) : ?>
					<div class="mpd-products-archive__category">
						<?php echo wp_kses_post( $this->get_product_categories( $product, $settings ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_title'] ) : ?>
					<h3 class="mpd-products-archive__title">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<?php echo esc_html( $product->get_name() ); ?>
						</a>
					</h3>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_rating'] && wc_review_ratings_enabled() ) : ?>
					<div class="mpd-products-archive__rating">
						<?php echo wp_kses_post( $this->get_star_rating_html( $product ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_price'] ) : ?>
					<div class="mpd-products-archive__price">
						<?php echo wp_kses_post( $product->get_price_html() ); ?>
					</div>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_add_to_cart'] ) : ?>
					<div class="mpd-products-archive__add-to-cart<?php echo \Elementor\Plugin::$instance->editor->is_edit_mode() ? ' mpd-editor-mode' : ''; ?>">
						<?php woocommerce_template_loop_add_to_cart(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get sale badge text with optional discount.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  Product object.
	 * @param array       $settings Widget settings.
	 * @return string
	 */
	protected function get_sale_badge_text( $product, $settings ) {
		$discount_type = $settings['badge_discount_type'] ?? 'hide';
		$after_text    = $settings['badge_after_text'] ?? '';

		if ( 'hide' === $discount_type ) {
			return esc_html__( 'Sale!', 'magical-products-display' );
		}

		// Calculate discount
		$regular_price = (float) $product->get_regular_price();
		$sale_price    = (float) $product->get_sale_price();

		// Handle variable products
		if ( $product->is_type( 'variable' ) ) {
			$regular_price = (float) $product->get_variation_regular_price( 'max' );
			$sale_price    = (float) $product->get_variation_sale_price( 'min' );
		}

		if ( $regular_price <= 0 || $sale_price <= 0 ) {
			return esc_html__( 'Sale!', 'magical-products-display' );
		}

		$discount = $regular_price - $sale_price;

		if ( 'percentage' === $discount_type ) {
			$percentage = round( ( $discount / $regular_price ) * 100 );
			return $percentage . '%' . esc_html( $after_text );
		} elseif ( 'number' === $discount_type ) {
			// Format price without HTML and decimals for badge display
			$currency_symbol = get_woocommerce_currency_symbol();
			$formatted_discount = $currency_symbol . number_format( $discount, 0, '', wc_get_price_thousand_separator() );
			return $formatted_discount . esc_html( $after_text );
		}

		return esc_html__( 'Sale!', 'magical-products-display' );
	}

	/**
	 * Get star rating HTML with icons.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product Product object.
	 * @return string
	 */
	protected function get_star_rating_html( $product ) {
		$average = $product->get_average_rating();
		$rating_percentage = ( $average / 5 ) * 100;

		ob_start();
		?>
		<div class="mpd-star-rating">
			<span class="mpd-star-rating__empty">
				<i class="eicon-star-o"></i>
				<i class="eicon-star-o"></i>
				<i class="eicon-star-o"></i>
				<i class="eicon-star-o"></i>
				<i class="eicon-star-o"></i>
			</span>
			<span class="mpd-star-rating__filled" style="width: <?php echo esc_attr( $rating_percentage ); ?>%;">
				<i class="eicon-star"></i>
				<i class="eicon-star"></i>
				<i class="eicon-star"></i>
				<i class="eicon-star"></i>
				<i class="eicon-star"></i>
			</span>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get product categories HTML.
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  Product object.
	 * @param array       $settings Widget settings.
	 * @return string
	 */
	protected function get_product_categories( $product, $settings ) {
		$display_type = $settings['category_display_type'] ?? 'first';
		$terms = get_the_terms( $product->get_id(), 'product_cat' );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return '';
		}

		$categories = array();

		if ( 'first' === $display_type ) {
			$term = reset( $terms );
			$categories[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
		} elseif ( 'random' === $display_type ) {
			$random_key = array_rand( $terms );
			$term = $terms[ $random_key ];
			$categories[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
		} else { // all
			foreach ( $terms as $term ) {
				$categories[] = '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
			}
		}

		return implode( ', ', $categories );
	}

	/**
	 * Render action buttons (Compare, Wishlist, Quick View)
	 *
	 * @since 2.0.0
	 *
	 * @param \WC_Product $product  Product object.
	 * @param array       $settings Widget settings.
	 * @return void
	 */
	protected function render_action_buttons( $product, $settings ) {
		$show_compare   = $this->is_pro() && 'yes' === ( $settings['show_compare_btn'] ?? '' );
		$show_wishlist  = $this->is_pro() && 'yes' === ( $settings['show_wishlist_btn'] ?? '' );
		$show_quickview = 'yes' === ( $settings['show_quickview_btn'] ?? '' );

		if ( ! $show_compare && ! $show_wishlist && ! $show_quickview ) {
			return;
		}

		$product_id   = $product->get_id();
		$btn_style    = $settings['action_btn_style'] ?? 'icon_only';
		$btn_position = $settings['action_btn_position'] ?? 'image_center';
		$show_on_hover = 'yes' === ( $settings['action_btn_show_on_hover'] ?? 'yes' );
		$show_text    = 'icon_text' === $btn_style;

		// Position classes
		$position_class = 'mpd-action-buttons';
		if ( 'image_center' === $btn_position ) {
			$position_class .= ' mpd-action-buttons--image-center';
		} elseif ( 'top_right' === $btn_position ) {
			$position_class .= ' mpd-action-buttons--top-right';
		} elseif ( 'top_left' === $btn_position ) {
			$position_class .= ' mpd-action-buttons--top-left';
		} elseif ( 'below_image' === $btn_position ) {
			$position_class .= ' mpd-action-buttons--below-image';
		}
		
		// Add hover visibility class (only for non-below positions)
		if ( 'below_image' !== $btn_position && $show_on_hover ) {
			$position_class .= ' mpd-action-buttons--hover-only';
		}
		?>
		<div class="<?php echo esc_attr( $position_class ); ?>">
			<?php if ( $show_wishlist ) : ?>
				<button type="button" class="mpd-wishlist-btn mpd-action-btn" data-product-id="<?php echo esc_attr( $product_id ); ?>" title="<?php esc_attr_e( 'Add to Wishlist', 'magical-products-display' ); ?>">
					<i class="eicon-heart-o"></i>
					<?php if ( $show_text ) : ?>
						<span><?php esc_html_e( 'Wishlist', 'magical-products-display' ); ?></span>
					<?php endif; ?>
				</button>
			<?php endif; ?>

			<?php if ( $show_compare ) : ?>
				<button type="button" class="mpd-compare-btn mpd-action-btn" data-product-id="<?php echo esc_attr( $product_id ); ?>" title="<?php esc_attr_e( 'Compare', 'magical-products-display' ); ?>">
					<i class="eicon-exchange"></i>
					<?php if ( $show_text ) : ?>
						<span><?php esc_html_e( 'Compare', 'magical-products-display' ); ?></span>
					<?php endif; ?>
				</button>
			<?php endif; ?>

			<?php if ( $show_quickview ) : ?>
				<button type="button" class="mpd-quick-view-btn mpd-action-btn" data-product-id="<?php echo esc_attr( $product_id ); ?>" title="<?php esc_attr_e( 'Quick View', 'magical-products-display' ); ?>">
					<i class="eicon-zoom-in-bold"></i>
					<?php if ( $show_text ) : ?>
						<span><?php esc_html_e( 'Quick View', 'magical-products-display' ); ?></span>
					<?php endif; ?>
				</button>
			<?php endif; ?>
		</div>
		<?php
	}
}
