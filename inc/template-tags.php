<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_has_logo' ) ) :
/**
 * Wrapper function to determine if a custom logo is set
 *
 * @since 1.0.4
 *
 * @return bool
 */
function bailey_has_logo() {
	if ( bailey_is_wpcom() && function_exists( 'has_site_logo' ) ) {
		return has_site_logo();
	} else if ( function_exists( 'bailey_get_logo' ) ) {
		return bailey_get_logo()->has_logo();
	} else {
		return false;
	}
}
endif;

if ( ! function_exists( 'bailey_is_preview' ) ) :
/**
 * Test if the current template is loading in the Customizer preview pane.
 *
 * @since 1.0.0.
 *
 * @return bool    True if within the preview pane.
 */
function bailey_is_preview() {
	global $wp_customize;
	return ( isset( $wp_customize ) && $wp_customize->is_preview() );
}
endif;

if ( ! function_exists( 'bailey_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since  1.0.0.
 *
 * @param  array    $comment    The current comment object.
 * @param  array    $args       The comment configuration arguments.
 * @param  mixed    $depth      Depth of the current comment.
 * @return void
 */
function bailey_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	if ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) : ?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<div class="comment-body">
			<?php _e( 'Pingback:', 'bailey' ); ?> <?php comment_author_link(); ?>
		</div>

	<?php else : ?>

	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'comment-parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<header class="comment-header">
				<?php // Avatar
				if ( 0 != $args['avatar_size'] ) :
					echo get_avatar( $comment, $args['avatar_size'] );
				endif;
				?>
				<div class="comment-date">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php
							printf(
								_x( '%1$s', 'date', 'bailey' ),
								get_comment_date()
							);
							?>
						</time>
					</a>
				</div>
				<div class="comment-author vcard">
					<?php
					printf(
						'%1$s <span class="says">%2$s</span>',
						sprintf(
							'<cite class="fn">%s</cite>',
							get_comment_author_link()
						),
						_x( 'says:', 'e.g. Bob says hello.', 'bailey' )
					);
					?>
				</div>

				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'bailey' ); ?></p>
				<?php endif; ?>
			</header>

			<div class="comment-content">
				<?php comment_text(); ?>
			</div>

			<?php
			comment_reply_link( array_merge( $args, array(
				'add_below' => 'div-comment',
				'depth'     => $depth,
				'max_depth' => $args['max_depth'],
				'before'    => '<footer class="comment-reply">',
				'after'     => '</footer>',
			) ) );
			?>
		</article>

	<?php endif;
}
endif;

if ( ! function_exists( 'bailey_comment_form_defaults' ) ) :
/**
 * Add theme specific defaults.
 *
 * @since  1.0.0.
 *
 * @param  array    $defaults    The default comment form values.
 * @return array                 The overriden comment form values.
 */
function bailey_comment_form_defaults( $defaults ) {
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );
	$html5     = current_theme_supports( 'html5', 'comment-form' );

	$fields =  array(
		'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'bailey' ) . '</label> ' .
			'<input placeholder="' . __( 'Name', 'bailey' ) . '" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
		'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email address', 'bailey' ) . '</label> ' .
			'<input placeholder="' . __( 'Email address', 'bailey' ) . '" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
		'url'    => '',
	);

	$defaults['fields']               = $fields;
	$defaults['comment_notes_before'] = '';
	$defaults['comment_field']        = '<p class="comment-form-comment"><label for="comment">' . _x( 'Message...', 'noun', 'bailey' ) . '</label> <textarea placeholder="' . __( 'Message...', 'bailey' ) . '"id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
	$defaults['comment_notes_after']  = '<p class="form-allowed-tags">' . __( 'Basic <abbr title="HyperText Markup Language">HTML</abbr> is allowed.', 'bailey' ) . '</p>';
	$defaults['label_submit']         = __( 'Comment', 'bailey' );

	return $defaults;
}
endif;

add_filter( 'comment_form_defaults', 'bailey_comment_form_defaults' );

if ( ! function_exists( 'bailey_categorized_blog' ) ) :
/**
 * Returns true if a blog has more than 1 category.
 *
 * @since  1.0.0.
 *
 * @return bool    Determine if the site has more than one active category.
 */
function bailey_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats, DAY_IN_SECONDS );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so bailey_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so bailey_categorized_blog should return false.
		return false;
	}
}
endif;

