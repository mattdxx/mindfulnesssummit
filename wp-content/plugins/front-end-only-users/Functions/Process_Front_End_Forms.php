<?php

function Process_EWD_FEUP_Front_End_Forms() {
	global $user_message;
		
	if (isset($_POST['ewd-feup-action'])) {
		switch ($_POST['ewd-feup-action']) {
			case "register":
			case "edit-profile":
			case "edit-account":
				$user_message = Add_Edit_User();
				break;
			case "login":
				$user_message['Message'] = Confirm_Login();
				break;
			case "forgot-password":
				$user_message['Message'] = Forgot_Password();
				break;

			case "confirm-forgot-password":
				$user_message['Message'] = Confirm_Forgot_Password();
				break;
				}
		}
}

function Confirm_Login() {
	global $wpdb, $feup_success;
	global $ewd_feup_user_table_name;
	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
		
	$User = $wpdb->get_row($wpdb->prepare("SELECT User_Password, User_Email_Confirmed, User_Admin_Approved FROM $ewd_feup_user_table_name WHERE Username ='%s'", $_POST['Username']));
		
	$Passwords_Match = false;
	if (function_exists('hash_equals')) {
		if($Use_Crypt == "Yes") {
			$Passwords_Match = hash_equals($User->User_Password, crypt($_POST['User_Password'], $User->User_Password));
		} else {
			$Passwords_Match = hash_equals($User->User_Password, sha1(md5($_POST['User_Password'].$Salt)));
		}
	} else {
		if($Use_Crypt == "Yes") {
			if (strcmp($User->User_Password, crypt($_POST['User_Password'], $User->User_Password)) === 0) {
				$Passwords_Match = true;
			} else {
				$Passwords_Match = false;
			}
		} else {
			if (strcmp($User->User_Password, sha1(md5($_POST['User_Password'].$Salt)), $User->User_Password) === 0) {
				$Passwords_Match = true;
			} else {
				$Passwords_Match = false;
			}
		}
	}

	if($Passwords_Match) {
		if ($Admin_Approval != "Yes" or $User->User_Admin_Approved == "Yes") {
			if ($Email_Confirmation != "Yes" or $User->User_Email_Confirmed == "Yes") {
			  	CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
				$Date = date("Y-m-d H:i:s");   
				$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Last_Login='" . $Date . "', User_Total_Logins=User_Total_Logins+1 WHERE Username ='%s'", $_POST['Username']));
				$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Sessioncheck='%s' WHERE Username ='%s'", sha1(md5($_SERVER['REMOTE_ADDR'].$Salt).$_SERVER['HTTP_USER_AGENT']), $_POST['Username']));
				$feup_success = true;
				return __("Login successful", 'EWD_FEUP');
			}
			return __("Login failed - you need to confirm your e-mail before you can log in", 'EWD_FEUP');
		}
		return __("Login failed - an administrator needs to approve your registration before you can log in", 'EWD_FEUP');
	}
	return __("Login failed - incorrect username or password", 'EWD_FEUP');
}

