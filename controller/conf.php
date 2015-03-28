<?php

/*
** =========== CONFIG FILE ===========
**/
 

// to indicate that this func file has been run, value.
$func=1;

//===== SET TIMEZONE
date_default_timezone_set('America/Winnipeg');

/*
** ========= STATIC FUNCTIONS - value store ============
**/
 
// main page title
function sitename(){return 'AllMusicLyrics.info';}

// main page and bare url
function mainURL(){return 'http://allmusiclyrics.info';}
function bareURL(){return 'allmusiclyrics.info';}

// admin email
function adminemail(){return 'boris.plotkin@gmail.com';}

// contact email (reply to)
function contactemail(){return 'contact@allmusiclyrics.info';}

// adf.ly key and ID
function adflykey(){return '8ab8b55639fc7ce52b53c4cb3f1305b8';}
function adflyuid(){return '1559170';}


// if admin, show more errors and focus on specific element
if($_SESSION['user']['department']=='admins'){
	error_reporting(-1); 
	if(!$_POST)$onload='onload="document.getElementById(\'songs\').focus();"';
	
}else{
	$onload='';
}
