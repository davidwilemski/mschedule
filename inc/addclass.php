<?php 
//included in classoperations.php only I think
include_once 'inc/common.php';
include_once 'inc/db.php';

//validate input here
$dept = strtoupper($dept);
debug("Tried to add class: ".$classid.':'.$dept.':'.$number.':'.$section);
if(!is_numeric($number)  or (strlen($number) > 3)){
	error("Number must be an integer with 3 or less digits.");
}

dbConnect();

if($section == ''){
	$result = sql("SELECT * FROM `$user_class` "
        . " WHERE `uniqname` = '$auth_uniqname' AND "
        . "`dept` = '$dept' AND "
        . "`number` = '$number' "
        . "LIMIT 1");
}else{
	$result = sql("SELECT * FROM `$user_class` "
        . " WHERE `uniqname` = '$auth_uniqname' AND "
        . "`dept` = '$dept' AND "
        . "`number` = '$number' AND "
        . "`section` = '$section' "
        . "LIMIT 1");
}

if(mysql_num_rows($result)){
	error("Class is already in your schedule.");	
}

//makes sure the course is valid
if($section == ''){
	$result = sql('SELECT * '
        . " FROM `$classes` "
        . ' WHERE `dept` = \''.$dept.'\' AND '
        . '`number` = \''.$number.'\'');
//find corresponding classid if exists
}else{
	$result = sql('SELECT `classid` '
        . " FROM `$classes` "
        . ' WHERE `dept` = \''.$dept.'\' AND '
        . '`number` = \''.$number.'\' AND '
        . '`section` = \''.$section.'\'');
}

if($myrow = mysql_fetch_row($result)){
	if($section == ''){
		$classid = '';
	}else{
		$classid = $myrow[0];
	}
}else{
	error("Class does not exist for current term. Please use <a href=\"importclasses.php\">import classes</a> for accurate schedule entry.");
}


if($section == ''){
	$classname = "$dept $number (no section specified)";
}else{
	$classname = "$dept $number, section $section";
}

//$sql = "create table if not exists $tablename ( classid int(6) unsigned unique default '0' null)";
sql('CREATE TABLE IF NOT EXISTS `'.$user_class.'` ( `uniqname` varchar( 8 ) NOT NULL default \'\','
		. ' `classid` mediumint( 5 ) unsigned not null default \'0\','
        . ' `dept` varchar( 8 ) NOT NULL default \'\','
        . ' `number` int( 3 ) unsigned zerofill NOT NULL default \'000\','
        . ' `section` int( 3 ) unsigned zerofill NOT NULL default \'000\' )'); 

$result = sql("
update $user_class set 
classid='$classid', 
section='$section' 
WHERE 
uniqname='$auth_uniqname' AND 
dept='$dept' AND 
number='$number' AND 
section='000'
");

if(mysql_affected_rows() == 0){
$result = sql("insert ignore into $user_class set 
uniqname='$auth_uniqname', 
classid='$classid', 
dept='$dept', 
number='$number', 
section='$section'
");
}

if(mysql_affected_rows()){
	sql("insert ignore into inserted_classids set classid='$classid', dept='$dept', number='$number', section='$section'");
	status("Added class: $classname");
}else{
	status("Already in schedule: $classname");
}
?>
