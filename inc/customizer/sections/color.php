<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_customizer_color' ) ) :
/**
 * Configure settings and controls for the Colors section.
 *
 * @since  1.0.0.
 *
 * @param  object    $wp_customize    The global customizer object.
 * @param  string    $section         The section name.
 * @return void
 */
function bailey_customizer_color( $wp_customize, $section ) {
	$priority       = new Bailey_Prioritizer( 200, 5 );
	$control_prefix = 'bailey_';
	$setting_prefix = str_replace( $control_prefix, '', $section );
	$section = ( bailey_is_wpcom() ) ? 'bailey_theme' : $section;

	// Accent Color
	$setting_id = $setting_prefix . '-accent';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'maybe_hash_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			$control_prefix . $setting_id,
			array(
				'settings' => $setting_id,
				'section'  => $section,
				'label'    => __( 'Accent Color', 'bailey' ),
				'priority' => $priority->add()
			)
		)
	);

	// Detail Color 1
	$setting_id = $setting_prefix . '-detail1';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'maybe_hash_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			$control_prefix . $setting_id,
			array(
				'settings' => $setting_id,
				'section'  => $section,
				'label'    => __( 'Detail Color 1', 'bailey' ),
				'priority' => $priority->add()
			)
		)
	);

	// Detail Color 2
	$setting_id = $setting_prefix . '-detail2';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'maybe_hash_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			$control_prefix . $setting_id,
			array(
				'settings' => $setting_id,
				'section'  => $section,
				'label'    => __( 'Detail Color 2', 'bailey' ),
				'priority' => $priority->add()
			)
		)
	);

	// Main Color
	$setting_id = $setting_prefix . '-main';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'maybe_hash_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			$control_prefix . $setting_id,
			array(
				'settings' => $setting_id,
				'section'  => $section,
				'label'    => __( 'Main Color', 'bailey' ),
				'priority' => $priority->add()
			)
		)
	);
}
endif;