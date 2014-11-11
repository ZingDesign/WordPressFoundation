<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 11/11/14
 * Time: 5:11 PM
 */


function zd_get_white_paper_form($post_id) {

	$html = '';
	$general_message = false;
	$has_error = true;

	$nonce_name = 'white-paper_' . $post_id;
	$nonce = wp_create_nonce( $nonce_name );

	if( $white_paper_meta = zd_metabox::zd_get_custom_meta( $post_id, 'white_paper' ) ) {
		$file_url = wp_get_attachment_url( $white_paper_meta['white_paper_pdf'] );

		// DB Option to store added users
		$added_users_option_name = '_mailchimp_added_users';
		$added_users = get_option( $added_users_option_name ) ? get_option( $added_users_option_name ) : array();

		$fh = new FormHelper();
		$inputs = array(
			array(
				'label'    => __('First name', 'zingdesign'),
				'name'     => 'first_name',
				'required' => true
			),
			array(
				'label'    => __('Last name', 'zingdesign'),
				'name'     => 'last_name',
				'required' => true
			),
			array(
				'label'    => __('Email', 'zingdesign'),
				'name'     => 'email',
				'type'     => 'email',
				'required' => true
			),
			array(
				'label'    => __('Phone', 'zingdesign'),
				'name'     => 'phone',
				'type'     => 'tel',
				'required' => true
			),
			array(
				'label'    => __('Company name', 'zingdesign'),
				'name'     => 'company_name',
				'required' => true
			),
			array(
				'label'    => __('Company website', 'zingdesign'),
				'name'     => 'company_website',
				'type'     => 'url',
				'required' => true
			),
			array(
				'label' => __('Please do not fill in this field', 'zingdesign'),
				'id'    => 'hp-sauce',
				'name'  => 'hp_sauce',
				'type'  => 'honeypot'
			)
			//			array(
			//				'label'    => 'Company size',
			//				'name'     => 'company_size',
			//				'type'     => 'select',
			//				'dropdown' => array(
			//					'201-or-more' => '201 or more people',
			//					'51-200'      => '51 to 200 people',
			//					'26-50'       => '26 to 50 people',
			//					'11-25'       => '11 to 25 people',
			//					'6-10'        => '6 to 10 people',
			//					'1-5'         => '1 to 5 people',
			//				),
			//				'required' => true
			//			),
			//			array(
			//				'label'    => 'Business type',
			//				'name'     => 'business_type',
			//				'type'     => 'select',
			//				'dropdown' => array(
			//					'saas'                 => 'SaaS (Software as a Service)',
			//					'ecommerce'            => 'E-commerce',
			//					'ad_supported'         => 'Ad-supported',
			//					'facebook_application' => 'Facebook application',
			//					'agency_consultancy'   => 'Agency / consultancy'
			//				),
			//				'required' => true
			//
			//
			//			),

		);

		if ( isset( $_POST['submit_white_paper'] ) ) {

			if( false === wp_verify_nonce($_POST['white_paper_nonce'], $nonce_name ) )  {
				echo '<p class="error">' . __('Hey! What do you think you\'re doing?!','zingdesign') . '</p>' . "\n";
				return false;
			}

			$clean_data = array_map( 'esc_html', $_POST );

			$encrypted_email = sha1($clean_data['email'] . NONCE_SALT);

			//			$sent_to_user_transient_name = $encrypted_email;

			// Store sent users for an hour to prevent multiple sendings
			$sent_to_user_posts = get_transient($encrypted_email) ? get_transient($encrypted_email) : array();

			$validation_errors = zd_validate_white_paper_form($clean_data, $inputs);

			// Valid
			if( empty( $validation_errors ) ) {

				// If the user has not already been sent the email
				if( ! in_array($post_id, $sent_to_user_posts ) ) {

					$full_name = $clean_data['first_name'] . ' ' . $clean_data['last_name'];

					$subject = __('Raygun white paper: ', 'zingdesign') . get_the_title($post_id);

					$message_body = '';

					if( get_option('white-paper-email-template') ) {
						$message_body .= get_option('white-paper-email-template');
					}
					else {
						$message_body .= '<h1 style="font-family:Helvetica,Arial,sans-serif;">' . __('Thanks for your interest in Raygun', 'zingdesign') . '</h1>';

						$message_body .= '<p style="font-family:Helvetica,Arial,sans-serif;">' . __('Click the link below to download our white paper:', 'zingdesign' ) . '</p>';
					}

					$message_body .= '<p><a style="font-family:Helvetica,Arial,sans-serif;background-color:#9aca40;color:#fff;padding:10px 40px;display:inline-block;text-decoration:none;border-radius:3px;" href="'.$file_url.'">' . __('Download the PDF!') . '</a></p>';

					$message_sender = zd_send_email_smtp( array(
						'to'        => $clean_data['email'],
						'to_name'   => $full_name,
						'from'      => get_option('white-paper-from-email'),
						'from_name' => 'Raygun',
						'subject'   => $subject,
						'body'      => $message_body
					) );

					if( true === $message_sender ) {
						$general_message .= __( 'Message sent, check your email inbox for a link to the white paper', 'zingdesign' );

						$has_error = false;

						//						$sent_to_users[] = $encrypted_email;

						$sent_to_user_posts[] = $post_id;
						set_transient($encrypted_email, $sent_to_user_posts, HOUR_IN_SECONDS);

						// If message sent, add user to Mailchimp list:

						// IF Mailchimp API key and list ID are set in admin
						// AND not dev mode
						// AND not already in list
						// Add the user details to list
						if( get_option('mail-chimp-api-key')
						    && get_option('mail-chimp-list-id')
						    && ! zd_check_user_in_list( $clean_data['email'], $added_users ) ) {

							$mailchimp_sender = zd_send_data_to_mailchimp($clean_data);

							if( isset($mailchimp_sender['status']) && 'error' === $mailchimp_sender['status'] ) {
								// Fail quietly?
								// TODO: Error tracking... ironically...
								//								_d($mailchimp_sender);
							}
							else {
								// if successful add user to the added users array
								// Encrypt and salt that shit
								$added_users[] = $encrypted_email;
								// Store in DB
								update_option($added_users_option_name, $added_users);
							}
						}
					}
					else {
						$general_message .= $message_sender;
					}
				}
				else {
					$general_message .= __('You have already been sent this white paper. Please check your Junk/Spam folder if it still hasn\'t arrived, try a different email address, or try again in an hour.', 'zingdesign');
				}

			}
		}
		$html .= '<h4 class="form-title">'."\n";
		if( $form_title = get_option('white-paper-form-title') ) {
			$html .= esc_attr( $form_title );
		}
		else {
			$html .= __('Fill out the form below to download this content', 'zingdesign');
		}
		$html .= '</h4>'."\n";

		$html .= '<form id="white-paper-form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">' . "\n";

		// First name

		foreach($inputs as $input) {

			if( isset($validation_errors[$input['name']] ) ) {
				$input['error'] = $validation_errors[$input['name']];
			}

			$html .= $fh->zd_setting_input( $input );
		}

		//Nonce field goes here
		$html .= '<input type="hidden" value="' . $nonce . '" name="white_paper_nonce" />' . "\n";

		$html .= '<button type="submit" class="green button" name="submit_white_paper">' . __( 'Send me my PDF!', 'zingdesign' ) . '</button>' . "\n";

		// "fine print" - message telling user what you're doing with their details:
		if( get_option('show-fine-print') ) {
			$html .= '<p><span data-tooltip aria-haspopup="true" class="has-tip"  title="';
			if( $fine_print = get_option('white-paper-fine-print') ) {
				$html .= sprintf( __('%s', 'zingdesign'), esc_html($fine_print) );
			}
			else {
				$html .= __('We will add your details to our lead nurturing mail list.', 'zingdesign');
			}
			$html .= '">';
			if( $fine_print_label = get_option('white-paper-fine-print-label') ) {
				$html .= sprintf( __('%s', 'zingdesign'), esc_html($fine_print_label) );
			}
			else {
				$html .= __('Read the fine print', 'zingdesign');
			}
			$html .= '</span></p>' . "\n";
		}


		$html .= '</form>' . "\n";

		if( $general_message ) {
			$message_class = $has_error ? 'error' : 'success';
			$html .= '<div class="general-message '.$message_class.'"><p>' . $general_message . "</p>\n</div>\n";
		}
	}
	else if(WP_DEBUG) {
		// if file hasn't been uploaded
		$html .= '<p>' . __('Upload a white paper PDF for this post to display the download form', 'zingdesign') . '</p>' . "\n";
	}

	echo $html;


}

