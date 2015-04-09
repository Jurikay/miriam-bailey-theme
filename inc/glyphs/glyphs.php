<?php

class Glyphs_Admin {
	/**
	 * The version of Glyphs.
	 *
	 * @since 1.0.2.
	 *
	 * @var    string    The version of Glyphs.
	 */
	public static $version = '1.0.2';

	/**
	 * The relative path of the glyphs directory.
	 *
	 * @since 1.0.1.
	 *
	 * @var   string    The glyphs path.
	 */
	private static $path;

	/**
	 * The one instance of Glyphs_Admin.
	 *
	 * @since  1.0.0.
	 *
	 * @var Glyphs_Admin
	 */
	private static $instance;

	/**
	 * Instantiate or return the one Glyphs_Admin instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return Glyphs_Admin
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
	 * @return Glyphs_Admin
	 */
	public function __construct() {
		self::$path = $this->get_path();

		add_action( 'admin_init', array( $this, 'load_languages' ) );

		add_action( 'admin_menu', array( $this, 'add_theme_page' ) );
		add_action( 'admin_init', array( $this, 'add_settings' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'wp_ajax_glyphs_hide_notice', array( $this, 'hide_notice' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ), 21 );

		foreach ( array( 'post.php', 'post-new.php' ) as $hook ) {
			add_action( "admin_head-$hook", array( $this, 'kit_id_js' ) );
		}
		add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugin' ) );
	}

	/**
	 * Determine the path of the Glyphs directory relative to the theme directory.
	 *
	 * @since 1.0.2.
	 *
	 * @return mixed|void
	 */
	private function get_path() {
		$component_dir = dirname( __FILE__ );
		$template_dir = get_template_directory();
		if ( false === strpos( $component_dir, $template_dir ) ) {
			// Paths don't match. Check for symlinking.
			$template_dir = readlink( $template_dir );
		}

		if ( false !== strpos( $component_dir, $template_dir ) ) {
			$path = preg_replace( '#' . $template_dir . '#', '', $component_dir );
		} else {
			// Path cannot be determined. Use fallback.
			$path = apply_filters( 'glyphs_path_fallback', 'inc/glyphs' );
		}

		// Trim leading slash
		if ( 0 === strpos( $path, '/' ) ) {
			$path = substr( $path, 1 );
		}

		return $path;
	}

	/**
	 * Load the translation files.
	 *
	 * @since 1.0.0.
	 *
	 * @return void
	 */
	public function load_languages() {
		$domain = 'glyphs';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		$path   = WP_LANG_DIR . '/themes/';
		$mofile = $domain . '-' . $locale . '.mo';

		// Attempt to load from within the languages directory
		if ( true !== load_textdomain( $domain, $path . $mofile ) ) {
			// As a fallback, load from the plugin
			$path = trailingslashit( get_template_directory() ) . trailingslashit( self::$path ) . 'languages/';
			load_textdomain( $domain, $path . $mofile );
		}
	}

	/**
	 * Add a new admin page under the Appearance tab.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function add_theme_page() {
		add_theme_page(
			__( 'Font Authorization', 'glyphs' ),
			__( 'Font Authorization', 'glyphs' ),
			'manage_options',
			'glyphs_auth_page',
			array( $this, 'render_glyphs_auth_page' )
		);
	}

	/**
	 * Render the theme page wrapper.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function render_glyphs_auth_page() {
		?>
		<style type="text/css">
			.form-table tr:nth-child(3) {
				display: none;
			}
			.glyphs-authed {
				color: green;
			}
		</style>
		<div class="wrap">
			<h2><?php _e( 'Font Authorization', 'glyphs' ); ?></h2>

			<?php if ( $msg = get_transient( 'glyphs-auth-' . get_current_user_id() ) ) : ?>
			<?php $status = ( isset( $msg['status'] ) ) ? $msg['status'] : ''; ?>
			<div id="setting-error-settings_updated" class="<?php echo esc_attr( $status ); ?> settings-error">
				<?php if ( isset( $msg['message'] ) ) : ?>
				<p>
					<?php echo esc_html( $msg['message'] ); ?>
				</p>
				<?php endif; ?>
				<?php if ( isset( $msg['details'] ) ) : ?>
					<ul>
						<?php foreach ( $msg['details'] as $detail ) : ?>
						<li>
							&nbsp;- <?php echo esc_html( $detail->msg ); ?> (<?php echo esc_html( $detail->code ); ?>)
						</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<form action="options.php" method="post">
				<?php settings_fields( 'glyphs_dummy' ); ?>
				<?php do_settings_sections( 'glyphs_auth_page' ); ?>
					<p class="submit">
						<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Authorize Site' ); ?>">
					</p>
					</form>
				</div>
			</form>
		</div>

		<script type="text/javascript">
			(function ($) {
				var $toggles = $('.glyphs-toggle-forms'),
					$authForm = $('.form-table tr:nth-child(1), .form-table tr:nth-child(2)'),
					$emailInput = $('.form-table tr:nth-child(1) input'),
					$advancedForm = $('.form-table tr:nth-child(3)'),
					$kitIDInput = $('input', $advancedForm),
					$reAuth = $('.glyphs-re-auth'),
					$formWrapper = $('.glyphs-form-wrapper');

				$toggles.on('click', function() {
					$authForm.toggle( 0, function() {
						if($authForm.is(':visible')){
							$emailInput.focus();
						}
					});

					$advancedForm.toggle(0, function() {
						if($advancedForm.is(':visible')){
							$kitIDInput.focus();
						}
					});
				});

				$reAuth.on('click', function(evt) {
					evt.preventDefault();
					$formWrapper.show();
				})
			})(jQuery);
		</script>
		<?php delete_transient( 'glyphs-auth-' . get_current_user_id() ); ?>
	<?php
	}

	/**
	 * Register the settings, sections, and field
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function add_settings() {
		// Register an option to flag whether or not the site is authorized
		register_setting(
			'glyphs_dummy',
			'glyphs_dummy',
			array( $this, 'authorize_theme_and_domain' )
		);

		// Add the settings section to hold the interface
		add_settings_section(
			'glyphs_settings',
			'',
			array( $this, 'render_input_section' ),
			'glyphs_auth_page'
		);

		// Add the username field
		add_settings_field(
			'glyphs_render_email',
			__( 'E-mail Address', 'glyphs' ),
			array( $this, 'render_email' ),
			'glyphs_auth_page',
			'glyphs_settings'
		);

		// Add the password field
		add_settings_field(
			'glyphs_render_password',
			__( 'Password', 'glyphs' ),
			array( $this, 'render_password' ),
			'glyphs_auth_page',
			'glyphs_settings'
		);

		// Add the typekit ID override field
		add_settings_field(
			'glyphs_render_tk_kit_id',
			__( 'Typekit Kit ID', 'glyphs' ),
			array( $this, 'render_tk_kit_id' ),
			'glyphs_auth_page',
			'glyphs_settings'
		);
	}

	/**
	 * Render a heading for the section.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function render_input_section() {
		$kit_id = get_theme_mod( 'typekit-id', false );
		if ( false !== $kit_id ) {
			// Show kit ID
			printf(
				'<h3 class="title glyphs-authed">%1$s <strong>%2$s</strong>.</h3>',
				__( 'You are currently using Typekit Kit ID,', 'glyphs' ),
				$kit_id
			);
			// Show available fonts
			$fonts = glyphs_get_typekit_fonts( $kit_id, true );
			if ( empty( $fonts ) ) {
				printf(
					'<p>%s</p>',
					__( 'This kit does not contain any valid fonts.', 'glyphs' )
				);
			} else {
				printf(
					'<p>%s</p>',
					__( 'This kit contains the following fonts:', 'glyphs' )
				);
				echo '<ul class="ul-disc">';
				foreach ( $fonts as $font ) {
					echo '<li>' . $font['label'] . '</li>';
				}
				echo '</ul>';
			}
			// Show help link
			printf(
				'<p>' . __( 'Problems?', 'glyphs' ) . ' %s.</p>',
				sprintf(
					'<a href="#" class="glyphs-re-auth">%s</a>',
					__( 'Try authorizing again', 'glyphs' )
				)
			);
		} else {
			_e(
				'<p>Please authorize your theme to display Typekit fonts with your Theme Foundry sign in credentials. If you have trouble logging in, please <a href="https://thethemefoundry.com/support/">contact us in the Help Center</a>. The authorization process will send your current domain to Typekit to whitelist this site.</p>',
				'glyphs'
			);
		}
	?>
		<div class="glyphs-form-wrapper"<?php if ( false !== get_theme_mod( 'typekit-id' ) ) : ?> style="display:none;"<?php endif; ?>>
			<input type="radio" id="glyph-auth" value="auth" class="glyphs-toggle-forms" name="glyphs-toggle-forms" checked />
			<label for="glyph-auth">
				<?php _e( 'Authorize', 'glyphs' ); ?>
			</label>
			&nbsp;
			<input type="radio" id="glyph-advanced" value="advanced" class="glyphs-toggle-forms" name="glyphs-toggle-forms" />
			<label for="glyph-advanced">
				<?php _e( 'Use Typekit Kit ID (advanced)', 'glyphs' ); ?>
			</label>
	<?php
	}

	/**
	 * Render the email input.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function render_email() {
		echo '<input type="text" name="glyphs-email" class="regular-text" value="" autofocus />';
	}

	/**
	 * Render the password input.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function render_password() {
		echo '<input type="password" name="glyphs-password" class="regular-text" value="" />';
	}

	/**
	 * Render the typekit ID input.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function render_tk_kit_id() {
		echo '<input type="text" name="glyphs-tk-kit-id" class="regular-text" value="" />';
	}

	/**
	 * Authorize the theme and save the auth status on submission.
	 *
	 * @since  1.0.0.
	 *
	 * @param  mixed    $value    The input value.
	 * @return bool               The auth status.
	 */
	public function authorize_theme_and_domain( $value ) {
		// If the Kit ID override is set, use it
		if ( isset( $_POST['glyphs-tk-kit-id'] ) && ! empty( $_POST['glyphs-tk-kit-id'] ) ) {
			$typekit_id       = $_POST['glyphs-tk-kit-id'];
			$clean_typekit_id = preg_replace( '/[^0-9a-z]+/', '', $_POST['glyphs-tk-kit-id'] );

			if ( $typekit_id === $clean_typekit_id ) {
				set_theme_mod( 'typekit-id', $clean_typekit_id );

				$msg = array(
					'status'  => 'updated',
					'message' => __( 'Your theme is updated to use your custom Typekit Kit ID to display fonts.', 'glyphs' ),
				);

				$this->add_msg( $msg );
				return 1;
			} else {
				$new_error       = new stdClass();
				$new_error->code = '501';
				$new_error->msg  = __( 'The Typekit ID entered is not valid', 'glyphs' );
				$errors[]        = $new_error;

				$msg = array(
					'status'  => 'error',
					'message' => __( 'There was an error adding your Typekit ID.', 'glyphs' ),
					'details' => $errors,
				);

				$this->add_msg( $msg );
				return 0;
			}
		}

		if ( ! isset( $_POST['glyphs-email'] ) || ! isset( $_POST['glyphs-password'] ) ) {
			return 0;
		}

		// Grab the auth details
		$email    = $_POST['glyphs-email'];
		$password = $_POST['glyphs-password'];

		// Set the endpoint
		$auth_endpoint = 'https://thethemefoundry.com/tk/auth';

		// Sometimes HTTP requests need a little extra time locally
		$timeout = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ) ? 20 : 5;

		// Make the request
		$response = wp_remote_post(
			$auth_endpoint,
			array(
				'headers' => array(
					'Content-Type' => 'application/x-www-form-urlencoded',
				),
				'body'    => array(
					'email'    => $email,
					'password' => $password,
					'domain'   => str_replace( array( 'http://', 'https://' ), array( '', '' ), get_option( 'siteurl' ) ),
					'theme'    => get_template(), // Always send the parent theme
				),
				'timeout' => $timeout,
			)
		);

		$response_code = (int) wp_remote_retrieve_response_code( $response );
		$response_body = json_decode( wp_remote_retrieve_body( $response ) );

		if ( 200 === $response_code ) {
			$msg = array(
				'status'  => 'updated',
				'message' => __( 'Your theme is authorized to display Typekit fonts! It may take up to 5 minutes for the updated fonts to appear.', 'glyphs' ),
			);

			// Record the Typekit ID
			$typekit_id = $response_body->data->typekit_id;

			// The ID needs to be added as the parent theme mod since that is where it is read from
			if ( get_template() === get_stylesheet() ) {
				set_theme_mod( 'typekit-id', $typekit_id );
			} else {
				$parent_theme = get_template();
				$parent_mods = get_option( $parent_theme, false );

				if ( false !== $parent_mods ) {
					$parent_mods['typekit-id'] = $typekit_id;
				} else {
					$parent_mods = array(
						'typekit-id' => $typekit_id
					);
				}

				update_option( 'theme_mods_' . $parent_theme, $parent_mods );
			}

			$this->add_msg( $msg );
			return 1;
		} else {
			if ( is_wp_error( $response ) ) {
				$errors = array();
				foreach ( $response as $error ) {
					foreach ( $error as $code => $data ) {
						$new_error       = new stdClass();
						$new_error->code = $code;
						$new_error->msg  = $data[0];
						$errors[]        = $new_error;
					}
				}
			} else {
				$errors = (array) $response_body->errors;
			}

			$msg = array(
				'status'  => 'error',
				'message' => __( 'There was an error authorizing your theme to display Typekit fonts.', 'glyphs' ),
				'details' => $errors,
			);

			$this->add_msg( $msg );
			return 0;
		}
	}

