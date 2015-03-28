<?php

// ====== DATABASE INFO ========

define('HOST','localhost');
define('USERNAME','root');
define('DB_NAME','aml');
define('PASSWORD','');


/**
* Function Description: Database Connection
*
* @return variable $connection
*/
function db_connect($i=0) {
	if($i==1){
		$connection = mysql_connect(HOST,USERNAME,PASSWORD);
	}else{
		$connection = mysql_connect(HOST,USERNAME,PASSWORD);
	}
	if (!$connection){die('Error: '.mysql_error());}	
	if($i==1){
		if (!mysql_select_db(DB_NAME2)){die('Error: '. mysql_error());}
	}else{
		if (!mysql_select_db(DB_NAME)){die('Error: '. mysql_error());}
	}
	return $connection;	
}

/**
* Function Description: Pass the value from database into an array
* @Param: $result
* @return: $result_array
*/
function db_result($result){
	$result_array = array();   
		for ($i=0; @$row = mysql_fetch_array($result) ; $i++){
		   $result_array[$i] = $row;
		}		
	return $result_array;
}

// === CHECK FOR CONNECTION ISSUE =======
if(!db_connect()){
	echo 'Down for maintenance. Be back shortly.';
	exit;
}

