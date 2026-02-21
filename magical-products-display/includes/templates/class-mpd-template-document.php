<?php
/**
 * Template Document Type for Elementor
 *
 * Simple document type that extends Elementor's Page document.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Templates;

use Elementor\Controls_Manager;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Only define the class if Elementor is loaded.
if ( ! class_exists( '\Elementor\Modules\Library\Documents\Page' ) ) {
	return;
}

/**
 * Class Template_Document
 *
 * Extends Elementor's page document for shop templates.
 *
 * @since 2.0.0
 */
class Template_Document extends \Elementor\Modules\Library\Documents\Page {

	/**
	 * Get document name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mpd-template';
	}

	/**
	 * Get document title.
	 *
	 * @return string
	 */
	public static function get_title() {
		return esc_html__( 'Shop Template', 'magical-products-display' );
	}

	/**
	 * Get document plural title.
	 *
	 * @return string
	 */
	public static function get_plural_title() {
		return esc_html__( 'Shop Templates', 'magical-products-display' );
	}

	/**
	 * Get document properties.
	 *
	 * @return array
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['cpt']             = array( 'mpd_template' );
		$properties['support_kit']     = true;
		$properties['show_in_library'] = true;

		return $properties;
	}

	/**
	 * Register controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_controls() {
		parent::register_controls();

		$this->register_preview_controls();
	}

	/**
	 * Register preview product controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_preview_controls() {
		$this->start_controls_section(
			'mpd_preview_settings',
			array(
				'label' => __( 'Preview Settings', 'magical-products-display' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);

		$this->add_control(
			'mpd_preview_product_id',
			array(
				'label'       => __( 'Preview Product', 'magical-products-display' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->get_products_options(),
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Select a product to preview while designing this template. The widgets will display this product\'s data in the editor.', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'mpd_preview_info',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<div style="background: #f0f6fc; padding: 10px; border-radius: 4px; border-left: 4px solid #0073aa;">
					<strong>' . __( 'Tip:', 'magical-products-display' ) . '</strong><br>
					' . __( 'Select a product above to see real product data while designing. This helps you create pixel-perfect layouts.', 'magical-products-display' ) . '
				</div>',
				'content_classes' => 'elementor-panel-alert',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get products options for the select control.
	 *
	 * @since 2.0.0
	 *
	 * @return array Products options.
	 */
	protected function get_products_options() {
		$options = array(
			'' => __( 'Auto (Latest Product)', 'magical-products-display' ),
		);

		if ( ! class_exists( 'WooCommerce' ) ) {
			return $options;
		}

		$products = wc_get_products( array(
			'status' => 'publish',
			'limit'  => 50,
			'orderby' => 'title',
			'order'  => 'ASC',
		) );

		foreach ( $products as $product ) {
			$options[ $product->get_id() ] = sprintf(
				'%s (#%d)',
				$product->get_name(),
				$product->get_id()
			);
		}

		return $options;
	}

	/**
	 * Save document settings.
	 *
	 * @since 2.0.0
	 *
	 * @param array $data Data to save.
	 * @return void
	 */
	public function save( $data ) {
		// Save preview product ID to post meta for easy access.
		if ( isset( $data['settings']['mpd_preview_product_id'] ) ) {
			update_post_meta(
				$this->get_main_id(),
				'_mpd_preview_product_id',
				absint( $data['settings']['mpd_preview_product_id'] )
			);
		}

		parent::save( $data );
	}
}
