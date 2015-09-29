<?php

function Generate_Password($plainPassword = null) {
	if(!$plainPassword) {
		return false;
	}
	$intermediateSalt = bin2hex(openssl_random_pseudo_bytes(30));
	$intermediateSalt = substr($intermediateSalt,0,22);
	$finalSalt = '$2y$13$'.$intermediateSalt.'$';
	$hashedPassword = crypt($plainPassword,$finalSalt);
	return $hashedPassword;
}

/* Prepare the data to add or edit a single product */
function Add_Edit_User() {
	global $wpdb, $feup_success, $ewd_feup_fields_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_user_table_name;
	$Salt = get_option("EWD_FEUP_Hash_Salt");
	$Sign_Up_Email = get_option("EWD_FEUP_Sign_Up_Email");
	$Default_User_Level = get_option("EWD_Default_User_Level");
	$Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
	$Use_Captcha = get_option("EWD_FEUP_Use_Captcha");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Email_On_Admin_Approval = get_option("EWD_FEUP_Email_On_Admin_Approval");
	$Admin_Email_On_Registration = get_option("EWD_FEUP_Admin_Email_On_Registration");
	
	$Sql = "SELECT * FROM $ewd_feup_fields_table_name ";
	$Fields = $wpdb->get_results($Sql);
	
	$date = date("Y-m-d H:i:s");

	$UserCookie = CheckLoginCookie();

	if (!isset($_POST['Admin_Approved'])) {$_POST['Admin_Approved'] = null;}
	if (!isset($_POST['action'])) {$_POST['action'] = null;}
	if (!isset($_POST['ewd-feup-action'])) {$_POST['ewd-feup-action'] = null;}

	if ($_POST['ewd-feup-action'] == "register" and $Use_Captcha == "Yes") {$Validate_Captcha = EWD_FEUP_Validate_Captcha();}
	else {$Validate_Captcha = "Yes";}

	$User = $wpdb->get_row($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'", $UserCookie['Username']));
	if (is_object($User)) {
		$User_ID = $User->User_ID;
	}

	if (is_admin()) {$User_ID = $_POST['User_ID'];}

	if (isset($User_ID)) {
		$User = $wpdb->get_row($wpdb->prepare("SELECT User_Admin_Approved FROM $ewd_feup_user_table_name WHERE User_ID='%d'", $User_ID));
		$User_Current_Admin_Approved = $User->User_Admin_Approved;
	}
	else {
		$User_Current_Admin_Approved = "No";
	}
	
	if (isset($_POST['Omit_Fields'])) {$Omitted_Fields = explode(",", $_POST['Omit_Fields']);}
	else {$Omitted_Fields = array();}
		
	if (isset($_POST['Username'])) {$User_Fields['Username'] = $_POST['Username'];}
	// check if the password is empty - so we won't try to update it if it is empty
	if (empty($_POST['User_Password'])) { unset($_POST['User_Password']); }

	if($Use_Crypt == "Yes") {
		if (isset($_POST['User_Password'])) {$User_Fields['User_Password'] = Generate_Password($_POST['User_Password']);}
	} else {
	if (isset($_POST['User_Password'])) {$User_Fields['User_Password'] = sha1(md5($_POST['User_Password'].$Salt));}
	}
	if (isset($_POST['Level_ID'])) {$User_Fields['Level_ID'] = $_POST['Level_ID'];}
	elseif ($_POST['ewd-feup-omit-level'] != "Yes") {$User_Fields['Level_ID'] = $Default_User_Level;}
	if ($_POST['Admin_Approved'] == "Yes") {$User_Fields['User_Admin_Approved'] = "Yes";}
	if ($_POST['Admin_Approved'] == "No") {$User_Fields['User_Admin_Approved'] = "No";}
	if ($_POST['User_Password'] != $_POST['Confirm_User_Password']) {$user_update = array("Message_Type" => "Error", "Message" => __("The passwords you entered did not match.", "EWD_FEUP")); return $user_update;}
	if ($_POST['action'] == "Add_User" or $_POST['ewd-feup-action'] == "register") {
		if (empty($_POST['User_Password'])) { $user_update = array("Message_Type" => "Error", "Message" => __("The password cannot be empty.", "EWD_FEUP")); return $user_update;}
		$wpdb->get_results($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username='%s'", $_POST['Username']));
		if ($wpdb->num_rows > 0) {$user_update = array("Message_Type" => "Error", "Message" => __("There is already a user with that Username, please select a different one.", "EWD_FEUP")); return $user_update;}
		if (strlen($_POST['Username']) < 3) {$user_update = array("Message_Type" => "Error", "Message" => __("Username must be at least 3 characters.", "EWD_FEUP")); return $user_update;}
	}

	if ($_POST['ewd-feup-action'] != "edit-account") {
		if (!isset($Additional_Fields_Array)) {$Additional_Fields_Array=array();}
		foreach ($Fields as $Field) {
			if (!in_array($Field->Field_Name, $Omitted_Fields)) {
				if ($Field->Field_Options != "") {$Field_Allowed_Values = explode(",", $Field->Field_Options);}
				if (!is_array($Field_Allowed_Values) or in_array($_POST[$Field_Name], $Field_Allowed_Values)) {
					$Additional_Fields_Array[$Field->Field_Name]['Field_ID'] = $Field->Field_ID;
					$Additional_Fields_Array[$Field->Field_Name]['Field_Name'] = $Field->Field_Name;
					$Field_Name = str_replace(" ", "_", $Field->Field_Name);
					if ($Field->Field_Type == "file") {
					  	$File_Upload_Return = Handle_File_Upload($Field_Name);
						if ($File_Upload_Return['Success'] == "No") {return $File_Upload_Return['Data'];}
						elseif ($File_Upload_Return['Success'] == "N/A") {unset($Additional_Fields_Array[$Field->Field_Name]);}
						else {$Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = $File_Upload_Return['Data'];}
					}
					elseif (is_array($_POST[$Field_Name])) {$Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = stripslashes_deep(implode(",", $_POST[$Field_Name]));}
					else {$Additional_Fields_Array[$Field->Field_Name]['Field_Value'] = stripslashes_deep($_POST[$Field_Name]);}
				}
			}
		}
	}

	if (!isset($error) and $Validate_Captcha == "Yes") {
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the user */
		if ($_POST['action'] == "Add_User" or $_POST['ewd-feup-action'] == "register") {
			if (is_object($User)) {$user_update = __("There is already an account with that Username. Please select a different one.", "EWD_FEUP"); return $user_update;}
			if (!isset($User_Fields['User_Admin_Approved'])) {$User_Fields['User_Admin_Approved'] = "No";}
			if (!isset($User_Fields['User_Email_Confirmed'])) {$User_Fields['User_Email_Confirmed'] = "No";}
			$User_Fields['User_Date_Created'] = $date;
			$User_Fields['User_Last_Login'] = $date;
			$user_update = Add_EWD_FEUP_User($User_Fields);
			$User_ID = $wpdb->insert_id;
			if (!isset($Additional_Fields_Array)) {
				$Additional_Fields_Array=array();
			}
			foreach ($Additional_Fields_Array as $Field) {
				$user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'], $Field['Field_Value'], $date);
			}
			if ($_POST['ewd-feup-action'] == "register") {
				$user_update = __("Your account has been succesfully created.", "EWD_FEUP");
				if ($Sign_Up_Email == "Yes") {EWD_FEUP_Send_Email($User_Fields, $Additional_Fields_Array, $User_ID);}
				if ($Admin_Email_On_Registration == "Yes") {EWD_FEUP_Send_Admin_Registration_Email($User_Fields, $Additional_Fields_Array, $User_ID);}
				if ($Email_Confirmation != "Yes" and $Admin_Approval != "Yes") {
					Confirm_Login();
					//CreateLoginCookie($_POST['Username'], $_POST['User_Password']);
					$feup_success = true;
				}
			}
		}
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the user */
		else {
			if (isset($User_Fields)) {$user_update = Edit_EWD_FEUP_User($User_ID, $User_Fields);}
			if (!isset($Additional_Fields_Array)) {$Additional_Fields_Array=array();}
			if (is_array($Additional_Fields_Array)) {
				foreach ($Additional_Fields_Array as $Field) {
					$CurrentField = $wpdb->get_row($wpdb->prepare("SELECT User_Field_ID FROM $ewd_feup_user_fields_table_name WHERE Field_ID='%d' AND User_ID='%d'", $Field['Field_ID'], $User_ID));
					if ($CurrentField->User_Field_ID != "") {$user_update = Edit_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'], $Field['Field_Value']);}
					else {$user_update = Add_EWD_FEUP_User_Field($Field['Field_ID'], $User_ID, $Field['Field_Name'], $Field['Field_Value'], $date);}
				}
			}
			if ($_POST['ewd-feup-action'] == "edit-account") {CreateLoginCookie($_POST['Username'], $_POST['User_Password']);}
		}

		// If the user receives admin approval for the first time and the option is selected, send them an e-mail
		//Need to check earlier, as it already gets set before this
		if (($User_Current_Admin_Approved == "No" and $User_Fields['User_Admin_Approved'] == "Yes") and $Email_On_Admin_Approval == "Yes") {
			EWD_FEUP_Send_Admin_Approval_Email($User_Fields, $Additional_Fields_Array, $User_ID);
		}

		$user_update = array("Message_Type" => "Update", "Message" => $user_update);
		$feup_success = true;
		return $user_update;
	}
	/* Return any error that might have occurred */
	else {
			if ($Validate_Captcha != "Yes") {$error = "The Captcha text did not match the image";}
			$output_error = array("Message_Type" => "Error", "Message" => $error);
			return $output_error;
	}
}

function EWD_FEUP_Send_Email($User_Fields, $Additional_Fields_Array, $User_ID = 0) {
	global $wpdb, $ewd_feup_user_table_name;
	$Admin_Email = get_option("EWD_FEUP_Admin_Email");
	$Email_Subject = get_option("EWD_FEUP_Email_Subject");
	$Encrypted_Admin_Password = get_option("EWD_FEUP_Admin_Password");
	$Port = get_option("EWD_FEUP_Port");
	$Use_SMTP = get_option("EWD_FEUP_Use_SMTP");
	$SMTP_Mail_Server = get_option("EWD_FEUP_SMTP_Mail_Server");
	$SMTP_Username = get_option("EWD_FEUP_SMTP_Username", "");
	$Message_Body = get_option("EWD_FEUP_Message_Body");
	$Email_Field = get_option("EWD_FEUP_Email_Field");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");

	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	
	$key = 'EWD_FEUP';
	if (function_exists('mcrypt_decrypt')) {$Admin_Password = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($Encrypted_Admin_Password), MCRYPT_MODE_CBC, md5(md5($key))), "\0");}
	else {$Admin_Password = $Encrypted_Admin_Password;}
	
	if ($Email_Confirmation == "Yes") {
		$Confirmation_Code = EWD_FEUP_RandomString();
		$PageLink = get_permalink($_POST['ewd-feup-post-id']);
		if (strpos($PageLink, "?") !== false) {
			$ConfirmationLink = $PageLink . "&ConfirmEmail=true&User_ID=" . $User_ID . "&ConfirmationCode=" . $ConfirmationCode;
		}
		else {
			$ConfirmationLink = $PageLink . "?ConfirmEmail=true&User_ID=" . $User_ID . "&ConfirmationCode=" . $ConfirmationCode;
		}
		$wpdb->query($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Confirmation_Code=%s WHERE User_ID=%d", $ConfirmationCode, $User_ID));
	}

	$Message_Body = str_replace("[username]", $User_Fields['Username'], $Message_Body);
	$Message_Body = str_replace("[password]", $_POST['User_Password'], $Message_Body);
	$Message_Body = str_replace("[join-date]", $User_Fields['User_Date_Created'], $Message_Body);
	if ($Email_Confirmation == "Yes") {$Message_Body = str_replace("[confirmation-link]", $ConfirmationLink, $Message_Body);}
	
	$Email_Field = str_replace(" ", "_", $Email_Field);
	if($Username_Is_Email == "Yes") {
		$User_Email = $User_Fields['Username'];
	} else {
	$User_Email = $Additional_Fields_Array[$Email_Field]['Field_Value'];
	}
	
	if ($SMTP_Mail_Server != "") {
		require_once(EWD_FEUP_CD_PLUGIN_PATH . '/PHPMailer/class.phpmailer.php');
		$mail = new PHPMailer(true);
		try {
  			$mail->CharSet = 'UTF-8';
			if ($Use_SMTP != "No") {
					$mail->IsSMTP();
					$mail->SMTPAuth = true;
					$mail->Username = $Username;
  					$mail->Password = $Admin_Password; 
				}
  			$mail->Host = $SMTP_Mail_Server;
  			$mail->Username = $SMTP_Username == "" ? $Admin_Email : $SMTP_Username;
  			$mail->Password = $Admin_Password;
  			$mail->Port = $Port;
  			$mail->WordWrap = 0;
  			$mail->AddCustomHeader('X-Mailer: EWD_FEUP v1.0');
  			$mail->SetFrom($Admin_Email);
  			$mail->AddAddress($User_Email);
  			$mail->Subject = $Email_Subject;
			$mail->Body = $Message_Body;
			$mail->isHTML(true);
  			//$mail->AltBody = $Text;
  			if (!$mail->Send()) {
				//echo "Email not sent.<br>";
    			//echo $mail->ErrorInfo;
  			} else {
    	 		//echo "Email sent.<br>";
  			}
		} catch (phpmailerException $e) {
    		//echo "FAIL:\n";
    		//echo $e->errorMessage(); // from PHPMailer
		} catch (Exception $e) {
    		//echo "FAIL:\n";
    		//echo $e->getMessage(); // from anything else!
		}		
	}
	else {
		$headers = 'From: ' . $Admin_Email . "\r\n" .
    				'Reply-To: ' . $Admin_Email . "\r\n" .
    				'X-Mailer: PHP/' . phpversion();
		$Mail_Success = mail($User_Email, $Email_Subject , $Message_Body, $headers);
	}
}

function EWD_FEUP_Send_Admin_Registration_Email($User_Fields, $Additional_Fields_Array, $User_ID) {
	global $wpdb, $ewd_feup_user_table_name;
	$Admin_Email = get_option("EWD_FEUP_Admin_Email");
	$Encrypted_Admin_Password = get_option("EWD_FEUP_Admin_Password");
	$Port = get_option("EWD_FEUP_Port");
	$Use_SMTP = get_option("EWD_FEUP_Use_SMTP");
	$SMTP_Mail_Server = get_option("EWD_FEUP_SMTP_Mail_Server");
	$SMTP_Username = get_option("EWD_FEUP_SMTP_Username", "");

	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	
	$key = 'EWD_FEUP';
	if (function_exists('mcrypt_decrypt')) {$Admin_Password = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($Encrypted_Admin_Password), MCRYPT_MODE_CBC, md5(md5($key))), "\0");}
	else {$Admin_Password = $Encrypted_Admin_Password;}

	$Email_Subject = "New FEUP User Registration";
	$Message_Body = "Hello Administrator, \n\r";
	$Message_Body .= "You have received a new registration on your site, " . get_site_url() . ".\n\r";
	$Message_Body .= "The username for the new user is: " . $User_Fields['Username'] . ",\n\r";
	$Message_Body .= "and the user has been assigned an ID of: " . $User_ID . ".\n\r";
	if ($Admin_Approval == "Yes") {$Message_Body .= "Your site currently requires admin approval of users, therefore you will need to approve this user before they are able to log in.\n\r";}
	$Message_Body .= "Thanks for using the Front-End Only Users plugin!\n\r";
	
	if ($SMTP_Mail_Server != "") {
		require_once(EWD_FEUP_CD_PLUGIN_PATH . '/PHPMailer/class.phpmailer.php');
		$mail = new PHPMailer(true);
		try {
  			$mail->CharSet = 'UTF-8';
			if ($Use_SMTP != "No") {
					$mail->IsSMTP();
					$mail->SMTPAuth = true;
					$mail->Username = $Username;
  					$mail->Password = $Admin_Password; 
				}
  			$mail->Host = $SMTP_Mail_Server;
  			$mail->Username = $SMTP_Username == "" ? $Admin_Email : $SMTP_Username;
  			$mail->Password = $Admin_Password;
  			$mail->Port = $Port;
  			$mail->WordWrap = 0;
  			$mail->AddCustomHeader('X-Mailer: EWD_FEUP v1.0');
  			$mail->SetFrom($Admin_Email);
  			$mail->AddAddress($Admin_Email);
  			$mail->Subject = $Email_Subject;
			$mail->Body = $Message_Body;
			$mail->isHTML(true);
  			//$mail->AltBody = $Text;
  			if (!$mail->Send()) {
				//echo "Email not sent.<br>";
    			//echo $mail->ErrorInfo;
  			} else {
    	 		//echo "Email sent.<br>";
  			}
		} catch (phpmailerException $e) {
    		//echo "FAIL:\n";
    		//echo $e->errorMessage(); // from PHPMailer
		} catch (Exception $e) {
    		//echo "FAIL:\n";
    		//echo $e->getMessage(); // from anything else!
		}		
	}
	else {
		$headers = 'From: ' . $Admin_Email . "\r\n" .
    				'Reply-To: ' . $Admin_Email . "\r\n" .
    				'X-Mailer: PHP/' . phpversion();
		$Mail_Success = mail($Admin_Email, $Email_Subject , $Message_Body, $headers);
	}
}

