<?php

namespace BetterSharingWP\API;

use BetterSharingWP\AdminScreens\EmailTemplate;
use BetterSharingWP\AdminScreens\UITemplate;
use BetterSharingWP\OptionData;

/**
 * Email API
 *
 * @package Email
 **/
class Email {

	/**
	 * Plugin settings
	 * 
	 * @var OptionData
	 */
	private $option_data;

	/**
	 * Email Sender Customization
	 * 
	 * @var SenderName
	 */
	private $senderName;

	
	/**
	 * Rest Init
	 *
	 * @return void
	 */
	public function rest_init() {

		$option_data = new OptionData( 'core' );
	
		if ( ! is_wp_error( $option_data ) ) {

			$this->option_data = $option_data;
		}

		register_rest_route(
			'bswp/v1',
			'/bswp_email',
			array(
				'methods'  => array( 'POST', 'GET' ),
				'callback' => array( $this, 'bswp_email_before_send' ),
				'permission_callback' => '__return_true'
			)			
		);
		register_rest_route(
			'bswp/v1',
			'/bswp_test_email',
			array(
				'methods'  => array( 'POST', 'GET' ),
				'callback' => array( $this, 'bswp_test_email_send' ),
				'permission_callback' => '__return_true'
			)			
		);

		$ui_template = new UITemplate();
		register_rest_route(
			'bswp/v1',
			'/bswp_email_template',
			array(
				'methods'  => array( 'GET' ),
				'callback' => array( $ui_template, 'bswp_get_template' ),
				'permission_callback' => '__return_true'
			)			
		);

		add_filter('wp_mail_from_name', array( $this, 'email_from_name' ));
	}

	/**
	 * Email Sender Name Update
	 *
	 * @param Name
	 * 
	 * @return Sender Name
	 */
	public function email_from_name( $name ) {
		$from_string = "";
		
		if( $this->senderName != "" ){

			$from_string .= $this->senderName;
		}

		$website_name = $this->get_website_name_option();
		if( $website_name ){

			if( $this->senderName != "" ){
				$from_string .= " via ";
			}
			
			$from_string .= "{$website_name}";
		}
		
		if( $from_string != "" ){
			return $from_string;
		}
	}

