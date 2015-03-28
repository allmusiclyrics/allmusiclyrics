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
if(isset($_GET['p'])&&$_GET['p']=='test'){
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
} */
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
	if(!$_GET['id'])exit;
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
if(isset($_GET['action'])&&$_GET['action']=='contactus'){
	//print_r($_GET);
	if(!filter_var($_GET['contact_email'], FILTER_VALIDATE_EMAIL)){
		echo "<script>alert('Email not valid');document.getElementById('email').focus();</script>";
		include(ROOTPATH.'/view/contact.php');
		// echo "<br><font color=red>".$_GET['email']." isn't valid</font>";
		exit;
	}
	$ip = getIP();
	$body = 'Message: '.$_GET['contact_message'].' <br><br>'.'IP: <a href="http://www.ip2location.com/'.$ip.'">'.$ip.'</a>';
	$headers = 'From: '.$_GET['contact_name'].' <'.$_GET['contact_email'].'>'. "\r\n" . "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$subject='New Contact Us '.$_GET['name'];
	if(mail(adminemail(), $subject, $body, $headers))echo '<br><font>Your message was sent, we will respond within 24 hours.</font><br>';
	else echo '<br><font color=red>Your message was NOT sent, please try email and let us know about this issue. Thank you.</font><br>';
	// include(ROOTPATH.'/view/contact.php');
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
	include(ROOTPATH.'/view/login.php');
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
	include(ROOTPATH.'/view/jen.php');
	exit;
}


