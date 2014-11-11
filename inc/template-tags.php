<?php
/**
 * Custom template tags for Zing Design
 *
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

if ( ! function_exists( 'zd_paging_nav' ) ) : /**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since Zing Design 1.0
 */ {
	function zd_paging_nav( $max_num_pages = false ) {
		// Don't print empty markup if there's only one page.
		//	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		//		return;
		//	}

		//	_d($GLOBALS['wp_query']);

		// Custom pagination logic for static templates
		if ( $max_num_pages ) {
			$total = $max_num_pages;
		} else if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		} else {
			$total = $GLOBALS['wp_query']->max_num_pages;
		}

//		$paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

		$paged = 1;

		if ( get_query_var('paged') ) {
			$paged = intval( get_query_var('paged') );
		}
		else if ( get_query_var('page') ) {
			$paged = intval( get_query_var('page') );
		}

//		_d( $paged );

//		_d(get_query_var('page') );

		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

		// Set up paginated links.
		$links = paginate_links( array(
			'base'      => $pagenum_link,
			'format'    => $format,
			'total'     => $total,
			'current'   => $paged,
			'mid_size'  => 1,
			'add_args'  => array_map( 'urlencode', $query_args ),
			'prev_text' => __( '&larr; Previous', 'zingdesign' ),
			'next_text' => __( 'Next &rarr;', 'zingdesign' ),
		) );

		if ( $links ) :

			?>
			<nav class="navigation paging-navigation" role="navigation">
				<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'zingdesign' ); ?></h1>

				<div class="pagination loop-pagination">
					<?php echo $links; ?>
				</div>
				<!-- .pagination -->
			</nav><!-- .navigation -->
		<?php
		endif;
	}
}
endif;

if ( ! function_exists( 'zd_post_nav' ) ) : /**
 * Display navigation to next/previous post when applicable.
 *
 * @since Zing Design 1.0
 */ {
	function zd_post_nav() {
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous ) {
			return;
		}

		?>
		<nav class="navigation post-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'zingdesign' ); ?></h1>

			<div class="nav-links">
				<?php
				if ( is_attachment() ) :
					previous_post_link( '%link', __( '<span class="meta-nav">Published In</span>%title', 'zingdesign' ) );
				else :
					previous_post_link( '%link', __( '<span data-tooltip aria-haspopup="true"  class="meta-nav" title="%title">Previous Post</span>', 'zingdesign' ) );
					next_post_link( '%link', __( '<span data-tooltip aria-haspopup="true"  class="meta-nav" title="%title">Next Post</span>', 'zingdesign' ) );
				endif;
				?>
			</div>
			<!-- .nav-links -->
		</nav><!-- .navigation -->
	<?php
	}
}
endif;

if ( ! function_exists( 'zd_posted_on' ) ) : /**
 * Print HTML with meta information for the current post-date/time and author.
 *
 * @since Zing Design 1.0
 */ {
	function zd_posted_on() {
		if ( is_sticky() && is_home() && ! is_paged() ) {
			echo '<span class="featured-post">' . __( 'Sticky', 'zingdesign' ) . '</span>';
		}

		// Set up and print post meta information.
		printf( ' <span class="byline"><span class="author vcard"><a class="url fn n" href="%4$s" rel="author">%5$s</a></span></span> <span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></span>',
			esc_url( get_permalink() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
		);
	}
}
endif;

/**
 * Find out if blog has more than one category.
 *
 * @since Zing Design 1.0
 *
 * @return boolean true if blog has more than 1 category
 */
function zd_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'zd_category_count' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'zd_category_count', $all_the_cool_cats );
	}

	if ( 1 !== (int) $all_the_cool_cats ) {
		// This blog has more than 1 category so zd_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so zd_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in zd_categorized_blog.
 *
 * @since Zing Design 1.0
 */
function zd_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'zd_category_count' );
}

add_action( 'edit_category', 'zd_category_transient_flusher' );
add_action( 'save_post', 'zd_category_transient_flusher' );

/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index
 * views, or a div element when on single views.
 *
 * @since Zing Design 1.0
 */
