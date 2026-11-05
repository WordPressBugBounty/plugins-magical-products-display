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

		// Defer rewrite flush to next init — calling flush_rewrite_rules()
		// directly during deactivation can fail on some hosts.
		update_option( 'mpd_flush_rewrite_rules', true );
	}

	/**
	 * Clear scheduled events.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private static function clear_scheduled_events() {
		$events = array(
			'mpd_daily_license_check',
			'mpd_weekly_cleanup',
			'mpd_cleanup_rate_limits',
		);

		foreach ( $events as $event ) {
			$timestamp = wp_next_scheduled( $event );
			if ( $timestamp ) {
				wp_unschedule_event( $timestamp, $event );
			}
			wp_clear_scheduled_hook( $event );
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

		// Delete all plugin transients (single-site + multisite variants).
		$patterns = array(
			'_transient_mpd_',
			'_transient_timeout_mpd_',
			'_site_transient_mpd_',
			'_site_transient_timeout_mpd_',
		);
		foreach ( $patterns as $pattern ) {
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
					$wpdb->esc_like( $pattern ) . '%'
				)
			);
		}

		// Clear plugin-specific object cache groups instead of flushing everything.
		wp_cache_delete( 'mpd_template_conditions', 'mpd' );
		wp_cache_delete( 'mpd_settings', 'mpd' );
	}
}
