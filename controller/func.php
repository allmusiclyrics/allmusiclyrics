<?php


function update_episodes($getShow,$showid,$seasonstart=1,$total=1){
	if(!$output)$output = '';
	
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

			$output .= '<table border=1>
			<tr ><th colspan=3>Season '.$ep['season'].' Episodes</th></tr>
			<tr><th>Title</th><th>Episode</th><th>Date</th><th>Saving</th><th>TotalCount</th>';
			$output .= '</tr>';	

			if($show)$episodes = $show->getSeason($ep['season']);
			if($episodes){foreach($episodes as $episode){
				if(strtotime($episode->FirstAired)&&$episode->EpisodeName!=''){		
					$title = $episode->EpisodeName;
					$output .= '<tr><td> '.$ep['title']=str_replace('&','and',$title);$output .= '</td>';
					$output .= '<td>'.$ep['episode']=$episode->EpisodeNumber;$output .= '</td>';
					$output .= '<td>' .$ep['date']=date('m/d/Y',strtotime($episode->FirstAired));	$output .= ' </td>';
					$ep['showid']=$showid;
					$ep['total']=$total;
					if(!$getEpisodes=getEpisodes2($showid,0,$ep['episode'],$ep['season'])){
						if(saveEpisode($ep,$getShow))
							$output .= "<td>SAVED</td>";
						else 
							$output .= "<td>ERROR SAVING</td>";
					}else{
						$output .= '<td>';$skip=0;
						if($getEpisodes['date']!=date('m/d/Y',strtotime($episode->FirstAired))){
							$skip=1;
							if(updateEpisode($getEpisodes['episodeid'],'date',$value=date('m/d/Y',strtotime($episode->FirstAired))))
								$output .= 'UPDATED DATE ';
							else 
								$output .= 'err date ';
							
							if(updateEpisode($getEpisodes['episodeid'],'timestamp',$value=strtotime($episode->FirstAired)))
								$output .= "UPDATED TIMESTAMP ";
							else 
								$output .= 'err timestamp ';
						}
						if($getEpisodes['total']==0||$getEpisodes['total']!=$total){
							$skip=1;
							if(updateEpisode($getEpisodes['episodeid'],'total',$total))	
								$output .= "UPDATED TOTAL ";
							else 
								$output .= 'err total';
						}
						if($getEpisodes['title']!=$title){
							$skip=1;
							if(updateEpisode($getEpisodes['episodeid'],'title',$title))
								$output .= "UPDATED TITLE ";
							else
								$output .= 'err title';
						}
						if(!$skip) $output .= "SKIPPED";
						$output .= '</td>';
					}
					$output .= '<td>'.$total.'</td>';
					$output .= '</tr>';
					$total++;
				}
			}}
			$output .= '</table>';
		}
		
	}else{
		$output .= 'nothing in show';
	}

	$select = select_table('episodes',$fields=null," showid = ".$showid." order by `season` desc limit 1",$display=null);
	updateTable('shows',$fields=array('lastupdated'=>time(),'updated'=>date('m/d/Y'),'lastseason'=>$select[0]['season']),"where showid=".$showid,$display=null);
	return $output;
}
function hourlyupdate(){
	$output = '';
	$fields = null;
	$where = '1 order by lastupdated asc limit 1';
	$show = select_table('shows',$fields,$where,$display=null);
	$output .= "Last updated: ".date('m/d/Y G:i',$show[0]['lastupdated']).' ';
	
	$episode = select_table('episodes',$fields=null," showid = ".$show[0]['showid']." and `season` = ".$show[0]['lastseason']." and `episode`=1 limit 1",$display=null);
	
	$output .=  ' <a href="?updateshow='.$show[0]['showid'].'" target="_blank">ShowID'.$show[0]['showid'].'</a> ';
	$output .= update_episodes($show[0],$show[0]['showid'],$show[0]['lastseason'],$episode[0]['total']);
	//include('../model/update.php');
	//include(ROOTPATH.'/model/update.php');
	
	return $output;
}


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

