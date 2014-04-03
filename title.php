<?php

$sitename = "All Music Lyrics";
$title = '';
$home = '';
$search = array(' ','(',')','@','!',"'",',','?','*','&');

if(isset($_GET['id'])&&is_numeric($_GET['id'])){
	if($getEpisode=getEpisode($_GET['id'])){
		if($getEpisode['season']!=0){
			$seasonepisode="S".sprintf("%02d",$getEpisode['season'])."E".sprintf("%02d",$getEpisode['episode']);		
			$getShow=getShow($getEpisode['showid']);			
			$addtitle =  str_replace ($search,'-',$getShow['showname'].' '.$seasonepisode.' song list soundtrack '.$getEpisode['title']);
			//$addtitle = htmlentities($addtitle);
			$addtitle = urlencode($addtitle);
			if($_SERVER['HTTP_HOST']!='localhost'){
				if($_SERVER["REQUEST_URI"]!='/?id='.$_GET['id'].'&'.$addtitle){
					header("HTTP/1.1 301 Moved Permanently"); 
					header('Location: /?id='.$_GET['id'].'&'.$addtitle);
				}
			}
			$title .= $getShow['showname'].' '.$seasonepisode.' songs list sound track '.$getEpisode['title'];
		}else{
			$addtitle =  str_replace ($search,'-',$getEpisode['title'].' song list soundtrack');
			$addtitle = urlencode($addtitle);
			if($_SERVER['HTTP_HOST']!='localhost'){
				if($_SERVER["REQUEST_URI"]!='/?id='.$_GET['id'].'&'.$addtitle){
					header("HTTP/1.1 301 Moved Permanently"); 
					header('Location: /?id='.$_GET['id'].'&'.$addtitle);
				}
			}
			$title .= $getEpisode['title'].' songs list sound track ';
		}
		
	}else{
		$title .= 'Not found';
	}
	
}elseif(isset($_GET['q'])&&$_GET['q']){
	$title.='Searching for "'.$_GET['q'].'"';
	
}elseif(isset($_GET['showid'])&&$_GET['showid']){
	$show=str_replace ('-',' ',$_GET['show']);
	
	if($getShowID=getShow($_GET['showid']))
		$title .= $show.' Episodes List ';
	else $home=1;
	
	
}else
	$home=1;

if($home){
	$getLastPosts=getLastPosts();
	$title = ' Home page ';
}

if(isset($_GET['p'])&&$_GET['p'])
	$title = $_GET['p'];
	
$title.=' - '.$sitename;

