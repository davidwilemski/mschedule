<?php
require_once "inc/common.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msmap.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msbuildingdb.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msbuilding.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msinputgrabber.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.mshtmlform.php";

//generate the contents of the page
$MSOUTPUT = new MSSmartyPrimary();
$smarty = new MSSmarty();
$input = new MSInputGrabber();

//set up the search form
$form = new MSHTMLForm("campusMapSearchForm","coursemaps.php",_BUTTON_SEARCH);

$form->add_textField("buildAbbrev",_SEARCHFIELD_BUILDABBREV,50,15);
$form->add_textField("buildName",_SEARCHFIELD_BUILDING_NAME,90,30);
$form->add_hiddenField("msAction","msSearchMapLocation");


//get an array of maps we can choose locations from
$maps = array();

foreach ($cfg['maps']['names'] as $mapName)
	$maps[$mapName] = new MSMap($mapName);
	
//process form input, if there was any
if(processSearchInput($maps))
	$smarty->assign("showParagraph", false);
else
	$smarty->assign("showParagraph", true);
	

//only show maps that have waypoints on them
$mapsToShow = array();
foreach ($maps as $map)
	if ($map->numWaypoints() > 0)
		array_push($mapsToShow,$map);

//assign the vars to the smarty, and then fetch the content
$smarty->assign("maps",$mapsToShow);
$smarty->assign("form",$form);
$content = $smarty->fetch("coursemaps.shtml");


//##################################################################
//set the pagetitle and content, and render the page
$MSOUTPUT->assign("pagetitle",_TITLE_COURSEMAPS);
$MSOUTPUT->assign("content",$content);

$MSOUTPUT->render();


function processSearchInput(&$maps)
{
	global $cfg;
	$input = new MSInputGrabber();
	
	//if the input stream didn't have the search action in it, fail out
	if ($input->inputVar("msAction")!="msSearchMapLocation")
		return false;
	
	//get the input
	$abbrevs = $input->inputVar("buildAbbrev");
	$names = $input->inputVar("buildName");
	
	//parse the commas correctly in the input (debugmjp: this stuff should gets its own function somewhere...MSTextProcessor class maybe?)
	$abbrevs = preg_replace("/\s+,\s+/",',',$abbrevs);
	$names = preg_replace("/\s+,\s+/",',',$names);

	//explode those input lists by comma
	$abbrevArray = explode(",", $abbrevs);
	$nameArray = explode(",", $names);
	
	
	//debugmjp: this is a hacked together search by name...it basically converts the searched name into an abbrev
	if ($nameArray) {
		
		global $MSDB;
		
		$beginsql = "SELECT abbr FROM {$cfg['db']['tables']['buildings']} WHERE ";
		$sql = '';
		
		foreach ($nameArray as $key=>$name) {
			
			if ($name != "" && $key!=0)
				$sql .= " or ";
			
			if ($name != "")
				$sql .= "name LIKE '%$name%'";
		}
		
		//exec the sql if it's valid
		if (@$sql != '') {
			$result = $MSDB->sql($beginsql.$sql);
		
			//now that we have the abbreviations, we append them to the array of abbrevs
			while($next = mysql_fetch_assoc($result)	)
				array_push($abbrevArray, $next['abbr']);	
		}
	}
		
	
	//do the DB search
	foreach ($abbrevArray as $abbrev) {
		
		$building = new MSBuilding($abbrev);
		
		$mapName = $building->location->map;
		$x = $building->location->x;
		$y = $building->location->y;
		$title = $building->location->name;
		
		$waypoint = new MSMapWaypoint($title,$x,$y);
		
		//set the popup HTML
		$HTML = "$abbrev <br> <b>$title</b>";
		$waypoint->set_popup($HTML);
		
		if (@$maps[$mapName])
			$maps[$mapName]->add_waypoint($waypoint);
	}
	
	return true;	
}


?>
