<?php
/**
 * @package Bailey
 */

global $post;
$mime_type = get_post_mime_type();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php
		if ( preg_match( '/image/', $mime_type ) ) :
			// Display attachment image
			get_template_part( 'partials/entry', 'thumbnail' );
		else :
			// Display attachment download link ?>
			<p><?php _e( 'Download this file:', 'bailey' ); ?></p>
			<p><a href="<?php echo esc_url( wp_get_attachment_url() ); ?>" class="bailey-download"><?php echo esc_html( basename( $post->guid ) ); ?></a></p>
		<?php endif; ?>
		<?php get_template_part( 'partials/entry', 'title' ); ?>
		<?php get_template_part( 'partials/entry', 'content' ); ?>
	</div>
</article>
