<?php
/**
 * Result Count Widget
 *
 * Displays the WooCommerce product result count.
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
 * Class Result_Count
 *
 * @since 2.0.0
 */
class Result_Count extends Widget_Base {

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
	protected $widget_icon = 'eicon-counter';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-result-count';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Result Count', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'result', 'count', 'products', 'shop', 'woocommerce', 'showing' );
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
			'format_type',
			array(
				'label'   => esc_html__( 'Format', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Showing X-X of X results', 'magical-products-display' ),
					'simple'  => esc_html__( 'X Products', 'magical-products-display' ),
					'custom'  => esc_html__( 'Custom Format', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'custom_format',
			array(
				'label'       => esc_html__( 'Custom Format', 'magical-products-display' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Showing {first} to {last} of {total} products', 'magical-products-display' ),
				'description' => esc_html__( 'Available placeholders: {first}, {last}, {total}, {per_page}, {current_page}', 'magical-products-display' ),
				'condition'   => array(
					'format_type' => 'custom',
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
					'{{WRAPPER}} .mpd-result-count' => 'text-align: {{VALUE}};',
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
		// Result Count Style Section.
		$this->start_controls_section(
			'section_count_style',
			array(
				'label' => esc_html__( 'Result Count', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-result-count__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_typography',
				'selector' => '{{WRAPPER}} .mpd-result-count__text',
			)
		);

		$this->add_responsive_control(
			'count_margin',
			array(
				'label'      => esc_html__( 'Margin', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-result-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		// Get WooCommerce loop properties for proper count.
		$total    = 0;
		$per_page = 0;
		$current  = max( 1, get_query_var( 'paged', 1 ) );

		// Check if we're on a WooCommerce shop/archive page.
		if ( function_exists( 'wc_get_loop_prop' ) ) {
			$total    = wc_get_loop_prop( 'total', 0 );
			$per_page = wc_get_loop_prop( 'per_page', 0 );
		}

		// Fallback to WP_Query if WooCommerce loop properties are not set.
		if ( 0 === $total ) {
			global $wp_query;
			if ( is_shop() || is_product_taxonomy() || is_search() ) {
				$total    = isset( $wp_query->found_posts ) ? $wp_query->found_posts : 0;
				$per_page = isset( $wp_query->query_vars['posts_per_page'] ) ? $wp_query->query_vars['posts_per_page'] : wc_get_default_products_per_row() * wc_get_default_product_rows_per_page();
			}
		}

		// If still 0, use WooCommerce default query.
		if ( 0 === $total && function_exists( 'wc' ) ) {
			$total    = wc()->query->get_main_query() ? wc()->query->get_main_query()->found_posts : 0;
			$per_page = wc()->query->get_main_query() ? wc()->query->get_main_query()->query_vars['posts_per_page'] : get_option( 'posts_per_page' );
		}

		// Calculate first and last.
		$first = ( $per_page * $current ) - $per_page + 1;
		$last  = min( $total, $per_page * $current );

		// For editor preview.
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && 0 === $total ) {
			$total    = 24;
			$per_page = 12;
			$first    = 1;
			$last     = 12;
		}

		// Generate result text.
		$result_text = '';

		if ( 'default' === $settings['format_type'] ) {
			if ( 1 === $total ) {
				$result_text = esc_html__( 'Showing the single result', 'magical-products-display' );
			} elseif ( $total <= $per_page || -1 === $per_page ) {
				/* translators: %d: total results */
				$result_text = sprintf( esc_html__( 'Showing all %d results', 'magical-products-display' ), $total );
			} else {
				/* translators: 1: first result 2: last result 3: total results */
				$result_text = sprintf( esc_html__( 'Showing %1$d&ndash;%2$d of %3$d results', 'magical-products-display' ), $first, $last, $total );
			}
		} elseif ( 'simple' === $settings['format_type'] ) {
			/* translators: %d: product count */
			$result_text = sprintf( _n( '%d Product', '%d Products', $total, 'magical-products-display' ), $total );
		} elseif ( 'custom' === $settings['format_type'] ) {
			$result_text = str_replace(
				array( '{first}', '{last}', '{total}', '{per_page}', '{current_page}' ),
				array( $first, $last, $total, $per_page, $current ),
				$settings['custom_format']
			);
		}
		?>
		<div class="mpd-result-count woocommerce-result-count">
			<p class="mpd-result-count__text"><?php echo wp_kses_post( $result_text ); ?></p>
		</div>
		<?php
	}
}
