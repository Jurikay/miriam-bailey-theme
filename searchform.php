<?php
/**
 * @package Bailey
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search&hellip;', 'placeholder', 'bailey' ); ?>" title="<?php esc_attr_e( 'Press Enter to submit your search', 'bailey' ) ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	</label>
</form>