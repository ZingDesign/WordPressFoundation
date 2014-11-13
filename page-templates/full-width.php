<?php
/**
 * Template Name: Full Width Page
 *
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

get_header(); ?>

<div id="main" class="main-full-width row">

	<?php
	if ( is_front_page() && zd_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
	?>

	<div id="primary" class="content-area small-12 columns">
		<div id="content" class="site-content" role="main">
			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

				// Include the page content template.
				get_template_part( 'content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( ( comments_open() || get_comments_number() ) && get_option('enable-comments-on-full-width') ) {
					comments_template();
				}
			endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

	<?php //get_sidebar(); ?>

</div><!-- #main -->

<?php
get_footer();