	/**
	 * Before Email Send
	 *
	 * @param \WP_REST_Request $request ajax request.
	 * 
	 * @return \WP_REST_Response arr
	 */
	public function bswp_email_before_send( \WP_REST_Request $request ) {

		$nonce = isset($_SERVER['HTTP_X_WP_NONCE']) ? $_SERVER['HTTP_X_WP_NONCE'] : '';
		
		if (!wp_verify_nonce($nonce, 'wp_rest')) {
				// Nonce verification failed, handle error or abort request
				return new \WP_REST_Response( 'Permission denied.', 403 );
		} 
		$email_config = include BETTER_SHARING_PATH . 'includes/config/emails.php';

		//data from bswp form
		$body = json_decode( $request->get_body() );

		$emails = isset( $body->emails ) ? (array) $body->emails : array();		
		
		//validate $emails.
		$emails_valid = $this->validate_emails( $emails );
	
		if( ! $emails_valid['result'] ){

			$response = new \WP_REST_Response(
    			array(
        			'mail' => $emails_valid,
    			)
			);		

			$response->set_status( 402 );

			return $response;
		}
		
		$email_message = isset( $body->message ) ? sanitize_text_field( $body->message ) : '';		
		
		//validate custom message if present.
		if( trim( $email_message ) ) :
			
			$email_message_valid = $this->validate_email_message( $email_message, $email_config );

			if ( $email_message_valid['error']['error'] ) :

				// spam detected. 0.
				if ( $email_message_valid['error']['error_code'] === $email_config['errors'][0]['code'] ) :
					
					$response = new \WP_REST_Response(
						array(
								'mail' => $email_message_valid['result'],
						)
					);	
					$response->set_status( 402 );
				
					return $response;
				endif;
				// spam detected. 1.
				if ( $email_message_valid['error']['error_code'] === $email_config['errors'][1]['code'] ) :
					
					$response = new \WP_REST_Response(
						array(
								'mail' => $email_message_valid['result'],
						)
					);	
					$response->set_status( 200 );
				
					return $response;
				endif;
				
				$response = new \WP_REST_Response(
						array(
								'mail' => $email_message_valid['result'],
						)
				);
				$response->set_status( 402 );
				
				return $response;
			endif;
		endif;	

		$response = $this->send_emails( $body );		
		
		return new \WP_REST_Response(
    		array(
        		'mail' => $response,
    		)
		);
	}
	/**
	 * Send Test email
	 *
	 * @param \WP_REST_Request $request ajax request.
	 * 
	 * @return \WP_REST_Response
	 */
	public function bswp_test_email_send( \WP_REST_Request $request ) {
		$success_emails = 0;
		$nonce = isset($_SERVER['HTTP_X_WP_NONCE']) ? $_SERVER['HTTP_X_WP_NONCE'] : '';
		
		if (!wp_verify_nonce($nonce, 'wp_rest')) {
				// Nonce verification failed, handle error or abort request
				return new \WP_REST_Response( 'Permission denied.', 403 );
		} 
		
		$body = json_decode( $request->get_body() );

		$emailTo = isset( $body->email ) ? $body->email : null;

		//validate $email
		if( !$emailTo ) {
			$response['result'] 	= false;
			$response['message'] 	= __('No Email Found!', 'better-sharing-wp' );    		
			return $response;
		}
		//email headers
		//allow html formatted content
		$headers = ['Content-Type: text/html; charset=UTF-8']; 
		
		//send the email	
    $mail = wp_mail( "Jane Doe <$emailTo>", 'Test email', $body->mailBody, $headers );
		
		//test email response will follow the real email sending response
		$mail_response = [];	
		array_push( $mail_response, $mail );

		if ($mail) {
			$success_emails +=1;
		}

		$response = $this->evaluate_mail_response( $mail_response, $success_emails );

		return $response;
	}

	/**
	 * Sends the emails
	 *
	 * @param array $body
	 * 
	 * @return void
	 */	
	public function send_emails( $body ){
		// number of mails sent successfully
		$success_emails = 0;
		//all data needed for to send emails
		$data = [];
		$data = $this->generate_email_data( $body );
		
		$sender = $data['emails_sender'];

		//email headers
		//allow html formatted content
		$headers = ['Content-Type: text/html; charset=UTF-8'];

		//from
		if ( $sender->first_name != "" || $sender->last_name != "" ){
			
			$this->senderName = "{$sender->first_name} {$sender->last_name}";		
		}
		
		// Reply-to. 
		$replyto_status = $this->check_replyto_status( $data['replyto'] ); // returns array with Is replyto enabled in settings & the replyto header string
		if ( $replyto_status['enabled'] ) : 
			$headers[] = $replyto_status['header_string'];
		endif;

		//separate email sending
		$mail_response = [];	
		
		foreach ( $data['emails'] as $email_address => $content ) {
			
			$message = $content['email_message'];
			$subject = $content['email_subject'];

			if( ! empty( $content['recipient_full_name'] ) ) : 

				$to = $content['recipient_full_name'] . " <$email_address>";
			else : 

				$to = $email_address;
			endif;			
					
    		$mail = wp_mail( $to, $subject, $message, $headers );
			
    		array_push( $mail_response, $mail );
				if ($mail) {
					$success_emails +=1;
				}
		}	
		//check response to differ the UI messages
		$response = $this->evaluate_mail_response( $mail_response, $success_emails ); 
		return $response;
	}

