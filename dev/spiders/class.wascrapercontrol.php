<?
require_once "../inc/configuration.php";
require_once "../inc/common.php";
/*
For reference while implementing this class:

WAScraper:

function refreshListOfSubjects()

//count = how many subjects there are in the subject list
function refreshListOfCourses($count)

//array of numbers where $num_array[k] is the number of courses in the k'th subject
function refreshListOfSections($num_array)

//2 dimensional array (all integer values and keys)
//first dimension corresponds to subjects (indexed by how they are layed out on WA)
//second dimension corresponds to courses
//final value corresponds to number of sections there are	
function refreshSectionInformation($arrayOfArrays)


*/


class WAScraperControl
{
	
	function getNumberOfSubjects()
	{
		global $MSDB, $cfg;
		$result = $MSDB->sql("SELECT count(*) FROM mschedule_wa_subjects");
		return mysql_result($result, 0);
	}
	
	function getNumbersOfCourses()
	{
		global $MSDB, $cfg;
		$result = $MSDB->sql("SELECT mschedule_wa_subjects.numOnPage, count(number) FROM  mschedule_wa_subjects join mschedule_wa_courses on mschedule_wa_subjects.subject = mschedule_wa_courses.subject GROUP  BY mschedule_wa_subjects.numOnPage");
		while($row = mysql_fetch_row($result)){
			//print $row[0].$row[1].mysql_error();
			$rv[$row[0]] = $row[1];
		}
		return $rv;
	}
	
	function getNumbersOfSections()
	{
		global $MSDB, $cfg;
		
		$result = $MSDB->sql("SELECT  subject, numOnPage FROM  `mschedule_wa_subjects` ");
		
		while($row = mysql_fetch_row($result)){
			$subjectToNum[$row[0]] = $row[1];
		}
		
		//var_dump($subjectToNum);
		
		$result = $MSDB->sql("SELECT  subject, number, numOnPage FROM  `mschedule_wa_courses` GROUP BY subject, number");
		while($row = mysql_fetch_row($result)){
			$numberToNum[$row[0]][$row[1]] = $row[2];
		}
		
		//var_dump($numberToNum);
		
		$result = $MSDB->sql("SELECT  subject, number, count(section) FROM  `mschedule_wa_sections` group by subject, number");
		//$firstRow = mysql_fetch_assoc($result);
		
		while($row = mysql_fetch_assoc($result)){
			//print $row['subject'].$row['number']."\n";
			//print $numberToNum[$row['subject']][$row['number']]."\n";
			$rv[$subjectToNum[$row['subject']]][$numberToNum[$row['subject']][$row['number']]] = $row['count(section)'];
		}
		return $rv;
		
	}
}

?>