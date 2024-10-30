<?php
    // shared template modules variables 
		//new ui default settings
    $is_full_view               = false;//default value
    $is_full_view_hidden        = 'view-style-hidden';
    $is_compact_view_hidden     = '';
		// if is a new ui template
    if( !empty( $bswp_ui_template_settings ) ) :
        //there is view_style setting
        if( isset($bswp_ui_template_settings['view_style'] ) ) :
					//full or compact is selected by settings
					if( $bswp_ui_template_settings['view_style'] == "full" ) :
            $is_full_view       = true;
            $is_full_view_hidden        = '';
            $is_compact_view_hidden     = 'view-style-hidden';
					endif;
				//old templates handle - full is selected by default
        else :
					$is_full_view       = true;
					$is_full_view_hidden        = '';
					$is_compact_view_hidden     = 'view-style-hidden';
				endif;
    endif;
    
    $link_value = '';
    if( !empty( $bswp_ui_template_settings ) ) :
			 
        if( $bswp_ui_template_settings['url_to_share']['link_type'] == "custom_url" ) : 
        $link_value = trim( esc_url( $bswp_ui_template_settings['url_to_share']['custom_link'] ) );
        endif;
    endif; 

    $link_placeholder= get_site_url() . $default_settings['url_to_share']['default_page_endpoint'];
