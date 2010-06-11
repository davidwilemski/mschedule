<?php
// configuration stuff
ini_set("include_path", $_SERVER['DOCUMENT_ROOT'] . '/mschedule/');
$courses = "http://www.ro.umich.edu/timesched/pdf/FA2010.csv";
$term = "fall10";
$mischedule = false;
include_once 'inc/db.php';
// Grab file
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $courses);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
$cvs = curl_exec($ch);
curl_close($ch);
$classes = preg_split('/\n/', $cvs);
// Remove description line
unset($classes[0]);
// Loop through classes
foreach($classes as $class) {
	if($class == null) continue;
	$fields = preg_split('/","/', $class);
	foreach($fields as $key => $field) {
		$fields[$key] = str_replace("'", "", $field);
		$fields[$key] = str_replace('"', "", $field);
	}
	$starttimeindex=0;
	$endtimeindex=0;
	$num = $fields[5];
	$name = $fields[7];
	$classnum = $fields[3];
	$location = $fields[18];
        $instructor = $fields[19];
        $section = $fields[6];
        $sectype = $fields[8];

	$time = $fields[17];
	$mon = $fields[10];
	$tue = $fields[11];
	$wed = $fields[12];
	$thu = $fields[13];
	$fri = $fields[14];
	$sat = $fields[15];
	$sun = $fields[16];
	$time = explode('-', $time);
	if(count($time) == 2) {
		if(preg_match('/PM/', $time[1])) {
			$time[1] = str_replace('PM', '', $time[1]);
			if($time[1] != '12' && $time[1] != '12:30') {
				$time[1] = convertPM($time[1]);
			}
			// Add 12 hours to start time if it s
			$start = (int) $time[0];
			if($start < 10 || ($start < 1000 && $start > 12)) {
				$time[0] = convertPM($time[0]);
			}
		} else {
			$time[1] = str_replace('AM', '', $time[1]);
		}
		$time = implode('-', $time);
	} else {
		$time = '0-0';
	}
	$tue = preg_replace('/T/', 'TU', $tue);
	$days = $mon.$tue.$wed.$thu.$fri.$sat.$sun;

	//make the fields sql-friendly
	$name = preg_replace("/'/", "\\'", $name);
	$location = preg_replace("/'/", "\\'", $location);
	$num = preg_replace('/[ "]/', "", $num);
	$classnum = preg_replace('/[ "]/', "", $classnum);
	$instructor = preg_replace("/'/", "\\'", $instructor);
	$section = preg_replace("/[^0-9]/", "", $section);

	if ($fields[4] = preg_match('/([^\"]+?) \(([^\"]+?)\)/', $fields[4], $matches))
	{
		#if we have a legit course name/number add it to the db
		if($name != "" && $num != "" && $classnum != "")
		{

			sql("DELETE FROM classes_$term WHERE classid = $classnum");
			sql("INSERT INTO classes_$term VALUES('$classnum','$matches[2]','$num','$section','','$sectype','$days','$time','$location','$instructor') ON DUPLICATE KEY UPDATE location='$location', instructor='$instructor'");
			echo $classnum . "<br>";
		}
		else 
		{
			$classnum = $prevclassnum;
			$section = $prevsection;
			#if we have a legit course name/number add it to the db
			if($name != "" && $num != "" && $classnum != "")
			{
				sql("DELETE FROM classes_$term WHERE classid = $classnum");
				sql("INSERT INTO classes_$term VALUES('$classnum','$matches[2]','$num','$section','','$sectype','$days','$time','$location','$instructor') ON DUPLICATE KEY UPDATE location='$location', instructor='$instructor'");
				echo $classnum . "<br>";
			}
		}
	}
	$prevclassnum = $classnum;
	$prevsection = $section;

}
exit('Success!');

/* FUNCTIONS */
function convertPm($time) {
	// Add 12 hours to end time
	$length = strlen($time);
	if($length == 1) { // i.e. 0, 1, 2 (0:00, 1:00, 2:00)
		return $time + 12;
	}
	else if ($length == 2 && $time > 12) { // i.e. 30 (0:30)
		return 12 . $time;
	}
	else if ($length == 2) { // i.e. 10, 11, 12 (10:00, 11:00, 12:00)
		return 12 + $time;
	}
	else { // i.e. 130, 230, 1130, 1230 (1:30, 2:30, 11:30, 12:30)
		return 1200 + $time;
	}
}
