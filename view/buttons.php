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
	$user = explode('@',$_SESSION['user']['username']);
	$email = $_SESSION['user']['username'];
	$default = '';
	$size = 30;
	$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
	echo ' - ';
	
	if($grav_url){
		echo '<img src="'.$grav_url.'>" alt="'.$user[0].'" title="'.$user[0].'" />';
	}else{
		echo '<b>'.$user[0].'  </b> ';
	}
}

echo ' <span style="float: right;">';

include(ROOTPATH.'/view/search.php');
echo '</span>';
