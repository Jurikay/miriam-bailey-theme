/**
 * @package Bailey
 */

/**
 * Initialize the Per Page metabox functionality.
 *
 * @since 1.0.0.
 */
(function($){
	// Color pickers
	$(document).ready(function() {
		var $colorpickers = $('.bailey_colors_picker');
		if ($colorpickers.length > 0) {
			$colorpickers.wpColorPicker();
		}
	});

	// Sidebar override toggle
	$('#bailey_sidebar_override').on('change', function() {
		var checked = $(this).prop('checked'),
			setting = $(this).parent().parent().find('#bailey_sidebar');

		if (checked) {
			setting.prop('disabled', '');
		} else {
			setting.prop('disabled', 'disabled');
		}
	});

	// Hide Sidebar metabox with Portfolio Template
	$('#page_template').on('change', function() {
		var $sidebar = $('#bailey_sidebar_metabox'),
			$sidebarToggle = $('#bailey_sidebar_metabox-hide'),
			template = $(this).val();

		if ('template-portfolio.php' === template) {
			$sidebar.hide();
			$sidebarToggle.hide();
		} else {
			$sidebar.show();
			$sidebarToggle.show();
		}
	});
	$('#page_template').trigger('change');
}(jQuery));