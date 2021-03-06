<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

get_header(); ?>


	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<div class="page-content">
				<p>The page you requested was not found. It may no longer be available,
                    or you might have typed the wrong address.</p>
                <p>Don’t worry, simply use the links below to browse through our website:</p>

                <ul class="error-list">
                    <li><a href="<?php echo home_url(); ?>">Home</a></li>
                </ul>
                <?php wp_nav_menu( array(
                    //                            'theme_location' => 'primary',
                    'menu' => 'Header Navigation',
                ) ); ?>
			</div><!-- .page-content -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php
//get_sidebar( 'content' );
//get_sidebar();
get_footer();