if ( ! function_exists( 'bailey_category_transient_flusher' ) ) :
/**
 * Flush out the transients used in bailey_categorized_blog.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_category_transient_flusher() {
	delete_transient( 'all_the_cool_cats' );
	bailey_categorized_blog();
}
endif;

add_action( 'edit_category', 'bailey_category_transient_flusher' );
add_action( 'save_post',     'bailey_category_transient_flusher' );

if ( ! function_exists( 'bailey_get_read_more' ) ) :
/**
 * Return a read more link
 *
 * Use '%s' as a placeholder for the post URL.
 *
 * @since  1.0.0.
 *
 * @param  string    $before    HTML before the text.
 * @param  string    $after     HTML after the text.
 * @return string               Full read more HTML.
 */
function bailey_get_read_more( $before = '<a class="read-more" href="%s">', $after = '</a>' ) {
	if ( strpos( $before, '%s' ) ) {
		$before = sprintf(
			$before,
			get_permalink()
		);
	}

	$more = apply_filters( 'bailey_read_more_text', __( 'Read more', 'bailey' ) );

	return $before . $more . $after;
}
endif;

if ( ! function_exists( 'bailey_is_image_large_enough' ) ) :
/**
 * Check if a particular size of an image from the media library meets minimum dimension requirements.
 *
 * @since 1.0.0.
 *
 * @param  int    $image_id   The image id.
 * @param  array  $min_size   The minimum width and height of the image.
 * @param  string $image_size The defined image size to use when analyzing the image.
 * @return array|bool         Image src, width, and height if true, otherwise false.
 */
function bailey_is_image_large_enough( $image_id, $min_size, $image_size = 'large' ) {
	// Get the actual dimensions of the closest generated thumbnail size
	$image = wp_get_attachment_image_src( $image_id, $image_size );

	// Fallback if wp_get_attachment_image_src doesn't return dimensions
	if ( ! isset( $image[1] ) || ! isset( $image[2] ) ) {
		// Get the filename
		$metadata = get_post_meta( $image_id, '_wp_attachment_metadata', true );
		if ( isset( $metadata['sizes'][$image_size] ) ) {
			// Use generated image size
			$file = $metadata['sizes'][$image_size]['path'];
		} else {
			// Use original image upload
			$image_size = 'full';
			$file = get_post_meta( $image_id, '_wp_attached_file', true );
		}

		// Look up the file
		$upload_path = wp_upload_dir();
		$file_path = $file;
		if ( $file_path && ! preg_match( "#{$upload_path['basedir']}#", $file ) ) {
			// Add base to relative path
			$file_path = trailingslashit( $upload_path['basedir'] ) . $file;
		}
		if ( $file_path ) {
			$image = getimagesize( $file_path );
			// Add image URL to beginning of array to mimic wp_get_attachment_image_src
			array_unshift( $image, trailingslashit( $upload_path['baseurl'] ) . $file );
		} else {
			return false;
		}
	}

	// It's large enough.
	if ( $image[1] >= $min_size[0] && $image[2] >= $min_size[1] ) {
		return $image;
	}

	// Not large enough.
	return false;
}
endif;

if ( ! function_exists( 'bailey_maybe_show_main_sidebar' ) ) :
/**
 * Determine whether the current view has the main sidebar enabled, and if so, display the markup
 *
 * @since 1.0.0.
 *
 * @return void
 */
function bailey_maybe_show_main_sidebar() {
	// Determine if current view has main sidebar enabled
	$view = bailey_get_view();
	$show_sidebar = absint( get_theme_mod( 'display-sidebar-' . $view, bailey_get_default( 'display-sidebar-' . $view ) ) );
	if ( 1 !== $show_sidebar ) {
		return;
	}

	get_sidebar( 'main' );
}
endif;

if ( ! function_exists( 'bailey_maybe_show_footer_widgets' ) ) :
/**
 * Determine whether the current view has footer widgets to show, and if so, display the markup
 *
 * @since 1.0.0.
 *
 * @return void
 */
