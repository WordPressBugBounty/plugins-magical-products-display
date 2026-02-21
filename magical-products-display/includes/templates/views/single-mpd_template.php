<?php
/**
 * Single Template for MPD Templates
 *
 * This template is used for rendering MPD templates in the frontend and Elementor preview.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;

// Check for various preview parameters.
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$mpd_elementor_preview_id = isset( $_GET['elementor-preview'] ) ? absint( $_GET['elementor-preview'] ) : 0;
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$mpd_wp_preview_id = isset( $_GET['preview_id'] ) ? absint( $_GET['preview_id'] ) : 0;
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$mpd_is_wp_preview = isset( $_GET['preview'] ) && 'true' === $_GET['preview'];

// Determine which post ID to use for preview.
$mpd_preview_post_id = 0;
if ( $mpd_elementor_preview_id ) {
	$mpd_preview_post_id = $mpd_elementor_preview_id;
} elseif ( $mpd_wp_preview_id && $mpd_is_wp_preview ) {
	$mpd_preview_post_id = $mpd_wp_preview_id;
}

// If this is a preview request, set up the post.
if ( $mpd_preview_post_id ) {
	$post = get_post( $mpd_preview_post_id );
	if ( $post ) {
		setup_postdata( $post );
	}
}

// Ensure we have a valid post.
if ( ! $post ) {
	if ( have_posts() ) {
		the_post();
	} else {
		// No post found.
		wp_die( esc_html__( 'Template not found.', 'magical-products-display' ) );
	}
}

$mpd_post_id = $post->ID;

// For WordPress native preview, get the parent post ID if this is a revision.
$mpd_content_post_id = $mpd_post_id;
if ( $mpd_is_wp_preview && wp_is_post_revision( $mpd_post_id ) ) {
	$mpd_parent_id = wp_get_post_parent_id( $mpd_post_id );
	if ( $mpd_parent_id ) {
		$mpd_content_post_id = $mpd_parent_id;
	}
}
// Also handle the case where preview_id points to the actual post (not revision).
if ( $mpd_is_wp_preview && $mpd_wp_preview_id && ! wp_is_post_revision( $mpd_wp_preview_id ) ) {
	$mpd_content_post_id = $mpd_wp_preview_id;
}

// Setup preview product for single product widgets.
// Use the parent post ID for meta lookup in case of revisions.
$mpd_preview_product_id = get_post_meta( $mpd_content_post_id, '_mpd_preview_product_id', true );
if ( ! $mpd_preview_product_id && class_exists( 'WooCommerce' ) ) {
	// Get the first product as fallback.
	$mpd_products = wc_get_products( array( 'status' => 'publish', 'limit' => 1 ) );
	if ( ! empty( $mpd_products ) ) {
		$mpd_preview_product_id = $mpd_products[0]->get_id();
	}
}

// Setup global product for widgets.
if ( $mpd_preview_product_id && class_exists( 'WooCommerce' ) ) {
	$product = wc_get_product( $mpd_preview_product_id );
}

// Get the page template setting from the parent post (not revision).
$mpd_page_template = get_post_meta( $mpd_content_post_id, '_wp_page_template', true );

// Check if any preview mode (Elementor or WordPress native).
// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$mpd_is_elementor_preview = isset( $_GET['elementor-preview'] );
$mpd_is_any_preview = $mpd_is_elementor_preview || ( $mpd_is_wp_preview && $mpd_wp_preview_id );

// Check if Elementor is active.
$mpd_has_elementor = class_exists( '\Elementor\Plugin' ) && defined( 'ELEMENTOR_PATH' );

// For Elementor preview mode ONLY, use canvas template (not WordPress native preview).
// WordPress native preview needs our custom rendering below.
if ( $mpd_has_elementor && $mpd_is_elementor_preview && 'elementor_canvas' === $mpd_page_template ) {
	$mpd_canvas_template = ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php';
	
	if ( file_exists( $mpd_canvas_template ) ) {
		require $mpd_canvas_template;
		exit;
	}
}

// For Elementor canvas template on frontend (not preview).
if ( $mpd_has_elementor && ! $mpd_is_any_preview && 'elementor_canvas' === $mpd_page_template ) {
	$mpd_canvas_template = ELEMENTOR_PATH . 'modules/page-templates/templates/canvas.php';
	
	if ( file_exists( $mpd_canvas_template ) ) {
		require $mpd_canvas_template;
		exit;
	}
}

// For Elementor header-footer template.
// For Elementor preview, use the template. For WP preview, fall through to notice.
if ( $mpd_has_elementor && 'elementor_header_footer' === $mpd_page_template ) {
	// For Elementor preview mode.
	if ( $mpd_is_elementor_preview ) {
		$mpd_header_footer_template = ELEMENTOR_PATH . 'modules/page-templates/templates/header-footer.php';
		
		if ( file_exists( $mpd_header_footer_template ) ) {
			require $mpd_header_footer_template;
			exit;
		}
	}
	
	// For frontend (not any preview).
	if ( ! $mpd_is_any_preview ) {
		$mpd_header_footer_template = ELEMENTOR_PATH . 'modules/page-templates/templates/header-footer.php';
		
		if ( file_exists( $mpd_header_footer_template ) ) {
			require $mpd_header_footer_template;
			exit;
		}
	}
	// For WP native preview, fall through to preview notice below.
}

// For Elementor Full Width template (elementor_theme).
// For Elementor preview, use the template. For WP preview, fall through to notice.
if ( $mpd_has_elementor && 'elementor_theme' === $mpd_page_template ) {
	// For Elementor preview mode.
	if ( $mpd_is_elementor_preview ) {
		$mpd_header_footer_template = ELEMENTOR_PATH . 'modules/page-templates/templates/header-footer.php';
		
		if ( file_exists( $mpd_header_footer_template ) ) {
			require $mpd_header_footer_template;
			exit;
		}
	}
	
	// For frontend (not any preview).
	if ( ! $mpd_is_any_preview ) {
		$mpd_header_footer_template = ELEMENTOR_PATH . 'modules/page-templates/templates/header-footer.php';
		
		if ( file_exists( $mpd_header_footer_template ) ) {
			require $mpd_header_footer_template;
			exit;
		}
	}
	// For WP native preview, fall through to preview notice below.
}

// For WordPress native preview, show a helpful preview page with links.
if ( $mpd_is_wp_preview && $mpd_wp_preview_id && ! $mpd_is_elementor_preview ) {
	// Get the template type.
	$mpd_template_type = get_post_meta( $mpd_content_post_id, '_mpd_template_type', true );
	
	// Get a sample product/page URL based on template type.
	$mpd_preview_url = '';
	$mpd_preview_label = '';
	
	switch ( $mpd_template_type ) {
		case 'single-product':
			if ( $mpd_preview_product_id && class_exists( 'WooCommerce' ) ) {
				$mpd_preview_url = get_permalink( $mpd_preview_product_id );
				$mpd_preview_label = __( 'View on Product Page', 'magical-products-display' );
			}
			break;
			
		case 'archive-product':
			if ( class_exists( 'WooCommerce' ) ) {
				$mpd_preview_url = wc_get_page_permalink( 'shop' );
				$mpd_preview_label = __( 'View on Shop Page', 'magical-products-display' );
			}
			break;
			
		case 'cart':
		case 'empty-cart':
			if ( class_exists( 'WooCommerce' ) ) {
				$mpd_preview_url = wc_get_cart_url();
				$mpd_preview_label = __( 'View on Cart Page', 'magical-products-display' );
			}
			break;
			
		case 'checkout':
			if ( class_exists( 'WooCommerce' ) ) {
				$mpd_preview_url = wc_get_checkout_url();
				$mpd_preview_label = __( 'View on Checkout Page', 'magical-products-display' );
			}
			break;
			
		case 'my-account':
			if ( class_exists( 'WooCommerce' ) ) {
				$mpd_preview_url = wc_get_page_permalink( 'myaccount' );
				$mpd_preview_label = __( 'View on My Account Page', 'magical-products-display' );
			}
			break;
			
		case 'thankyou':
			$mpd_preview_label = __( 'Thank You page preview requires a completed order', 'magical-products-display' );
			break;
	}
	
	// Get Elementor editor URL.
	$mpd_elementor_edit_url = admin_url( 'post.php?post=' . $mpd_content_post_id . '&action=elementor' );
	
	// Show the preview notice page.
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo esc_html( get_the_title( $mpd_content_post_id ) ); ?> - <?php esc_html_e( 'Preview', 'magical-products-display' ); ?></title>
		<style>
			* { box-sizing: border-box; margin: 0; padding: 0; }
			body { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
			.mpd-preview-notice { background: #fff; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 600px; width: 100%; padding: 40px; text-align: center; }
			.mpd-preview-notice .icon { font-size: 64px; margin-bottom: 20px; }
			.mpd-preview-notice h1 { font-size: 24px; color: #1e1e1e; margin-bottom: 10px; }
			.mpd-preview-notice .template-name { color: #667eea; font-weight: 600; }
			.mpd-preview-notice p { color: #666; font-size: 16px; line-height: 1.6; margin-bottom: 30px; }
			.mpd-preview-notice .info-box { background: #f0f0f1; border-radius: 8px; padding: 20px; margin-bottom: 30px; text-align: left; }
			.mpd-preview-notice .info-box h3 { font-size: 14px; color: #1e1e1e; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
			.mpd-preview-notice .info-box ul { list-style: none; }
			.mpd-preview-notice .info-box li { padding: 8px 0; border-bottom: 1px solid #ddd; font-size: 14px; color: #666; }
			.mpd-preview-notice .info-box li:last-child { border-bottom: none; }
			.mpd-preview-notice .info-box li strong { color: #1e1e1e; }
			.mpd-preview-notice .buttons { display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; }
			.mpd-preview-notice .btn { display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; transition: all 0.2s; }
			.mpd-preview-notice .btn-primary { background: #667eea; color: #fff; }
			.mpd-preview-notice .btn-primary:hover { background: #5a6fd6; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102,126,234,0.4); }
			.mpd-preview-notice .btn-secondary { background: #f0f0f1; color: #1e1e1e; }
			.mpd-preview-notice .btn-secondary:hover { background: #e0e0e1; }
			.mpd-preview-notice .note { margin-top: 25px; font-size: 13px; color: #999; }
		</style>
	</head>
	<body>
		<div class="mpd-preview-notice">
			<div class="icon">🎨</div>
			<h1><?php esc_html_e( 'Template Preview', 'magical-products-display' ); ?></h1>
			<p>
				<?php 
				printf(
					/* translators: %s: template name */
					esc_html__( 'You are previewing the template: %s', 'magical-products-display' ),
					'<span class="template-name">' . esc_html( get_the_title( $mpd_content_post_id ) ) . '</span>'
				);
				?>
			</p>
			
			<div class="info-box">
				<h3><?php esc_html_e( 'Template Details', 'magical-products-display' ); ?></h3>
				<ul>
					<li><strong><?php esc_html_e( 'Type:', 'magical-products-display' ); ?></strong> <?php echo esc_html( ucwords( str_replace( '-', ' ', $mpd_template_type ?: 'Not set' ) ) ); ?></li>
					<li><strong><?php esc_html_e( 'Status:', 'magical-products-display' ); ?></strong> <?php echo esc_html( ucfirst( get_post_status( $mpd_content_post_id ) ) ); ?></li>
					<?php if ( $mpd_preview_product_id && 'single-product' === $mpd_template_type ) : ?>
					<li><strong><?php esc_html_e( 'Preview Product:', 'magical-products-display' ); ?></strong> <?php echo esc_html( get_the_title( $mpd_preview_product_id ) ); ?></li>
					<?php endif; ?>
				</ul>
			</div>
			
			<div class="buttons">
				<a href="<?php echo esc_url( $mpd_elementor_edit_url ); ?>" class="btn btn-primary">
					<span>✏️</span> <?php esc_html_e( 'Edit with Elementor', 'magical-products-display' ); ?>
				</a>
				<?php if ( $mpd_preview_url ) : ?>
				<a href="<?php echo esc_url( $mpd_preview_url ); ?>" class="btn btn-secondary" target="_blank">
					<span>👁️</span> <?php echo esc_html( $mpd_preview_label ); ?>
				</a>
				<?php elseif ( $mpd_preview_label ) : ?>
				<span class="btn btn-secondary" style="opacity: 0.6; cursor: not-allowed;">
					<span>ℹ️</span> <?php echo esc_html( $mpd_preview_label ); ?>
				</span>
				<?php endif; ?>
			</div>
			
			<p class="note">
				<?php esc_html_e( 'Shop Builder templates are rendered on actual WooCommerce pages. Use "Edit with Elementor" for the full editing experience, or view on the actual page to see your template in action.', 'magical-products-display' ); ?>
			</p>
		</div>
	</body>
	</html>
	<?php
	exit;
}

