<?php

require_once "Cache/Lite.php";

require_once "db.php";
require_once "func.parseSubject.php";
require_once "func.parseLocation.php";
require_once "func.parseTime.php";


$term = 'WN2007';
$startdatearray = array('th' => '04', 'f' => '05', 's' => '06', 'su' => '07', 'm' => '08', 't' => '09', 'w' => '10');

set_time_limit(0);


$result = $db->getAll("SELECT * FROM `timesched_$term`");
if(PEAR::isError($result)){
	die($result->getMessage());
}

$placemarks = array();
$counter = 0;
foreach($result as $row){
	$subject = parseSubject($row['subject']);
	$location = parseLocation($row['location']);
	$time = parseTime($row['time']);

if(!isset($location) || !isset($location['center']) || !isset($location['center']['x']) || !isset($location['center']['y']) || $location['center']['x'] == 0 || $location['center']['y'] == 0) {
continue;
}

	foreach($startdatearray as $key => $day){
		if($row[$key]){
			array_push($placemarks, array('subject' => $subject, 'location' => $location, 'time' => $time, 'day' => $day, 'info' => $row, 'x' => $location['center']['x'], 'y' => $location['center']['y']));
		}
	}
//	if($counter > 1000) break;
	$counter++;
}

foreach($placemarks as $key => $p){
	foreach(array('start', 'end') as $i){
		$time = $p['time'][$i];
//if($time['minute'] == 60) var_dump($p['info']['time']);
		$placemarks[$key]['time'][$i] = date("Y-m-d\TH:i:s-05:00", strtotime("June {$p['day']}, 2007 {$time['hour']}:{$time['minute']} EST"));
	}
}

header("Content-Type: text/xml");

print '<?xml version="1.0" encoding="UTF-8"?>'."\n";
?>
<kml xmlns="http://earth.google.com/kml/2.1">
<Document>
  <name>Dan's Feature</name>
  <open>1</open>
<?php
foreach($placemarks as $p){
?>
  <Placemark>
    <name><![CDATA[<?=$p['subject']['abbr']?> <?=$p['info']['catalog_nbr']?> <?=$p['info']['component']?>]]></name>
	<description><![CDATA[ 
Section: <?=$p['info']['section']?><br>
Title: <?=$p['info']['course_title']?><br>
Time: <?=$p['info']['time']?><br>
Location: <?=$p['info']['location']?><br>
Instructor: <?=$p['info']['instructor']?>
]]></description>
	<TimeSpan>
		<begin><?=$p['time']['start']?></begin>
		<end><?=$p['time']['end']?></end>
	</TimeSpan>
    <Point>
      <coordinates><?=$p['x']?>,<?=$p['y']?></coordinates>
    </Point>
  </Placemark>
<?php	
}
?>
</Document>
</kml>

