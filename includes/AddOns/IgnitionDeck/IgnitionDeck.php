<?php
/**
 * Class IgnitionDeck - IgnitionDeck Plugin Add On
 *
 * @package BetterSharingWP
 */

namespace BetterSharingWP\AddOns\IgnitionDeck;

use BetterSharingWP\AddOns\BetterSharingAddOn;
use BetterSharingWP\AdminScreens\EmailTemplate;
use BetterSharingWP\AdminScreens\UITemplate;

class IgnitionDeck extends BetterSharingAddOn {
	/**
	 * Add On Path
	 *
	 * @var string
	 */
	private $add_on_path;
	/**
	 * Predefined
	 * IgnitionDeck Hooks
	 * used for BSWP
	 *
	 * @var arr
	 */
	private $igd_hooks;
	/**
	 * Dedicated IgnitionDeck
	 * Email Template's Title
	 * Currently its content is
	 * equal to the default 
	 * Email Template content used.
	 * 
	 * @var str
	 */
	private $igd_default_email_template_title = 'IgnitionDeck Sharing Email';
	/**
	 * Dedicated IgnitionDeck
	 * Sharing Block's title
	 * Currently it is equal
	 * to the default compact
	 * BS block
	 *
	 * @var str
	 */
	private $igd_default_sharing_block_title = 'IgnitionDeck Sharing Block';
	/**
	 * EmailTemplate 
	 * innstance to access
	 * EmailTemplate CPT's
	 * methods
	 *
	 * @var obj EmailTemplate
	 */
	private $email_template;
	/**
	 * UITemplate instance
	 * to access
	 * UITemplate CPT's
	 * methods
	 *
	 * @var obj UITemplate
	 */
	private $ui_template;
	/**
	 * Admin noticess
	 *
	 * @var arr
	 */
	private $admin_notices = [];

	public function __construct(){
		$this->email_templates = new EmailTemplate(); 
		$this->ui_templates 		= new UITemplate();
	}

