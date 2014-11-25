<?php
/**
 * Template Name: Recent Posts
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */
$args = array(
	'post_type'     => array('post', 'white_paper'),
	'post_status'   => 'publish'
);

$recent_posts = wp_get_recent_posts($args, OBJECT);

get_header(); ?>

<div id="main" class="">
	<div id="main-content" class="content-primary row">

		<div id="primary" class="content-area <?php primary_content_class(); ?>">
			<div id="content" class="site-content" role="main">

				<?php

				if ( !empty($recent_posts) ) :
					// Start the Loop.
					foreach($recent_posts as $post) :
						setup_postdata($post);

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

		<?php get_sidebar( 'content' ); ?>

	</div><!-- #main-content -->

</div><!-- #main -->




<?php
get_footer();
