<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}
	
require_once $cfg['ms_rootpath']['server']."/classes/class.msbuilding.php";

class MSCourseMeeting
{
	var $startTime;		//int (military time)
	var $endTime;		//int (military time)
	var $day;			//int (0=Sunday, 1=Monday, 2=Tuesday, 3=Wednesday, 4=Thursday, 5=Friday, 6=Saturday)
	var $location;		//string  (includes building)
	var $building;		//MSBuilding
	var $instructor;	//string
	
	function MSCourseMeeting($day, $row)
	{
		$this->day = $day;
		//$this->_convertTime($row['time'], $this->startTime, $this->endTime, $length);
		$this->startTime = $row['startTime'];
		$this->endTime = $row['endTime'];
		$this->location = $row['location'];
		//$this->building = new MSBuilding($row['location']);
		$this->instructor = $row['instructor'];
	}
	
	//converts a time from the registrar's version to the data I need:
	//start_time, end_time, and interval
	function _convertTime($time, &$start_time, &$end_time, &$interval){
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
	
	function get_building()
	{
		if(!isset($building))
		{
			$this->building = new MSBuilding($location);
		}
		return $this->building;
	}
}

?>