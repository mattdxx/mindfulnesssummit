<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Privilege_Level($atts, $content = null) {
	// Include the required global variables, and create a few new ones
	global $wpdb;
	global $ewd_feup_user_table_name, $ewd_feup_levels_table_name, $ewd_feup_user_fields_table_name;
	
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	$ReturnString="";
	
	$UserCookie = CheckLoginCookie();
	
	if ($UserCookie) {
		$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $UserCookie['Username']));
		$PrivilegeLevel = $wpdb->get_row($wpdb->prepare("SELECT Level_Privilege FROM $ewd_feup_levels_table_name WHERE Level_ID='%d'", $User->Level_ID));
		$User_Data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'", $User->User_ID));
	}

	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
								 	'login_page' => '',
									'no_message' => '',
									'minimum_level' => '',
									'maximum_level' => '',
									'level' => '',
									'field_name' => '',
									'field_value' => '',
									'sneak_peak_characters' => 0,
									'sneak_peak_words' => 0),
									$atts
							)
					);
											
	if (!$UserCookie) {
		if ($sneak_peak_characters > 0) {$ReturnString = substr(do_shortcode($content), 0, $sneak_peak_characters) . "...<br>";}
		if ($sneak_peak_words > 0) {$ReturnString = Return_Until_Nth_Occurance(do_shortcode($content), " ", $sneak_peak_words) . "...<br>";}
		
		$ReturnString .= __("Please log in to access this content.", 'EWD_FEUP'); 
		if ($login_page != "") {$ReturnString .= "<br />" . __('Please', 'EWD_FEUP') . " <a href='" . $login_page . "'>" . __('login', 'EWD_FEUP') . "</a> " . __('to continue.', 'EWD_FEUP');}
		if ($no_message != "Yes") {return $ReturnString;}
		else {return;}
	}
		
	$ReturnString = do_shortcode($content);
	
	if ($minimum_level != '' and $PrivilegeLevel->Level_Privilege < $minimum_level) {$ReturnString = "<div class='ewd-feup-error'>" . __("Sorry, your account isn't a high enough level to access this content.", 'EWD_FEUP') . "</div>";}
	if ($maximum_level != '' and $PrivilegeLevel->Level_Privilege > $maximum_level) {$ReturnString = "<div class='ewd-feup-error'>" . __("Sorry, your account level is too high to access this content.", 'EWD_FEUP') . "</div>";}
	if ($level != '' and $PrivilegeLevel->Level_Privilege != $level) {$ReturnString = "<div class='ewd-feup-error'>" . __("Sorry, your account isn't the correct level to acces this content.", 'EWD_FEUP') . "</div>";}
	if ($field_name != '') {
		foreach ($User_Data as $Field) {
			if ($Field->Field_Name == $field_name and $Field->Field_Value == $field_value) {$Validate = "Yes";}
		}
		if ($Validate != "Yes") {$ReturnString = "<div class='ewd-feup-error'>" . __("Sorry, this content is only for those whose " . $field_name . " is " . $field_value . ".", 'EWD_FEUP') . "</div>";}
	}
	
	if (substr($ReturnString, 0, 28) != "<div class='ewd-feup-error'>" or $no_message != "Yes") {return $ReturnString;}
}
add_shortcode("restricted", "Privilege_Level");


function Return_Until_Nth_Occurance($String, $Needle, $N) {
		$Count = 0;
		while ($Count < $N) {
				$Pos = strpos($String, $Needle);
				if (strpos($String, $Needle) === false) {$Pos = strlen($String); $Count = $N;}
				$ReturnString .= substr($String, 0, $Pos) . $Needle;
				$String = substr($String, $Pos+1);
				$Count++;
		}
		
		return $ReturnString;
}


?>