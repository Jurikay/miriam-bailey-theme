<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_option_defaults' ) ) :
/**
 * The big array of global option defaults.
 *
 * @since  1.0.0
 *
 * @return array    The default values for all theme options.
 */
function bailey_option_defaults() {
	$defaults = array(
		// Background
		'background_color'             => 'ffffff',
		'background_image'             => '',
		'background_repeat'            => 'repeat',
		'background_position_x'        => 'left',
		'background_attachment'        => 'scroll',

		// Navigation
		'navigation-label'             => __( 'menu', 'bailey' ),

		// Logo
		'logo-regular'                 => '',
		'logo-retina'                  => '',
		'logo-favicon'                 => '',
		'logo-apple-touch'             => '',

		// Fonts
		'font-main'                    => 'franklin-gothic-urw',
		'font-accent'                  => 'kepler-std',

		// Colors
		'color-accent'                 => '#0036ff',
		'color-detail1'                => '#f3f3f3',
		'color-detail2'                => '#a0a0a0',
		'color-main'                   => '#1b1b1c',

		// Display
		'display-sidebar-blog'         => 1,
		'display-sidebar-archive'      => 1,
		'display-sidebar-search'       => 1,
		'display-sidebar-post'         => 1,
		'display-sidebar-page'         => 1,
		'display-sticky-label'         => __( 'Featured', 'bailey' ),

		// Portfolio
		'portfolio-show-section-title' => 0,
		'portfolio-show-taxonomies'    => 1,
		'portfolio-archive-columns'    => 3,
		'portfolio-archive-captions'   => 'frame',
		'portfolio-archive-images'     => 'none',

		// Footer
		'footer-widgets-blog'          => 1,
		'footer-widgets-archive'       => 1,
		'footer-widgets-search'        => 1,
		'footer-widgets-post'          => 0,
		'footer-widgets-page'          => 0,
		'footer-widgets-portfolio'     => 1,
		'footer-widgets-project  '     => 0,
		'footer-widget-areas'          => 3,
		'footer-text'                  => '',

		// Social
		'social-facebook'              => '',
		'social-twitter'               => '',
		'social-google-plus'           => '',
		'social-linkedin'              => '',
		'social-instagram'             => '',
		'social-flickr'                => '',
		'social-youtube'               => '',
		'social-vimeo'                 => '',
		'social-pinterest'             => '',
		'social-fivehpx'               => '',
		'social-behance'               => '',
		'social-dribbble'              => '',
		'social-deviantart'            => '',
		'social-smugmug'               => '',
		'social-email'                 => '',
		'social-hide-rss'              => ( bailey_is_wpcom() ) ? 1 : 0,
		'social-custom-rss'            => '',
	);

	return apply_filters( 'bailey_option_defaults', $defaults );
}
endif;

if ( ! function_exists( 'bailey_get_default' ) ) :
/**
 * Return a particular global option default.
 *
 * @since  1.0.0.
 *
 * @param  string    $option    The key of the option to return.
 * @return mixed                Default value if found; false if not found.
 */
function bailey_get_default( $option ) {
	$defaults = bailey_option_defaults();
	return ( isset( $defaults[ $option ] ) ) ? $defaults[ $option ] : false;
}
endif;


if ( ! function_exists( 'bailey_get_choices' ) ) :
/**
 * Return the available choices for a given setting
 *
 * @since  1.0.0.
 *
 * @param  string|object    $setting    The setting to get options for.
 * @return array                        The options for the setting.
 */
function bailey_get_choices( $setting ) {
	if ( is_object( $setting ) ) {
		$setting = $setting->id;
	}

	$choices = array( 0 );

	switch ( $setting ) {
		case 'portfolio-archive-columns' :
		case 'footer-widget-areas' :
			$choices = array(
				1 => __( '1', 'bailey' ),
				2 => __( '2', 'bailey' ),
				3 => __( '3', 'bailey' ),
			);
			break;
		case 'portfolio-archive-captions' :
			$choices = array(
				'frame' => __( 'Frame', 'bailey' ),
				'solid' => __( 'Solid', 'bailey' ),
				'none' => __( 'None', 'bailey' ),
			);
			break;
		case 'portfolio-archive-images' :
			$choices = array(
				'landscape' => __( 'Landscape', 'bailey' ),
				'portrait' => __( 'Portrait', 'bailey' ),
				'square' => __( 'Square', 'bailey' ),
				'none' => __( 'None', 'bailey' ),
			);
			break;
		case 'font-main' :
		case 'font-accent' :
			if ( ! bailey_is_wpcom() && function_exists( 'glyphs_get_typekit_fonts' ) ) {
				$font_choices = glyphs_get_typekit_fonts();
				$choices = array();

				if ( is_array( $font_choices ) ) {
					foreach ( $font_choices as $value => $info ) {
						$choices[$value] = $info['label'];
					}
				}
			}
			break;
	}

	return apply_filters( 'bailey_get_choices', $choices, $setting );
}
endif;

if ( ! function_exists( 'bailey_sanitize_choice' ) ) :
/**
 * Sanitize a value from a list of allowed values.
 *
 * @since 1.0.0.
 *
 * @param  mixed    $value      The value to sanitize.
 * @param  mixed    $setting    The setting for which the sanitizing is occurring.
 * @return mixed                The sanitized value.
 */
function bailey_sanitize_choice( $value, $setting ) {
	if ( is_object( $setting ) ) {
		$setting = $setting->id;
	}

	$choices         = bailey_get_choices( $setting );
	$allowed_choices = array_keys( $choices );

	if ( ! in_array( $value, $allowed_choices ) ) {
		$value = bailey_get_default( $setting );
	}

	return $value;
}
endif;