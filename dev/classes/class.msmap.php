<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once $cfg['ms_rootpath']['server']."/inc/common.php";

/*
 * Provides for the editing of the internal building database
 */
 
class MSMapWaypoint
{
	var $x;
	var $y;
	var $label;
	var $popupHTML;
	
	function MSMapWaypoint($label,$x,$y)
	{
		$this->label = $label;
		$this->x = $x;
		$this->y = $y;
	}
	
	function set_popup($content)
	{
		$this->popupHTML = $content;
	}
}

class MSMap
{
	var $large;
	var $small;
	var $thumb;
	var $name;
	var $title;
	
	var $waypoints; //an array of MSMapWaypoints to display on the map
	
	//REQ: the name of a map
	//EFF: gets the map data for that map (width/height of large/small/thumb sizes)
	function MSMap($name)
	{
		global $MSDB,$cfg;
		
		//execute the SQL to get an array of values for this map
		$sql = "SELECT * FROM {$cfg['db']['tables']['mapdata']} WHERE name like '$name'";
		$result = $MSDB->sql($sql);
		
		if (!$result)
			return false;  //return false if the building was not found
		
		$array = mysql_fetch_assoc($result);
		
		//all members are arrays {x,y}
		$this->large = array(
						'x' => $array['largeX'],
						'y' => $array['largeY']);
					
		$this->small = array(
						'x' => $array['smallX'],
						'y' => $array['smallY']);
					
		$this->thumb = array(
						'x' => $array['thumbX'],
						'y' => $array['thumbY']);
		
		//set the name
		$this->name = $name;
		
		//set the title of this map
		$this->title = $array['title'];
		
		//init waypoints as an array
		$this->waypoints = array();
	}
	
	//REQ: an MSMapWaypoint
	//EFF: pushes this waypoint onto the waypoint array
	function add_waypoint($waypoint)
	{
		array_push($this->waypoints,$waypoint);
	}
	
	//EFF: returns the # of waypoints on this map
	function numWaypoints()
	{
		return count($this->waypoints);
	}
}
?>