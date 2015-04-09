/* global jQuery, glyphsData */
(function($, document, window, glyphsData) {
	'use strict';

	var glyphs = {
		cache: {
			$document: $(document)
		},

		init: function() {
			glyphs.cache.$document.on('unbox-postclose', function() {
				window.location = glyphsData.authUrl;
			});
		}
	};

	glyphs.init();
})(jQuery, document, window, glyphsData);