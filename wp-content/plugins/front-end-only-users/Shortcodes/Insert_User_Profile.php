<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_User_Profile($atts) {
	// Include the required global variables, and create a few new ones
	global $wpdb, $user_message;
	global $ewd_feup_fields_table_name, $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;
		
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Time = time();
	
	$CheckCookie = CheckLoginCookie();
	
	$Sql = "SELECT * FROM $ewd_feup_fields_table_name WHERE Field_Show_In_Front_End='Yes' ORDER BY Field_Order";
	$Fields = $wpdb->get_results($Sql);
	if (isset($_GET['User_ID'])) {$UserData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'", $_GET['User_ID']));}
	//elseif (isset(get_query_var('user_id')))) {$UserData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'", get_query_var('user_id')));}
	
	$ReturnString = "";
	if (!isset($UserData)) {return __("Please select a valid user profile", 'EWD_FEUP');}

	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
				'login_page' => '',
				'omit_fields' => '',
				'login_necessary' => 'Yes',
				'submit_text' => __('Edit Profile', 'EWD_FEUP')),
			$atts
		)
	);
											
	$ReturnString .= "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= "</style>";
											
	if ($CheckCookie['Username'] == "" and $login_necessary == "Yes") {
		$ReturnString .= __('You must be logged in to access this page.', 'EWD_FEUP');
		if ($login_page != "") {$ReturnString .= "<br />" . __('Please', 'EWD_FEUP') . " <a href='" . $login_page . "'>" . __('login', 'EWD_FEUP') . "</a> " . __('to continue.', 'EWD_FEUP');}
		return $ReturnString;
	}

	
	$ReturnString .= "<div id='ewd-feup-user-profile-div'>";
	
	$Omitted_Fields = explode(",", $omit_fields);
	
	foreach ($Fields as $Field) {
		if (!in_array($Field->Field_Name, $Omitted_Fields)) {
			$Value = "";
			foreach ($UserData as $UserField) {
				if ($Field->Field_Name == $UserField->Field_Name) {$Value = $UserField->Field_Value;}
			}
			$ReturnString .= "<div class='feup-user-profile-field'>";
			$ReturnString .= "<div id='ewd-feup-user-profile-lavel-" . $Field->Field_ID . "' class='ewd-feup-user-profile-label'>" . $Field->Field_Name . ": </div>";
			$ReturnString .= "<div class='ewd-feup-text-input'>" . $Value . "</div>";
			$ReturnString .= "</div>";
			unset($Req_Text);
		}
	}
	
	$ReturnString .= "<div class='feup-pure-control-group'><label for='submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Edit_Profile_Submit' value='" . $submit_text . "'></div>";
	$ReturnString .= "</form>";
	$ReturnString .= "</div>";
	
	return $ReturnString;
}
if ($EWD_FEUP_Full_Version == "Yes") {add_shortcode("user-profile", "Insert_User_Profile");}