<?php
/**
 * Template Functions
 *
 * Functions for the template builder system.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get template for a specific location.
 *
 * @since 2.0.0
 *
 * @param string $location The template location (single-product, archive, cart, etc.).
 * @param array  $context  Additional context for condition matching.
 * @return int|false Template ID or false if not found.
 */
if ( ! function_exists( 'mpd_get_template_for_location' ) ) {
function mpd_get_template_for_location( $location, $context = array() ) {
	// Check if templates are enabled.
	if ( 'yes' !== mpd_get_setting( 'enable_templates', 'yes' ) ) {
		return false;
	}

	// Query templates for this location.
	$templates = get_posts(
		array(
			'post_type'      => 'mpd_template',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'   => '_mpd_template_type',
					'value' => sanitize_text_field( $location ),
				),
			),
			'orderby'        => 'meta_value_num',
			'meta_key'       => '_mpd_template_priority',
			'order'          => 'ASC',
		)
	);

	if ( empty( $templates ) ) {
		return false;
	}

	// Find the first template whose conditions match.
	foreach ( $templates as $template ) {
		if ( mpd_template_conditions_match( $template->ID, $context ) ) {
			return $template->ID;
		}
	}

	return false;
}
}

/**
 * Check if template conditions match.
 *
 * @since 2.0.0
 *
 * @param int   $template_id The template ID.
 * @param array $context     Additional context.
 * @return bool Whether conditions match.
 */
if ( ! function_exists( 'mpd_template_conditions_match' ) ) {
function mpd_template_conditions_match( $template_id, $context = array() ) {
	$conditions = get_post_meta( $template_id, '_mpd_template_conditions', true );

	// No conditions means apply to all.
	if ( empty( $conditions ) || ! is_array( $conditions ) ) {
		return true;
	}

	$include_match = false;
	$exclude_match = false;
	$has_includes  = false;
	$has_excludes  = false;

	foreach ( $conditions as $condition ) {
		$type           = isset( $condition['type'] ) ? $condition['type'] : 'include';
		$condition_name = isset( $condition['condition'] ) ? $condition['condition'] : '';
		$value          = isset( $condition['value'] ) ? $condition['value'] : '';

		if ( 'include' === $type ) {
			$has_includes = true;
			if ( mpd_check_condition( $condition_name, $value, $context ) ) {
				$include_match = true;
			}
		} else {
			$has_excludes = true;
			if ( mpd_check_condition( $condition_name, $value, $context ) ) {
				$exclude_match = true;
			}
		}
	}

	// If excluded, return false.
	if ( $exclude_match ) {
		return false;
	}

	// If has includes and none match, return false.
	if ( $has_includes && ! $include_match ) {
		return false;
	}

	return true;
}
}

/**
 * Check a single condition.
 *
 * @since 2.0.0
 *
 * @param string $condition The condition name.
 * @param mixed  $value     The condition value.
 * @param array  $context   Additional context.
 * @return bool Whether the condition matches.
 */
