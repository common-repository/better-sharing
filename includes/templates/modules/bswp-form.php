<?php
/**
 * BSWP - FORM
 *
 * @package form
 */


if( $email_module_settings['enabled'] == '1' ) :

	global $post;

	$ajax = $ajax ? $ajax : false;
	$api_key = get_site_option( '_bswp_option_core_apiKey', false );
	$field_count = isset( $field_count ) ? absint( apply_filters( 'automatewoo/referrals/share_form/email_field_count', 5 ) ) : 5;

	$addon = isset( $addon ) ? sanitize_title_with_dashes( $addon ) : false;
 
	if ( !$addon ) :
    	return;
	endif;

	$action_data = array( 'addon' => $addon );

	do_action( 'bwp_form_before', $action_data );

?>
<?php
if ( $is_full_view ) : 
	$email_extra_classes = 'order-' . $email_module_settings['order'] . ' bswp-block-module';
else : 
	$email_extra_classes = 'order-3';
	?>
	<div class="break"></div>
	<?php
endif;
?>
<div class="email <?php echo $email_extra_classes ?>">
	<?php if (!$is_full_view) : ?>
	<a href="#" 
		class="btn btn-primary btn--primary bswp-email-btn trigger-email-btn" 
	>
	<svg width="20" height="13" viewBox="0 0 20 13" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M19.0711 0.0279541H0.928906C0.902773 0.0279541 0.87707 0.0297331 0.851484 0.0318215L1.72246 0.612938C1.77367 0.632507 1.82359 0.657335 1.87098 0.688971L10 6.11273L18.129 0.688971C18.1291 0.688893 18.1291 0.688893 18.1292 0.688855L19.1163 0.0302359C19.1012 0.0295011 19.0864 0.0279541 19.0711 0.0279541Z" fill="white"/>
		<path d="M15.8129 3.00871L10.4152 6.61011C10.2898 6.69388 10.1448 6.73572 9.99996 6.73572C9.85508 6.73572 9.71016 6.69384 9.58469 6.61011L7.84465 5.44915C7.79262 5.42947 7.74203 5.40371 7.69391 5.37161L0 0.23806V12.0522C0 12.5601 0.415898 12.9719 0.928906 12.9719H19.0711C19.5841 12.9719 20 12.5601 20 12.0522V0.215088L15.8129 3.00871Z" fill="white"/>
	</svg>
	</a>
	<div class="bswp-email-modal bswp-email-modal-hidden">
		<div class="bswp-block-module">
			<span>
				<a href="#" id="bswp-close-email-modal">
					<svg width="24px" height="24px" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect width="48" height="48" fill="white" fill-opacity="0.01"/>
						<path d="M14 14L34 34" stroke="black" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M14 34L34 14" stroke="black" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
					</svg> 
				</a>
			</span>
	<?php endif; ?>
	<?php 
		global $wp; 
		$current_url 	=  trailingslashit(home_url( $wp->request )); 
		$query_str 		= $_SERVER['QUERY_STRING'];
		if ( ! empty( $query_str ) ) :
			$referral_link = "$current_url/?$query_str";
		endif; 
	?>
			<form <?php echo ! $ajax ? 'action="' . esc_url( $current_url ) . '"' : ''; ?> accept-charset="UTF-8" method="post">
			
				<?php do_action( 'bwp_form_inner_before', $action_data ); ?>
			
				<input 
					type="hidden" 
					name="bswp_form_addon" 
					value="<?php echo esc_attr( $addon ); ?>" />
				<input 
					class="email-template" 
					type="hidden" 
					name="template" 
					value="<?php echo esc_attr( $email_module_settings['email_template'] ); ?>">
				<input 
					class="bswp-user-first-name" 
					type="hidden" 
					name="bswp_user_first_name" 
					value="<?php echo isset( $user['first_name'] ) ? esc_attr( $user['first_name'] ) :  ''; ?>">
				<input 
					class="bswp-user-last-name" 
					type="hidden" 
					name="bswp_user_last_name" 
					value="<?php echo isset( $user['last_name'] ) ? esc_attr( $user['last_name'] ) :  ''; ?>">
				<input 
					class="bswp-user-email" 
					type="hidden" 
					name="bswp_user_email" 
					value="<?php echo isset( $user['email'] ) ? esc_attr( $user['email'] ) :  ''; ?>">
				<input 
					class="bswp-emails-limit" 
					type="hidden" 
					name="bswp_emails_limit" 
					value="<?php echo ( $emails_limit ) ? esc_attr( $emails_limit ) :  ''; ?>">
				<input 
					class="bswp-ref-link" 
					type="hidden" 
					name="bswp_ref_link" 
					value="<?php echo ( $referral_link ) ? esc_attr( $referral_link ) :  ''; ?>">

				<h4 class="h4"><?php _e( esc_html($email_module_settings['title'] ), 'better-sharing-wp' ); ?></h3>
				<p class="sub-title"><?php _e( esc_html($email_module_settings['subtitle'] ), 'better-sharing-wp' ); ?></p>		

				<div class="flex items-center">
					<div class="flex-grow">
						<input 
							type="text"  
							name="bswp-share-email-input"
							placeholder="<?php _e( esc_attr( $email_module_settings['emails_input_placeholder'] ), 'better-sharing-wp' ); ?> (required)"
							class="form-control border rounded-r-none bswp-share-email-input"
							required
						>
					</div>

					<?php if ( $api_key ) : ?>
					<div>
						<a href="javascript:void(0)" class="add-from-address-book-init btn btn-secondary btn--secondary border rounded-l-none">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
													<path fill="none" d="M0 0h24v24H0z"></path>
													<path d="M3 2h16.005C20.107 2 21 2.898 21 3.99v16.02c0 1.099-.893 1.99-1.995 1.99H3V2zm4 2H5v16h2V4zm2 16h10V4H9v16zm2-4a3 3 0 0 1 6 0h-6zm3-4a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm8-6h2v4h-2V6zm0 6h2v4h-2v-4z"></path>
											</svg>
							<span><?php esc_attr_e( 'Add From Contacts', 'better-sharing-wp' ); ?></span>
						</a>
					</div>
					<?php endif; ?>
				</div>
				<?php if ( isset( $email_preview['has_template_vars'] ) ) : ?>
					<?php if ( in_array( 'sender_custom_message', $email_preview['has_template_vars'] ) ) : ?>			
				<div class="bswp-email-custom-message">
					<textarea 
						name="bswp-share-email-content"
						placeholder="<?php _e( esc_attr(  $email_module_settings['message_placeholder']), 'better-sharing-wp' ) ?> (required)"
						class="form-control border bswp-share-email-content"
						required></textarea>
				</div>
					<?php endif; ?>
				<?php endif; ?> 
				<?php if ( isset( $email_module_settings['email_preview'] ) &&  $email_module_settings['email_preview'] === 'on') : ?>
				<div 
					class="bswp-email-preview-container" 
					data-email-preview="<?php echo esc_attr(json_encode( $email_preview )); ?>">
				</div>
				<?php endif; ?>
				<div id="referral-emails-wrapper" data-max="<?php echo esc_attr( $field_count ); ?>"></div>

				<?php do_action( 'bwp_form_inner_after', $action_data ); ?>

				<?php if ( $ajax ) : ?>
				<a href="javascript:void(0)" class="bswp-submit btn btn-primary btn--primary">
					<?php _e( 'Send', 'better-sharing-wp' ); ?>
				</a>
				<?php else: ?>
				<button type="submit" class="bswp-submit btn btn-primary btn--primary"><?php _e( esc_html( $email_module_settings['send_btn_text'] ), 'better-sharing-wp' ); ?></button>
				<div id='coreblock-email-sent-msg'></div>
				<?php endif; ?>

			</form>		
		
		<?php if (!$is_full_view) : ?>
		</div>
	</div>
	<?php endif; ?> 

	<div class="bswp-email-success-modal bswp-email-success-modal-hidden"> 
		<div class="success-screen"> 
			<?php 
				$success_message = $this->bswp_ui_template_default_settings['email']['success_screen_msg'];
				$cta_label = $this->bswp_ui_template_default_settings['email']['success_screen_cta_label'];
				if ( isset( $email_module_settings['success_screen_msg'] ) ) :
					$success_message = $email_module_settings['success_screen_msg'];
				endif;
				if ( isset( $email_module_settings['success_screen_cta_label'] ) ) :
					$cta_label = $email_module_settings['success_screen_cta_label'];
				endif;
			?>
			<div class="bswp-success-message-hidden"><?php
					_e( wp_kses_post( $success_message ), 'better-sharing-wp' );
				?></div>
			<div class="bswp-success-message"></div>
			<button class="btn btn-primary btn--primary success-continue">
				<span class="bswp-success-cta-label"><?php _e( esc_html( $cta_label ), 'better-sharing-wp' ); ?></span>
			</button>
		</div>
	</div> 

</div>
	
<?php

	do_action( 'bwp_form_after', $action_data );
endif;
