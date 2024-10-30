<?php
/**
 * UITemplate - Custom Post Type
 *
 */

namespace BetterSharingWP\AdminScreens;

use BetterSharingWP\AdminScreens\EmailTemplate;
use BetterSharingWP\BSWP_DemoPage;


class UITemplate
{     
     /**
     * Used to store
     * the |UITemplate CPT
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
     * The default UI template title
     *
     * @var string
     */
    private $default_ui_template_title;
    /**
     * The default UI temlate settings
     *
     * @var array
     */
    private $default_ui_template_settings;

    /**
     * Demo link to use
     * in Social Share module
     */

    private $demo_permalink = 'https://domain.com/custom-path/';

    public function __construct() {

        add_action( 'init', array( $this, 'register_ui_template_cpt' ) );
        add_action('save_post', array( $this, 'save_ui_template_meta_data' ) );    

        add_action('pre_post_update', array( $this, 'cpt_validate' ), 10, 2 );        
        add_action('admin_notices', array( $this, 'bswp_ui_template_notifications' ), 10, 1 );
        
        add_action('admin_footer', array( $this, 'toggle_publish_button' ) );
        $this->set_default_ui_template_title();
        $this->set_default_ui_template_settings();
    }

    /**
     * Load UI Template
     * Hooks
     * 
     * @return void
     */
    public function init() {

        add_action('add_meta_boxes', array( $this, 'dynamic_add_ui_template_metabox' ));

        add_filter('use_ui_template_editor_for_post_type', array( $this, 'disable_gutenberg_posts' ), 10, 2);  
        
        add_action('admin_enqueue_scripts', array( $this, 'my_admin_enqueue_scripts' ) );

        add_action( 'rest_api_init', array( $this, 'add_custom_field') );
        add_action( 'rest_api_init', array( $this, 'rest_init' ) );
        //add ui template shortcode in cpt admin list
        add_filter('manage_posts_columns', array( $this, 'add_shortcode_column_to_admin_list' ), 5 );

        add_action( 'load-edit.php', function() {
            add_filter( 'views_edit-bswp_ui_template', array( $this, 'bswp_blocks_helper') );
        });
    }
    /**
     * Shows section
     * with basic getting started 
     * BSWP information
     *
     * @return void
     */
    public function bswp_blocks_helper( $views ) {  
        $add_new_block_link = admin_url('post-new.php?post_type=bswp_ui_template'); // always available link.
        $add_email_template_link =  admin_url('post-new.php?post_type=bswp_email_template'); // always available link.
        $config = include BETTER_SHARING_PATH . 'includes/config/general.php'; // always available link.
        
        $email_template = new EmailTemplate();
        $default_email_template_link = $email_template->get_default_email_template_edit_link(); // string | false, if no default email template

        $demo_page = new BSWP_DemoPage();
        $sample_page_link = $demo_page->get_demo_page_edit_link(); // string | false, if no demo page   
        $sample_page_view = $demo_page->get_demo_page_view_link(); // string | false, if no demo page   
       
        include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/blocks-helper.php';     

        add_action('manage_posts_custom_column', array( $this, 'print_shortcode_column_content' ), 5, 2 );
        return $views;
    }
    /**
	 * Rest Init
	 *
	 * @return void
	 */
	public function rest_init() {

		register_rest_route(
			'bswp/v1',
			'/bswp-ui-templates',
			array(
				'methods'  => array( 'GET' ),
				'callback' => array( $this, 'rest_get_ui_templates' ), 
				'permission_callback' => '__return_true'
			)			
		);
	}
    /**
     * Registers
     * the UITemplate as a CPT
     *
     * @return void
     */
    public function register_ui_template_cpt() {

        $labels = array(
            'name'          => __('Better Sharing', 'better-sharing-wp'),
            'singular_name' => __('Block', 'better-sharing-wp'),
            'add_new'       => __('Add Better Sharing Block', 'better-sharing-wp'),
            'add_new_item'  => __('Add New Block', 'better-sharing-wp'),
            'edit_item'     => __('Block', 'better-sharing-wp'),
            'new_item'      => __('Block', 'better-sharing-wp'),
            'view_item'     => __('View Block', 'better-sharing-wp'),
            'search_items'  => __('Search Blocks', 'better-sharing-wp'),
            'not_found'     => __('No Blocks', 'better-sharing-wp'),
            'not_found_in_trash' => __('No Blocks found in Trash', 'better-sharing-wp'),
        );
        $args = array(
            'labels'        =>  $labels,
            'description'   => __('BSWP UI Template custom post type', 'better-sharing-wp'),
            'public'        => true,
            'hierarchical'  => false,
            'exclude_from_search'   => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_menu'          => false,
            'show_in_nav_menus'     => true, 
            'capability_type'       => 'post',
            'supports'              => array('title'),  
            'has_archive'           => false,
            'delete_with_user'      => false,
            'show_in_rest'          => true
        );

        register_post_type('bswp_ui_template', $args);
    }
    /**
     * Adds Blocks 
     * submenu item
     * to BSWP Admin Menu
     *
     * @return void
     */
    public function add_to_submenu(){
        add_submenu_page(
			'better-sharing-wp',
            __('Blocks', 'better-sharing-wp'), // page title.
            __('Blocks', 'better-sharing-wp'), // menu title.
			'edit_pages',
			'edit.php?post_type=bswp_ui_template',
		);
    }

