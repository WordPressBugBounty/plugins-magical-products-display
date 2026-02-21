<?php
/**
 * Performance optimizations for Magical Shop Builder.
 *
 * Implements minify CSS, minify JS, defer JS, and template caching
 * based on the Performance settings in the admin dashboard.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Frontend;

use MPD\MagicalShopBuilder\Admin\Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Performance
 *
 * Hooks into WordPress to apply performance optimizations for plugin assets.
 *
 * @since 2.0.0
 */
class Performance {

	/**
	 * Plugin asset handle prefix.
	 *
	 * @var string
	 */
	const HANDLE_PREFIX = 'mpd-';

	/**
	 * Additional plugin handles that don't use the prefix.
	 *
	 * @var array
	 */
	const PLUGIN_HANDLES = array(
		'mgproducts-style',
		'mgproducts-script',
		'mgproducts-main',
		'mgproducts-slider',
		'mgproducts-carousel',
		'mgproducts-tcarousel',
		'mgproducts-hover-card',
		'mgproducts-tab',
		'mgproducts-pricing',
		'mgproducts-accordion',
		'bootstrap-custom',
		'bootstrap-grid',
		'bootstrap-bundle',
		'mg-swiper',
		'swiper',
	);

	/**
	 * Performance settings cache.
	 *
	 * @var array|null
	 */
	private $settings = null;

	/**
	 * Cache directory info.
	 *
	 * @var array|null
	 */
	private $cache_dir = null;

	/**
	 * Initialize performance hooks.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init() {
		$this->settings = $this->get_settings();

		// Minify CSS: filter plugin stylesheet output.
		if ( ! empty( $this->settings['minify_css'] ) ) {
			add_filter( 'style_loader_tag', array( $this, 'minify_style_tag' ), 10, 4 );
		}

		// Minify JS: filter plugin script output.
		if ( ! empty( $this->settings['minify_js'] ) ) {
			add_filter( 'script_loader_tag', array( $this, 'minify_script_tag' ), 10, 3 );
		}

		// Defer JS: add defer attribute to plugin scripts.
		if ( ! empty( $this->settings['defer_js'] ) ) {
			add_filter( 'script_loader_tag', array( $this, 'defer_script_tag' ), 10, 3 );
		}

		// Cache invalidation: clear template cache when templates are saved.
		add_action( 'save_post_mpd_template', array( $this, 'on_template_save' ), 10, 1 );
		add_action( 'elementor/editor/after_save', array( $this, 'on_template_save' ), 10, 1 );

		// Clear all caches when performance settings change.
		add_action( 'mpd_settings_updated', array( $this, 'on_settings_updated' ) );
	}

	/**
	 * Get performance settings.
	 *
	 * @since 2.0.0
	 *
	 * @return array
	 */
	private function get_settings() {
		if ( class_exists( '\MPD\MagicalShopBuilder\Admin\Settings' ) ) {
			return Settings::instance()->get_performance_settings();
		}

		// Fallback: read directly from option.
		$defaults = array(
			'lazy_load_widgets' => true,
			'minify_css'        => false,
			'minify_js'         => false,
			'defer_js'          => false,
			'cache_templates'   => true,
			'cache_duration'    => 3600,
		);
		$settings = get_option( 'mpd_performance_settings', array() );

		return wp_parse_args( $settings, $defaults );
	}

	/**
	 * Check if a handle belongs to this plugin.
	 *
	 * @since 2.0.0
	 *
	 * @param string $handle The asset handle.
	 * @return bool
	 */
	private function is_plugin_handle( $handle ) {
		// Check prefix.
		if ( 0 === strpos( $handle, self::HANDLE_PREFIX ) ) {
			return true;
		}

		// Check known handles.
		return in_array( $handle, self::PLUGIN_HANDLES, true );
	}

	// =========================================================================
	// File-based Minification
	// =========================================================================

	/**
	 * Get (or create) the minification cache directory.
	 *
	 * @since 2.0.0
	 *
	 * @return array|false Array with 'path' and 'url' keys, or false on failure.
	 */
	private function get_file_cache_dir() {
		if ( null !== $this->cache_dir ) {
			return $this->cache_dir;
		}

		$upload_dir = wp_upload_dir();

		if ( ! empty( $upload_dir['error'] ) ) {
			$this->cache_dir = false;
			return false;
		}

		$path = $upload_dir['basedir'] . '/mpd-cache';
		$url  = $upload_dir['baseurl'] . '/mpd-cache';

		if ( ! is_dir( $path ) ) {
			if ( ! wp_mkdir_p( $path ) ) {
				$this->cache_dir = false;
				return false;
			}
			// Add an index.php for security.
			global $wp_filesystem;
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			if ( WP_Filesystem() ) {
				$wp_filesystem->put_contents( $path . '/index.php', '<?php // Silence is golden.', FS_CHMOD_FILE );
			}
		}

		$this->cache_dir = array(
			'path' => $path,
			'url'  => $url,
		);

		return $this->cache_dir;
	}

