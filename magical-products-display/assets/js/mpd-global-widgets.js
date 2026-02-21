/**
 * MPD Global Widgets JavaScript
 *
 * Handles interactions for global widgets:
 * - Header Cart
 * - Wishlist
 * - Product Comparison
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

(function($) {
    'use strict';

    /**
     * Wishlist Handler
     */
    const MPDWishlist = {
        cookieName: 'mpd_wishlist',
        
        init: function() {
            this.bindEvents();
            this.updateButtonStates();
            // Initialize count from cookie on page load
            var wishlist = this.getWishlist();
            this.updateWishlistCount(wishlist.length);
            // Populate header dropdown if exists
            this.populateHeaderDropdown();
        },

        bindEvents: function() {
            // Add to wishlist buttons (supports both class names)
            $(document).on('click', '.mpd-add-to-wishlist, .mpd-wishlist-btn', this.addToWishlist.bind(this));
            
            // Remove from wishlist
            $(document).on('click', '.mpd-wishlist-remove', this.removeFromWishlist.bind(this));
            
            // Remove from header dropdown
            $(document).on('click', '.mpd-header-wc-remove-item', this.removeFromHeaderDropdown.bind(this));
            
            // Clear all wishlist items from header
            $(document).on('click', '.mpd-clear-wishlist', this.clearWishlist.bind(this));
            
            // Add all to cart
            $(document).on('click', '.mpd-wishlist-add-all', this.addAllToCart.bind(this));
            
            // Share wishlist toggle
            $(document).on('click', '.mpd-wishlist-share-toggle', this.toggleShare.bind(this));
            
            // Copy link
            $(document).on('click', '.mpd-wishlist-copy-link', this.copyLink.bind(this));
            
            // Listen for WooCommerce added_to_cart event to show View Cart
            $(document.body).on('added_to_cart', this.onAddedToCart.bind(this));
            
            // Listen for new products loaded (infinite scroll)
            $(document).on('mpd_products_loaded', this.updateButtonStates.bind(this));
        },
        
        /**
         * Update wishlist button states based on cookie
         */
        updateButtonStates: function() {
            const wishlist = this.getWishlist();
            $('.mpd-wishlist-btn, .mpd-add-to-wishlist').each(function() {
                const productId = parseInt($(this).data('product-id'));
                if (wishlist.includes(productId)) {
                    $(this).addClass('in-wishlist');
                } else {
                    $(this).removeClass('in-wishlist');
                }
            });
        },

        getWishlist: function() {
            const cookie = this.getCookie(this.cookieName);
            if (!cookie) return [];
            return cookie.split(',').map(id => parseInt(id)).filter(id => id > 0);
        },

        saveWishlist: function(list) {
            this.setCookie(this.cookieName, list.join(','), 30);
        },

        addToWishlist: function(e) {
            e.preventDefault();
            const $btn = $(e.currentTarget);
            const productId = parseInt($btn.data('product-id'));
            
            if (!productId) return;

            let wishlist = this.getWishlist();
            const now = Math.floor(Date.now() / 1000);
            
            if (wishlist.includes(productId)) {
                // Already in wishlist, remove it
                wishlist = wishlist.filter(id => id !== productId);
                $btn.removeClass('in-wishlist');
                this.removeDateEntry(productId);
                this.showNotice('Removed from wishlist');
            } else {
                // Add to wishlist
                wishlist.push(productId);
                $btn.addClass('in-wishlist');
                this.addDateEntry(productId, now);
                this.showNotice('Added to wishlist');
            }

            this.saveWishlist(wishlist);
            this.updateWishlistCount(wishlist.length);
            this.populateHeaderDropdown();
            $(document).trigger('mpd_wishlist_updated', [wishlist]);
        },

        removeFromWishlist: function(e) {
            e.preventDefault();
            e.stopPropagation();
            const $btn = $(e.currentTarget);
            const productId = parseInt($btn.data('product-id'));
            const $item = $btn.closest('.mpd-wishlist-item');
            
            if (!productId) return;

            let wishlist = this.getWishlist();
            wishlist = wishlist.filter(id => id !== productId);
            
            this.saveWishlist(wishlist);
            this.removeDateEntry(productId);
            
            // Animate removal
            $item.fadeOut(300, function() {
                $(this).remove();
                
                // Check if wishlist is empty
                if ($('.mpd-wishlist-item').length === 0) {
                    location.reload();
                }
            });

            this.updateWishlistCount(wishlist.length);
            this.showNotice('Removed from wishlist');
            $(document).trigger('mpd_wishlist_updated', [wishlist]);
        },

        /**
         * Add all wishlist products to cart via sequential AJAX
         */
        addAllToCart: function(e) {
            e.preventDefault();
            const $btn = $(e.currentTarget);
            const idsStr = $btn.data('product-ids');
            
            if (!idsStr) return;
            
            const ids = String(idsStr).split(',').map(id => parseInt(id)).filter(id => id > 0);
            if (!ids.length) return;
            
            $btn.prop('disabled', true).addClass('loading');
            
            let completed = 0;
            const self = this;
            
            // Add each product to cart sequentially
            const addNext = function() {
                if (completed >= ids.length) {
                    $btn.prop('disabled', false).removeClass('loading');
                    self.showNotice('All items added to cart!');
                    $(document.body).trigger('wc_fragment_refresh');
                    return;
                }
                
                $.ajax({
                    url: (typeof wc_add_to_cart_params !== 'undefined') ? wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'add_to_cart') : '/?wc-ajax=add_to_cart',
                    type: 'POST',
                    data: {
                        product_id: ids[completed],
                        quantity: 1
                    },
                    success: function() {
                        completed++;
                        addNext();
                    },
                    error: function() {
                        completed++;
                        addNext();
                    }
                });
            };
            
            addNext();
        },

        /**
         * Toggle share links visibility (debounced, click-outside to close)
         */
        toggleShare: function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $btn = $(e.currentTarget);

            // Debounce: prevent rapid double-click
            if ($btn.data('mpd-toggling')) return;
            $btn.data('mpd-toggling', true);

            const $links = $btn.siblings('.mpd-wishlist-share-links');
            const isOpen = $links.is(':visible');

            if (isOpen) {
                // Close
                $links.slideUp(200);
                $btn.attr('aria-expanded', 'false');
                $(document).off('click.mpdShareOutside');
            } else {
                // Open
                $links.slideDown(200);
                $btn.attr('aria-expanded', 'true');

                // Click outside to close
                setTimeout(function() {
                    $(document).on('click.mpdShareOutside', function(ev) {
                        if (!$(ev.target).closest('.mpd-wishlist-share').length) {
                            $links.slideUp(200);
                            $btn.attr('aria-expanded', 'false');
                            $(document).off('click.mpdShareOutside');
                        }
                    });
                }, 10);
            }

            // Release debounce lock
            setTimeout(function() {
                $btn.data('mpd-toggling', false);
            }, 300);
        },

        /**
         * Copy wishlist share link to clipboard
         */
        copyLink: function(e) {
            e.preventDefault();
            const $btn = $(e.currentTarget);
            const url = $btn.data('url');
            
            if (!url) return;
            
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(() => {
                    this.showNotice('Link copied to clipboard!');
                }).catch(() => {
                    this.fallbackCopy(url);
                });
            } else {
                this.fallbackCopy(url);
            }
        },

        fallbackCopy: function(text) {
            const $temp = $('<input>');
            $('body').append($temp);
            $temp.val(text).select();
            try {
                document.execCommand('copy');
                this.showNotice('Link copied to clipboard!');
            } catch(err) {
                this.showNotice('Failed to copy link');
            }
            $temp.remove();
        },

        /**
         * After WooCommerce AJAX add-to-cart, show View Cart button
         */
        onAddedToCart: function(e, fragments, cart_hash, $button) {
            if (!$button || !$button.closest('.mpd-wishlist-wrapper, .mpd-wishlist-actions').length) return;
            
            var cartUrl = (typeof wc_add_to_cart_params !== 'undefined') ? wc_add_to_cart_params.cart_url : '';
            if (!cartUrl) return;
            
            // Only add "View Cart" if not already there
            if (!$button.siblings('.added_to_cart').length) {
                $button.after(' <a href="' + cartUrl + '" class="added_to_cart wc-forward">' + (wc_add_to_cart_params.i18n_view_cart || 'View cart') + '</a>');
            }
        },

        /**
         * Date tracking helpers
         */
        getDateEntries: function() {
            const cookie = this.getCookie('mpd_wishlist_dates');
            if (!cookie) return {};
            const entries = {};
            cookie.split(',').forEach(function(entry) {
                const parts = entry.split(':');
                if (parts.length === 2) {
                    entries[parseInt(parts[0])] = parseInt(parts[1]);
                }
            });
            return entries;
        },

        saveDateEntries: function(entries) {
            const parts = [];
            for (const id in entries) {
                if (entries.hasOwnProperty(id)) {
                    parts.push(id + ':' + entries[id]);
                }
            }
            this.setCookie('mpd_wishlist_dates', parts.join(','), 30);
        },

        addDateEntry: function(productId, timestamp) {
            const entries = this.getDateEntries();
            entries[productId] = timestamp;
            this.saveDateEntries(entries);
        },

        removeDateEntry: function(productId) {
            const entries = this.getDateEntries();
            delete entries[productId];
            this.saveDateEntries(entries);
        },

        updateWishlistCount: function(count) {
            $('.mpd-wishlist-count').text(count);
            // Show/hide badge
            if (count === 0) {
                $('.mpd-wishlist-count').addClass('mpd-count-hidden');
            } else {
                $('.mpd-wishlist-count').removeClass('mpd-count-hidden');
            }
        },

        /**
         * Populate the header wishlist dropdown with items from cookie
         */
        populateHeaderDropdown: function() {
            var wishlist = this.getWishlist();
            var $dropdownContent = $('.mpd-wishlist-items');
            
            if (!$dropdownContent.length) return;
            
            if (wishlist.length === 0) {
                $dropdownContent.html('<p class="mpd-empty-message">Your wishlist is empty.</p>');
                return;
            }
            
            // Fetch product data via AJAX
            var self = this;
            $.ajax({
                url: (typeof mpdGlobalWidgets !== 'undefined') ? mpdGlobalWidgets.ajaxUrl : (typeof ajaxurl !== 'undefined' ? ajaxurl : '/wp-admin/admin-ajax.php'),
                type: 'POST',
                data: {
                    action: 'mpd_get_wishlist_items',
                    product_ids: wishlist.join(','),
                    nonce: (typeof mpdGlobalWidgets !== 'undefined') ? mpdGlobalWidgets.nonce : ''
                },
                success: function(response) {
                    if (response.success && response.data.html) {
                        $dropdownContent.html(response.data.html);
                    } else {
                        // Fallback: show basic items from cookie
                        self.populateDropdownFallback($dropdownContent, wishlist);
                    }
                },
                error: function() {
                    self.populateDropdownFallback($dropdownContent, wishlist);
                }
            });
        },

        /**
         * Fallback: populate dropdown with basic product links
         */
        populateDropdownFallback: function($container, wishlist) {
            var html = '';
            wishlist.forEach(function(id) {
                html += '<div class="mpd-header-wc-item-row" data-product-id="' + id + '">';
                html += '<span class="mpd-header-wc-item-name">Product #' + id + '</span>';
                html += '<button type="button" class="mpd-header-wc-remove-item" data-product-id="' + id + '" title="Remove"><i class="eicon-close"></i></button>';
                html += '</div>';
            });
            $container.html(html);
        },

        /**
         * Remove item from header dropdown
         */
        removeFromHeaderDropdown: function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $btn = $(e.currentTarget);
            var productId = parseInt($btn.data('product-id'));
            
            if (!productId) return;
            
            var wishlist = this.getWishlist();
            wishlist = wishlist.filter(function(id) { return id !== productId; });
            this.saveWishlist(wishlist);
            this.removeDateEntry(productId);
            this.updateWishlistCount(wishlist.length);
            this.updateButtonStates();
            
            // Remove the row
            $btn.closest('.mpd-header-wc-item-row').fadeOut(200, function() {
                $(this).remove();
                if ($('.mpd-wishlist-items .mpd-header-wc-item-row').length === 0) {
                    $('.mpd-wishlist-items').html('<p class="mpd-empty-message">Your wishlist is empty.</p>');
                }
            });
            
            $(document).trigger('mpd_wishlist_updated', [wishlist]);
        },

        /**
         * Clear entire wishlist
         */
        clearWishlist: function(e) {
            e.preventDefault();
            this.saveWishlist([]);
            this.saveDateEntries({});
            this.updateWishlistCount(0);
            this.updateButtonStates();
            $('.mpd-wishlist-items').html('<p class="mpd-empty-message">Your wishlist is empty.</p>');
            $(document).trigger('mpd_wishlist_updated', [[]]);
            this.showNotice('Wishlist cleared');
        },

        showNotice: function(message) {
            const $notice = $('<div class="mpd-notice">' + message + '</div>');
            $('body').append($notice);
            
            setTimeout(function() {
                $notice.addClass('show');
            }, 10);
            
            setTimeout(function() {
                $notice.removeClass('show');
                setTimeout(function() {
                    $notice.remove();
                }, 300);
            }, 2000);
        },

        getCookie: function(name) {
            const value = '; ' + document.cookie;
            const parts = value.split('; ' + name + '=');
            if (parts.length === 2) return parts.pop().split(';').shift();
            return '';
        },

        setCookie: function(name, value, days) {
            let expires = '';
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            document.cookie = name + '=' + (value || '') + expires + '; path=/';
        }
    };

    /**
     * Comparison Handler
     */
    const MPDComparison = {
        cookieName: 'mpd_comparison_list',
        maxItems: 4,
        
        init: function() {
            this.bindEvents();
            this.updateButtonStates();
            // Initialize count from cookie on page load
            var compareList = this.getCompareList();
            this.updateCompareCount(compareList.length);
            // Populate header dropdown if exists
            this.populateHeaderDropdown();
        },

        bindEvents: function() {
            // Add to comparison buttons (supports both class names)
            $(document).on('click', '.mpd-add-to-compare, .mpd-compare-btn', this.addToCompare.bind(this));
            
            // Remove from comparison
            $(document).on('click', '.mpd-comparison-remove', this.removeFromCompare.bind(this));
            
            // Remove from header dropdown
            $(document).on('click', '.mpd-header-compare-remove-item', this.removeFromHeaderDropdown.bind(this));
            
            // Clear all (both comparison page and header dropdown)
            $(document).on('click', '.mpd-comparison-clear, .mpd-clear-compare', this.clearAll.bind(this));
            
            // Listen for new products loaded (infinite scroll)
            $(document).on('mpd_products_loaded', this.updateButtonStates.bind(this));
        },
        
        /**
         * Update comparison button states based on cookie
         */
        updateButtonStates: function() {
            const compareList = this.getCompareList();
            $('.mpd-compare-btn, .mpd-add-to-compare').each(function() {
                const productId = parseInt($(this).data('product-id'));
                if (compareList.includes(productId)) {
                    $(this).addClass('in-comparison');
                } else {
                    $(this).removeClass('in-comparison');
                }
            });
        },

        getCompareList: function() {
            const cookie = this.getCookie(this.cookieName);
            if (!cookie) return [];
            return cookie.split(',').map(id => parseInt(id)).filter(id => id > 0);
        },

        saveCompareList: function(list) {
            this.setCookie(this.cookieName, list.join(','), 7);
        },

        addToCompare: function(e) {
            e.preventDefault();
            const $btn = $(e.currentTarget);
            const productId = parseInt($btn.data('product-id'));
            
            if (!productId) return;

            let compareList = this.getCompareList();
            
            if (compareList.includes(productId)) {
                // Already in list, remove it
                compareList = compareList.filter(id => id !== productId);
                $btn.removeClass('in-compare');
                this.showNotice('Removed from comparison');
            } else {
                // Check max items
                if (compareList.length >= this.maxItems) {
                    this.showNotice('Maximum ' + this.maxItems + ' products can be compared');
                    return;
                }
                
                // Add to list
                compareList.push(productId);
                $btn.addClass('in-compare');
                this.showNotice('Added to comparison');
            }

            this.saveCompareList(compareList);
            this.updateCompareCount(compareList.length);
            this.populateHeaderDropdown();
            $(document).trigger('mpd_comparison_updated', [compareList]);
        },

        removeFromCompare: function(e) {
            e.preventDefault();
            const $btn = $(e.currentTarget);
            const productId = parseInt($btn.data('product-id'));
            const $col = $btn.closest('td, .mpd-comparison-product');
            
            if (!productId) return;

            let compareList = this.getCompareList();
            compareList = compareList.filter(id => id !== productId);
            
            this.saveCompareList(compareList);
            
            // Reload to update table
            location.reload();

            this.updateCompareCount(compareList.length);
            $(document).trigger('mpd_comparison_updated', [compareList]);
        },

        clearAll: function(e) {
            e.preventDefault();
            this.saveCompareList([]);
            this.updateCompareCount(0);
            this.updateButtonStates();
            
            // Update header dropdown if present
            var $dropdownContent = $('.mpd-compare-items');
            if ($dropdownContent.length) {
                $dropdownContent.html('<p class="mpd-empty-message">No products to compare.</p>');
                this.showNotice('Compare list cleared');
            } else {
                // On comparison page, reload
                location.reload();
            }
            
            $(document).trigger('mpd_comparison_updated', [[]]);
        },

        updateCompareCount: function(count) {
            // Support both class names used in different widgets
            $('.mpd-comparison-count, .mpd-compare-count').text(count);
            // Show/hide badge
            if (count === 0) {
                $('.mpd-comparison-count, .mpd-compare-count').addClass('mpd-count-hidden');
            } else {
                $('.mpd-comparison-count, .mpd-compare-count').removeClass('mpd-count-hidden');
            }
        },

        /**
         * Populate the header compare dropdown with items from cookie
         */
        populateHeaderDropdown: function() {
            var compareList = this.getCompareList();
            var $dropdownContent = $('.mpd-compare-items');
            
            if (!$dropdownContent.length) return;
            
            if (compareList.length === 0) {
                $dropdownContent.html('<p class="mpd-empty-message">No products to compare.</p>');
                return;
            }
            
            // Fetch product data via AJAX
            var self = this;
            $.ajax({
                url: (typeof mpdGlobalWidgets !== 'undefined') ? mpdGlobalWidgets.ajaxUrl : (typeof ajaxurl !== 'undefined' ? ajaxurl : '/wp-admin/admin-ajax.php'),
                type: 'POST',
                data: {
                    action: 'mpd_get_compare_items',
                    product_ids: compareList.join(','),
                    nonce: (typeof mpdGlobalWidgets !== 'undefined') ? mpdGlobalWidgets.nonce : ''
                },
                success: function(response) {
                    if (response.success && response.data.html) {
                        $dropdownContent.html(response.data.html);
                    } else {
                        self.populateDropdownFallback($dropdownContent, compareList);
                    }
                },
                error: function() {
                    self.populateDropdownFallback($dropdownContent, compareList);
                }
            });
        },

        /**
         * Fallback: populate dropdown with basic product links
         */
        populateDropdownFallback: function($container, compareList) {
            var html = '';
            compareList.forEach(function(id) {
                html += '<div class="mpd-header-wc-item-row" data-product-id="' + id + '">';
                html += '<span class="mpd-header-wc-item-name">Product #' + id + '</span>';
                html += '<button type="button" class="mpd-header-compare-remove-item" data-product-id="' + id + '" title="Remove"><i class="eicon-close"></i></button>';
                html += '</div>';
            });
            $container.html(html);
        },

        /**
         * Remove item from header dropdown
         */
        removeFromHeaderDropdown: function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $btn = $(e.currentTarget);
            var productId = parseInt($btn.data('product-id'));
            
            if (!productId) return;
            
            var compareList = this.getCompareList();
            compareList = compareList.filter(function(id) { return id !== productId; });
            this.saveCompareList(compareList);
            this.updateCompareCount(compareList.length);
            this.updateButtonStates();
            
            $btn.closest('.mpd-header-wc-item-row').fadeOut(200, function() {
                $(this).remove();
                if ($('.mpd-compare-items .mpd-header-wc-item-row').length === 0) {
                    $('.mpd-compare-items').html('<p class="mpd-empty-message">No products to compare.</p>');
                }
            });
            
            $(document).trigger('mpd_comparison_updated', [compareList]);
        },

        showNotice: function(message) {
            MPDWishlist.showNotice(message);
        },

        getCookie: function(name) {
            return MPDWishlist.getCookie(name);
        },

        setCookie: function(name, value, days) {
            MPDWishlist.setCookie(name, value, days);
        }
    };

    /**
     * Quick View Handler
     */
    const MPDQuickView = {
        init: function() {
            this.bindEvents();
            // Don't create modal on init - only create when quick view button is clicked
        },

        bindEvents: function() {
            $(document).on('click', '.mpd-quick-view-btn', this.openQuickView.bind(this));
            $(document).on('click', '.mpd-quick-view-overlay, .mpd-quick-view-close', this.closeQuickView.bind(this));
            $(document).on('keyup', this.handleKeyup.bind(this));
        },

        createModal: function() {
            if ($('#mpd-quick-view-modal').length) return;
            
            var modalHtml = '<div id="mpd-quick-view-modal" class="mpd-quick-view-modal">' +
                '<div class="mpd-quick-view-overlay"></div>' +
                '<div class="mpd-quick-view-container">' +
                    '<button type="button" class="mpd-quick-view-close">&times;</button>' +
                    '<div class="mpd-quick-view-content"></div>' +
                    '<div class="mpd-quick-view-loading"><div class="mpd-spinner"></div></div>' +
                '</div>' +
            '</div>';
            
            $('body').append(modalHtml);
        },

        openQuickView: function(e) {
            e.preventDefault();
            var $btn = $(e.currentTarget);
            var productId = $btn.data('product-id');
            
            if (!productId) return;

            // Create modal if not exists
            this.createModal();

            var $modal = $('#mpd-quick-view-modal');
            var $content = $modal.find('.mpd-quick-view-content');
            var $loading = $modal.find('.mpd-quick-view-loading');
            
            // Show modal with loading
            $modal.addClass('is-open');
            $('body').addClass('mpd-quick-view-open');
            $content.html('');
            $loading.show();

            // Fetch product data via AJAX
            $.ajax({
                url: mpdGlobalWidgets.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'mpd_quick_view',
                    product_id: productId,
                    nonce: mpdGlobalWidgets.nonce
                },
                success: function(response) {
                    $loading.hide();
                    if (response.success) {
                        $content.html(response.data.html);
                        // Initialize WooCommerce scripts
                        $(document.body).trigger('wc_fragment_refresh');
                        // Initialize variations
                        if ($.fn.wc_variation_form) {
                            $content.find('.variations_form').each(function() {
                                $(this).wc_variation_form();
                            });
                        }
                    } else {
                        $content.html('<div class="mpd-quick-view-error">' + (response.data.message || 'Error loading product') + '</div>');
                    }
                },
                error: function() {
                    $loading.hide();
                    $content.html('<div class="mpd-quick-view-error">Error loading product. Please try again.</div>');
                }
            });
        },

        closeQuickView: function(e) {
            if (e) e.preventDefault();
            $('#mpd-quick-view-modal').removeClass('is-open');
            $('body').removeClass('mpd-quick-view-open');
        },

        handleKeyup: function(e) {
            if (e.keyCode === 27) { // ESC key
                this.closeQuickView();
            }
        }
    };

    /**
     * Header Cart Handler
     */
    const MPDHeaderCart = {
        init: function($scope) {
            this.bindEvents($scope);
            this.bindCartFragments();
        },

        bindEvents: function($scope) {
            var $context = $scope || $(document);
            var self = this;
            
            // Click trigger for dropdown
            $context.on('click', '.mpd-header-cart--trigger-click .mpd-header-cart__wrapper', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $dropdown = $(this).siblings('.mpd-header-cart__dropdown');
                $dropdown.toggleClass('is-active');
                
                // Position dropdown after it becomes visible
                if ($dropdown.hasClass('is-active')) {
                    setTimeout(function() {
                        self.positionDropdown($dropdown);
                    }, 10);
                }
            });
            
            // Hover trigger - position dropdown
            $context.on('mouseenter', '.mpd-header-cart--trigger-hover .mpd-header-cart__wrapper', function() {
                var $dropdown = $(this).siblings('.mpd-header-cart__dropdown');
                setTimeout(function() {
                    self.positionDropdown($dropdown);
                }, 10);
            });

            // Close dropdown on outside click
            $(document).off('click.mpdHeaderCart').on('click.mpdHeaderCart', function(e) {
                if (!$(e.target).closest('.mpd-header-cart').length) {
                    $('.mpd-header-cart__dropdown').removeClass('is-active');
                }
            });
            
            // Reposition on window resize
            $(window).on('resize.mpdHeaderCart', function() {
                $('.mpd-header-cart__dropdown.is-active, .mpd-header-cart--trigger-hover:hover .mpd-header-cart__dropdown').each(function() {
                    self.positionDropdown($(this));
                });
            });
        },

        bindCartFragments: function() {
            var self = this;
            
            // Listen for WooCommerce cart fragments refresh
            $(document.body).on('wc_fragments_refreshed wc_fragments_loaded added_to_cart removed_from_cart', function() {
                setTimeout(function() {
                    self.updateCartCount();
                }, 150);
            });

            // Also listen for AJAX complete for cart updates
            $(document).ajaxComplete(function(event, xhr, settings) {
                if (settings.url && (settings.url.indexOf('wc-ajax') !== -1 || settings.url.indexOf('add_to_cart') !== -1 || settings.url.indexOf('remove_item') !== -1)) {
                    setTimeout(function() {
                        self.updateCartCount();
                    }, 200);
                }
            });
            
            // Listen for clicks on remove buttons
            $(document).on('click', '.mpd-header-cart__dropdown .remove_from_cart_button, .mpd-header-cart__dropdown a.remove', function() {
                setTimeout(function() {
                    self.updateCartCount();
                }, 500);
            });
        },

        updateCartCount: function() {
            var self = this;
            
            // First, try counting items directly in the DOM (most reliable)
            var $cartContent = $('.mpd-header-cart__dropdown .widget_shopping_cart_content');
            var itemCount = $cartContent.find('.woocommerce-mini-cart-item').length;
            
            // Update badge
            self.setBadgeCount(itemCount);
            
            // Also try from WooCommerce fragments for accuracy
            try {
                if (typeof wc_cart_fragments_params !== 'undefined') {
                    var fragmentKey = wc_cart_fragments_params.fragment_name;
                    var storedFragments = sessionStorage.getItem('wc_fragments_' + fragmentKey);
                    
                    if (storedFragments) {
                        var fragments = JSON.parse(storedFragments);
                        if (fragments && fragments['a.cart-contents']) {
                            var $temp = $('<div>').html(fragments['a.cart-contents']);
                            var countText = $temp.find('.count, .cart-contents-count').text();
                            var count = parseInt(countText) || 0;
                            
                            // Use fragment count if available
                            self.setBadgeCount(count);
                        }
                    }
                }
            } catch(e) {
                // Fallback to DOM count.
            }
        },
        
        setBadgeCount: function(count) {
            var $badges = $('.mpd-header-cart__badge');
            
            $badges.text(count).attr('data-cart-count', count);
            
            // Handle hide when empty
            if (count === 0) {
                $badges.addClass('mpd-header-cart__badge--hidden');
            } else {
                $badges.removeClass('mpd-header-cart__badge--hidden');
            }
        },

        positionDropdown: function($dropdown) {
            if (!$dropdown.length) return;
            
            // Reset positioning
            $dropdown.css({
                'left': '',
                'right': ''
            });
            
            var dropdownRect = $dropdown[0].getBoundingClientRect();
            var viewportWidth = window.innerWidth;
            var padding = 15;
            
            // Check if going off right side
            if (dropdownRect.right > viewportWidth - padding) {
                var overflow = dropdownRect.right - viewportWidth + padding;
                $dropdown.css('right', -overflow + 'px');
            }
            
            // Check if going off left side
            if (dropdownRect.left < padding) {
                $dropdown.css({
                    'left': padding + 'px',
                    'right': 'auto'
                });
            }
        }
    };

    /**
     * Recently Viewed Products
     */
    const MPDRecentlyViewed = {
        cookieName: 'woocommerce_recently_viewed',
        maxItems: 10,

        init: function($scope) {
            this.trackProduct();
            this.initCarousels($scope);
        },

        trackProduct: function() {
            // Check if we're on a single product page
            if ($('body').hasClass('single-product')) {
                var productId = null;
                
                // Method 1: Try from WooCommerce product data
                var $product = $('div.product');
                if ($product.length && $product.attr('class')) {
                    var classMatch = $product.attr('class').match(/post-(\d+)/);
                    if (classMatch) {
                        productId = parseInt(classMatch[1]);
                    }
                }
                
                // Method 2: Try from body class
                if (!productId) {
                    var bodyClass = $('body').attr('class');
                    var match = bodyClass.match(/postid-(\d+)/);
                    if (match) {
                        productId = parseInt(match[1]);
                    }
                }
                
                // Method 3: Try from add to cart form
                if (!productId) {
                    var $form = $('form.cart');
                    if ($form.length) {
                        var addToCartVal = $form.find('button[name="add-to-cart"]').val();
                        var productIdVal = $form.find('input[name="product_id"]').val();
                        productId = parseInt(addToCartVal) || parseInt(productIdVal);
                    }
                }
                
                // Method 4: Try from product link
                if (!productId) {
                    var $addToCartBtn = $('form.cart button[type="submit"]');
                    if ($addToCartBtn.length && $addToCartBtn.val()) {
                        productId = parseInt($addToCartBtn.val());
                    }
                }
                
                if (productId && productId > 0) {
                    this.addToViewed(productId);
                    console.log('MPD Recently Viewed: Tracked product ID', productId);
                }
            }
        },
        
        initCarousels: function($scope) {
            var $context = $scope || $(document);
            
            $context.find('.mpd-recently-viewed__carousel[data-swiper]').each(function() {
                var $carousel = $(this);
                
                // Skip if already initialized
                if ($carousel.hasClass('swiper-initialized')) {
                    return;
                }
                
                var options = $carousel.data('swiper');
                
                if (typeof Swiper !== 'undefined') {
                    new Swiper($carousel[0], options);
                } else if (typeof elementorFrontend !== 'undefined' && elementorFrontend.utils && elementorFrontend.utils.swiper) {
                    // Use Elementor's Swiper
                    new elementorFrontend.utils.swiper($carousel, options);
                }
            });
        },

        getViewed: function() {
            var cookie = this.getCookie(this.cookieName);
            if (!cookie) return [];
            // WooCommerce uses pipe | separator
            return cookie.split('|').map(function(id) { return parseInt(id); }).filter(function(id) { return id > 0; });
        },

        addToViewed: function(productId) {
            var viewed = this.getViewed();
            
            // Remove if already exists (will be added to front)
            viewed = viewed.filter(function(id) { return id !== productId; });
            
            // Add to front
            viewed.unshift(productId);
            
            // Limit to max items
            viewed = viewed.slice(0, this.maxItems);
            
            // WooCommerce uses pipe | separator
            var cookieValue = viewed.join('|');
            this.setCookie(this.cookieName, cookieValue, 30);
            console.log('MPD Recently Viewed: Cookie set', this.cookieName, '=', cookieValue);
        },

        getCookie: function(name) {
            var value = '; ' + document.cookie;
            var parts = value.split('; ' + name + '=');
            if (parts.length === 2) return parts.pop().split(';').shift();
            return '';
        },

        setCookie: function(name, value, days) {
            var expires = '';
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            document.cookie = name + '=' + (value || '') + expires + '; path=/';
        }
    };

    /**
     * Store Notice Countdown Handler
     */
    const MPDStoreNotice = {
        init: function($scope) {
            const $context = $scope || $('body');
            this.checkDismissed($context);
            this.initCountdowns($context);
            this.bindDismiss();
        },

        checkDismissed: function($context) {
            // Check if any notices have been dismissed
            $context.find('.mpd-store-notice[data-notice-id]').each(function() {
                const $notice = $(this);
                const noticeId = $notice.data('notice-id');
                
                if (noticeId) {
                    // Check cookie
                    const cookieName = 'mpd_dismissed_' + noticeId;
                    const isDismissed = document.cookie.split(';').some(function(item) {
                        return item.trim().indexOf(cookieName + '=') === 0;
                    });
                    
                    if (isDismissed) {
                        $notice.hide();
                    }
                }
            });
        },

        initCountdowns: function($context) {
            const self = this;
            $context.find('.mpd-store-notice[data-countdown]').each(function() {
                const $notice = $(this);
                const countdownDate = $notice.data('countdown');
                const expiredAction = $notice.data('expired-action');
                const expiredMessage = $notice.data('expired-message');

                if (!countdownDate) return;

                // Clear any existing interval to avoid duplicates
                if ($notice.data('countdown-interval')) {
                    clearInterval($notice.data('countdown-interval'));
                }

                // Parse the date
                const endTime = new Date(countdownDate).getTime();

                // Update countdown every second
                const countdownInterval = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = endTime - now;

                    // Time calculations
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Display the result
                    $notice.find('.mpd-countdown-days').text(self.pad(days));
                    $notice.find('.mpd-countdown-hours').text(self.pad(hours));
                    $notice.find('.mpd-countdown-minutes').text(self.pad(minutes));
                    $notice.find('.mpd-countdown-seconds').text(self.pad(seconds));

                    // If countdown is finished
                    if (distance < 0) {
                        clearInterval(countdownInterval);
                        $notice.removeData('countdown-interval');
                        
                        if (expiredAction === 'hide') {
                            $notice.fadeOut(300);
                        } else if (expiredAction === 'message' && expiredMessage) {
                            $notice.find('.mpd-store-notice__text').text(expiredMessage);
                            $notice.find('.mpd-store-notice__countdown').remove();
                        }
                    }
                }, 1000);

                // Store interval ID for cleanup
                $notice.data('countdown-interval', countdownInterval);
            });
        },

        bindDismiss: function() {
            $(document).on('click', '.mpd-store-notice__close', function(e) {
                e.preventDefault();
                const $notice = $(this).closest('.mpd-store-notice');
                const noticeId = $notice.data('notice-id');
                const dismissDays = parseInt($notice.data('dismiss-days')) || 1;

                // Clear countdown interval if exists
                if ($notice.data('countdown-interval')) {
                    clearInterval($notice.data('countdown-interval'));
                    $notice.removeData('countdown-interval');
                }

                // Save to cookie
                if (noticeId) {
                    const expires = new Date();
                    expires.setDate(expires.getDate() + dismissDays);
                    document.cookie = 'mpd_dismissed_' + noticeId + '=1; expires=' + expires.toUTCString() + '; path=/';
                }

                // Fade out and remove
                $notice.fadeOut(300, function() {
                    $(this).remove();
                });
            });
        },

        pad: function(num) {
            return num < 10 ? '0' + num : num;
        }
    };

    /**
     * Header Wishlist Compare Handler
     */
    const MPDHeaderWishlistCompare = {
        init: function($scope) {
            this.bindEvents($scope);
        },

        bindEvents: function($scope) {
            var $context = $scope || $(document);
            var self = this;
            
            // Toggle dropdown on click
            $context.on('click', '.mpd-header-wc-item > .mpd-header-wc-icon-wrap', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $item = $(this).closest('.mpd-header-wc-item');
                var $dropdown = $item.find('.mpd-header-wc-dropdown');
                
                // Close other dropdowns
                $('.mpd-header-wc-dropdown').not($dropdown).removeClass('is-active');
                
                $dropdown.toggleClass('is-active');
            });
            
            // Close dropdown on outside click
            $(document).off('click.mpdHeaderWC').on('click.mpdHeaderWC', function(e) {
                if (!$(e.target).closest('.mpd-header-wc-item').length) {
                    $('.mpd-header-wc-dropdown').removeClass('is-active');
                }
            });
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        MPDWishlist.init();
        MPDComparison.init();
        MPDQuickView.init();
        MPDHeaderCart.init();
        MPDRecentlyViewed.init();
        MPDStoreNotice.init();
        MPDHeaderWishlistCompare.init();
    });

    // Re-initialize on Elementor frontend init (for editor preview)
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            // Store Notice widget
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-store-notice.default', function($scope) {
                MPDStoreNotice.init($scope);
            });

            // Header Cart widget
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-header-cart.default', function($scope) {
                MPDHeaderCart.init($scope);
            });

            // Wishlist widget
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-wishlist.default', function($scope) {
                MPDWishlist.init();
            });

            // Comparison widget
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-comparison.default', function($scope) {
                MPDComparison.init();
            });

            // Header Wishlist Compare widget
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-header-wishlist-compare.default', function($scope) {
                MPDWishlist.init();
                MPDComparison.init();
                MPDHeaderWishlistCompare.init($scope);
            });

            // Recently Viewed widget
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-recently-viewed.default', function($scope) {
                MPDRecentlyViewed.init($scope);
            });
        }
    });

    // Expose to global scope for external use
    window.MPDWishlist = MPDWishlist;
    window.MPDComparison = MPDComparison;
    window.MPDHeaderCart = MPDHeaderCart;
    window.MPDStoreNotice = MPDStoreNotice;
    window.MPDRecentlyViewed = MPDRecentlyViewed;
    window.MPDHeaderWishlistCompare = MPDHeaderWishlistCompare;

})(jQuery);
