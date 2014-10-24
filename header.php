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
//	$nav_menu_enabled = get_option('zd-enable-mobile-navigation');
//	$nav_menu_direction = get_option('zd-mobile-navigation-alignment');
//
//	$nav_class = 'cbp-spmenu cbp-spmenu-vertical';
//
//	if( $nav_menu_enabled ) {
//		$menu_toggle_id = 'showLeft';
//
//		if( $nav_menu_direction === 'left') {
//			$nav_class .= ' cbp-spmenu-left';
//			$menu_toggle_id = 'showRight';
//		}
//		else {
//
//			$nav_class .= ' cbp-spmenu-right';
//		}
//	}
//	else {
//		$nav_class = 'cbp-spmenu-disabled';
//		$menu_toggle_id = 'menu-disabled';
//	}



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

	<?php if($google_analytics_code = get_option('google-analytics-code')) : ?>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '<?php echo esc_attr($google_analytics_code); ?>', 'auto');
        ga('send', 'pageview');

    </script>

	<?php endif; ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<header class="header">

		<div class="header-content">

			<input type="checkbox" id="listener-mobile-navigation" onclick="toggleScroll()">
			<label for="listener-mobile-navigation" class="mobile-navigation-toggle"><i
					class="fa fa-bars fa-2x"></i></label>

			<div class="mobile-navigation-draw index-1">
				<label for="listener-mobile-navigation" class="mobile-navigation-toggle"><i
						class="fa fa-close fa-2x"></i></label>

				<div class="mobile-navigation-header"><a href="<?php echo site_url('/'); ?>" class="logo-raygun"></a></div>
				<div class="mobile-navigation-scroll">
					<!-- primary (mobile) menu-->
					<?php
					zd_get_menu('primary');
					 ?>
				</div>
			</div>

			<input type="checkbox" id="listener-mobile-search" onclick="toggleScroll()">
			<label for="listener-mobile-search" class="mobile-search-toggle"></label>

			<div class="mobile-search-draw index-1">
				<label for="listener-mobile-search" class="mobile-search-close"><i class="fa fa-close fa-2x"></i></label>
				<input class="mobile-search-input" type="text" placeholder="Search our blog">

				<h3>Filter by</h3>
				<ul>
					<li>
						<input type="checkbox" id="mobile-search-check-1">
						<label for="mobile-search-check-1">
							<span class="mobile-search-tick"></span>
							Product update</label>
					</li>
					<li>
						<input type="checkbox" id="mobile-search-check-2"><label for="mobile-search-check-2">
							<span class="mobile-search-tick"></span>
							Development</label>
					</li>
					<li>
						<input type="checkbox" id="mobile-search-check-3">
						<label for="mobile-search-check-3">
						<span class="mobile-search-tick"></span>
							Raygun labs</label>
					</li>
				</ul>
			</div>

			<a href="<?php echo site_url(); ?>" class="header-logo logo-raygun"><?php bloginfo('name'); ?></a>

			<div class="link-back"><i class="fa fa-angle-left fa-lg header-align header-margin-right"></i><a
					class="header-align" href="http://raygun.io">Back to Raygun</a></div>

			<?php
			if( get_option('show-search-form-in-header') ) {
				echo get_search_form();
			}
			?>

		</div>

	</header>
	<!-- main header ends -->

	<!-- Sub header that contains all the navigation for the blog except for search -->
	<nav class="header-sub">

		<div class="header-content">

			<?php

			if( is_page(array('Resources', 'resources', 41)) || is_tax('resource_category') ) {
				zd_get_menu('resources');
			}
			else {
				zd_get_menu('secondary');
			} ?>

			<a href="https://app.raygun.io/signup" class="button green promo" target="_blank">Get started for FREE</a>

		</div>

	</nav>
	<!-- sub header ends -->

	<div id="main-wrapper" class="site-main">
