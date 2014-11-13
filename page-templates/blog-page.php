<?php
/**
 * Template Name: Blog Page
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

// Exclude Resources (and posts with categories that are child of Resources)
$exclude_cats = '-' . get_cat_ID('resource');

//$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;

$paged = 1;

if ( get_query_var('paged') ) {
	$paged = intval( get_query_var('paged') );
}
else if ( get_query_var('page') ) {
	$paged = intval( get_query_var('page') );
}

//_d($paged);

//$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

$blog_query = new WP_Query(array(
	'paged' => $paged,
	'cat'   => $exclude_cats
));

get_header(); ?>

	<div id="main" class="row">
		<div id="main-content" class="content-primary <?php primary_content_class(); ?>">

			<?php
			if ( is_front_page() && zd_has_featured_posts() ) {
				// Include the featured content template.
				get_template_part( 'featured-content' );
			}
			?>

			<div id="primary" class="content-area">
				<div id="content" class="site-content ajax-content-area" role="main">


					<?php
					if ( $blog_query->have_posts() ) :
						// Start the Loop.
						while ( $blog_query->have_posts() ) : $blog_query->the_post();

							/*
							 * Include the post format-specific template for the content. If you want to
							 * use this in a child theme, then include a file called called content-___.php
							 * (where ___ is the post format) and that will be used instead.
							 */
							get_template_part( 'content', get_post_format() );

						endwhile;

					else :
						// If no content, include the "No posts found" template.
						get_template_part( 'content', 'none' );

					endif;

					// Previous/next post navigation.
					zd_paging_nav( $blog_query->max_num_pages );
//					_d($paged);

//					wp_reset_query();
					wp_reset_postdata();
					?>

				</div><!-- #content -->
			</div><!-- #primary -->

		</div><!-- #main-content -->
		<?php get_sidebar( 'content' ); ?>
	</div><!-- #main-->




<?php
get_sidebar();
get_footer();

