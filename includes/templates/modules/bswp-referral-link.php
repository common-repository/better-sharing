<?php if( $referral_link_module_settings['enabled'] == '1' ) : ?>
	<?php 
		if ($is_full_view) : 
			$referral_link_extra_classes = 'order-' .  $referral_link_module_settings['order'] . ' bswp-block-module';
		else :
			$referral_link_extra_classes = 'order-2';
		endif;
	?>
	<div class="referral-link <?php echo esc_attr($referral_link_extra_classes) ?>">
		<?php if ($is_full_view) : ?>
    	<h4 class="h4"><?php _e( esc_html( $referral_link_module_settings['title'] ), 'better-sharing-wp' ) ?></h4>
			<p class="sub-title"><?php _e( esc_html( $referral_link_module_settings['subtitle'] ), 'better-sharing-wp' ) ?></p>
		
			<div class="flex items-center">
				<div class="flex-grow">
		<?php endif; ?>
					<input 
					<?php if ($is_full_view) : ?>
						type="text"  
						class="form-control border rounded-r-none"
						readOnly
					<?php else : ?>
						type="hidden" 
					<?php endif; ?>
						value="<?php echo esc_url( $referral_link ) ?>" 
					>
			<?php if ($is_full_view) : ?>  
				</div>
				<div>
			<?php endif; ?> 
					<a href="javascript:void(0)" 
					<?php if ($is_full_view) : ?>  
						class="btn btn-secondary btn--secondary border rounded-l-none referral-btn-copy"   
					<?php else : ?>  
						class="btn btn-primary btn--primary bswp-link-btn referral-btn-copy"  
					<?php endif; ?>  
						id="referral-btn-copy"
						data-text-default="<?php _e( 'Copy', 'better-sharing-wp'  ) ?>" 
						data-text-done="<?php _e( 'Copied!', 'better-sharing-wp'  ) ?>"
					>	
					<?php if ($is_full_view) : ?>  
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
							<path fill="none" d="M0 0h24v24H0z"></path>
							<path d="M7 6V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-3v3c0 .552-.45 1-1.007 1H4.007A1.001 1.001 0 0 1 3 21l.003-14c0-.552.45-1 1.007-1H7zM5.003 8L5 20h10V8H5.003zM9 6h8v10h2V4H9v2z"></path>
						</svg>
						<span><?php _e( 'Copy', 'better-sharing-wp' ) ?></span>
					<?php else : ?>  
						<span class="copy-info"><?php _e( 'Copy', 'better-sharing-wp'  ) ?></span>
						<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M16.8032 1.4428L16.1772 0.889045C14.7192 -0.403786 12.4867 -0.269737 11.1943 1.18858L9.40278 3.20978C8.99841 3.66605 9.04043 4.36653 9.49652 4.77128L9.66467 4.92048C10.1223 5.32624 10.8207 5.28434 11.2267 4.82661L13.0182 2.80547C13.4178 2.35503 14.11 2.31358 14.5607 2.71256L15.186 3.26726C15.6368 3.66675 15.6793 4.35886 15.2791 4.80956L11.3617 9.22881C11.0301 9.60345 10.4876 9.70448 10.0423 9.47441C9.55546 9.22298 8.96165 9.33345 8.5981 9.74346L8.56755 9.77794C8.32157 10.0554 8.21903 10.4237 8.28621 10.7884C8.35346 11.153 8.58042 11.4605 8.90892 11.6322C9.42845 11.9037 9.98949 12.0352 10.545 12.0352C11.5294 12.0352 12.4968 11.6224 13.1861 10.8452L17.1032 6.42645C18.3942 4.9701 18.2598 2.73474 16.8032 1.4428Z" fill="white"/>
							<path d="M8.49283 13.2199L8.3243 13.0706C7.86771 12.6661 7.16704 12.7086 6.76261 13.165L4.97187 15.1859C4.57206 15.6362 3.88033 15.678 3.43001 15.2793L2.80388 14.7238C2.35293 14.3245 2.31059 13.6329 2.7109 13.1821L6.62818 8.76316C6.95294 8.39638 7.4873 8.29149 7.92773 8.50787C8.43389 8.75657 9.04849 8.63564 9.42319 8.21277L9.43726 8.19692C9.67798 7.92515 9.77926 7.56445 9.71525 7.2073C9.65123 6.85009 9.43105 6.54707 9.11104 6.37582C7.6642 5.60131 5.89329 5.91821 4.80454 7.14684L0.887008 11.5649V11.5649C-0.404112 13.0213 -0.269809 15.2566 1.18654 16.548L1.81255 17.1025C2.4833 17.6972 3.3191 17.9894 4.15211 17.9894C5.12778 17.9894 6.0994 17.5884 6.79621 16.8026L8.5872 14.7817C8.9924 14.3241 8.95082 13.626 8.49283 13.2199Z" fill="white"/>
						</svg>
					<?php endif; ?>
					</a>
	<?php if ($is_full_view) : ?> 
			</div>
		</div>
	<?php endif; ?>  
	</div>
<?php else: ?>
	<input 
		type="hidden" 
		id="referral-link"
		value="<?php echo esc_url( $referral_link ) ?>"
	>
<?php endif; ?>
