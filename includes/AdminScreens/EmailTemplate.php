<?php
/**
 * Email Template - Custom Post Type
 *
 */


namespace BetterSharingWP\AdminScreens;
use BetterSharingWP\Api\Email;


class EmailTemplate
{
    /**
     * Template variables
     * allow the user to set his values
     * in the emails to send
     * 
     * static property
     *
     * @var arr
     */
    private static $template_variables;
    /**
     * Title
     * for the default email template
     *
     * @var str
     */
    private $default_email_template_title;
    /**
     * Used to store
     * the Email Template CPT
     * result after validating
     * the CPT add/edit 
     * form data. 
     * Its value is changed to true
     * when the data didn't pass the validation
     *
     * @var boolean
     */
    private $save_template_as_draft = false;   
    
    /**
     * Default email body
     * seen in Add an Email Template form
     * 
     * @var str
     */
    private $default_email_body;

    /**
     * Default email subject
     * seen in Add an Email Template form
     *
     * @var str
     */
    private $default_email_subject;
   
    public function __construct() {

        add_action( 'init', array( $this, 'register_email_template_cpt' ) );
        
        $this->set_default_email_body();
        $this->set_default_email_subject();
        $this->set_default_email_template_title();
    }

    /**
     * Load Email Template
     * Hooks
     * 
     * @return void
     */
    public function init() {
        
        add_action('add_meta_boxes', array( $this, 'dynamic_add_email_subject_metabox' ) );
        add_action('add_meta_boxes', array( $this, 'dynamic_add_template_variables_metabox' ) ); 
        add_action( 'edit_form_after_title', array( $this, 'load_email_preview_toggle_buttons' ) ); 
                
        add_action('pre_post_update', array( $this, 'cpt_validate' ), 10, 2 );
        add_action('save_post', array( $this, 'save_email_template_meta_data' ) );
        add_action('admin_notices', array( $this, 'email_template_notifications' ), 10, 1 );
        
        add_action('admin_enqueue_scripts', array( $this, 'my_admin_enqueue_scripts' ) );
        add_action('admin_footer', array( $this, 'toggle_publish_button' ) );
        
        add_action('edit_form_after_title', array( $this, 'edit_form_after_title' ) );
        add_filter('user_can_richedit', array( $this, 'disable_richedit_for_cpt' ) );
        add_filter('wp_editor_settings', array( $this, 'disable_media_buttons_for_cpt' ) ); 
        add_filter('default_content', array( $this, 'set_default_editor_content' ) );

        add_filter('use_block_editor_for_post_type', array( $this, 'disable_gutenberg_posts' ), 10, 2 );     

        add_filter('manage_posts_columns', array( $this, 'add_id_column_to_admin_list' ), 5 );
        add_filter('manage_edit-bswp_email_template_sortable_columns', array( $this, 'set_sortable_id_column' ) );
        add_action('manage_posts_custom_column', array( $this, 'print_id_column_content' ), 10, 2 );
    }

