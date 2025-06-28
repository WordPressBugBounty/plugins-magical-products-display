;(function($){
	$(document).ready(function(){
		$('.mgpd-dismiss').on('click',function(){
			var url = new URL(location.href);
			url.searchParams.append('dismissed',1);
			location.href= url;
		});
		$('.mgpd-revdismiss').on('click',function(){
			var url = new URL(location.href);
			url.searchParams.append('revadded',1);
			location.href= url;
		});
		
		// AJAX dismiss for plugin suggestion notice
		$('.mpd-dismiss-suggestion').on('click', function(e){
			e.preventDefault();
			var $button = $(this);
			var $notice = $button.closest('.mpd-plugin-suggestion');
			var nonce = $notice.data('nonce');
			
			// Add dismissing animation
			$notice.addClass('dismissing');
			
			// Use localized AJAX URL or fallback to global ajaxurl
			var ajax_url = (typeof mpd_admin_ajax !== 'undefined') ? mpd_admin_ajax.ajax_url : ajaxurl;
			
			$.ajax({
				url: ajax_url,
				type: 'POST',
				data: {
					action: 'mpd_dismiss_suggestion',
					nonce: nonce
				},
				success: function(response) {
					if (response === 'success') {
						setTimeout(function() {
							$notice.fadeOut(300, function() {
								$(this).remove();
							});
						}, 300);
					}
				},
				error: function() {
					// Remove animation class and fallback to page reload method
					$notice.removeClass('dismissing');
					var url = new URL(location.href);
					url.searchParams.append('mpd_dismiss_suggestion', '1');
					url.searchParams.append('_wpnonce', nonce);
					location.href = url;
				}
			});
		});
		
		// Handle built-in WordPress dismiss button
		$('.mpd-plugin-suggestion').on('click', '.notice-dismiss', function() {
			var $notice = $(this).closest('.mpd-plugin-suggestion');
			var nonce = $notice.data('nonce');
			
			// Use localized AJAX URL or fallback to global ajaxurl
			var ajax_url = (typeof mpd_admin_ajax !== 'undefined') ? mpd_admin_ajax.ajax_url : ajaxurl;
			
			$.ajax({
				url: ajax_url,
				type: 'POST',
				data: {
					action: 'mpd_dismiss_suggestion',
					nonce: nonce
				}
			});
		});

		// AJAX plugin installation
		$(document).on('click', '.mpd-install-plugin', function(e) {
			e.preventDefault();
			var $button = $(this);
			var $notice = $button.closest('.mpd-plugin-suggestion');
			var originalText = $button.text();
			var pluginSlug = $notice.data('plugin-slug');
			var pluginFile = $notice.data('plugin-file');
			
			// Show loading state
			$button.addClass('loading').text('Installing...').prop('disabled', true);
			
			var ajax_url = (typeof mpd_admin_ajax !== 'undefined') ? mpd_admin_ajax.ajax_url : ajaxurl;
			
			$.ajax({
				url: ajax_url,
				type: 'POST',
				data: {
					action: 'mpd_install_plugin',
					plugin_slug: pluginSlug,
					nonce: (typeof mpd_admin_ajax !== 'undefined' && mpd_admin_ajax.updates_nonce) ? mpd_admin_ajax.updates_nonce : 
						   (typeof wp !== 'undefined' && wp.updates && wp.updates.ajaxNonce) ? wp.updates.ajaxNonce : ''
				},
				success: function(response) {
					if (response.success) {
						$button.removeClass('loading mpd-install-plugin').addClass('mpd-activate-plugin').text('Activating...');
						
						// Small delay for better UX
						setTimeout(function() {
							// Now activate the plugin
							$.ajax({
								url: ajax_url,
								type: 'POST',
								data: {
									action: 'mpd_activate_plugin',
									plugin_file: pluginFile,
									nonce: (typeof mpd_admin_ajax !== 'undefined' && mpd_admin_ajax.updates_nonce) ? mpd_admin_ajax.updates_nonce : 
										   (typeof wp !== 'undefined' && wp.updates && wp.updates.ajaxNonce) ? wp.updates.ajaxNonce : ''
								},
								success: function(activateResponse) {
									if (activateResponse.success) {
										$button.removeClass('loading button-primary').addClass('button-secondary').text('✓ Plugin Activated!');
										setTimeout(function() {
											$notice.addClass('dismissing');
											setTimeout(function() {
												$notice.fadeOut(300, function() {
													$(this).remove();
												});
											}, 300);
										}, 2000);
									} else {
										$button.removeClass('loading').addClass('button-secondary').text('Activation Failed').prop('disabled', false);
										if (activateResponse.data) {
											console.error('Activation error:', activateResponse.data);
										}
									}
								},
								error: function() {
									$button.removeClass('loading').addClass('button-secondary').text('Activation Failed').prop('disabled', false);
								}
							});
						}, 500);
					} else {
						$button.removeClass('loading').text('Installation Failed').prop('disabled', false);
						if (response.data) {
							console.error('Installation error:', response.data);
						}
					}
				},
				error: function() {
					$button.removeClass('loading').text(originalText).prop('disabled', false);
				}
			});
		});

		// AJAX plugin activation
		$(document).on('click', '.mpd-activate-plugin', function(e) {
			e.preventDefault();
			var $button = $(this);
			var $notice = $button.closest('.mpd-plugin-suggestion');
			var originalText = $button.text();
			var pluginFile = $notice.data('plugin-file');
			
			// Show loading state
			$button.addClass('loading').text('Activating...').prop('disabled', true);
			
			var ajax_url = (typeof mpd_admin_ajax !== 'undefined') ? mpd_admin_ajax.ajax_url : ajaxurl;
			
			$.ajax({
				url: ajax_url,
				type: 'POST',
				data: {
					action: 'mpd_activate_plugin',
					plugin_file: pluginFile,
					nonce: (typeof mpd_admin_ajax !== 'undefined' && mpd_admin_ajax.updates_nonce) ? mpd_admin_ajax.updates_nonce : 
						   (typeof wp !== 'undefined' && wp.updates && wp.updates.ajaxNonce) ? wp.updates.ajaxNonce : ''
				},
				success: function(response) {
					if (response.success) {
						$button.removeClass('loading button-primary').addClass('button-secondary').text('✓ Plugin Activated!');
						setTimeout(function() {
							$notice.addClass('dismissing');
							setTimeout(function() {
								$notice.fadeOut(300, function() {
									$(this).remove();
								});
							}, 300);
						}, 2000);
					} else {
						$button.removeClass('loading').text('Activation Failed').prop('disabled', false);
						if (response.data) {
							console.error('Activation error:', response.data);
						}
					}
				},
				error: function() {
					$button.removeClass('loading').text(originalText).prop('disabled', false);
				}
			});
		});
	});
})(jQuery);