<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once $cfg['ms_rootpath']['server']."/classes/class.mscourse.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msevent.php";
require_once $cfg['ms_rootpath']['server']."/inc/db.php";

//a user's schedule for a week
class MSSchedule
{
	var $courses;	//array of MSCourse
	var $events;	//array of MSEvent (does not include courses)
	
	function MSSchedule($uniqname)
	{
		$this->_loadCoursesFromDatabase($uniqname);
	}
	
	function _loadCoursesFromDatabase($uniqname)
	{
		global $MSDB, $cfg;
		
		$result = $MSDB->sql("SELECT * FROM `{$cfg['db']['tables']['user_class']}` where `uniqname` = '$uniqname'");
		while($row = mysql_fetch_assoc($result)){
			array_push(new MSCourse($row['classid']));
		}
	}
	
	//params:
	//	$event - MSCourse
	function add_course($course)
	{
		array_push($course);
	}
	
	function remove_course($index)
	{
		
	}
	
	//params:
	//	$event - MSEvent
	function add_event($event)
	{
		array_push($event);
	}
	
	function remove_event($index)
	{
		
	}
	
	function get_events()
	{
		return $this->events;
	}
	
	function get_courses()
	{
		return $this->courses;
	}
}

?>