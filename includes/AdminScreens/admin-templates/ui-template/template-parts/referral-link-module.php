<input type="radio" name="tabs" id="bswp-referral-link-module" class="tab-label">
<label class="bswp-tab-label" for="bswp-referral-link-module"><span><?php _e('Referral Link', 'better-sharing-wp'); ?></span><span></span></label>
<div class="tab <?php echo !$is_full_view ? 'compact-view' : '' ?>" data-tab="bswp-referral-link">
	 <div class="bswp-module-settings">
        <div class="bswp__form-group">
			<?php
				//is enabled module by settings
				$checked = 'checked="true"';

				if( !empty( $bswp_ui_template_settings ) ){

					if( !$bswp_ui_template_settings['referral_link']['enabled'] ){
						$checked = '';
					}
				}
			?>
			<label class="switch bswp-module-enable">
  				<input type="checkbox" <?php echo esc_attr($checked); ?> data-module="bswp-referral-link" autocomplete="off">
  				<span class="slider round"></span>
			</label>			
			<span class="bswp-toggle-label"><?php _e('Enabled', 'better-sharing-wp'); ?></span>
	  	</div>
	</div>
	<?php 
		$bswp_ui_template_module_hidden = '';

		if( !empty( $bswp_ui_template_settings ) ) : 
			
			if( !( $bswp_ui_template_settings['referral_link']['enabled'] ) ) : 

				$bswp_ui_template_module_hidden = 'bswp-ui-template-module-hidden'; 
			endif; 
		endif; 
	?>
	<div class="<?php echo esc_attr($bswp_ui_template_module_hidden); ?>">
    	<div class="wp-block-cgb-block-ea-better-sharing">        
    	    <?php 
    	        include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/ui-template-modules/referral_link.php';
    		?>
    	</div>    
    	<div class="bswp-module-settings">
			<div class="bswp__form-group bswp-title">
				<label for="bswp-referral-link-title"><?php _e('Title', 'better-sharing-wp'); ?></label>
				<div class="break"></div>
				<div class="bswp__input-group">
				<?php 
					$referral_link_title = $default_settings['referral_link']['title'];

					if( !empty( $bswp_ui_template_settings ) ) :

						if( !empty( trim( esc_attr( $bswp_ui_template_settings['referral_link']['title'] ) ) ) ) :

							$referral_link_title = trim( esc_attr( $bswp_ui_template_settings['referral_link']['title'] ) ); 
						endif;
					endif;
				?>
					<input 
						type="text" 
						id="bswp-referral-link-title" 
						class="bswp-text-update"
						data-update="text"	
						data-target="bswp-title"
						placeholder="Referral link heading"	
						name="bswp_ui_template_settings[referral_link][title]" 
						value="<?php echo esc_attr($referral_link_title); ?>"    
						autocomplete="off"   
						/>
				</div>
	  		</div>
        	<div class="bswp__form-group bswp-subtitle">
				<label for="bswp-referral-link-subtitle"><?php _e('Subtitle', 'better-sharing-wp'); ?></label>
				<div class="break"></div>
				<div class="bswp__input-group">
				<?php 
					$referral_link_subtitle = $default_settings['referral_link']['subtitle'];

					if( !empty( $bswp_ui_template_settings ) ) :

						if( !empty( trim( esc_attr( $bswp_ui_template_settings['referral_link']['subtitle'] ) ) ) ) :

							$referral_link_subtitle = trim( esc_attr( $bswp_ui_template_settings['referral_link']['subtitle'] ) ); 
						endif;
					endif;
				?>
					<input 
						type="text" 
						id="bswp-referral-link-subtitle" 	
						class="bswp-text-update"
						data-update="text"	
						data-target="bswp-sub-title"
						placeholder="Sub title text ..."
						name="bswp_ui_template_settings[referral_link][subtitle]" 										
						value="<?php echo esc_attr($referral_link_subtitle); ?>"   
						autocomplete="off"     
					/>
				</div>
	  		</div>			 
		</div>       
    </div>
</div>