<?php
include_once 'inc/accesscontrol.php';
include_once 'inc/classoperations.php';
include_once 'inc/common.php';
include_once 'inc/db.php';


showHTMLHead("My Schedule");

//$sql = "select `dept`, `number`, `section` from " . $tablename;
$my_schedule_result = sql("SELECT `classid`, `dept`, `number`, `section` "
        . " FROM `$user_class` "
        . " WHERE `uniqname` = '$auth_uniqname' "
        . " ORDER BY `dept`, `number`, `section`");
$num_of_classes = mysql_num_rows($my_schedule_result);

//don't show schedule if there are no classes
if($num_of_classes == 0){
	echo "<p>You have no classes in your schedule. Please use the <a href=\"importclasses.php\">import classes</a> function to tell the system what you are taking.</p>\n";
	showhtmlfoot();
	exit;
}

	$result = sql("SELECT DISTINCT t3.uniqname "
		. "FROM `$user_class` AS t1, `$user_class` AS t2, `$users` AS t3 "
        . "WHERE t1.uniqname = '$auth_uniqname' AND t2.uniqname != '$auth_uniqname' AND "
        . "t2.uniqname = t3.uniqname AND "
        . "t1.dept = t2.dept AND "
        . "t1.number = t2.number AND "
        . "t1.section = t2.section ");
$thismany = mysql_num_rows($result);
if($thismany == 1){
	echo "There is 1 other person ";
}else{
	echo "There are $thismany people "; 
}
echo "<a href=\"roster.php?getcommon=sections\">in your sections</a>.<br>\n";

	$result = sql("SELECT DISTINCT t3.uniqname "
		. "FROM `$user_class` AS t1, `$user_class` AS t2, `$users` AS t3 "
        . "WHERE t1.uniqname = '$auth_uniqname' AND t2.uniqname != '$auth_uniqname' AND "
        . "t2.uniqname = t3.uniqname AND "
        . "t1.dept = t2.dept AND "
        . "t1.number = t2.number");
$thismany = mysql_num_rows($result);
if($thismany == 1){
	echo "There is 1 other person ";
}else{
	echo "There are $thismany people ";
}
echo "<a href=\"roster.php?getcommon=courses\">taking a common course.</a><br>\n";
echo "Click on the links in the table below to see lists of people.<br>\n";
echo "<table border=1>\n";
echo <<<END
<tr>
<th>
</th>
<th>
Class ID#
</th>
<th>
Department
</th>
<th>
Number
</th>
<th>
Section
</th>
</tr>
END;


while($myrow = mysql_fetch_row($my_schedule_result)) {
	echo "<tr>";
	//echo "<td></td>";
	$classid = $myrow[0];
	$dept = $myrow[1];
	$number = $myrow[2];
	$section = $myrow[3];
	?>
	
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<input type="hidden" name="classid" value="<?=$classid?>">
	<input type="hidden" name="dept" value="<?=$dept?>">
	<input type="hidden" name="number" value="<?=$number?>">
	<input type="hidden" name="section" value="<?=$section?>">
	<td><input type="submit" name="submit" value="Remove"></td>
	</form>
	<?
	echo "<td>", $classid, "</td>";
	echo "<td><a href=\"roster.php?dept=".$dept."\">", $dept, "</a></td>";
	echo "<td><a href=\"roster.php?dept=".$dept."&number=".$number."\">", $number, "</a></td>";

	if($section == '000'){
		echo "<td></td>";
	}else{
		echo "<td><a href=\"roster.php?dept=".$dept."&number=".$number."&section=".$section."\">", $section, "</td>";
	}
	//echo "<td><a href=\"roster.php?classid=".$classid."\">Roster</a></td>";
	/*
	if($section == 0){
		echo "<td><a href=\"classinfo.php?dept=".$dept."&number=".$number."&submit=search\">Info</a></td>";
	}else{
		echo "<td><a href=\"classinfo.php?classid=".$classid."&submit=search\">Info</a></td>";
	}
	*/
	echo "</tr>\n";
	
	$last_dept = $dept;
	$last_number = $number;
}


?>

<form method="post"  autocomplete="off" action="<?=$_SERVER['PHP_SELF']?>">
<tr>
       <td> 
           <input type="submit" name="submit" value="    Add    " align="right" /> 
       </td> 
       <td> 
           <!-- classid -->
       </td> 
       <td> 
           <input name="dept" type="text" maxlength="8" size="10" /> 
       </td> 
       <td> 
           <input name="number" type="text" maxlength="3" size="5" /> 
       </td> 
       <td> 
           <input name="section" type="text" maxlength="3" size="5" /> 
       </td>
</tr>
</form>
</table>

<p><strong>Tip:</strong> The <a href="importclasses.php">import classes</a> function may be faster than
adding each of your classes individually.</p>
<?
//echo "<p>";
//echo "<a href=\"scheduleprint.php\" target=\"_NEW\">printable version</a>";
//echo " | <a href=\"classinfo.php\">detailed version</a>";
//echo "</p>";
//include 'scheduleprint.php';
showHTMLFoot();
?>