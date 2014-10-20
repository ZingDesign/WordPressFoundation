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
    
    function __construct() {

	    $this->text_domain = $this->text_domain;

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
                'dropdown'   => array(
	                'left'  => 'Left',
	                'right' => 'Right'
                ),
	            'default'   => 'right'
            ),
	        'google_analytics_code' => array(
		        'id' => 'google-analytics-code',
		        'type' => 'text',
		        'section' => 'analytics',
		        'default' => 'UA-XXXXXXXX-X'
	        )
        );

        $args = array(
            'sort_order' => 'ASC',
            'sort_column' => 'post_date',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => 0,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);

        $tabs = array( 'general' );

        foreach( $pages as $page ) {
            if( in_array($page->post_name, $options)) {
                $tabs[] = strtolower($page->post_name);
            }

        }

	    $tabs[] = 'analytics';

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
        foreach( $this->tabs as $i => $tab ) {
            $active = 0 === $i ? ' nav-tab-active' : '';
            $tab_label = str_replace("_", " ", ucfirst($tab) );
            echo "<a href=\"#tab-{$tab}\" class=\"nav-tab{$active}\">{$tab_label}</a>\n";
        }
        echo "</h2>\n";

        settings_fields( 'zd_theme_options_page' );

        //do_settings_sections( 'zd_theme_options_page' ); 	//pass slug name of page

        foreach( $this->tabs as $i => $tab ) {
            $active = (0 === $i) ? ' zd-active-tab' : '';
            echo "<div id=\"tab-{$tab}\" class=\"zd-tab{$active}\">\n";
            do_settings_fields( 'zd_theme_options_page', 'zd-'.$tab.'-section' );
            echo "</div>\n";
        }

        submit_button();

        echo '</form>';

        echo '<a href="http://www.zingdesign.com" target="_blank"><img src="'.get_template_directory_uri().'/images/logos/zing-design-logo.png" alt="A custom WordPress theme by Zing Design" width="200" height="64" /></a>'."\n";
    }

    function zd_theme_settings() {

        foreach( $this->tabs as $tab ) {
            $tab_title = ucfirst($tab);

            add_settings_section(
                'zd-'.$tab.'-section',
                __($tab_title, $this->text_domain),
                array($this, 'zd_section_callback'),
                $this->page_id,
                array(
                    'tab_title' => $tab_title
                )
            );
        }

        // Options

        foreach( $this->options as $key => $option ) {
            $id = $option['id'];
            $title = str_replace("_", " ", ucfirst( $key ) );
            $option_type = $option['type'];

            $filter = isset($option['filter']) ? $option['filter'] : 'esc_html';

            $section = isset($option['section']) ? $option['section'] : 'general';

	        $dropdown = isset($option['dropdown']) ? $option['dropdown'] : false;

	        if( isset($option['default']) ) {
		        add_option($id, $option['default']);
	        }

            add_settings_field(
                $id,
                '<div class="input-group"><label class="'.$option_type.'-label" for="'.$id.'">' . __($title, $this->text_domain) . '</label>',
                array($this, 'zd_setting_input'),
                $this->page_id,
                'zd-'.$section.'-section',
                array(
                    'id' => $id,
                    'type' => $option_type,
	                'dropdown' => $dropdown
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

    function zd_setting_input( $args ) {
//        echo '<p><textarea class="zd-text" id="zd-physical-address" name="zd-physical-address" rows="3" cols="50">'.get_option('zd-physical-address').'</textarea></p>';
        $type = $args['type'];
        $id = $args['id'];

	    $dropdown = isset($args['dropdown']) ? $args['dropdown'] : array();

	    $default = isset($args['default']) ? $args['default'] : '';

	    $is_checkbox = 'checkbox' === $type;
	    $is_hidden = 'hidden' === $type;

        if( ! $type ) {
            $type = 'text';
        }

        if( ! $id ) {
            echo "<p class=\"error\">Error: ID required when initialising new settings</p>\n";
            return;
        }

//        $name = str_replace("-", "_", $id);
        $current_value = get_option( $id ) ? get_option( $id ) : $default;
	    $value = ( $is_checkbox || $is_hidden ) ? '1' : $current_value;

	    if( in_array($type, array('text', 'email', 'number', 'checkbox', 'hidden')) ) {
		    $checked = '';

		    if( $is_checkbox ) {
			    $checked = (!empty($current_value) && $current_value === "1") ? ' checked="checked"' : '';
		    }

//		    if( $is_checkbox || $is_hidden ) {
//			    $value = '1';
//
//		    }

		    echo "<input type=\"{$type}\" id=\"{$id}\" name=\"{$id}\" value=\"{$value}\"{$checked} />\n";
	    }

        else if( 'image' === $type ) {
            $bg = '';
            echo "<div>\n";
            $rand = mt_rand(100, 1000);

            $button_text = empty($value) ? 'Insert image' : 'Change image';
            echo "<button class=\"zd-insert-image-button button button-default\">".__($button_text, $this->text_domain)."</button>\n";

//            $image_src = array();
            $image_url = '';

            if( !empty($value) ) {
                echo '<button class="zd-remove-image-button button button-default">' . __('Remove image') . '</button>'."\n";
                $image_src = wp_get_attachment_image_src($value);

                $image_url = empty($image_src[0]) ? '' : $image_src[0];

                $bg = ' style="background: url('.$image_url.') no-repeat;width:'.$image_src[1].'px;height:'.$image_src[2].'px;"';
            }


            echo "<input class=\"zd-image-src-output\" id=\"zd-image-src-{$rand}\" value=\"{$image_url}\"/>\n";
            echo "<input type=\"hidden\" id=\"zd-image-id-{$rand}\" name=\"{$id}\" value=\"{$value}\" />\n";

            $hide = empty($image_url) ? ' zd-hide' : '';

            echo "<p class=\"image-preview-label{$hide}\"><strong>" . __('Image Preview', $this->text_domain) . "</strong></p>\n";

            echo "<div class=\"zd-image-preview\" id=\"zd-image-preview-{$rand}\"{$bg}>";
//            echo $value;
            echo "</div>\n";

            echo "</div>\n";
        }
        else if( 'select' === $type ) {
	        echo "<select id=\"{$id}\" name=\"{$id}\">\n";

	        echo "<option value=\"-1\">" . __('Select an option', $this->text_domain) . "</option>\n";

	        if( !empty($dropdown) ) {
		        foreach($dropdown as $option_value => $option_label) {
			        $selected = $value === $option_value ? ' selected' : '';

			        echo "<option value=\"{$option_value}\"{$selected}>" . __($option_label, $this->text_domain) . "</option>\n";
		        }
	        }

	        echo "</select>\n";
        }

        echo "</div><!--.input-group-->\n";

//        echo $args['type'];
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