    /**
     * Registers
     * the Email Template as a CPT
     *
     * @return void
     */
    public function register_email_template_cpt() {

        $labels = array(
            'name'          => __('Email Template', 'better-sharing-wp'),
            'singular_name' => __('Email Template', 'better-sharing-wp'),
            'add_new'       => __('Add Email Template', 'better-sharing-wp'),
            'add_new_item'  => __('Add New Email Template', 'better-sharing-wp'),
            'edit_item'     => __('Edit Email Template', 'better-sharing-wp'),
            'new_item'      => __('New Email Template', 'better-sharing-wp'),
            'view_item'     => __('View Email Template', 'better-sharing-wp'),
            'search_items'  => __('Search Email Templates', 'better-sharing-wp'),
            'not_found'     => __('No Email Templates found', 'better-sharing-wp'),
            'not_found_in_trash' => __('No Email Templates found in Trash', 'better-sharing-wp'),
        );
        $args = array(
            'labels'        =>  $labels,
            'description'   => __('BSWP Email Template custom post type', 'better-sharing-wp'),
            'public'        => true,
            'hierarchical'  => false,
            'exclude_from_search'   => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_menu'          => false,
            'show_in_nav_menus'     => true,
            'capability_type'       => 'post',
            'supports'              => array('title', 'editor'),  
            'has_archive'           => false,
            'delete_with_user'      => false,
            'show_in_rest'          => true
        );

        register_post_type('bswp_email_template', $args);
    }
    /**
     * Adds Email Templates 
     * submenu item
     * to BSWP Admin Menu
     *
     * @return void
     */
    public function add_to_submenu(){
        add_submenu_page(
			'better-sharing-wp',
            __('Email Templates', 'better-sharing-wp'), // page title.
            __('Email Templates', 'better-sharing-wp'), // menu title.
			'edit_pages',
			'edit.php?post_type=bswp_email_template'
		);
    }
    /**
     * Disable the Gutenberg Page builder
     * for the Email Template CPT edit screen
     * used in the WP add_filter with 
     * the use_block_editor_for_post_type hook
     *
     * @param string $current_status
     * @param string $post_type
     * 
     * @return str
     */
    public function disable_gutenberg_posts( $current_status, $post_type ) {
        
        // Disabled post types
        $disabled_post_types = array( 'bswp_email_template' );
       
        if ( in_array( $post_type, $disabled_post_types, true ) ) {

            $current_status = false;
        }

        return $current_status;
    }
    /**
     * Sets a value for the
     * $default_email_template_title
     * class property
     *
     * @return string
     */
    public function set_default_email_template_title(){
        $this->default_email_template_title = 'Default email template';
    }
    /**
     * Set the value for the 
     * $default_email_body
     * class propery
     *
     * @return void
     */
    public function set_default_email_body() {

        $default_content  = "<p>Hi {{ greeting }},</p>\r\n";
        $default_content .= "<p>{{ email_message }}</p>\r\n";
		$default_content .= "<p>Here's what {{ sender_first_name }} had to say about it:</p>\r\n";
        $default_content .= "<p>{{ sender_custom_message }}</p>\r\n";       
        $default_content .= "<p>The link: {{ referral_link }}</p>\r\n"; 
		
        $this->default_email_body = $default_content;
    }

    /**
     * Set a value for the
     * $default_email_subject
     * class property
     *
     * @return void
     */
    public function set_default_email_subject() {

        $this->default_email_subject = "{{ sender_first_name }} wants you to see this";
    }
    /**
     * Gets the value of
     * $default_email_template_title
     * class property
     *
     * @return void
     */
    public function get_default_email_template_title(){
        return $this->default_email_template_title;
    }
    /**
     * Get the value of
     * $default_email_body
     * class property
     *
     * @return str
     */
    public function get_default_email_body() {

        return $this->default_email_body;
    }

    /**
     * Get the value of
     * $default_email_subject
     * class property
     *
     * @return void
     */
    public function get_default_email_subject() {

        return $this->default_email_subject;
    }

