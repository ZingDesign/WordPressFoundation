<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 18/06/14
 * Time: 12:38 PM
 */

/**
 * Calls the class on the post edit screen.
 */


function call_zd_metabox() {

//	$custom_post_types = get_post_types( array(
//		'public'   => true,
//		'_builtin' => false
//	), 'names');

//	$custom_post_types[] = 'post';

	$post_id = null;
	if(isset($_POST['post']) ) {
		$post_id = $_POST['post'];
	}
	else if(isset( $_GET['post'] ) ) {
		$post_id = $_GET['post'];
	}

	$category_options = array();

	if( "page-templates/landing-page.php" !== get_page_template_slug($post_id) ) {
		$category_options[] = array(
			'type' => 'label',
			'text' => 'Enable the "Landing Page" template in order to use this functionality'
		);
	}

	$category_options[] = array(
		'label' => 'Post container',
		'type' => 'checkbox'
	);

	$all_categories = get_categories();

	$all_categories_checkboxes = array();

	foreach($all_categories as $cat) {
		$all_categories_checkboxes[$cat->cat_ID] = $cat->name;
//		$category_options[] = array(
//			'label' => $cat->name,
//			'name'  => $cat->cat_ID,
//			'type'  => 'checkbox'
//		);


	}

	$category_options[] = array(
		'label'         => 'Categories to display',
		'type'          => 'multiple_checkbox',
		'checkboxes'    => $all_categories_checkboxes
	);
//	global $post;

//	_d($all_categories_checkboxes);

    new zd_metabox(
        'Landing page options',
        'page',
        'zd_display_categories',
        $category_options,
        $settings = array(
            'position' => 'advanced',
            'priority' => 'low'
        )
    );

	// Show post as a "Large" post on the landing page

    new zd_metabox(
        'Large post',
        'post',
        'is_large_post',
        array(
	        array(
		        'label' => 'Display as a large post on the Landing page',
		        'id'    => 'is-large-post',
		        'name'  => 'is_large_post',
		        'type'  => 'checkbox'
	        )
        ),
        $settings = array(
            'position' => 'side',
            'priority' => 'low'
        )
    );

	// File upload for White Paper

	new zd_metabox(
		'White Paper',
		'white_paper',
		'white_paper',
		array(
			array(
				'label' => 'Select White Paper PDF file',
				'id'    => 'white-paper-pdf',
				'name'  => 'white_paper_pdf',
				'type'  => 'file'
			)
		),
		$settings = array(
			'position' => 'advanced',
			'priority' => 'low'
		)
	);




}

function sanitize_checkbox( $value ) {
    return $value;
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'call_zd_metabox' );
    add_action( 'load-post-new.php', 'call_zd_metabox' );
}

/**
 * The Class.
 */
class zd_metabox {

    private $post_type;
    private $prefix;
    private $args_name;
    private $text_domain;
    private $metadata_name;
    private $nonce_name;
    private $nonce_id;

    private $options;

    private $metabox_title;
    private $metabox_id;

    private $position;
    private $priority;

	private $form_helper;

	static $_metadata_name;



    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct($metabox_title, $post_type, $prefix, $options=array(), $settings=array()) {
        add_action( 'add_meta_boxes', array( $this, 'add_custom_meta_box' ) );
        add_action( 'save_post', array( $this, 'save' ) );

        $this->post_type = $post_type;
        $this->args_name = $prefix . '_args';
        $this->metadata_name = '_' . $prefix . '_data';

	    self::$_metadata_name = $this->metadata_name;

        $this->nonce_name = $prefix . '_nonce';
        $this->nonce_id = $prefix . '_nonce_id';

	    $this->form_helper = new FormHelper();

        $default_settings = array(
            'position' => 'side',
            'priority' => 'low'
        );
        
        $metabox_settings = wp_parse_args($settings, $default_settings);

        extract( $metabox_settings );

        $this->metabox_title = $metabox_title;
        $this->metabox_id = str_replace(' ', '_', strtolower($metabox_title) );

        $this->position = $position;
        $this->priority = $priority;
        $this->text_domain = 'zingdesign';

        $this->options = $options;

        $this->prefix = $prefix;
    }

