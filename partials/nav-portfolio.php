<?php
/**
 * @package Bailey
 */

$previous_link = get_next_post_link(
	'<div class="nav-previous">%link</div>',
	'%title'
);

$next_link = get_previous_post_link(
	'<div class="nav-next">%link</div>',
	'%title'
);

if ( '' !== $next_link || '' !== $previous_link ) : ?>
	<nav class="navigation post-navigation" role="navigation">
		<span class="screen-reader-text"><?php _e( 'Project navigation', 'bailey' ); ?></span>
		<div class="nav-links">
			<?php
			echo $previous_link;
			echo $next_link;
			?>
		</div>
	</nav>
<?php endif;