if ( ! function_exists( 'mpd_check_condition' ) ) {
function mpd_check_condition( $condition, $value, $context = array() ) {
	switch ( $condition ) {
		case 'all':
			return true;

		case 'product_type':
			if ( ! isset( $context['product'] ) ) {
				global $product;
				$context['product'] = $product;
			}
			if ( ! $context['product'] ) {
				return false;
			}
			$types = is_array( $value ) ? $value : array( $value );
			return in_array( $context['product']->get_type(), $types, true );

		case 'product_category':
			if ( ! isset( $context['product_id'] ) ) {
				$context['product_id'] = get_the_ID();
			}
			$categories = is_array( $value ) ? $value : array( $value );
			return has_term( $categories, 'product_cat', $context['product_id'] );

		case 'product_tag':
			if ( ! isset( $context['product_id'] ) ) {
				$context['product_id'] = get_the_ID();
			}
			$tags = is_array( $value ) ? $value : array( $value );
			return has_term( $tags, 'product_tag', $context['product_id'] );

		case 'specific_product':
			if ( ! isset( $context['product_id'] ) ) {
				$context['product_id'] = get_the_ID();
			}
			$products = is_array( $value ) ? $value : array( $value );
			return in_array( (int) $context['product_id'], array_map( 'intval', $products ), true );

		case 'product_in_stock':
			if ( ! isset( $context['product'] ) ) {
				global $product;
				$context['product'] = $product;
			}
			if ( ! $context['product'] ) {
				return false;
			}
			return $context['product']->is_in_stock() === ( 'yes' === $value );

		case 'product_on_sale':
			if ( ! isset( $context['product'] ) ) {
				global $product;
				$context['product'] = $product;
			}
			if ( ! $context['product'] ) {
				return false;
			}
			return $context['product']->is_on_sale() === ( 'yes' === $value );

		case 'user_logged_in':
			return is_user_logged_in() === ( 'yes' === $value );

		case 'user_role':
			if ( ! is_user_logged_in() ) {
				return false;
			}
			$user  = wp_get_current_user();
			$roles = is_array( $value ) ? $value : array( $value );
			return ! empty( array_intersect( $user->roles, $roles ) );

		default:
			return apply_filters( 'mpd_check_condition_' . $condition, false, $value, $context );
	}
}
}

/**
 * Render a template.
 *
 * @since 2.0.0
 *
 * @param int $template_id The template ID.
 * @return string The rendered template HTML.
 */
if ( ! function_exists( 'mpd_render_template' ) ) {
function mpd_render_template( $template_id ) {
	if ( ! class_exists( '\Elementor\Plugin' ) ) {
		return '';
	}

	$template_id = absint( $template_id );

	if ( ! $template_id ) {
		return '';
	}

	// Use Elementor's frontend to render.
	return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id, true );
}
}

/**
 * Get available template types.
 *
 * @since 2.0.0
 *
 * @return array Template types.
 */
if ( ! function_exists( 'mpd_get_template_types' ) ) {
function mpd_get_template_types() {
	return apply_filters(
		'mpd_template_types',
		array(
			'single-product'  => __( 'Single Product', 'magical-products-display' ),
			'archive-product' => __( 'Shop / Archive', 'magical-products-display' ),
			'cart'            => __( 'Cart Page', 'magical-products-display' ),
			'empty-cart'      => __( 'Empty Cart', 'magical-products-display' ),
			'checkout'        => __( 'Checkout Page', 'magical-products-display' ),
			'my-account'      => __( 'My Account', 'magical-products-display' ),
			'thankyou'        => __( 'Thank You / Order Received', 'magical-products-display' ),
		)
	);
}
}

/**
 * Get available condition types.
 *
 * @since 2.0.0
 *
 * @return array Condition types.
 */
if ( ! function_exists( 'mpd_get_condition_types' ) ) {
function mpd_get_condition_types() {
	return apply_filters(
		'mpd_condition_types',
		array(
			'all'              => __( 'All', 'magical-products-display' ),
			'product_type'     => __( 'Product Type', 'magical-products-display' ),
			'product_category' => __( 'Product Category', 'magical-products-display' ),
			'product_tag'      => __( 'Product Tag', 'magical-products-display' ),
			'specific_product' => __( 'Specific Product', 'magical-products-display' ),
			'product_in_stock' => __( 'In Stock', 'magical-products-display' ),
			'product_on_sale'  => __( 'On Sale', 'magical-products-display' ),
			'user_logged_in'   => __( 'User Logged In', 'magical-products-display' ),
			'user_role'        => __( 'User Role', 'magical-products-display' ),
		)
	);
}
}

/**
 * Get product types for conditions.
 *
 * @since 2.0.0
 *
 * @return array Product types.
 */
if ( ! function_exists( 'mpd_get_product_types' ) ) {
function mpd_get_product_types() {
	return wc_get_product_types();
}
}

// Note: Template post type is registered in Template_Manager class.
// Do not add duplicate registration here.
