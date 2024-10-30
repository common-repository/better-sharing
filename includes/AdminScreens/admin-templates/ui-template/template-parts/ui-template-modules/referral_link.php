<div class="bswp-sortable">
    <div class="referral-link bswp-ui-template-module compact-view-container" data-module="bswp-referral-link">  
        <div class="flex items-center">           
            <a href="#" class="btn btn-primary btn--primary">
                <?php echo $default_settings['compact_view_icons']['share']; ?>
            </a>
            <span>
                <input 
                    type="text" 
                    class="bswp-referral-link"
                    value="<?php echo esc_url($link_value) ?>" 
                    placeholder="<?php echo esc_url($link_placeholder) ?>"
                    readonly="true"
                    autocomplete="off"
                    >
            </span>
            <?php
            if( $bswp_ui_template_settings['url_to_share']['link_type'] == "custom_url" ) : 
                echo "<p class='text-center'>In compact view, clicking the link icon will automatically copy the specified custom URL to the user's clipboard.</p>";
            else :
                echo "<p class='text-center'>In compact view, clicking the link icon will automatically copy the current page URL to the user's clipboard.</p>";
            endif;    
            ?>
        </div>
   </div>
   <div class="referral-link bswp-ui-template-module full-view-container" data-module="bswp-referral-link">            
        <h4 class="bswp-title">
            <?php 
			$referral_link_title = $default_settings['referral_link']['title'];

				if( !empty( $bswp_ui_template_settings ) ) :

                        if( !empty( trim( $bswp_ui_template_settings['referral_link']['title'] ) ) ) :

						$referral_link_title = trim( $bswp_ui_template_settings['referral_link']['title'] ); 
					endif;
				endif;
			?>
        <?php echo  esc_html($referral_link_title); ?> 
        </h4>
        <p class="bswp-sub-title">
           <?php  

            $referral_link_subtitle =  $default_settings['referral_link']['subtitle'];

		    if( !empty( $bswp_ui_template_settings ) ) :  

		        if( !empty( trim( $bswp_ui_template_settings['referral_link']['subtitle'] ) ) ) :  

		            $referral_link_subtitle =  trim( $bswp_ui_template_settings['referral_link']['subtitle'] );
		        endif;		
		    endif;		
		?>       
        <?php echo  esc_html($referral_link_subtitle); ?> 
        </p>
        <div class="flex items-center">
            <div class="flex-grow">
                <input 
                    type="text" 
                    id="referral-link" 
                    class="form-control border rounded-r-none bswp-referral-link" 
                    value="<?php echo $link_value ?>" 
                    placeholder="<?php echo $link_placeholder ?>"
                    readonly="true"
                    autocomplete="off"
                    >
            </div>
            <div>
                <a 
                    href="javascript:void(0)" 
                    class="btn btn-secondary btn--secondary border rounded-l-none" 
                    id="referral-btn-copy" data-clipboard-target="#referral-link" 
                    data-text-default="Copy" 
                    data-text-done="Copied!">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                       <path fill="none" d="M0 0h24v24H0z"></path>
                       <path d="M7 6V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-3v3c0 .552-.45 1-1.007 1H4.007A1.001 1.001 0 0 1 3 21l.003-14c0-.552.45-1 1.007-1H7zM5.003 8L5 20h10V8H5.003zM9 6h8v10h2V4H9v2z"></path>
                   </svg>
                   <span><?php _e('Copy', 'better-sharing-wp'); ?></span>
               </a>
           </div>
       </div>
   </div>
</div>