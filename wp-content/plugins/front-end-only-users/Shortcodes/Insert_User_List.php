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
		
	$display_fields = explode(",", $display_field);
	
	$ReturnString .= "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= EWD_FEUP_Add_Modified_Styles();

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
		foreach ($display_fields as $display_field) {
			if ($display_field == "Username") {
				$User = $wpdb->get_row($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID='%d'", $User_ID->User_ID));
				$Return_User[$display_field] = $User->Username;
			}
			else {
				$User = $wpdb->get_row($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d' and Field_Name=%s", $User_ID->User_ID, $display_field));
				$Return_User[$display_field] = $User->Field_Value;
			}
		}
		$Return_User['User_ID'] = $User_ID->User_ID;
		$UserDataSet[] = $Return_User;
		unset($Return_User);
	}
		
	if (is_array($UserDataSet)) {
		foreach ($UserDataSet as $User_Data) {			
			$ReturnString .= "<div class='ewd-feup-user-list-result' id='ewd-feup-user-list'>";
			if ($user_profile_page != "") {$ReturnString .= "<a href='" . $user_profile_page . "?User_ID=" . $User_Data['User_ID'] . "'>";}
			foreach ($display_fields as $display_field) {
				$ReturnString .= $User_Data[$display_field] . " ";
			}
			if ($user_profile_page != "") {$ReturnString .= "</a>";}
			$ReturnString .= "</div>";
		}
	}
		
	return $ReturnString;
}
add_shortcode("user-list", "User_List");

