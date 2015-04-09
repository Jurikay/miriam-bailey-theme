<?php
/**
 * @package Bailey
 */
?>

<?php if ( bailey_is_wpcom() && function_exists( 'the_site_logo' ) ) : ?>
	<?php the_site_logo(); ?>
<?php else : ?>
	<div class="custom-logo">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<span><?php bloginfo( 'name' ); ?></span>
		</a>
	</div>
<?php endif; ?>