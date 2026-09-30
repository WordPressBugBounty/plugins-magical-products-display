/**
 * Off-Canvas Sliding Cart Drawer JavaScript
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

(function ($) {
	'use strict';

	var MPDCartDrawer = {
		isOpen: false,
		updateTimeout: null,

		init: function () {
			this.bindEvents();
		},

		getContainer: function () {
			return $('#mpd-cart-drawer');
		},

		getContentWrap: function () {
			return $('#mpd-cart-drawer .mpd-cart-drawer-content-wrap');
		},

		bindEvents: function () {
			var self = this;

			// Open drawer triggers (Header Cart, Mini Cart, custom toggle elements).
			$(document).on('click', '[data-mpd-cart-toggle="drawer"], .mpd-open-cart-drawer', function (e) {
				e.preventDefault();
				self.open();
			});

			// Close triggers.
			$(document).on('click', '[data-mpd-cart-close]', function (e) {
				e.preventDefault();
				self.close();
			});

			// Close on ESC key.
			$(document).on('keydown', function (e) {
				if (27 === e.keyCode && self.isOpen) {
					self.close();
				}
			});

			// Auto open on native WooCommerce added_to_cart event (if enabled).
			$(document.body).on('added_to_cart', function () {
				if (typeof mpdCartDrawer !== 'undefined' && (mpdCartDrawer.autoOpen === false || mpdCartDrawer.autoOpen === '0' || mpdCartDrawer.autoOpen === 0)) {
					return;
				}
				if ($('[data-mpd-drawer-auto-open="no"]').length > 0) {
					return;
				}
				self.open();
			});

			// Quantity Plus.
			$(document).on('click', '#mpd-cart-drawer .mpd-qty-plus', function (e) {
				e.preventDefault();
				var $spinner = $(this).closest('.mpd-cart-drawer-qty-spinner');
				var $input = $spinner.find('.mpd-qty-input');
				var current = parseInt($input.val(), 10) || 1;
				var max = parseInt($input.attr('max'), 10);

				if (!isNaN(max) && current >= max) {
					return;
				}

				var next = current + 1;
				$input.val(next);

				var itemKey = $spinner.attr('data-cart-item-key') || $spinner.data('cart-item-key');
				self.queueQuantityUpdate(itemKey, next, $(this).closest('.mpd-cart-drawer-item'));
			});

			// Quantity Minus.
			$(document).on('click', '#mpd-cart-drawer .mpd-qty-minus', function (e) {
				e.preventDefault();
				var $spinner = $(this).closest('.mpd-cart-drawer-qty-spinner');
				var $input = $spinner.find('.mpd-qty-input');
				var current = parseInt($input.val(), 10) || 1;
				var min = parseInt($input.attr('min'), 10) || 1;
				var itemKey = $spinner.attr('data-cart-item-key') || $spinner.data('cart-item-key');

				if (current <= min) {
					self.removeItem(itemKey, $(this).closest('.mpd-cart-drawer-item'));
					return;
				}

				var next = current - 1;
				$input.val(next);
				self.queueQuantityUpdate(itemKey, next, $(this).closest('.mpd-cart-drawer-item'));
			});

			// Direct input change.
			$(document).on('change', '#mpd-cart-drawer .mpd-qty-input', function () {
				var $spinner = $(this).closest('.mpd-cart-drawer-qty-spinner');
				var val = parseInt($(this).val(), 10);
				var min = parseInt($(this).attr('min'), 10) || 1;
				var max = parseInt($(this).attr('max'), 10);

				if (isNaN(val) || val < min) {
					val = min;
				} else if (!isNaN(max) && val > max) {
					val = max;
				}

				$(this).val(val);
				var itemKey = $spinner.attr('data-cart-item-key') || $spinner.data('cart-item-key');
				self.queueQuantityUpdate(itemKey, val, $(this).closest('.mpd-cart-drawer-item'));
			});

			// Item Remove.
			$(document).on('click', '#mpd-cart-drawer .mpd-cart-drawer-item-remove', function (e) {
				e.preventDefault();
				var itemKey = $(this).attr('data-cart-item-key') || $(this).data('cart-item-key');
				var $item = $(this).closest('.mpd-cart-drawer-item');
				self.removeItem(itemKey, $item);
			});

			// Coupon apply button.
			$(document).on('click', '#mpd-cart-drawer .mpd-cart-drawer-apply-coupon', function (e) {
				e.preventDefault();
				self.applyCoupon();
			});

			// Coupon input Enter key.
			$(document).on('keydown', '#mpd-cart-drawer .mpd-cart-drawer-coupon-input', function (e) {
				if (13 === e.keyCode) {
					e.preventDefault();
					self.applyCoupon();
				}
			});

			// Remove coupon tag click.
			$(document).on('click', '#mpd-cart-drawer .mpd-cart-drawer-remove-coupon', function (e) {
				e.preventDefault();
				var couponCode = $(this).attr('data-coupon') || $(this).data('coupon');
				self.removeCoupon(couponCode);
			});
		},

		open: function () {
			var $container = this.getContainer();
			if (!$container.length) {
				return;
			}

			this.isOpen = true;
			$container.addClass('is-open mpd-cart-drawer--open').attr('aria-hidden', 'false');
			$('body').addClass('mpd-cart-drawer-active');
		},

		close: function () {
			var $container = this.getContainer();
			if (!$container.length) {
				return;
			}

			this.isOpen = false;
			$container.removeClass('is-open mpd-cart-drawer--open').attr('aria-hidden', 'true');
			$('body').removeClass('mpd-cart-drawer-active');
		},

		queueQuantityUpdate: function (itemKey, quantity, $item) {
			var self = this;
			clearTimeout(this.updateTimeout);

			if ($item && $item.length) {
				$item.addClass('is-updating');
			}

			this.updateTimeout = setTimeout(function () {
				self.sendQuantityUpdate(itemKey, quantity, $item);
			}, 300);
		},

		sendQuantityUpdate: function (itemKey, quantity, $item) {
			var self = this;
			var params = window.mpdCartDrawer || {};
			var ajaxUrl = params.ajaxUrl || (window.woocommerce_params ? window.woocommerce_params.ajax_url : '/wp-admin/admin-ajax.php');
			var nonce = params.nonce || '';

			if (!itemKey) {
				if ($item && $item.length) {
					$item.removeClass('is-updating').css('opacity', '').css('pointer-events', '');
				}
				return;
			}

			$.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: {
					action: 'mpd_drawer_update_quantity',
					nonce: nonce,
					cart_item_key: itemKey,
					quantity: quantity
				},
				success: function (res) {
					if (res && res.success && res.data) {
						if (res.data.drawer_html) {
							self.getContentWrap().html(res.data.drawer_html);
						}
						if (typeof res.data.count !== 'undefined') {
							$('#mpd-cart-drawer .mpd-cart-drawer-count-badge, .mpd-mini-cart-counter, .cart-customlocation .count').text(res.data.count);
						}
						// Refresh WC fragments across the page if provided.
						if (res.data.fragments) {
							$.each(res.data.fragments, function (key, value) {
								if (key !== '.mpd-cart-drawer-content-wrap') {
									$(key).replaceWith(value);
								}
							});
						}
						$(document.body).trigger('added_to_cart', [res.data.fragments, res.data.cart_hash]);
					}
				},
				error: function (xhr, status, error) {
					console.error('MPD Cart Drawer quantity update error:', status, error);
				},
				complete: function () {
					if ($item && $item.length) {
						$item.removeClass('is-updating').css('opacity', '').css('pointer-events', '');
					}
					$('#mpd-cart-drawer .mpd-cart-drawer-item').removeClass('is-updating').css('opacity', '').css('pointer-events', '');
				}
			});
		},

		removeItem: function (itemKey, $item) {
			var self = this;
			var params = window.mpdCartDrawer || {};
			var ajaxUrl = params.ajaxUrl || (window.woocommerce_params ? window.woocommerce_params.ajax_url : '/wp-admin/admin-ajax.php');
			var nonce = params.nonce || '';

			if (!itemKey) {
				return;
			}

			if ($item && $item.length) {
				$item.addClass('is-updating').css('opacity', '0.3').css('pointer-events', 'none');
			}

			$.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: {
					action: 'mpd_drawer_remove_item',
					nonce: nonce,
					cart_item_key: itemKey
				},
				success: function (res) {
					if (res && res.success && res.data) {
						if (res.data.drawer_html) {
							self.getContentWrap().html(res.data.drawer_html);
						}
						if (typeof res.data.count !== 'undefined') {
							$('#mpd-cart-drawer .mpd-cart-drawer-count-badge, .mpd-mini-cart-counter, .cart-customlocation .count').text(res.data.count);
						}
						if (res.data.fragments) {
							$.each(res.data.fragments, function (key, value) {
								if (key !== '.mpd-cart-drawer-content-wrap') {
									$(key).replaceWith(value);
								}
							});
						}
						$(document.body).trigger('removed_from_cart', [res.data.fragments, res.data.cart_hash]);
					}
				},
				error: function (xhr, status, error) {
					console.error('MPD Cart Drawer remove item error:', status, error);
				},
				complete: function () {
					if ($item && $item.length) {
						$item.removeClass('is-updating').css('opacity', '').css('pointer-events', '');
					}
					$('#mpd-cart-drawer .mpd-cart-drawer-item').removeClass('is-updating').css('opacity', '').css('pointer-events', '');
				}
			});
		},

		applyCoupon: function () {
			var self = this;
			var $wrap = $('#mpd-cart-drawer .mpd-cart-drawer-coupon-wrap');
			var $input = $wrap.find('.mpd-cart-drawer-coupon-input');
			var $btn = $wrap.find('.mpd-cart-drawer-apply-coupon');
			var $msg = $wrap.find('.mpd-cart-drawer-coupon-msg');
			var couponCode = $.trim($input.val());

			if (!couponCode) {
				$msg.removeClass('is-success').addClass('is-error').text('Please enter a coupon code.').show();
				return;
			}

			var params = window.mpdCartDrawer || {};
			var ajaxUrl = params.ajaxUrl || (window.woocommerce_params ? window.woocommerce_params.ajax_url : '/wp-admin/admin-ajax.php');
			var nonce = params.nonce || '';

			$btn.prop('disabled', true).find('.mpd-coupon-btn-text').hide();
			$btn.find('.mpd-coupon-spinner').show();
			$msg.hide().empty();

			$.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: {
					action: 'mpd_drawer_apply_coupon',
					nonce: nonce,
					coupon_code: couponCode
				},
				success: function (res) {
					if (res && res.success && res.data) {
						if (res.data.drawer_html) {
							self.getContentWrap().html(res.data.drawer_html);
						}
						if (res.data.fragments) {
							$.each(res.data.fragments, function (key, value) {
								if (key !== '.mpd-cart-drawer-content-wrap') {
									$(key).replaceWith(value);
								}
							});
						}
						var $newMsg = $('#mpd-cart-drawer .mpd-cart-drawer-coupon-msg');
						$newMsg.removeClass('is-error').addClass('is-success').text(res.data.message || 'Coupon applied!').show();
						$(document.body).trigger('applied_coupon_in_cart', [couponCode]);
					} else {
						var err = (res && res.data && res.data.message) ? res.data.message : 'Invalid coupon.';
						$msg.removeClass('is-success').addClass('is-error').text(err).show();
					}
				},
				error: function () {
					$msg.removeClass('is-success').addClass('is-error').text('Error applying coupon.').show();
				},
				complete: function () {
					$btn.prop('disabled', false).find('.mpd-coupon-spinner').hide();
					$btn.find('.mpd-coupon-btn-text').show();
				}
			});
		},

		removeCoupon: function (couponCode) {
			var self = this;
			if (!couponCode) {
				return;
			}

			var params = window.mpdCartDrawer || {};
			var ajaxUrl = params.ajaxUrl || (window.woocommerce_params ? window.woocommerce_params.ajax_url : '/wp-admin/admin-ajax.php');
			var nonce = params.nonce || '';

			$.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: {
					action: 'mpd_drawer_remove_coupon',
					nonce: nonce,
					coupon_code: couponCode
				},
				success: function (res) {
					if (res && res.success && res.data) {
						if (res.data.drawer_html) {
							self.getContentWrap().html(res.data.drawer_html);
						}
						if (res.data.fragments) {
							$.each(res.data.fragments, function (key, value) {
								if (key !== '.mpd-cart-drawer-content-wrap') {
									$(key).replaceWith(value);
								}
							});
						}
						var $newMsg = $('#mpd-cart-drawer .mpd-cart-drawer-coupon-msg');
						$newMsg.removeClass('is-error').addClass('is-success').text(res.data.message || 'Coupon removed.').show();
						$(document.body).trigger('removed_coupon_in_cart', [couponCode]);
					}
				}
			});
		}
	};

	$(document).ready(function () {
		MPDCartDrawer.init();
	});

	// Expose globally so other widgets or scripts can call MPDCartDrawer.open() / close().
	window.MPDCartDrawer = MPDCartDrawer;

})(jQuery);
