<?php
include_once 'inc/common.php';
include_once 'inc/db.php';

getdata(array('submit', 'uniqname'));

if($submit = 'send' and $uniqname != ''){
	// Check for existing user 
	$result = sql("SELECT COUNT(*) FROM users WHERE uniqname = '$uniqname'"); 
	
	//user already registered
	if(@mysql_result($result,0,0) == 0) { 
		error("This uniqname has not been registered. If this is your uniqname, please <a href=\"register.php?uniqname=$uniqname\">register</a>.");
	}
	$code = substr(md5(time()),0,6);
	
	sql("INSERT INTO unconfirmed SET 
             uniqname = '$uniqname',
             code = '$code',
             time = now()");
	$message = "
A password reset was requested for your account at Mschedule.com. 
If you wish to go through with the reset please follow the link below.
You will need your uniqname and the code printed below as well.
If not, just ignore this message and your password will stay the same.

http://www.mschedule.com/confirm.php

uniqname: $uniqname
code: $code
";
	sendemail($message, '', 'Password Reset Request', "$uniqname@umich.edu");
	status("A confirmation email has been sent to <b>$uniqname@umich.edu</b>");
}

showHTMLhead("Reset Password");
?>
Type in your uniqname below and click Send. It will send you instructions for reseting your password to your email address.
<form method="post" name=form autocomplete="off" action="<?=$_SERVER['PHP_SELF']?>">
<table border="0" cellpadding="0" cellspacing="5">
   <tr> 
       <td> 
           <input name="uniqname" type="text" value="<?=$_GET['uniqname']?>" maxlength="8" size="10" /> @umich.edu
       </td> 
   </tr> 
       <td align="right" colspan="2"> 
           <hr noshade="noshade" /> 
           <input type="submit" name="submit" value="Send" /> 
       </td> 
   </tr> 
</table> 
</form>
<script>
<!--
document.form.uniqname.focus();
// -->
</script>
<?
showHTMLfoot();
?>

