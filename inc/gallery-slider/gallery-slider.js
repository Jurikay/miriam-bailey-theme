/*!
 * Script for adding functionality to the Create Gallery view.
 *
 * @since 1.0.0
 */

(function($){
	var media = wp.media,
		renderFn = media.view.Settings.Gallery.prototype.render;

	media.view.Settings.Gallery = media.view.Settings.Gallery.extend({
		render: function() {
			var self = this,
				atts = self.model.attributes;

			// Begin with default function
			renderFn.apply( this, arguments );

			// Append the template
			this.$el.append( media.template( 'bailey-gallery-settings' ) );

			// Set up inputs
			// slider
			media.gallery.defaults.bailey_slider = false;
			this.update.apply( this, ['bailey_slider'] );
			// Autoplay
			media.gallery.defaults.bailey_autoplay = false;
			this.update.apply( this, ['bailey_autoplay'] );
			// prevnext
			media.gallery.defaults.bailey_prevnext = false;
			this.update.apply( this, ['bailey_prevnext'] );
			// delay
			media.gallery.defaults.bailey_delay = 6000;
			if ('undefined' === typeof this.model.attributes.bailey_delay) {
				this.model.attributes.bailey_delay = media.gallery.defaults.bailey_delay;
			}
			this.update.apply( this, ['bailey_delay'] );
			// effect
			media.gallery.defaults.bailey_effect = 'scrollHorz';
			this.update.apply( this, ['bailey_effect'] );

			// Toggle slider settings
			if ('undefined' === typeof atts.bailey_slider || false == atts.bailey_slider) {
				this.$el.find('#bailey-slider-settings').hide();
			}
			this.model.on('change', function(t) {
				// Only proceed if the slider toggle changed
				if ('undefined' === typeof this.changed.bailey_slider) {
					return;
				}

				var toggle = this.changed.bailey_slider,
					$settingsDiv = $('#bailey-slider-settings');

				if ( true === toggle ) {
					$settingsDiv.show();
				} else {
					$settingsDiv.hide();
				}
			});

			return this;
		}
	});
}(jQuery));