<?php
/**
 * @package Bailey
 */

$sidebar_id = esc_attr( apply_filters( 'bailey_sidebar_footer_3', 'footer-3' ) );
?>

<aside id="sidebar-footer-3" class="widget-area <?php echo $sidebar_id; ?> <?php echo ( is_active_sidebar( $sidebar_id ) ) ? 'active' : 'inactive'; ?>" role="complementary">
	<?php dynamic_sidebar( $sidebar_id ); ?>
</aside>