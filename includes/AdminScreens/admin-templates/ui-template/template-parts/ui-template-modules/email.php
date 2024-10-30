<div class="flex items-center email-trigger-container compact-view-container"> 
	<a href="#" class="btn btn-primary btn--primary">
		<?php echo $default_settings['compact_view_icons']['email']; ?>
  </a>
</div>
<div class="email bswp-ui-template-module"  data-module="bswp-email">
	<h4 class="bswp-title">
		<?php 
			$email_title = $default_settings['email']['title'];

			if( !empty( $bswp_ui_template_settings ) ) : 

				if( !empty( trim( $bswp_ui_template_settings['email']['title'] ) ) ) : 

					$email_title = trim( $bswp_ui_template_settings['email']['title'] );
				endif;		
			endif;		
		?>
		<?php echo esc_html($email_title); ?>
	</h4>
	<p class="bswp-sub-title">
		<?php 
			$email_subtitle = $default_settings['email']['subtitle'];

				if( !empty( $bswp_ui_template_settings ) ) : 

					if( !empty( trim( $bswp_ui_template_settings['email']['subtitle']) ) ) :

						$email_subtitle = trim( $bswp_ui_template_settings['email']['subtitle'] ); 
					endif; 
				endif; 
		?>
		<?php echo esc_html($email_subtitle); ?>
	</p>	
	<div class="flex items-center">
		<div class="flex-grow">
			<?php 
				$demo_emails_input_placeholder = $default_settings['email']['emails_input_placeholder'];

				if( !empty( $bswp_ui_template_settings ) ) :  
					
					if( !empty( trim( esc_attr( $bswp_ui_template_settings['email']['emails_input_placeholder'] ) ) ) ) :
						$demo_emails_input_placeholder = trim( esc_attr( $bswp_ui_template_settings['email']['emails_input_placeholder'] ) ); 
					endif;
				endif;		
			?>
			<input 
				type="text" 
				name="bswp-share-email-input" 
				id="bswp-share-email-input" 
				placeholder="<?php echo esc_attr($demo_emails_input_placeholder); ?> (required)" 
				class="form-control border rounded-r-none bswp-email-placeholder"
				readonly="true"
				value="" 
				autocomplete="off"
				>
		</div>
		<div>
			<a href="#" class="add-from-address-book-init btn btn-secondary btn--secondary border rounded-l-none">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
					<path fill="none" d="M0 0h24v24H0z"></path>
					<path d="M3 2h16.005C20.107 2 21 2.898 21 3.99v16.02c0 1.099-.893 1.99-1.995 1.99H3V2zm4 2H5v16h2V4zm2 16h10V4H9v16zm2-4a3 3 0 0 1 6 0h-6zm3-4a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm8-6h2v4h-2V6zm0 6h2v4h-2v-4z"></path>
				</svg>
				<span><?php _e('Add From Contacts', 'better-sharing-wp'); ?></span>
			</a>
		</div>
	</div>	
	<?php 
	//has custom message check			
	$hidden_custom_message_container = "bswp-hidden-custom-message-container";
	
	if ( isset( $email_preview['has_template_vars'] ) ) :

		if ( in_array( 'sender_custom_message',  $email_preview['has_template_vars'] ) ) :

			$hidden_custom_message_container = "";
		endif;
	endif;
	?>				
	<div class="bswp-email-custom-message <?php echo esc_attr($hidden_custom_message_container); ?>">
		<?php 
		
		$message_placeholder = $default_settings['email']['message_placeholder'];

		if( !empty( $bswp_ui_template_settings ) ) : 

			if( !empty( trim( $bswp_ui_template_settings['email']['message_placeholder'] ) ) ) : 

				$message_placeholder = trim( $bswp_ui_template_settings['email']['message_placeholder'] );			
			endif;		
		endif;		
		?>
		<textarea 
			name="bswp-share-email-content"
			class="form-control border bswp-email-message bswp-share-email-content"
			readonly="true"
			autocomplete="off"
		><?php echo esc_textarea($message_placeholder); ?> (required)</textarea>
	</div>		
	<?php 
	// decide if preview is visible on page load
	$hidden_email_preview_container="";

	if( !empty( $bswp_ui_template_settings) ) :
		//hidden by by settings
		if( !isset( $bswp_ui_template_settings['email']['email_preview'] ) ) : 
					
			$hidden_email_preview_container ="bswp-hidden-email-preview-container";
		elseif( $bswp_ui_template_settings['email']['email_preview'] == "off" ) : 
					
			$hidden_email_preview_container ="bswp-hidden-email-preview-container";
		endif;
	endif;
	?>				 
	<div 
		class="bswp-template-ui-email-preview <?php echo esc_attr( $hidden_email_preview_container ); ?>" 
		data-email-preview="<?php echo esc_attr(json_encode( $email_preview )); ?>">
	</div>
	

	<button class="bswp-submit btn btn-primary btn--primary">
		<?php	
			$send_btn_text = $default_settings['email']['send_btn_text'];

			if( !empty( $bswp_ui_template_settings ) ) : 

				if( !empty( trim( $bswp_ui_template_settings['email']['send_btn_text'] ) ) ) :
						
					$send_btn_text = trim( $bswp_ui_template_settings['email']['send_btn_text'] ); 
				endif;
			endif;
		?>
		<?php echo esc_html($send_btn_text); ?>
	</button>
</div>
