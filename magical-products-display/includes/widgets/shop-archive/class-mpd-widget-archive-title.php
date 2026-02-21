<?php
/**
 * Archive Title Widget
 *
 * Displays the WooCommerce archive/category/tag title.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\ShopArchive;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Archive_Title
 *
 * @since 2.0.0
 */
class Archive_Title extends Widget_Base {

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
	protected $widget_icon = 'eicon-archive-title';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-archive-title';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Shop/Archive Title', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'archive', 'title', 'category', 'tag', 'shop', 'woocommerce', 'heading' );
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
			'title_source',
			array(
				'label'   => esc_html__( 'Title Source', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'archive',
				'options' => array(
					'archive' => esc_html__( 'Shop/Archive Title', 'magical-products-display' ),
					'custom'  => esc_html__( 'Custom Title', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'shop_page_title',
			array(
				'label'       => esc_html__( 'Shop Page Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'Leave empty for default', 'magical-products-display' ),
				'description' => esc_html__( 'Custom title for the main shop page. Leave empty to use the default WooCommerce shop page title.', 'magical-products-display' ),
				'condition'   => array(
					'title_source' => 'archive',
				),
			)
		);

		$this->add_control(
			'custom_title',
			array(
				'label'       => esc_html__( 'Custom Title', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Shop', 'magical-products-display' ),
				'placeholder' => esc_html__( 'Enter custom title', 'magical-products-display' ),
				'condition'   => array(
					'title_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'html_tag',
			array(
				'label'   => esc_html__( 'HTML Tag', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h1',
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
			)
		);

		$this->add_control(
			'show_prefix',
			array(
				'label'        => esc_html__( 'Show Archive Prefix', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Show "Category:", "Tag:", etc. before the title', 'magical-products-display' ),
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'title_source' => 'archive',
				),
			)
		);

		$this->add_responsive_control(
			'alignment',
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
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .mpd-archive-title' => 'text-align: {{VALUE}};',
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
				'label' => esc_html__( 'Title', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-archive-title__heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mpd-archive-title__heading',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'title_text_shadow',
				'selector' => '{{WRAPPER}} .mpd-archive-title__heading',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-archive-title__heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Prefix Style Section.
		$this->start_controls_section(
			'section_prefix_style',
			array(
				'label'     => esc_html__( 'Prefix', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_prefix'  => 'yes',
					'title_source' => 'archive',
				),
			)
		);

		$this->add_control(
			'prefix_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-archive-title__prefix' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'prefix_typography',
				'selector' => '{{WRAPPER}} .mpd-archive-title__prefix',
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
		$title  = '';
		$prefix = '';

		if ( 'custom' === $settings['title_source'] ) {
			$title = $settings['custom_title'];
		} else {
			// Get archive title based on context.
			if ( is_shop() ) {
				// Use custom shop page title if set, otherwise use default.
				if ( ! empty( $settings['shop_page_title'] ) ) {
					$title = $settings['shop_page_title'];
				} else {
					$title = woocommerce_page_title( false );
				}
			} elseif ( is_product_category() ) {
				$term   = get_queried_object();
				$title  = $term->name;
				$prefix = esc_html__( 'Category:', 'magical-products-display' );
			} elseif ( is_product_tag() ) {
				$term   = get_queried_object();
				$title  = $term->name;
				$prefix = esc_html__( 'Tag:', 'magical-products-display' );
			} elseif ( is_product_taxonomy() ) {
				$term = get_queried_object();
				if ( $term ) {
					$title = $term->name;
					$tax   = get_taxonomy( $term->taxonomy );
					if ( $tax ) {
						$prefix = $tax->labels->singular_name . ':';
					}
				}
			} elseif ( is_search() ) {
				/* translators: %s: search query */
				$title = sprintf( esc_html__( 'Search results: "%s"', 'magical-products-display' ), get_search_query() );
			} else {
				$title = esc_html__( 'Shop', 'magical-products-display' );
			}

			// For editor preview.
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				if ( ! empty( $settings['shop_page_title'] ) ) {
					// Show custom shop page title in editor.
					$title = $settings['shop_page_title'];
				} elseif ( empty( $title ) ) {
					$title = esc_html__( 'Shop/Archive Title', 'magical-products-display' );
				}
			}
		}

		$tag = \Elementor\Utils::validate_html_tag( $settings['html_tag'] );
		?>
		<div class="mpd-archive-title">
			<<?php echo esc_attr( $tag ); ?> class="mpd-archive-title__heading">
				<?php if ( 'yes' === $settings['show_prefix'] && ! empty( $prefix ) && 'archive' === $settings['title_source'] ) : ?>
					<span class="mpd-archive-title__prefix"><?php echo esc_html( $prefix ); ?></span>
				<?php endif; ?>
				<span class="mpd-archive-title__text"><?php echo esc_html( $title ); ?></span>
			</<?php echo esc_attr( $tag ); ?>>
		</div>
		<?php
	}
}
