<?php

if(!isset($_SESSION['HTTP_REFERER'])&&isset($_SERVER['HTTP_REFERER'])){
	$_SESSION['HTTP_REFERER']=$_SERVER['HTTP_REFERER'];
	$HTTP_REFERER=preg_replace ('/\/?p=login/','',$_SESSION['HTTP_REFERER']);
	$HTTP_REFERER=preg_replace ('/\/?p=logout/','',$_SESSION['HTTP_REFERER']);
}
//$HTTP_REFERER=preg_replace ('/\//','',$HTTP_REFERER);

$REQUEST_URI=preg_replace ('/\/?p=login/','',$_SERVER['REQUEST_URI']);
$REQUEST_URI=preg_replace ('/\//','',$REQUEST_URI);

if (!isset($_COOKIE['expire'])) {
	if(isset($_POST['username'])&&isset($_POST['password'])){
		$_POST = parse_array();
		//$_POST['username'] = check_input($_POST['username']);
		$_POST['password'] = check_input($_POST['password']);
		$loginErrors = validate_login($_POST);
		if (isset($_POST['rememberme'])&&$_POST['rememberme'] == "on"){$remember = "yes";}else{$remember = "no";}
		if ($loginErrors) {
			// The subject
			//$subject = "Login Fail Attempt";
			//emailLoginAttempt($_POST,$subject);  logLoginFailAttempt($_POST);
			//if (isset($_POST['rememberme'])&&$_POST['rememberme'] == "on"){$remember = "yes";}else{$remember = "no";}
			//log to db
			//$_SESSION['counterror']++;
			// if($_SESSION['counterror']==$maxtries){					
				//if(updateLock($_POST['username'],1)){
					// $loginErrors['password']='Account locked. Call admin to unlock.';
					// $_SESSION['counterror']='';
					//logUserLoginStatus('Account locked (login)',$_POST['username'],$remember,$current_browser['Platform'],$current_browser['Parent']);
				//}					
			// }else{
				// $loginErrors['password']='';//logError($_POST,$remember,$current_browser['Platform'],$current_browser['Parent']);
				// ($_SESSION['counterror'])?$loginErrors['password'].='. You have '.($maxtries-$_SESSION['counterror']).' tries left.':'';
			// }
			//include('templates/_login.php');
		}else{
			//$_SESSION['rememberme']=$_POST['rememberme'];
			$short=time()+60*25; //short time (25 minutes) for those without 'rememberme'
			$long=time()+60*60*24*7; //Setting up the expiration time for long
			$verylong=time()+60*60*24*365; //Setting up the expiration time for very long
				
			//Check whether the 'remember me' box is checked
			if (isset($_POST['rememberme']) && $_POST['rememberme'] == "on"){
				setcookie('expire','no',$verylong, "/",""); //expire is set to no with very long expiration
				$remember = "yes";
			}	else	{
				setcookie('expire',$short,$short, "/",""); //expire is set to short
				//$_SESSION['LAST_ACTIVITY'] = time(); //set the time for last active
				$remember = "no";
			}
			//setcookie('logtime',time(),$verylong); //login time cookie with very long expiration
			setcookie('username',$_POST['username'],$verylong);//)echo 'worked';
			
			$_SESSION['user'] = userInfo($_POST['username']);
			$redirect=1;
		}
	}
}else{echo 'test';
	if(!isset($_SESSION['user']))
		$_SESSION['user'] = userInfo($_COOKIE['username']);
	$redirect=1;
}

if(isset($redirect)){
	if(isset($HTTP_REFERER))header('Location: '.$HTTP_REFERER);
	else header('Location: /'.$REQUEST_URI);
	exit;	
}

