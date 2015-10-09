<?php
/**
 * Helper Functions
 *
 * @package     PopMake\AJAXLoginModals\TemplateFunctions
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


function popmake_alm_footer_links( $which = array() ) {
	global $popmake_login_modal, $popmake_registration_modal, $popmake_recovery_modal;

	if( empty( $which ) ) {
		return;
	}

	ob_start(); ?>

	<ul class='popmake-alm-footer-links'><?php
		foreach( $which as $key ) : switch($key) :

		case 'login':
			if( popmake_get_popup_ajax_login( $popmake_login_modal, 'enabled' ) ) { ?>
				<li><?php _e( 'Already have an account?', 'popup-maker-ajax-login-modals' ); ?> <a href="<?php echo wp_login_url(); ?>" class="popmake-<?php echo $popmake_login_modal; ?> popswitch-login"><?php _e( 'Log in' ); ?></a></li><?php
			}
			break;

		case 'registration':
			$register = wp_register( '', '', false );
			if( popmake_get_popup_ajax_registration( $popmake_registration_modal, 'enabled' ) && ! empty( $register ) ) { ?>
				<li><?php _e( 'Don\'t have an account?', 'popup-maker-ajax-login-modals' ); ?> <?php echo $register; ?></li><?php
			}
			break;

		case 'recovery':
			if( popmake_get_popup_ajax_recovery( $popmake_recovery_modal, 'enabled' ) ) { ?>
				<li><?php _e( 'Lost your password?' ); ?> <a href='<?php echo wp_lostpassword_url(); ?>' class='popswitch-recovery'><?php _e( 'Click here', 'popup-maker-ajax-login-modals' ); ?></a></li><?php
			}
			break;

		endswitch; endforeach;

		do_action( 'popmake_alm_footer_links' ); ?>
	</ul><?php

	return ob_get_clean();
}
