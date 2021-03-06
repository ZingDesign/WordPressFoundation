<?php
/**
 * Zing Design functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Zing_Design
 * @since Zing Design 1.0
 */

define("ZD_TEXT_DOMAIN", "zingdesign");

//if( is_admin() ) {
include_once get_template_directory() . '/inc/FormHelper.php';
include_once get_template_directory() . '/inc/theme-options.php';
include_once get_template_directory() . '/inc/custom-post-types.php';
include_once get_template_directory() . '/inc/custom-sidebars.php';
include_once get_template_directory() . '/inc/custom-shortcodes.php';
include_once get_template_directory() . '/inc/custom-metaboxes.php';
//}
include_once get_template_directory() . '/inc/nav-walker.php';

//add_filter('widget_text', 'do_shortcode');

/**
 * Set up the content width value based on the theme's design.
 *
 * @see zd_content_width()
 *
 * @since Zing Design 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 474;
}

/**
 * Zing Design only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'zd_setup' ) ) :
/**
 * Zing Design setup.
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 * @since Zing Design 1.0
 */
function zd_setup() {

	/*
	 * Make Zing Design available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Zing Design, use a find and
	 * replace to change 'zingdesign' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'zingdesign', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', zd_font_url() ) );

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails, and declare two sizes.
	add_theme_support( 'post-thumbnails' );
//	set_post_thumbnail_size( 672, 372, true );
//	add_image_size( 'zd-full-width', 1038, 576 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'zingdesign' ),
		'secondary' => __( 'Secondary menu in left sidebar', 'zingdesign' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
	) );

	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', apply_filters( 'zd_custom_background_args', array(
		'default-color' => 'f5f5f5',
	) ) );

	// Add support for featured content.
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'zd_get_featured_posts',
		'max_posts' => 6,
	) );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );

}
endif; // zd_setup
add_action( 'after_setup_theme', 'zd_setup' );

/**
 * Adjust content_width value for image attachment template.
 *
 * @since Zing Design 1.0
 */
function zd_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 810;
	}
}
add_action( 'template_redirect', 'zd_content_width' );

/**
 * Getter function for Featured Content Plugin.
 *
 * @since Zing Design 1.0
 *
 * @return array An array of WP_Post objects.
 */
