<?php
/**
 * @package Bailey
 */
?>

<div class="entry-author">
	<div class="entry-author-byline">
		<span><?php _e( 'Words:', 'bailey' ); ?></span>
		<?php
		printf(
			'<a class="vcard" href="%1$s">%2$s</a>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author_meta( 'display_name' ) )
		);
		?>
	</div>
	<?php if ( is_singular() && $author_bio = get_the_author_meta( 'description' ) ) : ?>
	<div class="entry-author-bio">
		<?php echo wpautop( bailey_sanitize_text( $author_bio ) ); ?>
	</div>
	<?php endif; ?>
</div>