    /**
     * The CPT Content field
     * in the Add New Email Template form
     * is prepolutated
     * with a default email body
     * used in the WP add_filter with
     * the default_content hook
     * 
     * @param string $content - the base post content
     * 
     * @return str modified Email template CPT content
     */
    public function set_default_editor_content( $content ) {

        if ( ! function_exists( 'get_current_screen' ) )
            return;

        $current_screen = get_current_screen();    
        
        if ( $current_screen->post_type == 'bswp_email_template' ) :

            $content = $this->default_email_body;
        endif;

        return $content;
    }
    /**
     * Gets or Creates an Email Template
     * with default content and meta data
     * on plugin activation
     *
     * @param $post_title
     * @return WP_Error The post ID on success. The WP_Error on failure.
     */
    public function create_default_email_template( $post_title = '' ){
        if ( empty( trim( $post_title ) ) ) :
            $post_title = $this->default_email_template_title;
        endif;
        $result = $this->get_default_email_template_id( $post_title ); // exit if not false with the result( the ET ID).
     
        if ( !$result ) : 
            $postarr = array(
                'post_content' => $this->default_email_body,
                'post_title'   =>  $post_title,
                'post_status'  => 'publish',
                'post_type'    => 'bswp_email_template',
                'comment_status' => 'closed',
                'post_name'    =>  $post_title, // post slug
            );
            // save default post and get ID.
            $result = wp_insert_post( $postarr, true );// returns ID on success, WP_errorr on failure.
            $subject_meta_result = true;
            if ( $result ) :
                
                $subject_meta_result = add_post_meta( 
                    $result, 
                    'bswp_email_subject', 
                    $this->default_email_subject
                );// returns int|false Meta ID on success, false on failure.
                if ( $subject_meta_result ) :
                    return $result; //exit - the default email template with default email subject and body created.
                endif;
            endif;
        endif;
        return $result;
    }
    /**
     * Gets the
     * default email template 
     * ID if it exists 
     * or returns false
     *
     * @param str $post_title
     * @return int (the email template) ID|0
     */
    public function get_default_email_template_id( $post_title = ''){
        if ( empty( trim( $post_title ) ) ) :
            $post_title = $this->default_email_template_title;
        endif;
        $result = $this->post_exists( 
            $post_title, 
            'bswp_email_template'
        ); 
        if ( $result ) :
            // update post status if not published.
            // $status = 'publish'; // is required.
            // $status_check = $this->update_email_template_status( $result, $status ); 
            // if ( $status_check ) :
                return $result; // post ID
            // endif;
        endif;

       return 0; 
    }
    /**
     * Modified version of 
     * the wp-core's post_exists()
     * Determines if a post exists 
     * based on title and type.
     * the post can be with 'draft' status
     *
     * @param str $title 
     * @param str $type
     * @return int
     */
    function post_exists( $title, $type = '' ) {
        global $wpdb;
    
        $post_title   = wp_unslash( sanitize_post_field( 'post_title', $title, 0, 'db' ) ); 
        $post_type    = wp_unslash( sanitize_post_field( 'post_type', $type, 0, 'db' ) );
    
        $query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
        $args  = array();
    
        if ( ! empty( $title ) ) {
            $query .= ' AND post_title = %s';
            $args[] = $post_title;
        } 
        if ( ! empty( $type ) ) {
            $query .= ' AND post_type = %s';
            $args[] = $post_type;
        }
        if ( ! empty( $args ) ) {
            return (int) $wpdb->get_var( $wpdb->prepare( $query, $args ) );
        } 
        return 0;
    }
   
    /**
     * Get a list 
     * of all default 
     * email templates
     *
     * @return arr
     */
    function get_default_email_templates() {
        global $wpdb;
        $config = include BETTER_SHARING_PATH . 'includes/config/general.php';  
        $default_post_titles = $config['preserved_titles']['email_templates']; 
    
        $query = "SELECT ID, post_title FROM $wpdb->posts WHERE "; 

        if ( ! empty( $default_post_titles ) ) :
            $query .= "post_title IN (" . "'" . implode("','", $default_post_titles) . "'" . ") ";  
        endif;  

        $query .= " AND post_type = 'bswp_email_template'"; 
        $results = $wpdb->get_results( $query,  ARRAY_A);  // Obj or arr Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants. 

        return $results;
    }
    
    /**
     * Checks default
     * email template meta
     * exists
     * and is equal to the default subject
     *
     * @return bool
     */
    public function default_email_template_meta_exists( $post_id ){ 
        $meta = get_post_meta( $post_id, 'bswp_email_subject', true);
       
        if ( $meta ) :
            if ( $meta === $this->default_email_subject ) :
                return true;
            endif;
        endif;
        return false;
    }
    /**
     * Add the Email Template CPT
     * metabox for the email subject
     * Used in add_action WP function with
     * the add_meta_boxes hook
     * 
     * @return void
     */    
    public function dynamic_add_email_subject_metabox() {

        add_meta_box(
            'bswp_email_subject',
             __('Email Subject', 'better-sharing-wp' ), 
             array($this, 'load_email_subject_metabox'), 'bswp_email_template'
            );
    }
    
