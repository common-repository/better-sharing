<div class="wp-block-cgb-block-ea-better-sharing compact-view <?php echo $is_compact_view_hidden ?>">  
    <div class="flex items-center"> 
        <?php
            $bswp_ui_template_module_hidden = '';

            if( !empty( $bswp_ui_template_settings ) ) :
                
                if( $bswp_ui_template_settings['social_share']['enabled'] === '0' ) $bswp_ui_template_module_hidden = 'bswp-ui-template-module-hidden';
                
            endif;   
        ?>
        <div class="bswp-ui-template-module-container <?php echo esc_attr($bswp_ui_template_module_hidden); ?>">
            <div data-module="bswp-social-share">
                <ul class="flex items-center"> 
                    <?php
                    $hidden_social_share_control = '';
                    if( !empty( $bswp_ui_template_settings ) ) :  

                        if( !isset( $bswp_ui_template_settings['social_share']['twitter_enabled'] ) ) : 
                                
                                $hidden_social_share_control = "bswp-hidden-social-share-control";	
                        endif;
                    endif;	 
                    ?>
                    <?php   
                    $twitter_msg =  $default_settings['social_share']['twitter_msg'];

                    if( !empty( $bswp_ui_template_settings ) ) :  

                        if( !empty( trim( $bswp_ui_template_settings['social_share']['twitter_msg'] ) ) ) :  

                            $twitter_msg =  trim( $bswp_ui_template_settings['social_share']['twitter_msg'] );
                        endif;		
                    endif;		
                    ?>   
                    <li class="twitter <?php echo esc_attr($hidden_social_share_control); ?>">
                        <?php 
                        $link_to_share = $this->create_intent_url( $default_settings['social_share']['twitter_intentUrl'] );
                        ?>
                        <a href="<?php echo esc_url($link_to_share . $twitter_msg); ?>" target="_blank" ref="noopener noreferrer" class="btn btn-primary btn--primary">
                            <?php echo $default_settings['social_share']['twitter_icon']; // WPCS: XSS ok. ?>
                        </a>
                    </li>
                    <?php 
                    $hidden_social_share_control = '';

                    if( !empty( $bswp_ui_template_settings ) ) :  

                        if( !isset( $bswp_ui_template_settings['social_share']['fb_enabled'] ) ) : 
                                
                            $hidden_social_share_control = "bswp-hidden-social-share-control";	
                        endif;
                    endif;	 
                    ?>
                    <li class="fb <?php echo esc_attr($hidden_social_share_control); ?>">
                        <?php 
                            $link_to_share = $this->create_intent_url( $default_settings['social_share']['fb_intentUrl'] );
                        ?>
                        <a href="<?php echo esc_url($link_to_share); ?>" target="_blank" ref="noopener noreferrer" class="btn btn-primary btn--primary">
                            <?php echo $default_settings['social_share']['fb_icon']; // WPCS: XSS ok. ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <?php 
            $bswp_ui_template_module_hidden = '';
        
            if( !empty( $bswp_ui_template_settings ) ) :
        
                if( $bswp_ui_template_settings['referral_link']['enabled']  === '0' ) $bswp_ui_template_module_hidden = 'bswp-ui-template-module-hidden';
            endif; 
        ?>
        <div class="bswp-ui-template-module-container <?php echo esc_attr($bswp_ui_template_module_hidden); ?>">
            <div class="referral-link bswp-ui-template-module" data-module="bswp-referral-link">
                <a href="#" class="btn btn-primary btn--primary">
                    <?php echo $default_settings['compact_view_icons']['share']; ?>
                </a>
            </div>
        </div>
        <?php 
            $bswp_ui_template_module_hidden = '';
        
            if( !empty( $bswp_ui_template_settings ) ) :
        
                if( $bswp_ui_template_settings['email']['enabled']  === '0' ) $bswp_ui_template_module_hidden = 'bswp-ui-template-module-hidden';
            endif; 
        ?>
        <div class="bswp-ui-template-module-container <?php echo esc_attr($bswp_ui_template_module_hidden); ?>">
            <div class="email-trigger-container" data-module="bswp-email">
                <a href="#" class="btn btn-primary btn--primary">
                    <?php echo $default_settings['compact_view_icons']['email']; ?>
                </a>
            </div>
        </div>
    </div>
    <div><p class="text-center">Note: You can customize button colors by overwriting CSS classes: .bswp-twitter-btn, .bswp-facebook-btn, .bswp-link-btn, .bswp-email-btn. </p></div>
</div>