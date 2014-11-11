<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 21/10/14
 * Time: 11:37 AM
 */

class FormHelper {


	public function zd_setting_input( $args ) {


//		$id = isset($args['id']) ? $args['id'] : isset($args['label']) ? ;

		// Ascertain the input ID:
		$id = false;

		$name = '';

		if( isset($args['id'])) {
			$id = $args['id'];
		}
		else if( isset($args['label']) ) {
			$id = str_replace(" ", "-", strtolower( strip_tags($args['label']) ) );
		}
//		else if( isset($args['name']) ) {
//			$id = str_replace("_", "-", strtolower( strip_tags($args['name']) ) );
//		}
//		_d($id);

		// If neither id, label or name are set, this is not being used properly, return error
		if( ! $id ) {
			return "<p class=\"zd-error\">ZD Error: ID required when initialising new settings</p>\n";
		}

		// Transient IDs
		$transient_id = str_replace("-", "_", $id);
		$transient_value_id = $transient_id . '_value';

		// Values required for ascertaining value

		if( isset($args['name']) ) {
			$name = $args['name'];
		}
		else {
			$name = $id;
		}

		$option_name = $name;

		// Input type
		$type = isset($args['type']) ? $args['type'] : 'text';

		// default value
		$default = isset($args['default']) ? $args['default'] : '';

		// Label

		$label = isset($args['label']) ? $args['label'] : ucfirst($type) . ' input';

		$is_required = isset($args['required']) ? $args['required'] : false;

		$required_indicator = $is_required ? '<span class="required">*</span>' : '';

		$label_html = "<label for=\"{$id}\">{$label} {$required_indicator}</label>\n";





		// Ascertain current value
		// Set current value to stored value if a value has been set
		// Or set to default if not

		// Get value from post meta if the post_id and metadata name is set
		//		var_dump($args['metadata_name']);

		// Check if manually set
		if( isset($args['value']) ) {
			$current_value = $args['value'];
		}
		// If in post meta (metabox)
		else if( isset($args['post_id']) && isset($args['metadata_name']) ) {
			$post_id = $args['post_id'];
			$data = get_post_meta( $post_id, $args['metadata_name'], true );
			$current_value = isset($data[$option_name]) ? esc_attr($data[$option_name]) : '';

			if( 'multiple_checkbox' === $type && is_string($current_value) ) {
				//				_d( $current_value );
				$current_value = unserialize( base64_decode( $current_value ) );
			}
		}
		// If in a setting field (theme options)
		else if( get_option( $id ) ) {
			$current_value = get_option( $id );
		}
		// If sent by form
		else if(isset($_POST[$name]) ) {
			$current_value = esc_html($_POST[$name]);
		}
		// If a default value has been set / empty string
		else {
			$current_value = $default;
		}

		// Not cached by default
		$is_cached = false;
		$cached_html = '';


		// Editor type is exceptional
		if( 'editor' === $type ) {

			$editor_settings = isset($args['editor_settings']) ? $args['editor_settings'] : array();

			if( ! user_can_richedit() ) {
				$type = 'textarea';
			}
			else {
				// Echo the Editor and return
				echo $label_html;
				$editor_settings['textarea_name'] = $name;
				$editor_id = $this -> strip_non_alphanumeric($id);
//				_d( $editor_id );
				wp_editor($current_value, $editor_id, $editor_settings);
				return '';
			}
		}

		// First, Check if the the field has been cached

		// Check if there is a corresponding value

		// If the stored value is the same as the current value,
		// We don't need to regenerate the form input
		if( get_transient($transient_id)
		    && get_transient($transient_value_id)
			&& base64_decode( get_transient($transient_value_id) ) === $current_value ) {

			$cached_html = get_transient($transient_id);
			$is_cached = true;

		}

		// Check if cached and not dev
		if( $is_cached && ! zd_is_dev() ) {
			return base64_decode($cached_html);
		}
		else {
			// Generate HTML
			$html = $before = $after = $class = '';

			//		$name = isset($args['name']) ? $args['name'] : $id;

			//		$add_multiple = isset($args['add_multiple']) ? $args['add_multiple'] : false;

			//		if($add_multiple) {
			//			$name = $name . '[]';
			//		}

			if( isset($args['arg_name']) ) {
				$name = $args['arg_name'] . '[' . $name . ']';
			}

			$class = isset($args['class']) ? $args['class'] : '';

			$dropdown = isset($args['dropdown']) ? $args['dropdown'] : array();

			$is_checkbox = 'checkbox' === $type;
			$is_hidden = 'hidden' === $type;

			$has_placeholder = isset($args['placeholder']) ? $args['placeholder'] : false;

			$placeholder = $has_placeholder ? ' placeholder="'.$args['placeholder'].'"' : '';
			$required = $is_required ? ' required' : '';

			$text_based_inputs = array('text', 'email', 'number', 'hidden', 'url', 'color', 'tel');

			$wrapper = isset($args['wrapper']) ? $args['wrapper'] : true;
			$wrapper_element = isset($args['wrapper_element']) ? $args['wrapper_element'] : 'div';
			$wrapper_class = isset($args['wrapper_class']) ? $args['wrapper_class'] : 'input-group zd-input-group';

			$help = isset($args['help']) ? $args['help'] : false;
			$tooltip = isset($args['tooltip']) ? $args['tooltip'] : false;

			$rows = isset($args['rows']) ? $args['rows'] : '';
			$cols = isset($args['cols']) ? $args['cols'] : '';

			$custom_options = isset( $args['custom_options'] ) ? $args['custom_options'] : false;

			$error = isset($args['error']) ? $args['error'] : false;

			$deps = isset($args['dependencies']) ? $args['dependencies'] : false;

			$container_style = '';

			if( $error ) {
				$class .= ' input-error';
				$after .= '<small class="error">' . $error . '</small>' . "\n";
			}

			if( 'hidden' === $type ) {
				$wrapper = false;
			}

			if( 'honeypot' === $type ) {
				//			$label = false;
				$wrapper_class = '';
				if( ! $error ) {
					$container_style = ' style="position:absolute;left:-5000px"';
				}
			}

			// Before and after

			if( isset($args['before']) ) {
				$before .= ( $args['before'] );
			}


			// Add wrapper if wrapper true or if dependencies specified
			if( $wrapper || $deps ) {
				$atts = '';
				if( ! empty($deps) ) {
					foreach($deps as $key => $value) {
						$atts .= "data-zd-{$key}=\"{$value}\"";
					}
				}
				$before .= "<{$wrapper_element} class=\"{$wrapper_class}\"{$atts}{$container_style}>"."\n";
			}

			if( $label ) {

				if( $is_checkbox ) {
					$after .= $label_html;
				}
				else if( 'editor' === $type ) {
					echo $label_html;
				}
				else {
					$before .= $label_html;
				}
			}

			if( isset($args['after']) ) {
				$after .= ( $args['after'] );
			}

			if( $help ) {
				$after .= "<span class=\"zd-help-text\">{$help}</span>\n";
			}

			if( $tooltip ) {
				$after .= "<span data-tooltip aria-haspopup=\"true\" class=\"zd-tooltip has-tip\" title=\"{$tooltip}\">?</span>\n";
			}

			if( $wrapper ) {
				$after .= "</{$wrapper_element}><!--input-wrapper-->\n";
			}



			// Set display value to 1 if checkbox or hidden, otherwise whatever the current value
			$value = ( $is_checkbox || $is_hidden ) ? '1' : $current_value;

			// Before input
			$html .= $before;

			/*
			 * Standard text-style inputs
			 */

			if( in_array($type, $text_based_inputs) ) {

				if( 'color' === $type ) {
					$type = 'text';
					$class .= ' zd-color-input';
				}

				$html .= "<input type=\"{$type}\" id=\"{$id}\" class=\"{$class}\" name=\"{$name}\" value=\"{$value}\" {$required}{$placeholder} />\n";
			}

			/*
			 * Checkbox
			 */

			else if( 'checkbox' === $type ) {
				//			_d($current_value);
				$checked = (!empty($current_value) && $current_value === "on") ? ' checked' : '';

				$html .= "<input type=\"checkbox\" id=\"{$id}\" class=\"{$class}\" name=\"{$name}\"{$checked} />\n";
			}

			/*
			 * Hidden
			 */

			else if( 'hidden' === $type ) {

				$html .= "<input type=\"hidden\" id=\"{$id}\" class=\"{$class}\" name=\"{$name}\" value=\"{$current_value}\" />\n";
			}


			/*
			 * Textarea
			 */

			else if( 'textarea' === $type ) {
				$html .= "<textarea id=\"{$id}\" class=\"{$class}\" name=\"{$name}\" rows=\"{$rows}\" cols=\"{$cols}\" {$placeholder}>{$value}</textarea>\n";
			}


			/*
			 * WYSIWYG
			 * wp_editor echoes response
			 */

//			else if( 'editor' === $type ) {
//				//			echo $label_html;
//				wp_editor($current_value, $id);
//			}

			/*
			 * Image input
			 * Uses zd-admin.js
			 */

			else if( 'image' === $type || 'file' === $type ) {
				$html .= $this->get_image_input($name, $current_value, $type);
			}

			/*
			 * Select (dropdown) input
			 * Requires the dropdown property
			 */
			else if( 'select' === $type ) {
				$html .= $this->get_select_input($id, $name, $value, $dropdown, $custom_options);
			}

			/*
			 * Custom dropdown input
			 * Requires the dropdown property
			 */
			else if( 'custom_dropdown' === $type ) {
				$html .= $this->get_custom_dropdown($id, $name, $value, $dropdown, $args['custom_options'], $class);
			}

			/*
			 * Multiple checkboxes biatch
			 */

			else if( 'multiple_checkbox' === $type ) {
				if( $checkboxes = $args['checkboxes'] ) {
					//				print_r($checkboxes);

					//				_d($current_value);

					foreach( $checkboxes as $checkbox_name => $checkbox_label ) {
						$current_id = 'zd-checkbox-' . $checkbox_name;

						//					_d($current_value[$checkbox_name]);

						$selected = (isset($current_value[$checkbox_name]) && "on" === $current_value[$checkbox_name]) ? ' checked' : '';
						//					_d($checkbox_id);
						$html .= '<p>'."\n";
						$html .= '<input type="checkbox" id="'.$current_id.'" name="'.$name.'['.$checkbox_name.']"'.$selected.'>'."\n";
						$html .= '<label for="'.$current_id.'">'.$checkbox_label.'</label>'."\n";
						$html .= '</p>'."\n";
					}
					//				$html .= '';
				}
				else if( WP_DEBUG ){
					$html .= __( 'ZD ERROR: Attribute "checkboxes" not specified', 'zingdesign' );
				}
			}

			/*
			 Radio (multichoice) inputs
			*/

			else if( 'radio' === $type ) {
				$html .= $this->get_radio_input($name, $value, $args['options']);
			}

			/*
			 * Honey pot field
			 */

			else if( 'honeypot' === $type ) {
				$html .= '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$current_value.'" />';
			}

			else {
				$html .= "<p>" . __("Error: Invalid input type:", 'zingdesign') . '<strong>'. $type . "</strong></p>\n";
			}

			// After input
			$html .= $after;

			// SET TRANSIENTS after HTML generated
			set_transient($transient_id, base64_encode($html), WEEK_IN_SECONDS );
			set_transient($transient_value_id, $current_value, WEEK_IN_SECONDS);
		}



		return $html;
	}

