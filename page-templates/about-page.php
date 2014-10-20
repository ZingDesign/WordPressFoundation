<?php
/**
 * Template Name: About Page
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

get_header(); ?>

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
                    endwhile;
                ?>

                <h2><?php sprintf(__('About %1$s', 'zingdesign'), get_bloginfo('name') ); ?></h2>

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

<?php get_footer();
