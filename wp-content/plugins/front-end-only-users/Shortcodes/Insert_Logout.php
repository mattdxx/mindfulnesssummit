<?php 
/* The function that creates the HTML on the front-end, based on the parameters
* supplied in the product-catalog shortcode */
function Insert_Logout($atts) {
		// Include the required global variables, and create a few new ones
		$Salt = get_option("EWD_FEUP_Hash_Salt");
		$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
		$CookieName = urlencode("EWD_FEUP_Login" . "%" . sha1(md5(get_site_url().$Salt))); 
		$ReturnString="";
		
		// Get the attributes passed by the shortcode, and store them in new variables for processing
		extract( shortcode_atts( array(
						 								 		'no_message' => '',
																'redirect_page' => '#',
																'submit_text' => 'Logout'),
																$atts
														)
												);
		
		setcookie($CookieName, "", time()-3600, "/");
		if ($redirect_page != "#") {FEUPRedirect($redirect_page);}
		
		$ReturnString .= "<style type='text/css'>";
		$ReturnString .= $Custom_CSS;
		$ReturnString .= "</style>";
		
		$ReturnString .= "<div class='feup-information-div'>";
		$ReturnString .= __("You have been successfully logged out." , "EWD_FEUP");
		$ReturnString .= "</div>";
		
		if ($no_message != "Yes") {return $ReturnString;}
}
add_shortcode("logout", "Insert_Logout");
