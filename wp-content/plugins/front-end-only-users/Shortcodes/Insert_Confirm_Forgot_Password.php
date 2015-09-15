<?php
function Insert_Confirm_Forgot_Password($atts) {
	global $wpdb, $user_message, $feup_success;
	global $ewd_feup_user_table_name;
		
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");	

	$CheckCookie = CheckLoginCookie();
	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Time = time();
	$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $CheckCookie['Username']));
		
	$ReturnString = "";
		
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				'redirect_page' => '#',
				'login_page' => '',
				'submit_text' => __('Change password', 'EWD_FEUP')),
			$atts
		)
	);
												
	$ReturnString .= "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= "</style>";
		
	if ($feup_success and $redirect_page != '#') {FEUPRedirect($redirect_page);}
		
	$ReturnString .= "<div id='ewd-feup-edit-profile-form-div'>";
	if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
	$ReturnString .= "<form action='#' method='post' id='ewd-feup-edit-profile-form' class='feup-pure-form pure-form-aligned'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='confirm-forgot-password'>";
	$ReturnString .= "<div class='feup-pure-control-group'>";
	$ReturnString .= "<label for='Email' id='ewd-feup-edit-password' class='ewd-feup-field-label'>" . __('Email', 'EWD_FEUP') . ": </label>";
	$ReturnString .= "<input type='email' class='ewd-feup-text-input' name='Email' class='ewd-feup-text-input' value='".$_GET['add']."' />";
	$ReturnString .= "</div>";
	$ReturnString .= "<div class='feup-pure-control-group'>";
	$ReturnString .= "<label for='Resetcode' id='ewd-feup-edit-password' class='ewd-feup-field-label'>" . __('Reset code', 'EWD_FEUP') . ": </label>";
	$ReturnString .= "<input type='text' class='ewd-feup-text-input' name='Resetcode' class='ewd-feup-text-input' value='".$_GET['rc']."' />";
	$ReturnString .= "</div>";
	$ReturnString .= "<div class='feup-pure-control-group'>";
	$ReturnString .= "<label for='User_Password' id='ewd-feup-edit-password' class='ewd-feup-field-label'>" . __('Password', 'EWD_FEUP') . ": </label>";
	$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='User_Password' class='ewd-feup-text-input' value='' />";
	$ReturnString .= "</div>";
	$ReturnString .= "<div class='feup-pure-control-group'>";
	$ReturnString .= "<label for='Confirm_User_Password' id='ewd-feup-edit-confirm-password' class='ewd-feup-field-label'>" . __('Repeat Password', 'EWD_FEUP') . ": </label>";
	$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='Confirm_User_Password' class='ewd-feup-text-input' value='' />";
	$ReturnString .= "</div>";
	$ReturnString .= "<div class='feup-pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Edit_Password_Submit' value='" . $submit_text . "'></div>";
	$ReturnString .= "</div>";
	$ReturnString .= "</form>";

	return $ReturnString;
}
add_shortcode("confirm-forgot-password", "Insert_Confirm_Forgot_Password");
?>