    /**
     * Display buttons
     * that handles Email Preview
     * 
     * @return void
     */
    public function load_email_preview_toggle_buttons() {

        if ( ! function_exists( 'get_current_screen' ) )
            return;

        $current_screen = get_current_screen();    
        
        if ( $current_screen->post_type == 'bswp_email_template' ) : 
            $btn_labels = include BETTER_SHARING_PATH . 'includes/config/email_preview.php';
        ?>  
        <div class="bswp__email-preview-toggle">
            <button class="button active bswp__email-preview-toggle" data-toggle="source">
                <?php _e($btn_labels['buttons']['view_source'], 'better-sharing-wp'); ?>
            </button>
            <button class="button bswp__email-preview-toggle" data-toggle="preview">
                <?php _e($btn_labels['buttons']['preview'], 'better-sharing-wp'); ?>
            </button>
            <button id="bswp-email-test-modal-trigger" class="button">
                <?php _e($btn_labels['buttons']['send_test_modal'], 'better-sharing-wp'); ?>
            </button> 
        </div>
        <div id="bswp-email-preview-wrapper"  class="bswp__hidden-email-template bswp__email-preview-wrapper">   
           
        </div>
        <?php

            include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'email-template/test-email-modal.php';  

        endif;
    }

    /**
     * Display the Email Subject field
     * in the Email Template CPT
     * add/edit form
     * 
     * @return void
     */
    public function load_email_subject_metabox() {

        global $post;

        $email_subject = '';
        $email_subject = get_post_meta( $post->ID, 'bswp_email_subject', true );

        if( empty( $email_subject ) ) { $email_subject = $this->default_email_subject; }    

        ?>        
        <div class="bswp__email-subject">
            <input 
                type="text" 
                id="email-subject" 
                name="email_subject" 
                value="<?php echo esc_attr($email_subject); ?>">
        </div>
        <?php
    }

    /**
     * Add the Email Template CPT
     * metabox for the Template variables
     * Used in the add_action WP function with
     * the add_meta_boxes hook
     * 
     * @return void
     */
    public function dynamic_add_template_variables_metabox() {

        add_meta_box(
            'bswp_template_variables', 
                    __('Template Variables', 'better-sharing-wp' ), 
                    array($this, 'load_template_variables_metabox'), 
                    'bswp_email_template',
                    'side'
                );
    }

    /**
     * Display the Template Variables field
     * in the Email Template CPT
     * add/edit form
     * 
     * @return void
     */
    public function load_template_variables_metabox() {

         self::$template_variables = include BETTER_SHARING_PATH . 'includes/config/email_template_variables.php';

         $copy_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><g data-name="Layer 3"><path d="M40.63 13H12.38a4.69 4.69 0 00-4.68 4.67v39.64A4.69 4.69 0 0012.38 62h28.25a4.69 4.69 0 004.69-4.69V17.67A4.69 4.69 0 0040.63 13zm2.69 44.33A2.69 2.69 0 0140.63 60H12.38a2.69 2.69 0 01-2.68-2.69V17.67A2.69 2.69 0 0112.38 15h28.25a2.69 2.69 0 012.69 2.69z"/><path d="M51.74 2H23.26a4.58 4.58 0 00-4.58 4.57v3.55a1 1 0 002 0V6.57A2.58 2.58 0 0123.26 4h28.48a2.57 2.57 0 012.56 2.57v39.87A2.58 2.58 0 0151.74 49H48.5a1 1 0 000 2h3.24a4.58 4.58 0 004.57-4.58V6.57A4.57 4.57 0 0051.74 2z"/></g></svg>';
         
         if( self::$template_variables ) :
            
            foreach( self::$template_variables as $variable_name => $variable_description ) : 
            
            ?>
                <p class="bswp__template-variable-container">
                    <span class="bswp__tv-content">
                        &#123;&#123; <?php echo esc_html($variable_name); ?> &#125;&#125;
                    </span>
                    <a href="#" class="bswp__copy-variable">
                        <span><?php echo $copy_icon; // WPCS: XSS ok. ?></span>
                        <span>Copied</span>
                    </a>
                    <!-- @TODO replace title with tooltip -->
                    <a href="#" class="bswp__variable-info">
                        <span class="dashicons dashicons-info"></span>
                        <span class="tooltiptext"><?php echo esc_html($variable_description); ?></span>
                    </a>
                </p>
            <?php
            
            endforeach;
        
        endif;
        
        $this->copy_variable_to_content();
    }

    /**
     * Disable the Rich Editor functionality
     * for the Email Template CPT edit screen
     * used in the WP add_filter with 
     * the user_can_richedit hook
     *
     * @param boolean $default
     * 
     * @return boolean
     */
    public function disable_richedit_for_cpt( $default ) {    
        
        global $post;
    
        if ( 'bswp_email_template' == get_post_type( $post ) ) {
            
            return false;
        }

        return $default;
    }

