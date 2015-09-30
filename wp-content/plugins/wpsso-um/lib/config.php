<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2015 - Jean-Sebastien Morisset - http://wpsso.com/
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoUmConfig' ) ) {

	class WpssoUmConfig {

		public static $cf = array(
			'update_check_hours' => 24,
			'allow_update_host' => 'wpsso.com',
			'plugin' => array(
				'wpssoum' => array(
					'version' => '1.1.8',		// plugin version
					'short' => 'WPSSO UM',
					'name' => 'WPSSO Pro Update Manager (WPSSO UM)',
					'desc' => 'WPSSO extension to provide updates for the WordPress Social Sharing Optimization (WPSSO) Pro plugin and its extensions.',
					'slug' => 'wpsso-um',
					'base' => 'wpsso-um/wpsso-um.php',
					'update_auth' => '',
					'img' => array(
						'icon_small' => 'images/icon-128x128.png',
						'icon_medium' => 'images/icon-256x256.png',
					),
					'url' => array(
						// surniaulula
						'download' => 'http://wpsso.com/extend/plugins/wpsso-um/',
						'latest_zip' => 'http://wpsso.com/extend/plugins/wpsso-um/latest/',
						'review' => '',
						'readme' => 'https://raw.githubusercontent.com/SurniaUlula/wpsso-um/master/readme.txt',
						'wp_support' => '',
						'update' => 'http://wpsso.com/extend/plugins/wpsso-um/update/',
						'purchase' => '',
						'changelog' => 'http://wpsso.com/extend/plugins/wpsso-um/changelog/',
						'codex' => '',
						'faq' => '',
						'notes' => '',
						'feed' => '',
						'pro_support' => '',
					),
					'lib' => array(
						'gpl' => array(		// required for WpssoAdmin::show_metabox_status_gpl()
						),
					),
				),
			),
		);

		public static function set_constants( $plugin_filepath ) { 
			define( 'WPSSOUM_FILEPATH', $plugin_filepath );						
			define( 'WPSSOUM_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_filepath ) ) ) );
			define( 'WPSSOUM_PLUGINBASE', self::$cf['plugin']['wpssoum']['base'] );		// wpsso-um/wpsso-um.php
			define( 'WPSSOUM_TEXTDOM', self::$cf['plugin']['wpssoum']['slug'] );		// wpsso-um
			define( 'WPSSOUM_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );
		}

		public static function require_libs( $plugin_filepath ) {
			require_once( WPSSOUM_PLUGINDIR.'lib/com/update.php' );
			require_once( WPSSOUM_PLUGINDIR.'lib/register.php' );
		}
	}
}

?>
