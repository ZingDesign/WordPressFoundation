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
	<meta name="csrf-param" content="<?php echo wp_create_nonce( 'request_forgery_protection_token' ); ?>">
	<meta name="csrf-token" content="<?php echo wp_create_nonce( 'form_authenticity_token' ); ?>">

	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php if( get_option('favicon-image') ) : ?>
		<link rel="shortcut icon" href="<?php echo wp_get_attachment_url(get_option('favicon-image')); ?>" type="image/x-icon"/>
	<?php else : ?>
		<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico" type="image/x-icon"/>
	<?php endif; ?>
	<?php wp_head(); ?>


	<script type="text/javascript">if(!Modernizr.placeholder) {document.write('<script src="<?php echo get_template_directory_uri(); ?>/dist/js/zing-polyfill.min.js"><\/script>');}</script>


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

				<div class="mobile-navigation-header"><a href="<?php echo home_url('/'); ?>" class="logo-raygun"></a></div>
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
				<form id="mobile-search-form" method="get" action="<?php echo home_url( '/' ); ?>">
					<label for="listener-mobile-search" class="mobile-search-close"><i class="fa fa-close fa-2x"></i></label>
					<input name="s" class="mobile-search-input" type="text" placeholder="Search our blog">

					<h3><?php _e( 'Filter by category', 'zingdesign' ); ?></h3>

					<ul>
						<?php
						$categories = get_terms(
							array('category'),
							array('exclude' => get_cat_ID('Uncategorised') )
						);

						foreach($categories as $cat) : ?>
							<li>
								<input type="radio" class="mobile-search-category" id="mobile-search-check-<?php echo $cat->term_id; ?>" name="category_name" value="<?php echo esc_attr($cat->slug); ?>">
								<label for="mobile-search-check-<?php echo $cat->term_id; ?>">
									<span class="mobile-search-tick"></span>
									<span class="mobile-search-label-text"><?php echo esc_attr($cat->name); ?></span>
								</label>
							</li>

							<?php endforeach; ?>
					</ul>

					<button id="mobile-search-submit" type="submit"><?php _e( 'Search', 'zingdesign' ); ?></button>
				</form>

			</div>

			<a href="http://raygun.io" class="header-logo logo-raygun"><?php bloginfo('name'); ?></a>

			<div class="link-back">
				<a href="http://raygun.io">
					<i class="fa fa-angle-left fa-lg header-align header-margin-right"></i> <?php _e('Back to Raygun', 'zingdesign'); ?>
				</a>
			</div>

			<?php
//			if( get_option('show-search-form-in-header') ) {
				echo get_search_form();
//			}
			?>

		</div>

	</header>
	<!-- main header ends -->

	<!-- Sub header that contains all the navigation for the blog except for search -->
	<nav class="header-sub">

		<div class="header-content">

			<?php
//			_d( zd_is_resource_category() );

			if( zd_is_resource_page() ) {
				zd_get_menu('resources');
			}
			else {
				zd_get_menu('secondary');
			} ?>

			<a href="<?php esc_url(get_option('callout-button-url')); ?>" class="green promo button" target="_blank"><?php echo esc_attr(get_option('callout-button-text')) ?></a>

		</div>

	</nav>
	<!-- sub header ends -->

	<div id="main-wrapper" class="site-main">
