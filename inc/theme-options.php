<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 6/05/14
 * Time: 4:54 PM
 */

class ZingDesignThemeOptions {
    
    private $tabs;
    private $options;
    private $page_id;
	private $text_domain;

	private $form_helper;
    
    function __construct() {

	    $this->text_domain = ZD_TEXT_DOMAIN;
	    $this->form_helper = new FormHelper();

        if( is_admin() ) {
            add_action( 'admin_menu', array($this, 'zd_theme_options_init') );
            add_action( 'admin_init', array($this, 'zd_theme_settings') );
        }
        else {
            add_action('wp_head', array($this, 'zd_header_output'));
            add_action('wp_footer', array($this, 'zd_footer_output'));
        }

//        $this->tabs = array(
//            'general',
//            'home',
//            //        'about',
//            //        'markets',
//            //        'products',
//            //        'services',
//            //        'resources',
//            //        'contact'
//        );

	    // Define tabs
	    // Format: ID => Label
	    $tabs = array(
		    'general'       => 'General',
		    'social-media'  => 'Social Media',
		    'analytics'     => 'Analytics'
	    );

	    /*
	     * Define Options
	     * Format: ID => array Args
	     */


        $options = array(
            'header_logo' => array(
                'id'        => 'zd-logo-header',
                'type'      => 'image',
                'section'   => 'general'
            ),
            'footer_logo' => array(
                'id'        => 'zd-logo-footer',
                'type'      => 'image',
                'section'   => 'general'
            ),
            'enable_mobile_navigation' => array(
                'id'        => 'zd-enable-mobile-navigation',
                'type'      => 'checkbox',
                'section'   => 'general',
	            'default'   => true
            ),
            'mobile_navigation_alignment' => array(
                'id'        => 'zd-mobile-navigation-alignment',
                'type'      => 'select',
                'section'   => 'general',
                'dropdown'  => array(
	                'left'  => 'Left',
	                'right' => 'Right'
                ),
	            'default'   => 'right'
            ),
	        'show_search_form-in_header' => array(
		        'type'      => 'checkbox',
		        'default'   => true
	        ),
	        'google_analytics_code' => array(
		        'section' => 'analytics',
		        'placeholder' => 'UA-XXXXXXXX-X'
	        ),
	        'test_field' => array(
		        'type' => 'textarea'
	        ),
	        'test_select' => array(
		        'type' => 'select'
	        ),
	        'facebook' => array(
		        'section'   => 'social-media',
		        'type'      => 'url'
	        )

        );

//        $args = array(
//            'sort_order' => 'ASC',
//            'sort_column' => 'post_date',
//            'hierarchical' => 1,
//            'exclude' => '',
//            'include' => '',
//            'meta_key' => '',
//            'meta_value' => '',
//            'authors' => '',
//            'child_of' => 0,
//            'parent' => 0,
//            'exclude_tree' => '',
//            'number' => '',
//            'offset' => 0,
//            'post_type' => 'page',
//            'post_status' => 'publish'
//        );
//        $pages = get_pages($args);

//        foreach( $pages as $page ) {
//            if( in_array($page->post_name, $options)) {
//                $tabs[] = strtolower($page->post_name);
//            }
//
//        }

//	    $tabs[] = ;

        $this->tabs = $tabs;

        $this->options = $options;

        $this->page_id = 'zd_theme_options_page';

    }

    function zd_theme_options_init() {
        add_menu_page(
            'Theme Options',
            'Theme Options',
            'manage_options',
            'zd_theme_options_page',
            array($this, 'zd_theme_options_callback'),
            'dashicons-admin-generic',
            90
        );
    }

    function zd_theme_options_callback() {

        echo '<form id="zd-options-form" method="POST" action="options.php">';

        echo "<h2 class=\"nav-tab-wrapper zd-admin-tab-nav\">\n";

	    $i = 0;

        foreach( $this->tabs as $tab_id => $tab_label ) {
	        // Set first tab to active by default
            $active = 0 === $i ? ' nav-tab-active' : '';

            echo "<a href=\"#tab-{$tab_id}\" class=\"nav-tab{$active}\">{$tab_label}</a>\n";

	        $i ++;
        }
        echo "</h2>\n";

        settings_fields( 'zd_theme_options_page' );

        //do_settings_sections( 'zd_theme_options_page' ); 	//pass slug name of page

	    $i = 0;
        foreach( $this->tabs as $tab_id => $tab_label ) {
            $active = (0 === $i) ? ' zd-active-tab' : '';

            echo "<div id=\"tab-{$tab_id}\" class=\"zd-tab{$active}\">\n";
            do_settings_fields( 'zd_theme_options_page', 'zd-'.$tab_id.'-section' );
            echo "</div>\n";

	        $i ++;
        }

        submit_button();

        echo '</form>';

        echo '<a href="http://www.zingdesign.com" target="_blank"><img src="'.get_template_directory_uri().'/images/logos/zing-design-logo.png" alt="A custom WordPress theme by Zing Design" width="200" height="64" /></a>'."\n";
    }

