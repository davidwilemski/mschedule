<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once $cfg['ms_rootpath']['server']."/inc/miltime.php";

//an event in a calendar week
class MSEvent
{
	var $day;		//int 	(0=Sunday, 1=Monday, 2=Tuesday, 3=Wednesday, 4=Thursday, 5=Friday, 6=Saturday)
	var $startTime;	//int 	(military time, example: 1430=2:30pm, range: 0-2359)
	var $length;	//int 	(in minutes)
	var $title;		//string
	var $location;	//string
	var $desc;		//string
	
	function MSEvent($day, $startTime, $length, $title, $desc = '', $location = '')
	{
		//more error checking and formating should go here, but for now, take data straight up
		$this->day = $day;
		$this->startTime = $startTime;
		$this->length = $length;
		$this->title = $title;
		$this->desc = $desc;
		$this->location = $location;
	}
	
	//params:
	//	$format - 'int' or 'string'
	function get_day($format = 'string')
	{
		switch($format){
			case 'string':
				$rv = date('l', mktime(0,0,0,0,$this->day));
				break;
			case 'int':
				$rv = $this->day;
				break;
		}
		return $rv;
	}
	
	//params:
	//	$format - 'military', 'normal'
	function get_startTime($format = 'normal')
	{
		return formatTime($this->startTime, 'military', $format);
	}
	
	function get_endTime($format = 'normal')
	{
		// add length to startTime, then format
		return formatTime(miltimeadd($this->startTime, mintomil($this->length)), 'military', $format);
	}
	
	//params:
	//	$format - 'hours', 'minutes'
	function get_length($format = 'minutes')
	{
		return formatTime($this->length, 'minutes', $format);
	}
	
	function get_title()
	{
		return $this->title;
	}
	
	function get_desc()
	{
		return $this->desc;
	}
	
	function get_location()
	{
		return $this->location;
	}
}

?>