<?php
function Insert_Forgot_Password_Form($atts) {
	global $wpdb, $user_message, $feup_success;
	global $ewd_feup_user_table_name;
		
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	$Use_Captach = get_option("EWD_FEUP_Use_Captcha");
		
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				'redirect_page' => '#',
				'loggedin_page' => '/',
				'reset_email_url' => '',
				'submit_text' => __('Reset password', 'EWD_FEUP')),
			$atts
		)
	);

	if ($feup_success and $redirect_page != '#') {FEUPRedirect($redirect_page);}
		
	$User = new FEUP_User();
	if( $User -> Is_Logged_In() )
	{
		FEUPRedirect($loggedin_page);
	}
		
	$ReturnString = "";
												
	$ReturnString .= "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= "</style>";
		
		
	$ReturnString .= "<div id='ewd-feup-forgot-password-form-div'>";
	if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
	$ReturnString .= "<form action='#' method='post' id='ewd-feup-forgot-password-form' class='feup-pure-form feup-pure-form-aligned'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-reset-email-url' value='".$reset_email_url."'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='forgot-password'>";
	$ReturnString .= "<div class='feup-pure-control-group'>";
	$ReturnString .= "<label for='Email' id='ewd-feup-reset-password' class='ewd-feup-field-label'>" . __('Email', 'EWD_FEUP') . ": </label>";
	$ReturnString .= "<input type='email' class='ewd-feup-text-input pure-input-1-3' name='Email' value='' />";
	$ReturnString .= "</div>";
	if ($Use_Captcha == "Yes") {$ReturnString .= EWD_FEUP_Add_Captcha();}
	$ReturnString .= "<div class='feup-pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Reset_Password_Submit' value='" . $submit_text . "'></div>";
	$ReturnString .= "</div>";
	
	return $ReturnString;
}
add_shortcode("forgot-password", "Insert_Forgot_Password_Form");
?>