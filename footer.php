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
		
	</footer>
</div>

<?php wp_footer(); ?>

</body>
</html>