function bailey_maybe_show_footer_widgets() {
	// Determine if current view has footer widgets enabled
	$view = bailey_get_view();
	$show_footer_widgets = absint( get_theme_mod( 'footer-widgets-' . $view, bailey_get_default( 'footer-widgets-' . $view ) ) );
	if ( 1 !== $show_footer_widgets ) {
		return;
	}

	// Sanitize sidebar count
	$sidebar_count = get_theme_mod( 'footer-widget-areas', bailey_get_default( 'footer-widget-areas' ) );
	$sidebar_count = bailey_sanitize_choice( $sidebar_count, 'footer-widget-areas' );

	// Test for enabled sidebars that contain widgets
	$has_active_sidebar = false;
	if ( $sidebar_count > 0 ) {
		$i = 1;
		while ( $i <= $sidebar_count ) {
			if ( is_active_sidebar( 'footer-' . $i ) ) {
				$has_active_sidebar = true;
				break;
			}
			$i++;
		}
	}

	if ( true === $has_active_sidebar ) : ?>
	<div class="footer-widget-container columns-<?php echo esc_attr( $sidebar_count ); ?>">
		<?php
		$current_sidebar = 1;
		while ( $current_sidebar <= $sidebar_count ) :
			get_sidebar( 'footer-' . $current_sidebar );
			$current_sidebar++;
		endwhile; ?>
	</div>
	<?php endif;
}
endif;

if ( ! function_exists( 'bailey_get_social_links' ) ) :
/**
 * Get the social links from options.
 *
 * @since  1.0.0.
 *
 * @return array    Keys are service names and the values are links.
 */
function bailey_get_social_links() {
	// Define default services; note that these are intentional non-translatable
	$default_services = array(
		'facebook' => array(
			'title' => 'Facebook',
			'class' => 'facebook',
		),
		'twitter' => array(
			'title' => 'Twitter',
			'class' => 'twitter',
		),
		'google-plus' => array(
			'title' => 'Google+',
			'class' => 'google-plus',
		),
		'linkedin' => array(
			'title' => 'LinkedIn',
			'class' => 'linkedin',
		),
		'instagram' => array(
			'title' => 'Instagram',
			'class' => 'instagram',
		),
		'flickr' => array(
			'title' => 'Flickr',
			'class' => 'flickr',
		),
		'youtube' => array(
			'title' => 'YouTube',
			'class' => 'youtube',
		),
		'vimeo' => array(
			'title' => 'Vimeo',
			'class' => 'vimeo',
		),
		'pinterest' => array(
			'title' => 'Pinterest',
			'class' => 'pinterest',
		),
		'fivehpx' => array(
			'title' => '500px',
			'class' => 'fivehpx',
		),
		'behance' => array(
			'title' => 'Behance',
			'class' => 'behance',
		),
		'dribbble' => array(
			'title' => 'Dribbble',
			'class' => 'dribbble',
		),
		'deviantart' => array(
			'title' => 'deviantArt',
			'class' => 'deviantart',
		),
		'smugmug' => array(
			'title' => 'SmugMug',
			'class' => 'smugmug',
		),
		'email' => array(
			'title' => __( 'Email', 'bailey' ),
			'class' => 'email',
		),
		'rss' => array(
			'title' => __( 'RSS', 'bailey' ),
			'class' => 'rss',
		),
	);

	// Set up the collector array
	$services_with_links = array();

	// Get the links for these services
	foreach ( $default_services as $service => $details ) {
		$url = get_theme_mod( 'social-' . $service, bailey_get_default( 'social-' . $service ) );
		if ( '' !== $url ) {
			$services_with_links[ $service ] = array(
				'title' => $details['title'],
				'url'   => $url,
				'class' => $details['class'],
			);
		}
	}

	// Special handling for RSS
	$hide_rss = (int) get_theme_mod( 'social-hide-rss', bailey_get_default( 'social-hide-rss' ) );
	if ( 0 === $hide_rss ) {
		$custom_rss = get_theme_mod( 'social-custom-rss', bailey_get_default( 'social-custom-rss' ) );
		if ( ! empty( $custom_rss ) ) {
			$services_with_links['rss']['url'] = $custom_rss;
		} else {
			$services_with_links['rss']['url'] = get_feed_link();
		}
	} else {
		unset( $services_with_links['rss'] );
	}

	// Properly set the email
	if ( isset( $services_with_links['email']['url'] ) ) {
		$services_with_links['email']['url'] = esc_url( 'mailto:' . $services_with_links['email']['url'] );
	}

	return apply_filters( 'bailey_social_links', $services_with_links );
}
endif;