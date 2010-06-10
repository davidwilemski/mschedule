<?
require_once "convertarray.php";
require_once "../campusfromlocation.php";
require_once "converttohalfhourspastmonday.php";

//arrays are the arrays givin by the parse functions in mschedule/spiders/waparsefunctions.php file

define(_TERM, "w05");

function convertDivision($array)
{
	//convert array
	$rv = convertArray($array, array(
		'subject' => 'abbrev',
		'desc' => 'name'
	));
	//add term
	$rv['term'] = _TERM;

	return $rv;
}

function convertCourse($array)
{
	//convert array
	$rv = convertArray($array, array(
		'subject' => 'division',
		'number' => 'number',
		'title' => 'name'
	));
	//add term
	$rv['term'] = _TERM;
	return $rv;
}

function convertSection($array, &$sectionInfo)
{
	//convert array
	//for use with sections, meetings, and locations
	$sectionInfo = convertArray($array, array(
		'courseID' => 'classNum',
		'subject' => 'division',
		'number' => 'course'
	));
	$sectionInfo['term'] = _TERM;
	
	$rv = array_merge($sectionInfo, 
		convertArray($array, 
			array(
				'component' => 'sectionType',
				'section' => 'sectionNum',
				'credits' => 'credits',
				'openSeats' => 'openSeats',
				'waitNumber' => 'waitlistNum'
			)
		)
	);
	
	//get instructor from first meeting time
	//not sure if this will always work
	$rv['instructor'] = $array['meetings'][0]['instructor'];
	
	//set linkage group to last num in section number if Lecture
	//first otherwise
	if($array['component'] == "LEC"){
		$i = 2;
	}else{
		$i = 0;
	}
	$rv['linkageGroup'] = substr($array['section'], $i, 1);
	return $rv;
}

function convertLocation($array, $sectionInfo)
{
	$rv = $sectionInfo;
	$rv[timeString] = $array[days]." ".$array[startTime]."-".$array[endTime];
	$rv[location] = $array[location];
	return $rv;
}

function convertMeeting($array, $day, $sectionInfo)
{
	$rv = $sectionInfo;
	//parse campus
	$rv[campus] = getCampusFromLocation($array[location]);
			
	//parse time
	$rv[startTime] = convertToHalfHoursPastMonday($array[startTime], $day);
	$rv[endTime] = convertToHalfHoursPastMonday($array[endTime], $day);
	
	
	return $rv;
}