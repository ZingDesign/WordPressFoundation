<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 8/05/14
 * Time: 11:30 AM
 */

/*
 * Custom taxonomies
 */

function zd_register_custom_taxonomies() {

    // Markets - category
    register_taxonomy('market_category', 'zd_markets', array(
        'hierarchical'  => true,
        'label'         => __('Market Categories'),
        'rewrite'       => array('slug' => 'market-category')
    ));

    // Products - category
    register_taxonomy('product_category', 'zd_products', array(
        'hierarchical'      => true,
        'label'             => __('Product Categories'),
        'rewrite'           => array('slug' => 'product-category')
    ));

    // Products - tag
    register_taxonomy('product_tag', 'zd_products', array(
        'hierarchical'      => false,
        'label'             => __('Product Tags'),
        'rewrite'           => array('slug' => 'product-tag')
    ));

    //Services - category
    register_taxonomy('service_category', 'zd_services', array(
        'hierarchical'  => true,
        'label'         => __('Service Categories'),
        'rewrite'       => array('slug' => 'service-category')
    ));

    // Research - category
//    register_taxonomy('research_category', 'zd_research', array(
//        'hierarchical'      => true,
//        'label'             => __('Research Categories'),
//        'rewrite'           => array('slug' => 'research-category')
//    ));

    // Newsletter - category
//    register_taxonomy('newsletter_category', 'zd_newsletters', array(
//        'hierarchical'      => true,
//        'label'             => __('Newsletter Categories'),
//        'rewrite'           => array('slug' => 'newsletter-category')
//    ));
}

//add_action( 'init', 'zd_register_custom_taxonomies' );


