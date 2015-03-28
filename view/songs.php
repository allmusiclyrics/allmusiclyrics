<?php

echo '<span id=song'.$song['songid'].'><b>';
if(isset($_SESSION['user']['department'])&&$_SESSION['user']['department']=='admins'){
	$field = 'songtext';
	echo '<span id="button'.$field.'area'.$song['songid'].'"><font onclick="changview(\'';
	echo $field.'area'.$song['songid'].'\',\''.$field.$song['songid'].'\')" >'.$song[$field].'</font></span>';
	echo '<span id="'.$field.'area'.$song['songid'].'" style="display:none">';
	
	echo '<input type=text onkeypress="if (event.keyCode == 13) actionitem(\'savefield\',\''.$song['songid'].'\',\''.$field.'\')"';
	echo 'style="width:420px" placeholder="Add song name" id='.$field.$song['songid'].' value="'.str_replace('"', '\'',$song[$field]).'">';
	
	//echo '<input type=text style="width:420px" id="'.$field.$song['songid'].'2" value="'.$song['ord'].'">';
	
	echo '<input type=button value="Save" onclick="actionitem(\'savefield\',\''.$song['songid'].'\',\''.$field.'\')">';
	echo '</span>';
}else{
	echo $song['songtext'];
}
echo '</b> ';
if($getLinks=getLinks($song['songid'])){foreach($getLinks as $link){
	if(!$link['title'])$link['title']='youtube';
	
	
	if($link['title']=='Lyrics'){ 
		//$link['linktext']="http://adf.ly/".adflyuid()."/".$link['real'];
		echo gethref($link);
		$link['real']="https://www.youtube.com/results?q=".urlencode($song['songtext']);
		$link['title']='youtube';
		$link['linktext']="http://adf.ly/".adflyuid()."/".$link['real'];
		$link['linkid']=$song['songid'];
		$link['clickcount']=$song['clickcount'];
		echo ' - '.gethref($link,null,'songclicked');
	}else{
		echo gethref($link);
	}
	//echo ' <a href="'.$link['linktext'].'" target="_blank" title="">'.$link['title'].'</a> ';
	
	//echo $link['linktext'].' ...'.getadfly($link['linktext']);
}}else{
	if(findlinks($song))if($_SESSION['user']['department']=='admins')echo 'Looking for links...';
	
	$link['real']="https://www.youtube.com/results?q=".urlencode($song['songtext']);
	$link['title']='youtube';
	$link['linktext']="http://adf.ly/".adflyuid()."/".$link['real'];
	$link['linkid']=$song['songid'];
	$link['clickcount']=$song['clickcount'];
	echo gethref($link,null,'songclicked');
}

if(isset($_SESSION['user']['department'])&&$_SESSION['user']['department']=='admins'){
	echo '<span id=linkarea'.$song['songid'].'></span>'.linkform($song).
	'&nbsp;&nbsp;&nbsp; <span id="songarea'.$song['songid'].'">'.
	'<input type=text style="width:30px;" id="saveord'.$song['songid'].'" value="'.$song['ord'].'" onkeypress="if (event.keyCode == 13)actionitem3(\'saveord\',\''.$song['songid'].'\',\'song\');" >'.
	'<input type=button value="Save Order" onclick="actionitem3(\'saveord\',\''.$song['songid'].'\',\'song\');"></span>'.
	' &nbsp;&nbsp;&nbsp;';
	echo '<input type=button value="Delete song" onclick="actionitem2(\'deletesong\',\''.$song['songid'].'\',\'song\');"> &nbsp;&nbsp;&nbsp;';
	if($song['theme']==0)echo '<input type=button value="Theme song" onclick="actionitem2(\'themesong\',\''.$song['songid'].'\',\'song\');">';
	
	
}

echo '<br>';

if(preg_match("/add scene description/i",$song['desc'])||$song['desc']==''||$song['desc']==" "||$song['desc']=="\r"){
	$field = 'desc';
	$style = 'font-style:italic;';
	echo '<span id="button'.$field.'area'.$song['songid'].'"><font onclick="changview(\''.$field.
		'area'.$song['songid'].'\',\''.$field.$song['songid'].'\')" style="'.$style.'cursor:pointer" title="Click to add description">Add description</font></span>'.
		'<span id="'.$field.'area'.$song['songid'].'" style="display:none">'.
		'<input type=text onkeypress="if (event.keyCode == 13) actionitem(\'savefield\',\''.$song['songid'].'\',\''.$field.'\')" style="width:320px" placeholder="Add scene description" id='.$field.$song['songid'].'>'.
		'<input type=button value="Save" onclick="actionitem(\'savefield\',\''.$song['songid'].'\',\''.$field.'\')">'.
		'</span>';
}else{
	if(isset($_SESSION['user']['department'])&&$_SESSION['user']['department']=='admins'){
		$field = 'desc';
		echo '<span id="button'.$field.'area'.$song['songid'].'"><font onclick="changview(\''.
		$field.'area'.$song['songid'].'\',\''.$field.$song['songid'].'\')" >'.$song[$field].'</font></span>'.
		'<span id="'.$field.'area'.$song['songid'].'" style="display:none">'.
		'<input type=text onkeypress="if (event.keyCode == 13) actionitem(\'savefield\',\''.$song['songid'].'\',\''.$field.'\')" style="width:420px" placeholder="Add scene description" id='.$field.$song['songid'].' value="'.str_replace('"', '\'',$song[$field]).'">'.
		'<input type=button value="Save" onclick="actionitem(\'savefield\',\''.$song['songid'].'\',\''.$field.'\')">'.
		'</span>';
	}else{
		$field = 'desc';
		echo '<span id='.$field.$song['songid'].'>'.$song[$field].'</span>';
	}
}
	
echo '<br><br></span>';


