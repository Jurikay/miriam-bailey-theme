<?php
/**
 * @package Bailey
 */

if ( ! class_exists( 'Bailey_PerPage' ) ) :
/**
 * Post meta-related functionality.
 *
 * @since 1.0.0.
 */
class Bailey_PerPage {
	/**
	 * @var string
	 */
	var $prefix = 'bailey_';

	/**
	 * @var string
	 */
	var $color_slug = 'colors';

	/**
	 * @var string
	 */
	var $sidebar_slug = 'sidebar';

	/**
	 * The one instance of Bailey_PerPage.
	 *
	 * @since 1.0.0.
	 *
	 * @var   Bailey_PerPage
	 */
	private static $instance;

	/**
	 * Instantiate or return the one Bailey_PerPage instance.
	 *
	 * @since  1.0.0.
	 *
	 * @return Bailey_PerPage
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Bootstrap the module
	 *
	 * @since  1.0.0.
	 *
	 * @return Bailey_PerPage
	 */
	public function __construct() {}

	/**
	 * Hook into WordPress
	 *
	 * @since 1.0.0.
	 *
	 * @return void
	 */
	public function init() {
		// Enqueue
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// Add metaboxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		// Hook up save routines
		add_action( 'save_post', array( $this, 'colors_save' ) );
		add_action( 'save_post', array( $this, 'sidebar_save' ) );

		// Override applicable global settings
		if ( ! is_admin() ) {
			add_action( 'wp', array( $this, 'add_mod_filters' ) );
		}
		// Override global settings in TinyMCE
		add_action( 'admin_init', array( $this, 'add_tinymce_mod_filters' ), 5 );
	}

	/**
	 * Enqueue styles and scripts
	 *
	 * @since 1.0.0.
	 *
	 * @return void
	 */
	public function enqueue() {
		// Style
		wp_enqueue_style(
			$this->prefix . 'perpage',
			trailingslashit( get_template_directory_uri() ) . 'inc/admin-ui/css/per-page.css',
			array( 'wp-color-picker' ),
			BAILEY_VERSION
		);

		// Script
		wp_enqueue_script(
			$this->prefix . 'perpage',
			trailingslashit( get_template_directory_uri() ) . 'inc/admin-ui/js/per-page.js',
			array( 'wp-color-picker' ),
			BAILEY_VERSION,
			true
		);
	}

	/**
	 * Add meta boxes to relevant post edit screens
	 *
	 * @since 1.0.0.
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		// Colors metabox
		$colors_types = array( 'post', 'page', 'jetpack-portfolio' );
		foreach ( $colors_types as $type ) {
			add_meta_box(
				$this->prefix . $this->color_slug . '_metabox',
				__( 'Colors', 'bailey' ),
				array( $this, 'colors_callback' ),
				$type,
				'normal',
				'high'
			);
		}

		// Sidebar metabox
		$sidebar_types = array( 'post', 'page' );
		foreach ( $sidebar_types as $type ) {
			add_meta_box(
				$this->prefix . $this->sidebar_slug . '_metabox',
				__( 'Sidebar', 'bailey' ),
				array( $this, 'sidebar_callback' ),
				$type,
				'side',
				'default'
			);
		}
	}

	/**
	 * Render the Colors metabox
	 *
	 * @since 1.0.0.
	 *
	 * @param  object    $post    The current post object.
	 * @return void
	 */
	public function colors_callback( $post ) {
		global $typenow;

		// Help text
		$label = get_post_type_labels( get_post_type_object( $typenow ) )->singular_name;
		printf(
			'<p class="howto">%s</p>',
			sprintf(
				__( 'Optionally set colors for this %s only.', 'bailey' ),
				strtolower( $label )
			)
		);

		// Nonce
		wp_nonce_field( basename( __FILE__ ), $this->prefix . $this->color_slug . '_nonce' );

		// Color keys
		$color_keys = $this->get_color_keys();

		// Get values
		$meta_key = $this->prefix . $this->color_slug;
		$default_colors = array_fill_keys( $color_keys, '' );
		$stored_colors = get_post_meta( $post->ID, $meta_key, true );
		$colors = wp_parse_args( $stored_colors, $default_colors );

		// Labels
		$label_text = array(
			__( 'Background Color', 'bailey' ),
			__( 'Accent Color', 'bailey' ),
			__( 'Detail Color 1', 'bailey' ),
			__( 'Detail Color 2', 'bailey' ),
			__( 'Main Color', 'bailey' ),
		);
		$labels = array_combine( $color_keys, $label_text );

		// Controls wrapper
		echo '<div class="' . esc_attr( $this->prefix . $this->color_slug . '_wrapper' ) . '">';

		// Output the controls
		foreach ( $colors as $key => $color ) : ?>
			<label for="<?php echo esc_attr( $meta_key . "_$key" ); ?>">
				<span><?php echo esc_html( $labels[$key] ); ?></span>
				<input type="text" name="<?php echo esc_attr( $meta_key . "[$key]" ); ?>" id="<?php echo esc_attr( $meta_key . "_$key" ); ?>" class="<?php echo esc_attr( $this->prefix . $this->color_slug . '_picker' ); ?>" value="<?php echo maybe_hash_hex_color( $color ); ?>" />
			</label>
	<?php endforeach;

		// End controls wrapper
		echo '</div>';
	}

