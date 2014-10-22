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
//	public $zd_theme_options_id;
	public $custom_css_file;
	public $custom_css_file_name;

	private $form_helper;
    
    function __construct() {

	    $this->text_domain = ZD_TEXT_DOMAIN;
//	    $this->zd_theme_options_id = 'zd_theme_options';
	    $this->form_helper = new FormHelper();

	    $this->custom_css_file_name = '/css/theme.css';

	    $this->custom_css_file = get_template_directory() . $this->custom_css_file_name;

        if( is_admin() ) {
            add_action( 'admin_menu', array($this, 'zd_theme_options_init') );
            add_action( 'admin_init', array($this, 'zd_theme_settings') );
        }
        else {
//            add_action('wp_head', array($this, 'zd_header_output'));
	        if( ! $this->write_css_to_file() ) {
		        add_action('wp_head', array($this, 'generate_css_in_head'));
	        }

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

	    if( is_admin() ) {
		    // Define tabs
		    // Format: ID => Label
		    $tabs = array(
			    'general'           => 'General',
			    'contact-details'   => 'Contact details',
			    'social-media'      => 'Social Media',
			    'analytics'         => 'Analytics',
			    'newsletter'        => 'Newsletter',
			    'design'            => 'Design',
		    );

		    /*
		 * Define Options
		 * Format: ID => array Args
		 */
		    $options_data_file = get_template_directory() . '/data/theme-options.json';
		    $options = array();

//		    if( file_exists($options_data_file) && filesize($options_data_file) > 0) {
//			    $handle = fopen($options_data_file, "r");
//			    $file_contents = fread($handle, filesize($options_data_file));
//			    $options = json_decode($file_contents, TRUE);
//			    fclose($handle);
//		    }
//		    else {
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
				    'show_search_form_in_header' => array(
					    'type'      => 'checkbox',
					    'default'   => true
				    ),
				    'google_analytics_code' => array(
					    'section' => 'analytics',
					    'placeholder' => 'UA-XXXXXXXX-X'
				    ),



				    // Contact details
				    'mailto_email_address' => array(
					    'label'     => 'Email address',
					    'section'   => 'contact-details',
					    'type'      => 'url'
				    ),
				    'physical_address' => array(
					    'label'     => 'Physical address',
					    'section'   => 'contact-details',
					    'type'      => 'textarea'
				    ),

				    // Newsletter
				    'enable_mailchimp_subscription_form' => array(
					    'label'     => 'Enable MailChimp subscription form',
					    'section'   => 'newsletter',
					    'type'      => 'checkbox'
				    ),
				    'mail_chimp_endpoint_url' => array(
					    'label'     => 'MailChimp endpoint',
					    'section'   => 'newsletter',
					    'type'      => 'url'
				    ),

				    // Design
				    'custom_body_id' => array(
					    'label'     => 'Page content wrapper ID',
					    'default'   => 'page'
				    ),
				    'custom_header_id' => array(
					    'label'     => 'Header container ID',
					    'default'   => 'header-container'
				    ),
				    'custom_footer_id' => array(
					    'label'     => 'Footer container ID',
					    'default'   => 'colophon'
				    ),
				    'body_background_color' => array(
					    'label'     => 'Body background color',
					    'section'   => 'design',
					    'type'      => 'color'
				    ),
				    'body_text_color' => array(
					    'label'     => 'Body text color',
					    'section'   => 'design',
					    'type'      => 'color'
				    ),
				    'header_background_color' => array(
					    'label'     => 'Header background color',
					    'section'   => 'design',
					    'type'      => 'color'
				    ),
				    'header_text_color' => array(
					    'label'     => 'Header text color',
					    'section'   => 'design',
					    'type'      => 'color'
				    ),
				    'footer_background_color' => array(
					    'label'     => 'Footer background color',
					    'section'   => 'design',
					    'type'      => 'color'
				    ),
				    'footer_text_color' => array(
					    'label'     => 'Footer text color',
					    'section'   => 'design',
					    'type'      => 'color'
				    )


			    );

		        // Add social media outlets
			    $social_media_outlets = array(
				    'facebook',
				    'twitter',
				    'linkedin',
				    'google_plus',
				    'youtube',
				    'dribbble',
				    'flickr',
				    'tumblr'
			    );

			    foreach($social_media_outlets as $sm) {
				    // Social media
				    $sm_label = str_replace( "_", " ", ucfirst($sm) ) . ' URL';

				    $options[$sm . '_url'] = array(
					    'label'     => $sm_label,
					    'section'   => 'social-media',
					    'type'      => 'url'
				    );
			    }

			    $this->write_to_file(json_encode($options), $options_data_file);
//		    }



		    $this->tabs = $tabs;

		    //	    var_dump($options);

		    $this->options = $options;

		    $this->page_id = 'zd_theme_options_page';
	    }


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

        echo '<form id="zd-options-form" method="POST" action="options.php" data-abide>';

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

	    $theme_options_array = array();

        foreach( $this->options as $key => $option ) {
	        $default = $label = false;

	        // Validate options

	        // If ID not explicitly set, replace key underscores with dashes
            $id = isset($option['id']) ? $option['id'] : str_replace("_", "-", $key );

	        // replace key underscores with spaces to generate a title as a fallback for label
            $title = str_replace("_", " ", ucfirst( $key ) );

	        // Set option type to text by default (most common input?)
            $option_type = isset($option['type']) ? $option['type'] : 'text';

	        if( 'url' === $option_type && !isset($option['filter'] ) ) {
		        $option['filter'] = 'esc_url';
	        }

	        // Set filter to esc_html by default
            $filter = isset($option['filter']) ? $option['filter'] : 'esc_html';

	        // Set section to general by default
            $section = isset($option['section']) ? $option['section'] : 'general';

	        $dropdown = isset($option['dropdown']) ? $option['dropdown'] : false;

	        $placeholder = isset($option['placeholder']) ? $option['placeholder'] : false;

	        $required = isset($option['required']) ? $option['required'] : false;

	        // Textarea rows and cols
	        $rows = isset($option['rows']) ? $option['rows'] : '5';
	        $cols = isset($option['cols']) ? $option['cols'] : '100';


	        // Input wrapper - true by default
	        $wrapper = isset($option['wrapper']) ? $option['wrapper'] : true;

	        // Set label if the input is NOT hidden

	        if( ! in_array($option_type, $no_label) ) {
		        $label = isset($option['label']) ? $option['label'] : $title;
	        }

	        if( isset($option['default']) ) {
		        add_option($id, $option['default']);
		        $default = $option['default'];
	        }

	        $input_options_array = array(
		        'id'            => $id,
                'label'         => $label,
                'type'          => $option_type,
                'dropdown'      => $dropdown,
                'default'       => $default,
                'placeholder'   => $placeholder,
                'required'      => $required,
		        'rows'          => $rows,
		        'cols'          => $cols,
		        'wrapper'       => $wrapper
	        );

            add_settings_field(
                $id,
                '',
                array($this, 'get_setting_input'),
                $this->page_id,
                'zd-'.$section.'-section',
                $input_options_array
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



    function generate_css() {
        $css = '';

	    $body_id = get_option('custom-body-id');
	    $header_id = get_option('custom-header-id');
	    $footer_id = get_option('custom-footer-id');

	    $body_background_color = get_option('body-background-color');
	    $body_text_color = get_option('body-text-color');
	    $header_background_color = get_option('header-background-color');
	    $header_text_color = get_option('header-text-color');
	    $footer_background_color = get_option('footer-background-color');
	    $footer_text_color = get_option('footer-text-color');

	    $css .= "@charset \"utf-8\";\n";
	    $css .= "/* --------- Styles generated by ZD theme options --------- */\n";

        if( get_option('zd-logo') ) {
            $logo_src = wp_get_attachment_image_src( get_option('zd-logo'), 'full' );
            $css .= '.header-logo {'."\n";
            $css .= '  display: block;'."\n";
            $css .= '  background-image: url(' . $logo_src[0] . ');'."\n";
            $css .= '  width: ' . $logo_src[1] . 'px;'."\n";
            $css .= '  height: ' . $logo_src[2] . 'px;'."\n";
            $css .= '}'."\n";
//            $css .= '</style>'."\n";
        }

        if( get_option('zd-logo-footer') ) {
            $logo_src = wp_get_attachment_image_src( get_option('zd-logo-footer'), 'full' );

//            $css .= '<style type="text/css">'."\n";
            $css .= '.footer-logo {'."\n";
            $css .= '  display: block;'."\n";
            $css .= '  background-image: url(' . $logo_src[0] . ');'."\n";
            $css .= '  width: ' . $logo_src[1] . 'px;'."\n";
            $css .= '  height: ' . $logo_src[2] . 'px;'."\n";
            $css .= '}'."\n";
        }

	    // Body styles
	    if( $body_background_color || $body_text_color ) {
		    $css .= "#page {\n";

		    if( $body_background_color ) {
			    $css .= "  background-color: {$body_background_color};\n";
		    }
		    if( $body_text_color ) {
			    $css .= "  color: {$body_text_color};\n";
		    }

		    $css .= "}\n";
	    }

	    // Header styles
	    if( $header_background_color || $header_text_color ) {
		    $css .= "#{$header_id} {\n";
		    
		    if( $header_background_color ) {
			    $css .= "  background-color: {$header_background_color};\n";
		    }
		    if( $header_text_color ) {
			    $css .= "  color: {$header_text_color};\n";
		    }
		    
		    $css .= "}\n";
	    }
	    

	    if( $footer_background_color || $footer_text_color ) {
		    $css .= "#{$footer_id} {\n";
		    
		    if( $footer_background_color ) {
			    $css .= "  background-color: {$footer_background_color};\n";
		    }
		    if( $footer_text_color ) {
			    $css .= "  color: {$footer_text_color};\n";
		    }
		    
		    $css .= "}\n";
	    }

//        echo $css;

	    return $css;

    }

    function zd_footer_output() {

	    if( get_option('zd-enable-mobile-navigation') ) {
		    echo '<div class="cbp-menu-overlay"></div>'."\n";
	    }
    }

	function zd_get_option($option_id, $default=false) {
		$zd_theme_option = get_option( $option_id );

		if( isset($zd_theme_option) ) {
			return $zd_theme_option;
		}
		else if($default) {
			return $default;
		}
		else if(WP_DEBUG) {
			return "<p>ZD Error: Invalid option ID: {$option_id}</p>\n";
		}
	}

	function write_css_to_file() {
		return $this->write_to_file($this->generate_css(), $this->custom_css_file);
	}

	function write_to_file($_content, $filename) {
		// Let's make sure the file exists and is writable first.
		if (is_writable($filename)) {

			// In our example we're opening $filename in append mode.
			// The file pointer is at the bottom of the file hence
			// that's where $somecontent will go when we fwrite() it.
			if (!$handle = fopen($filename, 'w')) {
				echo "Cannot open file ($filename)";
//				exit;
				return false;
			}

			// Write $somecontent to our opened file.
			if (fwrite($handle, $_content) === FALSE) {
				echo "Cannot write to file ($filename)";
//				exit;
				return false;
			}

//			echo "Success, wrote ($_css) to file ($filename)";

			fclose($handle);
			return true;

		} else {
			echo "The file {$filename} is not writable";
			return false;
		}
	}

	function generate_css_in_head() {

		$css = $this->generate_css();

		echo '<style type="text/css">'."\n" . str_replace("\n", "", $css) . '\n</style>'."\n";
	}
}

new ZingDesignThemeOptions();