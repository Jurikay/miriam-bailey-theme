<?php
/**
 * @package Bailey
 */

if ( ! function_exists( 'bailey_css_add_rules' ) ) :
/**
 * Process user options to generate CSS needed to implement the choices.
 *
 * This function reads in the options from theme mods and determines whether a CSS rule is needed to implement an
 * option. CSS is only written for choices that are non-default in order to avoid adding unnecessary CSS. All options
 * are also filterable allowing for more precise control via a child theme or plugin.
 *
 * Note that all CSS for options is present in this function except for the CSS for fonts and the logo, which require
 * a lot more code to implement.
 *
 * @since  1.0.0.
 *
 * @return void
 */
function bailey_css_add_rules() {
	/**
	 * Background
	 */
	$background_color = maybe_hash_hex_color( get_theme_mod( 'background_color', bailey_get_default( 'background_color' ) ) );

	if ( $background_color !== bailey_get_default( 'background_color' ) ) {
		bailey_get_css()->add( array(
			'selectors'    => array( 'body.mce-content-body' ),
			'declarations' => array(
				'background-color' => $background_color
			)
		) );
	}
	// Override rules set via JS in the Customizer preview pane
	if ( bailey_is_preview() ) {
		bailey_get_css()->add( array(
			'selectors'    => array( 'body.custom-background' ),
			'declarations' => array(
				'background-color' => $background_color . ' !important'
			)
		) );
	}

	/**
	 * Fonts
	 */
	if ( ! bailey_is_wpcom() && function_exists( 'glyphs_get_typekit_fonts' ) ) {
		// Get and escape options
		$font_main = bailey_sanitize_choice( get_theme_mod( 'font-main', bailey_get_default( 'font-main' ) ), 'font-main' );
		$font_accent = bailey_sanitize_choice( get_theme_mod( 'font-accent', bailey_get_default( 'font-accent' ) ), 'font-accent' );

		$fonts = glyphs_get_typekit_fonts();

		// Main
		if ( $font_main !== bailey_get_default( 'font-main' ) && isset( $fonts[$font_main] ) ) {
			bailey_get_css()->add( array(
				'selectors'    => array(
					'body', 'blockquote cite', 'textarea',
					'input[type="date"]', 'input[type="datetime"]',
					'input[type="datetime-local"]', 'input[type="email"]',
					'input[type="month"]', 'input[type="number"]',
					'input[type="password"]', 'input[type="search"]',
					'input[type="tel"]', 'input[type="text"]',
					'input[type="time"]', 'input[type="url"]',
					'input[type="week"]'
				),
				'declarations' => array(
					'font-family' => $fonts[$font_main]['stack']
				)
			) );
		}

		// Accent
		if ( $font_accent !== bailey_get_default( 'font-accent' ) && isset( $fonts[$font_accent] ) ) {
			bailey_get_css()->add( array(
				'selectors'    => array(
					'.site-title', '.special'
				),
				'declarations' => array(
					'font-family' => $fonts[$font_accent]['stack']
				)
			) );
		}
	}

	/**
	 * Colors
	 */
	// Get and escape options
	$color_accent  = maybe_hash_hex_color( get_theme_mod( 'color-accent', bailey_get_default( 'color-accent' ) ) );
	$color_detail1 = maybe_hash_hex_color( get_theme_mod( 'color-detail1', bailey_get_default( 'color-detail1' ) ) );
	$color_detail2 = maybe_hash_hex_color( get_theme_mod( 'color-detail2', bailey_get_default( 'color-detail2' ) ) );
	$color_main    = maybe_hash_hex_color( get_theme_mod( 'color-main', bailey_get_default( 'color-main' ) ) );

	// Accent
	if ( $color_accent !== bailey_get_default( 'color-accent' ) ) {
		bailey_get_css()->add( array(
			'selectors'    => array( '.color-accent-text', 'a', '.widget a:hover', '.site-info a:hover', '.portfolio-container article:hover .entry-title a', '.portfolio-types a:hover', '.portfolio-tags a:hover', '.post-categories a:hover', '.post-tags a:hover' ),
			'declarations' => array(
				'color' => $color_accent
			)
		) );
		bailey_get_css()->add( array(
			'selectors'    => array( '.color-accent-background', '.portfolio-container.captions-solid .entry-thumbnail-frame' ),
			'declarations' => array(
				'background-color' => $color_accent
			)
		) );
		bailey_get_css()->add( array(
			'selectors'    => array( '.color-accent-border', '.portfolio-container.captions-frame .entry-thumbnail-frame' ),
			'declarations' => array(
				'border-color' => $color_accent
			)
		) );
	}
	// Detail 1
	if ( $color_detail1 !== bailey_get_default( 'color-detail1' ) ) {
		bailey_get_css()->add( array(
			'selectors'    => array( '.color-detail1-text' ),
			'declarations' => array(
				'color' => $color_detail1
			)
		) );
		bailey_get_css()->add( array(
			'selectors'    => array( '.color-detail1-background', '.comment.bypostauthor > .comment-body', '.comment-reply', '#bailey-bar', '.sticky-post-label' ),
			'declarations' => array(
				'background-color' => $color_detail1
			)
		) );
	}
	// Detail 2
	if ( $color_detail2 !== bailey_get_default( 'color-detail2' ) ) {
		bailey_get_css()->add( array(
			'selectors'    => array( '.color-detail2-text', '.wp-caption', '.wp-caption-text', '.entry-thumbnail-caption', '.comment-date a', '.comment-reply a', '.form-allowed-tags', '.blog .entry-date', '.blog .entry-date a','.blog .entry-author', '.blog .entry-author a', '.page-template-default .entry-date', '.page-template-default .entry-date a', '.page-template-default .entry-author', '.page-template-default .entry-author a', '.search .entry-date', '.search .entry-date a', '.search .entry-author', '.search .entry-author a', '.archive .entry-date', '.archive .entry-date a', '.archive .entry-author',  '.archive .entry-author a', '.widget', '#bailey-bar .site-navigation .menu a', '.footer-text', '.site-info', '.sticky-post-label', '.portfolio-types', '.portfolio-tags', '.post-categories', '.post-tags', '.portfolio-types a', '.portfolio-tags a', '.post-categories a', '.post-tags a', '.single .entry-date', '.single .entry-author', '.single .entry-date a', '.single .entry-author a' ),
			'declarations' => array(
				'color' => $color_detail2
			)
		) );
		bailey_get_css()->add( array(
			'selectors'    => array( '.color-detail2-background' ),
			'declarations' => array(
				'background-color' => $color_detail2
			)
		) );
	}
	// Main
	if ( $color_main !== bailey_get_default( 'color-main' ) ) {
		bailey_get_css()->add( array(
			'selectors'    => array( 'body', '.color-main-text', 'h1 a', 'h2 a', 'h3 a', 'h4 a', 'h5 a', 'h6 a', 'a h1', 'a h2', 'a h3', 'a h4', 'a h5', 'a h6', '.widget_calendar table thead', '.widget-title', '.widget-title a', '.widget a', '#bailey-bar .site-navigation .menu li a:hover', '#bailey-bar .site-navigation .menu li.current_page_item > a', '#bailey-bar .site-navigation .menu li.current-menu-item > a', '.portfolio-container .entry-title a', 'textarea', 'input[type="date"]', 'input[type="datetime"]', 'input[type="datetime-local"]', 'input[type="email"]', 'input[type="month"]', 'input[type="number"]', 'input[type="password"]', 'input[type="search"]', 'input[type="tel"]', 'input[type="text"]', 'input[type="time"]', 'input[type="url"]', 'input[type="week"]' ),
			'declarations' => array(
				'color' => $color_main
			)
		) );
		// These placeholder selectors have to be isolated in individual rules.
		// See http://css-tricks.com/snippets/css/style-placeholder-text/#comment-96771
		bailey_get_css()->add( array(
			'selectors'    => array( '::-webkit-input-placeholder' ),
			'declarations' => array(
				'color' => $color_main
			)
		) );
		bailey_get_css()->add( array(
			'selectors'    => array( ':-moz-placeholder' ),
			'declarations' => array(
				'color' => $color_main
			)
		) );
		bailey_get_css()->add( array(
			'selectors'    => array( '::-moz-placeholder' ),
			'declarations' => array(
				'color' => $color_main
			)
		) );
		bailey_get_css()->add( array(
			'selectors'    => array( ':-ms-input-placeholder' ),
			'declarations' => array(
				'color' => $color_main
			)
		) );
		bailey_get_css()->add( array(
			'selectors'    => array( '.color-main-background', 'tt', 'kbd', 'pre', 'code', 'samp', 'var', 'button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', 'a.bailey-button', 'a.bailey-download' ),
			'declarations' => array(
				'background-color' => $color_main
			)
		) );
		bailey_get_css()->add( array(
			'selectors'    => array( '.color-main-background', 'textarea', 'input[type="date"]', 'input[type="datetime"]', 'input[type="datetime-local"]', 'input[type="email"]', 'input[type="month"]', 'input[type="number"]', 'input[type="password"]', 'input[type="search"]', 'input[type="tel"]', 'input[type="text"]', 'input[type="time"]', 'input[type="url"]', 'input[type="week"]', '.widget li', '.single-post .entry-header .single-post-meta', 'table', 'table td', 'hr' ),
			'declarations' => array(
				'border-color' => $color_main
			)
		) );
	}
}
endif;

add_action( 'bailey_css', 'bailey_css_add_rules' );
