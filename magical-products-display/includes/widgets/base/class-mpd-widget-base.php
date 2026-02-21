<?php
/**
 * Base Widget Class
 *
 * Abstract base class for all Magical Shop Builder widgets.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Widgets\Base;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure Elementor is loaded.
if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
	return;
}

/**
 * Class Widget_Base
 *
 * Base class that all MPD widgets should extend.
 * Provides common functionality and standardized structure.
 *
 * @since 2.0.0
 */
abstract class Widget_Base extends \Elementor\Widget_Base {

	use \MPD\MagicalShopBuilder\Traits\Pro_Lock;
	use \MPD\MagicalShopBuilder\Traits\WC_Helpers;

	/**
	 * Category constants for widget organization.
	 *
	 * @since 2.0.0
	 */
	const CATEGORY_SHOP_BUILDER   = 'mpd-productwoo';
	const CATEGORY_SINGLE_PRODUCT = 'mpd-single-product';
	const CATEGORY_CART_CHECKOUT  = 'mpd-cart-checkout';
	const CATEGORY_MY_ACCOUNT     = 'mpd-my-account';
	const CATEGORY_SHOP_ARCHIVE   = 'mpd-shop-archive';
	const CATEGORY_THANKYOU       = 'mpd-thankyou';
	const CATEGORY_GLOBAL         = 'mpd-global';

	/**
	 * Widget category.
	 *
	 * @var string
	 */
	protected $widget_category = self::CATEGORY_SHOP_BUILDER;

	/**
	 * Widget icon.
	 *
	 * @var string
	 */
	protected $widget_icon = 'eicon-woocommerce';

	/**
	 * Whether widget is pro only.
	 *
	 * @var bool
	 */
	protected $is_pro_widget = false;

	/**
	 * Get widget categories.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( $this->widget_category );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return $this->widget_icon;
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.0.0
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'woocommerce', 'shop', 'product', 'magical' );
	}

	/**
	 * Get widget help URL.
	 *
	 * @since 2.0.0
	 *
	 * @return string Help URL.
	 */
	public function get_custom_help_url() {
		return 'https://mp.wpcolors.net/docs/';
	}

	/**
	 * Whether widget is a single product widget.
	 *
	 * Override in child classes to return true if widget is a single product widget.
	 *
	 * @since 2.0.0
	 *
	 * @return bool Whether widget is a single product widget.
	 */
	protected function is_single_product_widget() {
		// Auto-detect based on namespace.
		return strpos( static::class, 'SingleProduct' ) !== false;
	}

	/**
	 * Whether widget is a thank you page widget.
	 *
	 * Override in child classes to return true if widget is a thank you widget.
	 *
	 * @since 2.1.0
	 *
	 * @return bool Whether widget is a thank you page widget.
	 */
	protected function is_thankyou_widget() {
		// Auto-detect based on namespace.
		return strpos( static::class, 'ThankYou' ) !== false;
	}

	/**
	 * Whether widget requires scripts.
	 *
	 * Override in child classes to return true if widget needs JS.
	 *
	 * @since 2.0.0
	 *
	 * @return bool Whether widget requires scripts.
	 */
	protected function requires_scripts() {
		return false;
	}

	/**
	 * Whether widget requires styles.
	 *
	 * Override in child classes to return true if widget needs CSS.
	 *
	 * @since 2.0.0
	 *
	 * @return bool Whether widget requires styles.
	 */
	protected function requires_styles() {
		return true;
	}

	/**
	 * Get script dependencies.
	 *
	 * Override in child classes to define script dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Script handles.
	 */
	public function get_script_depends() {
		if ( ! $this->requires_scripts() ) {
			return array();
		}

		return array( 'mpd-' . $this->get_name() );
	}

	/**
	 * Get style dependencies.
	 *
	 * Override in child classes to define style dependencies.
	 *
	 * @since 2.0.0
	 *
	 * @return array Style handles.
	 */
	public function get_style_depends() {
		if ( ! $this->requires_styles() ) {
			return array();
		}

		// Single product widgets use shared stylesheet.
		if ( $this->is_single_product_widget() ) {
			return array( 'mpd-single-product' );
		}

		// Thank you page widgets use shared stylesheet.
		if ( $this->is_thankyou_widget() ) {
			return array( 'mpd-thankyou-widgets' );
		}

		return array( 'mpd-' . $this->get_name() );
	}

