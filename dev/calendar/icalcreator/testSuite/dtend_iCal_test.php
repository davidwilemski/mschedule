<?php // dtend_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );
$o = new vevent();
$o->setDtend( 1, 2, 3 );
$o->setComment( '1: 1, 2, 3' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( 1, 2, 3, 4, 5, 6 );
$o->setComment( '2: 1, 2, 3, 4, 5, 6' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( 2006, 8, 11, 16, 30, 0, '-040000' ); // 11 august 2006 16.30.00 -040000
$o->setComment( "2b: 2006, 8, 11, 16, 30, 0, '-040000'" );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( array( 1, 2, 3, 4, 5, 6 ) );
$o->setComment( '2c: array( 1, 2, 3, 4, 5, 6 )' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( array( 1, 2, 3, 4, 5, 6, '+0400' ) );
$o->setComment( "2d: array( 1, 2, 3, 4, 5, 6, '+0400' )" );
$c->addComponent( $o );


$o = new vevent();
$o->setDtend( 1, 2, 3 );
$o->setComment( '3: 1, 2, 3' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( array( 1, 2, 3 ));
$o->setComment( '3b: array( 1, 2, 3 )' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 6, 'tz' => 'UTC 2000' ));
$o->setComment( "4: array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 6, 'tz' = 'UTC 2000' )" );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( array( 'year' => 1, 'month' => 2, 'day' => 3, 'tz' => 'Z' ));
$o->setComment( "5: array( 'year' => 1, 'month' => 2, 'day' => 3, 'tz' => 'Z' )" );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( '2001-02-03 04:05:06 US-Eastern' );
$o->setComment( '6: 2001-02-03 04:05:06 US-Eastern' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( '2001-02-03' );
$o->setComment( '7: 2001-02-03' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( '20010203' );
$o->setComment( '8: 20010203' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( '20010203040506 UTC' );
$o->setComment( '9: 20010203040506 UTC' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( '3 Feb 2001 GMT' );
$o->setComment( '10: 3 Feb 2001 GMT' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtend( '02/03/2001' );
$o->setComment( '11: 02/03/2001' );
$c->addComponent( $o );

$o = new vevent();
$timestamp = mktime ( date('H') + 4, date('i'), date('s'), date('m'), date('d'), date('Y'));
$o->setDtend( array( 'timestamp' => $timestamp ), array ( 'jestanes', 'xkey' => 'xvalue', 'xxx' => 'yyy' ) );
$o->setComment( $timestamp.' =now+4hours tre xparams' );
$c->addComponent( $o );

$o = new vevent();
$timestamp = mktime ( date('H') + 4, date('i'), date('s'), date('m'), date('d'), date('Y'));
$o->setDtend( array( 'timestamp' => $timestamp, 'tz' => '+0100' ), array ( 'jestanes', 'xkey' => 'xvalue', 'xxx' => 'yyy' ) );
$o->setComment( $timestamp.' =now+4hours tz=+0100 tre xparams' );
$c->addComponent( $o );

$o = new vevent();
$timestamp = mktime ( date('H') + 4, date('i'), date('s'), date('m'), date('d'), date('Y'));
$o->setDtend( array( 'timestamp' => $timestamp, 'tz' => 'CEST' ), array ( 'jestanes', 'xkey' => 'xvalue', 'xxx' => 'yyy' ) );
$o->setComment( $timestamp.' =now+4hours tz=CEST tre xparams' );
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