<?php
/**
 * @package Bailey
 */

get_header();
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<header class="bailey-single-post-header entry-header">
	<div class="single-post-meta">
		<?php
			get_template_part( 'partials/entry', 'sticky' );
			get_template_part( 'partials/entry', 'date' );
			get_template_part( 'partials/entry', 'byline' );
			get_template_part( 'partials/nav', 'post' );
		?>
	</div>
	<?php
	get_template_part( 'partials/entry', 'title' );
	if ( bailey_is_image_large_enough( get_post_thumbnail_id(), array( 1042, 1 ), 'bailey_large' ) ) :
		get_template_part( 'partials/entry', 'thumbnail' );
	endif;
	?>
</header>

<main id="site-main" class="site-main" role="main">
	<?php get_template_part( 'partials/content', 'single' ); ?>
	<?php get_template_part( 'partials/content', 'comments' ); ?>
</main>
<?php endwhile; endif; ?>

<?php bailey_maybe_show_main_sidebar(); ?>

<?php get_footer(); ?>