    /**
     * Disable the Media Buttons functionality
     * for the Email Template CPT edit screen
     * used in the WP add_filter with 
     * the wp_editor_settings hook
     *
     * @param array$settings - the editor settings
     * 
     * @return arr the modified editor settings
     */
    public function disable_media_buttons_for_cpt( $settings ) {

        if ( ! function_exists( 'get_current_screen' ) )
            return;

        $current_screen = get_current_screen();    
       
        $post_types = array( 'bswp_email_template' );
    
        if ( ! $current_screen 
            || ! in_array( $current_screen->post_type, $post_types, true ) ) {
            
                return $settings;
        }
        
        $settings['media_buttons'] = false;
        
        return $settings;
    }

    /**
     * Modify the Email Template CPT 
     * default label for post content field
     * in the Email Template CPT edit screen
     * used in the WP add_action with 
     * the edit_form_after_title hook
     *
     * @param array$settings - the editor settings
     * 
     * @return array the modified editor settings
     */
    public function edit_form_after_title() {

        if ('bswp_email_template' == get_post_type()) :

        ?>

            <h2 class="postbox wp-heading-inline" style="margin-top: 20px;"><?php _e('Email Body', 'better-sharing-wp'); ?></h2>

        <?php

        endif;
    }

    /**
     * Copy the selected 
     * Email Template CPT
     * Template Variable 
     * to the Clipboard
     *
     * @return void
     */
    public function copy_variable_to_content() {
        ?>
        <script>
        (function ($) {
            $('.bswp__copy-variable').on('click', function(e) {
                e.preventDefault();
                var varContent = $(this).parents('.bswp__template-variable-container')
                                .find('.bswp__tv-content')
                                .text()
                                .trim(),
                    clipboard = navigator.clipboard;
                    clipboard.writeText( varContent );
                var copiedInfo = $(this)
                                .find('span:nth-of-type(2)')
                                .css('display', 'block');
                setTimeout(() => {
                    copiedInfo.css('display', 'none');
                }, 1000);
            })
        })(jQuery);
        </script>
        <?php 
    }

    /**
     * Save the Email Template CPT
     * meta data in the database
     *
     * @param int $post_id
     * 
     * @return void
     */
    public function save_email_template_meta_data( $post_id ) {     
        
        if( $this->save_template_as_draft ) {
          
            remove_action('save_post', array( $this, 'save_email_template_meta_data' ) );
            
            wp_update_post(
                array(
                    'ID' => $post_id,
                    'post_status' => 'draft',
                 )
            );
          
           add_action('save_post', array( $this, 'save_email_template_meta_data' ) );
            
        }

        if( isset( $_POST['email_subject'] ) )
            update_post_meta( 
                $post_id, 
                'bswp_email_subject', 
                sanitize_text_field( $_POST['email_subject'] ) 
            );
    }

    /**
     * Check the database
     * for an existing Email Template Title
     *
     * @param int $post_id
     * @param array $post_data
     * 
     * @return void
     */
    public function cpt_validate( $post_id, $post_data ) {  
        
        if ( 'bswp_email_template' == get_post_type() ) {
           
            if( !empty( $post_data['post_status'] ) ) {

                if( $post_data['post_status'] != 'trash' ) {

                    $error_messages = [];
                    $email_subject = sanitize_text_field($_POST['email_subject']);
            
                    if ( ( strpos( $email_subject, '{{ referral_link }}' ) !== false ) 
                        || ( strpos( $email_subject, '{{ sender_custom_message }}' ) ) ) { 

                            $error_messages[] = __('Please, use only sender/recipient name(s) and greeting template variables!', 'better-sharing-wp' );                
                    }            
        
                    if( !empty( $post_data['post_title'] ) ) {

                        $duplicate_template_title = get_page_by_title( $post_data['post_title'], OBJECT, 'bswp_email_template' );
                    
                        if( !empty( $duplicate_template_title && $duplicate_template_title->ID != $post_id ) ) {   

                            $error_messages[] = __('Email Template with the <b>same title</b> already exists!', 'better-sharing-wp' );
                        }
                    }
               
                    if( !empty( $error_messages ) ) {       

                        $this->save_template_as_draft = true;
                        update_option( '_bswp_option_email_template_notifications', json_encode( array( 'error', $error_messages ) ) ); 
                    
                        return false; 
                    }            
                }

            }

            return true;
        }
    }  

