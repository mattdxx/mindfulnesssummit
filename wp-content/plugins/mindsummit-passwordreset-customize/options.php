<?php
	
	# Exit if accessed directly
	if (!defined('ABSPATH'))
		exit;
	
	if (!class_exists('MindSummit_PasswordReset_Customize_Settings'))
	{
		class MindSummit_PasswordReset_Customize_Settings
		{
			private $plname = 'mindsummit-passwordreset-customize';
			
			private static $instance;
			
			public static function instance()
			{
				if (!self::$instance)
				{
					self::$instance = new MindSummit_PasswordReset_Customize_Settings();
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
					'Customize PasswordReset emails',
					'PassReset Settings',
					'administrator',
					__FILE__,
					array($this, 'settings_page')
					);
				
				# set of allowed params to submit
				add_action('admin_init', array($this, 'register_settings'));
			}
			
			public function register_settings()
			{
				register_setting( 'mindsummit-passreset-settings-group', 'mindsummit_passwordreset_title' );
			}
			
			public function settings_page()
			{
				?>
				<div class="wrap">
				<h2>Customize settings for outgoing PasswordReset emails</h2>
				
				<form method="post" action="options.php">
					<?php settings_fields( 'mindsummit-passreset-settings-group' ); ?>
					<table class="form-table">
						<tr valign="top">
						<th scope="row">Subject</th>
						<td><input type="text" style="width:500px;" name="mindsummit_passwordreset_title" value="<?php echo htmlentities(get_option('mindsummit_passwordreset_title')); ?>" /></td>
						</tr>
					</table>
					
					<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
					</p>
					
				</form>
				</div>
				<?php
			}
			
		} # class MindSummit_PasswordReset_Customize_Settings
	} # if (!class_exists('MindSummit_PasswordReset_Customize_Settings'))
	
	MindSummit_PasswordReset_Customize_Settings::instance();
	
?>
