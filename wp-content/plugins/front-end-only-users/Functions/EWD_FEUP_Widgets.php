<?php
class EWD_FEUP_Login_Logout_Toggle_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'ewd_feup_login_logout_toggle_widget', // Base ID
			__('FEUP Login/Logout Toggle', 'EWD_FEUP'), // Name
			array( 'description' => __( 'Insert a login form or logout button, depending on login status', 'EWD_FEUP' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		echo do_shortcode("[login-logout-toggle login_redirect_page='". $instance['login_redirect_page'] . "' logout_redirect_page='" . $instance['logout_redirect_page'] . "']");
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$login_redirect_page = ! empty( $instance['login_redirect_page'] ) ? $instance['login_redirect_page'] : __( 'Login Redirect Page', 'EWD_FEUP' );
		$logout_redirect_page = ! empty( $instance['logout_redirect_page'] ) ? $instance['logout_redirect_page'] : __( 'Logout Redirect Page', 'EWD_FEUP' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'login_redirect_page' ); ?>"><?php _e( 'Login Redirect Page:', 'EWD_FEUP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'login_redirect_page' ); ?>" name="<?php echo $this->get_field_name( 'login_redirect_page' ); ?>" type="text" value="<?php echo esc_attr( $login_redirect_page ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'logout_redirect_page' ); ?>"><?php _e( 'Logout Redirect Page:', 'EWD_FEUP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'logout_redirect_page' ); ?>" name="<?php echo $this->get_field_name( 'logout_redirect_page' ); ?>" type="text" value="<?php echo esc_attr( $logout_redirect_page ); ?>">
		</p>
		<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['login_redirect_page'] = ( ! empty( $new_instance['login_redirect_page'] ) ) ? strip_tags( $new_instance['login_redirect_page'] ) : '';
		$instance['logout_redirect_page'] = ( ! empty( $new_instance['logout_redirect_page'] ) ) ? strip_tags( $new_instance['logout_redirect_page'] ) : '';

		return $instance;
	}
}
add_action('widgets_init', create_function('', 'return register_widget("EWD_FEUP_Login_Logout_Toggle_Widget");'));

?>