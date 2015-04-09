<?php
/**
 * @package Bailey
 */

get_header();
?>

<main id="site-main" class="site-main" role="main">
<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'partials/content', 'attachment' ); ?>

		<?php get_template_part( 'partials/content', 'comments' ); ?>

	<?php endwhile; ?>

<?php endif; ?>
</main>

<?php get_footer(); ?>
