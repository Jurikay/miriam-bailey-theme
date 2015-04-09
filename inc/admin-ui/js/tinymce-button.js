(function(tinymce) {
	tinymce.PluginManager.add('bailey_mce_button_button', function( editor, url ) {
		editor.addButton('bailey_mce_button_button', {
			icon: 'bailey-button-button',
			tooltip: 'Add button',
			onclick: function() {
				editor.windowManager.open( {
					title: 'Insert Button',
					body: [
						{
							type: 'textbox',
							name: 'text',
							label: 'Button text'
						},
						{
							type: 'textbox',
							name: 'url',
							label: 'Button URL',
							value: 'http://'
						},
						{
							type: 'checkbox',
							name: 'download',
							label: 'Download icon',
							value: 'true'
						}
					],
					onsubmit: function( e ) {
						var classes = 'bailey-button';
						if (true == e.data.download) {
							classes = 'bailey-download';
						}
						editor.insertContent( '<a href="' + baileyEscAttr( e.data.url ) + '" class="' + baileyEscAttr( classes ) + '">' + baileyEscAttr( e.data.text ) + '</a>');
					}
				});
			}
		});
	});

	// @link http://stackoverflow.com/a/9756789/719811
	function baileyEscAttr(s, preserveCR) {
		preserveCR = preserveCR ? '&#13;' : '\n';
		return ('' + s) /* Forces the conversion to string. */
			.replace(/&/g, '&amp;') /* This MUST be the 1st replacement. */
			.replace(/'/g, '&apos;') /* The 4 other predefined entities, required. */
			.replace(/"/g, '&quot;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/\r\n/g, preserveCR) /* Must be before the next replacement. */
			.replace(/[\r\n]/g, preserveCR);
	}
})(tinymce);