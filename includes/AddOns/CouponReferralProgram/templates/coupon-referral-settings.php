<?php
/**
 * Coupon Referral Settings
 *
 * @package template
 */

$subject = $this->option_data->get( 'emailSubject' );
$content = $this->option_data->get( 'emailContent' );

?>

<h4><?php _e('Email Subject', 'better-sharing-wp'); ?></h4>
<div class="bswp__text">
	<p class="description">
	<?php _e('Subject of email being sent out with your coupon referral link', 'better-sharing-wp'); ?>
	</p>
	<label for="coupon_referral_email_subject" class="hidden"><?php _e('Email Subject', 'better-sharing-wp'); ?></label>
	<input 
		type="text" 
		id="coupon_referral_email_subject" 
		name="coupon_referral_email_subject" 
		placeholder="Save today with this coupon code" 
		value="<?php echo esc_attr( $subject ); ?>" />
</div>

<h4><?php _e('Email Content', 'better-sharing-wp'); ?></h4>
<div class="bswp__textarea">
	<p class="description">
	<?php _e('Use <strong>{{link}}</strong> to insert the coupon hyperlink, or it will be added at the end', 'better-sharing-wp'); ?>
	</p>
	<label for="coupon_referral_email_content" class="hidden"><?php _e('Email Content', 'better-sharing-wp'); ?></label>
	<textarea 
		type="text" 
		id="coupon_referral_email_content" 
		name="coupon_referral_email_content" 
		placeholder="Use the {{link}} to save!"><?php echo esc_textarea( $content ); ?></textarea>
</div>
