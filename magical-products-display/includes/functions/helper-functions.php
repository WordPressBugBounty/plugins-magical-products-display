<?php
/**
 * Helper Functions
 *
 * General utility functions for Magical Shop Builder.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if pro version is active.
 *
 * @since 2.0.0
 *
 * @return bool Whether pro is active.
 */
if ( ! function_exists( 'mpd_is_pro_active' ) ) {
function mpd_is_pro_active() {
	return 'yes' === get_option( 'mgppro_is_active', 'no' );
}
}

/**
 * Get pro badge HTML.
 *
 * @since 2.0.0
 *
 * @return string The pro badge HTML or empty string.
 */
if ( ! function_exists( 'mpd_get_pro_badge' ) ) {
function mpd_get_pro_badge() {
	if ( mpd_is_pro_active() ) {
		return '';
	}

	return sprintf(
		' <strong style="color:#ff5722;font-size:80%%">(%s)</strong>',
		esc_html__( 'Pro', 'magical-products-display' )
	);
}
}

/**
 * Get the plugin version.
 *
 * @since 2.0.0
 *
 * @return string The plugin version.
 */
if ( ! function_exists( 'mpd_get_version' ) ) {
function mpd_get_version() {
	return defined( 'MAGICAL_PRODUCTS_DISPLAY_VERSION' ) ? MAGICAL_PRODUCTS_DISPLAY_VERSION : '2.0.0';
}
}

/**
 * Check if Elementor is active.
 *
 * @since 2.0.0
 *
 * @return bool Whether Elementor is active.
 */
if ( ! function_exists( 'mpd_is_elementor_active' ) ) {
function mpd_is_elementor_active() {
	return did_action( 'elementor/loaded' );
}
}

/**
 * Check if Elementor Pro is active.
 *
 * @since 2.0.0
 *
 * @return bool Whether Elementor Pro is active.
 */
if ( ! function_exists( 'mpd_is_elementor_pro_active' ) ) {
function mpd_is_elementor_pro_active() {
	return class_exists( '\ElementorPro\Plugin' );
}
}

/**
 * Check if Elementor Theme Builder is available.
 *
 * @since 2.0.0
 *
 * @return bool Whether Theme Builder is available.
 */
if ( ! function_exists( 'mpd_has_elementor_theme_builder' ) ) {
function mpd_has_elementor_theme_builder() {
	return class_exists( '\ElementorPro\Modules\ThemeBuilder\Module' );
}
}

/**
 * Check if WooCommerce is active.
 *
 * @since 2.0.0
 *
 * @return bool Whether WooCommerce is active.
 */
if ( ! function_exists( 'mpd_is_woocommerce_active' ) ) {
function mpd_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}
}

/**
 * Check if WooCommerce Blocks checkout is being used.
 *
 * @since 2.0.0
 *
 * @return bool Whether Blocks checkout is used.
 */
if ( ! function_exists( 'mpd_is_block_checkout' ) ) {
function mpd_is_block_checkout() {
	if ( ! class_exists( 'Automattic\WooCommerce\Blocks\Package' ) ) {
		return false;
	}

	$checkout_page_id = wc_get_page_id( 'checkout' );
	if ( ! $checkout_page_id ) {
		return false;
	}

	$checkout_page = get_post( $checkout_page_id );
	if ( ! $checkout_page ) {
		return false;
	}

	return has_block( 'woocommerce/checkout', $checkout_page );
}
}

/**
 * Check if WooCommerce Blocks cart is being used.
 *
 * @since 2.0.0
 *
 * @return bool Whether Blocks cart is used.
 */
if ( ! function_exists( 'mpd_is_block_cart' ) ) {
function mpd_is_block_cart() {
	if ( ! class_exists( 'Automattic\WooCommerce\Blocks\Package' ) ) {
		return false;
	}

	$cart_page_id = wc_get_page_id( 'cart' );
	if ( ! $cart_page_id ) {
		return false;
	}

	$cart_page = get_post( $cart_page_id );
	if ( ! $cart_page ) {
		return false;
	}

	return has_block( 'woocommerce/cart', $cart_page );
}
}