function crawl_page($url, $depth = 5){
    static $seen = array();
    if (isset($seen[$url]) || $depth === 0)return;
    
	$seen[$url] = true;
	
    $dom = new DOMDocument('1.0');
    @$dom->loadHTMLFile($url);

    $anchors = $dom->getElementsByTagName('a');
    foreach ($anchors as $element) {
        $href = $element->getAttribute('href');
        if (0 !== strpos($href, 'http')) {
            $path = '/' . ltrim($href, '/');
            if (extension_loaded('http')) {
                $href = http_build_url($url, array('path' => $path));
            } else {
                $parts = parse_url($url);
                $href = $parts['scheme'] . '://';
                if (isset($parts['user']) && isset($parts['pass'])) {
                    $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                }
                $href .= $parts['host'];
                if (isset($parts['port'])) {
                    $href .= ':' . $parts['port'];
                }
                $href .= $path;
            }
        }
        crawl_page($href, $depth - 1);
    }
    echo "URL:",$url,PHP_EOL,"CONTENT:",PHP_EOL,$dom->saveHTML(),PHP_EOL,PHP_EOL;
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
    var adfly_id = '.adflyuid().'; 
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
	$fetchurl='http://api.adf.ly/api.php?key='.adflykey().'&uid='.adflyuid().'&advert_type=int&domain=adf.ly&url='.urlencode($link['linktext']);
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
		'<input type=text id="link'.$song['songid'].'" style="width:30px;" placeholder="Link" onkeypress="if (event.keyCode == 13) addLink(\''.$song['songid'].'\') ">'.
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
			$host=$hosts[$parse_url['host']];
		}
	}
	
	$out = '<span id="onelink'.$link['linkid'].'">';
	$onmousedown='sendData(\''.$action.'\',\''.$link['linkid'].'\',\''.$link['clickcount'].'\');this.href=\''.$link['linktext'].'\'';
	$out .= ' <a href="'.$link['real'].'" id="thelink'.$link['linkid'].'" onmousedown="'.$onmousedown.'" target="_blank" >'.$link['title'].'</a> ';
	
	if(isset($_SESSION['user']['department'])&&$_SESSION['user']['department']=='admins'){
		if($link['real'])$out .= ' <a href="'.$link['real'].'" target="_blank" title="'.$link['linktext'].'">ORG</a> ';
		$out .= 'clicked '.$link['clickcount'].' <span style="cursor:pointer;color:red" onclick="delLink(\''.$link['linkid'].'\')" title="delete">X</span>';
	}
	$out .= '</span> ';
	
	return $out;
}
function addsongs($params){
	$params['songs']=str_replace("\r\n \r\n", "\r\n",$params['songs']);
	$params['songs']=preg_replace("/^\r\n/", "",$params['songs']);
	$songs = explode("\n", $params['songs']);$count=null;$return=null;
	if($songs){foreach ($songs as $song){
		$count++;
		$return.= $count.' '.$song;
		if($count%2){
			$songid=saveSong($song,$params['id']);
		}else{
			if($song!='add scene description')updateSong($songid,'desc',$song);
		}
		
	}}
	$return.=$params['id'].' <br><br>';
	return $return;
}
function updateSong($songid,$field,$data){
	$link = db_connect();
	$query = sprintf("update `songs` set `$field`='%s' where `songid`='%s'",
	mysqli_real_escape_string($link,$data),
	mysqli_real_escape_string($link,$songid)
	);
	$result = mysqli_query($link,$query);
	return $result;
}
function updateLink($linkid,$field,$data,$by='linkid'){
	$link = db_connect();
	$query = sprintf("update `links` set `$field`='%s' where `$by`='%s'",
	mysqli_real_escape_string($link,$data),
	mysqli_real_escape_string($link,$linkid)
	);
	$result = mysqli_query($link,$query);
	return $result;
}
function saveLink($link,$id,$title='',$real,$deleted=0){
	if($title)$add=", `title`='".$title."'";
	if($deleted)$add=", `deleted`='1'";
	$date=time();
	$link = db_connect();
	$query = sprintf("insert into `links` set `linktext`='%s', `songid`='%s', `real`='$real', `dateadded`='$date' $add",
	mysqli_real_escape_string($link,$link),
	mysqli_real_escape_string($link,$id)
	);
	$result = mysqli_query($link,$query);
	return mysql_insert_id();
}
function saveSong($song,$id,$deleted=0,$desc=0){
	$link = db_connect();
	if($_SESSION['user']['eid'])$eid=$_SESSION['user']['eid'];
	else $eid=0;
	$date = time();
	$add=null;
	if($deleted)$add=", `deleted`=1";
	if($desc)$add.=", `desc`='".mysqli_real_escape_string($link,$desc)."'";
	
	$query = sprintf("insert into `songs` set `songtext`='%s', `episodeid`='%s', `dateadded`='$date', `eid`='$eid' $add",
	mysqli_real_escape_string($link,$song),
	mysqli_real_escape_string($link,$id)
	);
	$result = mysqli_query($link,$query);
	return mysqli_insert_id($link);
}
function saveMovie($values){
	$link = db_connect();
	$query = "insert into episodes set `title`='".mysqli_real_escape_string($link,$values['moviename'])."'";
	$result = mysqli_query($link,$query);
	return mysql_insert_id();
}
function saveEpisode($params,$getShow=null){
	$link = db_connect();
	$query = sprintf("insert into episodes set `title`='%s', `episode`='%s', `season`='%s', `date`='%s', `timestamp`='%s', `showid`='%s', `total`='%s'",
	mysqli_real_escape_string($link,$params['title']),
	mysqli_real_escape_string($link,$params['episode']),
	mysqli_real_escape_string($link,$params['season']),
	mysqli_real_escape_string($link,$params['date']),
	mysqli_real_escape_string($link,strtotime($params['date'])),
	mysqli_real_escape_string($link,$params['showid']),
	mysqli_real_escape_string($link,$params['total'])
	);
	$result = mysqli_query($link,$query);
	return $result;
}
function addsongform($id){
	$output = '<br>';
	$output .= '<input type=text placeholder="Song name and artist" style="width:320px"  id="song" onkeypress="if (event.keyCode == 13) addsong()" >*';
	$output .= '<br>';
	$output .= '<input type=text placeholder="Describe scene (optional)" style="width:320px" id="desc" onkeypress="if (event.keyCode == 13) addsong()">';
	$output .= '<br>';
	//$output .= '<input type=text placeholder="Link (optional, example: http://www.youtube.com/watch?v=YdPml5QhMIA ) optional" style="width:320px" id="link" onkeypress="if (event.keyCode == 13) addsong()">'.
	$output .= '<input type="hidden" name="id" id="id" value="'.$id.'">';
	$output .= '<input type=button onclick="addsong()" value="Add Song">';
	return $output;
}

