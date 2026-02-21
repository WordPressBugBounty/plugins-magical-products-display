<?php
/**
 * Remote Layout Client
 *
 * Fetches and caches layouts from the remote MPD Layout Server.
 *
 * @package Magical_Products_Display
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MPD_Remote_Layout_Client
 *
 * Handles fetching layouts from a remote server via REST API.
 *
 * @since 1.0.0
 */
class MPD_Remote_Layout_Client {

	/**
	 * Single instance.
	 *
	 * @var MPD_Remote_Layout_Client|null
	 */
	private static $instance = null;

	/**
	 * Remote server URL.
	 *
	 * @var string
	 */
	private $server_url = '';

	/**
	 * Default server URL.
	 *
	 * @var string
	 */
	const DEFAULT_SERVER_URL = 'https://mp.wpcolors.net/';

	/**
	 * API namespace.
	 *
	 * @var string
	 */
	const API_NAMESPACE = 'mpd-layout-server/v1';

	/**
	 * Cache group.
	 *
	 * @var string
	 */
	const CACHE_GROUP = 'mpd_remote_layouts';

	/**
	 * Cache expiration in seconds (1 day).
	 *
	 * @var int
	 */
	const CACHE_EXPIRATION = DAY_IN_SECONDS;

	/**
	 * Get single instance.
	 *
	 * @since 1.0.0
	 *
	 * @return MPD_Remote_Layout_Client
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->server_url = self::DEFAULT_SERVER_URL;
	}

	/**
	 * Check if remote server is configured.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_configured() {
		return ! empty( $this->server_url );
	}

	/**
	 * Get current server URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_server_url() {
		return $this->server_url;
	}

	/**
	 * Make API request.
	 *
	 * @since 1.0.0
	 *
	 * @param string $endpoint API endpoint.
	 * @param array  $args     Request arguments.
	 * @return array|WP_Error
	 */
	private function api_request( $endpoint, $args = array() ) {
		if ( ! $this->is_configured() ) {
			return new WP_Error(
				'server_not_configured',
				__( 'Remote server URL is not configured.', 'magical-products-display' )
			);
		}

		$url = trailingslashit( $this->server_url ) . 'wp-json/' . self::API_NAMESPACE . '/' . ltrim( $endpoint, '/' );

		// Add query parameters if any.
		if ( ! empty( $args['query'] ) ) {
			$url = add_query_arg( $args['query'], $url );
		}

		$request_args = array(
			'timeout'   => 30,
			'sslverify' => true,
			'headers'   => array(
				'Accept'       => 'application/json',
				'Content-Type' => 'application/json',
			),
		);

		// Add site URL for tracking.
		$request_args['headers']['X-MPD-Site-URL'] = home_url();

		$response = wp_remote_get( $url, $request_args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		$body        = wp_remote_retrieve_body( $response );
		$data        = json_decode( $body, true );

		if ( 200 !== $status_code ) {
			$message = isset( $data['message'] ) ? $data['message'] : __( 'Unknown error occurred.', 'magical-products-display' );
			return new WP_Error( 'api_error', $message, array( 'status' => $status_code ) );
		}

		return $data;
	}

	/**
	 * Get server info.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $force_refresh Skip cache.
	 * @return array|WP_Error
	 */
	public function get_server_info( $force_refresh = false ) {
		$cache_key = 'server_info';

		if ( ! $force_refresh ) {
			$cached = $this->get_cached( $cache_key );
			if ( false !== $cached ) {
				return $cached;
			}
		}

		$response = $this->api_request( 'info' );

		if ( ! is_wp_error( $response ) && ! empty( $response['success'] ) ) {
			$this->set_cache( $cache_key, $response );
		}

		return $response;
	}

	/**
	 * Get all remote layouts.
	 *
	 * @since 1.0.0
	 *
	 * @param array $filters   Optional filters.
	 * @param bool  $force_refresh Skip cache.
	 * @return array|WP_Error
	 */
	public function get_layouts( $filters = array(), $force_refresh = false ) {
		$cache_key = 'layouts_' . md5( wp_json_encode( $filters ) );

		if ( ! $force_refresh ) {
			$cached = $this->get_cached( $cache_key );
			if ( false !== $cached ) {
				return $cached;
			}
		}

		$response = $this->api_request( 'layouts', array( 'query' => $filters ) );

		if ( ! is_wp_error( $response ) && ! empty( $response['success'] ) ) {
			$this->set_cache( $cache_key, $response );
		}

		return $response;
	}

	/**
	 * Get layouts by type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type          Layout type.
	 * @param bool   $force_refresh Skip cache.
	 * @return array|WP_Error
	 */
	public function get_layouts_by_type( $type, $force_refresh = false ) {
		$cache_key = 'layouts_type_' . sanitize_key( $type );

		if ( ! $force_refresh ) {
			$cached = $this->get_cached( $cache_key );
			if ( false !== $cached ) {
				return $cached;
			}
		}

		$response = $this->api_request( 'layouts/type/' . sanitize_key( $type ) );

		if ( ! is_wp_error( $response ) && ! empty( $response['success'] ) ) {
			$this->set_cache( $cache_key, $response );
		}

		return $response;
	}

	/**
	 * Get single layout.
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout_id     Layout ID.
	 * @param bool   $force_refresh Skip cache.
	 * @return array|WP_Error
	 */
	public function get_layout( $layout_id, $force_refresh = false ) {
		$cache_key = 'layout_' . sanitize_key( $layout_id );

		if ( ! $force_refresh ) {
			$cached = $this->get_cached( $cache_key );
			if ( false !== $cached ) {
				return $cached;
			}
		}

		$response = $this->api_request( 'layouts/' . sanitize_key( $layout_id ) );

		if ( ! is_wp_error( $response ) && ! empty( $response['success'] ) ) {
			$this->set_cache( $cache_key, $response );
		}

		return $response;
	}

	/**
	 * Get layout structure (JSON).
	 *
	 * @since 1.0.0
	 *
	 * @param string $layout_id     Layout ID.
	 * @param bool   $force_refresh Skip cache.
	 * @return array|WP_Error
	 */
	public function get_layout_structure( $layout_id, $force_refresh = false ) {
		$cache_key = 'layout_structure_' . sanitize_key( $layout_id );

		if ( ! $force_refresh ) {
			$cached = $this->get_cached( $cache_key );
			if ( false !== $cached ) {
				return $cached;
			}
		}

		$response = $this->api_request( 'layouts/' . sanitize_key( $layout_id ) . '/structure' );

		if ( ! is_wp_error( $response ) && ! empty( $response['success'] ) ) {
			$this->set_cache( $cache_key, $response );
		}

		return $response;
	}

	/**
	 * Get cached data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Cache key.
	 * @return mixed|false
	 */
	private function get_cached( $key ) {
		return get_transient( self::CACHE_GROUP . '_' . $key );
	}

	/**
	 * Set cache.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key  Cache key.
	 * @param mixed  $data Data to cache.
	 */
	private function set_cache( $key, $data ) {
		set_transient( self::CACHE_GROUP . '_' . $key, $data, self::CACHE_EXPIRATION );
	}

	/**
	 * Clear all cache.
	 *
	 * @since 1.0.0
	 */
	public function clear_cache() {
		global $wpdb;

		// Clear all transients with our prefix.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
				'_transient_' . self::CACHE_GROUP . '_%',
				'_transient_timeout_' . self::CACHE_GROUP . '_%'
			)
		);
	}

	/**
	 * Get merged layouts (local + remote).
	 *
	 * @since 1.0.0
	 *
	 * @param array $local_layouts  Local layouts array.
	 * @param string $type          Layout type (optional).
	 * @param bool   $force_refresh Skip cache.
	 * @return array
	 */
	public function get_merged_layouts( $local_layouts, $type = '', $force_refresh = false ) {
		// If remote server not configured, return only local layouts.
		if ( ! $this->is_configured() ) {
			return $local_layouts;
		}

		// Get remote layouts.
		if ( ! empty( $type ) ) {
			$remote_response = $this->get_layouts_by_type( $type, $force_refresh );
		} else {
			$remote_response = $this->get_layouts( array(), $force_refresh );
		}

		// If error, return only local layouts.
		if ( is_wp_error( $remote_response ) || empty( $remote_response['layouts'] ) ) {
			return $local_layouts;
		}

		$remote_layouts = $remote_response['layouts'];
		$merged         = $local_layouts;

		// Get local layout IDs for duplicate checking.
		$local_ids = wp_list_pluck( $local_layouts, 'id' );

		// Add remote layouts that don't exist locally.
		foreach ( $remote_layouts as $remote_layout ) {
			if ( ! in_array( $remote_layout['id'], $local_ids, true ) ) {
				// Mark as remote source.
				$remote_layout['source'] = 'remote';
				$merged[]                = $remote_layout;
			}
		}

		return $merged;
	}
}

/**
 * Get remote layout client instance.
 *
 * @since 1.0.0
 *
 * @return MPD_Remote_Layout_Client
 */
if ( ! function_exists( 'mpd_remote_layout_client' ) ) {
function mpd_remote_layout_client() {
	return MPD_Remote_Layout_Client::instance();
}
}
