/**
 * MPD AJAX Search Widget JavaScript
 * Handles search functionality, debouncing, and UI interactions
 */

(function($) {
    'use strict';

    class MPDAjaxSearch {
        constructor(element) {
            this.element = $(element);
            this.widgetId = this.element.data('widget-id');
            this.minChars = parseInt(this.element.data('min-chars')) || 3;
            this.delay = parseInt(this.element.data('delay')) || 300;
            this.limit = parseInt(this.element.data('limit')) || 10;
            this.nonce = this.element.data('nonce');
            
            this.input = this.element.find('.mpd-ajax-search__input');
            this.results = this.element.find('.mpd-ajax-search__results');
            this.spinner = this.element.find('.mpd-ajax-search__spinner');
            this.clearBtn = this.element.find('.mpd-ajax-search__clear');
            
            this.searchTimeout = null;
            this.currentRequest = null;
            this.isSearching = false;
            this.cache = new Map();
            
            this.init();
        }

        init() {
            // Ensure DOM is fully ready with longer delay and multiple checks
            const initializeWidget = () => {
                // Verify elements exist before binding
                if (this.input.length === 0) {
                    return;
                }
                
                this.bindEvents();
                this.setupAccessibility();
            };
            
            // Try immediate initialization
            if (document.readyState === 'complete') {
                initializeWidget();
            } else {
                // Wait for DOM ready
                $(document).ready(() => {
                    setTimeout(initializeWidget, 50);
                });
            }
        }

        bindEvents() {
            // Input events
            this.input.on('input', this.handleInput.bind(this));
            this.input.on('keydown', this.handleKeydown.bind(this));
            this.input.on('focus', this.handleFocus.bind(this));
            this.input.on('blur', this.handleBlur.bind(this));

            // Clear button
            this.clearBtn.on('click', this.clearSearch.bind(this));

            // Results events
            this.results.on('click', '.mpd-ajax-search__result-link', this.handleResultClick.bind(this));
            this.results.on('keydown', '.mpd-ajax-search__result-link', this.handleResultKeydown.bind(this));

            // Filter events (Pro)
            if (this.element.find('.mpd-ajax-search__filters').length || this.element.find('.mpd-ajax-search__filter-toggle').length) {
                this.bindFilterEvents();
            }

            // Click outside to close
            $(document).on('click', this.handleDocumentClick.bind(this));

            // Escape key to close
            $(document).on('keydown', this.handleDocumentKeydown.bind(this));
        }

        bindFilterEvents() {
            // Wait a bit more for filter elements to be available
            setTimeout(() => {
                // YouTube-style filter toggle (now positioned outside filters container)
                const toggleButton = this.element.find('.mpd-ajax-search__filter-toggle');
                
                if (toggleButton.length) {
                    toggleButton.off('click.mpdSearch').on('click.mpdSearch', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const toggle = $(e.currentTarget);
                        const chips = this.element.find('.mpd-ajax-search__filter-chips');
                        
                        if (chips.length === 0) {
                            return;
                        }
                        
                        // Hide search results when showing filters
                        if (!chips.is(':visible')) {
                            this.hideResults();
                        }
                        
                        // Check current state and toggle
                        if (chips.is(':visible')) {
                            chips.slideUp(300);
                            toggle.removeClass('active');
                        } else {
                            chips.slideDown(300);
                            toggle.addClass('active');
                        }
                    });
                }

                // Filter changes - improved selector
                const filters = this.element.find('.mpd-ajax-search__filters');
                
                if (filters.length) {
                    // Categories and other select filters
                    const selectFilters = filters.find('.mpd-ajax-search__filter-select');
                    
                    selectFilters.off('change.mpdSearch').on('change.mpdSearch', 
                        this.debounce((e) => {
                            this.handleFilterChange();
                        }, 300)
                    );

                    // Checkbox filters (featured, etc.)
                    const checkboxFilters = filters.find('.mpd-ajax-search__filter-checkbox');
                    
                    checkboxFilters.off('change.mpdSearch').on('change.mpdSearch', 
                        this.debounce((e) => {
                            this.handleFilterChange();
                        }, 300)
                    );

                    // Price range filters
                    const priceFilters = filters.find('.mpd-ajax-search__price-min, .mpd-ajax-search__price-max');
                    
                    priceFilters.off('input.mpdSearch').on('input.mpdSearch', 
                        this.debounce((e) => {
                            this.handlePriceRangeChange();
                        }, 500)
                    );
                }
            }, 100);
        }

        setupAccessibility() {
            // ARIA attributes
            this.input.attr({
                'role': 'combobox',
                'aria-autocomplete': 'list',
                'aria-expanded': 'false',
                'aria-haspopup': 'listbox'
            });

            this.results.attr({
                'role': 'listbox',
                'aria-label': 'Search results'
            });
        }

        handleInput(e) {
            const query = e.target.value.trim();
            
            // Show/hide clear button
            this.toggleClearButton(query.length > 0);
            
            // Clear previous timeout
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            // If query is too short, hide results
            if (query.length < this.minChars) {
                this.hideResults();
                return;
            }

            // Debounced search
            this.searchTimeout = setTimeout(() => {
                this.performSearch(query);
            }, this.delay);
        }

        handleKeydown(e) {
            const resultsVisible = this.results.is(':visible');
            const resultItems = this.results.find('.mpd-ajax-search__result-item');
            const currentFocus = resultItems.filter(':focus');

            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (!resultsVisible) return;
                    
                    if (currentFocus.length === 0) {
                        resultItems.first().focus();
                    } else {
                        const nextItem = currentFocus.next('.mpd-ajax-search__result-item');
                        if (nextItem.length) {
                            nextItem.focus();
                        }
                    }
                    break;

                case 'ArrowUp':
                    e.preventDefault();
                    if (!resultsVisible) return;
                    
                    if (currentFocus.length === 0) {
                        resultItems.last().focus();
                    } else {
                        const prevItem = currentFocus.prev('.mpd-ajax-search__result-item');
                        if (prevItem.length) {
                            prevItem.focus();
                        } else {
                            this.input.focus();
                        }
                    }
                    break;

                case 'Enter':
                    if (currentFocus.length) {
                        e.preventDefault();
                        currentFocus.click();
                    }
                    break;

                case 'Escape':
                    e.preventDefault();
                    this.hideResults();
                    this.input.blur();
                    break;
            }
        }

        handleFocus() {
            const query = this.input.val().trim();
            if (query.length >= this.minChars && this.results.children().length > 0) {
                this.showResults();
            }
        }

        handleBlur(e) {
            // Delay hiding to allow clicking on results
            setTimeout(() => {
                if (!this.element.find(':focus').length) {
                    this.hideResults();
                }
            }, 150);
        }

        handleResultClick(e) {
            e.preventDefault();
            const item = $(e.currentTarget);
            const url = item.attr('href');
            
            if (url) {
                // Add click tracking if needed
                this.trackClick(item.data('product-id'));
                
                // Navigate to product
                window.location.href = url;
            }
        }

        handleResultKeydown(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(e.currentTarget).click();
            }
        }

        handleDocumentClick(e) {
            // Close search results if clicking outside
            if (!this.element.is(e.target) && !this.element.has(e.target).length) {
                this.hideResults();
            }
            
            // Close filter chips if clicking outside (for YouTube style filters)
            const filterChips = this.element.find('.mpd-ajax-search__filter-chips');
            const filterToggle = this.element.find('.mpd-ajax-search__filter-toggle');
            
            if (filterChips.length && filterChips.is(':visible')) {
                // Check if click is outside both the toggle button and filter chips
                const clickedElement = $(e.target);
                const isClickOutside = !filterToggle.is(clickedElement) && 
                                     !filterToggle.has(clickedElement).length &&
                                     !filterChips.is(clickedElement) && 
                                     !filterChips.has(clickedElement).length;
                
                if (isClickOutside) {
                    filterChips.slideUp(300);
                    filterToggle.removeClass('active');
                }
            }
        }

        handleDocumentKeydown(e) {
            if (e.key === 'Escape') {
                // Close search results if visible
                if (this.results.is(':visible')) {
                    this.hideResults();
                    this.input.focus();
                }
                
                // Close filter chips if visible
                const filterChips = this.element.find('.mpd-ajax-search__filter-chips');
                const filterToggle = this.element.find('.mpd-ajax-search__filter-toggle');
                
                if (filterChips.is(':visible')) {
                    filterChips.slideUp(300);
                    filterToggle.removeClass('active');
                }
            }
        }

        handleFilterChange() {
            const query = this.input.val().trim();
            
            // Clear cache when filters change
            this.cache.clear();
            
            // Perform search if there's a query
            if (query.length >= this.minChars) {
                this.performSearch(query);
            } else {
                // Hide results if no query
                this.hideResults();
            }
        }

        handlePriceRangeChange() {
            const minPrice = this.element.find('.mpd-ajax-search__price-min').val();
            const maxPrice = this.element.find('.mpd-ajax-search__price-max').val();
            
            // Update display
            this.element.find('.mpd-ajax-search__price-min-display').text(this.formatPrice(minPrice));
            this.element.find('.mpd-ajax-search__price-max-display').text(this.formatPrice(maxPrice));
            
            // Trigger search
            this.handleFilterChange();
        }

        performSearch(query) {
            // Check cache first
            const cacheKey = this.getCacheKey(query);
            if (this.cache.has(cacheKey)) {
                this.displayResults(this.cache.get(cacheKey));
                return;
            }

            // Cancel previous request
            if (this.currentRequest) {
                this.currentRequest.abort();
            }

            // Show loading state
            this.showLoading();

            // Prepare search data
            const searchData = {
                action: 'mpd_ajax_search',
                nonce: this.nonce,
                query: query,
                limit: this.limit,
                widget_id: this.widgetId,
                filters: this.getActiveFilters()
            };

            // Perform AJAX request
            this.currentRequest = $.ajax({
                url: mpdAjaxSearch.ajaxUrl,
                type: 'POST',
                data: searchData,
                timeout: 10000,
                beforeSend: () => {
                    this.isSearching = true;
                    this.element.addClass('mpd-ajax-search--loading');
                },
                success: (response) => {
                    if (response && response.success && response.data) {
                        // Cache results
                        this.cache.set(cacheKey, response.data);
                        
                        // Display results
                        this.displayResults(response.data);
                    } else {
                        // Handle error response
                        let errorMessage = 'Search failed';
                        if (response && response.data && response.data.message) {
                            errorMessage = response.data.message;
                        } else if (response && response.message) {
                            errorMessage = response.message;
                        }
                        this.displayError(errorMessage);
                    }
                },
                error: (xhr, status, error) => {
                    if (status !== 'abort') {
                        let errorMessage = 'Network error occurred';
                        
                        // Try to parse error response
                        if (xhr.responseText) {
                            try {
                                const errorResponse = JSON.parse(xhr.responseText);
                                if (errorResponse.data && errorResponse.data.message) {
                                    errorMessage = errorResponse.data.message;
                                } else if (errorResponse.message) {
                                    errorMessage = errorResponse.message;
                                }
                            } catch (e) {
                                console.log('MPD Search: Could not parse error response');
                            }
                        }
                        
                        this.displayError(errorMessage);
                        console.error('MPD Search Error:', error);
                    }
                },
                complete: () => {
                    this.isSearching = false;
                    this.hideLoading();
                    this.element.removeClass('mpd-ajax-search--loading');
                    this.currentRequest = null;
                }
            });
        }

        getActiveFilters() {
            const filters = {};
            const filterContainer = this.element.find('.mpd-ajax-search__filters');
            
            console.log('MPD Search: Getting active filters, container found:', filterContainer.length);
            
            if (!filterContainer.length) {
                return filters;
            }

            // Categories
            const category = filterContainer.find('[data-filter="categories"]').val();
            if (category && category !== '') {
                filters.category = category;
                console.log('MPD Search: Category filter:', category);
            }

            // Tags
            const tags = filterContainer.find('[data-filter="tags"]').val();
            if (tags && tags.length) {
                // Handle both single value and array
                if (Array.isArray(tags)) {
                    filters.tags = tags.filter(tag => tag !== ''); // Remove empty values
                } else if (tags !== '') {
                    filters.tags = [tags]; // Convert single value to array
                }
                console.log('MPD Search: Tags filter:', filters.tags);
            }

            // Price range
            const priceMin = filterContainer.find('[data-filter="price_min"]').val();
            const priceMax = filterContainer.find('[data-filter="price_max"]').val();
            if (priceMin && priceMin !== '' && parseFloat(priceMin) > 0) {
                filters.price_min = priceMin;
                console.log('MPD Search: Price min filter:', priceMin);
            }
            if (priceMax && priceMax !== '' && parseFloat(priceMax) > 0) {
                filters.price_max = priceMax;
                console.log('MPD Search: Price max filter:', priceMax);
            }

            // Featured
            const featured = filterContainer.find('[data-filter="featured"]').is(':checked');
            if (featured) {
                filters.featured = true;
                console.log('MPD Search: Featured filter enabled');
            }

            // Stock status
            const stockStatus = filterContainer.find('[data-filter="stock_status"]').val();
            if (stockStatus && stockStatus !== '') {
                filters.stock_status = stockStatus;
                console.log('MPD Search: Stock status filter:', stockStatus);
            }

            console.log('MPD Search: Final active filters:', filters);
            return filters;
        }

        getCacheKey(query) {
            const filters = this.getActiveFilters();
            return query + '|' + JSON.stringify(filters) + '|' + this.limit;
        }

        displayResults(data) {
            const { products, total, query } = data;
            
            if (!products || products.length === 0) {
                this.displayNoResults(query);
                return;
            }

            let html = `<div class="mpd-ajax-search__results-header">Found ${products.length} result${products.length !== 1 ? 's' : ''}</div>`;
            html += '<ul class="mpd-ajax-search__results-list">';
            
            products.forEach(product => {
                html += this.renderProductItem(product);
            });
            
            html += '</ul>';

            // Add "view all" link if there are more results
            if (total > products.length) {
                html += this.renderViewAllLink(query, total);
            }

            this.results.html(html);
            this.showResults();
            
            // Announce to screen readers
            this.announceResults(products.length, total);
        }

        renderProductItem(product) {
            const imageHtml = product.image ? 
                `<img src="${this.escapeHtml(product.image)}" alt="${this.escapeHtml(product.title)}" class="mpd-ajax-search__result-image" loading="lazy">` : '';

            const skuHtml = product.sku ? 
                `<div class="mpd-ajax-search__result-sku">SKU: ${this.escapeHtml(product.sku)}</div>` : '';

            return `
                <li class="mpd-ajax-search__result-item">
                    <a href="${this.escapeHtml(product.url)}" 
                       class="mpd-ajax-search__result-link" 
                       data-product-id="${product.id}"
                       tabindex="0"
                       role="option"
                       aria-label="${this.escapeHtml(product.title)} - ${this.escapeHtml(product.price_html)}">
                        ${imageHtml}
                        <div class="mpd-ajax-search__result-content">
                            <h4 class="mpd-ajax-search__result-title">${this.escapeHtml(product.title)}</h4>
                            <div class="mpd-ajax-search__result-price">${product.price_html}</div>
                            ${skuHtml}
                        </div>
                    </a>
                </li>
            `;
        }

        renderViewAllLink(query, total) {
            const shopUrl = mpdAjaxSearch.shopUrl || '/shop/';
            const searchUrl = `${shopUrl}?s=${encodeURIComponent(query)}`;
            
            return `
                <a href="${searchUrl}" 
                   class="mpd-ajax-search__result-item mpd-ajax-search__view-all" 
                   tabindex="0"
                   role="option">
                    <div class="mpd-ajax-search__result-item-content">
                        <h4 class="mpd-ajax-search__result-item-title">
                            View all ${total} results for "${this.escapeHtml(query)}"
                        </h4>
                    </div>
                </a>
            `;
        }

        displayNoResults(query) {
            const html = `
                <div class="mpd-ajax-search__no-results">
                    <div class="mpd-ajax-search__no-results-icon">🔍</div>
                    <p class="mpd-ajax-search__no-results-text">
                        No products found for "${this.escapeHtml(query)}"
                    </p>
                    <p class="mpd-ajax-search__no-results-suggestion">
                        Try different keywords or check spelling
                    </p>
                </div>
            `;
            
            this.results.html(html);
            this.showResults();
            
            // Announce to screen readers
            this.announceResults(0, 0);
        }

        displayError(message) {
            const html = `
                <div class="mpd-ajax-search__error">
                    <p>Error: ${this.escapeHtml(message)}</p>
                </div>
            `;
            
            this.results.html(html);
            this.showResults();
            this.element.addClass('mpd-ajax-search--error');
        }

        showResults() {
            // Hide filter chips when showing results
            const filterChips = this.element.find('.mpd-ajax-search__filter-chips');
            const filterToggle = this.element.find('.mpd-ajax-search__filter-toggle');
            
            if (filterChips.is(':visible')) {
                filterChips.slideUp(300);
                filterToggle.removeClass('active');
            }
            
            this.results.addClass('mpd-ajax-search__results--visible').show();
            this.input.attr('aria-expanded', 'true');
        }

        hideResults() {
            this.results.removeClass('mpd-ajax-search__results--visible').hide();
            this.input.attr('aria-expanded', 'false');
            this.element.removeClass('mpd-ajax-search--error');
        }

        showLoading() {
            // Show spinner and hide clear button during loading
            this.spinner.show();
            this.toggleClearButton(false);
            this.results.addClass('mpd-ajax-search__results--loading').show();
        }

        hideLoading() {
            // Hide spinner and show clear button again if there's text
            this.spinner.hide();
            this.results.removeClass('mpd-ajax-search__results--loading');
            // Show clear button again if there's text in input
            this.toggleClearButton(this.input.val().length > 0);
        }

        toggleClearButton(show) {
            if (this.clearBtn.length) {
                this.clearBtn.toggle(show);
            }
        }

        clearSearch() {
            this.input.val('').focus();
            this.hideResults();
            this.toggleClearButton(false);
            
            // Clear filters if in integrated mode
            const filters = this.element.find('.mpd-ajax-search__filters--integrated');
            if (filters.length) {
                filters.find('select').val('');
                filters.find('input[type="checkbox"]').prop('checked', false);
            }
        }

        announceResults(count, total) {
            const message = count > 0 ? 
                `${count} of ${total} results found` : 
                'No results found';
                
            this.announce(message);
        }

        announce(message) {
            // Create or update screen reader announcement
            let announcer = $('#mpd-search-announcer');
            if (!announcer.length) {
                announcer = $('<div id="mpd-search-announcer" aria-live="polite" aria-atomic="true" class="sr-only"></div>');
                $('body').append(announcer);
            }
            announcer.text(message);
        }

        trackClick(productId) {
            // Optional: Track search result clicks for analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'search_result_click', {
                    'product_id': productId,
                    'search_term': this.input.val()
                });
            }
        }

        formatPrice(price) {
            // Simple price formatting - can be enhanced
            return mpdAjaxSearch.currencySymbol + parseFloat(price).toFixed(2);
        }

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        debounce(func, delay) {
            let timeoutId;
            return function (...args) {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => func.apply(this, args), delay);
            };
        }

        // Public methods for external control
        destroy() {
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            if (this.currentRequest) {
                this.currentRequest.abort();
            }
            
            this.element.off();
            this.cache.clear();
        }

        reset() {
            this.clearSearch();
            this.cache.clear();
        }

        search(query) {
            this.input.val(query);
            if (query.length >= this.minChars) {
                this.performSearch(query);
            }
        }
    }

    // Initialize widgets when document is ready
    $(document).ready(function() {
        // Wait a bit for Elementor to fully render
        setTimeout(() => {
            $('.mpd-ajax-search').each(function() {
                const widget = new MPDAjaxSearch(this);
                $(this).data('mpdAjaxSearch', widget);
                console.log('MPD Search: Widget instance created');
            });
        }, 100);
    });

    // Re-initialize widgets after Elementor preview updates
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mpd_ajax_search.default', function($scope) {
            setTimeout(() => {
                const searchElement = $scope.find('.mpd-ajax-search')[0];
                if (searchElement) {
                    const widget = new MPDAjaxSearch(searchElement);
                    $scope.find('.mpd-ajax-search').data('mpdAjaxSearch', widget);
                    console.log('MPD Search: Elementor widget instance created');
                }
            }, 150);
        });
    });

    // Expose class globally for external access
    window.MPDAjaxSearch = MPDAjaxSearch;

})(jQuery);
