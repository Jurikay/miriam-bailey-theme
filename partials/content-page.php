<?php
/**
 * @package Bailey
 */

// Header
ob_start();
get_template_part( 'partials/entry', 'title' );
$entry_header = trim( ob_get_clean() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( $entry_header ) : ?>
	<header class="entry-header">
		<?php echo $entry_header; ?>
	</header>
	<?php endif; ?>

	<div class="entry-content">
		<?php get_template_part( 'partials/entry', 'content' ); ?>
		<?php get_template_part( 'partials/entry', 'pagination' ); ?>
	</div>
</article>
