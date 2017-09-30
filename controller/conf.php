<?php

/*
** ========== CONFIG FILE ===========
**/
 

// to indicate that this func file has been run, value.
$func=1;

// ========== BITCOIN ADDRESS =================
$btc = 'BTCaddress';

// ========== SET TIMEZONE =====================
date_default_timezone_set('America/Winnipeg');

/*
** ========= STATIC FUNCTIONS - value store ============
**/
 
// main page title
function sitename(){return 'DOMAIN.com';}

// main page and bare url
function mainURL(){return 'http://DOMAIN.com';}
function bareURL(){return 'DOMAIN.com';}

// admin email
function adminemail(){return 'EMAIL';}

// adf.ly key and ID
function adflykey(){return '';}
function adflyuid(){return '';}
function shortestid(){return '';}

// if admin, show more errors and focus on specific element
if($_SESSION['user']['department']=='admins'){
	error_reporting(-1); 
	if(!$_POST)$onload='onload="document.getElementById(\'songs\').focus();"';
	
}else{
	$onload='';
}


$search = array(' ','(',')','@','!',"'",',','?','*','&');