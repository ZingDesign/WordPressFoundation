<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 21/10/14
 * Time: 11:37 AM
 */

class FormHelper {


	public function zd_setting_input( $args ) {
		$html = $before = $after = $class = '';

		$type = $args['type'];
		$id = $args['id'];

		$label = $args['label'];

		$dropdown = isset($args['dropdown']) ? $args['dropdown'] : array();

		$default = isset($args['default']) ? $args['default'] : '';

		$is_checkbox = 'checkbox' === $type;
		$is_hidden = 'hidden' === $type;

		$placeholder = $args['placeholder'] ? ' placeholder="'.$args['placeholder'].'"' : '';

		$required = $args['required'] ? ' required' : '';

		$text_based_inputs = array('text', 'email', 'number', 'checkbox', 'hidden', 'url', 'color');

		if( ! $type ) {
			$type = 'text';
		}

		if( ! $id ) {
			return "<p class=\"zd-error\">ZD Error: ID required when initialising new settings</p>\n";
		}

		if( $args['wrapper'] ) {
			$before .= '<div class="input-group">'."\n";
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

		if( $args['wrapper'] ) {
			$after .= "</div><!--input-wrapper-->\n";
		}



		// Set current value to stored value if a value has been set
		// Or set to default if not
		$current_value = get_option( $id ) ? get_option( $id ) : $default;

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
				$class = 'zd-color-input';
			}

			$html .= "<input type=\"{$type}\" id=\"{$id}\" class=\"{$class}\" name=\"{$id}\" value=\"{$value}\" {$required}{$checked}{$placeholder} />\n";
		}

		/*
		 * Textarea
		 */

		else if( 'textarea' === $type ) {
			$html .= "<textarea id=\"{$id}\" name=\"{$id}\" rows=\"{$args['rows']}\" cols=\"{$args['cols']}\" {$placeholder}>{$value}</textarea>\n";
		}

		/*
		 * Image input
		 * Uses zd-admin.js
		 */

		else if( 'image' === $type ) {
			$html .= $this->get_image_input($id, $value);
		}

		/*
		 * Select (dropdown) input
		 * Requires the dropdown property
		 */
		else if( 'select' === $type ) {
			$html .= $this->get_select_input($id, $value, $dropdown);
		}

		/*
		 Radio (multichoice) inputs
		*/

		else if( 'radio' === $type ) {
			$html .= $this->get_radio_input($id, $value, $args['options']);
		}

		else {
			$html .= "<p>" . __("Error: Invalid input type:", ZD_TEXT_DOMAIN) . "</p>\n";
		}

		// After input
		$html .= $after;

		return $html;
	}

	public function get_radio_input($id, $value=null, $options=null) {
		$html = '';

		if( is_array($options) ) {
			foreach($options as $option_id => $option_label) {
				$selected = $option_id === $value ? ' checked' : '';
				$html .= "<label for=\"{$option_id}\">{$option_label}</label>";
				$html .= "<input type=\"radio\" id=\"{$option_id}\" name=\"{$id}\"{$selected}/>\n";
			}
		}
		else {
			$html .= '<p>' . __('Error: "options" property required for radio input', ZD_TEXT_DOMAIN) . "</p>\n";
		}

		return $html;
	}


	public function get_select_input($id, $value=null, $dropdown=null) {
		$html = '';

		$html .= "<select id=\"{$id}\" name=\"{$id}\">\n";

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

	public function get_image_input($id, $value=null) {
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
		$html .= "<input type=\"hidden\" id=\"zd-image-id-{$rand}\" name=\"{$id}\" value=\"{$value}\" />\n";

		$hide = empty($image_url) ? ' zd-hide' : '';

		$html .= "<p class=\"image-preview-label{$hide}\"><strong>" . __('Image Preview', ZD_TEXT_DOMAIN) . "</strong></p>\n";

		$html .= "<div class=\"zd-image-preview\" id=\"zd-image-preview-{$rand}\"{$bg}>";
		//            echo $value;
		$html .= "</div>\n";

		$html .= "</div>\n";

		return $html;
	}
} 