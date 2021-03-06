<br><br>

Started in <a href="http://lyrics.showmusic.ml/2008/11/all-music-lyrics.html">2008</a> as a blog for lyrics, we then added song names and links to intro and credit music in popular TV shows and movies.<br>
Since then we developed this site to automate the pages for episodes and ability to add songs by anyone.
<br><br>

<a href="?p=signup">Sign up</a> to subscribe to your favorite shows and receive emails when it is posted with songs..<br><br>

<?php
echo 'Our database has <b>';
echo number_format(select_table($table='episodes',$fields=null,$where=null,$display=null,$countonly=1));
echo '</b> episodes from <b>';
echo number_format(select_table($table='shows',$fields=null,$where=null,$display=null,$countonly=1));
echo '</b> shows, containing <b>';
echo number_format(select_table($table='songs',$fields=null,$where=null,$display=null,$countonly=1)); 
echo '</b> songs.<br>'; 
echo 'To date we had <b>';
echo number_format(select_table($table='episodes',$fields=null,$where=null,$display=null,null,'views')); 
echo '</b> total page views on episode pages, and <b>';
echo number_format(select_table($table='songs',$fields=null,$where=null,$display=null,null,'clickcount'));
echo '</b> total clicks on song links.<br>';
?>

<br>
Also feel free to take a look at the <a href="?p=popularshows">popular shows</a> and <a href="?p=popularlinks">popular song links</a>.<br><br>

<?php 

if($_SESSION['user']['department']=='admins'){
	echo 'ADMIN: ';
	echo ' <a href="?p=hourlyupdate" target="_blank">HourlyUpdate</a> ';
	echo '<br><br>';
}
?>

The code is open source for anyone to view/edit/modify, if you would like to contribute you can find the latest code on <a href="https://github.com/allmusiclyrics/allmusiclyrics" target="_blank">github</a>.<br><br>

The web development, hosting and updating of the content is supported by users like you and our ad network: <a href="http://adf.ly/?id=1559170">adf.ly</a> and <br> <a href="?p=donate">donations</a> using Bitcoin: <a href="bitcoin:<?php echo $btc;?>"><?php echo $btc; ?></a>. <!-- or paypal:

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<div style="text-align: center;">
<input name="cmd" type="hidden" value="_s-xclick" />
<input name="encrypted" type="hidden" value="-----BEGIN PKCS7-----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-----END PKCS7-----
" />
<input alt="PayPal - The safer, easier way to pay online!" border="0" name="submit" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" type="image" /><img alt="" border="0" height="1" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" />
</div>
</form>
-->
Thank you.
<br><br>

Like us on facebook: 
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-like-box" data-href="http://www.facebook.com/Allmusiclyrics" data-width="292" data-show-faces="false" data-stream="false" data-header="true"></div>

<br><br>

