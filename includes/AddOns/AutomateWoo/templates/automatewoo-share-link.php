<?php

if ( ! defined( 'ABSPATH' ) ) { 
    exit;
}

use AutomateWoo\Referrals\Advocate_Factory;

$field_count = absint( apply_filters( 'automatewoo/referrals/share_form/email_field_count', 5 ) );

$advocate = Advocate_Factory::get( get_current_user_id() );
$advocateKey = $advocate->get_advocate_key();
$shareParameter = AW_Referrals()->options()->share_link_parameter;

?>

<div class="bswp-automatewoo-share-link">
    <h3 class="bswp-share-emails-title"><?php _e('Share your Link', 'better-sharing-wp'); ?></h3>
    <p>
    <?php _e('Share your link to get referral credit', 'better-sharing-wp'); ?>
    </p>

    <div class="bswp-automatewoo-share-link-key">
        <input 
            readonly 
            type="text"
            id="bswp-automatewoo-share-link-key-to-copy" 
            value="<?php echo esc_url( get_site_url() . '?' . $shareParameter . '=' . $advocateKey ); ?>">
        <span class="bswp-automatewoo-share-link-key-copy button btn">
            <span class="dashicons dashicons-admin-page"></span>
            <span><?php _e('Copy Link', 'better-sharing-wp'); ?></span>
        </span>
        <p class="bswp-automatewoo-share-link-key-copy-confirm">
            <em><?php _e('Copied', 'better-sharing-wp'); ?></em>
        </p>
    </div>
</div>

<div class="aw-referrals-share-or"><?php _e('Or', 'better-sharing-wp'); ?></div>