<?php

namespace BetterSharingWP;

use BetterSharingWP\AdminScreens\EmailTemplate;
use BetterSharingWP\AdminScreens\UITemplate;

class BSWP_DemoPage
{
	/**
	 * Demo page post type.
	 *
	 * @var arr
	 */
	private $post_type = 'page';
	/**
	 * Demo page title.
	 *
	 * @var arr
	 */
	private $page_title = 'You Need Better Sharing';

	public function init(){
		$demo_data = [];
		$email_template = new EmailTemplate();
		
		$email_template_ID = $email_template->get_default_email_template_id();
		if ( $email_template_ID ) :
			// check email template status.
			$status = 'publish'; // is required. 
			$status_check = $email_template->update_email_template_status( $email_template_ID, $status ); 
		else :
			// create new email template for default BSWP sharing blocks.
			$email_template_ID = $email_template->create_default_email_template(); 
		endif;

		if ( $email_template_ID ) :
			$ui_template = new UITemplate();
			$ui_template_settings = [];
			$ui_template_settings["email"]["email_template"] 	= $email_template_ID;
			// UI template 1.
			$ui_template_settings['view_style']			 					= 'compact';
			$ui_template_settings['post_title']			 					= 'Default Compact Block';
			$compact_default_ui_template_ID = $ui_template->get_default_ui_template_id( $ui_template_settings ); // look only for the title.
			if ( $compact_default_ui_template_ID ) :
				// check and update the status to published, on activation step only.
				$status = 'publish'; // is required. 
				$status_check = $ui_template->update_block_status( $compact_default_ui_template_ID, $status ); 

				// check and update the email ID if not the latest one.
				$default_compact_block_settings = $ui_template->get_bswp_ui_template_settings( $compact_default_ui_template_ID );
				
				if ( $default_compact_block_settings['email']['email_template'] != $email_template_ID ) :
					// update email_template_ID.
					$default_compact_block_settings['email']['email_template'] = $email_template_ID;
					$bswp_ui_template_settings = base64_encode( serialize( $default_compact_block_settings ) ); 

                update_post_meta( 
									$compact_default_ui_template_ID, 
                    'bswp_ui_template_settings', 
                    $bswp_ui_template_settings
                );
				endif;

				$demo_data['ui_templates']['compact'] = $compact_default_ui_template_ID;
			else :
				// no block to add in demo page, create one on activation step.
				$demo_data['ui_templates']['compact'] = $ui_template->create_default_ui_template( $ui_template_settings );
			endif;
		 	
			// UI template 2.
			$ui_template_settings['view_style']			 					= 'full';
			$ui_template_settings['post_title']			 					= 'Default Inline Block';
			$inline_default_ui_template_ID = $ui_template->get_default_ui_template_id( $ui_template_settings ); // look only for the title.
			if ( $inline_default_ui_template_ID ) :
				// check and update the status to published, on activation step only.
				$status = 'publish'; // is required. 
				$status_check = $ui_template->update_block_status( $inline_default_ui_template_ID, $status ); 

				// check and update the email ID if not the latest one.
				$default_inline_block_settings = $ui_template->get_bswp_ui_template_settings( $inline_default_ui_template_ID );
			
				if ( $default_inline_block_settings['email']['email_template'] != $email_template_ID ) :
					// update email_template_ID.
					$default_inline_block_settings['email']['email_template'] = $email_template_ID;
					$bswp_ui_template_settings = base64_encode( serialize( $default_inline_block_settings ) ); 

								update_post_meta( 
									$inline_default_ui_template_ID, 
										'bswp_ui_template_settings', 
										$bswp_ui_template_settings
								);
				endif;

				$demo_data['ui_templates']['inline'] = $inline_default_ui_template_ID;
			else :
				// no block to add in demo page, create one on activation step.
				$demo_data['ui_templates']['inline'] = $ui_template->create_default_ui_template( $ui_template_settings );
			endif; 

			if ( !empty( $demo_data ) ) :
				$this->create_bswp_demo_page( $demo_data );
			endif;
		endif;
	}
	/**
	 * Gets the demo page ID
	 *
	 * @return int|bool demo page ID | false
	 */
	public function check_for_demo_page(){
		$demo_data = [];
		$email_template = new EmailTemplate();
		$email_template_ID = $email_template->get_default_email_template_id(); // (the email template) ID|false. 
		if ( $email_template_ID ) :
			$ui_template = new UITemplate();
			$ui_template_settings = [];
			$ui_template_settings["email"]["email_template"] 	= $email_template_ID;
			// defult UI template 1.
			$ui_template_settings['view_style']			 					= 'compact';
			$ui_template_settings['post_title']			 					= 'Default Compact Block';
			$compact_ui_template_ID = $ui_template->get_default_ui_template_id( $ui_template_settings ); // ID|false  
			
			if ( $compact_ui_template_ID ) :
				$demo_data['ui_templates']['compact'] = $compact_ui_template_ID;
				// default UI template 2.
				$ui_template_settings['view_style']			 					= 'full';
				$ui_template_settings['post_title']			 					= 'Default Inline Block';
				$inline_ui_template_ID = $ui_template->get_default_ui_template_id( $ui_template_settings );

				if ( $inline_ui_template_ID ) : 
					
					$demo_data['ui_templates']['inline'] = $inline_ui_template_ID;

					$data = [];
					$data['post_type']  	= $this->post_type;
					$data['post_title'] 	= $this->page_title;
					$data['post_content'] = $this->get_demo_page_content_exec( $demo_data ); 
					$data['post_content_sample'] = $this->get_demo_page_content_sample( $demo_data ); 
					$demo_page = $this->get_demo_page( $data );
					return $demo_page;
				endif;
				return false; // no demo page available.
			endif;
			return false; // no demo page available.
		endif;

		return false; // no demo page available.
	}
	/**
	 * Gets or Creates BSWP demo page
	 * with status 'draft'
	 * if one not alredy created
	 *
	 * @param arr $demo_data
	 * @return int returns ID on success, WP_errorr on failure.
	 */
	public function create_bswp_demo_page( $demo_data ){
		// check page not alredy created
		$data = [];
		$data['post_type']  	= $this->post_type;
		$data['post_title'] 	= $this->page_title;
		$data['post_content'] = $this->get_demo_page_content_exec( $demo_data ); // to create a new demo page.
		$data['post_content_sample'] = $this->get_demo_page_content_sample( $demo_data ); // without do_shortcode() in content for str comparison. 
		$demo_page = $this->get_demo_page( $data ); // ID or false, 

		if ( ! $demo_page ) :
			$postarr = array(
				'post_content' => $data['post_content'], // with do_shortcode().
				'post_title'   => $data['post_title'],
				'post_status'  => 'draft',
				'post_type'    => $data['post_type'],
				'comment_status' => 'closed',
				'post_name'    => $data['post_title'], // post slug.
			);
			// save demo page.
			$demo_page = wp_insert_post( $postarr, true );// returns ID on success, WP_errorr on failure.
			if ( is_int( $demo_page ) ) : 
				add_post_meta( $demo_page, 'bswp_demo_page', 1 );
			endif;
		endif;
		return $demo_page;
	}
	/**
	 * Get demo page
	 * content
	 * with executable do_shortcode() func
	 * @param arr $data
	 * @return string
	 */
	public function get_demo_page_content_exec( $data ){
		$compact_ui_template_edit = $this->bswp_get_post_edit_link( $data['ui_templates']['compact'] );
		$inline_ui_template_edit = $this->bswp_get_post_edit_link( $data['ui_templates']['inline'] );
		
		$content = '<div class="bswp-demo-content"><p>This page demonstrates the basic functionality of ';
		$content .= '<a rel="noreferrer noopener" href="https://wordpress.org/plugins/better-sharing/" target="_blank">';
		$content .= 'the Better Sharing plugin for WordPress</a>.</p>';
		$content .= '<div><h3 class="wp-block-heading">Compact User Interface</h3>';
		$compact_block_id = $data['ui_templates']['compact'];
		$content.= do_shortcode("[better-sharing id=$compact_block_id]"); 
		$content.= "<p class='demo-paragraph'>You can play with this <a href=$compact_ui_template_edit rel='noreferrer noopener' ";
		$content.= 'target="_blank">Compact Block\'s settings</a>.</p>';
		$content.= '<h3 class="wp-block-heading">Inline User Interface</h3>';
		$inline_block_id = $data['ui_templates']['inline'];
		$content.= do_shortcode("[better-sharing id=$inline_block_id]");  
		$content.= "<p class='demo-paragraph'>You can play with this <a href=$inline_ui_template_edit rel='noreferrer noopener' ";
		$content.= 'target="_blank" >Inline Block\'s settings</a>.</p></div></div>';

		return $content;
	}
	/**
	 * Get demo page
	 * content
	 * without executable do_shortcode() func
	 * @param arr $data
	 * @return string
	 */
	public function get_demo_page_content_sample( $data ){
		$compact_ui_template_edit = $this->bswp_get_post_edit_link( $data['ui_templates']['compact'] );
		$inline_ui_template_edit = $this->bswp_get_post_edit_link( $data['ui_templates']['inline'] );
		
		$content = '<div class="bswp-demo-content"><p>This page demonstrates the basic functionality of ';
		$content .= '<a rel="noreferrer noopener" href="https://wordpress.org/plugins/better-sharing/" target="_blank">';
		$content .= 'the Better Sharing plugin for WordPress</a>.</p>';
		$content .= '<div><h3 class="wp-block-heading">Compact User Interface</h3>';
		$compact_block_id = $data['ui_templates']['compact'];
		$content.=  "[better-sharing id=$compact_block_id]"; 
		$content.= "<p class='demo-paragraph'>You can play with this <a href=$compact_ui_template_edit rel='noreferrer noopener' ";
		$content.= 'target="_blank">Compact Block\'s settings</a>.</p>';
		$content.= '<h3 class="wp-block-heading">Inline User Interface</h3>';
		$inline_block_id = $data['ui_templates']['inline'];
		$content.= "[better-sharing id=$inline_block_id]";  
		$content.= "<p class='demo-paragraph'>You can play with this <a href=$inline_ui_template_edit rel='noreferrer noopener' ";
		$content.= 'target="_blank" >Inline Block\'s settings</a>.</p></div></div>';

		return $content;
	}
	/**
	 * Get BSWP
	 * demo page 
	 * 
	 * @param arr $data
	 * @return int|bool demo page ID or false.
	 */
	public function get_demo_page( $data ){    
		$demo_page = post_exists( $data['post_title'], $data['post_content_sample'], '', 'page');
		return $demo_page; 
	}
	/**
	 * Get the BSWP CPT
	 * link to admin 
	 * edit CPT page
	 *
	 * @param int $post_ID
	 * @return string
	 */
	public function bswp_get_post_edit_link( $post_ID ){
		$action = '&action=edit';
		$edit_link = "post.php?post=%d";
		$link = admin_url( sprintf( $edit_link . $action, $post_ID ) );
		return $link;
	}
	/**
	 * Get the BSWP CPT
	 * link to public page
	 *
	 * @param int $post_ID
	 * @return string
	 */
	public function bswp_get_post_view_link( $post_ID ){
		return get_permalink( $post_ID );
	}
	/**
	 * Get the demo page edit link
	 *
	 * @return string|bool the link | false when no demo page.
	 */
	public function get_demo_page_edit_link(){ 
		$demo_page_ID = $this->check_for_demo_page();  
		if ( $demo_page_ID ) : 
				return $this->bswp_get_post_edit_link( $demo_page_ID ); // admin.   
		endif;
		return false;
	}
	/**
	 * Get the demo page view link
	 *
	 * @return string|bool the link | false when no demo page.
	 */
	public function get_demo_page_view_link(){ 
		$demo_page_ID = $this->check_for_demo_page();  
		if ( $demo_page_ID ) : 
				return $this->bswp_get_post_view_link( $demo_page_ID ); // admin.   
		endif;
		return false;
	}
}