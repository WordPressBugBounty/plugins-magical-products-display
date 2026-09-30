/**
 * Sticky Add to Cart Bar JavaScript
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

(function ($) {
	'use strict';

	var MPDStickyCart = {
		init: function () {
			var $container = $('.mpd-sticky-add-to-cart-container');
			if (!$container.length) {
				return;
			}

			$container.each(function () {
				MPDStickyCart.initBar($(this));
			});
		},

		initBar: function ($bar) {
			var trigger = $bar.data('trigger') || 'scroll_form';
			var distance = parseInt($bar.data('distance'), 10) || 400;
			var $mainForm = $('form.cart').first();

			// In Elementor editor preview, make visible for styling if needed.
			if ($('body').hasClass('elementor-editor-active')) {
				$bar.addClass('is-visible');
				return;
			}

			// Scroll Visibility Logic.
			if ('scroll_form' === trigger && $mainForm.length && 'IntersectionObserver' in window) {
				var observer = new IntersectionObserver(function (entries) {
					entries.forEach(function (entry) {
						if (!entry.isIntersecting && entry.boundingClientRect.top < 0) {
							$bar.addClass('is-visible');
						} else {
							$bar.removeClass('is-visible');
						}
					});
				}, {
					threshold: 0.1
				});

				observer.observe($mainForm[0]);
			} else {
				// Scroll distance fallback.
				var ticking = false;
				$(window).on('scroll', function () {
					if (!ticking) {
						window.requestAnimationFrame(function () {
							if ($(window).scrollTop() > distance) {
								$bar.addClass('is-visible');
							} else {
								$bar.removeClass('is-visible');
							}
							ticking = false;
						});
						ticking = true;
					}
				});
			}

			// Quantity controls.
			$bar.on('click', '.mpd-sticky-qty-plus', function (e) {
				e.preventDefault();
				var $input = $bar.find('.mpd-sticky-qty-input');
				var current = parseInt($input.val(), 10) || 1;
				var max = parseInt($input.attr('max'), 10);

				if (!isNaN(max) && current >= max) {
					return;
				}

				var next = current + 1;
				$input.val(next);

				// Sync with main page form quantity.
				$('form.cart input.qty').val(next).trigger('change');
			});

			$bar.on('click', '.mpd-sticky-qty-minus', function (e) {
				e.preventDefault();
				var $input = $bar.find('.mpd-sticky-qty-input');
				var current = parseInt($input.val(), 10) || 1;
				var min = parseInt($input.attr('min'), 10) || 1;

				if (current <= min) {
					return;
				}

				var next = current - 1;
				$input.val(next);

				// Sync with main page form quantity.
				$('form.cart input.qty').val(next).trigger('change');
			});

			// Variable product direct variation selection (Pro).
			var $varWrap = $bar.find('.mpd-sticky-variations-wrap');
			if ($varWrap.length) {
				var productVariations = $varWrap.data('product_variations') || [];

				$bar.on('change', '.mpd-sticky-var-select', function () {
					var selectedAttributes = {};
					var allSelected = true;

					$bar.find('.mpd-sticky-var-select').each(function () {
						var attrName = $(this).data('attribute_name');
						var attrVal = $(this).val();
						if (!attrVal) {
							allSelected = false;
						}
						selectedAttributes[attrName] = attrVal;
					});

					var $btn = $bar.find('.mpd-sticky-var-add-cart');
					var $btnText = $btn.find('.mpd-sticky-btn-text');

					if (!allSelected) {
						$btn.addClass('disabled').attr('data-variation-id', '0');
						$btnText.text('Select Options');
						return;
					}

					// Find matching variation from available variations.
					var matchedVariation = null;
					$.each(productVariations, function (i, variation) {
						var match = true;
						$.each(selectedAttributes, function (attrKey, attrVal) {
							var varVal = variation.attributes ? variation.attributes[attrKey] : undefined;
							if (typeof varVal !== 'undefined' && varVal !== '' && varVal !== attrVal) {
								match = false;
								return false;
							}
						});
						if (match) {
							matchedVariation = variation;
							return false;
						}
					});

					if (matchedVariation && matchedVariation.is_in_stock) {
						$btn.removeClass('disabled').attr('data-variation-id', matchedVariation.variation_id);
						var btnLabel = (window.mpdStickyCartParams && window.mpdStickyCartParams.i18n && window.mpdStickyCartParams.i18n.add_to_cart) ? window.mpdStickyCartParams.i18n.add_to_cart : 'Add to Cart';
						$btnText.text(btnLabel);

						// Update price display if variation price HTML exists.
						if (matchedVariation.price_html) {
							$bar.find('.mpd-sticky-price').html(matchedVariation.price_html);
						}

						// Update thumbnail image.
						if (matchedVariation.image && matchedVariation.image.thumb_src) {
							$bar.find('.mpd-sticky-thumb img').attr('src', matchedVariation.image.thumb_src).attr('srcset', '');
						}

						// Sync with main WooCommerce variations form on page.
						$('form.variations_form select').each(function () {
							var name = $(this).attr('name');
							if (selectedAttributes[name]) {
								$(this).val(selectedAttributes[name]).trigger('change');
							}
						});
					} else {
						$btn.addClass('disabled').attr('data-variation-id', '0');
						$btnText.text('Unavailable');
					}
				});
			}

			// Variable product - Smooth scroll to main variation form (Free / Fallback).
			$bar.on('click', '.mpd-sticky-select-options', function (e) {
				e.preventDefault();
				var $target = $('form.cart').first();
				if ($target.length) {
					$('html, body').animate({
						scrollTop: $target.offset().top - 120
					}, 600, function () {
						// Focus first attribute select dropdown.
						$target.find('select').first().focus();
					});
				}
			});

			// Add to cart click (Simple and Variable).
			$bar.on('click', '.mpd-sticky-add-cart', function (e) {
				e.preventDefault();
				var $btn = $(this);

				if ($btn.hasClass('disabled')) {
					return;
				}

				var productId = $btn.data('product-id');
				var variationId = parseInt($btn.attr('data-variation-id'), 10) || 0;
				var qty = parseInt($bar.find('.mpd-sticky-qty-input').val(), 10) || 1;

				if (!productId) {
					return;
				}

				$btn.addClass('is-loading');

				// Resolve localized params.
				var params = window.mpdStickyCartParams || window.mpd_add_to_cart_params || {};
				var ajaxUrl = params.ajaxUrl || params.ajax_url || (window.woocommerce_params ? window.woocommerce_params.ajax_url : '/?wc-ajax=add_to_cart');
				var nonce = params.nonce || '';

				var data = {
					action: 'mpd_single_add_to_cart',
					product_id: productId,
					quantity: qty
				};

				if (variationId > 0) {
					data.variation_id = variationId;
					$bar.find('.mpd-sticky-var-select').each(function () {
						var attrName = $(this).data('attribute_name');
						data[attrName] = $(this).val();
					});
				}

				if (nonce) {
					data.nonce = nonce;
				}

				$.ajax({
					url: ajaxUrl,
					type: 'POST',
					data: data,
					success: function (res) {
						if (res && res.error) {
							// Fallback to standard form submit if product has required fields.
							$('form.cart').submit();
							return;
						}

						// Trigger standard WooCommerce added_to_cart event.
						$(document.body).trigger('added_to_cart', [res ? res.fragments : null, res ? res.cart_hash : null, $btn]);

						// Brief button feedback.
						var addedText = (params.i18n && params.i18n.added) ? params.i18n.added : 'Added!';
						var originalText = $btn.find('.mpd-sticky-btn-text').text();
						$btn.find('.mpd-sticky-btn-text').text('✓ ' + addedText);
						setTimeout(function () {
							$btn.find('.mpd-sticky-btn-text').text(originalText);
						}, 2000);
					},
					error: function () {
						// Fallback to native form submit.
						$('form.cart').submit();
					},
					complete: function () {
						$btn.removeClass('is-loading');
					}
				});
			});
		}
	};

	$(document).ready(function () {
		MPDStickyCart.init();
	});

})(jQuery);