	/**
	 * Save a message to display later.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $message    Notice details.
	 * @return void
	 */
	public function add_msg( $message ) {
		set_transient( 'glyphs-auth-' . get_current_user_id(), $message, 60 );
	}

	/**
	 * Display a notice to inform the user to authenticate the theme.
	 *
	 * @since  1.0.0.
	 *
	 * @return void.
	 */
	public function admin_notices() {
		global $pagenow;

		// Whitelist pages to receive the notice
		$display_on = array(
			'index.php',
			'themes.php',
			'options-general.php',
			'theme-editor.php'
		);

		// Only display if page is whitelisted and the theme is authenticated already
		if ( ! in_array( $pagenow, $display_on ) || false !== get_theme_mod( 'typekit-id', false ) || 1 === (int) get_theme_mod( 'hide-notice' ) || ( isset( $_GET['page'] ) && 'glyphs_auth_page' === $_GET['page'] ) ) {
			return;
		}
	?>
		<div id="message" class="error">
			<p>
				<?php
				printf(
					__(
						'Please <a href="%1$s" title="Typekit Authentication">authenticate your theme</a> to display Typekit premium fonts. <a href="#" data-nonce="%2$s" class="%3$s">hide</a>',
						'glyphs'
					),
					admin_url( 'themes.php?page=glyphs_auth_page' ),
					wp_create_nonce( 'hide-notice' ),
					'glyphs-hide-notice'
				);
				?>
			</p>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$('.error').on('click', '.glyphs-hide-notice', function (evt) {
					evt.preventDefault();

					var $target = $(evt.target),
						nonce = $target.attr('data-nonce'),
						$parent = $target.parents('.error');

					$.post(
						ajaxurl,
						{
							action: 'glyphs_hide_notice',
							nonce : nonce
						}
					).done(function (data) {
							if (1 === parseInt(data, 10)) {
								$parent.fadeOut('slow');
							}
						});
				});
			});
		</script>
	<?php
	}

	/**
	 * Callback for hiding the auth notice.
	 *
	 * @since 1.0.0.
	 */
	public function hide_notice() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'hide-notice' ) ) {
			return;
		}

		// Set flag to no longer show the notice
		set_theme_mod( 'hide-notice', 1 );

		// Return a success response.
		echo 1;
		wp_die();
	}

	/**
	 * Add JS for Typekit integration if necessary.
	 *
	 * @since 1.0.0.
	 */
	public function load_assets() {
		global $pagenow;

		$unbox_exists  = function_exists( 'unbox_init' );
		$tk_authed     = ( false !== get_theme_mod( 'typekit-id', false ) );
		$notice_hidden = ( 1 === (int) get_theme_mod( 'hide-notice' ) );

		if ( ! $unbox_exists || $tk_authed || $notice_hidden ) {
			return;
		}

		if ( 'themes.php' === $pagenow && isset( $_GET['activated'] ) && true === (bool) $_GET['activated'] ) {
			// Add the script
			wp_enqueue_script(
				'glyphs-redirect',
				trailingslashit( get_template_directory_uri() ) . trailingslashit( self::$path ) . 'js/glyphs.js',
				array(
					'jquery',
					'unbox-script'
				),
				'',
				true
			);

			// Send the URL to the script
			wp_localize_script(
				'glyphs-redirect',
				'glyphsData',
				array(
					'authUrl' => esc_url( admin_url( 'themes.php?page=glyphs_auth_page' ) ),
				)
			);
		}
	}

	/**
	 * Output the kit ID in the admin head for scripts to use.
	 *
	 * @since 1.0.1.
	 */
	public function kit_id_js() {
		if ( false !== $kit_id = get_theme_mod( 'typekit-id', false ) ) : ?>
<script type='text/javascript'>
/* <![CDATA[ */
var GlyphsFontKit = '<?php echo esc_js( $kit_id ); ?>';
/* ]]> */
</script>
	<?php
			// Load the Typekit script so it caches in the browser
			wp_register_script(
				'glyphs-typekit-script',
				'//use.typekit.net/' . $kit_id . '.js'
			);
			wp_print_scripts( array( 'glyphs-typekit-script' ) );
		endif;
	}

	/**
	 * Add a TinyMCE plugin to load in Typekit fonts.
	 *
	 * @since 1.0.1.
	 *
	 * @param $plugin_array
	 * @return mixed
	 */
	public function mce_external_plugin( $plugin_array ) {
		if ( false !== $kit_id = get_theme_mod( 'typekit-id', false ) ) {
			$plugin_array['glyphsTypekit'] = trailingslashit( get_template_directory_uri() ) . trailingslashit( self::$path ) . 'js/glyphs-typekit-tinymce-plugin.js';
		}
		return $plugin_array;
	}
}

Glyphs_Admin::instance();