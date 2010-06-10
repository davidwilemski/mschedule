<?php // exdate_iCal_test.php

require_once '../iCalcreator.class.php';

$d1  = array( 2001, 2, 3 );
$d2  = array( 2002, 2, 3, 4, 5, 6, '-040506' );
$d3  = array( 'year' => 2003, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 6 );
$d4  = array( 'year' => 2004, 'month' => 2, 'day' => 3, 'tz' => '+1200' );
$d5  = '2005-02-03 04:05:06';
$d6  = '2006-02-03';
$d7  = '20070203';
$d8  = '20080203040506';
$d9  = '3 Feb 2009';
$d10 = '01/02/2010';
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$d11 = array( 'timestamp' => $timestamp );
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$d12 = array( 'timestamp' => $timestamp, 'tz' => '+0100' );
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$d13 = array( 'timestamp' => $timestamp, 'tz' => 'CEST' );

$c = new vcalendar ();
$c->setFormat( "xcal" );
$o = new vtodo();
$o->setUid( '-#- this is input uid, NOT generated-#-', array ( 'xparamKey' => 'xparamValue' ));
$o->setExdate( array( $d1 ));
$o->setExdate( array( $d1, $d10 ), array( 'xparam' ));
$o->setExdate( array( $d1, $d10, $d2 ), array( 'xparamKey' => 'xparamValue' ));
$o->setComment( implode('-', $d1 ));
$o->setComment( implode('-', $d1).' '.$d10 );
$o->setComment( implode('-', $d1).' '.$d10.' '.implode('-', $d2 ));
$c->addComponent( $o );

$o = new vtodo();
$o->setExdate( array( $d2 ));
$o->setExdate( array( $d2, $d9 ));
$o->setExdate( array( $d2, $d9, $d4 ));
$o->setComment( implode('-', $d2 ));
$o->setComment( implode('-', $d2).' '.$d9 );
$o->setComment( implode('-', $d2).' '.$d9.' '.implode('-', $d4));
$c->addComponent( $o );

$o = new vtodo();
$o->setExdate( array( $d3 ));
$o->setExdate( array( $d3, $d8 ));
$o->setExdate( array( $d3, $d8, $d6 ));
$o->setComment( implode('-', $d3));
$o->setComment( implode('-', $d3).' '.$d8 );
$o->setComment( implode('-', $d3).' '.$d8.' '.$d6 );
$c->addComponent( $o );

$o = new vtodo();
$o->setExdate( array( $d4 ));
$o->setExdate( array( $d4, $d7 ));
$o->setExdate( array( $d4, $d7, $d8 ), array( 'xparamKey' => 'xparamValue', 'yparam' ));
$o->setComment( implode('-', $d4 ));
$o->setComment( implode('-', $d4).' '.$d7 );
$o->setComment( implode('-', $d4).' '.$d7.' '.$d8 );
$c->addComponent( $o );

$o = new vtodo();
$o->setExdate( array( $d5 ));
$o->setExdate( array( $d5, $d6 ));
$o->setExdate( array( $d5, $d6, $d10 ));
$o->setComment( $d5);
$o->setComment( $d5.' '.$d6 );
$o->setComment( $d5.' '.$d6.' '.$d10 );
$c->addComponent( $o );

$o = new vtodo();
$o->setExdate( array( $d6 ));
$o->setExdate( array( $d6, $d5 ));
$o->setExdate( array( $d6, $d5, $d9 ));
$o->setComment( $d6 );
$o->setComment( $d6.' '.$d5 );
$o->setComment( $d6.' '.$d5.' '.$d9 );
$c->addComponent( $o );

$o = new vtodo();
$o->setExdate( array( $d7 ));
$o->setExdate( array( $d7, $d4 ));
$o->setExdate( array( $d7, $d4, $d7 ));
$o->setComment( $d7 );
$o->setComment( $d7.' '.implode('-',$d4 ));
$o->setComment( $d7.' '.implode('-',$d4).' '.$d7 );
$c->addComponent( $o );

$o = new vtodo();
$o->setExdate( array( $d8 ));
$o->setExdate( array( $d8, $d3 ), array( 'xparamKey' => 'xparamValue', 'yparamKey' => 'yparamValue' ));
$o->setExdate( array( $d8, $d3, $d5 ));
$o->setComment( $d8 );
$o->setComment( $d8.' '.implode('-', $d3 ));
$o->setComment( $d8.' '.implode('-', $d3).' '.$d5 );
$c->addComponent( $o );

