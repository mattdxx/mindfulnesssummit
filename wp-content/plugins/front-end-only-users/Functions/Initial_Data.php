<?php
/* Adds a small amount of sample data to the UPCP database for demonstration purposes */
function Initial_EWD_FEUP_Data() {
		global $wpdb;
		global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_levels_table_name, $ewd_feup_fields_table_name;
		
		$myrows = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name LIMIT 0,3");
		$num_rows = $wpdb->num_rows; 
		
		if ($num_rows == 0) {
			  $wpdb->insert($ewd_feup_fields_table_name,
				array(
						'Field_Name' => __('First Name', 'EWD_FEUP'),
						'Field_Description' => __('Your first name.', 'EWD_FEUP'),
						'Field_Type' => 'text',
						'Field_Show_In_Admin' => __('Yes', 'EWD_FEUP'),
						'Field_Show_In_Front_End' => __('Yes', 'EWD_FEUP'),
						'Field_Required' => __('Yes', 'EWD_FEUP'),
						'Field_Date_Created' => date("Y-m-d H:i:s")
				));
				$wpdb->insert($ewd_feup_fields_table_name,
				array(
						'Field_Name' => __('Last Name', 'EWD_FEUP'),
						'Field_Description' => __('Your last name.', 'EWD_FEUP'),
						'Field_Type' => 'text',
						'Field_Show_In_Admin' => __('Yes', 'EWD_FEUP'),
						'Field_Show_In_Front_End' => __('Yes', 'EWD_FEUP'),
						'Field_Required' => __('Yes', 'EWD_FEUP'),
						'Field_Date_Created' => date("Y-m-d H:i:s")
				));
				$wpdb->insert($ewd_feup_fields_table_name,
				array(
						'Field_Name' => __('Email', 'EWD_FEUP'),
						'Field_Description' => __('Your e-mail address.', 'EWD_FEUP'),
						'Field_Type' => 'text',
						'Field_Show_In_Admin' => __('Yes', 'EWD_FEUP'),
						'Field_Show_In_Front_End' => __('Yes', 'EWD_FEUP'),
						'Field_Required' => __('Yes', 'EWD_FEUP'),
						'Field_Date_Created' => date("Y-m-d H:i:s")
				));
		
				$wpdb->insert($ewd_feup_levels_table_name,
				array(
						'Level_Name' => __('Regular User', 'EWD_FEUP'),
						'Level_Privilege' => 1,
						'Level_Date_Created' => date("Y-m-d H:i:s")
				));
		}
		
		update_option('EWD_FEUP_Login_Time', 1440);
}
?>