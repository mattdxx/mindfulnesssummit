<?php
function EWD_FEUP_UpdateTables() {
		/* Add in the required globals to be able to create the tables */
  	global $wpdb;
		global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_levels_table_name, $ewd_feup_fields_table_name;
    
		/* Create the categories table */  
   	$sql = "CREATE TABLE $ewd_feup_user_table_name (
  	User_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  	Username text DEFAULT '' NOT NULL,
		User_Password text  DEFAULT '' NOT NULL,
		User_First_Name text  DEFAULT '' NOT NULL,
		User_Last_Name text  DEFAULT '' NOT NULL,
		User_Email text  DEFAULT '' NOT NULL,
		User_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  	UNIQUE KEY id (User_ID)
    )
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
		
		/* Create the sub-categories table */
		$sql = "CREATE TABLE $ewd_feup_fields_table_name (
  	Field_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  	Field_Name text  DEFAULT '' NOT NULL,
		Field_Type text  DEFAULT '' NOT NULL,
		Field_Show_In_Admin text  DEFAULT '' NOT NULL,
		Field_Show_In_Front_End  DEFAULT '' text NOT NULL,
		Field_Required text  DEFAULT '' NOT NULL,
		Field_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  	UNIQUE KEY id (Field_ID)
    )	
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
		
		/* Create the items(products) table */
		$sql = "CREATE TABLE $ewd_feup_levels_table_name (
  	Level_ID mediumint(9) NOT NULL AUTO_INCREMENT,
  	Level_Name text  DEFAULT '' NOT NULL,
		Level_Privilege text  DEFAULT '' NOT NULL,
		Level_Date_Created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  	UNIQUE KEY id (Level_ID)
    )
		DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
   	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   	dbDelta($sql);
}
?>