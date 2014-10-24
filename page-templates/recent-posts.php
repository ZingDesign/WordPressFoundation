<?php
/**
 * Template Name: Recent Posts
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */
$args = array(
	'post_type' => 'any'
);

$recent_posts = wp_get_recent_posts($args, OBJECT);

//_d($recent_posts);

get_header(); ?>

	<div id="main-content" class="content-primary">

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">


				<?php

				if ( !empty($recent_posts) ) :
					// Start the Loop.
					foreach($recent_posts as $the_post) :
						setup_postdata($the_post);

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );

					endforeach;

					wp_reset_postdata();

					// Previous/next post navigation.
					zd_paging_nav();

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
				?>

			</div><!-- #content -->
		</div><!-- #primary -->

	</div><!-- #main-content -->


<?php
get_footer();
