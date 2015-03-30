<?php

session_start();

define('ROOTPATH', __DIR__);

include('controller/db.php');
include('controller/conf.php');
include('controller/func.php');
include('controller/core.php');


$strQueryTime = 'Page took %01.4f sec to load';
$querytime_before = querytime_before();

if (isset($_COOKIE['expire'])){
	if(!isset($_SESSION['user'])){
		$_SESSION['user'] = userInfo($_COOKIE['username']);
	}
}
$show = select_table('shows',null,'1 order by lastupdated desc limit 1');
if($show[0]['lastupdated']<=strtotime('-30 minutes')){
	require ROOTPATH.'/controller/TVDB.php';
	hourlyupdate($show);
}
include('controller/router.php');

include('view/html.php');

include('view/footer.php');

