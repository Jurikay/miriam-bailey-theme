<?php

if ( ! function_exists( 'glyphs_font_setup' ) ) :
/**
 * Print scripts for Typekit fonts
 *
 * These scripts should be printed at the top of wp_head before
 * other scripts and stylesheets.
 *
 * @since 1.0.
 */
function glyphs_font_setup() {
	if ( ! wp_script_is( 'glyphs-font-loader', 'registered' ) ) {
		// Determine the path of the font directory relative to the theme directory
		$path = get_template_directory();
		if ( is_link( $path ) ) {
			$path = readlink( $path );
		}
		$path = trailingslashit( get_template_directory_uri() ) . ltrim( preg_replace( '#' . $path . '#', '', dirname( __FILE__ ) ), '/' );

		$kit_id = apply_filters( 'glyphs_typekit_kit_id', get_theme_mod( 'typekit-id', false ) );
		if ( false === $kit_id ) {
			return;
		}

		if ( current_theme_supports( 'glyphs-async-load' ) ) {
			// Async loader
			wp_register_script(
				'glyphs-font-loader',
				$path . '/js/glyphs-typekit-async-loader.js'
			);

			// Add kit ID to async loader
			wp_localize_script(
				'glyphs-font-loader',
				'GlyphsFontKit',
				$kit_id
			);
		} else {
			// Typekit external script
			wp_register_script(
				'glyphs-typekit-script',
				'//use.typekit.net/' . $kit_id . '.js'
			);

			// Default loader
			wp_register_script(
				'glyphs-font-loader',
				$path . '/js/glyphs-typekit-loader.js',
				array( 'glyphs-typekit-script' )
			);
		}
	}

	wp_print_scripts( array( 'glyphs-font-loader' ) );
}
endif;

add_action( 'wp_head', 'glyphs_font_setup', 2 );
