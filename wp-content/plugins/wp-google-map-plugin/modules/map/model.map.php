<?php
/**
 * Class: WPGMP_Model_Map
 * Menu Order: 2
 * @author Flipper Code <hello@flippercode.com>
 * @version 3.0.0
 * @package Maps
 */

if ( ! class_exists( 'WPGMP_Model_Map' ) ) {

	/**
	 * Map model for CRUD operation.
	 * @package Maps
	 * @author Flipper Code <hello@flippercode.com>
	 */
	class WPGMP_Model_Map extends WPGMP_Model_Base {
		/**
		 * Validations on route properies.
		 * @var array
		 */
		protected $validations = array(
		'map_title'	 => array( 'req' => 'Please enter map title.' ),
		'map_height' => array( 'req' => 'Please enter map height.' ),
		);
		/**
		 * Intialize map object.
		 */
		function __construct() {

			$this->table = TBL_MAP;
			$this->unique = 'map_id';
		}
		/**
		 * Admin menu for CRUD Operation
		 * @return array Admin menu navigation(s).
		 */
		function navigation() {
			return array(
			'wpgmp_form_map' => __( 'Add Map', WPGMP_TEXT_DOMAIN ),
			'wpgmp_manage_map' => __( 'Manage Maps', WPGMP_TEXT_DOMAIN ),
			);

		}
		/**
		 * Install table associated with map entity.
		 * @return string SQL query to install create_map table.
		 */
		function install() {
			global $wpdb;
			$create_map = 'CREATE TABLE `'.$wpdb->prefix.'create_map` (
			`map_id` int(11) NOT NULL AUTO_INCREMENT,
			`map_title` varchar(255) DEFAULT NULL,
			`map_width` varchar(255) DEFAULT NULL,
			`map_height` varchar(255) DEFAULT NULL,
			`map_zoom_level` varchar(255) DEFAULT NULL,
			`map_type` varchar(255) DEFAULT NULL,
			`map_scrolling_wheel` varchar(255) DEFAULT NULL,
			`map_visual_refresh` varchar(255) DEFAULT NULL,
			`map_45imagery` varchar(255) DEFAULT NULL,
			`map_street_view_setting` text DEFAULT NULL,
			`map_route_direction_setting` text DEFAULT NULL,
			`map_all_control` text DEFAULT NULL,
			`map_info_window_setting` text DEFAULT NULL,
			`style_google_map` text DEFAULT NULL,
			`map_locations` text DEFAULT NULL,
			`map_layer_setting` text DEFAULT NULL,
			`map_polygon_setting` text DEFAULT NULL,
			`map_polyline_setting` text DEFAULT NULL,
			`map_cluster_setting` text DEFAULT NULL,
			`map_overlay_setting` text DEFAULT NULL,
			`map_geotags` text DEFAULT NULL,
			`map_infowindow_setting` text DEFAULT NULL,
			PRIMARY KEY (`map_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';

			return $create_map;
		}
		/**
		 * Get Map(s)
		 * @param  array $where  Conditional statement.
		 * @return array         Array of Map object(s).
		 */
		public function fetch($where = array()) {

			$objects = $this->get( $this->table, $where );

			if ( isset( $objects ) ) {
				foreach ( $objects as $object ) {
					$object->map_street_view_setting = unserialize( $object->map_street_view_setting );
					$object->map_route_direction_setting = unserialize( $object->map_route_direction_setting );
					$object->map_all_control = unserialize( $object->map_all_control );
					$object->map_info_window_setting = unserialize( $object->map_info_window_setting );
					$object->style_google_map = unserialize( $object->style_google_map );
					$object->map_locations = unserialize( $object->map_locations );
					$object->map_layer_setting = unserialize( $object->map_layer_setting );
					$object->map_polygon_setting = unserialize( $object->map_polygon_setting );
					$object->map_polyline_setting = unserialize( $object->map_polyline_setting );
					$object->map_cluster_setting = unserialize( $object->map_cluster_setting );
					$object->map_overlay_setting = unserialize( $object->map_overlay_setting );
					$object->map_infowindow_setting = unserialize( $object->map_infowindow_setting );
					$object->map_geotags = unserialize( $object->map_geotags );
				}
				return $objects;
			}
		}
		/**
		 * Add or Edit Operation.
		 */
		function save() {

			$data = array();
			$entityID = '';

			if ( isset( $_REQUEST['_wpnonce'] ) ) {
				$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ); }

			if ( isset( $nonce ) and ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {

				die( 'Cheating...' );

			}

			$this->verify( $_POST );

			if ( is_array( $this->errors ) and ! empty( $this->errors ) ) {
				$this->throw_errors();
			}

			if ( isset( $_POST['entityID'] ) ) {
				$entityID = intval( wp_unslash( $_POST['entityID'] ) );
			}

			if ( '' != sanitize_text_field( $_POST['map_locations'] ) ) {
				$map_locations = explode( ',', sanitize_text_field( $_POST['map_locations'] ) );
			} else { $map_locations = array(); }

			$data['map_title'] = sanitize_text_field( wp_unslash( $_POST['map_title'] ) );
			$data['map_width'] = str_replace( 'px','',sanitize_text_field( wp_unslash( $_POST['map_width'] ) ) );
			$data['map_height'] = str_replace( 'px','',sanitize_text_field( wp_unslash( $_POST['map_height'] ) ) );
			$data['map_zoom_level'] = intval( wp_unslash( $_POST['map_zoom_level'] ) );
			$data['map_type'] = sanitize_text_field( wp_unslash( $_POST['map_type'] ) );
			$data['map_scrolling_wheel'] = sanitize_text_field( wp_unslash( $_POST['map_scrolling_wheel'] ) );
			$data['map_45imagery'] = sanitize_text_field( wp_unslash( $_POST['map_45imagery'] ) );
			$data['map_street_view_setting'] = serialize( wp_unslash( $_POST['map_street_view_setting'] ) );
			$data['map_route_direction_setting'] = serialize( wp_unslash( $_POST['map_route_direction_setting'] ) );
			$data['map_all_control'] = serialize( wp_unslash( $_POST['map_all_control'] ) );
			$data['map_info_window_setting'] = serialize( wp_unslash( $_POST['map_info_window_setting'] ) );
			$data['style_google_map'] = serialize( wp_unslash( $_POST['style_google_map'] ) );
			$data['map_locations'] = serialize( wp_unslash( $map_locations ) );
			$data['map_layer_setting'] = serialize( wp_unslash( $_POST['map_layer_setting'] ) );
			$data['map_polygon_setting'] = serialize( wp_unslash( $_POST['map_polygon_setting'] ) );
			$data['map_polyline_setting'] = serialize( wp_unslash( $_POST['map_polyline_setting'] ) );
			$data['map_cluster_setting'] = serialize( wp_unslash( $_POST['map_cluster_setting'] ) );
			$data['map_overlay_setting'] = serialize( wp_unslash( $_POST['map_overlay_setting'] ) );
			$data['map_infowindow_setting'] = serialize( wp_unslash( $_POST['map_infowindow_setting'] ) );
			$data['map_geotags'] = serialize( wp_unslash( $_POST['map_geotags'] ) );

			if ( $entityID > 0 ) {
				$where[ $this->unique ] = $entityID;
			} else {
				$where = '';
			}

			$result = WPGMP_Database::insert_or_update( $this->table, $data, $where );
			if ( false === $result ) {
				$response['error'] = __( 'Something went wrong. Please try again.',WPGMP_TEXT_DOMAIN );
			} elseif ( $entityID > 0 ) {
				$response['success'] = __( 'Map updated successfully',WPGMP_TEXT_DOMAIN );
			} else {
				$response['success'] = __( 'Map added successfully.',WPGMP_TEXT_DOMAIN );
			}
			return $response;
		}
		/**
		 * Delete map object by id.
		 */
		function delete() {
			if ( isset( $_GET['map_id'] ) ) {
				$id = intval( wp_unslash( $_GET['map_id'] ) );
				$connection = WPGMP_Database::connect();
				$this->query = $connection->prepare( "DELETE FROM $this->table WHERE $this->unique='%d'", $id );
				return WPGMP_Database::non_query( $this->query, $connection );
			}
		}
		/**
		 * Clone map object by id.
		 */
		function copy($map_id) {
			if ( isset( $map_id ) ) {
				$id = intval( wp_unslash( $map_id ) );
				$map = $this->get( $this->table,array( array( 'map_id', '=', $id ) ) );
				$data = array();
				foreach ( $map[0] as $column => $value ) {

					if ( $column == 'map_id' ) {
						continue; } else if ( $column == 'map_title' ) {
						$data[$column] = $value.' '.__( 'Copy',WPGMP_TEXT_DOMAIN );
						} else { 					$data[$column] = $value; }
				}

				$result = WPGMP_Database::insert_or_update( $this->table, $data );
			}
		}

	}
}
