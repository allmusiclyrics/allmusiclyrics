<?php

/*
* =============================== ROUTER FILE ============================
*/

if($_SERVER['HTTP_HOST']=='i.'.bareURL()||$_SERVER['HTTP_HOST']=='www.'.bareURL()){
	$redirect = "http://".bareURL().$_SERVER['REQUEST_URI'];
	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: $redirect");
}

if(isset($_GET['p'])&&$_GET['p']=='lastupdated'){
	$where = '1 order by updated asc ';
	$show = select_table($table='shows',$fields=null,$where,$display=null);
	foreach($show as $match){
		if(!$updated)$updated = $match['updated'];
		if($match['updated']!=$updated){
			$count[$match['updated']]++;// .',';
		}
		
	}
	$counting=0;
	foreach($count as $key=>$value){
		echo $key .' - '.$value.' | ';
		$counting=$counting+$value;
	}
	echo ' COUNT: '.$counting.' | TOTAL:'.count($show);
	exit;
}
if(isset($_GET['p'])&&$_GET['p']=='hourlyupdate'){
	require ROOTPATH.'/controller/TVDB.php';
	echo hourlyupdate($_GET['id']);
	exit;
}

if(isset($_GET['p'])&&$_GET['p']=='news'){
	include(ROOTPATH.'/view/news.php');
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='fblogin'){
	$_SESSION['user']['u_email']=$_GET['email'];
	$_SESSION['user']['username']=$_GET['email'];
}
/* if(isset($_GET['action'])&&$_GET['action']=='q'){
	echo crawl_page("http://hobodave.com", 2);
}
 */
