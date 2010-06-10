<?php

require_once "db.php";

$term = "WN2007";

$result = $db->getAll("SELECT DISTINCT subject, catalog_nbr, course_title FROM timesched_$term");
if(PEAR::isError($result)){
	die($result->getMessage());
}

$courses = array();

foreach($result as $row){
	preg_match('/^(.*) \((.*)\)$/', $row['subject'], $matches);
	$dept_name = $matches[1];
	$dept = $matches[2];
	if(!is_array($courses[$dept])) $courses[$dept] = array();
	$row['dept_name'] = $dept_name;
	array_push($courses[$dept], $row);
}

ksort($courses);

?>
<html>
<head>
<script language="javascript" type="text/javascript">
<!--

var store = new Array();

<?php
$counter = 0;
foreach($courses as $dept => $courseList){
?>

store[<?=$counter?>] = new Array(
<?php
$first = true;
foreach($courseList as $course){
if(!$first){
	print ",";
}else{
	$first = false;
}
$value = $course['catalog_nbr']." - ".$course['course_title'];
$key = $dept."_".$course['catalog_nbr'];
$value = addslashes($value);
$key = addslashes($key);
?>
'<?=$value?>','<?=$key?>'
<?php
}
?>
);
<?php
$counter++;
}
?>

function init()
{
	optionTest = true;
	lgth = document.forms[0].course.options.length - 1;
	document.forms[0].course.options[lgth] = null;
	if (document.forms[0].course.options[lgth]) optionTest = false;
}


function populate()
{
	if (!optionTest) return;
	var box = document.forms[0].first;
	var number = box.options[box.selectedIndex].value;
	if (!number) return;
	var list = store[number];
	var box2 = document.forms[0].course;
	box2.options.length = 0;
	for(i=0;i<list.length;i+=2)
	{
		box2.options[i/2] = new Option(list[i],list[i+1]);
	}
}

// -->
</script>
</head>
<body onLoad="init()">
<form>
<select name="first" onchange="populate()">
	<option value="" selected="selected">Choose a Department</option>
<?php
$counter = 0;
foreach($courses as $dept => $courseList){
?>
	<option value="<?=$counter?>"><?=$dept?> - <?=$courseList[0]['dept_name']?></option>
<?php
$counter++;
}
?>
</select>
<br>
<select name="course">
<option value="2">^ Choose a Department First ^</option>
<option>Your browser sucks!</option>
</select>
<br>
<input type="submit" name="add" value="Add">
</form>

</body>
</html>
