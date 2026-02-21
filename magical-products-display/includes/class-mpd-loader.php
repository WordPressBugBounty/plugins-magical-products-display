<?php
/**
 * Autoloader for Magical Shop Builder
 *
 * PSR-4 style autoloader for the plugin.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Loader
 *
 * Handles autoloading of plugin classes.
 *
 * @since 2.0.0
 */
class Loader {

	/**
	 * Namespace prefix for the plugin.
	 *
	 * @var string
	 */
	private static $namespace_prefix = 'MPD\\MagicalShopBuilder\\';

	/**
	 * Base directory for the namespace prefix.
	 *
	 * @var string
	 */
	private static $base_dir = '';

	/**
	 * Registered class mappings.
	 *
	 * @var array
	 */
	private static $class_map = array();

	/**
	 * Initialize the autoloader.
	 *
	 * @since 2.0.0
	 *
	 * @param string $base_dir Base directory for the plugin.
	 * @return void
	 */
	public static function init( $base_dir ) {
		self::$base_dir = trailingslashit( $base_dir ) . 'includes/';
		
		// Register class mappings.
		self::register_class_map();
		
		// Register the autoloader.
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Register the class map for non-standard class names.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	private static function register_class_map() {
		self::$class_map = array(
			// Admin classes.
			'MPD\\MagicalShopBuilder\\Admin\\Admin'                  => 'admin/class-mpd-admin.php',
			'MPD\\MagicalShopBuilder\\Admin\\REST_API'               => 'admin/class-mpd-rest-api.php',
			'MPD\\MagicalShopBuilder\\Admin\\Settings'               => 'admin/class-mpd-settings.php',
			'MPD\\MagicalShopBuilder\\Admin\\Product_Video_Metabox'  => 'admin/class-mpd-product-video-metabox.php',
			
			// Core classes.
			'MPD\\MagicalShopBuilder\\Core\\Activator'     => 'core/class-mpd-activator.php',
			'MPD\\MagicalShopBuilder\\Core\\Deactivator'   => 'core/class-mpd-deactivator.php',
			'MPD\\MagicalShopBuilder\\Core\\Upgrader'      => 'core/class-mpd-upgrader.php',
			'MPD\\MagicalShopBuilder\\Core\\I18n'          => 'core/class-mpd-i18n.php',
			'MPD\\MagicalShopBuilder\\Core\\Pro'           => 'core/class-mpd-pro.php',
			
			// Template classes.
			'MPD\\MagicalShopBuilder\\Templates\\Template_Builder'    => 'templates/class-mpd-template-builder.php',
			'MPD\\MagicalShopBuilder\\Templates\\Template_Conditions' => 'templates/class-mpd-template-conditions.php',
			'MPD\\MagicalShopBuilder\\Templates\\Template_Renderer'   => 'templates/class-mpd-template-renderer.php',
			'MPD\\MagicalShopBuilder\\Templates\\Template_Manager'    => 'templates/class-mpd-template-manager.php',
			'MPD\\MagicalShopBuilder\\Templates\\Template_Document'   => 'templates/class-mpd-template-document.php',
			
			// Widget base classes.
			'MPD\\MagicalShopBuilder\\Widgets\\Base\\Widget_Base'     => 'widgets/base/class-mpd-widget-base.php',
		);
	}

	/**
	 * Autoload callback.
	 *
	 * @since 2.0.0
	 *
	 * @param string $class The fully-qualified class name.
	 * @return void
	 */
	public static function autoload( $class ) {
		// Check if the class uses our namespace prefix.
		$len = strlen( self::$namespace_prefix );
		if ( strncmp( self::$namespace_prefix, $class, $len ) !== 0 ) {
			return;
		}

		// Check class map first.
		if ( isset( self::$class_map[ $class ] ) ) {
			$file = self::$base_dir . self::$class_map[ $class ];
			if ( file_exists( $file ) ) {
				require_once $file;
				return;
			}
		}

		// Get the relative class name.
		$relative_class = substr( $class, $len );

		// Replace namespace separators with directory separators.
		// Convert CamelCase to snake_case for file names.
		$file = self::$base_dir . self::class_to_file( $relative_class );

		// If the file exists, require it.
		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}

	/**
	 * Convert a class name to a file path.
	 *
	 * @since 2.0.0
	 *
	 * @param string $class The relative class name.
	 * @return string The file path.
	 */
	private static function class_to_file( $class ) {
		// Split the class name by namespace separator.
		$parts     = explode( '\\', $class );
		$class_name = array_pop( $parts );
		
		// Convert directory parts to lowercase.
		$directory = strtolower( implode( '/', $parts ) );
		
		// Convert class name from CamelCase to kebab-case.
		$file_name = self::camel_to_kebab( $class_name );
		
		// Build the file path.
		$file_path = '';
		if ( ! empty( $directory ) ) {
			$file_path .= $directory . '/';
		}
		$file_path .= 'class-' . $file_name . '.php';
		
		return $file_path;
	}

	/**
	 * Convert CamelCase to kebab-case.
	 *
	 * @since 2.0.0
	 *
	 * @param string $string The string to convert.
	 * @return string The converted string.
	 */
	private static function camel_to_kebab( $string ) {
		// Insert hyphens before uppercase letters.
		$result = preg_replace( '/([a-z])([A-Z])/', '$1-$2', $string );
		// Handle consecutive uppercase letters (like REST -> rest).
		$result = preg_replace( '/([A-Z]+)([A-Z][a-z])/', '$1-$2', $result );
		// Convert to lowercase.
		return strtolower( $result );
	}

	/**
	 * Add a class to the class map.
	 *
	 * @since 2.0.0
	 *
	 * @param string $class     The fully-qualified class name.
	 * @param string $file_path The relative file path from includes directory.
	 * @return void
	 */
	public static function add_class( $class, $file_path ) {
		self::$class_map[ $class ] = $file_path;
	}

	/**
	 * Manually load a file.
	 *
	 * @since 2.0.0
	 *
	 * @param string $file_path The relative file path from includes directory.
	 * @return bool Whether the file was loaded.
	 */
	public static function load_file( $file_path ) {
		$file = self::$base_dir . $file_path;
		if ( file_exists( $file ) ) {
			require_once $file;
			return true;
		}
		return false;
	}
}
