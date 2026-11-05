/* global jQuery, aimeMarketingNotice */
(function ($) {
	'use strict';

	var $notice = $('#aime-marketing-notice');
	var $installBtn = $('#aime-install-plugin');
	var $dismissBtn = $('#aime-dismiss-notice');
	var $closeBtn = $('#aime-notice-close');

	function dismissNotice() {
		$notice.addClass('aime-out');
		setTimeout(function () { $notice.remove(); }, 350);
	}

	$installBtn.on('click', function (e) {
		e.preventDefault();
		if ($installBtn.hasClass('loading')) return;

		$installBtn.addClass('loading');

		$.ajax({
			url: aimeMarketingNotice.ajaxurl,
			type: 'POST',
			data: {
				action: 'aime_install_plugin',
				nonce: aimeMarketingNotice.nonce,
				slug: $installBtn.data('slug'),
				action_type: $installBtn.data('action'),
			},
			success: function (response) {
				if (response.success) {
					$installBtn.removeClass('loading').addClass('success');
					$installBtn.find('.aime-btn-label').text(response.data.message);
					setTimeout(function () { window.location.reload(); }, 1200);
				} else {
					$installBtn.removeClass('loading');
					alert(response.data.message);
				}
			},
			error: function () {
				$installBtn.removeClass('loading');
				alert(aimeMarketingNotice.error);
			},
		});
	});

	$closeBtn.on('click', function (e) {
		e.preventDefault();
		dismissNotice();
	});

	$dismissBtn.on('click', function (e) {
		e.preventDefault();
		dismissNotice();
		$.ajax({
			url: aimeMarketingNotice.ajaxurl,
			type: 'POST',
			data: {
				action: 'aime_dismiss_notice',
				nonce: aimeMarketingNotice.nonce,
			},
		});
	});
})(jQuery);
