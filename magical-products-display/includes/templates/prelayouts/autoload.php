<?php
/**
 * Pre-Layouts Autoloader
 *
 * Loads all pre-layout related classes.
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load pre-layout classes.
require_once __DIR__ . '/class-mpd-prelayout-library.php';
require_once __DIR__ . '/class-mpd-prelayout-importer.php';
require_once __DIR__ . '/class-mpd-prelayout-manager.php';