	/**
	 * Init
	 *
	 * @return mixed
	 */
	public function init() {
		// path.
		$this->add_on_path = BETTER_SHARING_PATH . 'includes/AddOns/IgnitionDeck';
		$this->igd_hooks    = include $this->add_on_path . '/config/ignition-deck-hooks.php';

		$init_return = parent::init_addon(
			'IgnitionDeck Crowdfunding',
			'Add Better Sharing functionality to your IgnitionDeck Crowdfunding projects.',
			false
		);

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) ); 
		if ( $this->is_active() ) {
			// init better-sharing in ID hooks. 
			$this->load_bswp();  
			
		}
		add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
		return is_wp_error( $init_return ) ? $init_return : $this->is_active();
	}
	/**
	 * Activate AddOn
	 * 
	 * @return string
	 */
	public function activate(){
		parent::activate();
		// Create default IgnitionDeck Sharing Email Template.
		// Identical to the current default email template.
		// It is re-created if deleted on ID re-activation.
		// returns the ID of the newly created or existent ID Email Template.
		$igd_email_template_ID = $this->email_templates->get_default_email_template_id(  $this->igd_default_email_template_title ); // exit if not false with the result( the ET ID).
		if ( $igd_email_template_ID ) :
			// check email template status.
			$status = 'publish'; // is required. 
			$status_check = $this->email_templates->update_email_template_status( $igd_email_template_ID, $status ); 
		else :
			// create new email template for IgD BSWP sharing block.
			$igd_email_template_ID = $this->email_templates->create_default_email_template( $this->igd_default_email_template_title ); 
		endif;
		// Block template `IgnitionDeck Sharing Block` is created and associated with IgnitionDeck Sharing Email template created above. 
		// Identical to the current default compact block template.
		if ( $igd_email_template_ID ) : 

			$igd_ui_template_settings = $this->get_default_id_sharing_block_settings( $igd_email_template_ID ); // prepare setting, to create new if not available.
			$igd_default_ui_template_ID = $this->ui_templates->get_default_ui_template_id( $igd_ui_template_settings ); // look only for this title.
			if ( $igd_default_ui_template_ID ) :
				// check and update the status to published, on activation step only.
				$status = 'publish'; // is required. 
				$status_check = $this->ui_templates->update_block_status( $igd_default_ui_template_ID, $status ); 
				// check and update the email ID if not the latest one.
				$default_block_settings = $this->ui_templates->get_bswp_ui_template_settings( $igd_default_ui_template_ID );
				
				if ( $default_block_settings['email']['email_template'] != $igd_email_template_ID ) :
					// update email_template_ID
					$default_block_settings['email']['email_template'] = $igd_email_template_ID;
					$bswp_ui_template_settings = base64_encode( serialize( $default_block_settings ) ); 

                update_post_meta( 
									$igd_default_ui_template_ID, 
                    'bswp_ui_template_settings', 
                    $bswp_ui_template_settings
                );
				endif;
			else :
				// no block to share, create one on activation step.
				$this->ui_templates->create_default_ui_template( $igd_ui_template_settings );
			endif;
		endif;
	}
	/**
	 * Deactivate AddOn
	 *
	 * @return void
	 */
	public function deactivate(){
		parent::deactivate();
		$status = 'trash';

		$igd_email_template_ID = $this->email_templates->get_default_email_template_id( $this->igd_default_email_template_title ); 
		if( $igd_email_template_ID ) :
			// move template to trash.
			$this->email_templates->update_email_template_status( $igd_email_template_ID, $status );
		endif;
		$settings = [];
		$settings['post_title'] = $this->igd_default_sharing_block_title;
		$igd_block_template_ID = $this->ui_templates->get_default_ui_template_id( $settings );
		if( $igd_block_template_ID ) :
			// move the block to trash.
			$this->ui_templates->update_block_status( $igd_block_template_ID, $status );
		endif;
	}
	/**
	 * Gets the
	 * default email template 
	 * ID if it exists 
	 * or returns false
	 *
	 * @param str $post_title
	 * @return int|bool (the email template) ID|false
	 */
	public function get_id_default_email_template_ID(){
		$igd_email_template_ID = $this->email_templates->get_default_email_template_id( $this->igd_default_email_template_title );
		return $igd_email_template_ID;
	}
	/**
	 * Checks that 
	 * ID BSWP block
	 * is available
	 * Check only the IgD BSWP title
	 *
	 * @return bool
	 */
	public function get_default_id_sharing_block_ID(){
		$post_status = 'publish'; // only published blocks are needed for do_shortcode.
		$settings = $this->get_default_id_sharing_block_settings(); // arr or false if no default igd sharing block
		if ( $settings ) :
			$igd_sharing_block_ID = $this->ui_templates->get_default_ui_template_id( $settings ); // returns false|block ID, 
			if ( $igd_sharing_block_ID ) :
				// only if block with status 'publish'.
				$post_status = get_post_status($igd_sharing_block_ID ); // Post status on success, false on failure.
				if ( 'publish' == $post_status ) :
					return $igd_sharing_block_ID;
				endif;
			endif;
		else :
			return false;
		endif;
	}
	/**
	 * Get default IgnDeck sharing block settings
	 *
	 * @param int $igd_email_template_ID
	 * @return arr|bool
	 */
	public function get_default_id_sharing_block_settings( $igd_email_template_ID = null ){// when created for the first time
		if ( empty ( $igd_email_template_ID ) ) :
			$igd_email_template_ID = $this->get_id_default_email_template_ID(); // if deleted, will fallback to default anyway.
		endif;
	
		$igd_ui_template_settings = [];
		$igd_ui_template_settings["email"]["email_template"] 	= $igd_email_template_ID;
		$igd_ui_template_settings['view_style']			 					= 'compact';
		$igd_ui_template_settings['post_title']			 					= $this->igd_default_sharing_block_title; 
		return $igd_ui_template_settings;
	}
	
	/**
	 * Check if IgnitionDeck,
	 * Crowd Funding and ID Commerce
	 * are Installed & Active
	 *
	 * @return bool
	 */
	public function is_plugin_active() {
		// Check if needed functions exists - if not, require them.
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		return is_plugin_active( 'ignitiondeck/idf.php' ) && is_plugin_active( 'idcommerce/idcommerce.php' ) && is_plugin_active( 'ignitiondeck-crowdfunding/ignitiondeck.php' );
	}

	/**
	 * Enqueue Scripts
	 *	
	 * @return mixed
	 */
	public function admin_scripts() { 
		wp_enqueue_script(
			'bswp-addons-ignitiondeck',
			BETTER_SHARING_URI . 'dist/addons/ignitiondeck.js',
			array(),
			BETTER_SHARING_VERSION,
			false
		);
	}
	/**
	 * Get messages by igd hook
	 *
	 * @param int $igd_post_ID
	 * @param str $hook
	 * @return arr
	 */
	public function get_id_hook_messages( $igd_post_ID, $hook ){
		$email_message = "";
		$x_message     = "";

			if ( $igd_post_ID ) :
				$igd_post = get_post( $igd_post_ID );
				if ( $igd_post ) :
					$igd_project_ID = get_post_meta( $igd_post_ID, 'ign_project_id', true);
					if ( $igd_project_ID ) :
						$hdeck = false;
						if ( class_exists( 'Deck' ) ) :
							$deck = new \Deck( $igd_project_ID );
							$hdeck = $deck->hDeck();   
						endif;
						if ( $hdeck ) :
							$campaign_title = $igd_post->post_title;
							$platform_name 	= get_bloginfo( 'name' );
							$goal 					= $hdeck->goal; // with currency.
							$project_short_descr 		= get_post_meta( $igd_post_ID, 'ign_project_description', true);
							$ignition_deck_encoded 	= '%40IgnitionDeck%0D%0D';
							$crowdfunding_encoded   = '%23crowdfunding';
							
							switch ( $hook ) {
								case 'ignitiondeck_share_public_project_page':
									/* translators: Campaign title, Platform name, Goal, Project short description */
									$email_format = __( 
																		"Check out this crowdfunding campaign! Help  %s on %s reach its goal of %s.</br></br>%s", 
																		'better-sharing-wp'
																	);
									$email_message = sprintf(
																		$email_format,
																		$campaign_title,
																		$platform_name,
																		$goal,
																		$project_short_descr
																);
									/* translators: Campaign title, Platform name, %40IgnitionDeck%0D%0D,  %23crowdfunding */
									$format = __( "Help make it happen for %s on %s. %s %s",  'better-sharing-wp' );
									$x_message = sprintf( 
																		$format, 
																		$campaign_title, 
																		$platform_name, 
																		$crowdfunding_encoded,
																		$ignition_deck_encoded 
																);
									break;
								
								case 'ignitiondeck_share_creator_project_page': 
									/* translators: Campaign title, Platform name, Goal, Project short description */
									$email_format =  __(
																	"Please help my crowdfunding campaign, %s on %s, reach its goal of %s. Every little bit helps to get the project across the finish line.</br></br>%s",
																	'better-sharing-wp'	
																); 
									$email_message 	= sprintf(
																		$email_format,
																		$campaign_title,
																		$platform_name,
																		$goal,
																		$project_short_descr
																);
									/* translators: %23crowdfunding, Campaign title, Goal, Platform name, %40IgnitionDeck%0D%0D */																
									$format 		= __(
																"Please support my %s campaign, %s  to reach its goal of %s on %s. Every little bit helps to get the project across the finish line. %s", 
																'better-sharing-wp'
															);
									$x_message = sprintf(
																	$format,
																	$crowdfunding_encoded,
																	$campaign_title,
																	$goal,
																	$platform_name,
																	$ignition_deck_encoded
																);										
									break;
								
								case 'ignitiondeck_share_backer_receipt_modal':
									$backers_count 	    = $hdeck->pledges - 1; 
									$current_amount_raw = $this->get_project_raised( $igd_project_ID ); 
									$goal_raw           = $this->get_project_goal( $igd_project_ID ); 
									$amount_left_raw		= $goal_raw - $current_amount_raw; 
									$amount_left        = $this->get_amount_with_currency( $amount_left_raw, $igd_project_ID );
									 
									if ( 0 < $amount_left_raw ) :
										/* translators: Campaign title, Platform name, Goal, Amount left, Project short description */
										$email_format       = __(
																					"Check out this crowdfunding campaign that I just supported! Help %s on %s reach its goal of %s. It needs %s more to get there, and every little bit helps!</br></br>%s",
																					'better-sharing-wp'
																				); 
										$email_message  		= sprintf(
																					$email_format,
																					$campaign_title,
																					$platform_name,
																					$goal,
																					$amount_left,
																					$project_short_descr
																				);
										/* translators: Bakers count, Campaign title, Platform name, Amount left, Goal, %23crowdfunding, %40IgnitionDeck%0D%0D */		
										$format		 				= __( 
																					"I just joined %s other people who have backed %s on %s. It needs %s more to reach its goal of %s! %s %s", 
																					'better-sharing-wp'
																				);
										$x_message				= sprintf(
																				$format,
																				$backers_count,
																				$campaign_title,
																				$platform_name,
																				$amount_left,
																				$goal,
																				$crowdfunding_encoded,
																				$ignition_deck_encoded
																			);
									else :
										/* translators: Campaign title, Platform name, Goal, Project short description */		
										$email_format     = __(
																				"Check out this crowdfunding campaign that I just supported! Help %s on %s reach its goal of %s.</br></br>%s",
																				'better-sharing-wp'
																			);  
										$email_message  	= sprintf(
																					$email_format, 
																					$campaign_title,
																					$platform_name,
																					$goal,
																					$project_short_descr
																				);
										/* translators: Bakers count, Campaign title, Platform name, %23crowdfunding, %40IgnitionDeck%0D%0D */		
										$format 				= __( "I just joined %s other people who have backed %s on %s. %s %s", 'better-sharing-wp' ); 
										
										$x_message			= sprintf(
																			$format,
																			$backers_count,
																			$campaign_title,
																			$platform_name,
																			$crowdfunding_encoded,
																			$ignition_deck_encoded
																		);
									endif;
								break;
							} 
						endif;
					endif;
				endif;
			endif;
		return ["email_message" => $email_message, 'x_message' => $x_message ];
	}
	/**
	 * Inject BSWP in IgnitionDeck modules
	 *
	 * @return void
	 */
	public function load_bswp() { 
		$igd_sharing_block = $this->get_default_id_sharing_block_ID(); 

		if ( ! $igd_sharing_block ) :
			$this->admin_notices[] = [
				'type' => 'error',
				'message' => __('No IgnitionDeck Sharing Block available or its status is not <b>publish</b>!', 'better-sharing-wp')
			];
			return;
		endif;
		if ( empty( $this->igd_hooks ) ) : 
			$this->admin_notices[] = [
				'type' => 'error',
				'message' => __('No IgnitionDeck Hooks to trigger Better Sharing available!', 'better-sharing-wp')
			];
			return;
		else :
			foreach ( $this->igd_hooks as $hook ) : 
				$link = '';
				$email_message = '';
			
					add_action(
						$hook,
						function ( $igd_post_ID = null ) use ( $igd_sharing_block, $hook, $link, $email_message ) {
							$link 		= get_permalink( $igd_post_ID );
							$messages = $this->get_id_hook_messages( $igd_post_ID, $hook );

							$bswp_shortcode = '[better-sharing ';
							$bswp_shortcode .= 'id="' . $igd_sharing_block . '" ';
							$bswp_shortcode .= ($link ? 'referral_link="' . $link . '" ' : ' ');

							if ( ! empty ( $messages['email_message'] ) ) :
								$bswp_shortcode .= ('email_message="' . $messages['email_message'] . '" ');
							endif;

							if ( ! empty ( $messages['x_message'] ) ) :
								$bswp_shortcode .= ('x_message="' . $messages['x_message'] . '" ');
							endif;

							$bswp_shortcode .= ']';

							$output = "<div style='margin: 1rem 0'>";
							$output .= do_shortcode( $bswp_shortcode );
							$output .= "</div>";

							echo $output;
						}
					); 
			endforeach;
		endif;
	}
	/**
	 * Get project's 
	 * raised up to now
	 *
	 * @param int $project_ID
	 * @return int
	 */
	public function get_project_raised( $project_ID ) {
		global $wpdb;	
		$sql    = 'SELECT SUM(prod_price) AS raise from ' . $wpdb->prefix . 'ign_pay_info WHERE product_id = "' . $project_ID . '"';
		$result = $wpdb->get_row( $sql );
		$raised = ( ! empty( $result->raise ) ? $result->raise : '0' );
		return $raised;
	}
	/**
	 * Get project's goal
	 * as number
	 *
	 * @param int $project_ID
	 * @return void
	 */
	public function get_project_goal( $project_ID ) { 
		global $wpdb;
		$sql = $wpdb->prepare( 'SELECT id, goal FROM ' . $wpdb->prefix . 'ign_products WHERE id = %d', $project_ID );
		$result = $wpdb->get_row( $sql );
		return $result->goal;
	}

	public function get_amount_with_currency( $amount, $project_ID ) {
		global $global_currency;
		if ($global_currency == 'BTC' || $global_currency == 'credits') {
			return ( apply_filters( 'id_display_currency', apply_filters( 'id_number_format', $amount, 8 ), $project_ID ) );
		} else {
			return ( apply_filters( 'id_display_currency', apply_filters( 'id_number_format', $amount ), $project_ID ) );
		}
	}
	/**
	 * Add Custom Admin Notice
	 * 
	 * @return void
	 */
	public function display_admin_notice(){
		if ( ! empty( $this->admin_notices ) ) :
			foreach( $this->admin_notices as $notice ) :
				echo "<div class='notice notice-" . $notice['type'] . " is-dismissible'><p>" . $notice['message'] . "</p></div>"; // WPCS: XSS ok.
			endforeach;
		endif;
	}
}