/**
 * Get allowed HTML tags for sanitization.
 *
 * @since 2.0.0
 *
 * @return array Allowed HTML tags.
 */
if ( ! function_exists( 'mpd_get_html_allowed_tags' ) ) {
function mpd_get_html_allowed_tags() {
	$allowed_html = array(
		'a'      => array(
			'href'   => array(),
			'title'  => array(),
			'class'  => array(),
			'id'     => array(),
			'target' => array(),
			'rel'    => array(),
		),
		'b'      => array(),
		'br'     => array(),
		'div'    => array(
			'class' => array(),
			'id'    => array(),
			'style' => array(),
		),
		'em'     => array(),
		'i'      => array(
			'class' => array(),
		),
		'img'    => array(
			'src'    => array(),
			'alt'    => array(),
			'class'  => array(),
			'width'  => array(),
			'height' => array(),
		),
		'p'      => array(
			'class' => array(),
		),
		'span'   => array(
			'class' => array(),
			'style' => array(),
		),
		'strong' => array(),
		'u'      => array(),
	);

	return apply_filters( 'mpd_allowed_html_tags', $allowed_html );
}
}

/**
 * Sanitize content with allowed HTML.
 *
 * @since 2.0.0
 *
 * @param string $content The content to sanitize.
 * @return string Sanitized content.
 */
if ( ! function_exists( 'mpd_kses' ) ) {
function mpd_kses( $content ) {
	return wp_kses( $content, mpd_get_html_allowed_tags() );
}
}

/**
 * Get template part.
 *
 * @since 2.0.0
 *
 * @param string $slug The template slug.
 * @param string $name The template name (optional).
 * @param array  $args Arguments to pass to template.
 * @return void
 */
if ( ! function_exists( 'mpd_get_template_part' ) ) {
function mpd_get_template_part( $slug, $name = null, $args = array() ) {
	$template = '';

	// Look in theme first.
	if ( $name ) {
		$template = locate_template(
			array(
				"magical-shop-builder/{$slug}-{$name}.php",
				"mpd/{$slug}-{$name}.php",
			)
		);
	}

	if ( ! $template && $name ) {
		$template = MAGICAL_PRODUCTS_DISPLAY_DIR . "templates/{$slug}-{$name}.php";
	}

	if ( ! $template ) {
		$template = locate_template(
			array(
				"magical-shop-builder/{$slug}.php",
				"mpd/{$slug}.php",
			)
		);
	}

	if ( ! $template ) {
		$template = MAGICAL_PRODUCTS_DISPLAY_DIR . "templates/{$slug}.php";
	}

	$template = apply_filters( 'mpd_get_template_part', $template, $slug, $name );

	if ( $template && file_exists( $template ) ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			// Make template args available as $mpd_args inside the template.
			// Individual variables NOT extracted — use $mpd_args['key'] in templates.
			$mpd_args = $args; // phpcs:ignore WordPress.PHP.DontExtract
		}
		include $template;
	}
}
}

/**
 * Get plugin setting.
 *
 * @since 2.0.0
 *
 * @param string $key     Setting key.
 * @param mixed  $default Default value.
 * @return mixed Setting value.
 */
if ( ! function_exists( 'mpd_get_setting' ) ) {
function mpd_get_setting( $key, $default = '' ) {
	$settings = get_option( 'mpd_general_settings', array() );

	return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
}
}

/**
 * Check if a widget is enabled.
 *
 * @since 2.0.0
 *
 * @param string $widget_id The widget ID.
 * @return bool Whether the widget is enabled.
 */
if ( ! function_exists( 'mpd_is_widget_enabled' ) ) {
function mpd_is_widget_enabled( $widget_id ) {
	$widget_settings = get_option( 'mpd_widget_settings', array() );

	if ( ! isset( $widget_settings['enabled_widgets'] ) ) {
		return true; // Enabled by default.
	}

	return ! empty( $widget_settings['enabled_widgets'][ $widget_id ] );
}
}

/**
 * Log debug message.
 *
 * @since 2.0.0
 *
 * @param mixed  $message The message to log.
 * @param string $level   Log level (debug, info, warning, error).
 * @return void
 */
