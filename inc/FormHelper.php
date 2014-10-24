<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 21/10/14
 * Time: 11:37 AM
 */

class FormHelper {


	public function zd_setting_input( $args ) {
		$id = $args['id'];

		if( ! $id ) {
			return "<p class=\"zd-error\">ZD Error: ID required when initialising new settings</p>\n";
		}

		$html = $before = $after = $class = '';

//		$name = isset($args['name']) ? $args['name'] : $id;

		$name = '';

//		$add_multiple = isset($args['add_multiple']) ? $args['add_multiple'] : false;

		if( isset($args['name']) ) {
			$name = $args['name'];
		}
		else {
			$name = $id;
		}

//		if($add_multiple) {
//			$name = $name . '[]';
//		}

		$option_name = $name;

		if( isset($args['arg_name']) ) {
			$name = $args['arg_name'] . '[' . $name . ']';
		}
		$type = isset($args['type']) ? $args['type'] : 'text';
		$label = isset($args['label']) ? $args['label'] : $id;

		$class = isset($args['class']) ? $args['class'] : '';

		$dropdown = isset($args['dropdown']) ? $args['dropdown'] : array();

		$default = isset($args['default']) ? $args['default'] : '';

		$is_checkbox = 'checkbox' === $type;
		$is_hidden = 'hidden' === $type;

		$has_placeholder = isset($args['placeholder']) ? $args['placeholder'] : false;

		$placeholder = $has_placeholder ? ' placeholder="'.$args['placeholder'].'"' : '';

		$is_required = isset($args['required']) ? $args['required'] : false;
		$required = $is_required ? ' required' : '';

		$text_based_inputs = array('text', 'email', 'number', 'checkbox', 'hidden', 'url', 'color');

		$wrapper = isset($args['wrapper']) ? $args['wrapper'] : true;

		$help = isset($args['help']) ? $args['help'] : false;

		$rows = isset($args['rows']) ? $args['rows'] : '';
		$cols = isset($args['cols']) ? $args['cols'] : '';

		if( 'hidden' === $type ) {
			$wrapper = false;
		}


		if( $wrapper ) {
			$before .= '<div class="input-group zd-input-group">'."\n";
		}

		if( $label ) {
			$label_html = "<label for=\"{$id}\">{$label}</label>\n";

			if( $is_checkbox ) {
				$after .= $label_html;
			}
			else {
				$before .= $label_html;
			}
		}

		if( $help ) {
			$after .= "<span class=\"zd-help-text\">{$help}</span>\n";
		}

		if( $wrapper ) {
			$after .= "</div><!--input-wrapper-->\n";
		}



		// Set current value to stored value if a value has been set
		// Or set to default if not

		// Get value from post meta if the post_id and metadata name is set
//		var_dump($args['metadata_name']);
		if( isset($args['value']) ) {
			$current_value = $args['value'];
		}
		else if( isset($args['post_id']) && isset($args['metadata_name']) ) {
			$post_id = $args['post_id'];
			$data = get_post_meta( $post_id, $args['metadata_name'], true );
			$current_value = isset($data[$option_name]) ? esc_attr($data[$option_name]) : '';
		}
		else {
			$current_value = get_option( $id ) ? get_option( $id ) : $default;
		}


		// Set display value to 1 if checkbox or hidden, otherwise whatever the current value
		$value = ( $is_checkbox || $is_hidden ) ? '1' : $current_value;

		// Before input
		$html .= $before;

		/*
		 * Standard text-style inputs
		 */

		if( in_array($type, $text_based_inputs) ) {
			$checked = '';

			if( $is_checkbox ) {
				$checked = (!empty($current_value) && $current_value === "1") ? ' checked="checked"' : '';
			}

			if( 'color' === $type ) {
				$type = 'text';
				$class .= ' zd-color-input';
			}

			$html .= "<input type=\"{$type}\" id=\"{$id}\" class=\"{$class}\" name=\"{$name}\" value=\"{$value}\" {$required}{$checked}{$placeholder} />\n";
		}

		/*
		 * Textarea
		 */

		else if( 'textarea' === $type ) {
			$html .= "<textarea id=\"{$id}\" class=\"{$class}\" name=\"{$name}\" rows=\"{$rows}\" cols=\"{$cols}\" {$placeholder}>{$value}</textarea>\n";
		}

		/*
		 * Image input
		 * Uses zd-admin.js
		 */

		else if( 'image' === $type ) {
			$html .= $this->get_image_input($name, $value);
		}

		/*
		 * Select (dropdown) input
		 * Requires the dropdown property
		 */
		else if( 'select' === $type ) {
			$html .= $this->get_select_input($id, $name, $value, $dropdown);
		}

		/*
		 Radio (multichoice) inputs
		*/

		else if( 'radio' === $type ) {
			$html .= $this->get_radio_input($name, $value, $args['options']);
		}

		else {
			$html .= "<p>" . __("Error: Invalid input type:", ZD_TEXT_DOMAIN) . "</p>\n";
		}

		// After input
		$html .= $after;

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
			$html .= '<p>' . __('Error: "options" property required for radio input', ZD_TEXT_DOMAIN) . "</p>\n";
		}

		return $html;
	}


	public function get_select_input($id, $name, $value=null, $dropdown=null) {
		$html = '';

		$html .= "<select id=\"{$id}\" name=\"{$name}\">\n";

		$html .= "<option value=\"-1\">" . __('Select an option', ZD_TEXT_DOMAIN) . "</option>\n";

		if( is_array($dropdown) && !empty($dropdown) ) {
			foreach($dropdown as $option_value => $option_label) {
				$selected = $value === $option_value ? ' selected' : '';

				$html .= "<option value=\"{$option_value}\"{$selected}>" . __($option_label, ZD_TEXT_DOMAIN) . "</option>\n";
			}
		}
		else if( WP_DEBUG ){
			$html .= "<option>" . __("Error: 'dropdown' property required for select input", ZD_TEXT_DOMAIN) . "</option>\n";
		}

		$html .= "</select>\n";

		return $html;
	}

	public function get_image_input($name, $value=null) {
		$html = '';
		$bg = '';
		$html .= "<div>\n";
		$rand = mt_rand(100, 1000);

		$button_text = empty($value) ? 'Insert image' : 'Change image';
		$html .= "<button class=\"zd-insert-image-button button button-default\">".__($button_text, ZD_TEXT_DOMAIN)."</button>\n";

		$image_url = '';

		if( !empty($value) ) {
			$html .= '<button class="zd-remove-image-button button button-default">' . __('Remove image') . '</button>'."\n";
			$image_src = wp_get_attachment_image_src($value);

			// get image URL from stored image
			$image_url = empty($image_src[0]) ? '' : $image_src[0];

			$bg = ' style="background: url('.$image_url.') no-repeat;width:'.$image_src[1].'px;height:'.$image_src[2].'px;"';
		}


		$html .= "<input class=\"zd-image-src-output\" id=\"zd-image-src-{$rand}\" value=\"{$image_url}\"/>\n";
		$html .= "<input type=\"hidden\" id=\"zd-image-id-{$rand}\" name=\"{$name}\" value=\"{$value}\" />\n";

		$hide = empty($image_url) ? ' zd-hide' : '';

		$html .= "<p class=\"image-preview-label{$hide}\"><strong>" . __('Image Preview', ZD_TEXT_DOMAIN) . "</strong></p>\n";

		$html .= "<div class=\"zd-image-preview\" id=\"zd-image-preview-{$rand}\"{$bg}>";
		//            echo $value;
		$html .= "</div>\n";

		$html .= "</div>\n";

		return $html;
	}
} 