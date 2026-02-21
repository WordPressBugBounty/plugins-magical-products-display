<?php
/**
 * Pro features management.
 *
 * Wrapper around existing pro license system for backward compatibility.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Pro
 *
 * Centralized pro feature management.
 * Uses existing option names for backward compatibility.
 *
 * @since 2.0.0
 */
class Pro {

	/**
	 * Cached pro status.
	 *
	 * @var bool|null
	 */
	private static $is_pro = null;

	/**
	 * Check if Pro is active.
	 *
	 * Uses EXISTING option names for backward compatibility.
	 * DO NOT CHANGE these option names.
	 *
	 * @since 2.0.0
	 *
	 * @return bool Whether pro is active.
	 */
	public static function is_active() {
		if ( null === self::$is_pro ) {
			// Use existing option name - DO NOT CHANGE.
			self::$is_pro = ( 'yes' === get_option( 'mgppro_is_active', 'no' ) );
		}
		return self::$is_pro;
	}

	/**
	 * Check if pro has valid license.
	 *
	 * @since 2.0.0
	 *
	 * @return bool Whether license is valid.
	 */
	public static function has_valid_license() {
		// Use existing option name - DO NOT CHANGE.
		return 'yes' === get_option( 'mgppro_has_valid_lic', 'no' );
	}

	/**
	 * Refresh the pro status from database.
	 *
	 * Call this after license activation/deactivation.
	 *
	 * @since 2.0.0
	 *
	 * @return bool The refreshed pro status.
	 */
	public static function refresh_status() {
		self::$is_pro = null;
		return self::is_active();
	}

	/**
	 * Get Pro badge HTML for widget controls.
	 *
	 * @since 2.0.0
	 *
	 * @param string $position Position of the badge (before, after).
	 * @return string The badge HTML or empty string if pro is active.
	 */
	public static function get_pro_badge( $position = 'after' ) {
		if ( self::is_active() ) {
			return '';
		}

		$badge = sprintf(
			'<strong style="color:#ff5722;font-size:80%%">(%s)</strong>',
			esc_html__( 'Pro', 'magical-products-display' )
		);

		if ( 'before' === $position ) {
			return $badge . ' ';
		}

		return ' ' . $badge;
	}

	/**
	 * Get label with pro badge.
	 *
	 * @since 2.0.0
	 *
	 * @param string $label The label text.
	 * @return string The label with pro badge if not pro.
	 */
	public static function get_label_with_badge( $label ) {
		if ( self::is_active() ) {
			return $label;
		}

		return $label . self::get_pro_badge();
	}

	/**
	 * Get fake option value for pro-locked dropdowns.
	 *
	 * This is the existing pattern used in the plugin.
	 *
	 * @since 2.0.0
	 *
	 * @param string $pro_value  The value to use when pro is active.
	 * @param string $fake_value The fake value to use when pro is not active.
	 * @return string The appropriate value based on pro status.
	 */
	public static function get_locked_value( $pro_value, $fake_value ) {
		return self::is_active() ? $pro_value : $fake_value;
	}

	/**
	 * Get default value for pro-locked switches.
	 *
	 * @since 2.0.0
	 *
	 * @param string $default_when_pro Default value when pro is active.
	 * @return string Empty string when not pro, default when pro.
	 */
	public static function get_switch_default( $default_when_pro = 'yes' ) {
		return self::is_active() ? $default_when_pro : '';
	}

	/**
	 * Get pro notice HTML for widget sections.
	 *
	 * @since 2.0.0
	 *
	 * @param string $feature_name The name of the pro feature.
	 * @return array Elementor control array for RAW_HTML notice.
	 */
	public static function get_pro_notice( $feature_name = '' ) {
		if ( self::is_active() ) {
			return array();
		}

		$message = empty( $feature_name )
			? __( 'This feature requires the Pro version.', 'magical-products-display' )
			: sprintf(
				/* translators: %s: feature name */
				__( '%s requires the Pro version.', 'magical-products-display' ),
				$feature_name
			);

		return array(
			'type'            => \Elementor\Controls_Manager::RAW_HTML,
			'raw'             => sprintf(
				'<div style="padding: 10px; background: #fff3cd; border-left: 4px solid #ff5722; margin: 10px 0;">
					<strong style="color: #ff5722;">%s</strong><br>
					<span style="color: #666;">%s</span>
					<a href="%s" target="_blank" style="display: inline-block; margin-top: 5px; color: #ff5722;">%s →</a>
				</div>',
				esc_html__( 'Pro Feature', 'magical-products-display' ),
				esc_html( $message ),
				esc_url( 'https://wpthemespace.com/product/magical-shop-builder/#pricing' ),
				esc_html__( 'Upgrade to Pro', 'magical-products-display' )
			),
			'content_classes' => 'mpd-pro-notice',
		);
	}

	/**
	 * Check if a specific pro feature is available.
	 *
	 * @since 2.0.0
	 *
	 * @param string $feature The feature slug.
	 * @return bool Whether the feature is available.
	 */
	public static function has_feature( $feature ) {
		// All pro features are available when pro is active.
		if ( self::is_active() ) {
			return true;
		}

		// List of features available in free version.
		$free_features = array(
			'products_grid',
			'products_list',
			'products_slider',
			'products_carousel',
			'products_tab',
			'products_cat',
			'ajax_search',
			'testimonial_carousel',
			'accordion',
			'pricing_table',
		);

		return in_array( $feature, $free_features, true );
	}

	/**
	 * Get upgrade URL.
	 *
	 * @since 2.0.0
	 *
	 * @param string $utm_source UTM source for tracking.
	 * @param string $utm_medium UTM medium for tracking.
	 * @return string The upgrade URL with UTM parameters.
	 */
	public static function get_upgrade_url( $utm_source = 'plugin', $utm_medium = 'widget' ) {
		return add_query_arg(
			array(
				'utm_source'   => $utm_source,
				'utm_medium'   => $utm_medium,
				'utm_campaign' => 'msb_upgrade',
			),
			'https://mp.wpcolors.net/magical-products-display-pro-pricing/'
		);
	}
}

// Global helper function for backward compatibility.
if ( ! function_exists( 'mpd_is_pro' ) ) {
	/**
	 * Check if pro version is active.
	 *
	 * @since 2.0.0
	 *
	 * @return bool Whether pro is active.
	 */
	function mpd_is_pro() {
		return Pro::is_active();
	}
}
