<?php
/**
 * Pro Lock Trait
 *
 * Provides methods for locking pro features in widgets.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

namespace MPD\MagicalShopBuilder\Traits;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Trait Pro_Lock
 *
 * Use this trait in widgets to easily manage pro-locked features.
 *
 * @since 2.0.0
 */
trait Pro_Lock {

	/**
	 * Check if pro is active.
	 *
	 * @since 2.0.0
	 *
	 * @return bool Whether pro is active.
	 */
	protected function is_pro() {
		return 'yes' === get_option( 'mgppro_is_active', 'no' );
	}

	/**
	 * Get pro badge for labels.
	 *
	 * @since 2.0.0
	 *
	 * @return string The pro badge HTML or empty string.
	 */
	protected function get_pro_badge() {
		if ( $this->is_pro() ) {
			return '';
		}

		return sprintf(
			' <strong style="color:#ff5722;font-size:80%%">(%s)</strong>',
			esc_html__( 'Pro', 'magical-products-display' )
		);
	}

	/**
	 * Get pro notice HTML for use in control descriptions.
	 *
	 * @since 2.0.0
	 *
	 * @param string $message The message to display.
	 * @return string The pro notice HTML.
	 */
	protected function get_pro_notice( $message = '' ) {
		if ( $this->is_pro() ) {
			return '';
		}

		if ( empty( $message ) ) {
			$message = __( 'This feature requires the Pro version.', 'magical-products-display' );
		}

		return sprintf(
			'<div style="padding: 10px; background: #fff3cd; border-left: 4px solid #ff5722; margin: 5px 0;">
				<strong style="color: #ff5722;">%s</strong><br>
				<span style="color: #666;">%s</span><br>
				<a href="%s" target="_blank" style="display: inline-block; margin-top: 5px; color: #ff5722;">%s →</a>
			</div>',
			esc_html__( 'Pro Feature', 'magical-products-display' ),
			esc_html( $message ),
			esc_url( 'https://wpthemespace.com/product/magical-shop-builder/#pricing' ),
			esc_html__( 'Upgrade to Pro', 'magical-products-display' )
		);
	}

	/**
	 * Get label with pro badge.
	 *
	 * @since 2.0.0
	 *
	 * @param string $label The label text.
	 * @return string The label with or without pro badge.
	 */
	protected function pro_label( $label ) {
		return $label . $this->get_pro_badge();
	}

	/**
	 * Get locked dropdown value.
	 *
	 * @since 2.0.0
	 *
	 * @param string $pro_value  Value when pro is active.
	 * @param string $fake_value Fake value when not pro.
	 * @return string The appropriate value.
	 */
	protected function pro_value( $pro_value, $fake_value ) {
		return $this->is_pro() ? $pro_value : $fake_value;
	}

	/**
	 * Get pro switch default.
	 *
	 * @since 2.0.0
	 *
	 * @param string $default Default value for pro users.
	 * @return string The default value.
	 */
	protected function pro_default( $default = 'yes' ) {
		return $this->is_pro() ? $default : '';
	}

	/**
	 * Add pro notice control.
	 *
	 * @since 2.0.0
	 *
	 * @param string $control_id   The control ID.
	 * @param string $feature_name The feature name.
	 * @return void
	 */
	protected function add_pro_notice( $control_id, $feature_name = '' ) {
		if ( $this->is_pro() ) {
			return;
		}

		$message = empty( $feature_name )
			? __( 'This feature requires the Pro version.', 'magical-products-display' )
			: sprintf(
				/* translators: %s: feature name */
				__( '%s requires the Pro version.', 'magical-products-display' ),
				$feature_name
			);

		$this->add_control(
			$control_id,
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					'<div style="padding: 10px; background: #fff3cd; border-left: 4px solid #ff5722; margin: 10px 0;">
						<strong style="color: #ff5722;">%s</strong><br>
						<span style="color: #666;">%s</span>
						<a href="%s" target="_blank" style="display: inline-block; margin-top: 5px; color: #ff5722;">%s →</a>
					</div>',
					esc_html__( 'Pro Feature', 'magical-products-display' ),
					esc_html( $message ),
					esc_url( 'https://wpthemespace.com/product/magical-shop-builder/#pricing' ),
					esc_html__( 'Upgrade to Pro', 'magical-products-display' )
				),
				'content_classes' => 'mpd-pro-notice',
			)
		);
	}

	/**
	 * Get pro-locked options for select control.
	 *
	 * Disables options that require pro.
	 *
	 * @since 2.0.0
	 *
	 * @param array $options     Array of options.
	 * @param array $pro_options Array of option keys that require pro.
	 * @return array Modified options array.
	 */
	protected function get_pro_options( $options, $pro_options ) {
		if ( $this->is_pro() ) {
			return $options;
		}

		$modified_options = array();
		foreach ( $options as $key => $label ) {
			if ( in_array( $key, $pro_options, true ) ) {
				$modified_options[ 'disabled_' . $key ] = $label . $this->get_pro_badge();
			} else {
				$modified_options[ $key ] = $label;
			}
		}

		return $modified_options;
	}

	/**
	 * Check if a setting should be used.
	 *
	 * Returns false for pro-locked settings when not pro.
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $value The setting value.
	 * @return bool Whether to use the setting.
	 */
	protected function should_use_pro_setting( $value ) {
		if ( $this->is_pro() ) {
			return ! empty( $value ) && 'yes' === $value;
		}
		return false;
	}

	/**
	 * Render pro upgrade message in widget output.
	 *
	 * @since 2.0.0
	 *
	 * @param string $feature The feature name.
	 * @return void
	 */
	protected function render_pro_message( $feature = '' ) {
		if ( $this->is_pro() ) {
			return;
		}

		$message = empty( $feature )
			? __( 'This feature requires the Pro version.', 'magical-products-display' )
			: sprintf(
				/* translators: %s: feature name */
				__( '%s requires the Pro version.', 'magical-products-display' ),
				$feature
			);

		printf(
			'<div class="mpd-pro-required" style="padding: 20px; background: #f8f8f8; border: 1px dashed #ddd; text-align: center;">
				<p>%s</p>
				<a href="%s" target="_blank" class="button button-primary">%s</a>
			</div>',
			esc_html( $message ),
			esc_url( 'https://wpthemespace.com/product/magical-shop-builder/#pricing' ),
			esc_html__( 'Upgrade to Pro', 'magical-products-display' )
		);
	}
}
