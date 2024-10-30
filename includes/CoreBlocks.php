<?php
/**
 * Core Blocks
 *
 * @package CoreBlocks
 */

namespace BetterSharingWP;

use BetterSharingWP\AdminScreens\EmailTemplate;
use BetterSharingWP\Api\Email;
use BetterSharingWP\OptionData;

/**
 * CoreBlocks
 */
class CoreBlocks {

	/**
	 * Default block attributes for shortcode output
	 *
	 * @var array  $block_attributes block attributes.
	 */
	public $block_attributes;

	/**
	 * API key for Cloudponge CDN
	 *
	 * @var string  $api_key API Keuy.
	 */
	public $api_key;


	/***
	 * Default settings for
	 * the UI Template CPT
	 */
	public $bswp_ui_template_default_settings;

	public function __construct() {

		$this->api_key = get_site_option( '_bswp_option_core_apiKey', false );

		$this->shortcode_attributes = array(
			'id' 						=> 0,
			'referral_link' 	=> '',
			'x_message' 		=> '',
			'email_message' => '',
			'email_template_id' => 0,
			'ui_type' 			=> '',
		);
		
		$this->bswp_ui_template_default_settings = include BETTER_SHARING_PATH . 'includes/config/ui_template.php';

		add_action( 'init', array( $this, 'add_better_sharing_shortcode' ) );
	}

	/**
	 * Public Scripts and Styles
	 *
	 * @return void
	 */
	public function core_block_public_scripts() {

		if ( has_block( 'cgb/block-ea-better-sharing' ) ) :

			wp_enqueue_script(
				'better-sharing-blocks-public',
				BETTER_SHARING_URI . 'dist/blocks/public.bundle.js',
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ),
				BETTER_SHARING_VERSION,
				false
			);
			$i18n = include BETTER_SHARING_PATH . 'includes/config/i18n.php';  
			wp_localize_script( 'better-sharing-blocks-public', 'translations', $i18n );
			
			wp_enqueue_style('dashicons');

			if ( ! empty( $this->api_key ) ) {
				wp_enqueue_script(
					'cloudsponge-js',
					'https://api.cloudsponge.com/widget/' . $this->api_key . '.js',
					array( 'jquery' ),
					BETTER_SHARING_VERSION,
					false
				);
			}

			wp_localize_script( 'better-sharing-blocks-public', 'bswpApiSettings', array(
				'root' 	=> esc_url_raw( rest_url('bswp/v1/bswp_email') ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'api_root' => get_rest_url(),
				'bswp_version' => BETTER_SHARING_VERSION,
				
			) );	
		endif;
	}

	/**
	 * Register Block
	 *
	 * @return mixed
	 */
	public function register_block() {

		$this->message = "🗣 Check out this link!";

		wp_enqueue_script(
			'better-sharing-blocks',
			BETTER_SHARING_URI . 'dist/blocks/blocks.bundle.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ),
			BETTER_SHARING_VERSION,
			false
		);
		
		// get default referral link to share.
		$default_page_url = get_site_url() . $this->bswp_ui_template_default_settings['url_to_share']['default_page_endpoint'];// default 
	 
		wp_localize_script(
			'better-sharing-blocks',
			'bswp_block_data',
			array(
				'defaultPageUrl' => $default_page_url,
			),
		);
		
		register_block_type(
			'cgb/block-ea-better-sharing',
			array(
				'editor_script'   => 'better-sharing-blocks',
				'render_callback' => array( $this, 'better_sharing_output' ),
				// default attributes.
				'attributes'      => array(
					'id' => array(
						'type'    => 'string',
						'default' => '',
					)
				),
			)
		);
	}

	/**
	 * Register shortcode.
	 *
	 * @return void
	 */
	public function add_better_sharing_shortcode() {

		add_shortcode( 'better-sharing', array( $this, 'better_sharing_output' ) );
	}

