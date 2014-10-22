<?php
/**
 * The Sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */
?>


<div id="content-secondary">

    <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> class="post">
            <div class="sidebar-features">
                <h3>Featured articles</h3>

                    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
                        <?php
                        if ( have_posts() ) :
                            // Start the Loop.
                            while ( have_posts() ) : the_post();

                                /*
                                 * Include the post format-specific template for the content. If you want to
                                 * use this in a child theme, then include a file called called content-___.php
                                 * (where ___ is the post format) and that will be used instead.
                                 */?>
                                <div class="sidebar-features-item">

                                    <?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() )
                                        . '"
                                                            rel="bookmark">',
                                        '</a></h4>' ); ?>
                                    <i class="fa fa-flask"></i><?php the_category(' '); ?><i class="fa fa-user"></i><?php the_author(); ?>
                                </div>

                            <?php endwhile;
                            // Previous/next post navigation.
                            zd_paging_nav();

                        else :
                            // If no content, include the "No posts found" template.
                            get_template_part( 'content', 'none' );

                        endif;
                        ?>
                    <?php endif; ?>
            </div>
            <?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
        </article><!-- #post-## -->
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    </div><!-- #primary-sidebar -->
</div><!-- #secondary -->
