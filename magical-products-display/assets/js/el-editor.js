
(function ($) {
    "use strict";

    /**
     * Pro section controls lock for free version.
     *
     * Finds .mpd-pro-notice elements in the Elementor panel and adds
     * a locked class to all sibling controls within the same section,
     * making them visible but non-interactive (read-only).
     */
    function initProSectionLock() {
        var doc = parent.document;

        function lockProControls() {
            var notices = doc.querySelectorAll('.mpd-pro-notice');

            for (var i = 0; i < notices.length; i++) {
                var controlEl = notices[i].closest('.elementor-control');
                if (!controlEl) continue;

                // Mark sibling controls as locked until the next section
                var sibling = controlEl.nextElementSibling;
                while (sibling && !sibling.classList.contains('elementor-control-type-section')) {
                    if (!sibling.classList.contains('mpd-pro-control-locked')) {
                        sibling.classList.add('mpd-pro-control-locked');
                    }
                    sibling = sibling.nextElementSibling;
                }
            }
        }

        // Run when widget editor panel opens
        if (typeof elementor !== 'undefined' && elementor.hooks) {
            elementor.hooks.addAction('panel/open_editor/widget', function() {
                setTimeout(lockProControls, 200);
            });
        }

        // Run when panel sections are toggled open
        doc.addEventListener('click', function(e) {
            if (e.target.closest('.elementor-control-type-section')) {
                setTimeout(lockProControls, 250);
            }
        }, true);

        // Initial run
        setTimeout(lockProControls, 500);
    }

    initProSectionLock();

    /**
     * Smart category collapse/expand based on template type.
     * Categories auto-expand when editing relevant page types.
     */
    function initSmartCategoryCollapse() {
        // Get the template type from localized data or URL.
        var mpdEditorData = window.mpdEditorData || {};
        var templateType = mpdEditorData.templateType || '';
        
        // Category mappings: template type => category slug.
        var categoryMappings = {
            'single-product': 'mpd-single-product',
            'archive-product': 'mpd-shop-archive',
            'cart': 'mpd-cart-checkout',
            'checkout': 'mpd-cart-checkout',
            'my-account': 'mpd-my-account',
            'thankyou': 'mpd-thankyou',
            'empty-cart': 'mpd-cart-checkout'
        };

        // Wait for Elementor panel to be ready.
        if (typeof elementor !== 'undefined' && elementor.channels) {
            elementor.channels.editor.on('open', function() {
                handleCategoryExpansion(templateType, categoryMappings);
            });
        }

        // Also handle on document ready.
        $(document).ready(function() {
            setTimeout(function() {
                handleCategoryExpansion(templateType, categoryMappings);
            }, 500);
        });
    }

    /**
     * Handle category expansion based on template type.
     */
    function handleCategoryExpansion(templateType, categoryMappings) {
        var targetCategory = categoryMappings[templateType];
        var parentDoc = parent.document;

        // All MPD categories.
        var mpdCategories = [
            'mpd-productwoo',
            'mpd-single-product',
            'mpd-cart-checkout',
            'mpd-my-account',
            'mpd-shop-archive',
            'mpd-thankyou',
            'mpd-global'
        ];

        mpdCategories.forEach(function(categorySlug) {
            var categoryPanel = parentDoc.querySelector('#elementor-panel-category-' + categorySlug);
            if (!categoryPanel) return;

            // Shop Builder Pro is always expanded.
            if (categorySlug === 'mpd-productwoo') {
                if (!categoryPanel.classList.contains('elementor-active')) {
                    var titleEl = categoryPanel.querySelector('.elementor-panel-category-title');
                    if (titleEl) titleEl.click();
                }
                return;
            }

            // Expand the matching category for the template type.
            if (categorySlug === targetCategory) {
                if (!categoryPanel.classList.contains('elementor-active')) {
                    var titleEl = categoryPanel.querySelector('.elementor-panel-category-title');
                    if (titleEl) titleEl.click();
                }
            }
        });
    }

    // Initialize smart category collapse.
    initSmartCategoryCollapse();

    // Pro widget promotion dialog handler.
    parent.document.addEventListener("mousedown", function(e) {
        var widgets = parent.document.querySelectorAll("#elementor-panel-category-mpd-productwoo .elementor-element--promotion");

        if (widgets.length > 0) {
            for (var i = 0; i < widgets.length; i++) {
                if (widgets[i].contains(e.target)) {
                    var dialog = parent.document.querySelector("#elementor-element--promotion__dialog");

                        dialog.querySelector(".dialog-buttons-action").style.display = "none";

                        if (dialog.querySelector(".mpd-dialog-promotion") === null) {
                            var button = document.createElement("a");
                            var buttonText = document.createTextNode("Upgrade Pro Version");

                            button.setAttribute("href", "https://wpthemespace.com/product/magical-shop-builder/#pricing");
                            button.setAttribute("target", "_blank");
                            button.classList.add(
                                "dialog-button",
                                "dialog-action",
                                "dialog-buttons-action",
                                "elementor-button",
                                "elementor-button-success",
                                "mpd-dialog-promotion"
                            );
                            button.appendChild(buttonText);

                            dialog.querySelector(".dialog-buttons-action").insertAdjacentHTML("afterend", button.outerHTML);
                        } else {
                            dialog.querySelector(".mpd-dialog-promotion").style.display = "";
                        }
                       $('.mpd-dialog-promotion').next('button').hide();
                    

                    // stop loop
                    break;
                }
            }
        }
    });


}(jQuery));