	/**
	 * Create intent url for shortcode.
	 *
	 * @param string $intent_url Social sharing intent URL w/o permalink.
	 *
	 * @return string
	 */
	public function create_intent_url( $referral_link, $intent_url ) {

		return str_replace( '{{permalink}}', $referral_link, $intent_url );
	}

	/**
	 * Render output.
	 *
	 * @param array  $atts block attributes.
	 * @param string $content post content.
	 * @param string $tag shortcode tag.
	 *
	 * @return string
	 */
	public function better_sharing_output( $atts, $content = null, $tag = null ) { 
		if ( true === is_array( $atts ) ) {
			
			$block_attributes = array_change_key_case( $atts, CASE_LOWER );
		} else {

			$block_attributes = $atts;
		}
		$block_attributes 				= $this->replace_deprecated_block_attributes( $block_attributes );
		$custom_block_attributes 	= $this->filter_block_attributes( $block_attributes, 'custom' );

		wp_enqueue_script(
			'better-sharing-blocks-public',
			BETTER_SHARING_URI . 'dist/blocks/public.bundle.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ),
			BETTER_SHARING_VERSION,
			false
		);

		if ( ! empty( $this->api_key ) ) {
			wp_enqueue_script(
				'cloudsponge-js',
				'https://api.cloudsponge.com/widget/' . $this->api_key . '.js',
				array( 'jquery' ),
				BETTER_SHARING_VERSION,
				false
			);
		}

		wp_localize_script( 'better-sharing-blocks-public', 'bswpApiSettings', array(
			'root' => esc_url_raw( rest_url('bswp/v1/bswp_email') ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
			'api_root' => get_rest_url(),
			'bswp_version' => BETTER_SHARING_VERSION,
		) );

		$i18n = include BETTER_SHARING_PATH . 'includes/config/i18n.php';  
		wp_localize_script( 'better-sharing-blocks-public', 'translations', $i18n );
		
		$bswp_ui_template = $block_attributes['id'];

		$bswp_ui_template_settings = $this->get_bswp_ui_template_data( $bswp_ui_template ); 

		$referral_link_module_settings = $bswp_ui_template_settings['referral_link'];
		
		$url_to_share_settings = $bswp_ui_template_settings['url_to_share'];
		$referral_link = $this->get_bswp_block_referral_link( $block_attributes, $url_to_share_settings ); 
		// ui_type shortcode attr overrides view_style setting.
		$is_full_view  = $this->revise_bswp_block_view_type( $block_attributes, $bswp_ui_template_settings );  
		// email_template_id shortcode attr ovverrides email_template setting.
		// email_message shortcode attr overrides email_message_fallback setting.
		$email_module_settings = $this->revise_bswp_email_settings( $block_attributes, $bswp_ui_template_settings['email'] );
		// x_message shortcode attr overrides twitter_msg) setting.
		$social_share_module_settings = $this->revise_bswp_social_share_settings( $block_attributes, $bswp_ui_template_settings['social_share']);
		
		// prepare sender data.
		$user = [];		
		$loggedin_user = wp_get_current_user();
		
		if( $loggedin_user->ID ) :
			$user['email'] 		= $loggedin_user->user_email;
			$user['first_name'] = get_user_meta( $loggedin_user->ID, 'first_name', true );
			$user['last_name']  = get_user_meta( $loggedin_user->ID, 'last_name', true );
		endif;
		// email preview email subject & email body.
		$email_preview = $this->core_blocks_get_email_preview( $email_module_settings, $referral_link, $user, $custom_block_attributes );
		$emails_limit = $this->bswp_limit_emails(); 
		
		// used in bswp-form.
		$addon              = 'core';
		$ajax               = false; 
		ob_start();  
		include BETTER_SHARING_PATH . 'includes/templates/bswp-modules-container.php'; 
		return ob_get_clean();

	}
	/**
	 * Replace the deprecated block attributes
	 * and handle the admin notice (save/remove).
	 * 
	 * @param array $block_attributes
	 * @return void
	 */
	public function replace_deprecated_block_attributes( $block_attributes ){
		$key = 'adminNotices';
		$data = [];
		$option_data = new OptionData( 'core' );
		$admin_notices = json_decode($option_data->get( $key ), true);

		if ( isset( $block_attributes['referrallink'] ) ) :
			$block_attributes['referral_link'] = $block_attributes['referrallink'];  
			unset( $block_attributes['referrallink'] );
			
			if ( !isset( $admin_notices['referrallink'] ) ) :  

				$reflink_notice_message = 'Better Sharing Plugin: The <strong>referrallink</strong> attribute is no longer supported in the ';
				$reflink_notice_message .= '<strong>better-sharing</strong> shortcode. ';
				$reflink_notice_message .= 'You must replace it with <strong>referral_link</strong> (sorry!)!';

				$data['referrallink']['reason'] = 'deprecated shortcode attribute';
				$data['referrallink']['message'] = $reflink_notice_message;
				$data['referrallink']['class_names'] = ['is-dismissible'];
				$data['referrallink']['type'] = 'warning';
				$value = wp_json_encode( $data );
				$option_data->save( $key, $value );
			endif;
		else : 
			if ( isset( $admin_notices['referrallink'] ) ) : 
				unset($admin_notices['referrallink']);
				if ( !empty( $admin_notices ) ) :
					$value = wp_json_encode( $admin_notices );
					$option_data->save( $key, $value );
				else :
					$option_data->delete( $key );
				endif;
			endif;
		endif;

		return $block_attributes;
	}
	/**
	 * Set limit value for emails 
	 * per form submission.
	 * 
	 * @return int|false
	 */

	public function bswp_limit_emails(){
		
		$option_data = new OptionData( 'core' );	
	
		if ( ! is_wp_error( $option_data ) ) :
			$this->option_data = $option_data;
		endif;
		
		if( $this->option_data->get( 'emailsLimit' ) ) :

			$limit_emails = json_decode( $this->option_data->get( 'emailsLimit' ), true );
			
			// check if emails limit is set.
			if( $limit_emails['limit_emails'] ) :
				return $limit_emails['emails_num'];
			endif;
		
		endif;
		
		return false;
	}

	/**
	 * Gets BSWP UI Template CPT's
	 * settings
	 * @param integer $template_id
	 * @return array $template_data if template_id is 0 or non existing, 
	 * the method returns the default BSWP UI Template CPT Settings
	 */
	public function get_bswp_ui_template_data( $template_id ){		
		
		// user passed an id.
		if( $template_id ){

			$template_data = @unserialize(base64_decode( get_post_meta( $template_id, 'bswp_ui_template_settings', true ) ) ); 	
			// saved without base64 encoding will throw notice.
			if( $template_data === false ){

				$template_data = unserialize( get_post_meta( $template_id, 'bswp_ui_template_settings', true ) );
			}
			
			// template data doesn't exists, load default settings.
			if( !$template_data ){
				// no valid template id provided, default settings are loaded.
				$template_data = $this->bswp_ui_template_default_settings;
			}

		} else {
			// no template id provided, default settings are loaded.
			$template_data = $this->bswp_ui_template_default_settings;
		}

		return $template_data;
	}
	

	/**
	 * Gets the referral link for the BSWP Block
	 * 
	 * @param array $block_attributes
	 * 
	 * @return string $referral_link
	 */

	public function get_bswp_block_referral_link( $block_attributes, $url_to_share_settings ){
		// get referral link attribute value
		// from the block shortcode if exists and not empty
		// else - get it from the referral link module settings - the key referral link 		
		$referral_link  = "";

		if ( array_key_exists( 'referral_link', $block_attributes ) && $block_attributes['referral_link'] != "" ) : 
			$referral_link = $block_attributes['referral_link'];
		else : 
			if( $url_to_share_settings['link_type'] == 'page_url' ) :
				global $wp; 
				$referral_link 	=  trailingslashit(home_url( $wp->request )); 
				$query_str 			= $_SERVER['QUERY_STRING'];
				if ( ! empty( $query_str ) ) :
					$referral_link = "$referral_link/?$query_str";
				endif; 
			elseif( $url_to_share_settings['link_type'] == 'custom_url' ) : 

				$referral_link = $url_to_share_settings['custom_link'];
			endif;
		endif;

		return $referral_link;
	}
	/**
	 * Updates the BSWP block 
	 * view style settings
	 * 
	 * @param array $block_attributes
	 * @param array $template_settings
	 * @return boolean true if inline
	 */
	public function revise_bswp_block_view_type( $block_attributes, $template_settings ){

		if ( array_key_exists( 'ui_type', $block_attributes ) ) :
			if ( 'compact' === $block_attributes['ui_type'] ) :
				return false;
			endif;
			if ( 'inline' === $block_attributes['ui_type'] ) :
				return true;
			endif;
		endif;
		if ( isset($template_settings['view_style'] ) ) :
			if ( 'compact' === $template_settings['view_style'] ) :
				return false;
			endif;
		endif;
		return true;
	}
	/**
	 * Updates the BSWP block 
	 * email settings
	 * 
	 * @param array $block_attributes
	 * @param array $email_settings
	 * @return array
	 */
	public function revise_bswp_email_settings( $block_attributes, $email_settings ){
		if ( array_key_exists( 'email_template_id', $block_attributes ) ) :
			if ( !empty($block_attributes['email_template_id']) ) :
				$email_settings['email_template'] = $block_attributes['email_template_id'];
			endif;
		endif;
		if ( array_key_exists( 'email_message', $block_attributes ) ) : 
			if ( !empty( $block_attributes['email_message'] ) ) :
				$email_settings['email_message_fallback'] = $block_attributes['email_message']; 
			endif;
		endif;
		if ( array_key_exists('email_success_message', $block_attributes ) ) :
			$email_settings['success_screen_msg'] = $block_attributes['email_success_message'];  
		endif;
		if ( array_key_exists('email_success_btn_label', $block_attributes ) ) :
			$email_settings['success_screen_cta_label'] = $block_attributes['email_success_btn_label']; 
		endif;
		return $email_settings;
	}
	/**
	 * Updates the BSWP block 
	 * social share settings
	 *
	 * @param array $block_attributes
	 * @param array $social_setting
	 * @return array
	 */
	public function revise_bswp_social_share_settings( $block_attributes, $social_share_settings ){
		if ( array_key_exists( 'x_message', $block_attributes ) ) : 
			if ( empty( !$block_attributes['x_message'] ) ) :
				$social_share_settings['twitter_msg'] = $block_attributes['x_message'];
			endif;
		endif;
		return $social_share_settings;
	}
	/**
	 * Prepare email preview content 
	 * 
	 * @param array $settings
	 * @param string $referral_link
	 * @param array $sender
	 * @param array $custom_block_attributes
	 * @return object
	 */
	public function core_blocks_get_email_preview( $settings, $referral_link, $sender, $custom_block_attributes ){  
		
	 	$email_template  = new EmailTemplate();
		$email_preview   = $email_template->bswp_get_email_preview( $settings, false, $referral_link, $sender, $custom_block_attributes );
		return $email_preview; 
	}
	/**
	 * Filter block attributes
	 * by type
	 * 
	 * @param arr $block_attributes
	 * @param str $type
	 * @return arr
	 */
	public function filter_block_attributes( $block_attributes, $type ){
		switch ($type) {
			case 'custom':
				// find the user defined block attributes.
				return array_diff_key( $block_attributes, $this->shortcode_attributes );
				break;
			
			default:
				return $block_attributes;
		}
	}
}
