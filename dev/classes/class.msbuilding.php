<?
if ( !defined('CONFIG_INCLUDED') )
	{var_dump(debug_backtrace());exit("configuration.php missing");}

require_once $cfg['ms_rootpath']['server']."/inc/common.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.mslocation.php";
require_once $cfg['ms_rootpath']['server']."/inc/db.php";

//	represents a building where abbreviation and name are taken from 
//	http://www.umich.edu/~regoff/registration/buildings.html
//
class MSBuilding
{
	var $abbr;		//string
	var $name;		//string
	var $location;	//MSLocation
	
	function MSBuilding($abbr)
	{
		
		$this->_loadFromDatabase($abbr);
		if(isset($this->abbr)){
			return;
		}
		
		$this->_parseBuilding($abbr);
	}
	
	function _loadFromDatabase($abbr)
	{
		global $MSDB, $cfg;
		//see if building is in database
		$result = $MSDB->sql("SELECT * FROM `{$cfg['db']['tables']['buildings']}` WHERE `abbr` = '$abbr'");
		if(mysql_num_rows($result) > 0){
			$row = mysql_fetch_assoc($result);
			$this->abbr = $row['abbr'];
			$this->name = $row['name'];
			$this->location = new MSLocation($row['map'], $row['x'], $row['y'], $row['name']);
			return;
		}
	}
	
	function _parseBuilding($abbr)
	{
		global $MSERROR;
		//see if location to building mapping has been cached (not implemented yet)
		
		// some caches that should be moved to a database
		if($abbr == 'SHAPIRO MAC LAB'){
			$this->abbr = 'SHAPIRO';
		}
		
		
		//no location
		if($abbr == 'ARR' or $abbr == ''){
			$this->abbr = '';
		}
		
		//try to take the last part
		$array = explode(' ', $abbr);
		$last = array_pop($array);
		$this->_loadFromDatabase($last);
		if(isset($this->abbr)){
			return;
		}
		
		//try to take the first part off
		$array = explode(' ', $abbr);
		$string = '';
		for($i = 1; $i < count($array); $i++){
			$string .= $array[$i];
		}
		$this->_loadFromDatabase($string);
		if(isset($this->abbr)){
			return;
		}

		//try to take the numbers off and trim
		$string = trim(preg_replace("/\d/", '', $abbr));
		$this->_loadFromDatabase($string);
		if(isset($this->abbr)){
			return;
		}
		
		$MSERROR->err("MSBuilding::_parseBuilding", _ERR_PARSE_BUILDING.": $abbr");	
		
		//cache location to building mapping (not implemented yet)
		//note that return statements above prevent reaching this section of code if building mapping found
	}
	
	//params:
	//	$location (MSLocation)
	function set_location($location)
	{
		global $MSERROR;
		if(!is_a($location, "MSLocation")){
			$MSERROR->err("MSBuilding::set_location()", "location not of type MSLocation");
		}
		$this->location = $location;
	}
}

?>