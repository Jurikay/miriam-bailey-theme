<?php
/**
 * @package Bailey
 */

// Header
ob_start();
get_template_part( 'partials/entry', 'sticky' );
get_template_part( 'partials/entry', 'title' );
get_template_part( 'partials/entry', 'thumbnail' );
$entry_header = trim( ob_get_clean() );

// Footer
ob_start();
get_template_part( 'partials/entry', 'date' );
get_template_part( 'partials/entry', 'author' );
get_template_part( 'partials/entry', 'taxonomy' );
$entry_footer = trim( ob_get_clean() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( $entry_header ) : ?>
	<header class="entry-header">
		<?php echo $entry_header; ?>
	</header>
	<?php endif; ?>

	<div class="entry-content">
		<?php get_template_part( 'partials/entry', 'content' ); ?>
	</div>

	<?php if ( $entry_footer ) : ?>
	<footer class="entry-footer">
		<?php echo $entry_footer; ?>
	</footer>
	<?php endif; ?>
</article>
