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

	$custom_post_types = get_post_types( array(
		'public'   => true,
		'_builtin' => false
	), 'names');

//	$custom_post_types[] = 'post';

    new zd_metabox(
        'Posts to display',
        'page',
        'zd_posts',
        $options = array(
	        array(
		        'label' => 'Post container',
		        'type' => 'checkbox'
	        ),
            array(
                'label' => 'Post type',
                'type' => 'select',
                'dropdown' => $custom_post_types
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


        if( isset($_POST[$this->args_name]) ) {
            // Sanitize the user input.
            $data = array();

            foreach($this->options as $option) {
                $filter = isset($option['filter']) ? $option['filter'] : 'sanitize_text_field';

                $name = isset($option['name']) ? $option['name'] : str_replace( ' ', '_', strtolower($option['label']) );

                $data[$name] = isset($_POST[$this->args_name][$name]) ? call_user_func($filter, $_POST[$this->args_name][$name]) : '';
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
            $label = $option['label'];
            $id = isset($option['id']) ? $option['id'] : str_replace( ' ', '-', strtolower( $label ) );
            $name = isset($option['name']) ? $option['name'] : str_replace( '-', '_', $id );
            $dropdown_list = isset($option['dropdown']) ? $option['dropdown'] : false;
	        echo $this->form_helper->zd_setting_input(array(
		        'label'         => $label,
		        'id'            => $this->prefix . '-' . $id,
		        'name'          => $name,
		        'type'          => $option['type'],
		        'dropdown'      => $dropdown_list,
		        'post_id'       => $post->ID,
		        'metadata_name' => $this->metadata_name,
		        'arg_name'      => $this->args_name
	        ));
        }
        unset($option, $label, $id, $name);


	    echo $this->form_helper->zd_setting_input(array(
		    'label'     => false,
            'type'      => 'hidden',
            'id'        => $this->prefix . '-force-save',
            'name'      => $this->prefix . '_force_save',
            'post_id'   => $post->ID,
            'metadata_name' => $this->metadata_name
	    ));
//
        echo '<input class="button button-primary button-large" type="submit" value="'.__('Save', $this->text_domain).'" />'."\n";
    }

	public static function zd_get_custom_meta($post_id, $meta_name) {

		$data = get_post_meta($post_id, self::$_metadata_name, true);

		return unserialize($data['_' . $meta_name . '_data'][0]);

//		print_r($data);
//
//		return $data['_' . $data_id . '_data'][$option_id];
	}
}