function EWD_FEUP_Send_Admin_Approval_Email($User_Fields, $Additional_Fields_Array, $User_ID = 0) {
	global $wpdb, $ewd_feup_user_table_name;
	$Admin_Email = get_option("EWD_FEUP_Admin_Email");
	$Email_Subject = get_option("EWD_FEUP_Email_Subject");
	$Encrypted_Admin_Password = get_option("EWD_FEUP_Admin_Password");
	$Port = get_option("EWD_FEUP_Port");
	$Use_SMTP = get_option("EWD_FEUP_Use_SMTP");
	$SMTP_Mail_Server = get_option("EWD_FEUP_SMTP_Mail_Server");
	$SMTP_Username = get_option("EWD_FEUP_SMTP_Username", "");
	$Message_Body = get_option("EWD_FEUP_Admin_Approval_Message_Body");
	$Email_Field = get_option("EWD_FEUP_Email_Field");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");

	$Email_On_Admin_Approval = get_option("EWD_FEUP_Email_On_Admin_Approval");
	
	$key = 'EWD_FEUP';
	if (function_exists('mcrypt_decrypt')) {$Admin_Password = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($Encrypted_Admin_Password), MCRYPT_MODE_CBC, md5(md5($key))), "\0");}
	else {$Admin_Password = $Encrypted_Admin_Password;}

	$Message_Body = str_replace("[username]", $User_Fields['Username'], $Message_Body);
	$Message_Body = str_replace("[join-date]", $User_Fields['User_Date_Created'], $Message_Body);
	
	$Email_Field = str_replace(" ", "_", $Email_Field);
	if($Username_Is_Email == "Yes") {
		$User_Email = $User_Fields['Username'];
	} else {
		$User_Email = $Additional_Fields_Array[$Email_Field]['Field_Value'];
	}
	
	if ($SMTP_Mail_Server != "") {
		require_once(EWD_FEUP_CD_PLUGIN_PATH . '/PHPMailer/class.phpmailer.php');
		$mail = new PHPMailer(true);
		try {
  			$mail->CharSet = 'UTF-8';
			if ($Use_SMTP != "No") {
					$mail->IsSMTP();
					$mail->SMTPAuth = true;
					$mail->Username = $Username;
  					$mail->Password = $Admin_Password; 
				}
  			$mail->Host = $SMTP_Mail_Server;
  			$mail->Username = $SMTP_Username == "" ? $Admin_Email : $SMTP_Username;
  			$mail->Password = $Admin_Password;
  			$mail->Port = $Port;
  			$mail->WordWrap = 0;
  			$mail->AddCustomHeader('X-Mailer: EWD_FEUP v1.0');
  			$mail->SetFrom($Admin_Email);
  			$mail->AddAddress($User_Email);
  			$mail->Subject = $Email_Subject;
			$mail->Body = $Message_Body;
			$mail->isHTML(true);
  			//$mail->AltBody = $Text;
  			if (!$mail->Send()) {
				//echo "Email not sent.<br>";
    			//echo $mail->ErrorInfo;
  			} else {
    	 		//echo "Email sent.<br>";
  			}
		} catch (phpmailerException $e) {
    		//echo "FAIL:\n";
    		//echo $e->errorMessage(); // from PHPMailer
		} catch (Exception $e) {
    		//echo "FAIL:\n";
    		//echo $e->getMessage(); // from anything else!
		}		
	}
	else {
		$headers = 'From: ' . $Admin_Email . "\r\n" .
    				'Reply-To: ' . $Admin_Email . "\r\n" .
    				'X-Mailer: PHP/' . phpversion();
		$Mail_Success = mail($User_Email, $Email_Subject , $Message_Body, $headers);
		//echo "Crappy Email Sent";
	}
}

