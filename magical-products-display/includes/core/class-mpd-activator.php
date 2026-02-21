<?php
/**
 * Fired during plugin activation.
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
 * Class Activator
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since 2.0.0
 */
class Activator {

	/**
	 * Run activation tasks.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public static function activate() {
		self::set_activation_options();
		self::migrate_general_settings();
		self::create_tables();
		self::set_capabilities();
		self::register_custom_cron_schedules();
		self::schedule_events();

		// Set flag to flush rewrite rules on next init.
		// flush_rewrite_rules() must NOT be called during plugins_loaded or
		// register_activation_hook in some edge cases; the safest approach
		// is to defer to an `init` hook via this flag.
		update_option( 'mpd_flush_rewrite_rules', true );
	}

	/**
	 * Migrate general settings from old schema to new.
	 *
	 * Ensures enable_custom_archive is set for existing installs
	 * that had outdated key names.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private static function migrate_general_settings() {
		$settings = get_option( 'mpd_general_settings', array() );
		if ( empty( $settings ) ) {
			return;
		}

		$needs_update = false;

		// Remove deprecated enable_shop_builder key.
		if ( isset( $settings['enable_shop_builder'] ) ) {
			unset( $settings['enable_shop_builder'] );
			$needs_update = true;
		}

		// Ensure enable_custom_archive defaults to true if not explicitly set.
		if ( ! isset( $settings['enable_custom_archive'] ) ) {
			$settings['enable_custom_archive'] = true;
			$needs_update = true;
		}

		if ( $needs_update ) {
			update_option( 'mpd_general_settings', $settings );
		}
	}

	/**
	 * Set activation options.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private static function set_activation_options() {
		// Mark plugin as activated.
		if ( 'yes' !== get_option( 'mpd_plugin_activated' ) ) {
			update_option( 'mpd_plugin_activated', 'yes' );
		}

		// Set installation date.
		if ( empty( get_option( 'mpd_install_date' ) ) ) {
			update_option( 'mpd_install_date', gmdate( 'Y-m-d H:i:s' ) );
		}

		// Set plugin version (used by Upgrader to detect version changes).
		update_option( 'mpd_version', MAGICAL_PRODUCTS_DISPLAY_VERSION );

		// Keep legacy option for backward compatibility.
		update_option( 'mpd_plugin_version', MAGICAL_PRODUCTS_DISPLAY_VERSION );

		// Initialize default settings if not exists.
		if ( false === get_option( 'mpd_general_settings' ) ) {
			$default_settings = array(
				'enable_custom_archive'     => true,
				'lazy_load_images'          => true,
				'add_to_cart_ajax'          => true,
			);
			update_option( 'mpd_general_settings', $default_settings );
		}

		// Initialize widget settings if not exists.
		if ( false === get_option( 'mpd_widget_settings' ) ) {
			$widget_settings = array(
				'enabled_widgets' => array(
					// Product display widgets (existing).
					'products-grid'         => true,
					'products-list'         => true,
					'products-slider'       => true,
					'products-carousel'     => true,
					'products-tab'          => true,
					'products-cat'          => true,
					'products-awesome-list' => true,
					'shop-products'         => true,
					'ajax-search'           => true,
					'pricing-table'         => true,
					'accordion'             => true,
					'testimonial-carousel'  => true,
					// Global widgets (Phase 9).
					'header-cart'           => true,
					'breadcrumbs'           => true,
					'store-notice'          => true,
					'recently-viewed'       => true,
					'product-comparison'    => true,
					'wishlist'              => true,
				),
			);
			update_option( 'mpd_widget_settings', $widget_settings );
		}
	}

	/**
	 * Create custom database tables if needed.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private static function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		// Template conditions table.
		$table_name = $wpdb->prefix . 'mpd_template_conditions';

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			template_id bigint(20) NOT NULL,
			condition_type varchar(50) NOT NULL,
			condition_name varchar(100) NOT NULL,
			condition_value longtext,
			priority int(11) DEFAULT 10,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY template_id (template_id),
			KEY condition_type (condition_type)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		// Store database version.
		update_option( 'mpd_db_version', '1.0.0' );
	}

	/**
	 * Set custom capabilities.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private static function set_capabilities() {
		$role = get_role( 'administrator' );

		if ( $role ) {
			$role->add_cap( 'manage_mpd_templates' );
			$role->add_cap( 'edit_mpd_templates' );
			$role->add_cap( 'delete_mpd_templates' );
		}

		// Also add to shop_manager if WooCommerce is active.
		$shop_manager = get_role( 'shop_manager' );

		if ( $shop_manager ) {
			$shop_manager->add_cap( 'manage_mpd_templates' );
			$shop_manager->add_cap( 'edit_mpd_templates' );
		}
	}

	/**
	 * Register custom cron schedules.
	 *
	 * WordPress only includes hourly, twicedaily, and daily.
	 * We need 'weekly' for our cleanup task.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private static function register_custom_cron_schedules() {
		add_filter( 'cron_schedules', array( __CLASS__, 'add_cron_schedules' ) );
	}

	/**
	 * Add custom cron schedule intervals.
	 *
	 * @since 2.0.0
	 *
	 * @param array $schedules Existing cron schedules.
	 * @return array Modified cron schedules.
	 */
	public static function add_cron_schedules( $schedules ) {
		if ( ! isset( $schedules['weekly'] ) ) {
			$schedules['weekly'] = array(
				'interval' => WEEK_IN_SECONDS,
				'display'  => esc_html__( 'Once Weekly', 'magical-products-display' ),
			);
		}
		return $schedules;
	}

	/**
	 * Schedule cron events.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private static function schedule_events() {
		// Schedule license check (for pro version).
		if ( ! wp_next_scheduled( 'mpd_daily_license_check' ) ) {
			wp_schedule_event( time(), 'daily', 'mpd_daily_license_check' );
		}

		// Schedule cleanup of expired template conditions cache.
		if ( ! wp_next_scheduled( 'mpd_weekly_cleanup' ) ) {
			wp_schedule_event( time(), 'weekly', 'mpd_weekly_cleanup' );
		}
	}
}
