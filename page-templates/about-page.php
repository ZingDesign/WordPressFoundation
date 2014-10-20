<?php
/**
 * Template Name: About Page
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

get_header(); ?>
<!--<div class="row">-->

    <div id="main-content" class="main-content medium-8 columns">

    <?php
        if ( is_front_page() && zd_has_featured_posts() ) {
            // Include the featured content template.
            get_template_part( 'featured-content' );
        }
    ?>

        <div id="primary" class="content-area">
            <div id="content" class="site-content" role="main">
                <?php
                    // Start the Loop.
                    while ( have_posts() ) : the_post();

                        // Include the page content template.
                        get_template_part( 'content', 'page' );

                        // If comments are open or we have at least one comment, load up the comment template.
    //					if ( comments_open() || get_comments_number() ) {
    //						comments_template();
    //					}
                    endwhile;
                ?>

                <h2>Meet Leda</h2>

                <div id="about-team-members">
                    <ul class="medium-block-grid-2">
                        <?php get_sidebar( 'team-members'); ?>
                    </ul>
                </div>
            </div><!-- #content -->
        </div><!-- #primary -->
    </div><!-- #main-content -->

    <div class="medium-3 columns">
        <?php get_sidebar( 'sidebar-1' ); ?>
    </div>

<!--</div>-->
    <!-- End of row -->
<?php get_footer();
