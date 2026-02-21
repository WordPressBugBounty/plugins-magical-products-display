<?php
/**
 * Canvas Template for MPD Templates
 *
 * Fallback canvas template when Elementor's is not available.
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
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class( array( 'mpd-canvas-template', 'woocommerce-page' ) ); ?>>
<?php
wp_body_open();

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

wp_footer();
?>
</body>
</html>
