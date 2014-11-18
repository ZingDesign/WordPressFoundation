<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 6/05/14
 * Time: 4:54 PM
 */


include_once get_template_directory() . '/inc/font-awesome-icons.php';

class ZingDesignThemeOptions {
    
    private $tabs;
    private $options;
    private $page_id;
	private $text_domain;
//	public $zd_theme_options_id;
	public $custom_css_file;
	public $custom_css_file_name;
	private $all_cats;

	private $form_helper;
    
    function __construct() {

	    $this->text_domain = ZD_TEXT_DOMAIN;
//	    $this->zd_theme_options_id = 'zd_theme_options';
	    $this->form_helper = new FormHelper();

	    $this->custom_css_file_name = '/css/theme.css';

	    $this->custom_css_file = get_template_directory() . $this->custom_css_file_name;

	    $exclude_cats = get_cat_ID('Uncategorised');

	    $exclude_cats .= ',' . get_term_id('Uncategorised', 'resource_category');

	    $all_categories = get_categories(array(
		    'exclude' => $exclude_cats
	    ));

	    $this->all_cats = $all_categories;

        if( is_admin() ) {
            add_action( 'admin_menu', array($this, 'zd_theme_options_init') );
            add_action( 'admin_init', array($this, 'zd_theme_settings') );
        }
        else {
//            add_action('wp_head', array($this, 'zd_header_output'));
//	        if( ! $this->write_css_to_file() ) {
//		        add_action('wp_head', array($this, 'generate_css_in_head'));
//	        }

//            add_action('wp_footer', array($this, 'zd_footer_output'));
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
			    'general'           => __('General',            'zingdesign'),
//			    'contact-details'   => __('Contact details',    'zingdesign'),
			    'social-media'      => __('Social Media',       'zingdesign'),
			    'analytics'         => __('Analytics',          'zingdesign'),
			    'newsletter'        => __('Mailchimp',          'zingdesign'),
			    'categories'        => __('Categories',         'zingdesign'),
			    'white_papers'      => __('White Papers',       'zingdesign'),
			    'design'            => __('Design',             'zingdesign'),
			    'addons'            => __('Add-ons',            'zingdesign'),
//			    'custom-post-types' => __('Custom post types',  'zingdesign'),
		    );

		    /*
		 * Define Options
		 * Format: ID => array Args
		 */
//		    $options_data_file = get_template_directory() . '/data/theme-options.json';
//		    $options = array();

//		    if( file_exists($options_data_file) && filesize($options_data_file) > 0) {
//			    $handle = fopen($options_data_file, "r");
//			    $file_contents = fread($handle, filesize($options_data_file));
//			    $options = json_decode($file_contents, TRUE);
//			    fclose($handle);
//		    }
//		    else {
			    $options = array(
//				    'header_logo' => array(
//					    'id'        => 'zd-logo-header',
//					    'type'      => 'image',
//					    'section'   => 'general'
//				    ),
//				    'footer_logo' => array(
//					    'id'        => 'zd-logo-footer',
//					    'type'      => 'image',
//					    'section'   => 'general'
//				    ),
				    'favicon_image' => array(
					    'type'      => 'image',
					    'section'   => 'general'
				    ),
				    'enable_comments_on_full_width' => array(
					    'type'      => 'checkbox',
					    'section'   => 'general'
				    ),
				    'enable_ajax_pagination' => array(
					    'label'     => __('Enable AJAX pagination', 'zingdesign'),
					    'tooltip'      => __('AJAX pagination means that the browser doesn\'t have to make a separate request for each page, which makes for a faster load and a smoother user experience.', 'zingdesign'),
					    'type'      => 'checkbox',
					    'section'   => 'addons'
				    ),
				    'enable_image_modal_slider' => array(
					    'label'     => __('Enable Image modal sliders', 'zingdesign'),
					    'tooltip'      => __('This feature allows users to view post images in a modal window, where they can be shown at their full-width, rather than constricted by the width of post content container. Users can then browse through all of the images on the page easily.', 'zingdesign'),
					    'type'      => 'checkbox',
					    'section'   => 'addons'
				    ),
				    'enable_image_lazy_load' => array(
					    'label'     => __('Enable image lazy-loading'),
					    'tooltip'   => __('Image lazy-loading involves loading images only as the user requires them (by watching how far the user has scrolled down the page), dramatically speeding up the page load'),
					    'type'      => 'checkbox',
					    'section'   => 'addons'
				    ),
				    'callout_button_text' => array(
					    'label'     => __('Header callout button text (The big green button)', 'zingdesign'),
					    'type'      => 'text',
					    'section'   => 'general',
					    'class'     => 'widefat',
//					    'default'   => __('Try Raygun FREE today', 'zingdesign')
				    ),
				    'callout_button_url' => array(
					    'label'     => __('Header callout button URL', 'zingdesign'),
					    'type'      => 'url',
					    'section'   => 'general',
					    'class'     => 'widefat',
//					    'default'   => 'https://app.raygun.io/signup'

				    ),
				    'show_full_post_in_blog' => array(
					    'type'      => 'checkbox',
					    'section'   => 'general',
					    'tooltip'   => __('Default is post excerpt and the "Continue Reading" button. It is recommended that you decrease the number of posts per page if enabled.', 'zingdesign')
				    ),
//				    'enable_mobile_navigation' => array(
//					    'id'        => 'zd-enable-mobile-navigation',
//					    'type'      => 'checkbox',
//					    'section'   => 'general',
//					    'default'   => true
//				    ),
//				    'mobile_navigation_alignment' => array(
//					    'id'        => 'zd-mobile-navigation-alignment',
//					    'type'      => 'select',
//					    'section'   => 'general',
//					    'dropdown'  => array(
//						    'left'  => 'Left',
//						    'right' => 'Right'
//					    ),
//					    'default'   => 'right'
//				    ),
//				    'show_search_form_in_header' => array(
//					    'type'      => 'checkbox',
//					    'default'   => true
//				    ),
				    'google_analytics_code' => array(
					    'section' => 'analytics',
					    'placeholder' => 'UA-XXXXXXXX-X'
				    ),



				    // Contact details
//				    'mailto_email_address' => array(
//					    'label'     => 'Email address',
//					    'section'   => 'contact-details',
//					    'type'      => 'email'
//				    ),
//				    'physical_address' => array(
//					    'label'     => 'Physical address',
//					    'section'   => 'contact-details',
//					    'type'      => 'textarea'
//				    ),

				    // Newsletter
//				    'enable_mailchimp_subscription_form' => array(
//					    'label'     => 'Enable MailChimp subscription form',
//					    'section'   => 'newsletter',
//					    'type'      => 'checkbox'
//				    ),
				    'mail_chimp_api_key' => array(
					    'label'     => 'MailChimp API key',
					    'name'      => 'mail_chimp_api_key',
					    'section'   => 'newsletter',
					    'type'      => 'text',
					    'class'     => 'widefat'
				    ),
				    'mail_chimp_list_id' => array(
					    'label'     => 'MailChimp List ID',
					    'name'      => 'mail_chimp_list_id',
					    'section'   => 'newsletter',
					    'type'      => 'text',
					    'class'     => 'widefat'
				    ),
				    'mail_chimp_endpoint_url' => array(
					    'label'     => 'MailChimp newsletter endpoint URL',
					    'section'   => 'newsletter',
					    'type'      => 'url',
					    'class'     => 'widefat'
				    ),

				    // White papers
				    'white_paper_form_title' => array(
					    'label'     => 'White Paper form title',
					    'section'   => 'white_papers',
					    'type'      => 'text',
					    'class'     => 'widefat'
				    ),
				    'white_paper_email_template' => array(
					    'label'     => 'White Paper email template',
					    'name'      => 'white_paper_email_template',
					    'section'   => 'white_papers',
					    'type'      => 'editor',
					    'editor_settings' => array(
						    'media_buttons' => false
					    )
				    ),
				    'white_paper_from_email' => array(
					    'label'     => 'White Paper "from" email',
					    'name'      => 'white_paper_from_email',
					    'section'   => 'white_papers',
					    'type'      => 'email'
				    ),
				    'show_fine_print' => array(
					    'label'     => 'Show Mailchimp "fine print"',
					    'section'   => 'white_papers',
					    'type'      => 'checkbox'
				    ),
				    'white_paper_fine_print_label' => array(
					    'label'     => 'White Paper fine print label',
					    'section'   => 'white_papers',
					    'type'      => 'text',
					    'class'     => 'widefat',
					    'dependencies' => array(
						    'target'    => '#show-fine-print',
						    'state'     => 'on',
						    'toggle'    => 'display'
					    )
				    ),
				    'white_paper_fine_print' => array(
					    'label'     => 'White Paper fine print',
					    'section'   => 'white_papers',
					    'type'      => 'textarea',
					    'dependencies' => array(
						    'target'    => '#show-fine-print',
						    'state'     => 'on',
						    'toggle'    => 'display'
					    )
				    ),
				    'primary_content_width_medium' => array(
					    'label'     => 'Primary content width (tablet-up)',
					    'section'   => 'design',
					    'type'      => 'number',
					    'help'   => 'See <a href="http://foundation.zurb.com/docs/components/grid.html" target="_blank">Foundation Grid docs</a> for more info',
					    'default'   => 8
				    ),
				    'primary_content_width_large' => array(
					    'label'     => 'Primary content width (desktop-up)',
					    'section'   => 'design',
					    'type'      => 'number',
					    'help'   => 'See <a href="http://foundation.zurb.com/docs/components/grid.html" target="_blank">Foundation Grid docs</a> for more info',
					    'default'   => 8
				    ),
				    'landing_posts_per_page' => array(
					    'label'     => 'Posts to display on the Landing Page',
					    'section'   => 'design',
					    'type'      => 'number',
				    ),
//				    'sidebar_content_width_medium' => array(
//					    'label'     => 'Sidebar width (tablet-up)',
//					    'section'   => 'design',
//					    'type'      => 'number',
//					    'default'   => '4'
//				    ),
//				    'sidebar_content_width_large' => array(
//					    'label'     => 'Sidebar width (desktop-up)',
//					    'section'   => 'design',
//					    'type'      => 'number',
//					    'default'   => '4'
//				    ),


//				    'category_icons' => array(
//					    'label'     => 'Category icons',
//					    'section'   => 'categories',
//					    'type'      => 'select',
//					    'dropdown'  => get_font_awesome_option_list(),
//					    'custom_options' => true
//				    )

				    // custom post types
//				    'new_custom_post_types' => array(
//					    'label'     => 'Custom post type names',
//					    'section'   => 'custom-post-types',
//					    'type'      => 'textarea',
//					    'help'      => __('List custom post types, separated by commas', ZD_TEXT_DOMAIN)
////					    'arg_name'  => 'zd_custom_post_type',
////					    'add_multiple' => true
//				    ),
//				    'custom_post_type_icon' => array(
//					    'label'     => 'Icon',
//					    'section'   => 'custom-post-types',
//					    'arg_name'  => 'zd_custom_post_type'
//				    )


			    );

		        // Add social media outlets
			    $social_media_outlets = array(
				    'github',
				    'facebook',
				    'twitter',
				    'google_plus',
				    'dribbble',
				    'linkedin',
				    'youtube',
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

		        // Categories

		        foreach($all_categories as $cat) {
			        $cat_label = str_replace( "_", " ", ucfirst($cat->name) ) . ' icon';
			        $option_id = $cat->slug . '_icon';
			        $option_access_id = str_replace("_", "-", $option_id);

			        $current_icon = get_option($option_access_id);

			        $options[$option_id] = array(
				        'label'             => $cat_label,
				        'section'           => 'categories',
				        'type'              => 'custom_dropdown',
                        'dropdown'          => get_font_awesome_dropdown_list($option_access_id, $current_icon),
                        'custom_options'    => true,
				        'after'             => '<span class="zd-icon-preview"><i class="fa fa-'.$current_icon.'"></i></span>',
				        'class'             => 'icon-dropdown'
			        );
		        }

//			    $this->write_to_file(json_encode($options), $options_data_file);
//		    }



		    $this->tabs = $tabs;

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

        echo '<form id="zd-options-form" method="POST" action="options.php">';

        echo "<h2 class=\"nav-tab-wrapper zd-admin-tab-nav\">\n";

//	    $i = 0;

        foreach( $this->tabs as $tab_id => $tab_label ) {
	        // Set first tab to active by default
//            $active = 0 === $i ? ' nav-tab-active' : '';

            echo "<a href=\"#tab-{$tab_id}\" class=\"nav-tab\">{$tab_label}</a>\n";

//	        $i ++;
        }
        echo "</h2>\n";

        settings_fields( 'zd_theme_options_page' );

        //do_settings_sections( 'zd_theme_options_page' ); 	//pass slug name of page

//	    $i = 0;

	    echo "<div class=\"zd-tab zd-active-tab loading\">\n";
	    echo "<i class=\"fa fa-spin fa-refresh\"></i>\n";
	    echo "<p>" . __('Loading', 'zingdesign') . "</p>\n";
	    echo "</div>\n";

        foreach( $this->tabs as $tab_id => $tab_label ) {
//            $active = (0 === $i) ? ' zd-active-tab' : '';

            echo "<div id=\"tab-{$tab_id}\" class=\"zd-tab\">\n";
            do_settings_fields( 'zd_theme_options_page', 'zd-'.$tab_id.'-section' );
            echo "</div>\n";

//	        $i ++;
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

	        $before = isset($option['before']) ? $option['before'] : '';
	        $after = isset($option['after']) ? $option['after'] : '';

	        // If ID not explicitly set, replace key underscores with dashes
            $id = isset($option['id']) ? $option['id'] : str_replace("_", "-", $key );
            $class = isset($option['class']) ? $option['class'] : '';

	        // replace key underscores with spaces to generate a title as a fallback for label
            $title = str_replace("_", " ", ucfirst( $key ) );

	        // Set option type to text by default (most common input?)
            $option_type = isset($option['type']) ? $option['type'] : 'text';

	        if( 'url' === $option_type && !isset($option['filter'] ) ) {
		        $option['filter'] = 'esc_url';
		        $class .= ' widefat';
	        }

	        // Set filter to esc_html by default
//            $filter = isset($option['filter']) ? $option['filter'] : 'esc_html';

	        // Set section to general by default
            $section = isset($option['section']) ? $option['section'] : 'general';

	        $dropdown = isset($option['dropdown']) ? $option['dropdown'] : false;

	        $placeholder = isset($option['placeholder']) ? $option['placeholder'] : false;

	        $required = isset($option['required']) ? $option['required'] : false;

	        $help = isset($option['help']) ? $option['help'] : false;
	        $tooltip = isset($option['tooltip']) ? $option['tooltip'] : false;

	        // Textarea rows and cols
	        $rows = isset($option['rows']) ? $option['rows'] : '5';
	        $cols = isset($option['cols']) ? $option['cols'] : '100';

	        $deps = isset($option['dependencies']) ? $option['dependencies'] : false;

	        if( isset($option['filter']) ) {
		        $filter = $option['filter'];
	        }
	        else if( 'textarea' === $option_type ) {
		        $filter = 'sanitize_text_field';
	        }
	        else if( 'editor' === $option_type ) {
		        $filter = ( current_user_can('unfiltered_html') ) ? 'balanceTags' : 'sanitize_text_field';
	        }
	        else if( 'url' === $option_type ) {
		        $filter = 'esc_url_raw';
	        }
	        else {
		        $filter = 'esc_html';
	        }

	        // Input wrapper - true by default
	        $wrapper = isset($option['wrapper']) ? $option['wrapper'] : true;

	        $editor_settings = isset($option['editor_settings']) ? $option['editor_settings'] : array();

//	        $add_multiple = isset($option['add_multiple']) ? $option['add_multiple'] : false;

	        // Set label if the input is NOT hidden

	        if( ! in_array($option_type, $no_label) ) {
		        $label = isset($option['label']) ? $option['label'] : $title;
	        }

	        if( isset($option['default']) ) {
		        add_option($id, $option['default']);
		        $default = $option['default'];
	        }

	        $custom_options = isset($option['custom_options']) ? $option['custom_options'] : false;

	        $input_options_array = array(
		        'id'            => $id,
		        'class'         => $class,
                'label'         => $label,
                'type'          => $option_type,
                'dropdown'      => $dropdown,
                'default'       => $default,
                'placeholder'   => $placeholder,
                'required'      => $required,
		        'rows'          => $rows,
		        'cols'          => $cols,
		        'wrapper'       => $wrapper,
		        'help'          => $help,
		        'custom_options'=> $custom_options,
		        'before'        => $before,
		        'after'         => $after,
		        'editor_settings' => $editor_settings,
		        'dependencies'  => $deps,
		        'tooltip'       => $tooltip
//		        'add_multiple'  => $add_multiple
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
		    $css .= "#{$body_id} {\n";

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

//	    if( get_option('zd-enable-mobile-navigation') ) {
//		    echo '<div class="cbp-menu-overlay"></div>'."\n";
//	    }
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