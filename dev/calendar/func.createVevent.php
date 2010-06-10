<?php
require_once "icalcreator/iCalcreator.class.php";
require_once "func.parseSubject.php";


function createVevent($row){
$subject = parseSubject($row['subject']);
$summary = "{$subject['abbr']} {$row['catalog_nbr']}";
$location = $row['location'];

$byday = '';
foreach(array('m' => 'MO', 't' => 'TU', 'w' => 'WE', 'th' => 'TH', 'f' => 'FR', 's' => 'SA', 'su' => 'SU') as $key => $value){
        if($row[$key]){
                if(!$firstday) $firstday = $value;
                if($byday != '') $byday .= ',';
                $byday .= $value;
        }
}
$startdatearray = array('TH' => 4, 'FR' => 5, 'SA' => 6, 'SU' => 7, 'MO' => 8, 'TU' => 9, 'WE' => 10);
$startdate = $startdatearray[$firstday];

$time = parseTime($row['time']);

$durationminute = $time['end']['minute'] - $time['start']['minute'];
$durationhour = $time['end']['hour'] - $time['start']['hour'];
$e = new vevent();
$e->setDtstart( 2007, 01, $startdate, $time['start']['hour'], $time['start']['minute'], 00 );
$e->setDuration( 0, 0, $durationhour, $durationminute );
$e->setRrule(array('BYDAY' => array($byday), 'FREQ' => 'WEEKLY', 'UNTIL' => array(2007, 04, 17)));
$e->setSummary($summary);
$e->setLocation($location);

//I couldn't get the EXDATEs to work with iCal, so I'm scraping them
//$exdates = array(array(2007, 1, 15), array(2007, 2, 25), array(2007, 2, 26), array(2007, 2, 27), array(2007, 2, 28), array(2007, 2, 29), array(2007, 3, 1), array(2007, 3, 2), array(2007, 3, 3), array(2007, 3, 4));

//foreach($exdates as $key => $exdate){
//	array_push($exdates[$key], (int)$time['start']['hour'], (int)$time['start']['minute'], 0, "US/Eastern");
//}
//$e->setExdate($exdate);

return $e;
}
