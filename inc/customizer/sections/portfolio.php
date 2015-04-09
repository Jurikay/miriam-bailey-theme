<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_customizer_portfolio' ) ) :
/**
 * Configure settings and controls for the Portfolio section
 *
 * @since  1.0.0.
 *
 * @param  object    $wp_customize    The global customizer object.
 * @param  string    $section         The section name.
 * @return void
 */
function bailey_customizer_portfolio( $wp_customize, $section ) {
	$priority = new Bailey_Prioritizer( 400, 5 );
	$control_prefix = 'bailey_';
	$setting_prefix = str_replace( $control_prefix, '', $section );
	$section = ( bailey_is_wpcom() ) ? 'bailey_theme' : $section;

	// Portfolio display heading
	$setting_id = $setting_prefix . '-heading-display';
	$wp_customize->add_control(
		new Bailey_Customize_Misc_Control(
			$wp_customize,
			$control_prefix . $setting_id,
			array(
				'section'  => $section,
				'type'     => 'heading',
				'label'    => __( 'Portfolio Display', 'bailey' ),
				'priority' => $priority->add()
			)
		)
	);

	// Show section title
	$setting_id = $setting_prefix . '-show-section-title';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Show archive title', 'make' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Show taxonomies
	$setting_id = $setting_prefix . '-show-taxonomies';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Show project types and tags', 'make' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Portfolio columns
	$setting_id = $setting_prefix . '-archive-columns';
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
			'label'    => __( 'Portfolio Columns', 'bailey' ),
			'type'     => 'select',
			'choices'  => bailey_get_choices( $setting_id ),
			'priority' => $priority->add()
		)
	);

	// Project captions
	$setting_id = $setting_prefix . '-archive-captions';
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
			'label'    => __( 'Project Overlay Style', 'bailey' ),
			'type'     => 'select',
			'choices'  => bailey_get_choices( $setting_id ),
			'priority' => $priority->add()
		)
	);

	// Project images
	$setting_id = $setting_prefix . '-archive-images';
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
			'label'    => __( 'Project Image Aspect Ratio', 'bailey' ),
			'type'     => 'select',
			'choices'  => bailey_get_choices( $setting_id ),
			'priority' => $priority->add()
		)
	);

	// Image info
	$setting_id = $setting_prefix . '-image-info';
	$wp_customize->add_control(
		new Bailey_Customize_Misc_Control(
			$wp_customize,
			$control_prefix . $setting_id,
			array(
				'section'     => $section,
				'type'        => 'text',
				'description' => __( 'Having problems with your image aspect ratio? Make sure your projects\' featured images have a minimum of <strong>1042 pixels</strong> on the longest side.', 'bailey' ),
				'priority'    => $priority->add()
			)
		)
	);
}
endif;