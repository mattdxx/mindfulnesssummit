<?php
/**
 * Parse Shortcode and display maps.
 * @package Maps
 * @author Flipper Code <hello@flippercode.com>
 */

if ( isset( $options['id'] ) ) {
	 $map_id = $options['id'];
} else { return ''; }

// Fetch map information.
$modelFactory = new FactoryModelWPGMP();
$map_obj = $modelFactory->create_object( 'map' );
$map_record = $map_obj->fetch( array( array( 'map_id', '=', $map_id ) ) );
$map = $map_record[0];
$category_obj = $modelFactory->create_object( 'group_map' );
$categories = $category_obj->fetch();
$all_categories = array();
$all_categories_name = array();

if ( ! empty( $categories ) ) {
	foreach ( $categories as $category ) {
		$all_categories[ $category->group_map_id ] = $category;
		$all_categories_name[ $category->group_map_title ] = $category;
	}
}
if ( ! empty( $map->map_locations ) ) {
		$location_obj = $modelFactory->create_object( 'location' );
		$map_locations = $location_obj->fetch( array( array( 'location_id', 'IN', implode( ',',$map->map_locations ) ) ) );
}

$map_data = array();
// Set map options.
$map_data['places'] = array();
$map_data['map_options'] = array(
'center_lat' => sanitize_text_field( $map->map_all_control['map_center_latitude'] ),
'center_lng' => sanitize_text_field( $map->map_all_control['map_center_longitude'] ),
'zoom' => intval( $map->map_zoom_level ),
'map_type_id' => sanitize_text_field( $map->map_type ),
'draggable' => (sanitize_text_field( $map->map_all_control['map_draggable'] ) != 'false'),
'scroll_wheel' => sanitize_text_field( $map->map_scrolling_wheel ),
'display_45_imagery' => sanitize_text_field( $map->map_45imagery ),
'marker_default_icon' => esc_url( $map->map_all_control['marker_default_icon'] ),
'infowindow_setting' => wp_unslash( $map->map_all_control['infowindow_setting'] ),
'default_infowindow_open' => false,
'infowindow_open_event' => 'click',
'pan_control' => ($map->map_all_control['pan_control'] != 'false'),
'zoom_control' => ($map->map_all_control['zoom_control'] != 'false'),
'map_type_control' => ($map->map_all_control['map_type_control'] != 'false'),
'scale_control' => ( $map->map_all_control['scale_control'] != 'false'),
'street_view_control' => ($map->map_all_control['street_view_control'] != 'false'),
'overview_map_control' => ($map->map_all_control['overview_map_control'] != 'false'),
'pan_control_position' => $map->map_all_control['pan_control_position'],
'zoom_control_position' => $map->map_all_control['zoom_control_position'],
'zoom_control_style' => $map->map_all_control['zoom_control_style'],
'map_type_control_position' => $map->map_all_control['map_type_control_position'],
'map_type_control_style' => $map->map_all_control['map_type_control_style'],
'street_view_control_position' => $map->map_all_control['street_view_control_position'],
);

$map_data['map_options']['width'] = sanitize_text_field( $map->map_width );

$map_data['map_options']['height'] = sanitize_text_field( $map->map_height );

$map_data['map_options'] = apply_filters( 'wpgmp_map_options',$map_data['map_options'] );

if ( isset( $map_data['map_options']['width'] ) ) {
	$width = $map_data['map_options']['width'];
} else { 	$width = '100%'; }

if ( isset( $map_data['map_options']['height'] ) ) {
	$height = $map_data['map_options']['height'];
} else { 	$height = '300px'; }

if ( strstr( $width, '%' ) === false ) {
	$width = str_replace( 'px', '', $width ).'px';
}

if ( strstr( $height, '%' ) === false ) {
	$height = str_replace( 'px', '', $height ).'px';
}

$wpgmp_local = array();
if ( get_option( 'wpgmp_language' ) ) {
	$wpgmp_local['language'] = get_option( 'wpgmp_language' );
} else { $wpgmp_local['language'] = 'en'; }

$wpgmp_local['wpgmp_not_working'] = __( 'not working...', WPGMP_TEXT_DOMAIN );
$wpgmp_local['place_icon_url'] = WPGMP_ICONS;
$wpgmp_local['wpgmp_location_no_results'] = __( 'No results found.', WPGMP_TEXT_DOMAIN );
$wpgmp_local['wpgmp_route_not_avilable'] = __( 'Route is not available for your requested route.', WPGMP_TEXT_DOMAIN );
wp_localize_script( 'wpgmp-google-map-main', 'wpgmp_local',$wpgmp_local );

