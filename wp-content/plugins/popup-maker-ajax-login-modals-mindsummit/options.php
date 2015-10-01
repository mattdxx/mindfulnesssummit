<?php
	
	# Exit if accessed directly
	if (!defined('ABSPATH'))
		exit;
	
	if (!class_exists('PopMake_Ajax_Login_Modals_MindSummit_Settings'))
	{
		class PopMake_Ajax_Login_Modals_MindSummit_Settings
		{
			private $plname = 'popup-maker-ajax-login-modals-mindsummit';
			
			private static $instance;
			
			public static function instance()
			{
				if (!self::$instance)
				{
					self::$instance = new PopMake_Ajax_Login_Modals_MindSummit_Settings();
					self::$instance->init();
				}
				return self::$instance;
			}
			
			private function init()
			{
				# create options page
				add_action('admin_menu', array($this, 'create_menu'));
				
				# adding 'settings' link near plugin name (in the list of plugins)
				//add_filter('plugin_action_links', array($this, 'settings_link'));
			}
			
			# adding 'settings' link near plugin name (in the list of plugins)
			public function settings_link($links)
			{
				$settings_link = '<a href="'.admin_url('themes.php?page='.$this->plname.'/options.php').'">Settings</a>'; 
				array_unshift($links, $settings_link); 
				return $links; 
			}
			
			# create options page
			public function create_menu()
			{
				# insert 'RegPopup Settings' as a submenu of Appearance
				add_submenu_page(
					'themes.php',
					'Appearance of registration popup',
					'RegPopup Settings',
					'administrator',
					__FILE__,
					array($this, 'settings_page')
					);
				
				# set of allowed params to submit
				add_action('admin_init', array($this, 'register_settings'));
			}
			
			public function register_settings()
			{
				register_setting( 'regpopup-settings-group', 'popmake_login_regcapt' );
				register_setting( 'regpopup-settings-group', 'popmake_login_regtext' );
				register_setting( 'regpopup-settings-group', 'popmake_login_regtext2' );
				register_setting( 'regpopup-settings-group', 'popmake_login_logcapt' );
				register_setting( 'regpopup-settings-group', 'popmake_login_reccapt' );
				
				register_setting( 'regpopup-settings-group', 'popmake_login_regphname' );
				register_setting( 'regpopup-settings-group', 'popmake_login_regphemail' );
				register_setting( 'regpopup-settings-group', 'popmake_login_regphpass' );
				register_setting( 'regpopup-settings-group', 'popmake_login_logphemail' );
				register_setting( 'regpopup-settings-group', 'popmake_login_logphpass' );
				register_setting( 'regpopup-settings-group', 'popmake_login_recphpass' );
			}
			
			public function settings_page()
			{
				?>
				<div class="wrap">
				<h2>Customize the appearance of registration popup</h2>
				
				<form method="post" action="options.php">
					<?php settings_fields( 'regpopup-settings-group' ); ?>
					<table class="form-table">
						<tr valign="top">
						<th scope="row" colspan="2"><h2>Registration</h2></th>
						</tr>
						
						<tr valign="top">
						<th scope="row">Caption</th>
						<td><input type="text" name="popmake_login_regcapt" value="<?php echo htmlentities(get_option('popmake_login_regcapt')); ?>" /></td>
						</tr>
						 
						<tr valign="top">
						<th scope="row">Text:</th>
						<td><textarea name="popmake_login_regtext"><?php echo htmlentities(get_option('popmake_login_regtext')); ?></textarea></td>
						</tr>
						
						<tr valign="top">
						<th scope="row">Text (invitation):</th>
						<td><textarea name="popmake_login_regtext2"><?php echo htmlentities(get_option('popmake_login_regtext2')); ?></textarea></td>
						</tr>
						
						<tr valign="top">
						<th scope="row">Name mobile hint:</th>
						<td><input type="text" name="popmake_login_regphname" value="<?php echo htmlentities(get_option('popmake_login_regphname')); ?>" /></td>
						</tr>
						
						<tr valign="top">
						<th scope="row">Email mobile hint:</th>
						<td><input type="text" name="popmake_login_regphemail" value="<?php echo htmlentities(get_option('popmake_login_regphemail')); ?>" /></td>
						</tr>
						
						<tr valign="top">
						<th scope="row">Password mobile hint:</th>
						<td><input type="text" name="popmake_login_regphpass" value="<?php echo htmlentities(get_option('popmake_login_regphpass')); ?>" /></td>
						</tr>
						
						<tr valign="top">
						<th scope="row" colspan="2"><h2>Login</h2></th>
						</tr>
						
						<tr valign="top">
						<th scope="row">Login caption</th>
						<td><input type="text" name="popmake_login_logcapt" value="<?php echo htmlentities(get_option('popmake_login_logcapt')); ?>" /></td>
						</tr>
						
						<tr valign="top">
						<th scope="row">Email mobile hint:</th>
						<td><input type="text" name="popmake_login_logphemail" value="<?php echo htmlentities(get_option('popmake_login_logphemail')); ?>" /></td>
						</tr>
						
						<tr valign="top">
						<th scope="row">Password mobile hint:</th>
						<td><input type="text" name="popmake_login_logphpass" value="<?php echo htmlentities(get_option('popmake_login_logphpass')); ?>" /></td>
						</tr>
						
						<tr valign="top">
						<th scope="row" colspan="2"><h2>Recovery</h2></th>
						</tr>
						
						<tr valign="top">
						<th scope="row">Recovery caption</th>
						<td><input type="text" name="popmake_login_reccapt" value="<?php echo htmlentities(get_option('popmake_login_reccapt')); ?>" /></td>
						</tr>
						
						<tr valign="top">
						<th scope="row">Email mobile hint:</th>
						<td><input type="text" name="popmake_login_recphemail" value="<?php echo htmlentities(get_option('popmake_login_recphemail')); ?>" /></td>
						</tr>
						
					</table>
					
					<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
					</p>
					
				</form>
				</div>
				<?php
			}
			
		} # class PopMake_Ajax_Login_Modals_MindSummit_Settings
	} # if (!class_exists('PopMake_Ajax_Login_Modals_MindSummit_Settings'))
	
	PopMake_Ajax_Login_Modals_MindSummit_Settings::instance();
	
?>
