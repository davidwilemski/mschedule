<?

function seekUntil($string, $array, &$i){
	while($i < count($array) and !strstr($array[$i], $string)){
		$i++;
	}
	$i++;
}

function seekNonBlank($array, &$i)
{
	while($i < count($array) and trim($array[$i]) == ''){
		$i++;
	}
}

//returs an array with subject and desc
function parseSubjects($subjectsPage)
{
	$rv = array();
	$array = file($subjectsPage);
	seekUntil("Return to Class Search", $array, $i);
	seekNonBlank($array, $i);

	$numOnPage = 0;
	while($i < count($array) and trim($array[$i]) != "Return to Class Search"){
		$sl['numOnPage'] = $numOnPage++;
		$sl['subject'] = trim($array[$i++]);
		$sl['desc'] = trim($array[$i++]);
		array_push($rv, $sl);
	}
	return $rv;
}


//returns an array with subject, number, title, and if there are any sections open
//requires coursesPage is a valid file name with the correct data
function parseCourses($coursesPage)
{
	$rv = array();
	/*
	$handle = fopen($coursesPage, "rb");
	$contents = '';
	while (!feof($handle)) {
	  $contents .= fread($handle, 8192);
	}
	fclose($handle);
	*/
	$array = file($coursesPage);
	//print $contents;
	$i = 0;
	seekUntil("Return to Subject Code Search Page", $array, $i);
	seekNonBlank($array, $i);

	$numOnPage = 0;
	while($i < count($array) and trim($array[$i]) != "Return to Class Search"){
		$line = preg_split("/ /", trim($array[$i]), 3, PREG_SPLIT_NO_EMPTY);
		$course['numOnPage'] = $numOnPage++;
		$course['subject'] = $line[0];
		$course['number'] = $line[1];
		$course['title'] = addslashes($line[2]);
		
		$i++;
		if(strstr($array[$i], "View open sections only")){
			$i++;
			$i++;
			$course['openSections'] = 1;
		}else{
			$i++;
			$course['openSections'] = 0;
		}
		//print "{$course['openSections']}";
		
		array_push($rv, $course);
	}
	
	return $rv;
}

function myTrim(&$string, $key)
{
	$string = trim($string);
}


function parseMeeting($array, &$i)
{
		if($array[$i] == ''){
			return false;
		}
		$meeting['startTime'] = $array[$i++];
		$meeting['endTime'] = $array[$i++];
		$meeting['days'] = $array[$i++];
		$meeting['location'] = $array[$i++];
		$meeting['startDate'] = $array[$i++];
		if($array[$i++] != "-"){
			print "Error with date...\n";
			var_dump($meeting);
			$i=$i-20;
			print "--------\n";
			for ($j = 0; $j < 40; $j++){
				print $array[$i]."\n";
				$i++;
			}
			print "--------\n";
			exit;
		}
		$meeting['endDate'] = $array[$i++];
		if($array[$i] == "Instructor:"){
			$i++;
			if($array[$i] != '' and !strstr($array[$i], "_____________")){
				$meeting['instructor'] = $array[$i++];
			}
		}
	return $meeting;	
}

function parseSection(&$numOnPage, $array, &$i)
{
	
	seekUntil("Wait #", $array, $i);
	$section['numOnPage'] = $numOnPage++;
	$section['courseID'] = $array[$i++];
	seekNonBlank($array, $i);
	
	if($array[$i] == "Open")
	{
		$section['status'] = 1;
		$i++;
	}else{
		$section['status'] = 0;
	}
	$section['subject'] = $array[$i++];
	$section['number'] = $array[$i++];
	$section['component'] = $array[$i++];
	$section['section'] = $array[$i++];
	$section['desc'] = $array[$i++];
	$section['credits'] = $array[$i++];
	$section['openSeats'] = $array[$i++];
	$section['waitNumber'] = $array[$i++];
	if($array[$i++] == "Closed" and $section['status'] != 0)
	{
		print "Error with status...\n";
		var_dump($section);
		$i=$i-3;
		print "--------";
		for ($j = 0; $j < 3; $j++){
			print "$i";
			$i++;
		}
		print "--------";
		exit;
	}
	$section['meetings'] = array();
	seekNonBlank($array, $i);
	while($array[$i++] == "Time: Room: Dates:"){
		$meeting = parseMeeting($array, $i);
		if($meeting){
			array_push($section['meetings'], $meeting);
		}
	}
	$i--;
	seekUntil("___", $array, $i);
	return $section;
}

function parseSections($sectionsPage)
{
	$rv = array();
	$array = file($sectionsPage);
	array_walk($array, 'myTrim');
	$i = 0;
	$numOnPage = 0;
	while($i < count($array)){
		$section = parseSection($numOnPage, $array, $i);
		array_push($rv, $section);
	}
	return $rv;
}

