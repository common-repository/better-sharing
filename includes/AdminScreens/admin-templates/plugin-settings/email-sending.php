<?php

namespace BetterSharingWP;

$email_config = include BETTER_SHARING_PATH . 'includes/config/emails.php';
?>
<div class="bswp__container">	
	<div class="bswp__email-sending-group"> 
		<div class="bswp__form-group flex-start">	
			<h4><?php esc_html_e( 'Email Branding', 'better-sharing-wp' ); ?></h4>
			<div class="bswp__website-name">	
				<?php
				if ( $this->option_data->get( 'websiteName' ) ) :
					$website_name = $this->option_data->get( 'websiteName' );
				else :
					$website_name = null;
				endif;
				?>
				<label for="bswp-website-name" class="">
					<?php esc_html_e( 'Enter your website or company name:', 'better-sharing-wp' ); ?>
				</label>
				<input 
					type="text" 
					name="__bswp_website_name" 
					id="bswp-website-name" 
					value="<?php echo esc_attr( $website_name ); ?>" />
					<p><?php esc_html_e( 'Will use this value as a suffix on the sender\'s name in the email "From" field, e.g. "John Doe via Your Company Name".', 'better-sharing-wp' ); ?></p>
			</div>  
		</div>
	</div>

	<div class="bswp__email-sending-group bswp-emails-replyto">
		<div class="bswp__form-group flex-start">
			<h4><?php esc_html_e( 'Email Reply To Settings', 'better-sharing-wp' ); ?></h4>
			<div class="email-sending-input">
			<?php
				// set default values.
				$replyto_is_enabled = "checked=true";
				$replyto_is_disabled = "";
				$replyto_is_custom = "";
				$readonly = "readonly=true"; 
				$custom_email_address = "";
				if ( $this->option_data->get( 'emailsReplyto' ) ) :
					$replyto_option = json_decode( $this->option_data->get( 'emailsReplyto' ), true );  
					if ( isset( $replyto_option['bswp'] ) ) :
						if ( "0" === $replyto_option['bswp']) : 
							$replyto_is_enabled = "";
							$replyto_is_disabled = "checked=true"; 
						elseif ( "2" === $replyto_option['bswp']) :
							$replyto_is_enabled = ""; 
							$replyto_is_custom = "checked=true";
							$readonly = ""; 
							if ( isset( $replyto_option['custom_address'] ) ) : 
								$custom_email_address = trim( $replyto_option['custom_address'] );
							endif; 
						endif;
					endif;
				endif;
				?>
				<div class="checkbox-group">
					<input type="radio" id="bswp-emails-replyto" name="__bswp_emails_replyto[bswp]" value="1" <?php echo $replyto_is_enabled ?> >
					<label for="bswp-emails-replyto">
						<?php esc_html_e( 'Let Better Sharing optimize', 'better-sharing-wp' ); ?>
						<b>Reply То</b>
						<?php esc_html_e( 'address', 'better-sharing-wp' ); ?>
					</label>
				</div>
				<div class="checkbox-group">
					<input type="radio" id="bswp-emails-replyto-disabled" name="__bswp_emails_replyto[bswp]" value="0" <?php echo $replyto_is_disabled ?> >
					<label for="bswp-emails-replyto-disabled">
						<?php esc_html_e( 'Never specify a', 'better-sharing-wp' ); ?>
						<b>Reply То</b>
						<?php esc_html_e( 'address', 'better-sharing-wp' ); ?>
					</label>
				</div>
				<div class="checkbox-group">
					<input type="radio" id="bswp-emails-custom-replyto" name="__bswp_emails_replyto[bswp]" value="2" <?php echo $replyto_is_custom; ?>>
					<label for="bswp-emails-custom-replyto">
						<?php esc_html_e( 'Always use', 'better-sharing-wp' ); ?>
						<input type="email" id="bswp-emails-custom-replyto-address" 
							name="__bswp_emails_replyto[custom_address]" 
							value="<?php esc_attr_e( $custom_email_address )?>" 
							<?php echo $readonly; ?>>
						<?php esc_html_e( 'as a', 'better-sharing-wp' ); ?>
						<b>Reply То</b>
						<?php esc_html_e( 'address', 'better-sharing-wp' ); ?>
					</label> 
				</div>
			</div>
		</div>
	</div>

	<div class="bswp__email-sending-group"> 
		<div class="bswp__form-group flex-start">	
		<h4><?php esc_html_e( 'Rate Limiting', 'better-sharing-wp' ); ?></h4>
			<div class="email-sending-input">
				<?php
					$checked    = '';
					$readonly 	= '';

					// set default values.
				if ( ! $this->option_data->get( 'emailsLimit' ) ) :
					$checked        = 'checked=true';
					$emails_max_num = $email_config['default_emails_limit'];

					else :
						$emails_limit_option = json_decode( $this->option_data->get( 'emailsLimit' ), true );

						if ( $emails_limit_option['limit_emails'] ) :

							// emails have been limited by the plugin settings.
							$checked        = 'checked=true';
							$emails_max_num = $emails_limit_option['emails_num'];
						else :

							// emails have not been limited by the plugin settings -
							// hide email num input on page load.
							$readonly = 'readonly=true';
						endif;

					endif;
					?>
							
				<div class="checkbox-group">
					<input 
						type="checkbox" 
						name="__bswp_limit_emails" 
						id="bswp-limit-emails" <?php echo esc_attr( $checked ); ?> 
						value="1" />
					<label for="bswp-limit-emails" class="checkbox-label">
					<?php esc_html_e( 'Enable rate limiting to prevent too many emails from being sent at once.', 'better-sharing-wp' ); ?>	</label>
				</div>	
				<div class="limit-emails">
					<input 
						type="text" 
						<?php echo esc_attr( $readonly ); ?> 
						name="__bswp_emails_num" 
						id="bswp-limit-emails-num" 
						value="<?php echo @esc_attr( $emails_max_num ); ?>" 
						/>			 
						<label for="bswp-limit-emails-num"><?php esc_html_e( 'is the maximum number of emails that a user can send at a time.', 'better-sharing-wp' ); ?></label>
				</div>	 
			</div> 
		</div>
	</div> 

	<div class="bswp__email-sending-group"> 
		<?php
		// default setting values.
		$spam_detection = 'checked=true';
		$hide_err_msg   = 'checked=true';
		$spam_regex  		= $email_config['default_spam_regex'];
		$readonly 			= '';
		$disabled				= '';
		// settings saved values.
		if ( ! empty( $this->option_data->get( 'spamDetection' ) ) ) :
			
			$spam_detection_options = json_decode( $this->option_data->get( 'spamDetection' ), true );

			if ( $spam_detection_options['enable_spam_detection'] === 0 ) : 
				$spam_detection = '';
				$readonly 			= 'readonly=true';
				$disabled				= 'disabled';
			endif;

			if ( trim($spam_detection_options['spam_regex']) != '' ) :
				$spam_regex     = $spam_detection_options['spam_regex'];
			endif;
			
			if ( empty( $spam_detection_options['hide_err_msg'] ) ) :
				$hide_err_msg = '';
			endif; 

		endif;
		?>
					
		<div class="bswp__form-group flex-start">	
			<h4><?php esc_html_e( 'Spam Detection in Custom Messages', 'better-sharing-wp' ); ?></h4>
			<div class="bswp__spam-detection">		
				<div class="checkbox-group">
					<input 
						type="checkbox" 
						name="__bswp_enable_spam_detection" 
						id="bswp-spam-detection"
						<?php echo esc_attr( $spam_detection ); ?> 
						value="1" />
						<label for="bswp-spam-detection" class="checkbox-label">
							<?php esc_html_e( 'Enable spam detection to prevent users from sending forbidden custom messages.', 'better-sharing-wp' ); ?>
						</label>
				</div>		
				
				<p><?php esc_html_e( 'Reject all custom messages that match this RegEx:', 'better-sharing-wp' ); ?></p>
				<textarea
					name = "__bswp_spam_regex" 
					id = "bswp_spam_regex"
					<?php echo esc_attr( $readonly ); ?>
					row = "1"
				><?php echo trim( $spam_regex ); ?></textarea>
				<div class="inner-checkbox-group checkbox-group">
					<input 
						type="checkbox"
						<?php echo esc_attr( $disabled ); ?> 
						name="__bswp_hide_spam_err_msg" 
						id="bswp-spam-err-msg"
						<?php echo esc_attr( $hide_err_msg ); ?> 
						value="1" />		 
					<label for="bswp-spam-err-msg" class="checkbox-label">
						<?php esc_html_e( 'Do not display an error message to the user when the message is rejected for this reason.', 'better-sharing-wp' ); ?>
					</label> 
				</div>
			</div>
		</div>
	</div>
</div>