<?php
require_once "../inc/configuration.php";
require_once "../admin/class.msbuildingeditor.php";

$MSBE = new MSBuildingEditor;

$buildings = $MSBE->get_buildings();

$array = array();
foreach($buildings as $building){
	array_push($array, new MSBuilding($building->abbr));
}

print "<pre>";
var_dump($array);
?>