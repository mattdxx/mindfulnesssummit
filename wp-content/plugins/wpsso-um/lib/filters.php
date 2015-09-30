<?php
/*
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2015 - Jean-Sebastien Morisset - http://surniaulula.com/
 */

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'WpssoUmFilters' ) ) {

	class WpssoUmFilters {

		protected $p;
		protected $plugin_filepath;

		public function __construct( &$plugin, $plugin_filepath = WPSSOUM_FILEPATH ) {
			$this->p =& $plugin;
			$this->plugin_filepath = $plugin_filepath;
			if ( is_admin() ) {
				$this->p->util->add_plugin_filters( $this, array( 
					'messages_tooltip_side' => 2,	// tooltip messages for side boxes
				) );
				$this->p->util->add_plugin_filters( $this, array( 
					'status_gpl_features' => 3,
				), 10, 'wpssoum' );
			}
		}

		public function filter_messages_tooltip_side( $text, $idx ) {
			switch ( $idx ) {
				case 'tooltip-side-update-check-schedule':
					$short = $this->p->cf['plugin']['wpsso']['short'];
					$um_name = $this->p->cf['plugin']['wpssoum']['name'];
					$check_hours = empty( $this->p->cf['update_check_hours'] ) ? 
						24 : $this->p->cf['update_check_hours'];
					$text = 'When the '.$um_name.' extension is active, an update check is scheduled every '.$check_hours.' hours to retrieve update information for '.$short.' '.$this->p->util->get_admin_url( 'licenses', 'Extension Plugins and Pro Licenses' ).'.';
					break;
			}
			return $text;
		}

		public function filter_status_gpl_features( $features, $lca, $info ) {
			$features['Update Check Schedule'] = array( 
				'status' => SucomUpdate::is_enabled() ? 'on' : 'off'
			);
			return $features;
		}
	}
}

?>
