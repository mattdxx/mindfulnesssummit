<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_Register_Form($atts) {
	// Include the required global variables, and create a few new ones
	global $wpdb, $post, $user_message, $feup_success;
	global $ewd_feup_fields_table_name;
		
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
	$Use_Captcha = get_option("EWD_FEUP_Use_Captcha");
	$Time = time();
		
	$Sql = "SELECT * FROM $ewd_feup_fields_table_name ORDER BY Field_Order";
	$Fields = $wpdb->get_results($Sql);
		
	$ReturnString = "";

	if (isset($_GET['ConfirmEmail'])) {ConfirmUserEmail();}
		
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				'redirect_page' => '#',
				'redirect_field' => "",
				'redirect_array_string' => "",
				'submit_text' => __('Register', 'EWD_FEUP')),
			$atts
		)
	);
		
	if (isset($_GET['ConfirmEmail'])) {$ConfirmationSuccess = ConfirmUserEmail();}

	if ($feup_success and $redirect_field != "") {$redirect_page = Determine_Redirect_Page($redirect_field, $redirect_array_string, $redirect_page);}
		
	if ($feup_success and $redirect_page != '#') {FEUPRedirect($redirect_page);}
		
	$ReturnString .= "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= "</style>";
	
	if (!isset($ConfirmationSuccess)) {	
		$ReturnString .= "<div id='ewd-feup-register-form-div'>";
		if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
		$ReturnString .= "<form action='#' method='post' id='ewd-feup-register-form' class='feup-pure-form feup-pure-form-aligned' enctype='multipart/form-data'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='register'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-post-id' value='" . $post->ID . "'>";
		$ReturnString .= "<div class='feup-pure-control-group'>";
		if($Username_Is_Email == "Yes") {
			$ReturnString .= "<label for='Username' id='ewd-feup-register-username-div' class='ewd-feup-field-label'>" . __('Email', 'EWD_FEUP') . ": </label>";
			if (isset($_POST['Username'])) {
				$ReturnString .= "<input type='email' class='ewd-feup-text-input' name='Username' value='" . $_POST['Username'] . "'>";
			} else {
				$ReturnString .= "<input type='email' class='ewd-feup-text-input' name='Username' placeholder='" . __('Email', 'EWD_FEUP') . "...'>";
			}
		} else {
		$ReturnString .= "<label for='Username' id='ewd-feup-register-username-div' class='ewd-feup-field-label'>" . __('Username', 'EWD_FEUP') . ": </label>";
		if (isset($_POST['Username'])) {$ReturnString .= "<input type='text' class='ewd-feup-text-input' name='Username' value='" . $_POST['Username'] . "'>";}
		else {$ReturnString .= "<input type='text' class='ewd-feup-text-input' name='Username' placeholder='" . __('Username', 'EWD_FEUP') . "...'>";}
		}
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='feup-pure-control-group'>";
		$ReturnString .= "<label for='Password' id='ewd-feup-register-password-div' class='ewd-feup-field-label'>" . __('Password', 'EWD_FEUP') . ": </label>";
		if (isset($_POST['User_Password'])) {$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='User_Password' value='" . $_POST['User_Password'] . "'>";}
		else {$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='User_Password'>";}
		$ReturnString .= "</div>";
		$ReturnString .= "<div class='feup-pure-control-group'>";
		$ReturnString .= "<label for='Repeat Password' id='ewd-feup-register-password-confirm-div' class='ewd-feup-field-label'>" . __('Repeat Password', 'EWD_FEUP') . ": </label>";
		if (isset($_POST['Confirm_User_Password'])) {$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='Confirm_User_Password' value='" . $_POST['Confirm_User_Password'] . "'>";}
		else {$ReturnString .= "<input type='password' class='ewd-feup-text-input' name='Confirm_User_Password'>";}
		$ReturnString .= "</div>";
			
		foreach ($Fields as $Field) {
			if ($Field->Field_Required == "Yes") {$Req_Text = "required";}
			else {$Req_Text = "";}
			$ReturnString .= "<div class='feup-pure-control-group'>";
			$ReturnString .= "<label for='" . $Field->Field_Name . "' id='ewd-feup-register-" . $Field->Field_ID . "' class='ewd-feup-field-label'>" . __($Field->Field_Name, 'EWD_FEUP') . ": </label>";
			if ($Field->Field_Type == "text" or $Field->Field_Type == "mediumint") {
				if (isset($_POST[str_replace(" ", "_", $Field->Field_Name)])) {$ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-text-input pure-input-1-3' type='text' value='" . $_POST[str_replace(" ", "_", $Field->Field_Name)] . "' " . $Req_Text . "/>";}
				else {$ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-text-input' type='text' placeholder='" . $Field->Field_Name . "' " . $Req_Text . "/>";}
			}
			elseif ($Field->Field_Type == "date") {
				$ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-date-input' type='date' value='" . $Value . "' " . $Req_Text . "/>";
			}
			elseif ($Field->Field_Type == "datetime") {
				$ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-datetime-input' type='datetime-local' value='" . $Value . "' " . $Req_Text . "/>";
			}
			elseif ($Field->Field_Type == "file") {
				$ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-date-input' type='file' value='' " . $Req_Text . "/>";
			}
			elseif ($Field->Field_Type == "textarea") {
				$ReturnString .= "<textarea name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-textarea' " . $Req_Text . ">" . $_POST[str_replace(" ", "_", $Field->Field_Name)] . "</textarea>";
			} 
			elseif ($Field->Field_Type == "select") { 
				$Options = explode(",", $Field->Field_Options);
				$ReturnString .= "<select name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-select'>";
			 	foreach ($Options as $Option) {
					$ReturnString .= "<option value='" . $Option . "' ";
					if (isset($_POST[str_replace(" ", "_", $Field->Field_Name)]) and $Option == $_POST[str_replace(" ", "_", $Field->Field_Name)]) {$ReturnString .= "selected='selected'";}
					$ReturnString .= ">" . $Option . "</option>";
				}
				$ReturnString .= "</select>";
			} 
			elseif ($Field->Field_Type == "radio") {
				$Counter = 0;
				$Options = explode(",", $Field->Field_Options);
				foreach ($Options as $Option) {
					if ($Counter != 0) {$ReturnString .= "</div><div class='feup-pure-control-group ewd-feup-negative-top'><label class='feup-pure-radio'></label>";}
					$ReturnString .= "<input type='radio' name='" . $Field->Field_Name . "' value='" . $Option . "' class='ewd-feup-radio' " . $Req_Text . " ";
					if (isset($_POST[str_replace(" ", "_", $Field->Field_Name)]) and $Option == $_POST[str_replace(" ", "_", $Field->Field_Name)]) {$ReturnString .= "checked='checked'";}
					$ReturnString .= ">" . $Option  . "<br/>";
					$Counter++;
				} 
			} 				
			elseif ($Field->Field_Type == "checkbox") {
  				$Counter = 0;
				$Options = explode(",", $Field->Field_Options);
				foreach ($Options as $Option) {
					if ($Counter != 0) {$ReturnString .= "</div><div class='feup-pure-control-group ewd-feup-negative-top'><label class='feup-pure-radio'></label>";}
					$ReturnString .= "<input type='checkbox' name='" . $Field->Field_Name . "[]' value='" . $Option . "' class='ewd-feup-checkbox' " . $Req_Text . " ";
					if (isset($_POST[str_replace(" ", "_", $Field->Field_Name)])) {if (in_array($Option, $_POST[str_replace(" ", "_", $Field->Field_Name)])) {$ReturnString .= "checked";}}
					$ReturnString .= ">" . $Option . "</br>";
					$Counter++;
				}
			}
			$ReturnString .= "</div>";
			unset($Req_Text);
		}
		
		if ($Use_Captcha == "Yes") {$ReturnString .= EWD_FEUP_Add_Captcha();}
		$ReturnString .= "<div class='feup-pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Register_Submit' value='" . $submit_text . "'></div>";
		$ReturnString .= "</form>";
		$ReturnString .= "</div>";
	}
	else {
		$ReturnString = "<div class='ewd-feup-email-confirmation'>";
		if ($ConfirmationSuccess == "Yes") {$ReturnString .= __("Thanks for confirming your e-mail address!", 'EWD_FEUP');}
		if ($ConfirmationSuccess == "No") {$ReturnString .= __("The confirmation number provided was incorrect. Please contact the site administrator for assistance.", 'EWD_FEUP');}
		$ReturnString .= "</div>";
	}
		
	return $ReturnString;
}
add_shortcode("register", "Insert_Register_Form");
