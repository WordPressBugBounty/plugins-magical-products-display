<?php
/**
 * Magical Shop Builder — Admin Notices
 *
 * 1. New-menu pointer notice  (Elementor-style "new home" banner)
 * 2. Pro upgrade / sales notice (white, animated, coupon, AJAX dismiss, 15-day reshow)
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =========================================================================
 * 1. Menu Tooltip Notice — anchored to sidebar menu item (like Elementor)
 * ========================================================================= */

if ( ! function_exists( 'mpd_display_menu_tooltip' ) ) {
function mpd_display_menu_tooltip() {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$current_version = defined( 'MAGICAL_PRODUCTS_DISPLAY_VERSION' )
		? MAGICAL_PRODUCTS_DISPLAY_VERSION
		: '2.0.0';

	// Already dismissed for this version.
	if ( get_option( 'mpd_menu_notice_version', '' ) === $current_version ) {
		return;
	}

	wp_enqueue_style( 'admin-info-style' );

	$dashboard_url = admin_url( 'admin.php?page=magical-shop-builder' );
	$nonce         = wp_create_nonce( 'mpd_dismiss_menu_notice' );
	?>
	<div id="mpd-menu-tooltip" class="mpd-menu-tooltip" style="display:none" data-nonce="<?php echo esc_attr( $nonce ); ?>">
		<div class="mpd-menu-tooltip__arrow"></div>
		<div class="mpd-menu-tooltip__header">
			<span class="mpd-menu-tooltip__icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
			</span>
			<strong><?php esc_html_e( 'Magical Shop Builder has a new home', 'magical-products-display' ); ?></strong>
		</div>
		<div class="mpd-menu-tooltip__body">
			<p>
				<?php esc_html_e( 'Magical Products Display is now Magical Shop Builder. Build and grow your store with everything you need in one place.', 'magical-products-display' ); ?>
				<a href="<?php echo esc_url( $dashboard_url ); ?>"><?php esc_html_e( 'Learn more', 'magical-products-display' ); ?></a>
			</p>
			<a href="<?php echo esc_url( $dashboard_url ); ?>" class="mpd-menu-tooltip__gotit"><?php esc_html_e( 'Got it', 'magical-products-display' ); ?></a>
		</div>
		<button type="button" class="mpd-menu-tooltip__dismiss" aria-label="<?php esc_attr_e( 'Dismiss', 'magical-products-display' ); ?>">
			<span class="dashicons dashicons-dismiss"></span> <?php esc_html_e( 'Dismiss', 'magical-products-display' ); ?>
		</button>
	</div>

	<script>
	jQuery(function($){
		var $tooltip = $('#mpd-menu-tooltip');
		var $menuItem = $('#toplevel_page_magical-shop-builder');

		if (!$menuItem.length || !$tooltip.length) return;

		// Position the tooltip next to the menu item using getBoundingClientRect
		// so it works correctly with position:fixed and scrolling
		function positionTooltip() {
			var rect = $menuItem[0].getBoundingClientRect();
			var sidebarWidth = $('#adminmenuback').width() || $menuItem.closest('#adminmenuwrap').width() || 160;
			var tooltipHeight = $tooltip.outerHeight();
			var viewportHeight = $(window).height();
			var topPos = rect.top + Math.round(rect.height / 2) - 50;

			// Keep tooltip within viewport bounds
			if (topPos + tooltipHeight > viewportHeight - 10) {
				topPos = viewportHeight - tooltipHeight - 10;
			}
			if (topPos < 10) {
				topPos = 10;
			}

			$tooltip.css({
				top: topPos,
				left: sidebarWidth + 12
			}).show();
		}

		// Small delay so sidebar is fully rendered
		setTimeout(positionTooltip, 300);
		$(window).on('resize.mpdTooltip', positionTooltip);
		$('#adminmenuwrap, window').on('scroll.mpdTooltip', positionTooltip);
		$(document).on('scroll.mpdTooltip', positionTooltip);

		// Dismiss
		$tooltip.on('click', '.mpd-menu-tooltip__dismiss, .mpd-menu-tooltip__gotit', function(e) {
			e.preventDefault();
			$.post(ajaxurl, {action:'mpd_dismiss_menu_notice', nonce: $tooltip.data('nonce')});
			$tooltip.addClass('mpd-menu-tooltip--out');
			$(window).off('resize.mpdTooltip');
			$('#adminmenuwrap, window').off('scroll.mpdTooltip');
			$(document).off('scroll.mpdTooltip');
			setTimeout(function(){ $tooltip.remove(); }, 400);
		});
	});
	</script>
	<?php
}
add_action( 'admin_footer', 'mpd_display_menu_tooltip' );
}

/**
 * AJAX: dismiss menu notice.
 */
if ( ! function_exists( 'mpd_ajax_dismiss_menu_notice' ) ) {
function mpd_ajax_dismiss_menu_notice() {
	check_ajax_referer( 'mpd_dismiss_menu_notice', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error();
	}

	$ver = defined( 'MAGICAL_PRODUCTS_DISPLAY_VERSION' ) ? MAGICAL_PRODUCTS_DISPLAY_VERSION : '2.0.0';
	update_option( 'mpd_menu_notice_version', $ver );
	wp_send_json_success();
}
add_action( 'wp_ajax_mpd_dismiss_menu_notice', 'mpd_ajax_dismiss_menu_notice' );
}


/* =========================================================================
 * 2. Pro Sales Notice — white, animated, coupon, urgency, AJAX dismiss
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

	// Re-show after 15 days.
	$dismissed_date = get_option( 'mpd_pro_notice_dismissed' );
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
					<p><?php esc_html_e( 'Build custom Shop, Archive, Cart, Checkout & Thank You pages with Elementor — most widgets include Pro features. 50+ premium widgets to skyrocket your conversions.', 'magical-products-display' ); ?></p>
				</div>
			</div>

			<!-- Features grid -->
			<div class="mpd-pro-notice__features">
				<span>✦ <?php esc_html_e( 'Custom Shop & Archive Pages', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Cart & Checkout Builder', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Thank You Page Builder', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Advanced Product Filters', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Countdown & Offer Timers', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Product Hotspots', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Live Product Ticker', 'magical-products-display' ); ?></span>
				<span>✦ <?php esc_html_e( 'Compare & Wishlist', 'magical-products-display' ); ?></span>
			</div>

			<!-- Bottom: coupon + CTA -->
			<div class="mpd-pro-notice__action-row">
				<div class="mpd-pro-notice__coupon">
					<span class="mpd-pro-notice__coupon-tag"><?php esc_html_e( 'LIMITED OFFER', 'magical-products-display' ); ?></span>
					<span class="mpd-pro-notice__coupon-text">
						<?php
						printf(
							/* translators: 1: discount percentage, 2: coupon code */
							__( '%1$s OFF for the first 100 customers — use coupon %2$s at checkout!', 'magical-products-display' ),
							'<strong>30%</strong>',
							'<code class="mpd-pro-notice__code">msp100</code>'
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

	update_option( 'mpd_pro_notice_dismissed', gmdate( 'Y-m-d H:i:s' ) );
	wp_send_json_success();
}
add_action( 'wp_ajax_mpd_dismiss_pro_notice', 'mpd_ajax_dismiss_pro_notice' );
}
