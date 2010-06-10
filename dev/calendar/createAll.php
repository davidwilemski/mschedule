<?php

$term = 'WN2007';
$folders = "calendars";
$filename = "temp.ics";
$uniqname = "mulka";

set_time_limit(0);

require_once "func.createVevent.php";

require_once "icalcreator/iCalcreator.class.php";
require_once "db.php";

$result = $db->getAll("SELECT * FROM timesched_$term WHERE time != 'ARR'");
if(PEAR::isError($result)){
	die($result->getMessage());
}


foreach($result as $key => $row){
	$v = new vcalendar();
	preg_match('/\(([^()]*)\)/', $row['subject'], $matches);
	
	$folders = "calendars/$term/{$matches[1]}/{$row['catalog_nbr']}";
	$filename = "{$matches[1]}_{$row['catalog_nbr']}_{$row['section']}.ics";
	if(is_file("$folders/$filename")) continue;
	if(!is_dir($folders)){
		mkdir($folders, 0777, true);
	}
	
	
	$v->setFilename($folders, $filename);
	$v->addComponent( createVevent($row) );
	
	$v->saveCalendar(); 
}




