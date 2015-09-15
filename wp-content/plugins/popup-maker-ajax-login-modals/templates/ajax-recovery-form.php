<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit; ?>
<div class='popmake-ajax-form popmake-recovery-form'>
	<form id="ajax-recovery-form" action="<?php echo wp_lostpassword_url( ! empty( $ajax_recovery['redirect_url'] ) ? $ajax_recovery['redirect_url'] : site_url( $_SERVER['REQUEST_URI'] ) ); ?>" method="post">
		<p class="recovery-username">
			<label for="ajax_recovery_user"><?php _e( 'Username or E-mail:' ); ?></label>
			<input type="text" name="user_login" id="ajax_recovery_user" class="input" value="<?php esc_attr_e( stripslashes( $user_login ) ); ?>" size="20" />
		</p>
		<?php do_action( 'lostpassword_form' ); ?>
		<p class="recovery-submit">
			<input type="submit" name="wp-submit" id="ajax_recovery_submit" class="button button-primary button-large" value="<?php esc_attr_e('Get New Password'); ?>" />
		</p>
	</form>
	<?php echo popmake_alm_footer_links( array( 'login', 'registration' ) ); ?>
</div>
