<?php
/**
 * Advanced Filter Widget
 *
 * Displays comprehensive product filters (categories, tags, brands, price, stock, featured, rating)
 * in a sidebar, popup, or normal display with AJAX support.
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
use Elementor\Group_Control_Background;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Advanced_Filter
 *
 * @since 2.0.0
 */
class Advanced_Filter extends Widget_Base {

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
		return 'mpd-advanced-filter';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Advanced Filter', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'filter', 'advanced', 'products', 'shop', 'woocommerce', 'category', 'price', 'attribute', 'sidebar', 'popup' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-shop-archive-widgets', 'mpd-advanced-filter' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script dependencies.
	 */
	public function get_script_depends() {
		return array( 'wc-price-slider', 'mpd-shop-archive', 'mpd-advanced-filter' );
	}

	/**
	 * Register content controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		// Display Settings Section.
		$this->start_controls_section(
			'section_display',
			array(
				'label' => esc_html__( 'Display Settings', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'display_style',
			array(
				'label'       => esc_html__( 'Display Style', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'normal',
				'options'     => array(
					'normal'       => esc_html__( 'Normal (Always Visible)', 'magical-products-display' ),
					'inline'       => esc_html__( 'Inline Dropdowns (Horizontal Bar)', 'magical-products-display' ),
					'popup'        => esc_html__( 'Popup (Click Button to Open)', 'magical-products-display' ),
					'sidebar'      => esc_html__( 'Sidebar (Slide from Side)', 'magical-products-display' ),
				),
				'description' => esc_html__( 'Display style for desktop/tablet.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'display_style_mobile',
			array(
				'label'       => esc_html__( 'Mobile Display Style', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''             => esc_html__( 'Same as Desktop', 'magical-products-display' ),
					'normal'       => esc_html__( 'Normal (Always Visible)', 'magical-products-display' ),
					'inline'       => esc_html__( 'Inline Dropdowns (Horizontal Bar)', 'magical-products-display' ),
					'popup'        => esc_html__( 'Popup (Click Button to Open)', 'magical-products-display' ),
					'sidebar'      => esc_html__( 'Sidebar (Slide from Side)', 'magical-products-display' ),
				),
				'description' => esc_html__( 'Override display style on mobile devices.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'sidebar_position',
			array(
				'label'     => esc_html__( 'Sidebar Position', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => esc_html__( 'Left', 'magical-products-display' ),
					'right' => esc_html__( 'Right', 'magical-products-display' ),
				),
				'condition' => array(
					'display_style' => 'sidebar',
				),
			)
		);

		$this->add_control(
			'inline_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter--inline .mpd-advanced-filter__inline-bar' => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'display_style' => 'inline',
				),
			)
		);

		$this->add_control(
			'inline_gap',
			array(
				'label'      => esc_html__( 'Gap Between Items', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter--inline .mpd-advanced-filter__inline-bar' => 'gap: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'display_style' => 'inline',
				),
			)
		);

		$this->add_control(
			'toggle_button_text',
			array(
				'label'      => esc_html__( 'Toggle Button Text', 'magical-products-display' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => esc_html__( 'Filter Products', 'magical-products-display' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'display_style',
							'operator' => 'in',
							'value'    => array( 'popup', 'sidebar' ),
						),
						array(
							'name'     => 'display_style_mobile',
							'operator' => 'in',
							'value'    => array( 'popup', 'sidebar' ),
						),
					),
				),
			)
		);

		$this->add_control(
			'toggle_button_icon',
			array(
				'label'      => esc_html__( 'Toggle Button Icon', 'magical-products-display' ),
				'type'       => Controls_Manager::ICONS,
				'default'    => array(
					'value'   => 'fas fa-filter',
					'library' => 'fa-solid',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'display_style',
							'operator' => 'in',
							'value'    => array( 'popup', 'sidebar' ),
						),
						array(
							'name'     => 'display_style_mobile',
							'operator' => 'in',
							'value'    => array( 'popup', 'sidebar' ),
						),
					),
				),
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => esc_html__( 'Left', 'magical-products-display' ),
					'right' => esc_html__( 'Right', 'magical-products-display' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'display_style',
							'operator' => 'in',
							'value'    => array( 'popup', 'sidebar' ),
						),
						array(
							'name'     => 'display_style_mobile',
							'operator' => 'in',
							'value'    => array( 'popup', 'sidebar' ),
						),
					),
				),
			)
		);

		$this->add_control(
			'show_filter_title',
			array(
				'label'        => esc_html__( 'Show Filter Title', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'filter_title',
			array(
				'label'     => esc_html__( 'Filter Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Filter Products', 'magical-products-display' ),
				'condition' => array(
					'show_filter_title' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Category Filter Section.
		$this->start_controls_section(
			'section_category_filter',
			array(
				'label' => esc_html__( 'Category Filter', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_category_filter',
			array(
				'label'        => esc_html__( 'Enable Category Filter', 'magical-products-display' ),
				'description'  => esc_html__( 'Allow visitors to filter products by categories.', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'category_filter_title',
			array(
				'label'     => esc_html__( 'Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Categories', 'magical-products-display' ),
				'condition' => array(
					'show_category_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_display_type',
			array(
				'label'     => esc_html__( 'Display Type', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'list',
				'options'   => array(
					'list'     => esc_html__( 'List', 'magical-products-display' ),
					'dropdown' => esc_html__( 'Dropdown', 'magical-products-display' ),
					'checkbox' => esc_html__( 'Checkboxes', 'magical-products-display' ),
				),
				'condition' => array(
					'show_category_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_show_count',
			array(
				'label'        => esc_html__( 'Show Product Count', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_category_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_show_hierarchy',
			array(
				'label'        => esc_html__( 'Show Hierarchy', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_category_filter'   => 'yes',
					'category_display_type!' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'category_hide_empty',
			array(
				'label'        => esc_html__( 'Hide Empty Categories', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_category_filter' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Tags Filter Section.
		$this->start_controls_section(
			'section_tags_filter',
			array(
				'label' => esc_html__( 'Tags Filter', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_tags_filter',
			array(
				'label'        => esc_html__( 'Enable Tags Filter', 'magical-products-display' ),
				'description'  => esc_html__( 'Allow visitors to filter products by tags.', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'tags_filter_title',
			array(
				'label'     => esc_html__( 'Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Tags', 'magical-products-display' ),
				'condition' => array(
					'show_tags_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'tags_display_type',
			array(
				'label'     => esc_html__( 'Display Type', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'list',
				'options'   => array(
					'list'     => esc_html__( 'List', 'magical-products-display' ),
					'dropdown' => esc_html__( 'Dropdown', 'magical-products-display' ),
					'checkbox' => esc_html__( 'Checkboxes', 'magical-products-display' ),
					'cloud'    => esc_html__( 'Tag Cloud', 'magical-products-display' ),
				),
				'condition' => array(
					'show_tags_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'tags_show_count',
			array(
				'label'        => esc_html__( 'Show Product Count', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_tags_filter' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Brand Filter Section.
		$this->start_controls_section(
			'section_brand_filter',
			array(
				'label' => esc_html__( 'Brand Filter', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_brand_filter',
			array(
				'label'        => esc_html__( 'Enable Brand Filter', 'magical-products-display' ),
				'description'  => esc_html__( 'Allow visitors to filter products by brands.', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'brand_filter_title',
			array(
				'label'     => esc_html__( 'Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Brands', 'magical-products-display' ),
				'condition' => array(
					'show_brand_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'brand_taxonomy',
			array(
				'label'       => esc_html__( 'Brand Taxonomy', 'magical-products-display' ),
				'description' => esc_html__( 'Enter the taxonomy slug for brands (e.g., product_brand, pa_brand).', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'product_brand',
				'condition'   => array(
					'show_brand_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'brand_display_type',
			array(
				'label'     => esc_html__( 'Display Type', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'list',
				'options'   => array(
					'list'     => esc_html__( 'List', 'magical-products-display' ),
					'dropdown' => esc_html__( 'Dropdown', 'magical-products-display' ),
					'checkbox' => esc_html__( 'Checkboxes', 'magical-products-display' ),
				),
				'condition' => array(
					'show_brand_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'brand_show_count',
			array(
				'label'        => esc_html__( 'Show Product Count', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_brand_filter' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Price Filter Section.
		$this->start_controls_section(
			'section_price_filter',
			array(
				'label' => esc_html__( 'Price Filter', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_price_filter',
			array(
				'label'        => esc_html__( 'Enable Price Filter', 'magical-products-display' ),
				'description'  => esc_html__( 'Allow visitors to filter products by price range.', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'price_filter_title',
			array(
				'label'     => esc_html__( 'Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Price Range', 'magical-products-display' ),
				'condition' => array(
					'show_price_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'price_display_type',
			array(
				'label'     => esc_html__( 'Display Type', 'magical-products-display' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'slider',
				'options'   => array(
					'slider' => esc_html__( 'Range Slider', 'magical-products-display' ),
					'inputs' => esc_html__( 'Input Fields', 'magical-products-display' ),
				),
				'condition' => array(
					'show_price_filter' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Stock Filter Section.
		$this->start_controls_section(
			'section_stock_filter',
			array(
				'label' => esc_html__( 'Stock Filter', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_stock_filter',
			array(
				'label'        => esc_html__( 'Enable Stock Filter', 'magical-products-display' ),
				'description'  => esc_html__( 'Allow visitors to filter products by stock status.', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'stock_filter_title',
			array(
				'label'     => esc_html__( 'Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Availability', 'magical-products-display' ),
				'condition' => array(
					'show_stock_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'stock_in_stock_text',
			array(
				'label'     => esc_html__( 'In Stock Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'In Stock', 'magical-products-display' ),
				'condition' => array(
					'show_stock_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'stock_out_of_stock_text',
			array(
				'label'     => esc_html__( 'Out of Stock Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Out of Stock', 'magical-products-display' ),
				'condition' => array(
					'show_stock_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'stock_on_backorder_text',
			array(
				'label'     => esc_html__( 'On Backorder Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'On Backorder', 'magical-products-display' ),
				'condition' => array(
					'show_stock_filter' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Featured Filter Section.
		$this->start_controls_section(
			'section_featured_filter',
			array(
				'label' => esc_html__( 'Featured Filter', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_featured_filter',
			array(
				'label'        => esc_html__( 'Enable Featured Filter', 'magical-products-display' ),
				'description'  => esc_html__( 'Allow visitors to filter featured products only.', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'featured_filter_text',
			array(
				'label'     => esc_html__( 'Featured Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Featured Products Only', 'magical-products-display' ),
				'condition' => array(
					'show_featured_filter' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Sale Filter Section.
		$this->start_controls_section(
			'section_sale_filter',
			array(
				'label' => esc_html__( 'Sale Filter', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_sale_filter',
			array(
				'label'        => esc_html__( 'Enable Sale Filter', 'magical-products-display' ),
				'description'  => esc_html__( 'Allow visitors to filter sale products only.', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'sale_filter_text',
			array(
				'label'     => esc_html__( 'Sale Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'On Sale Only', 'magical-products-display' ),
				'condition' => array(
					'show_sale_filter' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Rating Filter Section.
		$this->start_controls_section(
			'section_rating_filter',
			array(
				'label' => esc_html__( 'Rating Filter', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_rating_filter',
			array(
				'label'        => esc_html__( 'Enable Rating Filter', 'magical-products-display' ),
				'description'  => esc_html__( 'Allow visitors to filter products by rating.', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rating_filter_title',
			array(
				'label'     => esc_html__( 'Title', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Rating', 'magical-products-display' ),
				'condition' => array(
					'show_rating_filter' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// Buttons Section.
		$this->start_controls_section(
			'section_buttons',
			array(
				'label' => esc_html__( 'Buttons', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_apply_button',
			array(
				'label'        => esc_html__( 'Show Apply Button', 'magical-products-display' ),
				'description'  => esc_html__( 'If disabled, filters will apply automatically on change.', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'apply_button_text',
			array(
				'label'     => esc_html__( 'Apply Button Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Apply Filters', 'magical-products-display' ),
				'condition' => array(
					'show_apply_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_reset_button',
			array(
				'label'        => esc_html__( 'Show Reset Button', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'reset_button_text',
			array(
				'label'     => esc_html__( 'Reset Button Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Reset All', 'magical-products-display' ),
				'condition' => array(
					'show_reset_button' => 'yes',
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
		// Toggle Button Style.
		$this->start_controls_section(
			'section_toggle_button_style',
			array(
				'label'     => esc_html__( 'Toggle Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'display_style!' => 'normal',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'toggle_button_typography',
				'selector' => '{{WRAPPER}} .mpd-advanced-filter__toggle',
			)
		);

		$this->start_controls_tabs( 'toggle_button_tabs' );

		$this->start_controls_tab(
			'toggle_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'toggle_button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__toggle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_button_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__toggle' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'toggle_button_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__toggle:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_button_hover_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__toggle:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'toggle_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'toggle_button_border',
				'selector' => '{{WRAPPER}} .mpd-advanced-filter__toggle',
			)
		);

		$this->add_responsive_control(
			'toggle_button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Filter Container Style.
		$this->start_controls_section(
			'section_container_style',
			array(
				'label' => esc_html__( 'Filter Container', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'container_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'container_border',
				'selector' => '{{WRAPPER}} .mpd-advanced-filter__content',
			)
		);

		$this->add_responsive_control(
			'container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'container_box_shadow',
				'selector' => '{{WRAPPER}} .mpd-advanced-filter__content',
			)
		);

		$this->end_controls_section();

		// Filter Section Title Style.
		$this->start_controls_section(
			'section_filter_title_style',
			array(
				'label' => esc_html__( 'Filter Section Titles', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_title_typography',
				'selector' => '{{WRAPPER}} .mpd-advanced-filter__section-title',
			)
		);

		$this->add_control(
			'filter_title_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Categories Filter Style.
		$this->start_controls_section(
			'section_category_filter_style',
			array(
				'label'     => esc_html__( 'Categories Filter', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_category_filter' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'category_filter_typography',
				'selector' => '{{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__item label, {{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__item a, {{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__dropdown',
			)
		);

		$this->start_controls_tabs( 'category_filter_tabs' );

		$this->start_controls_tab(
			'category_filter_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'category_filter_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__item label, {{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__item a, {{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__dropdown' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'category_filter_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__dropdown' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'category_filter_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'category_filter_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__item label:hover, {{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__item a:hover, {{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__dropdown:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'category_filter_active',
			array(
				'label' => esc_html__( 'Active', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'category_filter_active_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__item.is-active label, {{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__item.is-active a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'category_dropdown_border',
				'label'     => esc_html__( 'Dropdown Border', 'magical-products-display' ),
				'selector'  => '{{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__dropdown',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'category_dropdown_border_radius',
			array(
				'label'      => esc_html__( 'Dropdown Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'category_dropdown_padding',
			array(
				'label'      => esc_html__( 'Dropdown Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__dropdown' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'category_filter_spacing',
			array(
				'label'      => esc_html__( 'Item Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--category .mpd-advanced-filter__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		// Tags Filter Style.
		$this->start_controls_section(
			'section_tags_filter_style',
			array(
				'label'     => esc_html__( 'Tags Filter', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_tags_filter' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tags_filter_typography',
				'selector' => '{{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__item label, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__item a, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__dropdown, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__tag',
			)
		);

		$this->start_controls_tabs( 'tags_filter_tabs' );

		$this->start_controls_tab(
			'tags_filter_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'tags_filter_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__item label, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__item a, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__dropdown, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__tag' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tags_filter_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__dropdown, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__tag' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tags_filter_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'tags_filter_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__item label:hover, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__item a:hover, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__dropdown:hover, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__tag:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tags_filter_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__tag:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tags_filter_active',
			array(
				'label' => esc_html__( 'Active', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'tags_filter_active_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__item.is-active label, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__item.is-active a, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__tag.is-active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tags_filter_active_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__tag.is-active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'tags_dropdown_border',
				'label'     => esc_html__( 'Dropdown/Tag Border', 'magical-products-display' ),
				'selector'  => '{{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__dropdown, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__tag',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'tags_dropdown_border_radius',
			array(
				'label'      => esc_html__( 'Dropdown/Tag Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__dropdown, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__tag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tags_dropdown_padding',
			array(
				'label'      => esc_html__( 'Dropdown/Tag Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__dropdown, {{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__tag' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tags_cloud_gap',
			array(
				'label'      => esc_html__( 'Tag Cloud Gap', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__cloud' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tags_filter_spacing',
			array(
				'label'      => esc_html__( 'Item Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--tags .mpd-advanced-filter__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		// Price Range Filter Style.
		$this->start_controls_section(
			'section_price_filter_style',
			array(
				'label'     => esc_html__( 'Price Range Filter', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_price_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'price_slider_bar_color',
			array(
				'label'     => esc_html__( 'Slider Bar Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--price .ui-slider' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'price_slider_range_color',
			array(
				'label'     => esc_html__( 'Slider Range Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--price .ui-slider-range' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'price_slider_handle_color',
			array(
				'label'     => esc_html__( 'Slider Handle Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--price .ui-slider-handle' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_filter_typography',
				'selector' => '{{WRAPPER}} .mpd-advanced-filter__section--price .price_label, {{WRAPPER}} .mpd-advanced-filter__section--price .mpd-advanced-filter__price-inputs input',
			)
		);

		$this->add_control(
			'price_filter_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--price .price_label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'price_filter_spacing',
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
					'{{WRAPPER}} .mpd-advanced-filter__section--price .price_slider_wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Stock Filter Style.
		$this->start_controls_section(
			'section_stock_filter_style',
			array(
				'label'     => esc_html__( 'Stock Filter', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_stock_filter' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'stock_filter_typography',
				'selector' => '{{WRAPPER}} .mpd-advanced-filter__section--stock .mpd-advanced-filter__item label',
			)
		);

		$this->start_controls_tabs( 'stock_filter_tabs' );

		$this->start_controls_tab(
			'stock_filter_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'stock_filter_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--stock .mpd-advanced-filter__item label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'stock_filter_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'stock_filter_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--stock .mpd-advanced-filter__item label:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'stock_filter_active',
			array(
				'label' => esc_html__( 'Active', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'stock_filter_active_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--stock .mpd-advanced-filter__item.is-active label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'stock_filter_spacing',
			array(
				'label'      => esc_html__( 'Item Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--stock .mpd-advanced-filter__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		// Rating Filter Style.
		$this->start_controls_section(
			'section_rating_filter_style',
			array(
				'label'     => esc_html__( 'Rating Filter', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_rating_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'rating_star_color',
			array(
				'label'     => esc_html__( 'Star Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--rating .star.filled' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-advanced-filter__section--rating .mpd-advanced-filter__stars .star.filled' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rating_star_empty_color',
			array(
				'label'     => esc_html__( 'Empty Star Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--rating .star.empty' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-advanced-filter__section--rating .mpd-advanced-filter__stars .star.empty' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rating_star_size',
			array(
				'label'      => esc_html__( 'Star Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--rating .mpd-advanced-filter__stars .star' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-advanced-filter__section--rating .mpd-advanced-filter__stars' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-advanced-filter__section--rating .star.filled, {{WRAPPER}} .mpd-advanced-filter__section--rating .star.empty' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'rating_filter_typography',
				'label'    => esc_html__( 'Text Typography', 'magical-products-display' ),
				'selector' => '{{WRAPPER}} .mpd-advanced-filter__section--rating .mpd-advanced-filter__rating-text',
			)
		);

		$this->add_control(
			'rating_filter_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--rating .mpd-advanced-filter__rating-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'rating_filter_spacing',
			array(
				'label'      => esc_html__( 'Item Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__section--rating .mpd-advanced-filter__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Buttons Style.
		$this->start_controls_section(
			'section_buttons_style',
			array(
				'label' => esc_html__( 'Buttons', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .mpd-advanced-filter__button',
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_normal',
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
					'{{WRAPPER}} .mpd-advanced-filter__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover',
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
					'{{WRAPPER}} .mpd-advanced-filter__button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_hover_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-advanced-filter__button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'selector' => '{{WRAPPER}} .mpd-advanced-filter__button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-advanced-filter__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$display_style        = $settings['display_style'];
		$display_style_mobile = ! empty( $settings['display_style_mobile'] ) ? $settings['display_style_mobile'] : $display_style;
		$sidebar_position     = $settings['sidebar_position'] ?? 'left';
		$auto_apply           = 'yes' !== $settings['show_apply_button'];

		// Determine if we need responsive mode (different styles for desktop/mobile).
		$is_responsive = $display_style !== $display_style_mobile;

		// Data attributes for responsive handling.
		$data_attrs = array(
			'auto-apply'           => $auto_apply ? 'yes' : 'no',
			'display-style'        => $display_style,
			'display-style-mobile' => $display_style_mobile,
		);

		// Build data attributes string.
		$data_attrs_html = '';
		foreach ( $data_attrs as $key => $value ) {
			$data_attrs_html .= ' data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}

		// If responsive mode is enabled (different desktop/mobile styles), render both layouts.
		if ( $is_responsive ) {
			$this->render_responsive_filter( $settings, $data_attrs_html, $auto_apply );
			return;
		}

		// Non-responsive mode - single layout for all devices.
		$wrapper_classes = array(
			'mpd-advanced-filter',
			'mpd-advanced-filter--' . $display_style,
		);

		if ( 'sidebar' === $display_style ) {
			$wrapper_classes[] = 'mpd-advanced-filter--sidebar-' . $sidebar_position;
		}

		// Render inline dropdown bar style.
		if ( 'inline' === $display_style ) {
			$this->render_inline_filter( $settings, $wrapper_classes, $auto_apply );
			return;
		}

		// Check if toggle button is needed.
		$needs_toggle = in_array( $display_style, array( 'popup', 'sidebar' ), true );
		$filter_title = ! empty( $settings['filter_title'] ) ? $settings['filter_title'] : esc_html__( 'Filter Products', 'magical-products-display' );
		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"<?php echo $data_attrs_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php if ( $needs_toggle ) : ?>
				<button type="button" class="mpd-advanced-filter__toggle">
					<?php if ( 'left' === $settings['icon_position'] && ! empty( $settings['toggle_button_icon']['value'] ) ) : ?>
						<?php \Elementor\Icons_Manager::render_icon( $settings['toggle_button_icon'], array( 'aria-hidden' => 'true' ) ); ?>
					<?php endif; ?>
					<span><?php echo esc_html( $settings['toggle_button_text'] ); ?></span>
					<?php if ( 'right' === $settings['icon_position'] && ! empty( $settings['toggle_button_icon']['value'] ) ) : ?>
						<?php \Elementor\Icons_Manager::render_icon( $settings['toggle_button_icon'], array( 'aria-hidden' => 'true' ) ); ?>
					<?php endif; ?>
				</button>
				<div class="mpd-advanced-filter__overlay"></div>
			<?php endif; ?>

			<div class="mpd-advanced-filter__content">
				<?php if ( $needs_toggle ) : ?>
					<div class="mpd-advanced-filter__header">
						<h3 class="mpd-advanced-filter__title"><?php echo esc_html( $filter_title ); ?></h3>
						<button type="button" class="mpd-advanced-filter__close" aria-label="<?php esc_attr_e( 'Close filters', 'magical-products-display' ); ?>">
							<span class="mpd-advanced-filter__close-icon">&times;</span>
						</button>
					</div>
					<div class="mpd-advanced-filter__body">
				<?php else : ?>
					<?php if ( 'yes' === $settings['show_filter_title'] ) : ?>
						<h3 class="mpd-advanced-filter__title"><?php echo esc_html( $filter_title ); ?></h3>
					<?php endif; ?>
				<?php endif; ?>

				<form class="mpd-advanced-filter__form" method="get">
					<?php
					// Render Price Filter FIRST (moved to top).
					if ( 'yes' === $settings['show_price_filter'] ) {
						$this->render_price_filter( $settings );
					}

					// Render Category Filter.
					if ( 'yes' === $settings['show_category_filter'] ) {
						$this->render_category_filter( $settings );
					}

					// Render Tags Filter.
					if ( 'yes' === $settings['show_tags_filter'] ) {
						$this->render_tags_filter( $settings );
					}

					// Render Brand Filter.
					if ( 'yes' === $settings['show_brand_filter'] ) {
						$this->render_brand_filter( $settings );
					}

					// Render Stock Filter.
					if ( 'yes' === $settings['show_stock_filter'] ) {
						$this->render_stock_filter( $settings );
					}

					// Render Featured Filter.
					if ( 'yes' === $settings['show_featured_filter'] ) {
						$this->render_featured_filter( $settings );
					}

					// Render Sale Filter.
					if ( 'yes' === $settings['show_sale_filter'] ) {
						$this->render_sale_filter( $settings );
					}

					// Render Rating Filter.
					if ( 'yes' === $settings['show_rating_filter'] ) {
						$this->render_rating_filter( $settings );
					}
					?>

					<div class="mpd-advanced-filter__buttons">
						<?php if ( 'yes' === $settings['show_apply_button'] ) : ?>
							<button type="submit" class="mpd-advanced-filter__button mpd-advanced-filter__button--apply">
								<?php echo esc_html( $settings['apply_button_text'] ); ?>
							</button>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['show_reset_button'] ) : ?>
							<button type="button" class="mpd-advanced-filter__button mpd-advanced-filter__button--reset">
								<?php echo esc_html( $settings['reset_button_text'] ); ?>
							</button>
						<?php endif; ?>
					</div>
				</form>

				<?php if ( $needs_toggle ) : ?>
					</div><!-- /.mpd-advanced-filter__body -->
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render inline dropdown bar filter style.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings        Widget settings.
	 * @param array $wrapper_classes Wrapper classes.
	 * @param bool  $auto_apply      Whether to auto-apply filters.
	 * @return void
	 */
	protected function render_inline_filter( $settings, $wrapper_classes, $auto_apply ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_cat     = isset( $_GET['product_cat'] ) ? sanitize_text_field( wp_unslash( $_GET['product_cat'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_tag     = isset( $_GET['product_tag'] ) ? sanitize_text_field( wp_unslash( $_GET['product_tag'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_stock   = isset( $_GET['stock_status'] ) ? sanitize_text_field( wp_unslash( $_GET['stock_status'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_featured = isset( $_GET['featured'] ) ? sanitize_text_field( wp_unslash( $_GET['featured'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_sale    = isset( $_GET['on_sale'] ) ? sanitize_text_field( wp_unslash( $_GET['on_sale'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_rating  = isset( $_GET['rating_filter'] ) ? sanitize_text_field( wp_unslash( $_GET['rating_filter'] ) ) : '';
		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-auto-apply="<?php echo esc_attr( $auto_apply ? 'yes' : 'no' ); ?>">
			<form class="mpd-advanced-filter__form mpd-advanced-filter__inline-bar" method="get">
				<?php
				// Category dropdown.
				if ( 'yes' === $settings['show_category_filter'] ) {
					$categories = get_terms( array(
						'taxonomy'   => 'product_cat',
						'hide_empty' => 'yes' === $settings['category_hide_empty'],
						'orderby'    => 'name',
						'order'      => 'ASC',
						'parent'     => 'yes' !== $settings['category_show_hierarchy'] ? 0 : '',
					) );

					if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
						?>
						<div class="mpd-inline-dropdown<?php echo ! empty( $current_cat ) ? ' has-selection' : ''; ?>" data-filter="product_cat">
							<button type="button" class="mpd-inline-dropdown__trigger">
								<span class="mpd-inline-dropdown__label"><?php echo esc_html( $settings['category_filter_title'] ); ?></span>
								<svg class="mpd-inline-dropdown__icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
							<div class="mpd-inline-dropdown__content">
								<ul class="mpd-inline-dropdown__list">
									<li class="mpd-inline-dropdown__item<?php echo empty( $current_cat ) ? ' is-active' : ''; ?>">
										<a href="#" data-value=""><?php esc_html_e( 'All Categories', 'magical-products-display' ); ?></a>
									</li>
									<?php foreach ( $categories as $category ) : ?>
										<li class="mpd-inline-dropdown__item<?php echo esc_attr( $current_cat === $category->slug ? ' is-active' : '' ); ?>">
											<a href="#" data-value="<?php echo esc_attr( $category->slug ); ?>">
												<?php echo esc_html( $category->name ); ?>
												<?php if ( 'yes' === $settings['category_show_count'] ) : ?>
													<span class="mpd-inline-dropdown__count">(<?php echo esc_html( $category->count ); ?>)</span>
												<?php endif; ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
								<input type="hidden" name="product_cat" value="<?php echo esc_attr( $current_cat ); ?>">
							</div>
						</div>
						<?php
					endif;
				}

				// Tags dropdown.
				if ( 'yes' === $settings['show_tags_filter'] ) {
					$tags = get_terms( array(
						'taxonomy'   => 'product_tag',
						'hide_empty' => true,
						'orderby'    => 'name',
						'order'      => 'ASC',
					) );

					if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) :
						?>
						<div class="mpd-inline-dropdown<?php echo ! empty( $current_tag ) ? ' has-selection' : ''; ?>" data-filter="product_tag">
							<button type="button" class="mpd-inline-dropdown__trigger">
								<span class="mpd-inline-dropdown__label"><?php echo esc_html( $settings['tags_filter_title'] ); ?></span>
								<svg class="mpd-inline-dropdown__icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
							<div class="mpd-inline-dropdown__content">
								<ul class="mpd-inline-dropdown__list">
									<li class="mpd-inline-dropdown__item<?php echo empty( $current_tag ) ? ' is-active' : ''; ?>">
										<a href="#" data-value=""><?php esc_html_e( 'All Tags', 'magical-products-display' ); ?></a>
									</li>
									<?php foreach ( $tags as $tag ) : ?>
										<li class="mpd-inline-dropdown__item<?php echo esc_attr( $current_tag === $tag->slug ? ' is-active' : '' ); ?>">
											<a href="#" data-value="<?php echo esc_attr( $tag->slug ); ?>">
												<?php echo esc_html( $tag->name ); ?>
												<?php if ( 'yes' === $settings['tags_show_count'] ) : ?>
													<span class="mpd-inline-dropdown__count">(<?php echo esc_html( $tag->count ); ?>)</span>
												<?php endif; ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
								<input type="hidden" name="product_tag" value="<?php echo esc_attr( $current_tag ); ?>">
							</div>
						</div>
						<?php
					endif;
				}

				// Attribute/Brand dropdown.
				if ( 'yes' === $settings['show_brand_filter'] ) {
					$taxonomy = $settings['brand_taxonomy'];
					$brands   = get_terms( array(
						'taxonomy'   => $taxonomy,
						'hide_empty' => true,
					) );

					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$current_brand = isset( $_GET[ $taxonomy ] ) ? sanitize_text_field( wp_unslash( $_GET[ $taxonomy ] ) ) : '';

					if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) :
						?>
						<div class="mpd-inline-dropdown<?php echo ! empty( $current_brand ) ? ' has-selection' : ''; ?>" data-filter="<?php echo esc_attr( $taxonomy ); ?>">
							<button type="button" class="mpd-inline-dropdown__trigger">
								<span class="mpd-inline-dropdown__label"><?php echo esc_html( $settings['brand_filter_title'] ); ?></span>
								<svg class="mpd-inline-dropdown__icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
							<div class="mpd-inline-dropdown__content">
								<ul class="mpd-inline-dropdown__list">
									<li class="mpd-inline-dropdown__item<?php echo empty( $current_brand ) ? ' is-active' : ''; ?>">
										<a href="#" data-value=""><?php esc_html_e( 'All', 'magical-products-display' ); ?></a>
									</li>
									<?php foreach ( $brands as $brand ) : ?>
										<li class="mpd-inline-dropdown__item<?php echo esc_attr( $current_brand === $brand->slug ? ' is-active' : '' ); ?>">
											<a href="#" data-value="<?php echo esc_attr( $brand->slug ); ?>">
												<?php echo esc_html( $brand->name ); ?>
												<?php if ( 'yes' === $settings['brand_show_count'] ) : ?>
													<span class="mpd-inline-dropdown__count">(<?php echo esc_html( $brand->count ); ?>)</span>
												<?php endif; ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
								<input type="hidden" name="<?php echo esc_attr( $taxonomy ); ?>" value="<?php echo esc_attr( $current_brand ); ?>">
							</div>
						</div>
						<?php
					endif;
				}

				// Stock filter dropdown.
				if ( 'yes' === $settings['show_stock_filter'] ) :
					?>
					<div class="mpd-inline-dropdown<?php echo ! empty( $current_stock ) ? ' has-selection' : ''; ?>" data-filter="stock_status">
						<button type="button" class="mpd-inline-dropdown__trigger">
							<span class="mpd-inline-dropdown__label"><?php echo esc_html( $settings['stock_filter_title'] ); ?></span>
							<svg class="mpd-inline-dropdown__icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</button>
						<div class="mpd-inline-dropdown__content">
							<ul class="mpd-inline-dropdown__list">
								<li class="mpd-inline-dropdown__item<?php echo empty( $current_stock ) ? ' is-active' : ''; ?>">
									<a href="#" data-value=""><?php esc_html_e( 'All', 'magical-products-display' ); ?></a>
								</li>
								<li class="mpd-inline-dropdown__item<?php echo 'instock' === $current_stock ? ' is-active' : ''; ?>">
									<a href="#" data-value="instock"><?php esc_html_e( 'In Stock', 'magical-products-display' ); ?></a>
								</li>
								<li class="mpd-inline-dropdown__item<?php echo 'outofstock' === $current_stock ? ' is-active' : ''; ?>">
									<a href="#" data-value="outofstock"><?php esc_html_e( 'Out of Stock', 'magical-products-display' ); ?></a>
								</li>
							</ul>
							<input type="hidden" name="stock_status" value="<?php echo esc_attr( $current_stock ); ?>">
						</div>
					</div>
				<?php endif; ?>

				<?php // Rating filter dropdown.
				if ( 'yes' === $settings['show_rating_filter'] ) :
					?>
					<div class="mpd-inline-dropdown<?php echo ! empty( $current_rating ) ? ' has-selection' : ''; ?>" data-filter="rating_filter">
						<button type="button" class="mpd-inline-dropdown__trigger">
							<span class="mpd-inline-dropdown__label"><?php echo esc_html( $settings['rating_filter_title'] ); ?></span>
							<svg class="mpd-inline-dropdown__icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</button>
						<div class="mpd-inline-dropdown__content">
							<ul class="mpd-inline-dropdown__list">
								<li class="mpd-inline-dropdown__item<?php echo empty( $current_rating ) ? ' is-active' : ''; ?>">
									<a href="#" data-value=""><?php esc_html_e( 'All Ratings', 'magical-products-display' ); ?></a>
								</li>
								<?php for ( $rating = 5; $rating >= 1; $rating-- ) : ?>
									<li class="mpd-inline-dropdown__item<?php echo (int) $current_rating === $rating ? ' is-active' : ''; ?>">
										<a href="#" data-value="<?php echo esc_attr( $rating ); ?>">
											<span class="mpd-inline-dropdown__stars">
												<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
													<span class="star <?php echo esc_attr( $i <= $rating ? 'filled' : 'empty' ); ?>">★</span>
												<?php endfor; ?>
											</span>
											<span class="mpd-inline-dropdown__rating-text"><?php echo esc_html( sprintf( __( '%d & up', 'magical-products-display' ), $rating ) ); ?></span>
										</a>
									</li>
								<?php endfor; ?>
							</ul>
							<input type="hidden" name="rating_filter" value="<?php echo esc_attr( $current_rating ); ?>">
						</div>
					</div>
				<?php endif; ?>

				<?php // Featured toggle button.
				if ( 'yes' === $settings['show_featured_filter'] ) :
					$featured_title = $settings['featured_filter_title'] ?? esc_html__( 'Featured', 'magical-products-display' );
					?>
					<button type="button" class="mpd-inline-toggle<?php echo 'yes' === $current_featured ? ' is-active' : ''; ?>" data-filter="featured" data-value="<?php echo 'yes' === $current_featured ? '' : 'yes'; ?>">
						<span class="mpd-inline-toggle__icon">★</span>
						<span class="mpd-inline-toggle__label"><?php echo esc_html( $featured_title ); ?></span>
						<input type="hidden" name="featured" value="<?php echo esc_attr( $current_featured ); ?>">
					</button>
				<?php endif; ?>

				<?php // On Sale toggle button.
				if ( 'yes' === $settings['show_sale_filter'] ) :
					$sale_title = $settings['sale_filter_title'] ?? esc_html__( 'On Sale', 'magical-products-display' );
					?>
					<button type="button" class="mpd-inline-toggle<?php echo 'yes' === $current_sale ? ' is-active' : ''; ?>" data-filter="on_sale" data-value="<?php echo 'yes' === $current_sale ? '' : 'yes'; ?>">
						<span class="mpd-inline-toggle__icon">%</span>
						<span class="mpd-inline-toggle__label"><?php echo esc_html( $sale_title ); ?></span>
						<input type="hidden" name="on_sale" value="<?php echo esc_attr( $current_sale ); ?>">
					</button>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_reset_button'] ) : ?>
					<a href="#" class="mpd-advanced-filter__reset-link">
						<?php echo esc_html( $settings['reset_button_text'] ); ?>
					</a>
				<?php endif; ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render responsive filter with different styles for desktop/mobile.
	 *
	 * @since 2.2.0
	 *
	 * @param array  $settings        Widget settings.
	 * @param string $data_attrs_html Data attributes HTML.
	 * @param bool   $auto_apply      Whether to auto-apply filters.
	 * @return void
	 */
	protected function render_responsive_filter( $settings, $data_attrs_html, $auto_apply ) {
		$display_style        = $settings['display_style'];
		$display_style_mobile = ! empty( $settings['display_style_mobile'] ) ? $settings['display_style_mobile'] : $display_style;
		$sidebar_position     = $settings['sidebar_position'] ?? 'left';
		$filter_title         = ! empty( $settings['filter_title'] ) ? $settings['filter_title'] : esc_html__( 'Filter Products', 'magical-products-display' );
		?>
		<div class="mpd-advanced-filter mpd-advanced-filter--responsive"<?php echo $data_attrs_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php
			// Desktop Layout.
			if ( 'inline' === $display_style ) {
				// Render inline layout for desktop.
				?>
				<div class="mpd-advanced-filter__desktop mpd-advanced-filter--inline" data-auto-apply="<?php echo esc_attr( $auto_apply ? 'yes' : 'no' ); ?>">
					<?php $this->render_inline_filter_content( $settings, $auto_apply ); ?>
				</div>
				<?php
			} else {
				// Render normal/popup/sidebar layout for desktop.
				$desktop_classes = array(
					'mpd-advanced-filter__desktop',
					'mpd-advanced-filter--' . $display_style,
				);
				if ( 'sidebar' === $display_style ) {
					$desktop_classes[] = 'mpd-advanced-filter--sidebar-' . $sidebar_position;
				}
				$needs_toggle = in_array( $display_style, array( 'popup', 'sidebar' ), true );
				?>
				<div class="<?php echo esc_attr( implode( ' ', $desktop_classes ) ); ?>" data-auto-apply="<?php echo esc_attr( $auto_apply ? 'yes' : 'no' ); ?>">
					<?php if ( $needs_toggle ) : ?>
						<button type="button" class="mpd-advanced-filter__toggle">
							<?php $this->render_toggle_button_content( $settings ); ?>
						</button>
						<div class="mpd-advanced-filter__overlay"></div>
					<?php endif; ?>
					<div class="mpd-advanced-filter__content">
						<?php if ( $needs_toggle ) : ?>
							<div class="mpd-advanced-filter__header">
								<h3 class="mpd-advanced-filter__title"><?php echo esc_html( $filter_title ); ?></h3>
								<button type="button" class="mpd-advanced-filter__close" aria-label="<?php esc_attr_e( 'Close filters', 'magical-products-display' ); ?>">
									<span class="mpd-advanced-filter__close-icon">&times;</span>
								</button>
							</div>
							<div class="mpd-advanced-filter__body">
								<?php $this->render_filter_form_content( $settings ); ?>
							</div>
						<?php else : ?>
							<?php $this->render_filter_form_content( $settings ); ?>
						<?php endif; ?>
					</div>
				</div>
				<?php
			}

			// Mobile Layout.
			if ( 'inline' === $display_style_mobile ) {
				// Render inline layout for mobile.
				?>
				<div class="mpd-advanced-filter__mobile mpd-advanced-filter--inline" data-auto-apply="<?php echo esc_attr( $auto_apply ? 'yes' : 'no' ); ?>">
					<?php $this->render_inline_filter_content( $settings, $auto_apply ); ?>
				</div>
				<?php
			} else {
				// Render normal/popup/sidebar layout for mobile.
				$mobile_classes = array(
					'mpd-advanced-filter__mobile',
					'mpd-advanced-filter--' . $display_style_mobile,
				);
				if ( 'sidebar' === $display_style_mobile ) {
					$mobile_classes[] = 'mpd-advanced-filter--sidebar-' . $sidebar_position;
				}
				$needs_toggle = in_array( $display_style_mobile, array( 'popup', 'sidebar' ), true );
				?>
				<div class="<?php echo esc_attr( implode( ' ', $mobile_classes ) ); ?>" data-auto-apply="<?php echo esc_attr( $auto_apply ? 'yes' : 'no' ); ?>">
					<?php if ( $needs_toggle ) : ?>
						<button type="button" class="mpd-advanced-filter__toggle">
							<?php $this->render_toggle_button_content( $settings ); ?>
						</button>
						<div class="mpd-advanced-filter__overlay"></div>
					<?php endif; ?>
					<div class="mpd-advanced-filter__content">
						<?php if ( $needs_toggle ) : ?>
							<div class="mpd-advanced-filter__header">
								<h3 class="mpd-advanced-filter__title"><?php echo esc_html( $filter_title ); ?></h3>
								<button type="button" class="mpd-advanced-filter__close" aria-label="<?php esc_attr_e( 'Close filters', 'magical-products-display' ); ?>">
									<span class="mpd-advanced-filter__close-icon">&times;</span>
								</button>
							</div>
							<div class="mpd-advanced-filter__body">
								<?php $this->render_filter_form_content( $settings ); ?>
							</div>
						<?php else : ?>
							<?php $this->render_filter_form_content( $settings ); ?>
						<?php endif; ?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render toggle button content.
	 *
	 * @since 2.2.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_toggle_button_content( $settings ) {
		$icon_position = $settings['icon_position'] ?? 'left';
		$button_text   = ! empty( $settings['toggle_button_text'] ) ? $settings['toggle_button_text'] : esc_html__( 'Filter Products', 'magical-products-display' );

		if ( 'left' === $icon_position && ! empty( $settings['toggle_button_icon']['value'] ) ) {
			\Elementor\Icons_Manager::render_icon( $settings['toggle_button_icon'], array( 'aria-hidden' => 'true' ) );
		}
		?>
		<span><?php echo esc_html( $button_text ); ?></span>
		<?php
		if ( 'right' === $icon_position && ! empty( $settings['toggle_button_icon']['value'] ) ) {
			\Elementor\Icons_Manager::render_icon( $settings['toggle_button_icon'], array( 'aria-hidden' => 'true' ) );
		}
	}

	/**
	 * Render inline filter content (form only).
	 *
	 * @since 2.2.0
	 *
	 * @param array $settings   Widget settings.
	 * @param bool  $auto_apply Whether to auto-apply filters.
	 * @return void
	 */
	protected function render_inline_filter_content( $settings, $auto_apply ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_cat     = isset( $_GET['product_cat'] ) ? sanitize_text_field( wp_unslash( $_GET['product_cat'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_tag     = isset( $_GET['product_tag'] ) ? sanitize_text_field( wp_unslash( $_GET['product_tag'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_stock   = isset( $_GET['stock_status'] ) ? sanitize_text_field( wp_unslash( $_GET['stock_status'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_featured = isset( $_GET['featured'] ) ? sanitize_text_field( wp_unslash( $_GET['featured'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_sale    = isset( $_GET['on_sale'] ) ? sanitize_text_field( wp_unslash( $_GET['on_sale'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_rating  = isset( $_GET['rating_filter'] ) ? sanitize_text_field( wp_unslash( $_GET['rating_filter'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_min_price = isset( $_GET['min_price'] ) ? sanitize_text_field( wp_unslash( $_GET['min_price'] ) ) : '';
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_max_price = isset( $_GET['max_price'] ) ? sanitize_text_field( wp_unslash( $_GET['max_price'] ) ) : '';
		?>
		<form class="mpd-advanced-filter__form mpd-advanced-filter__inline-bar" method="get">
			<?php
			// Category dropdown.
			if ( 'yes' === $settings['show_category_filter'] ) {
				$categories = get_terms( array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => 'yes' === $settings['category_hide_empty'],
					'orderby'    => 'name',
					'order'      => 'ASC',
					'parent'     => 'yes' !== $settings['category_show_hierarchy'] ? 0 : '',
				) );

				if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
					?>
					<div class="mpd-inline-dropdown<?php echo ! empty( $current_cat ) ? ' has-selection' : ''; ?>" data-filter="product_cat">
						<button type="button" class="mpd-inline-dropdown__trigger">
							<span class="mpd-inline-dropdown__label"><?php echo esc_html( $settings['category_filter_title'] ); ?></span>
							<svg class="mpd-inline-dropdown__icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</button>
						<div class="mpd-inline-dropdown__content">
							<ul class="mpd-inline-dropdown__list">
								<li class="mpd-inline-dropdown__item<?php echo empty( $current_cat ) ? ' is-active' : ''; ?>">
									<a href="#" data-value=""><?php esc_html_e( 'All Categories', 'magical-products-display' ); ?></a>
								</li>
								<?php foreach ( $categories as $category ) : ?>
									<li class="mpd-inline-dropdown__item<?php echo esc_attr( $current_cat === $category->slug ? ' is-active' : '' ); ?>">
										<a href="#" data-value="<?php echo esc_attr( $category->slug ); ?>">
											<?php echo esc_html( $category->name ); ?>
											<?php if ( 'yes' === $settings['category_show_count'] ) : ?>
												<span class="mpd-inline-dropdown__count">(<?php echo esc_html( $category->count ); ?>)</span>
											<?php endif; ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
							<input type="hidden" name="product_cat" value="<?php echo esc_attr( $current_cat ); ?>">
						</div>
					</div>
					<?php
				endif;
			}

			// Tags dropdown.
			if ( 'yes' === $settings['show_tags_filter'] ) {
				$tags = get_terms( array(
					'taxonomy'   => 'product_tag',
					'hide_empty' => 'yes' === ( $settings['tags_hide_empty'] ?? 'yes' ),
					'orderby'    => 'name',
					'order'      => 'ASC',
				) );

				if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) :
					?>
					<div class="mpd-inline-dropdown<?php echo ! empty( $current_tag ) ? ' has-selection' : ''; ?>" data-filter="product_tag">
						<button type="button" class="mpd-inline-dropdown__trigger">
							<span class="mpd-inline-dropdown__label"><?php echo esc_html( $settings['tags_filter_title'] ?? esc_html__( 'Tags', 'magical-products-display' ) ); ?></span>
							<svg class="mpd-inline-dropdown__icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</button>
						<div class="mpd-inline-dropdown__content">
							<ul class="mpd-inline-dropdown__list">
								<li class="mpd-inline-dropdown__item<?php echo empty( $current_tag ) ? ' is-active' : ''; ?>">
									<a href="#" data-value=""><?php esc_html_e( 'All Tags', 'magical-products-display' ); ?></a>
								</li>
								<?php foreach ( $tags as $tag ) : ?>
									<li class="mpd-inline-dropdown__item<?php echo esc_attr( $current_tag === $tag->slug ? ' is-active' : '' ); ?>">
										<a href="#" data-value="<?php echo esc_attr( $tag->slug ); ?>">
											<?php echo esc_html( $tag->name ); ?>
											<?php if ( 'yes' === ( $settings['tags_show_count'] ?? 'no' ) ) : ?>
												<span class="mpd-inline-dropdown__count">(<?php echo esc_html( $tag->count ); ?>)</span>
											<?php endif; ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
							<input type="hidden" name="product_tag" value="<?php echo esc_attr( $current_tag ); ?>">
						</div>
					</div>
					<?php
				endif;
			}

			// Brand dropdown (if brand taxonomy exists).
			if ( 'yes' === $settings['show_brand_filter'] ) {
				$brand_taxonomy = $settings['brand_taxonomy'] ?? 'product_brand';
				if ( taxonomy_exists( $brand_taxonomy ) ) {
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$current_brand = isset( $_GET[ $brand_taxonomy ] ) ? sanitize_text_field( wp_unslash( $_GET[ $brand_taxonomy ] ) ) : '';
					$brands = get_terms( array(
						'taxonomy'   => $brand_taxonomy,
						'hide_empty' => true,
						'orderby'    => 'name',
						'order'      => 'ASC',
					) );

					if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) :
						?>
						<div class="mpd-inline-dropdown<?php echo ! empty( $current_brand ) ? ' has-selection' : ''; ?>" data-filter="<?php echo esc_attr( $brand_taxonomy ); ?>">
							<button type="button" class="mpd-inline-dropdown__trigger">
								<span class="mpd-inline-dropdown__label"><?php echo esc_html( $settings['brand_filter_title'] ?? esc_html__( 'Brand', 'magical-products-display' ) ); ?></span>
								<svg class="mpd-inline-dropdown__icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</button>
							<div class="mpd-inline-dropdown__content">
								<ul class="mpd-inline-dropdown__list">
									<li class="mpd-inline-dropdown__item<?php echo empty( $current_brand ) ? ' is-active' : ''; ?>">
										<a href="#" data-value=""><?php esc_html_e( 'All Brands', 'magical-products-display' ); ?></a>
									</li>
									<?php foreach ( $brands as $brand ) : ?>
										<li class="mpd-inline-dropdown__item<?php echo esc_attr( $current_brand === $brand->slug ? ' is-active' : '' ); ?>">
											<a href="#" data-value="<?php echo esc_attr( $brand->slug ); ?>">
												<?php echo esc_html( $brand->name ); ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
								<input type="hidden" name="<?php echo esc_attr( $brand_taxonomy ); ?>" value="<?php echo esc_attr( $current_brand ); ?>">
							</div>
						</div>
						<?php
					endif;
				}
			}

			// Price filter dropdown.
			if ( 'yes' === $settings['show_price_filter'] ) :
				$has_price_selection = ! empty( $current_min_price ) || ! empty( $current_max_price );
				?>
				<div class="mpd-inline-dropdown mpd-inline-dropdown--price<?php echo esc_attr( $has_price_selection ? ' has-selection' : '' ); ?>" data-filter="price">
					<button type="button" class="mpd-inline-dropdown__trigger">
						<span class="mpd-inline-dropdown__label"><?php echo esc_html( $settings['price_filter_title'] ?? esc_html__( 'Price', 'magical-products-display' ) ); ?></span>
						<svg class="mpd-inline-dropdown__icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<div class="mpd-inline-dropdown__content mpd-inline-dropdown__content--price">
						<div class="mpd-inline-price-inputs">
							<input type="number" name="min_price" class="mpd-inline-price-input" placeholder="<?php esc_attr_e( 'Min', 'magical-products-display' ); ?>" value="<?php echo esc_attr( $current_min_price ); ?>">
							<span class="mpd-inline-price-separator">-</span>
							<input type="number" name="max_price" class="mpd-inline-price-input" placeholder="<?php esc_attr_e( 'Max', 'magical-products-display' ); ?>" value="<?php echo esc_attr( $current_max_price ); ?>">
						</div>
						<button type="button" class="mpd-inline-price-apply"><?php esc_html_e( 'Apply', 'magical-products-display' ); ?></button>
					</div>
				</div>
			<?php endif; ?>

			<?php // Stock filter dropdown.
			if ( 'yes' === $settings['show_stock_filter'] ) :
				?>
				<div class="mpd-inline-dropdown<?php echo ! empty( $current_stock ) ? ' has-selection' : ''; ?>" data-filter="stock_status">
					<button type="button" class="mpd-inline-dropdown__trigger">
						<span class="mpd-inline-dropdown__label"><?php echo esc_html( $settings['stock_filter_title'] ?? esc_html__( 'Availability', 'magical-products-display' ) ); ?></span>
						<svg class="mpd-inline-dropdown__icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<div class="mpd-inline-dropdown__content">
						<ul class="mpd-inline-dropdown__list">
							<li class="mpd-inline-dropdown__item<?php echo empty( $current_stock ) ? ' is-active' : ''; ?>">
								<a href="#" data-value=""><?php esc_html_e( 'All', 'magical-products-display' ); ?></a>
							</li>
							<li class="mpd-inline-dropdown__item<?php echo 'instock' === $current_stock ? ' is-active' : ''; ?>">
								<a href="#" data-value="instock"><?php esc_html_e( 'In Stock', 'magical-products-display' ); ?></a>
							</li>
							<li class="mpd-inline-dropdown__item<?php echo 'outofstock' === $current_stock ? ' is-active' : ''; ?>">
								<a href="#" data-value="outofstock"><?php esc_html_e( 'Out of Stock', 'magical-products-display' ); ?></a>
							</li>
						</ul>
						<input type="hidden" name="stock_status" value="<?php echo esc_attr( $current_stock ); ?>">
					</div>
				</div>
			<?php endif; ?>

			<?php // Rating filter dropdown.
			if ( 'yes' === $settings['show_rating_filter'] ) :
				?>
				<div class="mpd-inline-dropdown<?php echo ! empty( $current_rating ) ? ' has-selection' : ''; ?>" data-filter="rating_filter">
					<button type="button" class="mpd-inline-dropdown__trigger">
						<span class="mpd-inline-dropdown__label"><?php echo esc_html( $settings['rating_filter_title'] ?? esc_html__( 'Rating', 'magical-products-display' ) ); ?></span>
						<svg class="mpd-inline-dropdown__icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
					<div class="mpd-inline-dropdown__content">
						<ul class="mpd-inline-dropdown__list">
							<li class="mpd-inline-dropdown__item<?php echo empty( $current_rating ) ? ' is-active' : ''; ?>">
								<a href="#" data-value=""><?php esc_html_e( 'All Ratings', 'magical-products-display' ); ?></a>
							</li>
							<?php for ( $rating = 5; $rating >= 1; $rating-- ) : ?>
								<li class="mpd-inline-dropdown__item<?php echo (int) $current_rating === $rating ? ' is-active' : ''; ?>">
									<a href="#" data-value="<?php echo esc_attr( $rating ); ?>">
										<span class="mpd-inline-dropdown__stars">
											<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
												<span class="star <?php echo esc_attr( $i <= $rating ? 'filled' : 'empty' ); ?>">★</span>
											<?php endfor; ?>
										</span>
										<span class="mpd-inline-dropdown__rating-text"><?php echo esc_html( sprintf( __( '%d & up', 'magical-products-display' ), $rating ) ); ?></span>
									</a>
								</li>
							<?php endfor; ?>
						</ul>
						<input type="hidden" name="rating_filter" value="<?php echo esc_attr( $current_rating ); ?>">
					</div>
				</div>
			<?php endif; ?>

			<?php // Featured toggle button.
			if ( 'yes' === $settings['show_featured_filter'] ) :
				$featured_title = $settings['featured_filter_title'] ?? esc_html__( 'Featured', 'magical-products-display' );
				?>
				<button type="button" class="mpd-inline-toggle<?php echo 'yes' === $current_featured ? ' is-active' : ''; ?>" data-filter="featured" data-value="<?php echo 'yes' === $current_featured ? '' : 'yes'; ?>">
					<span class="mpd-inline-toggle__icon">★</span>
					<span class="mpd-inline-toggle__label"><?php echo esc_html( $featured_title ); ?></span>
					<input type="hidden" name="featured" value="<?php echo esc_attr( $current_featured ); ?>">
				</button>
			<?php endif; ?>

			<?php // On Sale toggle button.
			if ( 'yes' === $settings['show_sale_filter'] ) :
				$sale_title = $settings['sale_filter_title'] ?? esc_html__( 'On Sale', 'magical-products-display' );
				?>
				<button type="button" class="mpd-inline-toggle<?php echo 'yes' === $current_sale ? ' is-active' : ''; ?>" data-filter="on_sale" data-value="<?php echo 'yes' === $current_sale ? '' : 'yes'; ?>">
					<span class="mpd-inline-toggle__icon">%</span>
					<span class="mpd-inline-toggle__label"><?php echo esc_html( $sale_title ); ?></span>
					<input type="hidden" name="on_sale" value="<?php echo esc_attr( $current_sale ); ?>">
				</button>
			<?php endif; ?>

			<?php if ( 'yes' === $settings['show_reset_button'] ) : ?>
				<a href="#" class="mpd-advanced-filter__reset-link">
					<?php echo esc_html( $settings['reset_button_text'] ); ?>
				</a>
			<?php endif; ?>
		</form>
		<?php
	}

	/**
	 * Render filter form content (for normal/popup/sidebar modes).
	 *
	 * @since 2.2.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_filter_form_content( $settings ) {
		if ( 'yes' === $settings['show_filter_title'] ) : ?>
			<h3 class="mpd-advanced-filter__title"><?php echo esc_html( $settings['filter_title'] ); ?></h3>
		<?php endif; ?>

		<form class="mpd-advanced-filter__form" method="get">
			<?php
			// Render Price Filter FIRST (moved to top).
			if ( 'yes' === $settings['show_price_filter'] ) {
				$this->render_price_filter( $settings );
			}

			// Render Category Filter.
			if ( 'yes' === $settings['show_category_filter'] ) {
				$this->render_category_filter( $settings );
			}

			// Render Tags Filter.
			if ( 'yes' === $settings['show_tags_filter'] ) {
				$this->render_tags_filter( $settings );
			}

			// Render Brand Filter.
			if ( 'yes' === $settings['show_brand_filter'] ) {
				$this->render_brand_filter( $settings );
			}

			// Render Stock Filter.
			if ( 'yes' === $settings['show_stock_filter'] ) {
				$this->render_stock_filter( $settings );
			}

			// Render Featured Filter.
			if ( 'yes' === $settings['show_featured_filter'] ) {
				$this->render_featured_filter( $settings );
			}

			// Render Sale Filter.
			if ( 'yes' === $settings['show_sale_filter'] ) {
				$this->render_sale_filter( $settings );
			}

			// Render Rating Filter.
			if ( 'yes' === $settings['show_rating_filter'] ) {
				$this->render_rating_filter( $settings );
			}
			?>

			<div class="mpd-advanced-filter__buttons">
				<?php if ( 'yes' === $settings['show_apply_button'] ) : ?>
					<button type="submit" class="mpd-advanced-filter__button mpd-advanced-filter__button--apply">
						<?php echo esc_html( $settings['apply_button_text'] ); ?>
					</button>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_reset_button'] ) : ?>
					<button type="button" class="mpd-advanced-filter__button mpd-advanced-filter__button--reset">
						<?php echo esc_html( $settings['reset_button_text'] ); ?>
					</button>
				<?php endif; ?>
			</div>
		</form>
		<?php
	}

	/**
	 * Render category filter.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_category_filter( $settings ) {
		$display_type   = $settings['category_display_type'];
		$show_count     = 'yes' === $settings['category_show_count'];
		$show_hierarchy = 'yes' === $settings['category_show_hierarchy'];
		$hide_empty     = 'yes' === $settings['category_hide_empty'];
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_cat    = isset( $_GET['product_cat'] ) ? sanitize_text_field( wp_unslash( $_GET['product_cat'] ) ) : '';

		$args = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => $hide_empty,
			'orderby'    => 'name',
			'order'      => 'ASC',
		);

		if ( ! $show_hierarchy ) {
			$args['parent'] = 0;
		}

		$categories = get_terms( $args );

		if ( empty( $categories ) || is_wp_error( $categories ) ) {
			return;
		}
		?>
		<div class="mpd-advanced-filter__section mpd-advanced-filter__section--category">
			<h4 class="mpd-advanced-filter__section-title"><?php echo esc_html( $settings['category_filter_title'] ); ?></h4>

			<?php if ( 'dropdown' === $display_type ) : ?>
				<select name="product_cat" class="mpd-advanced-filter__dropdown mpd-advanced-filter__dropdown--category">
					<option value=""><?php esc_html_e( 'All Categories', 'magical-products-display' ); ?></option>
					<?php foreach ( $categories as $category ) : ?>
						<option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( $current_cat, $category->slug ); ?>>
							<?php echo esc_html( $category->name ); ?>
							<?php if ( $show_count ) : ?>
								(<?php echo esc_html( $category->count ); ?>)
							<?php endif; ?>
						</option>
					<?php endforeach; ?>
				</select>

			<?php elseif ( 'checkbox' === $display_type ) : ?>
				<div class="mpd-advanced-filter__list mpd-advanced-filter__list--checkbox">
					<?php
					$selected_cats = ! empty( $current_cat ) ? explode( ',', $current_cat ) : array();
					foreach ( $categories as $category ) :
						?>
						<div class="mpd-advanced-filter__item <?php echo in_array( $category->slug, $selected_cats, true ) ? 'is-active' : ''; ?>">
							<label>
								<input type="checkbox" name="product_cat[]" value="<?php echo esc_attr( $category->slug ); ?>" class="mpd-advanced-filter__checkbox" <?php checked( in_array( $category->slug, $selected_cats, true ) ); ?>>
								<?php echo esc_html( $category->name ); ?>
								<?php if ( $show_count ) : ?>
									<span class="mpd-advanced-filter__count">(<?php echo esc_html( $category->count ); ?>)</span>
								<?php endif; ?>
							</label>
						</div>
					<?php endforeach; ?>
				</div>

			<?php else : ?>
				<ul class="mpd-advanced-filter__list mpd-advanced-filter__list--category">
					<?php foreach ( $categories as $category ) : ?>
						<li class="mpd-advanced-filter__item <?php echo esc_attr( $current_cat === $category->slug ? 'is-active' : '' ); ?>">
							<a href="<?php echo esc_url( add_query_arg( 'product_cat', $category->slug ) ); ?>" data-filter="product_cat" data-value="<?php echo esc_attr( $category->slug ); ?>">
								<?php echo esc_html( $category->name ); ?>
								<?php if ( $show_count ) : ?>
									<span class="mpd-advanced-filter__count">(<?php echo esc_html( $category->count ); ?>)</span>
								<?php endif; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render tags filter.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_tags_filter( $settings ) {
		$display_type = $settings['tags_display_type'];
		$show_count   = 'yes' === $settings['tags_show_count'];
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_tag  = isset( $_GET['product_tag'] ) ? sanitize_text_field( wp_unslash( $_GET['product_tag'] ) ) : '';

		$tags = get_terms(
			array(
				'taxonomy'   => 'product_tag',
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( empty( $tags ) || is_wp_error( $tags ) ) {
			return;
		}
		?>
		<div class="mpd-advanced-filter__section mpd-advanced-filter__section--tags">
			<h4 class="mpd-advanced-filter__section-title"><?php echo esc_html( $settings['tags_filter_title'] ); ?></h4>

			<?php if ( 'dropdown' === $display_type ) : ?>
				<select name="product_tag" class="mpd-advanced-filter__dropdown mpd-advanced-filter__dropdown--tags">
					<option value=""><?php esc_html_e( 'All Tags', 'magical-products-display' ); ?></option>
					<?php foreach ( $tags as $tag ) : ?>
						<option value="<?php echo esc_attr( $tag->slug ); ?>" <?php selected( $current_tag, $tag->slug ); ?>>
							<?php echo esc_html( $tag->name ); ?>
							<?php if ( $show_count ) : ?>
								(<?php echo esc_html( $tag->count ); ?>)
							<?php endif; ?>
						</option>
					<?php endforeach; ?>
				</select>

			<?php elseif ( 'checkbox' === $display_type ) : ?>
				<div class="mpd-advanced-filter__list mpd-advanced-filter__list--checkbox">
					<?php
					$selected_tags = ! empty( $current_tag ) ? explode( ',', $current_tag ) : array();
					foreach ( $tags as $tag ) :
						?>
						<div class="mpd-advanced-filter__item <?php echo in_array( $tag->slug, $selected_tags, true ) ? 'is-active' : ''; ?>">
							<label>
								<input type="checkbox" name="product_tag[]" value="<?php echo esc_attr( $tag->slug ); ?>" class="mpd-advanced-filter__checkbox" <?php checked( in_array( $tag->slug, $selected_tags, true ) ); ?>>
								<?php echo esc_html( $tag->name ); ?>
								<?php if ( $show_count ) : ?>
									<span class="mpd-advanced-filter__count">(<?php echo esc_html( $tag->count ); ?>)</span>
								<?php endif; ?>
							</label>
						</div>
					<?php endforeach; ?>
				</div>

			<?php elseif ( 'cloud' === $display_type ) : ?>
				<div class="mpd-advanced-filter__cloud">
					<?php foreach ( $tags as $tag ) : ?>
						<a href="<?php echo esc_url( add_query_arg( 'product_tag', $tag->slug ) ); ?>" class="mpd-advanced-filter__tag <?php echo esc_attr( $current_tag === $tag->slug ? 'is-active' : '' ); ?>" data-filter="product_tag" data-value="<?php echo esc_attr( $tag->slug ); ?>">
							<?php echo esc_html( $tag->name ); ?>
							<?php if ( $show_count ) : ?>
								<span class="mpd-advanced-filter__count">(<?php echo esc_html( $tag->count ); ?>)</span>
							<?php endif; ?>
						</a>
					<?php endforeach; ?>
				</div>

			<?php else : ?>
				<ul class="mpd-advanced-filter__list mpd-advanced-filter__list--tags">
					<?php foreach ( $tags as $tag ) : ?>
						<li class="mpd-advanced-filter__item <?php echo esc_attr( $current_tag === $tag->slug ? 'is-active' : '' ); ?>">
							<a href="<?php echo esc_url( add_query_arg( 'product_tag', $tag->slug ) ); ?>" data-filter="product_tag" data-value="<?php echo esc_attr( $tag->slug ); ?>">
								<?php echo esc_html( $tag->name ); ?>
								<?php if ( $show_count ) : ?>
									<span class="mpd-advanced-filter__count">(<?php echo esc_html( $tag->count ); ?>)</span>
								<?php endif; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render brand filter.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_brand_filter( $settings ) {
		$taxonomy      = $settings['brand_taxonomy'];
		$display_type  = $settings['brand_display_type'];
		$show_count    = 'yes' === $settings['brand_show_count'];
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_brand = isset( $_GET[ $taxonomy ] ) ? sanitize_text_field( wp_unslash( $_GET[ $taxonomy ] ) ) : '';

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$brands = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);

		if ( empty( $brands ) || is_wp_error( $brands ) ) {
			return;
		}
		?>
		<div class="mpd-advanced-filter__section mpd-advanced-filter__section--brand">
			<h4 class="mpd-advanced-filter__section-title"><?php echo esc_html( $settings['brand_filter_title'] ); ?></h4>

			<?php if ( 'dropdown' === $display_type ) : ?>
				<select name="<?php echo esc_attr( $taxonomy ); ?>" class="mpd-advanced-filter__dropdown mpd-advanced-filter__dropdown--brand">
					<option value=""><?php esc_html_e( 'All Brands', 'magical-products-display' ); ?></option>
					<?php foreach ( $brands as $brand ) : ?>
						<option value="<?php echo esc_attr( $brand->slug ); ?>" <?php selected( $current_brand, $brand->slug ); ?>>
							<?php echo esc_html( $brand->name ); ?>
							<?php if ( $show_count ) : ?>
								(<?php echo esc_html( $brand->count ); ?>)
							<?php endif; ?>
						</option>
					<?php endforeach; ?>
				</select>

			<?php elseif ( 'checkbox' === $display_type ) : ?>
				<div class="mpd-advanced-filter__list mpd-advanced-filter__list--checkbox">
					<?php
					$selected_brands = ! empty( $current_brand ) ? explode( ',', $current_brand ) : array();
					foreach ( $brands as $brand ) :
						?>
						<div class="mpd-advanced-filter__item <?php echo in_array( $brand->slug, $selected_brands, true ) ? 'is-active' : ''; ?>">
							<label>
								<input type="checkbox" name="<?php echo esc_attr( $taxonomy ); ?>[]" value="<?php echo esc_attr( $brand->slug ); ?>" class="mpd-advanced-filter__checkbox" <?php checked( in_array( $brand->slug, $selected_brands, true ) ); ?>>
								<?php echo esc_html( $brand->name ); ?>
								<?php if ( $show_count ) : ?>
									<span class="mpd-advanced-filter__count">(<?php echo esc_html( $brand->count ); ?>)</span>
								<?php endif; ?>
							</label>
						</div>
					<?php endforeach; ?>
				</div>

			<?php else : ?>
				<ul class="mpd-advanced-filter__list mpd-advanced-filter__list--brand">
					<?php foreach ( $brands as $brand ) : ?>
						<li class="mpd-advanced-filter__item <?php echo esc_attr( $current_brand === $brand->slug ? 'is-active' : '' ); ?>">
							<a href="<?php echo esc_url( add_query_arg( $taxonomy, $brand->slug ) ); ?>" data-filter="<?php echo esc_attr( $taxonomy ); ?>" data-value="<?php echo esc_attr( $brand->slug ); ?>">
								<?php echo esc_html( $brand->name ); ?>
								<?php if ( $show_count ) : ?>
									<span class="mpd-advanced-filter__count">(<?php echo esc_html( $brand->count ); ?>)</span>
								<?php endif; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render price filter.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_price_filter( $settings ) {
		$display_type = $settings['price_display_type'];

		// Get price range from products.
		$prices = $this->get_filtered_price_range();

		if ( empty( $prices['min'] ) && empty( $prices['max'] ) ) {
			return;
		}

		$min_price = $prices['min'];
		$max_price = $prices['max'];

		// Get current filter values.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_min_price = isset( $_GET['min_price'] ) ? floor( floatval( wp_unslash( $_GET['min_price'] ) ) ) : $min_price;
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_max_price = isset( $_GET['max_price'] ) ? ceil( floatval( wp_unslash( $_GET['max_price'] ) ) ) : $max_price;

		$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();
		?>
		<div class="mpd-advanced-filter__section mpd-advanced-filter__section--price widget_price_filter">
			<h4 class="mpd-advanced-filter__section-title"><?php echo esc_html( $settings['price_filter_title'] ); ?></h4>

			<?php if ( 'slider' === $display_type ) : ?>
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
						<input type="hidden" id="min_price" name="min_price" value="<?php echo esc_attr( $current_min_price ); ?>" data-min="<?php echo esc_attr( $min_price ); ?>" placeholder="<?php echo esc_attr__( 'Min price', 'magical-products-display' ); ?>" class="min_price" />
						<input type="hidden" id="max_price" name="max_price" value="<?php echo esc_attr( $current_max_price ); ?>" data-max="<?php echo esc_attr( $max_price ); ?>" placeholder="<?php echo esc_attr__( 'Max price', 'magical-products-display' ); ?>" class="max_price" />
						<div class="price_label" style="<?php echo esc_attr( $is_editor ? '' : 'display:none;' ); ?>">
							<?php esc_html_e( 'Price:', 'magical-products-display' ); ?>
							<?php if ( $is_editor ) : ?>
								<span class="from"><?php echo wc_price( $min_price ); ?></span> &mdash; <span class="to"><?php echo wc_price( $max_price ); ?></span>
							<?php else : ?>
								<span class="from"></span> &mdash; <span class="to"></span>
							<?php endif; ?>
						</div>
					</div>
				</div>

			<?php else : ?>
				<div class="mpd-advanced-filter__price-inputs">
					<div class="mpd-advanced-filter__price-input">
						<label><?php esc_html_e( 'Min', 'magical-products-display' ); ?></label>
						<input type="number" name="min_price" value="<?php echo esc_attr( $current_min_price ); ?>" min="<?php echo esc_attr( $min_price ); ?>" max="<?php echo esc_attr( $max_price ); ?>" placeholder="<?php echo esc_attr( $min_price ); ?>" class="mpd-advanced-filter__input">
					</div>
					<span class="mpd-advanced-filter__price-separator">-</span>
					<div class="mpd-advanced-filter__price-input">
						<label><?php esc_html_e( 'Max', 'magical-products-display' ); ?></label>
						<input type="number" name="max_price" value="<?php echo esc_attr( $current_max_price ); ?>" min="<?php echo esc_attr( $min_price ); ?>" max="<?php echo esc_attr( $max_price ); ?>" placeholder="<?php echo esc_attr( $max_price ); ?>" class="mpd-advanced-filter__input">
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get filtered price range.
	 *
	 * @since 2.0.0
	 *
	 * @return array Min and max prices.
	 */
	protected function get_filtered_price_range() {
		global $wpdb;

		// Use direct SQL for performance instead of loading all products.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT MIN( CAST( pm.meta_value AS DECIMAL(10,2) ) ) AS min_price,
				        MAX( CAST( pm.meta_value AS DECIMAL(10,2) ) ) AS max_price
				 FROM {$wpdb->postmeta} pm
				 INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
				 WHERE pm.meta_key = %s
				   AND pm.meta_value != ''
				   AND pm.meta_value IS NOT NULL
				   AND p.post_type = %s
				   AND p.post_status = %s",
				'_price',
				'product',
				'publish'
			)
		);

		if ( ! $result || null === $result->min_price ) {
			return array(
				'min' => 0,
				'max' => 100,
			);
		}

		return array(
			'min' => floor( floatval( $result->min_price ) ),
			'max' => ceil( floatval( $result->max_price ) ),
		);
	}

	/**
	 * Render stock filter.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_stock_filter( $settings ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_stock = isset( $_GET['stock_status'] ) ? sanitize_text_field( wp_unslash( $_GET['stock_status'] ) ) : '';
		?>
		<div class="mpd-advanced-filter__section mpd-advanced-filter__section--stock">
			<h4 class="mpd-advanced-filter__section-title"><?php echo esc_html( $settings['stock_filter_title'] ); ?></h4>

			<div class="mpd-advanced-filter__list mpd-advanced-filter__list--checkbox">
				<div class="mpd-advanced-filter__item <?php echo 'instock' === $current_stock ? 'is-active' : ''; ?>">
					<label>
						<input type="radio" name="stock_status" value="instock" class="mpd-advanced-filter__radio" <?php checked( $current_stock, 'instock' ); ?>>
						<?php echo esc_html( $settings['stock_in_stock_text'] ); ?>
					</label>
				</div>
				<div class="mpd-advanced-filter__item <?php echo 'outofstock' === $current_stock ? 'is-active' : ''; ?>">
					<label>
						<input type="radio" name="stock_status" value="outofstock" class="mpd-advanced-filter__radio" <?php checked( $current_stock, 'outofstock' ); ?>>
						<?php echo esc_html( $settings['stock_out_of_stock_text'] ); ?>
					</label>
				</div>
				<div class="mpd-advanced-filter__item <?php echo 'onbackorder' === $current_stock ? 'is-active' : ''; ?>">
					<label>
						<input type="radio" name="stock_status" value="onbackorder" class="mpd-advanced-filter__radio" <?php checked( $current_stock, 'onbackorder' ); ?>>
						<?php echo esc_html( $settings['stock_on_backorder_text'] ); ?>
					</label>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render featured filter.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_featured_filter( $settings ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$is_featured = isset( $_GET['featured'] ) && 'yes' === sanitize_text_field( wp_unslash( $_GET['featured'] ) );
		?>
		<div class="mpd-advanced-filter__section mpd-advanced-filter__section--featured">
			<div class="mpd-advanced-filter__item <?php echo esc_attr( $is_featured ? 'is-active' : '' ); ?>">
				<label>
					<input type="checkbox" name="featured" value="yes" class="mpd-advanced-filter__checkbox" <?php checked( $is_featured ); ?>>
					<?php echo esc_html( $settings['featured_filter_text'] ); ?>
				</label>
			</div>
		</div>
		<?php
	}

	/**
	 * Render sale filter.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_sale_filter( $settings ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$is_on_sale = isset( $_GET['on_sale'] ) && 'yes' === sanitize_text_field( wp_unslash( $_GET['on_sale'] ) );
		?>
		<div class="mpd-advanced-filter__section mpd-advanced-filter__section--sale">
			<div class="mpd-advanced-filter__item <?php echo esc_attr( $is_on_sale ? 'is-active' : '' ); ?>">
				<label>
					<input type="checkbox" name="on_sale" value="yes" class="mpd-advanced-filter__checkbox" <?php checked( $is_on_sale ); ?>>
					<?php echo esc_html( $settings['sale_filter_text'] ); ?>
				</label>
			</div>
		</div>
		<?php
	}

	/**
	 * Render rating filter.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_rating_filter( $settings ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_rating = isset( $_GET['rating_filter'] ) ? intval( $_GET['rating_filter'] ) : 0;
		?>
		<div class="mpd-advanced-filter__section mpd-advanced-filter__section--rating">
			<h4 class="mpd-advanced-filter__section-title"><?php echo esc_html( $settings['rating_filter_title'] ); ?></h4>

			<div class="mpd-advanced-filter__list mpd-advanced-filter__list--rating">
				<?php for ( $rating = 5; $rating >= 1; $rating-- ) : ?>
					<div class="mpd-advanced-filter__item <?php echo esc_attr( $current_rating === $rating ? 'is-active' : '' ); ?>">
						<label>
							<input type="radio" name="rating_filter" value="<?php echo esc_attr( $rating ); ?>" class="mpd-advanced-filter__radio" <?php checked( $current_rating, $rating ); ?>>
							<span class="mpd-advanced-filter__stars">
								<?php
								for ( $i = 1; $i <= 5; $i++ ) {
									if ( $i <= $rating ) {
										echo '<span class="star filled">★</span>';
									} else {
										echo '<span class="star empty">☆</span>';
									}
								}
								?>
							</span>
							<span class="mpd-advanced-filter__rating-text">
								<?php
								if ( 5 === $rating ) {
									esc_html_e( '5 stars', 'magical-products-display' );
								} else {
									/* translators: %d: star rating */
									printf( esc_html__( '%d stars & up', 'magical-products-display' ), $rating );
								}
								?>
							</span>
						</label>
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php
	}
}
