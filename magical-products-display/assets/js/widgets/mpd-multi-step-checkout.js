/**
 * Multi-Step Checkout Widget JavaScript
 *
 * @package Magical_Shop_Builder
 * @since 2.5.0
 */

(function ($) {
	'use strict';

	/**
	 * Debug mode flag - set to false for production
	 */
	const MPD_MSC_DEBUG = false;

	/**
	 * Debug logger - only logs when debug mode is enabled
	 */
	const debugLog = (...args) => {
		if (MPD_MSC_DEBUG && typeof console !== 'undefined') {
			console.log('[MPD-MSC]', ...args);
		}
	};

	const debugWarn = (...args) => {
		if (MPD_MSC_DEBUG && typeof console !== 'undefined') {
			console.warn('[MPD-MSC]', ...args);
		}
	};

	/**
	 * Multi-Step Checkout Controller
	 */
	class MPDMultiStepCheckout {
		constructor(element) {
			this.$el = $(element);
			
			// Safety check
			if (!this.$el.length) {
				debugWarn('Element not found');
				return;
			}

			// Check if in Elementor editor mode
			this.isEditorMode = this.$el.hasClass('is-editor-mode') || $('body').hasClass('elementor-editor-active');
			
			// In editor mode, don't initialize step navigation
			if (this.isEditorMode) {
				debugLog('Editor mode - skipping initialization');
				return;
			}
			
			this.settings = this.getSettings();
			this.currentStep = 1;
			this.totalSteps = this.$el.find('.mpd-msc-step-panel').length;
			this.isAnimating = false;
			this.validationErrors = [];
			this.quantityUpdateTimer = null;
			this.pendingQuantityUpdates = new Map();

			// Safety check for steps
			if (this.totalSteps === 0) {
				debugWarn('No step panels found');
				return;
			}

			this.cacheElements();
			this.bindEvents();
			this.init();
			
			debugLog('Initialized:', {
				totalSteps: this.totalSteps,
				settings: this.settings
			});
		}

		/**
		 * Destroy instance and clean up
		 */
		destroy() {
			// Remove event handlers
			this.$prevBtn.off('.mpdMsc');
			this.$nextBtn.off('.mpdMsc');
			this.$steps.off('click');
			this.$dots.off('click');
			this.$form.off('submit');
			this.$el.off('.mpdMsc');
			$(document).off('.mpdMsc');
			
			// Clear any pending timers
			if (this.quantityUpdateTimer) {
				clearTimeout(this.quantityUpdateTimer);
			}
			
			// Remove data flag
			this.$el.removeData('mpd-msc-initialized');
			
			debugLog('Instance destroyed');
		}

		/**
		 * Get settings from data attributes
		 */
		getSettings() {
			return {
				stepLayout: this.$el.data('step-layout') || '3-steps',
				validationMode: this.$el.data('validation-mode') || 'both',
				scrollToError: this.$el.data('scroll-to-error') === 'yes',
				transition: this.$el.data('transition') || 'slide',
				transitionDuration: parseInt(this.$el.data('transition-duration'), 10) || 300,
				keyboardNav: this.$el.data('keyboard-nav') === 'yes',
				clickableSteps: this.$el.data('clickable-steps') === 'yes',
				checkoutNonce: this.$el.data('checkout-nonce') || ''
			};
		}

		/**
		 * Cache DOM elements
		 */
		cacheElements() {
			this.$form = this.$el.find('form.checkout, form.woocommerce-checkout');
			this.$panels = this.$el.find('.mpd-msc-step-panel');
			this.$steps = this.$el.find('.mpd-msc-step');
			this.$dots = this.$el.find('.mpd-msc-dot');
			this.$progressBar = this.$el.find('.mpd-msc-progress-bar-fill');
			this.$progressText = this.$el.find('.mpd-msc-progress-current');
			this.$prevBtn = this.$el.find('.mpd-msc-nav-prev');
			this.$nextBtn = this.$el.find('.mpd-msc-nav-next');
			this.$submitBtn = this.$el.find('.mpd-msc-nav-submit');
			this.$connectors = this.$el.find('.mpd-msc-step-connector');
			this.$shipDifferent = this.$el.find('#ship-to-different-address-checkbox');
			this.$shippingAddress = this.$el.find('.shipping_address');
			this.$navigation = this.$el.find('.mpd-msc-navigation');
			
			debugLog('Cached elements:', {
				form: this.$form.length,
				panels: this.$panels.length,
				nextBtn: this.$nextBtn.length,
				prevBtn: this.$prevBtn.length
			});
		}

		/**
		 * Bind event handlers
		 */
		bindEvents() {
			var self = this;
			
			// Navigation buttons - using function syntax for better compatibility
			this.$prevBtn.on('click.mpdMsc', function(e) {
				e.preventDefault();
				e.stopPropagation();
				debugLog('Prev button clicked');
				self.prevStep();
			});
			this.$nextBtn.on('click.mpdMsc', function(e) {
				e.preventDefault();
				e.stopPropagation();
				debugLog('Next button clicked');
				self.nextStep();
			});

			// Clickable steps
			if (this.settings.clickableSteps) {
				this.$steps.on('click', (e) => this.onStepClick(e));
				this.$dots.on('click', (e) => this.onDotClick(e));
			}

			// Form submission
			this.$form.on('submit', (e) => this.onFormSubmit(e));

			// Ship to different address toggle
			this.$shipDifferent.on('change', () => this.toggleShippingAddress());

			// Login/Coupon toggles - use namespaced events and stop propagation
			this.$el.find('.showlogin').off('click').on('click.mpdMsc', (e) => this.toggleLoginForm(e));
			this.$el.find('.showcoupon').off('click').on('click.mpdMsc', (e) => this.toggleCouponForm(e));
			this.$el.find('.mpd-msc-apply-coupon').off('click').on('click.mpdMsc', () => this.applyCoupon());

			// Payment method selection
			this.$el.find('input[name="payment_method"]').on('change', () => this.togglePaymentBox());

			// Quantity controls
			this.$el.on('click', '.mpd-msc-qty-minus', (e) => this.updateQuantity(e, 'decrease'));
			this.$el.on('click', '.mpd-msc-qty-plus', (e) => this.updateQuantity(e, 'increase'));

			// Real-time validation
			if (this.settings.validationMode === 'real-time' || this.settings.validationMode === 'both') {
				this.$form.find('input, select, textarea').on('blur', (e) => this.validateField(e.target));
			}

			// Keyboard navigation
			if (this.settings.keyboardNav) {
				$(document).on('keydown', (e) => this.handleKeyboard(e));
			}

			// WooCommerce AJAX events
			$(document.body).on('checkout_error', () => this.handleCheckoutError());
			$(document.body).on('update_checkout', () => this.onUpdateCheckout());
			$(document.body).on('updated_checkout', () => this.onUpdatedCheckout());
		}

		/**
		 * Initialize
		 */
		init() {
			// Ensure first panel is visible
			this.$panels.removeClass('is-active');
			this.$panels.first().addClass('is-active');
			
			// Ensure first step indicator is active
			this.$steps.removeClass('is-active is-completed');
			this.$steps.first().addClass('is-active');
			
			this.updateProgress();
			this.updateNavButtons();
			this.toggleShippingAddress();
			this.togglePaymentBox();

			// Mark steps as clickable
			if (this.settings.clickableSteps) {
				this.$steps.addClass('is-clickable');
				this.$dots.addClass('is-clickable');
			}
			
			debugLog('Init complete, current step:', this.currentStep);
		}

		/**
		 * Go to next step
		 */
		async nextStep() {
			debugLog('nextStep called, current:', this.currentStep, 'total:', this.totalSteps, 'animating:', this.isAnimating);
			
			if (this.isAnimating) {
				debugLog('Animation in progress, ignoring');
				return;
			}
			
			if (this.currentStep >= this.totalSteps) {
				debugLog('Already at last step');
				return;
			}

			// Show loading state
			this.showLoading();

			// Validate current step
			if (this.settings.validationMode === 'on-next' || this.settings.validationMode === 'both') {
				const isValid = await this.validateCurrentStep();
				if (!isValid) {
					this.hideLoading();
					debugLog('Validation failed');
					return;
				}
			}

			this.goToStep(this.currentStep + 1, 'next');
			this.hideLoading();
		}

		/**
		 * Go to previous step
		 */
		prevStep() {
			debugLog('prevStep called, current:', this.currentStep);
			
			if (this.isAnimating || this.currentStep <= 1) {
				return;
			}

			this.goToStep(this.currentStep - 1, 'prev');
		}

		/**
		 * Show loading state on button
		 */
		showLoading() {
			this.$nextBtn.addClass('is-loading').prop('disabled', true);
			
			// Add spinner if not exists
			if (!this.$nextBtn.find('.mpd-msc-spinner').length) {
				this.$nextBtn.prepend('<span class="mpd-msc-spinner"><svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-dasharray="32" stroke-dashoffset="12"><animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="0.8s" repeatCount="indefinite"/></circle></svg></span>');
			}
		}

		/**
		 * Hide loading state
		 */
		hideLoading() {
			this.$nextBtn.removeClass('is-loading').prop('disabled', false);
			this.$nextBtn.find('.mpd-msc-spinner').remove();
		}

		/**
		 * Go to specific step
		 */
		goToStep(stepNumber, direction = 'next') {
			debugLog('goToStep called:', stepNumber, direction);
			
			if (this.isAnimating || stepNumber === this.currentStep) {
				debugLog('Skipping - animating or same step');
				return;
			}

			if (stepNumber < 1 || stepNumber > this.totalSteps) {
				debugLog('Skipping - out of range');
				return;
			}

			this.isAnimating = true;
			const $currentPanel = this.$panels.eq(this.currentStep - 1);
			const $targetPanel = this.$panels.eq(stepNumber - 1);
			
			debugLog('Transitioning from panel', this.currentStep - 1, 'to', stepNumber - 1);

			// Apply transition
			if (this.settings.transition === 'slide') {
				this.slideTransition($currentPanel, $targetPanel, direction);
			} else if (this.settings.transition === 'fade') {
				this.fadeTransition($currentPanel, $targetPanel);
			} else {
				this.noTransition($currentPanel, $targetPanel);
			}

			// Update current step
			const prevStep = this.currentStep;
			this.currentStep = stepNumber;

			// Mark previous steps as completed
			this.updateStepStates(prevStep);

			// Update UI after transition
			setTimeout(() => {
				this.updateProgress();
				this.updateNavButtons();
				this.isAnimating = false;

				// Update ARIA attributes on panels
				this.$panels.attr('hidden', true);
				$targetPanel.removeAttr('hidden');

				// Move focus to new step title for screen readers
				const $stepTitle = $targetPanel.find('.mpd-msc-step-title');
				if ($stepTitle.length) {
					$stepTitle.focus();
				}

				// Announce step change for screen readers
				this.announceToScreenReader(
					this.getStepAnnouncement(stepNumber, $targetPanel.find('.mpd-msc-step-title').text())
				);

				// Scroll to top of form
				this.scrollToElement(this.$el);

				// Trigger WooCommerce update
				$(document.body).trigger('update_checkout');
			}, this.settings.transitionDuration);
		}

		/**
		 * Announce message to screen readers via ARIA live region
		 */
		announceToScreenReader(message) {
			let $announcer = $('#mpd-msc-announcer');
			if (!$announcer.length) {
				$announcer = $('<div id="mpd-msc-announcer" aria-live="polite" aria-atomic="true" class="screen-reader-text" style="position:absolute;left:-9999px;"></div>');
				$('body').append($announcer);
			}
			// Clear and set message to trigger announcement
			$announcer.text('');
			setTimeout(() => $announcer.text(message), 100);
		}

		/**
		 * Get announcement text for step change
		 */
		getStepAnnouncement(stepNumber, stepTitle) {
			return `Step ${stepNumber} of ${this.totalSteps}: ${stepTitle}`;
		}

		/**
		 * Slide transition
		 */
		slideTransition($current, $target, direction) {
			const duration = this.settings.transitionDuration;
			const slideClass = direction === 'next' ? 'slide-out-left' : 'slide-out-right';

			$current.addClass(slideClass);

			setTimeout(() => {
				$current.removeClass('is-active ' + slideClass);
				$target.addClass('is-active');
			}, duration);
		}

		/**
		 * Fade transition
		 */
		fadeTransition($current, $target) {
			const duration = this.settings.transitionDuration;

			$current.css('opacity', 0);

			setTimeout(() => {
				$current.removeClass('is-active').css('opacity', '');
				$target.addClass('is-active');
			}, duration);
		}

		/**
		 * No transition
		 */
		noTransition($current, $target) {
			$current.removeClass('is-active');
			$target.addClass('is-active');
		}

		/**
		 * Update step states (completed, active)
		 */
		updateStepStates(prevStep) {
			// Mark all previous steps as completed
			this.$steps.each((index, step) => {
				const $step = $(step);
				const stepNum = index + 1;

				$step.removeClass('is-active is-completed');

				if (stepNum < this.currentStep) {
					$step.addClass('is-completed');
				} else if (stepNum === this.currentStep) {
					$step.addClass('is-active');
				}
			});

			// Update dots
			this.$dots.each((index, dot) => {
				const $dot = $(dot);
				const stepNum = index + 1;

				$dot.removeClass('is-active is-completed');

				if (stepNum < this.currentStep) {
					$dot.addClass('is-completed');
				} else if (stepNum === this.currentStep) {
					$dot.addClass('is-active');
				}
			});

			// Update connectors
			this.updateConnectors();
		}

		/**
		 * Update connector lines
		 */
		updateConnectors() {
			this.$connectors.each((index, connector) => {
				const $connector = $(connector);
				const afterStep = index + 1;

				if (afterStep < this.currentStep) {
					$connector.addClass('is-completed');
				} else {
					$connector.removeClass('is-completed');
				}
			});
		}

		/**
		 * Update progress indicator
		 */
		updateProgress() {
			// Update progress bar
			const progress = (this.currentStep / this.totalSteps) * 100;
			this.$progressBar.css('width', progress + '%');

			// Update progress text
			this.$progressText.text(this.currentStep);
		}

		/**
		 * Update navigation buttons visibility
		 */
		updateNavButtons() {
			// Previous button
			if (this.currentStep <= 1) {
				this.$prevBtn.hide();
			} else {
				this.$prevBtn.show();
			}

			// Next/Submit buttons
			if (this.currentStep >= this.totalSteps) {
				this.$nextBtn.hide();
				this.$submitBtn.show();
			} else {
				this.$nextBtn.show();
				this.$submitBtn.hide();
			}
		}

		/**
		 * Handle step click
		 */
		onStepClick(e) {
			const $step = $(e.currentTarget);
			const stepNum = parseInt($step.data('step'), 10);

			// Can only go back to completed steps
			if ($step.hasClass('is-completed')) {
				this.goToStep(stepNum, 'prev');
			}
		}

		/**
		 * Handle dot click
		 */
		onDotClick(e) {
			const $dot = $(e.currentTarget);
			const stepNum = this.$dots.index($dot) + 1;

			// Can only go back to completed steps
			if ($dot.hasClass('is-completed')) {
				this.goToStep(stepNum, 'prev');
			}
		}

		/**
		 * Validate current step
		 */
		async validateCurrentStep() {
			const $currentPanel = this.$panels.eq(this.currentStep - 1);
			const self = this;
			
			// Find required fields in current panel
			const $requiredRows = $currentPanel.find('.form-row.validate-required');
			
			this.validationErrors = [];

			$requiredRows.each(function() {
				const $row = $(this);
				const $field = $row.find('input, select, textarea').first();
				
				if ($field.length && !$field.is(':hidden') && !self.validateField($field[0])) {
					self.validationErrors.push($field[0]);
				}
			});

			// Also check fields with required attribute
			$currentPanel.find('input[required], select[required], textarea[required]').each(function() {
				if (!$(this).is(':hidden') && !self.validateField(this) && self.validationErrors.indexOf(this) === -1) {
					self.validationErrors.push(this);
				}
			});

			if (this.validationErrors.length > 0) {
				// Show error message
				this.showError('Please fill in all required fields.');

				// Scroll to first error
				if (this.settings.scrollToError && this.validationErrors[0]) {
					this.scrollToElement($(this.validationErrors[0]).closest('.form-row'));
				}

				return false;
			}

			// Run AJAX validation if on billing/shipping step
			if (this.currentStep === 1 || (this.currentStep === 2 && this.settings.stepLayout !== '2-steps')) {
				return await this.validateFieldsAjax($currentPanel);
			}

			return true;
		}

		/**
		 * Validate single field
		 */
		validateField(field) {
			const $field = $(field);
			const $row = $field.closest('.form-row');
			const value = $field.val();
			const isRequired = $row.hasClass('validate-required') || $field.prop('required');

			// Remove existing validation classes
			$row.removeClass('woocommerce-invalid woocommerce-validated');

			// Required check
			if (isRequired && (!value || value.trim() === '')) {
				$row.addClass('woocommerce-invalid');
				return false;
			}

			// Email validation
			if ($field.attr('type') === 'email' && value) {
				const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				if (!emailRegex.test(value)) {
					$row.addClass('woocommerce-invalid');
					return false;
				}
			}

			// Phone validation (basic)
			if ($field.attr('type') === 'tel' && value) {
				const phoneRegex = /^[\d\s\-\+\(\)]+$/;
				if (!phoneRegex.test(value)) {
					$row.addClass('woocommerce-invalid');
					return false;
				}
			}

			$row.addClass('woocommerce-validated');
			return true;
		}

		/**
		 * AJAX field validation
		 */
		async validateFieldsAjax($panel) {
			// This would integrate with WooCommerce's AJAX validation
			// For now, return true if client-side validation passed
			return true;
		}

		/**
		 * Handle form submission
		 */
		onFormSubmit(e) {
			// Let WooCommerce handle the actual submission
			// Just ensure we're on the last step
			if (this.currentStep < this.totalSteps) {
				e.preventDefault();
				this.nextStep();
				return false;
			}

			// Show loading state
			this.$submitBtn.addClass('is-loading');
		}

		/**
		 * Handle checkout error from WooCommerce
		 */
		handleCheckoutError() {
			this.$submitBtn.removeClass('is-loading');
			
			// Check which step has errors and go back
			const $errorNotice = this.$el.find('.woocommerce-error');
			if ($errorNotice.length) {
				// Try to determine which step has the error
				const errorText = $errorNotice.text().toLowerCase();
				
				if (errorText.includes('billing') || errorText.includes('email') || errorText.includes('phone')) {
					this.goToStep(1, 'prev');
				} else if (errorText.includes('shipping') || errorText.includes('address')) {
					const shippingStep = this.settings.stepLayout === '2-steps' ? 1 : 2;
					this.goToStep(shippingStep, 'prev');
				}
			}
		}

		/**
		 * On update checkout (before AJAX)
		 */
		onUpdateCheckout() {
			// Preserve coupon form state before WooCommerce replaces content
			const $couponForm = this.$el.find('.mpd-msc-coupon-form');
			if ($couponForm.length) {
				this.couponFormVisible = $couponForm.is(':visible');
			}
		}

		/**
		 * On updated checkout (after AJAX)
		 */
		onUpdatedCheckout() {
			// Refresh payment methods visibility
			this.togglePaymentBox();
			
			// Restore coupon form state after WooCommerce updates
			const $couponForm = this.$el.find('.mpd-msc-coupon-form');
			if ($couponForm.length && this.couponFormVisible) {
				$couponForm.show();
			}
			
			// Re-bind coupon events (in case WooCommerce replaced elements)
			this.$el.find('.showcoupon').off('click').on('click.mpdMsc', (e) => this.toggleCouponForm(e));
			this.$el.find('.mpd-msc-apply-coupon').off('click').on('click.mpdMsc', () => this.applyCoupon());
		}

		/**
		 * Toggle shipping address visibility
		 */
		toggleShippingAddress() {
			if (this.$shipDifferent.is(':checked')) {
				this.$shippingAddress.slideDown(300);
			} else {
				this.$shippingAddress.slideUp(300);
			}
		}

		/**
		 * Toggle login form
		 */
		toggleLoginForm(e) {
			e.preventDefault();
			const $loginForm = this.$el.find('.mpd-msc-login-form');
			$loginForm.slideToggle(300);
		}

		/**
		 * Toggle coupon form
		 */
		toggleCouponForm(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			const $couponForm = this.$el.find('.mpd-msc-coupon-form');
			$couponForm.slideToggle(300);
			return false;
		}

		/**
		 * Apply coupon via AJAX
		 */
		applyCoupon() {
			const $couponCode = this.$el.find('#coupon_code');
			const code = $couponCode.val();

			if (!code) {
				return;
			}

			const $button = this.$el.find('.mpd-msc-apply-coupon');
			const $couponForm = this.$el.find('.mpd-msc-coupon-form');
			const originalButtonText = $button.text().trim();
			
			// Show loading state
			$button.addClass('is-loading').prop('disabled', true);
			$button.html('<span class="mpd-msc-spinner"></span> ' + originalButtonText);

			// Remove any existing notices
			$couponForm.find('.mpd-msc-coupon-notice, .woocommerce-error, .woocommerce-message').remove();

			$.ajax({
				type: 'POST',
				url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
				data: {
					security: wc_checkout_params.apply_coupon_nonce,
					coupon_code: code
				},
				dataType: 'html',
				success: (response) => {
					// Reset button
					$button.removeClass('is-loading').prop('disabled', false);
					$button.text(originalButtonText);

					// Show response message (success or error from WooCommerce)
					if (response) {
						$couponForm.prepend('<div class="mpd-msc-coupon-notice">' + response + '</div>');
						
						// Auto-hide success message after 5 seconds
						if (response.indexOf('woocommerce-error') === -1) {
							setTimeout(() => {
								$couponForm.find('.mpd-msc-coupon-notice').fadeOut(300, function() {
									$(this).remove();
								});
							}, 5000);
						}
					}

					// Trigger WooCommerce events
					$(document.body).trigger('applied_coupon_in_checkout', [code]);
					$(document.body).trigger('update_checkout', { update_shipping_method: false });
					$couponCode.val('');
				},
				error: (xhr, status, error) => {
					$button.removeClass('is-loading').prop('disabled', false);
					$button.text(originalButtonText);
					$couponForm.prepend('<div class="mpd-msc-coupon-notice"><ul class="woocommerce-error" role="alert"><li>Failed to apply coupon. Please try again.</li></ul></div>');
				}
			});
		}

		/**
		 * Toggle payment method boxes
		 */
		togglePaymentBox() {
			const $methods = this.$el.find('.wc_payment_method');
			
			$methods.each((index, method) => {
				const $method = $(method);
				const $input = $method.find('input[name="payment_method"]');
				const $box = $method.find('.payment_box');

				if ($input.is(':checked')) {
					$box.slideDown(200);
				} else {
					$box.slideUp(200);
				}
			});
		}

		/**
		 * Update product quantity via AJAX
		 */
		updateQuantity(e, action) {
			e.preventDefault();
			const $btn = $(e.currentTarget);
			const $container = $btn.closest('.mpd-msc-product-qty-controls');
			const $qtyDisplay = $container.find('.mpd-msc-qty-value');
			const cartItemKey = $container.data('cart-item-key');
			let currentQty = parseInt($qtyDisplay.text(), 10);

			// Calculate new quantity
			let newQty = action === 'increase' ? currentQty + 1 : currentQty - 1;
			
			// Minimum quantity is 0 (remove item)
			if (newQty < 0) {
				newQty = 0;
			}

			// Store pending update for this item
			this.pendingQuantityUpdates.set(cartItemKey, newQty);
			$qtyDisplay.text(newQty);

			// Debounce: clear existing timer and set new one
			if (this.quantityUpdateTimer) {
				clearTimeout(this.quantityUpdateTimer);
			}

			// Debounce AJAX call by 500ms to batch rapid clicks
			this.quantityUpdateTimer = setTimeout(() => {
				this.executeQuantityUpdate($container, cartItemKey, currentQty);
			}, 500);
		}

		/**
		 * Execute the actual quantity update AJAX call
		 */
		executeQuantityUpdate($container, cartItemKey, originalQty) {
			const newQty = this.pendingQuantityUpdates.get(cartItemKey);
			this.pendingQuantityUpdates.delete(cartItemKey);

			if (typeof newQty === 'undefined') {
				return;
			}

			// Disable buttons during update
			$container.find('.mpd-msc-qty-btn').prop('disabled', true);
			const $qtyDisplay = $container.find('.mpd-msc-qty-value');

			// Get AJAX URL
			const ajaxUrl = typeof wc_checkout_params !== 'undefined' && wc_checkout_params.ajax_url 
				? wc_checkout_params.ajax_url 
				: (typeof mpd_ajax !== 'undefined' ? mpd_ajax.ajax_url : '/wp-admin/admin-ajax.php');

			// Update cart using custom MPD endpoint
			$.ajax({
				type: 'POST',
				url: ajaxUrl,
				data: {
					action: 'mpd_update_cart_quantity',
					cart_item_key: cartItemKey,
					quantity: newQty,
					nonce: this.settings.checkoutNonce
				},
				success: (response) => {
					$container.find('.mpd-msc-qty-btn').prop('disabled', false);
					if (response.success) {
						// Update the order review section if available
						if (response.data && response.data.order_review) {
							$('.woocommerce-checkout-review-order-table').replaceWith(
								$(response.data.order_review).find('.woocommerce-checkout-review-order-table')
							);
						}
						// Trigger checkout update to refresh totals
						$(document.body).trigger('update_checkout');
					} else {
						// Restore original quantity on error
						$qtyDisplay.text(originalQty);
						debugLog('Quantity update failed', response.data);
					}
				},
				error: () => {
					// Restore original quantity on error
					$container.find('.mpd-msc-qty-btn').prop('disabled', false);
					$qtyDisplay.text(originalQty);
				}
			});
		}

		/**
		 * Handle keyboard navigation
		 */
		handleKeyboard(e) {
			// Only handle if we're focused within the checkout
			if (!this.$el.find(':focus').length && !this.$el.is(':focus')) {
				return;
			}

			// Exclude interactive elements from Enter key capture
			const excludeTags = ['TEXTAREA', 'BUTTON', 'A', 'SELECT'];
			const isExcludedElement = excludeTags.includes(e.target.tagName) ||
				$(e.target).closest('.select2-container, .wc_payment_methods, button, a').length > 0;

			// Enter key - go to next step (unless in excluded element)
			if (e.keyCode === 13 && !isExcludedElement) {
				e.preventDefault();
				if (this.currentStep < this.totalSteps) {
					this.nextStep();
				} else {
					this.$form.submit();
				}
			}

			// Escape key - go to previous step
			if (e.keyCode === 27 && this.currentStep > 1) {
				e.preventDefault();
				this.prevStep();
			}
		}

		/**
		 * Show error message
		 */
		showError(message) {
			// Remove existing error
			this.$el.find('.mpd-msc-error-notice').remove();

			// Add new error - use text() to prevent XSS
			const $error = $('<div class="mpd-msc-error-notice woocommerce-error"><ul><li></li></ul></div>');
			$error.find('li').text(message);
			this.$panels.eq(this.currentStep - 1).find('.mpd-msc-step-content').prepend($error);

			// Auto-remove after 5 seconds
			setTimeout(() => {
				$error.fadeOut(300, function() {
					$(this).remove();
				});
			}, 5000);
		}

		/**
		 * Scroll to element
		 */
		scrollToElement($element) {
			if (!$element.length) {
				return;
			}

			const offset = $element.offset().top - 100;

			$('html, body').animate({
				scrollTop: offset
			}, 300);
		}
	}

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function () {
		initMultiStepCheckout();
	});

	/**
	 * Initialize function
	 */
	function initMultiStepCheckout() {
		debugLog('Initializing...');
		$('.mpd-multi-step-checkout').each(function () {
			// Check if already initialized
			if ($(this).data('mpd-msc-initialized')) {
				debugLog('Already initialized, skipping');
				return;
			}
			
			$(this).data('mpd-msc-initialized', true);
			new MPDMultiStepCheckout(this);
		});
	}

	/**
	 * Reinitialize on Elementor frontend init (for editor preview)
	 */
	$(window).on('elementor/frontend/init', function () {
		debugLog('Elementor frontend init triggered');
		if (typeof elementorFrontend !== 'undefined') {
			elementorFrontend.hooks.addAction('frontend/element_ready/mpd-multi-step-checkout.default', function ($scope) {
				debugLog('Elementor widget ready');
				$scope.find('.mpd-multi-step-checkout').each(function () {
					// Check if already initialized
					if (!$(this).data('mpd-msc-initialized')) {
						$(this).data('mpd-msc-initialized', true);
						new MPDMultiStepCheckout(this);
					}
				});
			});
		}
	});

	// Also try initializing after WooCommerce checkout is ready
	$(document.body).on('init_checkout', function() {
		debugLog('WooCommerce init_checkout triggered');
		initMultiStepCheckout();
	});

})(jQuery);
