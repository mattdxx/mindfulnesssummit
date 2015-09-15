<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit; ?>
<div class='popmake-ajax-form popmake-registration-form'>
	<form id="ajax-registration-form" action="<?php echo wp_registration_url(); ?>" method="post">
		<p class="registration-username">
			<label for="ajax_registration_user"><?php _e( 'Username' ); ?></label>
			<input type="text" name="user_login" id="ajax_registration_user" class="input" value="<?php esc_attr_e( stripslashes( $user_login ) ); ?>" size="20" tabindex="1" />
		</p>
		<p class="registration-email">
			<label for="ajax_registration_email"><?php _e( 'Email' ); ?></label>
			<input type="text" name="user_email" id="ajax_registration_email" class="input" value="<?php esc_attr_e( stripslashes( $user_email ) ); ?>" size="20" tabindex="3" />
		</p>

		<?php if( ! empty( $ajax_registration['enable_password'] ) ) : ?>
		<p class="registration-password">
			<label for="ajax_registration_pass"><?php _e( 'Password' ); ?></label>
			<input type="password" name="user_pass" id="ajax_registration_pass" class="input" size="20" tabindex="3" />
		</p>
		<p class="registration-confirm">
			<label for="ajax_registration_confirm"><?php _e( 'Confirm Password', 'popup-maker-ajax-login-modals' ); ?></label>
			<input type="password" name="user_pass2" id="ajax_registration_confirm" class="input" size="20" tabindex="3" />
		</p>
		<?php endif; ?>
		
		<?php if( ! empty( $ajax_registration['enable_autologin'] ) ) : ?>
			<input type="hidden" name="ajax_registration_autologin" id="ajax_registration_autologin" value="true" />
		<?php endif; ?>
		<?php do_action('register_form'); ?>
		<p class="registration-submit">
			<input type="submit" name="wp-submit" id="ajax_registration_submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Register' ); ?>" />
		</p>
	</form>
	<?php echo popmake_alm_footer_links( array( 'login', 'recovery' ) ); ?>
</div>
