<form action="?p=login" method="POST">
<span>Email:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="username" value="<?php if(isset($_POST['username']))echo $_POST['username']; ?>"/>
<br/> 
<span>Password:</span> <input type="password" name="password"/>
<br/>
<span><input type="checkbox" name="rememberme" id="stay" <?php if(isset($_SESSION['rememberme'])&&$_SESSION['rememberme'] == "yes"){ echo "checked=\"checked\""; } ?>/></span>
<label for="stay" style="cursor:pointer">Stay signed in</label><br/><br/>
<input name="submit" type="submit" class="button" style="height: 25px;" value="Sign in" /><br/>
<span class="errors"><?php
if(isset($loginErrors['username']))echo $loginErrors['username'].'<br/>';
if(isset($loginErrors['password'])) echo $loginErrors['password']; 
?>
</form>
