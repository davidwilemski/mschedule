<?php
//building location editor admin page

include "../inc/common.php";
require_once "class.msbuildingeditor.php";

//open inteface to building database
$editor = new MSBuildingEditor();

function handlePostData($post)
{
	global $cfg, $editor;
	if(isset($post['abbr'])){
		
		//determine abbreviation
		$abbr = $post['abbr'];
		
		//determine map name
		$keys = array_keys($post);
		foreach($keys as $key){
			$array = explode("_", $key);
			if(count($array) == 2 and in_array($array[0], $cfg['maps']['names']) and $array[1] == "x"){
				$map = $array[0];
				break;
			}
		}
		
		//determine x and y coordinates
		$x = $post[$map.'_x'];
		$y = $post[$map.'_y'];
		
		$editor->changeLocation($abbr, $map, $x, $y);
		print "<b>building location saved</b>";
	}
}

//print post data
print "<p><pre>";
var_dump($_POST);
print "</pre></p>";

handlePostData($_POST);


//get list of buildings (and locations)
$array = $editor->get_buildings();
/*
print "<p><pre>";
var_dump($array);
print "</pre></p>";
*/

//start form and show building selecter
?>
<form method="post"  action="<?=$_SERVER['PHP_SELF']?>">
<SELECT NAME="abbr">
<OPTION VALUE="">Choose a Building...
<?
//iterate through buildings
foreach($array as $building){
	print '<OPTION VALUE="';
	print $building->abbr;
	print '">';
	print $building->abbr;
	print " - ";
	print $building->name;
	print " - ";
	print $building->location->map;
	print " - ";
	print $building->location->x;
	print " - ";
	print $building->location->y;
	print "\n";
}
print "</SELECT>";

//show images (which act like submit buttons)
foreach($cfg['maps']['names'] as $mapName){
	print "<hr>\n";
	print $mapName;
	print "<br>\n";
	print "<input name=\"";
	print $mapName;
	print "\" type=image src=";
	print $cfg['maps']['path'];
	print "/";
	print $mapName;
	print ".";
	print $cfg['maps']['ext'];
	print ">\n";
}
print "</form>";

?>