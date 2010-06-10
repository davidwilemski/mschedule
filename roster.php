<?
//roster is in need of serious help

include_once 'inc/accesscontrol.php';
include_once 'inc/common.php';
include_once 'inc/rosterlinks.php';
include_once 'inc/friendoperations.php';

/*
function getroster(){
	$sql = "SELECT DISTINCT $users.uniqname as uniqname, $users.fullname as fullname, t2.dept as dept, t2.number as number, t2.section as section ";
	$sql .= "FROM `$user_class` AS t1, `$user_class` AS t2, `$users` AS $users ";
	$sql .= "WHERE t1.uniqname = '$auth_uniqname' AND t2.uniqname != '$auth_uniqname' AND ";
	$sql .= "t2.uniqname = $users.uniqname AND ";
	$sql .= "t1.dept = t2.dept AND ";
	$sql .= "t1.number = t2.number AND ";
	$sql .= "t1.section = t2.section";
};
*/

/*
$classid = $_GET['classid'];
$dept = $_GET['dept'];
$number = $_GET['number'];
$section = $_GET['section'];
*/

getdata(array('classid', 'dept', 'number', 'section', 'getcommon'), 'get');


//set the type of roster based on input data
if($getcommon == 'sections'){
	$roster_type = 'cs';
}else if($getcommon == 'courses'){
	$roster_type = 'cc';
}else if($classid != ''){
	$roster_type = 'id';
}else if($dept != '' and $number != '' and $section != ''){
	$roster_type = 'dns';
}else if($dept != '' and $number != ''){
	$roster_type = 'dn';
}else if($dept != ''){
	$roster_type = 'd';
}else{
	error("Invalid Input.");
}

//start sql statment
$sql = "SELECT DISTINCT  $users.uniqname as uniqname, $users.fullname as fullname, $friends_table.uniqname as friend, $prefs.privacy as privacy ";

//add return values
switch($roster_type){
	case 'cs':
		$sql .= ", t2.dept as dept, t2.number as number, t2.section as section ";
		break;
	case 'cc':
		$sql .= ", t2.dept as dept, t2.number as number ";
		break;
	case 'id':
	case 'dns':
		break;
	case 'dn':
		$sql .= ", $user_class.section as section ";
		break;
	case 'd':
		$sql .= ", $user_class.number as number, $user_class.section as section ";
		break;
	
}

//add from clause
switch($roster_type){
	case 'cs':
	case 'cc':
		$sql .= "FROM `$user_class` AS t1, `$user_class` AS t2, `$users`";
		break;
	default:
		$sql .= "FROM `$user_class`, `$users`";
		break;
}

$sql .= " LEFT JOIN $prefs ON $users.uniqname = $prefs.uniqname ";

$sql .= " LEFT JOIN $friends_table ON $friends_table.uniqname = '$auth_uniqname' AND $users.uniqname = $friends_table.friend_uniqname ";

//add where clause
switch($roster_type){
	case 'cs':
	$sql .= "WHERE t1.uniqname = '$auth_uniqname' AND "
        . "t2.uniqname = $users.uniqname AND "
        . "t1.dept = t2.dept AND "
        . "t1.number = t2.number AND "
        . "t1.section = t2.section";
	break;
	case 'cc':
	$sql .= "WHERE t1.uniqname = '$auth_uniqname' AND "
        . "t2.uniqname = $users.uniqname AND "
        . "t1.dept = t2.dept AND "
        . "t1.number = t2.number";
	break;
	case 'id':
	$sql .= "WHERE $user_class.uniqname = $users.uniqname AND "
        . "`classid` = '$classid'";
	break;
	case 'dns':
	$sql .= "WHERE $user_class.uniqname = $users.uniqname AND "
        . "`dept` = '$dept' AND "
        . "`number` = '$number' AND "
        . "`section` = '$section'";
	break;
	case 'dn':
	$sql .= "WHERE $user_class.uniqname = $users.uniqname AND "
        . "`dept` = '$dept' AND "
        . "`number` = '$number'";
	break;
	case 'd':
	$sql .= "WHERE $user_class.uniqname = $users.uniqname AND "
        . "`dept` = '$dept'";
	break;
}


