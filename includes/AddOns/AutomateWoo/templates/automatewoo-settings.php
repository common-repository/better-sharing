<?php
    $shareLinkToggle = $this->option_data->get('share_link_toggle');
    $preview_email_toggle = $this->option_data->get('preview_email_toggle');
?>

<h4><?php _e('Display Share Link', 'better-sharing-wp'); ?></h4>
<div class="bswp__checkbox">
  <p class="description">
  <?php _e('Show "Share your Link" on the referral page.', 'better-sharing-wp'); ?>
  </p>
  <label for="share_link_toggle_true">
  <?php _e('Yes', 'better-sharing-wp'); ?>
    <input 
      type="radio" 
      id="share_link_toggle_true" 
      name="share_link_toggle" 
      value="true" 
      <?php checked( $shareLinkToggle ); ?>>
  </label>

  <label for="share_link_toggle_false">
  <?php _e('No', 'better-sharing-wp'); ?>
    <input 
      type="radio" 
      id="share_link_toggle_false" 
      name="share_link_toggle" 
      value="false" 
      <?php checked(! $shareLinkToggle); ?>>
  </label>
</div>

<h4><?php _e('Display Email Preview', 'better-sharing-wp'); ?></h4>
<div class="bswp__checkbox">
  <p class="description">
  <?php _e('Preview email and subject on referral page.', 'better-sharing-wp'); ?>
  </p>
  <label for="share_email_preview_toggle_true">
  <?php _e('Yes', 'better-sharing-wp'); ?>
    <input 
      type="radio" 
      id="share_email_preview_toggle_true" 
      name="share_email_preview_toggle" 
      value="true" 
      <?php checked($preview_email_toggle); ?>>
  </label>

  <label for="share_link_toggle_false">
  <?php _e('No', 'better-sharing-wp'); ?>
    <input 
      type="radio" 
      id="share_link_toggle_false" 
      name="share_email_preview_toggle" 
      value="false" <?php 
      checked(! $preview_email_toggle); ?>>
  </label>
</div>