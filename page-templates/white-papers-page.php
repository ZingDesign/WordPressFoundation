<?php
/**
 * Template Name: White Papers Page
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

$paged = 1;

if ( get_query_var('paged') ) {
	$paged = intval( get_query_var('paged') );
}
else if ( get_query_var('page') ) {
	$paged = intval( get_query_var('page') );
}

$custom_post_type_query = new WP_Query( array(
	'paged'     => $paged,
	'post_type' => 'white_paper'
) );

get_header(); ?>
	<div id="main">

		<div class="row">
			<div class="small-12 columns">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</div>
		</div>

		<div class="row flex-row" role="main">
			<?php

			if ( $custom_post_type_query->have_posts() ) :
				// Start the Loop.
				while ( $custom_post_type_query->have_posts() ) : $custom_post_type_query->the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', 'white-paper' );

				endwhile;
				// Previous/next post navigation.
				zd_paging_nav();

			else :
				// If no content, include the "No posts found" template.
				get_template_part( 'content', 'none' );

			endif;

			/* Restore original Post Data */
			wp_reset_postdata();
			?>
		</div><!-- .resources-container -->

	</div><!-- .content-wrapper -->


<?php
get_footer();