function getIP(){
	if($_SERVER['HTTP_X_FORWARDED_FOR'])$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else $ip = $_SERVER['REMOTE_ADDR'];
	return $ip;
}
function emailrequest($name,$eid){
	$ip = getIP();
	
	$body='Episode songs requested for show: '.$name.' <br>'.
	'Episode: <a href="'.mainURL().'/?id='.$eid.'">'.$eid.'</a><br>'.
	'IP: <a href="http://www.ip2location.com/'.$ip.'">'.$ip.'</a>'.
	'<br>';
	
	if(isset($_SESSION['user']['username']))$email=$_SESSION['user']['username'];
	else $email=contactemail();
	
	$headers = 'From: '.sitename().'  <'.$email.'>'. "\r\n" ;
	$headers .= "Bcc: ".adminemail()." \r\n"; 
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	if(mail($to=adminemail(), $subject='Episode songs requested for '.$name, $body, $headers))	return true;
}

function emailsong($params){
	$ip = getIP();
	$body='Song: '.$params['song'].' Desc:'.$params['desc'].' <br>'.
	'Episode: <a href="'.mainURL().'/?id='.$params['episodeid'].'">'.$params['episodeid'].'</a><br><br>'.
	'Link: <a href="'.$params['linktext'].'">link</a><br><br>'.
	'IP: <a href="http://www.ip2location.com/'.$ip.'">'.$ip.'</a><br>'.
	//'<a href="'.mainURL().'/?action=approvesongonly&songid='.$params['songid'].'&id='.$params['episodeid'].'">Approve song only</a><br><br>'.
	//'<a href="'.mainURL().'/?action=approvesong&songid='.$params['songid'].'&id='.$params['episodeid'].'">Approve song with link</a><br><br>'.
	'<a href="'.mainURL().'/?action=deletesong&itemid='.$params['songid'].'&id='.$params['episodeid'].'">DELETE</a><br><br>';
	
	if(isset($_SESSION['user']['username']))$email=$_SESSION['user']['username'];
	else $email=contactemail();
	
	$headers = 'From: '.sitename().'  <'.$email.'>'. "\r\n" ;
	$headers .= "Bcc: ".adminemail()." \r\n"; 
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	//echo $body;
	mail($to=adminemail(), $subject='Song added', $body, $headers);

	return '<br>Your song "'.$params['song'].'" was submitted Thank you.<br>Add another:<br>'.addsongform($params['episodeid']); //and will be posted once approved.
}

