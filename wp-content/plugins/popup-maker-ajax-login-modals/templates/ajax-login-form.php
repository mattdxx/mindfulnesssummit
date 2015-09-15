<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

$popup_id = function_exists( 'get_the_popup_ID' ) ? get_the_popup_ID() : get_the_ID(); ?>

<div class='popmake-ajax-form popmake-login-form'><?php

	$args = apply_filters( 'popmake_alm_login_form_args', array(
		'redirect' => ! empty( $ajax_login['redirect_url'] ) ? $ajax_login['redirect_url'] : site_url( $_SERVER['REQUEST_URI'] ),
		'form_id' => 'ajax-login-form',
		'label_username' => __( 'Username' ),
		'label_password' => __( 'Password' ),
		'label_remember' => __( 'Remember Me' ),
		'label_log_in' => __( 'Log In' ),
		'id_username' => 'ajax_login_user',
		'id_password' => 'ajax_login_pass',
		'id_remember' => 'ajax_login_remember',
		'id_submit' => 'ajax_login_submit',
		'remember' => ! empty( $ajax_login['allow_remember'] ) ? true : false,
		'value_username' => $user_login,
		'value_remember' => false
	), $popup_id );

	wp_login_form( $args );

	echo popmake_alm_footer_links( array( 'registration', 'recovery' ) ); ?>
	
</div>
