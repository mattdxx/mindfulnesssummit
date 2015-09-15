<?php
function CheckLoginCookie() {
global $wpdb, $ewd_feup_user_table_name;

$LoginTime = get_option("EWD_FEUP_Login_Time");
$Salt = get_option("EWD_FEUP_Hash_Salt");
$CookieName = "EWD_FEUP_Login" . "%" . sha1(md5(get_site_url().$Salt)); 

if (isset($_COOKIE[$CookieName])) {$Cookie = $_COOKIE[$CookieName];}
else {$Cookie = null;}

$Username = substr($Cookie, 0, strpos($Cookie, "%"));
$TimeStamp = substr($Cookie, strpos($Cookie, "%")+1, strrpos($Cookie, "%")-strpos($Cookie, "%")); 
$SecCheck = substr($Cookie, strrpos($Cookie, "%")+1);
$UserAgent = $_SERVER['HTTP_USER_AGENT'];


if (isset($_COOKIE[$CookieName]) and $TimeStamp < (time() + $LoginTime*60)) {
	$UserDB = $wpdb->get_row($wpdb->prepare("SELECT User_Sessioncheck , User_Password FROM $ewd_feup_user_table_name WHERE Username ='%s'", $Username));
	$DBSeccheck = $UserDB->User_Sessioncheck;
		
	if (strcmp(sha1($SecCheck . $UserAgent), $DBSeccheck) === 0) {
		$User = array('Username' => $Username, 'User_Password' => $UserDB->User_Password);
		return $User;
	}
	else {
		return false;
	}
}

return false;
}
?>