    /**
     * Adds the meta box container.
     */
    public function add_custom_meta_box( $post_type ) {
        if( $this->post_type === $post_type ) {
            add_meta_box(
                $this->metabox_id
                ,__( $this->metabox_title, $this->text_domain )
                ,array( $this, 'render_meta_box_content' )
                ,$post_type
                ,$this->position
                ,$this->priority
            );
        }
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {

        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( ! isset( $_POST[$this->nonce_name] ) )
            return $post_id;

        $nonce = $_POST[$this->nonce_name];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, $this->nonce_id ) )
            return $post_id;

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $post_id;

        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) )
                return $post_id;

        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }

        /* OK, its safe for us to save the data now. */

//	    _d($_POST[$this->args_name]);

        if( isset($_POST[$this->args_name]) ) {

            // Sanitize the user input.
            $data = array();

            foreach($this->options as $option) {

	            if( 'label' !== $option['type'] ) {

		            $filter = isset($option['filter']) ? $option['filter'] : 'sanitize_text_field';

		            $name = isset($option['name']) ? $option['name'] : str_replace( ' ', '_', strtolower($option['label']) );

		            if( isset($_POST[$this->args_name][$name]) ) {

			            $current_data = $_POST[$this->args_name][$name];

			            if( is_array($current_data) ) {
				            $current_data = base64_encode(serialize($current_data));
			            }

			            if( $filter ) {
				            $data[$name] = call_user_func($filter, $current_data);
			            }
			            else {
				            $data[$name] = $current_data;
			            }
		            }

	            }

             }

            // Update the meta field.
            update_post_meta( $post_id, $this->metadata_name, $data);
        }

    }


    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {

        $label = $id = $name = '';

        // Add an nonce field so we can check for it later.
        wp_nonce_field( $this->nonce_id, $this->nonce_name );

        foreach( $this->options as $option ) {
	        if( 'label' === $option['type'] ) {
		        echo isset($option['text']) ? '<p><strong>' . $option['text'] . '</strong></p>' : '';
	        }
	        else {
		        $label =                $option['label'];
		        $type =                 isset($option['type']) ? $option['type'] : 'text';
		        $id =                   isset($option['id']) ? $option['id'] : str_replace( ' ', '-', strtolower( $label ) );
		        $name =                 isset($option['name']) ? $option['name'] : str_replace( '-', '_', $id );
		        $dropdown_list =        isset($option['dropdown']) ? $option['dropdown'] : false;
		        $checkboxes =           isset($option['checkboxes']) ? $option['checkboxes'] : false;
		        $before =               isset($option['before']) ? $option['before'] : false;
		        $after =                isset($option['after']) ? $option['after'] : false;

		        echo $this->form_helper->zd_setting_input(array(
			        'label'             => $label,
			        'id'                => $this->prefix . '-' . $id,
			        'name'              => $name,
			        'type'              => $type,
			        'dropdown'          => $dropdown_list,
			        'post_id'           => $post->ID,
			        'metadata_name'     => $this->metadata_name,
			        'arg_name'          => $this->args_name,
			        'checkboxes'        => $checkboxes,
			        'before'            => $before,
			        'after'             => $after
		        ));
	        }


        }
//        unset($option, $label, $id, $name);


	    echo $this->form_helper->zd_setting_input(array(
		    'label'     => false,
            'type'      => 'hidden',
            'id'        => $this->prefix . '-force-save',
            'name'      => $this->prefix . '_force_save',
            'post_id'   => $post->ID,
            'metadata_name' => $this->metadata_name,
            'arg_name'          => $this->args_name,
		    'value'     => '1'
	    ));
//
        echo '<input class="button button-primary button-large" type="submit" value="'.__('Save', $this->text_domain).'" />'."\n";
    }

	public static function zd_get_custom_meta($post_id, $meta_name, $property=null) {

		$meta_data = false;

		$data = get_post_meta($post_id, self::$_metadata_name, true);

		$index = '_' . $meta_name . '_data';

		if( isset( $data[$index][0] )) {

			$meta_data = unserialize( $data[$index][0] );

			if( $property ) {
				return is_array($property) ? $meta_data[$property] : unserialize( base64_decode( $meta_data[$property] ) );
			}
		}

		return $meta_data;

//		print_r($data);
//
//		return $data['_' . $data_id . '_data'][$option_id];
	}
}