/**
 * Add Immediately-Invoked Function Expression that initiates the Attached Posts functionality.
 *
 * @since  1.0.
 *
 * @param  object    $         The jQuery object.
 * @param  object    window    The window object.
 * @return void
 */
( function ( $, window ) {

	function BaileyAttachedPosts() {
		/**
		 * Used by autocomplete to cache results for repeated requests.
		 *
		 * @since 1.0.
		 *
		 * @type  {{}}
		 */
		var cache = {};

		/**
		 * Collection of cached selectors.
		 *
		 * @since 1.0.
		 *
		 * @type  {{}}
		 */
		var UI = {};

		/**
		 * Local copy of data sent to JS.
		 *
		 * @since 1.0.
		 *
		 * @type  {}
		 */
		var baileyDataLocal;

		/**
		 * Initiate all actions for this class.
		 *
		 * @since  1.0.
		 *
		 * @return void
		 */
		function init() {
			// Cache reusable UI items
			cacheElements();

			// Setup event binding
			bindEvents();

			// Initiate the autocomplete
			setupAutocomplete();

			// Make elements sortable
			setupSortables();

			// Hide/show metaboxes initially
			toggleMetaboxes();
		}

		/**
		 * Cache all reusable cache elements.
		 *
		 * @since  1.0.
		 *
		 * @return void
		 */
		function cacheElements() {
			UI.postChoosers       = $( '.bailey-post-chooser' );           // The tab areas for toggle between recent and search posts
			UI.placeholders       = $( '.bailey-attached-posts' );         // The placeholders for lists of items
			UI.searchFields       = $( '.bailey-apa-input' );              // The input for the autocomplete search
			UI.pageTemplateSelect = $( '#page_template' );                 // The page template select input
			UI.portfolioWrapper   = $( '#bailey-attached-projects' );      // The portfolio metabox
			UI.portfolioMBToggle  = $( '#bailey-attached-projects-hide' ); // Portfolio screen options checkbox

			// Cache the prepared data to the local scope
			baileyDataLocal = baileyData;
		}

		/**
		 * Setup event binding.
		 *
		 * @since  1.0.
		 *
		 * @return void
		 */
		function bindEvents() {
			// Initiate the tabs
			UI.postChoosers.on( 'click', 'a.tab-link', function ( e ) {
				setupTabs( this );
				e.preventDefault();
			} );

			// Add click event to recent posts
			UI.postChoosers.on( 'click', 'a.bailey-recent-post', function ( e ) {
				var $this = $( this ),
					value = $this.attr( 'data-id' ),
					label = $this.attr( 'data-title' );

				prependItem( label, value, 'link', this );
				e.preventDefault();
			} );

			// Setup click event for removing items
			UI.placeholders.on( 'click', '.bailey-remove-item', function ( e ) {
				removeItem( this );
				e.preventDefault();
			} );

			// Toggle metaboxes depending on value of page select
			UI.pageTemplateSelect.on( 'change', toggleMetaboxes );
		}

		/**
		 * Click function for changing tabs.
		 *
		 * @since  1.0.
		 *
		 * @param  currentThis
		 * @return void
		 */
		function setupTabs( currentThis ) {
			var $this       = $( currentThis ),
				$container  = $this.parents( '.bailey-post-chooser' ),
				$currentTab = $( $this.attr( 'href' ), $container ),
				$tabsPanels = $( '.tabs-panel', $container );

			// Change the tab state
			$this.parent().addClass( 'tabs' ).siblings( 'li' ).removeClass( 'tabs' );

			// Open/close the tabs
			$tabsPanels.removeClass( 'bailey-active-tab' ).addClass( 'bailey-inactive-tab' );
			$currentTab.addClass( 'bailey-active-tab' ).removeClass( 'bailey-inactive-tab' );

			// Focus search field
			if ( 1 == $currentTab.find( 'input' ).length ) {
				$currentTab.find( 'input' ).focus();
			} else {
				$container.find( 'input' ).blur();
			}
		}

		/**
		 * Add the autocomplete functionality.
		 *
		 * Autocomplete is set to kick in after 3 characters are typed. The menu of items that is revealed will adjust
		 * to fit in the browser window. The data is source from a normal WordPress search. When hovering over an item,
		 * the item's label is show in the input field. Upon selecting the item, the ID is appended to a hidden value
		 * that collects the IDs. The item is also shown in the list of the attached items.
		 *
		 * @since  1.0.
		 *
		 * @return void
		 */
		function setupAutocomplete() {
			UI.searchFields.autocomplete( {
				// Require 3 characters before getting data
				minLength : 3,

				// If the opened autocomplete box runs into the window, adjust the position to fit in viewport
				position  : {
					collision : 'fit'
				},

				// Define where the data comes from
				source    : function ( request, response ) {
					// The search term
					var term = request.term;

					// If the searched term exists in cache, return the results it would generate with an AJAX request it
					if ( term in cache ) {
						response( cache[ term ] );
						return;
					}

					// Make the AJAX request
					var jsonRequest = autocompleteRequest( request, response, term, baileyDataLocal );
				},

				// Instead of showing the item value in the input, show the label
				focus     : function( e, ui ) {
					$( this ).val( ui.item.label );
					return false;
				},

				// Configure function to run when an item is selected from the dropdown
				select    : function ( e, ui ) {
					// Stop the selected value from being sent to the input field
					$( this ).val( '' );

					// Add item to list of items
					prependItem( ui.item.label, ui.item.value, 'select', this );

					// Prevent the select method from placing the item in the input field
					e.preventDefault();
				}
			} );
		}

		/**
		 * The AJAX request that is fired to get the autocomplete data.
		 *
		 * @since   1.0.
		 *
		 * @param   request
		 * @param   response
		 * @param   term
		 * @param   baileyDataLocal
		 * @returns {*}
		 */
		function autocompleteRequest( request, response, term, baileyDataLocal ) {
			return $.ajax( {
				url      : ajaxurl,
				type     : 'POST',
				data     : {
					bailey_apa_term     : term,
					bailey_apa_template : getTemplateVal(),
					bailey_apa_nonce    : baileyDataLocal.nonce,
					action              : baileyDataLocal.action
				},
				dataType : 'json',
				success  : function ( data, textStatus, jqXHR ) {
					// Cache the data
					cache[ term ] = data;

					// Hand the data back for processing
					response( data );
				}
			} );
		}

		/**
		 * Add an item to the list of items and to the hidden input.
		 *
		 * @since  1.0.
		 *
		 * @param  label          string    The item name
		 * @param  value          int       The item ID
		 * @param  type           string    The type of item.
		 * @param  currentThis    object    The current object.
		 * @return void
		 */
		function prependItem( label, value, type, currentThis ) {
			// Cache selectors and setup variables
			var $this        = $( currentThis ),
				$thisMetabox = $this.parents( '.postbox' ),
				metaboxID    = $thisMetabox.attr( 'id' ),
				$placeholder = $( '.bailey-attached-posts', $thisMetabox ),
				$hiddenField = $( '.bailey-current-posts', $thisMetabox ),
				currentVals  = getCurrentValsArray( $hiddenField );

			/**
			 * Add the item, only if it is not already added. Note that "value" will always be an integer and values
			 * in the "currentVals" array will be strings. To test if "value" is in "currentVals", "value" needs to be
			 * converted to a string before the search can be executed.
			 */
			if ( -1 === $.inArray( String( value ), currentVals ) ) {
				// Build the list item
				var html = buildAttachedItemHTML( metaboxID, value, label );

				// Add the item to the list of items
				$placeholder.prepend( html );

				// Prepend the ID to the list of IDs
				currentVals.unshift( value );

				// Replace the set of values with the new values
				setCurrentVals( $hiddenField, idsToString( currentVals ) );

				// Since an item was added, hide the "no posts" message
				$( '.bailey-no-posts', $thisMetabox ).hide();
			}
		}

		/**
		 * Remove an item from the item list and the hidden IDs.
		 *
		 * @since 1.0.
		 *
		 * @param  currentThis    object    Current this object.
		 * @param  type           string    Type of item to remove.
		 * @return void
		 */
		function removeItem( currentThis, type ) {
			var $this        = $( currentThis ),
				$parent      = $this.parent(),
				id           = $parent.attr( 'data-id' ),
				$thisMetabox = $this.parents( '.postbox' ),
				$hiddenField = $( '.bailey-current-posts', $thisMetabox ),
				currentVals  = getCurrentValsArray( $hiddenField );

			// Get the index of the item to remove
			var index = $.inArray( String( id ), currentVals );

			// Remove the item by index
			currentVals.splice( index, 1 );

			// Write the new items back to the hidden element
			$hiddenField.val( idsToString( currentVals ) );

			// Remove the <li>
			$parent.fadeOut( 300, function() {
				$( this ).remove();

				// If no items exist, show "no posts" message
				if ( 0 === $( '.bailey-attached-posts li', $thisMetabox ).length ) {
					$( '.bailey-no-posts', $thisMetabox ).show();
				}
			} );
		}

		/**
		 * Build the list item HTML.
		 *
		 * @since   1.0.
		 *
		 * @param   id          string    The unique ID for the li.
		 * @param   value       string    ID of the item.
		 * @param   label       string    Name of the item.
		 * @returns {string}
		 */
		function buildAttachedItemHTML( id, value, label ) {
			return '<li id="' + id + '-' + value + '" data-id="' + value + '">' + label + ' <a title="' + baileyDataLocal.removePostsTitleAttr + '" class="bailey-remove-item" href="#">' + baileyDataLocal.removePostsLinkText + '</a></li>';
		}

		/**
		 * Initiate the sortables.
		 *
		 * @since  1.0
		 *
		 * @return void
		 */
		function setupSortables() {
			UI.placeholders.sortable( {
				// Make the list hold a space for where the item will be dropped
				placeholder          : 'bailey-attached-posts-sortable-placeholder',
				forcePlaceholderSize : true,

				// Use "move" as the cursor when moving item
				cursor               : 'move',

				// Reorder the items once an item is dropped
				stop                 : function ( event, ui ) {
					reorderItems( event, ui, this );
				}
			} );
		}

		/**
		 * Reorder items when a draggable is dropped.
		 *
		 * @since  1.0.
		 *
		 * @param  event          object    Event object passed to the handler.
		 * @param  ui             object    The ui object.
		 * @param  currentThis              Current this.
		 */
		function reorderItems( event, ui, currentThis ) {
			var ids          = [],
				$this        = $( currentThis ),
				$thisMetabox = $this.parents( '.postbox' ),
				$hiddenField = $( '.bailey-current-posts', $thisMetabox );

			// Get the current order of the items
			$( '.bailey-attached-posts li', $thisMetabox ).each( function ( element ) {
				ids.push( $( this ).attr( 'data-id' ) );
			} );

			// Write the new order to the hidden field
			setCurrentVals( $hiddenField, ids );
		}

		/**
		 * Toggle metaboxes when the page template is changed.
		 *
		 * @since  1.0.
		 *
		 * @return void
		 */
		function toggleMetaboxes() {
			if ( 'template-portfolio.php' === getTemplateVal() ) {
				UI.portfolioWrapper.show();
			} else {
				UI.portfolioWrapper.hide();
			}
		}

		/**
		 * Change a CSV string to an array.
		 *
		 * @since   1.0.
		 *
		 * @param   idString    string    String of values, comma separated
		 * @returns {*}                   Array of values.
		 */
		function idsToArray( idString ) {
			return idString.split( ',' );
		}

		/**
		 * Convert array of values to a common delimited string.
		 *
		 * @since   1.0.
		 *
		 * @param   idArray     array    Array of items.
		 * @returns {string}             String representation of items.
		 */
		function idsToString( idArray ) {
			return idArray.join( ',' );
		}

		/**
		 * Get current vals of the hidden input.
		 *
		 * @since   1.0.
		 *
		 * @param   $field    jQuery    jQuery object of the hidden input.
		 * @returns {*}                 String of values.
		 */
		function getCurrentVals( $field ) {
			return $field.val();
		}

		/**
		 * Sets the values to hidden field.
		 *
		 * @since   1.0.
		 *
		 * @param   $field    object    The jQuery object to act on.
		 * @param   vals      string    String of vals.
		 * @returns {*}
		 */
		function setCurrentVals( $field, vals ) {
			return $field.val( vals );
		}

		/**
		 * Get the current hidden field values as an array.
		 *
		 * @since   1.0.
		 *
		 * @param   $field    object    The jQuery object representing the hidden field.
		 * @returns {*}                 Array of values.
		 */
		function getCurrentValsArray( $field ) {
			return idsToArray( getCurrentVals( $field ) );
		}

		/**
		 * Get the current value of the page template select.
		 *
		 * @since   1.0.
		 *
		 * @returns {*}    String    The value of the page template select.
		 */
		function getTemplateVal() {
			return UI.pageTemplateSelect.val();
		}

		// Initiate the actions.
		init();
	}

	window.BaileyAttachedPosts = new BaileyAttachedPosts();
} ) ( jQuery, window );