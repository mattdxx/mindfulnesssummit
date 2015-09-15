<?php
/**
 * Plugin Name: Popup Maker - AJAX Login Modals
 * Plugin URI: https://wppopupmaker.com/extensions/ajax-login-modals
 * Description: 
 * Author: Daniel Iser
 * Version: 1.1.1
 * Author URI: https://wppopupmaker.com
 * Text Domain: popup-maker-ajax-login-modals
 * 
 * @package     POPMAKE_ALM
 * @category    Addon\Security
 * @author      Daniel Iser
 * @copyright   Copyright (c) 2014, Wizard Internet Solutions
 * @since       1.0
*/


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'PopMake_Ajax_Login_Modals' ) ) {

    /**
     * Main PopMake_Ajax_Login_Modals class
     *
     * @since       1.0.0
     */
    class PopMake_Ajax_Login_Modals {

        /**
         * @var         PopMake_Ajax_Login_Modals $instance The one true PopMake_Ajax_Login_Modals
         * @since       1.0.0
         */
        private static $instance;

        public $site;
        public $admin;
        public $ajax;
        public $upgrades;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      object self::$instance The one true PopMake_Ajax_Login_Modals
         */
        public static function instance() {
            if( ! self::$instance ) {
                self::$instance = new PopMake_Ajax_Login_Modals();
                self::$instance->setup_constants();
                self::$instance->load_textdomain();
                self::$instance->includes();

                self::$instance->site =  new PopMake_Ajax_Login_Modals_Site();
                self::$instance->admin =  new PopMake_Ajax_Login_Modals_Admin();
                self::$instance->ajax =  new PopMake_Ajax_Login_Modals_Ajax();
                self::$instance->upgrades = new PopMake_Ajax_Login_Modals_Upgrades();


                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'POPMAKE_AJAXLOGINMODALS_VER', '1.1.1' );

            // Plugin path
            define( 'POPMAKE_AJAXLOGINMODALS_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'POPMAKE_AJAXLOGINMODALS_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            require_once POPMAKE_AJAXLOGINMODALS_DIR . 'includes/class-site.php';
            require_once POPMAKE_AJAXLOGINMODALS_DIR . 'includes/class-admin.php';
            require_once POPMAKE_AJAXLOGINMODALS_DIR . 'includes/class-ajax.php';
            require_once POPMAKE_AJAXLOGINMODALS_DIR . 'includes/admin/popups/class-metaboxes.php';
            require_once POPMAKE_AJAXLOGINMODALS_DIR . 'includes/admin/popups/class-metabox-fields.php';
            require_once POPMAKE_AJAXLOGINMODALS_DIR . 'includes/functions.php';
            require_once POPMAKE_AJAXLOGINMODALS_DIR . 'includes/template-functions.php';
            require_once POPMAKE_AJAXLOGINMODALS_DIR . 'includes/shortcodes.php';
            require_once POPMAKE_AJAXLOGINMODALS_DIR . 'includes/admin/upgrades/class-upgrades.php';

            if( defined( 'WPMEM_VERSION' ) ) {
                require_once POPMAKE_AJAXLOGINMODALS_DIR . 'includes/integrations/wp-members.php';
            }

            if( function_exists( 'wppb_free_plugin_init' ) ) {
                require_once POPMAKE_AJAXLOGINMODALS_DIR . 'includes/integrations/profile-builder.php';
            }
       }


        /**
         * Run action and filter hooks
         */
        private function hooks() {
            // Register settings
            //add_filter( 'popmake_settings_extensions', array( $this, 'settings' ), 1 );

            add_filter( 'popmake_template_paths', array( $this, 'template_path' ) );

            add_action( 'wp_enqueue_scripts', array( $this->site, 'scripts' ) );
            add_filter( 'loginout', array( $this->site, 'filter_login_link' ) );
            add_filter( 'register', array( $this->site, 'filter_registration_link' ) );
            add_filter( 'the_popup_content', array( $this->site, 'popup_content_filter' ), 10, 2 );
            add_filter( 'popmake_get_the_popup_classes', array( $this->site, 'popup_classes' ), 5, 2);
            add_filter( 'popmake_get_the_popup_data_attr', array( $this->site, 'popup_data_attr' ), 0, 2 );
            add_filter( 'popmake_popup_is_loadable', array( $this->site, 'popup_is_loadable' ), 10, 2 );


            add_action( 'wp_ajax_ajaxlogin', array( $this->ajax, 'ajax_call' ) );
            add_action( 'wp_ajax_nopriv_ajaxlogin', array( $this->ajax, 'ajax_call' ) );

            add_action( 'admin_enqueue_scripts', array( $this->admin, 'scripts' ), 100 );
            add_action( 'add_meta_boxes', array( $this->admin->metaboxes, 'register' ) );
            add_filter( 'popmake_popup_meta_fields', array( $this->admin->metaboxes, 'meta_fields' ) );
            add_filter( 'popmake_popup_meta_field_groups', array( $this->admin->metaboxes, 'meta_field_groups' ) );
            add_filter( 'popmake_popup_meta_field_group_ajax_login', array( $this->admin->metaboxes, 'meta_field_group_ajax_login' ) );
            add_filter( 'popmake_popup_meta_field_group_ajax_registration', array( $this->admin->metaboxes, 'meta_field_group_ajax_registration' ) );
            add_filter( 'popmake_popup_meta_field_group_ajax_recovery', array( $this->admin->metaboxes, 'meta_field_group_ajax_recovery' ) );
            add_filter( 'popmake_popup_ajax_login_defaults', array( $this->admin->metaboxes, 'login_defaults' ) );
            add_filter( 'popmake_popup_ajax_registration_defaults', array( $this->admin->metaboxes, 'registration_defaults' ) );
            add_filter( 'popmake_popup_ajax_recovery_defaults', array( $this->admin->metaboxes, 'recovery_defaults' ) );

            add_action( 'popmake_popup_ajax_login_meta_box_fields', array( $this->admin->metabox_fields, 'login_enabled' ), 10 );
            add_action( 'popmake_popup_ajax_login_meta_box_fields', array( $this->admin->metabox_fields, 'login_force_login' ), 20 );
            add_action( 'popmake_popup_ajax_login_meta_box_fields', array( $this->admin->metabox_fields, 'login_allow_remember' ), 30 );
            add_action( 'popmake_popup_ajax_login_meta_box_fields', array( $this->admin->metabox_fields, 'login_disable_redirect' ), 40 );
            add_action( 'popmake_popup_ajax_login_meta_box_fields', array( $this->admin->metabox_fields, 'login_redirect_url' ), 50 );
            add_action( 'popmake_popup_ajax_registration_meta_box_fields', array( $this->admin->metabox_fields, 'registration_enabled' ), 10 );
            add_action( 'popmake_popup_ajax_registration_meta_box_fields', array( $this->admin->metabox_fields, 'registration_enable_password' ), 20 );
            add_action( 'popmake_popup_ajax_registration_meta_box_fields', array( $this->admin->metabox_fields, 'registration_enable_autologin' ), 30 );
            add_action( 'popmake_popup_ajax_registration_meta_box_fields', array( $this->admin->metabox_fields, 'registration_disable_redirect' ), 40 );
            add_action( 'popmake_popup_ajax_registration_meta_box_fields', array( $this->admin->metabox_fields, 'registration_redirect_url' ), 50 );
            add_action( 'popmake_popup_ajax_recovery_meta_box_fields', array( $this->admin->metabox_fields, 'recovery_enabled' ), 10 );
            add_action( 'popmake_popup_ajax_recovery_meta_box_fields', array( $this->admin->metabox_fields, 'recovery_disable_redirect' ), 20 );
            add_action( 'popmake_popup_ajax_recovery_meta_box_fields', array( $this->admin->metabox_fields, 'recovery_redirect_url' ), 30 );

            if( defined( 'WPMEM_VERSION' ) ) {
                new PopMake_Ajax_Login_Modals_Integration_WPMembers();
            }
            if( function_exists( 'wppb_free_plugin_init' ) ) {
                new PopMake_Ajax_Login_Modals_Integration_Profile_Builder();
            }

            if ( is_admin() ) {
                add_action( 'after_setup_theme', array( $this->admin, 'install_check' ) );
            }

            // Handle licensing
            if( class_exists( 'PopMake_License' ) ) {
                $license = new PopMake_License( __FILE__, 'AJAX Login Modals', POPMAKE_AJAXLOGINMODALS_VER, 'Daniel Iser' );
            }
        }


        public function template_path( $file_paths ) {
            $key = max( array_keys( $file_paths ) ) + 1;
	        $file_paths[ $key ] = POPMAKE_AJAXLOGINMODALS_DIR . 'templates';
            return $file_paths;
        }

        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = POPMAKE_AJAXLOGINMODALS_DIR . '/languages/';
            $lang_dir = apply_filters( 'popmake_alm_languages_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), 'popup-maker-ajax-login-modals' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'popup-maker-ajax-login-modals', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/popup-maker/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                load_textdomain( 'popup-maker-ajax-login-modals', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                load_textdomain( 'popup-maker-ajax-login-modals', $mofile_local );
            } else {
                load_plugin_textdomain( 'popup-maker-ajax-login-modals', false, $lang_dir );
            }
        }


        /**
         * Add settings
         *
         * @access      public
         * @since       1.0.0
         * @param       array $settings The existing Popup Maker settings array
         * @return      array The modified Popup Maker settings array
         */
        public function settings( $settings ) {
            $new_settings = array(
                array(
                    'id'    => 'popmake_alm_settings',
                    'name'  => '<strong>' . __( 'AJAX Login Modal Settings', 'popup-maker-ajax-login-modals' ) . '</strong>',
                    'desc'  => __( 'Configure AJAX Login Modal Settings', 'popup-maker-ajax-login-modals' ),
                    'type'  => 'header',
                )
            );

            return array_merge( $settings, $new_settings );
        }

    }
} // End if class_exists check



