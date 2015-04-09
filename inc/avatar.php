<?php
/**
 * @package Bailey
 */

if ( ! class_exists( 'Bailey_Avatar' ) ) :
/**
 * Bailey has a custom replacement for "Mystery Man" that we're calling "Bailey Mystery Person".
 *
 * The "get_avatar" function does not offer easy filtering as it is a pluggable function and is meant to be
 * overridden altogether if a change is needed. Our fix is to remove "Mystery Man" in favor of the delectable
 * "Bailey Mystery Person" version in the list of avatars via the "avatar_defaults" filter. This allows us to save a slug that
 * represents the upgraded man--it is the URL to the image resource. Then, the "avatar_default" option is filtered
 * to override a non-existent value or to change the choice of "mystery" to the monolithic "Bailey Mystery Person". Note
 * that this method ensures normal avatar choice will continue to work, unless you want to choose "Mystery Man".
 *
 * @since 1.0.0.
 */
class Bailey_Avatar {

	/**
	 * The one instance of Bailey_Avatar.
	 *
	 * @since 1.0.0.
	 *
	 * @var Bailey_Avatar
	 */
	private static $instance;

	/**
	 * The name of the file to load for the custom avatar.
	 *
	 * @since 1.0.0.
	 *
	 * @var   string    $avatar_filename    The name of the file to load for the custom avatar.
	 */
	var $avatar_filename = 'avatar.png';

	/**
	 * Instantiate or return the one Bailey_Avatar instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return Bailey_Avatar
	 */
	public static function instance() {
		if ( is_null( self::$instance ) )
			self::$instance = new self();

		return self::$instance;
	}

	/**
	 * Initiate the actions.
	 *
	 * @since  1.0.0.
	 *
	 * @return Bailey_Avatar
	 */
	public function __construct() {
		// Change the list of default avatars
		add_filter( 'avatar_defaults', array( $this, 'avatar_defaults' ) );

		// Alter the value of the default avatar when read from the db
		add_filter( 'default_option_avatar_default', array( $this, 'default_option_avatar_default' ) );
		add_filter( 'option_avatar_default', array( $this, 'default_option_avatar_default' ) );
	}

	/**
	 * Replace "Mystery Man" with "Bailey Mystery Person".
	 *
	 * "Mystery Man" is replaced here with "Bailey Mystery Person". This filter removes the "Mystery
	 * Man" from the list of avatars in lieu of "Bailey Mystery Person". Since the array key for this array is what is
	 * saved to the db and subsequently used for requests for the logo, saving the path to the logo is the easiest
	 * way to handle this.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $avatar_defaults    The default avatar strings.
	 * @return array                        The modified avatar strings.
	 */
	function avatar_defaults( $avatar_defaults ) {
		// Remove the current mystery man
		unset( $avatar_defaults['mystery'] );

		// Define Bailey Mystery Person
		$rr[ get_template_directory_uri() . '/images/' . $this->avatar_filename ] = __( 'Bailey Mystery Person', 'bailey' );

		// Merge with the other avatars
		$avatar_defaults = array_merge( $rr, $avatar_defaults );
		return $avatar_defaults;
	}

	/**
	 * Potentially overwrite the avatar option when pulled from the database.
	 *
	 * If there is no value set in the database, default to the "Bailey Mystery Person". If the "Mystery Man" is chosen,
	 * display the "Bailey Mystery Person" instead. Because "get_option" is called prior as part of "add_option", be sure
	 * to exit this function when saving is happening.
	 *
	 * @since  1.0.0.
	 *
	 * @param  mixed     $value    The current option value.
	 * @return string              The altered (or unaltered) option value.
	 */
	function default_option_avatar_default( $value ) {
		// Avoid filtering when the avatar is updated
		if ( isset( $_POST['_wpnonce'] ) )
			return $value;

		/**
		 * If there is no value set (i.e., option not in database), set to "Bailey Mystery Person". Additionally, replace
		 * "Mystery Man" with "Bailey Mystery Person" if necessary.
		 */
		if ( empty( $value ) || 'mystery' === $value ) {
			return get_template_directory_uri() . '/images/' . $this->avatar_filename;
		} else
			return $value;
	}
}
endif;

/**
 * Return the one Bailey_Avatar object.
 *
 * @since  1.0.0.
 *
 * @return Bailey_Avatar
 */
function bailey_get_avatar() {
	return Bailey_Avatar::instance();
}

add_action( 'init', 'bailey_get_avatar' );