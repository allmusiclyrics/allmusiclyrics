<?php$count=0;$getShowID=getShowID(mysql_real_escape_string($_GET['q']),1);if($getShowID){foreach($getShowID as $getShow){	$count++;	$showurl='?show='.str_replace (' ','-', $getShow['showname']).'&showid='.$getShow['showid'];	echo '<a title="See all Episodes from '. $getShow['showname'].'" ';	echo 'href="'.$showurl.'">'.$getShow['showname'].'</a> <br>';}}else{	$getEpisodes=getEpisodes4("where `title` like '%".mysql_real_escape_string($_GET['q'])."%' limit 10");	if($getEpisodes){foreach($getEpisodes as $getEpisode){		include('list.php');		$count++;	}}}//echo $count.' index.php?show='.str_replace (' ','-', $getShow['showname']);if($count==0){	echo '<gcse:searchresults-only></gcse:searchresults-only> ';	// Nothing was found for your search "'.$_GET['q'].'". <br>'.	// 'Please try to <a href="?p=allshows">browse TV shows</a> or <a href="?p=allmovies">browse movies</a> or try our Google custom search: <br>'.	// '<div id="cse" style="width: 100%;">Loading</div>// <script src="http://www.google.com/jsapi" type="text/javascript"></script>// <script type="text/javascript">   // google.load(\'search\', \'1\', {language : \'en\'});  // google.setOnLoadCallback(function() {    // var customSearchControl = new google.search.CustomSearchControl(\'001781526679990595887:wvo-noulenc\');    // customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);    // customSearchControl.draw(\'cse\');  // }, true);// </script>'	// ;}else	//echo '<div style="display:none"> <gcse:searchresults-only  ></gcse:searchresults-only> </div>';	if($count==1){	//header("Location: index.php?show=".str_replace (' ','-', $getShow['showname']));	//echo ("Location: index.php?show=".str_replace (' ','-', $getShow['showname']));	echo 'Loading...<meta http-equiv="Refresh" content="0;URL='.$showurl.'">';	//exit;}	