<?php
/**
 * Contro Positioning over google maps.
 * @package Maps
 * @author Flipper Code <hello@flippercode.com>
 */

$form->add_element( 'group', 'map_control_layers', array(
	'value' => __( 'Layers Settings', WPGMP_TEXT_DOMAIN ),
	'before' => '<div class="col-md-12">',
	'after' => '</div>',
));
$form->add_element( 'group', 'map_control_layers', array(
	'value' => __( 'Layers Settings', WPGMP_TEXT_DOMAIN ),
	'before' => '<div class="col-md-12">',
	'after' => '</div>',
));



$form->add_element( 'checkbox', 'map_layer_setting[choose_layer][traffic_layer]', array(
	'lable' => __( 'Traffic Layer', WPGMP_TEXT_DOMAIN ),
	'value' => 'TrafficLayer',
	'id' => 'wpgmp_traffic_layer',
	'current' => $data['map_layer_setting']['choose_layer']['traffic_layer'],
	'desc' => __( 'Please check to enable traffic Layer.', WPGMP_TEXT_DOMAIN ),
	'class' => 'chkbox_class',
));

$form->add_element( 'checkbox', 'map_layer_setting[choose_layer][transit_layer]', array(
	'lable' => __( 'Transit Layer', WPGMP_TEXT_DOMAIN ),
	'value' => 'TransitLayer',
	'id' => 'wpgmp_transit_layer',
	'current' => $data['map_layer_setting']['choose_layer']['transit_layer'],
	'desc' => __( 'Please check to enable Transit Layer.', WPGMP_TEXT_DOMAIN ),
	'class' => 'chkbox_class',
));


$form->add_element( 'checkbox', 'map_layer_setting[choose_layer][bicycling_layer]', array(
	'lable' => __( 'Bicycling Layer', WPGMP_TEXT_DOMAIN ),
	'value' => 'BicyclingLayer',
	'id' => 'wpgmp_bicycling_layer',
	'current' => $data['map_layer_setting']['choose_layer']['bicycling_layer'],
	'desc' => __( 'Please check to enable Bicycling Layer.', WPGMP_TEXT_DOMAIN ),
	'class' => 'chkbox_class',
));
