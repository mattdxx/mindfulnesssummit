<?php
/**
 * Template for Add & Edit Map
 * @author  Flipper Code <hello@flippercode.com>
 * @package Maps
 */

if ( isset( $_REQUEST['_wpnonce'] ) ) {

	$nonce = sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {

		die( 'Cheating...' );

	} else {
		$data = $_POST;
	}
}
global $wpdb;
$modelFactory = new FactoryModelWPGMP();
$map_obj = $modelFactory->create_object( 'map' );
if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] and isset( $_GET['map_id'] ) ) {
	$map_obj = $map_obj->fetch( array( array( 'map_id', '=', intval( wp_unslash( $_GET['map_id'] ) ) ) ) );
	$data = (array) $map_obj[0];
} elseif ( ! isset( $_GET['doaction'] ) and isset( $response['success'] ) ) {
	// Reset $_POST object for antoher entry.
	unset( $data );
}

$form  = new Responsive_Markup();
$form->set_header( __( 'Map Information', WPGMP_TEXT_DOMAIN ), $response, __( 'Manage Maps', WPGMP_TEXT_DOMAIN ), 'wpgmp_manage_map' );
include( 'map-forms/general-setting-form.php' );
include( 'map-forms/map-center-settings.php' );
include( 'map-forms/locations-form.php' );
include( 'map-forms/control-setting-form.php' );
include( 'map-forms/control-position-style-form.php' );
include( 'map-forms/street-view-setting-form.php' );
include( 'map-forms/layers-form.php' );
$form->add_element( 'submit', 'save_entity_data', array(
	'value' => __( 'Save Map',WPGMP_TEXT_DOMAIN ),
));
$form->add_element( 'hidden', 'operation', array(
	'value' => 'save',
));
$form->add_element( 'hidden', 'map_locations', array(
	'value' => '',
));
if ( isset( $_GET['doaction'] ) and 'edit' == $_GET['doaction'] and isset( $_GET['map_id'] ) ) {

	$form->add_element( 'hidden', 'entityID', array(
		'value' => intval( wp_unslash( $_GET['map_id'] ) ),
	));
}
$form->render();
