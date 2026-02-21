/**
 * Magical Shop Builder - My Account Widgets JavaScript
 *
 * Handles interactive features for My Account widgets including
 * logout confirmation modal and inline editing.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

(function ($) {
    'use strict';

    /**
     * Logout Widget Handler
     */
    var MPDLogout = {
        init: function () {
            this.bindEvents();
        },

        bindEvents: function () {
            // Logout modal trigger
            $(document).on('click', '[data-mpd-logout-trigger]', this.openModal);

            // Close modal handlers
            $(document).on('click', '[data-mpd-logout-close]', this.closeModal);
            $(document).on('keydown', this.handleKeydown);
        },

        openModal: function (e) {
            e.preventDefault();
            var $button = $(this);
            var $wrapper = $button.closest('.mpd-logout');
            var $modal = $wrapper.find('[data-mpd-logout-modal]');

            if ($modal.length) {
                $modal.fadeIn(200);
                $('body').addClass('mpd-modal-open');

                // Focus trap
                $modal.find('.mpd-logout__cancel-button').focus();
            }
        },

        closeModal: function (e) {
            if (e) {
                e.preventDefault();
            }

            var $modal = $('[data-mpd-logout-modal]:visible');

            if ($modal.length) {
                $modal.fadeOut(200, function () {
                    $('body').removeClass('mpd-modal-open');
                });

                // Return focus to trigger button
                $modal.closest('.mpd-logout').find('[data-mpd-logout-trigger]').focus();
            }
        },

        handleKeydown: function (e) {
            // Close modal on Escape key
            if (e.key === 'Escape' || e.keyCode === 27) {
                var $modal = $('[data-mpd-logout-modal]:visible');
                if ($modal.length) {
                    MPDLogout.closeModal();
                }
            }
        }
    };

    /**
     * Addresses Widget Handler (Inline Editing - Pro)
     */
    var MPDAddresses = {
        init: function () {
            this.bindEvents();
        },

        bindEvents: function () {
            // Inline edit trigger
            $(document).on('click', '.mpd-addresses__inline-edit', this.toggleInlineEdit);

            // Save address
            $(document).on('submit', '.mpd-addresses__inline-form', this.saveAddress);

            // Cancel edit
            $(document).on('click', '.mpd-addresses__cancel-edit', this.cancelEdit);
        },

        toggleInlineEdit: function (e) {
            e.preventDefault();
            var $card = $(this).closest('.mpd-addresses__card');
            $card.addClass('mpd-addresses__card--editing');
        },

        cancelEdit: function (e) {
            e.preventDefault();
            var $card = $(this).closest('.mpd-addresses__card');
            $card.removeClass('mpd-addresses__card--editing');
        },

        saveAddress: function (e) {
            e.preventDefault();
            var $form = $(this);
            var $card = $form.closest('.mpd-addresses__card');
            var $submitBtn = $form.find('.mpd-addresses__save-edit');

            // Add loading state
            $submitBtn.prop('disabled', true).addClass('mpd-loading');

            $.ajax({
                url: mpdMyAccountWidgets.ajaxUrl,
                type: 'POST',
                data: $form.serialize() + '&action=mpd_save_address&nonce=' + mpdMyAccountWidgets.nonce,
                success: function (response) {
                    if (response.success) {
                        // Update address content
                        $card.find('.mpd-addresses__content').html(response.data.formatted_address);
                        $card.removeClass('mpd-addresses__card--editing');
                    } else {
                        alert(response.data.message || mpdMyAccountWidgets.i18n.saveError);
                    }
                },
                error: function () {
                    alert(mpdMyAccountWidgets.i18n.saveError);
                },
                complete: function () {
                    $submitBtn.prop('disabled', false).removeClass('mpd-loading');
                }
            });
        }
    };

    /**
     * Orders Widget Handler (Filtering - Pro)
     */
    var MPDOrders = {
        init: function () {
            this.bindEvents();
        },

        bindEvents: function () {
            // Search input
            $(document).on('input', '.mpd-orders__search-input', this.debounce(this.filterOrders, 300));

            // Status filter
            $(document).on('change', '.mpd-orders__status-select', this.filterOrders);

            // Date filters
            $(document).on('change', '.mpd-orders__date-from, .mpd-orders__date-to', this.filterOrders);
        },

        filterOrders: function () {
            var $ordersWidget = $(this).closest('.mpd-orders');
            var $table = $ordersWidget.find('.mpd-orders__table');
            var $tbody = $table.find('tbody');

            var searchTerm = $ordersWidget.find('.mpd-orders__search-input').val().toLowerCase();
            var statusFilter = $ordersWidget.find('.mpd-orders__status-select').val();
            var dateFrom = $ordersWidget.find('.mpd-orders__date-from').val();
            var dateTo = $ordersWidget.find('.mpd-orders__date-to').val();

            $tbody.find('tr').each(function () {
                var $row = $(this);
                var show = true;

                // Search filter
                if (searchTerm && $row.text().toLowerCase().indexOf(searchTerm) === -1) {
                    show = false;
                }

                // Status filter
                if (statusFilter && show) {
                    var rowStatus = $row.find('.mpd-order-status').attr('class');
                    if (rowStatus && rowStatus.indexOf(statusFilter.replace('wc-', '')) === -1) {
                        show = false;
                    }
                }

                $row.toggle(show);
            });
        },

        debounce: function (func, wait) {
            var timeout;
            return function () {
                var context = this, args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    func.apply(context, args);
                }, wait);
            };
        }
    };

    /**
     * Account Details Widget Handler (Avatar Upload - Pro)
     */
    var MPDAccountDetails = {
        init: function () {
            this.bindEvents();
        },

        bindEvents: function () {
            // Avatar upload button
            $(document).on('click', '.mpd-account-details__avatar-change', this.triggerFileInput);

            // Avatar file input change
            $(document).on('change', '.mpd-account-details__avatar-input', this.handleAvatarUpload);
        },

        triggerFileInput: function (e) {
            e.preventDefault();
            var $wrapper = $(this).closest('.mpd-account-details__avatar');
            var $fileInput = $wrapper.find('.mpd-account-details__avatar-input');

            if ($fileInput.length === 0) {
                // Create file input if it doesn't exist
                $fileInput = $('<input type="file" class="mpd-account-details__avatar-input" accept="image/*" style="display:none;">');
                $wrapper.append($fileInput);
            }

            $fileInput.click();
        },

        handleAvatarUpload: function (e) {
            var $input = $(this);
            var $wrapper = $input.closest('.mpd-account-details__avatar');
            var $image = $wrapper.find('img');

            if (this.files && this.files[0]) {
                var file = this.files[0];

                // Validate file type
                if (!file.type.match('image.*')) {
                    alert(mpdMyAccountWidgets.i18n.invalidFileType);
                    return;
                }

                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert(mpdMyAccountWidgets.i18n.fileTooLarge);
                    return;
                }

                // Preview image
                var reader = new FileReader();
                reader.onload = function (e) {
                    $image.attr('src', e.target.result);
                };
                reader.readAsDataURL(file);

                // Upload via AJAX
                var formData = new FormData();
                formData.append('avatar', file);
                formData.append('action', 'mpd_upload_avatar');
                formData.append('nonce', mpdMyAccountWidgets.nonce);

                $.ajax({
                    url: mpdMyAccountWidgets.ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            $image.attr('src', response.data.avatar_url);
                        } else {
                            alert(response.data.message || mpdMyAccountWidgets.i18n.uploadError);
                        }
                    },
                    error: function () {
                        alert(mpdMyAccountWidgets.i18n.uploadError);
                    }
                });
            }
        }
    };

    /**
     * Account Login Widget Handler (Tabs)
     */
    var MPDAccountLogin = {
        init: function () {
            this.bindEvents();
        },

        bindEvents: function () {
            // Tab click
            $(document).on('click', '.mpd-account-login__tab-btn', this.handleTabClick);
        },

        handleTabClick: function (e) {
            e.preventDefault();
            var $button = $(this);
            var $wrapper = $button.closest('.mpd-account-login');
            var tabName = $button.data('tab');

            // Update active tab button
            $wrapper.find('.mpd-account-login__tab-btn').removeClass('mpd-account-login__tab-btn--active');
            $button.addClass('mpd-account-login__tab-btn--active');

            // Update active panel
            $wrapper.find('.mpd-account-login__tab-panel').removeClass('mpd-account-login__tab-panel--active');
            $wrapper.find('.mpd-account-login__tab-panel[data-panel="' + tabName + '"]').addClass('mpd-account-login__tab-panel--active');
        }
    };

    /**
     * Account Navigation Widget Handler (Pro Features)
     */
    var MPDAccountNav = {
        stickyNavs: [],

        init: function () {
            this.bindEvents();
            this.initStickyNavigation();
        },

        bindEvents: function () {
            // Logout confirmation for navigation widget
            $(document).on('click', '.mpd-account-nav--logout-confirm .mpd-account-nav__logout-link', this.handleLogoutClick);
        },

        initStickyNavigation: function () {
            var self = this;

            // Find all sticky navs
            $('.mpd-account-nav--sticky').each(function () {
                var $nav = $(this);
                
                // Get settings from data attributes
                var offset = parseInt($nav.data('sticky-offset')) || 20;
                var zindex = parseInt($nav.data('sticky-zindex')) || 100;
                
                // Store original position and dimensions
                var navOffset = $nav.offset();
                var navWidth = $nav.outerWidth();
                var navHeight = $nav.outerHeight();
                
                // Create placeholder element to prevent layout jump
                var $placeholder = $('<div class="mpd-account-nav-placeholder"></div>');
                $placeholder.css({
                    width: navWidth,
                    height: navHeight
                });
                $nav.before($placeholder);
                
                // Set CSS variables
                $nav.css({
                    '--mpd-sticky-offset': offset + 'px',
                    '--mpd-sticky-zindex': zindex,
                    '--mpd-sticky-width': navWidth + 'px'
                });
                
                // Store nav data
                self.stickyNavs.push({
                    $nav: $nav,
                    $placeholder: $placeholder,
                    originalTop: navOffset.top,
                    offset: offset,
                    width: navWidth,
                    height: navHeight
                });
            });

            // Bind scroll event if we have sticky navs
            if (self.stickyNavs.length > 0) {
                $(window).on('scroll.mpdStickyNav', function () {
                    self.handleScroll();
                });
                
                // Handle resize to recalculate positions
                $(window).on('resize.mpdStickyNav', self.debounce(function () {
                    self.recalculatePositions();
                }, 250));
                
                // Initial check
                self.handleScroll();
            }
        },

        handleScroll: function () {
            var scrollTop = $(window).scrollTop();

            $.each(this.stickyNavs, function (index, data) {
                var triggerPoint = data.originalTop - data.offset;

                if (scrollTop >= triggerPoint) {
                    // Add fixed class
                    if (!data.$nav.hasClass('mpd-account-nav--fixed')) {
                        data.$nav.addClass('mpd-account-nav--fixed');
                        data.$placeholder.addClass('mpd-placeholder-active');
                    }
                } else {
                    // Remove fixed class
                    if (data.$nav.hasClass('mpd-account-nav--fixed')) {
                        data.$nav.removeClass('mpd-account-nav--fixed');
                        data.$placeholder.removeClass('mpd-placeholder-active');
                    }
                }
            });
        },

        recalculatePositions: function () {
            var self = this;

            $.each(this.stickyNavs, function (index, data) {
                // Temporarily remove fixed to get true position
                var wasFixed = data.$nav.hasClass('mpd-account-nav--fixed');
                data.$nav.removeClass('mpd-account-nav--fixed');
                data.$placeholder.removeClass('mpd-placeholder-active');

                // Recalculate
                var navOffset = data.$nav.offset();
                var navWidth = data.$nav.outerWidth();
                var navHeight = data.$nav.outerHeight();

                data.originalTop = navOffset.top;
                data.width = navWidth;
                data.height = navHeight;

                // Update placeholder
                data.$placeholder.css({
                    width: navWidth,
                    height: navHeight
                });

                // Update CSS variable
                data.$nav.css('--mpd-sticky-width', navWidth + 'px');

                // Restore fixed state if needed
                if (wasFixed) {
                    self.handleScroll();
                }
            });
        },

        debounce: function (func, wait) {
            var timeout;
            return function () {
                var context = this, args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    func.apply(context, args);
                }, wait);
            };
        },

        handleLogoutClick: function (e) {
            e.preventDefault();

            var $link = $(this);
            var $nav = $link.closest('.mpd-account-nav--logout-confirm');
            var message = $nav.data('logout-message') || 'Are you sure you want to logout?';
            var logoutUrl = $link.attr('href');

            // Create confirmation modal with inline SVG icon
            var $modal = $('<div class="mpd-nav-logout-modal">' +
                '<div class="mpd-nav-logout-modal__backdrop"></div>' +
                '<div class="mpd-nav-logout-modal__content">' +
                '<div class="mpd-nav-logout-modal__icon">' +
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg>' +
                '</div>' +
                '<p class="mpd-nav-logout-modal__message">' + message + '</p>' +
                '<div class="mpd-nav-logout-modal__actions">' +
                '<button type="button" class="mpd-nav-logout-modal__cancel">' + (mpdMyAccountWidgets?.i18n?.cancel || 'Cancel') + '</button>' +
                '<a href="' + logoutUrl + '" class="mpd-nav-logout-modal__confirm">' + (mpdMyAccountWidgets?.i18n?.logout || 'Logout') + '</a>' +
                '</div>' +
                '</div>' +
                '</div>');

            // Add modal to body
            $('body').append($modal).addClass('mpd-modal-open');

            // Fade in
            setTimeout(function () {
                $modal.addClass('mpd-nav-logout-modal--visible');
            }, 10);

            // Close on backdrop click
            $modal.find('.mpd-nav-logout-modal__backdrop, .mpd-nav-logout-modal__cancel').on('click', function (e) {
                e.preventDefault();
                MPDAccountNav.closeModal($modal);
            });

            // Close on escape
            $(document).one('keydown.mpd-logout', function (e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    MPDAccountNav.closeModal($modal);
                }
            });
        },

        closeModal: function ($modal) {
            $modal.removeClass('mpd-nav-logout-modal--visible');
            $(document).off('keydown.mpd-logout');

            setTimeout(function () {
                $modal.remove();
                $('body').removeClass('mpd-modal-open');
            }, 300);
        }
    };

    /**
     * Initialize all handlers on document ready
     */
    $(document).ready(function () {
        MPDLogout.init();
        MPDAddresses.init();
        MPDOrders.init();
        MPDAccountDetails.init();
        MPDAccountLogin.init();
        MPDAccountNav.init();
    });

    // Also initialize on Elementor frontend init (for editor preview)
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-account-logout.default', function () {
                MPDLogout.init();
            });
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-account-addresses.default', function () {
                MPDAddresses.init();
            });
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-account-orders.default', function () {
                MPDOrders.init();
            });
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-account-details.default', function () {
                MPDAccountDetails.init();
            });
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-account-login.default', function () {
                MPDAccountLogin.init();
            });
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-account-nav.default', function () {
                MPDAccountNav.init();
            });
        }
    });

})(jQuery);
