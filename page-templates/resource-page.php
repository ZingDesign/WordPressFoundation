<?php
/**
 * Template Name: Resources Page
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */


get_header(); ?>
	<div class="content-wrapper" role="main">

		<div class="content-resources">
			<?php

			$resource_post_query = new WP_Query( array(
				'post_type' => 'resource'
			) );

			if ( $resource_post_query->have_posts() ) :
				// Start the Loop.
				$i = 0;
				$display_large = array(0,3);
				while ( $resource_post_query->have_posts() ) : $resource_post_query->the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					$class = 'resources-card';
					if( in_array($i, $display_large) ) {
						$class .= ' large';
					}
					echo "<div class=\"{$class}\">\n";
					get_template_part( 'content', 'resource' );
					echo "</div><!--.resources-card-->\n";

					$i ++;

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