    /**
     * Override default 
     * admin notices.
     * Used in the WP add_action with
     * the admin_notices hook
     * 
     * @return void
     */
    public function email_template_notifications() {
        
        if ( 'bswp_email_template' == get_post_type() ) {

            $notifications = get_option('_bswp_option_email_template_notifications');

            if ( !empty( $notifications ) ) {

                if ( 'bswp_email_template' == get_post_type() ) { 

                    $notifications = json_decode($notifications);
                    
                    foreach( $notifications[1] as $error_message ) {

                        echo '<div class="notice notice-error is-dismissible">';
                        echo '<p>' .esc_html( $error_message ) . '</p>';
                        echo '</div>';  
                    }

                    echo '<div class="notice notice-warning is-dismissible">';
                    echo '<p>' . __('The Email Template was saved as <b>draft</b>', 'better-sharing-wp' ) . '</p>';
                    echo '</div>';                  
                  ?>
                    <style>
                        .updated {
                            display: none
                        }
                    </style>
                  <?php

                    update_option('_bswp_option_email_template_notifications', false);
                }
            }
        }
    }
        
    /**
     * Activate/Deactivate the CPT Publish Button 
     * based on form validation result
     * 
     * @return void
     */
    public function toggle_publish_button() {
        global $post;
        

        if ( ! function_exists( 'get_current_screen' ) )
            return;
            
        $screen = get_current_screen();

        if( $screen->base == 'post' && get_post_type() == 'bswp_email_template' ) : 
            // Get default ETs IDs and titles
            $default_templates = $this->get_default_email_templates(); // @TODO check when no defaults.  
            $default_templates_str = wp_json_encode( $default_templates );
        ?>
        <script>
            (function ($) {
                var btn = $('#publish'),
                    postId = "<?php echo $post->ID; ?>",
                    defaultTemplates = <?php echo $default_templates_str; ?>; 

                if( !$('#title').val() || !$('#content').val() || !$('#email-subject').val()) {

                    btn.attr('disabled', true)
                    btn.attr('title', 'Please, fill in the form fields!')
                }

                $('#post').on('input', ('#title, #content, #email-subject'), function(e) {

                    let templateVarsErr = false, emailSubject = $('#email-subject').val(),
                        inputsErr = true,
                        errorMsg = "<?php _e('Please, fill in the form fields!', 'better-sharing-wp') ?>";

                    if( $('#title').val().length && $('#content').val().length && $('#email-subject').val().length ) {
                       inputsErr = false;
                    } 
                    // Validate preserved titles are not used and allow updating the default ETs content
                    defaultTemplates.forEach(element => { 
                        if ( $('#title').val().trim() == element.post_title && postId != element.ID ){  
                            inputsErr = true; 
                        }
                    }); 
                    if( emailSubject.indexOf('{{ referral_link }}') !== -1 
                            || emailSubject.indexOf('{{ sender_custom_message }}') !== -1 ) {
                        templateVarsErr = true;                        
                    }                

                    if( templateVarsErr || inputsErr ) {

                        if( templateVarsErr ) { 
                            errorMsg =  "<?php _e('Please, use only recipient/sender name(s) and greeting variables in Email Subject field!', 'better-sharing-wp') ?>"; 
                        }
                        if (inputsErr){
                            errorMsg =  "<?php _e('Please, check the email template title!', 'better-sharing-wp') ?>"; // do not allow saving as draft
                        }
                        btn.attr('disabled', true)
                        btn.attr( 'title',  errorMsg )
                    } else {

                        btn.attr('disabled', false)
                        btn.attr( 'title', '' )
                    }
                })
            })(jQuery);
        </script>
        <?php
        endif;
    }

    /**
     * Add ID Column to
     * Admin Email Template CPTs List. 
     * Used in the WP add_filter with 
     * the 'manage_posts_columns hook
     * 
     * @param array $columns
     * 
     * @return array modified $columns
     */
    public function add_id_column_to_admin_list( $columns ) {

        if ( 'bswp_email_template' == get_post_type() ) {

            $columns = array(                
                'cb' => $columns['cb'], 
                'bswp_email_template_id' => __('ID', 'better-sharing-wp' ),   
                'title' => __('Title', 'better-sharing-wp' ),
                'date' => __('Date', 'better-sharing-wp' ),    
            );
        }   

        return $columns;
    }

