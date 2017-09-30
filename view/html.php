<?php
/* 
** ======================= HTML STARTS ================
**/

include(ROOTPATH.'/view/title.php');
include(ROOTPATH.'/view/header.php');


/* if(isset($_GET['p'])&&$_GET['p']=='logout'){
	echo '<script type="text/javascript">FB.logout(function(response) { // Person is now logged out
    });</script>';
} */

echo '<div class="topbar">';
include(ROOTPATH.'/view/buttons.php');
echo '</div>';
echo '<span id="refresh">';
echo '<span id="status"></span>';

//////======================= main headers ============


echo '</span>';
