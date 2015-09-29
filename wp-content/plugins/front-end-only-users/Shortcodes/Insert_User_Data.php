<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_User_Data($atts) {
		// Include the required global variables, and create a few new ones
		global $wpdb;
		global $ewd_feup_user_table_name, $ewd_feup_levels_table_name, $ewd_feup_user_fields_table_name;
		
		$UserCookie = CheckLoginCookie();
		
		$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username='%s'", $UserCookie['Username']));
		
		$ReturnString = "";
		
		// Get the attributes passed by the shortcode, and store them in new variables for processing
		extract( shortcode_atts( array(
						 								 		'field_name' => 'Username',
																'plain_text' => 'Yes'),
																$atts
														)
												);
		
		if ($field_name == "Level") {$PrivilegeLevel = $wpdb->get_row($wpdb->prepare("SELECT Level_Name FROM $ewd_feup_levels_table_name WHERE Level_ID='%d'", $User->Level_ID));}
		elseif ($field_name == "Username") {}
		else {$User_Data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d'", $User->User_ID));}

		if ($field_name == "Level") {$ReturnString = $PrivilegeLevel->Level_Name;}
		elseif ($field_name == "Username") {$ReturnString = $User->Username;}
		else {
				foreach ($User_Data as $Field) {
						if ($Field->Field_Name == $field_name) {$ReturnString .= $Field->Field_Value;}
				}
		}
		
		if ($plain_text != "Yes") {$ReturnString = "<span class='ewd-feup-user-data'>" . $ReturnString . "</span>";}
		return $ReturnString;
}
add_shortcode("user-data", "Insert_User_Data");

