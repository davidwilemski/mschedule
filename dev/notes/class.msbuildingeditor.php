<?php
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once "../inc/common.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msdbcn.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msbuilding.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.mslocation.php";


/*
 * Provides for the editing of the internal building database
 */

class MSBuildingEditor
{
	//returns an array holding all buildings in database
	function get_buildings()
	{
		global $MSDB, $cfg;
		$rv = array();
		$result = $MSDB->sql("SELECT * FROM `{$cfg['db']['tables']['buildings']}`");
		while($row = mysql_fetch_assoc($result)){
			array_push($rv, new MSBuilding($row['abbr']));
		}
		return $rv;
	}
	
	//add coordinates to the database
	function changeLocation($name, $map, $x, $y)
	{
		print "changeLocation($name, $map, $x, $y)";
		global $MSDB, $cfg;
		$MSDB->sql("UPDATE `{$cfg['db']['tables']['buildings']}` SET map='$map', x='$x', y='$y' WHERE `abbr` = '$abbr'");
	}
}
?>