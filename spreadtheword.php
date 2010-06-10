<?php
include_once 'inc/common.php';
$auth_uniqname = $_SESSION['uniqname'];
$fullname = $_SESSION['fullname'];


getdata(array('name', 'submit'), 'get');
getdata(array('new_uniqname'), 'get', 'uniqname');
if($submit == 'send'){
	if($new_uniqname == '' or $name == ''){
		error("One or more fields were left blank or were invalid.");
	}
	$result = sql("SELECT uniqname FROM $users WHERE uniqname = '$new_uniqname'");
	if(mysql_num_rows($result) > 0){
		status("This user, '$new_uniqname', has already registered");
	}else{
		$result = sql("SELECT uniqname FROM $invites WHERE uniqname = '$new_uniqname'");
		if(mysql_num_rows($result) > 0){
			status("This user, '$new_uniqname', has already been invited, but has not registered yet. Shoot them a reminder email.");
		}else{
			if($_SESSION['uniqname']){
				$email = $new_uniqname.'@umich.edu';
			}else{
				$message = "This invite was sent anonymously to uniqname: $new_uniqname\n\n\n";
				global $myaddress;
				$email = $myaddress;
			}
			$message .= "Dear $name,

$fullname ($auth_uniqname@umich.edu) has invited you to 
register for Mschedule.com at the following address:

http://www.mschedule.com/

This tool will allow you to share your class schedule and allow
you to see who is in your classes based on what other people enter.
Maybe you have some classes with this person...";
			sendemail($message, '', "Invite for Mschedule.com", $email);
			sql("INSERT INTO $invites VALUES ('$new_uniqname', '$auth_uniqname', now())");
			status("An email will be sent to $new_uniqname@umich.edu inviting them to join");
		}
	}	
}
	

showhtmlhead("Spread the Word");

?>
<p>Let your friends know about this site so that you can see if any of them are in your classes. <br>
If you don't know their uniqname, you can look it up in the <a target="_new" href="http://directory.umich.edu/">directory</a>. <br>
This form will send them an email inviting them to join.</p>
<form method="get"  autocomplete="off" action="<?=$_SEVER['PHP_SELF']?>">
<table border="0" cellpadding="0" cellspacing="5">
   <tr>
       <td align="right"> 
           <p>Uniqname</p>
       </td> 
       <td>
           <input name="new_uniqname" type="text" value="<?=$new_uniqname?>" maxlength="8" size="10" /> @umich.edu
       </td>
   </tr>
   <tr>
       <td align="right"> 
           <p>Name</p>
       </td>
       <td>
           <input name="name" type="text" value="<?=$name?>" maxlength="100" size="25" /> 
       </td> 
   </tr> 
   <tr> 
       <td align="right" colspan="2">
           <hr noshade="noshade" />
           <input type="submit" name="submit" value="Send" />
       </td> 
   </tr> 

</table>
</form>



<?
if($auth_uniqname){
	echo "<p>Copy and paste the code below into your blog, webpage, AIM profile, or any other place that takes html. <br>
	When clicked on, it will display your schedule if you have Priavcy set to Public.</p>";

	echo "<i>".htmlentities("<a href=\"http://www.mschedule.com/view.php?uniqname=$auth_uniqname\">")
	. "<a href=\"http://www.mschedule.com/view.php?uniqname=$auth_uniqname\">My Schedule</a>".htmlentities("</a>")."</i>";
}
showhtmlfoot();

?>
