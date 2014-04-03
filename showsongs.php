<?php

if($getEpisode['showid']!=0){if($getSongs=getSong2("where `deleted`=0 and `theme`=".$getEpisode['showid'])){foreach($getSongs as $song){
	include('songs.php');
}}}

if($getSongs=getSongs($getEpisode['episodeid'])){foreach($getSongs as $song){
	include('songs.php');
}}

if($getSongs==null)echo "Coming soon... please add the songs below.";

echo '<br><br>';
if(isset($_SESSION['user']['department'])&&$_SESSION['user']['department']=='admins'){
	echo 'Views: '.$getEpisode['views'];
	if($next){		
		$prev=$next[0];
		$seasonepisode2="S".sprintf("%02d",$prev['season'])."E".sprintf("%02d",$prev['episode']);
		$title2=$getShow['showname'].' '.$seasonepisode2.' song list soundtrack '.$prev['title'];
		$addtitle2 =  str_replace ($search,'-',$title2);
		echo ' <a href="?id='.$prev['episodeid'].'&'.urlencode($addtitle2).'" title="'.$title2.'">Next Episode >></a> ';
	}
	echo '<span id="loading"></span>'.
	'<form action="" method="POST">'.
	'<textarea cols=100 name="songs" id="songs" rows=30></textarea>'.
	'<input type="hidden" name="id" id="id" value="'.$_GET['id'].'">'.
	'<input type=submit name="addsongs" value="add songs">'.
	'<input type=button value="add songs2" onclick="addsongs(\''.$_GET['id'].'\')">'.
	'<br><br>'.
	'</form>'
	.'<br>';
//}else{
}
if(!isset($_SESSION['user']['department'])||$_SESSION['user']['department']!='admins'){
	$value=$getEpisode['views']+1;
	updateEpisode($id=$_GET['id'],$field='views',$value);
}

echo '<span id="addsong" style="display:none">'. addsongform($_GET['id']).'</span>'.
'<br><span id="buttonaddsong"><input type=button onclick="changview(\'addsong\',\'song\')" value="Add song"></span>';

//if($_SESSION['user']['department']=='admins')echo '</form>';
//if($_SESSION['user']['department']=='admins')echo '</span>';



if(isset($_SESSION['user']['department'])&&$_SESSION['user']['department']=='admins')$action='onfocus';
else $action='onclick';
echo '<br><br>Short URL:<input type=text '.$action.'="this.select()" style="width:220px" id="shorturl" value="'.mainURL().'/?id='.$_GET['id'].'"><br>';


?>	
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_pinterest_pinit"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4eb780d361b3ef1f"></script>
<!-- AddThis Button END -->