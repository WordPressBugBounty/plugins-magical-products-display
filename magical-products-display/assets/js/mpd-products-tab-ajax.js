/**
 * MPD Products Tab AJAX Handler
 * Handles lazy loading of tab content
 * 
 * @since 1.1.35
 */
(function($) {
    'use strict';

    // Store loaded tabs to avoid reloading
    var loadedTabs = {};

    /**
     * Initialize AJAX tabs
     */
    function initAjaxTabs() {
        // Find all AJAX-enabled tab containers
        $('.mpd-ajax-tabs').each(function() {
            var $container = $(this);
            var containerId = $container.data('container-id');
            
            // Initialize loaded tabs tracker for this container
            if (!loadedTabs[containerId]) {
                loadedTabs[containerId] = {};
            }

            // Load first tab content on init (if not already loaded)
            var $firstTab = $container.find('.nav-link.active');
            if ($firstTab.length && $firstTab.data('ajax-load') === 'yes') {
                var firstTabId = $firstTab.attr('data-bs-target');
                var $firstPane = $(firstTabId);
                
                if (!loadedTabs[containerId][firstTabId] && !$firstPane.find('.mpd-tab-loaded').length) {
                    loadTabContent($firstTab, $firstPane, containerId);
                }
            }

            // Bind click event to tab links
            $container.find('.nav-link[data-ajax-load="yes"]').on('click', function(e) {
                var $tab = $(this);
                var tabId = $tab.attr('data-bs-target');
                var $pane = $(tabId);

                // Check if already loaded
                if (loadedTabs[containerId][tabId] || $pane.find('.mpd-tab-loaded').length) {
                    return; // Already loaded, let Bootstrap handle the tab switch
                }

                // Load content via AJAX
                loadTabContent($tab, $pane, containerId);
            });
        });
    }

    /**
     * Load tab content via AJAX
     */
    function loadTabContent($tab, $pane, containerId) {
        var categorySlug = $tab.data('category-slug');
        var settings = $tab.data('settings');
        var nonce = $tab.data('nonce');
        var tabId = $tab.attr('data-bs-target');

        // Show loading indicator
        var $contentWrapper = $pane.find('.mpd-tab-content-wrapper');
        var $loader = $pane.find('.mpd-tab-loader');
        
        $loader.show();
        $contentWrapper.css('opacity', '0.3');

        // Make AJAX request
        $.ajax({
            url: mpdTabAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'mpd_load_tab_products',
                category_slug: categorySlug,
                settings: settings,
                nonce: nonce
            },
            success: function(response) {
                if (response.success) {
                    // Insert the HTML content
                    $contentWrapper.html(response.data.html);
                    $contentWrapper.addClass('mpd-tab-loaded');
                    
                    // Mark as loaded
                    loadedTabs[containerId][tabId] = true;

                    // Trigger WooCommerce events for add to cart buttons
                    $(document.body).trigger('wc_fragment_refresh');
                    
                    // Re-initialize any scripts that might be needed
                    $(document).trigger('mpd_tab_content_loaded', [$pane, categorySlug]);
                } else {
                    $contentWrapper.html('<div class="alert alert-warning text-center">' + (response.data.message || 'Error loading products') + '</div>');
                }
            },
            error: function(xhr, status, error) {
                $contentWrapper.html('<div class="alert alert-danger text-center">Error loading products. Please try again.</div>');
                console.error('MPD Tab AJAX Error:', error);
            },
            complete: function() {
                // Hide loader and restore opacity
                $loader.hide();
                $contentWrapper.css('opacity', '1');
            }
        });
    }

    /**
     * Get SVG loader HTML
     */
    function getLoaderSVG(color, size) {
        color = color || '#0073aa';
        size = size || 40;
        
        return '<svg xmlns="http://www.w3.org/2000/svg" width="' + size + '" height="' + size + '" viewBox="0 0 44 44" stroke="' + color + '">' +
            '<g fill="none" fill-rule="evenodd" stroke-width="2">' +
                '<circle cx="22" cy="22" r="1">' +
                    '<animate attributeName="r" begin="0s" dur="1.8s" values="1; 20" calcMode="spline" keyTimes="0; 1" keySplines="0.165, 0.84, 0.44, 1" repeatCount="indefinite"/>' +
                    '<animate attributeName="stroke-opacity" begin="0s" dur="1.8s" values="1; 0" calcMode="spline" keyTimes="0; 1" keySplines="0.3, 0.61, 0.355, 1" repeatCount="indefinite"/>' +
                '</circle>' +
                '<circle cx="22" cy="22" r="1">' +
                    '<animate attributeName="r" begin="-0.9s" dur="1.8s" values="1; 20" calcMode="spline" keyTimes="0; 1" keySplines="0.165, 0.84, 0.44, 1" repeatCount="indefinite"/>' +
                    '<animate attributeName="stroke-opacity" begin="-0.9s" dur="1.8s" values="1; 0" calcMode="spline" keyTimes="0; 1" keySplines="0.3, 0.61, 0.355, 1" repeatCount="indefinite"/>' +
                '</circle>' +
            '</g>' +
        '</svg>';
    }

    // Initialize on document ready
    $(document).ready(function() {
        initAjaxTabs();
    });

    // Re-initialize on Elementor frontend init (for editor preview)
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/mg_products_tab.default', function($scope) {
                initAjaxTabs();
            });
        }
    });

})(jQuery);
