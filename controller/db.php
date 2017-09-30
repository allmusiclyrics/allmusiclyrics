<?php

// ====== DATABASE INFO ========

define('HOST','p:');
define('USERNAME','');
define('DB_NAME','');
define('PASSWORD','');

function db_connect($i=0) {
	$connection = mysqli_connect(HOST,USERNAME,PASSWORD);
	if (!$connection)die('Error: '.mysqli_error());
	if (!mysqli_select_db($connection,DB_NAME))die('Error: '. mysqli_error());
	return $connection;	
}
function db_result($result){
	$result_array = array();   
	for ($i=0; @$row = mysqli_fetch_array($result) ; $i++)$result_array[$i] = $row;		
	return $result_array;
}

// === CHECK FOR CONNECTION ISSUE =======
if(!db_connect()){
	echo 'Down for maintenance. Be back shortly.';
	exit;
}