	/**
	 * Checks if emails meet 
	 * the BSWP setting requirements
	 *
	 * @param array $emails
	 * 
	 * @return array
	 */
	public function validate_emails( $emails ){
		
		$response = [];
		$response['message'] 	= __('Success!', 'better-sharing-wp' );
		$response['result'] 	= true;	
		// no emails provided.
		if( empty( $emails ) ) {
			$response['result'] 	= false;
			$response['message'] 	= __('No Email(s) Found!', 'better-sharing-wp' );    		
			return $response;
		}
		// no valid email provided.
		$valid_email_addresses = $this->check_email_addresses( $emails );
		if( !$valid_email_addresses ) {
			$response['result'] 	= false;
			$response['message'] 	= __('Enter only valid email(s)!', 'better-sharing-wp' );    		
			return $response;
		}

		$response = $this->validate_email_limit( $emails, $response );
		
		return $response;
	}
	/**
	 * Check that each of
	 * the provided emails 
	 * represents a valid 
	 * email address
	 *
	 * @param array $emails
	 * @return boolean
	 */
	public function check_email_addresses( $emails ){
		$emails_count = count( $emails );
		$valid = true;
    $email_validation_regex = "/.+@.+\..+/"; 

		foreach( $emails as $email ) :
			
			if ( ! preg_match( $email_validation_regex, $email->recipient_email ) ) :

				$valid = false;
				break;
			endif;
		endforeach;

		return $valid;
	}
	/**
	 * Validates the email custom message
	 * 
	 * @param string $email_message
	 * 
	 * @return array
	 */

	public function validate_email_message( $email_message, $email_config ){

		$response = [];
		// default response values.
		$response['result']['message'] 	= __('Success!', 'better-sharing-wp' );
		$response['result']['result'] 	= true;
		$response['error']['error_code'] 	= null;
		$response['error']['error'] 		= false;	

		// spam detection.
		$spam_check_result = $this->detect_spam( $email_message, $email_config );
		if ( $spam_check_result ) : 
			return $spam_check_result;
		endif;

		return $response;
	}
	/**
	 * Scans the email message
	 * for spam
	 *
	 * @param string $email_message
	 * @return bool|array
	 */
	public function detect_spam( $email_message, $email_config ){

		// default spam detection state.
		$spam_detection = true;
		$spam_regex     = "/{$email_config['default_spam_regex']}/";
		$hide_err_msg 	= true;
		// get spam detection option.
		$spam_detection_options = $this->option_data->get( 'spamDetection' );
		
		if ( $spam_detection_options ) :
			$spam_detection_options_decoded = json_decode( $this->option_data->get( 'spamDetection' ), true );
			// change spam detection based on the available options.
			if ( $spam_detection_options_decoded['enable_spam_detection'] !== 0 ) :
				// spam detection is on.
		
				if ( ! empty( $spam_detection_options_decoded['spam_regex'] ) ) :
					// other than the default regex.
					$spam_regex     = "/{$spam_detection_options_decoded['spam_regex']}/";
				endif;

				if ( empty( $spam_detection_options_decoded['hide_err_msg'] ) ) :
					// hide the error message.
					$hide_err_msg = false;
				endif;
			else :
				// spam detection is off.
				$spam_detection = false;
			endif;
		endif;

		// check for spam only if detection is on.
		if ( $spam_detection ) :
			if ( preg_match( $spam_regex, $email_message ) ) : 
				$response['error']['error'] 		= true;

				if ( ! $hide_err_msg ) :
					// include error message in the response.
					$response['result']['result'] 			= false;
					$response['result']['message'] 			= __('Your message contains unauthorized content.', 'better-sharing-wp' );
					$response['error']['error_code'] 		= $email_config['errors'][0]['code'];
	
				else :
					// no error message in the response.
					$response['result']['result'] 			= true; 
					$response['result']['message'] 		= __( 'The email has been sent successfully!', 'better-sharing-wp' );
					$response['error']['error_code'] 		= $email_config['errors'][1]['code'];;
				endif; 
				// return result.
				return $response;
			endif;

		endif; 
		// no spam detection performed.
		return false;
	}

	/**
	 * Validates emails per form submission
	 * not to exceed the limit set 
	 * in BSWP plugin settings
	 *
	 * @param array $emails
	 * @param array $response
	 * 
	 * @return $response
	 */

