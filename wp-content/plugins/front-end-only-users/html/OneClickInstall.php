<?php
	function EWD_FEUP_One_Click_Install_Pages_List() {
		$Pages = get_pages();

		echo "<option value='Edit Profile (Newly Created)'>" . __("Edit Profile", 'EWD_FEUP') . " (" . __("Newly Created", 'EWD_FEUP') . ")</option>";
		echo "<option value='Register (Newly Created)'>" . __("Register", 'EWD_FEUP') . " (" . __("Newly Created", 'EWD_FEUP') . ")</option>";
		echo "<option value='Login (Newly Created)'>" . __("Login", 'EWD_FEUP') . " (" . __("Newly Created", 'EWD_FEUP') . ")</option>";
		echo "<option value='Logout (Newly Created)'>" . __("Logout", 'EWD_FEUP') . " (" . __("Newly Created", 'EWD_FEUP') . ")</option>";
		echo "<option value='Forgot Password (Newly Created)'>" . __("Forgot Password", 'EWD_FEUP') . " (" . __("Newly Created", 'EWD_FEUP') . ")</option>";
		echo "<option value='Confirm Forgot Password (Newly Created)'>" . __("Confirm Forgot Password", 'EWD_FEUP') . " (" . __("New", 'EWD_FEUP') . ")</option>";
		echo "<option value='Change Password (Newly Created)'>" . __("Change Password", 'EWD_FEUP') . " (" . __("Newly Created", 'EWD_FEUP') . ")</option>";
		echo "<option value='Login Logout Toggle (Newly Created)'>" . __("Login-Logout Toggle", 'EWD_FEUP') . " (" . __("Newly Created", 'EWD_FEUP') . ")</option>";
		echo "<option value='User Profile (Newly Created)'>" . __("User Profile", 'EWD_FEUP') . " (" . __("Newly Created", 'EWD_FEUP') . ")</option>";
		echo "<option value='User Search (Newly Created)'>" . __("User Search", 'EWD_FEUP') . " (" . __("Newly Created", 'EWD_FEUP') . ")</option>";
		echo "<option value='User List (Newly Created)'>" . __("User List", 'EWD_FEUP') . " (" . __("Newly Created", 'EWD_FEUP') . ")</option>";
		echo "<option value='User Data (Newly Created)'>" . __("User Data", 'EWD_FEUP') . " (" . __("Newly Created", 'EWD_FEUP') . ")</option>";

		foreach ( $Pages as $Page ) {
			echo '<option value="' . $Page->post_title . '">' . substr($Page->post_title, 0, 35) . '</option>';
		}
	}

	function EWD_FEUP_One_Click_Install_Fields_List() {
		global $wpdb;
		global $ewd_feup_fields_table_name;

		$Fields = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name ORDER BY Field_Order");

		foreach ($Fields as $Field) {
			echo '<option value="' . $Field->Field_Name . '">' . $Field->Field_Name . '</option>';
		}
	}

	function EWD_FEUP_Insert_Attribute_Input($Page, $Attribute) {
		if ($Page['Attribute_' . $Attribute . '_Type'] == "page" ) {
			echo "<select name='" . $Page['Input_Name'] . "_" . $Page['Attribute_' . $Attribute] . "'>";
				EWD_FEUP_One_Click_Install_Pages_List(); 
			echo "</select>";
		}
		elseif ($Page['Attribute_' . $Attribute . '_Type'] == "plugin field" ) {
			echo "<select name='" . $Page['Input_Name'] . "_" . $Page['Attribute_' . $Attribute] . "'>";
				EWD_FEUP_One_Click_Install_Fields_List(); 
			echo "</select>";
		}
		elseif ($Page['Attribute_' . $Attribute . '_Type'] == "boolean" ) {
			echo "<select name='" . $Page['Input_Name'] . "_" . $Page['Attribute_' . $Attribute] . "'>";
				echo "<option value='No'>No</option>";
				echo "<option value='Yes'>Yes</option>";
			echo "</select>";
		}
		elseif ($Page['Attribute_' . $Attribute . '_Type'] == "text" ) {
			echo "<input type='text' name='" . $Page['Input_Name'] . "_" . $Page['Attribute_' . $Attribute] . "'>";
		}
	}

	$Page_Options[0]['Name'] = __("Edit Profile", "EWD_FEUP");
	$Page_Options[0]['Input_Name'] = "Edit_Profile";
	$Page_Options[0]['Attribute_One'] = "redirect_page";
	$Page_Options[0]['Attribute_One_Label'] = "Redirect To:";
	$Page_Options[0]['Attribute_One_Type'] = "page";
	$Page_Options[0]['Attribute_Two'] = "login_page";
	$Page_Options[0]['Attribute_Two_Label'] = "Login Page:";
	$Page_Options[0]['Attribute_Two_Type'] = "page";
	$Page_Options[0]['Attribute_Three'] = "";
	$Page_Options[0]['Attribute_Three_Label'] = "";
	$Page_Options[0]['Attribute_Three_Type'] = "";

	$Page_Options[1]['Name'] = __("Register", "EWD_FEUP");
	$Page_Options[1]['Input_Name'] = "Register";
	$Page_Options[1]['Attribute_One'] = "redirect_page";
	$Page_Options[1]['Attribute_One_Label'] = "Redirect To:";
	$Page_Options[1]['Attribute_One_Type'] = "page";
	$Page_Options[1]['Attribute_Two'] = "";
	$Page_Options[1]['Attribute_Two_Label'] = "";
	$Page_Options[1]['Attribute_Two_Type'] = "";
	$Page_Options[1]['Attribute_Three'] = "";
	$Page_Options[1]['Attribute_Three_Label'] = "";
	$Page_Options[1]['Attribute_Three_Type'] = "";

	$Page_Options[2]['Name'] = __("Login", "EWD_FEUP");
	$Page_Options[2]['Input_Name'] = "Login";
	$Page_Options[2]['Attribute_One'] = "redirect_page";
	$Page_Options[2]['Attribute_One_Label'] = "Redirect To:";
	$Page_Options[2]['Attribute_One_Type'] = "page";
	$Page_Options[2]['Attribute_Two'] = "";
	$Page_Options[2]['Attribute_Two_Label'] = "";
	$Page_Options[2]['Attribute_Two_Type'] = "";
	$Page_Options[2]['Attribute_Three'] = "";
	$Page_Options[2]['Attribute_Three_Label'] = "";
	$Page_Options[2]['Attribute_Three_Type'] = "";

	$Page_Options[3]['Name'] = __("Logout", "EWD_FEUP");
	$Page_Options[3]['Input_Name'] = "Logout";
	$Page_Options[3]['Attribute_One'] = "redirect_page";
	$Page_Options[3]['Attribute_One_Label'] = "Redirect To:";
	$Page_Options[3]['Attribute_One_Type'] = "page";
	$Page_Options[3]['Attribute_Two'] = "no_message";
	$Page_Options[3]['Attribute_Two_Label'] = "Display Logout Message:";
	$Page_Options[3]['Attribute_Two_Type'] = "boolean";
	$Page_Options[3]['Attribute_Three'] = "";
	$Page_Options[3]['Attribute_Three_Label'] = "";
	$Page_Options[3]['Attribute_Three_Type'] = "";

	$Page_Options[4]['Name'] = __("Forgot Password", "EWD_FEUP");
	$Page_Options[4]['Input_Name'] = "Forgot_Password";
	$Page_Options[4]['Attribute_One'] = "redirect_page";
	$Page_Options[4]['Attribute_One_Label'] = "Redirect To:";
	$Page_Options[4]['Attribute_One_Type'] = "page";
	$Page_Options[4]['Attribute_Two'] = "loggedin_page";
	$Page_Options[4]['Attribute_Two_Label'] = "Logged in Redirect Page:";
	$Page_Options[4]['Attribute_Two_Type'] = "page";
	$Page_Options[4]['Attribute_Three'] = "reset_email_url";
	$Page_Options[4]['Attribute_Three_Label'] = "Reset Email Page URL:";
	$Page_Options[4]['Attribute_Three_Type'] = "page";

	$Page_Options[5]['Name'] = __("Confirm Forgotten Password", "EWD_FEUP");
	$Page_Options[5]['Input_Name'] = "Confirm_Forgot_Password";
	$Page_Options[5]['Attribute_One'] = "redirect_page";
	$Page_Options[5]['Attribute_One_Label'] = "Redirect To:";
	$Page_Options[5]['Attribute_One_Type'] = "page";
	$Page_Options[5]['Attribute_Two'] = "login_page";
	$Page_Options[5]['Attribute_Two_Label'] = "Login Page:";
	$Page_Options[5]['Attribute_Two_Type'] = "page";
	$Page_Options[5]['Attribute_Three'] = "";
	$Page_Options[5]['Attribute_Three_Label'] = "";
	$Page_Options[5]['Attribute_Three_Type'] = "";

	$Page_Options[6]['Name'] = __("Change Password", "EWD_FEUP");
	$Page_Options[6]['Input_Name'] = "Change_Password";
	$Page_Options[6]['Attribute_One'] = "redirect_page";
	$Page_Options[6]['Attribute_One_Label'] = "Redirect To:";
	$Page_Options[6]['Attribute_One_Type'] = "page";
	$Page_Options[6]['Attribute_Two'] = "login_page";
	$Page_Options[6]['Attribute_Two_Label'] = "Login Page:";
	$Page_Options[6]['Attribute_Two_Type'] = "page";
	$Page_Options[6]['Attribute_Three'] = "";
	$Page_Options[6]['Attribute_Three_Label'] = "";
	$Page_Options[6]['Attribute_Three_Type'] = "";

	$Page_Options[7]['Name'] = __("Login-Logout Toggle (Optional)", "EWD_FEUP");
	$Page_Options[7]['Input_Name'] = "Login_Logout_Toggle";
	$Page_Options[7]['Attribute_One'] = "login_redirect_page";
	$Page_Options[7]['Attribute_One_Label'] = "Login Redirect To:";
	$Page_Options[7]['Attribute_One_Type'] = "page";
	$Page_Options[7]['Attribute_Two'] = "logout_redirect_page";
	$Page_Options[7]['Attribute_Two_Label'] = "Logout Redirect To:";
	$Page_Options[7]['Attribute_Two_Type'] = "page";
	$Page_Options[7]['Attribute_Three'] = "";
	$Page_Options[7]['Attribute_Three_Label'] = "";
	$Page_Options[7]['Attribute_Three_Type'] = "";

	$Page_Options[8]['Name'] = __("User Profile (Optional)", "EWD_FEUP");
	$Page_Options[8]['Input_Name'] = "User_Profile";
	$Page_Options[8]['Attribute_One'] = "login_page";
	$Page_Options[8]['Attribute_One_Label'] = "Login Page:";
	$Page_Options[8]['Attribute_One_Type'] = "page";
	$Page_Options[8]['Attribute_Two'] = "omit_fields";
	$Page_Options[8]['Attribute_Two_Label'] = "Don't Display:";
	$Page_Options[8]['Attribute_Two_Type'] = "plugin field";
	$Page_Options[8]['Attribute_Three'] = "login_necessary";
	$Page_Options[8]['Attribute_Three_Label'] = "Login to See Profiles?";
	$Page_Options[8]['Attribute_Three_Type'] = "boolean";

	$Page_Options[9]['Name'] = __("User Search (Optional)", "EWD_FEUP");
	$Page_Options[9]['Input_Name'] = "User_Search";
	$Page_Options[9]['Attribute_One'] = "login_page";
	$Page_Options[9]['Attribute_One_Label'] = "Login Page:";
	$Page_Options[9]['Attribute_One_Type'] = "page";
	$Page_Options[9]['Attribute_Two'] = "search_fields";
	$Page_Options[9]['Attribute_Two_Label'] = "Search Fields:";
	$Page_Options[9]['Attribute_Two_Type'] = "plugin field";
	$Page_Options[9]['Attribute_Three'] = "user_profile_page";
	$Page_Options[9]['Attribute_Three_Label'] = "User Profile Page:";
	$Page_Options[9]['Attribute_Three_Type'] = "page";

	$Page_Options[10]['Name'] = __("User List (Optional)", "EWD_FEUP");
	$Page_Options[10]['Input_Name'] = "User_List";
	$Page_Options[10]['Attribute_One'] = "field_name";
	$Page_Options[10]['Attribute_One_Label'] = "Field to Match:";
	$Page_Options[10]['Attribute_One_Type'] = "plugin field";
	$Page_Options[10]['Attribute_Two'] = "field_value";
	$Page_Options[10]['Attribute_Two_Label'] = "Value to Match:";
	$Page_Options[10]['Attribute_Two_Type'] = "text";
	$Page_Options[10]['Attribute_Three'] = "user_profile_page";
	$Page_Options[10]['Attribute_Three_Label'] = "User Profile Page:";
	$Page_Options[10]['Attribute_Three_Type'] = "page";

	$Page_Options[11]['Name'] = __("User Data (Optional)", "EWD_FEUP");
	$Page_Options[11]['Input_Name'] = "User_Data";
	$Page_Options[11]['Attribute_One'] = "field_name";
	$Page_Options[11]['Attribute_One_Label'] = "Field to Display:";
	$Page_Options[11]['Attribute_One_Type'] = "plugin field";
	$Page_Options[11]['Attribute_Two'] = "plain_text";
	$Page_Options[11]['Attribute_Two_Label'] = "Text Only:";
	$Page_Options[11]['Attribute_Two_Type'] = "boolean";
	$Page_Options[11]['Attribute_Three'] = "";
	$Page_Options[11]['Attribute_Three_Label'] = "";
	$Page_Options[11]['Attribute_Three_Type'] = "";
