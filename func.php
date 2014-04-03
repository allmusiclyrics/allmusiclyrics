<?php

$func=1;
date_default_timezone_set('America/Winnipeg');
if($_SESSION['user']['department']=='admins'){
	error_reporting(E_ERROR | E_PARSE | E_WARNING ); 
	if(!$_POST)$onload='onload="document.getElementById(\'songs\').focus();"';
}else $onload='';

function mainURL(){return 'http://allmusiclyrics.info';}

if(date('G')==21)rundailytask();

function rundailytask(){
	$getSub=getSub("where `lastsent`!='".date('m/d/Y')."' and `del`='0'");
	$out='';
	foreach($getSub as $sub){
		$getEpisodes=getEpisodes4("where `showid`='".$sub['showid']."' and `date`='".date('m/d/Y',strtotime('-1 day'))."'");
		$out.= subtask($getEpisodes,$sub['subid'],$sub['eid']);
		$getEpisodes=null;
		//$getEpisodes=getEpisodes4("where `showid`='".$sub['showid']."' and `date`='".date('m/d/Y',strtotime('-2 days'))."'");
		//$out.= subtask($getEpisodes,$sub['subid'],$sub['eid']);
		$getEpisodes=null;
		$getEpisodes=getEpisodes4("where `showid`='".$sub['showid']."' and `date`='".date('m/d/Y')."'");
		//if($getEpisodes)$out .= $getEpisodes[0]['title'];
		$out.= subtask($getEpisodes,$sub['subid'],$sub['eid']);
		$getEpisodes=null;
	}
	return $out;
}

function subtask($getEpisodes=null,$subid,$eid){
	$out='';
	if($getEpisodes){
		foreach($getEpisodes as $getEpisode){
			if(countSongs($getEpisode['episodeid'])){
				if(emailsub($getEpisode,$eid))
					updateSub($getEpisode['showid'],"`lastsent`='".date('m/d/Y')."'",$eid);
			}else{
				updateSub($getEpisode['showid'],"`lastsent`='".date('m/d/Y')."'",$eid);
			}
		}
	}
	return $out;
}

function getServer($ip){
	if($ip=='10.177.16.8')return 2;
	elseif($ip=='10.177.0.86')return 1;
}
function adflybanner(){
	return '<script type="text/javascript"> 
    var adfly_id = ; 
    var adfly_advert = \'banner\'; 
    var frequency_cap = 5; 
    var frequency_delay = 25; 
    var init_delay = 30; 
	</script> 
	<script src="http://cdn.adf.ly/js/entry.js"></script> ';
}
function hosts(){
	return array(
	'youtube.com'=>'',
	'adf.ly'=>'',
	'musicline.de'=>'musicline',
	'allmusiclyrics.info'=>'Lyrics',
	'songmeanings.net'=>'Lyrics',
	'vimeo.com'=>'vimeo',
	'dailymotion.com'=>'dailymotion',
	'amazon.com'=>'amazon',
	'amazon.co.uk'=>'amazon',
	'sweetwaterandthesatisfaction.com'=>'sweetwaterandthesatisfaction',
	'reverbnation.com'=>'reverbnation',
	'apple.com'=>'itunes',
	'soundcloud.com'=>'soundcloud',
	'last.fm'=>'lastfm',
	'myspace.com'=>'myspace',
	'beatport.com'=>'beatport',
	'zimbio.com'=>'zimbio',
	'bandcamp.com'=>'bandcamp',
	'last.fm'=>'lastfm'
	);
}

