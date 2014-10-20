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
    new zd_metabox(
        'Revolution Slider',
        'page',
        'zd',
        $options = array(
            array(
                'label' => 'Show slider',
                'type' => 'checkbox'
            ),
            array(
                'label' => 'Slider',
                'type' => 'select',
                'dropdown' => array(
                    'home_slider' => 'Home slider',
                    'about_slider' => 'About slider'
                )
            )
        ),
        $settings = array(
            'position' => 'advanced',
            'priority' => 'low'
        )
    );

    // Products in market metabox

    $products = get_posts( array(
        'posts_per_page'    => -1,
        'post_type'         => 'zd_products') );

    $product_options = array();

    foreach( $products as $p ) {
        $product_options[] = array(
            'label'     => $p->post_title,
            'name'      => $p->ID,
            'type'      => 'checkbox',
            'filter'    => 'sanitize_checkbox'
        );
    }

    new zd_metabox(
        'Products in this market',  // Title
        'zd_markets',             // Post type
        'pitm',                     // Prefix
        $product_options,
        $settings = array(
            'position' => 'side',
            'priority' => 'low'
        )
    );

    // Banner example
    // Things to fix:
    // Don't show preview if no image
    // Increase the size of the textarea, make block
    // Add color input type

//    new zd_metabox(
//        'Banner',  // Title
//        'zd_markets',             // Post type
//        'test',                     // Prefix
//        $options = array(
//            array(
//                'label' => 'Banner title',
//                'type' => 'textarea'
//            ),
//            array(
//                'label' => 'Background image',
//                'type' => 'image'
//            ),
//            array(
//                'label' => 'Background repeat',
//                'type' => 'select',
//                'dropdown' => array(
//                    0 => 'no-repeat'
//                )
//            ),
//            array(
//                'label' => 'Background colour',
//                'type' => 'text'
//            ),
//            array(
//                'label' => 'Foreground image',
//                'type' => 'image'
//            )
//        ),
//        $settings = array(
//            'position' => 'advanced',
//            'priority' => 'high'
//        )
//    );

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



    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct($metabox_title, $post_type, $prefix, $options=array(), $settings=array()) {
        add_action( 'add_meta_boxes', array( $this, 'add_custom_meta_box' ) );
        add_action( 'save_post', array( $this, 'save' ) );

        $this->post_type = $post_type;
        $this->args_name = $prefix . '_args';
        $this->metadata_name = '_' . $prefix . '_data';
        $this->nonce_name = $prefix . '_nonce';
        $this->nonce_id = $prefix . '_nonce_id';

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
//        var_dump(isset($_POST[$this->args_name]));

        if( isset($_POST[$this->args_name]) ) {
            // Sanitize the user input.
            $data = array();

//            $data['test'] = 'testing';

//            $data['zd_show_call_to_action'] = isset($_POST[$this->args_name]['zd_show_call_to_action']) ? sanitize_text_field($_POST['zdsa']['zd_show_call_to_action']) : '';
            //
            //            $data['zd_call_to_action'] = isset($_POST[$this->args_name]['zd_call_to_action']) ? esc_url($_POST['zdsa']['zd_call_to_action']) : '';
            //
            //            $data['zd_call_to_action_text'] = isset($_POST[$this->args_name]['zd_call_to_action_text']) ? sanitize_text_field($_POST['zdsa']['zd_call_to_action_text']) : '';
            //
            //            $data['zd_new_window'] = isset($_POST[$this->args_name]['zd_new_window']) ? sanitize_text_field($_POST['zdsa']['zd_new_window']) : '';
//            echo "<pre>";
//            print_r($_POST[$this->args_name]);
//            echo "</pre>";

            foreach($this->options as $option) {
                $filter = isset($option['filter']) ? $option['filter'] : 'sanitize_text_field';

                $name = isset($option['name']) ? $option['name'] : str_replace( ' ', '_', strtolower($option['label']) );

//                var_dump($name);

                $data[$name] = isset($_POST[$this->args_name][$name]) ? call_user_func($filter, $_POST[$this->args_name][$name]) : '';
             }
//            echo "<pre>";
//            print_r($data);
//            echo "</pre>";


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

        // Use get_post_meta to retrieve an existing value from the database.
//        $data = get_post_meta( $post->ID, $this->metadata_name, true );

//        $show_checked = isset($data['zd_show_call_to_action']) ? ' checked' : '';
//        $new_window_checked = isset($data['zd_new_window']) ? ' checked' : '';
//        $link = isset($data['zd_call_to_action']) ? $data['zd_call_to_action'] : '';
//        $text = isset($data['zd_call_to_action_text']) ? $data['zd_call_to_action_text'] : '';

//        $name_base = $this->args_name;

//        print_r($this->options);

        foreach( $this->options as $option ) {
            $label = $option['label'];
            $id = isset($option['id']) ? $option['id'] : str_replace( ' ', '-', strtolower( $label ) );
            $name = isset($option['name']) ? $option['name'] : str_replace( '-', '_', $id );
            $dropdown_list = isset($option['dropdown']) ? $option['dropdown'] : false;

            $this->setting_input(array(
                'label' => $label,
                'id' => $this->prefix . '-' . $id,
                'name' => $name,
                'type' => $option['type'],
                'dropdown' => $dropdown_list,
                'post_id' => $post->ID
            ));
        }
        unset($option, $label, $id, $name);

        $this->setting_input(array(
            'label' => false,
            'type' => 'hidden',
            'id'    => $this->prefix . '-force-save',
            'name' => $this->prefix . '_force_save',
            'post_id' => $post->ID
        ));
//
        echo '<input class="button button-primary button-large" type="submit" value="'.__('Save', $this->text_domain).'" />'."\n";
    }
    
    public function setting_input( $args ) {

        $label = $args['label'];
        $type = isset($args['type']) ? $args['type'] : 'text';
        $id = $args['id'];
        $name = $args['name'];
        $post_id = $args['post_id'];

        $base_name = $this->args_name;

//        if( ! $type ) {
//            $type = 'text';
//        }

        $is_checkbox = 'checkbox' === $type;
        $is_hidden = 'hidden' === $type;

        if( ! $id ) {
            echo "<p class=\"error\">Error: ID required when initialising new settings</p>\n";
            return;
        }

//        $value = get_option( $name );
        $data = get_post_meta( $post_id, $this->metadata_name, true );

//        print_r($data);
//        var_dump($name);

        $value = !empty($data[$name]) ? esc_attr($data[$name]) : '';

        if( ! $is_hidden ) {

            echo "<div class=\"zd-input-group\">\n";
            echo "<label for=\"{$id}\">{$label}</label>\n";
        }

        if( in_array($type, array('text', 'email', 'number', 'checkbox', 'hidden')) ) {
            $checked = '';

            if( $is_checkbox || $is_hidden ) {
                $value = '1';

                if( $is_checkbox ) {
                    $checked = (!empty($data[$name]) && $data[$name] === "1") ? ' checked="checked"' : '';
                }
                
//                $checked = checked( $value, true, false );
            }

            echo "<input type=\"{$type}\" id=\"{$id}\" name=\"{$base_name}[{$name}]\" value=\"{$value}\"{$checked} />\n";
        }

        else if( 'textarea' === $type ) {
            echo "<textarea id=\"{$id}\" name=\"{$base_name}[{$name}]\">{$value}</textarea>";
        }

        else if( 'image' === $type ) {
            $bg = '';
            echo "<div>\n";
            $rand = mt_rand(100, 1000);

            $button_text = empty($value) ? 'Insert image' : 'Change image';
            echo "<button class=\"zd-insert-image-button button button-default\">".__($button_text, $this->text_domain)."</button>\n";

            $image_url = '';

            if( !empty($value) ) {
                echo '<button class="zd-remove-image-button button button-default">' . __('Remove image') . '</button>'."\n";
                $image_src = wp_get_attachment_image_src($value);

                $image_url = empty($image_src[0]) ? '' : $image_src[0];

                $bg = ' style="background: url('.$image_url.') no-repeat;width:'.$image_src[1].'px;height:'.$image_src[2].'px;"';
            }


            echo "<input class=\"zd-image-src-output\" id=\"zd-image-src-{$rand}\" value=\"{$image_url}\"/>\n";
            echo "<input type=\"hidden\" id=\"zd-image-id-{$rand}\" name=\"{$base_name}[{$name}]\" value=\"{$value}\" />\n";

            $hide = empty($image_url) ? ' zd-hide' : '';

            echo "<p class=\"image-preview-label{$hide}\"><strong>" . __('Image Preview', $this->text_domain) . "</strong></p>\n";

            echo "<div class=\"zd-image-preview\" id=\"zd-image-preview-{$rand}\"{$bg}>";
            echo "</div>\n";

            echo "</div>\n";
        }

        else if( 'select' === $type ) {
            echo "<select id=\"{$id}\" name=\"{$name}\">\n";

            echo "<option value=\"-1\">" . __('Select an option', $this->text_domain) . "</option>\n";

            if( $args['dropdown'] ) {
                foreach($args['dropdown'] as $option_value => $option_label) {
                    $selected = $value === $option_value ? ' selected' : '';

                    echo "<option value=\"{$option_value}\"{$selected}>" . __($option_label, $this->text_domain) . "</option>\n";
                }
            }

            echo "</select>\n";
        }

        if( ! $is_hidden ) {
            echo "</div><!--.zd-input-group-->\n";
        }

    }
}