<?php
/**
 * @package Bailey
 */
?>

<?php
if ( function_exists( 'wp_pagenavi' ) ) :
	wp_pagenavi( array( 'type' => 'multipart' ) );
else :
	wp_link_pages( array(
		'before' => '<nav class="entry-pagination">' . __( 'Pages:', 'bailey' ),
		'after'  => '</nav>',
	) );
endif;
?>