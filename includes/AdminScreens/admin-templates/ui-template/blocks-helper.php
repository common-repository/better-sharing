<div class="bswp__helper-block-wrapper bswp__container">
  <button class="toggle active-content"></button>
  <div id="helper-heading" class="hidden-helper">
      <div class="helper-section">
          <h2>Get Started</h2>
      </div>
  </div>
  <div id="helper-content" class="">
    <?php if ( ! empty( $sample_page_link ) ) : ?>
    <div class="helper-section">
        <h2>Get Started</h2>
        <div>
            <span>We drafted a page for you, so you can see an example of how to use the inline and compact blocks.</span>
            <p class="row-actions">
                <a href="<?php echo esc_url( $sample_page_link )?>">Edit</a> | 
                <a href="<?php echo esc_url ( $sample_page_view )?>" target="_blank">View</a>
            </p>
        </div>
    </div>
    <?php endif; ?>
    <?php if ( ! empty( $default_email_template_link ) ) : ?>
    <div class="helper-section">
        <h2>What's Next?</h2>
        <div>
            <ul>
                <li><a href="https://www.cloudsponge.com/get-started/" target="_blank">Get a CloudSponge key</a> to enable your <a href="https://www.cloudsponge.com/contact-picker/" target="_blank">Contact Picker</a></li>
                <li><a href="<?php echo esc_url( $add_new_block_link )?>">Create</a> your own Better Sharing Block.</li>
                <li><a href="<?php echo esc_url( $default_email_template_link )?>">Edit</a> the default email template.</li>
                <li><a href="<?php echo esc_url( $add_email_template_link )?>">Create</a> your own email template.</li>
            </ul>
        </div>
    </div>
    <?php endif; ?>
    <div class="helper-section">
        <h2>Useful Links</h2>
        <div>
            <ul>
                <li><a href="<?php echo esc_url( $config['cs_documentation_url'] )?>" target="_blank">Check our documentation</a></li>
                <li><a href="https://www.youtube.com/playlist?list=PLRqR-OxeQ97oe58MKfkteg2f6luDL07f4" target="_blank">Watch our videos</a></li>
                <li><a href="<?php echo esc_url( $config['cs_contact_url'] )?>" target="_blank">Contact us for help</a></li>
                <li><a href="https://wordpress.org/support/plugin/better-sharing/reviews/" target="_blank">Leave a review</a> (it really helps!)</li>
            </ul>
        </div>
    </div>
  </div>
</div>