<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

get_header(); ?>

<div id="main" class="row">
	<div id="primary" class="content-primary small-12 columns">
		<div id="content" class="site-content" role="main">

			<div class="page-content">
				<h1>404 :(</h1>

				<p><strong><?php _e('Cannot locate the requested page.', 'zingdesign'); ?></strong></p>

				<p>The page you requested was not found. It may no longer be available,
					or you might have typed the wrong address.</p>
				<p>Don’t worry, simply use the links below to browse through our website:</p>

				<ul class="error-list">
					<li><a href="<?php echo home_url(); ?>">Home</a></li>
				</ul>
				<?php zd_get_menu('raygun_mobile'); ?>
			</div><!-- .page-content -->

		</div><!-- #content -->
	</div><!-- #primary -->
</div>



<?php
//get_sidebar( 'content' );
//get_sidebar();
get_footer();
