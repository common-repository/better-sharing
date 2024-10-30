<?php
/**
 * BSWP - FORM
 *
 * @package form
 */

global $post;

$ajax         = $ajax ? $ajax : false;
$api_key      = get_site_option( '_bswp_option_core_apiKey', false );
$email_subject = isset( $email_subject ) ? $email_subject : 'Sharing';
$email_content = isset( $email_content ) ? $email_content : 'Email Content';
$field_count  = isset( $field_count ) ? absint( apply_filters( 'automatewoo/referrals/share_form/email_field_count', 5 ) ) : 5;

$addon      = isset( $addon ) ? sanitize_title_with_dashes( $addon ) : false;
if ( ! $addon ) {
	return;
}
$action_data = array(
	'addon' => $addon,
);

do_action( 'bwp_addon_form_before', $action_data );

?>
<?php 
	global $wp; 
	$current_url 	=  trailingslashit(home_url( $wp->request )); 
	$query_str 		= $_SERVER['QUERY_STRING'];
	if ( ! empty( $query_str ) ) :
		$current_url = "$current_url/?$query_str";
	endif;
?>
<form <?php echo ! $ajax ? 'action="' . esc_url( $current_url ) . '"' : ''; ?> accept-charset="UTF-8" method="post">

	<?php wp_nonce_field( 'bswp_form_nonce', '_bswp_form_nonce' ); ?>

	<?php do_action( 'bwp_form_inner_before', $action_data ); ?>
	<input type="hidden" name="bswp_form_addon" value="<?php echo esc_attr( $addon ); ?>" />

	<h3 class="bswp-share-emails-title"><?php _e('Share via Email', 'better-sharing-wp'); ?></h3>
	<p>
	<?php _e('Invite people to use your referral code.', 'better-sharing-wp'); ?>
	</p>

	<div class="bswp-share-buttons bswp-share-emails">
		<input type="text" name="bswp-share-email-input" id="bswp-share-email-input" placeholder="To: enter contact emails separated by comma (,)">
		<?php if ( $api_key ) : ?>
			<a href="#" class="add-from-address-book-init btn button">
				<span class="dashicons dashicons-book-alt"></span>
				<span>
					<?php esc_attr_e( 'Add From Contacts', 'better-sharing-wp' ); ?>
				</span>
			</a>
		<?php endif; ?>
	</div>

	<hr/>

	<?php if ( $preview_email_toggle ) : ?>
		<div class="bswp-share-email-preview">
			<h4><?php _e('Email Preview', 'better-sharing-wp'); ?></h4>
			<p>
			<?php _e('This is the email that your referrals will see.', 'better-sharing-wp'); ?>
			</p>
			<div class="bswp-share-email-preview-subject">
				<strong><?php _e('Subject', 'better-sharing-wp'); ?></strong>
				<div class="box"><?php echo esc_html( $email_subject ); ?></div>
				<input type="hidden" name="bswp-share-email-subject" id="bswp-share-email-subject" value="<?php echo esc_attr( $email_subject ); ?>" />
			</div>
			<div class="bswp-share-email-preview-message">
				<strong><?php _e('Message', 'better-sharing-wp'); ?></strong>
				<div class="box"><?php echo esc_html( $email_content ); ?></div>
				<input type="hidden" name="bswp-share-email-content" id="bswp-share-email-content" value="<?php echo esc_attr( $email_content ); ?>" />
			</div>
		</div>
	<?php endif; ?>

	<div id="referral-emails-wrapper" data-max="<?php echo esc_attr( $field_count ); ?>"></div>

	<?php do_action( 'bwp_form_inner_after', $action_data ); ?>

	<?php if ( $ajax ) : ?>
		<div class="bswp-share-buttons">
			<a href="#" class="bswp-submit btn button"><?php _e('Send', 'better-sharing-wp'); ?></a>
		</div>
	<?php else : ?>
		<div class="bswp-share-buttons">
			<input type="submit" class="bswp-submit btn button" value="<?php _e('Send', 'better-sharing-wp'); ?>" />
			<p class='coreblock-error-msg'></p>
		</div>
	<?php endif; ?>

</form>

<?php
do_action( 'bwp_addon_form_after', $action_data );