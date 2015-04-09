<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_infinite_scroll_setup' ) ) :
/**
 * Add theme support for Infinite Scroll
 *
 * @since 1.0.1.
 *
 * @return void
 */
function bailey_infinite_scroll_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'site-main',
		'render'         => 'bailey_infinite_scroll_render',
		'footer_widgets' => array( 'footer-1', 'footer-2', 'footer-3' ),
		'footer'         => 'site-content',
	) );
}
endif;

add_action( 'after_setup_theme', 'bailey_infinite_scroll_setup' );

if ( ! function_exists( 'bailey_infinite_scroll_support' ) ) :
/**
 * Add support for Infinite Scroll on portfolio views.
 *
 * @since 1.0.1.
 *
 * @param  $supported
 * @return bool
 */
function bailey_infinite_scroll_support( $supported ) {
	if ( ( is_page() && 'template-portfolio.php' === get_page_template_slug() ) || is_post_type_archive( 'jetpack-portfolio' ) || is_tax( array( 'jetpack-portfolio-type', 'jetpack-portfolio-tag' ) ) ) {
		$supported = true;
	}

	return $supported;
}
endif;

add_filter( 'infinite_scroll_archive_supported', 'bailey_infinite_scroll_support' );

if ( ! function_exists( 'bailey_infinite_scroll_render' ) ) :
/**
 * Render the content output when a post-load event is triggered.
 *
 * @since 1.0.1.
 *
 * @return void
 */
function bailey_infinite_scroll_render() {
	if ( ( is_page() && 'template-portfolio.php' === get_page_template_slug() ) || is_post_type_archive( 'jetpack-portfolio' ) || is_tax( array( 'jetpack-portfolio-type', 'jetpack-portfolio-tag' ) ) ) {
		$slug = 'portfolio-archive';
	} else if ( is_search() ) {
		$slug = 'search';
	} else {
		$slug = 'archive';
	}

	while ( have_posts() ) : the_post();
		get_template_part( 'partials/content', $slug );
	endwhile;
}
endif;


/**
 * Conditionally modify the Infinite Scroll JS settings
 *
 * @since 1.0.1.
 *
 * @param  $settings
 * @return mixed
 */
function bailey_infinite_scroll_js_settings( $settings ) {
	$view = bailey_get_view();

	// Container ID on portfolio views
	if ( 'portfolio' === $view ) {
		$settings['id'] = 'portfolio-container';
	}

	// Check for visible footer widgets and adjust scroll type accordingly
	if ( 'click' === $settings['type'] ) {
		$settings['type'] = 'scroll';

		// Determine if current view has footer widgets enabled
		$show_footer_widgets = absint( get_theme_mod( 'footer-widgets-' . $view, bailey_get_default( 'footer-widgets-' . $view ) ) );
		if ( 1 === $show_footer_widgets ) {
			// Sanitize sidebar count
			$sidebar_count = get_theme_mod( 'footer-widget-areas', bailey_get_default( 'footer-widget-areas' ) );
			$sidebar_count = bailey_sanitize_choice( $sidebar_count, 'footer-widget-areas' );

			// Test for enabled sidebars that contain widgets
			if ( $sidebar_count > 0 ) {
				$i = 1;
				while ( $i <= $sidebar_count ) {
					if ( is_active_sidebar( 'footer-' . $i ) ) {
						$settings['type'] = 'click';
						break;
					}
					$i++;
				}
			}
		}
	}

	return $settings;
}

add_filter( 'infinite_scroll_js_settings', 'bailey_infinite_scroll_js_settings' );