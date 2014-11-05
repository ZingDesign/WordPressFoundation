<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> class="post">

	<header class="entry-header">
        <?php
			if ( is_single() ) :
				the_title( '<h2 class="entry-title">', '</h2>' );
			else :
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">',
                '</a></h2>' );
			endif;
		?>

		<div class="entry-meta">
            <?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) || in_array( 'resource_category', get_object_taxonomies( get_post_type() ) ) && zd_categorized_blog() ) : ?>
                <span class="cat-links"><?php get_category_icons(); ?></span>
            <?php
                endif;
				if ( in_array( get_post_type(), array('post', 'resource')) )
					zd_posted_on();

				if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
			?>

			<span class="comments-link"><?php comments_popup_link(
					__( 'Leave a comment', 'twentyfourteen' ),
					__( '1 Comment', 'twentyfourteen' ),
					__( '% Comments', 'twentyfourteen' )
				); ?></span>
			<?php
				endif;

				edit_post_link( __( 'Edit', 'zingdesign' ), '<span class="edit-link">', '</span>' );
			?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
        <?php
		zd_the_post_thumbnail();
        ?>
	<div class="entry-content">
        <?php
        if ( !is_single() ) :
            the_excerpt();
            ?> <a class="button blue" href="<?php echo get_permalink(); ?>">Continue reading</a>
        <?php else :
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'zingdesign' ) );
			wp_link_pages( array(
                'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'zingdesign' ) . '</span>',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
            ) );
            get_template_part( 'content', "social" );
        endif;
        ?>



	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
</article><!-- #post-## -->
