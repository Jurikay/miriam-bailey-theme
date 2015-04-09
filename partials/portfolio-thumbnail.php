<?php
/**
 * @package Bailey
 *
 * This partial is only used for the Portfolio archive view.
 */

$thumbnail_id = get_post_thumbnail_id();
$thumbnail_aspect = get_theme_mod( 'portfolio-archive-images', bailey_get_default( 'portfolio-archive-images' ) );
$thumbnail_aspect = bailey_sanitize_choice( $thumbnail_aspect, 'portfolio-archive-images' );
$thumbnail_size = ( 'none' === $thumbnail_aspect ) ? 'bailey_large' : 'bailey_' . $thumbnail_aspect;
$thumbnail_image = wp_get_attachment_image( $thumbnail_id, $thumbnail_size );
?>

<?php if ( ! empty( $thumbnail_image ) ) : ?>
	<figure class="entry-thumbnail portfolio-archive-thumbnail aspect-<?php echo $thumbnail_aspect; ?>">
		<a href="<?php the_permalink(); ?>" rel="bookmark">
			<div class="entry-thumbnail-frame"></div>
			<?php echo $thumbnail_image; ?>
		</a>
	</figure>
<?php endif; ?>
