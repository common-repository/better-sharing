<input type="radio" name="tabs" id="bswp-email-module" class="tab-label">
<label class="bswp-tab-label" for="bswp-email-module"><span>Email</span><span></span></label>
<div class="tab <?php echo !$is_full_view ? 'compact-view' : '' ?>" data-tab="bswp-email">
	<div class="bswp-module-settings">
        <div class="bswp__form-group">
			<label class="switch bswp-module-enable">
				<?php
					//is enabled module by default
					$checked = 'checked="true"';

					if( !empty( $bswp_ui_template_settings ) ){
						//is enabled module by settings
						if( !$bswp_ui_template_settings['email']['enabled'] ){
							$checked = '';
						}
					}
				?>
  				<input type="checkbox" <?php echo esc_attr($checked); ?> data-module="bswp-email" autocomplete="off">
  				<span class="slider round"></span>
			</label>			
			<span class="bswp-toggle-label"><?php _e('Enabled', 'better-sharing-wp') ?></span>
	  	</div>
	</div>
	<?php 
		$bswp_ui_template_module_hidden = '';

		if( !empty( $bswp_ui_template_settings ) ) : 
			
			if( !( $bswp_ui_template_settings['email']['enabled'] ) ) : 

				$bswp_ui_template_module_hidden = 'bswp-ui-template-module-hidden'; 
			endif; 
		endif; 
	?>
	<div class="<?php echo esc_attr($bswp_ui_template_module_hidden); ?>">
    	<div class="wp-block-cgb-block-ea-better-sharing">     
        <?php 
        	include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/ui-template-modules/email.php';
    		?>
    	</div>
    	<div class="bswp-module-settings">       
			<div class="bswp__form-group">
				<label for="bswp-email-title"><?php _e('Title', 'better-sharing-wp') ?></label>
				<div class="break"></div>
				<div class="bswp__input-group">
				<?php  
				
					$email_title = $default_settings['email']['title'];

					if( !empty( $bswp_ui_template_settings ) ) : 
						if( !empty( trim( esc_attr( $bswp_ui_template_settings['email']['title'] ) ) ) ) : 

							$email_title = trim( esc_attr( $bswp_ui_template_settings['email']['title'] ) ); 
						endif; 
					endif; 
					?>
				<input 
					type="text" 
					id="bswp-email-title"					
					class="bswp-text-update"
					data-update="text"	
					data-target="bswp-title"		
					placeholder="Share via Email heading"						
					name="bswp_ui_template_settings[email][title]" 
					value="<?php  echo esc_attr($email_title); ?>"  
					autocomplete="off"     
					/>
			</div>
	  		</div>
        <div class="bswp__form-group">
			<label for="bswp-email-subtitle"><?php _e('Subtitle', 'better-sharing-wp') ?></label>
			<div class="break"></div>
			<div class="bswp__input-group">
				<?php  
				
				$email_subtitle = $default_settings['email']['subtitle'];

				if( !empty( $bswp_ui_template_settings ) ) : 

					if( !empty( trim( $bswp_ui_template_settings['email']['subtitle'] )  ) ) :

						$email_subtitle = trim( esc_attr( $bswp_ui_template_settings['email']['subtitle'] ) ); 
					endif; 
				endif; 
				?>
				<input 
					type="text" 
					id="bswp-email-subtitle" 
					class="bswp-text-update"
					data-update="text"	
					data-target="bswp-sub-title"
					placeholder="Subtitle text ..."		
					name="bswp_ui_template_settings[email][subtitle]" 						
					value="<?php  echo esc_attr($email_subtitle); ?>"   
					autocomplete="off"    
					/>
			</div>
	  	</div>
		<div class="bswp__form-group">
			<label for="bswp-emails-input"><?php _e('Emails input placeholder text', 'better-sharing-wp') ?></label>
			<div class="break"></div>
			<div class="bswp__input-group">
				<?php  
				
				$emails_input_placeholder = $default_settings['email']['emails_input_placeholder'];

				if( !empty( $bswp_ui_template_settings ) ) :  
					
					if( !empty( trim( esc_attr( $bswp_ui_template_settings['email']['emails_input_placeholder'] ) ) ) ) :
						$emails_input_placeholder = trim( esc_attr( $bswp_ui_template_settings['email']['emails_input_placeholder'] ) ); 
					endif;
				endif;
				?>
				<input 
					type="text" 
					id="bswp-emails-input"
					class="bswp-text-update"
					placeholder="To: enter contact emails separated by comma (,)"
					name="bswp_ui_template_settings[email][emails_input_placeholder]" 
					data-update="placeholder"	
					data-target="bswp-email-placeholder"							
					value="<?php echo esc_attr($emails_input_placeholder); ?>" 
					autocomplete="off"       
					/>
			</div>
		</div>
		<div class="bswp__form-group <?php echo esc_attr($hidden_custom_message_container); ?>">
			<label for="bswp-custom-message-placeholder"><?php _e('Custom message placeholder text', 'better-sharing-wp') ?></label>
			<div class="break"></div>
			<div class="bswp__input-group">
				<?php  

				$email_message_placeholder = $default_settings['email']['message_placeholder'];

				if( !empty( $bswp_ui_template_settings ) ) :
					
					if( !empty( trim( $bswp_ui_template_settings['email']['message_placeholder'] ) ) ) :

						$email_message_placeholder = trim( esc_attr( $bswp_ui_template_settings['email']['message_placeholder'] ) ); 
					endif; 
				endif; 
				?>
				<input 
					type="text" 
					id="bswp-custom-message-placeholder" 
					class="bswp-text-update"
					placeholder="Message"	
					name="bswp_ui_template_settings[email][message_placeholder]" 						
					value="<?php echo esc_attr($email_message_placeholder); ?>"       
					data-update="text"	
					data-target="bswp-email-message"
					autocomplete="off"
				/>
			</div>
	  </div>
		
		<div class="bswp__form-group">
			<label for="bswp-send-btn-text"><?php _e('Send button text', 'better-sharing-wp') ?></label>
			<div class="break"></div>
			<div class="bswp__input-group">
				<?php  
				
				$email_btn_text = $default_settings['email']['send_btn_text'];

				if( !empty( $bswp_ui_template_settings ) ) : 

					if( !empty( trim( $bswp_ui_template_settings['email']['send_btn_text'] ) ) ) :
						
						$email_btn_text = trim( esc_attr( $bswp_ui_template_settings['email']['send_btn_text'] ) ); 
					endif;
				endif;
				?>
				<input 
					type="text" 
					id="bswp-send-btn-text" 
					class="bswp-text-update"
					name="bswp_ui_template_settings[email][send_btn_text]" 	
					value="<?php echo esc_attr($email_btn_text); ?>"        
					data-update="text"	
					data-target="bswp-submit"
					autocomplete="off"
					/>		  	
			</div>
	  </div>
		<div class="bswp__form-group">
			<label for="bswp-email-template"><?php _e('Email Template', 'better-sharing-wp'); ?></label>
			<div class="break"></div>
			<div class="bswp__input-group">
				<select 						
					id="bswp-email-template" 
					name=bswp_ui_template_settings[email][email_template]
					autocomplete="off"
					/>
					<?php if( !empty( $email_templates ) ) : ?>
					<?php $selected_email_template_ID = $default_email_ID; // int | 0. ?>
			
					<?php  if( !empty( $bswp_ui_template_settings ) ) : ?>
						<?php if ( !empty( $bswp_ui_template_settings['email']['email_template'] ) ) : 
							$selected_email_template_ID = $bswp_ui_template_settings['email']['email_template']; // update to int from default int|0.
						endif; ?>
					<?php endif; ?>
						<option value="">
						<?php _e('Select from the list', 'better-sharing-wp'); ?>
						</option>
						<?php foreach( $email_templates as $email_template ) : ?>
							<option value="<?php echo esc_attr($email_template->ID); ?>"  
									<?php if( $email_template->ID == $selected_email_template_ID ) : ?>
										selected="true"
									<?php endif; ?>
							>
								<?php echo esc_html($email_template->post_title); ?>
							</option>
						<?php endforeach; ?>
					<?php else : ?>
						<option value="">
						<?php _e('No email templates available yet!', 'better-sharing-wp'); ?>
						</option>
					<?php endif; ?>
				</select>
			</div>
	  </div>
		<div class="bswp__form-group">
			<label 
				for="bswp-contact-picker-config">
				<?php _e('Fallback message to share', 'better-sharing-wp'); ?>
			</label>
			<div class="break"></div>
			<div class="bswp__input-group">
				<?php
					$email_message_fallback = $default_settings['email']['email_message_fallback'];
					
					if( !empty( $bswp_ui_template_settings ) ) : 
						if ( isset( $bswp_ui_template_settings['email']['email_message_fallback'] ) ) : 

							$email_message_fallback = trim( esc_textarea( $bswp_ui_template_settings['email']['email_message_fallback'] ) );
						endif; 
					endif; 
				?>
				<textarea						
					id="bswp-email-message-fallback" 
					name=bswp_ui_template_settings[email][email_message_fallback]
					autocomplete="off"
					/><?php echo esc_textarea( $email_message_fallback ); ?></textarea>
			</div>
	  </div>	
		<div class='bswp-meta-itemprop'>
				<?php  _e('Override {{email_message}} template variable by using ', 'better-sharing-wp');?>
				<span><?php  _e('&lt;meta itemprop="bswp_email_message"  content="your content here" /&gt;, ', 'better-sharing-wp');?></span>
				<?php  _e('for more information read the ', 'better-sharing-wp');?>
				<a href="https://www.cloudsponge.com/developer/better-sharing-wordpress/template-variables/" target="_blank"><?php _e('documentation', 'better-sharing-wp'); ?></a>
			</div>			
		<div class="bswp__form-group bswp-email-preview">
			<h4>Email content preview</h4>
			<?php 
			//email preview is displayed by default
			$email_preview_on = "checked='true'";
			$email_preview_off = ""; 

			if( !empty( $bswp_ui_template_settings) ) :
				//hidden/selectd by settings 
				if ( !isset($bswp_ui_template_settings['email']['email_preview'] ) ) :
					$email_preview_on = "";
					$email_preview_off = "checked='true'";
				elseif( $bswp_ui_template_settings['email']['email_preview'] == "off" ) : 
					$email_preview_on = "";
					$email_preview_off = "checked='true'";
				endif;
			endif;
			?>
			<label for="bswp-email-preview-on" class="email-preview-toggle">
				<input 
					type="radio"
					id="bswp-email-preview-on"
					name="bswp_ui_template_settings[email][email_preview]"  							
					value="on" 
					<?php echo esc_attr($email_preview_on); ?>
					autocomplete="off"
					/>
					<span><?php _e('On', 'better-sharing-wp') ?></span>
		  </label>	
			<label for="bswp-email-preview-off" class="email-preview-toggle">		
				<input 
					type="radio"
					id="bswp-email-preview-off" 	
					name="bswp_ui_template_settings[email][email_preview]" 						
					value="off" 
					<?php echo esc_attr($email_preview_off); ?>
					autocomplete="off"
					/>
					<span><?php _e('Off', 'better-sharing-wp') ?></span>
		  </label>		
	  </div>
		<br/><hr/><br/>
		<div class="bswp__form-group">
			<label for="bswp-success-screen-msg"><?php _e('Success screen message', 'better-sharing-wp') ?></label>
			<div class="break"></div>
			<div class="bswp__input-group">
				<?php  
				
				$success_screen_msg = $default_settings['email']['success_screen_msg'];

				if( !empty( $bswp_ui_template_settings ) ) : 

					if( isset( $bswp_ui_template_settings['email']['success_screen_msg'] ) ) : 
						
						$success_screen_msg = trim( $bswp_ui_template_settings['email']['success_screen_msg'] );
					endif;
				endif;
				?>	
					<textarea						
					id="bswp-success-screen-msg" 
					name="bswp_ui_template_settings[email][success_screen_msg]"
					autocomplete="off" 
					><?php echo esc_html( $success_screen_msg ); ?></textarea>
			</div>
	  </div>
		<div class='bswp-meta-itemprop'>
					<?php  _e('Use <strong>{{emails_count}}</strong> template variable in your success message to show the selected contacts count.', 'better-sharing-wp');?>
					<br/><?php  _e('Override success message by using ', 'better-sharing-wp');?>
					<span><?php  _e('&lt;meta itemprop="bswp_email_success_message"  content="your message here" /&gt;,', 'better-sharing-wp');?></span>
					<?php  _e('for more information read the ', 'better-sharing-wp');?>
					<a href="https://www.cloudsponge.com/developer/better-sharing-wordpress/template-variables/" target="_blank"><?php _e('documentation', 'better-sharing-wp'); ?></a>. 
				</div>
		<div class="bswp__form-group">
			<label for="bswp-success-screen-cta-label"><?php _e('Success screen CTA label', 'better-sharing-wp') ?></label>
			<div class="break"></div>
			<div class="bswp__input-group">
				<?php  
				
				$success_screen_cta_label = $default_settings['email']['success_screen_cta_label'];

				if( !empty( $bswp_ui_template_settings ) ) : 

					if( isset( $bswp_ui_template_settings['email']['success_screen_cta_label'] ) 
							&& !empty( trim( $bswp_ui_template_settings['email']['success_screen_cta_label'] ) ) ) :
						
						$success_screen_cta_label = trim( $bswp_ui_template_settings['email']['success_screen_cta_label'] ); 
					endif;
				endif;
				?>
				<input 
					type="text" 
					id="bswp-success-screen-cta-label" 
					class="bswp-text-update"
					name="bswp_ui_template_settings[email][success_screen_cta_label]" 	 
					value="<?php echo esc_attr( $success_screen_cta_label ); ?>"       
					data-update="text"	
					data-target="bswp-submit"
					autocomplete="off"
					/>		  	
			</div>
	  </div>
		<div class='bswp-meta-itemprop'>
					<?php  _e('Override success button label by using ', 'better-sharing-wp');?>
					<span><?php  _e('&lt;meta itemprop="bswp_email_success_btn_label"  content="your label here" /&gt;,', 'better-sharing-wp');?></span>
					<?php  _e('for more information read the ', 'better-sharing-wp');?>
					<a href="https://www.cloudsponge.com/developer/better-sharing-wordpress/template-variables/" target="_blank"><?php _e('documentation', 'better-sharing-wp'); ?></a>
				</div>
		<br/><hr/><br/>
		<div class="bswp__form-group">
			<label 
				for="bswp-contact-picker-config"
				class="cloud-sponge-config">
				<?php _e('CloudSponge Contact Picker JSON configuration ', 'better-sharing-wp'); ?>
				<a href="https://www.cloudsponge.com/developer/contact-picker/options/" target="_blank"><?php _e('Documentation', 'better-sharing-wp'); ?></a>
			</label>
			<div class="break"></div>
			<div class="bswp__input-group">
				<?php
					$contact_picker_config = ""; 
					
					if( !empty( $bswp_ui_template_settings ) ) : 

						$contact_picker_config = trim( esc_textarea( $bswp_ui_template_settings['email']['contact_picker_config'] ) );
					endif; 
				?>
				<textarea						
					id="bswp-contact-picker-config" 
					name=bswp_ui_template_settings[email][contact_picker_config]
					autocomplete="off"
					><?php echo esc_textarea($contact_picker_config); ?></textarea>
			</div>
	  </div>
  </div>
	</div>
</div>