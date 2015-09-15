<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'PopMake_Ajax_Login_Modals_Admin_Popup_Metabox_Fields' ) ) {

	/**
	 * Main PopMake_Ajax_Login_Modals_Admin_Popup_Metabox_Fields class
	 *
	 * @since       1.0.0
	 */
	class PopMake_Ajax_Login_Modals_Admin_Popup_Metabox_Fields {

		public function login_enabled( $popup_id ) { ?>
			<tr>
				<th scope="row"><?php _e( 'Enable AJAX Login Modals', 'popup-maker-ajax-login-modals' );?></th>
				<td>
					<input type="checkbox" value="true" name="popup_ajax_login_enabled" id="popup_ajax_login_enabled" <?php echo popmake_get_popup_ajax_login( $popup_id, 'enabled' ) ? 'checked="checked" ' : '';?>/>
					<label for="popup_ajax_login_enabled" class="description"><?php _e( 'Checking this will enable login modal functionality.', 'popup-maker-ajax-login-modals' );?></label>
				</td>
			</tr><?php
		}


		public function login_force_login( $popup_id ) { ?>
			<tr class="ajax_login_enabled">
				<th scope="row"><?php _e( 'Force Login', 'popup-maker-ajax-login-modals' );?></th>
				<td>
					<input type="checkbox" value="true" name="popup_ajax_login_force_login" id="popup_ajax_login_force_login" <?php echo popmake_get_popup_ajax_login( $popup_id, 'force_login' ) ? 'checked="checked" ' : '';?>/>
					<label for="popup_ajax_login_force_login" class="description"><?php _e( 'Checking this will force users to login before they can close the modal.', 'popup-maker-ajax-login-modals' );?></label>
				</td>
			</tr><?php
		}

		public function login_allow_remember( $popup_id ) { ?>
			<tr class="ajax_login_enabled">
				<th scope="row"><?php _e( 'Allow Remember User?', 'popup-maker-ajax-login-modals' );?></th>
				<td>
					<input type="checkbox" value="true" name="popup_ajax_login_allow_remember" id="popup_ajax_login_allow_remember" <?php echo popmake_get_popup_ajax_login( $popup_id, 'allow_remember' ) ? 'checked="checked" ' : '';?>/>
					<label for="popup_ajax_login_allow_remember" class="description"><?php _e( 'Checking this will allow users to use remember me function.', 'popup-maker-ajax-login-modals' );?></label>
				</td>
			</tr><?php
		}

		public function login_disable_redirect( $popup_id ) { ?>
			<tr class="ajax_login_enabled">
				<th scope="row"><?php _e( 'Disable Redirect after Login', 'popup-maker-ajax-login-modals' );?></th>
				<td>
					<input type="checkbox" value="true" name="popup_ajax_login_disable_redirect" id="popup_ajax_login_disable_redirect" <?php echo popmake_get_popup_ajax_login( $popup_id, 'disable_redirect' ) ? 'checked="checked" ' : '';?>/>
					<label for="popup_ajax_login_disable_redirect" class="description"><?php _e( 'Checking this will not refresh the page after login. This may not work for situations, things like admin bar cannot be shown without refresh.', 'popup-maker-ajax-login-modals' );?></label>
				</td>
			</tr><?php
		}

		public function login_redirect_url( $popup_id ) { ?>
			<tr class="ajax_login_enabled ajax_login_redirect_enabled">
				<th scope="row">
					<label for="popup_ajax_login_redirect_url">
						<?php _e( 'Login Redirect URL', 'popup-maker-ajax-login-modals' );?>
					</label>
				</th>
				<td>
					<input type="text" class="regular-text" name="popup_ajax_login_redirect_url" id="popup_ajax_login_redirect_url" value="<?php esc_attr_e(popmake_get_popup_ajax_login( $popup_id, 'redirect_url' ))?>"/>
					<p class="description"><?php _e( 'If you want to redirect to another page after login enter the url here. Leaving blank will keep user on the same page.', 'popup-maker-ajax-login-modals' )?></p>
				</td>
			</tr><?php
		}


		public function registration_enabled( $popup_id ) { ?>
			<tr>
				<th scope="row"><?php _e( 'Enable Registration Modal', 'popup-maker-ajax-login-modals' );?></th>
				<td>
					<input type="checkbox" value="true" name="popup_ajax_registration_enabled" id="popup_ajax_registration_enabled" <?php echo popmake_get_popup_ajax_registration( $popup_id, 'enabled' ) ? 'checked="checked" ' : '';?>/>
					<label for="popup_ajax_registration_enabled" class="description"><?php _e( 'Checking this will enable registration modal functionality.', 'popup-maker-ajax-login-modals' );?></label><?php
					$multisite_reg = get_site_option( 'registration' );
					if( !( get_option( 'users_can_register' ) && ! is_multisite() ) && !( $multisite_reg == 'all' || $multisite_reg == 'blog' || $multisite_reg == 'user' ) ) { ?>
						<p class="description"><?php _e( 'Site registration is currently closed. This must be enabled for registration modal functionality to work.', 'popup-maker-ajax-login-modals' );?></p><?php
					}?>
				</td>
			</tr><?php
		}

		public function registration_enable_password( $popup_id ) { ?>
			<tr class="ajax_registration_enabled">
				<th scope="row"><?php _e( 'User created passwords?', 'popup-maker-ajax-login-modals' );?></th>
				<td>
					<input type="checkbox" value="true" name="popup_ajax_registration_enable_password" id="popup_ajax_registration_enable_password" <?php echo popmake_get_popup_ajax_registration( $popup_id, 'enable_password' ) ? 'checked="checked" ' : '';?>/>
					<label for="popup_ajax_registration_enable_password" class="description"><?php _e( 'Checking this will allow the user to enter their own password. Otherwise it will create and send them a unique password.', 'popup-maker-ajax-login-modals' );?></label>
				</td>
			</tr><?php
		}

		public function registration_enable_autologin( $popup_id ) { ?>
			<tr class="ajax_registration_enabled">
				<th scope="row"><?php _e( 'Login After Registration?', 'popup-maker-ajax-login-modals' );?></th>
				<td>
					<input type="checkbox" value="true" name="popup_ajax_registration_enable_autologin" id="popup_ajax_registration_enable_autologin" <?php echo popmake_get_popup_ajax_registration( $popup_id, 'enable_autologin' ) ? 'checked="checked" ' : '';?>/>
					<label for="popup_ajax_registration_enable_autologin" class="description"><?php _e( 'Checking this will log the user in automatically after registration.', 'popup-maker-ajax-login-modals' );?></label>
				</td>
			</tr><?php
		}

		public function registration_disable_redirect( $popup_id ) { ?>
			<tr class="ajax_registration_enabled">
				<th scope="row"><?php _e( 'Disable Redirect after Regitration', 'popup-maker-ajax-login-modals' );?></th>
				<td>
					<input type="checkbox" value="true" name="popup_ajax_registration_disable_redirect" id="popup_ajax_registration_disable_redirect" <?php echo popmake_get_popup_ajax_registration( $popup_id, 'disable_redirect' ) ? 'checked="checked" ' : '';?>/>
					<label for="popup_ajax_registration_disable_redirect" class="description"><?php _e( 'Checking this will not refresh the page after registration. This may not work for situations, things like admin bar cannot be shown without refresh.', 'popup-maker-ajax-login-modals' );?></label>
				</td>
			</tr><?php
		}

		public function registration_redirect_url( $popup_id ) { ?>
			<tr class="ajax_registration_enabled ajax_registration_redirect_enabled">
				<th scope="row">
					<label for="popup_ajax_registration_redirect_url">
						<?php _e( 'Registration Redirect URL', 'popup-maker-ajax-login-modals' );?>
					</label>
				</th>
				<td>
					<input type="text" class="regular-text" name="popup_ajax_registration_redirect_url" id="popup_ajax_registration_redirect_url" value="<?php esc_attr_e(popmake_get_popup_ajax_registration( $popup_id, 'redirect_url' ))?>"/>
					<p class="description"><?php _e( 'If you want to redirect to another page after registration enter the url here. Leaving blank will keep user on the same page.', 'popup-maker-ajax-login-modals' )?></p>
				</td>
			</tr><?php
		}


		public function recovery_enabled( $popup_id ) { ?>
			<tr>
				<th scope="row"><?php _e( 'Enable Password Recovery Modal', 'popup-maker-ajax-login-modals' );?></th>
				<td>
					<input type="checkbox" value="true" name="popup_ajax_recovery_enabled" id="popup_ajax_recovery_enabled" <?php echo popmake_get_popup_ajax_recovery( $popup_id, 'enabled' ) ? 'checked="checked" ' : '';?>/>
					<label for="popup_ajax_recovery_enabled" class="description"><?php _e( 'Checking this will enable password recovery modal functionality.', 'popup-maker-ajax-login-modals' );?></label>
				</td>
			</tr><?php
		}

		public function recovery_disable_redirect( $popup_id ) { ?>
			<tr class="ajax_recovery_enabled">
				<th scope="row"><?php _e( 'Disable Redirect after Recovery', 'popup-maker-ajax-login-modals' );?></th>
				<td>
					<input type="checkbox" value="true" name="popup_ajax_recovery_disable_redirect" id="popup_ajax_recovery_disable_redirect" <?php echo popmake_get_popup_ajax_recovery( $popup_id, 'disable_redirect' ) ? 'checked="checked" ' : '';?>/>
					<label for="popup_ajax_recovery_disable_redirect" class="description"><?php _e( 'Checking this will not refresh the page after recovery. This may not work for situations, things like admin bar cannot be shown without refresh.', 'popup-maker-ajax-login-modals' );?></label>
				</td>
			</tr><?php
		}

		public function recovery_redirect_url( $popup_id ) { ?>
			<tr class="ajax_recovery_enabled ajax_recovery_redirect_enabled">
				<th scope="row">
					<label for="popup_ajax_recovery_redirect_url">
						<?php _e( 'Recovery Redirect URL', 'popup-maker-ajax-login-modals' );?>
					</label>
				</th>
				<td>
					<input type="text" class="regular-text" name="popup_ajax_recovery_redirect_url" id="popup_ajax_recovery_redirect_url" value="<?php esc_attr_e(popmake_get_popup_ajax_recovery( $popup_id, 'redirect_url' ))?>"/>
					<p class="description"><?php _e( 'If you want to redirect to another page after password recovery enter the url here. Leaving blank will keep user on the same page.', 'popup-maker-ajax-login-modals' )?></p>
				</td>
			</tr><?php
		}

    }
} // End if class_exists check