<?php
/**
 * @package Bailey
 */

if ( is_attachment() ) :
	$thumbnail_id = get_post()->ID;
	$thumbnail_size = 'bailey_large';
	$thumbnail_image = wp_get_attachment_image( $thumbnail_id, apply_filters( 'bailey_featured_image_size', $thumbnail_size ) );
else :
	$thumbnail_id = get_post_thumbnail_id();
	$thumbnail_size = ( bailey_is_image_large_enough( $thumbnail_id, array( 1042, 1 ), 'bailey_large' ) ) ? 'bailey_large' : 'large';
	$thumbnail_image = get_the_post_thumbnail( null, apply_filters( 'bailey_featured_image_size', $thumbnail_size ) );
endif;
?>

<?php if ( ! empty( $thumbnail_image ) ) : ?>
<figure class="entry-thumbnail">
	<?php if ( ! is_singular() ) : ?><a href="<?php the_permalink(); ?>" rel="bookmark"><?php endif; ?>
		<?php echo $thumbnail_image; ?>
	<?php if ( ! is_singular() ) : ?></a><?php endif; ?>
	<?php if ( is_singular() && has_excerpt( $thumbnail_id ) ) : ?>
	<figcaption class="entry-thumbnail-caption">
		<?php echo bailey_sanitize_text( get_post( $thumbnail_id )->post_excerpt ); ?>
	</figcaption>
	<?php endif; ?>
</figure>
<?php endif; ?>
