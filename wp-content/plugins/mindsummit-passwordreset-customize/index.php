<?php

/**
 * Plugin Name: MindSummit Password Reset Customize
 * Description: Customizing password reset emails
 * Version: 0.1
 * Author: Gerasimov Eugene
 * Author URI: http://jspot.ru/
 * Text Domain: mindsummit-passwordreset-customize
*/


	# Exit if accessed directly
	if (!defined('ABSPATH'))
		exit;
	
	require plugin_dir_path(__FILE__).'options.php';
	
	if (!class_exists('MindSummit_PasswordReset_Customize'))
	{
		class MindSummit_PasswordReset_Customize
		{
			private static $instance;
			
			public static function instance()
			{
				if (!self::$instance)
				{
					self::$instance = new MindSummit_PasswordReset_Customize();
					self::$instance->hooks();
				}
				return self::$instance;
			}
			
			private function hooks()
			{
				add_filter('retrieve_password_title', array($this, 'change_email_title'));
			}
			
			public function change_email_title($old_title)
			{
					$title = get_option('mindsummit_passwordreset_title');
					return $title ? $title : $old_title;
			}
			
		} # class MindSummit_PasswordReset_Customize
		
	} # if class_exists
	
	MindSummit_PasswordReset_Customize::instance();
	
?>
