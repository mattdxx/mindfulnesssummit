<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'PopMake_Ajax_Login_Modals_Admin' ) ) {

    /**
     * Main PopMake_Ajax_Login_Modals_Admin class
     *
     * @since       1.0.0
     */
    class PopMake_Ajax_Login_Modals_Admin {

        public $metaboxes;
        public $metabox_fields;

        public function __construct() {
            $this->metaboxes = new PopMake_Ajax_Login_Modals_Admin_Popup_Metaboxes();
            $this->metabox_fields = new PopMake_Ajax_Login_Modals_Admin_Popup_Metabox_Fields();
        }

        public function install_check() {
            $version = get_option( 'popmake_alm_version' );
            if ( ! $version || version_compare( '1.1.0', $version, '>' ) ) {
                PopMake_Ajax_Login_Modals()->upgrades->upgrade_v1_1_0();
            }
            update_option( 'popmake_alm_version', POPMAKE_AJAXLOGINMODALS_VER );
        }


        /**
		 * Load frontend scripts
		 *
		 * @since       1.0.0
		 * @return      void
		 */
		public function scripts( $hook ) {
			// Use minified libraries if SCRIPT_DEBUG is turned off
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			if( popmake_is_admin_page() ) {
				wp_enqueue_script( 'popmake-ajax-login-modals-admin-js', POPMAKE_AJAXLOGINMODALS_URL . 'assets/js/admin' . $suffix . '.js', array( 'jquery', 'popup-maker-admin' ), POPMAKE_AJAXLOGINMODALS_VER );
			}
		}

    }
} // End if class_exists check
