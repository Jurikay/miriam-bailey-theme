<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_maybe_activate_portfolio' ) ) :
/**
 * Activate the Portfolio CPT module if not WP.com and the Jetpack module is not activated.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_maybe_activate_portfolio() {
	global $pagenow;
	$wpcom             = bailey_is_wpcom();
	$plugin_activation = ( 'plugins.php' === $pagenow && isset( $_REQUEST['action'] ) && 'activate' === $_REQUEST['action']
		&& isset( $_REQUEST['plugin'] ) );
	$module_activation = ( 'admin.php' === $pagenow && isset( $_REQUEST['page'] ) && 'jetpack' === $_REQUEST['page']
		&& isset( $_REQUEST['action'] ) && 'activate' === $_REQUEST['action'] && isset( $_REQUEST['module'] )
		&& 'custom-content-types' === $_REQUEST['module'] );
	$portfolio_class   = class_exists( 'Jetpack_Portfolio' );

	if ( ! $wpcom && ! $plugin_activation && ! $module_activation && ! $portfolio_class ) {
		$file = trailingslashit( get_template_directory() ) . 'inc/portfolio/portfolios.php';

		if ( file_exists( $file ) ) {
			// Load the file
			require_once( $file );

			// Filter the URL for the CSS file
			add_filter( 'plugins_url', 'bailey_plugins_url_portfolio', 10, 2 );

			// Check for the 'enable' option
			$portfolio_option = get_option( 'jetpack_portfolio', false );
			if ( false === $portfolio_option ) {
				update_option( 'jetpack_portfolio', 1 );
			}
		}
	}
}
endif;

add_action( 'after_setup_theme', 'bailey_maybe_activate_portfolio' );

if ( ! function_exists( 'bailey_plugins_url_portfolio' ) ) :
/**
 * Filter the URL for the Portfolio shortcode CSS.
 *
 * This is only hooked if the Portfolio CPT is being loaded from within the theme.
 *
 * @since  1.0.0.
 *
 * @param  string    $url     The default URL.
 * @param  string    $path    Path to the CSS.
 * @return string             Modified CSS URL.
 */
function bailey_plugins_url_portfolio( $url, $path ) {
	if ( preg_match( '/portfolio-shortcode\.css/', $path ) ) {
		$url = trailingslashit( get_template_directory_uri() ) . 'inc/portfolio/portfolio-shortcode.css';
	}

	return $url;
}
endif;

if ( ! function_exists( 'bailey_attached_posts_init' ) ) :
/**
 * Initialize the Attached Posts functionality
 *
 * This must be hooked to 'init' so that the 'jetpack-portfolio' CPT exists
 * when it fires.
 *
 * @since 1.0.0.
 *
 * @return void
 */
function bailey_attached_posts_init() {
	// Portfolio Template functionality
	if ( class_exists( 'Bailey_Attached_Posts' ) ) {
		new Bailey_Attached_Posts(
			'bailey-attached-projects',
			array( 'page' ),
			array( 'template-portfolio.php' ),
			array( 'meta_box_title' => __( 'Projects', 'bailey' ) ),
			array( 'jetpack-portfolio' )
		);
	}
}
endif;

add_action( 'init', 'bailey_attached_posts_init', 20 );

if ( ! function_exists( 'bailey_attached_posts_label_defaults' ) ) :
/**
 * Modify the Attached Posts labels for projects
 *
 * @since 1.0.0.
 *
 * @param $default_labels
 * @param $key
 * @return array
 */
function bailey_attached_posts_label_defaults( $default_labels, $key ) {
	if ( 'bailey-attached-projects' === $key ) {
		$default_labels = array(
			'meta_box_title'     => __( 'Attached Projects', 'bailey' ),
			'selected_posts'     => __( 'Selected Projects', 'bailey' ),
			'no_current_posts'   => __( 'Click to add Projects. Drag and drop Selected Projects into order.', 'bailey' ),
			'choose_posts'       => __( 'Choose Projects', 'bailey' ),
			'recent_posts'       => __( 'Recent Projects', 'bailey' ),
			'search_posts'       => __( 'Search', 'bailey' ),
			'no_available_posts' => __( 'No projects available', 'bailey' ),
		);
	}

	return $default_labels;
}
endif;

add_filter( 'bailey_attached_posts_label_defaults', 'bailey_attached_posts_label_defaults', 10, 2 );

/**
 * Register values for the updater.
 *
 * @since  1.0.0.
 *
 * @param  array    $values    The present updater values.
 * @return array               Modified updater values.
 */
function bailey_updater_config( $values ) {
	return array(
		'slug'            => 'bailey',
		'type'            => 'theme',
		'current_version' => BAILEY_VERSION,
	);
}
if ( ! bailey_is_wpcom() ) {
	// Load in the updater
	if ( file_exists( get_template_directory() . '/inc/updater/updater.php' ) ) {
		require get_template_directory() . '/inc/updater/updater.php';
	}

	add_filter( 'ttf_updater_config', 'bailey_updater_config' );
}

if ( ! function_exists( 'bailey_add_customizations' ) ) :
/**
 * Make sure the 'bailey_css' action only runs once.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_add_customizations() {
	do_action( 'bailey_css' );
}
endif;

add_action( 'admin_init', 'bailey_add_customizations' );

if ( ! function_exists( 'bailey_display_customizations' ) ) :
/**
 * Generates the style tag and CSS needed for the theme options.
 *
 * By using the "bailey_css" filter, different components can print CSS in the header. It is organized this way to
 * ensure that there is only one "style" tag and not a proliferation of them.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_display_customizations() {
	do_action( 'bailey_css' );

	// Echo the rules
	$css = bailey_get_css()->build();
	if ( ! empty( $css ) ) {
		echo "\n<!-- Begin Bailey Custom CSS -->\n<style type=\"text/css\" id=\"bailey-custom-css\">\n";
		echo $css;
		echo "\n</style>\n<!-- End Bailey Custom CSS -->\n";
	}
}
endif;

add_action( 'wp_head', 'bailey_display_customizations', 11 );

if ( ! function_exists( 'bailey_ajax_display_customizations' ) ) :
/**
 * Generates the theme option CSS as an Ajax response
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_ajax_display_customizations() {
	// Make sure this is an Ajax request
	if ( ! defined( 'DOING_AJAX' ) || true !== DOING_AJAX ) {
		return;
	}

	// Set the content type
	header( "Content-Type: text/css" );

	// Echo the rules
	echo bailey_get_css()->build();

	// End the Ajax response
	die();
}
endif;

add_action( 'wp_ajax_bailey-css', 'bailey_ajax_display_customizations' );

if ( ! function_exists( 'bailey_mce_css' ) ) :
/**
 * Make sure theme option CSS is added to TinyMCE last, to override other styles.
 *
 * @since  1.0.0.
 *
 * @param  string    $stylesheets    List of stylesheets added to TinyMCE.
 * @return string                    Modified list of stylesheets.
 */
function bailey_mce_css( $stylesheets ) {
	global $post_id;
	if ( ! isset( $post_id ) ) {
		$post_id = 0;
	}

	$ajax = admin_url( 'admin-ajax.php' );
	$ajax = add_query_arg( 'action', 'bailey-css', $ajax );
	$ajax = add_query_arg( 'post_id', $post_id, $ajax );
	$stylesheets .= ',' . $ajax;

	return $stylesheets;
}
endif;

add_filter( 'mce_css', 'bailey_mce_css', 99 );