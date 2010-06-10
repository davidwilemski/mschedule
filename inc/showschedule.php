<?php

include_once 'inc/db.php';
include_once 'inc/miltime.php';
require 'inc/schedule.inc.php';

//converts a time from the registrar's version to the data I need:
//start_time, end_time, and interval
function converttime($time, &$start_time, &$end_time, &$interval){
	if($time == 'ARR') return false;
		//echo "----------\n<pre>";
	$array = explode('-', $time);
	//var_dump($array);
	
	$value = $array[1];
	
	//couldn't get this code to work in a function
	if(strstr($value, 'PM')){
		$value = str_replace('PM', '', $value);
		$is_pm = true;
	}else{
		$is_pm = false;
	}
	if(strlen($value) < 3){
		$value *= 100;
	}
	if($is_pm and ($value < 1200)){
		$value += 1200;
	}
	$end_time = (int) $value;
	
	$value = $array[0];
	if(strstr($value, 'PM')){
		$value = str_replace('PM', '', $value);
		$is_pm = true;
	}else{
		$is_pm = false;
	}
	if(strlen($value) < 3){
		$value *= 100;
	}
	if($is_pm or ($value + 1200) < $end_time){
		$value += 1200;
	}
	
	$start_time = (int) $value;
	$interval = timediff($start_time, $end_time);
	return true;
}

function getLocationsURL($locations){
	global $auth_uniqname;
	if(!is_array($locations)){
		$locations = array($locations);
	}
	
	if($auth_uniqname == 'yarrmulka'){
		$locationsURL = "http://cartiki.com/iframe.php?keys=";
		$locationsURL .= rawurlencode(implode(",", $locations));
		$locationsURL .= "&source=mschedule";
	}else{
		$locationsURL = "http://dev.mschedule.com/coursemaps.php?buildAbbrev=";
		$locationsURL .= rawurlencode(implode(",", $locations));
		$locationsURL .= "&buildName=&msAction=msSearchMapLocation";
	}
	return $locationsURL;
}


//returns an array of locations of all classes in the user's schedule
function getLocations($uniqname = ''){
	global $user_class, $classes, $users;
	$result = sql("SELECT t2.location "
		. " FROM `$user_class` as t1, `$classes` as t2 "
        . " WHERE t1.uniqname = '$uniqname' AND "
        . " t1.classid = t2.classid ");
	$rv = array();
	while($myrow = mysql_fetch_assoc($result)) {
		array_push($rv, $myrow['location']);
	}
	return $rv;
}

//simply returns a string containing a person's schedule in html
function showschedule($uniqname = '', $classids = ''){
	//returns the time difference in minutes of two military times
	global $user_class, $classes, $users;
	$earliest_time = 2400;
	$latest_time = 0;
	
	//$result = sql("SELECT t2.dept, t2.number, t2.section, t2.days "
	if($uniqname == ''){
		$sql = "SELECT t2.classid, t2.dept, t2.number, t2.section, t2.type, t2.days, t2.time, t2.location, t2.instructor "
				. " FROM `$classes` as t2 "
		        . " WHERE 0";
		$classid_array = explode(",", $classids);
		//echo var_dump($classids);
		//echo var_dump($classid_array);
		foreach($classid_array as $classid){
			$sql .= " OR `classid` = '$classid'";
		}
		$result = sql($sql);
	}else{
		$result = sql("SELECT t2.classid, t2.dept, t2.number, t2.section, t2.type, t2.days, t2.time, t2.location, t2.instructor "
				. " FROM `$user_class` as t1, `$classes` as t2 "
		        . " WHERE t1.uniqname = '$uniqname' AND "
		        . " t1.classid = t2.classid "
		        . " ORDER BY `dept`, `number`, `section`");
  
	}
	/*
	while($myrow = mysql_fetch_assoc($result)) {
		echo "<tr>";
		//echo "<td></td>";
		foreach($myrow as $key => $value){
			echo $key." => ".$value." <br>\n";
		}
		echo "<p>\n";
	}
	//reset result to begining of data set
	mysql_data_seek($result, 0);
	echo "<p>Again:</p>";
	*/
	$classes_arr = array(1 => array(), 2 => array(), 3 => array(), 4 => array(), 5 => array());
	while($myrow = mysql_fetch_assoc($result)) {
		//echo "<tr>";
		//echo "<td></td>";
		/*
		foreach($myrow as $key => $value){
			echo $key." => ".$value." <br>\n";
		}
		echo "<p>\n";
		*/
		$dept = $myrow['dept'];
		$number = $myrow['number'];
		$section = $myrow['section'];
		$days = $myrow['days'];
		$time = $myrow['time'];
		$location = $myrow['location'];
		$type = $myrow['type'];
		$html = '<!--<font color="#000000">--><b>'.$dept.' '.$number.'</b><br>'.$type.'<br><a href="'.getLocationsURL($location).'">'.$location.'</a><!--</font>-->';
		//parse time
		//need to set $start_time and $interval
		//echo '1';
		if($time != 'ARR'){
			converttime($time, $start_time, $end_time, $interval);
			
			if($start_time < $earliest_time){
				$earliest_time = $start_time;
			}
			if($end_time > $latest_time){
				$latest_time = $end_time;
			}
			//var_dump($start_time);
			//var_dump($interval);
			//parse days
			$temp = $days; // a copy of days so we can use $days later
			$day_strings = array(0 => 'SU', 1 => 'M', 2 => 'T', 3 => 'W', 4 => 'TH', 5 => 'F', 6 => 'SA');
			//echo '2';
			//var_dump($day_strings);
			//have to go backwards in order to get TH to replace before T
			for($i = 6; $i >= 0; $i--){
				$key = $i;
				$value = $day_strings[$i];
				//echo '3';
				if(stristr($temp, $value)){
					$classes_arr[$key][$start_time] = array('html' => $html, 'interval' => $interval);
					//echo $html.$interval;
					//should be str_ireplace, but need >= PHP 5 
					$temp = str_replace($value, '', $temp);

				}
			}
		}
	}
	
	
	//get fullname from users table
	$result = sql("select fullname from $users where uniqname = '$uniqname'");
	if($uniqname == ''){
		$fullname = "UNKNOWN";
	}else{
		$fullname = mysql_result($result, 0, 'fullname');
	}
	$options = array(
				"row_interval" => 30, // set the schedule to display a row for every /2hr
				"start_time" => $earliest_time-70, // schedule start time
				"end_time" => $latest_time+30,   // schedule end time
				"title" => "Class Schedule for $fullname by <small><a style=\"color:white;\" href=\"http://www.mschedule.com/\" target=\"_top\">Mschedule.com</a></small>",
				"title_style" => "font-family: verdana; font-size: 14pt;", // css style for schedule title
				"time_style" => "font-family: verdana; font-size: 8pt;",  // css style for the time cells
				"dayheader_style" => "font-family: verdana; font-size: 10pt;", // css style for the day header cells
				
				// default css style for the class cells. Eachs style can be overridden using the "style" property of each class
				// see schedule.inc.php for details.
				"class_globalstyle" => "font-family: verdana; font-size: 8pt; text-align: center;background-color: #ccc;", 
				"blank_cell_style" => "background-color: #ccc;"
	);
	
	
	//var_dump($classes_arr);
	return schedule_generate($classes_arr, $options);
}
?>
