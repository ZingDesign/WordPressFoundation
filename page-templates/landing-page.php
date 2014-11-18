<?php
/**
 * Template Name: Landing Page
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

// get paged value manually

$paged = 1;

if ( get_query_var('paged') ) {
	$paged = intval( get_query_var('paged') );
}
else if ( get_query_var('page') ) {
	$paged = intval( get_query_var('page') );
}

// get categories to display from post meta

$displayed_categories = zd_metabox::zd_get_custom_meta(get_the_ID(), 'zd_display_categories', 'categories_to_display');

$displayed_categories_string = '';

if( is_array($displayed_categories) && ! empty($displayed_categories) ) {
	$displayed_categories_string = implode(",", array_keys($displayed_categories) );
}

$allowed_post_types = array( 'post', 'white_paper' );

$landing_posts_per_page = get_option('landing-posts-per-page') ? intval( get_option('landing-posts-per-page') ) : 7;

$landing_page_query = new WP_Query( array(
	'posts_per_page'    => $landing_posts_per_page,
	'post_type'         => $allowed_post_types,
	'paged'             => $paged,
	'cat'               => $displayed_categories_string
) );


$large_posts = array();
if( get_transient('landing_page_large_posts') && ( $landing_posts_per_page === get_transient('landing_posts_per_page') ) ) {
	$large_posts = get_transient('landing_page_large_posts');
}
else {

	for($i = 0; $i <= $landing_posts_per_page; $i++) {

		if( $i % 10 === 0 || ($i - 6) % 10 === 0 ) {
			$large_posts[] = $i;
		}

	}
	set_transient('landing_page_large_posts', $large_posts );
	set_transient('landing_posts_per_page', $landing_posts_per_page );
}
//_d( $large_posts );

get_header(); ?>
	<div id="landing-page-container" class="content-wrapper landing-page" role="main">

		<div id="resources-container" class="<?php echo get_option('enable-ajax-pagination') ? 'ajax-content-area' : ''; ?>">

			<div class="content-resources row flex-row">
				<?php

				if ( $landing_page_query->have_posts() ) :
					// Start the Loop.
					$resource_index = 0;
//					$element_index = 1;
//					$state_switch = 0;

					while ( $landing_page_query->have_posts() ) : $landing_page_query->the_post();

//						_d( );

						$is_large = ( in_array($resource_index, $large_posts) );
//						_d($state_switch);

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', 'resource' );

						$resource_index ++;
//						$element_index ++;

					endwhile;

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
				?>
			</div><!-- .content-resources -->

			<?php

			// Previous/next post navigation.
			zd_paging_nav($landing_page_query->max_num_pages);
//			_d($paged);

			/* Restore original Post Data */
			wp_reset_postdata();
			//			wp_reset_query();
			?>
		</div><!-- #resources-container -->



	</div><!-- .content-wrapper -->


<?php
get_footer();
