<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'PopMake_Ajax_Login_Modals_Admin_Popup_Metaboxes' ) ) {

    /**
     * Main PopMake_Ajax_Login_Modals_Admin_Popup_Metaboxes class
     *
     * @since       1.0.0
     */
    class PopMake_Ajax_Login_Modals_Admin_Popup_Metaboxes {

        public function register() {
            /** AJAX Login Modals Meta **/
            add_meta_box( 'popmake_popup_ajax_login_modals', __( 'AJAX Login Settings', 'popup-maker-ajax-login-modals' ),  array( $this, 'ajax_login_meta_box' ), 'popup', 'normal', 'high' );
            /** AJAX Login Modals Meta **/
            add_meta_box( 'popmake_popup_ajax_registration_modals', __( 'AJAX Registration Settings', 'popup-maker-ajax-login-modals' ),  array( $this, 'ajax_registration_meta_box' ), 'popup', 'normal', 'high' );
            /** AJAX Login Modals Meta **/
            add_meta_box( 'popmake_popup_ajax_recovery_modals', __( 'AJAX Recovery Settings', 'popup-maker-ajax-login-modals' ),  array( $this, 'ajax_recovery_meta_box' ), 'popup', 'normal', 'high' );
        }

        public function meta_fields( $fields ) {
            return array_merge( $fields, array(
                'popup_ajax_login_modals_defaults_set',
            ) );
        }

        public function meta_field_groups( $groups ) {
            return array_merge( $groups, array(
                'ajax_login',
                'ajax_registration',
                'ajax_recovery',
            ) );
        }

        public function meta_field_group_ajax_login( $fields ) {
            return array_merge( $fields, array(
                'enabled',
                'force_login',
                'allow_remember',
                'disable_redirect',
                'redirect_url',
            ) );
        }

        public function meta_field_group_ajax_registration( $fields ) {
            return array_merge( $fields, array(
                'enabled',
                'enable_password',
                'enable_autologin',
                'disable_redirect',
                'redirect_url',
            ) );
        }

        public function meta_field_group_ajax_recovery( $fields ) {
            return array_merge( $fields, array(
                'enabled',
                'disable_redirect',
                'redirect_url',
            ) );
        }


        public function login_defaults( $defaults ) {
            return array_merge( $defaults, array(
                'enabled' => NULL,
                'force_login' => NULL,
                'allow_remember' => NULL,
                'disable_redirect' => NULL,
                'redirect_url' => '',
            ));
        }

        public function registration_defaults( $defaults ) {
            return array_merge( $defaults, array(
                'enabled' => NULL,
                'enable_password' => NULL,
                'enable_autologin' => NULL,
                'disable_redirect' => NULL,
                'redirect_url' => '',
            ));
        }

        public function recovery_defaults( $defaults ) {
            return array_merge( $defaults, array(
                'enabled' => NULL,
                'disable_redirect' => NULL,
                'redirect_url' => '',
            ));
        }


        /**
         * Popup AJAX Login Modals Metabox
         *
         * Extensions (as well as the core plugin) can add items to the popup display
         * configuration metabox via the `popmake_popup_ajax_login_modals_meta_box_fields` action.
         *
         * @since 1.0
         * @return void
         */
        public function ajax_login_meta_box() {
            global $post; ?>
            <input type="hidden" name="popup_ajax_login_modals_defaults_set" value="true" />
            <div id="popmake_popup_ajax_login_modals_fields" class="popmake_meta_table_wrap">
                <table class="form-table">
                    <tbody>
                        <?php do_action( 'popmake_popup_ajax_login_meta_box_fields', $post->ID );?>
                    </tbody>
                </table>
            </div><?php
        }



        public function ajax_registration_meta_box() {
            global $post; ?>
            <div id="popmake_popup_ajax_registration_modals_fields" class="popmake_meta_table_wrap">
                <table class="form-table">
                    <tbody>
                        <?php do_action( 'popmake_popup_ajax_registration_meta_box_fields', $post->ID );?>
                    </tbody>
                </table>
            </div><?php
        }


        public function ajax_recovery_meta_box() {
            global $post; ?>
            <div id="popmake_popup_ajax_recovery_modals_fields" class="popmake_meta_table_wrap">
                <table class="form-table">
                    <tbody>
                        <?php do_action( 'popmake_popup_ajax_recovery_meta_box_fields', $post->ID );?>
                    </tbody>
                </table>
            </div><?php
        }


    }
} // End if class_exists check