<div class="wp-block-cgb-block-ea-better-sharing <?php echo 'bswp-'.$bswp_ui_template; ?>"
    data-block-attr="<?php echo esc_attr( json_encode( $block_attributes ) ); ?>">
<?php 
  if ( !$is_full_view ) : ?>
  <div class="flex items-center compact-view">
<?php 
  endif;

    include BETTER_SHARING_PATH . 'includes/templates/modules/bswp-social-share.php';
    include BETTER_SHARING_PATH . 'includes/templates/modules/bswp-referral-link.php';
    include BETTER_SHARING_PATH . 'includes/templates/modules/bswp-form.php';
    
  if ( !$is_full_view ) : ?>
  </div>
<?php 
  endif; 
?> 
</div>