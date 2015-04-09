<?php
/**
 * @package Bailey
 */

/**
 * The current version of the theme.
 */
define( 'BAILEY_VERSION', '1.0.4' );

if ( ! function_exists( 'bailey_is_wpcom' ) ) :
/**
 * Whether or not the current environment is WordPress.com.
 *
 * @since  1.0.0.
 *
 * @return bool    Whether or not the current environment is WordPress.com.
 */
function bailey_is_wpcom() {
	return ( defined( 'IS_WPCOM' ) && true === IS_WPCOM );
}
endif;

/**
 * The suffix to use for scripts.
 */
//if ( ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) || bailey_is_wpcom() ) {
	define( 'BAILEY_SUFFIX', '' );
//} else {
//	define( 'BAILEY_SUFFIX', '.min' );
//}

/**
 * Initial content width.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1042;
}

/**
 * Global includes.
 */
// Custom functions that act independently of the theme templates
require get_template_directory() . '/inc/extras.php';

// Custom template tags
require get_template_directory() . '/inc/template-tags.php';

// Customizer additions
require get_template_directory() . '/inc/customizer/bootstrap.php';

// Gallery slider
require get_template_directory() . '/inc/gallery-slider/gallery-slider.php';

/**
 * Admin-only includes.
 */
if ( is_admin() ) {
	// TinyMCE
	require trailingslashit( get_template_directory() ) . 'inc/admin-ui/tinymce.php';
}

/**
 * Self-hosted includes.
 */
if ( ! bailey_is_wpcom() ) {
	// Typekit (submodule)
	$glyphs_global = trailingslashit( get_template_directory() ) . 'inc/glyphs/glyphs-global.php';
	if ( file_exists( $glyphs_global ) ) {
		require $glyphs_global;
	}
	$glyphs_admin = trailingslashit( get_template_directory() ) . 'inc/glyphs/glyphs.php';
	if ( is_admin() && file_exists( $glyphs_admin ) ) {
		require $glyphs_admin;
	}
	$glyphs_frontend = trailingslashit( get_template_directory() ) . 'inc/glyphs/glyphs-frontend.php';
	if ( ! is_admin() && file_exists( $glyphs_frontend ) ) {
		require $glyphs_frontend;
	}

	// Custom default avatar
	$avatar_file = trailingslashit( get_template_directory() ) . 'inc/avatar.php';
	if ( file_exists( $avatar_file ) ) {
		require $avatar_file;
	}

	// Unbox (submodule)
	$unbox_file = trailingslashit( get_template_directory() ) . 'inc/unbox/unbox.php';
	if ( is_admin() && file_exists( $unbox_file ) ) {
		require $unbox_file;
	}

	// Portfolio page
	$portfolio_page_file = trailingslashit( get_template_directory() ) . 'inc/admin-ui/attached-posts.php';
	if ( file_exists( $portfolio_page_file ) ) {
		require $portfolio_page_file;
	}

	// Per Page metaboxes
	$per_page_file = trailingslashit( get_template_directory() ) . 'inc/admin-ui/per-page.php';
	if ( file_exists( $per_page_file ) ) {
		require $per_page_file;
	}

	// Other functions for self-hosted installs only
	$selfhosted_file = trailingslashit( get_template_directory() ) . 'inc/self-hosted.php';
	if ( file_exists( $selfhosted_file ) ) {
		require $selfhosted_file;
	}
}

/**
 * 3rd party compatibility includes.
 */
// Jetpack
// There are several plugins that duplicate the functionality of various Jetpack modules,
// so rather than conditionally loading our Jetpack compatibility file based on the presence
// of the main Jetpack class, we attempt to detect individual classes/functions related to
// their particular modules.
require trailingslashit( get_template_directory() ) . 'inc/jetpack.php';

if ( ! function_exists( 'bailey_setup' ) ) :
/**
 * Sets up text domain, theme support, menus, and editor styles
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_setup() {
	// Attempt to load text domain from child theme first
	if ( ! load_theme_textdomain( 'bailey', get_stylesheet_directory() . '/languages' ) ) {
		load_theme_textdomain( 'bailey', get_template_directory() . '/languages' );
	}

	// Feed links
	add_theme_support( 'automatic-feed-links' );

	// Featured images
	add_theme_support( 'post-thumbnails' );

	// Custom background
	add_theme_support( 'custom-background', array(
		'default-color'      => bailey_get_default( 'background_color' ),
		'default-image'      => bailey_get_default( 'background_image' ),
		'default-repeat'     => bailey_get_default( 'background_repeat' ),
		'default-position-x' => bailey_get_default( 'background_position_x' ),
		'default-attachment' => bailey_get_default( 'background_attachment' ),
	) );

	// HTML5
	add_theme_support( 'html5', array(
		'comment-list',
		'comment-form',
		'search-form',
		'gallery',
		'caption'
	) );

	// Portfolio CPT
	add_theme_support( 'jetpack-portfolio' );

	// Menu locations
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'bailey' )
	) );

	// Editor styles
	$editor_styles = array();

	$editor_styles[] = 'css/editor-style.css';

	// Another editor stylesheet is added via bailey_mce_css() in inc/customizer/bootstrap.php
	add_editor_style( $editor_styles );
}
endif;

add_action( 'after_setup_theme', 'bailey_setup' );

if ( ! function_exists( 'bailey_image_sizes' ) ) :
/**
 * Register custom image sizes
 *
 * @since 1.0.0.
 *
 * @return void
 */
