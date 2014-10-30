<?php
global $resource_index;
$display_large = array(0,3,7,10);
$class = 'resources-card';
$thumb_size = 'resource-thumb';
if( in_array($resource_index, $display_large) ) {
	$class .= ' large';
	$thumb_size = 'resource-large';
}
?>
<div class="<?php echo $class; ?>">

    <div class="resources-card-image"><?php if ( has_post_thumbnail() ) : ?>
		    <a href="<?php the_permalink(); ?>">
			    <?php the_post_thumbnail( $thumb_size ); ?>
		    </a><?php endif; ?></div>

	<div class="resources-card-content">
		<div class="resources-card-meta">
			<?php
//			$current_post_type = get_post_type(get_the_ID());
//			$post_cats = get_categories($args);

			get_category_icons(get_the_ID());

			?>

		</div>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>

		<div class="entry-content">
			<?php the_excerpt();?>
		</div>

			<?php //zd_posted_on();

			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
				?>
				<div class="entry-meta resources-card-meta">

					<span class="comments-link"><i class="fa fa-comment"></i><?php comments_popup_link(
							__( 'Leave a comment', 'twentyfourteen' ),
							__( '1 Comment', 'twentyfourteen' ),
							__( '% Comments', 'twentyfourteen' )
						); ?></span>
				</div><!-- .resources-card-meta -->
			<?php
			endif;

			?>

		<?php edit_post_link( __( 'Edit', 'zingdesign' ), '<span class="edit-link">', '</span>' );?>
	</div>
</div><!--.resources-card-->