<?php
function CreateLoginCookie($Username, $Password) {
$LoginTime = get_option("EWD_FEUP_Login_Time");
$Salt = get_option("EWD_FEUP_Hash_Salt");

//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

$CookieName = urlencode("EWD_FEUP_Login" . "%" . sha1(md5(get_site_url().$Salt))); 
$CookieValue = $Username . "%" . time() . "%" . md5($_SERVER['REMOTE_ADDR'].$Salt);
$ExpirySecond = time() + (1+$LoginTime)*60;

if (setcookie($CookieName, $CookieValue, $ExpirySecond, '/')) {return true;}
else {return false;}

/*if (setcookie($CookieName, $CookieValue, $ExpirySecond, '/', $_SERVER["HTTP_HOST"])) {echo "Cookie Set<br>";}
else {echo "Cookie Not Set<br>";}

echo "Cookie Name: " . $CookieName . "<br>";
echo "Cookie Value: " . $CookieValue . "<br>";
echo "Login Time: " . $LoginTime . "<br>";
echo "Expiry Second: " . $ExpirySecond . "<br>";
echo "Domain: " . $_SERVER["HTTP_HOST"] . "<br>";

return true;*/
}
?>
