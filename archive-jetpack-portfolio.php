<?php
/**
 * @package Bailey
 */

$show_section_title = (bool) get_theme_mod( 'portfolio-show-section-title', bailey_get_default( 'portfolio-show-section-title' ) );
$portfolio_columns = get_theme_mod( 'portfolio-archive-columns', bailey_get_default( 'portfolio-archive-columns' ) );
$portfolio_captions = get_theme_mod( 'portfolio-archive-captions', bailey_get_default( 'portfolio-archive-captions' ) );

// Sanitize choices
$portfolio_columns = bailey_sanitize_choice( $portfolio_columns, 'portfolio-archive-columns' );
$portfolio_captions = bailey_sanitize_choice( $portfolio_captions, 'portfolio-archive-captions' );

// Section header
ob_start();
if ( $show_section_title ) :
	get_template_part( 'partials/section', 'title' );
endif;
get_template_part( 'partials/section', 'description' );
$section_header = trim( ob_get_clean() );

get_header();
?>

<main id="site-main" class="site-main" role="main">
	<?php if ( have_posts() ) : ?>

		<?php if ( $section_header ) : ?>
		<header class="section-header">
			<?php echo $section_header; ?>
		</header>
		<?php endif; ?>

		<div id="portfolio-container" class="portfolio-container columns-<?php echo $portfolio_columns; ?> captions-<?php echo $portfolio_captions; ?>">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'partials/content', 'portfolio-archive' ); ?>
		<?php endwhile; ?>
			<div id="gutter-sizer"></div>
		</div>

		<?php get_template_part( 'partials/nav', 'paging' ); ?>

	<?php else : ?>
		<?php get_template_part( 'partials/content', 'none' ); ?>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