if ( is_array( $map_locations ) ) {
	$loc_count = 0;
	foreach ( $map_locations as $location ) {
		$location_categories = array();
		if ( empty( $location->location_group_map ) ) {
			$map_data['places'][ $loc_count ]['categories'][] = array(
			  'id'      => '',
			  'name'    => '',
			  'type'    => 'category',
			  'icon'    => '',
			);
		} else {

			foreach ( $location->location_group_map as $key => $loc_category_id ) {
				$loc_category = $all_categories[ $loc_category_id ];
				$location_categories[] = array(
				'id'      => $loc_category->group_map_id,
				'name'    => $loc_category->group_map_title,
				'type'    => 'category',
				'icon'    => $loc_category->group_marker,
				);
			}
		}
		$onclick = isset( $location->location_settings['onclick'] ) ? $location->location_settings['onclick'] : 'marker';
		$map_data['places'][ $loc_count ] = array(
			'id'          => $location->location_id,
			'title'       => $location->location_title,
			'address'     => $location->location_address,
			'content'     => ('' != $location->location_messages) ? stripcslashes( $location->location_messages ) : $location->location_title,
			'location' => array(
			'icon'      => $location_categories[0]['icon'],
			'lat'       => $location->location_latitude,
			'lng'       => $location->location_longitude,
			'city'      => $location->location_city,
			'state'     => $location->location_state,
			'country'   => $location->location_country,
			'redirect_url' => $onclick,
			'redirect_custom_link' => $location->location_settings['redirect_link'],
			'open_new_tab' => $location->location_settings['redirect_link_window'],
			'postal_code' => $location->location_postal_code,
			'draggable' => ( 'true' == $location->location_draggable ),
			'infowindow_default_open' => $location->location_infowindow_default_open,
			'animation' => $location->location_animation,
			'infowindow_disable' => false,
			'zoom'      => 5,
			),
			'categories' => $location_categories,
		  );

		$loc_count++;
	}
}

if ( ! empty( $map->map_layer_setting['choose_layer']['bicycling_layer'] ) && $map->map_layer_setting['choose_layer']['bicycling_layer'] == 'BicyclingLayer' ) {
	$map_data['bicyle_layer'] = array(
	'display_layer' => true,
	);
}

if ( ! empty( $map->map_layer_setting['choose_layer']['traffic_layer'] ) && $map->map_layer_setting['choose_layer']['traffic_layer'] == 'TrafficLayer' ) {
	$map_data['traffic_layer']  = array(
	'display_layer' => true,
	);
}

if ( ! empty( $map->map_layer_setting['choose_layer']['transit_layer'] ) && $map->map_layer_setting['choose_layer']['transit_layer'] == 'TransitLayer' ) {
	$map_data['transit_layer']  = array(
	'display_layer' => true,
	);
}

if ( '' == $map_data['map_options']['center_lat'] ) {
	$map_data['map_options']['center_lat'] = $map_data['places'][0]['location']['lat'];
}

if ( '' == $map_data['map_options']['center_lng'] ) {
	$map_data['map_options']['center_lng'] = $map_data['places'][0]['location']['lng'];
}


// Street view.
if ( $map->map_street_view_setting['street_control'] == 'true' ) {
	$map_data['street_view'] = array(
	'street_control'            => @$map->map_street_view_setting['street_control'],
	'street_view_close_button'  => (@$map->map_street_view_setting['street_view_close_button'] === 'true'?true:false),
	'links_control'             => (@$map->map_street_view_setting['links_control'] === 'true'?true:false),
	'street_view_pan_control'   => (@$map->map_street_view_setting['street_view_pan_control'] === 'true'?true:false),
	'pov_heading'				=> $map->map_street_view_setting['pov_heading'],
	'pov_pitch'					=> $map->map_street_view_setting['pov_pitch'],
	);
}


$map_data['map_property'] = array( 'map_id' => $map->map_id );

$map_output = '';

	  	$map_output .= '<div class="wpgmp_map_container" rel="map'.$map->map_id.'">';

		$map_output .= '<div class="wpgmp_map" style="width:'.$width.'; height:'.$height.';" id="map'.$map->map_id.'" ></div>';
if ( ! empty( $map->map_all_control['display_listing'] ) && $map->map_all_control['display_listing'] == true ) {

			$map_output .= '<div class="location_listing'.$map->map_id.'" style="float:left; width:100%;"></div>
			<div class="location_pagination'.$map->map_id.' wpgmp_pagination" style="float:left; width:100%;"></div>';
}
		$map_output .= '</div>';
		$map_data_obj = json_encode( $map_data );

$map_output .= '<script>
  jQuery(document).ready(function($) {

    var map = $("#map'.$map_id.'").maps('.$map_data_obj.').data("wpgmp_maps");

  });
  </script>';
	return $map_output;
