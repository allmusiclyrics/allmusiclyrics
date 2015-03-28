<?php

if(isset($_POST['email'])){
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		if(strlen ($_POST['password'])>=5){
			if(!$userInfo=userInfo($_POST['email'])){
				echo createaccount($_POST);
			}else{
				$errors = 'Email already signed up';
				if($userInfo['verfied'])$errors .= '. Please check verification email to verify the account (check spam or junk folder/label)';
				else $errors .= ' and verified';
				
				if($userInfo['password'])$errors .= ', please <a href="?p=login">Login</a> or <a href="?p=contact">contact us to recover</a>';
				else $errors .= 'test';
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
	'Enter a password: <input type=password name=password placeholder="Type your password"><br/><br/>'.
	'<input type=submit value="Create">'.
	//'or use facebook: <div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="false" data-auto-logout-link="false"></div>'.
	'</form><br/><font color=red>'.$errors.'</font>';
	
}