	public function get_radio_input($name, $value=null, $options=null) {
		$html = '';

		if( is_array($options) ) {
			foreach($options as $option_id => $option_label) {
				$selected = $option_id === $value ? ' checked' : '';
				$html .= "<label for=\"{$option_id}\">{$option_label}</label>";
				$html .= "<input type=\"radio\" id=\"{$option_id}\" name=\"{$name}\"{$selected}/>\n";
			}
		}
		else {
			$html .= '<p>' . __('Error: "options" property required for radio input', 'zingdesign') . "</p>\n";
		}

		return $html;
	}


	public function get_select_input($id, $name, $value=null, $dropdown=null, $custom_options=false) {
		$html = '';

		$html .= "<select id=\"{$id}\" name=\"{$name}\">\n";

		if( ! $custom_options ) {
			$html .= "<option value=\"-1\">" . __('Select an option', 'zingdesign') . "</option>\n";

			if( is_array($dropdown) && !empty($dropdown) ) {
				foreach($dropdown as $option_value => $option_label) {
					$selected = $value === $option_value ? ' selected' : '';

					$html .= "<option value=\"{$option_value}\"{$selected}>" . __($option_label, 'zingdesign') . "</option>\n";
				}
			}
			else if( WP_DEBUG ){
				$html .= "<option>" . __("Error: 'dropdown' property required for select input", 'zingdesign') . "</option>\n";
			}
		}
		else {
			$html .= $dropdown;
		}



		$html .= "</select>\n";

		return $html;
	}

