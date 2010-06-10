<?php // duration_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );
$o = new vtodo();
$o->setDuration( 1 );
$o->setComment( '1: 1' );
$c->addComponent( $o );

$o = new vtodo();
$o->setDuration( false, 2, FALSE, FALSE, FALSE, array( 'xparam' ) );
$o->setComment( "2: false, 2, FALSE, FALSE, FALSE, array( 'xparam' )" );
$c->addComponent( $o );

$o = new vtodo();
$o->setDuration( false, 2, 3 );
$o->setComment( '3: false, 2, 3' );
$c->addComponent( $o );

$o = new vtodo();
$o->setDuration( false, false, 3, false, 5 );
$o->setComment( '4: false, false, 3, false, 5' );
$c->addComponent( $o );

$o = new vtodo();
$o->setDuration( array( 'day' => 2, 'hour' => 3, 'sec' => 5 ), array( 'xparamkey' => 'xparamvalue' ));
$o->setComment( "5: array( 'day' => 2, 'hour' => 3,  'sec' => 5 ), array( 'xparamkey' => 'xparamvalue' )" );
$c->addComponent( $o );
$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

/*
echo strtotime ("now"), "<br />\n";
echo strtotime ("10 September 2000"), "<br />\n";
echo strtotime ("+1 day"), "<br />\n";
echo strtotime ("+1 week"), "<br />\n";
echo strtotime ("+1 week 2 days 4 hours 2 seconds"), "<br />\n";
echo strtotime ("next Thursday"), "<br />\n";
echo strtotime ("last Monday"), "<br />\n";

// LC_ALL=C TZ=UTC0 date
echo date( 'Y-m-d H:i:s Z', strtotime('Fri Dec 15 19:48:05 UTC 2000'));   echo "<br />\n"; // test ###
echo date( 'Y-m-d H:i:s',   strtotime('Fri Dec 15 19:48:05'));            echo "<br />\n"; // test ###
// TZ=UTC0 date +"%Y-%m-%d %H:%M:%SZ"
echo date( 'Y-m-d H:i:s Z', strtotime('2000-12-15 19:48:05Z'));           echo "<br />\n"; // test ###
// date --iso-8601=seconds  # a GNU extension
echo date( 'Y-m-d H:i:s Z', strtotime('2000-12-15T11:48:05-0800'));       echo "<br />\n"; // test ###
// date --rfc-822  # a GNU extension
echo date( 'Y-m-d H:i:s Z', strtotime('Fri, 15 Dec 2000 11:48:05 -0800'));echo "<br />\n"; // test ###
// date +"%Y-%m-%d %H:%M:%S %z"  # %z is a GNU extension.
echo date( 'Y-m-d H:i:s Z', strtotime('2000-12-15 11:48:05 -0800'));      echo "<br />\n"; // test ###

// string datestring // date in a string, acceptable by strtotime-command, only local time
*/
?>