<?php // due_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );
$o = new vtodo();
$o->setDue( 1, 2, 3 );
$o->setComment( '1: 1, 2, 3' );
$c->addComponent( $o );

$o = new vtodo();
$o->setDue( 1, 2, 3, 4, 5, 6, FALSE, array( 'xparam' ) );
$o->setComment( "2: 1, 2, 3, 4, 5, 6, FALSE, array( 'xparam' )" );
$c->addComponent( $o );

$o = new vtodo();
$o->setDue( 1, 2, 3, 4, 5, 6, '-040506', array( 'xparam' ) );
$o->setComment( "2b: 1, 2, 3, 4, 5, 6, '-040506', array( 'xparam' )" );
$c->addComponent( $o );

$o = new vtodo();
$o->setDue( array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 6, 'tz' => 'US-Eastern' ));
$o->setComment( "3: array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 6, 'tz' => 'US-Eastern' )" );
$c->addComponent( $o );

$o = new vtodo();
$o->setDue( array( 'year' => 1, 'month' => 2, 'day' => 3 ), array( 'xparamKey' => 'xparamValue' ));
$o->setComment( "4: array( 'year' => 1, 'month' => 2, 'day' => 3 ), array( 'xparamKey' => 'xparamValue' )" );
$c->addComponent( $o );

$o = new vtodo();
$o->setDue( '2001-02-03 04:05:06', array( 'xparamKey' => 'xparamValue' ) );
$o->setComment( "5: 2001-02-03 04:05:06, array( 'xparamKey' => 'xparamValue' )" );
$c->addComponent( $o );

$o = new vtodo();
$o->setDue( '2001-02-03' );
$o->setComment( '6: 2001-02-03' );
$c->addComponent( $o );

$o = new vtodo();
$o->setDue( '20010203' );
$o->setComment( '7: 20010203' );
$c->addComponent( $o );

$o = new vtodo();
$o->setDue( '20010203040506' );
$o->setComment( '8: 20010203040506' );
$c->addComponent( $o );

$o = new vtodo();
$o->setDue( '3 Feb 2001' );
$o->setComment( '9: 3 Feb 2001' );
$c->addComponent( $o );

$o = new vtodo();
$o->setDue( '02/03/2001', array( 'xparamKey' => 'xparamValue' ) );
$o->setComment( "10: 02/03/2001, array( 'xparamKey' => 'xparamValue' )" );
$c->addComponent( $o );

$o = new vtodo();
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$o->setDue( array( 'timestamp' => $timestamp ), array ( 'jestanes', 'xkey' => 'xvalue', 'xxx' => 'yyy' ) );
$o->setComment( '13: '.$timestamp.' =now tre xparams' );
$c->addComponent( $o );

$o = new vtodo();
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$o->setDue( array( 'timestamp' => $timestamp, 'tz' => '+0100' ), array ( 'jestanes', 'xkey' => 'xvalue', 'xxx' => 'yyy' ) );
$o->setComment( '14: '.$timestamp.' =now tz=+0100 tre xparams' );
$c->addComponent( $o );

$o = new vtodo();
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$o->setDue( array( 'timestamp' => $timestamp, 'tz' => 'CEST' ), array ( 'jestanes', 'xkey' => 'xvalue', 'xxx' => 'yyy' ) );
$o->setComment( '15: '.$timestamp.' =now tz=CEST tre xparams' );
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