<?php


function select_table($table,$fields=null,$where=null,$display=null,$countonly=null,$sum=null){
	if($countonly)$select = 'count(*)';
	elseif($sum)$select = 'sum('.$sum.')';
	else $select = '*';
	$link = db_connect();
	$query = "select $select from `$table` where ";
	if($fields){foreach($fields as $field=>$value){
		$count++;
		$query .= " `$field`='$value' ";
		if(count($fields)!=$count)$query .= " and ";
	}}elseif(!$where) $query .= "1 ";
	$query .= $where;
	$result = mysqli_query($link,$query);
	$results = db_result($result);
	if($display) return array('results'=>$results,'query'=>$query);
	elseif($countonly) return $results[0][0];
	elseif($sum) return $results[0]['sum('.$sum.')'];
	else return $results;
}
function updateTable($table,$fields,$where,$display=null){
	$link = db_connect();
	$query = "update $table set ";
	foreach($fields as $field=>$value){
		$count++;
		$query .= " `$field`='$value' ";
		if(count($fields)!=$count)$query .= " , ";
	}
	$query .= $where;
	$result = mysqli_query($link,$query);
	if($display==1) return array('results'=>$result,'query'=>$query);
	else return $result;
}


function insert_table($table,$fields,$display=0){
	$link = db_connect();
	$query = "insert into `$table` set ";
	foreach($fields as $field=>$value){
		$count++;
		$query .= " `$field`='$value' ";
		if(count($fields)!=$count)	$query .= " , ";
	}
	$result = mysqli_query($link,$query);
	if($display==1)return mysql_insert_id();
	else return $result;	
}



/*
** =============================
** ====== HTML GENERTING =======
** =============================
**/

