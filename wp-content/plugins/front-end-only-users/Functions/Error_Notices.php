<?php
/* Add any update or error notices to the top of the admin page */
function EWD_FEUP_Error_Notices(){
    global $feup_message;
		if (isset($feup_message)) {
			if ($feup_message['Message_Type'] == "Update") {echo "<div class='updated'><p>" . $feup_message['Message'] . "</p></div>";}
			if ($feup_message['Message_Type'] == "Error") {echo "<div class='error'><p>" . $feup_message['Message'] . "</p></div>";}
		}
}

?>