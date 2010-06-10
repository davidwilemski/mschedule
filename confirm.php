<?
include_once 'inc/common.php';
include_once 'inc/db.php';

getdata(array('uniqname', 'code', 'submit'));
getdata(array('new1', 'new2'), 'post', 'password');

if($new1 != ''){
	if($new1 == $new2){
		$result = sql("select * from `unconfirmed` where uniqname = '$uniqname'");
		if(mysql_num_rows($result) == 0){
			error("This uniqname, <b>$uniqname</b>, has already been confirmed or has not been registered yet. Please login or register.");
		}
		
		$result = sql("select * from `unconfirmed` where uniqname = '$uniqname' and code = '".mysql_real_escape_string($code)."'");
		if(mysql_num_rows($result) > 0){
			sql("update `users` set `password` = OLD_PASSWORD('".mysql_real_escape_string($new1)."') where `uniqname` = '$uniqname'");
			sql("delete from `unconfirmed` where uniqname = '$uniqname'");
		}else{
			error("Uniqname and Code did not match. Please try again.");
		}
		header("Location: logout.php");
		exit;
	}else{
		error("Passwords did not match. Please try again.");
	}
}

getdata(array('uniqname', 'code'), 'get');

showhtmlhead("Confirm Email");
?>
Do NOT use your UofM password here, as we can not guarantee the security of this site. 
You will be logged off as soon as you change your password.
<form method="post"  autocomplete="off" action="<?=$_SERVER['PHP_SELF']?>">
<table>
<tr><td>Uniqname</td><td align="right"><input type="text" name="uniqname" value="<?=$uniqname?>"></td></tr>
<tr><td>Confirmation Code</td><td align="right"><input type="text" name="code" value="<?=$code?>"></td></tr>
<tr><td>New Password</td><td align="right"><input type="password" name="new1"></td></tr>
<tr><td>New Password Again</td><td align="right"><input type="password" name="new2"></td></tr>
<tr><td align="right" colspan=2><input type="reset"><input type="submit" name="submit" value="Create Password"></td></tr>
</table>
<?
showhtmlfoot();
?>