function file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}
function getdomain($domainb)   { 
	$bits = explode('/', $domainb); 
	if ($bits[0]=='http:' || $bits[0]=='https:')  { 
		$domainb= $bits[2]; 
	} else { 
		$domainb= $bits[0]; 
	} 
	unset($bits); 
	$bits = explode('.', $domainb); 
	$idz=count($bits); 
	$idz-=3; 
	if (strlen($bits[($idz+2)])==2) { 
		$url=$bits[$idz].'.'.$bits[($idz+1)].'.'.$bits[($idz+2)]; 
	} else if (strlen($bits[($idz+2)])==0) { 
		$url=$bits[($idz)].'.'.$bits[($idz+1)]; 
	} else { 
		$url=$bits[($idz+1)].'.'.$bits[($idz+2)]; 
	} 
	return $url; 
} 
function adfly($link,$deleted=0){	
	$key='';
	$uid='';
	$fetchurl='http://api.adf.ly/api.php?key='.$key.'&uid='.$uid.'&advert_type=int&domain=adf.ly&url='.urlencode($link['linktext']);
	$parse_url=parse_url($link['linktext']);
	$hosts=hosts();
	
	$title=$hosts[getdomain($parse_url['host'])];
	if(function_exists (file_get_contents)){
	if($parse_url['host']!='adf.ly')
		$url=file_get_contents($fetchurl);
	else
		$url=$link['linktext'];
	}
	if(!$url)$url=$link['linktext'];
	
	$link['linkid']=saveLink($url,$link['songid'],$title,$link['linktext'],$deleted);
	if(!$deleted)
	return gethref($link,$title);
}
function linkform($song){
	$out='<a href="https://www.google.com/search?q='.urlencode($song['songtext']).'" target="_blank" onclick="document.getElementById(\'link'.$song['songid'].'\').focus()">Google</a>'.
		'<input type=text id="link'.$song['songid'].'" placeholder="Link" onkeypress="if (event.keyCode == 13) addLink(\''.$song['songid'].'\') ">'.
		'<input type=button value="add link" onclick="addLink(\''.$song['songid'].'\')">';
	return $out;
}

