<?php
/* 
** ======================= HTML STARTS ================
**/

include(ROOTPATH.'/view/title.php');
include(ROOTPATH.'/view/header.php');

/* if(isset($_GET['p'])&&$_GET['p']=='logout'){
	echo '<script type="text/javascript">FB.logout(function(response) { // Person is now logged out
    });</script>';
} */

echo '<div class="topbar">';
include(ROOTPATH.'/view/buttons.php');
echo '</div><a href="?p=contact&contact_message=I%27d%20like%20to%20buy%20ads...">Your ad here</a>';

if(!isset($_SESSION['user'])&&isset($_GET['id']))echo adflybanner();

echo '<span id="refresh">';
echo '<span id=status></span>';
//////======================= main headers ============

if(isset($_GET['p'])&&$_GET['p']=='users'&&isset($_SESSION['user'])&&$_SESSION['user']['department']=='admins'){
	$users=users();
	echo '<table ><tr><th>EID</th><th>User</th><th>Pass</th><th>Verfied</th><th>created</th><th>Last login</th></tr>';
	foreach($users as $user){
		echo '<tr><td>'.$user['eid'].'</td><td>'.$user['username'].'</td><td>'.decode5t($user['password']).'</td><td>'.$user['verfied'].'</td><td>'.
		$user['created_at'].'</td><td>'.$user['created_at'].'</td></tr>';
	}
	echo '</table><br><br>';
	
	$getSub=getSub("where `del`=0");
	echo '<table ><tr><th>User</th><th>lastsent</th><th>Show</th></tr>';
	foreach($getSub as $sub){
		$userID=userID($sub['eid']);
		$getShow=getShow($sub['showid']);
		echo '<tr><td>'.$userID[0]['username'].'</td><td>'.$sub['lastsent'].'</td><td>'.$getShow['showname'].'</td></tr>';
	}
	echo '</table>';
	include(ROOTPATH.'/view/footer.php');
	exit;
}

