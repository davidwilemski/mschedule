<?
require_once "convertfunctions.php";

//takes array (from parseDivisions) and puts it into the database
function storeDivisions($array){
	foreach($array as $key => $division){
		$newDivision = convertDivision($division);
		insertDB('divisions', $newDivision);
	}
}


//takes array (from parseCourses) and puts it into the database
function storeCourses($array){
	foreach($array as $key => $value){
		$newCourse = convertCourse($value);
		if($newCourse['division'] != $lastCourse[division] or $newCourse[number] != $lastCourse[number]){
			insertDB('courses', $newCourse);
			$lastCourse = $newCourse;
		}
	}
}

//takes array (from parseSections) and puts it into the database
function storeSections($array, &$enteredClassNums)
{
	
	foreach($array as $key => $section){
		$newSection = convertSection($section, $sectionInfo);
		$meetings = array_pop($section);
		if(!in_array($newSection['classNum'], $enteredClassNums)){
			insertDB('sections', $newSection);
			array_push($enteredClassNums, $newSection[classNum]);
		}else{
			continue;
		}
		
		$enteredStartTimes = array();
		foreach($meetings as $key => $meeting){

			$newLocation = convertLocation($meeting, $sectionInfo);
			insertDB('locations', $newLocation);
			
			//parse days
			$tempDays = $meeting[days];
			$day_strings = array(0 => 'Su', 1 => 'M', 2 => 'Tu', 3 => 'W', 4 => 'Th', 5 => 'F', 6 => 'Sa');

			$daysOfClass = array();
			for($i = 0; $i < 7; $i++){
				$day = $day_strings[$i];
				if(stristr($tempDays, $day)){
					array_push($daysOfClass, $i);
					$tempDays = str_replace($day, '', $tempDays);
				}
			}
			
			foreach($daysOfClass as $day){
				$newMeeting = convertMeeting($meeting, $day, $sectionInfo);
				if(!($newMeeting[startTime] == 0 or $newMeeting[endTime] == 0 or in_array($newMeeting[startTime], $enteredStartTimes))){
					insertDB('meetings', $newMeeting);
					array_push($enteredStartTimes, $newMeeting[startTime]);
				}
			}
		}
	}
}