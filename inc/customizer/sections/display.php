<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_customizer_display' ) ) :
/**
 * Configure settings and controls for the General section
 *
 * @since  1.0.0.
 *
 * @param  object    $wp_customize    The global customizer object.
 * @param  string    $section         The section name.
 * @return void
 */
function bailey_customizer_display( $wp_customize, $section ) {
	$priority = new Bailey_Prioritizer( 300, 5 );
	$control_prefix = 'bailey_';
	$setting_prefix = str_replace( $control_prefix, '', $section );
	$section = ( bailey_is_wpcom() ) ? 'bailey_theme' : $section;

	// Sidebar view heading
	$setting_id = $setting_prefix . '-heading-sidebar-views';
	$wp_customize->add_control(
		new Bailey_Customize_Misc_Control(
			$wp_customize,
			$control_prefix . $setting_id,
			array(
				'section'  => $section,
				'type'     => 'heading',
				'label'    => __( 'Show Main Sidebar', 'bailey' ),
				'priority' => $priority->add()
			)
		)
	);

	// Sidebar Blog
	$setting_id = $setting_prefix . '-sidebar-blog';
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

	// Sidebar Archives
	$setting_id = $setting_prefix . '-sidebar-archive';
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

	// Sidebar Search Results
	$setting_id = $setting_prefix . '-sidebar-search';
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

	// Sidebar Posts
	$setting_id = $setting_prefix . '-sidebar-post';
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

	// Sidebar Pages
	$setting_id = $setting_prefix . '-sidebar-page';
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

	// Sticky label
	$setting_id = $setting_prefix . '-sticky-label';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_html',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Sticky Label', 'bailey' ),
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);
}
endif;