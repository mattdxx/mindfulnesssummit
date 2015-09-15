<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'PopMake_Ajax_Login_Modals_Site' ) ) {

    /**
     * Main PopMake_Ajax_Login_Modals_Site class
     *
     * @since       1.0.0
     */
    class PopMake_Ajax_Login_Modals_Site {

    	public function popup_is_loadable( $is_loadable, $popup_id ) {
			global $popmake_login_modal, $popmake_registration_modal, $popmake_recovery_modal;

			if( popmake_get_popup_ajax_login( $popup_id, 'enabled' ) || popmake_get_popup_ajax_registration( $popup_id, 'enabled' ) || popmake_get_popup_ajax_recovery( $popup_id, 'enabled' ) ) {

				if( is_user_logged_in() ) {
					return false;
				}

				if( popmake_get_popup_ajax_login( $popup_id, 'enabled' ) && $popmake_login_modal === NULL ) {
					$popmake_login_modal = $popup_id;
				}

				if( popmake_get_popup_ajax_registration( $popup_id, 'enabled' ) && $popmake_registration_modal === NULL ) {
					$popmake_registration_modal = $popup_id;
				}

				if( popmake_get_popup_ajax_recovery( $popup_id, 'enabled' ) && $popmake_recovery_modal === NULL ) {
					$popmake_recovery_modal = $popup_id;
				}

				if( ! in_array( $popup_id, array( $popmake_login_modal, $popmake_registration_modal, $popmake_recovery_modal ) ) ) {
					return false;
				}

			}

			return $is_loadable;
    	}

		public function popup_data_attr( $data_attr, $popup_id ) {
			global $popmake_login_modal, $popmake_registration_modal, $popmake_recovery_modal;

			if( ! in_array( $popup_id, array( $popmake_login_modal, $popmake_registration_modal, $popmake_recovery_modal ) ) ) {
				return $data_attr;
			}

			if( $popmake_login_modal == $popup_id ) {
				$data_attr['meta']['ajax_login'] = popmake_get_popup_ajax_login( $popup_id );
				if( popmake_get_popup_ajax_login( $popup_id, 'force_login' ) ) {
					$data_attr['meta']['close']['esc_press'] = false;
					$data_attr['meta']['close']['overlay_click'] = false;
				}
			}

			if( $popmake_registration_modal == $popup_id ) {
				$data_attr['meta']['ajax_registration'] = popmake_get_popup_ajax_registration( $popup_id );
			}

			if( $popmake_recovery_modal == $popup_id ) {
				$data_attr['meta']['ajax_recovery'] = popmake_get_popup_ajax_recovery( $popup_id );
			}

			return $data_attr;
		}

		public function popup_content_filter( $content, $popup_id ) {
			global $popmake_login_modal, $popmake_registration_modal, $popmake_recovery_modal;

			if( ! in_array( $popup_id, array( $popmake_login_modal, $popmake_registration_modal, $popmake_recovery_modal ) ) ) {
				return $content;
			}

			if( $popmake_login_modal == $popup_id && ! has_shortcode( $content, 'ajax_login_modal' ) ) {
				$content .= apply_filters( 'popmake_alm_login_form_content', '[ajax_login_modal]', $popup_id );
			}

			if( $popmake_registration_modal == $popup_id && ! has_shortcode( $content, 'ajax_registration_modal' ) ) {
				$content .= apply_filters( 'popmake_alm_registration_form_content', '[ajax_registration_modal]', $popup_id );
			}

			if( $popmake_recovery_modal == $popup_id && ! has_shortcode( $content, 'ajax_recovery_modal' ) ) {
				$content .= apply_filters( 'popmake_alm_recovery_form_content', '[ajax_recovery_modal]', $popup_id );
			}

			return $content;
		}

		public function popup_classes( $classes, $popup_id ) {
			global $popmake_login_modal, $popmake_registration_modal, $popmake_recovery_modal;

			if( ! in_array( $popup_id, array( $popmake_login_modal, $popmake_registration_modal, $popmake_recovery_modal ) ) ) {
				return $classes;
			}

			if( $popmake_login_modal == $popup_id ) {
				$classes[] = 'ajax-login';
			}

			if( $popmake_registration_modal == $popup_id ) {
				$classes[] = 'ajax-registration';
			}

			if( $popmake_recovery_modal == $popup_id ) {
				$classes[] = 'ajax-recovery';
			}

			return $classes;
		}

		public function filter_login_link( $link ) {
			global $popmake_login_modal;
			if( $popmake_login_modal !== NULL && ! empty( $link ) ) {
				$html = new DOMDocument();
				$html->loadHTML( $link );
				$anchor = $html->getElementsByTagName( 'a' )->item( 0 );
				if( ! empty( $anchor ) && $anchor->hasAttribute( 'class' ) ) {
					$classes = explode( ' ', $anchor->getAttribute( 'class' ) );
					if( ! in_array( 'popmake-'. $popmake_login_modal, $classes ) )
						$classes[] = 'popmake-'. $popmake_login_modal;
						$classes[] = 'popswitch-login';
					$classes = array_map( 'trim', array_filter( $classes ) ); //Clean existing values
					$anchor->setAttribute( 'class', implode( ' ', $classes ) ); //Set cleaned attribute
				}
				else {
					$anchor->setAttribute( 'class', 'popmake-'. $popmake_login_modal .' popswitch-login' );
				}
				return $html->saveXML( $anchor );
			}
			return $link;
		}

		public function filter_registration_link( $link ) {
			global $popmake_registration_modal;
			if( $popmake_registration_modal !== NULL && ! empty( $link ) ) {
				$html = new DOMDocument();
				$html->loadHTML( $link );
				$anchor = $html->getElementsByTagName( 'a' )->item( 0 );
				if( ! empty( $anchor ) && $anchor->hasAttribute( 'class' ) ) {
					$classes = explode( ' ', $anchor->getAttribute( 'class' ) );
					if( ! in_array( 'popmake-'. $popmake_registration_modal, $classes ) ) {
						$classes[] = 'popmake-'. $popmake_registration_modal;
						$classes[] = 'popswitch-registration';
					}
					$classes = array_map( 'trim', array_filter( $classes ) ); //Clean existing values
					$anchor->setAttribute( 'class', implode( ' ', $classes ) ); //Set cleaned attribute
				}
				else {
					$anchor->setAttribute( 'class', 'popmake-'. $popmake_registration_modal .' popswitch-registration' );
				}
				if( strpos( $link, '<li>' ) === false ) {
					$link = $html->saveXML( $anchor );
				}
				else {
					$link = '<li>' . $html->saveXML( $anchor ) . '</li>';
				}
				return $link;
			}
			return $link;
		}


		/**
		 * Load frontend scripts
		 *
		 * @since       1.0.0
		 * @return      void
		 */
		public function scripts() {
			// Use minified libraries if SCRIPT_DEBUG is turned off
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			wp_enqueue_script( 'popmake-ajax-login-modals-js', POPMAKE_AJAXLOGINMODALS_URL . 'assets/js/scripts' . $suffix . '.js?defer', array( 'popup-maker-site' ), POPMAKE_AJAXLOGINMODALS_VER, true );
			wp_enqueue_style( 'popmake-ajax-login-modals-css', POPMAKE_AJAXLOGINMODALS_URL . 'assets/css/styles' . $suffix . '.css', array( 'popup-maker-site' ), POPMAKE_AJAXLOGINMODALS_VER );
			wp_localize_script( 'popmake-ajax-login-modals-js', 'popmake_alm', array(
				'nonce' => wp_create_nonce( 'popmake-alm-nonce' ),
				'I10n' => array(
					'login_loading_text' => __( 'Checking credentials...', 'popup-maker-ajax-login-modals' ),
	                'registration_loading_text' => __( 'Processing registration...', 'popup-maker-ajax-login-modals' ),
	                'recovery_loading_text' => __( 'Looking up info...', 'popup-maker-ajax-login-modals' ),
				),
			) );
		}


    }
} // End if class_exists check
