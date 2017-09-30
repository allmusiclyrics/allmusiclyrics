

function statusChangeCallback(response) {
	if (response.status === 'connected') {
		FB.api('/me', function(response2) {
			//document.getElementById('status').innerHTML =	'Thanks for logging in, ' + response2.email + '!';
			$.get("index.php?action=fblogin&email="+response2.email);
		});
	}
}

window.fbAsyncInit = function() {
	FB.init({
	appId      : '825953444111088',
	cookie     : true,  // enable cookies to allow the server to access 
						// the session
	xfbml      : true,  // parse social plugins on this page
	version    : 'v2.1' // use version 2.1
	});
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
}
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
function focuson(str){
	document.getElementById(str).focus();
}
function changview(view,field){
	document.getElementById('button'+view).style.display='none';
	document.getElementById(view).style.display='block';
	document.getElementById(field).focus();
}
function handleClick(view,data,value){
	$("#edit"+view).load("index.php?action="+view+"&data="+data+"&value="+value);
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
function contactus(){
	var contact_name = document.getElementById("contact_name").value;
	var contact_email = encodeURIComponent(document.getElementById("contact_email").value);
	var contact_message = encodeURIComponent(document.getElementById("contact_message").value);
	if(contact_email==''){
		alert('Email cannot be empty');
		document.getElementById("contact_email").focus();
	}else if(contact_message==''){
		alert('Message cannot be empty');
		document.getElementById("contact_message").focus();
	}else{
		$("#contactus").load("index.php?action=contactus&contact_message="+contact_message+"&contact_email="+contact_email+"&contact_name="+contact_name); //"&artist="+artist+
	}
}
function addsong(){
	var song = encodeURIComponent(document.getElementById("song").value);
	var desc = encodeURIComponent(document.getElementById("desc").value);
	//var link = encodeURIComponent(document.getElementById("link").value);
	//var artist = encodeURIComponent(document.getElementById("artist").value);
	var episodeid = document.getElementById("id").value;
	if(song==''){
		alert('Must add song name and artist');
		document.getElementById("song").focus();
	}else{
		$("#addsong").load("index.php?action=addsong&song="+song+"&desc="+desc+"&episodeid="+episodeid); //"&artist="+artist+"&linktext="+link
	}
}
function delitem(id){
	$("#song"+id).load("index.php?action=deletesong&itemid="+id);
}
function actionitem3(action,id,field){
	var data = encodeURIComponent(document.getElementById(action+id).value);
	$("#"+field+"area"+id).load("index.php?action="+action+"&itemid="+id+"&data="+data+"&field="+field);
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