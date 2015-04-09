<?php
/**
 * @package Bailey
 */

global $post;
$view = bailey_get_view();

$show_taxonomies = (int) get_theme_mod( 'portfolio-show-taxonomies', bailey_get_default( 'portfolio-show-taxonomies' ) );
?>

<?php if ( 1 === $show_taxonomies && ( has_term( null, 'jetpack-portfolio-type' ) || has_term( null, 'jetpack-portfolio-tag' ) ) ) : ?>
	<?php
	$type_list = get_the_term_list( $post->ID, 'jetpack-portfolio-type', '<ul class="portfolio-types"><li>', "</li>\n<li>", '</li></ul>' );
	$tag_list = get_the_term_list( $post->ID, 'jetpack-portfolio-tag', '<ul class="portfolio-tags"><li>', "</li>\n<li>", '</li></ul>' );
	if ( 'portfolio' === $view ) {
		$tag_list = '';
	}

	$taxonomy_output = '';

	// Categories
	if ( $type_list ) :
		$taxonomy_output .= '%1$s';
	endif;

	// Tags
	if ( $tag_list ) :
		$taxonomy_output .= '%2$s';
	endif;

	// Output
	printf(
		$taxonomy_output,
		$type_list,
		$tag_list
	);
	?>
<?php endif; ?>