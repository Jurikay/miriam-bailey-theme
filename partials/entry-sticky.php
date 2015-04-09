<?php
/**
 * @package Bailey
 */
?>

<?php if ( is_sticky() && $sticky_label = get_theme_mod( 'display-sticky-label', bailey_get_default( 'display-sticky-label' ) ) ) : ?>
	<span class="sticky-post-label">
		<?php echo esc_html( wp_strip_all_tags( $sticky_label ) ); ?>
	</span>
<?php endif;