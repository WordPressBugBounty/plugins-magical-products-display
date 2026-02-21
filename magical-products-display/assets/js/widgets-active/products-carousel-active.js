(function ($) {
	"use strict";
    
     $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/mgpd_carousel.default", function (scope, $) {
       
            var mgpCarousel = $(scope).find(".mgpc-pcarousel");
            
            // Basic settings
            var mgpCarLoop = mgpCarousel.data('loop');
            let mgpCarLoopSet = mgpCarLoop ? mgpCarLoop : false;
            var mgpCarDirection = mgpCarousel.data('direction') || 'horizontal';
            var mgpCarNumber = mgpCarousel.data('number') || 3;
            var mgpCarMargin = mgpCarousel.data('margin') || 30;
            var mgpCarSpeed = mgpCarousel.data('speed') || 500;
            var mgpCarAutoplay = mgpCarousel.data('autoplay');
            let mgpCarAutoplaySet = mgpCarAutoplay ? mgpCarAutoplay : false;
            var mgpCarAutoDelay = mgpCarousel.data('auto-delay') || 2500;
            var mgpCarGrabCursor = mgpCarousel.data('grab-cursor');
            let mgpCarGrabCursorSet = mgpCarGrabCursor ? mgpCarGrabCursor : false;
            
            // Effect settings
            var mgpCarEffect = mgpCarousel.data('effect') || 'slide';
            var mgpCarCoverflowDepth = mgpCarousel.data('coverflow-depth') || 100;
            var mgpCarCoverflowRotate = mgpCarousel.data('coverflow-rotate') || 50;
            
            // Continuous scroll (marquee) settings
            var mgpCarContinuousScroll = mgpCarousel.data('continuous-scroll') === 'yes';
            var mgpCarScrollDirection = mgpCarousel.data('scroll-direction') || 'rtl';
            var mgpCarScrollSpeed = mgpCarousel.data('scroll-speed') || 30;
            var mgpCarPauseOnHover = mgpCarousel.data('pause-on-hover') !== 'no';

            // Autoplay configuration
            var autoPlayData = false;
            if (mgpCarAutoplaySet == 'yes' && !mgpCarContinuousScroll) {
                autoPlayData = {
                    delay: mgpCarAutoDelay,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                    reverseDirection: mgpCarScrollDirection === 'ltr',
                };
            }
            
            // Continuous scroll (marquee) autoplay configuration
            if (mgpCarContinuousScroll) {
                autoPlayData = {
                    delay: 0,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: mgpCarPauseOnHover,
                    reverseDirection: mgpCarScrollDirection === 'ltr',
                };
                mgpCarLoopSet = true; // Force loop for continuous scroll
                mgpCarSpeed = (101 - mgpCarScrollSpeed) * 100; // Convert speed setting
            }
            
            // Breakpoints configuration
            var breakpointsValue = {};
            if (mgpCarNumber > 1 && mgpCarEffect === 'slide') {
                breakpointsValue = {
                    480: {
                        slidesPerView: Math.min(2, mgpCarNumber),
                        spaceBetween: Math.min(mgpCarMargin, 15),
                    },
                    768: {
                        slidesPerView: Math.min(2, mgpCarNumber),
                        spaceBetween: mgpCarMargin,
                    },
                    991: {
                        slidesPerView: mgpCarNumber,
                        spaceBetween: mgpCarMargin,
                    },
                };
            } else if (mgpCarEffect === 'coverflow') {
                breakpointsValue = {
                    480: {
                        slidesPerView: 2,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    991: {
                        slidesPerView: mgpCarNumber,
                        spaceBetween: mgpCarMargin,
                    },
                };
            } else {
                breakpointsValue = {
                    991: {
                        slidesPerView: mgpCarNumber,
                        spaceBetween: mgpCarMargin,
                    },
                };
            }

            // Swiper configuration object
            var swiperConfig = {
                direction: mgpCarDirection,
                slidesPerView: 1,
                spaceBetween: 10,
                loop: mgpCarLoopSet,
                speed: mgpCarSpeed,
                autoplay: autoPlayData,
                grabCursor: mgpCarGrabCursorSet,
                watchSlidesProgress: true,
                effect: mgpCarEffect,
                pagination: {
                    el: $(scope).find('.swiper-pagination').get(0),
                    clickable: true,
                },
                navigation: {
                    nextEl: $(scope).find('.swiper-button-next').get(0),
                    prevEl: $(scope).find('.swiper-button-prev').get(0),
                },
            };
            
            // Add breakpoints for slide effect or continuous scroll
            if (mgpCarEffect === 'slide' || mgpCarContinuousScroll) {
                swiperConfig.breakpoints = breakpointsValue;
            }
            
            // Effect-specific configurations
            switch (mgpCarEffect) {
                case 'fade':
                    swiperConfig.fadeEffect = {
                        crossFade: true
                    };
                    swiperConfig.slidesPerView = 1;
                    break;
                    
                case 'cube':
                    swiperConfig.cubeEffect = {
                        shadow: true,
                        slideShadows: true,
                        shadowOffset: 20,
                        shadowScale: 0.94,
                    };
                    swiperConfig.slidesPerView = 1;
                    break;
                    
                case 'coverflow':
                    swiperConfig.coverflowEffect = {
                        rotate: mgpCarCoverflowRotate,
                        stretch: 0,
                        depth: mgpCarCoverflowDepth,
                        modifier: 1,
                        slideShadows: true,
                    };
                    swiperConfig.centeredSlides = true;
                    swiperConfig.breakpoints = breakpointsValue;
                    break;
                    
                case 'flip':
                    swiperConfig.flipEffect = {
                        slideShadows: true,
                        limitRotation: true,
                    };
                    swiperConfig.slidesPerView = 1;
                    break;
                    
                case 'cards':
                    swiperConfig.cardsEffect = {
                        slideShadows: true,
                        perSlideOffset: 8,
                        perSlideRotate: 2,
                    };
                    swiperConfig.slidesPerView = 1;
                    swiperConfig.centeredSlides = true;
                    break;
                    
                case 'creative':
                    swiperConfig.creativeEffect = {
                        prev: {
                            shadow: true,
                            translate: [0, 0, -400],
                        },
                        next: {
                            translate: ['100%', 0, 0],
                        },
                    };
                    swiperConfig.slidesPerView = 1;
                    break;
            }
            
            // Continuous scroll specific settings
            if (mgpCarContinuousScroll) {
                swiperConfig.allowTouchMove = false; // Disable touch for smooth marquee
                swiperConfig.freeMode = {
                    enabled: true,
                    momentum: false,
                };
                // Add CSS class for marquee styling
                mgpCarousel.addClass('mgpc-continuous-scroll');
            }

            // Initialize Swiper
            var shopCarouselSwiper = new Swiper(mgpCarousel.get(0), swiperConfig);
            
            // Show slides after initialization
            $(document).ready(function() {
                mgpCarousel.find('.swiper-slide').removeClass('no-load');
            });
            
            // Handle share icon clicks for free version
            $(scope).find('.mgp-share-icon').on('click', function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                var title = $(this).data('title');
                
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        url: url
                    }).catch(console.error);
                } else {
                    // Fallback: copy to clipboard
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(url).then(function() {
                            alert('Link copied to clipboard!');
                        }).catch(function() {
                            prompt('Copy this link:', url);
                        });
                    } else {
                        prompt('Copy this link:', url);
                    }
                }
            });
        });
    });

}(jQuery));