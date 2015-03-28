<?php
//echo print_r($_GET);
$song=$_GET['song'].' by '.$_GET['artist'];
$songid=saveSong($song,$_GET['episodeid'],$deleted=1,$_GET['desc']);

$body='Song: '.$song.' Desc:'.$_GET['desc'].' <br>
Episode: <a href="http://i.allmusiclyrics.info/?id='.$_GET['episodeid'].'">'.$_GET['episodeid'].'</a><br><br>'.
'<a href="http://i.allmusiclyrics.info/?approvesong='.$songid.'">Approve '.$songid.'</a><br><br>';

$headers = 'From: <boris.plotkin@gmail.com>'. "\r\n" ;
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

//echo $body;
mail($to='boris.plotkin@gmail.com', $subject='Song added', $body, $headers);

echo 'Your song "'.$_GET['song'].'" was submitted and will be posted once approved.<br>Add another:<br>

		<input type=text placeholder="Song name" id="song" onkeypress="if (event.keyCode == 13) addsong()" >*
		<input type=text placeholder="Artist" id="artist" onkeypress="if (event.keyCode == 13) addsong()" ><br>
		<input type=text placeholder="Describe scene" id="desc" onkeypress="if (event.keyCode == 13) addsong()" >
		<input type="hidden" name="id" id="id" value="'.$_GET['episodeid'].'">
		<input type=button onclick="addsong()" value="Add Song">';
		
		
exit;

