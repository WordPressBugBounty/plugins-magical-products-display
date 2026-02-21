<?php
/**
 * Archive Description Widget
 *
 * Displays the WooCommerce category/tag description.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\ShopArchive;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Archive_Description
 *
 * @since 2.0.0
 */
class Archive_Description extends Widget_Base {

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
	protected $widget_icon = 'eicon-align-left';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-archive-description';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Archive Description', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'archive', 'description', 'category', 'tag', 'shop', 'woocommerce', 'text' );
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
			'description_source',
			array(
				'label'   => esc_html__( 'Description Source', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'archive',
				'options' => array(
					'archive' => esc_html__( 'Shop/Archive Description', 'magical-products-display' ),
					'custom'  => esc_html__( 'Custom Description', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'custom_description',
			array(
				'label'       => esc_html__( 'Custom Description', 'magical-products-display' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => esc_html__( 'Browse our products and find what you\'re looking for.', 'magical-products-display' ),
				'placeholder' => esc_html__( 'Enter custom description', 'magical-products-display' ),
				'condition'   => array(
					'description_source' => 'custom',
				),
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .mpd-archive-description' => 'text-align: {{VALUE}};',
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
		// Description Style Section.
		$this->start_controls_section(
			'section_description_style',
			array(
				'label' => esc_html__( 'Description', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-archive-description__content' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-archive-description__content p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .mpd-archive-description__content, {{WRAPPER}} .mpd-archive-description__content p',
			)
		);

		$this->add_responsive_control(
			'description_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-archive-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'description_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-archive-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$description = '';

		if ( 'custom' === $settings['description_source'] ) {
			$description = $settings['custom_description'];
		} else {
			// Get archive description based on context.
			if ( is_product_category() || is_product_tag() || is_product_taxonomy() ) {
				$term = get_queried_object();
				if ( $term && ! empty( $term->description ) ) {
					$description = $term->description;
				}
			} elseif ( is_shop() ) {
				$shop_page_id = wc_get_page_id( 'shop' );
				if ( $shop_page_id > 0 ) {
					$shop_page   = get_post( $shop_page_id );
					$description = $shop_page ? $shop_page->post_content : '';
				}
			}

			// For editor preview.
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && empty( $description ) ) {
				$description = esc_html__( 'This is where the category or archive description will appear. Add a description to your product categories to display content here.', 'magical-products-display' );
			}
		}

		// Don't render if no description.
		if ( empty( $description ) && ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return;
		}
		?>
		<div class="mpd-archive-description">
			<div class="mpd-archive-description__content">
				<?php echo wp_kses_post( wpautop( $description ) ); ?>
			</div>
		</div>
		<?php
	}
}
