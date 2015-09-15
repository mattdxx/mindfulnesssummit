<?php
/**
 * Shortcode Functions
 *
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

add_shortcode( 'ajax_login_modal', 'popmake_alm_ajax_login_modal_shortcode' );
function popmake_alm_ajax_login_modal_shortcode( $atts ) {
	global $popmake_login_modal, $popmake_registration_modal, $popmake_recovery_modal, $user_email, $user_login;

	get_currentuserinfo();

	$atts = shortcode_atts( array(), $atts );

	ob_start();

	$ajax_login = popmake_get_popup_ajax_login( $popmake_login_modal );
	include popmake_get_template_part( 'ajax-login-form', null, false );

	return ob_get_clean();
}

add_shortcode( 'ajax_registration_modal', 'popmake_alm_ajax_registration_modal_shortcode' );
function popmake_alm_ajax_registration_modal_shortcode( $atts ) {
	global $popmake_login_modal, $popmake_registration_modal, $popmake_recovery_modal, $user_email, $user_login;

	get_currentuserinfo();

	$atts = shortcode_atts( array(), $atts );

	ob_start();

	if( ( ! is_multisite() && get_option( 'users_can_register' ) ) || in_array( get_site_option( 'registration' ), array( 'all', 'blog', 'user' ) ) ) {

		$ajax_registration = popmake_get_popup_ajax_registration( $popmake_registration_modal );
		include popmake_get_template_part( 'ajax-registration-form', null, false );

	}

	return ob_get_clean();
}

add_shortcode( 'ajax_recovery_modal', 'popmake_alm_ajax_recovery_modal_shortcode' );
function popmake_alm_ajax_recovery_modal_shortcode( $atts ) {
	global $popmake_login_modal, $popmake_registration_modal, $popmake_recovery_modal, $user_email, $user_login;

	get_currentuserinfo();

	$atts = shortcode_atts( array(), $atts );

	ob_start();

	$ajax_recovery = popmake_get_popup_ajax_recovery( $popmake_recovery_modal );
	include popmake_get_template_part( 'ajax-recovery-form', null, false );

	return ob_get_clean();
}
