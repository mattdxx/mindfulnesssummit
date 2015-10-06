<?php
/*
Plugin Name: Disable Lost Password email notification
Description: Disable Lost Password email notification
Version: 1.0
Author: Stratos Nikolaidis
Author URI: https://gr.linkedin.com/in/stratosnikolaidis
Plugin URI: https://gr.linkedin.com/in/stratosnikolaidis
License: Toptal
*/


if (!function_exists( 'wp_password_change_notification')) {
	function wp_password_change_notification() {}
}