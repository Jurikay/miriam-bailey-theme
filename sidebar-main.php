<?php
/**
 * @package Bailey
 */

$sidebar_id = esc_attr( apply_filters( 'bailey_sidebar_main', 'sidebar-main' ) );
?>
<aside id="sidebar-main" class="widget-area <?php echo $sidebar_id; ?>" role="complementary">
<?php if ( is_active_sidebar( $sidebar_id ) ) : ?>
	<?php dynamic_sidebar( $sidebar_id ); ?>
<?php else : ?>
	<?php
	// When using `the_widget()` defaults are used, not the ones defined for the sidebar. Restore those here.
	$args = array(
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	);
	the_widget( 'WP_Widget_Categories', array(), $args );
	the_widget( 'WP_Widget_Archives', array(), $args );
	?>
<?php endif; ?>
</aside>