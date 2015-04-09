/*!
 * Script for initializing frontend functions and libs.
 *
 * @since 1.0.0
 */
/* global jQuery, baileyFitVids */
( function($) {
	'use strict';

	var Bailey = {
		/**
		 * Object cache
		 *
		 * @since 1.0.0.
		 */
		cache: {},

		/**
		 * Initialize the Bailey object
		 *
		 * @since 1.0.0.
		 *
		 * @return void
		 */
		init: function() {
			this.cacheElements();
			this.bindEvents();
		},

		/**
		 * Add objects to the object cache
		 *
		 * @since 1.0.0.
		 *
		 * @return void
		 */
		cacheElements: function() {
			this.cache.$window     = $(window);
			this.cache.$document   = $(document);
			this.cache.$body       = $('body');

			this.cache.$adminbar   = $('#wpadminbar');

			this.cache.$menuToggle = $('#menu-toggle');
			this.cache.$BaileyBar  = $('#bailey-bar');

			this.cache.$projectimg = $('.entry-content .size-bailey_large');

			this.cache.dontClose = false;
		},

		/**
		 * Bind functionality to events
		 *
		 * @since 1.0.0.
		 *
		 * @return void
		 */
		bindEvents: function() {
			var self = this;

			// Menu button toggles Bailey Bar
			self.cache.$menuToggle.on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				self.toggleBaileyBar();
			});

			// Bailey Bar closes when user clicks elsewhere
			self.cache.$document.on('click', 'body.pushed', function() {
				if (false === self.cache.dontClose) {
					self.toggleBaileyBar();
				}
				self.cache.dontClose = false;
			});

			// Bailey Bar doesn't close when user clicks on bar
			self.cache.$BaileyBar.on('click', function(e) {
				self.cache.dontClose = true;
			});

			// Image modifier
			self.cache.$document.on('ready post-load', function() {
				self.imageMod();
			});

			// FitVids
			self.cache.$document.on('ready post-load', function() {
				self.fitVidsInit();
			});

			// Masonry
			self.cache.$document.on('ready', function() {
				self.masonryInit();
			});
		},

		/**
		 * Toggle the state of the Bailey Bar
		 *
		 * @since 1.0.0.
		 *
		 * @return void
		 */
		toggleBaileyBar: function() {
			var $body      = this.cache.$body,
				$BaileyBar = this.cache.$BaileyBar,
				$adminbar  = $('#wpadminbar');

			$body.one('transitionend webkitTransitionEnd oTransitionEnd', function() {
				if (! $body.hasClass('pushed')) {
					$adminbar.removeClass('moved');
					$BaileyBar.hide();
				}
			});

			if ( $BaileyBar.hasClass('on-canvas') ) {
				$body.removeClass('pushed');
				$BaileyBar.removeClass('on-canvas');
			} else {
				$body.addClass('pushed');
				$BaileyBar.addClass('on-canvas').show();
				$adminbar.addClass('moved');
			}
		},

		/**
		 * Modify specific image sizes in content for better styling control.
		 *
		 * @since 1.0.0.
		 *
		 * @return void
		 */
		imageMod: function() {
			// Only proceed if the correct image size is present on the page.
			if (this.cache.$projectimg.length > 0) {
				this.cache.$projectimg.each(function() {
					// Without captions
					var $paragraph = $(this).parents('p').first();
					if ( $paragraph.children().length > 1 ) {
						// If multiple images are contained in one paragraph, move each of them
						// to a separate paragraph.
						$paragraph.children().each(function() {
							if ( $(this).is(':first-child') ) {
								$(this).unwrap();
							}
							$(this).wrap('<p>');
						});
					}
					$(this).parents('p').wrap('<div class="bailey-image-container">').find('>:first-child').unwrap();

					// With captions
					$(this).parents('figure').addClass('bailey-image-container');
				});
			}
		},

		/**
		 * Initialize the FitVids script
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		fitVidsInit: function() {
			// Make sure lib is loaded.
			if ( ! $.fn.fitVids ) {
				return;
			}

			// Update the cache
			this.cache.$fitvids = $('.site-main');

			var args = {};

			// Get custom selectors
			if ('object' === typeof baileyFitVids) {
				args.customSelector = baileyFitVids.selectors;
			}

			// Run FitVids
			this.cache.$fitvids.fitVids(args);

			// Full width on single project pages
			$('.fluid-width-video-wrapper', 'body.single-jetpack-portfolio').parents('p').wrap('<div class="bailey-image-container">').find('>:first-child').unwrap();

			// Fix padding issue with Blip.tv. Note that this *must* happen after Fitvids runs.
			// The selector finds the Blip.tv iFrame, then grabs the .fluid-width-video-wrapper div sibling.
			this.cache.$fitvids.find('.fluid-width-video-wrapper:nth-child(2)').css({ 'paddingTop': 0 });
		},

		/**
		 * Initialize the Masonry layout
		 *
		 * @since 1.0.0.
		 *
		 * @return void
		 */
		masonryInit: function() {
			// Make sure libs are loaded.
			if ( ! $.fn.masonry || 'undefined' === typeof imagesLoaded ) {
				return;
			}

			// Cache the Masonry container
			this.cache.$portfolio = $('.portfolio-container').not('.columns-1');

			// Bail if there is no Masonry container
			if ( this.cache.$portfolio.length < 1 ) {
				return;
			}

			// Fire Masonry after images are loaded
			this.cache.$portfolio.imagesLoaded(function() {
				Bailey.cache.$portfolio.masonry({
					gutter       : '#gutter-sizer',
					itemSelector : 'article'
				});
			});

			// Infinite Scroll support
			if (this.cache.$body.hasClass('infinite-scroll')) {
				this.cache.$document.on('post-load', function() {
					var $appended = $('.infinite-wrap article');
					Bailey.cache.$portfolio.masonry('appended', $appended);
					Bailey.cache.$portfolio.imagesLoaded(function() {
						Bailey.cache.$portfolio.masonry();
						$appended.unwrap();
					});
				});
			}
		}
	};

	Bailey.init();
} )( jQuery );