function zd_get_featured_posts() {
	/**
	 * Filter the featured posts to return in Zing Design.
	 *
	 * @since Zing Design 1.0
	 *
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters( 'zd_get_featured_posts', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @since Zing Design 1.0
 *
 * @return bool Whether there are featured posts.
 */
function zd_has_featured_posts() {
	return ! is_paged() && (bool) zd_get_featured_posts();
}

/**
 * Register three Zing Design widget areas.
 *
 * @since Zing Design 1.0
 */
function zd_widgets_init() {
	require get_template_directory() . '/inc/widgets.php';
//	register_widget( 'Zing_Design_Ephemera_Widget' );
//
//	register_sidebar( array(
//		'name'          => __( 'Primary Sidebar', 'zingdesign' ),
//		'id'            => 'sidebar-1',
//		'description'   => __( 'Main sidebar that appears on the left.', 'zingdesign' ),
//		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
//		'after_widget'  => '</aside>',
//		'before_title'  => '<h1 class="widget-title">',
//		'after_title'   => '</h1>',
//	) );
//	register_sidebar( array(
//		'name'          => __( 'Content Sidebar', 'zingdesign' ),
//		'id'            => 'sidebar-2',
//		'description'   => __( 'Additional sidebar that appears on the right.', 'zingdesign' ),
//		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
//		'after_widget'  => '</aside>',
//		'before_title'  => '<h1 class="widget-title">',
//		'after_title'   => '</h1>',
//	) );
//	register_sidebar( array(
//		'name'          => __( 'Footer Widget Area', 'zingdesign' ),
//		'id'            => 'sidebar-3',
//		'description'   => __( 'Appears in the footer section of the site.', 'zingdesign' ),
//		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
//		'after_widget'  => '</aside>',
//		'before_title'  => '<h1 class="widget-title">',
//		'after_title'   => '</h1>',
//	) );
}
add_action( 'widgets_init', 'zd_widgets_init' );

/**
 * Register Lato Google font for Zing Design.
 *
 * @since Zing Design 1.0
 *
 * @return string
 */
function zd_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'zingdesign' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Lato:300,400,700,900,300italic,400italic,700italic' ), "//fonts.googleapis.com/css" );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Zing Design 1.0
 */
function zd_scripts() {
    // Load Modernizr first to prevent FOUC in older browsers
    wp_enqueue_script('modernizr', get_template_directory_uri() . '/js/modernizr.wordpress.min.js', array(), '2.8.3');

	// Add Lato font, used in the main stylesheet.
//	wp_enqueue_style( 'zd-lato', zd_font_url(), array(), null );

    //wp_enqueue_style( 'open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans:200,400,600,700');

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.0.2' );

    // Load our custom stylesheet manually
	$stylesheet_file = WP_DEBUG ? 'zing.css' : 'zing.min.css';
    wp_enqueue_style( 'zd-main', get_template_directory_uri() . '/css/' . $stylesheet_file );

	// Load our main stylesheet.
	// Load the style.css file AFTER the custom stylesheet
	// So that any overrides can be made in the editor if necessary
	wp_enqueue_style( 'zd-style', get_stylesheet_uri(), array( 'genericons' ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'zd-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20130402' );
	}

	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		wp_enqueue_script( 'zd-slider', get_template_directory_uri() . '/js/slider.js', array( 'jquery' ), '20131205', true );
		wp_localize_script( 'zd-slider', 'featuredSliderDefaults', array(
			'prevText' => __( 'Previous', 'zingdesign' ),
			'nextText' => __( 'Next', 'zingdesign' )
		) );
	}


    // --- Custom JS ---

	$javascript_file = WP_DEBUG ? '/src/js/zing-client.js' :'/dist/js/zing-client.min.js';

    wp_enqueue_script(
        'zd-client',
        get_template_directory_uri() . $javascript_file,
        array('jquery'),
        '1',
        true
    );

	wp_localize_script('zd-client', 'ZDGlobals', array(
		'templateDirectoryURI' => get_template_directory_uri()
	));

	$theme_options = new ZingDesignThemeOptions();
	if( file_exists( $theme_options->custom_css_file ) ) {
		wp_enqueue_style('zd-generated-theme-css', get_template_directory_uri() . $theme_options->custom_css_file_name );
	}

}
add_action( 'wp_enqueue_scripts', 'zd_scripts' );

// Admin assets

function zd_admin_setup() {

    wp_enqueue_style( 'zd-admin-style', get_template_directory_uri() . '/css/zing-admin.css' );


	wp_enqueue_media();

	// Spectrum colour picker
	wp_enqueue_style('spectrum-css', get_template_directory_uri() . '/fnd/bower_components/spectrum/spectrum.css');
	wp_enqueue_script('spectrum-js', get_template_directory_uri() . '/fnd/bower_components/spectrum/spectrum.js', array('jquery'), '1', false);

	//Custom admin script
	wp_enqueue_script('zd-admin', get_template_directory_uri() . '/js/admin/zing-admin.js', array('jquery', 'spectrum-js'), '1', true);



}

add_action( 'admin_enqueue_scripts', 'zd_admin_setup' );

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 *
 * @since Zing Design 1.0
 */
