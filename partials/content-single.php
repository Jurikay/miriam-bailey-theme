<?php
/**
 * @package Bailey
 */

// Footer
ob_start();
get_template_part( 'partials/entry', 'taxonomy' );
$entry_footer = trim( ob_get_clean() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php
		if ( false === bailey_is_image_large_enough( get_post_thumbnail_id(), array( 1042, 1 ), 'bailey_large' ) ) :
			get_template_part( 'partials/entry', 'thumbnail' );
		endif;
		?>
		<?php get_template_part( 'partials/entry', 'content' ); ?>
		<?php get_template_part( 'partials/entry', 'pagination' ); ?>
	</div>

	<?php if ( $entry_footer ) : ?>
	<footer class="entry-footer">
		<?php echo $entry_footer; ?>
	</footer>
	<?php endif; ?>
</article>
