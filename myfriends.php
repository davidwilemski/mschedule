<?php

include_once 'inc/accesscontrol.php';
include_once 'inc/friendoperations.php';
include_once 'inc/common.php';
include_once 'inc/db.php';

showHTMLHead("VIPs");

echo "<p>These people will be able to see your schedule even if your Privacy <a href=\"prefs.php\">Preference</a> is set to Private. They will show up at the top of lists of people when you click the links in <a href=\"myschedule.php\">my schedule</a>. If you don't know their uniqname, you can look it up in the <a target=\"_new\" href=\"http://directory.umich.edu/\">directory</a>.</p>";
      
$result = sql("SELECT $friends_table.friend_uniqname as uniqname, $users.fullname as fullname "
		. " FROM $friends_table LEFT JOIN $users ON $friends_table.friend_uniqname = $users.uniqname "
		. " WHERE $friends_table.uniqname = '$auth_uniqname'");

echo "<table border=1>\n";
echo <<<END
<tr>
<th>
</th>
<th>
Uniqname
</th>
<th>
Name (if registered)
</th>

</tr>
END;
$uniqnames = '';
while($myrow = mysql_fetch_row($result)) {
	echo "<tr>";
	//echo "<td></td>";
	$uniqname = $myrow[0];
	$friend_fullname = $myrow[1];
	?>
	<form method="get" action="<?=$_SERVER['PHP_SELF']?>">
	<input type="hidden" name="uniqname" value="<?=$uniqname?>">
	<td><input type="submit" name="submit" value="Remove"></td>
	</form>
	<?
	echo "<td>", $uniqname, "</td>";
	if($friend_fullname){
		$uniqnames .= $uniqname.'+';
		echo "<td><a href=\"view.php?uniqname=".$uniqname."\">", $friend_fullname, "</a></td>";
	}else{
		echo "<td align=\"center\"><a href=\"spreadtheword.php?new_uniqname=".$uniqname."\">--Invite--</a></td>";
	}
	echo "</tr>\n";
}


?>

<form method="get"  autocomplete="off" action="<?=$_SERVER['PHP_SELF']?>">
<tr>
       <td> 
           <input type="submit" name="submit" value="Add" align="right" /> 
       </td> 
       <td> 
           <input name="uniqname" type="text" maxlength="8" size="10" /> 
       </td> 
</tr>
</form>
</table>

<a href="multiview.php?uniqnames=<?=$uniqnames?>">VIPs' schedules combined</a>
<?
showhtmlfoot();

?>