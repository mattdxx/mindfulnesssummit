<?php

/**
 * Plugin Name: Popup Maker - AJAX Login Modals Fix for MindSummit
 * Description: Plugin makes some fixes into 'Popup Maker - AJAX Login Modals'
 * Version: 0.1
 * Author: Gerasimov Eugene
 * Author URI: http://jspot.ru/
 * Text Domain: popup-maker-ajax-login-modals-mindsummit
*/


    # Exit if accessed directly
    if (!defined('ABSPATH'))
      exit;
    
    if (!class_exists('PopMake_Ajax_Login_Modals_MindSummit'))
    {
        class PopMake_Ajax_Login_Modals_MindSummit
        {
            private static $instance;
            
            public static function instance()
            {
                if (!self::$instance)
                {
                    self::$instance = new PopMake_Ajax_Login_Modals_MindSummit();
                    self::$instance->hooks();
                }
                return self::$instance;
            }
            
            private function hooks()
            {
                //add_action('user_register', array($this, 'add_cookie'));
                add_filter('registration_errors', array($this, 'check_login'));
                add_action('wp_enqueue_scripts', array($this, 'fix_js'));
            }
			
            public function add_cookie($user_id)
            {
            	setcookie('mindsummitreg', '1');
            }
			
			public function check_login($errors, $uname, $email)
			{
				if ($uname != $email)
				{
					//$errors->add('incorrect_login', "Login must be the same as the Email address");
				}
				return $errors;
			}

            public function fix_js()
            {
                wp_enqueue_script(
                    'popmake-ajax-login-modals-mindsummit-js',
                    plugin_dir_url(__FILE__).'fix.js?defer',
                    array('popmake-ajax-login-modals-js')
                    );
            }
            
        } # class PopMake_Ajax_Login_Modals_MindSummit
        
    } # if class_exists
    
    function PopMake_Ajax_Login_Modals_MindSummit_Load()
    {
        if (class_exists('PopMake_Ajax_Login_Modals'))
            PopMake_Ajax_Login_Modals_MindSummit::instance();
    }
    add_action('plugins_loaded', 'PopMake_Ajax_Login_Modals_MindSummit_Load');

?>
