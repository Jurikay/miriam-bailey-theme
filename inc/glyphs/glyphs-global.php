<?php

if ( ! function_exists( 'glyphs_get_parent_typekit_id' ) ) :
/**
 * Get the Typekit ID from the parent theme mods.
 *
 * Since the Typekit ID is help in the parent theme's theme mods, look at that those theme mods to get the Typekit ID
 * for the child theme.
 *
 * @param  mixed    $value    The original value of the theme mod.
 * @return mixed              The modified value.
 */
function glyphs_get_parent_typekit_id( $value ) {
	$template   = get_template();
	$stylesheet = get_stylesheet();

	if ( $template !== $stylesheet && empty( $value ) ) {
		$parent_mods = get_option( 'theme_mods_' . $template );

		if ( ! empty( $parent_mods ) && isset( $parent_mods['typekit-id'] ) ) {
			$value = $parent_mods['typekit-id'];
		}
	}

	return $value;
}
endif;

add_filter( 'theme_mod_typekit-id', 'glyphs_get_parent_typekit_id' );

if ( ! function_exists( 'glyphs_get_typekit_fonts' ) ) :
/**
 * Get info about the fonts available in the current kit
 *
 * @param  string        $kit_id    The id of the kit.
 * @param  bool          $force     True to ignore cached info.
 * @return array|bool               An array of font information, or false if the kit ID is invalid.
 */
function glyphs_get_typekit_fonts( $kit_id = null, $force = false ) {
	if ( null === $kit_id ) {
		$kit_id = get_theme_mod( 'typekit-id', false );
	}

	if ( ! $kit_id ) {
		return false;
	}

	// Get cached font info
	$fonts = get_transient( 'glyphs-typekit-fonts' );

	if ( empty( $fonts ) || true === $force ) {
		$fonts = array();

		// Look up the font kit
		$response      = wp_remote_get( 'https://typekit.com/api/v1/json/kits/' . $kit_id . '/published' );
		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = json_decode( wp_remote_retrieve_body( $response ) );

		// If the font kit returns a valid response, parse the font info
		if ( 200 === (int) $response_code && is_object( $response_body ) && isset( $response_body->kit ) && isset( $response_body->kit->families ) && is_array( $response_body->kit->families ) ) {
			foreach ( $response_body->kit->families as $family ) {
				$fonts[ sanitize_title_with_dashes( $family->slug ) ] = array(
					'label' => wp_strip_all_tags( $family->name ),
					'stack' => ( isset( $family->css_stack ) ) ? wp_strip_all_tags( $family->css_stack ) : '',
				);
			}
		}

		// Cache the font info
		set_transient( 'glyphs-typekit-fonts', $fonts, DAY_IN_SECONDS );
	}

	return $fonts;
}
endif;
