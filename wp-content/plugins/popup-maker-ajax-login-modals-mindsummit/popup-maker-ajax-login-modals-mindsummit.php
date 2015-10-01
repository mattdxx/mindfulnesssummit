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
	
	require plugin_dir_path(__FILE__).'options.php';
	require plugin_dir_path(__FILE__).'passresetredir.php';
	
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
				add_action('user_register', array($this, 'update_user'));
				add_filter('registration_errors', array($this, 'check_login'));
				add_action('wp_enqueue_scripts', array($this, 'fix_js'));
				add_action('wp_enqueue_scripts', array($this, 'fix_css'));
				add_action('wp_head', array($this, 'print_variables'));
			}
			
			public function print_variables()
			{
				$values = array(
					
					'regcapt' => get_option('popmake_login_regcapt'),
					'logcapt' => get_option('popmake_login_logcapt'),
					'reccapt' => get_option('popmake_login_reccapt'),
					
					'regtext' => get_option('popmake_login_regtext'),
					'regtext2' => get_option('popmake_login_regtext2'),
					
					'regphname' => get_option('popmake_login_regphname'),
					'regphemail' => get_option('popmake_login_regphemail'),
					'regphpass' => get_option('popmake_login_regphpass'),
					
					'logphemail' => get_option('popmake_login_logphemail'),
					'logphpass' => get_option('popmake_login_logphpass'),
					
					'recphemail' => get_option('popmake_login_recphemail')
					
					);
				echo '<script type="text/javascript" language="javascript">';
				echo 'window.popmake_login_appearance = '.json_encode($values);
				echo ';</script>';
			}
			
			public function update_user($user_id)
			{
				setcookie('mindsummitreg', '1');
				
				if ($_POST['popmake_reg']) // identifying registration process through ajax popup-window
				{
					$user_meta_data = array('ID' => $user_id);
					
					$display_name = array();
					if ($_POST['fname'])
						array_push($display_name, ($user_meta_data['first_name'] = $_POST['fname']));
					if ($_POST['lname'])
						array_push($display_name, ($user_meta_data['last_name'] = $_POST['lname']));
					if (count($display_name))
						$user_meta_data['display_name'] = join(' ', $display_name);
					
					if (count($user_meta_data) > 1)
						@wp_update_user($user_meta_data);
				}
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
			
			public function fix_css()
			{
				wp_enqueue_style(
					'popmake-ajax-login-modals-mindsummit-css',
					plugin_dir_url(__FILE__).'fix.css',
					array('parent-style', 'style', 'style-responsive', 'style-custom', 'popmake-ajax-login-modals-css')
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