	/**
	 * Register widget controls.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_controls() {
		// Check if this is a pro widget.
		if ( $this->is_pro_widget && ! $this->is_pro() ) {
			$this->register_pro_required_notice();
			return;
		}

		// Register content controls.
		$this->register_content_controls();

		// Register style controls.
		$this->register_style_controls();

		// Register advanced controls (optional).
		$this->register_advanced_controls();
	}

	/**
	 * Register content controls.
	 *
	 * Override in child classes.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		// Override in child classes.
	}

	/**
	 * Register style controls.
	 *
	 * Override in child classes.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_style_controls() {
		// Override in child classes.
	}

	/**
	 * Register advanced controls.
	 *
	 * Override in child classes.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_advanced_controls() {
		// Override in child classes.
	}

	/**
	 * Register pro required notice.
	 *
	 * Shows notice when trying to use a pro-only widget.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function register_pro_required_notice() {
		$this->start_controls_section(
			'section_pro_required',
			array(
				'label' => __( 'Pro Required', 'magical-products-display' ),
			)
		);

		$this->add_control(
			'pro_required_notice',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					'<div style="padding: 20px; background: #fff3cd; border-left: 4px solid #ff5722; text-align: center;">
						<h3 style="margin: 0 0 10px; color: #ff5722;">%s</h3>
						<p style="margin: 0 0 15px; color: #666;">%s</p>
						<a href="%s" target="_blank" class="elementor-button elementor-button-success">%s</a>
					</div>',
					esc_html__( 'Pro Widget', 'magical-products-display' ),
					esc_html__( 'This widget is available in the Pro version of Magical Shop Builder.', 'magical-products-display' ),
					esc_url( 'https://wpthemespace.com/product/magical-shop-builder/#pricing' ),
					esc_html__( 'Upgrade to Pro', 'magical-products-display' )
				),
				'content_classes' => 'mpd-pro-widget-notice',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function render() {
		// Check if pro widget without pro.
		if ( $this->is_pro_widget && ! $this->is_pro() ) {
			$this->render_pro_required_message();
			return;
		}

		// Check WooCommerce.
		if ( ! $this->is_woocommerce_active() ) {
			$this->render_woocommerce_required_message();
			return;
		}

		// Get settings.
		$settings = $this->get_settings_for_display();

		// Render widget content.
		$this->render_widget( $settings );
	}

	/**
	 * Render widget content.
	 *
	 * Override in child classes.
	 *
	 * @since 2.0.0
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	protected function render_widget( $settings ) {
		// Override in child classes.
		echo '<p>' . esc_html__( 'Widget output goes here.', 'magical-products-display' ) . '</p>';
	}

	/**
	 * Render pro required message.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function render_pro_required_message() {
		printf(
			'<div class="mpd-pro-required-message" style="padding: 40px; background: #f8f8f8; border: 1px dashed #ddd; text-align: center;">
				<h3 style="margin: 0 0 10px;">%s</h3>
				<p style="margin: 0 0 15px; color: #666;">%s</p>
				<a href="%s" target="_blank" class="button button-primary">%s</a>
			</div>',
			esc_html__( 'Pro Widget', 'magical-products-display' ),
			esc_html__( 'This widget requires the Pro version.', 'magical-products-display' ),
			esc_url( 'https://wpthemespace.com/product/magical-shop-builder/#pricing' ),
			esc_html__( 'Upgrade to Pro', 'magical-products-display' )
		);
	}

	/**
	 * Render WooCommerce required message.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function render_woocommerce_required_message() {
		printf(
			'<div class="mpd-woocommerce-required-message" style="padding: 40px; background: #fff3cd; border: 1px solid #ffc107; text-align: center;">
				<p style="margin: 0; color: #856404;">%s</p>
			</div>',
			esc_html__( 'WooCommerce is required for this widget.', 'magical-products-display' )
		);
	}

	/**
	 * Render WooCommerce required notice.
	 *
	 * Alias for render_woocommerce_required_message() for backward compatibility.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function render_wc_required_notice() {
		$this->render_woocommerce_required_message();
	}

	/**
	 * Render editor placeholder.
	 *
	 * Shows a placeholder in the editor when there's no content.
	 * Also shows preview product selection guidance.
	 *
	 * @since 2.0.0
	 *
	 * @param string $title   Placeholder title.
	 * @param string $message Placeholder message.
	 * @return void
	 */
	protected function render_editor_placeholder( $title = '', $message = '' ) {
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return;
		}

		$title   = $title ?: $this->get_title();
		$message = $message ?: __( 'No content to display. Please check your settings.', 'magical-products-display' );

		// Get preview product guidance.
		$preview_info = $this->get_preview_product_guidance();

