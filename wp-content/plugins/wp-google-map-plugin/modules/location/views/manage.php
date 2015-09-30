<?php

if ( class_exists( 'WP_List_Table_Helper' ) and ! class_exists( 'Wpgmp_Location_Table' ) ) {

	class Wpgmp_Location_Table extends WP_List_Table_Helper {  public function __construct($tableinfo) {
			parent::__construct( $tableinfo ); }  }

	// Minimal Configuration :)
	global $wpdb;
	$columns   = array( 'location_title' => 'Title','location_address' => 'Address','location_latitude' => 'Latitude','location_longitude' => 'Longitude' );
	$sortable  = array( 'location_title','location_address','location_latitude','location_longitude' );
	$tableinfo = array(
	'table' => $wpdb->prefix.'map_locations',
	'textdomain' => WPGMP_TEXT_DOMAIN,
	'singular_label' => 'location',
	'plural_label' => 'locations',
	'admin_listing_page_name' => 'wpgmp_manage_location',
	'admin_add_page_name' => 'wpgmp_form_location',
	'primary_col' => 'location_id',
	'columns' => $columns,
	'sortable' => $sortable,
	'per_page' => 200,
	'actions' => array( 'edit','delete' ),
	'col_showing_links' => 'location_title',
	'editrecord_filepath' => WPGMP_VIEWS_PATH.'/location/add_or_edit.php',
	);
	return new Wpgmp_Location_Table( $tableinfo );

}
?>
