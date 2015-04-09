<?php
/**
 * @package Bailey
 */
?>

<h4 class="section-title">
	<?php
	if ( is_archive() ) :
		if ( is_category() ) :
			printf(
				__( 'From %s', 'bailey' ),
				'<span>' . single_cat_title( '', false ) . '</span>'
			);

		elseif ( is_tag() ) :
			printf(
				__( 'Tagged %s', 'bailey' ),
				'<span>' . single_tag_title( '', false ) . '</span>'
			);

		elseif ( is_day() ) :
			printf(
				__( 'From %s', 'bailey' ),
				'<span>' . get_the_date() . '</span>'
			);

		elseif ( is_month() ) :
			printf(
				__( 'From %s', 'bailey' ),
				'<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'bailey' ) ) . '</span>'
			);

		elseif ( is_year() ) :
			printf(
				__( 'From %s', 'bailey' ),
				'<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'bailey' ) ) . '</span>'
			);

		elseif ( is_author() ) :
			printf(
				__( 'By %s', 'bailey' ),
				'<span class="vcard">' . get_the_author() . '</span>'
			);

		elseif ( is_post_type_archive( 'jetpack-portfolio' ) ) :
			printf(
				'<span>%s</span>',
				post_type_archive_title( null, false )
			);

		elseif ( is_tax( 'jetpack-portfolio-type' ) || is_tax( 'jetpack-portfolio-tag' ) ) :
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			printf(
				'<span>%s</span>',
				esc_html( $term->name )
			);

		else :
			_e( 'Archive', 'bailey' );

		endif;

	elseif ( is_search() ) :
		printf(
			__( 'Search for %s', 'bailey' ),
			'<span class="search-keyword">' . get_search_query() . '</span>'
		);
		printf(
			' &#45; <span class="search-result">%s</span>',
			sprintf(
				_n( '%s result found', '%s results found', absint( $wp_query->found_posts ), 'bailey' ),
				absint( $wp_query->found_posts )
			)
		);

	endif;
	?>
</h4>