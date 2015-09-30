<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function User_Search($atts, $content = null) {
		// Include the required global variables, and create a few new ones
		global $wpdb;
		global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name;
		
		$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
		
		$UserCookie = CheckLoginCookie();
		
		// Get the attributes passed by the shortcode, and store them in new variables for processing
		extract( shortcode_atts( array(
					 	'login_page' => '',
					 	'login_necessary' => 'Yes',
						'submit_text' => 'Search Users',
						'search_fields' => '',
						'user_profile_page' => ''),
						$atts
				)
		);
		
	$ReturnString .= "<style type='text/css'>";
	$ReturnString .= $Custom_CSS;
	$ReturnString .= EWD_FEUP_Add_Modified_Styles();
		
		if (!$UserCookie and $login_necessary == "Yes") {
			  $ReturnString .= __("Please log in to access this content.", 'EWD_FEUP'); 
				if ($login_page != "") {$ReturnString .= "<br />" . __('Please', 'EWD_FEUP') . " <a href='" . $login_page . "'>" . __('login', 'EWD_FEUP') . "</a> " . __('to continue.', 'EWD_FEUP');}
				return $ReturnString;
		}
		
		if ($search_fields == "") {
			  $ReturnString .= __("search_fields was left blank. Please make sure to include that attribute inside your shortcode.", 'EWD_FEUP'); 
				return $ReturnString;
		}
		
		if ($_POST['ewd-feup-action'] == "user-search") {
			  $Users = Get_User_Search_Results();
				
				$ReturnString .= "<div class='ewd-feup-user-list-result'>";
				foreach ($Users as $User) {
						$ReturnString .= "<div class='ewd-feup-user'>";
						foreach ($User as $FieldName => $ReturnField) {
								$ReturnString .= "<div class='ewd-feup-user-field'>";
								$ReturnString .= $FieldName . ": " . $ReturnField;
								$ReturnString .= "</div>";
						}
						$ReturnString .= "</div>";
				}
				$ReturnString .= "</div>";
		}
		
		$search_fields_array = explode(",", $search_fields);
		$ReturnString .= "<div id='ewd-feup-login-form-div'>";
		if (isset($user_message['Message'])) {$ReturnString .= $user_message['Message'];}
		$ReturnString .= "<form action='#' method='post' id='ewd-feup-login-form' class='feup-pure-form feup-pure-form-aligned'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-check' value='" . sha1(md5($Time.$Salt)) . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-time' value='" . $Time . "'>";
		$ReturnString .= "<input type='hidden' name='ewd-feup-action' value='user-search'>";
		foreach ($search_fields_array as $field) {	
				$field_clean = trim(str_replace(" ", "_", $field));
				$field_clean = str_replace("'", "&#39", $field_clean);
				$ReturnString .= "<div class='feup-pure-control-group'>";
				$ReturnString .= "<label for='" . $field . "' id='ewd-feup-" . $field_clean . "-div' class='ewd-feup-field-label'>" . $field . ": </label>";
				$ReturnString .= "<input type='text' class='ewd-feup-text-input' name='search_" . $field_clean . "' placeholder='" .$field . "...'>";
				$ReturnString .= "</div>";
		}
		$ReturnString .= "<div class='feup-pure-control-group'>";
		$ReturnString .= "<label for='Submit'></label><input type='submit' class='ewd-feup-submit feup-pure-button feup-pure-button-primary' name='Search_Submit' value='" . $submit_text . "'>";
		$ReturnString .= "</div>";
		$ReturnString .= "</form>";
		$ReturnString .= "</div>";
		
		return $ReturnString;
}
add_shortcode("user-search", "User_Search");

