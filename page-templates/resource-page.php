<?php
/**
 * Template Name: Resource Page
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

$post_id = get_the_ID();

$post_type = 'post';

$custom_meta = zd_metabox::zd_get_custom_meta($post_id, 'zd_posts');

//echo "<pre>";
//print_r( $custom_meta );
//echo "</pre>";

//if( get_post_meta($post_id, '') ) {
//	$post_type = zd_metabox::zd_get_custom_option($post_id);
//}

if( isset($custom_meta['post_container']) ) {
	$post_type = $custom_meta['post_type'];
}

$the_query = new WP_Query( array(
	'post_type' => $post_type
) );

get_header(); ?>

<div id="main-content" class="content-primary">

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">


		<?php

		var_dump($post_type);
			if ( $the_query->have_posts() ) :
				// Start the Loop.
				while ( have_posts() ) : the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', 'resource' );

				endwhile;
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
