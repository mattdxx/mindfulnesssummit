<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2012-2015 - Jean-Sebastien Morisset - http://surniaulula.com/
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoUmRegister' ) ) {

	class WpssoUmRegister {

		public function __construct() {

			register_activation_hook( WPSSOUM_FILEPATH, array( &$this, 'network_activate' ) );
			register_deactivation_hook( WPSSOUM_FILEPATH, array( &$this, 'network_deactivate' ) );
			register_uninstall_hook( WPSSOUM_FILEPATH, array( __CLASS__, 'network_uninstall' ) );

			if ( is_multisite() ) {
				add_action( 'wpmu_new_blog', array( &$this, 'wpmu_new_blog' ), 10, 6 );
				add_action( 'wpmu_activate_blog', array( &$this, 'wpmu_activate_blog' ), 10, 5 );
			}
		}

		// fires immediately after a new site is created
		public function wpmu_new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
			switch_to_blog( $blog_id );
			$this->activate_plugin();
			restore_current_blog();
		}

		// fires immediately after a site is activated
		// (not called when users and sites are created by a Super Admin)
		public function wpmu_activate_blog( $blog_id, $user_id, $password, $signup_title, $meta ) {
			switch_to_blog( $blog_id );
			$this->activate_plugin();
			restore_current_blog();
		}

		public function network_activate( $sitewide ) {
			self::do_multisite( $sitewide, array( &$this, 'activate_plugin' ) );
		}

		public function network_deactivate( $sitewide ) {
			self::do_multisite( $sitewide, array( &$this, 'deactivate_plugin' ) );
		}

		public static function network_uninstall() {
			$sitewide = true;

			// uninstall from the individual blogs first
			self::do_multisite( $sitewide, array( __CLASS__, 'uninstall_plugin' ) );
		}

		private static function do_multisite( $sitewide, $method, $args = array() ) {
			if ( is_multisite() && $sitewide ) {
				global $wpdb;
				$dbquery = 'SELECT blog_id FROM '.$wpdb->blogs;
				$ids = $wpdb->get_col( $dbquery );
				foreach ( $ids as $id ) {
					switch_to_blog( $id );
					call_user_func_array( $method, array( $args ) );
				}
				restore_current_blog();
			} else call_user_func_array( $method, array( $args ) );
		}

		private function activate_plugin() {
			$lca = 'wpssoum';
			$version = WpssoUmConfig::$cf['plugin'][$lca]['version'];	// only our config
			if ( class_exists( 'WpssoUtil' ) )
				WpssoUtil::save_all_times( $lca, $version );
			else WpssoUm::wpsso_missing_notice( true );			// $deactivate = true
			self::delete_options();
		}

		private function deactivate_plugin() {
			if ( class_exists( 'WpssoConfig' ) ) {
				$cf = WpssoConfig::get_config();	// get all plugins / extensions
				foreach ( $cf['plugin'] as $lca => $info ) {
					wp_clear_scheduled_hook( 'plugin_updates-'.$info['slug'] );
				}
			}
		}

		private static function uninstall_plugin() {
			self::delete_options();
		}

		private static function delete_options() {
			if ( class_exists( 'WpssoConfig' ) ) {
				$cf = WpssoConfig::get_config();	// get all plugins / extensions
				foreach ( $cf['plugin'] as $lca => $info ) {
					delete_option( $lca.'_umsg' );
					delete_option( $lca.'_utime' );
					delete_option( 'external_updates-'.$info['slug'] );
				}
			} else {	// in case wpsso is deactivated
				foreach ( array(
					'wpsso' => 'wpsso',
					'wpssoam' => 'wpsso-am',
					'wpssoplm' => 'wpsso-plm',
					'wpssorrssb' => 'wpsso-rrssb',
					'wpssossb' => 'wpsso-ssb',
					'wpssoum' => 'wpsso-um',
				) as $lca => $slug ) {
					delete_option( $lca.'_umsg' );
					delete_option( $lca.'_utime' );
					delete_option( 'external_updates-'.$slug );
				}
			}
		}
	}
}

?>
