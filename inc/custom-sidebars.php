<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 8/05/14
 * Time: 12:21 PM
 */

function zd_add_custom_sidebars() {

    register_sidebar( array(
        'name'          => __( 'Sidebar 1', 'zingdesign' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Sidebar that displays on the right hand side of the Home page, about page,
        market posts, resources page.',
            'zingdesign' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Testimonials', 'zingdesign' ),
        'id'            => 'sidebar-testimonials',
        'description'   => __( 'Sidebar that contains testimonials, displayed at the bottom of the Home page.', 'zingdesign' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '',
        'after_title'   => '',
    ) );

    register_sidebar( array(
        'name'          => __( 'Team Members Sidebar', 'zingdesign' ),
        'id'            => 'sidebar-team-members',
        'description'   => __( 'Display the team members on the About page', 'zingdesign' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
//    register_sidebar( array(
//        'name'          => __( 'About Page Sidebar', 'zingdesign' ),
//        'id'            => 'sidebar-about',
//        'description'   => __( 'Sidebar that displays on the About page.', 'zingdesign' ),
//        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
//        'after_widget'  => '</aside>',
//        'before_title'  => '<h1 class="widget-title">',
//        'after_title'   => '</h1>',
//    ) );

//    register_sidebar( array(
//        'name'          => __( 'Market Single Page Sidebar', 'zingdesign' ),
//        'id'            => 'sidebar-market-single',
//        'description'   => __( 'Sidebar that displays on the Market (single) page.', 'zingdesign' ),
//        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
//        'after_widget'  => '</aside>',
//        'before_title'  => '<h1 class="widget-title">',
//        'after_title'   => '</h1>',
//    ) );

//    register_sidebar( array(
//        'name'          => __( 'Products in the Market Sidebar', 'zingdesign' ),
//        'id'            => 'sidebar-market-products',
//        'description'   => __( 'Sidebar that displays products in the market.', 'zingdesign' ),
//        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
//        'after_widget'  => '</aside>',
//        'before_title'  => '<h1 class="widget-title">',
//        'after_title'   => '</h1>',
//    ) );

    register_sidebar( array(
        'name'          => __( 'Single Product Sidebar', 'zingdesign' ),
        'id'            => 'sidebar-single-products',
        'description'   => __( 'Sidebar that displays on single products.', 'zingdesign' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Services Page Sidebar', 'zingdesign' ),
        'id'            => 'sidebar-services',
        'description'   => __( 'Sidebar that displays on the Services page.', 'zingdesign' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Contact Page Sidebar', 'zingdesign' ),
        'id'            => 'sidebar-contact',
        'description'   => __( 'Sidebar that displays on the Contact page.', 'zingdesign' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'zd_add_custom_sidebars' );