	/**
	 * Check if a URL points to an already-minified file.
	 *
	 * @since 2.0.0
	 *
	 * @param string $url The asset URL.
	 * @return bool
	 */
	private function is_already_minified( $url ) {
		$path = strtok( $url, '?' );
		return (bool) preg_match( '/\.min\.(css|js)$/i', $path );
	}

	/**
	 * Convert a URL to a local filesystem path.
	 *
	 * @since 2.0.0
	 *
	 * @param string $url The full asset URL.
	 * @return string|false Local file path or false.
	 */
	private function url_to_path( $url ) {
		// Strip query string.
		$url = strtok( $url, '?' );

		// Strategy 1: content_url → WP_CONTENT_DIR.
		$content_url = content_url();
		$content_url_no_scheme = preg_replace( '#^https?:#', '', $content_url );
		$url_no_scheme         = preg_replace( '#^https?:#', '', $url );

		if ( 0 === strpos( $url_no_scheme, $content_url_no_scheme ) ) {
			$relative = substr( $url_no_scheme, strlen( $content_url_no_scheme ) );
			$local    = WP_CONTENT_DIR . str_replace( '/', DIRECTORY_SEPARATOR, $relative );
			if ( file_exists( $local ) ) {
				return $local;
			}
		}

		// Strategy 2: site_url → ABSPATH.
		$site_url            = site_url();
		$site_url_no_scheme  = preg_replace( '#^https?:#', '', $site_url );

		if ( 0 === strpos( $url_no_scheme, $site_url_no_scheme ) ) {
			$relative = substr( $url_no_scheme, strlen( $site_url_no_scheme ) );
			$local    = ABSPATH . ltrim( str_replace( '/', DIRECTORY_SEPARATOR, $relative ), DIRECTORY_SEPARATOR );
			if ( file_exists( $local ) ) {
				return $local;
			}
		}

		return false;
	}

	/**
	 * Get a minified version URL for a CSS or JS file.
	 *
	 * Reads the source file, minifies it, saves to cache directory,
	 * and returns the URL pointing to the cached minified file.
	 * Uses the file modification time as cache key so changes are detected instantly.
	 *
	 * @since 2.0.0
	 *
	 * @param string $src  The original file URL.
	 * @param string $type 'css' or 'js'.
	 * @return string|false The minified file URL, or false on failure.
	 */
	private function get_minified_url( $src, $type ) {
		$file_path = $this->url_to_path( $src );
		if ( ! $file_path ) {
			return false;
		}

		$cache = $this->get_file_cache_dir();
		if ( ! $cache ) {
			return false;
		}

		// Build a unique cache filename based on path hash + file mtime.
		$path_hash  = md5( $file_path );
		$mtime      = filemtime( $file_path );
		$cache_name = $path_hash . '-' . $mtime . '.min.' . $type;
		$cache_path = $cache['path'] . '/' . $cache_name;
		$cache_url  = $cache['url'] . '/' . $cache_name;

		// Return cached version if it already exists.
		if ( file_exists( $cache_path ) ) {
			return $cache_url;
		}

		// Read source file.
		global $wp_filesystem;
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		if ( ! WP_Filesystem() ) {
			return false;
		}
		$content = $wp_filesystem->get_contents( $file_path );
		if ( false === $content || '' === $content ) {
			return false;
		}

		// Minify.
		if ( 'css' === $type ) {
			// Resolve relative URLs (the minified file lives in a different directory).
			$source_url = dirname( strtok( $src, '?' ) );
			$content    = $this->resolve_css_urls( $content, $source_url );
			$content    = $this->minify_css_content( $content );
		} else {
			$content = $this->minify_js_content( $content );
		}

		if ( empty( $content ) ) {
			return false;
		}

		// Clean old cache files for this source (different mtime).
		$this->cleanup_old_cache( $cache['path'], $path_hash, $cache_name );

		// Write minified file.
		global $wp_filesystem;
		$written = $wp_filesystem->put_contents( $cache_path, $content, FS_CHMOD_FILE );

		return false !== $written ? $cache_url : false;
	}

	/**
	 * Resolve relative URLs in CSS to absolute URLs.
	 *
	 * Since the minified CSS file is served from a different directory,
	 * relative paths like url(../images/bg.png) would break.
	 *
	 * @since 2.0.0
	 *
	 * @param string $css        CSS content.
	 * @param string $source_url Directory URL of the original CSS file.
	 * @return string CSS with resolved URLs.
	 */
	private function resolve_css_urls( $css, $source_url ) {
		$source_url = rtrim( $source_url, '/' ) . '/';

		return preg_replace_callback(
			'/url\(\s*([\'"]?)(?!(?:data:|https?:|\/\/|\/|#))(.+?)\1\s*\)/i',
			function ( $m ) use ( $source_url ) {
				return 'url(' . $m[1] . $source_url . $m[2] . $m[1] . ')';
			},
			$css
		);
	}

