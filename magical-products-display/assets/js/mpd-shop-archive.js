/**
 * MPD Shop Archive Widgets JavaScript
 *
 * Handles Masonry layout and Infinite Scroll functionality.
 *
 * @package Magical_Products_Display
 * @since 2.0.0
 */

(function ($) {
    'use strict';
    
    // Ensure mpdShopArchive exists
    if (typeof mpdShopArchive === 'undefined') {
        window.mpdShopArchive = {
            ajaxUrl: '/wp-admin/admin-ajax.php',
            nonce: '',
            i18n: {
                loading: 'Loading more products...',
                noMore: 'No more products to load',
                error: 'Error loading products',
                filtering: 'Filtering products...'
            }
        };
    }

    /**
     * MPD Products Archive Handler
     */
    var MPDProductsArchive = {
        
        /**
         * Initialize
         */
        init: function () {
            this.bindEvents();
            this.initMasonry();
            this.initInfiniteScroll();
            this.initAjaxFilters();
            this.initLoadMorePagination();
        },

        /**
         * Bind events
         */
        bindEvents: function () {
            $(window).on('resize', this.debounce(this.handleResize.bind(this), 250));
        },

        /**
         * Initialize Masonry layout
         * Uses CSS columns for simple, reliable masonry effect
         * Column count is controlled by Elementor's responsive inline styles
         */
        initMasonry: function () {
            var self = this;
            
            $('.mpd-products-archive[data-masonry="yes"]').each(function () {
                var $container = $(this);
                var $grid = $container.find('.mpd-products-archive__grid--masonry');
                
                if ($grid.length === 0) {
                    return;
                }

                // Wait for images to load before marking masonry as ready
                self.imagesLoaded($grid, function () {
                    $grid.addClass('mpd-masonry-ready');
                });
            });
        },

        /**
         * Initialize Infinite Scroll
         */
        initInfiniteScroll: function () {
            var self = this;
            
            $('.mpd-products-archive[data-infinite-scroll="yes"]').each(function () {
                var $container = $(this);
                
                // Skip if already initialized
                if ($container.data('infinite-scroll-init')) {
                    return;
                }
                $container.data('infinite-scroll-init', true);
                
                var $grid = $container.find('.mpd-products-archive__grid, .mpd-products-archive__list');
                
                if ($grid.length === 0) {
                    return;
                }
                
                // Get current items count to calculate posts_per_page
                var currentItems = $grid.find('.mpd-products-archive__item').length;
                var postsPerPage = $container.data('posts-per-page') || currentItems || 12;

                var settings = {
                    container: $container,
                    grid: $grid,
                    page: 1,
                    maxPages: parseInt($container.data('max-pages')) || 1,
                    loading: false,
                    widgetId: $container.data('widget-id') || '',
                    postId: $container.data('post-id') || 0,
                    queryString: window.location.search.substring(1), // Get URL query params
                    postsPerPage: postsPerPage
                };

                // Create loading indicator (only if not already exists)
                var $existingLoader = $container.find('.mpd-infinite-scroll-loader');
                if ($existingLoader.length > 0) {
                    $existingLoader.remove();
                }
                var $loader = $('<div class="mpd-infinite-scroll-loader"><span class="mpd-loader-spinner"></span><span class="mpd-loader-text">' + (mpdShopArchive.i18n.loading || 'Loading...') + '</span></div>');
                $container.append($loader);

                // Bind scroll event
                $(window).on('scroll', self.debounce(function () {
                    self.checkScroll(settings, $loader);
                }, 100));
            });
        },

        /**
         * Initialize Load More Pagination Button
         */
        initLoadMorePagination: function () {
            var self = this;
            
            $(document).on('click', '.mpd-pagination__load-more', function (e) {
                e.preventDefault();
                
                var $button = $(this);
                
                // Prevent double-clicks
                if ($button.hasClass('mpd-loading')) {
                    return;
                }
                
                var currentPage = parseInt($button.data('page')) || 1;
                var maxPages = parseInt($button.data('max-pages')) || 1;
                var nextUrl = $button.data('next-url');
                var loadingText = $button.data('loading-text') || 'Loading...';
                var originalText = $button.text();
                
                // Check if we've reached the end
                if (currentPage >= maxPages) {
                    $button.prop('disabled', true).text(mpdShopArchive.i18n.noMore || 'No more products');
                    return;
                }
                
                // Find the products grid
                var $pagination = $button.closest('.mpd-pagination');
                var $container = $('.mpd-products-archive, .mpd-shop-products').first();
                var $grid = $container.find('.mpd-products-archive__grid, .mpd-products-archive__list, .products').first();
                
                if ($grid.length === 0) {
                    // Fallback: try to navigate to next page
                    if (nextUrl) {
                        window.location.href = nextUrl;
                    }
                    return;
                }
                
                // Show loading state
                $button.addClass('mpd-loading').prop('disabled', true).text(loadingText);
                
                // Load next page via AJAX
                $.ajax({
                    url: nextUrl,
                    type: 'GET',
                    dataType: 'html',
                    success: function (response) {
                        var $response = $(response);
                        
                        // Find products in the response
                        var $newProducts = $response.find('.mpd-products-archive__grid .mpd-products-archive__item, .mpd-products-archive__list .mpd-products-archive__item, .products .product');
                        
                        if ($newProducts.length > 0) {
                            // Append new products
                            $grid.append($newProducts);
                            
                            // Update button data
                            var nextPage = currentPage + 1;
                            $button.data('page', nextPage);
                            
                            // Update next URL
                            var newNextUrl = $response.find('.mpd-pagination__load-more').data('next-url');
                            if (newNextUrl) {
                                $button.data('next-url', newNextUrl);
                            }
                            
                            // Check if there are more pages
                            if (nextPage >= maxPages) {
                                $button.hide();
                            } else {
                                $button.removeClass('mpd-loading').prop('disabled', false).text(originalText);
                            }
                            
                            // Trigger WooCommerce events
                            $(document.body).trigger('wc_fragment_refresh');
                            if (typeof wc_add_to_cart_params !== 'undefined') {
                                $(document.body).trigger('init_add_to_cart_buttons');
                            }
                            
                            // Trigger custom event
                            $(document).trigger('mpd_products_loaded', [$grid, $newProducts]);
                            
                            // Re-init masonry if enabled
                            if ($container.data('masonry') === 'yes') {
                                self.imagesLoaded($grid, function () {
                                    $grid.addClass('mpd-masonry-ready');
                                });
                            }
                        } else {
                            // No more products
                            $button.hide();
                        }
                    },
                    error: function () {
                        // On error, fallback to page navigation
                        if (nextUrl) {
                            window.location.href = nextUrl;
                        } else {
                            $button.removeClass('mpd-loading').prop('disabled', false).text(originalText);
                        }
                    }
                });
            });
        },

        /**
         * Check scroll position and load more
         * 
         * @param {Object} settings Infinite scroll settings
         * @param {jQuery} $loader Loading indicator
         */
        checkScroll: function (settings, $loader) {
            var self = this;

            if (settings.loading || settings.page >= settings.maxPages) {
                return;
            }

            var scrollTop = $(window).scrollTop();
            var windowHeight = $(window).height();
            var containerBottom = settings.container.offset().top + settings.container.outerHeight();

            // Load more when user scrolls near the bottom
            if (scrollTop + windowHeight >= containerBottom - 200) {
                self.loadMoreProducts(settings, $loader);
            }
        },

        /**
         * Load more products via AJAX
         * 
         * @param {Object} settings Infinite scroll settings
         * @param {jQuery} $loader Loading indicator
         */
        loadMoreProducts: function (settings, $loader) {
            var self = this;
            
            settings.loading = true;
            settings.page++;
            
            $loader.addClass('mpd-loading');

            $.ajax({
                url: mpdShopArchive.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'mpd_load_more_products',
                    nonce: mpdShopArchive.nonce,
                    page: settings.page,
                    widget_id: settings.widgetId,
                    post_id: settings.postId,
                    query_string: settings.queryString,
                    posts_per_page: settings.postsPerPage
                },
                success: function (response) {
                    if (response.success && response.data.html) {
                        var $newItems = $(response.data.html);
                        
                        // Add animation class to new items
                        $newItems.addClass('mpd-item-new');
                        
                        settings.grid.append($newItems);

                        // Trigger WooCommerce to init AJAX add to cart for new items
                        $(document.body).trigger('wc_fragment_refresh');
                        
                        // Re-init WooCommerce add to cart buttons
                        if (typeof wc_add_to_cart_params !== 'undefined') {
                            $(document.body).trigger('init_add_to_cart_buttons');
                        }
                        
                        // Trigger custom event for action buttons to update their states
                        $(document).trigger('mpd_products_loaded', [$newItems]);

                        // CSS columns-based masonry auto-reflows, just wait for images
                        if (settings.container.data('masonry') === 'yes') {
                            self.imagesLoaded(settings.grid, function () {
                                // Remove animation class after animation completes
                                setTimeout(function() {
                                    $newItems.removeClass('mpd-item-new');
                                }, 500);
                            });
                        } else {
                            setTimeout(function() {
                                $newItems.removeClass('mpd-item-new');
                            }, 500);
                        }

                        // Update max pages
                        if (response.data.max_pages) {
                            settings.maxPages = response.data.max_pages;
                        }

                        // Hide loader if no more pages
                        if (settings.page >= settings.maxPages) {
                            $loader.removeClass('mpd-loading').addClass('mpd-no-more');
                            $loader.find('span:last').text(mpdShopArchive.i18n.noMore || 'No more products');
                        }
                    } else {
                        $loader.removeClass('mpd-loading').addClass('mpd-no-more');
                        $loader.find('span:last').text(mpdShopArchive.i18n.noMore || 'No more products');
                    }

                    settings.loading = false;
                },
                error: function () {
                    settings.loading = false;
                    $loader.removeClass('mpd-loading');
                    console.error('MPD: Failed to load more products');
                }
            });
        },

        /**
         * Initialize AJAX Filters (Price, Attribute, Category)
         */
        initAjaxFilters: function () {
            var self = this;
            var filterDebounceTimer = null;
            
            /**
             * Trigger filter with current URL params
             */
            function triggerFilter() {
                var $container = $('.mpd-products-archive').first();
                if ($container.length === 0) {
                    return false;
                }
                
                var queryString = window.location.search.substring(1);
                self.filterProducts($container, queryString);
                return true;
            }
            
            /**
             * Update URL and trigger AJAX filter
             */
            function updateUrlAndFilter(params) {
                var $container = $('.mpd-products-archive').first();
                if ($container.length === 0) {
                    return false;
                }
                
                // Build query string
                var queryString = $.param(params);
                var newUrl = window.location.pathname;
                if (queryString) {
                    newUrl += '?' + queryString;
                }
                
                // Update URL without reload
                if (window.history && window.history.pushState) {
                    window.history.pushState({}, '', newUrl);
                }
                
                // Filter products via AJAX
                self.filterProducts($container, queryString);
                return true;
            }
            
            /**
             * Get current URL params as object
             */
            function getCurrentParams() {
                var params = {};
                var queryString = window.location.search.substring(1);
                if (queryString) {
                    queryString.split('&').forEach(function(pair) {
                        var parts = pair.split('=');
                        if (parts[0]) {
                            params[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1] || '');
                        }
                    });
                }
                return params;
            }
            
            // ========================================
            // PRICE FILTER - Auto refresh on change
            // ========================================
            
            // Handle WooCommerce price slider change (auto-refresh)
            $(document).on('price_slider_change', function (e, min, max) {
                clearTimeout(filterDebounceTimer);
                filterDebounceTimer = setTimeout(function() {
                    var params = getCurrentParams();
                    params.min_price = min;
                    params.max_price = max;
                    delete params.paged;
                    updateUrlAndFilter(params);
                }, 500); // 500ms debounce
            });
            
            // Handle manual input changes (for input-style price filter)
            $(document).on('change', '.mpd-price-filter__input--min, .mpd-price-filter__input--max', function () {
                clearTimeout(filterDebounceTimer);
                var $form = $(this).closest('.mpd-price-filter__form');
                filterDebounceTimer = setTimeout(function() {
                    var minPrice = $form.find('[name="min_price"]').val();
                    var maxPrice = $form.find('[name="max_price"]').val();
                    var params = getCurrentParams();
                    
                    if (minPrice) params.min_price = minPrice;
                    else delete params.min_price;
                    
                    if (maxPrice) params.max_price = maxPrice;
                    else delete params.max_price;
                    
                    delete params.paged;
                    updateUrlAndFilter(params);
                }, 500);
            });
            
            // Prevent form submission (no button needed)
            $(document).on('submit', '.mpd-price-filter__form', function (e) {
                e.preventDefault();
            });
            
            // ========================================
            // ATTRIBUTE FILTER - AJAX support
            // ========================================
            
            // Prevent attribute filter form submission (use AJAX instead)
            $(document).on('submit', '.mpd-attribute-filter__form', function (e) {
                e.preventDefault();
            });
            
            // Handle attribute filter link clicks (list and label styles)
            $(document).on('click', '.mpd-attribute-filter__list a', function (e) {
                e.preventDefault();
                var href = $(this).attr('href');
                var params = {};
                
                // Parse the href to get params
                var queryString = href.split('?')[1] || '';
                if (queryString) {
                    queryString.split('&').forEach(function(pair) {
                        var parts = pair.split('=');
                        if (parts[0]) {
                            params[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1] || '');
                        }
                    });
                }
                
                // Update active state visually
                var $item = $(this).closest('.mpd-attribute-filter__item, .mpd-attribute-filter__label');
                if ($item.hasClass('is-chosen')) {
                    $item.removeClass('is-chosen');
                } else {
                    $item.addClass('is-chosen');
                }
                
                updateUrlAndFilter(params);
            });
            
            // Handle attribute filter dropdown change
            $(document).on('change', '.mpd-attribute-filter__dropdown', function (e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $select = $(this);
                var params = getCurrentParams();
                var filterName = $select.attr('name');
                var value = $select.val();
                
                if (value) {
                    params[filterName] = value;
                } else {
                    delete params[filterName];
                }
                delete params.paged;
                
                updateUrlAndFilter(params);
                return false;
            });
            
            // Handle attribute filter checkbox change
            $(document).on('change', '.mpd-attribute-filter__checkbox', function (e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $checkbox = $(this);
                var $form = $checkbox.closest('.mpd-attribute-filter__checkboxes');
                var filterName = $checkbox.attr('name').replace('[]', '');
                var values = [];
                
                $form.find('.mpd-attribute-filter__checkbox:checked').each(function() {
                    values.push($(this).val());
                });
                
                var params = getCurrentParams();
                if (values.length > 0) {
                    params[filterName] = values.join(',');
                } else {
                    delete params[filterName];
                }
                delete params.paged;
                
                updateUrlAndFilter(params);
                return false;
            });
            
            // ========================================
            // CATEGORY FILTER - AJAX support
            // ========================================
            
            // Handle category filter link clicks
            $(document).on('click', '.mpd-category-filter__list a, .mpd-category-filter__tree a', function (e) {
                e.preventDefault();
                var href = $(this).attr('href');
                var params = {};
                
                // Parse the href to get params
                var queryString = href.split('?')[1] || '';
                if (queryString) {
                    queryString.split('&').forEach(function(pair) {
                        var parts = pair.split('=');
                        if (parts[0]) {
                            params[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1] || '');
                        }
                    });
                }
                
                // Update active state visually
                $('.mpd-category-filter__item').removeClass('is-current');
                $(this).closest('.mpd-category-filter__item').addClass('is-current');
                
                updateUrlAndFilter(params);
            });
            
            // Handle category filter dropdown change
            $(document).on('change', '.mpd-category-filter__dropdown', function (e) {
                e.preventDefault();
                var value = $(this).val();
                var params = getCurrentParams();
                
                if (value && value !== '-1' && value !== '0') {
                    params.product_cat = value;
                } else {
                    delete params.product_cat;
                }
                delete params.paged;
                
                updateUrlAndFilter(params);
            });
            
            // ========================================
            // ORDERING DROPDOWN - AJAX support
            // ========================================
            
            // Prevent ordering form submission (use AJAX instead)
            $(document).on('submit', '.mpd-ordering__form', function (e) {
                e.preventDefault();
            });
            
            // Handle ordering dropdown change
            $(document).on('change', '.mpd-ordering__select', function (e) {
                e.preventDefault();
                e.stopPropagation();
                
                var value = $(this).val();
                var params = getCurrentParams();
                
                if (value && value !== 'menu_order') {
                    params.orderby = value;
                } else {
                    delete params.orderby;
                }
                delete params.paged;
                
                updateUrlAndFilter(params);
                return false;
            });
            
            // ========================================
            // BROWSER BACK/FORWARD SUPPORT
            // ========================================
            $(window).on('popstate', function() {
                triggerFilter();
            });
        },
        
        /**
         * Filter products via AJAX
         * 
         * @param {jQuery} $container Products archive container
         * @param {string} queryString Query string with filter parameters
         */
        filterProducts: function ($container, queryString) {
            var self = this;
            var $grid = $container.find('.mpd-products-archive__grid, .mpd-products-archive__list');
            
            if ($grid.length === 0) {
                return;
            }
            
            // Show loading state
            $container.addClass('mpd-filtering');
            
            // Add loading overlay if not exists
            if ($container.find('.mpd-filter-loading').length === 0) {
                $container.prepend('<div class="mpd-filter-loading"><span class="mpd-loader-spinner"></span><span>' + (mpdShopArchive.i18n.filtering || 'Filtering...') + '</span></div>');
            }
            $container.find('.mpd-filter-loading').show();
            
            var widgetId = $container.data('widget-id') || '';
            var postId = $container.data('post-id') || 0;
            var postsPerPage = $container.data('posts-per-page') || 12;
            
            $.ajax({
                url: mpdShopArchive.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'mpd_filter_products',
                    nonce: mpdShopArchive.nonce,
                    widget_id: widgetId,
                    post_id: postId,
                    query_string: queryString,
                    posts_per_page: postsPerPage
                },
                success: function (response) {
                    $container.removeClass('mpd-filtering');
                    $container.find('.mpd-filter-loading').hide();
                    
                    if (response.success && response.data.html) {
                        // Replace grid content
                        $grid.html(response.data.html);
                        
                        // Update max pages for infinite scroll
                        if (response.data.max_pages) {
                            $container.data('max-pages', response.data.max_pages);
                        }
                        
                        // Update product count if displayed
                        if (response.data.found_posts) {
                            $('.mpd-result-count__number, .woocommerce-result-count').each(function () {
                                var $this = $(this);
                                var text = $this.text();
                                // Try to update the count in the text
                                $this.text(text.replace(/\d+/, response.data.found_posts));
                            });
                        }
                        
                        // Trigger WooCommerce to init AJAX add to cart for new items
                        $(document.body).trigger('wc_fragment_refresh');
                        
                        // Re-init WooCommerce add to cart buttons
                        if (typeof wc_add_to_cart_params !== 'undefined') {
                            $(document.body).trigger('init_add_to_cart_buttons');
                        }
                        
                        // Trigger custom event
                        $(document).trigger('mpd_products_filtered', [$grid]);
                        
                        // Re-init masonry if enabled
                        if ($container.data('masonry') === 'yes') {
                            self.imagesLoaded($grid, function () {
                                $grid.addClass('mpd-masonry-ready');
                            });
                        }
                        
                        // Scroll to top of products
                        $('html, body').animate({
                            scrollTop: $container.offset().top - 100
                        }, 300);
                        
                    } else if (response.data && response.data.message) {
                        // Show no products message
                        $grid.html('<div class="mpd-no-products">' + response.data.message + '</div>');
                    }
                },
                error: function () {
                    $container.removeClass('mpd-filtering');
                    $container.find('.mpd-filter-loading').hide();
                    console.error('MPD: Failed to filter products');
                }
            });
        },

        /**
         * Handle window resize
         * Column count is handled by Elementor's responsive inline styles
         */
        handleResize: function () {
            // Elementor handles responsive column-count via inline styles
            // This function is kept for potential future use
        },

        /**
         * Wait for images to load
         * 
         * @param {jQuery} $container Container element
         * @param {Function} callback Callback function
         */
        imagesLoaded: function ($container, callback) {
            var $images = $container.find('img');
            var total = $images.length;
            var loaded = 0;

            if (total === 0) {
                callback();
                return;
            }

            $images.each(function () {
                var img = this;
                
                if (img.complete) {
                    loaded++;
                    if (loaded === total) {
                        callback();
                    }
                } else {
                    $(img).on('load error', function () {
                        loaded++;
                        if (loaded === total) {
                            callback();
                        }
                    });
                }
            });
        },

        /**
         * Debounce function
         * 
         * @param {Function} func Function to debounce
         * @param {number} wait Wait time in ms
         * @return {Function} Debounced function
         */
        debounce: function (func, wait) {
            var timeout;
            return function () {
                var context = this;
                var args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    func.apply(context, args);
                }, wait);
            };
        }
    };

    // Initialize on document ready
    $(document).ready(function () {
        MPDProductsArchive.init();
    });

    // Re-initialize on Elementor frontend init
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-products-archive.default', function ($scope) {
                MPDProductsArchive.init();
            });
        }
    });

    // Expose to window for external access (Advanced Filter, etc.)
    window.MPDProductsArchive = MPDProductsArchive;

})(jQuery);
