<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Check if we should delete data on uninstall.
$delete_data = get_option( 'mpd_delete_data_on_uninstall', 'no' );

if ( 'yes' !== $delete_data ) {
	return;
}

global $wpdb;

// Delete plugin options.
$options_to_delete = array(
	'mpd_plugin_activated',
	'mpd_install_date',
	'mpd_plugin_version',
	'mpd_version',
	'mpd_db_version',
	'mpd_flush_rewrite_rules',
	'mpd_general_settings',
	'mpd_single_product_settings',
	'mpd_cart_checkout_settings',
	'mpd_my_account_settings',
	'mpd_performance_settings',
	'mpd_preloader_settings',
	'mpd_widget_settings',
	'mpd_enabled_widgets',
	'mpd_known_widgets',
	'mpd_free_shipping_threshold',
	'mpd_template_cpt_version',
	'mpd_delete_data_on_uninstall',
	'mpd_addon_info1_text',
	'mpd_welcome_notice_version',
	'mpd_menu_notice_version',
	'mpd_pro_notice_dismissed',
	'mpd_pro_notice_dismissed_v2',
	'mpd_plugin_suggestion_dismissed1',
	// Keep existing option names for backward compatibility.
	'mgppro_is_active',
	'mgppro_has_valid_lic',
	'mgppro_license_key',
);

foreach ( $options_to_delete as $option ) {
	delete_option( $option );
}

// Delete all transients (single-site _transient_ and multisite _site_transient_ variants).
$transient_like = array(
	'_transient_mpd_',
	'_transient_timeout_mpd_',
	'_site_transient_mpd_',
	'_site_transient_timeout_mpd_',
);
foreach ( $transient_like as $pattern ) {
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
			$wpdb->esc_like( $pattern ) . '%'
		)
	);
}

// Delete custom tables.
$table_name = $wpdb->prefix . 'mpd_template_conditions';
$wpdb->query( "DROP TABLE IF EXISTS {$table_name}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

// Delete template posts and their meta via wp_delete_post for clean removal.
// Only touch posts of type mpd_template — never other post types.
$template_ids = $wpdb->get_col(
	$wpdb->prepare(
		"SELECT ID FROM {$wpdb->posts} WHERE post_type = %s",
		'mpd_template'
	)
);
if ( ! empty( $template_ids ) ) {
	foreach ( $template_ids as $template_id ) {
		wp_delete_post( (int) $template_id, true );
	}
}

// Remove capabilities.
$role = get_role( 'administrator' );
if ( $role ) {
	$role->remove_cap( 'manage_mpd_templates' );
	$role->remove_cap( 'edit_mpd_templates' );
	$role->remove_cap( 'delete_mpd_templates' );
}

$shop_manager = get_role( 'shop_manager' );
if ( $shop_manager ) {
	$shop_manager->remove_cap( 'manage_mpd_templates' );
	$shop_manager->remove_cap( 'edit_mpd_templates' );
}

// Clear scheduled events (including AJAX Search cleanup event).
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
	// Clear any remaining instances.
	wp_clear_scheduled_hook( $event );
}

// Clear plugin object-cache groups instead of flushing everything.
wp_cache_delete( 'mpd_template_conditions', 'mpd' );
wp_cache_delete( 'mpd_settings', 'mpd' );
