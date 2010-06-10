<?
//include "../inc/common.php";
include "../inc/configuration.php";

require_once "../classes/class.mscourseinfo.php";

$MSCI = new MSCourseInfo();
print "<pre>";
$array = $MSCI->search('', 'eecs');


print "<table>\n";
foreach($array as $course){
	print "<tr>";
	print "<td>".$course->courseID."</td>";
	print "<td>".$course->subject."</td>";	
	print "<td>".$course->number."</td>";
	print "<td>".$course->section."</td>";
	print "<td>";
	foreach($course->_meetings as $meeting){
		print $meeting->day.": ".$meeting->startTime."-".$meeting->endTime."<br>\n";
	}
	print "</td>";
	print "</tr>\n";
}
print "</table>";
?>
