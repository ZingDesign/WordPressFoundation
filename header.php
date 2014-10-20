<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="no-js ie ie7 lt-ie9 lt-ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="no-js ie ie8 lt-ie9" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html class="no-js" <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico" type="image/x-icon"/>

	<?php wp_head(); ?>


    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/polyfill/ie.min.js"></script>
    <![endif]-->

	<?php if($google_analytics_code = get_option('google_analytics_code')) : ?>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', <?php echo esc_attr($google_analytics_code); ?>, 'auto');
        ga('send', 'pageview');

    </script>

	<?php endif; ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<div id="header-container">
		<header class="masthead container">

			<a class="header-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo('name'); ?></a>
			<nav id="cbp-spmenu-s1" class="top-menu cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" role="navigation" aria-label="Main menu">
				<a class="screen-reader-text skip-link" href="#content"><?php _e( 'Skip to content', 'zingdesign' ); ?></a>

				<!-- Top menu-->
				<?php wp_nav_menu( array(
					'theme_location'    => 'primary',
//					'menu'              => 'Header Navigation',
					'menu_class'        => 'nav-menu',
					'walker'            => new Zing_Design_Nav_Menu()
				) ); ?>

			</nav>

			<a id="showLeft" class="burger-icon"><i class="fa fa-bars"></i></a>


		</header>
	</div>

	<div id="main" class="site-main row">