<?php

$term = "WN2007";

require_once "db.php";

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

var depts = new Array(
<?php
$counter = 0;
foreach($allCourses as $dept => $courseList){
if($counter != 0){
        print ",";
}
$dept_name = $courseList[0]['dept_name'];
$value = addslashes("$dept - $dept_name");
?>
'<?=$value?>','<?=$counter?>'
<?php
$counter++;
}
?>
);

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