	public function get_image_input($name, $value=null, $type='image') {
		$html = '';
		$bg = '';
		$html .= "<div class=\"zd-image-input-wrapper\">\n";
		$rand = mt_rand(100, 1000);

		$button_text = empty($value) ? 'Insert ' . $type : 'Change ' . $type;
		$html .= "<button class=\"zd-insert-image-button button button-primary\">".__($button_text, 'zingdesign')."</button>\n";

		$image_url = '';

//		_d($value);

		if( !empty($value) ) {
			$html .= '<button class="zd-remove-image-button button button-default">' . __('Remove ', 'zingdesign') . $type . '</button>'."\n";

			if( 'image' === $type ){
				$image_src = wp_get_attachment_image_src($value, 'thumbnail');

				if( $image_src !== false ) {
					// get image URL from stored image
					$image_url = $image_src[0];

					$bg = ' style="background: url('.$image_url.') no-repeat;width:'.$image_src[1].'px;height:'.$image_src[2].'px;"';
				}
			}
			else if( 'file' === $type ){
//				$uploads_dir = wp_upload_dir();
				$image_url = wp_get_attachment_url($value);
			}


		}


		$html .= "<input class=\"zd-image-src-output\" id=\"zd-image-src-{$rand}\" value=\"{$image_url}\"/>\n";
		$html .= "<input type=\"hidden\" id=\"zd-image-id-{$rand}\" name=\"{$name}\" value=\"{$value}\" />\n";

		if( 'image' === $type ) {

			$hide = empty( $image_url ) ? ' zd-hide' : '';

			$html .= "<p class=\"image-preview-label{$hide}\"><strong>" . __( 'Image Preview', 'zingdesign' ) . "</strong></p>\n";

			$html .= "<div class=\"zd-image-preview{$hide}\" id=\"zd-image-preview-{$rand}\"{$bg}>";
			//            echo $value;
			$html .= "</div>\n";
		}

		$html .= "</div>\n";

		return $html;
	}