function zd_admin_fonts() {
	wp_enqueue_style( 'zd-lato', zd_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'zd_admin_fonts' );

if ( ! function_exists( 'zd_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Zing Design 1.0
 */
function zd_the_attached_image() {
	$post                = get_post();
	/**
	 * Filter the default Zing Design attachment size.
	 *
	 * @since Zing Design 1.0
	 *
	 * @param array $dimensions {
	 *     An array of height and width dimensions.
	 *
	 *     @type int $height Height of the image in pixels. Default 810.
	 *     @type int $width  Width of the image in pixels. Default 810.
	 * }
	 */
	$attachment_size     = apply_filters( 'zd_attachment_size', array( 810, 810 ) );
	$next_attachment_url = wp_get_attachment_url();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID',
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id ) {
			$next_attachment_url = get_attachment_link( $next_id );
		}

		// or get the URL of the first image attachment.
		else {
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
		}
	}

	printf( '<a href="%1$s" rel="attachment">%2$s</a>',
		esc_url( $next_attachment_url ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

if ( ! function_exists( 'zd_list_authors' ) ) :
/**
 * Print a list of all site contributors who published at least one post.
 *
 * @since Zing Design 1.0
 */
function zd_list_authors() {
	$contributor_ids = get_users( array(
		'fields'  => 'ID',
		'orderby' => 'post_count',
		'order'   => 'DESC',
		'who'     => 'authors',
	) );

	foreach ( $contributor_ids as $contributor_id ) :
		$post_count = count_user_posts( $contributor_id );

		// Move on if user has not published a post (yet).
		if ( ! $post_count ) {
			continue;
		}
	?>

	<div class="contributor">
		<div class="contributor-info">
			<div class="contributor-avatar"><?php echo get_avatar( $contributor_id, 132 ); ?></div>
			<div class="contributor-summary">
				<h2 class="contributor-name"><?php echo get_the_author_meta( 'display_name', $contributor_id ); ?></h2>
				<p class="contributor-bio">
					<?php echo get_the_author_meta( 'description', $contributor_id ); ?>
				</p>
				<a class="button contributor-posts-link" href="<?php echo esc_url( get_author_posts_url( $contributor_id ) ); ?>">
					<?php printf( _n( '%d Article', '%d Articles', $post_count, 'zingdesign' ), $post_count ); ?>
				</a>
			</div><!-- .contributor-summary -->
		</div><!-- .contributor-info -->
	</div><!-- .contributor -->

	<?php
	endforeach;
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since Zing Design 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function zd_body_classes( $classes ) {
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( get_header_image() ) {
		$classes[] = 'header-image';
	} else {
		$classes[] = 'masthead-fixed';
	}

	if ( is_archive() || is_search() || is_home() ) {
		$classes[] = 'list-view';
	}

	if ( ( ! is_active_sidebar( 'sidebar-2' ) )
		|| is_page_template( 'page-templates/full-width.php' )
		|| is_page_template( 'page-templates/contributors.php' )
		|| is_attachment() ) {
		$classes[] = 'full-width';
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$classes[] = 'footer-widgets';
	}

	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}

	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		$classes[] = 'slider';
	} elseif ( is_front_page() ) {
		$classes[] = 'grid';
	}

	return $classes;
}
add_filter( 'body_class', 'zd_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @since Zing Design 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function zd_post_classes( $classes ) {
	if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}
add_filter( 'post_class', 'zd_post_classes' );

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Zing Design 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function zd_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'zingdesign' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'zd_wp_title', 10, 2 );

// Implement Custom Header features.
require get_template_directory() . '/inc/custom-header.php';

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Add Theme Customizer functionality.
require get_template_directory() . '/inc/customizer.php';

/*
 * Add Featured Content functionality.
 *
 * To overwrite in a plugin, define your own Featured_Content class on or
 * before the 'setup_theme' hook.
 */
if ( ! class_exists( 'Featured_Content' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require get_template_directory() . '/inc/featured-content.php';
}

/*
 * Foundation classes for top nav
 */

/*
 * Customise main nav menu classes
 * Add 'has-dropdown' class if the menu item has children
 */

add_filter( 'wp_nav_menu_objects', 'zd_add_menu_parent_class' );

function zd_add_menu_parent_class( $items ) {

    $parents = array();
    foreach ( $items as $item ) {
        if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
            $parents[] = $item->menu_item_parent;
        }
    }
    foreach ( $items as $item ) {
        if ( in_array( $item->ID, $parents ) ) {
            $item->classes[] = 'has-dropdown';
        }
    }

    return $items;
}

function get_image_style( $echo=true ) {
    // Get the featured image data
    $image_data = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full', true);
    // Get the image source (always first item in array)
    $image_src = $image_data[0];
//    $image_width = $image_data[1];
//    $image_height = $image_data[2];
    // Store the inline CSS in a string to append to the div
    $background = ' style="background-image: url(' . $image_src . ');"';

    if( ! $echo ) {
        return $background;
    }

    echo $background;
    return 1;
}

if( !function_exists( 'get_background_image_by_post') ) {
    function get_background_image_by_post( $post_id, $size='full', $echo=false ) {
        // Get the featured image data
        $image_data = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), $size, true );
        // Get the image source (always first item in array)
        $image_src = $image_data[0];
        //    $image_width = $image_data[1];
        //    $image_height = $image_data[2];
        // Store the inline CSS in a string to append to the div
        $background = ' style="background-image: url(' . $image_src . ');"';

        if( ! $echo ) {
            return $background;
        }

        echo $background;
        return true;
    }
}


