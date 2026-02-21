<?php
/**
 * Fired during plugin deactivation.
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
 * Class Deactivator
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since 2.0.0
 */
class Deactivator {

	/**
	 * Run deactivation tasks.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public static function deactivate() {
		self::clear_scheduled_events();
		self::clear_transients();
		
		// Flush rewrite rules.
		flush_rewrite_rules();
	}

	/**
	 * Clear scheduled events.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private static function clear_scheduled_events() {
		// Clear license check event.
		$timestamp = wp_next_scheduled( 'mpd_daily_license_check' );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, 'mpd_daily_license_check' );
		}

		// Clear weekly cleanup event.
		$timestamp = wp_next_scheduled( 'mpd_weekly_cleanup' );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, 'mpd_weekly_cleanup' );
		}
	}

	/**
	 * Clear plugin transients.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private static function clear_transients() {
		global $wpdb;

		// Delete all plugin transients.
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
				$wpdb->esc_like( '_transient_mpd_' ) . '%',
				$wpdb->esc_like( '_transient_timeout_mpd_' ) . '%'
			)
		);

		// Clear plugin-specific object cache groups instead of flushing everything.
		wp_cache_delete( 'mpd_template_conditions', 'mpd' );
		wp_cache_delete( 'mpd_settings', 'mpd' );
	}
}
