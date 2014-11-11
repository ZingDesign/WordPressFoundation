<?php

?>
<div class="white-paper-card medium-4 columns">

    <div class="card-image white-paper-card-image"><?php zd_the_post_thumbnail( 'medium' ); ?></div>

	<div class="white-paper-card-content">

		<h3><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>

		<div class="entry-content">
			<?php the_excerpt();?>
		</div>

			<?php //zd_posted_on();

			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
				?>
				<div class="entry-meta white-paper-card-meta">

					<span class="comments-link"><?php comments_popup_link(
							__( 'Leave a comment', 'twentyfourteen' ),
							__( '1 Comment', 'twentyfourteen' ),
							__( '% Comments', 'twentyfourteen' )
						); ?></span>
				</div><!-- .white-papers-card-meta -->
			<?php
			endif;

			?>

		<?php edit_post_link( __( 'Edit', 'zingdesign' ), '<span class="edit-link">', '</span>' );?>
	</div>
</div><!--.white-papers-card-->