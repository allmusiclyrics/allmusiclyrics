<?php

if($getLastPosts){
	foreach($getLastPosts as $getEpisode){
		if($getEpisode['date']==date('m/d/Y')){
			$getShow=getShow($getEpisode['showid']);
			// if($_SESSION['user']['department']=='admins'){
			// if($getShow['updated']!=date('m/d/Y')){
				// if($getShow['thetvdb']!=0)
					// updateShowEpisodes($getShow);
			// }
			if(!isset($today))
				echo '<br><br>Today '.date('l F j, Y').':<br>';
			
			$today=1;
			
			include('list.php');
		}
	}
	//$getShow=null;
	foreach($getLastPosts as $getEpisode){
		if($getEpisode['date']==date('m/d/Y',strtotime('yesterday'))){
			if(!isset($yesterday))
				echo '<br>Yesterday '.date('l F j, Y',strtotime('yesterday')).':<br>';
			
			$yesterday=1;
			
			include('list.php');
			
		}
	}
	for ($i = 2; $i <= 7; $i++) {
		
		foreach($getLastPosts as $getEpisode){
			if($getEpisode['date']==date('m/d/Y',strtotime('-'.$i.' days'))){
				
				if(!isset($day[$i]))
					echo '<br>'.date('l F j, Y',strtotime('-'.$i.' days')).':<br>';
				
				$day[$i]=1;
				
				include('list.php');
			}
		}
	}
}

echo '<br><br>Movies:<br>';
$getMovies=getMovies($where="`season`='0' order by `timestamp` desc limit 20");
foreach($getMovies as $getEpisode){
	$addtitle =  str_replace (' ','-',$getEpisode['title'].' song list soundtrack');
	echo '<a href="?'.urlencode($addtitle).'&id='.$getEpisode['episodeid'].'" title="'.$getEpisode['title'].' song list soundtrack">';
	$countSongs=countSongs($getEpisode['episodeid']);
	if($countSongs==1)$songsout='song';
	else $songsout='songs';
	echo $getEpisode['title'].'</a>'.
	' &nbsp; '.$countSongs.' '.$songsout;
	if(isset($_SESSION['user'])&&$_SESSION['user']['department']=='admins'){
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;views: '.$getEpisode['views'].
			
			'<input type=button onclick="window.open(\'?deleteepisode='.$getEpisode['episodeid'].'&showid='.$getEpisode['showid'].'\',\'_self\')" value="delete">';
	}
	echo  '<br>'; 
}
	