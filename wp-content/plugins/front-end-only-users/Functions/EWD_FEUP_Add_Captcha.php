<?php

function EWD_FEUP_Add_Captcha() {
	$Code = rand(1000,9999);
	$ModifiedCode = EWD_FEUP_Encrypt_Captcha_Code($Code);

	$ReturnString .= "<div class='feup-pure-control-group'><label for='captcha_image'></label>";
	$ReturnString .= "<img src=" . EWD_FEUP_CD_PLUGIN_URL . "/Functions/EWD_FEUP_Create_Captcha_Image.php?Code=" . $ModifiedCode . " />";
	$ReturnString .= "<input type='hidden' name='ewd_feup_modified_captcha' value='" . $ModifiedCode . "' />";
	$ReturnString .= "</div>";
	$ReturnString .= "<div class='feup-pure-control-group'><label for='captcha_text'>" . __("Image Number: ", 'EWD_FEUP') . "</label>";
	$ReturnString .= "<input type='text' name='ewd_feup_captcha' value='' />";
	$ReturnString .= "</div>";
	
	return $ReturnString;
}

function EWD_FEUP_Validate_Captcha() {
	$ModifiedCode = $_POST['ewd_feup_modified_captcha'];
	$UserCode = $_POST['ewd_feup_captcha'];

	$Code = EWD_FEUP_Decrypt_Catpcha_Code($ModifiedCode);

	if ($Code == $UserCode) {$Validate_Captcha = "Yes";}
	else {$Validate_Captcha = "No";}

	return $Validate_Captcha;
}

function EWD_FEUP_Encrypt_Captcha_Code($Code) {
	$ModifiedCode = ($Code + 5) * 3;

	return $ModifiedCode;
}

function EWD_FEUP_Decrypt_Catpcha_Code($ModifiedCode) {
	$Code = ($ModifiedCode / 3) - 5;

	return $Code;
}
?>
