
    <div class="resources-card-image">
	    <?php
	    if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
		    the_post_thumbnail();
	    }
	    ?>
    </div>

	<div class="resources-card-content">
		<div class="resources-card-meta">
			<?php
//			$current_post_type = get_post_type(get_the_ID());
//			$post_cats = get_categories($args);

			get_category_icons(get_the_ID());

			?>

		</div>
		<h3><?php the_title();?></h3>

		<div class="entry-content">
			<?php the_excerpt();?>
		</div>

		<div class="entry-meta resources-card-meta">
			<?php zd_posted_on();

			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
				?>

				<span class="comments-link"><i class="fa fa-comment"></i><?php comments_popup_link(
						__( 'Leave a comment', 'twentyfourteen' ),
						__( '1 Comment', 'twentyfourteen' ),
						__( '% Comments', 'twentyfourteen' )
					); ?></span>
			<?php
			endif;

			?>
		</div>

		<?php edit_post_link( __( 'Edit', 'zingdesign' ), '<span class="edit-link">', '</span>' );?>
	</div>