<?php
/**
 * @package Bailey
 */

// Posts and Pages
if ( is_singular() ) :
	the_content();

// Blog, Archives, Search Results
else :
	if ( has_excerpt() ) :
		echo wpautop( get_the_excerpt() . "\n\n" . bailey_get_read_more() );
	else :
		the_content( bailey_get_read_more( '', '' ) );
	endif;
endif;