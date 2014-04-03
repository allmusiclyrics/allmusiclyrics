<!doctype html>
<html>

	<head>
		<meta charset="utf-8"/>
		<title><?php echo $title; ?></title>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css">
		<script type="text/javascript" src="custom.js?v=<?php echo filemtime('custom.js'); ?>"></script>
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link rel="stylesheet" media="all" href=""/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<script type="text/javascript">var p="http",d="static";if(document.location.protocol=="https:"){p+="s";d="engine";}var z=document.createElement("script");z.type="text/javascript";z.async=true;z.src=p+"://"+d+".adzerk.net/ados.js";var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(z,s);</script>
<script type="text/javascript">
var ados = ados || {};
ados.run = ados.run || [];
ados.run.push(function() {
/* load placement for account: bplotkin, site: Allmusiclyrics, size: 125x125 - Square Button*/
ados_add_placement(5397, 27934, "azk91906", 16);
ados_load();
});</script>

<?php if(!isset($_SESSION['user'])){ ?>
<script type="text/javascript"> 
    var adfly_id = 1559170; 
    var adfly_advert = 'banner'; 
    var frequency_cap = 5; 
    var frequency_delay = 15; 
    var init_delay = 0; 
</script> 
<script src="http://cdn.adf.ly/js/entry.js"></script> 
<?php } ?>

		<!-- Adding "maximum-scale=1" fixes the Mobile Safari auto-zoom bug: http://filamentgroup.com/examples/iosScaleBug/ -->
<style type="text/css">		
		/*	Less Framework 4
	http://lessframework.com
	by Joni Korpi
	License: http://opensource.org/licenses/mit-license.php	*/


/*	Resets
	------	*/

html, body, div, span, object, iframe, h1, h2, h3, h4, h5, h6, 
p, blockquote, pre, a, abbr, address, cite, code, del, dfn, em, 
img, ins, kbd, q, samp, small, strong, sub, sup, var, b, i, hr, 
dl, dt, dd, ol, ul, li, fieldset, form, label, legend, 
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, figure, figcaption, hgroup, 
menu, footer, header, nav, section, summary, time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
}

article, aside, canvas, figure, figure img, figcaption, hgroup,
footer, header, nav, section, audio, video {
	display: block;
}

a img {border: 0;}



/*	Typography presets
	------------------	*/

.gigantic {
	font-size: 110px;
	line-height: 120px;
	letter-spacing: -2px;
}

.huge, h1 {
	font-size: 68px;
	line-height: 72px;
	letter-spacing: -1px;
}

.large, h2 {
	font-size: 42px;
	line-height: 48px;
}

.bigger, h3 {
	font-size: 26px;
	line-height: 36px;
}

.big, h4 {
	font-size: 22px;
	line-height: 30px;
}

body {
	font: 16px/24px Georgia, serif;
}

.small, small {
	font-size: 13px;
	line-height: 18px;
}

/* Selection colours (easy to forget) */

::-moz-selection 	{background: rgb(255,255,158);}
img::selection 		{background: transparent;}
img::-moz-selection	{background: transparent;}




/*		Default Layout: 992px. 
		Gutters: 24px.
		Outer margins: 48px.
		Leftover space for scrollbars @1024px: 32px.
-------------------------------------------------------------------------------
cols    1     2      3      4      5      6      7      8      9      10
px      68    160    252    344    436    528    620    712    804    896    */

body {
	width: 90%;
	padding: 72px 48px 84px;
	background: rgb(232,232,232);
	color: rgb(60,60,60);
	-webkit-text-size-adjust: 100%; /* Stops Mobile Safari from auto-adjusting font-sizes */
}



/*		Tablet Layout: 768px.
		Gutters: 24px.
		Outer margins: 28px.
		Inherits styles from: Default Layout.
-----------------------------------------------------------------
cols    1     2      3      4      5      6      7      8
px      68    160    252    344    436    528    620    712    */

@media only screen and (min-width: 768px) and (max-width: 991px) {
	
	body {
		width: 712px;
		padding: 48px 28px 60px;
	}
}



/*		Mobile Layout: 320px.
		Gutters: 24px.
		Outer margins: 34px.
		Inherits styles from: Default Layout.
---------------------------------------------
cols    1     2      3
px      68    160    252    */

@media only screen and (max-width: 767px) {
	
	body {
		width: 252px;
		padding: 48px 34px 60px;
	}
	
}



/*		Wide Mobile Layout: 480px.
		Gutters: 24px.
		Outer margins: 22px.
		Inherits styles from: Default Layout, Mobile Layout.
------------------------------------------------------------
cols    1     2      3      4      5
px      68    160    252    344    436    */

@media only screen and (min-width: 480px) and (max-width: 767px) {
	
	body {
		width: 436px;
		padding: 36px 22px 48px;
	}
	
}
</style>

<script>
  (function() {
    var cx = '001781526679990595887:wvo-noulenc';
    var gcse = document.createElement('script'); gcse.type = 'text/javascript'; gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(gcse, s);
  })();
</script>

	</head>
	
	<body lang="en" <?php echo $onload; ?> >
		