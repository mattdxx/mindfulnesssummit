<?php

/**
 * Plugin Name: MindSummit Mobile Mockup - Mobile View
 * Description: Plugin adds "read more" button to /sessions/ pages
 * Version: 0.1
 * Author: Gerasimov Eugene
 * Author URI: http://jspot.ru/
 * Text Domain: mindsummit-mobile-mockup-mobileview
*/


    # Exit if accessed directly
    if (!defined('ABSPATH'))
      exit;
    
    if (!class_exists('MindSummit_MobileMockup_MobileView'))
    {
        class MindSummit_MobileMockup_MobileView
        {
            private static $instance;
            
            public static function instance()
            {
                if (!self::$instance)
                {
                    self::$instance = new MindSummit_MobileMockup_MobileView();
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
                wp_enqueue_style(
                    'mindsummit-mobilemockup-mobileview-css',
                    plugin_dir_url(__FILE__).'mobileview.css',
                    array('parent-style', 'style', 'style-responsive', 'style-custom')
                    );
            }

        } # class MindSummit_MobileMockup_MobileView
        
    } # if class_exists
    
    function MindSummit_MobileMockup_MobileView_Load()
    {
        preg_match('~^/sessions/\w~', $_SERVER['REQUEST_URI']) and
            MindSummit_MobileMockup_MobileView::instance();
    }
    add_action('plugins_loaded', 'MindSummit_MobileMockup_MobileView_Load');

?>
