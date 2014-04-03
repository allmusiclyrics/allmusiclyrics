function GetXmlHttpObject(){
	var xmlHttp=null;
	try	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}	catch (e)	{		
	// Internet Explorer
		try		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}	catch (e)		{
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
}
function refreshfunc() { 
	if (xmlHttp.readyState!=4)	{
		document.getElementById("loading").innerHTML = "<b>&nbsp;Loading...&nbsp;</b>";
		var runningRequest=true;
	}	else	{
		document.getElementById("loading").innerHTML = "";
		document.getElementById("refresh").innerHTML=xmlHttp.responseText;
	}
}
function changview(view,field){
	document.getElementById('button'+view).style.display='none';
	document.getElementById(view).style.display='block';
	document.getElementById(field).focus();
}
function updateshow(showid){
	var thetvdb = encodeURIComponent(document.getElementById("thetvdb").value);
	var lastseason = encodeURIComponent(document.getElementById("lastseason").value);
	//var airday = encodeURIComponent(document.getElementById("airday").value);
	$("#refresh").load("index.php?action=updateshow&thetvdb="+thetvdb+"&lastseason="+lastseason+"&showid="+showid);
}
function addmovie(){
	var movie = encodeURIComponent(document.getElementById("moviename").value);
	$("#refresh").load("index.php?action=addmovie&moviename="+movie);
}
function addshow(){
	var show = encodeURIComponent(document.getElementById("showname").value);
	$("#refresh").load("index.php?showname="+show);
}
function addsong(){
	var song = encodeURIComponent(document.getElementById("song").value);
	var desc = encodeURIComponent(document.getElementById("desc").value);
	var link = encodeURIComponent(document.getElementById("link").value);
	//var artist = encodeURIComponent(document.getElementById("artist").value);
	var episodeid = document.getElementById("id").value;
	if(song==''){
		alert('Must add song name and artist');
		document.getElementById("song").focus();
	}else{
		$("#addsong").load("index.php?action=addsong&song="+song+"&desc="+desc+"&episodeid="+episodeid+"&linktext="+link); //"&artist="+artist+
	}
}
function delitem(id){
	$("#song"+id).load("index.php?action=deletesong&itemid="+id);
}
function actionitem(action,id,field){
	var data = encodeURIComponent(document.getElementById(field+id).value);
	$("#"+field+"area"+id).load("index.php?action="+action+"&itemid="+id+"&data="+data+"&field="+field);
}
function actionitem2(action,id,field){
	//alert(action+" "+id+" "+field);
	$("#"+field+id).load("index.php?action="+action+"&itemid="+id+"&field="+field);
}

function addLink(id){
	var link = encodeURIComponent(document.getElementById("link"+id).value);
	//var episodeid = document.getElementById("id").value;
	$("#linkarea"+id).load("index.php?action=addlink&songid="+id+"&linktext="+link);//"&id="+episodeid+
}
function sub(id){
	$("#sub"+id).load("index.php?action=sub&showid="+id+"&checked="+document.getElementById("subbox"+id).checked);//"&id="+episodeid+
}
function sub2(id){
	$("#sub"+id).load("index.php?action=sub&showid="+id+"&eid="+document.getElementById("eid").value+"&checked="+document.getElementById("subbox"+id).checked);//"&id="+episodeid+
}
function unsub(eid) {
	var answer = confirm ("Are you sure you want to unsubscribe from ALL? click OK to continue.")
	if (answer)window.location="?action=unsubscribeall&eid="+eid;
}
function delLink(linkid){
	var episodeid = document.getElementById("id").value;
	$("#onelink"+linkid).load("index.php?action=dellink&linkid="+linkid+"&id="+episodeid);
}
function sendData(action,id,data,link){
	$.get("index.php?action="+action+"&itemid="+id+"&data="+data);
	//document.getElementById("thelink"+id+"").href=link; 
}