	public function validate_email_limit( $emails, $response ){
		
		if( $this->option_data->get( 'emailsLimit' ) ) :			
			
			$limit_emails = json_decode( $this->option_data->get( 'emailsLimit' ), true );			
			// check if is set emails limit.
			if( $limit_emails['limit_emails'] ) :	

				$limit_emails_num = $limit_emails['emails_num'];				
				$current_emails_num = count( $emails );

				if( $current_emails_num > $limit_emails_num ) : 

					$response['result'] 	= false;
    				$response['message'] 	= __('Too many emails!', 'better-sharing-wp' );				
				endif;
			endif;		
		endif;	
				
		return $response;
	}

	/**
	 * Get Website Name 
	 * if any set as an option
	 * in BSWP plugin settings	
	 * 
	 * @return string|bool website name or false
	 */

	public function get_website_name_option(){

		$option_data = new OptionData( 'core' );
	
		if ( ! is_wp_error( $option_data ) ) {

			$this->option_data = $option_data;
		}
		
		if( $this->option_data->get( 'websiteName' ) ) :			
			
			return $this->option_data->get( 'websiteName' );
		endif;	
				
		return false;
	}

	/**
	 * Get the content of
	 * the email template CPT 
	 * 
	 * @param int templateID
	 * 
	 * @return str
	 */
	
	public function get_email_template( $templateID ){	

		$template_post = get_post( $templateID );
		$template_content = $template_post->post_content;

		return $template_content;
	}
	
	/**
	 * Get the email template CPT
	 * meta data - bswp_email_subject,
	 * an email subject
	 * 
	 * @param int $templateID
	 * 
	 * @return str
	 */
	public function get_email_template_subject( $templateID ){

		return get_post_meta( $templateID, 'bswp_email_subject', true );
	} 
	/**
	 * Prepare the email data
	 * using the chosen email template
	 * 
	 * @param object $data 
	 * 
	 * @return array
	 */
	public function generate_email_data( $data ){	 
		$templateID = isset( $data->template ) ? sanitize_text_field( $data->template ) : '';// the EMAIL template ID.
		$custom_message = isset( $data->message ) ? sanitize_text_field( $data->message ) : '';
		$referral_link = isset( $data->referral_link ) ? esc_url( $data->referral_link ) : '';
		$sender = isset( $data->sender ) ? $data->sender : ''; // bswp_sender_first_name overrides {{sender_first_name}}.
		$replyto = isset ( $data->replyto ) ? $data->replyto : ''; // CS owner if CS used, Loggedin user otherwise.
		$greeting = isset( $data->greeting ) ? $data->greeting : ''; //bswp_greeting overrides default greeting
		$email_message = isset( $data->email_message ) ? $data->email_message : ''; // check the 'bswp_email_message' DTV (it overrides the email_message fallback).
		$custom_dynamic_templ_vars = isset( $data->custom_dynamic_templ_vars ) ? $data->custom_dynamic_templ_vars : array(); 
		$parsed_template = '';
		$email_messages = [];
		$parsed_data = [];

		// 1 get content.
		if( $templateID && ( get_post_status( $templateID ) == 'publish' ) && get_post_type( $templateID ) == 'bswp_email_template' ) :

			$template = $this->get_email_template( $templateID );
			$template_subject = $this->get_email_template_subject( $templateID );
    else :
      // default template.
			$default_mail_data = new EmailTemplate();
			$template = $default_mail_data->get_default_email_body();			
			$template_subject = $default_mail_data->get_default_email_subject();
    endif;

		if ( empty( $email_message ) ) :
			// check the UI template's settings for email_message fallback.
			$ui_templateID = isset ( $data->ui_template ) ? trim( $data->ui_template ) : '';// UI template ID.
			if ( !empty( $ui_templateID ) ) :
				$templateUI = new UITemplate();
				$templateUI_settings = $templateUI->get_bswp_ui_template_settings( $ui_templateID );
				$email_message = $templateUI_settings['email']['email_message_fallback'];
			endif;
		endif;
	
		// parse the vars in the template and the subject.
		// constant values for all the emails to send - the link, the sender name/first and last/, the custom_message.
		$replace_args = [];
		$replace_args['sender'] 				= $sender;
		$replace_args['custom_message'] = $custom_message;
		$replace_args['email_message']  = $email_message;
		$replace_args['referral_link']  = $referral_link;
		$replace_args['custom_dynamic_templ_vars']  = $custom_dynamic_templ_vars;
		$parsed_template 	= $this->replace_template_variables( $template, $replace_args );
		// sender variable and user defined DTVs can be in the email subject.
		$replace_args = [];
		$replace_args['sender'] 				= $sender;
		$replace_args['custom_dynamic_templ_vars']  = $custom_dynamic_templ_vars;
		$parsed_subject 	= $this->replace_template_variables( $template_subject, $replace_args ); 

		// generate the email content for each email address.
		// parse template vars that have differrent value per email.
		$emails = $this->generate_emails_content( $parsed_template, $parsed_subject, $data->emails, $greeting );	
		
		$parsed_data['emails'] = $emails;
		$parsed_data['emails_sender'] = $sender; 
		$parsed_data['replyto'] = $replyto; 

		return $parsed_data;
	}

