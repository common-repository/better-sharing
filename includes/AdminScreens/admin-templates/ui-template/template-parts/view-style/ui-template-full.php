<div class="wp-block-cgb-block-ea-better-sharing full-view <?php echo $is_full_view_hidden ?>">      
    <?php
        $bswp_ui_template_module_hidden = '';
        $bswp_sortable = 'social_share.php';

        if( isset( $sorted_ui_template_settings ) ) :

            if( !$sorted_ui_template_settings[1]['enabled'] ) :
                
                $bswp_ui_template_module_hidden = 'bswp-ui-template-module-hidden';
            endif;

            $bswp_sortable = $sorted_ui_template_settings[1]['module_name'] . '.php'; 
        endif;       
    ?>
    <div class="bswp-ui-template-module-container <?php echo esc_attr($bswp_ui_template_module_hidden); ?>" data-order="1"> 
        <div class="bswp-handle-module">
            <a href="#" data-action="customize"><?php _e('Customize', 'better-sharing-wp'); ?></a>
            <span class="bswp-reorder">
                <a href="#" data-action="down"><?php _e('Move Down', 'better-sharing-wp'); ?></a>
            </span>
        </div>
        <div class="bswp-sortable"> 
            <?php 
                include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/ui-template-modules/' .  $bswp_sortable; 
            ?>
        </div>
    </div>
    <?php
        $bswp_ui_template_module_hidden = '';
        $bswp_sortable = 'referral_link.php';

        if( isset( $sorted_ui_template_settings ) ) :

            if( !$sorted_ui_template_settings[2]['enabled'] ) :
                
                $bswp_ui_template_module_hidden = 'bswp-ui-template-module-hidden';
            endif;

            $bswp_sortable = $sorted_ui_template_settings[2]['module_name'] . '.php'; 
        endif;      
    ?>
    <div class="bswp-ui-template-module-container <?php echo esc_attr($bswp_ui_template_module_hidden); ?>" data-order="2">
        <div class="bswp-handle-module">
            <a href="#" data-action="customize"><?php _e('Customize', 'better-sharing-wp'); ?></a>
            <span class="bswp-reorder">
                <a href="#" data-action="up"><?php _e('Move Up', 'better-sharing-wp'); ?></a>
                <a href="#" data-action="down"><?php _e('Move Down', 'better-sharing-wp'); ?></a>
            </span>
        </div>
        <div class="bswp-sortable"> 
            <?php 
                include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/ui-template-modules/' . $bswp_sortable; 
            ?>
        </div>
    </div>
    <?php
        $bswp_ui_template_module_hidden = '';
        $bswp_sortable = 'email.php';

        if( isset( $sorted_ui_template_settings ) ) :

            if( !$sorted_ui_template_settings[3]['enabled'] ) :

                $bswp_ui_template_module_hidden = 'bswp-ui-template-module-hidden';
            endif;
            $bswp_sortable = $sorted_ui_template_settings[3]['module_name'] . '.php'; 
        endif;       
    ?>
    <div class="bswp-ui-template-module-container <?php echo esc_attr($bswp_ui_template_module_hidden); ?>" data-order="3">
        <div class="bswp-handle-module">
            <a href="#" data-action="customize"><?php _e('Customize', 'better-sharing-wp'); ?></a>
            <span class="bswp-reorder">
                <a href="#" data-action="up"><?php _e('Move Up', 'better-sharing-wp'); ?></a>
            </span>
        </div>
        <div class="bswp-sortable"> 
        <?php 
            include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/template-parts/ui-template-modules/' . $bswp_sortable; 
        ?>
        </div>
    </div>
</div>