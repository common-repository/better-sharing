<?php

if (! defined('ABSPATH') ) { exit;
}

use BetterSharingWP\OptionData;
use AutomateWoo\Referrals\Invite_Email;
use AutomateWoo\Referrals\Advocate_Factory;

$option_data = new OptionData('automatewoos-refer-a-friend-add-on');
$shareLinkToggle = (bool) rest_sanitize_boolean($option_data->get('share_link_toggle'));
$preview_email_toggle = (bool) rest_sanitize_boolean($option_data->get('preview_email_toggle'));
$field_count = absint( apply_filters('automatewoo/referrals/share_form/email_field_count', 5 ) );
$api_key = get_site_option('_bswp_option_core_apiKey', false);

$user = get_user_by('id', get_current_user_id());
$email = new Invite_Email($user->user_email, Advocate_Factory::get($user->ID));
$emailSubject = $email->get_subject();
$emailContent = $email->get_content();


if ( $shareLinkToggle ) {
    include_once 'automatewoo-share-link.php';
}
?>

<form class="aw-email-referral-form bswp-refferals" action="" accept-charset="UTF-8" method="post">
    <input type="hidden" name="action" value="aw-referrals-email-share">

    <h3 class="bswp-share-emails-title"><?php _e('Share via Email', 'better-sharing-wp'); ?></h3>
    <p>
       <?php _e('Invite people to use your referral code.', 'better-sharing-wp'); ?>
    </p>

    <div class="bswp-share-buttons bswp-share-emails">
        <input 
            type="text" 
            name="bswp-share-email-input" 
            id="bswp-share-email-input" 
            placeholder="To: enter contact emails separated by comma (,)">
        
        <?php if ( $api_key ) : ?>
            <a href="#" class="add-from-address-book-init btn button">
                <span class="dashicons dashicons-book-alt"></span>
                <span>
                    <?php esc_attr_e( 'Add From Contacts', 'better-sharing-wp' ); ?>
                </span>
            </a>
        <?php endif; ?>
    </div>

    <?php if ( $preview_email_toggle ) : ?>

        <div class="bswp-share-email-preview">
            <h4><?php _e('Email Preview', 'better-sharing-wp');?></h4>
            <p>
            <?php _e('This is the email that your referrals will see.', 'better-sharing-wp'); ?>
            </p>
            <div class="bswp-share-email-preview-subject">
                <strong><?php _e('Subject', 'better-sharing-wp'); ?></strong>
                <div class="box"><?php echo esc_html( $emailSubject ); ?></div>
            </div>
            <div class="bswp-share-email-preview-message">
                <strong><?php _e('Message', 'better-sharing-wp'); ?></strong>
                <div class="box"><?php echo wp_kses($emailContent, [
                                                            'a' => ['style' => 'align'],
                                                            'strong' => [],
                                                            'p' =>  ['style' => 'align'],
                                                            'h1' => ['style' => 'align'],
                                                            'h2' => ['style' => 'align'],
                                                            'h3' => ['style' => 'align'],
                                                            'h4' => ['style' => 'align'],
                                                            'h5' => ['style' => 'align'],
                                                            'h6' => ['style' => 'align'],
                                                            ]
                                                            ); ?></div>
            </div>
        </div>
    <?php endif; ?>

    <div id="referral-emails-wrapper" data-max="<?php echo esc_attr( $field_count ); ?>"></div>
   
    <?php wp_nonce_field( $nonce_action ); ?>
    
    <div class="aw-referrals-share-buttons bswp-share-buttons">
        <a href="#" class="bswp-submit btn button"><?php _e('Send', 'better-sharing-wp'); ?></a>
    </div>
</form>