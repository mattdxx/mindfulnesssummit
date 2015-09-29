<?php 

function EWD_FEUP_Set_Default_Style_Values() {
	
	$update = __("Styles have been succesfully reset.", 'FEUP');
	return $update;
}

function EWD_FEUP_Add_Modified_Styles() {

	$StylesString .=".ewd-feup-field-label { ";
		if (get_option("EWD_FEUP_Styling_Form_Font") != "") {$StylesString .= "font-family:" .  get_option("EWD_FEUP_Styling_Form_Font") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Form_Font_Size") != "") {$StylesString .= "font-size:" .  get_option("EWD_FEUP_Styling_Form_Font_Size") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Form_Font_Weight") != "") {$StylesString .= "font-weight:" .  get_option("EWD_FEUP_Styling_Form_Font_Weight") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Form_Font_Color") != "") {$StylesString .= "color:" .  get_option("EWD_FEUP_Styling_Form_Font_Color") . " !important;";} 
	$StylesString .="}\n";
	$StylesString .=".feup-pure-control-group { ";
		if (get_option("EWD_FEUP_Styling_Form_Margin") != "") {$StylesString .= "margin:" .  get_option("EWD_FEUP_Styling_Form_Margin") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Form_Padding") != "") {$StylesString .= "padding:" .  get_option("EWD_FEUP_Styling_Form_Padding") . " !important;";} 
	$StylesString .="}\n";
	
	$StylesString .=".ewd-feup-submit { ";
		if (get_option("EWD_FEUP_Styling_Submit_Bg_Color") != "") {$StylesString .= "background-color:" .  get_option("EWD_FEUP_Styling_Submit_Bg_Color") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Submit_Font") != "") {$StylesString .= "font-family:" .  get_option("EWD_FEUP_Styling_Submit_Font") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Submit_Font_Color") != "") {$StylesString .= "color:" .  get_option("EWD_FEUP_Styling_Submit_Font_Color") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Submit_Margin") != "") {$StylesString .= "margin:" .  get_option("EWD_FEUP_Styling_Submit_Margin") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Submit_Padding") != "") {$StylesString .= "padding:" .  get_option("EWD_FEUP_Styling_Submit_Padding") . " !important;";} 
	$StylesString .="}\n";
		
	$StylesString .=".ewd-feup-user-list-result a { ";
		if (get_option("EWD_FEUP_Styling_Userlistings_Font") != "") {$StylesString .= "font-family:" .  get_option("EWD_FEUP_Styling_Userlistings_Font") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Userlistings_Font_Size") != "") {$StylesString .= "font-size:" .  get_option("EWD_FEUP_Styling_Userlistings_Font_Size") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Userlistings_Font_Weight") != "") {$StylesString .= "font-weight:" .  get_option("EWD_FEUP_Styling_Userlistings_Font_Weight") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Userlistings_Font_Color") != "") {$StylesString .= "color:" .  get_option("EWD_FEUP_Styling_Userlistings_Font_Color") . " !important;";} 
	$StylesString .="}\n";
	$StylesString .=".ewd-feup-user-list-result { ";
		if (get_option("EWD_FEUP_Styling_Userlistings_Margin") != "") {$StylesString .= "margin:" .  get_option("EWD_FEUP_Styling_Userlistings_Margin") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Userlistings_Padding") != "") {$StylesString .= "padding:" .  get_option("EWD_FEUP_Styling_Userlistings_Padding") . " !important;";} 
	$StylesString .="}\n";
	$StylesString .=".ewd-feup-user-profile-label { ";
		if (get_option("EWD_FEUP_Styling_Userprofile_Label_Font") != "") {$StylesString .= "font-family:" .  get_option("EWD_FEUP_Styling_Userprofile_Label_Font") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Userprofile_Label_Font_Size") != "") {$StylesString .= "font-size:" .  get_option("EWD_FEUP_Styling_Userprofile_Label_Font_Size") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Userprofile_Label_Font_Weight") != "") {$StylesString .= "font-weight:" .  get_option("EWD_FEUP_Styling_Userprofile_Label_Font_Weight") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Userprofile_Label_Font_Color") != "") {$StylesString .= "color:" .  get_option("EWD_FEUP_Styling_Userprofile_Label_Font_Color") . " !important;";} 
	$StylesString .="}\n";
	$StylesString .=".ewd-feup-user-profile-input' { ";
		if (get_option("EWD_FEUP_Styling_Userprofile_Content_Font") != "") {$StylesString .= "font-family:" .  get_option("EWD_FEUP_Styling_Userprofile_Content_Font") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Userprofile_Content_Font_Size") != "") {$StylesString .= "font-size:" .  get_option("EWD_FEUP_Styling_Userprofile_Content_Font_Size") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Userprofile_Content_Font_Weight") != "") {$StylesString .= "font-weight:" .  get_option("EWD_FEUP_Styling_Userprofile_Content_Font_Weight") . " !important;";} 
		if (get_option("EWD_FEUP_Styling_Userprofile_Content_Font_Color") != "") {$StylesString .= "color:" .  get_option("EWD_FEUP_Styling_Userprofile_Content_Font_Color") . " !important;";} 
	$StylesString .="}\n";
	$StylesString .= "</style>";

	return $StylesString;
}