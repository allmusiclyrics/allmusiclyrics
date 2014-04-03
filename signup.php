<?php

if(isset($_POST['email'])){
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		if(strlen ($_POST['password'])>=5){
			if(!userInfo($_POST['email'])){
				$_POST['verify']=uniqid();
				if($eid=insert_user($_POST)){
					if($email=emailverification($_POST)){
						echo 'Email verification was sent to '.$_POST['email'].' <br>Make sure to check the junk or spam folder. Once verified you can <a href="?p=login">Login</a>.';
					}else{
						$errors = 'Error emailing verification. Click <a href="?action=emailverification&email='.urlencode($_POST['email']).'">here</a> to try again or '.
						'<a href="?p=contact">email</a> about this issue.<br>'.print_r($email);
					}
				}else{
					$errors = 'Error creating user account. Try again or <a href="?p=contact">email</a> about this issue.';
				}
			}else{
				$errors = 'Email already signed up. <a href="?p=login">Login</a> or <a href="?p=contact">emails to recover</a>';
			}
		}else{
			$errors = 'Password too short.';
		}
	}else{
		$errors = 'Email incorrect format.';
	}
}else{
	$errors='';
	$_POST['email']='';
}

if(isset($errors)){
	echo '<form action="?p=signup" method="POST">'.
	'Benifits of signing up: ability to subscribe to shows - get emails when they air with songs, eliminate the advertising shown<br>'.
	'**We will never sell or rent your email, we hate spam too.**<br><br>'.
	'Enter your email: <input type=text value="'.$_POST['email'].'" name=email placeholder="Type your email" required pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" /><br/>'.
	'Enter a password: <input type=password name=password placeholder="Type your password"><br/>'.
	'<input type=submit value=Submit></form><br/><font color=red>'.$errors.'</font>';
	
}