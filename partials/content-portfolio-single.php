<?php
/**
 * @package Bailey
 */

// Footer
ob_start();
get_template_part( 'partials/portfolio', 'taxonomy' );
$entry_footer = trim( ob_get_clean() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php get_template_part( 'partials/entry', 'content' ); ?>
		<?php get_template_part( 'partials/entry', 'pagination' ); ?>
	</div>

	<?php if ( ! empty( $entry_footer ) ) : ?>
		<footer class="entry-footer">
			<?php echo $entry_footer; ?>
		</footer>
	<?php endif; ?>
</article>
