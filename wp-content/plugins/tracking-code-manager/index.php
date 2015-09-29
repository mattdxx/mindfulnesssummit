<?php
/*
Plugin Name: Tracking Code Manager
Plugin URI: http://intellywp.com/tracking-code-manager/
Description: A plugin to manage ALL your tracking code and conversion pixels, simply. Compatible with Facebook Ads, Google Adwords, WooCommerce, Easy Digital Downloads, WP eCommerce.
Author: IntellyWP
Author URI: http://intellywp.com/
Email: info@intellywp.com
Version: 1.8.1
*/
define('TCM_PLUGIN_PREFIX', 'TCM_');
define('TCM_PLUGIN_FILE',__FILE__);
define('TCM_PLUGIN_SLUG', 'tracking-code-manager');
define('TCM_PLUGIN_NAME', 'Tracking Code Manager');
define('TCM_PLUGIN_VERSION', '1.8.1');
define('TCM_PLUGIN_AUTHOR', 'IntellyWP');
define('TCM_PLUGIN_ROOT', dirname(__FILE__).'/');
define('TCM_PLUGIN_IMAGES', plugins_url( 'assets/images/', __FILE__ ));
define('TCM_PLUGIN_ASSETS', plugins_url( 'assets/', __FILE__ ));

define('TCM_LOGGER', FALSE);

define('TCM_QUERY_POSTS_OF_TYPE', 1);
define('TCM_QUERY_POST_TYPES', 2);
define('TCM_QUERY_CATEGORIES', 3);
define('TCM_QUERY_TAGS', 4);
define('TCM_QUERY_CONVERSION_PLUGINS', 5);

define('TCM_INTELLYWP_SITE', 'http://www.intellywp.com/');
define('TCM_INTELLYWP_ENDPOINT', TCM_INTELLYWP_SITE.'wp-content/plugins/intellywp-manager/data.php');
define('TCM_PAGE_FAQ', TCM_INTELLYWP_SITE.'tracking-code-manager');
define('TCM_PAGE_PREMIUM', TCM_INTELLYWP_SITE.'tracking-code-manager');
define('TCM_PAGE_MANAGER', admin_url().'options-general.php?page='.TCM_PLUGIN_SLUG);
define('TCM_PLUGIN_URI', plugins_url('/', __FILE__ ));

define('TCM_POSITION_HEAD', 0);
define('TCM_POSITION_BODY', 1);
define('TCM_POSITION_FOOTER', 2);
define('TCM_POSITION_CONVERSION', 3);

define('TCM_TAB_EDITOR', 'editor');
define('TCM_TAB_EDITOR_URI', TCM_PAGE_MANAGER.'&tab='.TCM_TAB_EDITOR);
define('TCM_TAB_MANAGER', 'manager');
define('TCM_TAB_MANAGER_URI', TCM_PAGE_MANAGER.'&tab='.TCM_TAB_MANAGER);
define('TCM_TAB_SETTINGS', 'settings');
define('TCM_TAB_SETTINGS_URI', TCM_PAGE_MANAGER.'&tab='.TCM_TAB_SETTINGS);
define('TCM_TAB_DOCS', 'docs');
define('TCM_TAB_DOCS_URI', 'https://intellywp.com/docs/category/tracking-code-manager/');
define('TCM_TAB_ABOUT', 'about');
define('TCM_TAB_ABOUT_URI', TCM_PAGE_MANAGER.'&tab='.TCM_TAB_ABOUT);
define('TCM_TAB_WHATS_NEW', 'whatsnew');
define('TCM_TAB_WHATS_NEW_URI', TCM_PAGE_MANAGER.'&tab='.TCM_TAB_WHATS_NEW);

include_once(dirname(__FILE__).'/autoload.php');
tcm_include_php(dirname(__FILE__).'/includes/');

global $tcm;
$tcm=new TCM_Singleton();
$tcm->init();