/**
 * MPD Cross-Sells Widget JavaScript
 *
 * Handles Swiper carousel initialization for cross-sells products.
 *
 * @package Magical_Shop_Builder
 * @since   2.0.0
 */

(function ($) {
    'use strict';

    /**
     * Cross-Sells Carousel Handler
     */
    var MPDCrossSells = {
        /**
         * Initialize cross-sells functionality.
         */
        init: function () {
            this.initCarousels();
        },

        /**
         * Initialize Swiper carousels.
         */
        initCarousels: function () {
            var $carousels = $('.mpd-cross-sells-carousel');

            $carousels.each(function () {
                var $this = $(this);
                var $swiper = $this.find('.mpd-cross-sells-swiper');

                if (!$swiper.length) {
                    return;
                }

                // Get settings from data attribute
                var settings = $this.data('carousel-settings') || {};

                // Default settings
                var swiperConfig = {
                    slidesPerView: parseInt(settings.slides_per_view) || 4,
                    spaceBetween: 20,
                    loop: settings.loop !== false,
                    autoplay: settings.autoplay ? {
                        delay: parseInt(settings.autoplay_speed) || 5000,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true
                    } : false,
                    navigation: settings.navigation !== false ? {
                        nextEl: $this.find('.swiper-button-next')[0],
                        prevEl: $this.find('.swiper-button-prev')[0]
                    } : false,
                    pagination: settings.pagination ? {
                        el: $this.find('.swiper-pagination')[0],
                        clickable: true
                    } : false,
                    breakpoints: {
                        320: {
                            slidesPerView: 1,
                            spaceBetween: 10
                        },
                        480: {
                            slidesPerView: 2,
                            spaceBetween: 15
                        },
                        768: {
                            slidesPerView: Math.min(3, parseInt(settings.slides_per_view) || 3),
                            spaceBetween: 20
                        },
                        1024: {
                            slidesPerView: parseInt(settings.slides_per_view) || 4,
                            spaceBetween: 20
                        }
                    }
                };

                // Initialize Swiper
                new Swiper($swiper[0], swiperConfig);
            });
        },

        /**
         * Destroy and reinitialize carousels.
         */
        refresh: function () {
            var $carousels = $('.mpd-cross-sells-carousel');

            $carousels.each(function () {
                var $swiper = $(this).find('.mpd-cross-sells-swiper');
                
                if ($swiper.length && $swiper[0].swiper) {
                    $swiper[0].swiper.destroy(true, true);
                }
            });

            this.initCarousels();
        }
    };

    /**
     * Initialize on document ready.
     */
    $(document).ready(function () {
        // Wait for Swiper to be available
        if (typeof Swiper !== 'undefined') {
            MPDCrossSells.init();
        } else {
            // Retry after a short delay if Swiper isn't loaded yet
            setTimeout(function () {
                if (typeof Swiper !== 'undefined') {
                    MPDCrossSells.init();
                }
            }, 500);
        }
    });

    /**
     * Re-initialize on Elementor frontend init (for live preview).
     */
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/mpd-cross-sells.default', function () {
                setTimeout(function () {
                    MPDCrossSells.refresh();
                }, 100);
            });
        }
    });

    /**
     * Handle WooCommerce cart updates (refresh cross-sells after cart changes).
     */
    $(document.body).on('wc_fragments_refreshed updated_cart_totals', function () {
        setTimeout(function () {
            MPDCrossSells.refresh();
        }, 200);
    });

})(jQuery);
