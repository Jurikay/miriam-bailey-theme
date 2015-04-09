<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_customizer_navigation' ) ) :
/**
 * Configure settings and controls for the Navigation section.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_customizer_navigation() {
	global $wp_customize;

	$priority       = new Bailey_Prioritizer();
	$control_prefix = 'bailey_';
	$section        = 'nav';

	// Menu Label
	$setting_id = 'navigation-label';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_html',
			'theme_supports'    => 'menus',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Menu Button Label', 'bailey' ),
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);
}
endif;

add_action( 'customize_register', 'bailey_customizer_navigation', 20 );