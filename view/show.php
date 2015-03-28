<br><br>Episodes for <?php 
echo $getShowID['showname'].':<br><br>';
if($_SESSION['user']['department']=='admins'){
	if($getShowID['thetvdb']==0)echo '<a href="http://thetvdb.com/?string='.$getShowID['showname'].'&searchseriesid=&tab=listseries&function=Search" target="_blank" onclick="document.getElementById(\'thetvdb\').focus()" >TheTVDB</a>';
	else echo 'TheTVDB';
	
	echo ': <input type=text value="'.$getShowID['thetvdb'].'" title="TheTVDB" id=thetvdb onkeypress="if (event.keyCode == 13) updateshow(\''.$_GET['showid'].'\')">';
	echo 'Last Season:<input type=text value="'.$getShowID['lastseason'].'" id=lastseason onkeypress="if (event.keyCode == 13) updateshow(\''.$_GET['showid'].'\')">';
	//echo 'AirDay:<input type=text value="'.$getShowID['airday'].'" id=airday onkeypress="if (event.keyCode == 13) updateshow(\''.$_GET['showid'].'\')">';
	echo '<input type=button onclick="updateshow(\''.$_GET['showid'].'\')" value=Save><br><br>';
	echo '<a href="?updateshow='.$getShowID['showid'].'" target="_blank">Update All</a><br>';
}
if($getShowID['lastseason']){
	for ($i = 1; $i <= $getShowID['lastseason']; $i++) {
		echo '<br>';
		if(isset($_SESSION['user'])&&$_SESSION['user']['department']=='admins')
			echo '<a href="?updateshow='.$getShowID['showid'].'&season='.$i.'" target="_blank">Update Season '.$i.'</a> - ';
		
		if(isset($getShowID))echo '<h4><a href="?show='.str_replace ($search,'-', $getShowID['showname']).'&showid='.$getShowID['showid'].'#season'.$i.'" name="season'.$i.'" id="season'.$i.'">Season '.$i.'</a>:</h4><br>';
		$getEpisode=getEpisodes($getShowID['showid']);
		foreach($getEpisode as $getEpisode){
			if($_SESSION){
				if($getEpisode['season']==$i)
					include('list.php');
			}else{
				if($getEpisode['season']==$i&&$getEpisode['timestamp']<time())
					include('list.php');
			}
		}
	}
}