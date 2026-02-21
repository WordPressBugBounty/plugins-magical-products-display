/**
 * MPD Checkout Widgets JavaScript
 *
 * Handles AJAX updates for checkout coupon and order review widgets.
 *
 * @package Magical_Products_Display
 * @since 2.0.0
 */

(function($) {
    'use strict';

    var MPDCheckoutWidgets = {
        /**
         * Initialize the checkout widgets functionality.
         */
        init: function() {
            this.bindEvents();
            this.initCouponForm();
            this.initCheckoutForm();
        },

        /**
         * Bind event handlers.
         */
        bindEvents: function() {
            var self = this;

            // Intercept WooCommerce's coupon form BEFORE it binds
            // Unbind any existing handlers and use ours
            this.interceptCouponForm();

            // Handle remove coupon click in our coupon widget
            $(document).on('click', '.mpd-checkout-coupon__remove', this.handleRemoveCoupon.bind(this));

            // Handle remove coupon click in Order Review widget (WooCommerce native)
            $(document).on('click', '.mpd-order-review .woocommerce-remove-coupon, #order_review .woocommerce-remove-coupon', this.handleOrderReviewRemoveCoupon.bind(this));

            // Listen for WooCommerce checkout update events
            $(document.body).on('update_checkout', function() {
                // WooCommerce will handle the update
            });

            // Listen for applied/removed coupon events from WooCommerce
            $(document.body).on('applied_coupon', function(e, couponCode) {
                self.updateAppliedCoupons();
                self.refreshOrderReview();
            });
            
            $(document.body).on('removed_coupon', function() {
                self.updateAppliedCoupons();
            });

            // Handle WooCommerce checkout fragment updates - this is key!
            $(document.body).on('updated_checkout', function(e, data) {
                self.onCheckoutUpdated(data);
            });
            
            // Global AJAX interceptor to catch WooCommerce coupon AJAX responses
            $(document).ajaxComplete(function(event, xhr, settings) {
                if (settings.url && settings.url.indexOf('apply_coupon') !== -1) {
                    // WooCommerce coupon was applied via AJAX
                    var response = xhr.responseText || '';
                    if (response.indexOf('error') === -1 || response.indexOf('woocommerce-message') !== -1) {
                        // Coupon likely applied successfully
                        setTimeout(function() {
                            self.updateAppliedCoupons();
                            self.refreshOrderReview();
                        }, 300);
                    }
                }
            });
        },
        
        /**
         * Intercept the WooCommerce coupon form to use our handler.
         */
        interceptCouponForm: function() {
            var self = this;
            var $form = $('.mpd-checkout-coupon form.checkout_coupon');
            
            if ($form.length) {
                // Unbind any existing submit handlers (including WooCommerce's)
                $form.off('submit');
                
                // Bind our handler directly to the form
                $form.on('submit', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    self.handleCouponSubmit(e);
                    return false;
                });
            }
            
            // Also handle dynamically added forms
            $(document).on('submit', '.mpd-checkout-coupon form.checkout_coupon', function(e) {
                // Only if not already handled
                if (!e.isDefaultPrevented()) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    self.handleCouponSubmit(e);
                    return false;
                }
            });
        },

        /**
         * Initialize coupon form functionality.
         */
        initCouponForm: function() {
            // Ensure the toggle link works
            $(document).on('click', '.mpd-checkout-coupon .showcoupon', function(e) {
                e.preventDefault();
                var $form = $(this).closest('.mpd-checkout-coupon').find('.checkout_coupon');
                $form.slideToggle(200);
            });
        },

        /**
         * Initialize checkout form for MPD widgets.
         * Ensures all checkout widgets work together even when not inside a single form.
         */
        initCheckoutForm: function() {
            var self = this;
            
            // Check if there's a MPD place order widget
            var $placeOrderWidget = $('.mpd-place-order');
            if (!$placeOrderWidget.length) {
                return;
            }

            // Check if the place order button is inside a proper checkout form
            var $existingForm = $placeOrderWidget.closest('form.woocommerce-checkout, form.checkout');
            if ($existingForm.length) {
                // Already inside a checkout form, no need to do anything special
                return;
            }

            // Check if there's a MPD checkout form wrapper already
            var $mpdForm = $placeOrderWidget.find('form.mpd-checkout-form');
            if (!$mpdForm.length) {
                return;
            }

            // Initialize WooCommerce checkout on our form
            if (typeof $.fn.wc_checkout_form !== 'undefined') {
                $mpdForm.wc_checkout_form();
            }

            // Collect billing/shipping fields from other MPD widgets
            var $billingForm = $('.mpd-billing-form');
            var $shippingForm = $('.mpd-shipping-form');
            var $paymentMethods = $('.mpd-payment-methods');
            var $orderNotes = $('.mpd-order-notes');

            // Handle form submission
            $mpdForm.on('submit', function(e) {
                // Collect all field values from MPD widgets
                self.collectCheckoutFields($mpdForm, $billingForm, $shippingForm, $orderNotes);
            });

            // Listen for payment method changes and sync to form
            $(document).on('change', '.mpd-payment-methods input[name="payment_method"]', function() {
                var selectedValue = $(this).val();
                var $hiddenPayment = $mpdForm.find('input[type="hidden"][name="payment_method"]');
                
                if ($hiddenPayment.length) {
                    $hiddenPayment.val(selectedValue);
                } else {
                    $mpdForm.append('<input type="hidden" name="payment_method" value="' + selectedValue + '">');
                }
                
                // Trigger WooCommerce payment method change event
                $(document.body).trigger('payment_method_selected');
            });

            // Initialize payment method if one is already selected
            var $selectedPayment = $paymentMethods.find('input[name="payment_method"]:checked');
            if ($selectedPayment.length && !$mpdForm.find('input[type="hidden"][name="payment_method"]').length) {
                $mpdForm.append('<input type="hidden" name="payment_method" value="' + $selectedPayment.val() + '">');
            }

            // Also trigger checkout initialization
            $(document.body).trigger('init_checkout');
        },

        /**
         * Collect checkout fields from separate MPD widgets into the checkout form.
         *
         * @param {jQuery} $form The checkout form.
         * @param {jQuery} $billingForm Billing form widget.
         * @param {jQuery} $shippingForm Shipping form widget.
         * @param {jQuery} $orderNotes Order notes widget.
         */
        collectCheckoutFields: function($form, $billingForm, $shippingForm, $orderNotes) {
            var self = this;

            /**
             * Helper function to get field value properly (handles checkboxes/radios)
             */
            function getFieldValue($field) {
                var type = $field.attr('type');
                
                if (type === 'checkbox') {
                    // Only include checkbox if it's checked
                    return $field.is(':checked') ? $field.val() : null;
                } else if (type === 'radio') {
                    // Only include radio if it's checked
                    return $field.is(':checked') ? $field.val() : null;
                } else {
                    return $field.val();
                }
            }

            /**
             * Helper function to collect fields from a widget container
             */
            function collectFieldsFrom($container) {
                if (!$container.length) {
                    return;
                }
                
                $container.find('input, select, textarea').each(function() {
                    var $field = $(this);
                    var name = $field.attr('name');
                    var type = $field.attr('type');
                    
                    if (!name) {
                        return;
                    }
                    
                    // Skip if field already exists in form
                    if ($form.find('[name="' + name + '"]').length) {
                        return;
                    }
                    
                    // For radio buttons, skip unchecked ones (we only want the checked one)
                    if (type === 'radio' && !$field.is(':checked')) {
                        return;
                    }
                    
                    var value = getFieldValue($field);
                    
                    // For checkboxes, only add if checked (value will be null if unchecked)
                    if (value !== null) {
                        $form.append('<input type="hidden" name="' + name + '" value="' + value + '">');
                    }
                });
            }

            // Collect billing fields
            collectFieldsFrom($billingForm);

            // Collect shipping fields - check if "ship to different address" is checked
            if ($shippingForm.length) {
                var $shipToDifferent = $shippingForm.find('#ship-to-different-address-checkbox');
                
                // Always include the ship_to_different_address field if it exists
                if ($shipToDifferent.length && !$form.find('[name="ship_to_different_address"]').length) {
                    if ($shipToDifferent.is(':checked')) {
                        $form.append('<input type="hidden" name="ship_to_different_address" value="1">');
                    }
                }
                
                // Collect shipping address fields
                collectFieldsFrom($shippingForm);
            }

            // Collect order notes
            collectFieldsFrom($orderNotes);

            // Collect payment method from Payment Methods widget
            var $paymentMethods = $('.mpd-payment-methods');
            if ($paymentMethods.length) {
                var $selectedPayment = $paymentMethods.find('input[name="payment_method"]:checked');
                if ($selectedPayment.length && !$form.find('[name="payment_method"]').length) {
                    $form.append('<input type="hidden" name="payment_method" value="' + $selectedPayment.val() + '">');
                }
                
                // Also collect any payment gateway specific fields (like card numbers, etc.)
                $paymentMethods.find('.payment_box:visible input, .payment_box:visible select, .payment_box:visible textarea').each(function() {
                    var $field = $(this);
                    var name = $field.attr('name');
                    if (name && !$form.find('[name="' + name + '"]').length) {
                        $form.append('<input type="hidden" name="' + name + '" value="' + $field.val() + '">');
                    }
                });
            }
        },

        /**
         * Handle coupon form submission.
         *
         * @param {Event} e Form submit event.
         */
        handleCouponSubmit: function(e) {
            e.preventDefault();

            var self = this;
            var $form = $(e.currentTarget);
            var $wrapper = $form.closest('.mpd-checkout-coupon');
            var $button = $form.find('button[type="submit"]');
            var $input = $form.find('input[name="coupon_code"]');
            var couponCode = $input.val().trim();

            // Validate empty coupon code
            if (!couponCode) {
                self.showCouponMessage($wrapper, wc_checkout_params.i18n_required_coupon || 'Please enter a coupon code.', 'error');
                $input.focus();
                return false;
            }

            // Clear any existing messages
            self.clearCouponMessages($wrapper);

            // Disable button and show loading state
            $button.prop('disabled', true).addClass('loading');
            $wrapper.addClass('processing');

            // Check if wc_checkout_params exists
            var ajaxUrl = (typeof wc_checkout_params !== 'undefined') 
                ? wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon')
                : wc_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon');

            var nonce = (typeof wc_checkout_params !== 'undefined')
                ? wc_checkout_params.apply_coupon_nonce
                : wc_cart_params.apply_coupon_nonce;

            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {
                    coupon_code: couponCode,
                    security: nonce
                },
                dataType: 'html',
                success: function(response) {
                    var $response = $('<div>' + response + '</div>');
                    var $errorMsg = $response.find('.woocommerce-error');
                    var $successMsg = $response.find('.woocommerce-message');

                    if ($errorMsg.length > 0) {
                        var errorText = $errorMsg.find('li').first().text().trim() || $errorMsg.text().trim() || 'Coupon could not be applied.';
                        self.showCouponMessage($wrapper, errorText, 'error');
                    } else if (response.toLowerCase().indexOf('error') !== -1 && $successMsg.length === 0) {
                        self.showCouponMessage($wrapper, 'Coupon could not be applied.', 'error');
                    } else {
                        // Success
                        $input.val('');
                        var successText = $successMsg.text().trim() || 'Coupon code applied successfully.';
                        self.showCouponMessage($wrapper, successText, 'success');

                        // Update applied coupons display immediately
                        self.addAppliedCoupon($wrapper, couponCode);

                        // Trigger WooCommerce update checkout - this updates order review
                        $(document.body).trigger('update_checkout');
                        $(document.body).trigger('applied_coupon', [couponCode]);

                        // Also refresh order review manually as fallback (longer delay to let WC update first)
                        setTimeout(function() {
                            self.refreshOrderReview();
                        }, 800);
                    }
                },
                error: function() {
                    self.showCouponMessage($wrapper, 'An error occurred. Please try again.', 'error');
                },
                complete: function() {
                    $button.prop('disabled', false).removeClass('loading');
                    $wrapper.removeClass('processing');
                }
            });

            return false;
        },

        /**
         * Handle remove coupon click.
         *
         * @param {Event} e Click event.
         */
        handleRemoveCoupon: function(e) {
            e.preventDefault();

            var self = this;
            var $link = $(e.currentTarget);
            var $wrapper = $link.closest('.mpd-checkout-coupon');
            var $couponItem = $link.closest('.mpd-checkout-coupon__applied-item');
            var couponCode = $link.data('coupon') || $couponItem.text().replace('×', '').trim();

            // Add loading state
            $couponItem.css('opacity', '0.5');
            $wrapper.addClass('processing');

            // Check if wc_checkout_params exists
            var ajaxUrl = (typeof wc_checkout_params !== 'undefined') 
                ? wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_coupon')
                : wc_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_coupon');

            var nonce = (typeof wc_checkout_params !== 'undefined')
                ? wc_checkout_params.remove_coupon_nonce
                : wc_cart_params.remove_coupon_nonce;

            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {
                    coupon: couponCode,
                    security: nonce
                },
                dataType: 'html',
                success: function(response) {
                    var $response = $('<div>' + response + '</div>');
                    var $errorMsg = $response.find('.woocommerce-error');

                    if ($errorMsg.length === 0) {
                        // Success - remove the coupon item
                        $couponItem.slideUp(200, function() {
                            $(this).remove();
                        });

                        self.showCouponMessage($wrapper, 'Coupon has been removed.', 'success');

                        // Trigger WooCommerce update checkout
                        $(document.body).trigger('update_checkout');
                        $(document.body).trigger('removed_coupon', [couponCode]);
                        
                        // Also refresh order review manually as fallback
                        setTimeout(function() {
                            self.refreshOrderReview();
                        }, 300);
                        
                        // Also remove from our applied coupons display (redundant but ensures sync)
                        self.removeAppliedCoupon($wrapper, couponCode);
                    } else {
                        $couponItem.css('opacity', '1');
                        var errorText = $errorMsg.text().trim() || 'Could not remove coupon.';
                        self.showCouponMessage($wrapper, errorText, 'error');
                    }
                },
                error: function() {
                    $couponItem.css('opacity', '1');
                    self.showCouponMessage($wrapper, 'An error occurred.', 'error');
                },
                complete: function() {
                    $wrapper.removeClass('processing');
                }
            });

            return false;
        },

        /**
         * Handle remove coupon click from Order Review widget.
         *
         * @param {Event} e Click event.
         */
        handleOrderReviewRemoveCoupon: function(e) {
            e.preventDefault();

            var self = this;
            var $link = $(e.currentTarget);
            var $row = $link.closest('tr');
            var couponCode = $link.data('coupon');

            if (!couponCode) {
                // Try to extract from href
                var href = $link.attr('href');
                if (href) {
                    var match = href.match(/remove_coupon=([^&]+)/);
                    if (match) {
                        couponCode = decodeURIComponent(match[1]);
                    }
                }
            }

            if (!couponCode) {
                return false;
            }

            // Add loading state
            $row.css('opacity', '0.5');

            // Check if wc_checkout_params exists
            var ajaxUrl = (typeof wc_checkout_params !== 'undefined') 
                ? wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_coupon')
                : wc_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_coupon');

            var nonce = (typeof wc_checkout_params !== 'undefined')
                ? wc_checkout_params.remove_coupon_nonce
                : wc_cart_params.remove_coupon_nonce;

            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {
                    coupon: couponCode,
                    security: nonce
                },
                dataType: 'html',
                success: function(response) {
                    var $response = $('<div>' + response + '</div>');
                    var $errorMsg = $response.find('.woocommerce-error');

                    if ($errorMsg.length === 0) {
                        // Success - trigger WooCommerce update checkout to refresh order review
                        $(document.body).trigger('update_checkout');
                        $(document.body).trigger('removed_coupon', [couponCode]);

                        // Also refresh order review manually as fallback
                        setTimeout(function() {
                            self.refreshOrderReview();
                        }, 300);

                        // Show success message if we have a coupon widget
                        var $couponWrapper = $('.mpd-checkout-coupon');
                        if ($couponWrapper.length > 0) {
                            self.showCouponMessage($couponWrapper, 'Coupon has been removed.', 'success');
                            
                            // Remove the coupon from our applied coupons display
                            self.removeAppliedCoupon($couponWrapper, couponCode);
                        }
                    } else {
                        $row.css('opacity', '1');
                        var errorText = $errorMsg.text().trim() || 'Could not remove coupon.';
                        alert(errorText);
                    }
                },
                error: function() {
                    $row.css('opacity', '1');
                    alert('An error occurred while removing the coupon.');
                }
            });

            return false;
        },

        /**
         * Called when WooCommerce checkout is updated.
         *
         * @param {Object} data Response data from WooCommerce.
         */
        onCheckoutUpdated: function(data) {
            // WooCommerce automatically updates #order_review content via fragments
            // We just need to update our applied coupons display
            this.updateAppliedCoupons();
        },
        /**
         * Manually refresh the order review table.
         * This is a fallback in case WooCommerce's fragment update doesn't work.
         */
        refreshOrderReview: function() {
            var self = this;
            var $orderReviewWrapper = $('.mpd-order-review');
            var $orderReview = $orderReviewWrapper.find('#order_review');
            
            if ($orderReview.length === 0) {
                $orderReview = $('#order_review');
            }
            
            if ($orderReview.length === 0) {
                return;
            }

            // Add loading state with fallback for missing blockUI
            $orderReview.addClass('processing');
            if (typeof $.fn.block === 'function') {
                $orderReview.block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });
            } else {
                $orderReview.css('opacity', '0.5');
            }

            // Reload the order review content via AJAX
            var ajaxUrl = (typeof mpd_checkout_params !== 'undefined' && mpd_checkout_params.ajax_url)
                ? mpd_checkout_params.ajax_url
                : (typeof wc_checkout_params !== 'undefined' && wc_checkout_params.ajax_url)
                    ? wc_checkout_params.ajax_url
                    : (typeof ajaxurl !== 'undefined')
                        ? ajaxurl
                        : '/wp-admin/admin-ajax.php';

            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {
                    action: 'mpd_refresh_order_review'
                },
                dataType: 'html',
                success: function(response) {
                    if (response && response.length > 10 && response.indexOf('0') !== 0) {
                        $orderReview.html(response);
                    } else {
                        $(document.body).trigger('update_checkout');
                    }
                    
                    // Update applied coupons display
                    self.updateAppliedCoupons();
                },
                error: function() {
                    // Fallback: try triggering WooCommerce's native update
                    $(document.body).trigger('update_checkout');
                },
                complete: function() {
                    $orderReview.removeClass('processing').css('opacity', '1');
                    if (typeof $.fn.unblock === 'function') {
                        $orderReview.unblock();
                    }
                }
            });
        },
        /**
         * Update applied coupons display.
         */
        updateAppliedCoupons: function() {
            var $wrapper = $('.mpd-checkout-coupon');

            if ($wrapper.length === 0) {
                return;
            }

            // Get applied coupons from the order review table
            var appliedCoupons = [];
            $('.mpd-order-review .cart-discount, #order_review .cart-discount').each(function() {
                var $row = $(this);
                var couponClass = $row.attr('class');
                var match = couponClass.match(/coupon-([^\s]+)/);
                if (match && match[1]) {
                    appliedCoupons.push(match[1]);
                }
            });

            // Update our applied coupons display
            var $appliedSection = $wrapper.find('.mpd-checkout-coupon__applied');
            
            if (appliedCoupons.length > 0) {
                if ($appliedSection.length === 0) {
                    $appliedSection = $('<div class="mpd-checkout-coupon__applied"></div>');
                    $wrapper.append($appliedSection);
                }
                
                $appliedSection.empty();
                appliedCoupons.forEach(function(couponCode) {
                    var $item = $('<span class="mpd-checkout-coupon__applied-item">' +
                        couponCode.toUpperCase() +
                        '<a href="#" class="mpd-checkout-coupon__remove" data-coupon="' + couponCode + '">×</a>' +
                    '</span>');
                    $appliedSection.append($item);
                });
                $appliedSection.show();
            } else if ($appliedSection.length > 0) {
                $appliedSection.empty().hide();
            }
        },

        /**
         * Remove applied coupon from display.
         *
         * @param {jQuery} $wrapper Coupon form wrapper.
         * @param {string} couponCode Coupon code to remove.
         */
        removeAppliedCoupon: function($wrapper, couponCode) {
            var $appliedSection = $wrapper.find('.mpd-checkout-coupon__applied');
            
            if ($appliedSection.length === 0) {
                return;
            }

            // Find and remove the coupon item
            $appliedSection.find('.mpd-checkout-coupon__applied-item').each(function() {
                var itemText = $(this).text().replace('×', '').trim().toLowerCase();
                if (itemText === couponCode.toLowerCase()) {
                    $(this).slideUp(200, function() {
                        $(this).remove();
                    });
                }
            });
        },

        /**
         * Add applied coupon to display.
         *
         * @param {jQuery} $wrapper Coupon form wrapper.
         * @param {string} couponCode Applied coupon code.
         */
        addAppliedCoupon: function($wrapper, couponCode) {
            var $appliedSection = $wrapper.find('.mpd-checkout-coupon__applied');
            
            if ($appliedSection.length === 0) {
                // Create the section if it doesn't exist
                $appliedSection = $('<div class="mpd-checkout-coupon__applied"></div>');
                $wrapper.append($appliedSection);
            }

            // Show the section (it may be hidden if was empty)
            $appliedSection.show();

            // Check if coupon already displayed
            var exists = false;
            $appliedSection.find('.mpd-checkout-coupon__applied-item').each(function() {
                if ($(this).text().replace('×', '').trim().toLowerCase() === couponCode.toLowerCase()) {
                    exists = true;
                    return false;
                }
            });

            if (!exists) {
                var $item = $('<span class="mpd-checkout-coupon__applied-item">' +
                    couponCode.toUpperCase() +
                    '<a href="#" class="mpd-checkout-coupon__remove" data-coupon="' + couponCode + '">×</a>' +
                '</span>');
                $item.hide();
                $appliedSection.append($item);
                $item.fadeIn(200);
            }
        },

        /**
         * Show coupon message.
         *
         * @param {jQuery} $wrapper Form wrapper.
         * @param {string} message Message text.
         * @param {string} type Message type (success/error).
         */
        showCouponMessage: function($wrapper, message, type) {
            this.clearCouponMessages($wrapper);

            var cssClass = type === 'error' ? 'woocommerce-error' : 'woocommerce-message';
            var $message = $('<div class="' + cssClass + ' mpd-checkout-coupon__message">' + message + '</div>');
            
            $wrapper.prepend($message);

            // Auto-hide success messages
            if (type === 'success') {
                setTimeout(function() {
                    $message.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        },

        /**
         * Clear coupon messages.
         *
         * @param {jQuery} $wrapper Form wrapper.
         */
        clearCouponMessages: function($wrapper) {
            $wrapper.find('.mpd-checkout-coupon__message').remove();
            $wrapper.find('.woocommerce-error, .woocommerce-message').filter(':not(.woocommerce-info)').remove();
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        // Only init on frontend checkout, not in editor
        if ($('.mpd-checkout-coupon').length > 0 || $('.mpd-order-review').length > 0) {
            if (typeof wc_checkout_params !== 'undefined') {
                MPDCheckoutWidgets.init();
            } else {
                // Try again after a short delay in case scripts load late
                setTimeout(function() {
                    if (typeof wc_checkout_params !== 'undefined') {
                        MPDCheckoutWidgets.init();
                    }
                }, 500);
            }
        }
    });

    // Reinitialize after Elementor frontend loads
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-checkout-coupon.default', function() {
                if (typeof wc_checkout_params !== 'undefined') {
                    MPDCheckoutWidgets.init();
                }
            });
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-order-review.default', function() {
                if (typeof wc_checkout_params !== 'undefined') {
                    MPDCheckoutWidgets.init();
                }
            });
        }
    });

})(jQuery);
