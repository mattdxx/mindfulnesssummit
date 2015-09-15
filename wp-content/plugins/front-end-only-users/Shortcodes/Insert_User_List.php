<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function User_List($atts, $content = null) {
	// Include the required global variables, and create a few new ones
	global $wpdb;
	global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;
		
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	
	$UserCookie = CheckLoginCookie();
	
	// Get the attributes passed by the shortcode, and store them in new variables for processing
	extract( shortcode_atts( array(
					'login_page' => '',
					'field_name' => '',
					'field_value' => '',
					'login_necessary' => 'Yes',
					'display_field' => 'Username',
					'user_profile_page' => ''),
					$atts
			)
	);
		
	if (!$UserCookie and $login_necessary == "Yes") {
		$ReturnString .= __("Please log in to access this content.", 'EWD_FEUP'); 
		if ($login_page != "") {$ReturnString .= "<br />" . __('Please', 'EWD_FEUP') . " <a href='" . $login_page . "'>" . __('login', 'EWD_FEUP') . "</a> " . __('to continue.', 'EWD_FEUP');}
		return $ReturnString;
	}
		
	if ($field_name != ""  and $field_value != "") {
		$User_IDs = $wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_fields_table_name WHERE Field_Name='%s' AND Field_Value='%s'", $field_name, $field_value));
	}
	else {
		$User_IDs = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name");
	}

	foreach ($User_IDs as $User_ID) {
		$User = $wpdb->get_row($wpdb->prepare("SELECT " . $display_field . " FROM $ewd_feup_user_table_name WHERE User_ID='%d'", $User_ID->User_ID));
		$Return_User['User_Data'] = $User->$display_field;
		$Return_User['User_ID'] = $User_ID->User_ID;
		$UserDataSet[] = $Return_User;
		unset($Return_User);
	}
		
	if (is_array($UserDataSet)) {
		foreach ($UserDataSet as $User_Data) {			
			$ReturnString .= "<div class='ewd-feup-user-list-result'>";
			if ($user_profile_page != "") {$ReturnString .= "<a href='" . $user_profile_page . "?User_ID=" . $User_Data['User_ID'] . "'>";}
			$ReturnString .= $User_Data['User_Data'];
			if ($user_profile_page != "") {$ReturnString .= "</a>";}
			$ReturnString .= "</div>";
		}
	}
		
	return $ReturnString;
}
add_shortcode("user-list", "User_List");