function makeRadio($values='',$onclick='',$style='',$title='',$id=''){
	$output = '';
	foreach($values as $key=>$value){
		$output .= '<input type=radio value="'.$key.'" style="'.$style.'" title="'.$title.'" onclick="'.$onclick.'" id="'.$id.'" name="'.$id.'">'.$value;
	}
	return $output;
}
function makeCheckbox($value='',$onclick='',$style='',$title='',$checked='',$id=''){
	$output = '<input type=checkbox value="'.$value.'" style="'.$style.'" title="'.$title.'" onclick="'.$onclick.'" '.$checked.' id="'.$id.'" name="'.$id.'">';
	return $output;
}
function makeImg($src='',$onclick='',$style='',$title='',$id='',$span='',$onmouseover=''){ 
	//EXAMPLE: makeImg($src='images/cal.gif',$onclick='displayDatePicker(\'date\')',$style='cursor: pointer;',$title='Choose date')
	$output .= '';
	if($span!='')$output .= '<span id="'.$span.'">';
	$output .= '<img src="'.$src.'" style="'.$style.'" title="'.$title.'" onclick="'.$onclick.'" id="'.$id.'" name="'.$id.'" onmouseover="'.$onmouseover.'" >';
	if($span!='')$output .= '</span>';
	return $output;
}
function makeLinkAID($aid='',$addviewas='',$newwindow=''){
	if($aid!=0&&$aid!=1)return makeLink($value='AppID# '.$aid,$href='?aid='.$aid.$addviewas,$onclick='',$onmouseover='',$span='',$title='',$style='',$id='');
}
function makeLink($value='',$href='#',$onclick='',$onmouseover='',$span='',$title='',$style='',$id=''){
	$output = '';
	if($span!='')$output .= '<span id="'.$span.'">';
	$output .= '<a href="'.$href.'" target="_blank" title="'.$title.'" onclick="'.$onclick.'" onmouseover="'.$onmouseover.'" style="'.$style.'" id="'.$id.'">'.$value.'</a>';
	if($span!='')$output .= '</span>';
	return $output;
}
function makeButton($value,$onclick='',$span='',$title='',$id='',$style=''){
	$output .= '';
	if($span!='')$output .= '<span id="'.$span.'">';
	$output .= '<input type=button class=button value="'.$value.'" title="'.$title.'" onclick="'.$onclick.'" id="'.$id.'" name="'.$id.'" style="'.$style.'" onkeypress="if (event.keyCode == 13){ '.$onclick.' }">';
	if($span!='')$output .= '</span>';
	return $output;
}
function makeSpecialButton($value,$onclick='',$span='',$title='',$id='',$class=''){
	$output .= '';
	if($span)$output .= '<span id="'.$span.'">';
	$output .= '<input type=button value="'.$value.'" ';
	if($title)$output .= 'title="'.$title.'"';
	if($onclick)$output .= 'onclick="'.$onclick.'"';
	if($id)$output .= 'id="'.$id.'"';
	if($name)$output .= 'name="'.$id.'"';
	if($class)$output .= 'class="'.$class.'"';
	else $output .= 'class=button';
	$output .= '>';
	if($span)$output .= '</span>';
	return $output;
}
function makeSelect($values,$id='',$onchange='',$span='',$title='',$data=''){
	$output .= '';
	if($span!='')$output .= '<span id="'.$span.'">';
	$output .= '<select title="'.$title.'" onchange="'.$onchange.'" id="'.$id.'" name="'.$id.'" ><option value="">Choose</option>';
	if($values){foreach($values as $key=>$value){
		$output .= '<option value="'.$key.'"';
		if($data&&$data==$key)$output .= ' selected ';
		$output .= '>'.$value.'</option>';
	}}
	$output .= '</select >';
	if($span!='')$output .= '</span>';
	return $output;
}
function makeHiddenfield($value='',$span='',$id=''){
	$output .= '';
	if($span!='')$output .= '<span id="'.$span.'">';
	$output .= '<input type=hidden value="'.$value.'" id="'.$id.'" name="'.$id.'">';
	if($span!='')$output .= '</span>';
	return $output;
}
function makeTextfield($value='',$placeholder='',$onkeypress='',$width='150',$span='',$title='',$id='',$onfocus='',$style='',$onchange=''){
	$value = str_replace('"', '\'',$value);
	if($width=='')$width = strlen ( $value ).'em;';
	$output .= '';
	if($span!='')$output .= '<span id="'.$span.'">';
	if($width>250)
		$output .= '<textarea placeholder="'.$placeholder.'" style="width:550px;height:100px;'.$style.'" title="'.$title.'" onkeypress="if (event.keyCode == 13){ '.$onkeypress.' }" onchange="'.$onchange.'" onfocus="'.$onfocus.'" id="'.$id.'" name="'.$id.'">'.$value.'</textarea>';
	else
		$output .= '<input type=text value="'.$value.'" placeholder="'.$placeholder.'" style="width:'.$width.'px;'.$style.'" title="'.$title.'" onkeypress="if (event.keyCode == 13){ '.$onkeypress.' }" onchange="'.$onchange.'" onfocus="'.$onfocus.'" id="'.$id.'" name="'.$id.'">';
	
	if($span!='')$output .= '</span>';
	return $output;
}
function editable($aid,$field,$data=null,$onclick=null,$second=null,$type=null,$table='orders',$values=null,$save=null,$nobutton=null,$data2=null){
	if($type=='date')$width='80';
	else{
		if($data)$width = strlen ( $data ).'em;';
		else $width='60';
	
	}
	$output = '';
	if(!$second)$output .= '<font id="edit'.$field.$aid.'">';
	
	if($data&&$type!='checkbox'){
		$output .= '<font id="showedit'.$field.$aid.'">';
		$output .= '<font onclick="showedit3(\''.$aid.','.$field.'\')" title="Click to edit \''.$field.'\' field (AppID'.$aid.')">';
	
		if($type=='select')$output .= $values[$data];
		elseif($data2) $output .= '<font title="'.$data.'">'.make_clickable($data2).'</font>';
		else $output .= make_clickable($data);
		if(!$nobutton)$output .= makeButton($value='Edit',$onclick='',$span='',$title='',$id='');
		$output .= '</font>';
		$output .= '</font>';
		$hide = 'style="display:none"';
	}
	
	$output .= '<font id="edit2'.$field.$aid.'" '.$hide.'>';
	if(!$onclick)$onclick = 'saveafieldlocal(\''.$field.','.$table.','.$type.','.$aid.'\')';
	if($nobutton)$onchange=$onclick;
	else $onchange='';
	if($type=='select')$output .= makeSelect($values,$id=$field.$aid,$onchange,$span='',$title='',$data);
	elseif($type=='checkbox'&&!$data)
		$output .= '<input type=checkbox onclick="saveafieldlocal3(\''.$field.','.$table.',1,'.$aid.','.$type.'\')" id="'.$field.$aid.'">';
	elseif($type=='checkbox'&&$data)
		$output .= makeImg($src='images/checkmark_small.gif','saveafieldlocal3(\''.$field.','.$table.',0,'.$aid.','.$type.'\')',$style='cursor: pointer;');
	else 
		$output .= makeTextfield($value=$data,$placeholder,$onkeypress=$onclick,$width,$span='',$title='Press enter to save',$id=$field.$aid,$onfocus='',$style='').' ';
	
	if($type=='date')$output .= makeImg($src='images/cal.gif',$onclicki='displayDatePicker(\''.$field.$aid.'\')',$style='cursor: pointer;',$title='Choose date').' ';
	if(!$nobutton){
		if(!$save)$save = 'Save';
		if($type!='checkbox')$output .= makeButton($value=$save,$onclick,$span='',$title='',$id='',$style='');
	}
	$output .= '</font>';
	
	if(!$second)$output .= '</font>';
	
	return $output;
}



// =============

function querytime_before(){
	list($usec, $sec) = explode(' ',microtime());
	return $querytime_before = ((float)$usec + (float)$sec);
}
function qtime($querytime_before){
	list($usec, $sec) = explode(' ',microtime()); 
	$querytime_after = ((float)$usec + (float)$sec);
	return $querytime = $querytime_after - $querytime_before; 
}

function stripslashes_deep($value) {
	$value = is_array($value) ?
	array_map('stripslashes_deep', $value) :
	stripslashes($value);

	return $value;
}


