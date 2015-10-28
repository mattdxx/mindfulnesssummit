<?php
/*
Plugin Name: Optimize Wordpress
Description: Optimize wordpress to speed up the whole process
Version: 1.5.15
Author: Stratos Nikolaidis
Author URI: https://gr.linkedin.com/in/stratosnikolaidis
Plugin URI: https://gr.linkedin.com/in/stratosnikolaidis
License: Toptal
*/

/**
 * Fix Mandrill reset password issue
 * Fix provided by @paulveevers
 */
add_filter( 'retrieve_password_message', 'forgot_password_email', 10, 2 );
function forgot_password_email($message, $key) {

  // Replace first open bracket
  $message = str_replace('<', '', $message);

  // Replace second open bracket
  $message = str_replace('>', '', $message);

  // Convert line returns to <br>'s
  $message = str_replace("\r\n", '<br>', $message);

  return $message;
}

/**
 * Disable plugins for specific pages in order to speed up the page load
 */
// add_filter( 'option_active_plugins', 'disable_plugins_on_demand' );
function disable_plugins_on_demand($plugins){
	// Template
    // if(strpos($_SERVER['REQUEST_URI'], '/store/') === FALSE AND strpos($_SERVER['REQUEST_URI'], '/wp-admin/') === FALSE) {
    //     $key = array_search( 'cart66/cart66.php' , $plugins );
    //     if ( false !== $key ) {
    //         unset( $plugins[$key] );
    //     }
    // }
    return $plugins;
}

require_once(dirname(__FILE__) . "/popup_login.php");
require_once(dirname(__FILE__) . "/gdlr_image_switcher.php");
