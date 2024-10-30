<input type="radio" name="tabs" id="bswp-social-share-module" class="tab-label" />
<label class="bswp-tab-label" for="bswp-social-share-module"><span><?php _e('Social Networks', 'better-sharing-wp'); ?></span><span></span></label>
<div class="tab <?php echo !$is_full_view ? 'compact-view' : '' ?>" data-tab="bswp-social-share">
	<div class="bswp-module-settings">
    <div class="bswp__form-group">
			<?php
				$checked = 'checked="true"';

				if( !empty( $bswp_ui_template_settings ) ){
					
					if( !$bswp_ui_template_settings['social_share']['enabled'] ){

						$checked = '';
					}
				}
			?>
			<label class="switch bswp-module-enable">
  				<input type="checkbox" <?php echo esc_attr($checked); ?> data-module="bswp-social-share" autocomplete="off">
  				<span class="slider round"></span>
			</label>			
			<span class="bswp-toggle-label"><?php _e('Enabled', 'better-sharing-wp'); ?></span>
	  </div>
	</div>
	<?php 
		$bswp_ui_template_module_hidden = '';

		if( !empty( $bswp_ui_template_settings ) ) : 
			
			if( !( $bswp_ui_template_settings['social_share']['enabled'] ) ) : 

				$bswp_ui_template_module_hidden = 'bswp-ui-template-module-hidden'; 
			endif; 
		endif; 
	?>
	<div class="<?php echo esc_attr($bswp_ui_template_module_hidden); ?>">
    <div class="wp-block-cgb-block-ea-better-sharing">
			<?php 
					include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/ui-template-modules/social_share.php'; 
			?>
    </div>
    <div class="bswp-module-settings">        
      <div class="bswp__form-group bswp-title">
				<label for="bswp-social-net-title"><?php _e('Title', 'better-sharing-wp'); ?></label>
				<div class="break"></div>
				<div class="bswp__input-group">
					<?php 
						$social_share_title = $default_settings['social_share']['title'];

						if( !empty( $bswp_ui_template_settings ) ) : 
						
							if( !empty( trim( esc_attr( $bswp_ui_template_settings['social_share']['title'] ) ) ) ) : 

								$social_share_title = trim( esc_attr( $bswp_ui_template_settings['social_share']['title'] ) ); 						
							endif;
						endif;
					?>
					<input 
						type="text" 
						id="bswp-social-net-title" 	
						class="bswp-text-update"
						data-update="text"	
						data-target="bswp-title"
						placeholder="Share on Social heading"	
						name="bswp_ui_template_settings[social_share][title]" 					
						value="<?php  echo esc_attr($social_share_title); ?>"   
						autocomplete="off"
						/>
				</div>
			</div>
			<div class="bswp__form-group bswp-subtitle">
				<label for="bswp-social-net-subtitle"><?php _e('Subtitle', 'better-sharing-wp'); ?></label>
				<div class="break"></div>
				<div class="bswp__input-group">
					<?php 
						$social_share_subtitle =  $default_settings['social_share']['subtitle'];
					
						if( !empty( $bswp_ui_template_settings ) ) : 
						
							if( !empty( trim( esc_attr( $bswp_ui_template_settings['social_share']['subtitle'] ) ) ) ) : 

								$social_share_subtitle = trim( esc_attr( $bswp_ui_template_settings['social_share']['subtitle'] ) ); 
							endif;
						endif;
					?>
					<input 
						type="text" 
						id="bswp-social-net-subtitle" 
						class="bswp-text-update"
						data-update="text"	
						data-target="bswp-sub-title"
						placeholder="Sub title text ..."	
						name="bswp_ui_template_settings[social_share][subtitle]" 							
						value="<?php  echo esc_attr($social_share_subtitle); ?>" 
						autocomplete="off"      
					/>
				</div>
			</div>
			<?php 
				//twitter share enabled by default
				$twitter_share_enabled = "checked='true'";
				$hidden_twitter_share_msg = "";

				if( !empty( $bswp_ui_template_settings ) ) :
					//fb disabled by settings
					if( !isset( $bswp_ui_template_settings['social_share']['twitter_enabled'] ) ) : 

						$twitter_share_enabled = "";
						$hidden_twitter_share_msg = "bswp-hidden-social-share-control";	
					endif;
				endif;
			?>
			<div class="bswp__form-group bswp-social-networks-toggle">
				<label class="switch">
					<input 
						type="checkbox" 
						name="bswp_ui_template_settings[social_share][twitter_enabled]"  
						value="1"
						data-social-share="twitter"
						<?php echo esc_attr($twitter_share_enabled); ?>
					autocomplete="off"
					>
					<span class="slider round"></span>
				</label>			
				<span class="bswp-toggle-label"><?php _e('X.com', 'better-sharing-wp'); ?></span>
			</div>
		
      <div class="bswp__form-group <?php echo esc_attr($hidden_twitter_share_msg); ?>">
				<label for="bswp-message-to-share"><?php _e('Fallback message to share', 'better-sharing-wp'); ?></label>
				<div class="break"></div>
				<div class="bswp__input-group">
					<?php 
						$twitter_msg =  $default_settings['social_share']['twitter_msg'];

						if( !empty( $bswp_ui_template_settings ) ) :

							if( !empty( trim( esc_attr( $bswp_ui_template_settings['social_share']['twitter_msg'] ) ) ) ) :

								$twitter_msg = trim( esc_attr( $bswp_ui_template_settings['social_share']['twitter_msg'] ) ); 					
							endif;
						endif;

					?>
					<input 
						type="text" 
						id="bswp-message-to-share" 
						name="bswp_ui_template_settings[social_share][twitter_msg]" 
						value="<?php  echo esc_attr($twitter_msg); ?>"      	
						data-social-share="twitter-msg"	
						autocomplete="off"					
					/>
				</div> 
	  	</div>
			<div class='bswp-meta-itemprop'>
					<?php  _e('Override message by using ', 'better-sharing-wp');?>
					<span><?php  _e('&lt;meta itemprop="bswp_x_message"  content="your message here" /&gt;,', 'better-sharing-wp');?></span>
					<?php  _e('for more information read the ', 'better-sharing-wp');?>
					<a href="https://www.cloudsponge.com/developer/better-sharing-wordpress/template-variables/" target="_blank"><?php _e('documentation', 'better-sharing-wp'); ?></a>
				</div>
			<?php 
				//fb share enabled by default
				$fb_share_enabled = "checked='true'";

				if( !empty( $bswp_ui_template_settings ) ) :
					//fb disabled by settings
					if( !isset( $bswp_ui_template_settings['social_share']['fb_enabled'] ) ) : 
						$fb_share_enabled = "";
					endif;
				endif;
			?>
      <div class="bswp__form-group bswp-social-networks-toggle">
				<label class="switch">
  					<input 
				  		type="checkbox"
				  		name="bswp_ui_template_settings[social_share][fb_enabled]"  
				  		value="1"
				  		data-social-share="fb"
				  		<?php echo esc_attr($fb_share_enabled); ?>
						autocomplete="off"
				  	>
  					<span class="slider round"></span>
				</label>			
				<span class="bswp-toggle-label"><?php _e('Facebook', 'better-sharing-wp'); ?></span>
	  	</div>
    </div> 
	</div>
</div>