<?php
/**
 * Template name: Portfolio Template
 *
 * @package Bailey
 */

$show_section_title = (bool) get_theme_mod( 'portfolio-show-section-title', bailey_get_default( 'portfolio-show-section-title' ) );
$portfolio_captions = get_theme_mod( 'portfolio-archive-captions', bailey_get_default( 'portfolio-archive-captions' ) );
$portfolio_columns  = get_theme_mod( 'portfolio-archive-columns', bailey_get_default( 'portfolio-archive-columns' ) );

// Projects query
$query_args = array(
	'post_type'      => 'jetpack-portfolio',
	'posts_per_page' => 999,
);

// Self-hosted functionality
if ( ! bailey_is_wpcom() ) {
	$portfolio_columns = get_post_meta( get_the_ID(), 'bailey-attached-projects-page-columns', true );
	$projects = get_post_meta( get_the_ID(), 'bailey-attached-projects', true );
	// Protect against inefficient queries
	if ( ! empty( $projects ) ) {
		$query_args['post__in'] = (array) $projects;
		$query_args['orderby']  = 'post__in';
	} else {
		$query_args['p'] = 0;
	}
}

// Sanitize choices
$portfolio_columns = bailey_sanitize_choice( $portfolio_columns, 'portfolio-archive-columns' );
$portfolio_captions = bailey_sanitize_choice( $portfolio_captions, 'portfolio-archive-captions' );

get_header();
?>

<main id="site-main" class="site-main" role="main">
	<?php // Page content
	if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post();
			// Build the markup
			ob_start();
			if ( $show_section_title ) : ?>
				<h2 class="entry-title">
					<?php the_title(); ?>
				</h2>
			<?php endif;
			if ( get_the_content() ) : ?>
				<div class="section-description">
					<?php get_template_part( 'partials/entry', 'content' ); ?>
				</div>
			<?php endif;
			$page_content = trim( ob_get_clean() );
			// If there is any content/markup, output it
			if ( $page_content ) : ?>
			<header class="section-header">
				<?php echo $page_content; ?>
			</header>
			<?php endif; ?>
		<?php endwhile; ?>
	<?php endif; ?>

	<?php // Projects
	$project_query = new WP_Query( $query_args );
	if ( $project_query->have_posts() ) : ?>
		<div class="portfolio-container columns-<?php echo $portfolio_columns; ?> captions-<?php echo $portfolio_captions; ?>">
			<?php while ( $project_query->have_posts() ) : $project_query->the_post(); ?>
				<?php get_template_part( 'partials/content', 'portfolio-archive' ); ?>
			<?php endwhile; wp_reset_postdata(); ?>
			<div id="gutter-sizer"></div>
		</div>
	<?php endif; ?>
</main>

<?php get_footer(); ?>