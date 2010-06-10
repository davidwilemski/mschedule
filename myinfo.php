<?
include_once 'inc/accesscontrol.php';
include_once 'inc/common.php';
include_once 'inc/db.php';


getdata(array('fullname'));
getdata(array('pass1', 'pass2'), 'post','password');

if($pass1 == $pass2){
	$sql = 'UPDATE `'.$users.'` SET `password` = OLD_PASSWORD(\''.$pass1.'\') WHERE `uniqname` = \''.$auth_uniqname.'\'';
}else{
	error("Passwords do not match, please try again.");
}


showHTMLHead('My Information');
?>
<form method="post" action="<?=$update_page?>">
<table>
<tr>
<td>Full name:</td> 
<td align="right">
<input type="text" name="fullname" value="<?=$fullname?>">
</td>
</tr>
<tr>
<td>New Password: </td>
<td align="right">
<input type="password" name="pass1">
</td>
</tr>
<tr>
<td>New Password Again: </td>
<td align="right">
<input type="password" name="pass2">
</td>
</tr>
<tr>
<td colspan=2>
<hr>
</td>
</tr>
<tr>
<td align="right" colspan=2>
<input type="reset" value="Reset Form" /> 
<input type="submit" name="submit" value="Submit" />
<td>
</tr>
</table>
</form>
<?
showHTMLFoot();


?>