    /**
     * Print the value of the 
     * Email Template ID
     * in the Admin Email Template CPTs List. 
     * Used in the WP add_action with
     * the manage_posts_custom_column hook
     * 
     * @param string $column 
     * @param int $id
     */
    public function print_id_column_content( $column, $id ) {
        if ( 'bswp_email_template' == get_post_type() ) {    
            if ( 'bswp_email_template_id' == $column ) {
                echo esc_html($id);
            }
        }
    }

    /**
     * Make the CPT ID column
     * sortable. 
     * Used in the WP add_filter with
     * the manage_edit-bswp_email_template_sortable_columns hook
     * 
     * @param array $columns
     * 
     * @return array modified $columns
     */    
    public function set_sortable_id_column( $columns ) {

        if ( 'bswp_email_template' == get_post_type() ) {

            $columns['bswp_email_template_id'] = 'email_template_id';            
        }

        return $columns;
    }


   /**
    * Disable the autosave functionality
    * for the Email Template CPT 
    * 
    * @return void
    */
    public function my_admin_enqueue_scripts() {

        if ( 'bswp_email_template' == get_post_type() ) {

            wp_dequeue_script('autosave');
        }
    }

    /**
     * get all EmailTemplate CPTs
     * with status published
     */

     public static function get_all_email_templates(){
        return get_posts([
            'post_type' => 'bswp_email_template',
            'post_status' => 'publish',
            'numberposts' => -1
        ]);
    }

    /**
	 * Prepare email preview 
     * subject and body
	 *
     * @param array $settings
     * @param bool $is_admin_preview
     * @param string $referral_link
     * @param array $sender
     * @param array $custom_block_attributes
     * @return array
     */

	public function bswp_get_email_preview( $settings, $is_admin_preview, $referral_link, $sender, $custom_block_attributes = [] ){

		$templateID = isset( $settings['email_template'] ) ? $settings['email_template'] : null; 
        $email_message = isset( $settings['email_message_fallback'] ) ? $settings['email_message_fallback'] : '';
    
		// 1 get content
		if( $templateID && ( get_post_status( $templateID ) == 'publish' ) && get_post_type( $templateID ) == 'bswp_email_template' ) :

			$email_template_data = new Email();
			$email_body = $email_template_data->get_email_template( $templateID ); 
			$email_subject = get_post_meta( $templateID, 'bswp_email_subject', true );
			
	    else : 
        //default template
            $email_body     = $this->get_default_email_body();
            $email_subject  = $this->get_default_email_subject();
        endif;
        
		$parsed_email_body = $email_body;
		// to form the email preview
        // checks the email body.
        $has_template_vars    = $this->has_template_vars( $email_body ); 
        if ( !empty( $has_template_vars ) ) :
		    $parsed_email_body 	= $this->email_preview_replace_template_variables( $email_body, $sender, $is_admin_preview, $referral_link, $email_message );// @TODO move to one array param
        endif; 
		
        $parsed_email_subject   = $this->email_preview_replace_template_variables( $email_subject, $sender, $is_admin_preview );

        if ( !empty( $custom_block_attributes ) ) :
            $parsed_email_body    = $this->email_preview_replace_custom_template_variables( $parsed_email_body, $custom_block_attributes );
            $parsed_email_subject = $this->email_preview_replace_custom_template_variables( $parsed_email_subject, $custom_block_attributes );
        endif;
		
		return ['email_subject' => $parsed_email_subject, 
                'email_body'    => $parsed_email_body, 
                'has_template_vars' => $has_template_vars,]; 
	}
    /**
     * Scans for template variables
     * included in a string
     *
     * @param string $string
     * @return array
     */
    public function has_template_vars( $string ){
        
        $has_template_vars = [];

        $template_vars = include BETTER_SHARING_PATH . 'includes/config/email_template_variables.php';

        foreach ($template_vars as $var => $value) {
            $needle = '{{ ' . $var . ' }}';
           if( str_contains( $string, $needle ) )  array_push( $has_template_vars, $var );
        }

        return  $has_template_vars;
    }
	/**
	 * Replace Email Template CPT
	 * template variables
     * to form the Email Preview
	 * 
	 * @param string $parsable, the template string containing the variables to be parsed
	 * @param string $sender
     * @param bool $is_admin_preview
	 * @param string $referral_link 
	 * 
	 * @return string
	 */

