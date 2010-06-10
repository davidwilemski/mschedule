<?php

$_GET['getdate'] = "20070113";

require_once "obfuscate.php";
require_once "db.php";

$term = $_GET['term']; //eg. WN2007
if($term != "WN2007"){
header("Location: ?term=WN2007");
exit();
}

$courses = $_GET['courses']; //eg. EECS_477,EECS_481,SI_110,STATS_412
if(!is_array($_GET['cal'])){
$_GET['cal'] = array();
}

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

$result = $db->getAll("SELECT DISTINCT subject FROM timesched_$term");
if(PEAR::isError($result)){
        die($result->getMessage());
}

$allCourses = array();

foreach($result as $row){
        preg_match('/^(.*) \((.*)\)$/', $row['subject'], $matches);
        $dept_name = $matches[1];
        $dept = $matches[2];
        $allCourses[$dept] = $dept_name;
}

ksort($allCourses);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Mschedule</title>
  	<link rel="stylesheet" type="text/css" href="templates/mschedule/default.css" />
<script language="javascript" type="text/javascript" src="courses_WN2007_20070206.js"></script>
<script language="javascript" type="text/javascript" src="selectlist.js?1"></script>
<script language="JavaScript" type="text/javascript" src="popup.js"></script>

</head>
<body onLoad="init()">
<table>
<tr>
<td valign="top">
<a href="http://www.mschedule.com">
<img src="http://www.mschedule.com/images/minitopbar.jpg" width="373" height="60"  border="0">
</a>
<br>
<br>
<form>
<input type="hidden" name="term" value="<?=$term?>">
<?php
foreach($courseInfo as $key => $course){
        print '<input type="hidden" name="courses[]" value="'.$key.'">';
}
?>
<input type="submit" value="Refresh Calendar">
<a href="?term=<?=$term?>">Start Over</a>
<pre>
<?php
foreach($courseInfo as $key => $course){
        print "\n\n".$key." - ".$course['course_title']."\n";
        foreach($course['sections'] as $component => $sections){
                print "  $component\n";
                foreach($sections as $section){
                        print '    <input type="checkbox" name="cal[]" value="'.$key.'_'.$section['section'].'"';
			if(in_array($key.'_'.$section['section'], $_GET['cal'])){
				print 'checked="checked"';
			}
			print '>'.$section['m'].$section['t'].$section['w'].$section['th'].$section['f'].$section['s'].$section['su']." ".$section['time']." ".$section['instructor']." ".$section['location']."\n";
                }
        }
}
?>
</pre>
<input type="submit" value="Refresh Calendar">
<br>
<br>
<select name="first" onchange="populate()">
        <option value="" selected="selected">Eek... Javascript isn't working</option>
</select>
<br>
<select name="course">
<option value="2">^ Choose a Department First ^</option>
<option>Your browser sucks!</option>
</select>
<br>
<input type="submit" name="add" value="Add">
</form>
<br>
<br>
Created by <a href="http://www.kylemulka.com/">Kyle Mulka</a>. Email feedback to: <?=obfuscate('mschedule@umich.edu')?>
</td>
<td valign="top">
<form name="eventPopupForm" id="eventPopupForm" method="post" action="includes/event.php" style="display: none;">

  <input type="hidden" name="date" id="date" value="" />
  <input type="hidden" name="time" id="time" value="" />
  <input type="hidden" name="uid" id="uid" value="" />
  <input type="hidden" name="cpath" id="cpath" value="" />
  <input type="hidden" name="event_data" id="event_data" value="" />
</form>
<?php include "week.php"; ?>
</td>
</tr>
</table>
</body>
</html>
