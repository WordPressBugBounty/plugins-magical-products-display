(function ($) {
	"use strict";

	/**
	 * MPD Product Gallery Handler
	 */
	function initMpdGallery($container) {
		var $galleries = $container ? $container.find('.mpd-product-gallery') : $('.mpd-product-gallery');
		
		$galleries.each(function() {
			var $gallery = $(this);
			
			// Skip if already initialized
			if ($gallery.data('mpd-gallery-init')) {
				return;
			}
			$gallery.data('mpd-gallery-init', true);
			
			var $mainImage = $gallery.find('.mpd-gallery-main-image');
			var $slides = $mainImage.find('.mpd-gallery-slide');
			var $thumbs = $gallery.find('.mpd-thumb-item');
			var $lightboxTrigger = $gallery.find('.mpd-gallery-lightbox-trigger');
			
			// Get Pro feature settings from data attributes
			var enableZoom = $gallery.data('enable-zoom') === 'yes';
			var zoomType = $gallery.data('zoom-type') || 'inner';
			var enableLightbox = $gallery.data('enable-lightbox') === 'yes';
			var enableVideoSupport = $gallery.data('video-support') === 'yes';
			var autoplay = $gallery.data('autoplay') === 'yes';
			var autoplaySpeed = parseInt($gallery.data('autoplay-speed')) || 5000;
			
			// Store current slide index
			var currentIndex = 0;
			var slideCount = $slides.length;
			var autoplayInterval = null;

			// Skip if no slides
			if (slideCount === 0) {
				return;
			}

			// Thumbnail click handler
			$thumbs.on('click', function(e) {
				e.preventDefault();
				var $thumb = $(this);
				var index = parseInt($thumb.data('index'));
				
				goToSlide(index);
				
				// Reset autoplay on interaction
				if (autoplay) {
					stopAutoplay();
					startAutoplay();
				}
			});
			
			/**
			 * Check if current slide is a video
			 */
			function isVideoSlide($slide) {
				return $slide.data('type') === 'video';
			}
			
			/**
			 * Update lightbox trigger visibility based on slide type
			 */
			function updateLightboxTrigger($slide) {
				if (isVideoSlide($slide)) {
					$lightboxTrigger.hide();
				} else {
					if (enableLightbox) {
						$lightboxTrigger.show();
					}
				}
			}
			
			/**
			 * Stop any playing videos
			 */
			function stopAllVideos() {
				$slides.each(function() {
					var $slide = $(this);
					if (isVideoSlide($slide) && $slide.hasClass('mpd-video-playing')) {
						$slide.removeClass('mpd-video-playing');
						$slide.find('.mpd-video-embed-container iframe').attr('src', '');
					}
				});
			}
			
			/**
			 * Go to specific slide
			 */
			function goToSlide(index) {
				// Stop any playing videos before switching
				stopAllVideos();
				
				currentIndex = index;
				
				// Update active thumbnail
				$thumbs.removeClass('mpd-thumb-active');
				$thumbs.filter('[data-index="' + index + '"]').addClass('mpd-thumb-active');

				// Update active slide
				$slides.removeClass('mpd-slide-active');
				var $activeSlide = $slides.filter('[data-index="' + index + '"]');
				$activeSlide.addClass('mpd-slide-active');
				
				// Update lightbox trigger visibility
				updateLightboxTrigger($activeSlide);
				
				// Update lightbox trigger URL (only for image slides)
				if (!isVideoSlide($activeSlide)) {
					var newUrl = $activeSlide.find('a').attr('href');
					$lightboxTrigger.attr('href', newUrl);
				}
				
				// Reinitialize zoom on new slide (only for image slides)
				if (enableZoom && !isVideoSlide($activeSlide)) {
					initZoomOnSlide($activeSlide);
				}
			}
			
			/**
			 * Go to next slide
			 */
			function nextSlide() {
				var nextIndex = (currentIndex + 1) % slideCount;
				goToSlide(nextIndex);
			}
			
			/**
			 * Start autoplay
			 */
			function startAutoplay() {
				if (autoplayInterval) {
					clearInterval(autoplayInterval);
				}
				autoplayInterval = setInterval(function() {
					nextSlide();
				}, autoplaySpeed);
			}
			
			/**
			 * Stop autoplay
			 */
			function stopAutoplay() {
				if (autoplayInterval) {
					clearInterval(autoplayInterval);
					autoplayInterval = null;
				}
			}
			
			/**
			 * Initialize zoom on a slide
			 */
			function initZoomOnSlide($slide) {
				var $img = $slide.find('img');
				var $link = $slide.find('a');
				var zoomUrl = $link.attr('href') || $img.attr('data-large_image') || $img.attr('data-src') || $img.attr('src');
				
				// Remove any existing zoom elements
				$slide.find('.mpd-zoom-lens, .mpd-zoom-overlay').remove();
				$slide.off('.mpdZoom');
				
				if (!zoomUrl) return;
				
				// Add zoom class
				$img.addClass('mpd-zoomable');
				$img.attr('data-zoom-image', zoomUrl);
				
				// Preload the zoom image
				var zoomImg = new Image();
				zoomImg.src = zoomUrl;
				
				if (zoomType === 'inner') {
					// Inner zoom - show zoomed background image on hover
					var $zoomOverlay = $('<div class="mpd-zoom-overlay"></div>');
					$zoomOverlay.css({
						'position': 'absolute',
						'top': 0,
						'left': 0,
						'width': '100%',
						'height': '100%',
						'background-repeat': 'no-repeat',
						'background-image': 'url(' + zoomUrl + ')',
						'opacity': 0,
						'pointer-events': 'none',
						'z-index': 10
					});
					$slide.css('position', 'relative').append($zoomOverlay);
					
					$slide.on('mouseenter.mpdZoom', function() {
						$zoomOverlay.css('opacity', 1);
						$img.css('opacity', 0);
					}).on('mousemove.mpdZoom', function(e) {
						var offset = $slide.offset();
						var slideWidth = $slide.width();
						var slideHeight = $slide.height();
						var x = e.pageX - offset.left;
						var y = e.pageY - offset.top;
						
						// Calculate percentage position
						var xPercent = (x / slideWidth) * 100;
						var yPercent = (y / slideHeight) * 100;
						
						// Set background position and size
						$zoomOverlay.css({
							'background-size': (slideWidth * 2) + 'px auto',
							'background-position': xPercent + '% ' + yPercent + '%'
						});
					}).on('mouseleave.mpdZoom', function() {
						$zoomOverlay.css('opacity', 0);
						$img.css('opacity', 1);
					});
				} else if (zoomType === 'lens') {
					// Lens zoom - magnifying lens follows cursor
					var $lens = $('<div class="mpd-zoom-lens"></div>');
					$slide.css('position', 'relative').append($lens);
					
					$slide.on('mouseenter.mpdZoom', function() {
						$lens.show();
					}).on('mousemove.mpdZoom', function(e) {
						var offset = $slide.offset();
						var imgWidth = $slide.width();
						var imgHeight = $slide.height();
						var lensSize = 150;
						var x = e.pageX - offset.left;
						var y = e.pageY - offset.top;
						
						// Calculate lens position (centered on cursor)
						var lensX = x - (lensSize / 2);
						var lensY = y - (lensSize / 2);
						
						// Keep lens within bounds
						lensX = Math.max(0, Math.min(lensX, imgWidth - lensSize));
						lensY = Math.max(0, Math.min(lensY, imgHeight - lensSize));
						
						// Calculate background position
						var bgX = (x / imgWidth) * 100;
						var bgY = (y / imgHeight) * 100;
						
						$lens.css({
							'display': 'block',
							'left': lensX + 'px',
							'top': lensY + 'px',
							'background-image': 'url(' + zoomUrl + ')',
							'background-position': bgX + '% ' + bgY + '%',
							'background-size': (imgWidth * 2) + 'px auto'
						});
					}).on('mouseleave.mpdZoom', function() {
						$lens.hide();
					});
				} else if (zoomType === 'window') {
					// Window zoom - separate zoom window appears beside image
					var $zoomWindow = $gallery.find('.mpd-zoom-window');
					if (!$zoomWindow.length) {
						$zoomWindow = $('<div class="mpd-zoom-window"></div>');
						$gallery.find('.mpd-gallery-main-image').append($zoomWindow);
					}
					
					$slide.on('mouseenter.mpdZoom', function() {
						$zoomWindow.show();
					}).on('mousemove.mpdZoom', function(e) {
						var offset = $slide.offset();
						var imgWidth = $slide.width();
						var imgHeight = $slide.height();
						var x = e.pageX - offset.left;
						var y = e.pageY - offset.top;
						var xPercent = (x / imgWidth) * 100;
						var yPercent = (y / imgHeight) * 100;
						
						$zoomWindow.css({
							'display': 'block',
							'background-image': 'url(' + zoomUrl + ')',
							'background-position': xPercent + '% ' + yPercent + '%',
							'background-size': '200%'
						});
					}).on('mouseleave.mpdZoom', function() {
						$zoomWindow.hide();
					});
				}
			}

			// Initialize zoom on first/active slide
			if (enableZoom) {
				var $activeSlide = $slides.filter('.mpd-slide-active');
				if ($activeSlide.length && !isVideoSlide($activeSlide)) {
					initZoomOnSlide($activeSlide);
				}
			}
			
			// Update lightbox trigger visibility for initial slide
			var $initialSlide = $slides.filter('.mpd-slide-active');
			if ($initialSlide.length) {
				updateLightboxTrigger($initialSlide);
			}
			
			// Video click handler - play video inline or in lightbox
			if (enableVideoSupport) {
				$gallery.on('click keypress', '.mpd-video-trigger', function(e) {
					// Handle click and Enter/Space key
					if (e.type === 'keypress' && e.which !== 13 && e.which !== 32) {
						return;
					}
					
					e.preventDefault();
					e.stopPropagation();
					
					var $trigger = $(this);
					var $videoSlide = $trigger.closest('.mpd-gallery-video-slide');
					var action = $trigger.data('action');
					var embedUrl = $videoSlide.data('embed-url');
					
					if (action === 'lightbox' && enableLightbox) {
						// Open video in lightbox
						openMpdVideoLightbox(embedUrl);
					} else {
						// Play video inline
						$videoSlide.addClass('mpd-video-playing');
						$videoSlide.find('.mpd-video-embed-container iframe').attr('src', embedUrl);
						
						// Stop autoplay when video is playing
						if (autoplay) {
							stopAutoplay();
						}
					}
				});
			}
			
			// Initialize lightbox
			if (enableLightbox) {
				// Lightbox trigger click
				$lightboxTrigger.on('click', function(e) {
					e.preventDefault();
					var $activeSlide = $slides.filter('.mpd-slide-active');
					// Don't open image lightbox for video slides
					if (!isVideoSlide($activeSlide)) {
						openMpdLightbox($gallery, $slides, currentIndex);
					}
				});
				
				// Also allow clicking on main image to open lightbox (only for image slides)
				$slides.find('a').on('click', function(e) {
					e.preventDefault();
					var $slide = $(this).closest('.mpd-gallery-slide');
					// Skip if this is a video slide
					if (isVideoSlide($slide)) {
						return;
					}
					var slideIndex = parseInt($slide.data('index'));
					openMpdLightbox($gallery, $slides, slideIndex);
				});
			} else {
				// Disable lightbox link clicks
				$slides.find('a').on('click', function(e) {
					e.preventDefault();
				});
				$lightboxTrigger.on('click', function(e) {
					e.preventDefault();
				});
			}
			
			// Start autoplay if enabled
			if (autoplay && slideCount > 1) {
				startAutoplay();
				
				// Pause on hover
				$gallery.on('mouseenter', function() {
					stopAutoplay();
				}).on('mouseleave', function() {
					startAutoplay();
				});
			}
		});
	}

	/**
	 * Enhanced Lightbox for Gallery
	 */
	function openMpdLightbox($gallery, $slides, startIndex) {
		startIndex = startIndex || 0;
		
		// Build array of images (skip video slides)
		var images = [];
		var slideIndexMap = {}; // Map gallery index to image array index
		var imageIndex = 0;
		
		$slides.each(function() {
			var $slide = $(this);
			var slideIdx = parseInt($slide.data('index'));
			
			// Skip video slides in image lightbox
			if ($slide.data('type') === 'video') {
				return;
			}
			
			var url = $slide.find('a').attr('href') || $slide.find('img').attr('src');
			images.push(url);
			slideIndexMap[slideIdx] = imageIndex;
			imageIndex++;
		});
		
		// If no images, don't open lightbox
		if (images.length === 0) {
			return;
		}
		
		// Adjust startIndex to match the images array
		var adjustedStartIndex = slideIndexMap[startIndex] !== undefined ? slideIndexMap[startIndex] : 0;
		
		// Check if lightbox already exists
		var $lightbox = $('#mpd-lightbox-overlay');
		if ($lightbox.length === 0) {
			$lightbox = $('<div id="mpd-lightbox-overlay">' +
				'<div class="mpd-lb-content">' +
					'<img src="" class="mpd-lb-image">' +
					'<button class="mpd-lb-prev" aria-label="Previous">&lt;</button>' +
					'<button class="mpd-lb-next" aria-label="Next">&gt;</button>' +
					'<button class="mpd-lb-close" aria-label="Close">&times;</button>' +
					'<div class="mpd-lb-counter"><span class="mpd-lb-current">1</span> / <span class="mpd-lb-total">1</span></div>' +
				'</div>' +
			'</div>');
			$('body').append($lightbox);
			
			// Add lightbox styles
			var lbStyles = '<style id="mpd-lightbox-styles">' +
				'#mpd-lightbox-overlay{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.95);z-index:999999;display:none;align-items:center;justify-content:center;}' +
				'.mpd-lb-content{position:relative;max-width:90%;max-height:90%;display:flex;align-items:center;justify-content:center;}' +
				'.mpd-lb-image{max-width:100%;max-height:90vh;object-fit:contain;}' +
				'.mpd-lb-close{position:fixed;top:20px;right:30px;color:#fff;font-size:40px;background:none;border:none;cursor:pointer;z-index:10;}' +
				'.mpd-lb-prev,.mpd-lb-next{position:fixed;top:50%;transform:translateY(-50%);color:#fff;font-size:30px;background:rgba(0,0,0,0.5);border:none;cursor:pointer;padding:20px 15px;z-index:10;}' +
				'.mpd-lb-prev{left:20px;}' +
				'.mpd-lb-next{right:20px;}' +
				'.mpd-lb-prev:hover,.mpd-lb-next:hover{background:rgba(255,255,255,0.2);}' +
				'.mpd-lb-counter{position:fixed;bottom:20px;left:50%;transform:translateX(-50%);color:#fff;font-size:14px;}' +
			'</style>';
			if (!$('#mpd-lightbox-styles').length) {
				$('head').append(lbStyles);
			}
		}
		
		var currentLbIndex = adjustedStartIndex;
		
		function showImage(index) {
			currentLbIndex = index;
			$lightbox.find('.mpd-lb-image').attr('src', images[index]);
			$lightbox.find('.mpd-lb-current').text(index + 1);
			$lightbox.find('.mpd-lb-total').text(images.length);
			
			// Hide prev/next if only one image
			if (images.length <= 1) {
				$lightbox.find('.mpd-lb-prev, .mpd-lb-next, .mpd-lb-counter').hide();
			} else {
				$lightbox.find('.mpd-lb-prev, .mpd-lb-next, .mpd-lb-counter').show();
			}
		}
		
		// Event handlers
		$lightbox.off('click.mpdLb').on('click.mpdLb', '.mpd-lb-close', function() {
			$lightbox.fadeOut(200);
		}).on('click.mpdLb', '.mpd-lb-prev', function(e) {
			e.stopPropagation();
			var prevIndex = (currentLbIndex - 1 + images.length) % images.length;
			showImage(prevIndex);
		}).on('click.mpdLb', '.mpd-lb-next', function(e) {
			e.stopPropagation();
			var nextIndex = (currentLbIndex + 1) % images.length;
			showImage(nextIndex);
		}).on('click.mpdLb', function(e) {
			if ($(e.target).is('#mpd-lightbox-overlay')) {
				$lightbox.fadeOut(200);
			}
		});
		
		// Keyboard navigation
		$(document).off('keydown.mpdLb').on('keydown.mpdLb', function(e) {
			if (!$lightbox.is(':visible')) return;
			
			if (e.key === 'Escape') {
				$lightbox.fadeOut(200);
			} else if (e.key === 'ArrowLeft') {
				var prevIndex = (currentLbIndex - 1 + images.length) % images.length;
				showImage(prevIndex);
			} else if (e.key === 'ArrowRight') {
				var nextIndex = (currentLbIndex + 1) % images.length;
				showImage(nextIndex);
			}
		});
		
		showImage(adjustedStartIndex);
		$lightbox.css('display', 'flex').hide().fadeIn(200);
	}

	/**
	 * Video Lightbox
	 */
	function openMpdVideoLightbox(embedUrl) {
		// Check if video lightbox already exists
		var $videoLightbox = $('#mpd-video-lightbox-overlay');
		if ($videoLightbox.length === 0) {
			$videoLightbox = $('<div id="mpd-video-lightbox-overlay">' +
				'<div class="mpd-vlb-content">' +
					'<div class="mpd-vlb-video-wrapper">' +
						'<iframe src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' +
					'</div>' +
					'<button class="mpd-vlb-close" aria-label="Close">&times;</button>' +
				'</div>' +
			'</div>');
			$('body').append($videoLightbox);
			
			// Add video lightbox styles
			var vlbStyles = '<style id="mpd-video-lightbox-styles">' +
				'#mpd-video-lightbox-overlay{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.95);z-index:999999;display:none;align-items:center;justify-content:center;}' +
				'.mpd-vlb-content{position:relative;width:90%;max-width:1000px;display:flex;align-items:center;justify-content:center;}' +
				'.mpd-vlb-video-wrapper{position:relative;width:100%;padding-bottom:56.25%;background:#000;}' +
				'.mpd-vlb-video-wrapper iframe{position:absolute;top:0;left:0;width:100%;height:100%;}' +
				'.mpd-vlb-close{position:fixed;top:20px;right:30px;color:#fff;font-size:40px;background:none;border:none;cursor:pointer;z-index:10;transition:transform 0.2s;}' +
				'.mpd-vlb-close:hover{transform:scale(1.1);}' +
			'</style>';
			if (!$('#mpd-video-lightbox-styles').length) {
				$('head').append(vlbStyles);
			}
			
			// Close button handler
			$videoLightbox.on('click', '.mpd-vlb-close', function() {
				closeMpdVideoLightbox();
			});
			
			// Click outside to close
			$videoLightbox.on('click', function(e) {
				if ($(e.target).is('#mpd-video-lightbox-overlay')) {
					closeMpdVideoLightbox();
				}
			});
			
			// Keyboard close
			$(document).on('keydown.mpdVlb', function(e) {
				if (!$videoLightbox.is(':visible')) return;
				if (e.key === 'Escape') {
					closeMpdVideoLightbox();
				}
			});
		}
		
		function closeMpdVideoLightbox() {
			$videoLightbox.fadeOut(200, function() {
				$videoLightbox.find('iframe').attr('src', '');
			});
		}
		
		// Set video URL and show lightbox
		$videoLightbox.find('iframe').attr('src', embedUrl);
		$videoLightbox.css('display', 'flex').hide().fadeIn(200);
	}

	// Initialize on document ready
	$(document).ready(function() {
		initMpdGallery();
		initMpdCollapsible();
	});

	/**
	 * MPD Collapsible Attributes Handler
	 */
	function initMpdCollapsible() {
		$('.mpd-attributes-collapsible').each(function() {
			var $wrapper = $(this);
			var $toggle = $wrapper.find('.mpd-attributes-toggle');
			var $content = $wrapper.find('.mpd-attributes-content');
			var $icon = $toggle.find('.mpd-toggle-icon');
			
			// Skip if already initialized
			if ($wrapper.data('mpd-collapsible-init')) {
				return;
			}
			$wrapper.data('mpd-collapsible-init', true);
			
			// Remove any existing click handlers and add new one
			$toggle.off('click.mpdCollapsible').on('click.mpdCollapsible', function(e) {
				e.preventDefault();
				e.stopPropagation();
				
				var isExpanded = $wrapper.hasClass('mpd-attributes-expanded');
				
				if (isExpanded) {
					// Collapse
					$content.stop(true, true).slideUp(300);
					$toggle.attr('aria-expanded', 'false');
					$wrapper.removeClass('mpd-attributes-expanded').addClass('mpd-attributes-collapsed');
				} else {
					// Expand
					$content.stop(true, true).slideDown(300);
					$toggle.attr('aria-expanded', 'true');
					$wrapper.removeClass('mpd-attributes-collapsed').addClass('mpd-attributes-expanded');
				}
			});
		});
	}

	// Re-initialize on Elementor frontend init
	$(window).on("elementor/frontend/init", function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/global', function($scope) {
			$('body .bsk-tabs').removeClass('no-load');
		});

		// Re-init gallery when widget is loaded in editor
		elementorFrontend.hooks.addAction('frontend/element_ready/mpd-product-gallery.default', function($scope) {
			// Reset init flag and reinitialize
			$scope.find('.mpd-product-gallery').data('mpd-gallery-init', false);
			initMpdGallery($scope);
		});

		// Re-init collapsible when widget is loaded in editor
		elementorFrontend.hooks.addAction('frontend/element_ready/mpd-product-attributes.default', function($scope) {
			// Reset init flag and reinitialize
			$scope.find('.mpd-attributes-collapsible').data('mpd-collapsible-init', false);
			initMpdCollapsible();
		});
	});

	// Also watch for Elementor editor panel changes
	$(document).on('click', '.mpd-thumb-item', function(e) {
		e.preventDefault();
		var $thumb = $(this);
		var $gallery = $thumb.closest('.mpd-product-gallery');
		var $mainImage = $gallery.find('.mpd-gallery-main-image');
		var $slides = $mainImage.find('.mpd-gallery-slide');
		var $thumbs = $gallery.find('.mpd-thumb-item');
		var index = $thumb.data('index');

		// Update active thumbnail
		$thumbs.removeClass('mpd-thumb-active');
		$thumb.addClass('mpd-thumb-active');

		// Update active slide
		$slides.removeClass('mpd-slide-active');
		$slides.filter('[data-index="' + index + '"]').addClass('mpd-slide-active');
	});
	
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl, {
    html: true
  })
})

	
   


}(jQuery));	


