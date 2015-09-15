<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'PopMake_Ajax_Login_Modals_Integration_WPMembers' ) ) {

    /**
     * Main PopMake_Ajax_Login_Modals_Integration_WPMembers class
     *
     * @since       1.0.0
     */
    class PopMake_Ajax_Login_Modals_Integration_WPMembers {

    	public function __construct() {
    		if( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['register'] ) ) {
				add_filter( 'wpmem_msg_dialog_arr', array( $this, 'ajax_dialog_messages' ), 10, 2 );
    		}
            add_action( 'popmake_alm_ajax_override_registration', array( $this, 'ajax_registration_override' ) );
            add_action( 'wpmem_post_register_data', array( $this, 'fetch_register_user_id' ) );
            add_filter( 'popmake_get_template_part', array( $this, 'registration_form_template' ), 10, 3 );
            remove_action( 'popmake_popup_ajax_registration_meta_box_fields', array( PopMake_Ajax_Login_Modals()->admin->metabox_fields, 'registration_enable_password' ), 20 );
    	}

        public function registration_form_template( $templates, $slug, $name ) {
            if( $slug !== 'ajax-registration-form' ) {
                return $templates;
            }
            return array_merge( array( 'ajax-registration-form-wp-members.php' ), $templates );
        }

        public function fetch_register_user_id( $fields ) {
            if( popmake_get_popup_ajax_registration( $_POST['popup_id'], 'enable_autologin' ) ) {
                $creds = array(
                    'user_login' => $fields['username'],
                    'user_password' => $fields['password'],
                    'remember' => true
                );
                $user = wp_signon( $creds );
            }
        }

    	public function ajax_dialog_messages( $defaults, $toggle ) {
			$defaults =  array_merge( $defaults, array(
				'div_before' => '',
				'div_after'  => '', 
				'p_before'   => '',
				'p_after'    => '',
			) );
			return $defaults;
    	}

        public function ajax_registration_override() {
            global $wpmem_regchk, $wpmem_themsg;

            if ( version_compare( WPMEM_VERSION, '3', '>=' ) ) {
                require_once WPMEM_PATH . 'inc/dialogs.php';
            }
            else {
                require_once WPMEM_PATH . 'wp-members-dialogs.php';
            }

            $message = wpmem_inc_regmessage( $wpmem_regchk, $wpmem_themsg );

            $response = array(
                'success' => false,
                'message' => $message,
            );

            if( $wpmem_regchk != 'success' ) {
                $field = str_ireplace( array( 'Sorry', 'is a required field', '.', ',' ), '', $message );
                $field = str_ireplace( array( '<br />', ' ' ), '_', trim( strtolower( $field ) ) );

                switch( $field ) {
                    case 'username':
                    case 'that_username_is_taken_please_try_another':
                        $field = 'log';
                        break;

                    case 'you_must_enter_a_valid_email_address':
                    case 'that_email_address_already_has_an_account_please_try_another':
                        $field = 'user_email';
                        break;

                    case 'address_1':
                        $field = 'addr1';
                        break;

                    case 'state':
                        $field = 'thestate';
                        break;

                    case 'day_phone':
                        $field = 'phone1';
                        break;
                }

                $field = apply_filters( 'popmake_wpmembers_ajax_registration_error_field', $field, $message );

                $response['field'] = $field;
            }
            else {
                $response['success'] = true;
            }

            echo json_encode($response);
            die();
        }
    }

}
