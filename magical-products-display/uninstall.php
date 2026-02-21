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
	'mpd_widget_settings',
	'mpd_delete_data_on_uninstall',
	'mpd_addon_info1_text',
	'mpd_welcome_notice_version',
	'mpd_menu_notice_version',
	'mpd_pro_notice_dismissed',
	'mpd_plugin_suggestion_dismissed1',
	// Keep existing option names for backward compatibility.
	'mgppro_is_active',
	'mgppro_has_valid_lic',
);

foreach ( $options_to_delete as $option ) {
	delete_option( $option );
}

// Delete all transients.
$wpdb->query(
	$wpdb->prepare(
		"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
		$wpdb->esc_like( '_transient_mpd_' ) . '%',
		$wpdb->esc_like( '_transient_timeout_mpd_' ) . '%'
	)
);

// Delete custom tables.
$table_name = $wpdb->prefix . 'mpd_template_conditions';
$wpdb->query( "DROP TABLE IF EXISTS {$table_name}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

// Delete template posts.
$wpdb->query(
	$wpdb->prepare(
		"DELETE FROM {$wpdb->posts} WHERE post_type = %s",
		'mpd_template'
	)
);

// Delete template post meta.
$wpdb->query(
	$wpdb->prepare(
		"DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE %s",
		$wpdb->esc_like( '_mpd_' ) . '%'
	)
);

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

// Clear scheduled events.
$events = array(
	'mpd_daily_license_check',
	'mpd_weekly_cleanup',
);

foreach ( $events as $event ) {
	$timestamp = wp_next_scheduled( $event );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, $event );
	}
}

// Clear any object cache.
wp_cache_flush();
