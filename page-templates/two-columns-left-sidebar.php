<?php
/**
 * Template Name: Two Columns (Left sidebar)
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

get_header();?>

<div id="main">
	<?php get_sidebar(); ?>

	<div id="main-content" class="main-content">

		<?php
		if ( is_front_page() && zd_has_featured_posts() ) {
			// Include the featured content template.
			get_template_part( 'featured-content' );
		}
		?>
		<?php get_sidebar( 'content' ); ?>
		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

				<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
				?>

			</div><!-- #content -->
		</div><!-- #primary -->
	</div><!-- #main-content -->
</div>


<?php
get_footer();
