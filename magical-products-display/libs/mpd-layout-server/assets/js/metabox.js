/**
 * MPD Layout Server - Metabox Scripts
 *
 * @package MPD_Layout_Server
 * @since   1.0.0
 */

(function($) {
	'use strict';

	/**
	 * Validate JSON button handler.
	 */
	$('#mpd_validate_json').on('click', function(e) {
		e.preventDefault();
		
		var $textarea = $('#mpd_layout_structure');
		var $status = $('#mpd_json_status');
		var content = $textarea.val().trim();

		if (!content) {
			$status.removeClass('valid invalid').text('');
			return;
		}

		try {
			JSON.parse(content);
			$status.removeClass('invalid').addClass('valid').text('✓ Valid JSON');
		} catch (error) {
			$status.removeClass('valid').addClass('invalid').text('✗ Invalid JSON: ' + error.message);
		}
	});

	/**
	 * Format JSON button handler.
	 */
	$('#mpd_format_json').on('click', function(e) {
		e.preventDefault();
		
		var $textarea = $('#mpd_layout_structure');
		var $status = $('#mpd_json_status');
		var content = $textarea.val().trim();

		if (!content) {
			return;
		}

		try {
			var parsed = JSON.parse(content);
			var formatted = JSON.stringify(parsed, null, '\t');
			$textarea.val(formatted);
			$status.removeClass('invalid').addClass('valid').text('✓ JSON formatted');
		} catch (error) {
			$status.removeClass('valid').addClass('invalid').text('✗ Cannot format: ' + error.message);
		}
	});

	/**
	 * Auto-generate layout ID from title.
	 */
	$('#title').on('blur', function() {
		var $layoutId = $('#mpd_layout_id');
		
		// Only auto-fill if layout ID is empty.
		if ($layoutId.val().trim() !== '') {
			return;
		}

		var title = $(this).val();
		var slug = title.toLowerCase()
			.replace(/[^a-z0-9\s-]/g, '')
			.replace(/\s+/g, '-')
			.replace(/-+/g, '-')
			.trim();

		$layoutId.val(slug);
	});

})(jQuery);