$regular_sql = $sql." HAVING uniqname != '$auth_uniqname' AND (friend IS NULL OR friend != '$auth_uniqname')";
$friends_sql = $sql." HAVING uniqname != '$auth_uniqname' AND friend = '$auth_uniqname'";

$result = sql($regular_sql);
$friends_result = sql($friends_sql);


//create title
if($getcommon == 'sections'){
	$title = "your sections";
}else if($getcommon == 'courses'){
	$title = "your courses";
}else{
	$title = rosterlinks($dept, $number, $section);
}

showHTMLHead("People in ".$title); 


echo "<p><b>VIPs:</b> <a href=\"myfriends.php\">?</a><br>";
if(mysql_num_rows($friends_result) == 0){
	echo "(none)";
}
for($i = 0; $myrow = mysql_fetch_assoc($friends_result); $i++) {
	$uniqnameother = 	$myrow['uniqname'];
	$fullnameother = 	$myrow['fullname'];
	$privacy = $myrow['privacy'];
	if($privacy == 'private'){
		$check_friend_result = sql("SELECT count(*) FROM $friends_table WHERE uniqname = '$uniqnameother' AND friend_uniqname = '$auth_uniqname'");
		if(mysql_result($check_friend_result, 0, 0) == 0){
			continue;
		}
	}
	if($myrow['dept']){
		$dept =	$myrow['dept'];
	}
	if($myrow['number']){
		$number =	$myrow['number'];
	}
	if($myrow['section']){
		$section =	$myrow['section'];
	}
	echo "<a href=\"view.php?uniqname=$uniqnameother\">";
	echo $fullnameother;
	echo "</a>";
	if($myrow['dept']){
		echo " <a href=\"roster.php?dept=$dept\">".$dept."</a>";
	}
	if($myrow['number']){
		echo " <a href=\"roster.php?dept=$dept&number=$number\">".$number."</a>";
	}
	if($myrow['section']){ 
		echo " (<a href=\"roster.php?dept=$dept&number=$number&section=$section\">".$section."</a>)";
	}
	//echo " (".$uniqnameother.")";
	echo "<br>\n";
}
echo "</p>";


echo "<p><b>Others:</b><br>";
if(mysql_num_rows($result) == 0){
	echo "(none)";
}
echo "<table>";
for($i = 0; $myrow = mysql_fetch_assoc($result); $i++) {
	$uniqnameother = 	$myrow['uniqname'];
	$fullnameother = 	$myrow['fullname'];
	$friend = $myrow['friend'];
	$privacy = $myrow['privacy'];
		if($privacy == 'private'){
		$check_friend_result = sql("SELECT count(*) FROM $friends_table WHERE uniqname = '$uniqnameother' AND friend_uniqname = '$auth_uniqname'");
		if(mysql_result($check_friend_result, 0, 0) == 0){
			continue;
		}
	}
	if($myrow['dept']){
		$dept =	$myrow['dept'];
	}
	if($myrow['number']){
		$number =	$myrow['number'];
	}
	if($myrow['section']){
		$section =	$myrow['section'];
	}
	echo "<tr><td><a href=\"view.php?uniqname=$uniqnameother\">";
	echo $fullnameother;
	echo "</a>";
	if($myrow['dept']){
		echo " <a href=\"roster.php?dept=$dept\">".$dept."</a>";
	}
	if($myrow['number']){
		echo " <a href=\"roster.php?dept=$dept&number=$number\">".$number."</a>";
	}
	if($myrow['section']){ 
		echo " (<a href=\"roster.php?dept=$dept&number=$number&section=$section\">".$section."</a>)";
	}
?>
</td>
<td nowrap>
<form method="get" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="uniqname" value="<?=$uniqnameother?>">
<input type="hidden" name="dept" value="<?=$_GET['dept']?>">
<input type="hidden" name="number" value="<?=$_GET['number']?>">
<input type="hidden" name="section" value="<?=$_GET['section']?>">
<input type="hidden" name="getcommon" value="<?=$_GET['getcommon']?>">
<input type="submit" name="submit" value="Add to VIPs">
</form>
</td>
</tr>
<?
}
echo "</table></p>";
echo "<p>If you want more people in this list, maybe you should <a href=\"spreadtheword.php\">spread the word</a> about this site.</p>";

showHTMLFoot();
?>