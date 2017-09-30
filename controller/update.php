<?php

if(!$output)$output = '';

$showid=$_GET['updateshow'];
$getShow=getShow($showid);
updateShow($getShow['showid'],'updated',date('m/d/Y'));

if(!$seasonstart)$seasonstart=1;
if(!$total)$total=1;
// =========== TVDB API
// require_once('TVDB.php');
if(!PHPTVDB_API_KEY)define('PHPTVDB_API_KEY', 'EA6559FC1B0C7AEA');
require ROOTPATH.'/TVDB/TVDB.class.php';
require ROOTPATH.'/TVDB/TV_Show.class.php';
require ROOTPATH.'/TVDB/TV_Shows.class.php';
require ROOTPATH.'/TVDB/TV_Episode.class.php';
// ====================

if(!$getShow['thetvdb']){
	$output .= 'Searching: '.$getShow['showname']. '<br>TheTVDB: ';
	$shows = TV_Shows::search(str_replace('and','',$getShow['showname']));
	foreach($shows as $show){
		$output .= $show->seriesName;
		$output .= ' ';
		$output .= ' <a href="?savetdb='.$show->id.'&show='.$showid.'">'.$show->id.'</a><br>';
	}
	
}else{
	$output .= 'Searching: '.$getShow['thetvdb']. ' ('.$getShow['showname']. ') ';//<br>TheTVDB: ';
	$show = TV_Shows::findById($getShow['thetvdb']);
	// $show = TV_Shows::search(str_replace('and','',$getShow['showname']));
}
$output .= '<br>';

if(!$getEpisodes=getEpisodes($showid))$output .= '<a href="http://thetvdb.com/?tab=series&id='.$show->id.'&lid=7" target="_blank">check on thetvdb.com</a>';

//print_r($show);exit;

if($show){
	for ($i = $seasonstart; $i <= ($getShow['lastseason']+1); $i++) {
		$ep['season']=$i;
		include(ROOTPATH.'/model/seasonupdate.php');
	}
	
}else{
	$output .= 'nothing in show';
}

$select = select_table('episodes',$fields=null," showid = ".$showid." order by `season` desc limit 1",$display=null);
updateTable('shows',$fields=array('lastseason'=>$select[0]['season']),"where showid=".$showid,$display=null);
