<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_Edit_Profile($atts) {
	// Include the required global variables, and create a few new ones
	global $wpdb, $user_message, $feup_success;
	global $ewd_feup_fields_table_name, $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;
		
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Time = time();
	
	$CheckCookie = CheckLoginCookie();
	
	$Sql = "SELECT * FROM $ewd_feup_fields_table_name WHERE Field_Show_In_Front_End='Yes' ORDER BY Field_Order";
	$Fields = $wpdb->get_results($Sql);
	$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $CheckCookie['Username']));
	$UserData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'", $User->User_ID));
	
	$ReturnString = "";
	
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				'redirect_page' => '#',
				'login_page' => '',
				'omit_fields' => '',
				'submit_text' => __('Edit Profile', 'EWD_FEUP')),
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
	
	$ReturnString .= "<div id='ewd-feup-edit-profile-form-div'>";
	if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
	$ReturnString .= "<form action='#' method='post' id='ewd-feup-edit-profile-form' class='pure-form pure-form-aligned' enctype='multipart/form-data'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
	$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='edit-profile'>";
	$ReturnString .= "<input type='hidden' name='Omit_Fields' value='" . $omit_fields . "'>";
	
	$Omitted_Fields = explode(",", $omit_fields);
	
	foreach ($Fields as $Field) {
		if (!in_array($Field->Field_Name, $Omitted_Fields)) {
			if ($Field->Field_Required == "Yes") {$Req_Text = "required";} 
			else {$Req_Text="";};
			$Value = "";
			foreach ($UserData as $UserField) {
				if ($Field->Field_Name == $UserField->Field_Name) {$Value = $UserField->Field_Value;}
			}
			$ReturnString .= "<div class='feup-pure-control-group'>";
			$ReturnString .= "<label for='" . $Field->Field_Name . "' id='ewd-feup-edit-" . $Field->Field_ID . "' class='ewd-feup-field-label'>" . $Field->Field_Name . ": </label>";
			if ($Field->Field_Type == "text" or $Field->Field_Type == "mediumint") {
			    $ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-text-input' type='text' value='" . $Value . "' " . $Req_Text . "/>";
			}
			elseif ($Field->Field_Type == "date") {
				$ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-date-input' type='date' value='" . $Value . "' " . $Req_Text . "/>";
			}
			elseif ($Field->Field_Type == "datetime") {
				$ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-datetime-input' type='datetime-local' value='" . $Value . "' " . $Req_Text . "/>";
			}
			elseif ($Field->Field_Type == "textarea") {
				$ReturnString .= "<textarea name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-textarea' " . $Req_Text . ">" . $Value . "</textarea>";
			}
			elseif ($Field->Field_Type == "file") {
				$ReturnString .= __("Current file:", 'EWD_FEUP') . " " . substr($Value, 10) . " | ";
				$ReturnString .= "<input name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-date-input' type='file' value='' " . $Req_Text . "/>";
			} 
			elseif ($Field->Field_Type == "select") { 
				$Options = explode(",", $Field->Field_Options);
				$ReturnString .= "<select name='" . $Field->Field_Name . "' id='ewd-feup-register-input-" . $Field->Field_ID . "' class='ewd-feup-select'>";
		 		foreach ($Options as $Option) {
					$ReturnString .= "<option value='" . $Option . "' ";
					if (trim($Option) == trim($Value)) {$ReturnString .= "selected='selected'";}
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
					if (trim($Option) == trim($Value)) {$ReturnString .= "checked";}
					$ReturnString .= ">" . $Option;
					$Counter++;
				} 
			} 
			elseif ($Field->Field_Type == "checkbox") {
 				$Counter = 0;
				$Options = explode(",", $Field->Field_Options);
				$Values = explode(",", $Value);
				foreach ($Options as $Option) {
					if ($Counter != 0) {$ReturnString .= "</div><div class='feup-pure-control-group ewd-feup-negative-top'><label class='feup-pure-radio'></label>";}
					$ReturnString .= "<input type='checkbox' name='" . $Field->Field_Name . "[]' value='" . $Option . "' class='ewd-feup-checkbox' " . $Req_Text . " ";
					if (in_array($Option, $Values)) {$ReturnString .= "checked";}
					$ReturnString .= ">" . $Option . "</br>";
					$Counter++;
				}
			}
			$ReturnString .= "</div>";
			unset($Req_Text);
		}
	}
	
	$ReturnString .= "<div class='feup-pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Edit_Profile_Submit' value='" . $submit_text . "'></div>";
	$ReturnString .= "</form>";
	$ReturnString .= "</div>";
	
	return $ReturnString;
}
add_shortcode("edit-profile", "Insert_Edit_Profile");