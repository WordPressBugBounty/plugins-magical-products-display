/**
 * MPD Coupon Form Widget JavaScript
 *
 * Handles AJAX coupon application and suggestions.
 *
 * @package Magical_Products_Display
 * @since 2.0.0
 */

(function($) {
    'use strict';

    var MPDCouponForm = {
        /**
         * Initialize the coupon form functionality.
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind event handlers.
         */
        bindEvents: function() {
            // AJAX coupon form submission
            $(document).on('submit', '.mpd-ajax-coupon .mpd-coupon-form-inner', this.handleAjaxSubmit.bind(this));

            // Apply coupon suggestion
            $(document).on('click', '.mpd-coupon-suggestion .apply-suggestion', this.handleSuggestionClick.bind(this));

            // Remove coupon via AJAX
            $(document).on('click', '.mpd-ajax-coupon .mpd-applied-coupon .remove-coupon', this.handleRemoveCoupon.bind(this));
        },

        /**
         * Handle AJAX form submission.
         *
         * @param {Event} e Form submit event.
         */
        handleAjaxSubmit: function(e) {
            e.preventDefault();
            e.stopPropagation();

            var self = this;
            var $form = $(e.currentTarget);
            var $wrapper = $form.closest('.mpd-coupon-form');
            var $button = $form.find('button[type="submit"]');
            var $input = $form.find('input[name="coupon_code"]');
            var couponCode = $input.val().trim();

            if (!couponCode) {
                this.showMessage($wrapper, 'Please enter a coupon code.', 'error');
                return false;
            }

            // Disable button and show loading state
            $button.prop('disabled', true).addClass('loading');
            $wrapper.addClass('loading');
            this.clearMessages($wrapper);

            $.ajax({
                type: 'POST',
                url: wc_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
                data: {
                    coupon_code: couponCode,
                    security: wc_cart_params.apply_coupon_nonce
                },
                dataType: 'html',
                success: function(response) {
                    // Parse the response to find WooCommerce messages
                    var $response = $('<div>' + response + '</div>');
                    var $errorMsg = $response.find('.woocommerce-error');
                    var $successMsg = $response.find('.woocommerce-message');

                    // Check for error in response
                    if ($errorMsg.length > 0) {
                        // Extract error text - get li content or direct text
                        var errorText = $errorMsg.find('li').first().text().trim() || $errorMsg.text().trim() || 'Coupon could not be applied.';
                        self.showMessage($wrapper, errorText, 'error');
                    } else if (response.toLowerCase().indexOf('error') !== -1 && $successMsg.length === 0) {
                        // Fallback error detection
                        self.showMessage($wrapper, 'Coupon could not be applied.', 'error');
                    } else {
                        // Success - clear input
                        $input.val('');
                        
                        var successText = $successMsg.text().trim() || 'Coupon applied successfully!';
                        self.showMessage($wrapper, successText, 'success');
                        
                        // Trigger cart update without page reload
                        $(document.body).trigger('wc_update_cart');
                        $(document.body).trigger('applied_coupon_in_checkout', [couponCode]);

                        // Refresh cart fragments without page reload
                        self.refreshCartFragments();
                        
                        // Update applied coupons section
                        self.updateAppliedCoupons($wrapper, couponCode);
                    }
                },
                error: function() {
                    self.showMessage($wrapper, 'An error occurred. Please try again.', 'error');
                },
                complete: function() {
                    $button.prop('disabled', false).removeClass('loading');
                    $wrapper.removeClass('loading');
                }
            });

            return false;
        },

        /**
         * Handle coupon suggestion click.
         *
         * @param {Event} e Click event.
         */
        handleSuggestionClick: function(e) {
            e.preventDefault();
            e.stopPropagation();

            var self = this;
            var $button = $(e.currentTarget);
            var $suggestion = $button.closest('.mpd-coupon-suggestion');
            var $wrapper = $suggestion.closest('.mpd-coupon-form');
            var couponCode = $suggestion.data('coupon');

            if (!couponCode) {
                return false;
            }

            // Check if AJAX is enabled
            if ($wrapper.hasClass('mpd-ajax-coupon')) {
                // Apply via AJAX
                var originalText = $button.text();
                $button.prop('disabled', true).text('Applying...');
                this.clearMessages($wrapper);

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
                        
                        if ($errorMsg.length > 0) {
                            var errorText = $errorMsg.find('li').first().text().trim() || $errorMsg.text().trim() || 'Coupon could not be applied.';
                            self.showMessage($wrapper, errorText, 'error');
                            $button.prop('disabled', false).text(originalText);
                        } else if (response.toLowerCase().indexOf('error') !== -1) {
                            self.showMessage($wrapper, 'Coupon could not be applied.', 'error');
                            $button.prop('disabled', false).text(originalText);
                        } else {
                            self.showMessage($wrapper, 'Coupon applied successfully!', 'success');
                            $(document.body).trigger('wc_update_cart');
                            self.refreshCartFragments();
                            
                            // Hide the applied suggestion
                            $suggestion.fadeOut();
                            
                            // Update applied coupons section
                            self.updateAppliedCoupons($wrapper, couponCode);
                        }
                    },
                    error: function() {
                        self.showMessage($wrapper, 'An error occurred. Please try again.', 'error');
                        $button.prop('disabled', false).text(originalText);
                    }
                });
            } else {
                // Fill the input and submit the form
                var $input = $wrapper.find('input[name="coupon_code"]');
                $input.val(couponCode);
                $wrapper.find('.mpd-coupon-form-inner').submit();
            }

            return false;
        },

        /**
         * Handle remove coupon via AJAX.
         *
         * @param {Event} e Click event.
         */
        handleRemoveCoupon: function(e) {
            e.preventDefault();
            e.stopPropagation();

            var self = this;
            var $link = $(e.currentTarget);
            var $wrapper = $link.closest('.mpd-coupon-form');
            var $couponItem = $link.closest('.mpd-applied-coupon');
            var couponCode = $couponItem.find('.coupon-code').text().trim();

            $couponItem.css('opacity', '0.5');

            $.ajax({
                type: 'POST',
                url: wc_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_coupon'),
                data: {
                    coupon: couponCode,
                    security: wc_cart_params.remove_coupon_nonce
                },
                dataType: 'html',
                success: function(response) {
                    var $response = $('<div>' + response + '</div>');
                    var $errorMsg = $response.find('.woocommerce-error');
                    
                    if ($errorMsg.length === 0) {
                        $couponItem.slideUp(200, function() {
                            $(this).remove();
                        });
                        $(document.body).trigger('wc_update_cart');
                        self.refreshCartFragments();
                        self.showMessage($wrapper, 'Coupon removed.', 'success');
                    } else {
                        $couponItem.css('opacity', '1');
                        var errorText = $errorMsg.text().trim() || 'Could not remove coupon.';
                        self.showMessage($wrapper, errorText, 'error');
                    }
                },
                error: function() {
                    $couponItem.css('opacity', '1');
                    self.showMessage($wrapper, 'An error occurred.', 'error');
                }
            });

            return false;
        },

        /**
         * Update the applied coupons section.
         *
         * @param {jQuery} $wrapper Form wrapper element.
         * @param {string} couponCode Applied coupon code.
         */
        updateAppliedCoupons: function($wrapper, couponCode) {
            var $appliedSection = $wrapper.find('.mpd-applied-coupons');
            
            // Create section if it doesn't exist
            if ($appliedSection.length === 0) {
                $appliedSection = $('<div class="mpd-applied-coupons"></div>');
                $wrapper.find('.mpd-coupon-form-inner').before($appliedSection);
            }
            
            // Check if coupon is already displayed
            var exists = false;
            $appliedSection.find('.coupon-code').each(function() {
                if ($(this).text().toLowerCase() === couponCode.toLowerCase()) {
                    exists = true;
                    return false;
                }
            });
            
            if (!exists) {
                var $couponItem = $('<div class="mpd-applied-coupon">' +
                    '<span class="coupon-code">' + couponCode.toUpperCase() + '</span>' +
                    '<a href="#" class="remove-coupon" title="Remove coupon">&times;</a>' +
                '</div>');
                $appliedSection.append($couponItem);
            }
        },

        /**
         * Show a message in the form.
         *
         * @param {jQuery} $wrapper Form wrapper element.
         * @param {string} message  Message text.
         * @param {string} type     Message type (success/error).
         */
        showMessage: function($wrapper, message, type) {
            this.clearMessages($wrapper);

            var $message = $('<div class="mpd-coupon-message mpd-coupon-' + type + '">' + message + '</div>');
            $wrapper.find('.mpd-coupon-form-inner').after($message);
            
            // Show message with animation
            $message.hide().fadeIn(200);

            // Auto-hide success messages after 5 seconds
            if (type === 'success') {
                setTimeout(function() {
                    $message.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        },

        /**
         * Clear all messages.
         *
         * @param {jQuery} $wrapper Form wrapper element.
         */
        clearMessages: function($wrapper) {
            $wrapper.find('.mpd-coupon-message').remove();
        },

        /**
         * Refresh cart fragments without page reload.
         */
        refreshCartFragments: function() {
            // Trigger WooCommerce fragment refresh
            $(document.body).trigger('wc_fragment_refresh');
            
            // Also update mini cart if present
            if (typeof wc_cart_fragments_params !== 'undefined') {
                $.ajax({
                    type: 'POST',
                    url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
                    success: function(data) {
                        if (data && data.fragments) {
                            $.each(data.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });
                        }
                    }
                });
            }

            // Update cart totals via AJAX instead of page reload
            this.updateCartTotals();
        },

        /**
         * Update cart totals section via AJAX.
         */
        updateCartTotals: function() {
            var $cartTotals = $('.cart_totals, .mpd-cart-totals');
            
            if ($cartTotals.length === 0) {
                return;
            }

            // Try to use WooCommerce's update cart AJAX
            var $cartForm = $('form.woocommerce-cart-form');
            if ($cartForm.length > 0) {
                // Trigger the update cart button if available
                var $updateButton = $cartForm.find('button[name="update_cart"]');
                if ($updateButton.length > 0) {
                    $updateButton.prop('disabled', false).trigger('click');
                    return;
                }
            }

            // Fallback: Fetch cart totals via AJAX
            $.ajax({
                type: 'POST',
                url: wc_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_cart_totals'),
                success: function(response) {
                    if (response) {
                        // Find the inner content and update
                        var $newTotals = $(response);
                        if ($newTotals.find('.shop_table').length > 0) {
                            $cartTotals.find('.shop_table').replaceWith($newTotals.find('.shop_table'));
                        }
                    }
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        MPDCouponForm.init();
    });

    // Re-initialize for Elementor editor
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-coupon-form.default', function() {
                MPDCouponForm.init();
            });
        }
    });

})(jQuery);
