<?php if( $post->ID ) : ?>
<div class="bswp__short-code-container">
    <span>
        [better-sharing id="<?php echo esc_attr($post->ID); ?>"]
    </span>
    <a href="#" class="bswp-shortcode-copy" data-text="bswp-shortcode"><?php _e('Copy', 'better-sharing-wp'); ?></a>
</div>
<?php endif; ?>