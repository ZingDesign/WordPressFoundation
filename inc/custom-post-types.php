<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 8/05/14
 * Time: 11:30 AM
 */

/*
 * Custom post types
 */

function get_custom_post_type_title($str) {
	return ucfirst( str_replace("_", " ", $str) );
}

function zd_custom_taxonomies() {
	// Resources - category
	register_taxonomy('resource_category', 'resource', array(
		'hierarchical'  => true,
		'label'         => __('Resource Categories'),
		'rewrite'       => array('slug' => 'resource-category')
	));
}

add_action('init', 'zd_custom_taxonomies');

// Register Custom Post Type - Services
function zd_add_custom_post_types() {

//	global $post;

//	$post_id = isset($_POST['post']) ? $_POST['post'] : $_GET['post'];


	// Manually define post types
	// Format: ID (singular form) => (array)Options

	$custom_post_types = array(
		'resource' => array(
			'menu_position' => 5,
			'plural'        => 'resources',
			'icon'          => 'category',
			'hierarchical'  => true,
			'taxonomies'    => array('resource_category')
		),

	);

//	if( $custom_meta = zd_metabox::zd_get_custom_meta($post_id, 'zd_add_custom_post_type') ) {
//		if( $custom_meta['add_custom_post_type'] ) {
//			$custom_post_types[$custom_meta['post_type_id']] = array(
//				'icon' => $custom_meta['post_type_icon']
//			);
//		}
//	}

	$defaults = array(
		'icon'                  => 'media-document',
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'show_in_admin_bar'     => true,
		'menu_position'         => 20,
		'supports'              => array(
									'title',
									'editor',
									'excerpt',
									'thumbnail',
									'revisions',
									'custom-fields',
									'page-attributes',
									'author'
								),
		'taxonomies'            => array('category'),
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page'



	);

	foreach($custom_post_types as $post_type_id => $options) {

		$singular_title = ucfirst( str_replace("_", " ", $post_type_id ) );

		$plural = isset($options['plural']) ? $options['plural'] : $post_type_id;

		$plural_title = ucfirst( str_replace("_", " ", $plural) );

		extract(wp_parse_args($options, $defaults));

		$labels = array(
			'name'                => _x( $plural_title, 'Post Type General Name', 'zingdesign' ),
			'singular_name'       => _x( $singular_title, 'Post Type Singular Name', 'zingdesign' ),
			'menu_name'           => __( $plural_title, 'zingdesign' ),
			'parent_item_colon'   => __( 'Parent ' . $singular_title.':', 'zingdesign' ),
			'all_items'           => __( 'All '.$plural_title, 'zingdesign' ),
			'view_item'           => __( 'View ' . $singular_title, 'zingdesign' ),
			'add_new_item'        => __( 'Add New ' . $singular_title, 'zingdesign' ),
			'add_new'             => __( 'Add New', 'zingdesign' ),
			'edit_item'           => __( 'Edit ' . $singular_title, 'zingdesign' ),
			'update_item'         => __( 'Update ' . $singular_title, 'zingdesign' ),
			'search_items'        => __( 'Search Item', 'zingdesign' ),
			'not_found'           => __( $singular_title. ' not found', 'zingdesign' ),
			'not_found_in_trash'  => __( $singular_title . ' not found in Trash', 'zingdesign' ),
		);
		$rewrite = array(
			'slug'                => $post_type_id,
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => __( $post_type_id, 'zingdesign' ),
			'description'         => __( $post_type_id, 'zingdesign' ),
			'labels'              => $labels,
			'supports'            => $supports,
			'taxonomies'          => $taxonomies,
			'hierarchical'        => $hierarchical,
			'public'              => $public,
			'show_ui'             => $show_ui,
			'show_in_menu'        => $show_in_menu,
			'show_in_nav_menus'   => $show_in_nav_menus,
			'show_in_admin_bar'   => $show_in_admin_bar,
			'menu_position'       => $menu_position,
			'menu_icon'           => 'dashicons-' . $icon,
			'can_export'          => $can_export,
			'has_archive'         => $has_archive,
			'exclude_from_search' => $exclude_from_search,
			'publicly_queryable'  => $publicly_queryable,
			'rewrite'             => $rewrite,
			'capability_type'     => $capability_type,
		);
		register_post_type( $post_type_id, $args );

	}




}

// Hook into the 'init' action
add_action( 'init', 'zd_add_custom_post_types', 0 );