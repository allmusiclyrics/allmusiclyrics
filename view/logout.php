<?php
include('func.php');
if ($_SESSION['user']['fullname'])
	$fullname = $_SESSION['user']['fullname'].", "; 
if ($_COOKIE['expire'] == "no")
	$rememberme = "yes";
setcookie("username", "", time()-3600*25);
setcookie("expire", "", time()-3600*25, "/","");
$_SESSION['button'] = "";
$_SESSION['logout'] = "$fullname You have logged out";
if ($rememberme == "yes")
	$_SESSION['rememberme'] = "yes";
header('Location: login.php');

?>