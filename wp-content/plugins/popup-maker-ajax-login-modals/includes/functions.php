<?php
/**
 * Helper Functions
 *
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Returns the ajax login modals meta of a popup.
 *
 * @since 1.0
 * @param int $popup_id ID number of the popup to retrieve a ajax login modals meta for
 * @return mixed array|string of the popup ajax login modals meta 
 */
function popmake_get_popup_ajax_login( $popup_id = NULL, $key = NULL ) {
	global $popmake_login_modal;

	if( did_action( 'wp_head' ) && ! $popup_id && $popmake_login_modal > 0 ) {
		$popup_id = $popmake_login_modal;
	}

	return popmake_get_popup_meta_group( 'ajax_login', $popup_id, $key );
}

function popmake_get_popup_ajax_registration( $popup_id = NULL, $key = NULL ) {
	global $popmake_registration_modal;

	if( did_action( 'wp_head' ) && ! $popup_id && $popmake_registration_modal > 0 ) {
		$popup_id = $popmake_registration_modal;
	}

	return popmake_get_popup_meta_group( 'ajax_registration', $popup_id, $key );
}

function popmake_get_popup_ajax_recovery( $popup_id = NULL, $key = NULL ) {
	global $popmake_recovery_modal;

	if( did_action( 'wp_head' ) && ! $popup_id && $popmake_recovery_modal > 0 ) {
		$popup_id = $popmake_recovery_modal;
	}

	return popmake_get_popup_meta_group( 'ajax_recovery', $popup_id, $key );
}