	/**
	 * Minify CSS content string.
	 *
	 * @since 2.0.0
	 *
	 * @param string $css Raw CSS content.
	 * @return string Minified CSS.
	 */
	private function minify_css_content( $css ) {
		// Remove comments.
		$css = preg_replace( '!/\*.*?\*/!s', '', $css );
		// Collapse whitespace.
		$css = preg_replace( '/\s+/', ' ', $css );
		// Remove spaces around selectors/properties.
		$css = preg_replace( '/\s*([{}:;,>+~])\s*/', '$1', $css );
		// Remove last semicolons before closing braces.
		$css = str_replace( ';}', '}', $css );

		return trim( $css );
	}

	/**
	 * Minify JavaScript content string.
	 *
	 * @since 2.0.0
	 *
	 * @param string $js Raw JS content.
	 * @return string Minified JS.
	 */
	private function minify_js_content( $js ) {
		// Remove single-line comments (not URLs with ://).
		$js = preg_replace( '#(?<!:)//(?![\'"]).+$#m', '', $js );
		// Remove multi-line comments.
		$js = preg_replace( '!/\*.*?\*/!s', '', $js );
		// Collapse whitespace.
		$js = preg_replace( '/\s+/', ' ', $js );

		return trim( $js );
	}

	/**
	 * Remove old cached files for a given source (stale mtimes).
	 *
	 * @since 2.0.0
	 *
	 * @param string $cache_dir  Absolute path to cache directory.
	 * @param string $path_hash  MD5 hash of the source file path.
	 * @param string $keep_name  Filename to keep (current cache file).
	 * @return void
	 */
	private function cleanup_old_cache( $cache_dir, $path_hash, $keep_name ) {
		$pattern = $cache_dir . '/' . $path_hash . '-*';
		$old     = glob( $pattern );
		if ( ! $old ) {
			return;
		}
		global $wp_filesystem;
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		WP_Filesystem();
		foreach ( $old as $file ) {
			if ( basename( $file ) !== $keep_name ) {
				$wp_filesystem->delete( $file );
			}
		}
	}

	// =========================================================================
	// Style / Script Tag Filters
	// =========================================================================

	/**
	 * Minify CSS file and inline styles for plugin handles.
	 *
	 * For external CSS files: creates a minified cached version and swaps the URL.
	 * For inline <style> blocks (from wp_add_inline_style): minifies in-place.
	 * Skips files that are already minified (.min.css).
	 *
	 * @since 2.0.0
	 *
	 * @param string $tag    The full style tag HTML.
	 * @param string $handle The style handle.
	 * @param string $href   The stylesheet URL.
	 * @param string $media  The media attribute.
	 * @return string Modified tag.
	 */
	public function minify_style_tag( $tag, $handle, $href, $media ) {
		if ( ! $this->is_plugin_handle( $handle ) ) {
			return $tag;
		}

		// Minify the external CSS file (skip already-minified).
		if ( ! empty( $href ) && ! $this->is_already_minified( $href ) ) {
			$minified_url = $this->get_minified_url( $href, 'css' );
			if ( $minified_url ) {
				$tag = str_replace( $href, $minified_url, $tag );
			}
		}

		// Also minify any inline <style> blocks (from wp_add_inline_style).
		$tag = preg_replace_callback(
			'/<style[^>]*>(.*?)<\/style>/s',
			function ( $matches ) {
				$css = $matches[1];
				if ( empty( trim( $css ) ) ) {
					return $matches[0];
				}
				return '<style>' . $this->minify_css_content( $css ) . '</style>';
			},
			$tag
		);

		return $tag;
	}

	/**
	 * Minify JS file and inline scripts for plugin handles.
	 *
	 * For external JS files: creates a minified cached version and swaps the URL.
	 * For inline <script> blocks (from wp_add_inline_script): minifies in-place.
	 * Skips files that are already minified (.min.js).
	 *
	 * @since 2.0.0
	 *
	 * @param string $tag    The full script tag HTML.
	 * @param string $handle The script handle.
	 * @param string $src    The script source URL.
	 * @return string Modified tag.
	 */
	public function minify_script_tag( $tag, $handle, $src ) {
		if ( ! $this->is_plugin_handle( $handle ) ) {
			return $tag;
		}

		// Minify the external JS file (skip already-minified).
		if ( ! empty( $src ) && ! $this->is_already_minified( $src ) ) {
			$minified_url = $this->get_minified_url( $src, 'js' );
			if ( $minified_url ) {
				$tag = str_replace( $src, $minified_url, $tag );
			}
		}

		// Minify inline <script> blocks (from wp_add_inline_script).
		$tag = preg_replace_callback(
			'/<script(?![^>]*\bsrc\b)[^>]*>(.*?)<\/script>/s',
			function ( $matches ) {
				$js = $matches[1];
				if ( empty( trim( $js ) ) ) {
					return $matches[0];
				}
				return '<script>' . $this->minify_js_content( $js ) . '</script>';
			},
			$tag
		);

		return $tag;
	}

