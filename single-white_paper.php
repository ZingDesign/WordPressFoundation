<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */


get_header(); ?>
<div id="main">

	<div class="row">
		<div id="white-paper-content" class="<?php primary_content_class(); ?>">

			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the post format-specific template for the content. If you want to
				 * use this in a child theme, then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

				// Previous/next post navigation.
				//zd_post_nav();

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
			endwhile;
			?>

		</div><!-- #content -->

		<div class="medium-4 columns">

			<div class="grey-box">
				<?php zd_get_white_paper_form(get_the_ID()); ?>
			</div>


		</div><!--.medium-4-->
	</div><!--.row-->

</div><!-- #main -->


<?php
get_footer();
