<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'PopMake_Ajax_Login_Modals_Ajax' ) ) {

    /**
     * Main PopMake_Ajax_Login_Modals_Ajax class
     *
     * @since       1.0.0
     */
    class PopMake_Ajax_Login_Modals_Ajax {

        public function ajax_call() { 
            // Check our nonce and make sure it's correct.
            check_ajax_referer( 'popmake-alm-nonce', 'nonce' );

            // Check that we are submitting the login form
            if( isset( $_REQUEST['login'] ) ) {

                $this->process_login();

            }
            // Check if we are submitting the register form
            elseif( isset( $_REQUEST['register'] ) ) {

                $this->process_registration();

            }
            // Check if we are submitting the forgotten pwd form
            elseif( isset( $_REQUEST['recovery'] ) ) {

                $this->process_recovery();

            }
        }

        public function process_login() {
            do_action( 'popmake_alm_ajax_override_login' );

            $secure_cookie = false;

            if( ! empty( $_POST['log'] ) && ! force_ssl_admin() ) {
                $user_name = sanitize_user( $_POST['log'] );
                if( $user = get_user_by( 'login', $user_name ) ) {
                    if( get_user_option( 'use_ssl', $user->ID ) ) {
                        $secure_cookie = true;
                        force_ssl_admin( true );
                    }
                }
            }

            $user = wp_signon( '', $secure_cookie );

            // Check the results of our login and provide the needed feedback
            if( is_wp_error( $user ) ) {
                $response = array(
                    'success' => false,
                    'message'  => __( 'Wrong Email or Password!', 'popup-maker-ajax-login-modals' ),
                );
            } else {
                $response = array(
                    'success' => true,
                    'message'  => __( 'Login Successful!', 'popup-maker-ajax-login-modals' ),
                );
            }

            echo json_encode($response);
            die();
        }

        public function process_registration() {
            do_action( 'popmake_alm_ajax_override_registration' );

            $user_login = $_POST['user_login'];
            $user_email = $_POST['user_email'];
            $user_pass = isset( $_POST['user_pass'] ) ? $_POST['user_pass'] : wp_generate_password( 12, false );

            $userdata = compact( 'user_login', 'user_email', 'user_pass' );

            $user = wp_insert_user( $userdata );

            if( ! isset( $_POST['user_pass'] ) ) {       
                update_user_option( $user, 'default_password_nag', true, true ); // Set up the Password change nag.
                wp_new_user_notification( $user, $user_pass );
            }

            if( is_wp_error( $user ) ) {
				
				$response_error_message = $user->get_error_message();
				if ($user->get_error_code() == 'existing_user_email')
					$response_error_message = 
						'Sorry, that email address is already registered, please <a href="/wp-login.php" onclick="jQuery(\'.popmake-registration-form,.popmake-recovery-form\').slideUp();jQuery(\'.popmake-login-form\').appendTo(jQuery(\'.popmake-login-form\').parent()).slideDown();return false;">login here</a>';

                $response = array(
                    'success' => false,
                    'message'   => $response_error_message,
                );
            }
            else {
                if( popmake_get_popup_ajax_registration( $_POST['popup_id'], 'enable_autologin' ) ) {
                    $creds = array(
                        'user_login' => $user_login,
                        'user_password' => $user_pass,
                        'remember' => true
                    );
                    $user = wp_signon( $creds );
                }
                $message = __( 'Registration complete.', 'popup-maker-ajax-login-modals' );
                if( ! isset( $_POST['user_pass'] ) ) {
                    $message .= ' ' . __( 'Please check your e-mail.', 'popup-maker-ajax-login-modals' );
                }
                $response = array(
                    'success' => true,
                    'message'   => $message,
                );
            }

            echo json_encode($response);
            die();
        }

        public function process_recovery() {
            do_action( 'popmake_alm_ajax_override_recovery' );

            // Check if we are sending an email or username and sanitize it appropriately
            if( is_email( $_REQUEST['user_login'] ) ) {
                $username = sanitize_email( $_REQUEST['user_login'] );
            }
            else {
                $username = sanitize_user( $_REQUEST['user_login'] );
            }
            // Send our information
            $user_forgotten = $this->retrieve_password( $username );
            // Check if there were any errors when requesting a new password
            if( is_wp_error( $user_forgotten ) ) {
				
				$response_error_message = $user_forgotten->get_error_message();
				if ($user_forgotten->get_error_code() == 'invalid_email')
					$response_error_message = 
						"This email address isn’t registered for a 'Access Pass' - <a href=\"/wp-login.php\" onclick=\"jQuery('.popmake-login-form,.popmake-recovery-form').slideUp();jQuery('.popmake-registration-form').appendTo(jQuery('.popmake-registration-form').parent()).slideDown();return false;\">register here</a>";

				
                $response = array(
                    'reset'      => false,
                    'message' => $response_error_message,
                );
            }
            else {
                $response = array(
                    'reset'   => true,
                    'message' => __( 'Password Reset. Please check your email.', 'popup-maker-ajax-login-modals' ),
                );
            }

            echo json_encode( $response );
            die();
        }

        public function retrieve_password( $user_data ) {
            global $wpdb;
            $errors = new WP_Error();
            if( empty( $user_data ) ) {
                $errors->add( 'empty_username', __( 'Please enter a username or e-mail address.', 'popup-maker-ajax-login-modals' ) );
            } else if( strpos( $user_data, '@' ) ) {
                $user_data = get_user_by( 'email', trim( $user_data ) );
                if( empty( $user_data ) )
                    $errors->add( 'invalid_email', __( 'There is no user registered with that email address.', 'popup-maker-ajax-login-modals'  ) );
            } else {
                $login = trim( $user_data );
                $user_data = get_user_by( 'login', $login );
            }
            do_action( 'lostpassword_post' );
            if( $errors->get_error_code() )
                return $errors;
            if( ! $user_data ) {
                $errors->add( 'invalidcombo', __( 'Invalid username or e-mail.', 'popup-maker-ajax-login-modals' ) );
                return $errors;
            }
            // redefining user_login ensures we return the right case in the email
            $user_login = $user_data->user_login;
            $user_email = $user_data->user_email;
            do_action( 'retreive_password', $user_login );  // Misspelled and deprecated
            do_action( 'retrieve_password', $user_login );
            $allow = apply_filters( 'allow_password_reset', true, $user_data->ID );
            if( ! $allow )
                return new WP_Error( 'no_password_reset', __( 'Password reset is not allowed for this user', 'popup-maker-ajax-login-modals' ) );
            else if( is_wp_error( $allow ) )
                return $allow;
            
            // Generate something random for a key
            $key = wp_generate_password( 20, false );
            do_action( 'retrieve_password_key', $user_login, $key );
            
            // Now insert the key, hashed, into the DB.
            global $wp_hasher;
            if (empty($wp_hasher)) {
                require_once ABSPATH . WPINC . '/class-phpass.php';
                $wp_hasher = new PasswordHash( 8, true );
            }
            $hashed = $wp_hasher->HashPassword( $key );
            $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );
            

            $message = __( 'Someone requested that the password be reset for the following account:', 'popup-maker-ajax-login-modals' ) . "\r\n\r\n";
            $message .= network_home_url( '/' ) . "\r\n\r\n";
            $message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
            $message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', 'popup-maker-ajax-login-modals' ) . "\r\n\r\n";
            $message .= __( 'To reset your password, visit the following address:', 'popup-maker-ajax-login-modals' ) . "\r\n\r\n";
            $message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";
            if( is_multisite() ) {
                $blogname = $GLOBALS['current_site']->site_name;
            } else {
                // The blogname option is escaped with esc_html on the way into the database in sanitize_option
                // we want to reverse this for the plain text arena of emails.
                $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
            }
            $title   = sprintf( __( '[%s] Password Reset' ), $blogname );
            $title   = apply_filters( 'retrieve_password_title', $title );
            $message = apply_filters( 'retrieve_password_message', $message, $key );
            if( $message && ! wp_mail( $user_email, $title, $message ) ) {
                $errors->add( 'noemail', __( 'The e-mail could not be sent. Possible reason: your host may have disabled the mail() function.', 'popup-maker-ajax-login-modals' ) );
                return $errors;
                wp_die();
            }
            return true;
        }

    }
} // End if class_exists check