	public function get_custom_dropdown($id, $name, $value=null, $dropdown=null, $custom_options=false, $class='') {
		$html = '<button data-dropdown="'.$id.'" aria-controls="'.$id.'" aria-expanded="false" class="button-primary button dropdown">'.__('Select an option', 'zingdesign').'</button>';

		$html .= "<ul id=\"{$id}\" data-dropdown-content class=\"f-dropdown {$class}\" aria-hidden=\"true\" tabindex=\"-1\">\n";

		if( ! $custom_options ) {
			$html .= "<li value=\"-1\">" . __('Select an option', 'zingdesign') . "</li>\n";

			if( is_array($dropdown) && !empty($dropdown) ) {
				foreach($dropdown as $option_value => $option_label) {
					$selected = $value === $option_value ? ' checked' : '';

					$html .= "<li><input type=\"radio\" name=\"{$name}\" value=\"{$option_value}\"{$selected}/>" . __($option_label, 'zingdesign') . "</li>\n";
				}
			}
			else if( WP_DEBUG ){
				$html .= "<li>" . __("Error: 'dropdown' property required for select input", 'zingdesign') . "</li>\n";
			}
		}
		else {
			$html .= $dropdown;
		}



		$html .= "</ul>\n";

		return $html;
	}

	public function get_label($label, $id='') {
		return "<label for=\"{$id}\">{$label}</label>\n";
	}

	private function strip_non_alphanumeric($str) {
		return preg_replace('/[^a-zA-Z0-9]/', "", $str );
	}
} 