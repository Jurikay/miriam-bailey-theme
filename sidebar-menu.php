<?php
/**
 * @package Bailey
 */

$sidebar_id = esc_attr( apply_filters( 'bailey_sidebar_menu', 'sidebar-menu' ) );
?>

<?php if ( is_active_sidebar( $sidebar_id ) ) : ?>
<aside id="sidebar-menu" class="widget-area <?php echo $sidebar_id; ?>" role="complementary">
	<?php dynamic_sidebar( $sidebar_id ); ?>
</aside>
<?php endif; ?>