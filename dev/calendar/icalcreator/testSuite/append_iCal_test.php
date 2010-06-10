<?php // append_iCal_test.php
/*
echo"12345678901234567890123456789012345678901234567890123456789012345678901234567890<br />\n";
echo "         1         2         3         4         5         6         7         8<br />\n";
echo 'TRIGGER;TZID=Europe/Rome:20060404T234800'."\n"
    .'X-WR-ALARMID:SOUNDALARM:Glass'."\n"
    .'X-WR-ALARMUID:E2393CA4-4B32-4DC4-93F2-EEAC86E598D0'."\n"
    .'ATTACH;VALUE=URI:Glass';echo "\n<br />\n<br />\n";
*/
require_once '../iCalcreator.class.php';
// ##############################################
// create calendar ONE with one component/subcomponent
$c = new vcalendar();
$e = new vevent();
$e->setDescription( "Description ONE event" );
$e->setDtstart( 2006, 4, 5, 7, 0, 0, 'Europe/Rome' );
$e->setDuration( 0, 1, 0 );
$a = new valarm();
$a->setAction( 'AUDIO' );
$a->setDescription( "Description ONE event alarm" );
$a->setTrigger( 2006, 4, 4, FALSE, 23, 48, 0, TRUE, TRUE, 'Europe/Rome' );
$a->setXprop( 'X-WR-ALARMID', 'SOUNDALARM:Glass' );
$a->setXprop( 'X-WR-ALARMUID', 'E2393CA4-4B32-4DC4-93F2-EEAC86E598D0' );
$a->setAttach( 'http://www.domain.net/agendas/Glass.wav' );
$e->addSubComponent( $a );
$c->addComponent( $e );
// $str = $c->createCalendar();
echo $str."<br />\n";
$c->setFilename( '', 'test.ics' ); // set filename
$filearr = $c->saveCalendar();     // save calendar in file
echo "file=".$filearr[0].'/'.$filearr[1].' size='.$filearr[2]."<br />\n";
// ##############################################
// create calendar TWO with two components
$c = new vcalendar ();
$e = new vevent();
$e->setDescription( "Description TWO one" );
$e->setDtstart( 2006, 5, 4, 3, 2, 1, 'Europe/Rome' );
$c->addComponent( $e );
$e = new vevent();
$e->setDescription( "Description TWO 2", 'alt.text. 2' );
$e->setDtstart( 2001, 2, 3, 4, 4, 6, 'Europe/Rome' );
$c->addComponent( $e );
// ##############################################
// append components (in calendar TWO ) to calendar (ONE) file
$filearr = $c->appendCalendar( $filearr[0], $filearr[1] );
echo "file=".$filearr[0].'/'.$filearr[1].' size='.$filearr[2]."<br />\n";
// ##############################################
// display results
$c->useCachedCalendar( $filearr[0], $filearr[1] );
?>