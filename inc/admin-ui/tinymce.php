<?php
/**
 * @package Bailey
 */

if ( ! class_exists( 'Bailey_TinyMCE' ) ) :
/**
 * Customizations to the TinyMCE editor.
 *
 * @since 1.0.0.
 *
 * Class Bailey_TinyMCE
 */
class Bailey_TinyMCE {
	/**
	 * The one instance of Bailey_TinyMCE.
	 *
	 * @since 1.0.0.
	 *
	 * @var   Bailey_TinyMCE
	 */
	private static $instance;

	/**
	 * Instantiate or return the one Bailey_TinyMCE instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return Bailey_TinyMCE
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Setup.
	 *
	 * @since  1.0.0.
	 *
	 * @return Bailey_TinyMCE
	 */
	public function __construct() {
		// Add the buttons
		add_action( 'admin_init', array( $this, 'add_buttons' ), 11 );

		// Add translations for plugin
		add_filter( 'wp_mce_translation', array( $this, 'wp_mce_translation' ), 10, 2 );

		// Add the CSS for the icon
		add_action( 'admin_print_styles-post.php', array( $this, 'admin_print_styles' ) );
		add_action( 'admin_print_styles-post-new.php', array( $this, 'admin_print_styles' ) );
	}

	/**
	 * Implement the TinyMCE button for creating a button.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function add_buttons() {
		if ( ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) ) {
			return;
		}

		// The button button
		add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_plugin' ) );
		add_filter( 'mce_buttons', array( $this, 'register_mce_button' ) );

		// The style formats
		add_filter( 'tiny_mce_before_init', array( $this, 'style_formats' ) );
		add_filter( 'mce_buttons_2', array( $this, 'register_mce_formats' ) );
	}

	/**
	 * Implement the TinyMCE plugin for creating a button.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $plugins    The current array of plugins.
	 * @return array                The modified plugins array.
	 */
	public function add_tinymce_plugin( $plugins ) {
		$plugins['bailey_mce_button_button'] = trailingslashit( get_template_directory_uri() ) .'inc/admin-ui/js/tinymce-button.js';

		return $plugins;
	}

	/**
	 * Implement the TinyMCE button for creating a button.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array     $buttons      The current array of plugins.
	 * @return array                   The modified plugins array.
	 */
	public function register_mce_button( $buttons ) {
		$buttons[] = 'bailey_mce_button_button';

		return $buttons;
	}

	/**
	 * Add translations for plugin.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array     $mce_translation    Key/value pairs of strings.
	 * @param  string    $mce_locale         Locale.
	 * @return array                         The updated translation array.
	 */
	public function wp_mce_translation( $mce_translation, $mce_locale ) {
		$additional_items = array(
			'Add button'    => __( 'Add button', 'bailey' ),
			'Insert Button' => __( 'Insert Button', 'bailey' ),
			'Button text'   => __( 'Button text', 'bailey' ),
			'Button URL'    => __( 'Button URL', 'bailey' ),
			'Download icon' => __( 'Download icon', 'bailey' ),
		);

		return array_merge( $mce_translation, $additional_items );
	}

	/**
	 * Add styles to the Styles dropdown.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $settings    TinyMCE settings array.
	 * @return array                 Modified array.
	 */
	public function style_formats( $settings ) {
		$style_formats = array(
			// Big (big)
			array(
				'title'   => __( 'Run In', 'bailey' ),
				'block'   => 'p',
				'classes' => 'run-in',
			),
		);

		// Combine with existing format definitions
		if ( isset( $settings['style_formats'] ) ) {
			$existing_formats = json_decode( $settings['style_formats'] );
			$style_formats = array_merge( $existing_formats, $style_formats );
		}

		// Allow styles to be customized
		$style_formats = apply_filters( 'bailey_style_formats', $style_formats );

		// Encode
		$settings['style_formats'] = json_encode( $style_formats );

		return $settings;
	}

	/**
	 * Add the Styles dropdown for the Visual editor.
	 *
	 * @since  1.0.0
	 *
	 * @param  array    $buttons    Array of activated buttons.
	 * @return array                The modified array.
	 */
	public function register_mce_formats( $buttons ) {
		// Add the styles dropdown
		array_unshift( $buttons, 'styleselect' );

		return $buttons;
	}

	/**
	 * Print CSS for the buttons.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function admin_print_styles() {
		?>
		<style type="text/css">
			i.mce-i-bailey-button-button {
				font: normal 20px/1 'dashicons';
				padding: 0;
				vertical-align: top;
				speak: none;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
				margin-left: -2px;
				padding-right: 2px;
			}
			i.mce-i-bailey-button-button:before {
				content: '\f502';
			}
		</style>
	<?php
	}
}
endif;

/**
 * Instantiate or return the one Bailey_TinyMCE instance.
 *
 * @since  1.0.0.
 *
 * @return Bailey_TinyMCE
 */
function bailey_get_tinymce_buttons() {
	return Bailey_TinyMCE::instance();
}

add_action( 'admin_init', 'bailey_get_tinymce_buttons' );