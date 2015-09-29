<?php

/**
 * Plugin Name: MindSummit Mobile Mockup - Read more
 * Description: Plugin adds "read more" button to /speaker/ pages
 * Version: 0.1
 * Author: Gerasimov Eugene
 * Author URI: http://jspot.ru/
 * Text Domain: mindsummit-mobile-mockup-readmore
*/


    # Exit if accessed directly
    if (!defined('ABSPATH'))
      exit;
    
    if (!class_exists('MindSummit_MobileMockup_ReadMore'))
    {
        class MindSummit_MobileMockup_ReadMore
        {
            private static $instance;
            
            public static function instance()
            {
                if (!self::$instance)
                {
                    self::$instance = new MindSummit_MobileMockup_ReadMore();
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
                    'mindsummit-mobilemockup-readmore-js',
                    plugin_dir_url(__FILE__).'script.js?defer',
                    array('jquery')
                    );
                wp_enqueue_style(
                    'mindsummit-mobilemockup-readmore-css',
                    plugin_dir_url(__FILE__).'style.css',
                    array('style-responsive')
                    );
                wp_enqueue_style(
                    'mindsummit-mobilemockup-mobileview-css',
                    plugin_dir_url(__FILE__).'mobileview.css',
                    array('parent-style', 'style', 'style-responsive', 'style-custom')
                    );
            }

        } # class MindSummit_MobileMockup_ReadMore
        
    } # if class_exists
    
    function MindSummit_MobileMockup_ReadMore_Load()
    {
        preg_match('~^/sessions/\w~', $_SERVER['REQUEST_URI']) and
            MindSummit_MobileMockup_ReadMore::instance();
    }
    add_action('plugins_loaded', 'MindSummit_MobileMockup_ReadMore_Load');

?>
