<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_wp_title' ) ) :
/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since 1.0.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function bailey_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() ) {
		return $title;
	}

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 ) {
		$title .= " $sep " . sprintf( __( 'Page %s', 'oxford' ), max( $paged, $page ) );
	}

	return $title;
}
endif;

add_filter( 'wp_title', 'bailey_wp_title', 10, 2 );

if ( ! function_exists( 'bailey_body_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @since  1.0.0.
 *
 * @param  array    $classes    Classes for the body element.
 * @return array                Modified class list.
 */
function bailey_body_classes( $classes ) {
	// Without sidebar
	$view = bailey_get_view();
	$show_sidebar = absint( get_theme_mod( 'display-sidebar-' . $view, bailey_get_default( 'display-sidebar-' . $view ) ) );
	if ( 1 !== $show_sidebar ) {
		$classes[] = 'without-sidebar';
	}

	// Single post with full-width featured image
	if ( is_single() && bailey_is_image_large_enough( get_post_thumbnail_id(), array( 1042, 1 ), 'bailey_large' ) ) {
		$classes[] = 'full-width-thumbnail';
	}

	return $classes;
}
endif;

add_filter( 'body_class', 'bailey_body_classes' );

if ( ! function_exists( 'bailey_sanitize_text' ) ) :
/**
 * Sanitize a string to allow only tags in the allowedtags array.
 *
 * @since  1.0.0.
 *
 * @param  string    $string    The unsanitized string.
 * @return string               The sanitized string.
 */
function bailey_sanitize_text( $string ) {
	global $allowedtags;
	return wp_kses( $string , $allowedtags );
}
endif;

if ( ! function_exists( 'sanitize_hex_color' ) ) :
/**
 * Sanitizes a hex color.
 *
 * This is a copy of the core function for use when the customizer is not being shown.
 *
 * @since  1.0.0.
 *
 * @param  string         $color    The proposed color.
 * @return string|null              The sanitized color.
 */
function sanitize_hex_color( $color ) {
	if ( '' === $color ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}

	return null;
}
endif;

if ( ! function_exists( 'sanitize_hex_color_no_hash' ) ) :
/**
 * Sanitizes a hex color without a hash. Use sanitize_hex_color() when possible.
 *
 * This is a copy of the core function for use when the customizer is not being shown.
 *
 * @since  1.0.0.
 *
 * @param  string         $color    The proposed color.
 * @return string|null              The sanitized color.
 */
function sanitize_hex_color_no_hash( $color ) {
	$color = ltrim( $color, '#' );

	if ( '' === $color ) {
		return '';
	}

	return sanitize_hex_color( '#' . $color ) ? $color : null;
}
endif;

if ( ! function_exists( 'maybe_hash_hex_color' ) ) :
/**
 * Ensures that any hex color is properly hashed.
 *
 * This is a copy of the core function for use when the customizer is not being shown.
 *
 * @since  1.0.0.
 *
 * @param  string         $color    The proposed color.
 * @return string|null              The sanitized color.
 */
function maybe_hash_hex_color( $color ) {
	if ( $unhashed = sanitize_hex_color_no_hash( $color ) ) {
		return '#' . $unhashed;
	}

	return $color;
}
endif;

if ( ! function_exists( 'bailey_get_view' ) ) :
/**
 * Determine the current view
 *
 * For use with view-related theme options.
 *
 * @since  1.0.0.
 *
 * @return string    The string representing the current view.
 */
function bailey_get_view() {
	// Return cached value if set.
	global $bailey_view;
	if ( isset( $bailey_view ) ) {
		return apply_filters( 'bailey_get_view', $bailey_view );
	}

	// Post types
	$post_types = get_post_types(
		array(
			'public' => true,
			'_builtin' => false
		)
	);
	$post_types[] = 'post';
	unset( $post_types['jetpack-portfolio'] );

	// Post parent
	$parent_post_type = '';
	if ( is_attachment() ) {
		$post_parent = get_post()->post_parent;
		$parent_post_type = get_post_type( $post_parent );
	}

	$bailey_view = 'post';

	// Blog
	if ( is_home() ) {
		$bailey_view = 'blog';
	}
	// Archives
	else if ( is_archive() ) {
		$bailey_view = 'archive';
	}
	// Search results
	else if ( is_search() ) {
		$bailey_view = 'search';
	}
	// Posts and public custom post types (except Portfolio)
	else if ( is_singular( $post_types ) || ( is_attachment() && in_array( $parent_post_type, $post_types ) ) ) {
		$bailey_view = 'post';
	}
	// Pages
	else if ( is_page() || ( is_attachment() && 'page' === $parent_post_type ) ) {
		$bailey_view = 'page';
	}

	// Portfolio archives
	if ( ( is_page() && 'template-portfolio.php' === get_page_template_slug() ) || is_post_type_archive( 'jetpack-portfolio' ) || is_tax( array( 'jetpack-portfolio-type', 'jetpack-portfolio-tag' ) ) ) {
		$bailey_view = 'portfolio';
	}
	// Portfolio items
	else if ( is_singular( 'jetpack-portfolio' ) || ( is_attachment() && 'jetpack-portfolio' === $parent_post_type ) ) {
		$bailey_view = 'project';
	}

	// Filter the view and return
	return apply_filters( 'bailey_get_view', $bailey_view );
}
endif;

if ( ! function_exists( 'bailey_sidebar_description' ) ) :
/**
 * Output a sidebar description that reflects its current status.
 *
 * @since  1.0.0.
 *
 * @param  string    $sidebar_id    The sidebar to look up the description for.
 * @return string                   The description.
 */
function bailey_sidebar_description( $sidebar_id ) {
	$description = '';

	$enabled_views = array();
	switch ( $sidebar_id ) {
		case 'sidebar-main' :
			$enabled_views = bailey_sidebar_list_enabled( 'display-sidebar' );
			break;
		case 'footer-1' :
		case 'footer-2' :
		case 'footer-3' :
			$column = (int) str_replace( 'footer-', '', $sidebar_id );
			$column_count = (int) get_theme_mod( 'footer-widget-areas', bailey_get_default( 'footer-widget-areas' ) );
			if ( $column <= $column_count ) {
				$enabled_views = bailey_sidebar_list_enabled( 'footer-widgets' );
			}
			break;
	}

	// Not enabled anywhere
	if ( empty( $enabled_views ) ) {
		$description = __( 'This widget area is currently disabled. Enable it in the Theme Customizer.', 'bailey' );
	}
	// List enabled views
	else {
		$description = sprintf(
			__( 'This widget area is currently enabled for the following views: %s. Change this in the Theme Customizer.', 'bailey' ),
			esc_html( implode( _x( ', ', 'list item separator', 'bailey' ), $enabled_views ) )
		);
	}

	return esc_html( $description );
}
endif;

if ( ! function_exists( 'bailey_sidebar_list_enabled' ) ) :
/**
 * Compile a list of views where a particular sidebar is enabled.
 *
 * @since  1.0.0.
 *
 * @param  string    $prefix    The sidebar option prefix.
 * @return array                The sidebar's enabled views.
 */
function bailey_sidebar_list_enabled( $prefix ) {
	$enabled_views = array();

	if ( ! in_array( $prefix, array( 'display-sidebar', 'footer-widgets' ) ) ) {
		return $enabled_views;
	}

	$views = array(
		'blog'      => __( 'Blog (Post Page)', 'bailey' ),
		'archive'   => __( 'Archives', 'bailey' ),
		'search'    => __( 'Search Results', 'bailey' ),
		'post'      => __( 'Posts', 'bailey' ),
		'page'      => __( 'Pages', 'bailey' ),
		'portfolio' => __( 'Portfolio', 'bailey' ),
		'project'   => __( 'Portfolio Items', 'bailey' ),
	);

	foreach ( $views as $view => $label ) {
		$option = (bool) get_theme_mod( "$prefix-$view", bailey_get_default( "$prefix-$view" ) );
		if ( true === $option ) {
			$enabled_views[] = $label;
		}
	}

	return $enabled_views;
}
endif;

if ( ! function_exists( 'bailey_projects_per_page' ) ) :
/**
 * Modify the number of projects per page on Portfolio archive pages
 *
 * @since 1.0.0.
 *
 * @param $query    The WP_Query object.
 */
function bailey_projects_per_page( $query ) {
	if ( $query->is_main_query() && ( $query->is_post_type_archive( 'jetpack-portfolio' ) || $query->is_tax( array( 'jetpack-portfolio-type', 'jetpack-portfolio-tag' ) ) ) ) {
		$ppp = ( $query->get( 'posts_per_page' ) ) ? absint( $query->get( 'posts_per_page' ) ) : absint( get_option( 'posts_per_page' ) );
		$columns = (int) get_theme_mod( 'portfolio-archive-columns', bailey_get_default( 'portfolio-archive-columns' ) );

		$new_ppp = apply_filters( 'bailey_projects_per_page', $ppp * $columns );

		$query->set( 'posts_per_page', $new_ppp );
	}
}
endif;

add_action( 'pre_get_posts', 'bailey_projects_per_page' );