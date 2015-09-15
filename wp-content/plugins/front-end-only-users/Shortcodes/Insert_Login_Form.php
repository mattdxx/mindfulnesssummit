<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_Login_Form($atts) {
		global $user_message, $feup_success;
		// Include the required global variables, and create a few new ones
		$Salt = get_option("EWD_FEUP_Hash_Salt");
		$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
		$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
		$Time = time();
		
		$ReturnString = "";
		
		// Get the attributes passed by the shortcode, and store them in new variables for processing
		extract( shortcode_atts( array(
						 								 		'redirect_page' => '#',
																'redirect_field' => '',
																'redirect_array_string' => '',
																'submit_text' => __('Login', 'EWD_FEUP')),
																$atts
														)
												);
		
		$ReturnString .= "<style type='text/css'>";
		$ReturnString .= $Custom_CSS;
		$ReturnString .= "</style>";
		
		if ($feup_success and $redirect_field != "") {$redirect_page = Determine_Redirect_Page($redirect_field, $redirect_array_string, $redirect_page);}

		if ($feup_success and $redirect_page != '#') {FEUPRedirect($redirect_page);}
		
		$ReturnString .= "<div id='ewd-feup-login-form-div'>";
		if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
		$ReturnString .= "<form action='#' method='post' id='ewd-feup-login-form' class='pure-form pure-form-aligned'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='login'>";
		$ReturnString .= "<div class='feup-pure-control-group'>";
		if($Username_Is_Email == "Yes") {
			$ReturnString .= "<label for='Username' id='ewd-feup-login-username-div' class='ewd-feup-field-label'>" . __('Email', 'EWD_FEUP') . ": </label>";
			$ReturnString .= "<input type='email' class='ewd-feup-text-input' name='Username' placeholder='" . __('Email', 'EWD_FEUP') . "...'>";
		} else {
		$ReturnString .= "<label for='Username' id='ewd-feup-login-username-div' class='ewd-feup-field-label'>" . __('Username', 'EWD_FEUP') . ": </label>";
		$ReturnString .= "<input type='text' class='ewd-feup-text-input' name='Username' placeholder='" . __('Username', 'EWD_FEUP') . "...'>";
		}
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='feup-pure-control-group'>";
		$ReturnString .= "<label for='Password' id='ewd-feup-login-password-div' class='ewd-feup-field-label'>" . __('Password', 'EWD_FEUP') . ": </label>";
		$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='User_Password'>";
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='feup-pure-control-group'>";
		$ReturnString .= "<label for='Submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Login_Submit' value='" . $submit_text . "'>";
		$ReturnString .= "</div>";
		$ReturnString .= "</form>";
		$ReturnString .= "</div>";
		
		return $ReturnString;
}
add_shortcode("login", "Insert_Login_Form");

?>
