/**
 * MPD Advanced Filter Widget JavaScript
 *
 * Handles sidebar/popup toggle, AJAX filtering, and form interactions.
 *
 * @package Magical_Products_Display
 * @since 2.0.0
 */

(function ($) {
    'use strict';

    /**
     * MPD Advanced Filter Handler
     */
    var MPDAdvancedFilter = {
        
        /**
         * Initialize
         */
        init: function () {
            this.bindEvents();
            this.initPriceSlider();
        },

        /**
         * Bind events
         */
        bindEvents: function () {
            var self = this;

            // Toggle button click (sidebar/popup)
            $(document).on('click', '.mpd-advanced-filter__toggle', function (e) {
                e.preventDefault();
                // For responsive mode, the toggle is inside __desktop or __mobile container
                // which has the popup/sidebar class. Otherwise use the main wrapper.
                var $container = $(this).closest('.mpd-advanced-filter__desktop, .mpd-advanced-filter__mobile');
                if ($container.length === 0) {
                    $container = $(this).closest('.mpd-advanced-filter');
                }
                self.openFilter($container);
            });

            // Close button click
            $(document).on('click', '.mpd-advanced-filter__close', function (e) {
                e.preventDefault();
                var $container = $(this).closest('.mpd-advanced-filter__desktop, .mpd-advanced-filter__mobile');
                if ($container.length === 0) {
                    $container = $(this).closest('.mpd-advanced-filter');
                }
                self.closeFilter($container);
            });

            // Overlay click
            $(document).on('click', '.mpd-advanced-filter__overlay', function (e) {
                e.preventDefault();
                var $container = $(this).closest('.mpd-advanced-filter__desktop, .mpd-advanced-filter__mobile');
                if ($container.length === 0) {
                    $container = $(this).closest('.mpd-advanced-filter');
                }
                self.closeFilter($container);
            });

            // ESC key to close
            $(document).on('keyup', function (e) {
                if (e.key === 'Escape') {
                    // Close responsive mode containers
                    $('.mpd-advanced-filter__desktop.is-open, .mpd-advanced-filter__mobile.is-open').each(function () {
                        self.closeFilter($(this));
                    });
                    // Close normal mode containers
                    $('.mpd-advanced-filter.is-open').each(function () {
                        self.closeFilter($(this));
                    });
                }
            });

            // Form submit prevention (when auto-apply is on)
            $(document).on('submit', '.mpd-advanced-filter__form', function (e) {
                var $wrapper = $(this).closest('.mpd-advanced-filter');
                var autoApply = $wrapper.data('auto-apply') === 'yes';
                
                if (autoApply) {
                    e.preventDefault();
                    self.applyFilters($wrapper);
                } else {
                    e.preventDefault();
                    self.applyFilters($wrapper);
                }
            });

            // Reset button
            $(document).on('click', '.mpd-advanced-filter__button--reset', function (e) {
                e.preventDefault();
                // Find the closest form first, then the wrapper
                var $form = $(this).closest('.mpd-advanced-filter__form');
                var $wrapper = $(this).closest('.mpd-advanced-filter');
                self.resetFilters($wrapper, $form);
            });

            // Auto-apply on filter change
            $(document).on('change', '.mpd-advanced-filter__dropdown, .mpd-advanced-filter__checkbox, .mpd-advanced-filter__radio', function () {
                var $wrapper = $(this).closest('.mpd-advanced-filter');
                var autoApply = $wrapper.data('auto-apply') === 'yes';
                
                if (autoApply) {
                    self.applyFilters($wrapper);
                }
            });

            // Auto-apply on price input change (debounced)
            var priceDebounce;
            $(document).on('change', '.mpd-advanced-filter__section--price input[type="number"]', function () {
                var $wrapper = $(this).closest('.mpd-advanced-filter');
                var autoApply = $wrapper.data('auto-apply') === 'yes';
                
                if (autoApply) {
                    clearTimeout(priceDebounce);
                    priceDebounce = setTimeout(function () {
                        self.applyFilters($wrapper);
                    }, 500);
                }
            });

            // List item click (AJAX filter)
            $(document).on('click', '.mpd-advanced-filter__list a[data-filter]', function (e) {
                e.preventDefault();
                var $wrapper = $(this).closest('.mpd-advanced-filter');
                var filterName = $(this).data('filter');
                var filterValue = $(this).data('value');
                var $item = $(this).closest('.mpd-advanced-filter__item');
                
                // Toggle active state
                if ($item.hasClass('is-active')) {
                    $item.removeClass('is-active');
                    filterValue = '';
                } else {
                    $item.siblings().removeClass('is-active');
                    $item.addClass('is-active');
                }
                
                // Update hidden input or directly apply filter
                self.updateFilter(filterName, filterValue);
                
                var autoApply = $wrapper.data('auto-apply') === 'yes';
                if (autoApply) {
                    self.applyFilters($wrapper);
                }
            });

            // Tag cloud click
            $(document).on('click', '.mpd-advanced-filter__tag', function (e) {
                e.preventDefault();
                var $wrapper = $(this).closest('.mpd-advanced-filter');
                var filterName = $(this).data('filter');
                var filterValue = $(this).data('value');
                
                // Toggle active state
                if ($(this).hasClass('is-active')) {
                    $(this).removeClass('is-active');
                    filterValue = '';
                } else {
                    $(this).siblings().removeClass('is-active');
                    $(this).addClass('is-active');
                }
                
                self.updateFilter(filterName, filterValue);
                
                var autoApply = $wrapper.data('auto-apply') === 'yes';
                if (autoApply) {
                    self.applyFilters($wrapper);
                }
            });

            // Inline dropdown trigger click
            $(document).on('click', '.mpd-inline-dropdown__trigger', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var $dropdown = $(this).closest('.mpd-inline-dropdown');
                
                // Close other dropdowns
                $('.mpd-inline-dropdown').not($dropdown).removeClass('is-open');
                
                // Toggle current dropdown
                $dropdown.toggleClass('is-open');
            });

            // Inline dropdown item click
            $(document).on('click', '.mpd-inline-dropdown__item a', function (e) {
                e.preventDefault();
                var $dropdown = $(this).closest('.mpd-inline-dropdown');
                var $form = $(this).closest('.mpd-advanced-filter__form');
                var $wrapper = $(this).closest('.mpd-advanced-filter');
                var filterName = $dropdown.data('filter');
                var filterValue = $(this).data('value');
                var $item = $(this).closest('.mpd-inline-dropdown__item');
                var $hiddenInput = $dropdown.find('input[type="hidden"]');

                // Update active state
                $dropdown.find('.mpd-inline-dropdown__item').removeClass('is-active');
                $item.addClass('is-active');

                // Update hidden input
                $hiddenInput.val(filterValue);

                // Update has-selection class
                if (filterValue) {
                    $dropdown.addClass('has-selection');
                } else {
                    $dropdown.removeClass('has-selection');
                }

                // Close dropdown
                $dropdown.removeClass('is-open');

                // Apply filters
                self.applyFilters($wrapper, $form);
            });

            // Inline price filter apply button
            $(document).on('click', '.mpd-inline-price-apply', function (e) {
                e.preventDefault();
                var $dropdown = $(this).closest('.mpd-inline-dropdown');
                var $form = $(this).closest('.mpd-advanced-filter__form');
                var $wrapper = $dropdown.closest('.mpd-advanced-filter');
                var minPrice = $dropdown.find('input[name="min_price"]').val();
                var maxPrice = $dropdown.find('input[name="max_price"]').val();

                // Update filter values
                if (minPrice || maxPrice) {
                    $dropdown.addClass('has-selection');
                } else {
                    $dropdown.removeClass('has-selection');
                }

                // Close dropdown
                $dropdown.removeClass('is-open');

                // Apply filters
                self.applyFilters($wrapper, $form);
            });

            // Inline toggle button click (Featured, On Sale)
            $(document).on('click', '.mpd-inline-toggle', function (e) {
                e.preventDefault();
                var $button = $(this);
                var $form = $button.closest('.mpd-advanced-filter__form');
                var $wrapper = $button.closest('.mpd-advanced-filter');
                var filterName = $button.data('filter');
                var $hiddenInput = $button.find('input[type="hidden"]');
                var isActive = $button.hasClass('is-active');

                // Toggle active state
                if (isActive) {
                    $button.removeClass('is-active');
                    $hiddenInput.val('');
                    $button.data('value', 'yes');
                } else {
                    $button.addClass('is-active');
                    $hiddenInput.val('yes');
                    $button.data('value', '');
                }

                // Apply filters
                self.applyFilters($wrapper, $form);
            });

            // Reset link click (inline style)
            $(document).on('click', '.mpd-advanced-filter__reset-link', function (e) {
                e.preventDefault();
                var $form = $(this).closest('.mpd-advanced-filter__form');
                var $wrapper = $(this).closest('.mpd-advanced-filter');
                self.resetFilters($wrapper, $form);
            });

            // Close dropdowns when clicking outside
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.mpd-inline-dropdown').length) {
                    $('.mpd-inline-dropdown').removeClass('is-open');
                }
            });

            // Close dropdowns on ESC
            $(document).on('keyup', function (e) {
                if (e.key === 'Escape') {
                    $('.mpd-inline-dropdown').removeClass('is-open');
                }
            });
        },

        /**
         * Open filter panel
         */
        openFilter: function ($wrapper) {
            $wrapper.addClass('is-open');
            $('body').addClass('mpd-filter-open');
        },

        /**
         * Close filter panel
         */
        closeFilter: function ($wrapper) {
            $wrapper.removeClass('is-open');
            $('body').removeClass('mpd-filter-open');
        },

        /**
         * Initialize WooCommerce price slider
         */
        initPriceSlider: function () {
            var self = this;

            // Check if wc-price-slider is available and already handled
            if (typeof woocommerce_price_slider_params !== 'undefined') {
                // WooCommerce's wc-price-slider will handle initialization automatically
                // when it finds the widget_price_filter class
                return;
            }

            // Fallback: Manual initialization if WC script not loaded
            $('.mpd-advanced-filter__section--price .price_slider_wrapper').each(function () {
                var $wrapper = $(this);
                var $slider = $wrapper.find('.price_slider');
                var $minInput = $wrapper.find('.min_price');
                var $maxInput = $wrapper.find('.max_price');
                var $label = $wrapper.find('.price_label');
                var $from = $label.find('.from');
                var $to = $label.find('.to');

                var min = parseFloat($minInput.data('min')) || 0;
                var max = parseFloat($maxInput.data('max')) || 100;
                var currentMin = parseFloat($minInput.val()) || min;
                var currentMax = parseFloat($maxInput.val()) || max;

                // Check if jQuery UI slider is available
                if (typeof $.fn.slider !== 'function') {
                    return;
                }

                // Skip if already initialized
                if ($slider.hasClass('ui-slider')) {
                    return;
                }

                $slider.show();
                $label.show();

                $slider.slider({
                    range: true,
                    min: min,
                    max: max,
                    values: [currentMin, currentMax],
                    slide: function (event, ui) {
                        $minInput.val(ui.values[0]);
                        $maxInput.val(ui.values[1]);
                        self.updatePriceLabel($from, $to, ui.values[0], ui.values[1]);
                    },
                    change: function (event, ui) {
                        // Trigger price_slider_change event for AJAX
                        $(document).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
                    }
                });

                // Initialize price label
                self.updatePriceLabel($from, $to, currentMin, currentMax);
            });
        },

        /**
         * Update price label
         */
        updatePriceLabel: function ($from, $to, min, max) {
            // Try to get currency symbol from WooCommerce
            var currencySymbol = typeof woocommerce_price_slider_params !== 'undefined' 
                ? woocommerce_price_slider_params.currency_format_symbol 
                : '$';

            $from.text(currencySymbol + min);
            $to.text(currencySymbol + max);
        },

        /**
         * Update a filter value in URL params object
         */
        updateFilter: function (name, value) {
            // This will be picked up by the apply function
            window.mpdAdvancedFilterUpdates = window.mpdAdvancedFilterUpdates || {};
            if (value) {
                window.mpdAdvancedFilterUpdates[name] = value;
            } else {
                delete window.mpdAdvancedFilterUpdates[name];
            }
        },

        /**
         * Apply filters
         */
        applyFilters: function ($wrapper, $passedForm) {
            var self = this;
            // Use passed form if available, otherwise find form in wrapper
            // In responsive mode, find the visible form
            var $form;
            if ($passedForm && $passedForm.length) {
                $form = $passedForm;
            } else {
                // Try to find visible form first (for responsive mode)
                $form = $wrapper.find('.mpd-advanced-filter__form:visible').first();
                if (!$form.length) {
                    $form = $wrapper.find('.mpd-advanced-filter__form').first();
                }
            }
            
            if (!$form.length) {
                console.warn('MPD Advanced Filter: Form not found');
                return;
            }
            
            var params = self.getCurrentParams();

            // Collect form data
            var formData = {};

            // Dropdowns
            $form.find('.mpd-advanced-filter__dropdown').each(function () {
                var name = $(this).attr('name');
                var value = $(this).val();
                if (value) {
                    formData[name] = value;
                }
            });

            // Checkboxes (including multi-select)
            var checkboxGroups = {};
            $form.find('.mpd-advanced-filter__checkbox:checked').each(function () {
                var name = $(this).attr('name').replace('[]', '');
                var value = $(this).val();
                
                if ($(this).attr('name').indexOf('[]') !== -1) {
                    checkboxGroups[name] = checkboxGroups[name] || [];
                    checkboxGroups[name].push(value);
                } else {
                    formData[name] = value;
                }
            });

            // Convert checkbox groups to comma-separated values
            $.each(checkboxGroups, function (name, values) {
                formData[name] = values.join(',');
            });

            // Radios
            $form.find('.mpd-advanced-filter__radio:checked').each(function () {
                var name = $(this).attr('name');
                var value = $(this).val();
                formData[name] = value;
            });

            // Price inputs
            var minPrice = $form.find('input[name="min_price"]').val();
            var maxPrice = $form.find('input[name="max_price"]').val();
            if (minPrice) formData.min_price = minPrice;
            if (maxPrice) formData.max_price = maxPrice;

            // Inline dropdown hidden inputs
            $form.find('.mpd-inline-dropdown input[type="hidden"]').each(function () {
                var name = $(this).attr('name');
                var value = $(this).val();
                if (value) {
                    formData[name] = value;
                }
            });

            // Inline toggle button hidden inputs
            $form.find('.mpd-inline-toggle input[type="hidden"]').each(function () {
                var name = $(this).attr('name');
                var value = $(this).val();
                if (value) {
                    formData[name] = value;
                }
            });

            // Merge with manual filter updates
            if (window.mpdAdvancedFilterUpdates) {
                $.extend(formData, window.mpdAdvancedFilterUpdates);
                window.mpdAdvancedFilterUpdates = {};
            }

            // Remove pagination
            delete params.paged;

            // Merge with existing params (remove filter params first)
            var filterParams = ['product_cat', 'product_tag', 'min_price', 'max_price', 'stock_status', 'featured', 'on_sale', 'rating_filter', 'product_brand'];
            filterParams.forEach(function (param) {
                delete params[param];
            });

            // Also remove any brand/attribute params (pa_*, filter_*, product_*)
            Object.keys(params).forEach(function (key) {
                if (key.indexOf('pa_') === 0 || key.indexOf('filter_') === 0 || (key.indexOf('product_') === 0 && key !== 'product_cat' && key !== 'product_tag')) {
                    delete params[key];
                }
            });

            // Add new filter params
            $.extend(params, formData);

            // Build query string
            var queryString = $.param(params);
            var newUrl = window.location.pathname;
            if (queryString) {
                newUrl += '?' + queryString;
            }

            // Update URL
            if (window.history && window.history.pushState) {
                window.history.pushState({}, '', newUrl);
            }

            // Find products container and trigger AJAX filter
            var $container = $('.mpd-products-archive').first();
            if ($container.length && typeof window.MPDProductsArchive !== 'undefined') {
                window.MPDProductsArchive.filterProducts($container, queryString);
            } else {
                // Fallback: reload page with new params
                window.location.href = newUrl;
            }

            // Close sidebar/popup
            if ($wrapper.hasClass('mpd-advanced-filter--sidebar') || $wrapper.hasClass('mpd-advanced-filter--popup')) {
                self.closeFilter($wrapper);
            }
        },

        /**
         * Reset all filters
         */
        resetFilters: function ($wrapper, $passedForm) {
            var self = this;
            // Use passed form if available, otherwise find form in wrapper
            var $form = $passedForm && $passedForm.length ? $passedForm : $wrapper.find('.mpd-advanced-filter__form').first();

            if (!$form.length) {
                // Fallback: just redirect to base URL without filter params
                var newUrl = window.location.pathname;
                window.location.href = newUrl;
                return;
            }

            // Reset form elements
            $form.find('.mpd-advanced-filter__dropdown').val('');
            $form.find('.mpd-advanced-filter__checkbox, .mpd-advanced-filter__radio').prop('checked', false);
            $form.find('input[type="number"]').val('');
            $form.find('.mpd-advanced-filter__item').removeClass('is-active');
            $form.find('.mpd-advanced-filter__tag').removeClass('is-active');

            // Reset inline dropdowns
            $form.find('.mpd-inline-dropdown').each(function () {
                var $dropdown = $(this);
                $dropdown.removeClass('has-selection is-open');
                $dropdown.find('.mpd-inline-dropdown__item').removeClass('is-active');
                $dropdown.find('.mpd-inline-dropdown__item:first-child').addClass('is-active');
                $dropdown.find('input[type="hidden"]').val('');
            });

            // Reset inline toggle buttons
            $form.find('.mpd-inline-toggle').each(function () {
                var $toggle = $(this);
                $toggle.removeClass('is-active');
                $toggle.find('input[type="hidden"]').val('');
                $toggle.data('value', 'yes');
            });

            // Reset price slider if exists
            var $slider = $form.find('.price_slider');
            if ($slider.length && typeof $.fn.slider !== 'undefined') {
                var min = $form.find('.min_price').data('min') || 0;
                var max = $form.find('.max_price').data('max') || 100;
                $slider.slider('values', [min, max]);
                $form.find('.min_price').val('');
                $form.find('.max_price').val('');
            }

            // Clear filter updates
            window.mpdAdvancedFilterUpdates = {};

            // Get current URL without filter params
            var params = self.getCurrentParams();
            var filterParams = ['product_cat', 'product_tag', 'min_price', 'max_price', 'stock_status', 'featured', 'on_sale', 'rating_filter', 'product_brand'];
            filterParams.forEach(function (param) {
                delete params[param];
            });

            // Remove attribute params (pa_*, filter_*, product_* except product_cat/product_tag)
            Object.keys(params).forEach(function (key) {
                if (key.indexOf('pa_') === 0 || key.indexOf('filter_') === 0 || (key.indexOf('product_') === 0 && key !== 'product_cat' && key !== 'product_tag')) {
                    delete params[key];
                }
            });

            delete params.paged;

            // Build query string
            var queryString = $.param(params);
            var newUrl = window.location.pathname;
            if (queryString) {
                newUrl += '?' + queryString;
            }

            // Update URL
            if (window.history && window.history.pushState) {
                window.history.pushState({}, '', newUrl);
            }

            // Trigger AJAX filter
            var $container = $('.mpd-products-archive').first();
            if ($container.length && typeof window.MPDProductsArchive !== 'undefined') {
                window.MPDProductsArchive.filterProducts($container, queryString);
            } else {
                window.location.href = newUrl;
            }

            // Close sidebar/popup
            if ($wrapper.hasClass('mpd-advanced-filter--sidebar') || $wrapper.hasClass('mpd-advanced-filter--popup')) {
                self.closeFilter($wrapper);
            }
        },

        /**
         * Get current URL params as object
         */
        getCurrentParams: function () {
            var params = {};
            var queryString = window.location.search.substring(1);
            if (queryString) {
                queryString.split('&').forEach(function (pair) {
                    var parts = pair.split('=');
                    if (parts[0]) {
                        params[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1] || '');
                    }
                });
            }
            return params;
        }
    };

    // Initialize on document ready
    $(document).ready(function () {
        MPDAdvancedFilter.init();
    });

    // Expose for external access
    window.MPDAdvancedFilter = MPDAdvancedFilter;

})(jQuery);
