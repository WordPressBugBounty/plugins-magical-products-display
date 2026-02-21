<?php
/**
 * Header-Footer Template for MPD Templates
 *
 * Renders MPD template content with theme header and footer.
 * Used for Full Width and Header-Footer page template settings.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure Elementor frontend is initialized.
if ( class_exists( '\Elementor\Plugin' ) ) {
	\Elementor\Plugin::$instance->frontend->add_content_filter();
	\Elementor\Plugin::$instance->frontend->add_body_class( 'elementor-template-full-width' );
}

get_header();
?>

<div id="mpd-template-content" class="mpd-header-footer-template">
	<?php
	/**
	 * Fires before template content is rendered.
	 *
	 * @since 2.0.0
	 */
	do_action( 'mpd_before_render_template' );

	/**
	 * Render the template content.
	 *
	 * @since 2.0.0
	 */
	do_action( 'mpd_render_template' );

	/**
	 * Fires after template content is rendered.
	 *
	 * @since 2.0.0
	 */
	do_action( 'mpd_after_render_template' );
	?>
</div>

<?php
get_footer();
