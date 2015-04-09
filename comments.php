<?php
/**
 * @package Bailey
 */

if ( post_password_required() ) :
	return;
endif;
?>

<div id="comments" class="comments-area">
	<h3 class="bailey-comments-title">
		<?php _e( 'Comments', 'bailey' ); ?>
	</h3>
	<?php if ( have_comments() ) : ?>
		<?php if ( get_comment_pages_count() > 1 ) : ?>
		<nav id="comment-nav-above" class="comment-navigation" role="navigation">
			<span class="screen-reader-text"><?php _e( 'Comment navigation', 'bailey' ); ?></span>
			<?php paginate_comments_links(); ?>
		</nav>
		<?php endif; ?>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'avatar_size' => 48,
				'callback'    => 'bailey_comment'
			) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 ) : ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<span class="screen-reader-text"><?php _e( 'Comment navigation', 'bailey' ); ?></span>
			<?php paginate_comments_links(); ?>
		</nav>
		<?php endif; ?>

	<?php endif; ?>

	<?php if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
	<p class="no-comments">
		<?php _e( 'Comments are closed.', 'bailey' ); ?>
	</p>
	<?php endif; ?>

	<?php comment_form(); ?>
</div>
