<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_customizer_footer' ) ) :
/**
 * Configure settings and controls for the Footer section
 *
 * @since  1.0.0.
 *
 * @param  object    $wp_customize    The global customizer object.
 * @param  string    $section         The section name.
 * @return void
 */
function bailey_customizer_footer( $wp_customize, $section ) {
	$priority       = new Bailey_Prioritizer( 500, 5 );
	$control_prefix = 'bailey_';
	$setting_prefix = str_replace( $control_prefix, '', $section );
	$section = ( bailey_is_wpcom() ) ? 'bailey_theme' : $section;

	// Footer widget area view heading
	$setting_id = $setting_prefix . '-heading-widget-views';
	$wp_customize->add_control(
		new Bailey_Customize_Misc_Control(
			$wp_customize,
			$control_prefix . $setting_id,
			array(
				'section'  => $section,
				'type'     => 'heading',
				'label'    => __( 'Show Footer Widgets', 'bailey' ),
				'priority' => $priority->add()
			)
		)
	);

	// Footer Widgets Blog
	$setting_id = $setting_prefix . '-widgets-blog';
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
			'label'    => __( 'Blog (Posts Page)', 'bailey' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Footer Widgets Archives
	$setting_id = $setting_prefix . '-widgets-archive';
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
			'label'    => __( 'Archives', 'bailey' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Footer Widgets Search Results
	$setting_id = $setting_prefix . '-widgets-search';
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
			'label'    => __( 'Search Results', 'bailey' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Footer Widgets Posts
	$setting_id = $setting_prefix . '-widgets-post';
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
			'label'    => __( 'Posts', 'bailey' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Footer Widgets Pages
	$setting_id = $setting_prefix . '-widgets-page';
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
			'label'    => __( 'Pages', 'bailey' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Footer Widgets Portfolio
	$setting_id = $setting_prefix . '-widgets-portfolio';
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
			'label'    => __( 'Portfolio', 'make' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Footer Widgets Projects
	$setting_id = $setting_prefix . '-widgets-project';
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
			'label'    => __( 'Projects', 'make' ),
			'type'     => 'checkbox',
			'priority' => $priority->add()
		)
	);

	// Footer widget areas
	$setting_id = $setting_prefix . '-widget-areas';
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
			'label'    => __( 'Footer Widget Areas', 'bailey' ),
			'type'     => 'select',
			'choices'  => bailey_get_choices( $setting_id ),
			'priority' => $priority->add()
		)
	);

	// Footer text
	$setting_id = $setting_prefix . '-text';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'bailey_sanitize_text',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Footer Text', 'bailey' ),
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);
}
endif;