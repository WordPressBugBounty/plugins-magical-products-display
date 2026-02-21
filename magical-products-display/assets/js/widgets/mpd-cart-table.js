/**
 * MPD Cart Table Widget JavaScript
 *
 * Handles quantity button interactions, AJAX updates, and coupon form for the cart table.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

(function($) {
    'use strict';

    var MPDCartTable = {
        initialized: false,
        
        /**
         * Initialize the cart table functionality.
         */
        init: function() {
            // Prevent double initialization
            if (this.initialized) {
                return;
            }
            this.initialized = true;
            this.bindEvents();
        },

        /**
         * Flag to indicate a remove operation is in progress.
         * Used to prevent the quantity change handler from conflicting.
         */
        isRemoving: false,

        /**
         * Reference to the capture-phase click handler for cleanup.
         */
        _captureHandler: null,

        /**
         * Bind event handlers.
         */
        bindEvents: function() {
            var self = this;

            // Remove any existing handlers first to prevent duplicates
            $(document).off('click.mpdCartTable');
            $(document).off('change.mpdCartTable');
            $(document).off('submit.mpdCartTableCoupon');

            // Clean up previous capture-phase handler if it exists
            if (self._captureHandler) {
                document.removeEventListener('click', self._captureHandler, true);
            }

            // Remove item button - use capture phase to fire BEFORE WooCommerce's handler
            // WC's cart.js binds on '.woocommerce-cart-form .product-remove > a' via jQuery delegation (bubble phase).
            // We use native addEventListener with capture=true to intercept the click before WC processes it.
            self._captureHandler = function(e) {
                var target = e.target;
                var $link = $(target).closest('.mpd-cart-table-wrapper .product-remove > a');
                if ($link.length) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    self.handleRemoveItem($link);
                }
            };
            document.addEventListener('click', self._captureHandler, true);

            // Quantity plus/minus buttons - use namespaced events
            $(document).on('click.mpdCartTable', '.mpd-cart-table-wrapper .mpd-qty-minus, .mpd-cart-table-wrapper .mpd-qty-plus', function(e) {
                e.preventDefault();
                e.stopPropagation();
                self.handleQuantityButton($(this));
            });

            // Dropdown quantity change
            $(document).on('change.mpdCartTable', '.mpd-cart-table-wrapper .qty-dropdown', function() {
                var $cartWrapper = $(this).closest('.mpd-cart-table-wrapper');
                if ($cartWrapper.hasClass('mpd-ajax-cart')) {
                    self.debounceUpdate($(this));
                } else {
                    self.triggerCartUpdate();
                }
            });

            // AJAX cart update (for AJAX-enabled carts) - also handle direct input changes
            // Skip if a remove operation is in progress to avoid race conditions
            $(document).on('change.mpdCartTable', '.mpd-ajax-cart .quantity input.qty', function() {
                if (!self.isRemoving) {
                    self.debounceUpdate($(this));
                }
            });

            // Coupon form AJAX submit - intercept the apply_coupon button click
            $(document).on('click.mpdCartTableCoupon', '.mpd-cart-table-wrapper .mpd-coupon-form-wrapper button[name="apply_coupon"]', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                self.handleCouponSubmit($(this));
                return false;
            });

            // Also prevent form submission when pressing Enter in coupon field
            $(document).on('keypress.mpdCartTableCoupon', '.mpd-cart-table-wrapper .mpd-coupon-form-wrapper input[name="coupon_code"]', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).closest('.mpd-coupon-form-wrapper').find('button[name="apply_coupon"]').trigger('click.mpdCartTableCoupon');
                    return false;
                }
            });
        },

        /**
         * Handle coupon form submission via AJAX.
         *
         * @param {jQuery} $button The apply coupon button.
         */
        handleCouponSubmit: function($button) {
            var self = this;
            var $wrapper = $button.closest('.mpd-coupon-form-wrapper');
            var $cartWrapper = $button.closest('.mpd-cart-table-wrapper');
            var $input = $wrapper.find('input[name="coupon_code"]');
            var couponCode = $input.val().trim();

            // Remove ALL existing notices on the page related to cart/coupons
            $('.woocommerce-error, .woocommerce-message, .woocommerce-info').each(function() {
                var $notice = $(this);
                // Only remove notices that are near the cart table widget
                if ($notice.closest('.elementor-widget-mpd-cart-table').length || 
                    $notice.prev('.mpd-cart-table-wrapper').length ||
                    $notice.next('.mpd-cart-table-wrapper').length ||
                    $cartWrapper.parent().find($notice).length) {
                    $notice.remove();
                }
            });
            // Also remove any notices immediately before cart wrapper
            $cartWrapper.prevAll('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();

            if (!couponCode) {
                self.showNotice($cartWrapper, 'Please enter a coupon code.', 'error');
                return false;
            }

            // Check if wc_cart_params exists
            if (typeof wc_cart_params === 'undefined') {
                // Fallback to form submit if AJAX params not available
                $button.closest('form').submit();
                return false;
            }

            // Disable button and show loading state
            $button.prop('disabled', true).addClass('loading');

            $.ajax({
                type: 'POST',
                url: wc_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
                data: {
                    coupon_code: couponCode,
                    security: wc_cart_params.apply_coupon_nonce
                },
                dataType: 'html',
                success: function(response) {
                    var $response = $('<div>' + response + '</div>');
                    var $errorMsg = $response.find('.woocommerce-error');
                    var $successMsg = $response.find('.woocommerce-message');

                    if ($errorMsg.length > 0) {
                        // Extract error text - remove icon text if present
                        var errorText = $errorMsg.find('li').first().text().trim() || $errorMsg.text().trim() || 'Coupon could not be applied.';
                        // Clean up the error text (remove any duplicate icon characters)
                        errorText = errorText.replace(/^[\u26A0\u24C4\u2757\u274C\uD83D\uDEAB]+\s*/, '');
                        self.showNotice($cartWrapper, errorText, 'error');
                    } else if (response.toLowerCase().indexOf('error') !== -1 && $successMsg.length === 0) {
                        self.showNotice($cartWrapper, 'Coupon could not be applied.', 'error');
                    } else {
                        // Success - clear input and update cart
                        $input.val('');
                        var successText = $successMsg.text().trim() || 'Coupon applied successfully!';
                        self.showNotice($cartWrapper, successText, 'success');
                        
                        // Trigger cart update without showing WC notices
                        $(document.body).trigger('wc_update_cart', { update_shipping_method: false });
                    }
                },
                error: function() {
                    self.showNotice($cartWrapper, 'An error occurred. Please try again.', 'error');
                },
                complete: function() {
                    $button.prop('disabled', false).removeClass('loading');
                }
            });

            return false;
        },

        /**
         * Show a notice message above the cart table.
         *
         * @param {jQuery} $cartWrapper The cart table wrapper element.
         * @param {string} message      The message to display.
         * @param {string} type         The type of notice (error, success, info).
         */
        showNotice: function($cartWrapper, message, type) {
            var noticeClass = type === 'error' ? 'woocommerce-error' : (type === 'success' ? 'woocommerce-message' : 'woocommerce-info');
            var $notice = $('<ul class="' + noticeClass + '"><li>' + message + '</li></ul>');
            
            // Insert notice before the cart wrapper
            $cartWrapper.before($notice);
            
            // Scroll to notice
            $('html, body').animate({
                scrollTop: $notice.offset().top - 100
            }, 300);
        },

        /**
         * Handle remove item button click.
         *
         * Removes a cart item by making an AJAX GET request to the remove URL,
         * then updates the cart table and totals from the response HTML.
         * Falls back to standard page navigation if AJAX fails.
         *
         * @param {jQuery} $link The remove link element.
         */
        handleRemoveItem: function($link) {
            var self = this;
            var removeUrl = $link.attr('href');
            var $row = $link.closest('tr');
            var $cartWrapper = $link.closest('.mpd-cart-table-wrapper');

            if (!removeUrl) {
                return;
            }

            // Set removing flag to prevent quantity change handlers from firing
            self.isRemoving = true;

            // Cancel any pending debounced quantity updates
            clearTimeout(self.updateTimer);

            // Show loading state on the row
            $row.addClass('mpd-removing');
            $cartWrapper.addClass('mpd-cart-loading');

            // Block the cart table during removal
            if ($.fn.block) {
                $cartWrapper.block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
            }

            $.ajax({
                type: 'GET',
                url: removeUrl,
                dataType: 'html',
                success: function(response) {
                    var $response = $('<div />').append($.parseHTML(response));
                    var $newCartTable = $response.find('.mpd-cart-table-wrapper');
                    var $newTotals = $response.find('.cart_totals, .mpd-cart-totals-wrapper');
                    var $emptyCart = $response.find('.mpd-cart-empty, .cart-empty, .woocommerce-info');

                    if ($newCartTable.length) {
                        // Cart still has items - replace the cart table
                        $cartWrapper.replaceWith($newCartTable);
                    } else if ($emptyCart.length || $response.find('.woocommerce-cart-form').length === 0) {
                        // Cart is now empty - reload the page to show empty cart state
                        window.location.href = removeUrl;
                        return;
                    } else {
                        // Unexpected response - fallback to page reload
                        window.location.href = removeUrl;
                        return;
                    }

                    // Update cart totals on the page
                    if ($newTotals.length) {
                        $('.cart_totals, .mpd-cart-totals-wrapper').each(function() {
                            var $newTotalClone = $newTotals.first().clone();
                            $(this).replaceWith($newTotalClone);
                        });
                    }

                    // Trigger WooCommerce events to update fragments, mini-cart, etc.
                    $(document.body).trigger('removed_from_cart');
                    $(document.body).trigger('updated_cart_totals');
                    $(document.body).trigger('wc_fragment_refresh');
                },
                error: function() {
                    // AJAX failed - fallback to standard page navigation
                    window.location.href = removeUrl;
                },
                complete: function() {
                    self.isRemoving = false;
                    $row.removeClass('mpd-removing');
                    $cartWrapper.removeClass('mpd-cart-loading');
                    if ($.fn.block) {
                        $cartWrapper.unblock();
                    }
                }
            });
        },

        /**
         * Handle quantity plus/minus button click.
         *
         * @param {jQuery} $button The clicked button.
         */
        handleQuantityButton: function($button) {
            var $wrapper = $button.closest('.quantity');
            var $input = $wrapper.find('input.qty');
            var currentVal = parseFloat($input.val()) || 0;
            var min = parseFloat($input.attr('min')) || 0;
            var max = parseFloat($input.attr('max')) || Infinity;
            var step = parseFloat($input.attr('step')) || 1;
            var newVal;

            if ($button.hasClass('mpd-qty-plus')) {
                newVal = currentVal + step;
                if (max !== Infinity && newVal > max) {
                    newVal = max;
                }
            } else {
                newVal = currentVal - step;
                if (newVal < min) {
                    newVal = min;
                }
            }

            if (newVal !== currentVal) {
                $input.val(newVal).trigger('change');
                
                // Check if AJAX update is enabled
                var $cartWrapper = $button.closest('.mpd-cart-table-wrapper');
                if ($cartWrapper.hasClass('mpd-ajax-cart')) {
                    this.debounceUpdate($input);
                } else {
                    this.triggerCartUpdate();
                }
            }
        },

        /**
         * Trigger cart update button.
         */
        triggerCartUpdate: function() {
            var $updateButton = $('button[name="update_cart"]');
            if ($updateButton.length) {
                $updateButton.prop('disabled', false);
                $updateButton.trigger('click');
            }
        },

        /**
         * Debounced cart update for AJAX carts.
         *
         * @param {jQuery} $input The quantity input.
         */
        debounceUpdate: function($input) {
            var self = this;
            
            clearTimeout(this.updateTimer);
            this.updateTimer = setTimeout(function() {
                if ($input.closest('.mpd-ajax-cart').length) {
                    self.ajaxUpdateCart($input);
                }
            }, 500);
        },

        /**
         * AJAX update cart quantity.
         *
         * @param {jQuery} $input The quantity input.
         */
        ajaxUpdateCart: function($input) {
            var self = this;
            var $row = $input.closest('tr');
            var $form = $input.closest('form');
            var $cartWrapper = $input.closest('.mpd-cart-table-wrapper');
            
            if (!$form.length) {
                // Fallback to triggering update button
                self.triggerCartUpdate();
                return;
            }

            // Show loading state
            $row.addClass('mpd-updating');
            $cartWrapper.addClass('mpd-cart-loading');

            // Block the cart table during update
            $cartWrapper.block && $cartWrapper.block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            $.ajax({
                type: 'POST',
                url: $form.attr('action'),
                data: $form.serialize() + '&update_cart=Update+cart',
                dataType: 'html',
                success: function(response) {
                    // Parse the response and update cart fragments
                    var $response = $(response);
                    var $newCartTable = $response.find('.mpd-cart-table-wrapper');
                    var $newTotals = $response.find('.cart_totals, .mpd-cart-totals-wrapper');
                    
                    // Update cart table content if found
                    if ($newCartTable.length) {
                        $cartWrapper.find('.woocommerce-cart-form').html($newCartTable.find('.woocommerce-cart-form').html());
                    }
                    
                    // Update cart totals if on same page
                    if ($newTotals.length) {
                        $('.cart_totals, .mpd-cart-totals-wrapper').html($newTotals.html());
                    }
                    
                    // Trigger WooCommerce cart update event
                    $(document.body).trigger('updated_cart_totals');
                    $(document.body).trigger('wc_fragments_refreshed');
                },
                error: function() {
                    // Fallback: trigger full page cart update
                    $(document.body).trigger('wc_update_cart');
                },
                complete: function() {
                    $row.removeClass('mpd-updating');
                    $cartWrapper.removeClass('mpd-cart-loading');
                    $cartWrapper.unblock && $cartWrapper.unblock();
                }
            });
        },

        /**
         * Refresh on Elementor frontend init - reset and rebind.
         */
        refresh: function() {
            // Reset initialized flag to allow rebinding
            this.initialized = false;
            this.init();
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        MPDCartTable.init();

        // Listen for WooCommerce cart updates and clean up duplicate notices
        $(document.body).on('updated_cart_totals updated_wc_div', function() {
            // Remove any notices that WooCommerce might add after our AJAX call
            // Keep only the first notice to prevent duplicates
            var $notices = $('.mpd-cart-table-wrapper').prevAll('.woocommerce-error, .woocommerce-message, .woocommerce-info');
            if ($notices.length > 1) {
                $notices.not(':first').remove();
            }
        });
    });

    // Re-initialize on Elementor frontend init - only once
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-cart-table.default', function($scope) {
                // Only refresh if this is a new widget instance
                if ($scope.find('.mpd-cart-table-wrapper').length) {
                    MPDCartTable.refresh();
                }
            });
        }
    });

})(jQuery);