function depluralize($word){
    // Here is the list of rules. To add a scenario,
    // Add the plural ending as the key and the singular
    // ending as the value for that key. This could be
    // turned into a preg_replace and probably will be
    // eventually, but for now, this is what it is.
    //
    // Note: The first rule has a value of false since
    // we don't want to mess with words that end with
    // double 's'. We normally wouldn't have to create
    // rules for words we don't want to mess with, but
    // the last rule (s) would catch double (ss) words
    // if we didn't stop before it got to that rule.
    $rules = array(
        'ss' => false,
        'os' => 'o',
        'ies' => 'y',
        'xes' => 'x',
        'oes' => 'o',
        'ies' => 'y',
        'ves' => 'f',
        's' => '');
    // Loop through all the rules and do the replacement.
    foreach(array_keys($rules) as $key){
        // If the end of the word doesn't match the key,
        // it's not a candidate for replacement. Move on
        // to the next plural ending.
        if(substr($word, (strlen($key) * -1)) != $key)
            continue;
        // If the value of the key is false, stop looping
        // and return the original version of the word.
        if($key === false)
            return $word;
        // We've made it this far, so we can do the
        // replacement.
        return substr($word, 0, strlen($word) - strlen($key)) . $rules[$key];
    }
    return $word;


}

function get_uploads_url() {
    $upload_url = wp_upload_dir();
    echo trailingslashit($upload_url['baseurl']);
}

function list_services() {
    $services = get_posts(array(
        'post_type'         => 'zd_services',
        'posts_per_page'    => -1
    ));
    ?>
    <aside class="widget"><h3 class="pagenav">Services we offer</h3><ul>
    <?php
    foreach ( $services as $service ) : setup_postdata( $service ); ?>
        <li>
            <a href="<?php echo get_permalink(get_page_by_title('Services')); ?>#<?php echo $service -> post_name; ?>">
                <?php echo $service -> post_title; ?></a>
        </li>
    <?php endforeach;
    wp_reset_postdata();?>
    </ul>
    </aside>
<?php
}

/*
 * Custom method to check if an array is empty recursively
 */
if( ! function_exists('empty_r') ) {
    function empty_r( $arr ) {
        if( empty($arr) ) {
            return true;
        }
        $is_empty = true;

        foreach($arr as $a) {
            if( $a ) {
                $is_empty = false;
                break;
            }

        }
        return $is_empty;
    }
}

/*
 * Add custom image sizes
 */

if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'product-thumb', 310, 300, array('center', 'top') ); //(width, height, is_cropped)
    add_image_size( 'resource-thumb', 212, 250, array('center', 'top') ); //(width, height, is_cropped)
    add_image_size( 'banner', 1440 );
}

add_filter( 'image_size_names_choose', 'zd_custom_image_sizes' );

function zd_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'product-thumb' => __('Product thumbnail'),
        'banner' => __('Banner size'),
    ) );
}