if(isset($_GET['action'])&&$_GET['action']=='verify'){
	echo '<br><br>';
	if(isset($_GET['email'])){
		if($userinfo = userInfo($_GET['email'])){
			if($userinfo['verify']==$_GET['verifyid']){
				if($userinfo['verfied']=='1'){
					if(updateuser($_GET['email'])){
						if($userinfo['password']){
							echo 'Account verified! you can now <a href="?p=login">Login</a>';
						}else{
							echo 'Account verified! you can now <a href="?p=createpassword">create a password</a>';
						}
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
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='subshow'){
	if(!filter_var($_GET['data'], FILTER_VALIDATE_EMAIL)){
		echo '<script>alert(\'Email not valid\');focuson(\'email'.$_GET['itemid'].'\');</script>'.
		'<font color=red>Email not valid</font>';
		$_GET['action']='subemail';
	}else{
		$params['email'] = $_GET['data'];
		$params['showid'] = $_GET['itemid'];
		if(!$userInfo=userInfo($_POST['email'])){
			echo createaccount($params);
		}else{
		
			$errors = 'Email already signed up';
			if($userInfo['verfied'])$errors .= '. Please check verification email to verify the account (check spam or junk folder/label)';
			else $errors .= ' and verified. You will receive emails for this show when it is updated.';
			echo $errors;
			saveSub($params['showid'],$checked='',$userInfo['eid']);
		}
		exit;
	}	
	
}
if(isset($_GET['action'])&&$_GET['action']=='requestepisode'){
	$getShow=getShow($_GET['value']);
	$onclick = '';
	if(emailrequest($getShow['showname'],$_GET['data'])){
		//updateEpisode($_GET['data'],$field='request',1,$idtype="episodeid");
		$id = $_GET['value'];
		$field = 'email';
		echo '<span id="'.$field.'area'.$id.'"><br><b>Request has been sent. </b>';
		$_GET['action']='subemail';
		
	}else{
		echo 'There was an error, please try again or report in <a href="?p=contact">contact us</a> page';
		exit;
	}
	
}
if(isset($_GET['action'])&&$_GET['action']=='subemail'){
	if(isset($_GET['itemid'])){
		$id = $_GET['itemid'];
		$value = $_GET['data'];
	}
	//elseif(!$_GET['itemid']&&$_GET['data'])$id = $_GET['data'];
	$field = 'email';
	$action = 'subshow';
	$onclick='actionitem(\''.$action.'\',\''.$id.'\',\''.$field.'\');';
	echo '<script>focuson(\'email'.$id.'\');</script><br>Enter your email if you like to be notified when the show is updated: '.
		makeTextfield($value,$placeholder='Email',$onkeypress=$onclick,$width='150',$span='',$title='',$field.$id,$onfocus='',$style='',$onchange='').
		makeButton($value='Subscribe',$onclick,$span='',$title='',$id='',$style='').
		'</span>';
	
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='savetotals'){
	$getEpisodes=getEpisodes4("where `episodeid`=".$_GET['itemid']);
	if($output=updateEpisode($getEpisodes[0]['showid'],'total',0,"showid"))
		echo 'Saved : '.$output;
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='savetotal'){
	//$_GET['itemid']
	if(updateEpisode($_GET['itemid'],'total',0))
		echo 'Saved : '.$_GET['data'];
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='savemagnet'){
	if(updateEpisode($_GET['itemid'],'magnet',$_GET['data']))
		echo 'Saved magnet: '.$_GET['data'];
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='savetunefind'){
	if(updateEpisode($_GET['itemid'],$_GET['field'],$_GET['data']))
		echo 'Saved tunefind: '.$_GET['data'];
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='saveord'){
	if(updateSong($_GET['itemid'],'ord',$_GET['data']))echo 'saved';
	if(!$_GET['id'])exit;
}
if(isset($_GET['action'])&&$_GET['action']=='deletesong'){
	if(updateSong($_GET['itemid'],'deleted',1))echo 'deleted';
	if(!isset($_GET['id']))exit;
}
if(isset($_GET['action'])&&$_GET['action']=='themesong'){
	$getSongs=getSong2("where `songid`=".$_GET['itemid']);
	$getEpisodes=getEpisodes4("where `episodeid`=".$getSongs[0]['episodeid']);
	if(updateSong($_GET['itemid'],'theme',$getEpisodes[0]['showid']))echo 'Theme song set';
	exit;
}/* 
if(isset($_GET['p'])&&$_GET['p']=='scrape'&&$_SESSION['user']['department']=='admins'){
	include('../simple_html_dom.php');
	// Create DOM from URL or file
	$html = file_get_html($_GET['url']);
	//echo $html;
	//echo $html->getElementById("div1")->childNodes(1)->childNodes(1)->childNodes(2)->getAttribute('id');
	// echo $html->find("ul li", 8).'<hr> ';
	// echo $html->find("ul", 3).'<hr><hr> ';
	// $html->find('ul', 0)->find('li', 0);
	// foreach($html->find('ul') as $element) 
		// echo $element . '<br>';
	$count=0;
	//echo '<textarea>';
	foreach($html->find('ul') as $ul) {
		if($count==3){
		foreach($ul->find('li') as $li) 
			echo $li->innertext;
		}
		$count++;
	}
	// foreach($html->find('ul li') as $element) {
		// if($count>=8){
		// echo $element->innertext ;
		// }
		// $count++;
	// }
	//echo '</textarea>';
	exit;
} 
*/
if(isset($_GET['p'])&&$_GET['p']=='sitemap'){
	header( 'Content-Type: application/xml' );
	//<?xml-stylesheet type="text/xsl" href="'.mainURL().'/xml-sitemap.xsl"
	$data = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
	//<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
	
	$getEpisodes=getEpisodes4("ORDER BY  `episodes`.`views` DESC limit 4000");
	foreach($getEpisodes as $getEpisode){
		//if(isset($getEpisode['showid']))
		$getShow=getShow($getEpisode['showid']);
		$seasonepisode2="S".sprintf("%02d",$getEpisode['season'])."E".sprintf("%02d",$getEpisode['episode']);
		$title2=$getShow['showname'].' '.$seasonepisode2.' song list soundtrack '.$getEpisode['title'];
		$addtitle2 =  str_replace (' ','-',$title2);
		$data .= '<url><loc>'.mainURL().'/?id='.$getEpisode['episodeid'].'&amp;'.urlencode($addtitle2).'</loc></url>'."\n";
		//echo mainURL().'/?'.urlencode($addtitle2).'&id='.$getEpisode['episodeid'].'<br>';
	}
	$data .= '</urlset> ';
	echo $data;
	// $file = fopen('sitemap.xml',"w");
	// fwrite($file,$data);
	// fclose($file);
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='sub'){
	if($_GET['checked']=='true')$checked='checked';
	if($_GET['checked']=='false')$checked=' ';
	if(saveSub($_GET['showid'],$_GET['checked'],$_GET['eid'])){
		echo subcheckbox($_GET['showid'],null,$checked,$_GET['eid']);
		//'<input type=checkbox onclick="sub(\''.$_GET['showid'].'\')" id=subbox'.$_GET['showid'].' '.$checked.'/>';//.$_GET['checked'];
	}else{
		echo 'error';	
	}
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='dellink'){	
	if(updateLink($_GET['linkid'],'deleted',1))
		echo 'deleted';
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='addlink'){	
	if($_GET['linktext'])
		echo adfly($_GET);
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='addsong'){
	if($_SESSION['user']['department']=='admins'){
		$_GET['songid']=saveSong($_GET['song'],$_GET['episodeid'],0,$_GET['desc']);
		if($_GET['linktext'])adfly($_GET);
		echo addsongform($_GET['episodeid']);
	}else{	
		$_GET['songid']=saveSong($_GET['song'],$_GET['episodeid'],$deleted=0,$_GET['desc']);
		if($_GET['linktext'])adfly($_GET,$deleted=0);
		echo emailsong($_GET);
	}
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='songclicked'){
	//if($_SESSION['user']['department']!='admins'){
		$getSong=getSong($_GET['itemid']);
		$data = $getSong[0]['clickcount']+1;
		updateSong($_GET['itemid'],'clickcount',$data);
	//}
}
if(isset($_GET['action'])&&$_GET['action']=='linkclicked'){
	//if($_SESSION['user']['department']!='admins'){
		$getLink=getLink($_GET['itemid']);
		$data = $getLink['clickcount']+1;
		updateLink($_GET['itemid'],'clickcount',$data);
	//}
}
if(isset($_GET['action'])&&$_GET['action']=='savefield'){
	if($_SESSION['user']['department']=='admins'){
		if(updateSong($songid=$_GET['itemid'],$field=$_GET['field'],$data=$_GET['data']))
			echo $_GET['data'];
		else
			echo 'error saving';
	}else{
		if($_GET['data']!=''){
			if(emailitem($_GET,$_GET['field'])&&updateSong($songid=$_GET['itemid'],$field=$_GET['field'],$data=$_GET['data']))
				echo $_GET['field'].': '.$_GET['data'].' --- Will be added once approved.';
		}else{
			echo '<span id="buttondescarea'.$song['songid'].'"><font onclick="changview(\'descarea'.$song['songid'].'\',\'desc'.$song['songid'].'\')" style="font-style:italic;cursor:pointer" title="Click to add description">Add description</font></span>'.
				'<span id="descarea'.$song['songid'].'" style="display:none">'.
				'<input type=text onkeypress="if (event.keyCode == 13) actionitem(\'savefield\',\''.$song['songid'].'\',\'desc\')" style="width:320px" placeholder="Add scene description" id=desc'.$song['songid'].'>'.
				'<input type=button value="Save" onclick="actionitem(\'savefield\',\''.$song['songid'].'\',\'desc\')">'.
				'</span>';
		}
	}
	if(!$_GET['id'])exit;
}
if(isset($_GET['action'])&&$_GET['action']=='addmovie'){
	if($_SESSION['user']['department']=='admins'){
		if($id=saveMovie($_GET))echo '<br><br>added: <a href="?id='.$id.'">'.$_GET['moviename'].'</a>Loading...<meta http-equiv="Refresh" content="0;URL=?id='.$id.'">';;
	}else{
		if(emailmovie($_GET))echo '<br><br>Submitted: '.$_GET['moviename'].'. Will be added once approved.';
	}
	exit;
}


///// ============= SET PATH

$addinclude = ROOTPATH.'/view/';
/// =========== BEGIN ROUTING ==========


if(isset($_GET['action'])&&$_GET['action']=='contactus'){
	//print_r($_GET);
	if(!filter_var($_GET['contact_email'], FILTER_VALIDATE_EMAIL)){
		echo "<script>alert('Email not valid');document.getElementById('email').focus();</script>";
		include($addinclude.'contact.php');
		// echo "<br><font color=red>".$_GET['email']." isn't valid</font>";
		exit;
	}
	$ip = getIP();
	$body = 'Message: '.$_GET['contact_message'].' <br><br>'.'IP: <a href="http://www.ip2location.com/'.$ip.'">'.$ip.'</a>';
	$headers = 'From: '.$_GET['contact_name'].' <'.$_GET['contact_email'].'>'. "\r\n" . "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$subject='New Contact Us '.$_GET['name'];
	if(mail(adminemail(), $subject, $body, $headers))echo '<br><font>Your message was sent, we will respond within 24 hours.</font><br>';
	else echo '<br><font color=red>Your message was NOT sent, please try email and let us know about this issue. Thank you.</font><br>';
	// include($addinclude.'contact.php');
	exit;
}
if(isset($_GET['action'])&&$_GET['action']=='updateshow'&&$_SESSION['user']['department']=='admins'){
	if(updateShow($id=$_GET['showid'],$field='thetvdb',$value=$_GET['thetvdb'])&&
	updateShow($id=$_GET['showid'],$field='lastseason',$value=$_GET['lastseason'])&&
	updateShow($id=$_GET['showid'],$field='airday',$value=$_GET['airday'])
	)
		echo '<br>Done'.print_r($_GET);
	else
		echo print_r($_GET).' error';
	$getShowID=getShow($_GET['showid']);
	// include('../view/show.php');
	// include(ROOTPATH.'/model/update.php');
	exit;
}
if(isset($_GET['showname'])){
	if($_SESSION['user']['department']=='admins'){
		if(saveShow($_GET))
			echo '<br><br>added: '.$_GET['showname'];
	}else{
		if(emailshow($_GET))
			echo '<br><br>Submitted: '.$_GET['showname'].'. Will be added once approved.';
	}
	exit;
}

if(isset($_POST['addsongs'])){
	addsongs($_POST);
	
}

if(isset($_GET['special'])&&$_GET['special']==1){
	if($_SESSION['user']['department']=='admins'){
		if($getShows=getShows()){
			foreach($getShows as $show){
				$getEpisodes=getEpisodes($show['showid'],$limit=1);
				foreach($getEpisodes as $ep){if($ep['date']&&!$ep['timestamp']){
					echo 'Show: '.$show['showid'].' Season:'.$ep['season'].' Ep'.$ep['episode'].' Date:'.$ep['date'];
					//if(updateEpisode($ep['episodeid'],'timestamp',strtotime($ep['date'])))echo 'done';
					echo '<br>';
				}}
			}
		}
		
		exit;
	}else echo 'Not admin';
}

if(isset($_GET['p'])&&$_GET['p']=='login'){
	include($addinclude.'login.php');
}

if(isset($_GET['p'])&&$_GET['p']=='logout'){
	setcookie("logtime", "", time()-3600);
	setcookie("username", "", time()-3600);
	//setcookie("expire", "", time()-3600);//, "/","");
	setcookie("expire", "", time()-3600, "/","");
	session_unset();
	session_destroy();
}


if(isset($_GET['p'])&&$_GET['p']=='jen'){
	include($addinclude.'jen.php');
	exit;
}

include($addinclude.'donation.php');

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
	if(!isset($getEpisode))$getEpisode = getEpisode($_GET['id']);
	//if(!isset($getShow))$getShow = getShow($_GET['id']);
	if($getEpisode['season']!=0)include($addinclude.'episode.php');
	elseif($getEpisode['title']){
		echo '<h3>'.$getEpisode['title'].' song list soundtrack</h3> <br><br>';
		include($addinclude.'showsongs.php');
		
	}else{
		//
		header("HTTP/1.0 404 Not Found");
		echo '<br><br><b>Oops... Movie or Episode is not found, please report it <a href="?p=contact">here</a></b>';
	}
}elseif(isset($_GET['q'])&&$_GET['q']){
	echo '<br><br>';
	include($addinclude.'query.php');
	
}elseif(isset($_GET['p'])&&$_GET['p']=='how-to-find-music-in-shows'){
	include($addinclude.'how.php');
	
}elseif(isset($_GET['p'])&&$_GET['p']=='popularlinks'){
	echo '<b>Top 50 most clicked songs:</b><br><br>';
	$getLinks=getLinks($id=0,$popular=1);
	foreach ($getLinks as $link){
		$getSong=getSong($link['songid']);
		$getEpisode=getEpisode($getSong[0]['episodeid']);
		$ctr=round(($link['clickcount']/$getEpisode['views'])*100);
		$ctrtotal=$ctrtotal+$ctr;
		$count++;
		echo $getSong[0]['songtext'].' '.gethref($link).' CTR: '.$ctr.'%<br><br>';
	}
	echo '<br><b>AVARAGE: '.$ctrtotal/$count.'%</b>';
	
}elseif(isset($_GET['action'])&&$_GET['action']=='emailverification'){
	echo $_GET['email'];
	
}elseif(isset($_GET['p'])&&$_GET['p']=='createpassword'){
	echo 'coming soon...';
	
}elseif(isset($_GET['p'])&&$_GET['p']=='signup'){
	include($addinclude.'signup.php');
	
}elseif(isset($_GET['p'])&&$_GET['p']=='logout'){
	echo 'Signed out';
	
}elseif(isset($_GET['p'])&&$_GET['p']=='login'){
	include($addinclude.'login.php');
	include($addinclude.'loginform.php');
	
}elseif(isset($_GET['p'])&&$_GET['p']=='popularshows'){
	echo '<br>';
	$getLastPosts=getEpisodes4($lastpart='order by `views` desc limit 100');
	foreach($getLastPosts as $getEpisode){
		include($addinclude.'list.php');
	}
	
}elseif(isset($_GET['p'])&&$_GET['p']=='missinglinks'){
	$getSongs=getMissingLinks();
	foreach($getSongs as $song){
		include($addinclude.'songs.php');
	}

}elseif(isset($_GET['p'])&&$_GET['p']=='addshow'){
	echo '<br><br><p>Are we missing your favorite show/movie? Add it here:<br>';
	echo '<input id="showname" placeholder="Show name" onkeypress="if (event.keyCode == 13) addshow()" type="text" list="suggestions" />';
	echo '<input type=button value="Add show" onclick="addshow()">';
		
	echo '<br><input id="moviename" placeholder="Movie name (year)" onkeypress="if (event.keyCode == 13) addmovie()" type="text">';
	echo '<input type=button value="Add movie" onclick="addmovie()">';
	
	echo '<br>Note: if you have a question about an existing show, <a href="#" onclick="document.getElementById(\'q\').focus();">search it</a>, <a href="?p=allshows">browse TV shows</a> or <a href="?p=allmovies">browse movies</a> '.
	'</p>';
	
}elseif(isset($_GET['p'])&&$_GET['p']=='allmovies'){
	echo '<br><br>Top 250 movies:';
	echo '<br><br>';
	$getMovies=getMovies("`season`='0' order by `views` desc limit 250");
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

}elseif(isset($_GET['p'])&&$_GET['p']=='allshows'){
	echo '<br><br>';
	if(!isset($getShows))$getShows=select_table($table='shows','','1 order by `showname`');
	foreach($getShows as $getShow){
		if(isset($_SESSION['user'])){
			echo '<span id="sub'.$getShow['showid'].'">'.subcheckbox($getShow['showid'],$getShow['showname']).'</span>';
		}
		if(isset($_SESSION['user'])&&$_SESSION['user']['department']=='admins'){
			if($getShow['thetvdb']==0)echo '<a href="?showid='.$getShow['showid'].'" target="_blank">'.$getShow['showid'].'</a>';
			echo '<input type=button onclick="window.open(\'?updateshow='.$getShow['showid'].
			'\')" value="Update" title="Update this season" >';
		}
		echo '<a title="See all Episodes from '. $getShow['showname'].'" ';
		echo 'href="?show='.str_replace (' ','-', $getShow['showname']).'&showid='.$getShow['showid'].'">'. $getShow['showname'].'</a> <br>';
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
	<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
	">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribe_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>';

}elseif(isset($_GET['p'])&&$_GET['p']=='magnet'){
	echo 'Magnet links can be used to download torrents with a compatible <a href="http://utorrent.com">client</a>.';
	
}elseif(isset($_GET['p'])&&$_GET['p']=='contact'){
	echo '<br><br><p>Send us an email use the form below:<br><br>';
	echo '<span id="contactus">';
	include($addinclude.'contact.php');
	echo '</p>';
	
}elseif(isset($_GET['p'])&&$_GET['p']=='about'){
	include($addinclude.'about.php');
	
}elseif(isset($_GET['p'])&&$_GET['p']=='donate'){
	include($addinclude.'donate.php');
	
}elseif(isset($_GET['showid'])&&$_GET['showid']){
	include($addinclude.'show.php');

}else{
	include($addinclude.'home.php');	
}