function gethref($link,$title=null,$action='linkclicked'){	
	if($title)$link['title']=$title;
	if(!$link['title']){
		$parse_url=parse_url($link['linktext']);
		if($parse_url['host']=='adf.ly'){
			$host='youtube';
		}else{
			$hosts=hosts();
			//$link['linktext']='http://adf.ly//'.$link['linktext'];
			$host=$hosts[$parse_url['host']];
		}
	}
	
	$out = '<span id="onelink'.$link['linkid'].'">';
	//$linktext=$link['linktext'];
	//$linktext=$link['real'];
	//if(isset($_SESSION['user']['department'])||!isset($_SESSION['user']['department'])||$_SESSION['user']['department']!='admins')
	$onmousedown='sendData(\''.$action.'\',\''.$link['linkid'].'\',\''.$link['clickcount'].'\');this.href=\''.$link['linktext'].'\'';
	$out .= ' <a href="'.$link['real'].'" id="thelink'.$link['linkid'].'" onmousedown="'.$onmousedown.'" target="_blank" >'.$link['title'].'</a> ';
	
	if(isset($_SESSION['user']['department'])&&$_SESSION['user']['department']=='admins'){
		if($link['real'])$out .= ' <a href="'.$link['real'].'" target="_blank" title="'.$link['linktext'].'">ORG</a> ';
		$out .= 'clicked '.$link['clickcount'].' <span style="cursor:pointer;color:red" onclick="delLink(\''.$link['linkid'].'\')" title="delete">X</span>';
	}
	$out .= '</span> ';
	
	return $out;
}
function addsongs($_POST){
	$_POST['songs']=str_replace("\r\n \r\n", "\r\n",$_POST['songs']);
	$_POST['songs']=preg_replace("/^\r\n/", "",$_POST['songs']);
	$songs = explode("\n", $_POST['songs']);$count=null;$return=null;
	if($songs){foreach ($songs as $song){
		$count++;
		$return.= $count.' '.$song;
		if($count%2){
			$songid=saveSong($song,$_POST['id']);
		}else{
			if($song!='add scene description')updateSong($songid,'desc',$song);
		}
		
	}}
	$return.=$_POST['id'].' <br><br>';
	return $return;
}
function updateSong($songid,$field,$data){
	db_connect();
	$query = sprintf("update `songs` set `$field`='%s' where `songid`='%s'",
	mysql_real_escape_string($data),
	mysql_real_escape_string($songid)
	);
	$result = mysql_query($query);
	return $result;
}
function updateLink($linkid,$field,$data,$by='linkid'){
	db_connect();
	$query = sprintf("update `links` set `$field`='%s' where `$by`='%s'",
	mysql_real_escape_string($data),
	mysql_real_escape_string($linkid)
	);
	$result = mysql_query($query);
	return $result;
}
function saveLink($link,$id,$title='',$real,$deleted=0){
	if($title)$add=", `title`='".$title."'";
	if($deleted)$add=", `deleted`='1'";
	$date=time();
	db_connect();
	$query = sprintf("insert into `links` set `linktext`='%s', `songid`='%s', `real`='$real', `dateadded`='$date' $add",
	mysql_real_escape_string($link),
	mysql_real_escape_string($id)
	);
	$result = mysql_query($query);
	return mysql_insert_id();
}
function saveSong($song,$id,$deleted=0,$desc=0){
	if($_SESSION['user']['eid'])$eid=$_SESSION['user']['eid'];
	else $eid=0;
	$date = time();
	$add=null;
	if($deleted)$add=", `deleted`=1";
	if($desc)$add.=", `desc`='".mysql_real_escape_string($desc)."'";
	db_connect();
	$query = sprintf("insert into `songs` set `songtext`='%s', `episodeid`='%s', `dateadded`='$date', `eid`='$eid' $add",
	mysql_real_escape_string($song),
	mysql_real_escape_string($id)
	);
	$result = mysql_query($query);
	return mysql_insert_id();
}
function saveMovie($values){
	db_connect();
	$query = "insert into episodes set `title`='".mysql_real_escape_string($values['moviename'])."'";
	$result = mysql_query($query);
	return $result;
}
function saveEpisode($_POST,$getShow){
	db_connect();
	$query = sprintf("insert into episodes set `title`='%s', `episode`='%s', `season`='%s', `date`='%s', `timestamp`='%s', `showid`='%s', `total`='%s'",
	mysql_real_escape_string($_POST['title']),
	mysql_real_escape_string($_POST['episode']),
	mysql_real_escape_string($_POST['season']),
	mysql_real_escape_string($_POST['date']),
	mysql_real_escape_string(strtotime($_POST['date'])),
	mysql_real_escape_string($_POST['showid']),
	mysql_real_escape_string($_POST['total'])
	);
	$result = mysql_query($query);
	//updateShow($getShow['showid'],'tobeposted',($getShow['tobeposted']+1));
	return $result;
}
function addsongform($id){
	return '<input type=text placeholder="Song name and artist" style="width:320px"  id="song" onkeypress="if (event.keyCode == 13) addsong()" >*'.
		//'<input type=text placeholder="Artist" id="artist" onkeypress="if (event.keyCode == 13) addsong()">'.
		'<br>'.
		'<input type=text placeholder="Describe scene" style="width:320px" id="desc" onkeypress="if (event.keyCode == 13) addsong()">'.
		//'<br><input type=text placeholder="Email (optional)" style="width:320px" id="email" onkeypress="if (event.keyCode == 13) addsong()">';
		'<br><input type=text placeholder="Link (example: http://www.youtube.com/watch?v=YdPml5QhMIA ) optional" style="width:320px" id="link" onkeypress="if (event.keyCode == 13) addsong()">'.
		'<input type="hidden" name="id" id="id" value="'.$id.'">'.
		'<input type=button onclick="addsong()" value="Add Song">';
		
}

function emailsong($_GET){	
	$body='Song: '.$_GET['song'].' Desc:'.$_GET['desc'].' <br>'.
	'Episode: <a href="'.mainURL().'/?id='.$_GET['episodeid'].'">'.$_GET['episodeid'].'</a><br><br>'.
	'Link: <a href="'.$_GET['linktext'].'">link</a><br><br>'.
	//'<a href="'.mainURL().'/?action=approvesongonly&songid='.$_GET['songid'].'&id='.$_GET['episodeid'].'">Approve song only</a><br><br>'.
	//'<a href="'.mainURL().'/?action=approvesong&songid='.$_GET['songid'].'&id='.$_GET['episodeid'].'">Approve song with link</a><br><br>'.
	'<a href="'.mainURL().'/?action=deletesong&itemid='.$_GET['songid'].'&id='.$_GET['episodeid'].'">DELETE</a><br><br>';
	
	if(isset($_SESSION['user']['username']))$email=$_SESSION['user']['username'];
	else $email='contact@allmusiclyrics.info';
	
	$headers = 'From: Allmusiclyrics service <'.$email.'>'. "\r\n" ;
	$headers .= "Bcc: boris.plotkin@gmail.com \r\n"; 
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	//echo $body;
	mail($to='boris.plotkin@gmail.com', $subject='Song added', $body, $headers);

	return 'Your song "'.$_GET['song'].'" was submitted and will be posted once approved.<br>Add another:<br>'.addsongform($_GET['episodeid']);
}