function zd_validate_white_paper_form($form_data, $_inputs) {
	$errors = array();

	$error_required = __('This field is required', 'zingdesign');
	$error_invalid_email = __('Please enter a valid email address', 'zingdesign');
	$error_invalid_url = __('Please enter a valid URL', 'zingdesign');
	$honeypot_not_empty = __('Please do not fill in this field', 'zingdesign');

	foreach($_inputs as $input) {
		$name = $input['name'];
		$type = isset($input['type']) ? $input['type'] : 'text';
		$required = isset($input['required']) ? $input['required'] : false;

		if( 'honeypot' === $type && isset($form_data[$name]) && (strlen($form_data[$name]) > 0) ) {
			$errors[$name] = $honeypot_not_empty;
		}

		if( $required ) {
			if( ! isset( $form_data[$name] ) ) {
				$errors[$name] = $error_required;
			}

			else if( 'select' === $type && $form_data[$name] === "-1" ) {
				$errors[$name] = $error_required;
			}
		}

		if( 'email' === $type && ! is_email($form_data[$name]) ) {
			$errors[$name] = $error_invalid_email;
		}

		if( 'url' === $type && (esc_url($form_data[$name]) === '') ) {
			$errors[$name] = $error_invalid_url;
		}
	}

	return $errors;
}