function createaccount($params){
	
	$params['verify'] = uniqid();
	if($params['password'])$params['password'] = encode5t($params['password']);
	else $params['password'] = '';
	
	$fields = array(
	'username'=>$params['email'],
	'password'=>$params['password'],
	'department'=>'users',
	'verify'=>$params['verify'],
	'verfied' => '1',
	'created_at' => 'NOW()'
	);
	
	if($eid=insert_table($table='employee',$fields,$display=1)){
		if($email=emailverification($params)){
			$output = 'Email verification was sent to '.$params['email'].' <br>Make sure to check the junk or spam folder. Once verified you can';
			if($params['password'])$output .= ' <a href="?p=login">Login</a>.';
			if(!$params['password']){
				$output .= ' create a password and/or receive email updates.';
				if($params['showid']&&$eid)saveSub($params['showid'],$checked='',$eid);
			}
		}else{
			$output = 'Error emailing verification. Click <a href="?action=emailverification&email='.urlencode($params['email']).'">here</a> to try again or '.
			'<a href="?p=contact">email</a> about this issue.<br>'.print_r($email);
		}
	}else{
		$output = 'Error creating user account. Try again or <a href="?p=contact">email</a> about this issue.';
	}
	return $output;
}
function emailverification($params){
	$verify = ''.mainURL().'/?action=verify&verifyid='.$params['verify'].'&email='.urlencode($params['email']);
	$body='Thank you for signing up to <a href="'.mainURL().'">'.sitename().'</a>,<br><br>'.
	'To verify your account click <a href="'.$verify.'">HERE</a> or copy and past this link to your browser: <br>'.$verify.'<br><br>'.
	'If you did not sign up for a new account please ignore this email or reply to let us know.<br><br>';

	$headers = 'From: '.sitename().'  <'.contactemail().'>'. "\r\n" ;
	$headers .= "Bcc: ".adminemail()." \r\n"; 
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	return $params.mail($params['email'], $subject='Verify your new account at '.sitename(), $body, $headers);
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
	'<br><br><br>------<br>Thank you for visiting <a href="'.mainURL().'">'.sitename().'</a>.<br>To unsubscribe from this show or all shows click <a href="'.mainURL().'/?action=unsubscribe&email='.urlencode($user[0]['username']).'">here</a> ';
	// $body='test';
	$headers = 'From: '.sitename().'  <'.contactemail().'>'. "\r\n" . "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= "Bcc: ".adminemail()." \r\n"; 
	$subject='New episode for '.$getShow['showname'];
	return mail($user[0]['username'], $subject, $body, $headers);
}
function emailitem($params,$item){
	$ip = getIP();
	$getsong = getSong($params['itemid']);
	$body='Add '.$item.': '.$params['data'].' <br>'.
	'Song: '.$getsong[0]['songtext'].'<br>'.
	'Episode: <a href="'.mainURL().'/?id='.$getsong[0]['episodeid'].'">'.$getsong[0]['episodeid'].'</a><br><br><br>'.
	'IP: <a href="http://www.ip2location.com/'.$ip.'">'.$ip.'</a><br>'.
	//'<a href="'.mainURL().'/?action=savefield&itemid='.$params['itemid'].'&data='.rawurlencode($params['data']).'&field='.$params['field'].'">ADD '.$item.'</a><br><br>'.
	'<a href="'.mainURL().'/?action=savefield&itemid='.$params['itemid'].'&data='.rawurlencode('add scene description').'&field='.$params['field'].'">DELETE </a><br><br>';

	if(isset($_SESSION['user']['username']))$email=$_SESSION['user']['username'];
	else $email=contactemail();
	$headers = 'From: '.sitename().'  <'.$email.'>'. "\r\n" ;
	$headers .= "Bcc: ".adminemail()." \r\n"; 
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	if(mail($to=adminemail(), $subject=$item.' added', $body, $headers))
		return true;
	else
		return false;
}
function emailshow($params){
	$ip = getIP();
	$body='Add show: '.$params['showname'].' <br>
	ADD: <a href="'.mainURL().'/?showname='.$params['showname'].'">ADD SHOW</a><br><br>'.
	'IP: <a href="http://www.ip2location.com/'.$ip.'">'.$ip.'</a>';

	if(isset($_SESSION['user']['username']))$email=$_SESSION['user']['username'];
	else $email=contactemail();
	$headers = 'From: '.sitename().'  <'.$email.'>'. "\r\n" ;
	$headers .= "Bcc: ".adminemail()." \r\n"; 
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	if(mail($to=adminemail(), $subject='Show added', $body, $headers))
		return true;
	else
		return false;
}
function emailmovie($params){
	$ip = getIP();
	$body='Add movie: '.$params['moviename'].' <br>
	ADD: <a href="'.mainURL().'/?action=addmovie&moviename='.$params['moviename'].'">ADD MOVIE</a><br><br>'.
	'IP: <a href="http://www.ip2location.com/'.$ip.'">'.$ip.'</a>';

	if(isset($_SESSION['user']['username']))$email=$_SESSION['user']['username'];
	else $email=contactemail();
	$headers = 'From: '.sitename().'  <'.$email.'>'. "\r\n" ;
	$headers .= "Bcc: ".adminemail()." \r\n"; 
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	if(mail($to=adminemail(), $subject='Movie added', $body, $headers))
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
	if($eid==null)$eid = $_SESSION['user']['eid'];
	if($getSub=getSub("where showid=".$showid." and eid=".$eid)){
		if($checked=='false'){
			return updateSub($showid,"`del`='1'");
		}else{
			return updateSub($showid,"`del`='0'");
		}
	}else{
		return insert_table('subs',array('showid'=>$showid,'eid'=>$eid));
	}
}
function updateSub($showid,$set,$eid=null){
	if(!$eid)$eid=$_SESSION['user']['eid'];
	$link = db_connect();
	$query = sprintf("update subs set $set where showid='%s' and eid='%s'",
	mysqli_real_escape_string($link,$showid),
	mysqli_real_escape_string($link,$eid)
	);
	$result = mysqli_query($link,$query);
	return $result;
}
function insertSub($showid){
	$link = db_connect();
	$query = sprintf("insert into subs set showid='%s', eid='%s'",
	mysqli_real_escape_string($link,$showid),
	mysqli_real_escape_string($link,$_SESSION['user']['eid'])
	);
	$result = mysqli_query($link,$query);
	return $result;
}
function saveShow($params){
	$link = db_connect();
	$query = sprintf("insert into shows set showname='%s', imdb='%s', thetvdb='%s', shownamevariation='%s'",
	mysqli_real_escape_string($link,$params['showname']),
	mysqli_real_escape_string($link,$params['imdb']),
	mysqli_real_escape_string($link,$params['thetvdb']),
	mysqli_real_escape_string($link,$params['altshowname'])
	);
	$result = mysqli_query($link,$query);
	return $result;
}
function getShows(){
	$link = db_connect();
	$query = "select * from `shows` order by `showname` limit 100";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}

