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