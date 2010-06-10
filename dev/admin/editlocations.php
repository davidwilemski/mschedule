<?php
require_once "../inc/common.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msmap.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msbuildingdb.php";
require_once $cfg['ms_rootpath']['server']."/classes/class.msinputgrabber.php";

//generate the contents of the page
$MSOUTPUT = new MSSmartyPrimary();
$smarty = new MSSmarty();
$input = new MSInputGrabber();
$buildingdata = new MSBuildingDB();


//if there was input, go ahead and update the DB
updateIfNeccessary($buildingdata);

//get an array of maps we can choose locations from
$maps = array();

foreach ($cfg['maps']['names'] as $mapName)
	$maps[$mapName] = new MSMap($mapName);

$smarty->assign("maps",$maps);


//get an array of locations we can choose from
$locations = $buildingdata->get_allBuildingLocations();
$smarty->assign("locations",$locations);

//set the msAction
$smarty->assign("msAction","updateLocation");


//fetch the content
$content = $smarty->fetch("admin/editlocations.shtml");

//set the pagetitle and content, and render the page
$MSOUTPUT->assign("pagetitle",_ADMIN_TITLE_EDITLOCATIONS);
$MSOUTPUT->assign("content",$content);

$MSOUTPUT->render();



//####################################################
//####################################################
//####################################################

function updateIfNeccessary(&$buildingdata)
{
	global $cfg;
	
	$input = new MSInputGrabber();
	
	//if the msAction was set, do the action
	if ($input->postVar("msAction") == "updateLocation") {
		
		//determine the name of the location
		$locName = $input->postVar("locName");

		//determine if no map was found
		if ($input->postVar("noMapFound")) {
			$locX = 0;
			$locY = 0;
			$mapName = "NONE";
		
		//if a map was found, then get its coordinates
		} else {
			
			//determine map name
			$keys = array_keys($_POST);
			foreach($keys as $key){
				$array = explode("_", $key);
				if(count($array) == 2 and in_array($array[0], $cfg['maps']['names']) and $array[1] == "x"){
					$mapName = $array[0];
					break;
				}
			}
			
			$locX = $input->postVar($mapName."_x");
			$locY = $input->postVar($mapName."_y");
		}
		
		//modify the database
		$buildingdata->change_location($locName, $mapName, $locX, $locY);
	}
}
?>