function emailverification($params){
	$verify = ''.mainURL().'/?action=verify&verifyid='.$params['verify'].'&email='.urlencode($params['email']);
	$body='Thank you for signing up to <a href="'.mainURL().'">AllMusicLyrics.info</a>,<br><br>'.
	'To verify your account click <a href="'.$verify.'">HERE</a> or copy and past this link to your browser: <br>'.$verify.'<br><br>'.
	'If you did not sign up for a new account please ignore this email or reply to let us know.<br><br>';

	$headers = 'From: Allmusiclyrics service <contact@allmusiclyrics.info>'. "\r\n" ;
	$headers .= "Bcc:  \r\n"; 
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	return $params.mail($params['email'], $subject='Verify your new account at AllMusicLyrics.info', $body, $headers);
}
//function emailsub($eid){
function emailsub($params,$eid){
	$getShow = getShow($params['showid']);
	$user = userID($eid);
	$usr =explode('@',$user[0]['username']);
	$body = 'Hello '.$usr[0].',<br><br>'.
	$getShow['showname'].' Season '.$params['season'].' Episode '.$params['episode'].' called "'.$params['title'].'" aired on: '.date('l F j, Y',strtotime($params['date'])).
	' <br> <a href="'.mainURL().'/?id='.$params['episodeid'].'">'.$getShow['showname'].' S'.$params['season'].'E'.$params['episode'].' '.$params['title'].
	'</a><br><br>If you cannot click the link above, copy and past this to your browser:<br>'.mainURL().'/?id='.$params['episodeid'].
	'<br><br><br>------<br>Thank you for visiting <a href="'.mainURL().'">AllMusicLyrics.info</a>.<br>To unsubscribe from this show or all shows click <a href="'.mainURL().'/?action=unsubscribe&email='.urlencode($user[0]['username']).'">here</a> ';
	// $body='test';
	$headers = 'From: Allmusiclyrics service <contact@allmusiclyrics.info>'. "\r\n" . "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= "Bcc:  \r\n"; 
	$subject='New episode for '.$getShow['showname'];
	return mail($user[0]['username'], $subject, $body, $headers);
}
function emailitem($_GET,$item){
	$getsong = getSong($_GET['itemid']);
	$body='Add '.$item.': '.$_GET['data'].' <br>'.
	'Song: '.$getsong[0]['songtext'].'<br>'.
	'Episode: <a href="'.mainURL().'/?id='.$getsong[0]['episodeid'].'">'.$getsong[0]['episodeid'].'</a><br><br><br>'.
	//'<a href="'.mainURL().'/?action=savefield&itemid='.$_GET['itemid'].'&data='.rawurlencode($_GET['data']).'&field='.$_GET['field'].'">ADD '.$item.'</a><br><br>'.
	'<a href="'.mainURL().'/?action=savefield&itemid='.$_GET['itemid'].'&data='.rawurlencode('add scene description').'&field='.$_GET['field'].'">DELETE </a><br><br>';

	if(isset($_SESSION['user']['username']))$email=$_SESSION['user']['username'];
	else $email='contact@allmusiclyrics.info';
	$headers = 'From: Allmusiclyrics service <'.$email.'>'. "\r\n" ;
	$headers .= "Bcc: boris.plotkin@gmail.com \r\n"; 
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	if(mail($to='', $subject=$item.' added', $body, $headers))
		return true;
	else
		return false;
}
function emailshow($_GET){
	$body='Add show: '.$_GET['showname'].' <br>
	ADD: <a href="'.mainURL().'/?showname='.$_GET['showname'].'">ADD SHOW</a><br><br>';

	if(isset($_SESSION['user']['username']))$email=$_SESSION['user']['username'];
	else $email='contact@allmusiclyrics.info';
	$headers = 'From: Allmusiclyrics service <'.$email.'>'. "\r\n" ;
	$headers .= "Bcc:  \r\n"; 
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	if(mail($to='', $subject='Show added', $body, $headers))
		return true;
	else
		return false;
}
function emailmovie($_GET){
	$body='Add movie: '.$_GET['moviename'].' <br>
	ADD: <a href="'.mainURL().'/?action=addmovie&moviename='.$_GET['moviename'].'">ADD MOVIE</a><br><br>';

	if(isset($_SESSION['user']['username']))$email=$_SESSION['user']['username'];
	else $email='contact@allmusiclyrics.info';
	$headers = 'From: Allmusiclyrics service <'.$email.'>'. "\r\n" ;
	$headers .= "Bcc:  \r\n"; 
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	if(mail($to='', $subject='Movie added', $body, $headers))
		return true;
	else
		return false;
}
function subcheckbox($showid,$showname=null,$checked=null,$eid=null){
	if(!$checked){
		if(getSub("where showid=".$showid." and eid=".$eid." and `del`=0")){
			$checked="checked";
			$subtitle="You are subscribed to receive an email when a new episode airs";
		}else{
			$checked="";
			$subtitle="Click to subscribe to ".$showname." to receive an email when new episodes are airing";
		}
	}else $subtitle="Toggle subscription";
	if(!$eid)
		return '<input type=checkbox onclick="sub(\''.$showid.'\')" id=subbox'.$showid.' '.$checked.' title="'.$subtitle.'"/>';
	else
		return '<input type=hidden id=eid value='.$eid.'>'.
			'<input type=checkbox onclick="sub2(\''.$showid.'\')" id=subbox'.$showid.' '.$checked.' title="'.$subtitle.'"/>';
}
function saveSub($showid,$checked='',$eid=null){
	if(!$eid)$eid = $_SESSION['user']['eid'];
	if($getSub=getSub("where showid=".$showid." and eid=".$eid)){
		if($checked=='false'){
			return updateSub($showid,"`del`='1'");
		}else{
			return updateSub($showid,"`del`='0'");
		}
	}else{
		return insertSub($showid);
	}
}
function updateSub($showid,$set,$eid=null){
	if(!$eid)$eid=$_SESSION['user']['eid'];
	db_connect();
	$query = sprintf("update subs set $set where showid='%s' and eid='%s'",
	mysql_real_escape_string($showid),
	mysql_real_escape_string($eid)
	);
	$result = mysql_query($query);
	return $result;
}
function insertSub($showid){
	db_connect();
	$query = sprintf("insert into subs set showid='%s', eid='%s'",
	mysql_real_escape_string($showid),
	mysql_real_escape_string($_SESSION['user']['eid'])
	);
	$result = mysql_query($query);
	return $result;
}
function saveShow($_POST){
	db_connect();
	$query = sprintf("insert into shows set showname='%s', imdb='%s', thetvdb='%s', shownamevariation='%s'",
	mysql_real_escape_string($_POST['showname']),
	mysql_real_escape_string($_POST['imdb']),
	mysql_real_escape_string($_POST['thetvdb']),
	mysql_real_escape_string($_POST['altshowname'])
	);
	$result = mysql_query($query);
	return $result;
}
function getShows(){
	db_connect();
	$query = "select * from shows order by `showname`";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}

