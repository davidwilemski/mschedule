<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once $cfg['ms_rootpath']['server']."/classes/class.msevent.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msbuilding.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.mscoursemeeting.php";
require_once $cfg['ms_rootpath']['server']."/inc/db.php";

class MSCourse
{
	//format taken from Wolverine Access in hopes of scraping it
	var $courseID;		//int
	var $status;		//bool		(1=Open, 0=Closed)
	var $subject;		//string 	(department, ex: EECS)
	var $number;		//int		(3 digit course number)
	var $component;		//string	(LEC, DIS, REC, LAB, etc.)
	var $section;		//int		(course section number)
	var $desc;			//string	(description, title)
	var $credits;		//int
	var $openSeats;		//int
	var $waitNumber;	//int		(number of people on the wait list)
	var $_meetings;		//array of MSCourseMeeting (use get_meetings to access)
	var $directenroll;	//bool		(whether can directly enroll in this course)
	var $autoenrollID;	//int		(courseID of the course that you will be autoenrolled in when you enroll for this course)
	//building is now part of a CourseMeeting
	//var $building;		//MSBuilding
	
	//params:
	//	$param - a course id or an associative array from a row of the database
	function MSCourse($param)
	{
		if(is_array($param)){
			//load data from associative array
			$this->_loadFromArray($param);
		}else{
			//get data from database
			$this->_loadFromDatabase($param);
		}
	}
	
	function get_meetings(){
		if(!isset($this->_meetings)){
			_loadMeetings($this->courseID);
		}
		
		return $this->_meetings;
	}
	
	//params:
	//	array - an associative array with the required variables
	//
	//NOTE: This is called both from the constructor and from _loadFromDatabase
	function _loadFromArray($array)
	{
		$this->courseID = $array['courseID'];
		$this->status = $array['status'];
		$this->subject = $array['subject'];
		$this->number = $array['number'];
		$this->component = $array['component'];
		$this->section = $array['section'];
		$this->desc = $array['desc'];
		$this->credits = $array['credits'];
		$this->openSeats = $array['openSeats'];
		$this->waitNumber = $array['waitNumber'];
		
		//$this->_loadMeetings($array['courseID']);
	}
	
	function _loadFromDatabase($courseID)
	{
		global $MSDB, $cfg;
		$result = $MSDB->sql("SELECT * FROM `{$cfg['db']['tables']['wa_sections']}` WHERE courseID = '{$courseID}'");
		$row = mysql_fetch_assoc($result);
		$this-_loadFromArray($row);
	}
	
	function _loadMeetings($courseID)
	{		
		global $MSDB, $cfg;
		
		
		//print "loading meetings\n";
		//parse course meeting days and put into separate MSCourseMeeting's
		$day_strings = array(0 => 'Su', 1 => 'M', 2 => 'Tu', 3 => 'W', 4 => 'Th', 5 => 'F', 6 => 'Sa');

		$result = $MSDB->sql("SELECT * FROM `{$cfg['db']['tables']['wa_meetings']}` WHERE courseID = '{$courseID}'");
		
		$this->_meetings = array();
		while($row = mysql_fetch_assoc($result)){
			//go through $day_strings backwards (in order to get TH to replace before T) to search for day_strings
			for($day = 6; $day >= 0; $day--){
				$day_string = $day_strings[$day];
				//print "day $i\n";
				if(stristr($row['days'], $day_string)){
					//print "push";
					array_push($this->_meetings, new MSCourseMeeting($day, $row));
					$days = str_replace($day_string, '', $row['days']);
				}
			}
		}
		
		$this->_meetings = array_reverse($this->_meetings);
	}

}