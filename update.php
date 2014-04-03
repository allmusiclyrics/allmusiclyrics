<?php

$showid=$_GET['updateshow'];

$getShow=getShow($showid);

updateShow($getShow['showid'],'updated',date('m/d/Y'));

//require 'TVDB.php';
if(!$getShow['thetvdb']){
	echo 'Searching: '.$getShow['showname']. '<br>TheTVDB: ';
	$shows = TV_Shows::search(str_replace('and','',$getShow['showname']));
	foreach($shows as $show){
		echo $show->seriesName;echo ' ';
		echo ' <a href="?savetdb='.$show->id.'&show='.$showid.'">'.$show->id.'</a><br>';
	}
}else{
	echo 'Searching: '.$getShow['thetvdb']. ' <br>TheTVDB: ';
	$show = TV_Shows::findById($getShow['thetvdb']);
}
echo '<br>';


if(!$getEpisodes=getEpisodes($showid))
	echo '<a href="http://thetvdb.com/?tab=series&id='.$show->id.'&lid=7" target="_blank">check on thetvdb.com</a>';

	
// if(!$_GET['season']){
	for ($i = 1; $i <= $getShow['lastseason']; $i++) {
		$ep['season']=$i;
		include('seasonupdate.php');
	}
// }else{
	// $ep['season']=$_GET['season'];
	// include('seasonupdate.php');
// }
