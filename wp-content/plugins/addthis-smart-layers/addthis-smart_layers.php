<?php
/*
 * Plugin Name: AddThis Smart Layers
 * Description: AddThis Smart Layers. Make your site smarter. Increase traffic, engagement and revenue by instantly showing the right social tools and content to every visitor. 
 * Version: 1.0.10
 * Author: The AddThis Team
 * Author URI: http://www.addthis.com/blog
 * Plugin URI: http://www.addthis.com
 * License: GPL2
*/

define('ADDTHIS_SMART_LAYER_PRODUCT_CODE', 'wpp-1.0.10');
define('ADDTHIS_SMART_LAYER_AT_VERSION', 300);

function insert_smart_layer() {
	require('views/smart_layer_include.php');
}

if(get_option('smart_layer_activated') == '1') {
	global $pagenow;
	if( $pagenow != 'wp-login.php' && $pagenow != 'wp-register.php' && !is_admin() ) {
		add_action('wp_footer', 'insert_smart_layer');
	}
}

function init_smart_layer_config() {
	add_option( "smart_layer_activated", "0", '', 'yes' );
	add_option( "smart_layer_settings", "{}", '', 'yes' );
	add_option( "smart_layer_settings_advanced","0",'','yes');
	register_setting('smart_layer_activated','smart_layer_activated');
	register_setting('smart_layer_settings','smart_layer_settings');
	register_setting('smart_layer_settings_advanced','smart_layer_settings_advanced');
	register_setting('smart_layer_profile', 'smart_layer_profile');
	register_deactivation_hook( __FILE__, 'smart_layer_deactivate' );
}
add_action('admin_init', 'init_smart_layer_config');

function smart_layer_admin() {
    if (get_option('smart_layer_settings_advanced') != '0') {
        $smart_layer_pro = get_option('smart_layer_pro');
        if ($smart_layer_pro) {
            require("views/smart_layer_admin.php");
        } else {
            require("views/smart_layer_advanced.php");
        }
    } else {
        require("views/smart_layer_admin.php");
    }
}