/* Prepare the data to add multiple users from a spreadsheet */
function Add_Users_From_Spreadsheet() {
		
	if (!is_user_logged_in()) {exit();}

	/* Test if there is an error with the uploaded spreadsheet and return that error if there is */
	if (!empty($_FILES['Users_Spreadsheet']['error']))
	{
		switch($_FILES['Users_Spreadsheet']['error'])	{

			case '1':
				$error = __('The uploaded file exceeds the upload_max_filesize directive in php.ini', 'EWD_FEUP');
				break;
			case '2':
				$error = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'EWD_FEUP');
				break;
			case '3':
				$error = __('The uploaded file was only partially uploaded', 'EWD_FEUP');
				break;
			case '4':
				$error = __('No file was uploaded.', 'EWD_FEUP');
				break;

			case '6':
				$error = __('Missing a temporary folder', 'EWD_FEUP');
				break;
			case '7':
				$error = __('Failed to write file to disk', 'EWD_FEUP');
				break;
			case '8':
				$error = __('File upload stopped by extension', 'EWD_FEUP');
				break;
			case '999':
				default:
				$error = __('No error code avaiable', 'EWD_FEUP');
			}
	}
		/* Make sure that the file exists */ 	 	
		elseif (empty($_FILES['Users_Spreadsheet']['tmp_name']) || $_FILES['Users_Spreadsheet']['tmp_name'] == 'none') {
				$error = __('No file was uploaded here..', 'EWD_FEUP');
		}
		/* Check that it is a .xls or .xlsx file */
		if(!preg_match("/\.(xls.?)$/", $_FILES['Users_Spreadsheet']['name'])) {
			$error = __('File must be .xls or .xlsx', 'EWD_FEUP');
		}
		/* Move the file and store the URL to pass it onwards*/ 	 	
		else {				 
				 	  $msg .= $_FILES['Users_Spreadsheet']['name'];
						//for security reason, we force to remove all uploaded file
						$target_path = ABSPATH . 'wp-content/plugins/front-end-only-users/user-sheets/';

						$target_path = $target_path . basename( $_FILES['Users_Spreadsheet']['name']); 

						if (!move_uploaded_file($_FILES['Users_Spreadsheet']['tmp_name'], $target_path)) {
						//if (!$upload = wp_upload_bits($_FILES["Item_Image"]["name"], null, file_get_contents($_FILES["Item_Image"]["tmp_name"]))) {
				 			  $error .= "There was an error uploading the file, please try again!";
						}
						else {
				 				$Excel_File_Name = basename( $_FILES['Users_Spreadsheet']['name']);
						}	
		}

		/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the users */
		if (!isset($error)) {
				$user_update = Add_FEUP_Users_From_Spreadsheet($Excel_File_Name);
				return $user_update;
		}
		else {
				$output_error = array("Message_Type" => "Error", "Message" => $error);
				return $output_error;
		}
}

