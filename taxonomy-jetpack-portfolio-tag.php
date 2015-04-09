<?php
/**
 * @package Bailey
 */

$file = trailingslashit( get_template_directory() ) . 'archive-jetpack-portfolio.php';
if ( file_exists( $file ) ) :
	require( $file );
endif;