$o = new vtodo();
$o->setExdate( array( $d9 ));
$o->setExdate( array( $d9, $d2 ));
$o->setExdate( array( $d9, $d2, $d3 ));
$o->setComment( $d9 );
$o->setComment( $d9.' '.implode('-', $d2 ));
$o->setComment( $d9.' '.implode('-', $d2 ).' '.implode('-',$d3 ));
$c->addComponent( $o );

$o = new vtodo();
$o->setExdate( array( $d10 ));
$o->setExdate( array( $d10, $d1 ));
$o->setExdate( array( $d10, $d1, $d1 ));
$o->setComment( $d10 );
$o->setComment( $d10.' '.implode('-',$d1));
$o->setComment( $d10.' '.implode('-',$d1).' '.implode('-',$d1));
$c->addComponent( $o );

$o = new vtodo();
$o->setExdate( array( $d4 ));
$o->setExdate( array( $d11, $d4 ));
$o->setExdate( array( $d4, $d11, $d12, $d13 ));
$o->setComment( implode('-', $d4 ));
$o->setComment( implode('-', $d11 ).' '.implode('-', $d4) );
$o->setComment( implode('-', $d4 ).' '.implode('-', $d11).' '.implode('-', $d12).' '.implode('-', $d13) );
$c->addComponent( $o );
$str = $c->createCalendar();
// echo $str."<br />\n";
$c->returnCalendar( FALSE, 'test.xml' );

/*
echo 'strtotime ("now")=',strtotime ("now"), "<br />\n";
echo 'strtotime ("10 September 2000")=', strtotime ("10 September 2000"), "<br />\n";
echo 'strtotime ("+1 day")=', strtotime ("+1 day"), "<br />\n";
echo 'strtotime ("+1 week")=', strtotime ("+1 week"), "<br />\n";
echo 'strtotime ("+1 week 2 days 4 hours 2 seconds")=', strtotime ("+1 week 2 days 4 hours 2 seconds"), "<br />\n";
echo 'strtotime ("next Thursday")=', strtotime ("next Thursday"), "<br />\n";
echo 'strtotime ("last Monday")=', strtotime ("last Monday"), "<br />\n";
 	
echo " LC_ALL=C TZ=UTC0 date "."<br />\n";
echo      "'Y-m-d H:i:s Z', strtotime('Fri Dec 15 19:48:05 UTC 2000')"."<br />\n";
echo date( 'Y-m-d H:i:s Z', strtotime('Fri Dec 15 19:48:05 UTC 2000'));   echo "<br />\n"; // test ###
echo     " 'Y-m-d H:i:s',   strtotime('Fri Dec 15 19:48:05')"."<br />\n";
echo date( 'Y-m-d H:i:s',   strtotime('Fri Dec 15 19:48:05'));            echo "<br />\n"; // test ###
echo     ' TZ=UTC0 date +"%Y-%m-%d %H:%M:%SZ" '."<br />\n";
echo     " 'Y-m-d H:i:s Z', strtotime('2000-12-15 19:48:05Z')"."<br />\n";
echo date( 'Y-m-d H:i:s Z', strtotime('2000-12-15 19:48:05Z'));           echo "<br />\n"; // test ###
echo " date --iso-8601=seconds  # a GNU extension"."<br />\n";
echo     " 'Y-m-d H:i:s Z', strtotime('2000-12-15T11:48:05-0800')"."<br />\n";
echo date( 'Y-m-d H:i:s Z', strtotime('2000-12-15T11:48:05-0800'));       echo "<br />\n"; // test ###
echo " date --rfc-822  # a GNU extension"."<br />\n";
echo     " 'Y-m-d H:i:s Z', strtotime('Fri, 15 Dec 2000 11:48:05 -0800')"."<br />\n";
echo date( 'Y-m-d H:i:s Z', strtotime('Fri, 15 Dec 2000 11:48:05 -0800'));echo "<br />\n"; // test ###
echo ' date +"%Y-%m-%d %H:%M:%S %z"  # %z is a GNU extension.'."<br />\n";
echo     " 'Y-m-d H:i:s Z', strtotime('2000-12-15 11:48:05 -0800')"."<br />\n";
echo date( 'Y-m-d H:i:s Z', strtotime('2000-12-15 11:48:05 -0800'));      echo "<br />\n"; // test ###
*/
?>