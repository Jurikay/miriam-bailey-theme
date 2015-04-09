<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_customizer_font' ) ) :
/**
 * Configure settings and controls for the Colors section.
 *
 * @since  1.0.0.
 *
 * @param  object    $wp_customize    The global customizer object.
 * @param  string    $section         The section name.
 * @return void
 */
function bailey_customizer_font( $wp_customize, $section ) {
	$priority       = new Bailey_Prioritizer();
	$control_prefix = 'bailey_';
	$setting_prefix = str_replace( $control_prefix, '', $section );
	$section = ( bailey_is_wpcom() ) ? 'bailey_theme' : $section;

	$font_choices = array();
	if ( function_exists( 'glyphs_get_typekit_fonts' ) ) {
		$font_choices = glyphs_get_typekit_fonts();
	}

	if ( empty( $font_choices ) ) {
		// No Fonts error message
		$setting_id = $setting_prefix . '-no-fonts';
		$wp_customize->add_control(
			new Bailey_Customize_Misc_Control(
				$wp_customize,
				$control_prefix . $setting_id,
				array(
					'section'     => $section,
					'type'        => 'text',
					'description' => sprintf(
						__( 'It looks like you haven\'t authorized your theme to display Typekit fonts yet. %s', 'bailey' ),
						sprintf(
							'<a href="%1$s">%2$s</a>',
							admin_url( 'themes.php?page=glyphs_auth_page' ),
							__( 'Authorize now.', 'bailey' )
						)
					),
					'priority'    => $priority->add()
				)
			)
		);
	} else {
		// Main Font
		$setting_id = $setting_prefix . '-main';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => bailey_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'bailey_sanitize_choice',
			)
		);
		$wp_customize->add_control(
			$control_prefix . $setting_id,
			array(
				'settings' => $setting_id,
				'section'  => $section,
				'label'    => __( 'Main Font', 'bailey' ),
				'type'     => 'select',
				'choices'  => bailey_get_choices( $setting_id ),
				'priority' => $priority->add()
			)
		);

		// Accent Font
		$setting_id = $setting_prefix . '-accent';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => bailey_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'bailey_sanitize_choice',
			)
		);
		$wp_customize->add_control(
			$control_prefix . $setting_id,
			array(
				'settings' => $setting_id,
				'section'  => $section,
				'label'    => __( 'Accent Font', 'bailey' ),
				'type'     => 'select',
				'choices'  => bailey_get_choices( $setting_id ),
				'priority' => $priority->add()
			)
		);
	}
}
endif;