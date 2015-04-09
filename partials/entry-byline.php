<?php
/**
 * @package Bailey
 */
?>

<span class="entry-author">
	<span><?php _e( 'Words:', 'bailey' ); ?></span>
	<?php
	printf(
		'<a class="vcard" href="%1$s">%2$s</a>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author_meta( 'display_name' ) )
	);
	?>
</span>