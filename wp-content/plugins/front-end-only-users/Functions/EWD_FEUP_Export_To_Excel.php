<?php
function EWD_FEUP_Export_To_Excel() {
	global $wpdb;
	global $ewd_feup_user_table_name, $ewd_feup_user_fields_table_name, $ewd_feup_levels_table_name, $ewd_feup_fields_table_name;
		
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");

	include_once('../wp-content/plugins/front-end-only-users/PHPExcel/Classes/PHPExcel.php');
		
	// Instantiate a new PHPExcel object 
	$objPHPExcel = new PHPExcel();  
	// Set the active Excel worksheet to sheet 0 
	$objPHPExcel->setActiveSheetIndex(0);  

	// Print out the regular order field labels
	$objPHPExcel->getActiveSheet()->setCellValue("A1", "Username");
	$objPHPExcel->getActiveSheet()->setCellValue("B1", "Date Created");
	$objPHPExcel->getActiveSheet()->setCellValue("C1", "Last Login");
		
	$column = 'D';

	if ($Admin_Approval == "Yes") {
		$objPHPExcel->getActiveSheet()->setCellValue($column . "1", "Admin Approved");
		$column++;
	}
	if ($Email_Confirmation == "Yes"){
		$objPHPExcel->getActiveSheet()->setCellValue($column . "1", "Email Confirmed");
		$column++;
	}

	//start of printing column names as names of custom fields  
	$Sql = "SELECT * FROM $ewd_feup_fields_table_name";
	$User_Fields = $wpdb->get_results($Sql);
	foreach ($User_Fields as $User_Field) {
     	$objPHPExcel->getActiveSheet()->setCellValue($column . "1", $User_Field->Field_Name);
    	$column++;
	}  

	//start while loop to get data  
	$rowCount = 2;  
	$Users = $wpdb->get_results("SELECT * FROM $ewd_feup_user_table_name");
	foreach ($Users as $User)  
	{  
    	$objPHPExcel->getActiveSheet()->setCellValue("A" . $rowCount, $User->Username);
		$objPHPExcel->getActiveSheet()->setCellValue("B" . $rowCount, $User->User_Date_Created);
		$objPHPExcel->getActiveSheet()->setCellValue("C" . $rowCount, $User->User_Last_Login);

		$column = 'D';

		if ($Admin_Approval == "Yes") {
			$objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $User->User_Admin_Approved);
			$column++;
		}
		if ($Email_Confirmation == "Yes"){
			$objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $User->User_Email_Confirmed);
			$column++;
		}

    	foreach ($User_Fields as $User_Field) {  
        	$MetaValue = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_ID=%d AND User_ID=%d", $User_Field->Field_ID, $User->User_ID));
        	$objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $MetaValue);
        	$column++;
    	}  
    	$rowCount++;
	} 


	// Redirect output to a client’s web browser (Excel5) 
	header('Content-Type: application/vnd.ms-excel'); 
	header('Content-Disposition: attachment;filename="FEUP_Users_Export.xls"'); 
	header('Cache-Control: max-age=0'); 
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
	$objWriter->save('php://output');

}
?>