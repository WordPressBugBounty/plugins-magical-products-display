<?php
/**
 * Pagination Widget
 *
 * Displays the WooCommerce product pagination with multiple styles.
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

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Pagination
 *
 * @since 2.0.0
 */
class Pagination extends Widget_Base {

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
	protected $widget_icon = 'eicon-pagination';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-pagination';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Pagination', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'pagination', 'paging', 'page', 'products', 'shop', 'woocommerce', 'navigation' );
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
			'pagination_type',
			array(
				'label'   => esc_html__( 'Pagination Type', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'numbers',
				'options' => array(
					'numbers'         => esc_html__( 'Numbers', 'magical-products-display' ),
					'numbers_arrows'  => esc_html__( 'Numbers + Arrows', 'magical-products-display' ),
					'prev_next'       => esc_html__( 'Previous/Next', 'magical-products-display' ),
					'load_more'       => $this->pro_label( esc_html__( 'Load More Button', 'magical-products-display' ) ),
				),
			)
		);

		// Pro notice for Load More option.
		if ( ! $this->is_pro() ) {
			$this->add_control(
				'load_more_pro_notice',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => $this->get_pro_notice( __( 'Load More Button pagination requires the Pro version. Standard pagination will be used instead.', 'magical-products-display' ) ),
					'content_classes' => 'elementor-panel-alert',
					'condition'       => array(
						'pagination_type' => 'load_more',
					),
				)
			);
		}

		$this->add_control(
			'prev_label',
			array(
				'label'     => esc_html__( 'Previous Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( '« Previous', 'magical-products-display' ),
				'condition' => array(
					'pagination_type' => array( 'prev_next', 'numbers_arrows' ),
				),
			)
		);

		$this->add_control(
			'next_label',
			array(
				'label'     => esc_html__( 'Next Label', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Next »', 'magical-products-display' ),
				'condition' => array(
					'pagination_type' => array( 'prev_next', 'numbers_arrows' ),
				),
			)
		);

		$this->add_control(
			'load_more_text',
			array(
				'label'     => esc_html__( 'Button Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Load More Products', 'magical-products-display' ),
				'condition' => array(
					'pagination_type' => 'load_more',
				),
			)
		);

		$this->add_control(
			'loading_text',
			array(
				'label'     => esc_html__( 'Loading Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Loading...', 'magical-products-display' ),
				'condition' => array(
					'pagination_type' => 'load_more',
				),
			)
		);

		$this->add_control(
			'page_limit',
			array(
				'label'       => esc_html__( 'Page Limit', 'magical-products-display' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 5,
				'min'         => 1,
				'max'         => 20,
				'description' => esc_html__( 'Number of pages to show (0 for all).', 'magical-products-display' ),
				'condition'   => array(
					'pagination_type' => array( 'numbers', 'numbers_arrows' ),
				),
			)
		);

		$this->add_control(
			'show_first_last',
			array(
				'label'        => esc_html__( 'Show First/Last', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'pagination_type' => array( 'numbers', 'numbers_arrows' ),
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .mpd-pagination' => 'justify-content: {{VALUE}};',
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
		// Pagination Style Section.
		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label' => esc_html__( 'Pagination', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'pagination_spacing',
			array(
				'label'      => esc_html__( 'Items Spacing', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'size' => 5,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-pagination__list' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_pagination_style' );

		$this->start_controls_tab(
			'tab_pagination_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.mpd-pagination ul li.mpd-pagination__item a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pagination_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.mpd-pagination ul li.mpd-pagination__item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'pagination_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.mpd-pagination ul li.mpd-pagination__item:hover a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pagination_background_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.mpd-pagination ul li.mpd-pagination__item:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_active',
			array(
				'label' => esc_html__( 'Active', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'pagination_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.mpd-pagination ul li.mpd-pagination__item.is-current' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pagination_background_active',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} nav.mpd-pagination ul li.mpd-pagination__item.is-current' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'pagination_typography',
				'selector'  => '{{WRAPPER}} nav.mpd-pagination ul li.mpd-pagination__item a, {{WRAPPER}} nav.mpd-pagination ul li.mpd-pagination__item span',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pagination_border',
				'selector' => '{{WRAPPER}} nav.mpd-pagination ul li.mpd-pagination__item',
			)
		);

		$this->add_responsive_control(
			'pagination_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} nav.mpd-pagination ul li.mpd-pagination__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} nav.mpd-pagination ul li.mpd-pagination__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_min_size',
			array(
				'label'      => esc_html__( 'Min Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} nav.mpd-pagination ul li.mpd-pagination__item' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Load More Button Style Section.
		$this->start_controls_section(
			'section_load_more_style',
			array(
				'label'     => esc_html__( 'Load More Button', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'pagination_type' => 'load_more',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'load_more_typography',
				'selector' => '{{WRAPPER}} .mpd-pagination__load-more',
			)
		);

		$this->start_controls_tabs( 'tabs_load_more_style' );

		$this->start_controls_tab(
			'tab_load_more_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'load_more_color',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-pagination__load-more' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'load_more_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-pagination__load-more' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_load_more_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'load_more_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-pagination__load-more:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'load_more_background_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-pagination__load-more:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'load_more_border',
				'selector'  => '{{WRAPPER}} .mpd-pagination__load-more',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'load_more_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-pagination__load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'load_more_padding',
			array(
				'label'      => esc_html__( 'Padding', 'magical-products-display' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-pagination__load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		global $wp_query;

		$total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
		$current = max( 1, get_query_var( 'paged' ) );

		// Show demo pagination in editor for styling purposes.
		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			$this->render_editor_preview( $settings );
			return;
		}

		if ( $total <= 1 ) {
			return;
		}

		$pagination_type = $settings['pagination_type'];

		// Load More requires Pro - fallback to numbers pagination.
		if ( 'load_more' === $pagination_type && ! $this->is_pro() ) {
			$pagination_type = 'numbers';
		}

		?>
		<nav class="mpd-pagination" aria-label="<?php esc_attr_e( 'Product Pagination', 'magical-products-display' ); ?>">
			<?php
			switch ( $pagination_type ) {
				case 'prev_next':
					$this->render_prev_next_pagination( $settings, $current, $total );
					break;

				case 'load_more':
					$this->render_load_more_pagination( $settings, $current, $total );
					break;

				case 'numbers':
				case 'numbers_arrows':
				default:
					$this->render_numbers_pagination( $settings, $current, $total );
					break;
			}
			?>
		</nav>
		<?php
	}

	/**
	 * Render numbers pagination.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @param int   $current  Current page.
	 * @param int   $total    Total pages.
	 * @return void
	 */
	protected function render_numbers_pagination( $settings, $current, $total ) {
		$show_arrows = 'numbers_arrows' === $settings['pagination_type'];
		$page_limit  = ! empty( $settings['page_limit'] ) ? absint( $settings['page_limit'] ) : 5;

		$links_args = array(
			'base'      => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
			'format'    => '',
			'add_args'  => false,
			'current'   => $current,
			'total'     => $total,
			'prev_next' => $show_arrows,
			'prev_text' => $show_arrows ? esc_html( $settings['prev_label'] ) : '',
			'next_text' => $show_arrows ? esc_html( $settings['next_label'] ) : '',
			'type'      => 'array',
			'end_size'  => 'yes' === $settings['show_first_last'] ? 1 : 0,
			'mid_size'  => $page_limit > 0 ? floor( $page_limit / 2 ) : 2,
		);

		$links = paginate_links( $links_args );

		if ( $links ) {
			echo '<ul class="mpd-pagination__list page-numbers">';
			foreach ( $links as $link ) {
				$is_current = strpos( $link, 'current' ) !== false;
				$class      = $is_current ? 'mpd-pagination__item is-current' : 'mpd-pagination__item';
				echo '<li class="' . esc_attr( $class ) . '">' . $link . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			echo '</ul>';
		}
	}

	/**
	 * Render previous/next pagination.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @param int   $current  Current page.
	 * @param int   $total    Total pages.
	 * @return void
	 */
	protected function render_prev_next_pagination( $settings, $current, $total ) {
		?>
		<ul class="mpd-pagination__list mpd-pagination__list--prev-next">
			<?php if ( $current > 1 ) : ?>
				<li class="mpd-pagination__item mpd-pagination__item--prev">
					<a href="<?php echo esc_url( get_pagenum_link( $current - 1 ) ); ?>">
						<?php echo esc_html( $settings['prev_label'] ); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if ( $current < $total ) : ?>
				<li class="mpd-pagination__item mpd-pagination__item--next">
					<a href="<?php echo esc_url( get_pagenum_link( $current + 1 ) ); ?>">
						<?php echo esc_html( $settings['next_label'] ); ?>
					</a>
				</li>
			<?php endif; ?>
		</ul>
		<?php
	}

	/**
	 * Render load more pagination.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @param int   $current  Current page.
	 * @param int   $total    Total pages.
	 * @return void
	 */
	protected function render_load_more_pagination( $settings, $current, $total ) {
		if ( $current >= $total ) {
			return;
		}

		$next_page_url = get_pagenum_link( $current + 1 );
		?>
		<div class="mpd-pagination__load-more-wrap">
			<button 
				type="button" 
				class="mpd-pagination__load-more" 
				data-page="<?php echo esc_attr( $current ); ?>"
				data-max-pages="<?php echo esc_attr( $total ); ?>"
				data-next-url="<?php echo esc_url( $next_page_url ); ?>"
				data-loading-text="<?php echo esc_attr( $settings['loading_text'] ); ?>"
			>
				<?php echo esc_html( $settings['load_more_text'] ); ?>
			</button>
		</div>
		<?php
	}

	/**
	 * Render editor preview pagination for styling.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_editor_preview( $settings ) {
		$pagination_type = $settings['pagination_type'];
		
		// Load More requires Pro - show numbers preview with notice instead.
		if ( 'load_more' === $pagination_type && ! $this->is_pro() ) {
			$pagination_type = 'numbers';
		}
		
		$show_arrows     = 'numbers_arrows' === $pagination_type;
		?>
		<nav class="mpd-pagination mpd-pagination--preview" aria-label="<?php esc_attr_e( 'Product Pagination Preview', 'magical-products-display' ); ?>">
			<?php if ( 'prev_next' === $pagination_type ) : ?>
				<ul class="mpd-pagination__list mpd-pagination__list--prev-next">
					<li class="mpd-pagination__item mpd-pagination__item--prev">
						<a href="#" class="page-numbers prev" onclick="return false;">
							<?php echo esc_html( $settings['prev_label'] ); ?>
						</a>
					</li>
					<li class="mpd-pagination__item mpd-pagination__item--next">
						<a href="#" class="page-numbers next" onclick="return false;">
							<?php echo esc_html( $settings['next_label'] ); ?>
						</a>
					</li>
				</ul>

			<?php elseif ( 'load_more' === $pagination_type ) : ?>
				<div class="mpd-pagination__load-more-wrap">
					<button type="button" class="mpd-pagination__load-more mpd-pagination__load-more--editor-preview" disabled style="pointer-events: none; cursor: default;">
						<?php echo esc_html( $settings['load_more_text'] ); ?>
					</button>
				</div>

			<?php else : ?>
				<ul class="mpd-pagination__list page-numbers">
					<?php if ( $show_arrows ) : ?>
						<li class="mpd-pagination__item">
							<a href="#" class="page-numbers prev" onclick="return false;">
								<?php echo esc_html( $settings['prev_label'] ); ?>
							</a>
						</li>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_first_last'] ) : ?>
						<li class="mpd-pagination__item">
							<a href="#" class="page-numbers" onclick="return false;">1</a>
						</li>
						<li class="mpd-pagination__item">
							<span class="page-numbers dots">…</span>
						</li>
					<?php endif; ?>

					<li class="mpd-pagination__item">
						<a href="#" class="page-numbers" onclick="return false;">1</a>
					</li>
					<li class="mpd-pagination__item is-current">
						<span aria-current="page" class="page-numbers current">2</span>
					</li>
					<li class="mpd-pagination__item">
						<a href="#" class="page-numbers" onclick="return false;">3</a>
					</li>
					<li class="mpd-pagination__item">
						<a href="#" class="page-numbers" onclick="return false;">4</a>
					</li>
					<li class="mpd-pagination__item">
						<a href="#" class="page-numbers" onclick="return false;">5</a>
					</li>

					<?php if ( 'yes' === $settings['show_first_last'] ) : ?>
						<li class="mpd-pagination__item">
							<span class="page-numbers dots">…</span>
						</li>
						<li class="mpd-pagination__item">
							<a href="#" class="page-numbers" onclick="return false;">10</a>
						</li>
					<?php endif; ?>

					<?php if ( $show_arrows ) : ?>
						<li class="mpd-pagination__item">
							<a href="#" class="page-numbers next" onclick="return false;">
								<?php echo esc_html( $settings['next_label'] ); ?>
							</a>
						</li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
		</nav>
		<?php
	}
}
