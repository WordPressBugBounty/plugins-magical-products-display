/**
 * MPD Mini Cart Widget JavaScript
 *
 * Handles dropdown hover, slide-out panel, floating cart,
 * and AJAX cart fragment updates.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

(function ($) {
    'use strict';

    /**
     * Mini Cart Handler
     */
    var MPDMiniCart = {
        /**
         * Initialize mini cart functionality.
         */
        init: function () {
            this.cacheElements();
            this.bindEvents();
            this.initFragmentRefresh();
        },

        /**
         * Cache DOM elements.
         */
        cacheElements: function () {
            this.$window = $(window);
            this.$body = $('body');
            this.$miniCarts = $('.mpd-mini-cart');
        },

        /**
         * Bind events.
         */
        bindEvents: function () {
            var self = this;

            // Slide-out panel toggle
            this.$body.on('click', '.mpd-mini-cart-toggle', function (e) {
                var $cart = $(this).closest('.mpd-mini-cart');
                var $panel = $cart.find('.mpd-mini-cart-panel');

                if ($panel.length) {
                    e.preventDefault();
                    self.toggleSlidePanel($cart);
                }
            });

            // Close panel button
            this.$body.on('click', '.mpd-panel-close', function () {
                var $cart = $(this).closest('.mpd-mini-cart');
                self.closeSlidePanel($cart);
            });

            // Close panel on overlay click
            this.$body.on('click', '.mpd-mini-cart-overlay', function () {
                var $cart = $(this).closest('.mpd-mini-cart');
                self.closeSlidePanel($cart);
            });

            // Close panel on ESC key
            $(document).on('keydown', function (e) {
                if (e.key === 'Escape') {
                    self.closeAllPanels();
                }
            });

            // Handle remove item clicks
            this.$body.on('click', '.mpd-mini-cart-remove', function (e) {
                e.preventDefault();
                var $this = $(this);
                var removeUrl = $this.attr('href');
                
                if (removeUrl) {
                    self.removeItem($this, removeUrl);
                }
            });

            // Handle quantity changes in dropdown (if enabled)
            this.$body.on('change', '.mpd-mini-cart-dropdown .qty', function () {
                var $this = $(this);
                self.updateQuantity($this);
            });

            // Touch device handling for dropdown
            if ('ontouchstart' in window) {
                this.$body.on('touchstart', '.mpd-mini-cart-toggle', function (e) {
                    var $cart = $(this).closest('.mpd-mini-cart');
                    var $dropdown = $cart.find('.mpd-mini-cart-dropdown');

                    if ($dropdown.length && !$dropdown.is(':visible')) {
                        e.preventDefault();
                        self.toggleDropdown($cart);
                    }
                });
            }
        },

        /**
         * Toggle slide-out panel.
         *
         * @param {jQuery} $cart Mini cart container.
         */
        toggleSlidePanel: function ($cart) {
            var $panel = $cart.find('.mpd-mini-cart-panel');
            var $overlay = $cart.find('.mpd-mini-cart-overlay');

            if ($panel.hasClass('active')) {
                this.closeSlidePanel($cart);
            } else {
                $panel.addClass('active');
                $overlay.addClass('active');
                this.$body.addClass('mpd-cart-panel-open');
            }
        },

        /**
         * Close slide-out panel.
         *
         * @param {jQuery} $cart Mini cart container.
         */
        closeSlidePanel: function ($cart) {
            var $panel = $cart.find('.mpd-mini-cart-panel');
            var $overlay = $cart.find('.mpd-mini-cart-overlay');

            $panel.removeClass('active');
            $overlay.removeClass('active');
            this.$body.removeClass('mpd-cart-panel-open');
        },

        /**
         * Close all open panels.
         */
        closeAllPanels: function () {
            var self = this;
            this.$miniCarts.each(function () {
                self.closeSlidePanel($(this));
            });
        },

        /**
         * Toggle dropdown for touch devices.
         *
         * @param {jQuery} $cart Mini cart container.
         */
        toggleDropdown: function ($cart) {
            var $dropdown = $cart.find('.mpd-mini-cart-dropdown');
            $dropdown.toggleClass('active');
        },

        /**
         * Remove item from cart via AJAX.
         *
         * @param {jQuery} $button Remove button element.
         * @param {string} removeUrl Remove URL.
         */
        removeItem: function ($button, removeUrl) {
            var self = this;
            var $product = $button.closest('.mpd-mini-cart-product');
            
            // Add loading state
            $product.addClass('mpd-removing');

            $.ajax({
                type: 'GET',
                url: removeUrl,
                success: function () {
                    // Trigger WooCommerce cart fragments refresh
                    $(document.body).trigger('wc_fragment_refresh');
                    $(document.body).trigger('removed_from_cart');
                },
                error: function () {
                    $product.removeClass('mpd-removing');
                }
            });
        },

        /**
         * Update quantity via AJAX.
         *
         * @param {jQuery} $input Quantity input element.
         */
        updateQuantity: function ($input) {
            var cartItemKey = $input.data('cart-item-key');
            var quantity = $input.val();

            if (!cartItemKey) {
                return;
            }

            $.ajax({
                type: 'POST',
                url: wc_cart_fragments_params ? wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'update_cart_item') : '',
                data: {
                    cart_item_key: cartItemKey,
                    quantity: quantity
                },
                success: function () {
                    $(document.body).trigger('wc_fragment_refresh');
                    $(document.body).trigger('updated_cart_totals');
                }
            });
        },

        /**
         * Initialize WooCommerce cart fragment refresh handling.
         */
        initFragmentRefresh: function () {
            var self = this;

            // Listen for cart fragment updates (remove, quantity change, etc.)
            $(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function () {
                self.cacheElements();
            });

            // Listen for added to cart event (fired by mpd-add-to-cart.js and WC core)
            $(document.body).on('added_to_cart', function (event, fragments, cart_hash, $button) {
                self.handleAddedToCart(fragments, $button);
            });
        },

        /**
         * Handle added to cart event.
         *
         * @param {object} fragments WooCommerce fragments returned by server.
         * @param {jQuery} $button   Add to cart button.
         */
        handleAddedToCart: function (fragments, $button) {
            var self = this;

            // Apply any fragments not already replaced (e.g. from WC core AJAX add-to-cart)
            if (fragments && typeof fragments === 'object') {
                $.each(fragments, function (key, value) {
                    $(key).replaceWith(value);
                });
            }

            // Re-cache elements after DOM update
            self.cacheElements();

            // Animate the counter on each mini cart instance
            self.$miniCarts.each(function () {
                var $cart = $(this);
                var $counter = $cart.find('.mpd-mini-cart-counter');

                if ($counter.length) {
                    $counter.addClass('mpd-pulse');
                    setTimeout(function () {
                        $counter.removeClass('mpd-pulse');
                    }, 600);
                }
            });
        }
    };

    /**
     * Initialize on document ready.
     */
    $(document).ready(function () {
        MPDMiniCart.init();
    });

    /**
     * Re-initialize on Elementor frontend init (for live preview).
     */
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-mini-cart.default', function () {
                MPDMiniCart.init();
            });
        }
    });

})(jQuery);