function getShowID($name,$all=0){
	$link = db_connect();
	$query = "select * from `shows` where `showname` like '%".mysqli_real_escape_string($link,$name)."%' ";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	if($all)return $results;
	else return $results[0];
}
function getShow($id){
	$link = db_connect();
	$query = "select * from shows where showid='".mysqli_real_escape_string($link,$id)."'";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return @$results[0];
}
function getShowName($id){
	$link = db_connect();
	$query = "select * from shows where showid=$id";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results[0]['showname'];
}
function removeposted(){
	$link = db_connect();
	$query = "delete from `episodes` where `posted`='1'";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function deleteepisode($episodeid){
	$link = db_connect();
	$query = "delete from `episodes` where `episodeid`='$episodeid'";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function getTodayEpisodes($date){
	$link = db_connect();
	$query = "select * from `episodes` where `date`='$date' and `posted`='0'";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function getLastPosts($lastpart='order by `timestamp` desc,`views` desc limit 0,150'){
	$today=time();
	$link = db_connect();
	$query = "select * from `episodes` where `timestamp`<='$today' and `season`!='0' $lastpart"; //order by `dateadded`
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function getEpisodes2($id,$limit=0,$episode=0,$season=0){
	if($episode||$season)$add.=" and `season`='$season' and `episode`='$episode'";
	if(!$limit)$add.="order by `timestamp`,`episode` ";
	
	$link = db_connect();
	$query = "select * from `episodes` where `showid`='".mysqli_real_escape_string($link,$id)."' $add";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	if($episode||$season)return $results[0];
	else return $results;
}
function getEpisodes($id,$limit=0,$episode=0,$season=0){
	$add='';
	if($episode||$season)$add.=" and `season`='$season' and `episode`='$episode'";
	if(!$limit)$add.="order by `timestamp`,`episode` ";
	
	$link = db_connect();
	$query = "select * from `episodes` where `showid`='".mysqli_real_escape_string($link,$id)."' $add";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	if($episode||$season)return $results[0]['episodeid'];
	else return $results;
}
function getEpisodes3($id,$limit=0,$episode=0,$season=0){
	$add='';
	if(isset($episode)||isset($season))$add.=" and `season`='$season' and `episode`='$episode'";
	if(!isset($limit))$add.="order by `timestamp`,`episode` ";
	
	$link = db_connect();
	$query = "select * from `episodes` where `showid`='".mysqli_real_escape_string($link,$id)."' $add";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function getEpisodes4($where=null){
	$link = db_connect();
	$query = "select * from `episodes` $where";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function getMovies($where="`season`='0' order by `title`"){
	$link = db_connect();
	$query = "select * from `episodes` where $where";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function countSongs($id){
	$link = db_connect();
	if($id)$where="and episodeid=".mysqli_real_escape_string($link,$id);
	$query = "select count(*) from `songs` where `deleted`=0 and `theme`=0 $where";
	$result = mysqli_query($link,$query);
	$results = mysqli_fetch_array($result);
	return $results[0];
}
function getSongs($id){
	$link = db_connect();
	if($id)$where="and episodeid=".mysqli_real_escape_string($link,$id);
	
	$query = "select * from `songs` where `deleted`=0 and `theme`=0 $where order by ord";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function getSub($where=""){
	$link = db_connect();
	$query = "select * from `subs` $where";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function getSong($id){
	$link = db_connect();
	if($id)$where="and `songid`=".mysqli_real_escape_string($link,$id);
	$query = "select * from `songs` where `deleted`=0 $where";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function getSong2($where=null){
	$link = db_connect();
	$query = "select * from `songs` $where";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function getMissingLinks(){
	$link = db_connect();
	$query = "select * from `songs` where `deleted`=0 and not exists (select 1 from `links` where `songs`.`songid`=`links`.`songid`)";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function getLink($id){
	$link = db_connect();
	if($id)$where="where `linkid`=".mysqli_real_escape_string($link,$id);
	
	$query = "select * from `links` $where";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results[0];
}
function getLinks($id=0,$popular=0){
	$link = db_connect();
	
	if($id)$where="where `songid`=".mysqli_real_escape_string($link,$id)." and `deleted` = 0 ";
	if($popular)$where = "where `clickcount`!=0 order by `clickcount` desc limit 50";
	
	$query = "select * from `links` $where";
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	return $results;
}
function getEpisode($id){
	$link = db_connect();
	if($id)$where="where episodeid=".mysqli_real_escape_string($link,$id);
	$query = "select * from episodes $where";
	$result = mysqli_query($link,$query);
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
	$link = db_connect();
	if($id)$add="where `".$idtype."`=".mysqli_real_escape_string($link,$id);
	$query = "update `episodes` set `$field`='".mysqli_real_escape_string($link,$value)."' $add";
	$result = mysqli_query($link,$query);
	return $result;
}
function updateEpisode2($id=null,$field,$value=0){
	$link = db_connect();
	if($id)$add="where showid=".mysqli_real_escape_string($link,$id);
	$query = "update `episodes` set `$field`='".mysqli_real_escape_string($link,$value)."' $add";
	$result = mysqli_query($link,$query);
	return $result;
}
function updateShow($id,$field,$value){
	$link = db_connect();
	$query = "update `shows` set `$field`='".mysqli_real_escape_string($link,$value)."' where `showid`=".mysqli_real_escape_string($link,$id);
	$result = mysqli_query($link,$query);
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
	$link = db_connect();
	$query = "update employee set `verfied` = '0' where username = '". mysqli_real_escape_string($link,$username)."'";
	$result = mysqli_query($link,$query);		
	return $result;
}
function userInfo($username) {
	$link = db_connect();
	$query = sprintf("select * from employee where username = '%s' and onleave = 0",  mysqli_real_escape_string($link,$username));
	$result = mysqli_query($link,$query);
	//Test to see if there is no row
	$number_of_row = @mysql_num_rows($result);
	if ($number_of_row == 0)$userInfo = null;
	$userInfo = mysqli_fetch_array($result);			
	return $userInfo;
}
function users($where="") {
	$link = db_connect();
	$query = "select * from employee $where";
	$result = mysqli_query($link,$query);
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
	$link = db_connect();
	$query = sprintf("select * from employee where `eid` = '%s' and onleave = 0",  mysqli_real_escape_string($link,$eid));
	$result = mysqli_query($link,$query);
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
	$link = db_connect();   
	$query = sprintf("insert into employee set username = '%s', password = '%s', department = '%s',	`verify` = '%s', `verfied` = '1', created_at = NOW()",
		mysqli_real_escape_string($link,$params['email']),
		mysqli_real_escape_string($link,encode5t($params['password'])),
		mysqli_real_escape_string($link,'users'),
		mysqli_real_escape_string($link,$params['verify'])
		);
	$result = mysqli_query($link,$query);
	return mysql_insert_id();
}


class Curl{   	

    public $cookieJar = "";

    public function __construct($cookieJarFile = 'cookies.txt') {
        $this->cookieJar = $cookieJarFile;
    }

    function setup(){
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


    function get($url){ 
    	$this->curl = curl_init($url);
    	$this->setup();

    	return $this->request();
    }

    function getAll($reg,$str){
    	preg_match_all($reg,$str,$matches);
    	return $matches[1];
    }

    function postForm($url, $fields, $referer=''){
    	$this->curl = curl_init($url);
    	$this->setup();
    	curl_setopt($this->curl, CURLOPT_URL, $url);
    	curl_setopt($this->curl, CURLOPT_POST, 1);
    	curl_setopt($this->curl, CURLOPT_REFERER, $referer);
    	curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fields);
    	return $this->request();
    }

    function getInfo($info){
    	$info = ($info == 'lasturl') ? curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL) : curl_getinfo($this->curl, $info);
    	return $info;
    }

    function request(){
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

