<?php
/**
 * Coupon Code Template
 *
 * @package template
 */

$referral_link = $referral_link ? $referral_link : false;

if ( ! $referral_link ) {

	return false;
}
?>

<div class="bswp-coupon-referral-coupon-code">
	<h3>
		<?php _e( 'Coupon Referral Program', 'better-sharing-wp' ); ?>
	</h3>
	<strong><?php _e('Your referral link:', 'better-sharing-wp'); ?></strong>
	<div>
		<input 
			readonly 
			type="text" 
			value="<?Php echo esc_attr( $referral_link ); ?>" 
			id="bswp-coupon-referral-copy" />
		<span class="bswp-copy button btn">
			<span class="dashicons dashicons-admin-page"></span>
			<span><?php _e('Copy Link', 'better-sharing-wp'); ?></span>
		</span>
		<p class="bswp-copy-confirm">
			<em><?php _e('Copied', 'better-sharing-wp'); ?></em>
		</p>
	</div>
</div>
