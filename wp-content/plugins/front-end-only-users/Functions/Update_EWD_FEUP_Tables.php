<?php
function Update_EWD_FEUP_Tables() {
	/* Add in the required globals to be able to create the tables */
  	global $wpdb;
   	global $EWD_FEUP_db_version;
	global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_levels_table_name, $ewd_feup_fields_table_name, $ewd_feup_user_events_table_name;
    
	/* Create the users table */  
   	$sql = "CREATE TABLE $ewd_feup_user_table_name (
  		User_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  		Username text NULL,
		User_Password text NULL,
		Level_ID mediumint(9) DEFAULT 0 NOT NULL,
		User_Email_Confirmed text NULL,
		User_Confirmation_Code text NULL,
		User_Admin_Approved text NULL,
		User_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
		User_Last_Login datetime DEFAULT '0000-00-00 00:00:00' NULL,
		User_Total_Logins mediumint(9) DEFAULT 0 NOT NULL,
		User_Password_Reset_Code text NULL,
		User_Password_Reset_Date datetime DEFAULT '0000-00-00 00:00:00' NULL,
		User_Sessioncheck varchar(255) DEFAULT NULL,
  		UNIQUE KEY id (User_ID)
    	)
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
		
	/* Create the fields table */
	$sql = "CREATE TABLE $ewd_feup_fields_table_name (
  		Field_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  		Field_Name text   NULL,
		Field_Description text   NULL,
		Field_Type text   NULL,
		Field_Options text   NULL,
		Field_Show_In_Admin text   NULL,
		Field_Show_In_Front_End   text NULL,
		Field_Required text   NULL,
		Field_Order mediumint(9) DEFAULT 0 NOT NULL,
		Field_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (Field_ID)
    	)	
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
		
	/* Create the user-fields table */
	$sql = "CREATE TABLE $ewd_feup_user_fields_table_name (
  		User_Field_ID mediumint(9) NOT NULL AUTO_INCREMENT,
		Field_ID mediumint(9) DEFAULT 0 NOT NULL,
		User_ID mediumint(9) DEFAULT 0 NOT NULL,
  		Field_Name text NULL,
		Field_Value text NULL,
		User_Field_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (User_Field_ID)
    	)	
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
		
	/* Create the levels table */
	$sql = "CREATE TABLE $ewd_feup_levels_table_name (
  		Level_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  		Level_Name text   NULL,
		Level_Privilege text   NULL,
		Level_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (Level_ID)
    	)
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);

   	/* Create the user-events table */
	$sql = "CREATE TABLE $ewd_feup_user_events_table_name (
  		User_Event_ID mediumint(9) NOT NULL AUTO_INCREMENT,
		User_ID mediumint(9) DEFAULT 0 NOT NULL,
  		Event_Type text NULL,
  		Event_Location text NULL,
  		Event_Location_ID mediumint(9) DEFAULT 0 NOT NULL,
  		Event_Location_Title text NULL,
		Event_Value text NULL,
		Event_Target_ID mediumint(9) DEFAULT 0 NOT NULL,
		Event_Target_Title text NULL,
		Event_Date datetime DEFAULT '0000-00-00 00:00:00' NULL,
  		UNIQUE KEY id (User_Event_ID)
    	)	
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
 	
   	if (get_option("EWD_FEUP_Use_Crypt") == "true" or get_option("EWD_FEUP_Use_Crypt")) {add_option("EWD_FEUP_Use_Crypt", "Yes");}
   	if (get_option("EWD_FEUP_Use_Crypt") == "false" or get_option("EWD_FEUP_Use_Crypt") == "") {add_option("EWD_FEUP_Use_Crypt", "No");}
	if (get_option("EWD_FEUP_Username_Is_Email") == "true" or get_option("EWD_FEUP_Username_Is_Email")) {add_option("EWD_FEUP_Username_Is_Email", "Yes");}
	if (get_option("EWD_FEUP_Username_Is_Email") == "false" or get_option("EWD_FEUP_Username_Is_Email") == "") {add_option("EWD_FEUP_Username_Is_Email", "No");}

	if (get_option("EWD_FEUP_Use_Captcha") == "") {add_option("EWD_FEUP_Use_Captcha", "No");}
	if (get_option("EWD_FEUP_Track_Events") == "") {add_option("EWD_FEUP_Track_Events", "No");}

	if (get_option("EWD_FEUP_Use_SMTP") == "") {update_option("EWD_FEUP_Use_SMTP", "Yes");}
	if (get_option("EWD_FEUP_Port") == "") {update_option("EWD_FEUP_Port", "25");}

	if (get_option("EWD_FEUP_WooCommerce_Integration") == "") {update_option("EWD_FEUP_WooCommerce_Integration", "No");}
	if (get_option("EWD_FEUP_WooCommerce_First_Name_Field") == "") {update_option("EWD_FEUP_WooCommerce_First_Name_Field", "First Name");}
	if (get_option("EWD_FEUP_WooCommerce_Last_Name_Field") == "") {update_option("EWD_FEUP_WooCommerce_Last_Name_Field", "Last Name");}
	if (get_option("EWD_FEUP_WooCommerce_Company_Field") == "") {update_option("EWD_FEUP_WooCommerce_Company_Field", "Company");}
	if (get_option("EWD_FEUP_WooCommerce_Address_Line_One_Field") == "") {update_option("EWD_FEUP_WooCommerce_Address_Line_One_Field", "Address Line One");}
	if (get_option("EWD_FEUP_WooCommerce_Address_Line_Two_Field") == "") {update_option("EWD_FEUP_WooCommerce_Address_Line_Two_Field", "Address Line Two");}
	if (get_option("EWD_FEUP_WooCommerce_City_Field") == "") {update_option("EWD_FEUP_WooCommerce_City_Field", "City");}
	if (get_option("EWD_FEUP_WooCommerce_Postcode_Field") == "") {update_option("EWD_FEUP_WooCommerce_Postcode_Field", "Postcode");}
	if (get_option("EWD_FEUP_WooCommerce_Country_Field") == "") {update_option("EWD_FEUP_WooCommerce_Country_Field", "Country");}
	if (get_option("EWD_FEUP_WooCommerce_State_Field") == "") {update_option("EWD_FEUP_WooCommerce_State_Field", "State");}
	if (get_option("EWD_FEUP_WooCommerce_Email_Field") == "") {update_option("EWD_FEUP_WooCommerce_Email_Field", "Email");}
	if (get_option("EWD_FEUP_WooCommerce_Phone_Field") == "") {update_option("EWD_FEUP_WooCommerce_Phone_Field", "Phone");}

	update_option("EWD_FEUP_db_version", $EWD_FEUP_db_version);
}
?>
