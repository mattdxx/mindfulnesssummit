<?php
function Insert_Edit_Account_Form($atts) {
		global $wpdb, $user_message, $feup_success;
		global $ewd_feup_user_table_name;
		
		$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
		$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
		
		$CheckCookie = CheckLoginCookie();
		
		//$Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
		//$Fields = $wpdb->get_results($Sql);
		$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $CheckCookie['Username']));
		
		$ReturnString = "";
		
		// Get the attributes passed by the shortcode, and store them in new variables for processing
		extract( shortcode_atts( array(
						 								 		'redirect_page' => '#',
																'login_page' => '',
																'submit_text' => __('Update Account', 'EWD_FEUP')),
																$atts
														)
												);
												
		$ReturnString .= "<style type='text/css'>";
		$ReturnString .= $Custom_CSS;
		$ReturnString .= "</style>";
		
		if ($CheckCookie['Username'] == "") {
				$ReturnString .= __('You must be logged in to access this page.', 'EWD_FEUP');
				if ($login_page != "") {$ReturnString .= "<br />" . __('Please', 'EWD_FEUP') . " <a href='" . $login_page . "'>" . __('login', 'EWD_FEUP') . "</a> " . __('to continue.', 'EWD_FEUP');}
				return $ReturnString;
		}
		
		if ($feup_success and $redirect_page != '#') {FEUPRedirect($redirect_page);}
		
		$ReturnString .= "<div id='ewd-feup-edit-account-form-div'>";
		if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
		$ReturnString .= "<form action='#' method='post' id='ewd-feup-edit-account-form'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='edit-account'>";
		if($Username_Is_Email == "Yes") {
			$ReturnString .= "<div id='ewd-feup-register-username-div' class='ewd-feup-field-label'>" . __('Email', 'EWD_FEUP') . ": </div>";
			$ReturnString .= "<input type='email' class='ewd-feup-text-input' name='Username' value='" . $User->Username . "'>";
		} else {
			$ReturnString .= "<div id='ewd-feup-register-username-div' class='ewd-feup-field-label'>" . __('Username', 'EWD_FEUP') . ": </div>";
			$ReturnString .= "<input type='text' class='ewd-feup-text-input' name='Username' value='" . $User->Username . "'>";
		}
		$ReturnString .= "<div id='ewd-feup-register-password-div' class='ewd-feup-field-label'>" . __('Password', 'EWD_FEUP') . ": </div>";
		$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='User_Password' value=''>";
		$ReturnString .= "<div id='ewd-feup-register-password-confirm-div' class='ewd-feup-field-label'>" . __('Repeat Password', 'EWD_FEUP') . ": </div>";
		$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='Confirm_User_Password' value=''>";
		$ReturnString .= "<div class='feup-pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Edit_Profile_Submit' value='" . $submit_text . "'></div>";
		$ReturnString .= "</form>";
		$ReturnString .= "</div>";

		return $ReturnString;
}
add_shortcode("account-details", "Insert_Edit_Account_Form");
?>
