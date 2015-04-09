<?php
/**
 * @package Bailey
 */

$footer_text = get_theme_mod( 'footer-text', bailey_get_default( 'footer-text' ) );
$social_links = bailey_get_social_links();
?>

		</div>
	</div>

	<footer id="site-footer" class="site-footer" role="contentinfo">
		<?php bailey_maybe_show_footer_widgets(); ?>

		<?php // Footer text
		if ( $footer_text ) : ?>
		<div class="footer-text">
			<?php echo bailey_sanitize_text( $footer_text ); ?>
		</div>
		<?php endif; ?>

		<div class="site-info">
			<a href="https://thethemefoundry.com/wordpress-themes/bailey">
				<em class="theme-name">Bailey WordPress template</em>
			</a>
			<span class="theme-by"><?php _ex( 'by', 'attribution', 'bailey' ); ?></span>
			<em class="theme-author">
				<a title="The Theme Foundry <?php esc_attr_e( 'homepage', 'bailey' ); ?>" href="https://thethemefoundry.com/">
					The Theme Foundry
				</a>
			</em>
		</div>

		<?php // Social profile links
		if ( ! empty( $social_links ) ) : ?>
		<ul class="footer-social-links">
			<?php foreach ( $social_links as $service => $details ) : ?>
			<li class="<?php echo esc_attr( $service ); ?>">
				<a href="<?php echo esc_url( $details['url'] ); ?>" title="<?php echo esc_attr( $details['title'] ); ?>">
					<span><?php echo esc_html( $details['title'] ); ?></span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</footer>
</div>

<?php wp_footer(); ?>

</body>
</html>