function Forgot_Password() {
	global $wpdb, $feup_success;
	global $ewd_feup_user_table_name, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name;

	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Admin_Email = get_option("EWD_FEUP_Admin_Email");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
	$Use_Captcha = get_option("EWD_FEUP_Use_Captcha");
	$Email_Field = get_option("EWD_FEUP_Email_Field");
	$Email_Field = str_replace(" ", "_", $Email_Field);
		
	//$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username ='%s'", $_POST['Email']));
	if($Username_Is_Email == "Yes") {
		$User = $wpdb -> get_row( $wpdb -> prepare( "SELECT * FROM $ewd_feup_user_table_name WHERE Username = '%s'", $_POST['Email'] ) );
		$User_Email = $User->Username;
	} else {
		$User_Fields = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE Field_Value = '%s' AND Field_Name = '%s' ", $_POST['Email'], $Email_Field));
		$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE User_ID = '%d'", $User_Fields->User_ID ));
		$User_Email = $User_Fields->Field_Value;
	}

	if ($Use_Captcha == "Yes") {$Validate_Captcha = EWD_FEUP_Validate_Captcha();}
	else {$Validate_Captcha = "Yes";}
		
	if( !empty( $User ) and $Validate_Captcha == "Yes")
	{
		//update users password
		//$password = wp_generate_password();
		//$hashedPassword = sha1( md5( $password.$Salt ) );

		// generate pw reset code
		$letters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$resetcode = '';
		$length = 15;
		for ($i = 0; $i < $length; $i++) {
			$resetcode .= $letters[rand(0, strlen($letters) - 1)];
		}

		
		$wpdb -> update( $ewd_feup_user_table_name, array(
				'User_Password_Reset_Code' => $resetcode,
				'User_Password_Reset_Date' => date('Y-m-d H:i:s', time())
			),
			array(
				'User_ID' => $User->User_ID,
			),
			array(
				'%s'
			)
		);
		
		//send email to user with account credentials
		$subject = __("Password reset requested from ", 'EWD_FEUP').get_bloginfo('name');

		$headers = 'From: ' . $Admin_Email . "\r\n" .
    		'Reply-To: ' . $Admin_Email . "\r\n" .
    		'X-Mailer: PHP/' . phpversion();
		
		$message = __("Greetings from ", 'EWD_FEUP').get_bloginfo('name')."\n\n";
		$message .= __("Somebody requested a password reset for you. If this wasn't you, you can ignore this mail.", 'EWD_FEUP')."\n\n";
		$message .= __("If you want to reset the password, please visit ", 'EWD_FEUP').site_url()."/".$_POST['ewd-feup-reset-email-url']."?add=". urlencode($User_Email)."&rc=".$resetcode."\n";
		$message .= __("If the link above doesn't work, go to ", 'EWD_FEUP').site_url()."/".$_POST['ewd-feup-reset-email-url'].__(" and enter your email address and the following code:", 'EWD_FEUP')."\n";
		$message .= $resetcode;
		//$feup_success = true;
		//return($User_Email."\n". $subject."\n". $message."\n". $headers);
		
		wp_mail( $User_Email, $subject, $message, $headers);
		
		$feup_success = true;
		
		//return success message
		return __("For completing the password reset procedure, please follow the instructions in your email.", 'EWD_FEUP');
	}
	else
	{
		//return success message even though operation failed - we don't want 'them' to know which
		// email addresses are used
		return __("For completing the password reset procedure, please follow the instructions in your email.", 'EWD_FEUP');
	}
}

function Confirm_Forgot_Password() {
	global $wpdb, $feup_success;
	global $ewd_feup_user_table_name, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name;

	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Admin_Email = get_option("EWD_FEUP_Admin_Email");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
	$Email_Field = get_option("EWD_FEUP_Email_Field");
	$Email_Field = str_replace(" ", "_", $Email_Field);
	$Given_Reset_Code = $_POST['Resetcode'];
	$Given_Password = $_POST['User_Password'];

	if(!empty($Given_Reset_Code)) {
		if (strcmp($Given_Password, $_POST['Confirm_User_Password']) === 0) {
			if(!empty($Given_Password)) {

				if ($Username_Is_Email == "Yes") {
					$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE Username = '%s'", $_POST['Email']));
					$User_Email = $User->Username;
				} else {
					$User_Fields = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE Field_Value = '%s' AND Field_Name = '%s' ", $_POST['Email'], $Email_Field));
					$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE User_ID = '%d'", $User_Fields->User_ID));
					$User_Email = $User_Fields->Field_Value;
				}

				if (!empty($User) && !empty($User->User_Password_Reset_Code)) {
					$Current_Date = new DateTime();
					$Request_Date = new DateTime($User->User_Password_Reset_Date);
					$Time_Since_Reset_Requested = $Current_Date->diff($Request_Date);
					if($Time_Since_Reset_Requested->d < 7) {
						if (strcmp($Given_Reset_Code, $User->User_Password_Reset_Code) === 0) {
							// everything seems ok, let's change the pw
							// also remove the reset code so it can't be reused
							$New_Password_Hash = Generate_Password($Given_Password);
							$wpdb->update($ewd_feup_user_table_name, array(
									'User_Password_Reset_Code' => '',
									'User_Password' => $New_Password_Hash,
								),
								array(
									'User_ID' => $User->User_ID,
								),
								array(
									'%s'
								)
							);


							$feup_success = true;

							//return success message
							return __("Your password has been successfully changed. You can log in using your new password now.", 'EWD_FEUP');
						} else {
							return __("The password reset code you entered was wrong. You need to get a new one before using this function again.", 'EWD_FEUP');
						}
					} else {
						$wpdb->update($ewd_feup_user_table_name, array(
								'User_Password_Reset_Code' => '',
							),
							array(
								'User_ID' => $User->User_ID,
							),
							array(
								'%s'
							)
						);
						return __("This password reset code is too old and we have disabled it for your security. Please use the 'I forgot my password' function to acquire a new one.");
					}
				} else {
					return __("You need a password reset code to reset your password. Please use the 'I forgot my password' function first to acquire one.");
				}
			} else {
				return __("Please select a new password");
			}
		} else {
			return __("The passwords you entered did not match");
		}
	} else {
		return __("You need a password reset code to reset your password. Please use the 'I forgot my password' function first to acquire one.");
	}
}

