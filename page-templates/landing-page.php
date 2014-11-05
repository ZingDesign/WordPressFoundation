<?php
/**
 * Template Name: Landing Page
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

// get paged value manually
if ( get_query_var('paged') ) {
	$paged = get_query_var('paged');
}
elseif ( get_query_var('page') ) {
	$paged = get_query_var('page');
}
else {
	$paged = 1;
}

// get categories to display from post meta

$displayed_categories = zd_metabox::zd_get_custom_meta(get_the_ID(), 'zd_display_categories', 'categories_to_display');

$displayed_categories_string = '';

if( is_array($displayed_categories) && ! empty($displayed_categories) ) {
	$displayed_categories_string = implode(",", array_keys($displayed_categories) );
}

$landing_page_query = new WP_Query( array(
	//				'posts_per_page'    => 11,
	'cat'               => $displayed_categories_string,
	'paged'             => $paged
) );

get_header(); ?>
	<div class="content-wrapper landing-page" role="main">

		<div class="content-resources">
			<?php

			if ( $landing_page_query->have_posts() ) :
				// Start the Loop.
//				$resource_index = 0;
				while ( $landing_page_query->have_posts() ) : $landing_page_query->the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', 'resource' );

//					$resource_index ++;

				endwhile;

			else :
				// If no content, include the "No posts found" template.
				get_template_part( 'content', 'none' );

			endif;
			?>
		</div><!-- .resources-container -->

		<?php

		// Previous/next post navigation.
		zd_paging_nav($landing_page_query->max_num_pages);

		/* Restore original Post Data */
		wp_reset_postdata();
//			wp_reset_query();
		?>

	</div><!-- .content-wrapper -->


<?php
get_footer();