function PopMake_Ajax_Login_Modals() {
    return PopMake_Ajax_Login_Modals::instance();
}

/**
 * The main function responsible for returning the one true PopMake_Ajax_Login_Modals
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      PopMake_Ajax_Login_Modals The one true PopMake_Ajax_Login_Modals
 *
 * @todo        Inclusion of the activation code below isn't mandatory, but
 *              can prevent any number of errors, including fatal errors, in
 *              situations where your extension is activated but Popup Maker is not
 *              present.
 */
function popmake_alm_load() {
    if( ! class_exists( 'Popup_Maker' ) ) {
        if( ! class_exists( 'PopMake_Extension_Activation' ) ) {
            require_once 'includes/class.extension-activation.php';
        }

        $activation = new PopMake_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
        $activation = $activation->run();
    } else {
        PopMake_Ajax_Login_Modals::instance();
    }
}
add_action( 'plugins_loaded', 'popmake_alm_load' );


/**
 * The activation hook is called outside of the singleton because WordPress doesn't
 * register the call from within the class, since we are preferring the plugins_loaded
 * hook for compatibility, we also can't reference a function inside the plugin class
 * for the activation function. If you need an activation function, put it here.
 *
 * @since       1.0.0
 * @return      void
 */
function popmake_alm_activation() {
    /* Activation functions here */
}
register_activation_hook( __FILE__, 'popmake_alm_activation' );
