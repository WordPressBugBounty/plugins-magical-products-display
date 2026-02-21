<?php
/**
 * Handles plugin version upgrades for existing users.
 *
 * WordPress's register_activation_hook() only fires on initial activation,
 * NOT on plugin updates. This class ensures that database migrations,
 * capability changes, cron scheduling, and settings migrations all run
 * correctly when an existing user updates from an older version.
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
 * Class Upgrader
 *
 * Checks the stored plugin version on every admin page load
 * and runs incremental upgrade routines when the code version
 * is newer than the stored version.
 *
 * @since 2.0.0
 */
class Upgrader {

	/**
	 * Option name used to store the currently installed version.
	 *
	 * @var string
	 */
	const VERSION_OPTION = 'mpd_version';

	/**
	 * Run the upgrade check.
	 *
	 * Call this early (admin_init or plugins_loaded) so that
	 * database tables, options, and capabilities are ready before
	 * any front-end or admin code relies on them.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public static function init() {
		// Only run in admin or during cron/CLI to avoid front-end overhead.
		if ( ! is_admin() && ! wp_doing_cron() && ! ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			return;
		}

		self::maybe_upgrade();
	}

	/**
	 * Compare stored version with current and run upgrades if needed.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private static function maybe_upgrade() {
		$installed_version = get_option( self::VERSION_OPTION, '0.0.0' );
		$current_version   = MAGICAL_PRODUCTS_DISPLAY_VERSION;

		// If versions match, no upgrade needed.
		if ( version_compare( $installed_version, $current_version, '>=' ) ) {
			return;
		}

		// Run version-specific upgrade routines in order.
		self::run_upgrades( $installed_version );

		// Update stored version so upgrades don't run again.
		update_option( self::VERSION_OPTION, $current_version );

		/**
		 * Fires after all upgrade routines complete.
		 *
		 * @since 2.0.0
		 *
		 * @param string $installed_version Previous version.
		 * @param string $current_version   New version.
		 */
		do_action( 'mpd_upgraded', $installed_version, $current_version );
	}

	/**
	 * Run all applicable upgrade routines in ascending version order.
	 *
	 * Each method handles one version bump. They are designed to be
	 * idempotent — safe to run more than once.
	 *
	 * @since 2.0.0
	 *
	 * @param string $from_version Version the user is upgrading from.
	 * @return void
	 */
	private static function run_upgrades( $from_version ) {
		// ── Upgrade to 2.0.0 ─────────────────────────────────
		if ( version_compare( $from_version, '2.0.0', '<' ) ) {
			self::upgrade_to_200();
		}

		// ── Future upgrades go here in version order ─────────
		// if ( version_compare( $from_version, '2.1.0', '<' ) ) {
		//     self::upgrade_to_210();
		// }
	}

	/*
	|--------------------------------------------------------------------------
	| Version-specific upgrade methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Upgrade to 2.0.0.
	 *
	 * Runs the same critical tasks that the Activator handles for
	 * fresh installs:
	 *  - Migrate old settings schema.
	 *  - Create database tables (idempotent via CREATE TABLE IF NOT EXISTS).
	 *  - Set user capabilities.
	 *  - Register cron events.
	 *  - Initialize default option values.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private static function upgrade_to_200() {
		// Delegate to the Activator — its methods are already idempotent.
		Activator::activate();

		// Schedule a rewrite flush for the next `init` hook since
		// flush_rewrite_rules() must not be called before `init`.
		if ( ! get_option( 'mpd_flush_rewrite_rules' ) ) {
			update_option( 'mpd_flush_rewrite_rules', true );
		}
	}
}
