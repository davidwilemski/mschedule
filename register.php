<?php // register.php 
include_once 'inc/common.php';
include_once 'inc/db.php';

getdata(array('submit', 'uniqname', 'fullname'));

if ($submit != "ok"):

getdata(array('uniqname'), 'get');

// Display the user signup form 
$body = <<<END
<p>All fields are required. Your username must be the same as your <br>
uniqname at UofM. You will be emailed a confirmation with more <br>
instructions. Sorry, if you are not from UofM, you cannot use <br>
this system... yet.</p> 
<form method="post"  autocomplete="off" action="{$_SERVER['PHP_SELF']}"> 
<table border="0" cellpadding="0" cellspacing="5">
   <tr> 
       <td align="right"> 
           <p>Uniqname</p> 
       </td> 
       <td> 
           <input name="uniqname" type="text" value="$uniqname" maxlength="8" size="10" /> @umich.edu
       </td> 
   </tr> 
   <tr> 
       <td align="right"> 
           <p>Full Name</p> 
       </td> 
       <td> 
           <input name="fullname" type="text" maxlength="100" size="25" /> 
       </td> 
   </tr> 
   <tr> 
       <td align="right" colspan="2"> 
           <hr noshade="noshade" /> 
           <input type="reset" value="Reset Form" /> 
           <input type="submit" name="submit" value="   OK   " /> 
       </td> 
   </tr> 
</table> 
</form> 
END;
showHTMLPage("New User Registration", $body);

else: 
   // Process signup submission 
   dbConnect();
   
   $uniqname = strtolower(trim($uniqname));
   $fullname =  trim($fullname);
   $address = $uniqname.'@umich.edu';
    
   if ($uniqname == '' or $fullname == '') {
       error('One or more fields were left blank, or is invalid.');
   }
   
   // Check for existing user with the new id 
   $result = sql("SELECT COUNT(*) FROM users WHERE uniqname = '$uniqname'"); 
   
   //user already registered
   if (@mysql_result($result,0,0)>0) { 
       error("This uniqname has already been registered. Please check your email, <b>$uniqname@umich.edu</b>, for the confirmation link or try <a href=\"passreset.php\">reseting your password</a>.");
   }
   

//create confirmation code for user
   $code = substr(md5(time()),0,6);
   
	//store user info in database
	sql("INSERT INTO users SET 
             uniqname = '$uniqname', 
             password = '', 
             fullname = '$fullname',
             time = now()"); 
             // password = PASSWORD('$newpass'), 
	sql("INSERT INTO unconfirmed SET 
             uniqname = '$uniqname', 
             code = '$code',
             time = now()");

	//create new user account email
	$to = $fullname." <".$address.">"; 
	$from = $myname." <".$myaddress.">";
	$subject = 'Mschedule Login';

$message = "
To get started with Mschedule.com, click the link below to set your initial password for the site. You will need your uniqname and the code printed below.
http://www.mschedule.com/confirm.php

uniqname: $uniqname
code:	$code
";    

//send email
   //mail($address, $subject, $message, $header);
   sendemail($message, $from, $subject, $to);

//show registration completion page
$body = " 
   <p>Your confirmation code has been emailed to you at 
      <strong>$address</strong>. Please check your email and follow the instructions there.</p> 
";

      showHTMLPage("Registration Complete", $body);
endif; 
?>