// Register Custom Post Type - Markets
function zd_add_post_type_markets() {

    $labels = array(
        'name'                => _x( 'Markets', 'Post Type General Name', 'zingdesign' ),
        'singular_name'       => _x( 'Market', 'Post Type Singular Name', 'zingdesign' ),
        'menu_name'           => __( 'Markets', 'zingdesign' ),
        'parent_item_colon'   => __( 'Parent Market:', 'zingdesign' ),
        'all_items'           => __( 'All Markets', 'zingdesign' ),
        'view_item'           => __( 'View Market', 'zingdesign' ),
        'add_new_item'        => __( 'Add New Market', 'zingdesign' ),
        'add_new'             => __( 'Add New', 'zingdesign' ),
        'edit_item'           => __( 'Edit Market', 'zingdesign' ),
        'update_item'         => __( 'Update Market', 'zingdesign' ),
        'search_items'        => __( 'Search Item', 'zingdesign' ),
        'not_found'           => __( 'Market not found', 'zingdesign' ),
        'not_found_in_trash'  => __( 'Market not found in Trash', 'zingdesign' ),
    );
    $rewrite = array(
        'slug'                => 'market',
        'with_front'          => true,
        'pages'               => true,
        'feeds'               => true,
    );
    $args = array(
        'label'               => __( 'zd_markets', 'zingdesign' ),
        'description'         => __( 'Markets', 'zingdesign' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
        'taxonomies'          => array( 'market_category' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-admin-site',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'rewrite'             => $rewrite,
        'capability_type'     => 'page',
    );
    register_post_type( 'zd_markets', $args );

}

// Hook into the 'init' action
//add_action( 'init', 'zd_add_post_type_markets', 0 );

// Register Custom Post Type - Products
function zd_add_post_type_products() {

    $labels = array(
        'name'                => _x( 'Products', 'Post Type General Name', 'zingdesign' ),
        'singular_name'       => _x( 'Product', 'Post Type Singular Name', 'zingdesign' ),
        'menu_name'           => __( 'Products', 'zingdesign' ),
        'parent_item_colon'   => __( 'Parent Product:', 'zingdesign' ),
        'all_items'           => __( 'All Products', 'zingdesign' ),
        'view_item'           => __( 'View Product', 'zingdesign' ),
        'add_new_item'        => __( 'Add New Product', 'zingdesign' ),
        'add_new'             => __( 'Add New', 'zingdesign' ),
        'edit_item'           => __( 'Edit Product', 'zingdesign' ),
        'update_item'         => __( 'Update Product', 'zingdesign' ),
        'search_items'        => __( 'Search Item', 'zingdesign' ),
        'not_found'           => __( 'Product not found', 'zingdesign' ),
        'not_found_in_trash'  => __( 'Product not found in Trash', 'zingdesign' ),
    );
    $rewrite = array(
        'slug'                => 'product',
        'with_front'          => true,
        'pages'               => true,
        'feeds'               => true,
    );
    $args = array(
        'label'               => __( 'zd_products', 'zingdesign' ),
        'description'         => __( 'Products', 'zingdesign' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
        'taxonomies'          => array( 'product_category' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-cart',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'rewrite'             => $rewrite,
        'capability_type'     => 'page',
    );
    register_post_type( 'zd_products', $args );

}

// Hook into the 'init' action
//add_action( 'init', 'zd_add_post_type_products', 0 );

// Register Custom Post Type - Services
function zd_add_post_type_services() {

    $labels = array(
        'name'                => _x( 'Services', 'Post Type General Name', 'zingdesign' ),
        'singular_name'       => _x( 'Service', 'Post Type Singular Name', 'zingdesign' ),
        'menu_name'           => __( 'Services', 'zingdesign' ),
        'parent_item_colon'   => __( 'Parent Service:', 'zingdesign' ),
        'all_items'           => __( 'All Services', 'zingdesign' ),
        'view_item'           => __( 'View Service', 'zingdesign' ),
        'add_new_item'        => __( 'Add New Service', 'zingdesign' ),
        'add_new'             => __( 'Add New', 'zingdesign' ),
        'edit_item'           => __( 'Edit Service', 'zingdesign' ),
        'update_item'         => __( 'Update Service', 'zingdesign' ),
        'search_items'        => __( 'Search Item', 'zingdesign' ),
        'not_found'           => __( 'Service not found', 'zingdesign' ),
        'not_found_in_trash'  => __( 'Service not found in Trash', 'zingdesign' ),
    );
    $rewrite = array(
        'slug'                => 'service',
        'with_front'          => true,
        'pages'               => true,
        'feeds'               => true,
    );
    $args = array(
        'label'               => __( 'zd_services', 'zingdesign' ),
        'description'         => __( 'Services', 'zingdesign' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
        'taxonomies'          => array( 'service_category' ),
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-admin-tools',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'rewrite'             => $rewrite,
        'capability_type'     => 'page',
    );
    register_post_type( 'zd_services', $args );

}

// Hook into the 'init' action
//add_action( 'init', 'zd_add_post_type_services', 0 );

// Register Custom Post Type - Services
function zd_add_post_type_research() {

    $labels = array(
        'name'                => _x( 'Researchs', 'Post Type General Name', 'zingdesign' ),
        'singular_name'       => _x( 'Research', 'Post Type Singular Name', 'zingdesign' ),
        'menu_name'           => __( 'Researchs', 'zingdesign' ),
        'parent_item_colon'   => __( 'Parent Research:', 'zingdesign' ),
        'all_items'           => __( 'All Researchs', 'zingdesign' ),
        'view_item'           => __( 'View Research', 'zingdesign' ),
        'add_new_item'        => __( 'Add New Research', 'zingdesign' ),
        'add_new'             => __( 'Add New', 'zingdesign' ),
        'edit_item'           => __( 'Edit Research', 'zingdesign' ),
        'update_item'         => __( 'Update Research', 'zingdesign' ),
        'search_items'        => __( 'Search Item', 'zingdesign' ),
        'not_found'           => __( 'Research not found', 'zingdesign' ),
        'not_found_in_trash'  => __( 'Research not found in Trash', 'zingdesign' ),
    );
    $rewrite = array(
        'slug'                => 'research',
        'with_front'          => true,
        'pages'               => true,
        'feeds'               => true,
    );
    $args = array(
        'label'               => __( 'zd_research', 'zingdesign' ),
        'description'         => __( 'research', 'zingdesign' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
        'taxonomies'          => array(),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-category',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'rewrite'             => $rewrite,
        'capability_type'     => 'page',
    );
    register_post_type( 'zd_research', $args );

}

// Hook into the 'init' action
add_action( 'init', 'zd_add_post_type_research', 0 );

// Register Custom Post Type - Services
function zd_add_post_type_newsletters() {

    $labels = array(
        'name'                => _x( 'Newsletters', 'Post Type General Name', 'zingdesign' ),
        'singular_name'       => _x( 'Newsletter', 'Post Type Singular Name', 'zingdesign' ),
        'menu_name'           => __( 'Newsletters', 'zingdesign' ),
        'parent_item_colon'   => __( 'Parent Newsletter:', 'zingdesign' ),
        'all_items'           => __( 'All Newsletters', 'zingdesign' ),
        'view_item'           => __( 'View Newsletter', 'zingdesign' ),
        'add_new_item'        => __( 'Add New Newsletter', 'zingdesign' ),
        'add_new'             => __( 'Add New', 'zingdesign' ),
        'edit_item'           => __( 'Edit Newsletter', 'zingdesign' ),
        'update_item'         => __( 'Update Newsletter', 'zingdesign' ),
        'search_items'        => __( 'Search Item', 'zingdesign' ),
        'not_found'           => __( 'Newsletter not found', 'zingdesign' ),
        'not_found_in_trash'  => __( 'Newsletter not found in Trash', 'zingdesign' ),
    );
    $rewrite = array(
        'slug'                => 'newsletter',
        'with_front'          => true,
        'pages'               => true,
        'feeds'               => true,
    );
    $args = array(
        'label'               => __( 'zd_newsletters', 'zingdesign' ),
        'description'         => __( 'newsletters', 'zingdesign' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
        'taxonomies'          => array(),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 80,
        'menu_icon'           => 'dashicons-media-document',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'rewrite'             => $rewrite,
        'capability_type'     => 'page',
    );
    register_post_type( 'zd_newsletters', $args );

}

// Hook into the 'init' action
//add_action( 'init', 'zd_add_post_type_newsletters', 0 );


//function zd_rewrite_flush() {
//    flush_rewrite_rules();
//}
//add_action( 'after_switch_theme', 'zd_rewrite_flush' );