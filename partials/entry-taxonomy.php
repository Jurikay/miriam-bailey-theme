<?php
/**
 * @package Bailey
 */
?>

<?php if ( ( has_category() && bailey_categorized_blog() ) || has_tag() ) : ?>
	<?php
	$category_list   = get_the_category_list();
	$tag_list        = get_the_tag_list( '<ul class="post-tags"><li>', "</li>\n<li>", '</li></ul>' ); // Replicates category output
	$taxonomy_output = '';

	// Categories
	if ( $category_list ) :
		$taxonomy_output .= '%1$s';
	endif;

	// Tags
	if ( $tag_list ) :
		$taxonomy_output .= '%2$s';
	endif;

	// Output
	printf(
		$taxonomy_output,
		$category_list,
		$tag_list
	);
	?>
<?php endif; ?>