<?php
function Determine_Redirect_Page($redirect_field, $redirect_array_string, $original_redirect) {
		global $wpdb, $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;

		$CheckCookie = CheckLoginCookie();

		$User = $wpdb->get_row($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'", $CheckCookie['Username']));
		$Field = $wpdb->get_row("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_Name='" . $redirect_field . "' AND User_ID='" . $User->User_ID . "'");
		
		$redirect_array = explode(",", $redirect_array_string);
		foreach ($redirect_array as $redirect) {
				$redirect_key = trim(substr($redirect, 0, strpos($redirect, "=>")));
				$redirect_value = trim(substr($redirect, strpos($redirect, "=>")+2));
				$redirects[$redirect_key] = $redirect_value;
		}
		
		$Return_Redirect = $redirects[trim($Field->Field_Value)];
		if ($Return_Redirect == "") {$Return_Redirect = $original_redirect;}
		
		return $Return_Redirect;
}
?>