function smart_layer_admin_menu() {
	$imgLocationBase = apply_filters( 'smart_files_uri',  plugins_url( '' , basename(dirname(__FILE__)))) . '/addthis-smart-layers/img/'  ;

	if ($_SERVER['QUERY_STRING'] == 'page=addthis-smart_layers.php' || $_SERVER['QUERY_STRING'] == 'page=addthis-smart_layers.php&settings-updated=true' ) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('qtip_script', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/js/jquery.qtip.min.js');
		wp_enqueue_script('smart_layer_script', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/js/gtcCommon.js');
		wp_enqueue_script('widget_script', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/js/jqueryui.widgetfactory.js');
		wp_enqueue_script('selectbox_script', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/js/jquery.selectBoxIt.min.js');
		wp_enqueue_script('lodash_script', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/js/lodash-0.10.0.js');
		wp_enqueue_script('bootstrap_script', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/js/bootstrap.js');
		wp_enqueue_script('ibutton_script', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/js/ibutton.js');
		//wp_enqueue_script('animate_script', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/js/animate-colors.js');
		wp_enqueue_script('placeholder_script', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/js/jquery.placeholder.js');
		wp_enqueue_script('gtc_smart_layer_script', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/js/gtc.smart-layers.js');
		wp_enqueue_script('smart_layer_modal_script', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/js/smart_layer_modal.js');
		wp_enqueue_script('prettify', 'https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js');
		
		wp_enqueue_style('bootstrap_style', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/css/bootstrap.css');
		wp_enqueue_style('wp-jquery-ui-dialog');
		wp_enqueue_style('gtc_style', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/css/gtc.css');
		wp_enqueue_style('selectbox_style', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/css/jquery.selectBoxIt.css');
		wp_enqueue_style('ibutton_style', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/css/ibutton.css');
		wp_enqueue_style('gtc_smart_layer_style', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/css/gtc.smart-layers.css');
		wp_enqueue_style('smart_layer_style', plugins_url( '', basename(dirname(__FILE__)) ) . '/addthis-smart-layers/css/addthis-smart_layer.css');
		
		wp_localize_script( 'gtc_smart_layer_script', 'smart_layer_params', array('wp_ajax_url'=> admin_url('admin-ajax.php'), 'img_base' => $imgLocationBase) );
		wp_localize_script( 'smart_layer_modal_script', 'smartlayer_param', array('ajax_url'=> admin_url('admin-ajax.php')) );
	}
}
add_action('admin_menu','smart_layer_admin_menu');

function smart_layer_admin_actions() {
    at_smart_layer_is_pro_user();
    update_option('smart_layer_activated', '1');
    add_options_page("AddThis Smart Layers", "AddThis Smart Layers", 'manage_options', basename(__FILE__), "smart_layer_admin");
}

add_action('admin_menu', 'smart_layer_admin_actions');  

add_action("wp_ajax_save_smart_layer_settings", "save_smart_layer_settings");


function strip_if_needed($value) {
	if (get_magic_quotes_gpc()) {
    	$value = stripslashes($value);
	}
	return $value;
}

function save_settings() {
    if (current_user_can('manage_options')) {
        $value = isset($_POST['value']) ? strip_if_needed($_POST['value']) : '';
        $id = isset($_POST['profileId']) ? $_POST['profileId'] : '';
        if (!at_smart_layer_is_pro_user($id)) {
            if ( !$value ) {
                update_option('smart_layer_settings', "{}");
            } else {
                update_option('smart_layer_settings', "$value");
            }
        }
        global $addthis_addjs;
        $addthis_addjs['profile'] = $id;
        update_option('smart_layer_profile', "$id");
        die('{"value":"' . $value . '"}');
    }
}

function save_smart_layer_settings() {
	//Wait till plugabble.php is loaded to check user capability
	add_action('plugins_loaded', 'save_settings');
}

function save_custom_layer_settings($value, $id) {
    $value = strip_if_needed($value);
    if (!at_smart_layer_is_pro_user($id)) { 
        if ( !$value ) {
            update_option('smart_layer_settings', "{}");
        } else {
            update_option('smart_layer_settings', "$value");
        }
    }
    update_option('smart_layer_profile', "$id");
}

function smart_layer_deactivate() {
	update_option( 'smart_layer_activated', '0' );
	update_option( 'smart_layer_settings', '{}' );
	update_option( 'smart_layer_settings_advanced', '0' );
}

if(isset($_POST['action'])) {
    if($_POST['action'] == 'save_smart_layer_settings') {
        save_smart_layer_settings($_POST['value']);
    } 
}

if (isset($_POST['save_my_smart_layer'])) { 
	if($_POST['save_my_smart_layer'] == 'save_my_smart_layer') {
		$value = $_POST['smart_layer_settings'];
		$id = $_POST['addthis_profile'];
		save_custom_layer_settings($value, $id);
	}
}

// Setup our shared resources early
add_action('init', 'smart_layer_early', 1);
function smart_layer_early(){
	global $addthis_addjs;
	if (! isset($addthis_addjs)){
		require('views/includes/addthis_addjs.php');
		$addthis_options = get_option('addthis_settings');
		$addthis_addjs = new AddThis_addjs($addthis_options);
	} elseif (! method_exists( $addthis_addjs, 'getAtPluginPromoText')){
		require('views/includes/addthis_addjs_extender.php');
		$addthis_addjs = new AddThis_addjs_extender($addthis_options);
	}
}

// check for pro user
function at_smart_layer_is_pro_user($id = null) {
    $isPro = false;
    if ($id) {
        $profile = $id;
    } else {
        $profile = get_option('smart_layer_profile');
    }
    $options = get_option('addthis_settings');
    $share_profile = $options['profile'];
    if ($profile || $share_profile) {
        $smart_layer_pro = get_option('smart_layer_pro');
        if ($profile) {
            $request = wp_remote_get( "http://q.addthis.com/feeds/1.0/config.json?pubid=" . $profile );
        } else {
            $request = wp_remote_get( "http://q.addthis.com/feeds/1.0/config.json?pubid=" . $share_profile );
        }

        $server_output = wp_remote_retrieve_body( $request );
        $array = json_decode($server_output);
        // check for pro user
        if (is_array($array) && array_key_exists('_default', $array)){
            if ($smart_layer_pro) {
                // update pro user settings 
                update_option('smart_layer_pro', true);
                update_option('smart_layer_pro_setting', $server_output);
            } else {
                // add pro user settings 
                add_option('smart_layer_pro', true);
                add_option('smart_layer_pro_setting', $server_output);
            }
            $isPro = true;
        } else {
            if ($smart_layer_pro) {
                // update pro user settings 
                delete_option('smart_layer_pro');
                delete_option('smart_layer_pro_setting');
            }
            $isPro = false;
        }
    }
    return $isPro;
}

?>
