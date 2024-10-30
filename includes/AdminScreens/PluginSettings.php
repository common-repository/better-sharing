<?php

namespace BetterSharingWP\AdminScreens;

use BetterSharingWP\OptionData;

class PluginSettings {

	/**
	 * Option Data
	 *
	 * @var OptionData
	 */
	private $option_data;

	/**
	 * An Error Message to display
	 * as admin notice
	 * if any occur when modifying
	 * BSWP general plugin settings
	 *
	 * @var string
	 */
	private $errorMsg;

	public function init() {
		add_submenu_page(
			'better-sharing-wp',
			__('Settings', 'better-sharing-wp'), // page title.
			__('Settings', 'better-sharing-wp'), // menu title.
			'manage_options',
			'bswp-plugin-settings',
			array( $this, 'template' ), 
		);

		add_action( 'admin_init', array( $this, 'load_init' ) );
	}

	/**
	 * Template for
	 * the BSWP Admin page
	 *
	 * @return void
	 */
	public function template() {
		
		echo '<div class="wrap bswp">';
		echo '<h1>' . __( 'Settings', 'better-sharing-wp' ) . '</h1>';

		echo '<form action="' . esc_url( admin_url( 'admin.php?page=bswp-plugin-settings' ) ) . '" method="post" class="bswp__plugin-settings-form">';
		echo wp_nonce_field( 'bswp_plugin-settings_nonce', '_bswp_plugin-settings_nonce' );
		include_once BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'plugin-settings/email-sending.php';
		include_once BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'plugin-settings/cloudsponge-settings.php';
		echo '</form>';

		echo '</div>';
	}


