<?php
/**
 * Magical Shop Builder — Admin Notices
 *
 * Pro upgrade / sales notice (white, animated, coupon, AJAX dismiss, 15-day reshow)
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =========================================================================
 * Pro Sales Notice — white, animated, coupon, urgency, AJAX dismiss
 * ========================================================================= */

if ( ! function_exists( 'mpd_display_pro_sales_notice' ) ) {
function mpd_display_pro_sales_notice() {

	// Skip for Pro users.
	if ( 'yes' === get_option( 'mgppro_is_active', 'no' ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Wait 5 days after install.
	$install_date = get_option( 'mpd_install_date' );
	if ( ! empty( $install_date ) ) {
		$days = round( ( time() - strtotime( $install_date ) ) / DAY_IN_SECONDS );
		if ( $days < 5 ) {
			return;
		}
	}

	// Re-show after 15 days (v2 notice).
	$user_id        = get_current_user_id();
	$dismissed_date = get_user_meta( $user_id, 'mpd_pro_notice_dismissed_v2', true );
	if ( empty( $dismissed_date ) ) {
		$dismissed_date = get_option( 'mpd_pro_notice_dismissed_v2' );
	}
	if ( ! empty( $dismissed_date ) ) {
		$days = round( ( time() - strtotime( $dismissed_date ) ) / DAY_IN_SECONDS );
		if ( $days < 15 ) {
			return;
		}
	}

	wp_enqueue_style( 'admin-info-style' );

	$pro_pricing = 'https://wpthemespace.com/product/magical-products-display-pro/#pricing-mpd';
	$pro_buy     = 'https://wpthemespace.com/product/magical-products-display-pro/?add-to-cart=9177';
	$nonce       = wp_create_nonce( 'mpd_dismiss_pro_notice' );
	?>
	<div class="notice mpd-pro-notice" data-nonce="<?php echo esc_attr( $nonce ); ?>">
		<div class="mpd-pro-notice__shimmer"></div>

		<div class="mpd-pro-notice__inner">
			<!-- Left: badge + headline -->
			<div class="mpd-pro-notice__main">
				<div class="mpd-pro-notice__badge"><?php esc_html_e( 'PRO', 'magical-products-display' ); ?></div>
				<div class="mpd-pro-notice__text">
					<h3><?php esc_html_e( 'Unlock the Full Power of Magical Shop Builder', 'magical-products-display' ); ?></h3>
					<p><?php esc_html_e( 'Build high-converting Funnels, One-Page Checkout, Order Bumps, Cart Drawer, Shop, Archive & Thank You pages with Elementor. 50+ premium widgets & Pro features to skyrocket your sales.', 'magical-products-display' ); ?></p>
				</div>
			</div>

			<!-- Features grid -->
			<div class="mpd-pro-notice__features">
				<span>✦ <?php esc_html_e( 'One-Page Checkout & Funnels', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'In-Checkout Order Bumps', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Interactive Cart Drawer', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Free Shipping Progress Bar', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Sticky Add to Cart Bar', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Custom Shop & Cart Builder', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Thank You Page Builder', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Advanced Filters & Wishlist', 'magical-products-display' ); ?></span>
			</div>

			<!-- Bottom: coupon + CTA -->
			<div class="mpd-pro-notice__action-row">
				<div class="mpd-pro-notice__coupon">
					<span class="mpd-pro-notice__coupon-tag"><?php esc_html_e( 'LIMITED OFFER', 'magical-products-display' ); ?></span>
					<span class="mpd-pro-notice__coupon-text">
						<?php
						echo wp_kses(
							sprintf(
								/* translators: 1: discount percentage, 2: coupon code */
								__( '%1$s OFF for the first 100 customers — use coupon %2$s at checkout!', 'magical-products-display' ),
								'<strong>30%</strong>',
								'<code class="mpd-pro-notice__code">msp100</code>'
							),
							array(
								'strong' => array(),
								'code'   => array(
									'class' => array(),
								),
							)
						);
						?>
					</span>
				</div>
				<div class="mpd-pro-notice__ctas">
					<a href="<?php echo esc_url( $pro_buy ); ?>" target="_blank" class="mpd-pro-notice__btn mpd-pro-notice__btn--buy">
						<?php esc_html_e( 'Upgrade Now — 30% Off', 'magical-products-display' ); ?>
					</a>
					<a href="<?php echo esc_url( $pro_pricing ); ?>" target="_blank" class="mpd-pro-notice__btn mpd-pro-notice__btn--plan">
						<?php esc_html_e( 'See All Plans', 'magical-products-display' ); ?>
					</a>
				</div>
			</div>
		</div>

		<button type="button" class="mpd-pro-notice__close" aria-label="<?php esc_attr_e( 'Dismiss', 'magical-products-display' ); ?>">&times;</button>
	</div>

	<script>
	jQuery(function($){
		var $n=$('.mpd-pro-notice');
		$n.on('click','.mpd-pro-notice__close',function(){
			$.post(ajaxurl,{action:'mpd_dismiss_pro_notice',nonce:$n.data('nonce')});
			$n.addClass('mpd-pro-notice--out');
			setTimeout(function(){$n.remove()},400);
		});
	});
	</script>
	<?php
}
add_action( 'admin_notices', 'mpd_display_pro_sales_notice' );
}

/**
 * AJAX: dismiss pro notice.
 */
if ( ! function_exists( 'mpd_ajax_dismiss_pro_notice' ) ) {
function mpd_ajax_dismiss_pro_notice() {
	check_ajax_referer( 'mpd_dismiss_pro_notice', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error();
	}

	$now     = gmdate( 'Y-m-d H:i:s' );
	$user_id = get_current_user_id();
	if ( $user_id ) {
		update_user_meta( $user_id, 'mpd_pro_notice_dismissed_v2', $now );
	}
	update_option( 'mpd_pro_notice_dismissed_v2', $now );
	wp_send_json_success();
}
add_action( 'wp_ajax_mpd_dismiss_pro_notice', 'mpd_ajax_dismiss_pro_notice' );
}
