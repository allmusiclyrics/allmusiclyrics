<?php

echo '<a href="?home" title="TV shows music">Home</a> - ';
echo '<a href="?p=allshows" title="Browse title credit Songs list sound track">TV shows</a> - ';
echo '<a href="?p=allmovies" title="Browse movies song lists soundtracks">Movies</a> - ';
echo '<a href="?p=addshow" title="Add TV Show or Movie credit Song lists, soundtracks">Add TV Show or Movie</a> - ';

echo '<a href="?p=about">About</a> - ';
echo '<a href="?p=contact">Contact</a> - ';

if(isset($_SESSION['user'])){
	echo '<a href="?p=logout">Logout</a>';
}else{
	echo '<a href="?p=login">Login</a> - ';
	echo '<a href="?p=signup" title="New! ability to subscribe to get emails from your show with songs when it airs!" >Sign up</a> ';
}


if(isset($_SESSION['user'])){
	$user =explode('@',$_SESSION['user']['username']);
	echo '&nbsp;&nbsp;&nbsp;&nbsp; <b>'.$user[0].'  </b> ';// &nbsp;&nbsp;&nbsp; <a href="?p=logout">Logout</a><br>';
	
	/* if($_SESSION['user']['songsadded']==0||date('m/d/Y',$_SESSION['user']['lastactive'])!=date('m/d/Y')){
		$getSong2=getSong2("where `eid`='".$_SESSION['user']['eid']."' and `deleted`=0");
		$n=0;
		foreach($getSong2 as $t)$n++;
		updateTable('employee',array('songsadded'=>$n,'lastactive'=>time()),'where `eid`='.$_SESSION['user']['eid']);
		$_SESSION['user'] = userInfo($_COOKIE['username']);		
	}else
		$n = $_SESSION['user']['songsadded'];
	
	if($n==0)echo 'You have not added any songs yet.';
	else echo 'You have added '.$n.' songs so far.'; */
}

echo '<span style="float: right;">';
include(ROOTPATH.'/view/search.php');
echo '</span>';