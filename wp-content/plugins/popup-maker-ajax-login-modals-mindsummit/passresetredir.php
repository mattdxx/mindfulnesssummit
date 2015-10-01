<?php
	
	# Exit if accessed directly
	if (!defined('ABSPATH'))
		exit;
	
	if (!class_exists('PopMake_Ajax_Login_Modals_MindSummit_PassResetRedir'))
	{
		class PopMake_Ajax_Login_Modals_MindSummit_PassResetRedir
		{
			private static $instance;
			
			public static function instance()
			{
				if (!self::$instance)
				{
					self::$instance = new PopMake_Ajax_Login_Modals_MindSummit_PassResetRedir();
					self::$instance->hooks();
				}
				return self::$instance;
			}
			
			private function hooks()
			{
				# create options page
				add_action('login_head', array($this, 'redir'));
			}

			public function redir()
			{
				?>
				<script>
				jQuery(function(){
					var message = jQuery('p.message.reset-pass');
					if (!message.size()) return;
					if (window.getComputedStyle(message.get(0)).display === 'none') return;
					if (!message.html().match(/Your password has been reset/)) return;
					message.html('Your password has been reset. You will be redirected shortly.');
					window.setTimeout(function(){
						window.location.href = '/live?pop=login&email=<?php echo urlencode(urldecode($_COOKIE['mindsummit_passreset_email'])); ?>';
					}, 5000);
				});
				</script>
				<?php
			}
			
			
		} # class PopMake_Ajax_Login_Modals_MindSummit_PassResetRedir
	} # if (!class_exists('PopMake_Ajax_Login_Modals_MindSummit_PassResetRedir'))
	
	preg_match('~^/wp-login.php~', $_SERVER['REQUEST_URI']) and
		PopMake_Ajax_Login_Modals_MindSummit_PassResetRedir::instance();
	
?>