function Handle_File_Upload($Field_Name) {
	
	/* Test if there is an error with the uploaded file and return that error if there is */
	if (!empty($_FILES[$Field_Name]['error'])) {
		switch($_FILES[$Field_Name]['error']) {
			case '1':
				$error = __('The uploaded file exceeds the upload_max_filesize directive in php.ini', 'EWD_FEUP');
				break;
			case '2':
				$error = __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 'EWD_FEUP');
				break;
			case '3':
				$error = __('The uploaded file was only partially uploaded', 'EWD_FEUP');
				break;
			case '4':
				$error = __('No file was uploaded.', 'EWD_FEUP');
				break;

			case '6':
				$error = __('Missing a temporary folder', 'EWD_FEUP');
				break;
			case '7':
				$error = __('Failed to write file to disk', 'EWD_FEUP');
				break;
			case '8':
				$error = __('File upload stopped by extension', 'EWD_FEUP');
				break;
			case '999':
			default:
				$error = __('No error code avaiable', 'EWD_FEUP');
		}
	}
	/* Make sure that the file exists */ 	 	
	elseif (empty($_FILES[$Field_Name]['tmp_name']) || $_FILES[$Field_Name]['tmp_name'] == 'none') {
		$error = __('No file was uploaded here..', 'EWD_FEUP');
	}
	/* Move the file and store the URL to pass it onwards*/ 	 	
	else {				 
		$msg .= $_FILES[$Field_Name]['name'];
		//for security reason, we force to remove all uploaded file
		$target_path = ABSPATH . 'wp-content/uploads/ewd-feup-user-uploads/';
						
		//create the uploads directory if it doesn't exist
		if (!file_exists($target_path)) {
			  mkdir($target_path, 0777, true);
		}

		$Random = EWD_FEUP_RandomString();
		$target_path = $target_path . $Random . basename( $_FILES[$Field_Name]['name']); 

		if (!move_uploaded_file($_FILES[$Field_Name]['tmp_name'], $target_path)) {
		//if (!$upload = wp_upload_bits($_FILES["Item_Image"]["name"], null, file_get_contents($_FILES["Item_Image"]["tmp_name"]))) {
			$error .= "There was an error uploading the file, please try again!";
		}
		else {
					$User_Upload_File_Name = $Random . basename( $_FILES[$Field_Name]['name']);
		}	
	}
		
	/* Return the file name, or the error that was generated. */
	if (isset($error) and $error == __('No file was uploaded.', 'EWD_FEUP')) {
		$Return['Success'] = "N/A";
		$Return['Data'] = __('No file was uploaded.', 'EWD_FEUP');
	}
	elseif (!isset($error)) {
		$Return['Success'] = "Yes";
		$Return['Data'] = $User_Upload_File_Name;
	}
	else {
		$Return['Success'] = "No";
		$Return['Data'] = $error;
	}
	return $Return;
}

