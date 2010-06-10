<?
include_once 'inc/accesscontrol.php';
include_once 'inc/common.php';
include_once 'inc/db.php';

getdata(array('classid', 'dept', 'number', 'section', 'submit'), 'get');


showhtmlhead("Class Info");
?>
<b>Note:</b> This data cannot be assumed accurate. Please verify your schedule with Wolverine Access.
<form method="get" action="<?=$_SERVER['PHP_SELF']?>">
<table border="0" cellpadding="0" cellspacing="3">
   <tr> 
       <td> 
           <p><b>Class ID</b></p> 
       </td> 
       <td> 
           <p><b>Department</b></p> 
       </td> 
       <td> 
           <p><b>Number</b></p> 
       </td> 
       <td> 
           <p><b>Section</b></p> 
       </td>       
   </tr> 
   <tr> 
       <td> 
           <input name="classid" type="text" value="<?=$classid?>" maxlength="5" size="7" /> 
       </td>
       <td> 
           <input name="dept" type="text" value="<?=$dept?>" maxlength="8" size="10" /> 
       </td>
       <td>
           <input name="number" type="text" value="<?=$number?>" maxlength="3" size="5" /> 
       </td>
       <td>
           <input name="section" type="text" value="<?=$section?>" maxlength="3" size="5" /> 
       </td>
       <td> 
           <input type="submit" name="submit" value="Search" /> 
       </td> 
   </tr> 
</table> 
</form> 
<?
if($submit == 'search' and ($dept != '' or $classid != '')){
	$where_statment = '1';
	if($classid != ''){
		$where_statment .= " AND `classid` = '$classid'";
	}
	if($dept != ''){
		$where_statment .= " AND `dept` = '$dept'";
	}
	if($number != ''){
		$where_statment .= " AND `number` = '$number'";
	}
	if($section != 0){
		$where_statment .= " AND `section` = '$section'";
	}
	
	$result = sql("select * from `$classes` where ".$where_statment." order by dept, number, section");
}else{
	echo "<b>My Schedule</b>";
	$result = sql("SELECT t2.* "
        . " FROM `$user_class` AS t1, `$classes` AS t2 "
        . " WHERE t1.uniqname = '$auth_uniqname' AND "
        . " t1.classid = t2.classid"
        . " ORDER BY t2.`dept`, t2.`number`, t2.`section`");
}
	echo "<table border=1>";
	for($i = 1; $myrow = mysql_fetch_row($result); $i++){
		echo "<tr>";
		//debug($i);
		foreach($myrow as $value){
			echo "<td>$value</td>";
		}
		echo "</tr>";
	}
	echo "</table>";

?>

<?
showhtmlfoot();
?>