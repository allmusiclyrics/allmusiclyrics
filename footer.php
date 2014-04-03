<br><br><hr>
BitCoin: <a href="bitcoin:17qxZajF3Mz2vTKPLx4Y2kBHqWPBCQyjuh">17qxZajF3Mz2vTKPLx4Y2kBHqWPBCQyjuh</a>
<br><br>
<?php if(!isset($_GET['id'])){ ?>
<!-- facebook part -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-like-box" data-href="http://www.facebook.com/pages/Allmusiclyrics/268906969809709" data-width="292" data-show-faces="false" data-stream="false" data-header="true"></div>
<?php  } ?>
<br>
Trending:<br>
<!-- AddThis Trending Content BEGIN -->
<div id="addthis_trendingcontent"></div>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4eb780d361b3ef1f"></script>
<script type="text/javascript">
addthis.box("#addthis_trendingcontent", {
    feed_title : "",
    feed_type : "trending",
    feed_period : "week",
    num_links : 5,
    height : "auto",
    width : "auto"});
</script>

<div align="right">
<!-- AddThis Trending Content END -->


<?php 
//if ($_SERVER['HTTP_HOST']!='localhost'){ ?>

<!-- <script type="text/javascript">
//default banner house ad url 
clicksor_default_url = '';
clicksor_banner_border = '#7a7a7a'; clicksor_banner_ad_bg = '#b2b2b2';
clicksor_banner_link_color = '#000000'; clicksor_banner_text_color = '#000000';
clicksor_banner_image_banner = true; clicksor_banner_text_banner = true;
clicksor_layer_border_color = '#7a7a7a';
clicksor_layer_ad_bg = '#b2b2b2'; clicksor_layer_ad_link_color = '#000000';
clicksor_layer_ad_text_color = '#000000'; clicksor_text_link_bg = '';
clicksor_text_link_color = ''; clicksor_enable_text_link = false;
clicksor_layer_banner = false;
</script>
<script type="text/javascript" src="http://ads.clicksor.com/newServing/showAd.php?nid=1&amp;pid=302570&amp;adtype=8&amp;sid=497359"></script>
<noscript><a href="http://www.yesadvertising.com">affiliate marketing</a></noscript> -->

<!--<script type="text/javascript" src="http://www.adcash.com/script/java.php?option=rotateur&rotateur=119999"></script>-->

<!--<script type="text/javascript" src="http://www.adcash.com/script/java.php?option=rotateur&rotateur=120006"></script>
<img src="adhere.jpg">
-->
<div id="azk91906"></div>
<br>
<a href="?p=contact&ref=ad">Your Ad Here</a>
<?php// } ?>
</div>

<br><br><hr>

<?php

include('buttons.php');
//if(!isset($_SESSION['user'])&&$_SESSION['user']['department']!='admins')
	include('analytics.php');

if($server=getServer($_SERVER["SERVER_ADDR"])) echo ' <font style="color:#E8E8E8;">'.$server.'</font>';

?>
</body>
	
</html>