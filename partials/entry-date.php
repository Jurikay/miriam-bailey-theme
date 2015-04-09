<?php
/**
 * @package Bailey
 */
?>

<time class="entry-date" datetime="<?php the_time( 'c' ); ?>">
	<span><?php _e( 'Published:', 'bailey' ); ?></span>
	<?php if ( ! is_singular() ) : ?><a href="<?php the_permalink(); ?>" rel="bookmark"><?php endif; ?>
	<?php echo get_the_date(); ?>
	<?php if ( ! is_singular() ) : ?></a><?php endif; ?>
</time>