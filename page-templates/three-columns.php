<?php
/**
 * Template Name: Three Columns
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

$current_page = get_post();
$page_name = depluralize($current_page -> post_name);
$current_post_type = "zd_".$page_name."s";
// var_dump($page_name);

get_header(); ?>

<div id="main-content" class="main-content">

<?php
	//if ( is_front_page() && zd_has_featured_posts() ) {
		// Include the featured content template.
	//	get_template_part( 'featured-content' );
	//}

?>

<!--    <div class="row">-->
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

            <ul class="medium-block-grid-3 small-block-grid-1">

                <?php
                    $the_query = new WP_Query(array(
                        "post_type"     => $current_post_type,
                        "orderby"       =>'menu_order',
                        "order"         =>'ASC',
                        "posts_per_page" => -1
                    ));
                    // Start the Loop.
                    while ( $the_query -> have_posts() ) : $the_query -> the_post();

                        // Include the page content template.
                        get_template_part( 'content', "block-list" );

                        // If comments are open or we have at least one comment, load up the comment template.
    //					if ( comments_open() || get_comments_number() ) {
    //						comments_template();
    //					}
                    endwhile;
                    wp_reset_postdata();
                ?>
            </ul>
		</div><!-- #content -->
<!--	</div>  -->
<!-- #primary -->
</div><!-- #main-content -->

<?php
// get_sidebar();
get_footer();
