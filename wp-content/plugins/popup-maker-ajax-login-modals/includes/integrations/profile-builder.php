<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'PopMake_Ajax_Login_Modals_Integration_Profile_Builder' ) ) {

    /**
     * Main PopMake_Ajax_Login_Modals_Integration_Profile_Builder class
     *
     * @since       1.0.0
     */
    class PopMake_Ajax_Login_Modals_Integration_Profile_Builder {

    	public function __construct() {
            add_action( 'popmake_alm_ajax_override_registration', array( $this, 'ajax_registration_override' ) );
            add_filter( 'popmake_get_template_part', array( $this, 'registration_form_template' ), 10, 3 );
            remove_action( 'popmake_popup_ajax_registration_meta_box_fields', array( PopMake_Ajax_Login_Modals()->admin->metabox_fields, 'registration_enable_password' ), 20 );
    	}

        public function registration_form_template( $templates, $slug, $name ) {
            if( $slug !== 'ajax-registration-form' ) {
                return $templates;
            }
            return array_merge( array( 'ajax-registration-form-profile-builder.php' ), $templates );
        }

        public function popup_data_attr( $data_attr, $popup_id ) {
            global $popmake_registration_modal;
            if( $popup_id !== $popmake_registration_modal ) {
                return $data_attr;
            }
            return $data_attr;
        }

        public function ajax_registration_override() {
            require_once WPPB_PLUGIN_DIR.'/front-end/class-formbuilder.php';
            require_once WPPB_PLUGIN_DIR.'/front-end/register.php';
            $register = wppb_front_end_register_handler( array() );
            $errors = $register->wppb_test_required_form_values( $_REQUEST );
            ob_start();
            echo $register;
            $form = ob_get_clean();
            $response = array(
                'success' => false,
            );
            if( ! empty( $errors ) ) {
                $response['form'] = $form;
            }
            else {
                if( popmake_get_popup_ajax_registration( $_POST['popup_id'], 'enable_autologin' ) ) {
                    $creds = array(
                        'user_login' => $_POST['username'],
                        'user_password' => $_POST['passw1'],
                        'remember' => true
                    );
                    $user = wp_signon( $creds );
                }
                $response['success'] = true;
            }
            echo json_encode($response);
            die();
        }
    }

}
