<?php
/**
 * 
 * @package   MasterSlider
 * @author    averta [averta.net]
 * @license   LICENSE.txt
 * @link      http://masterslider.com
 * @copyright Copyright © 2014 averta
*/

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}


class Axiom_Plugin_Check_Update
{
    /**
     * The plugin current version
     * @var string
     */
    public $current_version;

    /**
     * The plugin remote update path
     * @var string
     */
    public $update_path;

    /**
     * Plugin Slug (plugin_directory/plugin_file.php)
     * @var string
     */
    public $plugin_slug;

    /**
     * Plugin name (plugin_file)
     * @var string
     */
    public $slug;

    /**
     * The item name while requesting to update api
     * @var string
     */
    public $request_name;


    /**
     * The item name while requesting to update api
     * @var string
     */
    public $plugin_file_path;


    /**
     * Initialize a new instance of the WordPress Auto-Update class
     * @param string $current_version
     * @param string $update_path
     * @param string $plugin_slug
     * @param string $slug
     */
    function __construct( $current_version, $update_path, $plugin_slug, $slug, $item_request_name = '', $plugin_file = '' ) {
        // Set the class public variables
        $this->current_version  = $current_version;
        $this->update_path      = $update_path;
        $this->plugin_slug      = $plugin_slug;
        $this->slug             = $slug;

        $this->request_name     = empty( $item_request_name ) ? $this->slug : $item_request_name;

        $this->plugin_file_path = $plugin_file;

        // define the alternative API for updating checking
        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update') );

        // Define the alternative response for information checking
        add_filter( 'plugins_api', array( $this, 'check_info'), 10, 3 );
    }
    

    /**
     * Add our self-hosted autoupdate plugin to the filter transient
     *
     * @param $transient
     * @return object $ transient
     */
    public function check_update( $transient ) {
        
        if( apply_filters( 'masterslider_disable_auto_update', 0 ) )
            return $transient;

        // Get the remote version
        $remote_version = $this->get_remote_version();

        // echo '<pre>';
        // $isl = version_compare( $this->current_version, $remote_version, '<' );
        // echo 'current is less than remote? : ' . $this->current_version .' < '. $remote_version;
        // var_dump( $isl );
        // echo '</pre>';

        // If a newer version is available, add the update info to update transient
        if ( version_compare( $this->current_version, $remote_version, '<' ) ) {
            $obj = new stdClass();
            $obj->slug = $this->slug;
            $obj->plugin = $this->plugin_slug;
            $obj->new_version = $remote_version;
            $obj->url = '';
            $obj->package = '';
            $transient->response[ $this->plugin_slug ] = $obj;
        } elseif ( isset( $transient->response[ $this->plugin_slug ] ) ) {
            unset( $transient->response[ $this->plugin_slug ] );
        }
        return $transient;
    }


    /**
     * Return the remote version
     * @return string $remote_version
     */
    public function get_remote_version() {
        global $wp_version;

        $request = wp_remote_post( $this->update_path, array(
                'user-agent' => 'WordPress/'.$wp_version.'; '.get_bloginfo('url'),
                'body' => array(
                    'action' => 'version', 
                    'log'    => $this->request_name,
                    'live'   => 1
                ) 
            )
        );
        if ( ! is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200 ) {
            return $request['body'];
        }
        return false;
    }


    /**
     * Add our self-hosted description to the filter
     *
     * @param boolean $false
     * @param array $action
     * @param object $arg
     * @return bool|object
     */
    public function check_info( $false, $action, $arg ) {

        if( apply_filters( 'masterslider_disable_auto_update', 0 ) )
            return $false;

        if( ! isset( $arg->slug ) )
            return $false;

        if ( $arg->slug === $this->slug ) {
            $information = $this->get_remote_information();
            return apply_filters( 'axiom_pre_insert_plugin_info' . $this->slug , $information );
        }
        return $false;
    }


    /**
     * Get information about the remote version
     * @return bool|object
     */
    public function get_remote_information() {

        $request = wp_remote_post( $this->update_path, array(
                'body' => array(
                    'action' => 'info', 
                    'log'    => $this->request_name 
                ) 
            )
        );

        if ( !is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {

            $plugin_info_data = empty( $this->plugin_file_path ) ? array() : get_plugin_data( $this->plugin_file_path );

            $info = maybe_unserialize( $request['body'] );
            $info->slug = $this->slug;
            $info->plugin_name = isset( $plugin_info_data['Name'] )      ? $plugin_info_data['Name']      : '';
            $info->author      = isset( $plugin_info_data['Author'] )    ? $plugin_info_data['Author']    : '';
            $info->homepage    = isset( $plugin_info_data['PluginURI'] ) ? $plugin_info_data['PluginURI'] : '';

            return $info;
        }
        return false;
    }

    /**
     * Return the status of the plugin licensing
     * @return boolean $remote_license
     */
    public function get_remote_license() {

        $request = wp_remote_post( $this->update_path, array( 'body' => array('action' => 'license') ) );
        if ( !is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200 ) {
            return $request['body'];
        }
        return false;
    }
}