<?php
/**
 * Product Query Trait
 *
 * Provides common product query methods for widgets.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Traits;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait Product_Query
 *
 * Use this trait in widgets to handle WooCommerce product queries.
 *
 * @since 2.0.0
 */
trait Product_Query {

	/**
	 * Get product query arguments from widget settings.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return array WP_Query arguments.
	 */
	protected function get_product_query_args( $settings ) {
		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => isset( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 8,
			'orderby'        => isset( $settings['orderby'] ) ? sanitize_text_field( $settings['orderby'] ) : 'date',
			'order'          => isset( $settings['order'] ) ? sanitize_text_field( $settings['order'] ) : 'DESC',
		);

		// Handle tax query for categories.
		if ( ! empty( $settings['product_categories'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => array_map( 'sanitize_text_field', $settings['product_categories'] ),
			);
		}

		// Handle tax query for tags.
		if ( ! empty( $settings['product_tags'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_tag',
				'field'    => 'slug',
				'terms'    => array_map( 'sanitize_text_field', $settings['product_tags'] ),
			);
		}

		// Set tax_query relation if multiple taxonomies.
		if ( isset( $args['tax_query'] ) && count( $args['tax_query'] ) > 1 ) {
			$args['tax_query']['relation'] = 'AND';
		}

		// Handle product visibility.
		$product_visibility_term_ids = wc_get_product_visibility_term_ids();
		
		if ( ! empty( $product_visibility_term_ids['exclude-from-catalog'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => array( $product_visibility_term_ids['exclude-from-catalog'] ),
				'operator' => 'NOT IN',
			);
		}

		// Handle out of stock products.
		if ( isset( $settings['hide_out_of_stock'] ) && 'yes' === $settings['hide_out_of_stock'] ) {
			$args['meta_query'][] = array(
				'key'     => '_stock_status',
				'value'   => 'outofstock',
				'compare' => '!=',
			);
		}

		// Handle featured products.
		if ( isset( $settings['show_only_featured'] ) && 'yes' === $settings['show_only_featured'] ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
			);
		}

		// Handle on sale products.
		if ( isset( $settings['show_only_on_sale'] ) && 'yes' === $settings['show_only_on_sale'] ) {
			$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
		}

		// Handle specific products inclusion.
		if ( ! empty( $settings['include_products'] ) ) {
			$args['post__in'] = array_map( 'absint', $settings['include_products'] );
		}

		// Handle specific products exclusion.
		if ( ! empty( $settings['exclude_products'] ) ) {
			$args['post__not_in'] = array_map( 'absint', $settings['exclude_products'] );
		}

		// Handle offset.
		if ( ! empty( $settings['offset'] ) ) {
			$args['offset'] = absint( $settings['offset'] );
		}

		// Handle custom orderby options.
		$args = $this->apply_orderby( $args, $settings );

		return apply_filters( 'mpd_product_query_args', $args, $settings );
	}

	/**
	 * Apply custom orderby options.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args     Query arguments.
	 * @param array $settings Widget settings.
	 * @return array Modified query arguments.
	 */
	protected function apply_orderby( $args, $settings ) {
		$orderby = isset( $settings['orderby'] ) ? $settings['orderby'] : 'date';

		switch ( $orderby ) {
			case 'price':
				$args['meta_key'] = '_price';
				$args['orderby']  = 'meta_value_num';
				break;

			case 'price-desc':
				$args['meta_key'] = '_price';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;

			case 'rating':
				$args['meta_key'] = '_wc_average_rating';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;

			case 'popularity':
				$args['meta_key'] = 'total_sales';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;

			case 'menu_order':
				$args['orderby'] = 'menu_order title';
				break;

			case 'rand':
				$args['orderby'] = 'rand';
				break;

			case 'title':
				$args['orderby'] = 'title';
				$args['order']   = 'ASC';
				break;
		}

		return $args;
	}

	/**
	 * Get products using WC_Product_Query.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return array Array of WC_Product objects.
	 */
	protected function get_products( $settings ) {
		$args = $this->get_product_query_args( $settings );
		
		$query = new \WP_Query( $args );
		
		return $query->posts;
	}

	/**
	 * Get product categories for select control.
	 *
	 * @since 2.0.0
	 *
	 * @return array Category options.
	 */
	protected function get_product_categories() {
		$options = array();
		$terms   = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
			)
		);

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->slug ] = $term->name;
			}
		}

		return $options;
	}

	/**
	 * Get product tags for select control.
	 *
	 * @since 2.0.0
	 *
	 * @return array Tag options.
	 */
	protected function get_product_tags() {
		$options = array();
		$terms   = get_terms(
			array(
				'taxonomy'   => 'product_tag',
				'hide_empty' => true,
			)
		);

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->slug ] = $term->name;
			}
		}

		return $options;
	}

	/**
	 * Get products for select control.
	 *
	 * @since 2.0.0
	 *
	 * @param int $limit Number of products to return.
	 * @return array Product options.
	 */
	protected function get_products_options( $limit = -1 ) {
		$options  = array();
		$products = wc_get_products(
			array(
				'limit'  => $limit,
				'status' => 'publish',
			)
		);

		foreach ( $products as $product ) {
			$options[ $product->get_id() ] = $product->get_name();
		}

		return $options;
	}

	/**
	 * Get orderby options for select control.
	 *
	 * @since 2.0.0
	 *
	 * @return array Orderby options.
	 */
	protected function get_orderby_options() {
		return array(
			'date'       => __( 'Date', 'magical-products-display' ),
			'title'      => __( 'Title', 'magical-products-display' ),
			'price'      => __( 'Price: Low to High', 'magical-products-display' ),
			'price-desc' => __( 'Price: High to Low', 'magical-products-display' ),
			'rating'     => __( 'Rating', 'magical-products-display' ),
			'popularity' => __( 'Popularity (Sales)', 'magical-products-display' ),
			'menu_order' => __( 'Menu Order', 'magical-products-display' ),
			'rand'       => __( 'Random', 'magical-products-display' ),
		);
	}

	/**
	 * Register common query controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_query_controls() {
		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Products Per Page', 'magical-products-display' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 1,
				'max'     => 100,
			)
		);

		$this->add_control(
			'product_categories',
			array(
				'label'       => __( 'Categories', 'magical-products-display' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'options'     => $this->get_product_categories(),
				'multiple'    => true,
				'label_block' => true,
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'magical-products-display' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => $this->get_orderby_options(),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'     => __( 'Order', 'magical-products-display' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => array(
					'ASC'  => __( 'Ascending', 'magical-products-display' ),
					'DESC' => __( 'Descending', 'magical-products-display' ),
				),
				'condition' => array(
					'orderby!' => array( 'price', 'price-desc', 'rating', 'popularity', 'rand' ),
				),
			)
		);

		$this->add_control(
			'hide_out_of_stock',
			array(
				'label'        => __( 'Hide Out of Stock', 'magical-products-display' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_only_featured',
			array(
				'label'        => __( 'Featured Products Only', 'magical-products-display' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'show_only_on_sale',
			array(
				'label'        => __( 'On Sale Products Only', 'magical-products-display' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'magical-products-display' ),
				'label_off'    => __( 'No', 'magical-products-display' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);
	}
}