	/**
	 * Replace Email Template CPT
	 * template variables with
	 * the data received from the BSWP Share via email form
	 * The replaced variables will have same value
	 * for each email sent via BSWP 
	 * If $sender, $custom_message and $referral_link 
	 * are empty strings, a space is left for the template variable
	 * 
	 * @param string 	$parsable the template string containing the variables to be parsed
	 * @param array 	$replace 
	 * 
	 * @return string
	 */
	public function replace_template_variables( $parsable, $replace ){

		$default_strings = include BETTER_SHARING_PATH . 'includes/config/email_preview.php';

		if ( !empty( $replace['custom_message'] ) ):

				$custom_message = sanitize_text_field( trim( $replace['custom_message'] ) );
        $parsable = str_replace( '{{ sender_custom_message }}', $custom_message, $parsable );
    	else:

        $parsable = str_replace( '{{ sender_custom_message }}', '', $parsable );
		endif;

		if ( !empty( $replace['email_message'] ) ):
			// bswp_email_message DTV's value overrides email_message TV's fallback value.
			$email_message = sanitize_text_field( trim( $replace['email_message'] ) );
			$parsable = str_replace( '{{ email_message }}', $email_message, $parsable );
		else:

			$parsable = str_replace( '{{ email_message }}', '', $parsable );
		endif;	

		if ( !empty( $replace['referral_link'] ) ):

			$referral_link = filter_var( trim( $replace['referral_link'] ) , FILTER_VALIDATE_URL );
			$parsable = str_replace( '{{ referral_link }}', '<a href="'.$referral_link.'" target="_blank">'.$referral_link.'</a>', $parsable );
    	else:

        $parsable = str_replace( '{{ referral_link }}', '', $parsable );    		
		endif;

		if ( !empty( $replace['sender']->first_name ) ):

				$first_name = sanitize_text_field( $replace['sender']->first_name );
				$parsable = str_replace( '{{ sender_first_name }}', trim( $first_name ), $parsable );
    	else:

			$parsable = str_replace( '{{ sender_first_name }}', $default_strings['template_variables']['sender_first_name'], $parsable );    		
		endif;

		if ( !empty( $replace['custom_dynamic_templ_vars'] ) ) :

			foreach ( $replace['custom_dynamic_templ_vars'] as $id => $var) {
				$pattern 	= "/{{\s?$id\s?}}/";
				$replacement 	= sanitize_text_field( $var );
				$parsable = preg_replace( $pattern, $replacement, $parsable );
			}
		endif;

		return $parsable; 
	}
	