function getShowID($name,$all=0){
	db_connect();
	$query = "select * from `shows` where `showname` like '%".mysql_real_escape_string($name)."%' ";
	$result = mysql_query($query);
	$results = db_result($result);
	if($all)return $results;
	else return $results[0];
}
function getShow($id){
	db_connect();
	$query = "select * from shows where showid='".mysql_real_escape_string($id)."'";
	$result = mysql_query($query);
	$results = db_result($result);
	return @$results[0];
}
function getShowName($id){
	db_connect();
	$query = "select * from shows where showid=$id";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results[0]['showname'];
}
function removeposted(){
	db_connect();
	$query = "delete from `episodes` where `posted`='1'";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function deleteepisode($episodeid){
	db_connect();
	$query = "delete from `episodes` where `episodeid`='$episodeid'";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function getTodayEpisodes($date){
	db_connect();
	$query = "select * from `episodes` where `date`='$date' and `posted`='0'";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function getLastPosts($lastpart='order by `timestamp` desc,`views` desc limit 0,150'){
	$today=time();
	db_connect();
	$query = "select * from `episodes` where `timestamp`<='$today' and `season`!='0' $lastpart"; //order by `dateadded`
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function getEpisodes2($id,$limit=0,$episode=0,$season=0){
	if($episode||$season)$add.=" and `season`='$season' and `episode`='$episode'";
	if(!$limit)$add.="order by `timestamp`,`episode` ";
	
	db_connect();
	$query = "select * from `episodes` where `showid`='".mysql_real_escape_string($id)."' $add";
	$result = mysql_query($query);
	$results = db_result($result);
	if($episode||$season)return $results[0];
	else return $results;
}
function getEpisodes($id,$limit=0,$episode=0,$season=0){
	$add='';
	if($episode||$season)$add.=" and `season`='$season' and `episode`='$episode'";
	if(!$limit)$add.="order by `timestamp`,`episode` ";
	
	db_connect();
	$query = "select * from `episodes` where `showid`='".mysql_real_escape_string($id)."' $add";
	$result = mysql_query($query);
	$results = db_result($result);
	if($episode||$season)return $results[0]['episodeid'];
	else return $results;
}
function getEpisodes3($id,$limit=0,$episode=0,$season=0){
	$add='';
	if(isset($episode)||isset($season))$add.=" and `season`='$season' and `episode`='$episode'";
	if(!isset($limit))$add.="order by `timestamp`,`episode` ";
	
	db_connect();
	$query = "select * from `episodes` where `showid`='".mysql_real_escape_string($id)."' $add";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function getEpisodes4($where=null){
	db_connect();
	$query = "select * from `episodes` $where";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function getMovies($where="`season`='0' order by `title`"){
	db_connect();
	$query = "select * from `episodes` where $where";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function countSongs($id){
	if($id)$where="and episodeid=".mysql_real_escape_string($id);
	db_connect();
	$query = "select count(*) from `songs` where `deleted`=0 and `theme`=0 $where";
	$result = mysql_query($query);
	$results = mysql_fetch_array($result);
	return $results[0];
}
function getSongs($id){
	if($id)$where="and episodeid=".mysql_real_escape_string($id);
	db_connect();
	//$query = "select * from `songs`,`links` where `songs`.`deleted`=0 and `songs`.`theme`=0 $where and `songs`.`songid`=`links`.`songid` order by `links`.`clickcount` desc";
	$query = "select * from `songs` where `deleted`=0 and `theme`=0 $where";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function getSub($where=""){
	db_connect();
	$query = "select * from `subs` $where";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function getSong($id){
	if($id)$where="and songid=".mysql_real_escape_string($id);
	db_connect();
	$query = "select * from `songs` where `deleted`=0 $where";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function getSong2($where=null){
	db_connect();
	$query = "select * from `songs` $where";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function getMissingLinks(){
	db_connect();
	$query = "select * from `songs` where `deleted`=0 and not exists (select 1 from `links` where `songs`.`songid`=`links`.`songid`)";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function getLink($id){
	if($id)$where="where `linkid`=".mysql_real_escape_string($id);
	db_connect();
	$query = "select * from `links` $where";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results[0];
}
function getLinks($id=0,$popular=0){
	if($id)$where="where `songid`=".mysql_real_escape_string($id)." and `deleted` = 0 ";
	if($popular)$where = "where `clickcount`!=0 order by `clickcount` desc limit 50";
	db_connect();
	$query = "select * from `links` $where";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function getEpisode($id){
	if($id)$where="where episodeid=".mysql_real_escape_string($id);
	db_connect();
	$query = "select * from episodes $where";
	$result = mysql_query($query);
	$results = db_result($result);
	if($id){
		if($results)return $results[0];
		else return false;
	
	}else return $results;
}
function updateEpisodes($episodes,$i,$getShow){
	foreach($episodes as $episode){
		if(strtotime($episode->FirstAired)&&$episode->EpisodeName!=''){
			$total++;
			$title = $episode->EpisodeName;
			$ep['episode']=$episode->EpisodeNumber;
			$ep['date']=date('m/d/Y',strtotime($episode->FirstAired));
			$ep['total']=$total;
			$ep['season']=$i;
			$ep['showid']=$getShow['showid'];
			if(!$getEpisodes=getEpisodes2($ep['showid'],0,$ep['episode'],$ep['season'])){
				if(!saveEpisode($ep,$getShow))echo "ERROR SAVING";
			}else{
				$skip=0;
				if($getEpisodes['date']!=date('m/d/Y',strtotime($episode->FirstAired))){
					$skip=1;
					if(!updateEpisode($getEpisodes['episodeid'],$field='date',$value=date('m/d/Y',strtotime($episode->FirstAired))))	echo 'err date ';
						
					if(!updateEpisode($getEpisodes['episodeid'],$field='timestamp',$value=strtotime($episode->FirstAired)))	echo 'err timestamp ';
				}
				if($getEpisodes['total']==0){
					$skip=1;
					if(!updateEpisode($getEpisodes['episodeid'],'total',$total))echo 'err total';
				}
				if($getEpisodes['title']!=$title){
					$skip=1;
					if(!updateEpisode($getEpisodes['episodeid'],'title',$title))echo 'err title';
				}
				//if(!$skip)echo "SKIPPED";
			}			
		}
	}
	return $total;
}
function updateShowEpisodes($getShow){
	updateShow($getShow['showid'],'updated',date('m/d/Y'));
	if(!$getShow['thetvdb']){
		$shows = TV_Shows::search(str_replace('and','',$getShow['showname']));
	}else{
		$show = TV_Shows::findById($getShow['thetvdb']);
	}
	for ($i = 1; $i <= $getShow['lastseason']; $i++) {
		$episodes = $show->getSeason($i);
		if($episodes)$total=updateEpisodes($episodes,$i,$getShow);
	}
	return $total;
}

function updateEpisode($id=null,$field,$value,$idtype="episodeid"){
	if($id)$add="where `".$idtype."`=".mysql_real_escape_string($id);
	db_connect();
	$query = "update `episodes` set `$field`='".mysql_real_escape_string($value)."' $add";
	$result = mysql_query($query);
	return $result;
}
function updateEpisode2($id=null,$field,$value=0){
	if($id)$add="where showid=".mysql_real_escape_string($id);
	db_connect();
	$query = "update `episodes` set `$field`='".mysql_real_escape_string($value)."' $add";
	$result = mysql_query($query);
	return $result;
}
function updateShow($id,$field,$value){
	db_connect();
	$query = "update `shows` set `$field`='".mysql_real_escape_string($value)."' where `showid`=".mysql_real_escape_string($id);
	$result = mysql_query($query);
	return $result;
}

function encode5t($str){
	for($i=0; $i<5;$i++)  {
		$str=strrev(base64_encode($str)); //apply base64 first and then reverse the string
	}
	return $str;
}

function validate_login($params){
	$fields = array('username' => 'Email', 'password' => 'Password');  
	$patterns = array('username' => '/^[a-z0-9.@]{2,34}$/', 'password' => '/^[a-z0-9A-Z]{5,14}$/');
	$loginErrors = null;

	//Check to see if the input meets the require field
	foreach ($fields as $key => $value)   	{
		if (!preg_match($patterns[$key],$params[$key]))	    {
			$loginErrors[$key] = $value." is missing or incorrect";
		}
	}
	$login = userInfo($params['username']);
	//Check to see if the enter correct password
	//$params['password'] = md5($params['password']);
	if ($login == null)
		$loginErrors['username'] = "Email does not exist or disabled";

	// if ($login['locked'])
		// $loginErrors['username'] = "Account is locked. Email Admin";
	
	if ($login['verfied'] == 1)
		$loginErrors['password'] = 'Account not yet verified. Check your email or click <a href="?action=emailverification&email='.urlencode($login['username']).'">here</a> to send again';
		
	if ($login['password'] != encode5t($params['password']))
		$loginErrors['password'] = "Incorrect Password";

	return $loginErrors;	
}

function check_input($string)   {
   $string = trim($string);
   $string = strip_tags($string);
   $string = htmlspecialchars($string);

   return $string;
}

function updateuser($username) {
	db_connect();
	$query = "update employee set `verfied` = '0 'where username = '". mysql_real_escape_string($username)."'";
	$result = mysql_query($query);		
	return $result;
}
function userInfo($username) {
	db_connect();
	$query = sprintf("select * from employee where username = '%s' and onleave = 0",  mysql_real_escape_string($username));
	$result = mysql_query($query);
	//Test to see if there is no row
	$number_of_row = @mysql_num_rows($result);
	if ($number_of_row == 0)  {		$userInfo = null;	}	
	$userInfo = mysql_fetch_array($result);			
	return $userInfo;
}
function users($where="") {
	db_connect();
	$query = "select * from employee $where";
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}
function decode5t($str){
	for($i=0; $i<5;$i++)  {
		$str=base64_decode(strrev($str)); //apply base64 first and then reverse the string}
	}
	return $str;
}
function userID($eid) {
	db_connect();
	$query = sprintf("select * from employee where `eid` = '%s' and onleave = 0",  mysql_real_escape_string($eid));
	$result = mysql_query($query);
	$results = db_result($result);
	return $results;
}

function parse_array()   {
	if (!empty($_POST))	{
		$params = array();
		$params = array_merge($params,$_POST);
	}	else 	{
		$params = null;
	}
	return $params;
}
function insert_user($params) {
	db_connect();   
	$query = sprintf("insert into employee set username = '%s', password = '%s', department = '%s',	`verify` = '%s', `verfied` = '1', created_at = NOW()",
		mysql_real_escape_string($params['email']),
		mysql_real_escape_string(encode5t($params['password'])),
		mysql_real_escape_string('users'),
		mysql_real_escape_string($params['verify'])
		);
	$result = mysql_query($query);
	return mysql_insert_id();
}


class Curl
{   	

    public $cookieJar = "";

    public function __construct($cookieJarFile = 'cookies.txt') {
        $this->cookieJar = $cookieJarFile;
    }

    function setup()
    {


        $header = array();
        $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] =  "Cache-Control: max-age=0";
        $header[] =  "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: en-us,en;q=0.5";
        $header[] = "Pragma: "; // browsers keep this blank.


        curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7');
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $header);
    	curl_setopt($this->curl,CURLOPT_COOKIEJAR, $cookieJar); 
    	curl_setopt($this->curl,CURLOPT_COOKIEFILE, $cookieJar);
    	curl_setopt($this->curl,CURLOPT_AUTOREFERER, true);
    	curl_setopt($this->curl,CURLOPT_FOLLOWLOCATION, true);
    	curl_setopt($this->curl,CURLOPT_RETURNTRANSFER, true);	
    }


    function get($url)
    { 
    	$this->curl = curl_init($url);
    	$this->setup();

    	return $this->request();
    }

    function getAll($reg,$str)
    {
    	preg_match_all($reg,$str,$matches);
    	return $matches[1];
    }

    function postForm($url, $fields, $referer='')
    {
    	$this->curl = curl_init($url);
    	$this->setup();
    	curl_setopt($this->curl, CURLOPT_URL, $url);
    	curl_setopt($this->curl, CURLOPT_POST, 1);
    	curl_setopt($this->curl, CURLOPT_REFERER, $referer);
    	curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fields);
    	return $this->request();
    }

    function getInfo($info)
    {
    	$info = ($info == 'lasturl') ? curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL) : curl_getinfo($this->curl, $info);
    	return $info;
    }

    function request()
    {
    	return curl_exec($this->curl);
    }
}
function findlinks($song){
	$getSongs=getSong2("where `songtext` like '".$song['songtext']."'");
	if($getSongs){foreach($getSongs as $sn){
		if($getLinks=getLinks($sn['songid'])){
			foreach($getLinks as $link){
				$count++;
				if($count<2)
					saveLink($link['link'],$song['songid'],$link['title'],$link['real']);
			}
		}
	}}
}