/*
 * Function: zd_send_email_smtp
 *
 * Args: $details
 * Format :
 * [to]         => (string)
 * [to_name]    => (string)
 * [from]       => (string)
 * [from_name]  => (string)
 * [subject]    => (string)
 * [cc]         => (string)
 * [cc_name]    => (string)
 * [body]       => (string)
 *
 */

function zd_send_email_smtp( $message=array() ) {

	$from = $from_name = $subject = $body = $to = $to_name = '';

	if( zd_is_dev() ) {
		return true;
	}

	if(empty($message)) {
		return false;
	}

	if( ! isset($message['from_name']) ) {
		$message['from_name'] = get_bloginfo( 'name' );
	}

	if( ! isset($message['from']) ) {
		$message['from'] = get_option('admin_email');
	}

	if( ! isset($message['to_name']) ) {
		$message['to_name'] = __('Recipient', 'zingdesign');
	}

	if( ! isset($message['subject']) ) {
		$message['subject'] = sprintf( __('Message from %s', 'zingdesign'), get_bloginfo( 'name' ) );
	}

	if( ! isset($message['body']) && WP_DEBUG ) {
		return __('Message body required', 'zingdesign');
	}

	if( ! isset($message['to']) && WP_DEBUG ) {
		return __('Message "to" recipient required', 'zingdesign');
	}

	require_once( get_template_directory() .'/libs/PHPMailer/class.phpmailer.php' );
	require_once( get_template_directory() .'/libs/PHPMailer/class.smtp.php' );

	extract($message);

	try {
		// smtp settings
		// set this to true to throw exceptions
		// if you're running into issues
		$mail = new PHPMailer();

		$mail -> IsSMTP();
		$mail -> SMTPAuth = true;
		$mail -> SMTPSecure = "tls";
		$mail -> Host = "smtp.mandrillapp.com";
		$mail -> Username = "hello@zingdesign.co.nz";
		$mail -> Password = "l_EWEfrXJLrbGd-KL4LRcw";

		$mail -> SetFrom( $from, $from_name );
		$mail -> Subject = __( $subject, "zingdesign" );
		$mail -> MsgHTML($body);

		if( isset($cc) && isset($cc_name) ) {
			$mail -> addCC( $cc, $cc_name );
		}

		// recipient
		$mail -> AddAddress( $to, $to_name ); // this is where the email will be sent

		// success
		if ($mail -> Send()) {
			// woohoo! the mail sent! do your success things here.
			return true;
		}
		else {
			return __('Message failed to send. Please try again', 'zingdesign');
		}


		// errors :(
	} catch (phpmailerException $e) {

		if( WP_DEBUG ) {
			return $e -> errorMessage();
		}

	} catch (Exception $e) {

		if( WP_DEBUG ) {
			return $e -> getMessage();
		}

	}

	return false;
}

function zd_send_data_to_mailchimp($data) {

	$email = $first_name = $last_name = $phone = $company_name = $company_website = '';

	if( empty( $data ) ) {
		return false;
	}

	extract($data);

	$mailchimp_api_key = get_option('mail-chimp-api-key');
	$mailchimp_list_id = get_option('mail-chimp-list-id');

	require_once( get_template_directory() . '/libs/mailchimp-api/src/Drewm/MailChimp.php' );

	try {
		$MailChimp = new \Drewm\MailChimp($mailchimp_api_key);

		return $MailChimp->call('lists/subscribe', array(
			'id'                => $mailchimp_list_id,
			'email'             => array('email'=>$email),
			'merge_vars'        => array(
				'FNAME'     => $first_name,
				'LNAME'     => $last_name,
				'PHONE'     => $phone,
				'COMPANY'   => $company_name,
				'WEBSITE'   => $company_website,
			),
			'double_optin'      => false,
			'update_existing'   => true,
			'replace_interests' => false,
			'send_welcome'      => false,
		));
	}
	catch( Exception $e ) {
		if( WP_DEBUG ) {
			return $e -> getMessage();
		}
	}

	return false;


}

function zd_is_dev() {
	return WP_DEBUG && ( 'boilerplate' === $_SERVER['SERVER_NAME'] );
}

/*
 * zd_check_user_in_list()
 * Check the if user's email is in the option
 */

function zd_check_user_in_list($email, $_list=array()) {
	return in_array( sha1($email . NONCE_SALT), $_list );
}

add_filter('wp_handle_upload_prefilter', 'zd_manipulate_pdf_filename');

function zd_manipulate_pdf_filename($file) {

	$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

	if( 'pdf' === $ext ) {

		$file['name'] = sanitize_file_name( sha1($file['name']) . '.' . $ext );
	}

	return $file;
}