?>

<h3>One Click Setup</h3>
<form action='admin.php?page=EWD-FEUP-options&DisplayPage=Dashboard&Action=EWD_FEUP_OneClickInstall' method='post'>
	
	<table>
		<thead>
			<tr>
				<th>
					Include?
				</th>
				<th>
					Page Name
				</th>
				<th>
					Attribute One
				</th>
				<th>
					Attribute Two
				</th>
				<th>
					Attribute Three
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($Page_Options as $Page) { ?>
				<tr>
					<input type='hidden' name="<?php echo $Page['Input_Name']; ?>_attribute_one" value="<?php echo $Page['Attribute_One']; ?>" />
					<input type='hidden' name="<?php echo $Page['Input_Name']; ?>_attribute_two" value="<?php echo $Page['Attribute_Two']; ?>" />
					<input type='hidden' name="<?php echo $Page['Input_Name']; ?>_attribute_three" value="<?php echo $Page['Attribute_Three']; ?>" />
					<td>
						<input type='checkbox' name='page[]' value="<?php echo $Page['Input_Name']; ?>" />
					</td>
					<td>
						<label for="<?php echo $Page['Input_Name']; ?>"><?php echo $Page['Name']; ?></label>
					</td>
					<td>
						<?php echo $Page['Attribute_One_Label']; ?><br />
						<?php EWD_FEUP_Insert_Attribute_Input($Page, "One"); ?>
					</td>
					<td>
						<?php echo $Page['Attribute_Two_Label']; ?><br />
						<?php EWD_FEUP_Insert_Attribute_Input($Page, "Two"); ?>
					</td>
					<td>
						<?php echo $Page['Attribute_Three_Label']; ?><br />
						<?php EWD_FEUP_Insert_Attribute_Input($Page, "Three"); ?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>

	<?php 
		if ($EWD_FEUP_Full_Version == "Yes") {echo "<input type='submit' name='One_Click_Install_Submit' value='Create Pages' />";}
		else {echo "<input type='submit' name='One_Click_Install_Submit' value='Upgrade to Use!' disabled />";}
	?>
</form>
