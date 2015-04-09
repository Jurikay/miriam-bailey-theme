<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_customizer_init' ) ) :
/**
 * Load the customizer files and enqueue scripts
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_customizer_init() {
	$path = get_template_directory() . '/inc/customizer/';

	// Always load
	require_once( $path . 'defaults.php' );
	if ( ! bailey_is_wpcom() ) {
		require_once( $path . 'css.php' );
		require_once( $path . 'display.php' );
		require_once( $path . 'logo.php' );
	}

	// Hook up functions
	add_action( 'customize_register', 'bailey_customizer_add_sections' );
	add_action( 'customize_controls_print_styles', 'bailey_customizer_admin_styles' );
	add_action( 'customize_preview_init', 'bailey_add_customizations' );
}
endif;

add_action( 'after_setup_theme', 'bailey_customizer_init' );

if ( ! function_exists( 'bailey_customizer_add_sections' ) ) :
/**
 * Add sections and controls to the customizer.
 *
 * Hooked to 'customize_register' via bailey_customizer_init().
 *
 * @since  1.0.0.
 *
 * @param  WP_Customize_Manager    $wp_customize    Theme Customizer object.
 * @return void
 */
function bailey_customizer_add_sections( $wp_customize ) {
	$path         = get_template_directory() . '/inc/customizer/';
	$section_path = $path . 'sections/';

	// Get the custom controls
	require_once( $path . 'controls.php' );

	// Modifications for existing sections
	require_once( $section_path . 'background.php' );
	require_once( $section_path . 'navigation.php' );

	// List of new sections to add
	$sections = array(
		'logo'      => array( 'title' => __( 'Logo', 'bailey' ), 'path' => $section_path ),
		'font'      => array( 'title' => __( 'Fonts', 'bailey' ), 'path' => $section_path ),
		'color'     => array( 'title' => __( 'Colors', 'bailey' ), 'path' => $section_path ),
		'display'   => array( 'title' => __( 'Display', 'bailey' ), 'path' => $section_path ),
		'portfolio' => array( 'title' => __( 'Portfolio', 'bailey' ), 'path' => $section_path ),
		'footer'    => array( 'title' => __( 'Footer', 'bailey' ), 'path' => $section_path ),
		'social'    => array( 'title' => __( 'Social Profiles &amp; RSS', 'bailey' ), 'path' => $section_path ),
	);
	$sections = apply_filters( 'bailey_customizer_sections', $sections );

	// Remove sections for WP.com
	if ( bailey_is_wpcom() ) {
		unset( $sections['logo'] );
		unset( $sections['font'] );
		unset( $sections['color'] );
	}

	// Priority for first section
	$priority = new Bailey_Prioritizer( 200, 10 );

	// Add the "Theme" section for WP.com
	if ( bailey_is_wpcom() ) {
		$wp_customize->add_section(
			'bailey_theme',
			array(
				'title'    => __( 'Theme Options', 'bailey' ),
				'priority' => $priority->add(),
			)
		);
	}

	// Add and populate each section, if it exists
	foreach ( $sections as $section => $data ) {
		$file = trailingslashit( $data[ 'path' ] ) . $section . '.php';
		if ( file_exists( $file ) ) {
			// First load the file
			require_once( $file );

			// Then add the section
			$section_callback = 'bailey_customizer_';
			$section_callback .= ( strpos( $section, '-' ) ) ? str_replace( '-', '_', $section ) : $section;
			if ( function_exists( $section_callback ) ) {
				$section_id = 'bailey_' . esc_attr( $section );

				// Only add separate sections for self-hosted sites
				if ( ! bailey_is_wpcom() ) {
					// Sanitize the section title
					if ( ! isset( $data[ 'title' ] ) || ! $data[ 'title' ] ) {
						$data[ 'title' ] = ucfirst( esc_attr( $section ) );
					}

					// Add section
					$wp_customize->add_section(
						$section_id,
						array(
							'title'    => $data[ 'title' ],
							'priority' => $priority->add(),
						)
					);
				}

				// Callback to populate the section
				call_user_func_array(
					$section_callback,
					array(
						$wp_customize,
						$section_id
					)
				);
			}
		}
	}
}
endif;

if ( ! function_exists( 'bailey_customizer_admin_styles' ) ) :
/**
 * Styles for our Customizer sections and controls. Prints in the <head>
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_customizer_admin_styles() { ?>
	<style type="text/css">
		.customize-control.customize-control-heading {
			margin-top: 6px;
			margin-bottom: -2px;
		}
		.customize-control.customize-control-line {
			margin-top: 10px;
			margin-bottom: 6px;
		}
	</style>
<?php }
endif;
