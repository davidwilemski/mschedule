<?php // dtstamp_iCal_test.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );
$o = new vevent();
$o->setComment( '0a auto setting DEFAULT!!!' );
$c->addComponent( $o );

$o = new vevent();
$UTCoffset = date( 'Z'); // UTC offset in seconds
if( '-' == substr( $UTCoffset, 0, 1 )) {
  $UTCoffset = substr( $UTCoffset, 1 );
  $sign = '-';
}
else {
  $sign = '+';
}
$UTCoffsetHour = $UTCoffset / 3600;
$UTCoffsetMod  = $UTCoffset % 3600;
$UTCoffsetMin  = $UTCoffsetMod / 60;
$UTCoffsetSec  = $UTCoffsetMod % 60;
$UTCoffset     = sprintf( $sign."%02d%02d%02d", $UTCoffsetHour, $UTCoffsetMin, $UTCoffsetSec );   
$dtstamp = $c->validDate( array( 'year'  => date( 'Y' )
                               , 'month' => date( 'm' )
                               , 'day'   => date( 'd' )
                               , 'hour'  => date( 'H' )
                               , 'min'   => date( 'i' )
                               , 'sec'   => date( 's' )
                               , 'tz'    => $UTCoffset )
                        , TRUE);
$o->setDtstamp( $dtstamp );
$o->setComment( '0b : '.implode( '-', $dtstamp ));
$c->addComponent( $o );

$o = new vevent();
$o->setDtstamp( 1, 2, 3 );
$o->setComment( '1: 1, 2, 3' );
$c->addComponent( $o );

$o = new vtodo();
$o->setComment( "2: array( 'year' = 2006, 'month' => 10, 'day' => 10, 'tz' = '+0200' )" );
$date = array( 'year' => 2006, 'month' => 10, 'day' => 10, 'tz' => '+0200' );
$date = $c->validDate( $date, TRUE ); // localdate + offset to UTC
$o->setDtstamp( $date );
$c->addComponent( $o );

$o = new vevent();
$o->setDtstamp( 1, 2, 3, 4, 5, 6, array( 'xparam' ) );
$o->setComment( "3: 1, 2, 3, 4, 5, 6, array( 'xparam' )" );
$c->addComponent( $o );

$o = new vevent();
$o->setDtstamp( array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 6 ), array( 'xparam', 'xparaMKey' => 'xparamValue' ));
$o->setComment( "4: array( 'year' => 1, 'month' => 2, 'day' => 3, 'hour' => 4, 'min' => 5, 'sec' => 6 ), array( 'xparam', 'xparaMKey' => 'xparamValue' )" );
$c->addComponent( $o );

$o = new vevent();
$o->setDtstamp( array( 'year' => 1, 'month' => 2, 'day' => 3 ), array( 'xparam', 'xparaMKey' => 'xparamValue' ));
$o->setComment( "5: array( 'year' => 1, 'month' => 2, 'day' => 3), array( 'xparam', 'xparaMKey' => 'xparamValue' )" );
$c->addComponent( $o );

$o = new vevent();
$o->setDtstamp( '2001-02-03 04:05:06', array( 'xparam', 'xparaMKey' => 'xparamValue' ) );
$o->setComment( "6: 2001-02-03 04:05:06, array( 'xparam', 'xparaMKey' => 'xparamValue' )" );
$c->addComponent( $o );

$o = new vevent();
$o->setDtstamp( '2001-02-03' );
$o->setComment( '7: 2001-02-03' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtstamp( '20010203' );
$o->setComment( '8: 20010203' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtstamp( '20010203040506' );
$o->setComment( '9: 20010203040506' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtstamp( '3 Feb 2001' );
$o->setComment( '10: 3 Feb 2001' );
$c->addComponent( $o );

$o = new vevent();
$o->setDtstamp( '02/03/2001' );
$o->setComment( '11: 02/03/2001' );
$c->addComponent( $o );

$o = new vevent();
$timestamp = mktime ( date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
$o->setDtstamp( array( 'timestamp' => $timestamp ), array ( 'jestanes', 'xkey' => 'xvalue', 'xxx' => 'yyy' ) );
$o->setComment( '12: '.$timestamp.' =now tre xparams' );
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