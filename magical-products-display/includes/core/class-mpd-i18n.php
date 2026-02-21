<?php
/**
 * Internationalization functionality.
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
 * Class I18n
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since 2.0.0
 */
class I18n {

	/**
	 * Text domain for the plugin.
	 *
	 * @var string
	 */
	private $domain = 'magical-products-display';

	/**
	 * Initialize the class.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( plugin_basename( MAGICAL_PRODUCTS_DISPLAY_FILE ) ) . '/languages/'
		);
	}

	/**
	 * Get the text domain.
	 *
	 * @since 2.0.0
	 *
	 * @return string The text domain.
	 */
	public function get_domain() {
		return $this->domain;
	}
}
