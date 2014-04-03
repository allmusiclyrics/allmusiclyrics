<?php

	//: <input type=text onclick="this.field.select()" value="?id='.$_GET['id'].'">';
	echo '<br><br>';
	if($getEpisode['total']){
		$prev=getEpisodes4("where `showid`=".$getEpisode['showid']." and `total`=".($getEpisode['total']-1));
		$next=getEpisodes4("where `showid`=".$getEpisode['showid']." and `total`=".($getEpisode['total']+1));
	}else{
		$prev=getEpisodes3($getEpisode['showid'],$limit=0,($getEpisode['episode']-1),$getEpisode['season']);
		$next=getEpisodes3($getEpisode['showid'],$limit=0,($getEpisode['episode']+1),$getEpisode['season']);
	}
	if($prev){
		$prev=$prev[0];
		$seasonepisode2="S".sprintf("%02d",$prev['season'])."E".sprintf("%02d",$prev['episode']);
		$title2=$getShow['showname'].' '.$seasonepisode2.' song list soundtrack '.$prev['title'];
		$addtitle2 =  str_replace ($search,'-',$title2);
		echo '<a href="?id='.$prev['episodeid'].'&'.urlencode($addtitle2).'" title="'.$title2.'"><< Previous Episode</a> ';
	}
	echo ' | ';
	if($next){		
		$prev=$next[0];
		$seasonepisode2="S".sprintf("%02d",$prev['season'])."E".sprintf("%02d",$prev['episode']);
		$title2=$getShow['showname'].' '.$seasonepisode2.' song list soundtrack '.$prev['title'];
		$addtitle2 =  str_replace ($search,'-',$title2);
		echo ' <a href="?id='.$prev['episodeid'].'&'.urlencode($addtitle2).'" title="'.$title2.'">Next Episode >></a> ';
	}
	echo '<br>';
	
	$short=$getEpisode['season']."x".sprintf("%02d",$getEpisode['episode']);
	
	$long='<a title="Season '.$getEpisode['season'].' Episodes from '.$getShow['showname'].'" '.
		'href="?show='. str_replace ($search,'-',$getShow['showname']).'&showid='.$getEpisode['showid'].'#season'.$getEpisode['season'].'">Season '.
		$getEpisode['season'].'</a> Episode '.$getEpisode['episode'].' ';
	
	if($getEpisode['total'])$total=' or Episode #'.$getEpisode['total'].' counting from the beginning ';
	
	if(isset($_SESSION['user'])&&$_SESSION['user']['department']=='admins'){
		echo ' <input type=button onclick="window.open(\'?updateshow='.$getEpisode['showid'].'&season='.$getEpisode['season'].
		'\')" value="Update" title="Update this season" >'.
		'&nbsp;&nbsp;&nbsp;'.
		'<input type=button onclick="window.open(\'?deleteepisode='.$getEpisode['episodeid'].'&showid='.$getEpisode['showid'].'\',\'_self\')" value="delete">';
	}
	if(isset($_SESSION['user'])){
		echo '<span id="sub'.$getEpisode['showid'].'">'.subcheckbox($getEpisode['showid'],$getShow['showname']).'</span>';
	}
	echo ' <a title="See all Episodes from '. $getShow['showname'].'" '.
		'href="?show='.str_replace ($search,'-', $getShow['showname']).'&showid='.$getEpisode['showid'].'">'. $getShow['showname'].'</a> '.
		$seasonepisode.' '.$long.$total.' ('.$short.') songs list sound track called "'.$getEpisode['title'].'"<br>';
	if ($getEpisode['date']){
		if(time()>$getEpisode['timestamp'])
			echo 'Aired: ';
		else 
			echo 'Will Air: ';
		echo date('l F j, Y',strtotime($getEpisode['date']));
	}
	if($getEpisode['magnet'])echo '<br><br><a href="'.$getEpisode['magnet'].'">Magnet</a> (<a href="?p=magnet" title="find out more about magnet links">?</a>)';
	echo '<br><br>';
	if($_SESSION['user']['department']=='admins'){
		$action = 'actionitem(\'savetunefind\',\''.$getEpisode['episodeid'].'\',\'tunefind\')';
		$action2 = 'actionitem(\'savetotal\',\''.$getEpisode['episodeid'].'\',\'tunefind\')';
		$action3 = 'actionitem(\'savetotals\',\''.$getEpisode['episodeid'].'\',\'tunefind\')';
		$action4 = 'actionitem(\'savemagnet\',\''.$getEpisode['episodeid'].'\',\'tunefind\')';
		$scrape='';$tunefind='';$tunefindadd='';
		if($getEpisode['tunefind']!=0){
			$scrape='?p=scrape&url=';
			$tunefindadd = '/'.$getEpisode['tunefind'];
			$tunefind = $getEpisode['tunefind'];
		}
		echo '<span id=tunefindarea'.$getEpisode['episodeid'].'>';
		//'<input type=hidden value=0 id=total'.$getEpisode['episodeid'].' > '.
		if(!$getEpisode['magnet'])echo '<input type=text value="'.$tunefind.'" id=tunefind'.$getEpisode['episodeid'].' onkeypress="if (event.keyCode == 13) '.$action.
		'" placeholder=text >';
		//'<input type=button value="Save tunefind" onclick="'.$action.'">'.
		if(!$getEpisode['magnet'])echo '<input type=button value="Save magnet" onclick="'.$action4.'">';
		echo '<input type=button value="Del All Totals" onclick="'.$action3.'">';
		echo '<a href="'.$scrape.'http://www.tunefind.com/show/'.str_replace (' ','-', $getShow['showname']).'/season-'.$getEpisode['season'].$tunefindadd.'" target="_blank" onclick="document.getElementById(\'songs\').focus();" >TuneFind</a>';
		echo '</span><br><br>';
		
	}
	include('showsongs.php');