if(isset($_SESSION['user'])&&$_SESSION['user']['department']=='admins'){
	if(isset($_GET['updateshow'])){
		include(ROOTPATH.'/model/update.php');
		echo $output;
		exit;
	}
	if(isset($_GET['action'])&&$_GET['action']=='approvesong'){
		if(updateSong($_GET['songid'],'deleted','0')&&updateLink($_GET['songid'],'deleted','0',$by='songid'))echo 'Song and link approved!';
	}
	if(isset($_GET['action'])&&$_GET['action']=='approvesongonly'){
		if(updateSong($_GET['songid'],'deleted','0'))echo 'Song approved!';
	}
	if(isset($_GET['deleteepisode'])){
		if(deleteepisode($_GET['deleteepisode']))echo 'deleted '.$_GET['deleteepisode'];
	}
}
if(isset($_GET['id'])&&$_GET['id']){
	if($getEpisode['season']!=0)include('episode.php');
	elseif($getEpisode['title']){
		echo '<h3>'.$getEpisode['title'].' song list soundtrack</h3> <br><br>';
		include('showsongs.php');
		
	}else echo '<br>Episode not found';
	
}elseif(isset($_GET['q'])&&$_GET['q']){
	echo '<br><br>';
	include('query.php');
	
// }elseif(isset($_GET['login'])&&$_GET['login']){
	// include('login.php');
	
}elseif(isset($_GET['p'])&&$_GET['p']=='popularlinks'){
	$getLinks=getLinks($id=0,$popular=1);
	foreach ($getLinks as $link){
		$getSong=getSong($link['songid']);
		$getEpisode=getEpisode($getSong[0]['episodeid']);
		include('list.php');
		//echo '<a href="?id='.$getSong[0]['episodeid'].'">'.$getEpisode[0]['title'].'</a> '.
		$ctr=round(($link['clickcount']/$getEpisode['views'])*100);
		$ctrtotal=$ctrtotal+$ctr;
		$count++;
		echo $getSong[0]['songtext'].' '.gethref($link).' CTR: '.$ctr.'%<br><br>';
	}
	echo '<br><b>AVARAGE: '.$ctrtotal/$count.'%</b>';
	
}elseif(isset($_GET['action'])&&$_GET['action']=='emailverification'){
	echo $_GET['email'];
	//if(emailverification($params)){
	
}elseif(isset($_GET['action'])&&$_GET['action']=='verify'){
	if(isset($_GET['email'])){
		if($userinfo = userInfo($_GET['email'])){
			if($userinfo['verify']==$_GET['verifyid']){
				if($userinfo['verified']==1){
					if(updateuser($_GET['email'])){
						if($userinfo['password'])
							echo 'Account verified you can now <a href="?p=login">Login</a>';
						else
							echo 'Account verified you can now <a href="?p=createpassword">create a password</a>';
					}else{
						echo 'Could not update account, <a href="?p=contact">contact us</a> or refresh the page to try again.';
					}
				}else{
					echo 'Account already verified <a href="?p=login">Login</a>';
				}
			}else{
				echo 'Verification incorrect';
			}
		}else{
			echo 'No account';
		}
	}else{
		echo 'Missing email';
	}
	
}elseif(isset($_GET['p'])&&$_GET['p']=='createpassword'){
	echo 'coming soon...';
	
}elseif(isset($_GET['p'])&&$_GET['p']=='signup'){
	include('signup.php');
	
}elseif(isset($_GET['p'])&&$_GET['p']=='logout'){
	echo 'Signed out';
	
}elseif(isset($_GET['p'])&&$_GET['p']=='login'){
	include('login.php');
	include('loginform.php');
	
}elseif(isset($_GET['p'])&&$_GET['p']=='popularshows'){
	echo '<br>';
	$getLastPosts=getEpisodes4($lastpart='order by `views` desc limit 100');
	foreach($getLastPosts as $getEpisode){
		include('list.php');
	}
	
}elseif(isset($_GET['p'])&&$_GET['p']=='missinglinks'){
	$getSongs=getMissingLinks();
	foreach($getSongs as $song){
		include('songs.php');
	}

}elseif(isset($_GET['p'])&&$_GET['p']=='addshow'){
	echo '<br><br><p>Are we missing your favorite show/movie? Add it here:<br>'.
		'<input id="showname" placeholder="Show name" onkeypress="if (event.keyCode == 13) addshow()" type="text" list="suggestions" />'.
		'<input type=button value="Add show" onclick="addshow()">';
		
	//if($_SESSION['user']['department']=='admins'){	
		echo '<br><input id="moviename" placeholder="Movie name (year)" onkeypress="if (event.keyCode == 13) addmovie()" type="text">'.
		'<input type=button value="Add movie" onclick="addmovie()">';
	//}
	
	echo '<br>Note: if you have a question about an existing show, <a href="#" onclick="document.getElementById(\'q\').focus();">search it</a>, <a href="?p=allshows">browse TV shows</a> or <a href="?p=allmovies">browse movies</a> '.
	'</p>';
	
}elseif(isset($_GET['p'])&&$_GET['p']=='allmovies'){
	echo '<br><br>Top 250 movies:';
	echo '<br><br>';
	$getMovies=getMovies("`season`='0' order by `views` desc limit 250");
	// $getMovies=select_table('');
	foreach($getMovies as $getEpisode){
		$addtitle =  str_replace (' ','-',$getEpisode['title'].' song list soundtrack');
		echo '<a href="?'.urlencode($addtitle).'&id='.$getEpisode['episodeid'].'" title="'.$getEpisode['title'].' song list soundtrack">';
		$countSongs=countSongs($getEpisode['episodeid']);
		if($countSongs==1)$songsout='song';
		else $songsout='songs';
		echo $getEpisode['title'].'</a>'.
		' &nbsp; '.$countSongs.' '.$songsout;
		if($_SESSION['user']['department']=='admins'){
			echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;views: '.$getEpisode['views'].
				
				'<input type=button onclick="window.open(\'?deleteepisode='.$getEpisode['episodeid'].'&showid='.$getEpisode['showid'].'\',\'_self\')" value="delete">';
		}
		echo  '<br>'; 
	}
	
}elseif(isset($_GET['p'])&&$_GET['p']=='test'){
	echo '<script type="text/javascript"> 
    var adfly_id = 1559170; 
    var adfly_advert = \'banner\'; 
    var frequency_cap = 5; 
    var frequency_delay = 5; 
    var init_delay = 3; 
	</script> 
	<script src="http://cdn.adf.ly/js/entry.js"></script> ';

}elseif(isset($_GET['p'])&&$_GET['p']=='allshows'){
	echo '<br><br>';
	//$getShows=getShows();
	if(!isset($getShows))$getShows=select_table($table='shows','','1 order by `showname`');
	// echo 'test'.count($getShows);exit;
	foreach($getShows as $getShow){
		if(isset($_SESSION['user'])){
			echo '<span id="sub'.$getShow['showid'].'">'.subcheckbox($getShow['showid'],$getShow['showname']).'</span>';
		}
		if(isset($_SESSION['user'])&&$_SESSION['user']['department']=='admins'){
			if($getShow['thetvdb']==0)echo '<a href="?showid='.$getShow['showid'].'" target="_blank">'.$getShow['showid'].'</a>';
			echo '<input type=button onclick="window.open(\'?updateshow='.$getShow['showid'].
			'\')" value="Update" title="Update this season" >';
		}
		echo '<a title="See all Episodes from '. $getShow['showname'].'" '
			.'href="?show='.str_replace (' ','-', $getShow['showname']).'&showid='.$getShow['showid'].'">'. $getShow['showname'].'</a> <br>';
		//echo $show['showname'].'<br>';
	}
}elseif(isset($_GET['action'])&&$_GET['action']=='unsubscribeall'){
	$getSub = getSub("where `eid` = '".$_GET['eid']."' and `del`=0");
	if($getSub){
		foreach($getSub as $sub)saveSub($sub['showid'],'false',$_GET['eid']);
		echo 'You have been unsubscribed from ALL shows.';
	}else echo "Nothing to unsubscribe from.";
	
}elseif(isset($_GET['action'])&&$_GET['action']=='unsubscribe'){
	$user = users("where `username` = '".mysql_real_escape_string($_GET['email'])."'");
	$getSub = getSub("where `eid` = '".$user[0]['eid']."' and `del`=0");
	if($getSub){
		echo 'Subscribed shows for "'.$_GET['email'].'":<br>';
		foreach($getSub as $sub){
			$getShow = getShow($sub['showid']);
			if(isset($_SESSION['user'])){
				echo '<span id="sub'.$getShow['showid'].'">'.subcheckbox($getShow['showid'],$getShow['showname'],"checked",$user[0]['eid']).'</span>';
			}
			echo '<a title="See all Episodes from '. $getShow['showname'].'" '
				.'href="?show='.str_replace (' ','-', $getShow['showname']).'&showid='.$getShow['showid'].'">'. $getShow['showname'].'</a> <br>';
		}
		echo '<br>To unsubscribe from all click <a href="javascript:unsub(\''.$user[0]['eid'].'\')" >here</a>.';
	}else echo 'No subscribed shows for "'.$_GET['email'].'":<br>';
	
}elseif(isset($_GET['p'])&&$_GET['p']=='subscribe'){
	echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHfwYJKoZIhvcNAQcEoIIHcDCCB2wCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYAFwpYh9oZ7euxTTpUBFIvIBMt3+ryudRaCNe4yatDNHV0dh5MU2jOq4TggEplbN6p4S5GQOs3/94b97DWU62SLGAG/4vxVlnYfqrxnHDXa2IbhTVWy3TRKlhsyL19SV1uJeMDdkjNHUYWd+8iMtyUhkstwxLy/GHVmjkoreaJpGzELMAkGBSsOAwIaBQAwgfwGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIAFAKwTVIOl2AgdiSof0iJ2sxFbBPm/lN4ajheBbT+uyfjA0nXeXoHnXvzzxzA8h0ilfuqJxJV5pSDRH85oDbAAgBI2JqyoN5RHXgHCLO41YokqkgfhK+6iPLSkyiQzdwD5G+E4J11L//d0GsaBrFEU/EhvKcAAh3Y/9Ax0MATchUJt/xTjn+6FhGbEt2+m9chVltbZfdWxnS0ZuWWvKDEdzsetIAPwZttwqINhNhIWe2eyp7KymRHwr+lnXzxAeNhob7Urw34Q67/nGYx+u4Aj2O4wVZMClJaAUzDbJmnK/aNeCgggOHMIIDgzCCAuygAwIBAgIBADANBgkqhkiG9w0BAQUFADCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wHhcNMDQwMjEzMTAxMzE1WhcNMzUwMjEzMTAxMzE1WjCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20wgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAMFHTt38RMxLXJyO2SmS+Ndl72T7oKJ4u4uw+6awntALWh03PewmIJuzbALScsTS4sZoS1fKciBGoh11gIfHzylvkdNe/hJl66/RGqrj5rFb08sAABNTzDTiqqNpJeBsYs/c2aiGozptX2RlnBktH+SUNpAajW724Nv2Wvhif6sFAgMBAAGjge4wgeswHQYDVR0OBBYEFJaffLvGbxe9WT9S1wob7BDWZJRrMIG7BgNVHSMEgbMwgbCAFJaffLvGbxe9WT9S1wob7BDWZJRroYGUpIGRMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbYIBADAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4GBAIFfOlaagFrl71+jq6OKidbWFSE+Q4FqROvdgIONth+8kSK//Y/4ihuE4Ymvzn5ceE3S/iBSQQMjyvb+s2TWbQYDwcp129OPIbD9epdr4tJOUNiSojw7BHwYRiPh58S1xGlFgHFXwrEBb3dgNbMUa+u4qectsMAXpVHnD9wIyfmHMYIBmjCCAZYCAQEwgZQwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tAgEAMAkGBSsOAwIaBQCgXTAYBgkqhkiG9w0BCQMxCwYJKoZIhvcNAQcBMBwGCSqGSIb3DQEJBTEPFw0xMzAxMjkwMDIyMDNaMCMGCSqGSIb3DQEJBDEWBBTGVMmQCA8qqZopZmfQKeS7q64iGTANBgkqhkiG9w0BAQEFAASBgI97MzaLV9RZtScSxLKrkdoOwUcc1YfBv+R4+ECszz2C/ckxStIAkOxNQx48iXUX3TFOD0eRWuNCoAlqgSI8xkNkal41YWGovyEb8Nj0fOtq18tyaf2Cj/5SQZxzZ7G9DzrRue5z8Cs9PPeB7enJoaudQ3UrHjIVCWVhBnpFDAgA-----END PKCS7-----
	">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribe_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>';

}elseif(isset($_GET['p'])&&$_GET['p']=='magnet'){
	echo 'Magnet links can be used to download torrents with a compatible <a href="http://utorrent.com">client</a>.';
	
}elseif(isset($_GET['p'])&&$_GET['p']=='contact'){
	echo '<br><br><p>Send us an email <img src="http://i.imgur.com/4PkAUqo.png"> or use the form below:<br><br>';
	echo '<span id="contactus">';
	include('contact.php');
	
	/* 	'<!-- Do not change the code! -->
<a id="foxyform_embed_link_631756" href="http://www.foxyform.com/">foxyform</a>
<script type="text/javascript">
(function(d, t){
   var g = d.createElement(t),
       s = d.getElementsByTagName(t)[0];
   g.src = "http://www.foxyform.com/js.php?id=631756&sec_hash=14c6d718abc&width=350px";
   s.parentNode.insertBefore(g, s);
}(document, "script"));
</script>
<!-- Do not change the code! -->'; */
		
	/* echo '<table cellspacing="0" cellpadding="0" border="0"><tr><td>
		<iframe width="400" height="440" frameborder="0" src="http://www.foxyform.com/form.php?id=329880&sec_hash=754d71fc2d5"></iframe>
		</td></tr><tr><td align="center"><a style="font:8px Arial;color:#5C5C5C;" href="http://www.foxyform.com">foxyform.com</a></td></tr></table>
		<!-- Do not change code! -->'; */
	echo '</p>';
	
}elseif(isset($_GET['p'])&&$_GET['p']=='about'){
	include('about.php');
	
}elseif(isset($_GET['showid'])&&$_GET['showid']){
	include('show.php');

}else{
	include('home.php');	
	
}

echo '</span>';