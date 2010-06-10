<?
include_once 'inc/common.php';
include_once 'inc/db.php';
include_once 'inc/showschedule.php';
include_once 'inc/friendoperations.php';

//show schedule unless there is reason not to
$show = true;

getdata(array('uniqname'), 'get');

//view defaults to the uniqname that is logged in
if($uniqname == '' && $auth_uniqname != ''){
	$uniqname = $auth_uniqname;
	header("Location: view.php?uniqname=$uniqname");
	exit();
}
if($uniqname != '' and $uniqname != $_SESSION['uniqname'] and $auth_uniqname != $uniqname){
	$result = sql("select * from `$users` where `uniqname` = '$uniqname'");
	
	if(mysql_num_rows($result) == 0){
		status("This user, <b>$uniqname</b>, has not registered for this service. <a href=\"register.php?uniqname=$uniqname\">Click here</a> to register this uniqname.");
		$show = false;
	}else{
		
		$result = sql("select privacy from `$prefs` where `uniqname` = '$uniqname'");
		
		$privacy = mysql_num_rows($result) ? mysql_result($result, 0, 0) : '';
		
		
		//default is to show schedule if the haven't set prefs even if default in database is private
		//might want to fix that, oops
		if($privacy != 'public' and $privacy != ''){
			if(!isset($_SESSION['uniqname'])){
				status("You must be logged in to view this user's schedule.");
				$show = false;
			}else{
				if($privacy != 'protected'){
					$result = sql("select count(*) from $friends_table where uniqname = '$uniqname' and friend_uniqname = '$auth_uniqname'");
					if(mysql_result($result, 0, 0) == 0){
						status("The user, '$uniqname', has not authorized you to view their schedule.");
						$show = false;
					}
				}
			}
		}
	}
}

showhtmlhead("View Schedule");
?>
<form method="get"  autocomplete="off" action="<?=$_SEVER['PHP_SELF']?>">
<table border="0" cellpadding="0" cellspacing="5">
   <tr>
       <td align="right"> 
           <p>Uniqname</p>
       </td> 
       <td>
           <input name="uniqname" type="text" value="<?=$uniqname?>" maxlength="8" size="10" /> 
       </td>

   		<td colspan="2" nowrap>
           <input type="submit" value="View" /><form>
<?
	if($auth_uniqname){
			?><input type="submit" name="submit" value="Add to VIPs"><?
	}
?>
       </td> 
   </tr> 

</table>
</form>
<?
if($show and $uniqname != ''){
	echo showschedule($uniqname);
	echo '<b>Note:</b> This data cannot be assumed accurate. Please verify your schedule with <a href="http://wolverineaccess.umich.edu">Wolverine Access</a>.';
}

echo "<p>";
echo "<a href=\"scheduleprint.php?uniqname=$uniqname&term=$term\" target=\"_NEW\">print</a>, ";
print '<a href="http://umichigan.facebook.com/s.php?q='. $uniqname .'%40umich.edu">facebook</a>, <a href='.getLocationsURL(getLocations($uniqname)).'>map</a></p>';

//if this schedule is the current users schedule
//if($uniqname == $_SESSION['uniqname'] and strlen($_SESSION['uniqname']) > 2){
	print '<b>link</b>:<br>';
	echo "<i>http://www.mschedule.com/view.php?uniqname=$uniqname&term=$term</i>";
//}
echo "</p>";

?>
<p><b>embed</b>:<br>
<i><?php
		print htmlentities("<iframe src=\"http://mschedule.com/scheduleprint.php?uniqname=$uniqname&term=$term\" width=\"500\" height=\"300\"></iframe>")
?>
</i>
<?php
showhtmlfoot();
?>