function bailey_image_sizes() {
	$defaults = array(
		'bailey_large'     => array( 1042, 9999, false ),
		'bailey_landscape' => array( 1042,  782,  true ),
		'bailey_portrait'  => array(  782, 1042,  true ),
		'bailey_square'    => array( 1042, 1042,  true ),
	);
	$image_sizes = apply_filters( 'bailey_image_sizes', $defaults );
	foreach ( $image_sizes as $name => $prop ) {
		$crop = ( isset( $prop[2] ) ) ? $prop[2] : false;
		add_image_size( $name, $prop[0], $prop[1], $crop );
	}
}
endif;

add_action( 'after_setup_theme', 'bailey_image_sizes' );

if ( ! function_exists( 'bailey_image_size_options' ) ) :
/**
 * Add additional size options when inserting an image into content.
 *
 * @since 1.0.0.
 *
 * @param  array    $sizes    The array of available image sizes.
 * @return array              The modified array of available image sizes.
 */
function bailey_image_size_options( $sizes ) {
	global $typenow;

	$new_sizes = array();

	if ( 'jetpack-portfolio' === $typenow || ( defined( 'DOING_AJAX') && true === DOING_AJAX ) ) {
		$new_sizes['bailey_large'] = __( 'Project Full Width', 'bailey' );
	}

	// Add new sizes above "Full" size
	if ( ! empty( $new_sizes ) ) {
		// Get the position of the Full size in the array
		$keys = array_keys( $sizes );
		$positions = array_flip( $keys );
		$full_size = absint( $positions[ 'full' ] );

		// Slice the array
		$front = array_slice( $sizes, 0, $full_size );
		$back  = array_slice( $sizes, $full_size );

		// Compile arrays
		$sizes = array_merge( $front, $new_sizes, $back );
	}

	return $sizes;
}
endif;

add_filter( 'image_size_names_choose', 'bailey_image_size_options' );

if ( ! function_exists( 'bailey_set_view' ) ) :
/**
 * Set the current view for later use.
 *
 * @since 1.0.0.
 *
 * @return void
 */
function bailey_set_view() {
	bailey_get_view();
}
endif;

add_action( 'wp', 'bailey_set_view' );

if ( ! function_exists( 'bailey_widgets_init' ) ) :
/**
 * Register widget areas
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_widgets_init() {
	register_sidebar( array(
		'id'            => 'sidebar-main',
		'name'          => __( 'Main Sidebar', 'bailey' ),
		'description'   => bailey_sidebar_description( 'sidebar-main' ),

		// Note that if these arguments are changed, they should also be updated in sidebar-main.php
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'id'            => 'sidebar-menu',
		'name'          => __( 'Menu Sidebar', 'bailey' ),
		'description'   => __( 'Widgets placed here will appear beneath the navigation menu.', 'bailey' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'id'            => 'footer-1',
		'name'          => __( 'Footer 1', 'bailey' ),
		'description'   => bailey_sidebar_description( 'footer-1' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'id'            => 'footer-2',
		'name'          => __( 'Footer 2', 'bailey' ),
		'description'   => bailey_sidebar_description( 'footer-2' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'id'            => 'footer-3',
		'name'          => __( 'Footer 3', 'bailey' ),
		'description'   => bailey_sidebar_description( 'footer-3' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
endif;

add_action( 'widgets_init', 'bailey_widgets_init' );

if ( ! function_exists( 'bailey_head_early' ) ) :
/**
 * Add items to the top of the wp_head section of the document head.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_head_early() {
	// JavaScript detection ?>
	<script type="text/javascript">
		/* <![CDATA[ */
		document.documentElement.className = document.documentElement.className.replace(new RegExp('(^|\\s)no-js(\\s|$)'), '$1js$2');
		/* ]]> */
	</script>
<?php
}
endif;

add_action( 'wp_head', 'bailey_head_early', 1 );

