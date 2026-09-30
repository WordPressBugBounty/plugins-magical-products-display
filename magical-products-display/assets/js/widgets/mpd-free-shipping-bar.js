/**
 * Free Shipping Progress Bar JavaScript
 *
 * @package Magical_Shop_Builder
 * @since   2.1.0
 */

(function ($) {
	'use strict';

	var MPDFreeShippingBar = {
		init: function () {
			$(document.body).on('added_to_cart removed_from_cart updated_cart_totals wc_fragments_refreshed', function () {
				MPDFreeShippingBar.refresh();
			});
		},

		refresh: function () {
			// Trigger fragment refresh if not already firing to keep all bars in sync.
			if (typeof wc_cart_fragments_params !== 'undefined') {
				$(document.body).trigger('wc_fragment_refresh');
			}
		}
	};

	$(document).ready(function () {
		MPDFreeShippingBar.init();
	});

})(jQuery);