	/**
	 * Add defer attribute to plugin script tags.
	 *
	 * Only defers scripts that load from an external src. Inline scripts
	 * and scripts already deferred/async are left unchanged.
	 *
	 * @since 2.0.0
	 *
	 * @param string $tag    The full script tag HTML.
	 * @param string $handle The script handle.
	 * @param string $src    The script source URL.
	 * @return string Modified tag.
	 */
	public function defer_script_tag( $tag, $handle, $src ) {
		// Only defer plugin scripts with a src.
		if ( ! $this->is_plugin_handle( $handle ) || empty( $src ) ) {
			return $tag;
		}

		// Don't double-defer or defer async scripts.
		if ( false !== strpos( $tag, 'defer' ) || false !== strpos( $tag, 'async' ) ) {
			return $tag;
		}

		// Don't defer jQuery or its dependencies.
		if ( 'jquery' === $handle || 'jquery-core' === $handle || 'jquery-migrate' === $handle ) {
			return $tag;
		}

		// Add defer attribute.
		$tag = str_replace( ' src=', ' defer src=', $tag );

		return $tag;
	}

	// =========================================================================
	// Template Caching
	// =========================================================================

	/**
	 * Check if template caching is enabled.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_cache_enabled() {
		return ! empty( $this->settings['cache_templates'] );
	}

	/**
	 * Get cache duration in seconds.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function get_cache_duration() {
		return isset( $this->settings['cache_duration'] ) ? absint( $this->settings['cache_duration'] ) : 3600;
	}

	/**
	 * Get cached template content.
	 *
	 * @since 2.0.0
	 *
	 * @param int $template_id Template post ID.
	 * @return string|false Cached content or false if not cached.
	 */
	public function get_cached_template( $template_id ) {
		if ( ! $this->is_cache_enabled() ) {
			return false;
		}

		$cache_key = 'mpd_tpl_' . $template_id;
		$cached    = get_transient( $cache_key );

		return false !== $cached ? $cached : false;
	}

	/**
	 * Set cached template content.
	 *
	 * @since 2.0.0
	 *
	 * @param int    $template_id Template post ID.
	 * @param string $content     Rendered content.
	 * @return void
	 */
	public function set_cached_template( $template_id, $content ) {
		if ( ! $this->is_cache_enabled() || empty( $content ) ) {
			return;
		}

		$cache_key = 'mpd_tpl_' . $template_id;
		set_transient( $cache_key, $content, $this->get_cache_duration() );
	}

	/**
	 * Clear cached template.
	 *
	 * @since 2.0.0
	 *
	 * @param int $template_id Template post ID.
	 * @return void
	 */
	public function clear_cached_template( $template_id ) {
		$cache_key = 'mpd_tpl_' . $template_id;
		delete_transient( $cache_key );
	}

	/**
	 * Clear all caches (template transients + minified file cache).
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function clear_all_caches() {
		global $wpdb;

		// Clear template transients.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
				$wpdb->esc_like( '_transient_mpd_tpl_' ) . '%',
				$wpdb->esc_like( '_transient_timeout_mpd_tpl_' ) . '%'
			)
		);

		// Clear minified file cache.
		$this->clear_minified_cache();
	}

	/**
	 * Clear all minified CSS/JS files from the cache directory.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private function clear_minified_cache() {
		$cache = $this->get_file_cache_dir();
		if ( ! $cache ) {
			return;
		}

		$files = glob( $cache['path'] . '/*.min.{css,js}', GLOB_BRACE );
		if ( $files ) {
			global $wp_filesystem;
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			WP_Filesystem();
			foreach ( $files as $file ) {
				$wp_filesystem->delete( $file );
			}
		}
	}

	// =========================================================================
	// Cache Invalidation Hooks
	// =========================================================================

	/**
	 * Handle template save — clear its cache.
	 *
	 * @since 2.0.0
	 *
	 * @param int $post_id The template post ID.
	 * @return void
	 */
	public function on_template_save( $post_id ) {
		$this->clear_cached_template( $post_id );
	}

	/**
	 * Handle settings update — clear all caches when performance settings change.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Updated settings.
	 * @return void
	 */
	public function on_settings_updated( $settings ) {
		if ( isset( $settings['performance'] ) ) {
			$this->clear_all_caches();
		}
	}
}
