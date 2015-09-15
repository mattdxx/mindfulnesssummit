<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'PopMake_Ajax_Login_Modals_Upgrades' ) ) {

    /**
     * Main PopMake_Ajax_Login_Modals_Upgrades class
     *
     * @since       1.1.0
     */
    class PopMake_Ajax_Login_Modals_Upgrades {

        public function upgrade_v1_1_0() {
            global $wpdb;
            $key_changes = array(
                'popup_ajax_login_enabled'                       => 'popup_ajax_login_enabled',
                'popup_ajax_login_force_login'                   => 'popup_ajax_login_force_login',
                'popup_ajax_login_allow_remember'                => 'popup_ajax_login_allow_remember',
                'popup_ajax_login_disable_redirect'              => 'popup_ajax_login_disable_redirect',
                'popup_ajax_login_login_redirect_url'            => 'popup_ajax_login_redirect_url',
                'popup_ajax_login_login_loading_text'            => NULL,
                'popup_ajax_login_registration_enabled'          => 'popup_ajax_registration_enabled',
                'popup_ajax_login_registration_enable_password'  => 'popup_ajax_registration_enable_password',
                'popup_ajax_login_registration_enable_autologin' => 'popup_ajax_registration_enable_autologin',
                'popup_ajax_login_registration_disable_redirect' => 'popup_ajax_registration_disable_redirect',
                'popup_ajax_login_registration_redirect_url'     => 'popup_ajax_registration_redirect_url',
                'popup_ajax_login_registration_loading_text'     => NULL,
                'popup_ajax_login_recovery_enabled'              => 'popup_ajax_recovery_enabled',
                'popup_ajax_login_recovery_disable_redirect'     => 'popup_ajax_recovery_disable_redirect',
                'popup_ajax_login_recovery_redirect_url'         => 'popup_ajax_recovery_redirect_url',
                'popup_ajax_login_recovery_loading_text'         => NULL,
            );
            foreach( $key_changes as $old => $new ) {
                if( ! $new ) {
                    $wpdb->delete( $wpdb->postmeta, array( 'meta_key' => $old ) );
                }
                else {
                    $wpdb->update( $wpdb->postmeta, array( 'meta_key' => $new ), array( 'meta_key' => $old ) );
                }
            }
        }

    }
} // End if class_exists check