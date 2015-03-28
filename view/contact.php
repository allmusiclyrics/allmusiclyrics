<?php

	$onclick = 'contactus();';
	echo 'Name: '.makeTextfield($_GET['contact_name'],$placeholder='Name (optional)',$onkeypress='',$width='200',$span='',$title='',$id='contact_name',$onfocus='',$style='',$onchange='');
	echo '<br>';
	echo 'Email: '.makeTextfield($_GET['contact_email'],$placeholder='Email',$onclick,$width='200',$span='',$title='',$id='contact_email',$onfocus='',$style='',$onchange='');
	echo '<br>';
	echo makeTextfield($_GET['contact_message'],$placeholder='Message',$onclick,$width='300',$span='',$title='',$id='contact_message',$onfocus='',$style='',$onchange='');
	echo '<br>';
	echo makeButton("Send",$onclick,$span='',$title='Send us your message!',$id='',$style='');