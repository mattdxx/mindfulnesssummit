<?php

/**
 * Plugin Name: MailChimp AutoSubscribe for MindSummit
 * Description: Adds user to the MailChimp List right after registration made through 'Popup Maker - AJAX Login Modals'
 * Version: 0.1
 * Author: Gerasimov Eugene
 * Author URI: http://jspot.ru/
 * Text Domain: mailchimp-autosubscribe-mindsummit
*/

    # Exit if accessed directly
    if (!defined('ABSPATH'))
      exit;
    
    if (!class_exists('MailChimp_AutoSubscribe_MindSummit'))
    {
        class MailChimp_AutoSubscribe_MindSummit
        {
            private static $instance;
            
            public static function instance()
            {
                if (!self::$instance)
                {
                    self::$instance = new MailChimp_AutoSubscribe_MindSummit();
                    self::$instance->hooks();
                }
                return self::$instance;
            }
            
            private function hooks()
            {
                add_action('user_register', array($this, 'subscribe'));
                add_action('user_register', array($this, 'track'));
                add_action('wp_enqueue_scripts', array($this, 'mark_form'));
            }

            public function mark_form()
            {
                wp_enqueue_script(
                    'mailchimp-autosubscribe-mark-form-js',
                    plugin_dir_url(__FILE__).'mark_form.js?defer',
                    array('popmake-ajax-login-modals-js')
                    );
            }

			// Track in segment.io
			public function track( $user_id )
			{
				if ( $user = get_userdata( $user_id ) ) {
					$identify = array(
						'user_id' => $user->ID,
						'traits'  => array(
							'username'  => $user->user_login,
							'email'     => $user->user_email,
							'firstName' => $user->user_firstname,
							'lastName'  => $user->user_lastname,
						)
					);
					Analytics::track( 'User registered', $identify );
				}
			}
				
            public function subscribe($user_id)
            {
                # credentials
                $API_KEY = '1b6ab216f0fa8372bfef36666c479495-us6';
                $LIST_ID = '56890d6052';
                
                # check if special key (mark) exists
                if (!array_key_exists('mailchimp_autosubscribe', $_REQUEST))
                    return;
                
                # extract datacenter name
                $datacenter = '';
                $splitted_api_key = explode('-', $API_KEY, 2);
                if (count($splitted_api_key) == 2 and preg_match('/^[a-zA-Z0-9-]+$/', $splitted_api_key[1]))
                    $datacenter = $splitted_api_key[1];
                if (!$datacenter) // FIXME: here should be a handler
                    return;
                
                # request subscription
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_URL, "https://$datacenter.api.mailchimp.com/3.0/lists/$LIST_ID/members");
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, "apikey:$API_KEY");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
                        'email_address' => $_REQUEST['user_email'],
                        'status' => 'subscribed'
                    )));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                # check response
                // FIXME: should there be a checker for $http_code and $resonse?
            }
            
        } # class MailChimp_AutoSubscribe_MindSummit
        
    } # if class_exists
    
    function MailChimp_AutoSubscribe_MindSummit_Load()
    {
        if (class_exists('PopMake_Ajax_Login_Modals'))
            MailChimp_AutoSubscribe_MindSummit::instance();
    }
    add_action('plugins_loaded', 'MailChimp_AutoSubscribe_MindSummit_Load');

?>
