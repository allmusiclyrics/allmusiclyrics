<?php


if($getEpisode['season']!=0){
	$seasonepisode="S".sprintf("%02d",$getEpisode['season'])."E".sprintf("%02d",$getEpisode['episode']);
	$short='S'.sprintf("%02d",$getEpisode['season'])."E".sprintf("%02d",$getEpisode['episode']);
	$long='Season '.$getEpisode['season'].' Episode '.$getEpisode['episode'];
	if(!isset($getShow))$getShow=getShow($getEpisode['showid']);
	$addtitle =  str_replace ($search,'-',$getShow['showname'].' '.$seasonepisode.' song list soundtrack '.$getEpisode['title']);
	if(isset($_SESSION['user'])){
		echo '<span id="sub'.$getEpisode['showid'].'">'.subcheckbox($getEpisode['showid'],$getShow['showname']).'</span>';
	}
	if(isset($_SESSION['user'])&&$_SESSION['user']['department']=='admins'){
		if($getShow['thetvdb']==0)echo '<a href="?showid='.$getShow['showid'].'" target="_blank">'.$getShow['showid'].'</a>';
		echo ' <input type=button onclick="window.open(\'?updateshow='.$getEpisode['showid'].'&season='.$getEpisode['season'].
		'\')" value="Update" title="Update this season" >'.
		'&nbsp;&nbsp;&nbsp;'.
		'<input type=button onclick="window.open(\'?deleteepisode='.$getEpisode['episodeid'].'&showid='.$getEpisode['showid'].'\',\'_self\')" value="delete">'.
		'&nbsp;&nbsp;&nbsp;';
	}
	
	$showurl = '?id='.$getEpisode['episodeid'].'&'.urlencode($addtitle);
	echo '<a href="'.$showurl.'" title="'.$getShow['showname'].' '.$long.' ';	
	
	if(time()>$getEpisode['timestamp'])echo 'Aired: ';
	else echo 'Will Air: ';
	
	echo date('l F j, Y',strtotime($getEpisode['date'])).'">';
	echo $getShow['showname'].' '.$short.' ';
	$countSongs=countSongs($getEpisode['episodeid']);
	
	if($countSongs==1)$songsout='song';
	else $songsout='songs';
	
	echo $getEpisode['title'].'</a>&nbsp; '.$countSongs.' '.$songsout;
	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; views: '.$getEpisode['views'];
	echo  '<br>'; 
	
}else{
	$addtitle =  str_replace ($search,'-',$getEpisode['title'].' song list soundtrack');
	$showurl='?id='.$getEpisode['episodeid'].'&'.urlencode($addtitle);
	echo '<a href="'.$showurl.'" title="'.$getEpisode['title'].' song list soundtrack">';
	
	$countSongs=countSongs($getEpisode['episodeid']);
	
	if($countSongs==1)$songsout='song';
	else $songsout='songs';
	
	echo $getEpisode['title'].'</a> &nbsp; '.$countSongs.' '.$songsout;
	if($_SESSION['user']['department']=='admins'){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;views: '.$getEpisode['views'].
		'<input type=button onclick="window.open(\'?deleteepisode='.$getEpisode['episodeid'].'&showid='.$getEpisode['showid'].'\',\'_self\')" value="delete">';
	}
	echo  '<br>'; 
}
$getShow=null;
