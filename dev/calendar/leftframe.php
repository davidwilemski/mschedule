<?php

require_once "db.php";

$term = $_GET['term']; //eg. WN2007
$courses = $_GET['courses']; //eg. EECS_477,EECS_481,SI_110,STATS_412

$course = $_GET['course'];
$add = $_GET['add'];

if($courses){
if(!is_array($courses)){
	$courses = explode(",", $courses);
}
}else{
	$courses = array();
}

if($course && $add){
	array_push($courses, $course);
}

$courseInfo = array();

foreach($courses as $course){
	preg_match('/^(.*)_(.*)$/', $course, $matches);
	$result = $db->getAll("SELECT * FROM timesched_$term WHERE subject LIKE ? AND catalog_nbr = ?", array("%({$matches[1]})", $matches[2]));
	if(PEAR::isError($result)){
		die($result->getMessage());
	}
	if(!count($result)) continue;
	$courseInfo[$course] = array('course_title' => $result[0]['course_title']);
	$courseInfo[$course]['sections'] = array();

	foreach($result as $row){
		$component = $row['component'];
		if(!is_array($courseInfo[$course]['sections'][$component])) $courseInfo[$course]['sections'][$component] = array();
		array_push($courseInfo[$course]['sections'][$component], $row);
	}
}


$result = $db->getAll("SELECT DISTINCT subject, catalog_nbr, course_title FROM timesched_$term");
if(PEAR::isError($result)){
        die($result->getMessage());
}

$allCourses = array();

foreach($result as $row){
        preg_match('/^(.*) \((.*)\)$/', $row['subject'], $matches);
        $dept_name = $matches[1];
        $dept = $matches[2];
        if(!is_array($allCourses[$dept])) $allCourses[$dept] = array();
        $row['dept_name'] = $dept_name;
        array_push($allCourses[$dept], $row);
}

ksort($allCourses);

?>
<html>
<head>
<script language="javascript" type="text/javascript">
<!--

var store = new Array();

<?php
$counter = 0;
foreach($allCourses as $dept => $courseList){
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
        lgth = document.forms[1].course.options.length - 1;
        document.forms[1].course.options[lgth] = null;
        if (document.forms[1].course.options[lgth]) optionTest = false;
}


function populate()
{
        if (!optionTest) return;
        var box = document.forms[1].first;
        var number = box.options[box.selectedIndex].value;
        if (!number) return;
        var list = store[number];
        var box2 = document.forms[1].course;
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
<form target="rightframe" action="rightframe.php">
<input type="hidden" name="term" value="<?=$term?>">
<input type="submit" value="Refresh Calendar">
<pre>
<?php
foreach($courseInfo as $key => $course){
	print "\n\n".$key." - ".$course['course_title']."\n";
	foreach($course['sections'] as $component => $sections){
		print "  $component\n";
		foreach($sections as $section){
			print '    <input type="checkbox" name="cal[]" value="'.$key.'_'.$section['section'].'">'.$section['m'].$section['t'].$section['w'].$section['th'].$section['f'].$section['s'].$section['su']." ".$section['time']." ".$section['instructor']." ".$section['location']."\n";
		}
	}
}
?>
</pre>
<input type="submit" value="Refresh Calendar">
</form>
<br>
<br>
<form>
<input type="hidden" name="term" value="<?=$term?>">
<?php
foreach($courseInfo as $key => $course){
        print '<input type="hidden" name="courses[]" value="'.$key.'">';
}
?>
<select name="first" onchange="populate()">
        <option value="" selected="selected">Choose a Department</option>
<?php
$counter = 0;
foreach($allCourses as $dept => $courseList){
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
