<?php
/* This file is the action handler. The appropriate function is then called based 
*  on the action that's been selected by the user. The functions themselves are all
* stored either in Prepare_Data_For_Insertion.php or Update_Admin_Databases.php */

function Update_EWD_FEUP_Content() {
global $feup_message;
if (isset($_GET['Action'])) {
				switch ($_GET['Action']) {
    				case "EWD_FEUP_AddUser":
						case "EWD_FEUP_EditUser":
        				$feup_message = Add_Edit_User();
								break;
						case "EWD_FEUP_DeleteUser":
								$feup_message = Delete_EWD_FEUP_User($_GET['User_ID']);
								break;
						case "EWD_FEUP_MassUserAction":
								$feup_message = EWD_FEUP_Mass_User_Action();
								break;
						case "EWD_FEUP_DeleteAllUsers":
								$feup_message = Delete_All_EWD_FEUP_Users();
								break;
						case "EWD_FEUP_AddUserSpreadsheet":
								$feup_message = Add_Users_From_Spreadsheet();
								break;
						case "EWD_FEUP_AddField":
						case "EWD_FEUP_EditField":
								$feup_message = Add_Edit_Field();
								break;
						case "EWD_FEUP_DeleteField":
								$feup_message = Delete_EWD_FEUP_Field($_GET['Field_ID']);
								break;
						case "EWD_FEUP_MassDeleteFields":
								$feup_message = Mass_Delete_EWD_FEUP_Fields();
								break;
						case "EWD_FEUP_AddLevel":
						case "EWD_FEUP_EditLevel":
								$feup_message = Add_Edit_Level();
								break;
						case "EWD_FEUP_DeleteLevel":
								$feup_message = Delete_EWD_FEUP_Level($_GET['Level_ID']);
								break;
						case "EWD_FEUP_MassDeleteLevels":
								$feup_message = Mass_Delete_EWD_FEUP_Levels();
								break;
						case "EWD_FEUP_UpdateOptions":
								$feup_message = Update_EWD_FEUP_Options();
								break;
						case "EWD_FEUP_UpdateEmailSettings":
        				$feup_message = Update_EWD_FEUP_Email_Settings();
								break;
						case "EWD_FEUP_ExportToExcel":
								$feup_message = EWD_FEUP_Export_To_Excel();
								break;
						case "EWD_FEUP_OneClickInstall":
								$feup_message = EWD_FEUP_One_Click_Install();
								break;
						default:
								//$feup_update_message = __("The form has not worked correctly. Please contact the plugin developer.", 'EWD_FEUP');
								break;
				}
		}
}

?>