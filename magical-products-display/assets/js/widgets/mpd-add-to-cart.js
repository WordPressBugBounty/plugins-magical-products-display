/**
 * MPD Add to Cart Widget JavaScript
 *
 * Handles quantity +/- buttons and sticky cart functionality.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

(function($) {
	'use strict';

	/**
	 * Throttle helper
	 */
	function mpdThrottle(delay, callback) {
		var lastCall = 0;
		return function() {
			var now = new Date().getTime();
			if (now - lastCall >= delay) {
				lastCall = now;
				callback.apply(this, arguments);
			}
		};
	}

	/**
	 * Debounce helper
	 */
	function mpdDebounce(delay, callback) {
		var timeout;
		return function() {
			var context = this;
			var args = arguments;
			clearTimeout(timeout);
			timeout = setTimeout(function() {
				callback.apply(context, args);
			}, delay);
		};
	}

	/**
	 * Quantity Buttons Handler
	 */
	var MPD_Quantity = {
		init: function() {
			// Handle click on +/- buttons
			$(document).on('click', '.mpd-qty-btn', this.handleClick);
			// Validate input on change
			$(document).on('change', '.mpd-quantity-wrapper input.qty, .mpd-quantity-wrapper input[type="number"]', this.validateInput);
		},

		handleClick: function(e) {
			e.preventDefault();
			e.stopPropagation();

			var $btn = $(this);
			var $wrapper = $btn.closest('.mpd-quantity-wrapper');
			var $input = $wrapper.find('input.qty, input[type="number"]').first();
			
			if (!$input.length) {
				return;
			}
			
			var currentVal = parseFloat($input.val()) || 1;
			var min = parseFloat($input.attr('min')) || 1;
			var max = parseFloat($input.attr('max')) || '';
			var step = parseFloat($input.attr('step')) || 1;

			if ($btn.hasClass('mpd-qty-plus')) {
				// Increase quantity
				if (max === '' || currentVal < max) {
					$input.val(currentVal + step).trigger('change').trigger('input');
				}
			} else if ($btn.hasClass('mpd-qty-minus')) {
				// Decrease quantity
				if (currentVal > min) {
					$input.val(currentVal - step).trigger('change').trigger('input');
				}
			}
		},

		validateInput: function() {
			var $input = $(this);
			var val = parseFloat($input.val()) || 1;
			var min = parseFloat($input.attr('min')) || 1;
			var max = parseFloat($input.attr('max')) || '';
			var step = parseFloat($input.attr('step')) || 1;

			// Ensure value is within bounds
			if (val < min) {
				$input.val(min);
			} else if (max !== '' && val > max) {
				$input.val(max);
			}

			// Ensure value is a multiple of step
			var remainder = (val - min) % step;
			if (remainder !== 0) {
				$input.val(val - remainder);
			}
		}
	};

	/**
	 * Sticky Cart Handler
	 */
	var MPD_StickyCart = {
		$widget: null,
		$stickyBar: null,
		isVisible: false,

		init: function() {
			var self = this;

			// Find sticky cart enabled widgets
			$('.mpd-sticky-cart-enabled').each(function() {
				self.createStickyBar($(this));
			});

			// Handle scroll
			if ($('.mpd-sticky-cart-enabled').length) {
				$(window).on('scroll', mpdThrottle(100, function() {
					self.handleScroll();
				}));
			}
		},

		createStickyBar: function($widget) {
			// Only create on mobile
			if ($(window).width() > 767) {
				return;
			}

			var productTitle = $widget.closest('.product').find('.product_title, .mpd-product-title').first().text() || '';
			var productPrice = $widget.closest('.product').find('.price').first().html() || '';
			var productImage = $widget.closest('.product').find('.woocommerce-product-gallery__image img, .mpd-gallery-slide img').first().attr('src') || '';
			var $addToCartBtn = $widget.find('.single_add_to_cart_button').first();

			if (!$addToCartBtn.length) {
				return;
			}

			var $stickyBar = $('<div class="mpd-sticky-cart-bar" style="display: none;"></div>');
			
			var html = '<div class="mpd-sticky-product-info">';
			if (productImage) {
				html += '<div class="mpd-sticky-product-image"><img src="' + productImage + '" alt=""></div>';
			}
			html += '<div class="mpd-sticky-product-details">';
			if (productTitle) {
				html += '<div class="mpd-sticky-product-title">' + productTitle + '</div>';
			}
			if (productPrice) {
				html += '<div class="mpd-sticky-product-price">' + productPrice + '</div>';
			}
			html += '</div></div>';
			html += '<div class="mpd-sticky-add-to-cart">';
			html += '<button type="button" class="button mpd-sticky-add-btn">' + $addToCartBtn.text() + '</button>';
			html += '</div>';

			$stickyBar.html(html);
			$('body').append($stickyBar);

			// Handle sticky button click
			$stickyBar.on('click', '.mpd-sticky-add-btn', function() {
				// Scroll to the add to cart form
				$('html, body').animate({
					scrollTop: $widget.offset().top - 100
				}, 300);

				// Trigger the original button click
				$addToCartBtn.trigger('click');
			});

			this.$widget = $widget;
			this.$stickyBar = $stickyBar;
		},

		handleScroll: function() {
			if (!this.$widget || !this.$stickyBar || $(window).width() > 767) {
				return;
			}

			var widgetTop = this.$widget.offset().top;
			var widgetBottom = widgetTop + this.$widget.outerHeight();
			var scrollTop = $(window).scrollTop();
			var windowHeight = $(window).height();

			// Show sticky bar when widget is above the viewport
			if (scrollTop > widgetBottom - windowHeight / 2) {
				if (!this.isVisible) {
					this.$stickyBar.slideDown(200);
					this.isVisible = true;
				}
			} else {
				if (this.isVisible) {
					this.$stickyBar.slideUp(200);
					this.isVisible = false;
				}
			}
		}
	};

	/**
	 * Dropdown Quantity Handler
	 */
	var MPD_DropdownQty = {
		init: function() {
			// When dropdown changes, update hidden quantity input if exists
			$(document).on('change', '.mpd-qty-dropdown select', function() {
				var $select = $(this);
				var val = $select.val();
				
				// Update any hidden qty input in the same form
				$select.closest('form').find('input.qty[type="hidden"]').val(val);
			});
		}
	};

	/**
	 * AJAX Add to Cart Handler
	 */
	var MPD_AjaxCart = {
		init: function() {
			var self = this;

			// Intercept add to cart form submit for AJAX wrappers
			$(document).on('submit', '.mpd-ajax-add-to-cart form.cart', function(e) {
				var $wrapper = $(this).closest('.mpd-ajax-add-to-cart');
				var productType = $wrapper.data('product-type');

				// Only AJAX for simple products (variable needs page validation)
				if (productType === 'simple' || productType === 'subscription') {
					e.preventDefault();
					self.handleAddToCart($(this), $wrapper);
				}
			});

			// For variable products, intercept the button click after validation
			$(document).on('click', '.mpd-ajax-add-to-cart .single_add_to_cart_button', function(e) {
				var $wrapper = $(this).closest('.mpd-ajax-add-to-cart');
				var productType = $wrapper.data('product-type');
				var $form = $(this).closest('form.cart');

				if (productType === 'variable' || productType === 'variable-subscription') {
					// Check if a variation is selected
					var variationId = $form.find('input[name="variation_id"]').val();
					if (variationId && variationId !== '0') {
						e.preventDefault();
						self.handleAddToCart($form, $wrapper);
					}
				}
			});

			// Buy Now button handler
			$(document).on('click', '.mpd-buy-now-btn', function(e) {
				e.preventDefault();
				self.handleBuyNow($(this));
			});
		},

		handleAddToCart: function($form, $wrapper) {
			var self = this;
			var $btn = $form.find('.single_add_to_cart_button');
			var originalText = $btn.text();
			var addedText = $wrapper.data('added-text') || 'Added!';

			// Already loading
			if ($btn.hasClass('mpd-loading')) return;

			// Add loading state
			$btn.addClass('mpd-loading');
			self.injectSpinner($btn);

			$.ajax({
				url: wc_add_to_cart_params ? wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart') : '/?wc-ajax=add_to_cart',
				type: 'POST',
				data: $form.serialize(),
				success: function(response) {
					if (response.error) {
						// Show WooCommerce notices
						if (response.notices) {
							$wrapper.prepend('<div class="woocommerce-error mpd-ajax-notice" style="animation: mpdFadeSlideIn 0.3s ease">' + response.notices + '</div>');
							setTimeout(function() {
								$wrapper.find('.mpd-ajax-notice').fadeOut(300, function() { $(this).remove(); });
							}, 4000);
						}
						$btn.removeClass('mpd-loading');
						self.removeSpinner($btn);
						return;
					}

					// Trigger WooCommerce added_to_cart event
					$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $btn]);

					// Show added state
					$btn.removeClass('mpd-loading').addClass('mpd-added');
					self.removeSpinner($btn);
					$btn.find('.mpd-btn-text').length
						? $btn.find('.mpd-btn-text').text(addedText)
						: $btn.text(addedText);

					// Show View Cart button
					if ($wrapper.data('show-view-cart') === 'yes') {
						self.showViewCartButton($wrapper);
					}

					// Reset button after 3 seconds
					setTimeout(function() {
						$btn.removeClass('mpd-added');
						$btn.find('.mpd-btn-text').length
							? $btn.find('.mpd-btn-text').text(originalText)
							: $btn.text(originalText);
					}, 3000);
				},
				error: function() {
					$btn.removeClass('mpd-loading');
					self.removeSpinner($btn);
				}
			});
		},

		handleBuyNow: function($btn) {
			var self = this;
			var $wrapper = $btn.closest('.mpd-add-to-cart');
			var $form = $wrapper.find('form.cart');
			var checkoutUrl = $wrapper.data('checkout-url') || '/checkout';

			if ($btn.hasClass('mpd-loading')) return;

			// Add loading state
			$btn.addClass('mpd-loading');
			$btn.find('.mpd-btn-spinner').removeClass('mpd-spinner-hidden');

			$.ajax({
				url: wc_add_to_cart_params ? wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart') : '/?wc-ajax=add_to_cart',
				type: 'POST',
				data: $form.serialize(),
				success: function(response) {
					if (response.error) {
						$btn.removeClass('mpd-loading');
						$btn.find('.mpd-btn-spinner').addClass('mpd-spinner-hidden');
						return;
					}

					// Redirect to checkout
					window.location.href = checkoutUrl;
				},
				error: function() {
					$btn.removeClass('mpd-loading');
					$btn.find('.mpd-btn-spinner').addClass('mpd-spinner-hidden');
				}
			});
		},

		injectSpinner: function($btn) {
			if ($btn.find('.mpd-btn-spinner').length) {
				$btn.find('.mpd-btn-spinner').removeClass('mpd-spinner-hidden');
				return;
			}
			var spinnerSvg = '<svg class="mpd-btn-spinner" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>';
			$btn.prepend(spinnerSvg);
		},

		removeSpinner: function($btn) {
			$btn.find('.mpd-btn-spinner').addClass('mpd-spinner-hidden');
		},

		showViewCartButton: function($wrapper) {
			// Don't duplicate
			if ($wrapper.find('.mpd-view-cart-btn').length) return;

			var cartUrl = $wrapper.data('cart-url') || '/cart';
			var viewCartText = $wrapper.data('view-cart-text') || 'View Cart';

			var $viewCartBtn = $('<a href="' + cartUrl + '" class="mpd-view-cart-btn">' +
				'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>' +
				'<span>' + viewCartText + '</span></a>');

			$wrapper.append($viewCartBtn);
		}
	};

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function() {
		MPD_Quantity.init();
		MPD_DropdownQty.init();
		MPD_AjaxCart.init();

		// Initialize sticky cart with a small delay
		setTimeout(function() {
			MPD_StickyCart.init();
		}, 500);
	});

	/**
	 * Re-initialize on window resize
	 */
	$(window).on('resize', mpdDebounce(250, function() {
		// Reinit sticky cart on resize
		if ($('.mpd-sticky-cart-bar').length) {
			if ($(window).width() > 767) {
				$('.mpd-sticky-cart-bar').hide();
			}
		}
	}));

})(jQuery);