function EWD_FEUP_RandomString($CharLength = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < $CharLength; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}

function EWD_FEUP_Mass_User_Action() {
	if ($_POST['action'] == "delete") {$update = Mass_Delete_EWD_FEUP_Users();}
	elseif ($_POST['action'] == "approve") {$update = Mass_Approve_EWD_FEUP_Users();}
	elseif ($_POST['action'] == "-1") {}
	else {$update = Mass_Assign_Levels_EWD_FEUP_Users();}

	return $update;
}

function Mass_Delete_EWD_FEUP_Users() {
	$Users = $_POST['Users_Bulk'];
		
	if (is_array($Users)) {
		foreach ($Users as $User) {
			if ($User != "") {
				Delete_EWD_FEUP_User($User);
			}
		}
	}
		
	$update = __("Users have been successfully deleted.", 'EWD_FEUP');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Mass_Approve_EWD_FEUP_Users() {
	global $wpdb, $ewd_feup_user_table_name;
	$Users = $_POST['Users_Bulk'];

	if (is_array($Users)) {
		foreach ($Users as $User) {
			$Users = $wpdb->get_results($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET User_Admin_Approved='Yes' WHERE User_ID=%d", $User));
		}
	}

	$update = __("Users have been successfully approved.", 'EWD_FEUP');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Mass_Assign_Levels_EWD_FEUP_Users() {
	global $wpdb, $ewd_feup_user_table_name;
	$Users = $_POST['Users_Bulk'];

	if (is_array($Users)) {
		foreach ($Users as $User) {
			$Users = $wpdb->get_results($wpdb->prepare("UPDATE $ewd_feup_user_table_name SET Level_ID=%d WHERE User_ID=%d", $_POST['action'], $User));
		}
	}

	$update = __("User levels have been successfully updated.", 'EWD_FEUP');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Delete_All_EWD_FEUP_Users() {
	global $wpdb, $ewd_feup_user_table_name;
	$Users = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name");
		
	if (is_array($Users)) {
		foreach ($Users as $User) {
			if ($User->User_ID != "") {
				Delete_EWD_FEUP_User($User->User_ID);
			}
		}
	}
		
	$update = __("Users have been successfully deleted.", 'EWD_OTP');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Add_Edit_Field() {
	global $wpdb, $ewd_feup_fields_table_name;
		
	$Field_ID = stripslashes_deep($_POST['Field_ID']);
	$Field_Name = stripslashes_deep($_POST['Field_Name']);
	$Field_Type = stripslashes_deep($_POST['Field_Type']);
	$Field_Description = stripslashes_deep($_POST['Field_Description']);
	$Field_Options = stripslashes_deep($_POST['Field_Options']);
	$Field_Show_In_Admin = stripslashes_deep($_POST['Field_Show_In_Admin']);
	$Field_Show_In_Front_End = stripslashes_deep($_POST['Field_Show_In_Front_End']);
	$Field_Required = stripslashes_deep($_POST['Field_Required']);
		
	$Field_Date_Created = date("Y-m-d H:i:s");

	if (!isset($error)) {
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the product */
		if ($_POST['action'] == "Add_Field") {
			  $user_update = Add_EWD_FEUP_Field($Field_Name, $Field_Type, $Field_Description, $Field_Options, $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required, $Field_Date_Created);
		}
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the product */
		else {
				$user_update = Edit_EWD_FEUP_Field($Field_ID, $Field_Name, $Field_Type, $Field_Description, $Field_Options, $Field_Show_In_Admin, $Field_Show_In_Front_End, $Field_Required);
		}
		$user_update = array("Message_Type" => "Update", "Message" => $user_update);
		return $user_update;
	}
	/* Return any error that might have occurred */
	else {
		$output_error = array("Message_Type" => "Error", "Message" => $error);
		return $output_error;
	}
}

function Mass_Delete_EWD_FEUP_Fields() {
	$Fields = $_POST['Fields_Bulk'];
		
	if (is_array($Fields)) {
		foreach ($Fields as $Field) {
			if ($Field != "") {
				Delete_EWD_FEUP_Field($Field);
			}
		}
	}
		
	$update = __("Fields have been successfully deleted.", 'EWD_FEUP');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function Add_Edit_Level() {
		
	$Level_ID = $_POST['Level_ID'];
	$Level_Name = $_POST['Level_Name'];
	$Level_Privilege = $_POST['Level_Privilege'];
		
	$Level_Date_Created = date("Y-m-d H:i:s");

	if (!isset($error)) {
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to create the product */
		if ($_POST['action'] == "Add_Level") {
			  $user_update = Add_EWD_FEUP_Level($Level_Name, $Level_Privilege, $Level_Date_Created);
		}
		/* Pass the data to the appropriate function in Update_Admin_Databases.php to edit the product */
		else {
				$user_update = Edit_EWD_FEUP_Level($Level_ID, $Level_Name, $Level_Privilege, $Level_Date_Created);
		}
		$user_update = array("Message_Type" => "Update", "Message" => $user_update);
		return $user_update;
	}
	/* Return any error that might have occurred */
	else {
		$output_error = array("Message_Type" => "Error", "Message" => $error);
		return $output_error;
	}
}

function Mass_Delete_EWD_FEUP_Levels() {
	$Levels = $_POST['Levels_Bulk'];
		
	if (is_array($Levels)) {
		foreach ($Levels as $Level) {
			if ($Level != "") {
				Delete_EWD_FEUP_Level($Level);
			}
		}
	}
		
	$update = __("Fields have been successfully deleted.", 'EWD_FEUP');
	$user_update = array("Message_Type" => "Update", "Message" => $update);
	return $user_update;
}

function EWD_FEUP_One_Click_Install() {
	global $EWD_FEUP_Full_Version, $Links_Array;

	if ($EWD_FEUP_Full_Version != "Yes") {exit();}

	foreach ($_POST['page'] as $Page) {
		$Page_Attribute_One = $_POST[$Page . "_attribute_one"];
		$Page_Attribute_Two = $_POST[$Page . "_attribute_two"];
		$Page_Attribute_Three = $_POST[$Page . "_attribute_three"];

		if ($Page_Attribute_One != "") {$Attribute_One_Value = $_POST[$Page . "_" . $Page_Attribute_One];}
		if ($Page_Attribute_One != "") {$Attribute_Two_Value = $_POST[$Page . "_" . $Page_Attribute_Two];}
		if ($Page_Attribute_One != "") {$Attribute_Three_Value = $_POST[$Page . "_" . $Page_Attribute_Three];}

		$Attributes = array();
		if ($Page_Attribute_One != "") {$Attributes[$Page_Attribute_One] = EWD_FEUP_Get_Attribute_Insert_Value($Page, $Page_Attribute_One, $Attribute_One_Value);}
		if ($Page_Attribute_One != "") {$Attributes[$Page_Attribute_Two] = EWD_FEUP_Get_Attribute_Insert_Value($Page, $Page_Attribute_Two, $Attribute_Two_Value);}
		if ($Page_Attribute_One != "") {$Attributes[$Page_Attribute_Three] = EWD_FEUP_Get_Attribute_Insert_Value($Page, $Page_Attribute_Three, $Attribute_Three_Value);}

		$Page_Content = EWD_FEUP_Get_Page_Content($Page, $Attributes);

		$Page_ID = EWD_FEUP_Add_Page(str_replace("_", " ", $Page), $Page_Content);
	}

	EWD_FEUP_Handle_Newly_Created_Page_Links($Links_Array);

	$update = __("Pages have been successfully added. Check the 'Pages' section to modify them.", 'EWD_FEUP');
	$user_update = array("Message_Type" => "Update", "Message" => $update);

	return $user_update;
}

function EWD_FEUP_Get_Attribute_Insert_Value($Page, $Page_Attribute, $Attribute_Value) {
	global $Links_Array;

	if ($Page_Attribute == "redirect_page" or $Page_Attribute == "login_page" or $Page_Attribute == "loggedin_page" or $Page_Attribute == "reset_email_url") {
		if (strpos($Attribute_Value, "(Newly Created)") !== FALSE) {
			$Links_Array[$Page . "|" . $Page_Attribute] = $Attribute_Value;
			$Insert_Attribute_Value = $Page . "|" . $Page_Attribute;
		}
		else {
			$Redirect_Page_Object = get_page_by_title(str_replace("_", " ", $Attribute_Value));
			$Insert_Attribute_Value  = get_permalink($Redirect_Page_Object->ID);
		}
	}
	else {$Insert_Attribute_Value = $Attribute_Value;}

	return $Insert_Attribute_Value;
}

function EWD_FEUP_Get_Page_Content($Page, $Attributes = array()) {
	switch ($Page) {
		case 'Edit_Profile':
			$Content = "[edit-profile";
			break;
		case 'Register':
			$Content = "[register";
			break;
		case 'Login':
			$Content = "[login";
			break;
		case 'Logout':
			$Content = "[logout";
			break;
		case 'Forgot_Password':
			$Content = "[forgot-password";
			break;
		case 'Confirm_Forgot_Password':
			$Content = "[confirm-forgot-password";
			break;
		case 'Change_Password':
			$Content = "[reset-password";
			break;
		case 'Login_Logout_Toggle':
			$Content = "[login-logout-toggle";
			break;
		case 'User_Profile':
			$Content = "[user-profile";
			break;
		case 'User_Search':
			$Content = "[user-search";
			break;
		case 'User_List':
			$Content = "[user-list";
			break;
		case 'User_Data':
			$Content = "[user-data";
			break;
	}

	foreach ($Attributes as $Attribute_Name => $Attribute_Value) {
		if ($Attribute_Value != "") {$Content .= " " . $Attribute_Name . "='" . $Attribute_Value . "'";}
	}
	
	$Content .= "]";

	return $Content;
}

function EWD_FEUP_Handle_Newly_Created_Page_Links($Links_Array) {
	if (is_array($Links_Array)) {
		foreach ($Links_Array as $Replace_Value => $Page_Name) {
			$Page_Name = substr($Page_Name, 0, -16);
			$Replace_Page_Name = substr($Replace_Value, 0, strpos($Replace_Value, "|"));
		
			$Redirect_Page_Object = get_page_by_title(str_replace("_", " ", $Page_Name));
			$Insert_Attribute_Value  = get_permalink($Redirect_Page_Object->ID);
			
			$Replace_Page_Object = get_page_by_title(str_replace("_", " ", $Replace_Page_Name));
			$Current_Content = $Replace_Page_Object->post_content;
			$New_Content = str_replace($Replace_Value, $Insert_Attribute_Value, $Current_Content);

			$update_post = array(
							'ID' => $Replace_Page_Object->ID, 
							'post_content' => $New_Content
							);
			wp_update_post($update_post);
		}
	}
}
?>
