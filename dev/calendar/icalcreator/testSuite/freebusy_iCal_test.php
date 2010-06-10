<?php // completed_iCal_text.php

require_once '../iCalcreator.class.php';

$c = new vcalendar ();
$c->setFormat( "xcal" );
$c->unique_id = 'kigkonsult.se';

$e = new vfreebusy();

$fdate1 = array ( 'year' => 2001, 'month' => 1, 'day' => 1, 'hour' => 1, 'min' => 1, 'sec' => 1 ); 
$fdate2 = array ( 2002, 2, 2, 2, 2, 2, '-020202'  ); 
$fdate3 = array ( 2003, 3, 3, 3, 3, 3 ); 
$fdate4 = '4 April 2004 4:4:4'; 
$fdate5 = array ( 'year' => 2005, 'month' => 5, 'day' => 5, 'tz' => '+1200' );

$fdate6 = array ( 5 );
// alt.
$fdate7 = array ( 'week' => false, 'day' => 5, 'hour' => 5, 'min' => 5, 'sec' => 5 ); 
$fdate8 = array ( 0, 0, 6 );             // duration for 6 hours 
$timestamp1 = mktime ( 0, 0, 0, date('m'), date('d')+ 1, date('Y'));
$timestamp1 = array( 'timestamp' => $timestamp1 );
$timestamp3 = mktime ( 0, 0, 0, date('m'), date('d')+ 3, date('Y'));
$timestamp3 = array( 'timestamp' => $timestamp3 );

$e->setFreebusy ( 'FREE'
                , array( array( $fdate1, $fdate2 )
                       , array( $fdate3, $fdate6 )
                       , array( $fdate4, $fdate7 ))
                , array( 'xparamValue', 'yparamKey' => 'yparamValue' ));
$e->setFreebusy ( 'Buzy'
                , array( array( $fdate1, $fdate5 )
                       , array( $fdate3, $fdate6 )
                       , array( $fdate4, $fdate7 )
                       , array( $fdate1, $fdate8 )));
$e->setFreebusy ( 'Buzy'
                , array( array( $timestamp1, $fdate6 )
                       , array( $fdate3, $fdate6 )));
$e->setFreebusy ( 'Buzy'
                , array( array( $timestamp1, $timestamp3 )));
$c->addComponent( $e );


$str = $c->createCalendar();
// echo $str;
$c->returnCalendar( FALSE, 'test.xml' );

?>