?>
<input type="radio" name="tabs" id="bswp-ui-template-module" checked="true" class="tab-label">
<label class="bswp-tab-label" for="bswp-ui-template-module"><span><?php _e('Main', 'better-sharing-wp'); ?></span><span></span></label>
<div class="tab <?php echo !$is_full_view ? 'compact-view' : '' ?>" data-tab="bswp-ui-template">
	<div class="bswp-module-settings">
		<h3><?php _e('Style', 'better-sharing-wp'); ?></h3>
		<div class="bswp__form-group">
			<label 
				for="bswp-compact-view"
				class="bswp-compact-view bswp-style-view">				
				<input 
					type="radio" 
					name="bswp_ui_template_settings[view_style]" 
					id="bswp-compact-view" 							
					value="compact"
					<?php echo !$is_full_view ? "checked='true'" : '' ?>
					autocomplete="off"
				/>
				<?php _e('Compact', 'better-sharing-wp'); ?>
			</label>
			<label 
				for="bswp-full-view" 
				class="bswp-full-view bswp-style-view">					
				<input 
					type="radio" 
					name="bswp_ui_template_settings[view_style]" 
					id="bswp-full-view"  					
					value="full"
					<?php echo $is_full_view ? "checked='true'" : '' ?>
					autocomplete="off"
				/>
				<?php _e('Inline', 'better-sharing-wp'); ?>
			</label>	
		</div>	
	</div>  
	<div class="bswp-module-settings">
		<h3><?php _e('URL to share', 'better-sharing-wp'); ?></h3>
		<?php 
		//has url to share type by default
		$is_type_post_url = "checked='true'";
		$is_type_custom_link = "";
		$is_hidden_custom_referral_link ="bswp-hidden-referral-link-control";

		if( !empty( $bswp_ui_template_settings ) ) :
			//hidden/selectd by settings
			if( $bswp_ui_template_settings['url_to_share']['link_type'] == "custom_url" ) : 
				$is_type_post_url = "";
				$is_type_custom_link = "checked='true'";
				$is_hidden_custom_referral_link ="";
			endif;
		endif;
		?>
		<div class="bswp__form-group">
			<label 
				for="bswp-page-post-url" 
				class="bswp-page-url bswp-share-url">					
				<input 
					type="radio" 
					name="bswp_ui_template_settings[url_to_share][link_type]" 
					id="bswp-post-url" 							
					value="page_url" 
					<?php echo esc_attr($is_type_post_url); ?>
					autocomplete="off"
				/>
				<?php _e('Page/Post url', 'better-sharing-wp'); ?>
			</label>	
			<label 
				for="bswp-custom-url"
				class="bswp-custom-url bswp-share-url">				
				<input 
					type="radio" 
					name="bswp_ui_template_settings[url_to_share][link_type]" 
					id="bswp-custom-url" 							
					value="custom_url" 
					<?php echo esc_attr($is_type_custom_link); ?>
					autocomplete="off"
				/>
				<?php _e('Custom url', 'better-sharing-wp'); ?>
			</label>	
			<div class="break"></div>
			<div class="bswp__input-group custom-link-input-wrapper">
				<input 
					type="text" 
					placeholder="https://domain.com/custom-path/"
					name="bswp_ui_template_settings[url_to_share][custom_link]" 
					id="bswp-custom-url-content"
					class="bswp-text-update <?php echo esc_attr($is_hidden_custom_referral_link); ?>"
					data-update="value"	
					data-target="bswp-referral-link"		
					autocomplete="off"			
					value="<?php  
					if( !empty( $bswp_ui_template_settings ) ) :
						echo trim( esc_url_raw( $bswp_ui_template_settings['url_to_share']['custom_link'] ) ); 
					endif; ?>"        
				/>
			</div>	
		</div>	
		<div class='bswp-meta-itemprop'>
			<?php  _e('Override URL by using ', 'better-sharing-wp');?>
			<span><?php  _e('&lt;meta itemprop="bswp_referral_link"  content="your url here" /&gt;,', 'better-sharing-wp');?></span>
			<?php  _e('for more information read the ', 'better-sharing-wp');?>
			<a href="https://www.cloudsponge.com/developer/better-sharing-wordpress/template-variables/" target="_blank"><?php _e('documentation', 'better-sharing-wp'); ?></a>
		</div>
	</div>    
	<div class="bswp-main-preview-container" >   
		<h3><?php _e('Preview', 'better-sharing-wp'); ?></h3>   
    <?php  
        include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/view-style/ui-template-full.php';
        include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/view-style/ui-template-compact.php'; 
    ?>
	</div>
	<input 
		type="hidden" 
		name="bswp_ui_template_settings[social_share][order]" 
		value="<?php echo !empty( $bswp_ui_template_settings ) ?  esc_attr( $bswp_ui_template_settings['social_share']['order'] ) : 1;  ?>" 
		id="bswp-social-share-order"
		autocomplete="off"
	>
	<input 
		type="hidden" 
		name="bswp_ui_template_settings[social_share][enabled]" 
		value="<?php echo !empty( $bswp_ui_template_settings ) ? esc_attr( $bswp_ui_template_settings['social_share']['enabled'] ) : 1;  ?>" 
		id="bswp-social-share-enabled"
		autocomplete="off"
	>
	<input 
		type="hidden" 
		name="bswp_ui_template_settings[referral_link][order]" 
		value="<?php echo !empty( $bswp_ui_template_settings ) ? esc_attr( $bswp_ui_template_settings['referral_link']['order'] ) : 2;  ?>"  
		id="bswp-referral-link-order"
		autocomplete="off"
	>
	<input 
		type="hidden" 
		name="bswp_ui_template_settings[referral_link][enabled]" 
		value="<?php echo !empty( $bswp_ui_template_settings ) ? esc_attr( $bswp_ui_template_settings['referral_link']['enabled'] ) : 1;  ?>"  
		id="bswp-referral-link-enabled"
		autocomplete="off"
	>
	<input 
		type="hidden" 
		name="bswp_ui_template_settings[email][order]" 
		value="<?php echo !empty( $bswp_ui_template_settings ) ? esc_attr( $bswp_ui_template_settings['email']['order'] ) : 3;  ?>"  
		id="bswp-email-order"
		autocomplete="off"
	>   
	<input 
		type="hidden" 
		name="bswp_ui_template_settings[email][enabled]"
		value="<?php echo !empty( $bswp_ui_template_settings ) ? esc_attr( $bswp_ui_template_settings['email']['enabled'] ) : 1;  ?>"  
		id="bswp-email-enabled"
		autocomplete="off"
	>   
</div>
