<?php
/**
 * Plugin Name: Goodlayers Conference
 * Plugin URI: http://goodlayers.com/
 * Description: 
 * Version: 1.0.0
 * Author: Goodlayers
 * Author URI: http://goodlayers.com/
 * License: 
 */
	
	include_once('framework/ticket-option.php');
	include_once('framework/speaker-option.php');
	include_once('framework/session-option.php');

	include_once('include/speaker-item.php');
	include_once('include/session-item.php');
	include_once('include/ticket-item.php');
	
	// action to loaded the plugin translation file
	add_action('plugins_loaded', 'gdlr_lms_textdomain_init');
	if( !function_exists('gdlr_lms_textdomain_init') ){
		function gdlr_lms_textdomain_init() {
			load_plugin_textdomain('gdlr-conference', false, dirname(plugin_basename( __FILE__ ))  . '/languages/'); 
		}
	}	
	
?>