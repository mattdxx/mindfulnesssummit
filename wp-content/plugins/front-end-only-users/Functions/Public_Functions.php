<?php
if (!class_exists('FEUP_User')){
    class FEUP_User {
    	private $Username;
		private $User_ID;
				
		function __construct() {
			global $wpdb, $ewd_feup_user_table_name;

			$CheckCookie = CheckLoginCookie();
			if ($CheckCookie['Username'] != "") {
								
				$this->Username = $CheckCookie['Username'];
				$this->User_ID = $wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username=%s", $this->Username));
			}
    	}
		
		function Get_User_Name_For_ID($id = null) {
			global $wpdb, $ewd_feup_user_table_name;

			if(!$id) {
				return null;
			}

			return $wpdb->get_var($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID=%d", $id));
		}

		function Get_Field_Value_For_ID($Field, $id) {
			global $wpdb, $ewd_feup_user_fields_table_name;

			if(!$Field || !$id) {
				return null;
			}
			$Value = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_Name=%s AND User_ID=%d", $Field, $id));
			
			return $Value;
		}
				
		function Get_User_ID() {
			return $this->User_ID;
		}
				
		function Get_Username() {
			return $this->Username;
		}
				
		function Get_Field_Value($Field) {
			global $wpdb, $ewd_feup_user_fields_table_name;
						
			$Value = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_Name=%s AND User_ID=%d", $Field, $this->User_ID));
			
			return $Value;
		}

    	function Is_Logged_In() {
			if ($this->Username == "") {return false;}
			else {return true;}
    	}
	}
}
	
?>