	public function email_preview_replace_template_variables( $parsable, $sender, $is_admin_preview, $referral_link = '', $email_message = '' ){

		$default_str = include BETTER_SHARING_PATH . 'includes/config/email_preview.php';
		
        // decide for replacement: admin custom message or client custom message
		$custom_message = $is_admin_preview ? '' : '<span class="custom-message-wrapper"></span>';

        $parsable = str_replace( '{{ sender_custom_message }}', $custom_message, $parsable );

        // both will need a wrapping container, we expect a fe-replacement.
        $parsable = str_replace( '{{ email_message }}', "<span class='bswp-email-message-wrapper'>$email_message</span>", $parsable );

        if ( !empty( $referral_link ) ):

            $referral_link = filter_var( trim( $referral_link ) , FILTER_VALIDATE_URL );
            $parsable = str_replace( '{{ referral_link }}', '<a href="'.$referral_link.'" target="_blank" class="bswp-email-preview-ref-link">'.$referral_link.'</a>', $parsable );
        else:

            $parsable = str_replace( '{{ referral_link }}', '', $parsable );    		
        endif; 
        
        if ( !empty( $sender['first_name'] ) ):
            $replace =  '<span class="bswp-sender-name">' . $sender['first_name'] . '</span>'; 
        else:
            $replace =  '<span class="bswp-sender-name">' . $default_str['template_variables']['sender_first_name'] . '</span>';
        endif;

        $parsable = str_replace( '{{ sender_first_name }}', $replace, $parsable );    		

		//always replaced with defaults.
		$greeting = $default_str['template_variables']['default_contact_name']; 

		$parsable = str_replace( '{{ greeting }}', $greeting, $parsable );			
		
		return $parsable; 
	}
    /**
     * Search for custom template variable
     * and replace with the given string
     * @param string $parsable
     * @param array $custom_tempate_variables
     * @return string
     */
    public function email_preview_replace_custom_template_variables( $parsable, $custom_tempate_variables ){
        foreach ( $custom_tempate_variables as $id => $var ) :
            $pattern 	= "/{{\s?$id\s?}}/";
            $replacement 	= sanitize_text_field( $var );
            $parsable = preg_replace( $pattern, $replacement, $parsable );
        endforeach;
        return $parsable;
    }
    /**
     * Gets the default Email Template
     * edit link
     *
     * @return string|bool the link | false if no default email template
     */
    public function get_default_email_template_edit_link(){ 

        $default_email_template_ID = $this->get_default_email_template_id();

        if (  $default_email_template_ID ) : 
            return $this->bswp_get_post_edit_link( $default_email_template_ID ); // admin.  
        endif; 
        return false;
    }
    /**
	 * Get the BSWP CPT
	 * link to admin 
	 * edit CPT page
	 *
	 * @param int $post_ID
	 * @return string
	 */
	public function bswp_get_post_edit_link( $post_ID ){
		$action = '&action=edit';
		$edit_link = "post.php?post=%d";
		$link = admin_url( sprintf( $edit_link . $action, $post_ID ) );
		return $link;
	}
    /**
     * Checks and updates
     * the status of 
     * EmailTemplate
     *
     * @param int $email_template_ID
     * @param str $status
     * @return bool
     */
    public function update_email_template_status( $email_template_ID, $status ){
        // check post status
        $current_status = get_post_status( $email_template_ID ); // false on failure
        if ( $current_status ) :
            if ( $status == $current_status ) :
                return true;
            else :
                // wp_update_post( array|object $postarr = array(), bool $wp_error = false, bool $fire_after_hooks = true ): int|WP_Error
                $update_status = array(
                    'ID' => $email_template_ID,
                    'post_status' => $status
                );
                $status_update = wp_update_post($update_status);
                if ( ! is_wp_error(  $status_update  ) ) :
                    return true;
                endif; 
            endif;
        endif;
        return false;
    }
}