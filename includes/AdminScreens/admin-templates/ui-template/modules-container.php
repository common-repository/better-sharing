<?php 
$default_settings = $this->default_ui_template_settings;
include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/shortcode-module.php';
?>
<div class="bswp__ui-template-tabs">
  <?php   
  include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/ui-template.php';
  include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/social-share-module.php';
  include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/referral-link-module.php';
  include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/email-module.php';
  ?>
</div>