if ( ! function_exists( 'bailey_scripts' ) ) :
/**
 * Enqueue styles and scripts.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_scripts() {
	$view = bailey_get_view();

	// Styles
	$style_dependencies = array();

	// Main stylesheet
	wp_enqueue_style(
		'bailey-main-style',
		get_stylesheet_uri(),
		$style_dependencies,
		BAILEY_VERSION
	);
	$style_dependencies[] = 'bailey-main-style';

	// Print stylesheet
	wp_enqueue_style(
		'bailey-print-style',
		get_template_directory_uri() . '/css/print.css',
		$style_dependencies,
		BAILEY_VERSION,
		'print'
	);

	// Scripts
	$script_dependencies = array();

	// jQuery
	$script_dependencies[] = 'jquery';

	// Cycle2
	if ( defined( 'BAILEY_SUFFIX' ) && '.min' === BAILEY_SUFFIX ) {
		wp_enqueue_script(
			'bailey-cycle2',
			trailingslashit( get_template_directory_uri() ) . 'js/lib/cycle2/jquery.cycle2' . BAILEY_SUFFIX . '.js',
			$script_dependencies,
			BAILEY_VERSION,
			true
		);
	} else {
		// Core script
		wp_enqueue_script(
			'bailey-cycle2',
			trailingslashit( get_template_directory_uri() ) . 'js/lib/cycle2/jquery.cycle2.js',
			$script_dependencies,
			'2.1.3',
			true
		);

		// Vertical centering
		wp_enqueue_script(
			'bailey-cycle2-center',
			trailingslashit( get_template_directory_uri() ) . 'js/lib/cycle2/jquery.cycle2.center.js',
			'bailey-cycle2',
			'20140121',
			true
		);

		// Swipe support
		wp_enqueue_script(
			'bailey-cycle2-swipe',
			trailingslashit( get_template_directory_uri() ) . 'js/lib/cycle2/jquery.cycle2.swipe.js',
			'bailey-cycle2',
			'20121120',
			true
		);
	}
	$script_dependencies[] = 'bailey-cycle2';

	// FitVids
	wp_enqueue_script(
		'bailey-fitvids',
		get_template_directory_uri() . '/js/lib/fitvids/jquery.fitvids' . BAILEY_SUFFIX . '.js',
		$script_dependencies,
		'1.1',
		true
	);
	bailey_localize_fitvids( 'bailey-fitvids' );
	$script_dependencies[] = 'bailey-fitvids';

	// Masonry and ImagesLoaded
	if ( is_front_page() || 'portfolio' === $view ) {
		$script_dependencies[] = 'masonry';

		wp_enqueue_script(
			'bailey-imagesloaded',
			get_template_directory_uri() . '/js/lib/imagesloaded/imagesloaded.pkgd' . BAILEY_SUFFIX . '.js',
			$script_dependencies,
			'3.1.8',
			true
		);
		$script_dependencies[] = 'bailey-imagesloaded';
	}

	// Filter script dependencies before enqueuing the Frontend script
	$script_dependencies = apply_filters( 'bailey_frontend_script_dependencies', $script_dependencies );

	// Frontend script
	wp_enqueue_script(
		'bailey-frontend',
		get_template_directory_uri() . '/js/frontend' . BAILEY_SUFFIX . '.js',
		$script_dependencies,
		BAILEY_VERSION,
		true
	);

	// Comment reply script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
endif;

add_action( 'wp_enqueue_scripts', 'bailey_scripts' );

if ( ! function_exists( 'bailey_localize_fitvids' ) ) :
/**
 * Pass data for FitVids script to a JS object.
 *
 * @since 1.0.0.
 *
 * @param  string    $handle    The registered ID of the FitVids script.
 * @return void
 */
function bailey_localize_fitvids( $handle ) {
	// Default selectors
	$selector_array = array(
		"iframe[src*='www.viddler.com']",
		"iframe[src*='money.cnn.com']",
		"iframe[src*='www.educreations.com']",
		"iframe[src*='//blip.tv']",
		"iframe[src*='//embed.ted.com']",
		"iframe[src*='//www.hulu.com']",
	);

	// Filter selectors
	$selector_array = apply_filters( 'bailey_fitvids_custom_selectors', $selector_array );

	// Compile selectors
	$fitvids_custom_selectors = array(
		'selectors' => implode( ',', $selector_array )
	);

	// Send to the script
	wp_localize_script(
		$handle,
		'baileyFitVids',
		$fitvids_custom_selectors
	);
}
endif;

if ( ! function_exists( 'bailey_head_late' ) ) :
/**
 * Add additional items to the end of the wp_head section of the document head.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_head_late() {
	// Favicon
	$logo_favicon = get_theme_mod( 'logo-favicon', bailey_get_default( 'logo-favicon' ) );
	if ( ! empty( $logo_favicon ) ) : ?>
		<link rel="icon" href="<?php echo esc_url( $logo_favicon ); ?>" />
	<?php endif;

	// Apple Touch Icon
	$logo_apple_touch = get_theme_mod( 'logo-apple-touch', bailey_get_default( 'logo-apple-touch' ) );
	if ( ! empty( $logo_apple_touch ) ) : ?>
		<link rel="apple-touch-icon" href="<?php echo esc_url( $logo_apple_touch ); ?>" />
	<?php endif;

	// Pingback
	?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php
}
endif;

add_action( 'wp_head', 'bailey_head_late', 99 );