    function zd_theme_settings() {

        foreach( $this->tabs as $tab_id => $tab_label ) {
            $tab_title = ucfirst($tab_label);

            add_settings_section(
                'zd-'.$tab_id.'-section',
                __($tab_title, $this->text_domain),
                array($this, 'zd_section_callback'),
                $this->page_id,
                array(
                    'tab_title' => $tab_title
                )
            );
        }

        // Options

	    // define option types which don't have a label
	    $no_label = array('hidden', 'radio');

        foreach( $this->options as $key => $option ) {
	        $default = $label = false;

	        // Validate options

	        // If ID not explicitly set, replace key underscores with dashes
            $id = isset($option['id']) ? $option['id'] : str_replace("_", "-", $key );

	        // replace key underscores with spaces to generate a title as a fallback for label
            $title = str_replace("_", " ", ucfirst( $key ) );

	        // Set option type to text by default (most common input?)
            $option_type = isset($option['type']) ? $option['type'] : 'text';

	        // Set filter to esc_html by default
            $filter = isset($option['filter']) ? $option['filter'] : 'esc_html';

	        // Set section to general by default
            $section = isset($option['section']) ? $option['section'] : 'general';

	        $dropdown = isset($option['dropdown']) ? $option['dropdown'] : false;

	        $placeholder = isset($option['placeholder']) ? $option['placeholder'] : false;

	        $required = isset($option['required']) ? $option['required'] : false;

	        // Set label if the input is NOT hidden

	        if( ! in_array($option_type, $no_label) ) {
		        $label = isset($option['label']) ? $option['label'] : $title;
	        }

	        if( isset($option['default']) ) {
		        add_option($id, $option['default']);
		        $default = $option['default'];
	        }

            add_settings_field(
                $id,
                '',
                array($this, 'get_setting_input'),
                $this->page_id,
                'zd-'.$section.'-section',
                array(
                    'id'            => $id,
	                'label'         => $label,
                    'type'          => $option_type,
	                'dropdown'      => $dropdown,
	                'default'       => $default,
	                'placeholder'   => $placeholder,
	                'required'      => $required
                )
            );

            register_setting(
                $this->page_id,
                $id,
                $filter
            );
        }


    }

    function zd_section_callback( $args ) {
        echo '<h1>'.$args['tab_title'].'</h1>'."\n";
    }

	function get_setting_input($_args) {
		echo $this->form_helper->zd_setting_input($_args);
	}



    function zd_header_output() {
        $html = '';

        if( get_option('zd-logo') ) {
            $logo_src = wp_get_attachment_image_src( get_option('zd-logo'), 'full' );

            $html .= '<style type="text/css">'."\n";
            $html .= '.header-logo {'."\n";
            $html .= 'display: block;'."\n";
            $html .= 'background-image: url(' . $logo_src[0] . ');'."\n";
            $html .= 'width: ' . $logo_src[1] . 'px;'."\n";
//            $html .= 'height: ' . $logo_src[2] . 'px;'."\n";
            $html .= '}'."\n";
            $html .= '</style>'."\n";
        }

        if( get_option('zd-logo-footer') ) {
            $logo_src = wp_get_attachment_image_src( get_option('zd-logo-footer'), 'full' );

            $html .= '<style type="text/css">'."\n";
            $html .= '.footer-logo {'."\n";
            $html .= 'display: block;'."\n";
            $html .= 'background-image: url(' . $logo_src[0] . ');'."\n";
            $html .= 'width: ' . $logo_src[1] . 'px;'."\n";
            $html .= 'height: ' . $logo_src[2] . 'px;'."\n";
            $html .= '}'."\n";
            $html .= '</style>'."\n";
        }

        echo $html;

    }

    function zd_footer_output() {

	    if( get_option('zd-enable-mobile-navigation') ) {
		    echo '<div class="cbp-menu-overlay"></div>'."\n";
	    }
    }
}

new ZingDesignThemeOptions();