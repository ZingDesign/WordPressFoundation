<?php
/**
 * Template Name: Two Columns
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

$current_page = get_post();
$page_name = depluralize($current_page -> post_name);

$current_post_type = "zd_" . $page_name;

get_header(); ?>

    <div id="main-content" class="main-content medium-8 columns">

        <div class="row">

            <div class="medium-12 columns content">
                <?php
                // Start the Loop.
                while ( have_posts() ) : the_post();

                    // Include the page content template.
                    get_template_part( 'content', 'page' );
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
                    endwhile;
                    wp_reset_postdata();
                    ?>
            </div><!-- #content -->

        </div><!-- #primary -->

    </div><!-- #main-content -->

    <div class="medium-3 columns">

        <?php get_sidebar(); ?>

    </div>
<?php
//get_sidebar();
get_footer();
