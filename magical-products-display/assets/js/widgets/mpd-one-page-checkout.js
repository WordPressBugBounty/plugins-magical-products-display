/**
 * One-Page Direct Checkout & Order Bump Scripts
 *
 * @package Magical_Shop_Builder
 * @since   2.2.0
 */

(function ($) {
	'use strict';

	var MPD_OPC = {
		updateTimeout: null,

		isInitialized: false,

		init: function () {
			if (this.isInitialized) return;
			this.isInitialized = true;
			this.bindEvents();
		},

		bindEvents: function () {
			var self = this;

			// Quantity Plus.
			$(document).off('click.mpdOPC', '.mpd-opc-qty-plus').on('click.mpdOPC', '.mpd-opc-qty-plus', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var $wrap = $(this).closest('.mpd-opc-qty-wrap');
				var $input = $wrap.find('.mpd-opc-qty-input');
				var val = parseInt($input.val(), 10) || 1;
				var max = parseInt($input.attr('max'), 10) || 99;

				if (val < max) {
					var next = val + 1;
					$input.val(next);
					self.triggerQuantityUpdate($wrap, next);
				}
			});

			// Quantity Minus.
			$(document).off('click.mpdOPC', '.mpd-opc-qty-minus').on('click.mpdOPC', '.mpd-opc-qty-minus', function (e) {
				e.preventDefault();
				e.stopPropagation();
				var $wrap = $(this).closest('.mpd-opc-qty-wrap');
				var $input = $wrap.find('.mpd-opc-qty-input');
				var val = parseInt($input.val(), 10) || 1;
				var min = parseInt($input.attr('min'), 10) || 1;

				if (val > min) {
					var next = val - 1;
					$input.val(next);
					self.triggerQuantityUpdate($wrap, next);
				}
			});

			// Quantity direct input change.
			$(document).off('change.mpdOPC', '.mpd-opc-qty-input').on('change.mpdOPC', '.mpd-opc-qty-input', function (e) {
				e.stopPropagation();
				var $wrap = $(this).closest('.mpd-opc-qty-wrap');
				var val = parseInt($(this).val(), 10) || 1;
				if (val < 1) val = 1;
				$(this).val(val);
				self.triggerQuantityUpdate($wrap, val);
			});

			// Order Bump Checkbox Toggle.
			$(document).off('change.mpdOPC', '.mpd-opc-bump-checkbox').on('change.mpdOPC', '.mpd-opc-bump-checkbox', function (e) {
				e.stopPropagation();
				var $checkbox = $(this);
				var $bumpBox = $checkbox.closest('.mpd-opc-bump-box');
				var isChecked = $checkbox.is(':checked');
				var bumpId = $bumpBox.data('bump-id') || $bumpBox.attr('data-bump-id');
				var customPrice = $bumpBox.data('custom-price') || $bumpBox.attr('data-custom-price');
				var bumpAction = $bumpBox.data('bump-action') || 'add';
				var $container = $checkbox.closest('.mpd-opc-wrapper, .mpd-standalone-bump-wrap');
				var nonce = $container.data('nonce') || (window.mpdOPCParams ? window.mpdOPCParams.nonce : '');

				self.toggleOrderBump(bumpId, customPrice, isChecked, $bumpBox, nonce, bumpAction);
			});

			// Pro: 2-Step Modern Checkout Navigation (Step 1 -> Step 2).
			$(document).off('click.mpdOPC', '.mpd-opc-step-next-btn').on('click.mpdOPC', '.mpd-opc-step-next-btn', function (e) {
				e.preventDefault();
				var $wrapper = $(this).closest('.mpd-opc-wrapper');
				var $step1 = $wrapper.find('.mpd-opc-col-left');
				var hasError = false;

				$step1.find('.validate-required input, .validate-required select').each(function () {
					if (!$(this).val() || $(this).val().trim() === '') {
						$(this).closest('.form-row').addClass('woocommerce-invalid woocommerce-invalid-required-field');
						hasError = true;
					} else {
						$(this).closest('.form-row').removeClass('woocommerce-invalid woocommerce-invalid-required-field');
					}
				});

				if (hasError) {
					var $firstErr = $step1.find('.woocommerce-invalid').first();
					if ($firstErr.length) {
						$('html, body').animate({ scrollTop: $firstErr.offset().top - 80 }, 300);
					}
					return;
				}

				self.goToStep($wrapper, 2);
			});

			// Pro: 2-Step Navigation Return to Step 1.
			$(document).off('click.mpdOPC', '.mpd-opc-step-prev-link').on('click.mpdOPC', '.mpd-opc-step-prev-link', function (e) {
				e.preventDefault();
				var $wrapper = $(this).closest('.mpd-opc-wrapper');
				self.goToStep($wrapper, 1);
			});

			// Pro: Step Navigation Tabs Click.
			$(document).off('click.mpdOPC', '.mpd-opc-step-tab').on('click.mpdOPC', '.mpd-opc-step-tab', function (e) {
				e.preventDefault();
				var targetStep = $(this).data('step');
				var $wrapper = $(this).closest('.mpd-opc-wrapper');
				if (targetStep === 1) {
					self.goToStep($wrapper, 1);
				} else if (targetStep === 2) {
					$wrapper.find('.mpd-opc-step-next-btn').trigger('click');
				}
			});

			// Pro: Quantity Package Deals Selection.
			$(document).off('click.mpdOPC', '.mpd-opc-deal-card').on('click.mpdOPC', '.mpd-opc-deal-card', function (e) {
				var $card = $(this);
				var $wrap = $card.closest('.mpd-opc-deals-wrap');
				$wrap.find('.mpd-opc-deal-card').removeClass('selected');
				$card.addClass('selected');
				$card.find('input[type="radio"]').prop('checked', true);

				var qty = parseInt($card.data('qty'), 10) || 1;
				var $qtyInput = $card.closest('.mpd-opc-wrapper').find('.mpd-opc-qty-input');
				if ($qtyInput.length) {
					$qtyInput.val(qty);
				}
				self.triggerQuantityUpdate($card, qty);
			});
		},

		triggerQuantityUpdate: function ($wrap, qty) {
			var self = this;
			clearTimeout(this.updateTimeout);

			this.updateTimeout = setTimeout(function () {
				var $wrapper = $wrap.closest('.mpd-opc-wrapper');
				var productId = $wrapper.data('product-id');
				var nonce = $wrapper.data('nonce') || (window.mpdOPCParams ? window.mpdOPCParams.nonce : '');
				var ajaxUrl = (window.mpdOPCParams && window.mpdOPCParams.ajaxUrl) ? window.mpdOPCParams.ajaxUrl : (window.woocommerce_params ? window.woocommerce_params.ajax_url : '/wp-admin/admin-ajax.php');

				if (!productId) return;

				// Show loading overlay on review order.
				$('#order_review').block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});

				$.ajax({
					url: ajaxUrl,
					type: 'POST',
					data: {
						action: 'mpd_opc_update_product',
						nonce: nonce,
						product_id: productId,
						quantity: qty
					},
					success: function () {
						// Trigger WooCommerce checkout update event.
						$(document.body).trigger('update_checkout');
					},
					complete: function () {
						$('#order_review').unblock();
					}
				});
			}, 350);
		},

		toggleOrderBump: function (bumpId, customPrice, isChecked, $bumpBox, nonce, bumpAction) {
			var ajaxUrl = (window.mpdOPCParams && window.mpdOPCParams.ajaxUrl) ? window.mpdOPCParams.ajaxUrl : (window.woocommerce_params ? window.woocommerce_params.ajax_url : '/wp-admin/admin-ajax.php');

			$bumpBox.addClass('is-loading');

			// Block order review while bump is being calculated.
			$('#order_review').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			$.ajax({
				url: ajaxUrl,
				type: 'POST',
				data: {
					action: 'mpd_toggle_order_bump',
					nonce: nonce,
					bump_id: bumpId,
					custom_price: customPrice,
					action_type: isChecked ? 'add' : 'remove',
					bump_action_mode: bumpAction || 'add'
				},
				success: function (res) {
					if (res && res.success && res.data) {
						if (res.data.is_added) {
							$bumpBox.addClass('is-bump-active');
						} else {
							$bumpBox.removeClass('is-bump-active');
						}
						// Trigger native WooCommerce checkout recalculation.
						$(document.body).trigger('update_checkout');
					}
				},
				complete: function () {
					$bumpBox.removeClass('is-loading');
					$('#order_review').unblock();
				}
			});
		},

		goToStep: function ($wrapper, step) {
			var $navTabs = $wrapper.find('.mpd-opc-step-tab');
			$navTabs.removeClass('active');
			$wrapper.find('.mpd-opc-step-tab[data-step="' + step + '"]').addClass('active');

			if (step === 1) {
				$wrapper.removeClass('step-2-active').addClass('step-1-active');
				$('html, body').animate({ scrollTop: $wrapper.offset().top - 80 }, 300);
			} else if (step === 2) {
				$wrapper.removeClass('step-1-active').addClass('step-2-active');
				var $step2 = $wrapper.find('.mpd-opc-step-2-content');
				if ($step2.length) {
					$('html, body').animate({ scrollTop: $step2.offset().top - 80 }, 300);
				}
			}
		}
	};

	$(document).ready(function () {
		MPD_OPC.init();
	});

	window.MPD_OPC = MPD_OPC;

})(jQuery);