if ( ! function_exists( 'mpd_log' ) ) {
function mpd_log( $message, $level = 'debug' ) {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return;
	}

	if ( is_array( $message ) || is_object( $message ) ) {
		$message = print_r( $message, true ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
	}

	$log_message = sprintf(
		'[%s] [%s] %s',
		gmdate( 'Y-m-d H:i:s' ),
		strtoupper( $level ),
		$message
	);

	error_log( 'Magical Shop Builder: ' . $log_message ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
}
}

/**
 * Get asset URL.
 *
 * @since 2.0.0
 *
 * @param string $path Path relative to assets directory.
 * @return string Full URL to asset.
 */
if ( ! function_exists( 'mpd_asset_url' ) ) {
function mpd_asset_url( $path ) {
	return MAGICAL_PRODUCTS_DISPLAY_ASSETS . ltrim( $path, '/' );
}
}

/**
 * Get asset path.
 *
 * @since 2.0.0
 *
 * @param string $path Path relative to assets directory.
 * @return string Full path to asset.
 */
if ( ! function_exists( 'mpd_asset_path' ) ) {
function mpd_asset_path( $path ) {
	return MAGICAL_PRODUCTS_DISPLAY_DIR . 'assets/' . ltrim( $path, '/' );
}
}

/**
 * Check minimum requirements.
 *
 * @since 2.0.0
 *
 * @return array Array of requirement check results.
 */
if ( ! function_exists( 'mpd_check_requirements' ) ) {
function mpd_check_requirements() {
	$requirements = array(
		'php'         => array(
			'required' => '7.4',
			'current'  => PHP_VERSION,
			'met'      => version_compare( PHP_VERSION, '7.4', '>=' ),
		),
		'wordpress'   => array(
			'required' => '6.0',
			'current'  => get_bloginfo( 'version' ),
			'met'      => version_compare( get_bloginfo( 'version' ), '6.0', '>=' ),
		),
		'woocommerce' => array(
			'required' => '8.0',
			'current'  => defined( 'WC_VERSION' ) ? WC_VERSION : '0',
			'met'      => defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '8.0', '>=' ),
		),
		'elementor'   => array(
			'required' => '3.15',
			'current'  => defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '0',
			'met'      => defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.15', '>=' ),
		),
	);

	return $requirements;
}
}

/**
 * Format price for display.
 *
 * @since 2.0.0
 *
 * @param float $price The price to format.
 * @return string Formatted price.
 */
if ( ! function_exists( 'mpd_format_price' ) ) {
function mpd_format_price( $price ) {
	if ( ! function_exists( 'wc_price' ) ) {
		return number_format( $price, 2 );
	}

	return wc_price( $price );
}
}

/**
 * Get SVG icon.
 *
 * @since 2.0.0
 *
 * @param string $icon Icon name.
 * @param array  $args Icon arguments.
 * @return string SVG HTML.
 */
if ( ! function_exists( 'mpd_get_icon' ) ) {
function mpd_get_icon( $icon, $args = array() ) {
	$defaults = array(
		'width'  => 24,
		'height' => 24,
		'class'  => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$icons = array(
		'cart'    => '<svg xmlns="http://www.w3.org/2000/svg" width="%1$s" height="%2$s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="%3$s"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>',
		'heart'   => '<svg xmlns="http://www.w3.org/2000/svg" width="%1$s" height="%2$s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="%3$s"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
		'search'  => '<svg xmlns="http://www.w3.org/2000/svg" width="%1$s" height="%2$s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="%3$s"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
		'compare' => '<svg xmlns="http://www.w3.org/2000/svg" width="%1$s" height="%2$s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="%3$s"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>',
		'eye'     => '<svg xmlns="http://www.w3.org/2000/svg" width="%1$s" height="%2$s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="%3$s"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',
		'star'    => '<svg xmlns="http://www.w3.org/2000/svg" width="%1$s" height="%2$s" viewBox="0 0 24 24" fill="currentColor" class="%3$s"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
	);

	if ( ! isset( $icons[ $icon ] ) ) {
		return '';
	}

	return sprintf(
		$icons[ $icon ],
		esc_attr( $args['width'] ),
		esc_attr( $args['height'] ),
		esc_attr( $args['class'] )
	);
}
}
