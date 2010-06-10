<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once $cfg['ms_rootpath']['server']."/inc/common.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msdbcn.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.mslocation.php";


/*
 * Provides for the editing of the internal building database
 */

class MSBuildingDB
{
	//returns an array holding all building abbreviations in the database
	function get_allBuildingLocations()
	{
		global $MSDB, $cfg;
		$locations = array();
		
		//search the DB for a list of all building names
		$sql = "SELECT * FROM `{$cfg['db']['tables']['buildings']}`";
		
		$result = $MSDB->sql($sql);
		
		if(!$result)
			return false; //return false on DB failure
		
		//push the results into an array
		while($row = mysql_fetch_assoc($result))
			array_push($locations, new MSLocation(
									$row['map'],
									$row['x'],
									$row['y'],
									$row['name']));
		
		return $locations;
	}
	
	//add coordinates to the database
	function change_location($name, $map, $x, $y)
	{
		global $MSDB, $cfg;
		
		//UPDATE THE BUILDINGS DATABASE
		$MSDB->sql("UPDATE `{$cfg['db']['tables']['buildings']}` SET map='$map', x='$x', y='$y' WHERE `name` = '$name'");
	}
}
?>