    /**
     * Sets a value for
     * $default_ui_template_settings
     * class property
     *
     * @return void
     */
    public function set_default_ui_template_settings(){
        $default_settings = include BETTER_SHARING_PATH . 'includes/config/ui_template.php';
        $this->default_ui_template_settings = $default_settings;
    }
    /**
     * Sets a value for the
     * $default_ui_template_title
     * class property
     *
     * @return string
     */
    public function set_default_ui_template_title(){
        $this->default_ui_template_title = 'Default Block';
    }
    /**
     * Gets the value for the
     * $default_ui_template_title
     * class property
     *
     * @return array
     */
    public function get_default_ui_template_settings(){
        return $this->default_ui_template_settings;
    }
    /**
     * Gets a value for the
     * $default_ui_template_title
     * class property
     *
     * @return string
     */
    public function get_default_ui_template_title(){
        return $this->default_ui_template_title;
    }
    /**
     * Gets or Creates an UI Template
     * with default content and meta data
     * on plugin activation
     *
     * @param arr $settings
     * @return int UI template ID
     */
    public function create_default_ui_template( $settings ){
        $result = $this->get_default_ui_template_id( $settings ); // exit if true with the result( the UI ID). 
        // create the default ui template.
        if ( !$result ) : 
            $postarr = array(
                'post_content' => '',
                'post_title'   => $settings['post_title'],
                'post_status'  => 'publish',
                'post_type'    => 'bswp_ui_template',
                'comment_status' => 'closed',
                'post_name'    => $settings['post_title'], // post slug.
            );
            // save default post and get ID.
            $result = wp_insert_post( $postarr, true );// returns ID on success, WP_errorr on failure.
            if ( $result ) :
                $meta_data = base64_encode( serialize( $this->shrink_default_ui_template_settings( $settings ) ) ); 
                add_post_meta( 
                    $result, // post ID
                    'bswp_ui_template_settings', 
                    $meta_data
                );// returns int|false Meta ID on success, false on failure.
            endif;
        endif;
        return $result;
    }
    /**
     * Get template UI ID
     * the template that has
     * the default settings and title
     *
     * @param arr $settings
     * @param str $post_status
     * @return int ID|0
     */
    public function get_default_ui_template_id( $settings, $post_status = 'any' ){  
       
        $block = $this->post_exists( $settings['post_title'], 'bswp_ui_template' );  // UI templates with this title.  

        if ( $block ) :  
            return $block; // the post ID. 
        endif;

       return 0;
    }
    /**
     * Checks and updates
     * the status of 
     * BSWP block
     *
     * @param int $block_ID
     * @param str $status
     * @return bool
     */
    public function update_block_status( $block_ID, $status ){
        // check post status
        $current_status = get_post_status( $block_ID ); // false on failure
        if ( $current_status ) :
            if ( $status == $current_status ) :
                return true;
            else :
                // wp_update_post( array|object $postarr = array(), bool $wp_error = false, bool $fire_after_hooks = true ): int|WP_Error
                $update_status = array(
                    'ID' => $block_ID,
                    'post_status' => $status
                );
                $status_update = wp_update_post( $update_status );
                if ( ! is_wp_error(  $status_update  ) ) :
                    return true;
                endif; 
            endif;
        endif;
        return false;
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
     * Check the probable default
     * ui template meta exists.
     * ( UI template meta data
     * holds its settings )
     * If meta exists, 
     * this is the default UI template
     *
     * @param int $ui_template_ID
     * @param arr $settings
     * @return void
     */
    public function default_ui_template_meta_exists( $ui_template_ID, $settings ){
        $check = $this->shrink_default_ui_template_settings( $settings );
        $bswp_ui_template_meta = $this->get_bswp_ui_template_settings( $ui_template_ID );  
        if ( $check  === $bswp_ui_template_meta ) :        
            return true;
        endif;
        return false;
    }
    /**
     * Get only the values
     * used in template UI CPT meta
     * 'bswp_ui_template_settings' 
     *
     * @param arr $settings
     * @return array
     */
    public function shrink_default_ui_template_settings( $settings){
        $settings_check = [];

        $settings_check["view_style"]                   = $settings['view_style'];
        
        $settings_check["url_to_share"]["link_type"]    = $this->default_ui_template_settings["url_to_share"]["link_type"];
        $settings_check["url_to_share"]["custom_link"]  = $this->default_ui_template_settings["url_to_share"]["custom_link"]; 
        
        $settings_check["social_share"]["order"]        = $this->default_ui_template_settings["social_share"]["order"]; 
        $settings_check["social_share"]["enabled"]      = $this->default_ui_template_settings["social_share"]["enabled"]; 
        $settings_check["social_share"]["title"]        = $this->default_ui_template_settings["social_share"]["title"]; 
        $settings_check["social_share"]["subtitle"]     = $this->default_ui_template_settings["social_share"]["subtitle"]; 
        $settings_check["social_share"]["fb_enabled"]   = $this->default_ui_template_settings["social_share"]["fb_enabled"];  
        $settings_check["social_share"]["twitter_enabled"] = $this->default_ui_template_settings["social_share"]["twitter_enabled"];  
        $settings_check["social_share"]["twitter_msg"]  = $this->default_ui_template_settings["social_share"]["twitter_msg"];  
        
        $settings_check["referral_link"]["order"]       = $this->default_ui_template_settings["referral_link"]["order"];  
        $settings_check["referral_link"]["enabled"]     = $this->default_ui_template_settings["referral_link"]["enabled"];  
        $settings_check["referral_link"]["title"]       = $this->default_ui_template_settings["referral_link"]["title"];  
        $settings_check["referral_link"]["subtitle"]    = $this->default_ui_template_settings["referral_link"]["subtitle"];   
        
        $settings_check["email"]["order"]           = $this->default_ui_template_settings["email"]["order"];   
        $settings_check["email"]["enabled"]         = $this->default_ui_template_settings["email"]["enabled"];   
        $settings_check["email"]["title"]           = $this->default_ui_template_settings["email"]["title"];   
        $settings_check["email"]["subtitle"]        = $this->default_ui_template_settings["email"]["subtitle"];   
        $settings_check["email"]["emails_input_placeholder"]    = $this->default_ui_template_settings["email"]["emails_input_placeholder"];   
        $settings_check["email"]["message_placeholder"]         = $this->default_ui_template_settings["email"]["message_placeholder"];   
        $settings_check["email"]["send_btn_text"]               = $this->default_ui_template_settings["email"]["send_btn_text"];   
        $settings_check["email"]["email_template"]          = strval( $settings["email"]["email_template"] );   
        $settings_check["email"]["email_preview"]           = $this->default_ui_template_settings["email"]["email_preview"];   
        $settings_check["email"]["email_message_fallback"]  = $this->default_ui_template_settings["email"]["email_message_fallback"];   
        $settings_check["email"]["contact_picker_config"]   = $this->default_ui_template_settings["email"]["contact_picker_config"];  

        return $settings_check;
    }
     /**
     * Get a list 
     * of all default 
     * BSWP blocks
     *
     * @return arr
     */
    function get_default_blocks() {
        global $wpdb;
        $config = include BETTER_SHARING_PATH . 'includes/config/general.php';  
        $default_post_titles = $config['preserved_titles']['blocks']; 
    
        $query = "SELECT ID, post_title FROM $wpdb->posts WHERE "; 

        if ( ! empty( $default_post_titles ) ) :
            $query .= "post_title IN (" . "'" . implode("','", $default_post_titles) . "'" . ") ";  
        endif;  

        $query .= " AND post_type = 'bswp_ui_template'"; 
        $results = $wpdb->get_results( $query,  ARRAY_A);

        return $results;
    }
    /**
     * Allows bswp_ui_template_settings meta
     * to be seen in REST API request
     */
    public function add_custom_field() {
        register_rest_field( 'bswp_ui_template',
            'bswp_ui_template_settings',
            array(
                'get_callback'  => array( $this, 'rest_get_post_meta_field'),
                'update_callback'   => null,
                'schema'            => null,
            )
        );
    }

    /**
     * Callback to add bswp_ui_template settings
     * to be fetched in REST API
     * @param $post integer
     * @param $field_name string meta key
     * @param $request boolean return single value
     */

    function rest_get_post_meta_field( $post, $field_name, $request ) {
        
        $meta_data = get_post_meta( $post[ 'id' ], $field_name, true );
       
        $unserialized_metadata = @unserialize(base64_decode( $meta_data) );
        if( $unserialized_metadata === false ){
             $unserialized_metadata = unserialize( $meta_data );
        }
        
        // inject email preview content in response
        if ( isset( $unserialized_metadata['email']) ) : 
            
            $preview_content = $this->bswp_get_email_preview( $unserialized_metadata );
            $unserialized_metadata['email']['preview_content'] = $preview_content; 
        endif; 
        $json_encoded_data = json_encode(  $unserialized_metadata ); 
        return $json_encoded_data;
    }

    /**
     * Disable the Gutenberg Page builder
     * for the BS Block CPT edit screen
     * used in the WP add_filter with 
     * the use_block_editor_for_post_type hook
     *
     * @param str $current_status
     * @param str $post_type
     * 
     * @return str
     */
    public function disable_gutenberg_posts( $current_status, $post_type ) {
        
        // Disabled post types
        $disabled_post_types = array( 'bswp_ui_template' );
       
        if ( in_array( $post_type, $disabled_post_types, true ) ) {

            $current_status = false;
        }

        return $current_status;
    }

    /**
     * Add the UITemplate CPT
     * metabox to display the block settings
     * Used in add_action WP function with
     * the add_meta_boxes hook
     * 
     * @return void
     */    
    public function dynamic_add_ui_template_metabox() {

        add_meta_box(
            'bswp_ui_template_settings',
             ' ', 
             array( $this, 'load_ui_template_settings_metabox' ), 'bswp_ui_template'
            );
    }

    public function get_bswp_ui_template_settings( $post_id ){
        
        $bswp_ui_template_settings = @unserialize( base64_decode(get_post_meta( $post_id, 'bswp_ui_template_settings', true )) );  
        
        //saved without base64 encoding will throw notice
      		
        if( $bswp_ui_template_settings === false ){

			$bswp_ui_template_settings = unserialize( get_post_meta( $post_id, 'bswp_ui_template_settings', true ) );
		}
        return $bswp_ui_template_settings;
    }
    /**
     * Display the UITemplate Settings Screen
     * in the UITemplate CPT
     * add/edit form
     * 
     * @return void
     */
    public function load_ui_template_settings_metabox() {   

        global $post;
        $bswp_ui_template_settings = $this->get_bswp_ui_template_settings( $post->ID );
        $default_email_ID = 0;
        // sort block by order.
        if( !empty( $bswp_ui_template_settings ) ) :
            //to display the modules in saved order only
            $sorted_ui_template_settings  = $this->sort_by_order( $bswp_ui_template_settings ); 
           
            $email_preview = $this->bswp_get_email_preview( $bswp_ui_template_settings );
       
        else :
            // case new UI template, no settings yet
            // so get email preview with default template
            $email_preview = $this->bswp_get_email_preview();
            $email_template  = new EmailTemplate();
            $default_email_ID = $email_template->get_default_email_template_id();
        endif; 
        $email_templates = EmailTemplate::get_all_email_templates();   
        
        include BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'ui-template/modules-container.php';        
    }

     /**
     * Sort UI Template modules 
     * by their order setting
     * saved in DB
     *
     * @param array $settings UI Template meta data 
     * 
     * @return array $sorted sorted by order key value of the $settings array
     */

    public function sort_by_order( $settings ){

        $sorted = [];

        foreach ( $settings as $module_name => $module_settings ) {
            
            if ( is_array($module_settings) ) :
            //save module name
                $module_settings['module_name'] = $module_name;
            
                if( isset( $module_settings['order'] ) ) :

                    $sorted[$module_settings['order']] = $module_settings;
                endif;
            endif;          
        }

        ksort( $sorted );
        
        return $sorted;
    }
     

    /**
     * Disable the autosave functionality
     * for the UITemplate CPT 
     * 
     * @return void
     * 
     */
    public function my_admin_enqueue_scripts() {

        if ('bswp_ui_template' == get_post_type() ) {

            wp_dequeue_script('autosave');
        }
    }

    /**
     * Save the UITemplate CPT
     * and meta data in the database
     *
     * @param int $post_id
     * 
     * @return void
     */
    public function save_ui_template_meta_data( $post_id ) {

        if( $this->save_template_as_draft ) :
          
            remove_action('save_post', array( $this, 'save_ui_template_meta_data' ) );
            
            wp_update_post(
                array(
                    'ID' => $post_id,
                    'post_status' => 'draft',
                 )
            );
          
           add_action('save_post', array( $this, 'save_ui_template_meta_data' ) );
            
        endif;
       
        if( isset( $_POST['bswp_ui_template_settings'] ) ) : 
            
            $is_valid_meta = $this->validate_cpt_meta( $post_id, $_POST['bswp_ui_template_settings'] );

            if ( $is_valid_meta ) :
                $safe_setttings_data = $this->sanitize_before_saving( $_POST['bswp_ui_template_settings'] );
                $bswp_ui_template_settings = base64_encode( serialize( $safe_setttings_data ) ); 

                update_post_meta( 
                    $post_id, 
                    'bswp_ui_template_settings', 
                    $bswp_ui_template_settings
                );
            endif;
        endif;         
    }
    /**
     * Validates UI template's
     * meta data
     *
     * @param arr $meta_data
     * @param int $post_id 
     * @return boolean
     */
    public function validate_cpt_meta( $post_id, $meta_data ){
        $error_messages = [];
        // email template ID is available when email module is activated.     
        if ( !empty ( $meta_data['email']['enabled'] ) ) :
            if( empty( $meta_data['email']['email_template'] ) ) : 
                $error_messages[] = __('Email Template is required!', 'better-sharing-wp' );
            endif; 
        endif; 
        
        if( !empty( $error_messages ) ) :   
            update_option( '_bswp_option_ui_template_notifications', json_encode( array( 'error', $error_messages ) ) ); 
            remove_action('save_post', array( $this, 'save_ui_template_meta_data' ) ); // prevent infinite calling of current method on cpt save.
            // save post as draft.
            wp_update_post(
                array(
                    'ID' => $post_id,
                    'post_status' => 'draft',
                    )
            );
            return false;
        endif;

        return true;
    }
    /**
     * Removes backslashes
     * WP automatically escapes post data
     * which leads to undesired backslashes displayed 
     * 
     * The data is escaped before displayed in 
     * the browser
     * 
     * @param array $data the post data to be filtered for backslashes
     */
    
   public function sanitize_before_saving( $post_data ){

    foreach( $post_data as $key=>$data ){
        //stripslashes to text input fields
        if( isset( $post_data[$key]['custom_link'] ) ){
            $post_data[$key]['custom_link'] = stripslashes($post_data[$key]['custom_link']);
        }
        if( isset( $post_data[$key]['title'] ) ){
            $post_data[$key]['title'] = stripslashes($post_data[$key]['title']);
        }
        if( isset( $post_data[$key]['subtitle'] ) ){
            $post_data[$key]['subtitle'] = stripslashes($post_data[$key]['subtitle']);
        }
        if( isset( $post_data[$key]['twitter_msg'] ) ){
            $post_data[$key]['twitter_msg'] = stripslashes($post_data[$key]['twitter_msg']);
        }
        if( isset( $post_data[$key]['emails_input_placeholder'] ) ){
            $post_data[$key]['emails_input_placeholder'] = stripslashes($post_data[$key]['emails_input_placeholder']);
        }
        if( isset( $post_data[$key]['message_placeholder'] ) ){
            $post_data[$key]['message_placeholder'] = stripslashes($post_data[$key]['message_placeholder']);
        }
        if( isset( $post_data[$key]['send_btn_text'] ) ){
            $post_data[$key]['send_btn_text'] = stripslashes($post_data[$key]['send_btn_text']);
        }
    }
    
    return $post_data;
   }

    /**
     * Activate/Deactivate the CPT Publish Button 
     * based on form validation result
     * 
     * @return void
     */
    public function toggle_publish_button() {
        global $post;

        $screen = get_current_screen();

        if( $screen->base == 'post' && get_post_type() == 'bswp_ui_template' ) :
            $default_blocks = $this->get_default_blocks(); // @TODO check when no default nlocks
            $default_blocks_str = wp_json_encode( $default_blocks );  
        ?>
        <script>
            (function ($) {
                const VALIDATION_ERRORS = {
                    title: {
                        type: 'title',
                        message: 'Please, fill in the title!'
                    },
                    titleNotAllowed: {
                        type: 'title not allowed',
                        message: 'This title is not allowed!'
                    },
                    emailTemplate: {
                        type: 'email template',
                        message: 'Email Template is required!'
                    }
                }
                const postId = "<?php echo $post->ID; ?>";
                const defaultBlocks = <?php echo $default_blocks_str; ?>; 
                var btn = $('#publish'), errors = [], btnTitle,
                togglePublishButton = () => { 
                    if (errors.length){
                        btn.attr('disabled', true);
                        btn.attr('title', errors[0].message );
                    } else {
                        btn.attr('disabled', false);
                        btn.attr('title', '' );
                    }
                },
                removeError = (errType, arr) => {
                    const index = arr.findIndex(
                                (i) => i.type === errType
                            )
                    if (index !== -1) {
                        arr.splice(
                            index, 1
                        );
                    }   
                };
                if( !$('#title').val() ) {
                    errors.push(VALIDATION_ERRORS.title)
                }
               
                // Validate preserved UIT titles are not used, allow updating default UITs.
                defaultBlocks.forEach(element => {
                    if($('#title').val().trim() == element.post_title && postId != element.ID){
                        const hasErrTitleNotAllowed = errors.find(err => err.type == VALIDATION_ERRORS.titleNotAllowed.type) 
                        if ( !hasErrTitleNotAllowed ){
                            errors.push(VALIDATION_ERRORS.titleNotAllowed)
                        }
                    }
                });
                
                if ($('#bswp-email-enabled').val() != 0) {
                    if (!$('#bswp-email-template').val()){
                        errors.push(VALIDATION_ERRORS.emailTemplate)
                    }
                }
                togglePublishButton();
                $('#post').on('input', ('#title'), function(e) { 
                    if( $('#title').val().trim().length ) { 
                        removeError(VALIDATION_ERRORS.title.type, errors)
                    } else {
                        const hasErrTitle = errors.find(err => err.type == VALIDATION_ERRORS.title.type) 
                        if ( !hasErrTitle ){
                            errors.push(VALIDATION_ERRORS.title)
                        } 
                            removeError(VALIDATION_ERRORS.titleNotAllowed.type, errors) 
                    }
                  
                    // Validate preserved UIT titles are not used, allow updating default UITs.
                    let validTitleUsed = true;
                    defaultBlocks.forEach(element => {
                        if($('#title').val().trim() == element.post_title && postId != element.ID){
                            const hasErrTitleNotAllowed = errors.find(err => err.type == VALIDATION_ERRORS.titleNotAllowed.type) 
                            validTitleUsed = false;
                            if ( !hasErrTitleNotAllowed ){
                                errors.push(VALIDATION_ERRORS.titleNotAllowed)
                            }
                        }
                    });
                    if (validTitleUsed){
                        removeError(VALIDATION_ERRORS.titleNotAllowed.type, errors)
                    }

                   togglePublishButton();
                })
                $('*[data-tab="bswp-email"]').on('change', '.bswp-module-enable input, #bswp-email-template', function(e) {

                    if ($('#bswp-email-enabled').val() != 0) {
                        if (!$('#bswp-email-template').val()){
                            const hasErrET = errors.find(err => err.type == VALIDATION_ERRORS.emailTemplate.type)
                            if ( !hasErrET ){
                                errors.push(VALIDATION_ERRORS.emailTemplate)
                            }
                        } else {
                            removeError(VALIDATION_ERRORS.emailTemplate.type, errors)
                        }
                    } else {
                        const index = errors.findIndex(
                                (i) => i.type === VALIDATION_ERRORS.emailTemplate.type
                            )
                            if (index !== -1) {
                                errors.splice(
                                    index, 1
                                );
                            }   
                    }
                    togglePublishButton();
                })
                
            })(jQuery);
        </script>
        <?php
        endif;
    }

    /**
     * Check the database
     * for an existing UI Template Title
     *
     * @param int $post_id
     * @param array $post_data
     * 
     * @return void
     */
    public function cpt_validate( $post_id, $post_data ) {  
        
        if ( 'bswp_ui_template' == get_post_type() ) {
           
            if( !empty( $post_data['post_status'] ) ) {

                if( $post_data['post_status'] != 'trash' ) {

                    $error_messages = [];
                    
                    if( !empty( $post_data['post_title'] ) ) {

                        $duplicate_template_title = get_page_by_title( $post_data['post_title'], OBJECT, 'bswp_ui_template' );
                    
                        if( !empty( $duplicate_template_title && $duplicate_template_title->ID != $post_id ) ) {   

                            $error_messages[] = __('Block with the same title already exists!', 'better-sharing-wp' );
                        }
                    }
               
                    if( !empty( $error_messages ) ) {       

                        $this->save_template_as_draft = true;
                        update_option( '_bswp_option_ui_template_notifications', json_encode( array( 'error', $error_messages ) ) ); 
                    
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
    public function bswp_ui_template_notifications() {
        
        if ( 'bswp_ui_template' == get_post_type() ) {

            $notifications = get_option('_bswp_option_ui_template_notifications');

            if ( !empty( $notifications ) ) {

                if ( 'bswp_ui_template' == get_post_type() ) { 

                    $notifications = json_decode($notifications);
                    
                    foreach( $notifications[1] as $error_message ) {

                        echo '<div class="notice notice-error is-dismissible">';
                        echo '<p>' . esc_html( $error_message ) . '</p>';
                        echo '</div>';  
                    }

                    echo '<div class="notice notice-warning is-dismissible">';
                    echo '<p>' . __('The block was saved as <b>draft</b>', 'better-sharing-wp' ) . '</p>';
                    echo '</div>';                  
                  ?>
                    <style>
                        .updated {
                            display: none
                        }
                    </style>
                  <?php

                    update_option('_bswp_option_ui_template_notifications', false);
                }
            }
        }
    }

    /**
	 * Create intent url for the UI Template.
	 *
	 * @param string $intent_url Social sharing intent URL w/o permalink.
	 *
	 * @return string
	 */
	public function create_intent_url( $intent_url ) {

		return str_replace( '{{permalink}}', $this->demo_permalink, $intent_url );
	}

    /**
     * Add UI template shortcode Column to
     * UI Templates Admin List
     * Used in the WP add_filter with 
     * the 'manage_posts_columns hook
     * 
     * @param array $columns
     * 
     * @return array modified $columns
     */
    public function add_shortcode_column_to_admin_list( $columns ) {

        if ( 'bswp_ui_template' == get_post_type() ) {
            $columns = array(                
                'cb' => $columns['cb'],                   
                'title' => __('Title', 'better-sharing-wp' ),
                'bswp_ui_template_shortcode' => __('Shortcode', 'better-sharing-wp'  ), 
                'bswp_ui_template_wrapper_css' => __('Layout Wrapper CSS Class', 'better-sharing-wp'  ), 
                'date' => __('Date', 'better-sharing-wp' ),    
            );
        }   

        return $columns;
    }

    /** shortcodevalue of 
    * the BS UI template
     * in the Admin UI Template CPTs List. 
     * Used in the WP add_action with
     * the manage_posts_custom_column hook
     * 
     * @param string $column 
     * @param int $id
     */
    public function print_shortcode_column_content( $column, $id ) {

        if ( 'bswp_ui_template' == get_post_type() ) {    

            if ( 'bswp_ui_template_shortcode' == $column ) {

                echo "[better-sharing id=" . esc_attr($id) . "]";
            }

            if ( 'bswp_ui_template_wrapper_css' == $column ) {

                echo ".bswp-" . esc_attr($id);
            }
        }
    }

    /**
	 * Prepare email preview content
	 *
	 * @param array $settings 
	 * @return object
	 */
	public function bswp_get_email_preview( $settings = null ){
        $email_settings = [];
        if ( $settings ) :
            $email_settings = $settings['email'];
        endif;

        // get referral link, $settings['url_to_share'] -> get the ref link 
        $referral_link = $this->get_referral_link( $settings );
        $sender = $this->get_email_sender();
        // $email_message = 

        $email_template  = new EmailTemplate();
        $email_preview   = $email_template->bswp_get_email_preview( $email_settings, true, $referral_link, $sender );

        return $email_preview; 
    }

    /**
     * Rest endpoint callback method
     * 
     * @param $data array
     * @return json string - stringified email preview subject & body
     */
    public function bswp_get_template( $data ){
        
        $email_templateID = $data->get_param( 'eid' );// email template id.
        $ui_templateID    = $data->get_param( 'uiid'); // ui template id.
        // shape the email_settings param.
        $email_settings = [];
        if ( $email_templateID ) :
            $email_settings['email_template']           = $email_templateID;
        endif;
        if ( $ui_templateID ) : 
            $email_settings['email_message_fallback']   = $this->get_email_message_fallback( $ui_templateID );
        endif;

        // default referral link
        $referral_link = $this->get_referral_link();
        $sender = $this->get_email_sender();

        $email_template  = new EmailTemplate();
        $email_preview   = $email_template->bswp_get_email_preview( $email_settings, true, $referral_link, $sender);

        return json_encode( $email_preview );
    }
    /**
     * Helper method
     *
     * @param string $ui_templateID
     * @return string
     */
    public function get_email_message_fallback( $ui_templateID ){
        $email_message_fallback = '';

        $bswp_ui_template_settings = $this->get_bswp_ui_template_settings( $ui_templateID );
        if ( $bswp_ui_template_settings ) :
            $email_message_fallback = $bswp['email']['email_message_fallback'];
        endif;
        return $email_message_fallback;
    }

    /**
     * Helper method
     * prepares the url to share value 
     * 
     * @param array|null $settings
     * @return string
     */
    public function get_referral_link( $settings = null ){
        $ui_template_defaults = include BETTER_SHARING_PATH . 'includes/config/ui_template.php';
         // get referral link, $settings['url_to_share'] -> get the ref link 
        $referral_link = get_site_url() . $ui_template_defaults['url_to_share']['default_page_endpoint'];// default 
        
        if( !empty( $settings ) ) :
                
            if( $settings['url_to_share']['link_type'] == "custom_url" ) : 
                $referral_link = trim( esc_url( $settings['url_to_share']['custom_link'] ) );
            endif;
        endif; 

        return $referral_link;
    }

    /**
     * Helper method
     * prepares the sender 
     * (first and last name) data
     *
     * @return array
     */
    public function get_email_sender(){

        $str_defaults = include BETTER_SHARING_PATH . 'includes/config/email_preview.php';

        // get sender data i.e. the currently logged in user
        $loggedin_user = wp_get_current_user(); 

		if( $loggedin_user->ID ) :

			$first_name = get_user_meta( $loggedin_user->ID, 'first_name', true );
			$last_name  = get_user_meta( $loggedin_user->ID, 'last_name', true );
		endif;

        $sender = [];

        $sender['first_name'] = isset( $first_name ) ? $first_name : $str_defaults['template_variables']['sender_first_name'];

        return $sender;
    }

    /**
     * Gets all published 
     * UI templates of a selected type
     *
     * @param [type] $type
     * @return array UI template ID and name
     */
    public function get_ui_templates( $type ){
        $compact_templates = [];
        $templates = get_posts([
            'post_type' => 'bswp_ui_template',
            'post_status' => 'publish',
            'numberposts' => -1,
          ]);
        
        if ( ! empty( $templates ) ) :
            foreach ($templates as $template) :
                $data = $this->get_bswp_ui_template_data( $template->ID );
                if ( $type === $data['view_style'] ) :
                    
                    $current_template = [ 'id' => $template->ID, 'name' => $template->post_title ];
                    array_push( $compact_templates, $current_template );
                endif;
            endforeach;
        endif;

        return $compact_templates;
    }

     /**
     * Callback for the '/bswp-ui-templates' 
     * Gets all published 
     * UI templates of a selected type 
     * @param [type] $type
     * @return array UI template ID and name
     */
    public function rest_get_ui_templates( $data){
        $type = $data->get_param('type');
        $result_templates = [];
        $templates = get_posts([
            'post_type' => 'bswp_ui_template',
            'post_status' => 'publish',
            'numberposts' => -1,
          ]);
        
        if ( ! empty( $templates ) ) :
            foreach ($templates as $template) :
                $data = $this->get_bswp_ui_template_data( $template->ID );
                if ( $type === $data['view_style'] ) :
                    
                    $current_template = [ 'id' => $template->ID, 'name' => $template->post_title ];
                    array_push( $result_templates, $current_template );
                endif;
            endforeach;
        endif;

        return  $result_templates;
    }

    /**
	 * Gets BSWP UI Template CPT's
	 * settings
	 * @param integer $template_id
	 * @return array $template_data if template_id is 0 or non existing, 
	 * the method returns the default BSWP UI Template CPT Settings
	 */
	public function get_bswp_ui_template_data( $template_id ){	 
		//user passed an id
		if( $template_id ){

			$template_data = @unserialize(base64_decode( get_post_meta( $template_id, 'bswp_ui_template_settings', true ) ) ); 	
			//saved without base64 encoding will throw notice
			if( $template_data === false ){

				$template_data = unserialize( get_post_meta( $template_id, 'bswp_ui_template_settings', true ) );
			}
			
			//template data doesn't exists, load default settings
			if( !$template_data ){
				//no valid template id provided, default settings are loaded
				$template_data = $this->default_ui_template_settings;
			}

		} else {
			//no template id provided, default settings are loaded
			$template_data = $this->default_ui_template_settings;
		}

		return $template_data;
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
}