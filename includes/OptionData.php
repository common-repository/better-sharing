<?php

namespace BetterSharingWP;


class OptionData
{

    /**
     * @var global vars 
     */
    private $globalPrefix;
    private $isCore = false;

    /**
     * @var data vars 
     */
    private $objectType;
    private $prefix;

    /**
     * OptionData constructor.
     *
     * @param string $objectType
     * @param string $prefix
     */
    public function __construct( $objectType = '', $prefix = '' ) {

        $this->globalPrefix = '_bswp_option_';

        return $this->init( $objectType, $prefix );
    }

    /**
     * @param string $message
     * @param mixed  $data
     *
     * @return \WP_Error
     */
    protected function __permissionCheck( $message = 'Permission Error', $data = '' ) {

        // permissions check - admins only
        if ( ! current_user_can( 'manage_options' ) ) {
            return new \WP_Error( 401, __( $message ), $data );
        }

        return true;
    }

    /**
     * Initialize new OptionData Object
     *
     * @param string $objectType - can be slug of AddOn
     * @param string $prefix     - override prefix
     *
     * @return $this|\WP_Error|bool
     */
    public function init( $objectType = '', $prefix = '' ) {

        if (! empty( $objectType ) && 'core' === $objectType ) {

            $this->prefix = 'core';
            $this->isCore = true;

            return $this;
        }

        if ( empty( $objectType ) ) {

            return false;
        }

        $this->objectType = $objectType;
        $this->prefix = '' !== $prefix ? $prefix : sanitize_title($objectType);

        return $this;
    }

    /**
     * Get the data prefix
     * NOTE: does not force trailing underscore, so that will be up to the code architecture of the object
     *
     * @return string
     */
    private function __getPrefix() {

        return $this->globalPrefix . $this->prefix;
    }

    /**
     * Save Data
     *
     * @param $key
     * @param $value
     *
     * @return bool|\WP_Error
     */
    public function save( $key, $value ){

        $permission = $this->__permissionCheck( 'Error Saving DataObject', '' );
        
        if ( is_wp_error( $permission ) ) {
            return $permission;
        }

        if ( empty( $key ) ) {
            return new \WP_Error( 400, __( 'Missing Key' ), $key );
        }

        $key = sanitize_text_field( $key );
        $saveKey = $this->__getPrefix() . '_' . $key;

        return update_site_option( $saveKey, $value );
    }

    /**
     * Get Data
     *
     * @param $key
     *
     * @return mixed|\WP_Error
     */
    public function get( $key ) {

        $key = $this->__getPrefix() . '_' . $key;

        return get_site_option( $key, false );
    }

    /**
     * Delete Data
     *
     * @param $key
     *
     * @return bool|\WP_Error
     */
    public function delete( $key ) {

        // permissions check - admins only
        $permission = $this->__permissionCheck( 'Permission Error Get Data' );

        if ( is_wp_error( $permission ) ) {
            return $permission;
        }

        return delete_site_option( $key = $this->__getPrefix() . '_' . $key );
    }

    /**
     * Delete ALL data for this object
     *
     * @param bool $check
     *
     * @return \WP_Error | bool
     */
    public function deleteAll( $check = false ) {

        // permissions check - admins only
        $permission = $this->__permissionCheck('Permission Error Get Data');

        if (is_wp_error($permission) || false === $check ) {
            return $permission;
        }

        global $wpdb;
        
        $prefix = $this->globalPrefix;
        $plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '$prefix%'" );

        if( empty( $plugin_options ) ) {

            return false;
        }

        foreach( $plugin_options as $option ) {

            delete_option( $option->option_name );
        }
        
        return true;
    }
}