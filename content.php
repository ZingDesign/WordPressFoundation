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
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">',
                '</a></h1>' );
			endif;
		?>

		<div class="entry-meta">
            <?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && zd_categorized_blog() ) : ?>
                <span class="cat-links"><i class="fa fa-flask"></i><?php echo get_the_category_list( _x( ', ',
                        'Used between list items,
                there is a space after the comma.', 'zingdesign' ) ); ?></span>
            <?php
                endif;
				if ( 'post' == get_post_type() )
					zd_posted_on();

				if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
			?>

			<span class="comments-link"><i class="fa fa-comment"></i><?php comments_popup_link(  __( '1 Comment',
                        'zingdesign' ),
                    __( '% Comments', 'zingdesign' ) ); ?></span>
			<?php
				endif;

//				edit_post_link( __( 'Edit', 'zingdesign' ), '<span class="edit-link">', '</span>' );
			?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
        <?php
        if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
            the_post_thumbnail();
        }
        ?>
	<div class="entry-content">
        <?php

			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'zingdesign' ) );
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'zingdesign' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
		?>
        <button class="button blue">Continue reading</button>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
</article><!-- #post-## -->