	/**
	 * Inits the the BSWP Admin
	 * page load
	 *
	 * @return void
	 */
	public function load_init() {
		// Load OptionData.
		$option_data = new OptionData( 'core' );

		if ( ! is_wp_error( $option_data ) ) {

			$this->option_data = $option_data;
		}

		if (
			isset( $_POST['_bswp_plugin-settings_nonce'] ) &&
			wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['_bswp_plugin-settings_nonce'] ) ),
				'bswp_plugin-settings_nonce'
			) ) {
			// Save data.
			if ( ! isset( $_POST['__bswp_settings__save'] ) ) {

				return;
			}
			if ( isset( $_POST['__bswp_api_key'] ) ) {

				$api_keySaved = $this->save_api_key( sanitize_text_field( wp_unslash( $_POST['__bswp_api_key'] ) ) );
				$this->process_option_saved_result( $api_keySaved );
			}

			// emails per form submission limit.
			$emails_limitSaved = $this->save_emails_limit( $_POST );
			$this->process_option_saved_result( $emails_limitSaved );

			// emails replyto.  
			if ( isset( $_POST['__bswp_emails_replyto'] ) ) {
				
				$emails_replytoSaved = $this->save_emails_replyto( $_POST['__bswp_emails_replyto'] ); 
				$this->process_option_saved_result( $emails_replytoSaved );
			}
			// website name
			if ( isset( $_POST['__bswp_website_name'] ) ) {

				$website_nameSaved = $this->save_website_name( sanitize_text_field( wp_unslash( $_POST['__bswp_website_name'] ) ) );
				$this->process_option_saved_result( $website_nameSaved );
			}
			// spam detection.
			if ( isset( $_POST['__bswp_enable_spam_detection'] )
				|| isset( $_POST['__bswp_spam_regex'] )
				|| isset( $_POST['__bswp_hide_spam_err_msg'] ) ) {
				$enable_spam_detectionSaved = $this->save_enable_spam_detection( $_POST );
				$this->process_option_saved_result( $enable_spam_detectionSaved );
			}
		}
	}

	/**
	 * Save the api key
	 * BSWP plugin general setting
	 *
	 * @param string $keyValue
	 *
	 * @return mixed WP Error|bool
	 */
	private function save_api_key( $keyValue ) {
		$key = 'apiKey';

		if ( '' === $keyValue ) {

			return $this->option_data->delete( $key );
		} else {

			return $this->option_data->save( $key, $keyValue );
		}
	}

	/**
	 * Save the email limit
	 * /per form submission/
	 * BSWP plugin general setting
	 *
	 * @param array $data the post data from the general settings form.
	 *
	 * @return mixed WP Error|bool
	 */
	private function save_emails_limit( $data ) {

		$key        = 'emailsLimit';
		$limit_data = array();
		$error      = array();

		if ( isset( $data['__bswp_limit_emails'] ) ) :
			// emails sent have limit.
			$limit_data['limit_emails'] = 1;
			// validate the emails num.
			$emails_num = $this->validate_email_limit_num( sanitize_text_field( $data['__bswp_emails_num'] ) );

			if ( $emails_num ) :

				$limit_data['emails_num'] = $emails_num;
			else :

				$error = new \WP_Error();
				$error->add( 'invalid', __( 'Emails limit must be an integer and not 0!', 'better-sharing-wp' ) );

				return $error;
			endif;
		else :

			// emails sent are not limited.
			$limit_data['limit_emails'] = 0;
			$limit_data['emails_num']   = '';
		endif;

		$value = wp_json_encode( $limit_data );

		return $this->option_data->save( $key, $value );
	}

	/**
	 * Save replyto 
	 * selected option
	 * BSWP plugin general setting
	 *
	 * @param array $data the post data from the general settings form.
	 *
	 * @return mixed WP Error|bool
	 */
	private function save_emails_replyto( $data ) {

		$key        = 'emailsReplyto';
		$error      = array();
		
		// validate the replyto is option
		$replyto = $this->validate_emails_replyto( $data );   
		if ( $replyto['is_valid'] ) :
			return $this->option_data->save( $key, $replyto['value'] );
		else :

			$error = new \WP_Error();
			$error->add( 'invalid', __( 'Select one of the \'Email Reply To\' options. If selected, \'Reply To\' address cannot be empty!', 'better-sharing-wp' ) );

			return $error;
		endif;  
	}

	/**
	 * Save the Website Name
	 * BSWP plugin general setting
	 *
	 * @param string $websiteNameValue
	 *
	 * @return mixed WP Error|bool
	 */
	private function save_website_name( $websiteNameValue ) {
		$key = 'websiteName';

		if ( '' === $websiteNameValue ) {

			return $this->option_data->delete( $key );
		} else {

			return $this->option_data->save( $key, $websiteNameValue );
		}
	}

	/**
	 * Save spam detection settings
	 * __bswp_enable_spam_detection, __bswp_spam_regex, __bswp_hide_spam_err_msg
	 * BSWP plugin general setting
	 *
	 * @param array $data the post data from the general settings form.
	 *
	 * @return mixed WP Error|bool
	 */
	private function save_enable_spam_detection( $data ) {
		
		$key                       = 'spamDetection';
		$spam_data                 = array(
			'enable_spam_detection' => 0,
			'spam_regex'            => '',
			'hide_err_msg'          => 0,
		);
		if ( isset( $data['__bswp_enable_spam_detection'] ) ) :
			// detection is enabled.
			$spam_data['enable_spam_detection'] = 1;
		endif;
		if ( isset( $data['__bswp_spam_regex'] ) ) :
			$regex = trim( sanitize_text_field( wp_unslash( $data['__bswp_spam_regex'] ) ) );
			$spam_data['spam_regex'] = $regex;
		endif;
		if ( isset( $data['__bswp_hide_spam_err_msg'] ) ) :
			$spam_data['hide_err_msg'] = $data['__bswp_hide_spam_err_msg'];
		endif;

		$value = wp_json_encode( $spam_data );

		return $this->option_data->save( $key, $value );
	}


	/**
	 * Validate the email sent limit
	 * to be int or convertable to int
	 * and not 0 or empty
	 *
	 * @param mixed $data
	 *
	 * @return mixed false - if validation fails|int(the email limit) - if validation passes
	 */
	private function validate_email_limit_num( $data ) {
		// string convertable to num and not empty.
		if ( is_numeric( $data ) ) :
			// convertable to int.
			$value = intval( $data );
			// not 0, or empty after casting.
			if ( ! empty( $value ) && 0 !== $value ) :

				return $value;
			endif;
		endif;

		return false;
	}
	/**
	 * Validate at least one option with 
	 * non empty value for how to handle 
	 * email replyto is selected
	 *
	 * @param mixed $data
	 * @return bool 
	 */
	private function validate_emails_replyto( $data ){
		if ( isset( $data['bswp'] ) ) :
			if ( '0' === $data['bswp'] || '1' === $data['bswp'] ) :
				return ['is_valid' => true, 'value' => wp_json_encode( $data ) ];
			elseif ( '2' === $data['bswp'] ) :
				if ( isset( $data['custom_address'] ) && !empty( trim( $data['custom_address'] ) ) ) :
					$data['custom_address'] = sanitize_text_field( $data['custom_address'] );
					return ['is_valid' => true, 'value' => wp_json_encode( $data ) ];
				endif;
			endif;
		endif;
		return ['is_valid' => false];
	}
	/**
	 * Displays admin notice
	 * on error 
	 *
	 * @param mixed $result
	 * @return void
	 */
	private function process_option_saved_result( $result ){
		if ( is_wp_error( $result ) ) {
			$this->errorMsg = $result->get_error_message();
			add_action(
				'admin_notices',
				function () {
					$class   = 'notice notice-error is-dismissible';
					$message = $this->errorMsg;
					printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
				}
			);
		}
	}
}
