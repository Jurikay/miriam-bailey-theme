<?php
/**
 * @package Bailey
 */

get_header();
?>

<main id="site-main" class="site-main" role="main">
	<article class="error-404 not-found">
		<header class="entry-header">
			<h3 class="entry-title">
				<?php _e( 'Oops! This page can&rsquo;t be found.', 'bailey' ); ?>
			</h3>
		</header>

		<div class="entry-content">
			<p>
				<?php _e( 'Maybe try searching this website:', 'bailey' ); ?>
			</p>
			<?php get_search_form(); ?>
		</div>
	</article>
</main>

<?php get_footer(); ?>