// Fallback: Use a simple HTML5 template.
// This is used for WordPress native preview and when no specific template is set.

// Ensure Elementor frontend is initialized for preview.
if ( $mpd_has_elementor && $mpd_is_any_preview ) {
	$mpd_elementor = \Elementor\Plugin::instance();
	
	// Force Elementor to register styles and scripts.
	if ( ! did_action( 'elementor/frontend/before_enqueue_styles' ) ) {
		$mpd_elementor->frontend->enqueue_styles();
	}
	if ( ! did_action( 'elementor/frontend/before_enqueue_scripts' ) ) {
		$mpd_elementor->frontend->enqueue_scripts();
	}
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
wp_body_open();

// Output Elementor content.
if ( class_exists( '\Elementor\Plugin' ) ) {
	$mpd_elementor = \Elementor\Plugin::instance();
	
	// Check if this post was built with Elementor. Use the parent post ID for content in case of revision.
	if ( $mpd_elementor->documents->get( $mpd_content_post_id ) ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $mpd_elementor->frontend->get_builder_content_for_display( $mpd_content_post_id, true );
	} else {
		// Fallback: try the original post_id.
		if ( $mpd_elementor->documents->get( $mpd_post_id ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $mpd_elementor->frontend->get_builder_content_for_display( $mpd_post_id, true );
		} else {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo apply_filters( 'the_content', $post->post_content );
		}
	}
} else {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo apply_filters( 'the_content', $post->post_content );
}

wp_footer();
?>
</body>
</html>
