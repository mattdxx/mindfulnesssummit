<?php
/*
 * Plugin Name: WPSSO Pro Update Manager (WPSSO UM)
 * Plugin URI: http://surniaulula.com/extend/plugins/wpsso-um/
 * Author: Jean-Sebastien Morisset
 * Author URI: http://surniaulula.com/
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Description: Update Manager for the WordPress Social Sharing Optimization (WPSSO) Pro plugin and its extensions
 * Requires At Least: 3.1
 * Tested Up To: 4.3.1
 * Version: 1.1.8
 * 
 * Copyright 2015 - Jean-Sebastien Morisset - http://surniaulula.com/
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoUm' ) ) {

	class WpssoUm {

		public $p;			// Wpsso
		public $reg;			// WpssoUmRegister
		public $filters;		// WpssoUmFilters
		public $update;			// SucomUpdate

		protected static $instance = null;

		private static $wpsso_short = 'WPSSO';
		private static $wpsso_name = 'WordPress Social Sharing Optimization (WPSSO)';
		private static $wpsso_min_version = '3.10.1';
		private static $wpsso_has_min_ver = true;

		public static function &get_instance() {
			if ( self::$instance === null )
				self::$instance = new self;
			return self::$instance;
		}

		public function __construct() {

			require_once ( dirname( __FILE__ ).'/lib/config.php' );
			WpssoUmConfig::set_constants( __FILE__ );
			WpssoUmConfig::require_libs( __FILE__ );		// includes the register.php class library
			$this->reg = new WpssoUmRegister();			// activate, deactivate, uninstall hooks

			if ( is_admin() )
				add_action( 'admin_init', array( &$this, 'check_for_wpsso' ) );

			add_filter( 'wpsso_get_config', array( &$this, 'wpsso_get_config' ), 10, 1 );
			add_action( 'wpsso_init_plugin', array( &$this, 'wpsso_init_plugin' ), 10 );
		}

		public function check_for_wpsso() {
			if ( ! class_exists( 'Wpsso' ) )
				add_action( 'all_admin_notices', array( &$this, 'wpsso_missing_notice' ) );
		}

		public static function wpsso_missing_notice( $deactivate = false ) {
			$lca = 'wpssoum';
			$name = WpssoUmConfig::$cf['plugin'][$lca]['name'];
			$short = WpssoUmConfig::$cf['plugin'][$lca]['short'];
			if ( $deactivate === true ) {
				require_once( ABSPATH.'wp-admin/includes/plugin.php' );
				deactivate_plugins( WPSSOUM_PLUGINBASE );
				wp_die( '<p>'.sprintf( __( 'The %s extension requires the %s plugin &mdash; please install and '.
					'activate the %s plugin before trying to re-activate the %s extension.', WPSSOUM_TEXTDOM ), 
						$name, self::$wpsso_name, self::$wpsso_short, $short ).'</p>' );
			} else echo '<div class="error"><p>'.sprintf( __( 'The %s extension requires the %s plugin &mdash; '.
					'please install and activate the %s plugin.', WPSSOUM_TEXTDOM ), 
						$name, self::$wpsso_name, self::$wpsso_short ).'</p></div>';
		}

		public function wpsso_get_config( $cf ) {
			if ( version_compare( $cf['plugin']['wpsso']['version'], self::$wpsso_min_version, '<' ) ) {
				self::$wpsso_has_min_ver = false;
				return $cf;
			}
			$cf = SucomUtil::array_merge_recursive_distinct( $cf, WpssoUmConfig::$cf );
			return $cf;
		}

		public function wpsso_init_plugin() {

			if ( method_exists( 'Wpsso', 'get_instance' ) )
				$this->p =& Wpsso::get_instance();
			else $this->p =& $GLOBALS['wpsso'];

			if ( self::$wpsso_has_min_ver === false )
				return $this->warning_wpsso_version( WpssoUmConfig::$cf['plugin']['wpssoum'] );

			require_once( WPSSOUM_PLUGINDIR.'lib/filters.php' );
			$this->filters = new WpssoUmFilters( $this->p, __FILE__ );

			$check_hours = empty( $this->p->cf['update_check_hours'] ) ? 
				24 : $this->p->cf['update_check_hours'];

			$this->update = new SucomUpdate( $this->p, $this->p->cf['plugin'], $check_hours );

			if ( is_admin() ) {
				/*
				 * Force immediate check if no update check for past 2 days
				 */
				foreach ( $this->p->cf['plugin'] as $lca => $info ) {

					// skip plugins that have an auth type but no auth string
					if ( ! empty( $info['update_auth'] ) &&
						empty( $this->p->options['plugin_'.$lca.'_'.$info['update_auth']] ) )
							continue;

					$last_utime = get_option( $lca.'_utime' );

					// 24 hours * 7200 = 2 days
					if ( empty( $last_utime ) || $last_utime + ( $check_hours * 7200 ) < time() ) {
						if ( $this->p->debug->enabled ) {
							$this->p->debug->log( 'requesting update check for '.$lca );
							$this->p->notice->inf( 'Performing an update check for the '.$info['name'].' plugin.' );
						}
						$this->update->check_for_updates( $lca, false, false );	// $use_cache = false
					}
				}
			}
		}

		private function warning_wpsso_version( $info ) {
			$wpsso_version = $this->p->cf['plugin']['wpsso']['version'];
			if ( ! empty( $this->p->debug->enabled ) )
				$this->p->debug->log( $info['name'].' requires WPSSO version '.self::$wpsso_min_version.
					' or newer ('.$wpsso_version.' installed)' );
			if ( is_admin() )
				$this->p->notice->err( 'The '.$info['name'].' version '.$info['version'].
					' extension requires WPSSO version '.self::$wpsso_min_version.
					' or newer (version '.$wpsso_version.' is currently installed).', true );
		}
	}

        global $wpssoum;
	$wpssoum = WpssoUm::get_instance();
}

?>
