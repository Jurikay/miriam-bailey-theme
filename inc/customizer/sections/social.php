<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_customizer_social' ) ) :
/**
 * Configure settings and controls for the Social section
 *
 * @since 1.0.0
 *
 * @param  object    $wp_customize    The global customizer object.
 * @param  string    $section         The section name.
 * @return void
 */
function bailey_customizer_social( $wp_customize, $section ) {
	$priority       = new Bailey_Prioritizer( 600, 5 );
	$control_prefix = 'bailey_';
	$setting_prefix = str_replace( $control_prefix, '', $section );
	$section = ( bailey_is_wpcom() ) ? 'bailey_theme' : $section;

	// Social description
	$setting_id = $setting_prefix . '-description';
	$wp_customize->add_control(
		new Bailey_Customize_Misc_Control(
			$wp_customize,
			$control_prefix . $setting_id,
			array(
				'section'     => $section,
				'type'        => 'text',
				'description' => __( 'Enter the complete URL to your profile for each service below that you would like to share.', 'bailey' ),
				'priority'    => $priority->add()
			)
		)
	);

	// Facebook
	$setting_id = $setting_prefix . '-facebook';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Facebook', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// Twitter
	$setting_id = $setting_prefix . '-twitter';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Twitter', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// Google +
	$setting_id = $setting_prefix . '-google-plus';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Google +', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// LinkedIn
	$setting_id = $setting_prefix . '-linkedin';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'LinkedIn', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// Instagram
	$setting_id = $setting_prefix . '-instagram';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Instagram', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// Flickr
	$setting_id = $setting_prefix . '-flickr';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Flickr', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// YouTube
	$setting_id = $setting_prefix . '-youtube';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'YouTube', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// Vimeo
	$setting_id = $setting_prefix . '-vimeo';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Vimeo', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// Pinterest
	$setting_id = $setting_prefix . '-pinterest';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Pinterest', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// 500px
	$setting_id = $setting_prefix . '-fivehpx';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => '500px', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// Behance
	$setting_id = $setting_prefix . '-behance';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Behance', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// Dribbble
	$setting_id = $setting_prefix . '-dribbble';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'Dribbble', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// deviantArt
	$setting_id = $setting_prefix . '-deviantart';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'deviantArt', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// SmugMug
	$setting_id = $setting_prefix . '-smugmug';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => 'SmugMug', // brand names not translated
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// Email
	$setting_id = $setting_prefix . '-email';
	$wp_customize->add_setting(
		$setting_id,
		array(
			'default'           => bailey_get_default( $setting_id ),
			'type'              => 'theme_mod',
			'sanitize_callback' => 'sanitize_email',
		)
	);
	$wp_customize->add_control(
		$control_prefix . $setting_id,
		array(
			'settings' => $setting_id,
			'section'  => $section,
			'label'    => __( 'Email', 'bailey' ),
			'type'     => 'text',
			'priority' => $priority->add()
		)
	);

	// RSS options are not available on WP.com
	if ( ! bailey_is_wpcom() ) {
		// RSS Heading
		$setting_id = $setting_prefix . '-rss-heading';
		$wp_customize->add_control(
			new Bailey_Customize_Misc_Control(
				$wp_customize,
				$control_prefix . $setting_id,
				array(
					'section'     => $section,
					'type'        => 'heading',
					'label' => __( 'Default RSS', 'bailey' ),
					'priority'    => $priority->add()
				)
			)
		);

		// Hide RSS
		$setting_id = $setting_prefix . '-hide-rss';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => bailey_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control(
			$control_prefix . $setting_id,
			array(
				'settings' => $setting_id,
				'section'  => $section,
				'label'    => __( 'Hide default RSS feed link', 'bailey' ),
				'type'     => 'checkbox',
				'priority' => $priority->add()
			)
		);

		// Custom RSS
		$setting_id = $setting_prefix . '-custom-rss';
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => bailey_get_default( $setting_id ),
				'type'              => 'theme_mod',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			$control_prefix . $setting_id,
			array(
				'settings' => $setting_id,
				'section'  => $section,
				'label'    => __( 'Custom RSS URL (replaces default)', 'bailey' ),
				'type'     => 'text',
				'priority' => $priority->add()
			)
		);
	}
}
endif;