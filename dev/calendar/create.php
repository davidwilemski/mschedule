<?php

$term = 'WN2007';
$uniqname = $_GET['uniqname'];
$folders = "calendars/temp";

set_time_limit(0);

require_once "func.createVevent.php";

require_once "icalcreator/iCalcreator.class.php";
require_once "db.php";

$result = $db->getAll("SELECT t2.* FROM `uniqname_class_winter07` as t1, `timesched_WN2007` as t2 WHERE t1.uniqname = ? AND t1.classid = t2.class_nbr", array($uniqname));
if(PEAR::isError($result)){
	die($result->getMessage());
}

if(!is_dir($folders)){
        mkdir($folders, 0777, true);
}

$v = new vcalendar();

foreach($result as $row){
	$v->addComponent( createVevent($row) );
}

$v->returnCalendar($folders); 


