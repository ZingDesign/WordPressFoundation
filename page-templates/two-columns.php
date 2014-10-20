<?php
/**
 * Template Name: Two Columns
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

$current_page = get_post();
$page_name = depluralize($current_page -> post_name);

if( 'news' === $current_page -> post_name ) {
    $page_name = 'newsletter';
}

$current_post_type = "zd_".$page_name."s";

// Check to see which sidebar needs to be displayed
$sidebar = '';

if( is_page(23) ) {
    $sidebar = 'contact';
}

//var_dump( is_active_sidebar('sidebar-contact') );

get_header(); ?>
<!--<div class="row">-->

    <div id="main-content" class="main-content medium-8 columns">

        <?php
        //if ( is_front_page() && zd_has_featured_posts() ) {
        // Include the featured content template.
        //	get_template_part( 'featured-content' );
        //}

        ?>

        <div class="row">
            <div class="medium-12 columns content">
                <?php
                // Start the Loop.
                while ( have_posts() ) : the_post();

                    // Include the page content template.
                    get_template_part( 'content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
//                if ( comments_open() || get_comments_number() ) {
//                    comments_template();
//                }
                endwhile;
                wp_reset_postdata();
                ?>

                    <?php
                    $the_query = new WP_Query(array(
                        "post_type" => $current_post_type
                    ));
                    // Start the Loop.
                    while ( $the_query -> have_posts() ) : $the_query -> the_post();
                        // Include the page content template.
                        get_template_part( 'content', "row-list" );


                        // If comments are open or we have at least one comment, load up the comment template.
                        //					if ( comments_open() || get_comments_number() ) {
                        //						comments_template();
                        //					}
                    endwhile;
                    wp_reset_postdata();
                    ?>
            </div><!-- #content -->
        </div><!-- #primary -->
    </div><!-- #main-content -->
    <div class="medium-3 columns">

        <?php get_sidebar( $sidebar ); ?>
    </div>
<!--</div>--><!-- End of Row -->
<?php
//get_sidebar();
get_footer();