function FEUPRedirect($redirect_page) {
	header("location:" . $redirect_page);
}

function ConfirmUserEmail() {
	global $wpdb, $ewd_feup_user_table_name;

	$User_ID = $_GET['User_ID'];
	$Email_Address = $_GET['ConfirmEmail'];
	$Confirmation_Code = $_GET['Confirmation_Code'];

	$Retrieved_User_ID = $wpdb->get_row($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_ID=%d AND User_Confirmation_Code=%s", $User_ID, $Confirmation_Code));
	if (isset($Retrieved_User_ID->User_ID)) {
		$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Email_Confirmed='Yes' WHERE User_ID=%d", $Retrieved_User_ID->User_ID));
		$ConfirmationSuccess = "Yes";
	}
	else {
		$ConfirmationSuccess = "No";
	}

	return $ConfirmationSuccess;
}

function Get_User_Search_Results($search_logic, $display_field) {
	global $wpdb, $ewd_feup_user_fields_table_name, $ewd_feup_user_table_name;
		
	foreach ($_POST as $field => $value) {
		if (substr($field, 0, 7) == "search_") {
			$DataSet['Criteria'] .= str_replace("_", " ", substr($field, 7));
			$DataSet['Value'] = "%" . $value . "%";
			$Criterion[] = $DataSet;
			unset($DataSet);
		}
	}

	if (!is_array($Criterion)) {return array();}
		
	$list = array();
	foreach ($Criterion as $DataSet) {
		unset($IDs);
		$IDs = array();
		if ($DataSet['Criteria'] == "Username") {$UserList = $wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username LIKE '%s'", $DataSet['Value']));}
		else {$UserList = $wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_fields_table_name WHERE Field_Name='%s' AND Field_Value LIKE '%s'", $DataSet['Criteria'], $DataSet['Value']));}
		foreach ($UserList as $User) {
			$IDs[] = $User->User_ID;
		}
		$list[] = $IDs;
	}
		
	if (sizeOf($list) < 2) {
		$UserIDs = $IDs;
	} else {
		if ($search_logic == "AND") {$UserIDs = call_user_func_array('array_intersect',$list);}
		else {
			foreach ($list as $Criteria_List) {
				foreach ($Criteria_List as $Matching_User) {
					$Combined_IDs[] = $Matching_User;
				}
			}
			$UserIDs = array_unique($Combined_IDs); 
		}
	}

	foreach ($UserIDs as $UserID) {
		if ($display_field == "Username") {$User = $wpdb->get_row($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID='%d'", $UserID));}
		else {$User = $wpdb->get_row($wpdb->prepare("SELECT field_value FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d' and field_name=%s", $UserID, $display_field));}
		$UserInformation[$display_field] = $User->field_value;
		$UserInformation['User_ID'] = $UserID;
		$Users[] = $UserInformation;
		unset($UserInformation);
	}
		
	return $Users;
}
?>
