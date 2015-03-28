<!doctype html>
<html>

	<head>
		<!--<script src="//widget.battleforthenet.com/widget.min.js" async></script>-->
		<meta charset="utf-8"/>
		<title><?php echo $title; ?></title>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css">
		<script type="text/javascript" src="js/custom.js?v=<?php echo filemtime('js/custom.js'); ?>"></script>
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link rel="stylesheet" media="all" href=""/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<!--
		<script type="text/javascript">var p="http",d="static";if(document.location.protocol=="https:"){p+="s";d="engine";}var z=document.createElement("script");z.type="text/javascript";z.async=true;z.src=p+"://"+d+".adzerk.net/ados.js";var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(z,s);</script>
		
<script type="text/javascript">
var ados = ados || {};
ados.run = ados.run || [];
ados.run.push(function() {
/* load placement for account: bplotkin, site: Allmusiclyrics, size: 125x125 - Square Button*/
ados_add_placement(5397, 27934, "azk91906", 16);
ados_load();
});</script>
-->

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
 <link rel="stylesheet" type="text/css" href="css/style.css?v=<?php echo filemtime('css/style.css'); ?>">


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

<body lang="en" <?php if(isset($onload))echo $onload; ?> >





