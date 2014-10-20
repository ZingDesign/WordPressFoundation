<?php
/**
 * Template Name: Home Page
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

get_header(); ?>
<!--<div class="row">-->

    <div id="main-content" class="main-content medium-8 columns">

        <div id="primary" class="content-area">
            <div id="content" class="site-content" role="main">
                <?php
                    // Start the Loop.
                    while ( have_posts() ) : the_post();

                        the_content();

                    endwhile;
                ?>
            </div><!-- #content -->
            <h2>Testimonials:</h2>
            <ul id="testimonial-section" class="medium-block-grid-2 small-block-grid-1">
                <?php get_sidebar('testimonials'); ?>
            </ul>
        </div><!-- #primary -->
    </div><!-- #main-content -->
    <div class="medium-3 columns">
        <?php get_sidebar( 'sidebar-1' ); ?>
    </div>
<!--</div>-->
    <!-- End of Row -->
<?php
// get_sidebar( 'home-page' );
get_footer();