	/**
	 * Generate unique email content
	 * per email address
	 * by replacing recipient's first and last name
	 * 
	 * @param str $template
	 * @param array $emails_data
	 * 
	 * @return array $data
	 */
	public function generate_emails_content( $template, $subject, $emails_data, $greeting ){

		$default_strings = include BETTER_SHARING_PATH . 'includes/config/email_preview.php';

		$data = [];
		
		foreach ( $emails_data as $recipient ) :
			
			$first_name = ! empty( $recipient->recipient_first_name ) ? sanitize_text_field( $recipient->recipient_first_name ) : '';
			$last_name 	= ! empty( $recipient->recipient_last_name ) ? sanitize_text_field( $recipient->recipient_last_name ) : ''; 
			$recipient_full_name = $first_name . ' ' . $last_name;

			$current_template = $template;
			$current_subject 	= $subject; 
			// personalised content to send.
			if ( !empty( $first_name ) ) :
				$current_template = str_replace( '{{ greeting }}', $first_name, $current_template );			
				$current_subject 	= str_replace( '{{ greeting }}', $first_name, $current_subject );	
			elseif( !empty( $greeting ) ) :		
				// bswp_greeting value if any.
				$greeting = sanitize_text_field( $greeting );
				$current_template = str_replace( '{{ greeting }}', $greeting, $current_template );			
				$current_subject 	= str_replace( '{{ greeting }}', $greeting, $current_subject );	
			else :
				$current_template = str_replace( '{{ greeting }}', $default_strings['template_variables']['greeting'], $current_template );			
				$current_subject 	= str_replace( '{{ greeting }}', $default_strings['template_variables']['greeting'], $current_subject );			
			endif;		
			$recipient_full_name = $recipient->recipient_first_name . ' ' . $recipient->recipient_last_name;			
			
			$data[$recipient->recipient_email] = [
												'email_message' 		=> $current_template,
												'email_subject'			=> $current_subject,
												'recipient_full_name' 	=>  $recipient_full_name 
											];
		endforeach;
		
		return $data;		
	}

	
	/**
	 * Check email response array
	 * to differ the error messages 
	 * sent to the frontend
	 * 
	 * @param array $mail_response
	 * @param int $success_emails
	 * 
	 * @return array
	 */
	 public function evaluate_mail_response( $mail_response , $success_emails ){

		$unique_arr_responses = array_unique( $mail_response );

		$response = [];
		$response['message'] 	= __('The email has been sent successfully.', 'better-sharing-wp' );
		$response['result'] 	= true;
		$response['success_emails'] = $success_emails;

		
		if( !$success_emails ) :   

				$response['message'] 	= __('Sending mails failed! Try again later!', 'better-sharing-wp' );
				$response['result'] 	= false; 
		else :

			if( in_array( false, $unique_arr_responses ) ): 
				
				$response['message'] = __('Errors while sending mails! Check the email addresses!', 'better-sharing-wp' );
				$response['result'] = false;
			endif;

		endif;

		return $response;
	}	
	/**
	 * Decides on replyto 
	 * header of emails to send
	 *
	 * @param object $sender
	 * @return void
	 */
	private function check_replyto_status( $replyto ){
		$replyto_option 	= [];
		$replyto_enabled  = false;
		$header_string 		= '';
		// email can be empty when no loggedin user and CS is not used. No use of Replyto header.
		if ( ! empty( $replyto->email ) ) :
			$replyto_enabled 	= true; // by default; 
			$header_string   	= "Reply-To: ";
			// f-name, l-name are optional on registration and can be blank when CS is not used.
			if ( ! empty( $replyto->first_name ) ) :
				$header_string .= "{$replyto->first_name} ";
			endif; 
			if ( !empty ( $replyto->last_name ) ) :
				$header_string .= "{$replyto->last_name} ";
			endif;
			$header_string   .=  "<{$replyto->email}>";
		endif;

		if ( $this->option_data->get( 'emailsReplyto' ) ) :
			$replyto_option = json_decode( $this->option_data->get( 'emailsReplyto' ), true ); 
			if ( isset( $replyto_option['bswp'] )) :
				if ('0' === $replyto_option['bswp'] ) :
					$replyto_enabled 	= false;
					$header_string 		= "";
				elseif ( '2' === $replyto_option['bswp']) :
					if ( !empty( $replyto_option['custom_address'] ) ) :
						$customReplyto 	= trim( $replyto_option['custom_address'] ); 
						$header_string  = "Reply-To: <{$customReplyto}>"; 
						// $replyto_enabled is still true.
					endif;
				endif;
			endif;
		endif;
		
		return ['enabled' => $replyto_enabled, 'header_string' => $header_string];
	}
}
