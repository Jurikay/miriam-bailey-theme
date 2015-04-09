<?php
/**
 * @package Bailey
 */

$view = bailey_get_view();
$element = ( 'portfolio' === $view ) ? 'h4' : 'h2';
?>

<?php if ( get_the_title() && 'page' !== get_post_type() ) : ?>
<<?php echo $element; ?> class="entry-title">
	<?php if ( ! is_singular() || 'portfolio' === $view ) : ?><a href="<?php the_permalink(); ?>" rel="bookmark"><?php endif; ?>
		<?php the_title(); ?>
	<?php if ( ! is_singular() || 'portfolio' === $view ) : ?></a><?php endif; ?>
</<?php echo $element; ?>>
<?php endif; ?>