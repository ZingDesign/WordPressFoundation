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

	<?php if( get_option('favicon-image') ) : ?>
		<link rel="shortcut icon" href="<?php echo wp_get_attachment_url(get_option('favicon-image')); ?>" type="image/x-icon"/>
	<?php else : ?>
		<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico" type="image/x-icon"/>
	<?php endif; ?>
	<?php wp_head(); ?>


	<script type="text/javascript">if(!Modernizr.placeholder) {document.write('<script src="<?php echo get_template_directory_uri(); ?>/dist/js/zing-polyfill.min.js"><\/script>');}</script>


    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/dist/js/zing-polyfill.min.js"></script>
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

		<div class="header-content row">
			<div class="small-12 columns">

				<input aria-hidden="true" type="checkbox" id="listener-mobile-navigation" onclick="toggleScroll()">
				<label for="listener-mobile-navigation" class="mobile-navigation-toggle open">
					<span class="screen-reader-text"><?php _e('Menu', 'zingdesign'); ?></span>
				</label>

				<div aria-hidden="true" class="mobile-navigation-draw index-1">

					<div class="mobile-menu-inner-container">
						<label for="listener-mobile-navigation" class="mobile-navigation-toggle close">
							<span class="screen-reader-text"><?php _e('Close', 'zingdesign'); ?></span>
						</label>

						<div class="mobile-navigation-header"><a href="<?php echo home_url('/'); ?>" class="logo-raygun"></a></div>
						<div class="mobile-navigation-scroll">
							<!-- primary (mobile) menu-->
							<?php
							zd_get_menu('raygun_mobile');
							 ?>
						</div>

					</div><!-- .mobile-menu-inner-container -->
				</div><!-- mobile-navigation-draw -->

				<input aria-hidden="true" type="checkbox" id="listener-mobile-search" onclick="toggleScroll()">
				<label for="listener-mobile-search" class="mobile-search-toggle">
					<span class="screen-reader-text"><?php _e('Search', 'zingdesign'); ?></span>
				</label>

				<div aria-hidden="true" class="mobile-search-draw index-1">

					<div class="mobile-menu-inner-container">

						<form id="mobile-search-form" method="get" action="<?php echo home_url( '/' ); ?>">
							<label for="listener-mobile-search" class="mobile-search-close">
								<span class="screen-reader-text"><?php _e('Close', 'zingdesign'); ?></span>
							</label>
							<input name="s" class="mobile-search-input" type="text" placeholder="Search our blog">

							<h3><?php _e( 'Filter by category', 'zingdesign' ); ?></h3>

							<ul>
								<?php
								$categories = get_terms(
									array('category'),
									array('exclude' => get_cat_ID('Uncategorised') )
								);?>
								<li>
										<input type="radio" class="mobile-search-category" id="mobile-search-check-all" name="category_name" value="" checked>
										<label for="mobile-search-check-all">
											<span class="mobile-search-tick ticked"></span>
											<span class="mobile-search-label-text"><?php _e('All Categories'); ?></span>
										</label>
								</li>

								<?php
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

					</div><!-- .mobile-menu-inner-container -->

				</div><!-- mobile-search-draw -->

				<a href="http://raygun.io" class="header-logo logo-raygun"><?php bloginfo('name'); ?></a>

				<?php
	//			if( get_option('show-search-form-in-header') ) {
					echo get_search_form();
	//			}
				?>
			</div><!--.columns-->
		</div><!--.header-content-->

	</header>
	<!-- main header ends -->

	<!-- Sub header that contains all the navigation for the blog except for search -->
	<nav class="header-sub">

		<div class="header-content row">
			<div class="small-12 columns">

				<?php
	//			_d( zd_is_resource_category() );

				if( zd_is_resource_page() ) {
					zd_get_menu('raygun_resources');
				}
				else {
					zd_get_menu('raygun_blog');
				} ?>

				<a href="<?php echo esc_url(get_option('callout-button-url')); ?>" class="green promo button" target="_blank"><?php echo esc_attr(get_option('callout-button-text')) ?></a>
			</div>

		</div>

	</nav>
	<!-- sub header ends -->

	<div id="main-wrapper" class="site-main">
