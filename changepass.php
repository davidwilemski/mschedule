<?
include_once 'inc/common.php';
include_once 'inc/accesscontrol.php';
include_once 'inc/db.php';

getdata(array('old', 'new1', 'new2'), 'post', 'password');

if($new1 != ''){
	if($new1 == $new2){
		dbConnect();
		$new1 = mysql_real_escape_string($new1);
		sql("update `users` set `password` = OLD_PASSWORD('$new1') where `uniqname` = '$auth_uniqname' limit 1");
		//sql("delete from `unconfirmed` where uniqname = '$uniqname'");
		header("Location: logout.php");
	}else{
		error("Passwords did not match. Please try again.");
	}
}



showhtmlhead("Change Password");
?>
Do NOT use your UofM password here, as we can not guarantee the security of this site. You will be logged off as soon as you change your password.
<form method="post"  autocomplete="off" action="<?=$_SERVER['PHP_SELF']?>"> 
<table>
<!--<tr><td>Old Password</td><td align="right"><input type="password" name="old"></td></tr>-->
<tr><td>New Password</td><td align="right"><input type="password" name="new1"></td></tr>
<tr><td>New Password Again</td><td align="right"><input type="password" name="new2"></td></tr>
<tr><td align="right" colspan=2><input type="reset"><input type="submit" name="submit" value="Change Password"></td></tr>
</table>
<?
showhtmlfoot();
?>
