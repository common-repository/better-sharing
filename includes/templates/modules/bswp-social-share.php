<?php 

if( $social_share_module_settings['enabled'] == '1' ) :
?>
<?php 
	if ($is_full_view) : 
		$social_share_extra_classes = 'order-' . $social_share_module_settings['order'] . ' bswp-block-module';
	else :
		$social_share_extra_classes = 'order-1'; 
	endif;
?>
<div class="social-links <?php echo esc_attr($social_share_extra_classes); ?>">
	<?php if ($is_full_view) : ?> 
		<h4 class="h4"><?php _e( esc_html( $social_share_module_settings['title'] ), 'better-sharing-wp' ) ?></h3>
		<p class="sub-title"><?php _e( esc_html( $social_share_module_settings['subtitle'] ), 'better-sharing-wp' ) ?></p> 
	<?php endif; ?> 
		<ul class="flex items-center">
		<?php	
      if( isset( $social_share_module_settings['twitter_enabled'] ) ) :
        	
				if( $social_share_module_settings['twitter_enabled'] == '1') :

            $intent_url = $this->bswp_ui_template_default_settings['social_share']['twitter_intentUrl'] . $social_share_module_settings['twitter_msg'];           
        ?>
      <li>
				<a 
					href="<?php echo esc_url( $this->create_intent_url( $referral_link, $intent_url ) ) ?>"
					target="_blank" 
					ref="noopener noreferrer"
					class="btn btn-primary btn--primary bswp-twitter-btn"
				>
					<?php echo $this->bswp_ui_template_default_settings['social_share']['twitter_icon']; // WPCS: XSS ok. ?>
					<?php if ($is_full_view) : ?> 
						<?php _e('X.com', 'better-sharing-wp'); ?>
					<?php endif ?> 
			  </a>
		  </li>				
			
			<?php endif; ?> 
		<?php endif; //is TW enabled end?>
		<?php	
			if( isset($social_share_module_settings['fb_enabled'] ) ) :
        	
				if( $social_share_module_settings['fb_enabled'] == '1') :

            	$intent_url = $this->bswp_ui_template_default_settings['social_share']['fb_intentUrl'];
        ?>
      <li>
				<a 
					href="<?php echo esc_url( $this->create_intent_url( $referral_link, $intent_url ) ) ?>"
					target="_blank" 
					ref="noopener noreferrer"
					class="btn btn-primary btn--primary bswp-facebook-btn"
				>
        		<?php echo $this->bswp_ui_template_default_settings['social_share']['fb_icon'];	// WPCS: XSS ok. ?>
					<?php if ($is_full_view) : ?>
	
            <?php _e('Facebook', 'better-sharing-wp'); ?>
					<?php endif; ?>

			    </a>
		  </li>				
			<?php endif; ?>
		<?php endif; ?>
	  </ul>
	</div> 
<?php endif; ?>