	/**
	 * Sanitize and save data submitted through the Colors metabox
	 *
	 * @since 1.0.0.
	 *
	 * @param  int    $post_id    The current post ID
	 * @return void
	 */
	public function colors_save( $post_id ) {
		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $this->prefix . $this->color_slug . '_nonce' ] ) && wp_verify_nonce( $_POST[ $this->prefix . $this->color_slug . '_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

		// Exits script depending on save status
		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
			return;
		}

		$meta_key = $this->prefix . $this->color_slug;

		if ( isset( $_POST[$meta_key] ) && ! empty( $_POST[$meta_key] ) ) {
			$colors = (array) $_POST[$meta_key];
			$sanitized_colors = array();

			$color_keys = $this->get_color_keys();
			foreach ( $colors as $key => $value ) {
				if ( in_array( $key, $color_keys ) ) {
					$sanitized_colors[$key] = maybe_hash_hex_color( $value );
				}
			}
			$sanitized_colors = array_filter( $sanitized_colors );

			if ( ! empty( $sanitized_colors ) ) {
				update_post_meta( $post_id, $meta_key, $sanitized_colors );
			} else {
				delete_post_meta( $post_id, $meta_key );
			}
		} else {
			delete_post_meta( $post_id, $meta_key );
		}
	}

	/**
	 * Return an array of color-related theme mod keys
	 *
	 * @since 1.0.0.
	 *
	 * @return array    The array of theme mod keys
	 */
	private function get_color_keys() {
		$keys = array(
			'background_color',
			'color-accent',
			'color-detail1',
			'color-detail2',
			'color-main',
		);
		return $keys;
	}

	/**
	 * Render the Sidebar metabox
	 *
	 * @since 1.0.0.
	 *
	 * @param  object    $post    The current post object.
	 * @return void
	 */
	public function sidebar_callback( $post ) {
		global $typenow;

		// Override
		$meta_key = $this->prefix . $this->sidebar_slug;
		$override = get_post_meta( $post->ID, $meta_key, true );
		$override_toggle = ( in_array( $override, array( 'true', 'false' ) ) ) ? true : false;

		// Global option
		$show_sidebar = ( (bool) get_theme_mod( 'display-sidebar-' . $typenow, bailey_get_default( 'display-sidebar-' . $typenow ) ) ) ? 'true' : 'false';
		if ( true === $override_toggle ) {
			$show_sidebar = $override;
		}

		// Label
		$label = get_post_type_labels( get_post_type_object( $typenow ) )->singular_name;

		// Nonce
		wp_nonce_field( basename( __FILE__ ), $this->prefix . $this->sidebar_slug . '_nonce' );
		?>
		<p class="howto">
			<?php
			printf(
				__( 'Check the first box to allow the global setting to be overridden for this %s.', 'bailey' ),
				strtolower( $label )
			);
			?>
		</p>
		<ul class="<?php echo esc_attr( $this->prefix . $this->sidebar_slug . '_wrapper' ); ?>">
			<li>
				<label for="<?php echo esc_attr( $meta_key . '_override' ); ?>">
					<input id="<?php echo esc_attr( $meta_key . '_override' ); ?>" name="<?php echo esc_attr( $meta_key . '_override' ); ?>" type="checkbox" title="<?php esc_attr_e( 'Override the global setting.', 'bailey' ); ?>" value="1" <?php checked( $override_toggle ); ?> />
				</label>
				<label class="selectit" for="<?php echo esc_attr( $meta_key ); ?>">
					<input id="<?php echo esc_attr( $meta_key ); ?>" name="<?php echo esc_attr( $meta_key ); ?>" type="checkbox" value="true" <?php checked( $show_sidebar, 'true' ); ?><?php if ( ! $override_toggle ) echo ' disabled="disabled"'; ?> />
					<?php _e( 'Show Main sidebar', 'bailey' ); ?>
				</label>
			</li>
		</ul>
	<?php
	}

	/**
	 * Sanitize and save data submitted through the Sidebar metabox
	 *
	 * @since 1.0.0.
	 *
	 * @param  int    $post_id    The current post ID
	 * @return void
	 */
	public function sidebar_save( $post_id ) {
		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $this->prefix . $this->sidebar_slug . '_nonce' ] ) && wp_verify_nonce( $_POST[ $this->prefix . $this->sidebar_slug . '_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

		// Exits script depending on save status
		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
			return;
		}

		$meta_key = $this->prefix . $this->sidebar_slug;
		$override_key = $meta_key . '_override';

		if ( isset( $_POST[$override_key] ) ) {
			if ( isset( $_POST[$meta_key] ) && 'true' == $_POST[$meta_key] ) {
				$value = 'true';
			} else {
				$value = 'false';
			}
			update_post_meta( $post_id, $meta_key, $value );
		} else {
			delete_post_meta( $post_id, $meta_key );
		}
	}

	/**
	 * Add the setting overrides for the current view.
	 *
	 * @since  1.0.0.
	 *
	 * @return void
	 */
	public function add_mod_filters() {
		global $post;
		$view = bailey_get_view();

		// Only do this for certain views.
		if ( is_object( $post ) && in_array( $view, array( 'post', 'page', 'portfolio', 'project' ) ) ) {
			// Colors
			$colors_meta_key = $this->prefix . $this->color_slug;
			if ( ( $settings = get_post_meta( $post->ID, $colors_meta_key, true ) ) && ! is_archive() ) {
				foreach ( (array) $settings as $mod_key => $value ) {
					add_filter( 'theme_mod_' . $mod_key, array( $this, 'filter_mod' ) );
				}
			}

			// Sidebar
			if ( ! in_array( $view, array( 'portfolio', 'project' ) ) ) {
				$sidebar_meta_key = $this->prefix . $this->sidebar_slug;
				if ( $override = get_post_meta( $post->ID, $sidebar_meta_key, true ) ) {
					$mod_key = 'display-sidebar-' . $view;
					add_filter( 'theme_mod_' . $mod_key, array( $this, 'filter_mod' ), 20 );
				}
			}
		}
	}

	/**
	 * Add the color setting overrides in the TinyMCE editor
	 *
	 * @since 1.0.0.
	 *
	 * @return void
	 */
	public function add_tinymce_mod_filters() {
		$post_id = 0;
		if ( isset( $_REQUEST['post_id'] ) ) {
			$post_id = absint( $_REQUEST['post_id'] );
		}
		if ( $post_id > 0 ) {
			// Colors
			$colors_meta_key = $this->prefix . $this->color_slug;
			if ( $settings = get_post_meta( $post_id, $colors_meta_key, true ) ) {
				foreach ( (array) $settings as $mod_key => $value ) {
					add_filter( 'theme_mod_' . $mod_key, array( $this, 'filter_mod' ), 20 );
				}
			}
		}
	}

	/**
	 * Filter a theme mod to override its value.
	 *
	 * @since  1.0.0.
	 *
	 * @param  mixed    $value    The original value of the theme mod
	 * @return mixed              The modified value of the theme mod
	 */
	public function filter_mod( $value ) {
		global $post;
		if ( isset( $post ) ) {
			$post_id = $post->ID;
		} else if ( isset( $_REQUEST['post_id'] ) ) {
			$post_id = absint( $_REQUEST['post_id'] );
		} else {
			return $value;
		}
		$view = bailey_get_view();

		// Reverse-engineer the setting key from the filter
		$filter = current_filter();
		$mod_key = str_replace( 'theme_mod_', '', $filter );

		// Color keys
		$color_keys = $this->get_color_keys();

		if ( in_array( $mod_key, $color_keys ) ) {
			// Color setting
			$colors_meta_key = $this->prefix . $this->color_slug;
			$colors = get_post_meta( $post_id, $colors_meta_key, true );
			$value = maybe_hash_hex_color( $colors[$mod_key] );
			// WordPress surprisingly doesn't account for hashes in the stored color value
			if ( 'background_color' === $mod_key ) {
				$value = str_replace( '#', '', $value );
			}
		} else if ( 'display-sidebar-' . $view === $mod_key ) {
			// Sidebar setting
			$sidebar_meta_key = $this->prefix . $this->sidebar_slug;
			$stored_value = get_post_meta( $post_id, $sidebar_meta_key, true );
			$value = ( 'true' === $stored_value ) ? 1 : 0;
		}

		return $value;
	}
}
endif;

if ( ! function_exists( 'bailey_get_perpage' ) ) :
/**
 * Instantiate or return the one Bailey_PerPage instance.
 *
 * @since  1.0.0.
 *
 * @return Bailey_PerPage
 */
function bailey_get_perpage() {
	return Bailey_PerPage::instance();
}
endif;

/**
 * Initialize the class
 */
bailey_get_perpage()->init();