<?php
function Insert_Reset_Password_Form($atts) {
	global $wpdb, $user_message, $feup_success;
	global $ewd_feup_user_table_name;
		
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");

	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Time = time();
	$ReturnString = "";

	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				'redirect_page' => '#',
				'login_page' => '',
				'submit_text' => __('Update Account', 'EWD_FEUP')),
			$atts
		)
	);
	
	$CheckCookie = CheckLoginCookie();
		
	if ($CheckCookie['Username'] == "") {
		$ReturnString .= __('You must be logged in to access this page.', 'EWD_FEUP');
		if ($login_page != "" ) {$ReturnString .= "<br />" . __('Please', 'EWD_FEUP') . " <a href='" . $login_page . "'>" . __('login', 'EWD_FEUP') . "</a> " . __('to continue.', 'EWD_FEUP');}
		return $ReturnString;
	}
		
	/*$Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
	$Fields = $wpdb->get_results($Sql);*/
	$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $CheckCookie['Username']));
												
	$ReturnString .= "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= "</style>";
		
	if ($feup_success and $redirect_page != '#') {FEUPRedirect($redirect_page);}
		
	$ReturnString .= "<div id='ewd-feup-edit-profile-form-div'>";
	if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
	$ReturnString .= "<form action='#' method='post' id='ewd-feup-edit-profile-form' class='feup-pure-form feup-pure-form-aligned'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='edit-account'>";
	$ReturnString .= "<input type='hidden' name='Username' value='" . $User->Username . "'>";
	$ReturnString .= "<div id='ewd-feup-register-username-div' class='ewd-feup-field-label'>" . __('Email', 'EWD_FEUP') . ": " . $User->Username . "</div>";
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
add_shortcode("reset-password", "Insert_Reset_Password_Form");
?>