		printf(
			'<div class="mpd-editor-placeholder" style="padding: 30px; background: linear-gradient(135deg, #f5f7fa 0%%, #e4e8ec 100%%); border: 1px dashed #c5ccd3; text-align: center; border-radius: 8px;">
				<div style="font-size: 36px; color: #9ea7b3; margin-bottom: 15px;">
					<i class="%s"></i>
				</div>
				<h4 style="margin: 0 0 10px; color: #2d3748; font-size: 16px;">%s</h4>
				<p style="margin: 0 0 15px; color: #666; font-size: 13px;">%s</p>
				%s
			</div>',
			esc_attr( $this->get_icon() ),
			esc_html( $title ),
			esc_html( $message ),
			$preview_info
		);
	}

	/**
	 * Get preview product guidance HTML.
	 *
	 * @since 2.0.0
	 *
	 * @return string HTML for preview guidance.
	 */
	protected function get_preview_product_guidance() {
		if ( ! $this->is_woocommerce_active() ) {
			return '<p style="margin: 0; padding: 10px; background: #fff3cd; border-radius: 4px; font-size: 12px; color: #856404;">' . 
				esc_html__( 'WooCommerce is required for this widget.', 'magical-products-display' ) . 
			'</p>';
		}

		// Check if any products exist.
		$products = wc_get_products( array( 'status' => 'publish', 'limit' => 1 ) );
		if ( empty( $products ) ) {
			return '<p style="margin: 0; padding: 10px; background: #fff3cd; border-radius: 4px; font-size: 12px; color: #856404;">' . 
				esc_html__( 'No products found. Please add products to WooCommerce first.', 'magical-products-display' ) . 
			'</p>';
		}

		return '<p style="margin: 0; padding: 10px; background: #e8f4fd; border-radius: 4px; font-size: 12px; color: #0c5460;">' . 
			'<strong>💡 ' . esc_html__( 'Tip:', 'magical-products-display' ) . '</strong> ' .
			sprintf(
				/* translators: %s: settings location */
				esc_html__( 'Go to %s to select a preview product and see live data.', 'magical-products-display' ),
				'<strong>' . esc_html__( 'Page Settings → Preview Settings', 'magical-products-display' ) . '</strong>'
			) . 
		'</p>';
	}

	/**
	 * Add common typography controls.
	 *
	 * @since 2.0.0
	 *
	 * @param string $id       Control ID prefix.
	 * @param string $selector CSS selector.
	 * @param string $label    Control label.
	 * @return void
	 */
	protected function add_typography_controls( $id, $selector, $label = '' ) {
		$label = $label ?: __( 'Typography', 'magical-products-display' );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => $id . '_typography',
				'label'    => $label,
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);
	}

	/**
	 * Add common color controls.
	 *
	 * @since 2.0.0
	 *
	 * @param string $id       Control ID.
	 * @param string $label    Control label.
	 * @param string $selector CSS selector.
	 * @param string $property CSS property (default: color).
	 * @return void
	 */
	protected function add_color_control( $id, $label, $selector, $property = 'color' ) {
		$this->add_control(
			$id,
			array(
				'label'     => $label,
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $selector => $property . ': {{VALUE}};',
				),
			)
		);
	}

	/**
	 * Add common spacing controls.
	 *
	 * @since 2.0.0
	 *
	 * @param string $id       Control ID.
	 * @param string $label    Control label.
	 * @param string $selector CSS selector.
	 * @param string $property CSS property (margin or padding).
	 * @return void
	 */
	protected function add_spacing_control( $id, $label, $selector, $property = 'margin' ) {
		$this->add_responsive_control(
			$id,
			array(
				'label'      => $label,
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => $property . ': {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Add common border controls.
	 *
	 * @since 2.0.0
	 *
	 * @param string $id       Control ID prefix.
	 * @param string $selector CSS selector.
	 * @return void
	 */
	protected function add_border_controls( $id, $selector ) {
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => $id . '_border',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);

		$this->add_responsive_control(
			$id . '_border_radius',
			array(
				'label'      => __( 'Border Radius', 'magical-products-display' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Add common box shadow controls.
	 *
	 * @since 2.0.0
	 *
	 * @param string $id       Control ID prefix.
	 * @param string $selector CSS selector.
	 * @return void
	 */
	protected function add_box_shadow_controls( $id, $selector ) {
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => $id . '_box_shadow',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);
	}

	/**
	 * Add common background controls.
	 *
	 * @since 2.0.0
	 *
	 * @param string $id       Control ID prefix.
	 * @param string $selector CSS selector.
	 * @return void
	 */
	protected function add_background_controls( $id, $selector ) {
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'     => $id . '_background',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);
	}

	/**
	 * Get responsive breakpoints.
	 *
	 * @since 2.0.0
	 *
	 * @return array Breakpoints configuration.
	 */
	protected function get_responsive_breakpoints() {
		return array(
			'desktop' => '',
			'tablet'  => '_tablet',
			'mobile'  => '_mobile',
		);
	}

	/**
	 * Get setting for current device.
	 *
	 * @since 2.0.0
	 *
	 * @param array  $settings   Widget settings.
	 * @param string $key        Setting key.
	 * @param mixed  $default    Default value.
	 * @return mixed Setting value.
	 */
	protected function get_responsive_setting( $settings, $key, $default = '' ) {
		// Check for responsive values.
		$device = \Elementor\Plugin::$instance->breakpoints->get_current_device();

		if ( 'desktop' !== $device && isset( $settings[ $key . '_' . $device ] ) && '' !== $settings[ $key . '_' . $device ] ) {
			return $settings[ $key . '_' . $device ];
		}

		return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
	}
}
