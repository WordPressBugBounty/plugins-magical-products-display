<?php
/**
 * Breadcrumbs Widget
 *
 * Displays WooCommerce breadcrumbs with customizable separator and schema support.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\GlobalWidgets;

use MPD\MagicalShopBuilder\Widgets\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Breadcrumbs
 *
 * @since 2.0.0
 */
class Breadcrumbs extends Widget_Base {

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_GLOBAL;

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	protected $widget_icon = 'eicon-post-navigation';

	/**
	 * Get widget name.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mpd-breadcrumbs';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'MPD Breadcrumbs', 'magical-products-display' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'breadcrumbs', 'navigation', 'path', 'woocommerce', 'shop' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style dependencies.
	 */
	public function get_style_depends() {
		return array( 'mpd-global-widgets' );
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
				'label' => esc_html__( 'Breadcrumbs', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_home',
			array(
				'label'        => esc_html__( 'Show Home Link', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'home_text',
			array(
				'label'     => esc_html__( 'Home Text', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Home', 'magical-products-display' ),
				'condition' => array(
					'show_home' => 'yes',
				),
			)
		);

		$this->add_control(
			'separator_type',
			array(
				'label'   => esc_html__( 'Separator Type', 'magical-products-display' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'text' => esc_html__( 'Text', 'magical-products-display' ),
					'icon' => esc_html__( 'Icon', 'magical-products-display' ),
				),
			)
		);

		$this->add_control(
			'separator_text',
			array(
				'label'     => esc_html__( 'Separator', 'magical-products-display' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '/',
				'condition' => array(
					'separator_type' => 'text',
				),
			)
		);

		$this->add_control(
			'separator_icon',
			array(
				'label'     => esc_html__( 'Separator Icon', 'magical-products-display' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'separator_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'show_current',
			array(
				'label'        => esc_html__( 'Show Current Page', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'schema_markup',
			array(
				'label'        => esc_html__( 'Schema Markup', 'magical-products-display' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'magical-products-display' ),
				'label_off'    => esc_html__( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Enable BreadcrumbList schema markup for SEO.', 'magical-products-display' ),
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
		// Container Style Section.
		$this->start_controls_section(
			'section_container_style',
			array(
				'label' => esc_html__( 'Container', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'container_background',
			array(
				'label'     => esc_html__( 'Background Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-breadcrumbs' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .mpd-breadcrumbs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'magical-products-display' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start'   => array(
						'title' => esc_html__( 'Left', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'  => array(
						'title' => esc_html__( 'Right', 'magical-products-display' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .mpd-breadcrumbs ol' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		// Links Style Section.
		$this->start_controls_section(
			'section_links_style',
			array(
				'label' => esc_html__( 'Links', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'links_typography',
				'selector' => '{{WRAPPER}} .mpd-breadcrumbs a, {{WRAPPER}} .mpd-breadcrumbs__item',
			)
		);

		$this->start_controls_tabs( 'tabs_links_style' );

		$this->start_controls_tab(
			'tab_links_normal',
			array(
				'label' => esc_html__( 'Normal', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'links_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-breadcrumbs a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_links_hover',
			array(
				'label' => esc_html__( 'Hover', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'links_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-breadcrumbs a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Current Page Style Section.
		$this->start_controls_section(
			'section_current_style',
			array(
				'label'     => esc_html__( 'Current Page', 'magical-products-display' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_current' => 'yes',
				),
			)
		);

		$this->add_control(
			'current_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-breadcrumbs__current' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'current_typography',
				'selector' => '{{WRAPPER}} .mpd-breadcrumbs__current',
			)
		);

		$this->end_controls_section();

		// Separator Style Section.
		$this->start_controls_section(
			'section_separator_style',
			array(
				'label' => esc_html__( 'Separator', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => esc_html__( 'Color', 'magical-products-display' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mpd-breadcrumbs__separator' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mpd-breadcrumbs__separator svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'separator_size',
			array(
				'label'      => esc_html__( 'Size', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 6,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-breadcrumbs__separator' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mpd-breadcrumbs__separator svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'separator_spacing',
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
					'{{WRAPPER}} .mpd-breadcrumbs__separator' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'separator_rotate',
			array(
				'label'      => esc_html__( 'Rotate', 'magical-products-display' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'deg' ),
				'range'      => array(
					'deg' => array(
						'min' => 0,
						'max' => 360,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mpd-breadcrumbs__separator' => 'transform: rotate({{SIZE}}deg);',
				),
				'condition'  => array(
					'separator_type' => 'icon',
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
		// Build breadcrumb items.
		$breadcrumbs = $this->get_breadcrumb_items( $settings );

		if ( empty( $breadcrumbs ) ) {
			return;
		}

		// Render separator.
		$separator = $this->get_separator_html( $settings );

		// Schema attributes.
		$schema_enabled = 'yes' === $settings['schema_markup'];
		$list_attrs     = $schema_enabled ? ' itemscope itemtype="https://schema.org/BreadcrumbList"' : '';
		?>
		<nav class="mpd-breadcrumbs woocommerce-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'magical-products-display' ); ?>">
			<ol class="mpd-breadcrumbs__list"<?php echo $list_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php
				$position = 0;
				$total    = count( $breadcrumbs );

				foreach ( $breadcrumbs as $index => $item ) :
					$position++;
					$is_last       = ( $index === $total - 1 );
					$item_attrs    = $schema_enabled ? ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"' : '';
					$link_attrs    = $schema_enabled ? ' itemprop="item"' : '';
					$name_attrs    = $schema_enabled ? ' itemprop="name"' : '';
					$current_class = $is_last && 'yes' === $settings['show_current'] ? ' mpd-breadcrumbs__current' : '';
					?>
					<li class="mpd-breadcrumbs__item<?php echo esc_attr( $current_class ); ?>"<?php echo $item_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php if ( ! empty( $item['link'] ) && ! $is_last ) : ?>
							<a href="<?php echo esc_url( $item['link'] ); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<span<?php echo $name_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $item['title'] ); ?></span>
							</a>
						<?php else : ?>
							<span<?php echo $name_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $item['title'] ); ?></span>
						<?php endif; ?>

						<?php if ( $schema_enabled ) : ?>
							<meta itemprop="position" content="<?php echo esc_attr( $position ); ?>" />
						<?php endif; ?>

						<?php if ( ! $is_last ) : ?>
							<span class="mpd-breadcrumbs__separator" aria-hidden="true"><?php echo $separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ol>
		</nav>
		<?php
	}

	/**
	 * Get breadcrumb items.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return array Breadcrumb items.
	 */
	protected function get_breadcrumb_items( $settings ) {
		$breadcrumbs = array();

		// Home.
		if ( 'yes' === $settings['show_home'] ) {
			$breadcrumbs[] = array(
				'title' => $settings['home_text'],
				'link'  => home_url( '/' ),
			);
		}

		// Shop page.
		if ( class_exists( 'WooCommerce' ) && is_woocommerce() && ! is_shop() ) {
			$shop_page_id = wc_get_page_id( 'shop' );
			if ( $shop_page_id > 0 ) {
				$breadcrumbs[] = array(
					'title' => get_the_title( $shop_page_id ),
					'link'  => get_permalink( $shop_page_id ),
				);
			}
		}

		// Product category.
		if ( class_exists( 'WooCommerce' ) && is_product_category() ) {
			$current_term = get_queried_object();

			// Get parent categories.
			$ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
			$ancestors = array_reverse( $ancestors );

			foreach ( $ancestors as $ancestor_id ) {
				$ancestor = get_term( $ancestor_id, 'product_cat' );
				if ( $ancestor && ! is_wp_error( $ancestor ) ) {
					$breadcrumbs[] = array(
						'title' => $ancestor->name,
						'link'  => get_term_link( $ancestor ),
					);
				}
			}

			if ( 'yes' === $settings['show_current'] ) {
				$breadcrumbs[] = array(
					'title' => $current_term->name,
					'link'  => '',
				);
			}
		}

		// Product tag.
		if ( class_exists( 'WooCommerce' ) && is_product_tag() ) {
			$current_term = get_queried_object();

			if ( 'yes' === $settings['show_current'] ) {
				$breadcrumbs[] = array(
					'title' => $current_term->name,
					'link'  => '',
				);
			}
		}

		// Single product.
		if ( class_exists( 'WooCommerce' ) && is_singular( 'product' ) ) {
			global $post;

			// Get primary category.
			$terms = wc_get_product_terms(
				$post->ID,
				'product_cat',
				array(
					'orderby' => 'parent',
					'order'   => 'DESC',
				)
			);

			if ( ! empty( $terms ) ) {
				$main_term = $terms[0];

				// Get parent categories.
				$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
				$ancestors = array_reverse( $ancestors );

				foreach ( $ancestors as $ancestor_id ) {
					$ancestor = get_term( $ancestor_id, 'product_cat' );
					if ( $ancestor && ! is_wp_error( $ancestor ) ) {
						$breadcrumbs[] = array(
							'title' => $ancestor->name,
							'link'  => get_term_link( $ancestor ),
						);
					}
				}

				$breadcrumbs[] = array(
					'title' => $main_term->name,
					'link'  => get_term_link( $main_term ),
				);
			}

			if ( 'yes' === $settings['show_current'] ) {
				$breadcrumbs[] = array(
					'title' => get_the_title(),
					'link'  => '',
				);
			}
		}

		// Shop page.
		if ( class_exists( 'WooCommerce' ) && is_shop() ) {
			if ( 'yes' === $settings['show_current'] ) {
				$shop_page_id = wc_get_page_id( 'shop' );
				$breadcrumbs[] = array(
					'title' => $shop_page_id > 0 ? get_the_title( $shop_page_id ) : esc_html__( 'Shop', 'magical-products-display' ),
					'link'  => '',
				);
			}
		}

		// Cart.
		if ( is_cart() ) {
			if ( 'yes' === $settings['show_current'] ) {
				$breadcrumbs[] = array(
					'title' => esc_html__( 'Cart', 'magical-products-display' ),
					'link'  => '',
				);
			}
		}

		// Checkout.
		if ( is_checkout() ) {
			if ( 'yes' === $settings['show_current'] ) {
				$breadcrumbs[] = array(
					'title' => esc_html__( 'Checkout', 'magical-products-display' ),
					'link'  => '',
				);
			}
		}

		// My Account.
		if ( is_account_page() ) {
			if ( 'yes' === $settings['show_current'] ) {
				$breadcrumbs[] = array(
					'title' => esc_html__( 'My Account', 'magical-products-display' ),
					'link'  => '',
				);
			}
		}

		// Regular Pages.
		if ( is_page() && ! is_shop() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
			global $post;

			// Get parent pages.
			if ( $post->post_parent ) {
				$ancestors = get_post_ancestors( $post->ID );
				$ancestors = array_reverse( $ancestors );

				foreach ( $ancestors as $ancestor_id ) {
					$breadcrumbs[] = array(
						'title' => get_the_title( $ancestor_id ),
						'link'  => get_permalink( $ancestor_id ),
					);
				}
			}

			// Current page.
			if ( 'yes' === $settings['show_current'] ) {
				$breadcrumbs[] = array(
					'title' => get_the_title(),
					'link'  => '',
				);
			}
		}

		// Single Post.
		if ( is_single() && ! is_singular( 'product' ) ) {
			// Get post categories.
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				$main_category = $categories[0];

				// Get parent categories.
				if ( $main_category->parent ) {
					$ancestors = get_ancestors( $main_category->term_id, 'category' );
					$ancestors = array_reverse( $ancestors );

					foreach ( $ancestors as $ancestor_id ) {
						$ancestor = get_term( $ancestor_id, 'category' );
						if ( $ancestor && ! is_wp_error( $ancestor ) ) {
							$breadcrumbs[] = array(
								'title' => $ancestor->name,
								'link'  => get_term_link( $ancestor ),
							);
						}
					}
				}

				$breadcrumbs[] = array(
					'title' => $main_category->name,
					'link'  => get_term_link( $main_category ),
				);
			}

			// Current post.
			if ( 'yes' === $settings['show_current'] ) {
				$breadcrumbs[] = array(
					'title' => get_the_title(),
					'link'  => '',
				);
			}
		}

		// Category Archive.
		if ( is_category() ) {
			$current_term = get_queried_object();

			// Get parent categories.
			if ( $current_term->parent ) {
				$ancestors = get_ancestors( $current_term->term_id, 'category' );
				$ancestors = array_reverse( $ancestors );

				foreach ( $ancestors as $ancestor_id ) {
					$ancestor = get_term( $ancestor_id, 'category' );
					if ( $ancestor && ! is_wp_error( $ancestor ) ) {
						$breadcrumbs[] = array(
							'title' => $ancestor->name,
							'link'  => get_term_link( $ancestor ),
						);
					}
				}
			}

			// Current category.
			if ( 'yes' === $settings['show_current'] ) {
				$breadcrumbs[] = array(
					'title' => $current_term->name,
					'link'  => '',
				);
			}
		}

		// Tag Archive.
		if ( is_tag() ) {
			$current_term = get_queried_object();

			if ( 'yes' === $settings['show_current'] ) {
				$breadcrumbs[] = array(
					'title' => $current_term->name,
					'link'  => '',
				);
			}
		}

		// Author Archive.
		if ( is_author() ) {
			$author = get_queried_object();

			if ( 'yes' === $settings['show_current'] ) {
				$breadcrumbs[] = array(
					'title' => get_the_author_meta( 'display_name', $author->ID ),
					'link'  => '',
				);
			}
		}

		// Date Archive.
		if ( is_date() ) {
			if ( is_year() ) {
				$breadcrumbs[] = array(
					'title' => get_the_date( 'Y' ),
					'link'  => '',
				);
			} elseif ( is_month() ) {
				$breadcrumbs[] = array(
					'title' => get_the_date( 'Y' ),
					'link'  => get_year_link( get_the_date( 'Y' ) ),
				);
				$breadcrumbs[] = array(
					'title' => get_the_date( 'F' ),
					'link'  => '',
				);
			} elseif ( is_day() ) {
				$breadcrumbs[] = array(
					'title' => get_the_date( 'Y' ),
					'link'  => get_year_link( get_the_date( 'Y' ) ),
				);
				$breadcrumbs[] = array(
					'title' => get_the_date( 'F' ),
					'link'  => get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) ),
				);
				$breadcrumbs[] = array(
					'title' => get_the_date( 'd' ),
					'link'  => '',
				);
			}
		}

		// Search Results.
		if ( is_search() ) {
			$breadcrumbs[] = array(
				'title' => sprintf( esc_html__( 'Search Results for: %s', 'magical-products-display' ), get_search_query() ),
				'link'  => '',
			);
		}

		// 404.
		if ( is_404() ) {
			$breadcrumbs[] = array(
				'title' => esc_html__( '404 - Page Not Found', 'magical-products-display' ),
				'link'  => '',
			);
		}

		return $breadcrumbs;
	}

	/**
	 * Get separator HTML.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return string Separator HTML.
	 */
	protected function get_separator_html( $settings ) {
		if ( 'icon' === $settings['separator_type'] && ! empty( $settings['separator_icon']['value'] ) ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon( $settings['separator_icon'], array( 'aria-hidden' => 'true' ) );
			return ob_get_clean();
		}

		return esc_html( $settings['separator_text'] );
	}
}
