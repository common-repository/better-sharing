<div class="social-links bswp-ui-template-module" data-module="bswp-social-share">
    <h4 class="bswp-title"> 
        <?php  

        $social_share_title =  $default_settings['social_share']['title'];

		if( !empty( $bswp_ui_template_settings ) ) :  
		    if( !empty( trim( $bswp_ui_template_settings['social_share']['title'] ) ) ) :  

		    $social_share_title =  trim( $bswp_ui_template_settings['social_share']['title'] );		
		    endif;		
		endif;		
		?>       
        <?php echo  esc_html($social_share_title); ?> 
    </h4>
    <p class="bswp-sub-title">
        <?php  

        $social_share_subtitle =  $default_settings['social_share']['subtitle'];

		if( !empty( $bswp_ui_template_settings ) ) :  

		    if( !empty( trim( $bswp_ui_template_settings['social_share']['subtitle'] ) ) ) :  

		    $social_share_subtitle =  trim( $bswp_ui_template_settings['social_share']['subtitle'] );		
		    endif;		
		endif;		
		?>       
        <?php echo  esc_html($social_share_subtitle); ?> 
    </p>
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
                <span class="social-net-name">
                    <?php _e('X.com', 'better-sharing-wp'); ?>
                </span>
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
                <span class="social-net-name">
                    <?php _e('Facebook', 'better-sharing-wp'); ?>
                </span>
            </a>
        </li>
    </ul>
</div>
