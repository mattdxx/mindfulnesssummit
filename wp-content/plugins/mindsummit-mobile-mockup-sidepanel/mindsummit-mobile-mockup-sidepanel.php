<?php

/**
 * Plugin Name: MindSummit Mobile Mockup - Side Panel
 * Description: Plugin adds side panel menu to /speaker/ pages
 * Version: 0.1
 * Author: Gerasimov Eugene
 * Author URI: http://jspot.ru/
 * Text Domain: mindsummit-mobile-mockup-sidepanel
*/


    # Exit if accessed directly
    if (!defined('ABSPATH'))
      exit;
    
    if (!class_exists('MindSummit_MobileMockup_SidePanel'))
    {
        class MindSummit_MobileMockup_SidePanel
        {
            private static $instance;
            
            public static function instance()
            {
                if (!self::$instance)
                {
                    self::$instance = new MindSummit_MobileMockup_SidePanel();
                    self::$instance->hooks();
                }
                return self::$instance;
            }
            
            private function hooks()
            {
                add_action('wp_enqueue_scripts', array($this, 'load_scripts'));
            }
			
            public function load_scripts()
            {
                wp_enqueue_script(
                    'mindsummit-mobilemockup-sidepanel-js',
                    plugin_dir_url(__FILE__).'script.js?defer',
                    'jquery'
                    );
				wp_enqueue_style(
					'mindsummit-mobilemockup-sidepanel-css',
                    plugin_dir_url(__FILE__).'style.css',
                    array('style-responsive')
                    );
            }

        } # class MindSummit_MobileMockup_SidePanel
        
    } # if class_exists
    
    function MindSummit_MobileMockup_SidePanel_Load()
    {
		preg_match('~^/sessions/~', $_SERVER['REQUEST_URI']) and
			MindSummit_MobileMockup_SidePanel::instance();
    }
    add_action('plugins_loaded', 'MindSummit_MobileMockup_SidePanel_Load');

?>