function zd_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
		?>

		<div class="post-thumbnail">
			<?php
			if ( ( ! is_active_sidebar( 'sidebar-2' ) || is_page_template( 'page-templates/full-width.php' ) ) ) {
				the_post_thumbnail( 'zd-full-width' );
			} else {
				the_post_thumbnail();
			}
			?>
		</div>

	<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>">
			<?php
			if ( ( ! is_active_sidebar( 'sidebar-2' ) || is_page_template( 'page-templates/full-width.php' ) ) ) {
				the_post_thumbnail( 'zd-full-width' );
			} else {
				the_post_thumbnail();
			}
			?>
		</a>

	<?php endif; // End is_singular()
}

/*---
 * Get category icons
---*/

if ( ! function_exists( 'get_category_icons' ) ) :

	function get_category_icons( $post_id = false, $exclude = null, $echo = true ) {
		if ( ! zd_categorized_blog() ) {
			return false;
		}

		global $post;
		if ( ! $post_id ) {
			if ( ! isset( $post->ID ) ) {
				return false;
			}
			$post_id = $post->ID;
		}

		$html = '';

		$post_type = get_post_type( $post_id );

		$categories = array();

		if ( is_string( $exclude ) ) {
			$exclude = explode( ",", $exclude );
		}

		if ( 'post' === $post_type ) {
			$categories = get_the_category();
		}
		//		else if( 'resource' === $post_type ) {
		//			$categories = get_the_terms($post_id, 'resource_category');
		//		}
		foreach ( $categories as $cat ) {
			// Exclude featured
			if ( is_array( $exclude ) && in_array( $cat->name, $exclude ) ) {
				continue;
			}

			$cat_class = '';

			if ( $icon_suffix = get_option( $cat->slug . '-icon' ) ) {
				$cat_class = 'fa fa-' . $icon_suffix;
			} else {
				$cat_class = 'category-icon-' . $cat->slug;
			}

			$html .= "<span class=\"$cat_class\">{$cat->name}</span>";
		}

		if ( ! $echo ) {
			return $html;
		}
		echo $html;
	}

endif;

if ( ! function_exists( 'zd_the_post_thumbnail' ) ) :

	function zd_the_post_thumbnail( $image_size = 'full' ) {

		global $post;

		$before = $after = $post_image = '';

		$is_single = is_single();

		$post_link = get_the_permalink();

		$post_link_before = "<a href=\"{$post_link}\">\n";
		$post_link_after  = "</a>\n";

		// Check if the post thumbnail exists, show this regardless of single/multiple
		if ( has_post_thumbnail() ) {
			$post_image = get_the_post_thumbnail( $post->ID, $image_size );
			$before     = $post_link_before;
			$after      = $post_link_after;
		} else if ( ! $is_single ) {
			$first_image_id = false;

			if ( $attached_media = get_attached_media( 'image' ) ) {

				if ( ! empty( $attached_media ) ) {
					$keys           = array_keys( $attached_media );
					$first_image_id = $attached_media[ $keys[0] ]->ID;
				}
			} else if ( is_int( intval( zd_get_first_image() ) ) ) {

				// If not in media, use a custom function to search
				// through the post with regex
				$first_image_id = zd_get_first_image();
			}


			// After all that's done, check to see if the image ID was set
			// If it is, display the image

			if ( $first_image_id ) {

				$alt = get_post_meta($first_image_id, '_wp_attachment_image_alt', true);

				$first_image_src = wp_get_attachment_image_src( $first_image_id, $image_size );

				if ( ! empty( $first_image_src ) ) {

					$post_image = "<img src=\"{$first_image_src[0]}\" width=\"{$first_image_src[1]}\" height=\"{$first_image_src[2]}\" alt=\"{$alt}\" />\n";

					//					$post_image = $first_image;
					$before = $post_link_before;
					$after  = $post_link_after;

				}


			}
		}

		echo $before;

		echo $post_image;

		echo $after;


	}

endif;


if ( ! function_exists( 'zd_get_first_image' ) ) :
	function zd_get_first_image() {
		global $post;

		$first_img = false;
		ob_start();
		ob_end_clean();
		//		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		preg_match_all( '/<img.+class=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );

		//		_d( $matches );

		if ( isset( $matches[1][0] ) ) {
			preg_match( '/([0-9]+)/', $matches[1][0], $decimals );
			//			_d($decimals);
			if ( isset( $decimals[0] ) ) {
				$first_img = $decimals[0];
			}
		}
		//		$first_img = isset($matches[1][0]) ? $matches[1][0] : false;

		//		if(empty($first_img)) {
		//			$first_img = get_template_directory_uri() . "/images/default.png";

		//		}
		return $first_img;
	}
endif;