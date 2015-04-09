<?php
/**
 * @package Bailey
 */

?><!DOCTYPE html>
<!--[if lte IE 9]><html class="no-js IE9 IE" <?php language_attributes(); ?>><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="site-wrapper" class="site-wrapper">

	<header id="site-header" class="site-header" role="banner">
		
		<div class="site-branding">
			<?php if ( bailey_has_logo() ) : ?>
				<?php get_template_part( 'partials/site', 'logo' ); ?>
			<?php endif; ?>
			<?php if ( bailey_is_wpcom() || ! bailey_has_logo() ) : ?>
			<h1 class="site-title">
				<?php // Site title
				if ( get_bloginfo( 'name' ) ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				<?php endif; ?>
			</h1>
			<?php endif; ?>
			<?php // Tagline
			if ( get_bloginfo( 'description' ) ) : ?>
				<span class="site-description">
					<?php bloginfo( 'description' ); ?>
				</span>
			<?php endif; ?>
		</div>
			<div id="navigation">
			<ul>
				<li><a href="<?php echo get_settings('home'); ?>">about</a></li>
				<li><a href="wordpress/recipes/">design</a></li>
				<li><a href="wordpress/travel/">illustration</a></li>
				<li><a href="http://www.wordpress.org">contact</a></li>
			</ul>
			</div>
	</header>

	<div id="site-content" class="site-content">
		<div class="container">